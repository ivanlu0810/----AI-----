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

// ✅ 單純 AI 自主回覆
app.post("/api/chat", async (req, res) => {
  //console.log("📩 收到前端 req.body:", req.body);
  const userMessage = req.body.messages?.[0]?.content || "";

  try {
    const response = await axios.post(
      "https://api.openai.com/v1/chat/completions",
      {
        model: "gpt-4o-mini", // 你也可以改成其他模型
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
    console.error("❌ GPT 諮詢錯誤：", error.response?.data || error.message);
    return res.status(500).json({ error: "無法取得 AI 回覆" });
  }
});

app.listen(3001, () => {
  console.log("✅ Server running on http://localhost:3001");
});