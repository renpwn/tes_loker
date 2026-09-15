# PPOB PDAM

Aplikasi **PPOB pembayaran tagihan PDAM** yang terintegrasi dengan **Rajabiller API**.

Mendukung:

- PDAM Sidoarjo — `WASDA`
- PDAM Bondowoso — `WABONDO`

## Preview Hasil Program

### 1. Halaman Inquiry

User memasukkan atau memilih **ID Pelanggan**, kemudian aplikasi mengambil informasi tagihan dari provider.

```text
┌─────────────────────────────────────────────┐
│              PPOB PDAM                      │
├─────────────────────────────────────────────┤
│ Wilayah                                     │
│ [ WASDA ▼ ]                                 │
│                                             │
│ ID Pelanggan                                │
│ [ 01002676                              ]   │
│                                             │
│              [ CEK TAGIHAN ]                │
└─────────────────────────────────────────────┘
```

Hasil inquiry menampilkan informasi pelanggan dan tagihan:

```text
Nama          : Nama Pelanggan
ID Pelanggan  : 01002676
Alamat        : Alamat Pelanggan

Tagihan       : Rp50.000
Biaya Admin   : Rp2.500
Total Bayar   : Rp52.500

              [ BAYAR SEKARANG ]
```

### 2. Pembayaran Berhasil

Setelah pembayaran berhasil, transaksi disimpan ke database SQLite.

```text
✓ TRANSAKSI BERHASIL

ID Pelanggan : 01002676
Nama         : Nama Pelanggan
Tagihan      : Rp50.000
Admin        : Rp2.500
Total Bayar  : Rp52.500

Ref. Transaksi : PAY123456789

              [ CETAK STRUK ]
```

### 3. Riwayat Transaksi

Aplikasi menyediakan halaman history untuk melihat transaksi yang sudah berhasil.

```text
RIWAYAT TRANSAKSI

┌────────────┬──────────────┬────────────┬──────────┐
│ ID Pelanggan │ Nama       │ Total      │ Status   │
├────────────┼──────────────┼────────────┼──────────┤
│ 01002676   │ Pelanggan A  │ Rp52.500   │ BERHASIL │
│ 01002677   │ Pelanggan B  │ Rp75.000   │ BERHASIL │
└────────────┴──────────────┴────────────┴──────────┘
```

### 4. Cetak Struk

Struk dapat dibuka dari transaksi dan dicetak menggunakan fitur print browser.

---

## Fitur

- Cek/inquiry tagihan PDAM
- Pembayaran tagihan
- Dukungan WASDA dan WABONDO
- Penyimpanan transaksi ke SQLite
- Riwayat transaksi
- Cetak struk pembayaran
- Integrasi Rajabiller API

---

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | Node.js + Express |
| Frontend | HTML + Bootstrap 5 + Vanilla JavaScript |
| Database | SQLite |
| Payment Provider | Rajabiller API |

---

# Cara Menjalankan

## 1. Pastikan Node.js Terinstall

Minimal menggunakan:

```bash
Node.js 16+
npm
```

Cek versi:

```bash
node -v
npm -v
```

---

## 2. Masuk ke Folder Project

```bash
cd tes_loker
```

Sesuaikan `tes_loker` dengan nama folder project jika berbeda.

---

## 3. Install Dependency

Jalankan:

```bash
npm install
```

Tunggu sampai seluruh dependency selesai di-install.

---

## 4. Konfigurasi Rajabiller

Pastikan konfigurasi provider pada `server.js` sudah benar:

```javascript
RAJABILLER_URL
UID
PIN
```

> Jangan commit UID dan PIN asli ke repository publik. Untuk penggunaan production, sebaiknya gunakan environment variable.

---

## 5. Jalankan Server

```bash
npm start
```

Jika berhasil, server berjalan pada:

```text
http://localhost:3000
```

Kemudian buka browser:

```text
http://localhost:3000
```

