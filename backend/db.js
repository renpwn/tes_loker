import Database from "better-sqlite3";

const db = new Database("sendpick.db");

// Buat tabel jika belum ada
db.exec(`
CREATE TABLE IF NOT EXISTS job_orders (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  customer_name TEXT,
  origin TEXT,
  destination TEXT,
  status TEXT,
  date TEXT
);

CREATE TABLE IF NOT EXISTS manifests (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  job_order_id INTEGER,
  item_name TEXT,
  quantity INTEGER,
  FOREIGN KEY (job_order_id) REFERENCES job_orders(id)
);
`);

// Tambah data dummy jika kosong
const count = db.prepare("SELECT COUNT(*) AS c FROM job_orders").get().c;
if (count === 0) {
  const insertJO = db.prepare(
    "INSERT INTO job_orders (customer_name, origin, destination, status, date) VALUES (?, ?, ?, ?, ?)"
  );
  const insertManifest = db.prepare(
    "INSERT INTO manifests (job_order_id, item_name, quantity) VALUES (?, ?, ?)"
  );

  for (let i = 1; i <= 3; i++) {
    insertJO.run(`Customer ${i}`, "Jakarta", "Surabaya", "On Process", "2025-10-17");
  }

  const allJO = db.prepare("SELECT id FROM job_orders").all();
  for (const jo of allJO) {
    insertManifest.run(jo.id, "Barang A", 5);
    insertManifest.run(jo.id, "Barang B", 2);
  }

  console.log("✅ Dummy data inserted.");
}

export default db;
