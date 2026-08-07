import express from "express";
import axios from "axios";
const router = express.Router();

router.get("/", async (req, res) => {
  try {
    const { origin, destination, weight } = req.query;
    const apiKey = process.env.RAJAONGKIR_API_KEY;

    if (!origin || !destination || !weight) {
      return res.status(400).json({ message: "Missing origin, destination, or weight" });
    }

    // Fungsi bantu cari ID kota
    const getCityId = async (cityName) => {
      const response = await axios.get(
        `https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=${encodeURIComponent(cityName)}&limit=1&offset=0`,
        { headers: { key: apiKey } }
      );
      const cities = response.data?.data;
      if (!cities || cities.length === 0) throw new Error(`City not found: ${cityName}`);
      return cities[0].id;
    };

    const originId = await getCityId(origin);
    const destinationId = await getCityId(destination);

    // Kurir yang digunakan
    const couriers = ["jne", "sicepat"];
    const results = [];

    for (const courier of couriers) {
      try {
        const response = await axios.post(
          "https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost",
          new URLSearchParams({
            origin: originId,
            destination: destinationId,
            weight,
            courier
          }),
          { headers: { "Content-Type": "application/x-www-form-urlencoded", key: apiKey } }
        );

        if (response.data?.data) {
        //   results.push({
        //     courier,
        //     costs: response.data
        //   });
          results.push( ...response.data.data );
        }
      } catch (err) {
        console.log({ courier, error: err.response?.data?.meta?.message || err.message });
      }
    }

    res.json({ origin, destination, weight, results });

  } catch (err) {
    console.error("Shipping error:", err.response?.data || err.message);
    res.status(500).json({ message: err.message || "Failed to fetch shipping cost" });
  }
});

export default router;