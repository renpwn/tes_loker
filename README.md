# 📨 SendPick Mini

**SendPick Mini** adalah aplikasi web berbasis **Fullstack (Vue 3 + Express.js)** yang dirancang untuk mengelola dan memantau pengiriman (delivery management), termasuk fitur **cek ongkos kirim** dan **informasi cuaca** untuk mendukung operasional pengiriman barang.  
Proyek ini menggunakan **frontend modern (Vite + TailwindCSS)** serta **backend sederhana berbasis Node.js + SQLite** untuk penyimpanan data lokal.

---

### 📂 Dokumen Terkait

- [📄 SendPick Technical Test (PDF)](./docs/SendPick%20Technical%20Test.pdf)
- [📄 Bagian 2 (Word)](./docs/Bagian%202.docx)


## 🚀 Fitur Utama

### 🔧 Backend (API Server)
- **Cek Ongkir** – Endpoint API untuk menghitung biaya kirim (`/api/ongkir`)
- **Informasi Cuaca** – Endpoint API untuk mendapatkan data cuaca (`/api/weather`)
- **Database SQLite** – Penyimpanan data ringan melalui file `sendpick.db`
- **Konfigurasi .env** – Mendukung variabel lingkungan untuk API key dan pengaturan server
- **Express.js** – Framework ringan dan cepat untuk REST API

### 💻 Frontend (Aplikasi Web)
- **Vue 3 + Vite** – Build cepat dan modular
- **TailwindCSS** – Styling modern berbasis utility
- **Routing** – Menggunakan Vue Router (`frontend/src/router/index.js`)
- **Halaman Utama**
  - `LoginPage.vue`
  - `Dashboard.vue`
  - `JobOrderDetail.vue`
- **Komponen Dinamis** – Komponen modular di `src/components`

---

## 🗂️ Struktur Proyek

```
sendpick-mini-main/
│
├── backend/                  # Backend server (Node.js + Express)
│   ├── api/routes/
│   │   ├── ongkir.js         # Endpoint cek ongkos kirim
│   │   └── weather.js        # Endpoint data cuaca
│   ├── db.js                 # Koneksi SQLite
│   ├── init-db.js            # Inisialisasi database
│   ├── server.js             # Entry point backend
│   ├── .env                  # File konfigurasi lingkungan
│   └── package.json          # Dependensi backend
│
├── frontend/                 # Frontend aplikasi (Vue 3)
│   ├── src/
│   │   ├── pages/            # Halaman utama aplikasi
│   │   ├── components/       # Komponen UI
│   │   ├── router/           # Konfigurasi routing
│   │   ├── App.vue           # Komponen utama
│   │   └── main.js           # Entry point frontend
│   ├── vite.config.js        # Konfigurasi Vite
│   ├── tailwind.config.js    # Konfigurasi TailwindCSS
│   └── package.json          # Dependensi frontend
│
├── package.json              # Dependensi root (frontend & backend)
├── LICENSE                   # Lisensi proyek
└── testQuest.txt             # Catatan / testing file
```

---

## ⚙️ Instalasi & Menjalankan Proyek

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/sendpick-mini.git
cd sendpick-mini-main
```

---

### 2️⃣ Setup Backend
Masuk ke direktori backend:
```bash
cd backend
```

Instal dependensi:
```bash
npm install
```

Buat file `.env` (jika belum ada) dan sesuaikan isinya:
```env
API_KEY_WEATHER=YOUR_API_KEY
RAJAONGKIR_API_KEY1=YOUR_API_KEY
```

Jalankan server backend:
```bash
npm start
```
Atau (jika tidak ada script `start`):
```bash
node server.js
```

Server backend akan berjalan di:  
👉 `http://localhost:3000`

---

### 3️⃣ Setup Frontend
Buka terminal baru, masuk ke folder frontend:
```bash
cd ../frontend
```

Instal dependensi:
```bash
npm install
```

Jalankan server development:
```bash
npm run dev
```

Frontend akan berjalan di:  
👉 `http://localhost:5173`

---

4️⃣ Setup Frontend & Backend (Jalankan Bersamaan)
Jika kamu ingin menjalankan frontend dan backend sekaligus tanpa membuka dua terminal, pastikan sudah terinstal npm-run-all.

📦 Instal dulu di root folder:
```bash
cd ..
npm install
```

Atau jika belum punya npm-run-all secara global:
```bash
npm install npm-run-all --save-dev
```

Lalu pastikan file package.json di root memiliki script seperti ini:
```bash
{
  "scripts": {    
    "start": "npm-run-all --parallel backend frontend",
    "dev": "npm-run-all --parallel backend frontend",
    "backend": "npm run dev --prefix backend",
    "frontend": "npm run dev --prefix frontend"
  }
}
```

Sekarang cukup jalankan satu perintah di root:
```bash
npm run dev

atau

npm start
```

💡 Maka:

👉 Backend akan berjalan di `http://localhost:3000`
👉 Frontend akan berjalan di `http://localhost:5173`


## 🔗 Integrasi Frontend & Backend

Frontend berkomunikasi dengan backend melalui endpoint API seperti:
- `GET /api/ongkir` → Mengambil data ongkos kirim  
- `GET /api/weather` → Mengambil informasi cuaca  

Pastikan alamat server backend diatur dengan benar (misalnya melalui `axios` atau `fetch` di frontend).

---

## 🧠 Teknologi yang Digunakan

| Layer | Teknologi |
|-------|------------|
| **Frontend** | Vue 3, Vite, TailwindCSS |
| **Backend** | Node.js, Express.js |
| **Database** | SQLite |
| **Tools** | npm, dotenv, axios |

---

## 🧩 Script Penting

### Backend (`backend/package.json`)
```json
{
  "scripts": {
    "start": "node server.js",
    "init-db": "node init-db.js"
  }
}
```

### Frontend (`frontend/package.json`)
```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview"
  }
}
```

### Root (`package.json`)
```json
{
  "scripts": {
    "start": "npm-run-all --parallel backend frontend",
    "dev": "npm-run-all --parallel backend frontend",
    "backend": "npm run dev --prefix backend",
    "frontend": "npm run dev --prefix frontend"
  }
}
```

---

## 🛠️ Pengembangan Lebih Lanjut

- Tambahkan autentikasi (JWT / OAuth)
- Koneksikan ke API ongkir nyata (RajaOngkir, dsb.)
- Tambah integrasi API cuaca (OpenWeatherMap)
- Gunakan SQLite hanya untuk prototipe; ganti ke PostgreSQL/MySQL di produksi
- Deploy ke Vercel (frontend) + Render/Heroku (backend)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah lisensi MIT.  
Lihat file [LICENSE](LICENSE) untuk informasi lebih lanjut.

---

## 👨‍💻 Kontributor

| Nama | Peran |
|------|-------|
| ARDY RENDRA | Pengembang Utama |

---

## 🌟 Catatan
> Proyek ini merupakan versi mini dari sistem **SendPick**, disederhanakan untuk kebutuhan demo, pembelajaran, atau pengembangan lokal.

---

### 🧾 Versi
- Backend: Node.js 18+
- Frontend: Vue 3 + Vite 5
- Database: SQLite 3

---
