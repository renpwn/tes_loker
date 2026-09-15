# 🚰 PPOB PDAM — Rajabiller Integration

Aplikasi PPOB untuk **inquiry dan pembayaran tagihan PDAM** menggunakan Rajabiller API.

Mendukung dua produk:

- 🟢 **WASDA** — PDAM Sidoarjo
- 🔵 **WABONDO** — PDAM Bondowoso

---

## 🎯 Demo Flow

Aplikasi memiliki alur pembayaran end-to-end:

```text
┌───────────────┐
│  Pilih PDAM   │
└───────┬───────┘
        ↓
┌───────────────────┐
│ Input ID Pelanggan│
└────────┬──────────┘
         ↓
┌───────────────────┐
│   🔎 INQUIRY      │
│  Cek Tagihan      │
└────────┬──────────┘
         ↓
┌───────────────────┐
│ Detail Tagihan    │
│ Nama / Periode    │
│ Nominal / Admin   │
└────────┬──────────┘
         ↓
┌───────────────────┐
│  💳 PAYMENT       │
│ Bayar Sekarang    │
└────────┬──────────┘
         ↓
┌───────────────────┐
│ ✅ TRANSAKSI      │
│     SUKSES        │
└────────┬──────────┘
         ↓
┌───────────────────┐
│ 📋 Transaction    │
│     History       │
└────────┬──────────┘
         ↓
┌───────────────────┐
│ 🧾 Cetak Struk    │
└───────────────────┘
```

---

# 🚀 Quick Start

### Requirements

- Node.js v16+
- npm

### Install

```bash
npm install
```

### Run

```bash
npm start
```

Buka:

```text
http://localhost:3000
```

---

# ✅ Real Integration Test

Pengujian dilakukan menggunakan **response aktual dari Rajabiller**, bukan mock response.

## 🟢 Test 1 — WASDA / PDAM Sidoarjo

### Inquiry

**ID Pelanggan**

```text
01002676
```

**Result**

```text
Status       : 00
Keterangan   : SUKSES
PDAM         : PDAM SIDOARJO
Customer     : PERM. BUMI CITRA FAJ
Tagihan      : Rp294.500
Biaya Admin  : Rp10.806
Jumlah Bulan : 6
```

Rajabiller mengembalikan detail tagihan untuk **6 periode**.

### Payment

Payment dikirim menggunakan:

```text
method       : fastpay.pay
kode_produk  : WASDA
idpel        : 01002676
nominal      : 294500
```

**Result**

```text
Status       : 00
Keterangan   : SUKSES
Nominal      : Rp294.500
```

✅ **Payment berhasil diproses oleh Rajabiller.**

---

# 🔵 Test 2 — WABONDO / PDAM Bondowoso

### Inquiry

**ID Pelanggan**

```text
09000879
```

**Result**

```text
Status       : 00
Keterangan   : EXT: REQUEST SUKSES.
PDAM         : PDAM BONDOWOSO
Customer     : DWI YULIANA
Tagihan      : Rp94.130
Biaya Admin  : Rp7.500
Jumlah Bulan : 3
```

Rajabiller mengembalikan detail tagihan untuk **3 periode**.

### Payment

Payment dikirim menggunakan:

```text
method       : fastpay.pay
kode_produk  : WABONDO
idpel        : 09000879
nominal      : 94130
```

**Result**

```text
Status       : 00
Keterangan   : EXT: PAYMENT SUKSES.
Nominal      : Rp94.130
```

✅ **Payment berhasil diproses oleh Rajabiller.**

---

# 📊 Integration Test Summary

| Product | Provider | Inquiry | Payment |
|---|---|---:|---:|
| WASDA | Rajabiller | ✅ Success | ✅ Success |
| WABONDO | Rajabiller | ✅ Success | ✅ Success |

### Yang berhasil dibuktikan

- ✅ Request dari aplikasi ke Rajabiller
- ✅ Inquiry real data
- ✅ Parsing response Rajabiller
- ✅ Menampilkan informasi pelanggan
- ✅ Menampilkan detail tagihan
- ✅ Payment real request
- ✅ Payment response berhasil
- ✅ Reference transaction
- ✅ Penyimpanan transaksi
- ✅ History transaksi
- ✅ Generate / print receipt

---

# 🔄 API Flow

### Inquiry

