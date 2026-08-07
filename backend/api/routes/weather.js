import express from "express";
import axios from "axios";
const router = express.Router();

// GET /api/weather/:city
router.get("/:city", async (req, res) => {
  try {
    const city = req.params.city;
    const apiKey = process.env.OPENWEATHER_API_KEY;
    // console.log("OpenWeather Key:", process.env.OPENWEATHER_API_KEY);
    if (!apiKey) {
      return res.status(500).json({ message: "Missing OpenWeather API key" });
    }

    // Encode kota agar aman (contoh: “Jakarta Selatan” menjadi “Jakarta%20Selatan”)
    const encoded = encodeURIComponent(city);
    //&lang=id u/ opsi bahasa indo
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${encoded}&appid=${apiKey}&units=metric`;
    const response = await axios.get(url);

    const data = response.data;

    // Simplifikasi data yang ingin dikirim ke frontend
    const result = {
      city: data.name,
      country: data.sys?.country,
      temp: data.main?.temp,
      feels_like: data.main?.feels_like,
      temp_min: data.main?.temp_min,
      temp_max: data.main?.temp_max,
      humidity: data.main?.humidity,
      weather_main: data.weather[0]?.main,
      weather_description: data.weather[0]?.description,
      wind_speed: data.wind?.speed,
      wind_deg: data.wind?.deg,
      clouds: data.clouds?.all,
      precipitation: (data.rain?.['1h'] || data.snow?.['1h'] || 0) // mm per hour
    };

    res.json(result);
  } catch (err) {
    console.error("Weather error:", err.response?.data || err.message);
    // Kalau error karena kota tidak ditemukan → 404
    if (err.response && err.response.status === 404) {
      return res.status(404).json({ message: "City not found" });
    }
    res.status(500).json({ message: "Failed to fetch weather" });
  }
});

// module.exports = router;
export default router;