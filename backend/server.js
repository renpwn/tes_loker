import express from "express";
import cors from "cors";
import db from "./db.js";

import dotenv from "dotenv";
dotenv.config();

// Import API
import weatherRoute from "./api/routes/weather.js";
import shippingRoute from "./api/routes/ongkir.js";

const app = express();
app.use(cors());
app.use(express.json());

app.get("/", (req, res) => res.send("Backend is running"));

// routes
app.get("/api/job-orders", (req, res) => {
  const rows = db.prepare("SELECT * FROM job_orders").all();
  res.json(rows);
});

app.get('/api/job-orders/:id', (req, res) => {
  const id = req.params.id
  const jobOrder = db.prepare('SELECT * FROM job_orders WHERE id = ?').get(id)
  if (!jobOrder) return res.status(404).json({ error: 'Job Order not found' })
  res.json(jobOrder)
})

app.get("/api/job-orders/:id/manifests", (req, res) => {
  const rows = db
    .prepare("SELECT * FROM manifests WHERE job_order_id = ?")
    .all(req.params.id);
  res.json(rows);
});

// *** Tambahkan ini: API ***
app.use("/api/weather", weatherRoute);
app.use("/api/shipping", shippingRoute);

const PORT = 3000;
app.listen(PORT, () => console.log(`✅ Backend running on port ${PORT}`));

// Add error handling middleware at the end
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ 
    success: false,
    message: 'Something went wrong!' 
  });
});

// Handle 404
app.use((req, res) => {
  res.status(404).json({ 
    success: false,
    message: 'Route not found' 
  });
});