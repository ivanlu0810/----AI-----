require("dotenv").config();

const express = require("express");
const axios = require("axios");
const cors = require("cors");
const bodyParser = require("body-parser");
const mysql = require("mysql2/promise");

const db = mysql.createPool({
  host: process.env.DB_HOST,
  port: process.env.DB_PORT || 3306,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
});

const app = express();
app.use(cors());
app.use(bodyParser.json());
app.use(express.static("public"));

//const fs = require("fs");
//console.log("📂 .env 原始內容：");
//console.log(fs.readFileSync(".env", "utf8"));

// ✅ AI 聊天接口（保留原本）
app.post("/api/chat", async (req, res) => {
  const { messages } = req.body;

  // 1️⃣ 查詢 MySQL 資料
  let dbContent = "";
  try {
    const [rows] = await db.query("SELECT * FROM `inbody_records` LIMIT 3"); // 🔁 替換為你自己的表名
    dbContent = rows.map(row => JSON.stringify(row)).join("\n");
  } catch (error) {
    console.error("❌ 資料庫查詢失敗：", error.message);
    dbContent = "⚠️ 查詢資料庫失敗";
  }

  // 2️⃣ 把資料插入到 GPT 的 messages 前面
  messages.unshift({
    role: "system",
    content: `以下是使用者的健康資料庫內容，請結合這些資訊再回答接下來的問題：\n${dbContent}`,
  });

  // 3️⃣ 呼叫 GPT API（保留你的原始設定）
  try {
    const response = await axios.post(
      "https://api.openai.com/v1/chat/completions",
      {
        model: "gpt-4o-mini",
        messages: [
          {
            role: "system",
            content:
              "你是一位熱血又專業的健身小助手，擅長幫助不同背景的人擬定健身計畫。不論對方問什麼，都用健身的角度提供專業建議，並搭配一些激勵語句與生活小技巧。也請你之後的所有回答都使用繁體中文，並優先使用台灣常用用語。",
          },
          ...messages,
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

    res.json(response.data);
  } catch (error) {
    console.error("❌ 發生錯誤：", error.response?.data || error.message);
    res.status(500).json({ error: "OpenAI API 請求失敗" });
  }
});
const port = 3001;
app.listen(port, () => {
  console.log(`✅ Server running on http://localhost:${port}`);
});
