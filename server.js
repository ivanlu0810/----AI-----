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
  host: process.env.DB_HOST,
  port: process.env.DB_PORT,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
});
let hasShownData = false; // 全域變數，記錄是否已經顯示過
// ✅ AI 回覆
app.post("/api/chat", async (req, res) => {
  const userMessage = req.body.messages?.[0]?.content || "";

  try {
    // 1️⃣ 查詢最新身體數據
    let userDataText = "（⚠️ 尚未查到身體數據，請先輸入健康數據）";
    try {
      const [rows] = await db.query(
        "SELECT * FROM `inbody_records` ORDER BY `Date` DESC LIMIT 1"
      );

      if (rows.length > 0) {
        const u = rows[0];
        userDataText = `
目前您的最新身體數據如下：
- 年齡：${u.age}
- 身高：${u["height-cm"]} cm
- 體重：${u["weight-kg"]} kg
- 骨骼肌量：${u.skeletal_muscle} kg
- 體脂肪重量：${u.body_fat} kg
- 體脂率：${u.fat_percentage} %
- 基礎代謝：${u.basal_metabolism} kcal
- BMI：${u.bmi}
- 測量日期：${u.Date}
`;
      }

      //console.log(userDataText);
    } catch (dbErr) {
      console.error("⚠️ 查詢資料庫失敗：", dbErr.message);
    }

    // 2️⃣ 呼叫 OpenAI，把使用者數據加進 Prompt
    const response = await axios.post(
      "https://api.openai.com/v1/chat/completions",
      {
        model: "gpt-4o-mini",
        messages: [
          {
            role: "system",
            content:
              "你是一位健身 AI 諮詢助理，當前端第一次提供健康數據時，你要先列出使用者的身體數據，再給予建議；但之後的對話就不用再重複列出數據，除非使用者要求更新，並使用繁體中文回答。",
          },
          {
            role: "user",
            content: !hasShownData
              ? `以下是使用者的身體數據：\n${userDataText}\n\n接下來是使用者的問題：${userMessage}`
              : `接下來是使用者的問題：${userMessage}`,
          },
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
    hasShownData = true;
    return res.json(response.data);
    
  } catch (error) {
    console.error("❌ GPT 諮詢錯誤：", error.response?.data || error.message);
    return res.status(500).json({ error: "無法取得 AI 回覆" });
  }
});

app.listen(3001, () => {
  console.log("✅ Server running on http://localhost:3001");
});