```text
Frontend
   │
   │ POST /api/inquiry
   ↓
Backend
   │
   │ fastpay.inq
   ↓
Rajabiller
   │
   │ status: 00
   ↓
Backend
   │
   ↓
Frontend
   │
   ↓
Detail Tagihan
```

### Payment

```text
Frontend
   │
   │ POST /api/payment
   ↓
Backend
   │
   │ fastpay.pay
   ↓
Rajabiller
   │
   │ status: 00
   ↓
Backend
   │
   ├── Save Transaction
   │
   ↓
Frontend
   │
   ↓
Payment Success
   │
   ↓
History → Receipt
```

---

# 🧾 Contoh Data Response

Berikut contoh struktur response yang diterima dari Rajabiller:

```json
{
  "kodeproduk": "WASDA",
  "idpelanggan1": "01002676",
  "nominal": "294500",
  "biayaadmin": "10806",
  "ref1": "REF...",
  "ref2": "2818949083",
  "status": "00",
  "keterangan": "SUKSES",
  "billquantity": "6",
  "customername": "PERM. BUMI CITRA FAJ",
  "customeraddress": "SEKAWAN SEJUK C.16A",
  "pdamname": "PDAM SIDOARJO"
}
```

> Credential seperti `uid` dan `pin` tidak ditampilkan pada dokumentasi publik.

---

# 📋 Fitur Aplikasi

### 🔎 Inquiry

- Pilih produk PDAM
- Input ID Pelanggan
- Request ke Rajabiller
- Menampilkan informasi pelanggan
- Menampilkan jumlah periode tagihan
- Menampilkan nominal tagihan
- Menampilkan biaya administrasi

### 💳 Payment

- Menggunakan data hasil inquiry
- Membentuk payload `fastpay.pay`
- Mengirim request ke Rajabiller
- Memproses status transaksi
- Menyimpan transaksi berhasil

### 📚 History

Menampilkan transaksi yang sudah diproses:

- Produk PDAM
- ID Pelanggan
- Nama pelanggan
- Nominal
- Biaya admin
- Total pembayaran
- Reference
- Status
- Waktu transaksi

### 🧾 Receipt

- Detail transaksi
- Detail pelanggan
- Detail tagihan
- Status pembayaran
- Reference transaksi
- Print-friendly layout

---

# 🧪 Testing Notes

Testing dilakukan menggunakan **real integration environment Rajabiller**.

Pada tahap awal testing terdapat kendala `Bad Request` yang berkaitan dengan whitelist IP. Setelah akses dapat digunakan, dilakukan pengujian ulang dan diperoleh hasil:

```text
WASDA
Inquiry  → SUCCESS
Payment  → SUCCESS

WABONDO
Inquiry  → SUCCESS
Payment  → SUCCESS
```

Dengan demikian, flow utama aplikasi telah berhasil diuji sampai tahap **payment**.

---

# ⚙️ Technology Stack

| Component | Technology |
|---|---|
| Backend | Node.js + Express |
| Frontend | HTML + Bootstrap 5 + Vanilla JavaScript |
| Database | SQLite |
| Payment Provider | Rajabiller API |

---

# 📁 Project Structure

```text
tes_loker/
│
├── server.js
├── package.json
├── database.sqlite
│
├── public/
│   ├── index.html
│   ├── history.html
│   └── struk.html
│
└── README.md
```

---

# 🔌 API Endpoint

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/inquiry` | Inquiry tagihan |
| `POST` | `/api/payment` | Pembayaran |
| `GET` | `/api/history` | Riwayat transaksi |

---

# 👨‍💻 Project

**PPOB PDAM — Technical Test**

Developer: **Ardy Rendra**

Fokus implementasi:

> **Inquiry → Payment → History → Receipt**

---

## 🎬 Demo Checklist

Untuk reviewer, aplikasi dapat diuji dengan flow berikut:

```text
1. npm install
2. npm start
3. Open http://localhost:3000
4. Pilih WASDA / WABONDO
5. Masukkan ID Pelanggan
6. Klik "Cek Tagihan"
7. Review detail tagihan
8. Klik "Bayar"
9. Cek status transaksi
10. Buka History
11. Buka & Print Receipt
```

**Expected Result:**

```text
Inquiry  → ✅
Payment  → ✅
History  → ✅
Receipt  → ✅
```