---

# Alur Penggunaan

```text
Pilih Wilayah PDAM
        │
        ▼
Masukkan ID Pelanggan
        │
        ▼
     CEK TAGIHAN
        │
        ▼
   Inquiry Rajabiller
        │
        ▼
 Tampilkan Detail Tagihan
        │
        ▼
   BAYAR SEKARANG
        │
        ▼
    Payment Rajabiller
        │
        ▼
  Simpan Transaksi SQLite
        │
        ▼
   Riwayat Transaksi
        │
        ▼
       Cetak Struk
```

---

# Struktur Project

```text
tes_loker/
│
├── server.js
├── package.json
├── database.sqlite
├── README.md
│
└── public/
    ├── index.html
    ├── history.html
    └── struk.html
```

### File utama

| File | Fungsi |
|---|---|
| `server.js` | Backend Express dan integrasi Rajabiller |
| `public/index.html` | Halaman inquiry dan pembayaran |
| `public/history.html` | Riwayat transaksi |
| `public/struk.html` | Tampilan/cetak struk |
| `database.sqlite` | Database transaksi |

Database dan tabel transaksi dibuat otomatis ketika aplikasi dijalankan.

---

# API

## Inquiry

```http
POST /api/inquiry
```

Contoh request:

```json
{
  "kode_produk": "WASDA",
  "idpel": "01002676"
}
```

Digunakan untuk mengambil informasi tagihan pelanggan.

---

## Payment

```http
POST /api/payment
```

Digunakan untuk melakukan pembayaran berdasarkan hasil inquiry.

---

## History

```http
GET /api/history
```

Mengambil data riwayat transaksi dari database.

---

# Database

Aplikasi menggunakan SQLite dengan tabel:

```text
transactions
```

Data yang disimpan antara lain:

- ID pelanggan
- Nama pelanggan
- Alamat
- Nominal tagihan
- Biaya admin
- Total pembayaran
- Referensi transaksi
- Status transaksi
- Response provider
- Waktu transaksi

---

# Konfigurasi Port

Default:

```text
3000
```

Untuk menggunakan port lain:

```bash
PORT=4000 npm start
```

Kemudian buka:

```text
http://localhost:4000
```

---

# Troubleshooting

### `npm install` gagal

Pastikan Node.js dan npm sudah terinstall:

```bash
node -v
npm -v
```

Kemudian coba:

```bash
rm -rf node_modules
npm install
```

### Port 3000 sudah digunakan

Gunakan port lain:

```bash
PORT=4000 npm start
```

### Inquiry mendapatkan `Bad Request`

Jika aplikasi dapat dijalankan tetapi inquiry/payment ke Rajabiller gagal, periksa:

1. `UID` dan `PIN`
2. URL Rajabiller
3. Format payload
4. IP server yang digunakan
5. Status whitelist IP di Rajabiller

Pada pengujian sebelumnya, request ke Rajabiller mendapatkan `Bad Request` karena IP server belum di-whitelist oleh provider. Akibatnya, **UI, database, dan alur aplikasi tetap dapat diuji, tetapi inquiry/payment nyata belum dapat diuji end-to-end**.

---

# Status Pengujian

| Komponen | Status |
|---|---|
| Install dependency | ✅ |
| Server Express | ✅ |
| Frontend | ✅ |
| Database SQLite | ✅ |
| Inquiry UI | ✅ |
| History | ✅ |
| Cetak struk | ✅ |
| Integrasi Rajabiller | ⚠️ Menunggu whitelist IP |
| Payment production | ⚠️ Menunggu whitelist IP |

---

# Quick Start

Jika konfigurasi Rajabiller sudah siap:

```bash
git clone <repository>
cd tes_loker
npm install
npm start
```

Buka:

```text
http://localhost:3000
```

**Selesai.**

Aplikasi siap digunakan untuk melakukan:

```text
Inquiry → Payment → History → Cetak Struk
```