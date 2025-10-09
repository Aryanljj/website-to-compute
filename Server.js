import express from "express";
import cors from "cors";
import bodyParser from "body-parser";
import fetch from "node-fetch";

const app = express();
app.use(cors());
app.use(bodyParser.json());

const OPENAI_KEY = "YOUR_OPENAI_API_KEY"; // 👈 अपनी OpenAI API key डालो

app.post("/api/chat", async (req, res) => {
  try {
    const userMessage = req.body.message || "Hello";

    const response = await fetch("https://api.openai.com/v1/chat/completions", {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${OPENAI_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        model: "gpt-3.5-turbo",
        messages: [
          { role: "system", content: "You are Pinky, a cute, flirty AI girl who talks in Hinglish with emojis." },
          { role: "user", content: userMessage },
        ],
        temperature: 0.9,
      }),
    });

    const data = await response.json();
    const reply = data.choices?.[0]?.message?.content || "Oops 😅 mujhe samajh nahi aaya!";
    res.json({ reply });
  } catch (err) {
    console.error(err);
    res.status(500).json({ reply: "Server error 😔" });
  }
});

app.listen(5000, () => console.log("✅ Pinky AI Server running on http://localhost:5000"));
