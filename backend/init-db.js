import Database from 'better-sqlite3'

// Buat / buka database
const db = new Database('sendpick.db')


// ---------------------------
// 1️⃣ Hapus tabel lama (jika ada)
// ---------------------------
db.prepare(`DROP TABLE IF EXISTS manifests`).run()
db.prepare(`DROP TABLE IF EXISTS job_orders`).run()


// ---------------------------
// 2️⃣ Buat tabel job_orders
// ---------------------------
db.prepare(`
CREATE TABLE IF NOT EXISTS job_orders (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  customer_name TEXT NOT NULL,
  origin TEXT NOT NULL,
  destination TEXT NOT NULL,
  status TEXT NOT NULL,
  date TEXT NOT NULL DEFAULT (DATETIME('now'))
)
`).run()

// ---------------------------
// 3️⃣ Buat tabel manifests
// ---------------------------
db.prepare(`
CREATE TABLE IF NOT EXISTS manifests (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  job_order_id INTEGER NOT NULL,
  item_name TEXT NOT NULL,
  quantity INTEGER NOT NULL,
  FOREIGN KEY (job_order_id) REFERENCES job_orders(id)
)
`).run()

// ---------------------------
// 4️⃣ Isi data dummy ke job_orders
// ---------------------------
// ---------------------------
// 4️⃣ Isi data dummy job_orders (lebih realistis)
// ---------------------------
db.prepare(`
INSERT INTO job_orders (customer_name, origin, destination, status, date) VALUES
('PT Garuda Cargo', 'Jakarta', 'Surabaya', 'On Process', '2025-10-17 08:15:23'),
('CV Maju Bersama', 'Bandung', 'Semarang', 'On Process', '2025-10-17 09:32:11'),
('PT Nusantara Logistic', 'Surabaya', 'Denpasar', 'On Delivery', '2025-10-17 10:45:56'),
('PT ABC', 'Jakarta', 'Bandung', 'Pending', '2025-10-17 12:21:34'),
('PT XYZ', 'Surabaya', 'Bali', 'Delivered', '2025-10-17 13:58:42')
`).run()

// ---------------------------
// 5️⃣ Isi data dummy manifests (realistic items)
// ---------------------------
db.prepare(`
INSERT INTO manifests (job_order_id, item_name, quantity) VALUES
(1, 'Spare Part Mesin Diesel', 10),
(1, 'Kardus Oli 1L', 24),
(2, 'Pakaian Seragam Karyawan', 50),
(2, 'Helm Safety', 20),
(3, 'Peralatan Elektronik', 8),
(3, 'Kabel Roll 25m', 12),
(4, 'Dokumen Kontrak', 3),
(4, 'Karton Produk Sample', 6),
(5, 'Bahan Kimia Cair (non-hazard)', 4),
(5, 'Botol Plastik 1L', 200)
`).run()

// ---------------------------
// 6️⃣ Tampilkan hasil untuk verifikasi
// ---------------------------
const jobOrders = db.prepare(`SELECT * FROM job_orders`).all()
const manifests = db.prepare(`SELECT * FROM manifests`).all()

console.log('✅ Database sendpick.db berhasil dibuat ulang tanpa nilai NULL.')
console.log('📦 Data Job Orders:')
console.table(jobOrders)
console.log('📦 Data Manifests:')
console.table(manifests)