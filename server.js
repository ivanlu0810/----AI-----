// server.js
require("dotenv").config();
const express = require("express");
const cors = require("cors");
const bodyParser = require("body-parser");
const mysql = require("mysql2/promise");
const axios = require("axios");

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createPool({
  host: "localhost",
  user: "root",
  password: "123456",
  database: "health",
});

app.post("/api/chat", async (req, res) => {
  const userMessage = req.body.messages?.[0]?.content || "";
  let reply = "";

  // 第一階段引導：有來過 or 沒來過
  if (userMessage.includes("有") || userMessage.includes("沒有")) {
    reply = `我們這裡有提供多項功能，請試著輸入：\n\n-「我要輸入健康數據」\n-「請提供訓練建議」`;
    return res.json({ choices: [{ message: { content: reply } }] });
  }

  // 第二階段：輸入健康數據
  else if (userMessage.includes("健康數據")) {
    reply = `__trigger_modal_addData__`; // 前端會判斷這句話並觸發 Modal
    return res.json({ choices: [{ message: { content: reply } }] });
  }

  // 第三階段：訓練建議
  else if (userMessage.includes("訓練建議")) {
    reply = `您想要的是增肌還是減脂呢？`;
    return res.json({ choices: [{ message: { content: reply } }] });
  }

  // 第四階段：增肌/減脂後的分化選擇
  else if (userMessage.includes("增肌") || userMessage.includes("減脂")) {
    const goal = userMessage.includes("增肌") ? "增肌" : "減脂";
    const tip = goal === "減脂" ? "建議可搭配有氧運動以增加熱量缺口。\n" : "";
    reply = `${tip}您有想要使用特別的訓練方式嗎？像是三分化訓練或是五分化訓練？`;
    return res.json({ choices: [{ message: { content: reply } }] });
  }

  // 第五階段：三分化或五分化進階訓練建議（由 AI 自動判斷）
  else if (userMessage.includes("三分化") || userMessage.includes("五分化")) {
    const userSplit = userMessage.includes("三分化") ? "三分化" : "五分化";

    let dbContent = "";
    try {
      const [rows] = await db.query("SELECT * FROM `inbody_records` ORDER BY id DESC LIMIT 1");
      dbContent = rows.length ? JSON.stringify(rows[0]) : "⚠️ 查無資料";
    } catch (err) {
      dbContent = "⚠️ 查詢資料庫失敗";
    }

    const messages = [
      {
        role: "system",
        content: `你是一位熱血且專業的健身教練，擅長根據使用者的健康數據與訓練目標，提供個人化的訓練建議。請使用繁體中文，建議內容可以包含訓練安排、訓練日分配、注意事項，以及針對健康數據的簡單分析與提醒。例如：體脂率高就提醒有氧，基礎代謝率低可建議飲食調整。`,
      },
      {
        role: "user",
        content: `使用者的健康數據如下：\n${dbContent}\n他想採用${userSplit}訓練方式，請為他設計建議。`,
      },
    ];

    try {
      const response = await axios.post(
        "https://api.openai.com/v1/chat/completions",
        {
          model: "gpt-4o",
          messages,
        },
        {
          headers: {
            Authorization: `Bearer ${process.env.OPENAI_API_KEY}`,
            "Content-Type": "application/json",
            "OpenAI-Project": process.env.OPENAI_PROJECT_ID,
          },
        }
      );

      return res.json(response.data);
    } catch (error) {
      console.error("❌ GPT 請求失敗", error.message);
      return res.status(500).json({ error: "OpenAI GPT 回覆錯誤" });
    }
  }

  // 其他：傳給 GPT 決定
  try {
    const response = await axios.post(
      "https://api.openai.com/v1/chat/completions",
      {
        model: "gpt-4o",
        messages: [
          {
            role: "system",
            content:
              "你是一位健身 AI 諮詢助理，根據使用者輸入與訓練目的給予友善、具體的建議，並使用繁體中文回答。",
          },
          { role: "user", content: userMessage },
        ],
      },
      {
        headers: {
          Authorization: `Bearer ${process.env.OPENAI_API_KEY}`,
          "Content-Type": "application/json",
          "OpenAI-Project": process.env.OPENAI_PROJECT_ID,
        },
      }
    );

    return res.json(response.data);
  } catch (error) {
    console.error("❌ GPT 諮詢錯誤：", error.message);
    return res.status(500).json({ error: "無法取得 AI 回覆" });
  }
});

app.listen(3001, () => {
  console.log("✅ Server running on http://localhost:3001");
});
