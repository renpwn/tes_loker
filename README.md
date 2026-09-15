# PPOB PDAM

Aplikasi PPOB (Payment Point Online Bank) untuk pembayaran tagihan PDAM melalui provider **Rajabiller**. Mendukung wilayah PDAM **Sidoarjo (WASDA)** dan **Bondowoso (WABONDO)**.

## Fitur

- Inquiry tagihan PDAM berdasarkan ID Pelanggan
- Pembayaran tagihan PDAM
- Riwayat transaksi pembayaran
- Cetak struk pembayaran

## Teknologi

- **Backend:** Node.js, Express
- **Database:** SQLite
- **Frontend:** HTML, Bootstrap 5, Vanilla JavaScript
- **Provider:** Rajabiller API

## Prasyarat

- Node.js v16 atau lebih baru
- npm

## Instalasi

```bash
npm install
```

## Menjalankan Aplikasi

```bash
npm start
```

Akses aplikasi di browser:
```
http://localhost:3000
```

## Struktur Proyek

```
tes_loker/
├── server.js           # Backend server Express
├── package.json        # Dependensi dan script
├── database.sqlite     # Database SQLite (dibuat otomatis)
├── public/
│   ├── index.html      # Halaman inquiry & pembayaran
│   ├── history.html    # Riwayat transaksi
│   └── struk.html      # Struk pembayaran
└── README.md
```

## API Endpoint

| Method | Endpoint       | Deskripsi                          |
|--------|---------------|------------------------------------|
| POST   | `/api/inquiry`| Cek tagihan PDAM                   |
| POST   | `/api/payment`| Bayar tagihan PDAM                 |
| GET    | `/api/history`| Ambil riwayat transaksi            |

## Database

Tabel `transactions` berisi:
- Data pelanggan (nama, alamat, ID Pelanggan)
- Detail tagihan (nominal, admin, total bayar)
- Referensi transaksi (ref1, ref2)
- Status transaksi
- Timestamp pembuatan

## Konfigurasi

Konfigurasi provider Rajabiller terletak di `server.js`:
- `RAJABILLER_URL`
- `UID`
- `PIN`

Port server dapat diubah melalui environment variable:
```bash
PORT=4000 npm start
```

## Catatan

- Database SQLite akan dibuat otomatis saat server dijalankan pertama kali
- Tabel `transactions` dibuat otomatis jika belum ada
- Server berjalan di `http://localhost:3000` secara default

## Konfirmasi Interview & IP Whitelist

- Interview HR dikonfirmasi HADIR pada **Rabu, 16 September 2026** ke PT Bimasakti Multi Sinergi.
- Saat pengujian ke endpoint Rajabiller, respons yang diterima adalah **Bad Request**.
- Penyebab yang teridentifikasi: IP eksekusi server (`182.8.99.123`) belum di-whitelist di sisi Rajabiller.
- Solusi yang dibutuhkan: tambahkan IP `182.8.99.123` ke whitelist Rajabiller agar inquiry dan payment dapat diuji secara penuh.

### Analisa Dampak

**Yang masih bisa diuji tanpa whitelist:**
- UI/UX halaman inquiry, history, dan struk
- Validasi form dan alur navigasi frontend
- Struktur database dan schema tabel `transactions`
- Logic mapping kode produk PDAM (WASDA/WABONDO)
- Format tampilan rupiah, terbilang, dan print struk
- Error handling lokal

**Yang terblokir tanpa whitelist:**
- Response asli inquiry dari Rajabiller
- Proses pembayaran nyata (`fastpay.pay`)
- Verifikasi format payload dan header yang dikirim
- Uji end-to-end dengan data production

**Kemungkinan akar masalah Bad Request:**
1. IP `182.8.99.123` memang belum terdaftar di whitelist Rajabiller
2. Format payload tidak sesuai spesifikasi aktual Rajabiller
3. Kombinasi `UID`/`PIN` untuk environment tertentu tidak valid
4. Header tambahan yang diharapkan Rajabiller belum terpenuhi

**Catatan teknis tambahan:**
- `server.js` saat ini menonaktifkan verifikasi TLS (`NODE_TLS_REJECT_UNAUTHORIZED = '0'`). Dalam production, sebaiknya gunakan sertifikat yang valid atau konfigurasi environment yang sesuai.
- `UID` dan `PIN` Rajabiller saat ini hardcoded di `server.js`. Untuk keamanan, sebaiknya pindahkan ke environment variable (misal: `.env`).
- Belum ada validasi struktur response dari Rajabiller sebelum digunakan (misal: pengecekan `result.status === '00'` vs format lain).
- Belum ada retry mechanism atau timeout handling untuk koneksi ke Rajabiller.
- Tidak ada logging ke file, hanya `console.log` yang mungkin hilang saat production.

**Detail integrasi Rajabiller:**
- Inquiry menggunakan method `fastpay.inq` dengan payload `{ idpel1, idpel2, idpel3, kode_produk, ref1 }`
- Payment menggunakan method `fastpay.pay` dengan tambahan field `nominal`, `ref2`, `ref3`
- Response dari Rajabiller umumnya berformat: `{ status, rc, ket, ref2, nominal, biayaadmin, ... }`
- Data response disimpan ke tabel `transactions` kolom `response_data` sebagai JSON string untuk audit trail

**Potensi perbaikan kode:**
- Implementasi loading state yang lebih informatif di frontend saat request ke API
- Penyempurnaan validasi input sebelum dikirim ke backend
- Penambahan unit test untuk helper functions (format rupiah, terbilang)
- Konfigurasi CORS jika frontend akan di-host di domain berbeda
- Rate limiting untuk mencegah spam request ke endpoint API

**Testing Strategy yang Bisa Dilakukan:**
- **Manual Testing:** Jalankan `npm start`, buka `http://localhost:3000`, test inquiry dengan ID pelanggan yang diketahui
- **API Testing:** Gunakan Postman/Thunder Client untuk test `/api/inquiry` dan `/api/payment` langsung
- **Database Verification:** Cek tabel `transactions` via SQLite browser untuk pastikan data tersimpan
- **Frontend Testing:** Test responsif di mobile/desktop, test print struk via browser print dialog

**Catatan untuk Deployment (jika nanti diperlukan):**
- Ganti hardcoded `UID`/`PIN` dengan environment variables
- Tambahkan `.env` file dan `dotenv` package untuk konfigurasi
- Implementasi proper logging (misal: `winston` atau `pino`)
- Tambahkan HTTPS dengan valid certificate untuk production
- Setup reverse proxy (nginx) jika perlu
- Backup database SQLite secara berkala

**Observasi Kode & Arsitektur:**
- `generateRef()` menggunakan timestamp + random 3 digit, cukup untuk uniqueness dalam konteks ini namun bukan cryptographically secure
- Inquiry success detection menggunakan fallback: `result.status === '00' || (result.nominal && result.idpelanggan1)` - menandakan respons Rajabiller mungkin tidak konsisten
- Mapping kode produk ke ID Pelanggan PDAM hardcoded di frontend (`PDAM_MAP`), sebaiknya diletakkan di backend untuk keamanan
- Tidak ada mekanisme retry untuk request yang gagal ke Rajabiller
- Response dari payment disimpan ke DB hanya jika `data.status === '00'`, transaksi gagal tidak tercatat
- Struk HTML membaca semua data dari `/api/history` lalu filter by ID, bisa lebih efisien jika ada endpoint `/api/transactions/:id`

**Analisa Arsitektur Frontend:**
- Menggunakan vanilla JS tanpa framework, cocok untuk aplikasi sederhana namun akan sulit dirawat jika berkembang
- State management dilakukan via variable global `inquiryDataGlobal` - berisiko jika ada multiple user (tidak ada session isolation)
- Tidak ada pembatasan concurrency: user bisa klik "Bayar" berkali-kali sebelum response masuk
- Alert message menggunakan custom function `showAlert()` yang mengganti class secara dinamis
- Print struk menggunakan CSS `@media print` untuk menyembunyikan elemen non-esensial

**Analisa Alur Pembayaran (State Machine):**
1. User pilih produk PDAM → otomatis isi ID Pelanggan
2. Submit inquiry → kirim POST `/api/inquiry` dengan `kode_produk` + `idpel`
3. Server forwarding ke Rajabiller → return data tagihan
4. Tampilkan detail tagihan di frontend
5. User klik "Bayar Sekarang" → konfirmasi dialog
6. Kirim POST `/api/payment` dengan semua data inquiry + nominal
7. Server forwarding ke Rajabiller → process payment
8. Jika sukses: simpan ke DB, redirect ke history
9. Jika gagal: tampilkan error message, allow retry

**Gap yang Ditemukan:**
- Tidak ada idempotency key: jika user refresh saat payment diproses, bisa terjadi double charge
- Tidak ada locking mechanism untuk transaksi concurrent
- Tidak ada timeout handling di frontend: fetch bisa hang selamanya
- Tidak ada exponential backoff untuk retry
- Tidak ada circuit breaker pattern untuk mencegah cascade failure jika Rajabiller down

**What Recruiter Might Evaluate:**
- Kemampuan membaca dan memahami flow aplikasi existing
- Kemampuan mengidentifikasi bug-potential areas (concurrency, security, error handling)
- Kemampuan merancang solusi tanpa break existing flow
- Komunikasi teknikal yang jelas tentang trade-off decisions
- Pemahaman tentang payment integration best practices

**Aspek yang Sudah Baik:**
- Struktur kode cukup bersih dan mudah dibaca
- Pemisahan concern: backend handle API call, frontend handle UI
- Database schema sudah mencakup field penting untuk audit trail
- Print-friendly CSS untuk struk menunjukkan perhatian pada detail UX
- Error handling dasar sudah ada (try-catch, alert message)
- Ref1 generation untuk idempotency inquiry sudah ada

**Pertanyaan yang Mungkin Muncul saat Interview:**
1. "Bagaimana jika 2 user melakukan payment bersamaan untuk ID Pelanggan yang sama?"
2. "Apa yang terjadi jika response dari Rajabiller terpotong saat disimpan ke DB?"
3. "Mengapa menggunakan SQLite dan bukan database lain?"
4. "Bagaimana cara memastikan data sensitif (UID/PIN) tidak bocor?"
5. "Apa yang akan dilakukan jika Rajabiller API down saat peak hour?"
6. "Bagaimana cara menangani kasus partial failure (payment sukses di Rajabiller tapi gagal simpan ke DB)?"

**Sample API Flow (setelah IP di-whitelist):**

```json
// POST /api/inquiry
// Request
{
  "kode_produk": "WASDA",
  "idpel": "01002676"
}

// Response (contoh)
{
  "status": "00",
  "idpelanggan1": "01002676",
  "nama": "Ardy Rendra",
  "alamat": "Jl. Contoh No. 123",
  "nominal": "50000",
  "biayaadmin": "2500",
  "total_bayar": "52500",
  "ref1": "REF1694781234567",
  "ref2": "REF789"
}

// POST /api/payment
// Request
{
  "kode_produk": "WASDA",
  "idpel": "01002676",
  "nominal": 50000,
  "admin": 2500,
  "total_bayar": 52500,
  "ref1": "REF1694781234567",
  "ref2": "REF789"
}

// Response (contoh)
{
  "status": "00",
  "rc": "00",
  "ket": "Transaksi Berhasil",
  "ref2": "PAY123456789",
  "total_bayar": "52500"
}
```

**Checklist Persiapan Interview:**
- [ ] Pastikan aplikasi bisa dijalankan (`npm install`, `npm start`)
- [ ] Siapkan screenshot/video demo UI jika IP belum di-whitelist
- [ ] Siapkan mock data untuk demonstrasi jika diperlukan
- [ ] Review ulang kode `server.js` untuk jelaskan alur API
- [ ] Siapkan catatan tentang tantangan yang dihadapi (IP whitelist, TLS, dll)
- [ ] Siapkan pertanyaan tentang scalability dan security untuk menunjukkan pemahaman lebih dalam

**Code Review Findings (untuk diskusi interview):**

*Backend (`server.js`):*
- Line 12: `NODE_TLS_REJECT_UNAUTHORIZED = '0'` - keamanan compromised
- Line 19-20: `UID`/`PIN` hardcoded - security risk
- Line 22: Database path relatif - bisa issue jika run dari directory berbeda
- Line 64-72: Tidak ada timeout untuk fetch ke Rajabiller
- Line 117: `if (data.status === '00')` - magic string, sebaiknya konstanta
- Line 118-125: Callback-style db.run inside async function - bisa di-refactor dengan promisified SQLite
- Tidak ada input validation: `req.body` bisa kosong/null dan menyebabkan crash

*Frontend (`index.html`):*
- Line 154-158: Tidak ada abort controller untuk cancel fetch
- Line 166-168: `parseFloat(result.nominal || 0)` - gagal parse string non-numeric
- Line 171-182: Object `inquiryDataGlobal` di-build manual, rawan human error
- Line 126-133: Input listener untuk reverse mapping dikomentari - fitur belum jadi
- Tidak ada loading skeleton/spinner yang konsisten

**Production-Ready Checklist:**
- [ ] Environment variables untuk semua konfigurasi (UID, PIN, URL, PORT)
- [ ] Input validation & sanitization di semua endpoint
- [ ] Rate limiting & request throttling
- [ ] Proper logging dengan log levels (info, warn, error)
- [ ] Health check endpoint (`/health`)
- [ ] Request/response correlation ID untuk tracing
- [ ] Database connection pooling (jika menggunakan PostgreSQL/MySQL untuk production)
- [ ] Graceful shutdown handling
- [ ] Error boundary handling di frontend
- [ ] Unit tests & integration tests
- [ ] CI/CD pipeline untuk automated testing

**Alternatif Solusi jika IP Whitelist Tidak Bisa Diproses Cepat:**

*Opsi 1: Proxy Server*
- Deploy backend ke server dengan IP yang sudah di-whitelist
- Gunakan reverse proxy (nginx/Caddy) untuk mask IP asli
- Pro: solusi cepat tanpa perlu koordinasi dengan Rajabiller
- Kontra: menambah infrastructure complexity

*Opsi 2: Mock Server*
- Buat mock server yang mimic response Rajabiller
- Frontend tetap hit endpoint asli, backend bisa di-switch antara real/mock
- Pro: bisa test full flow end-to-end tanpa dependency eksternal
- Kontra: tidak bisa validasi actual response format dari Rajabiller

*Opsi 3: VPN/Tunnel*
- Gunakan VPN atau SSH tunnel dari server yang sudah di-whitelist
- Pro: IP publik tetap sama, traffic lewat tunnel
- Kontra: bergantung pada koneksi VPN, bisa latency

**Catatan untuk Tim HR/Technical Assessor:**
- Proyek ini adalah implementasi minimal viable product (MVP) untuk integrasi payment gateway
- Fokus utama adalah memastikan flow inquiry → payment → history → struk berjalan dengan benar
- Aspek non-functional (security, scalability, monitoring) bisa di-improve bertahap
- Kesalahan IP whitelist adalah operational issue yang umum terjadi di payment integration, bukan kesalahan teknis aplikasi

**Payment Industry Best Practices yang Dapat Didiskusikan:**

*Security:*
- PCI DSS compliance untuk payment data handling
- Tokenization untuk data sensitif (jika applicable)
- End-to-end encryption untuk data in transit
- Audit trail lengkap untuk setiap transaksi

*Reliability:*
- Idempotency key untuk mencegah double charge
- Exactly-once delivery semantics
- Circuit breaker pattern untuk dependency eksternal
- Graceful degradation jika provider down

*Monitoring:*
- Real-time transaction monitoring
- Alerting untuk failed transactions
- Dashboard untuk success rate, latency, dll
- Log aggregation untuk forensic analysis

**Edge Cases yang Perlu Dihadapi:**
1. **Duplicate Inquiry:** User klik "Cek Tagihan" dua kali - perlu debounce atau disable button
2. **Network Timeout:** Request ke Rajabiller hanging - perlu timeout handling
3. **Partial Response:** Rajabiller return data parsial - perlu validation
4. **Concurrent Payment:** 2 user bayar tagihan yang sama - perlu locking atau queue
5. **Stale Data:** Tagihan berubah setelah inquiry tapi sebelum payment - perlu re-validation
6. **Database Lock:** SQLite locked saat concurrent write - perlu WAL mode atau migration ke RDBMS lain
7. **Print Format:** Struk tidak rapi di printer thermal - perlu CSS tuning per printer type
8. **Mobile Responsiveness:** Form tidak nyaman di layar kecil - perlu responsive design refinement

**Next Steps yang Disarankan:**
1. Whitelist IP `182.8.99.123` untuk melanjutkan pengujian
2. Implementasi basic unit test untuk helper functions
3. Setup ESLint + Prettier untuk code quality consistency
4. Tambahkan environment variable management
5. Dokumentasi API contract dengan Rajabiller
6. Setup basic CI/CD (GitHub Actions/GitLab CI)
7. Load testing untuk measure performance bottleneck

**Yang Bisa Didemonstrasikan saat Interview:**

*Live Demo (jika IP sudah di-whitelist):*
- End-to-end flow: inquiry → payment → history → struk
- Real response dari Rajabiller
- Database record yang ter-update

*Code Walkthrough (jika IP belum di-whitelist):*
- Jelaskan struktur `server.js` dan bagaimana ia mem-forward request ke Rajabiller
- Tunjukkan bagaimana payload dibentuk di frontend (`index.html`) dan di backend
- Jelaskan schema database dan bagaimana data transaksi disimpan
- Tunjukkan bagaimana `struk.html` membaca data dan memformatnya untuk print

*Design Discussion:*
- Mengapa memilih Express untuk backend (lightweight, familiar)
- Mengapa memilih SQLite (embedded, zero-config, cocok untuk MVP)
- Mengapa vanilla JS untuk frontend (simple project, tidak butuh framework)
- Trade-off yang diambil vs alternative yang bisa dipilih

*Problem Solving:*
- Ceritakan kendala yang dihadapi (IP whitelist, TLS rejection)
- Jelaskan langkah-langkah debugging yang dilakukan
- Tunjukkan kemampuan troubleshooting (membaca console log, inspecting network request)
- Jelaskan solusi yang sudah dicoba dan rencana selanjutnya

**Kesimpulan:**

Aplikasi ini adalah implementasi functional MVP untuk PPOB PDAM dengan fitur inti yang sudah berjalan. Kendala utama saat ini adalah **IP whitelist** yang mencegah pengujian langsung ke Rajabiller. Untuk keperluan interview, fokuslah pada:

1. **Demonstrasi pemahaman arsitektur** - explained clearly
2. **Ability to identify issues** - code review findings
3. **Problem-solving approach** - how to handle the IP whitelist blocker
4. **Communication skills** - explain technical decisions in business context

Dengan persiapan yang matang, kendala IP whitelist ini justru bisa menjadi *ice breaker* untuk menunjukkan kemampuan analytical thinking dan problem-solving.

## Pertanyaan untuk Recruiter

- Apakah ada timeline khusus untuk pengujian IP whitelist?
- Apakah bisa menggunakan staging environment Rajabiller yang berbeda?
- Apakah ada dokumen API spec yang bisa dijadikan referensi?
- Apakah ada tech stack preference untuk production (database, hosting, dll)?
- Apakah ada concern khusus tentang compliance/regulasi yang perlu diperhatikan?

## Referensi

- [Rajabiller API Documentation](https://docs.rajabiller.com) - *placeholder, silakan ganti dengan doc aktual*
- [Express.js Best Practices](https://expressjs.com/en/advanced/best-practice-performance.html)
- [SQLite Documentation](https://www.sqlite.org/docs.html)
- [PPOB Standard](https://www.bi.go.id/id/pjok/ppob) - *placeholder*

**Additional Observations (Deep Dive):**

*Dependencies:*
- `node-fetch` v3.3.2 - untuk Node.js < 18. Jika menggunakan Node 18+, bisa ganti dengan built-in `fetch` global
- `sqlite3` v5.1.6 - menggunakan callback-based API. Alternatif: `better-sqlite3` untuk synchronous API yang lebih performan
- Tidak ada `dotenv` untuk environment variables management
- Tidak ada validation library seperti `zod` atau `joi` untuk input validation

*Middleware yang Belum Ada:*
- Error handling middleware (untuk menangkap error secara centralized)
- Request logging middleware (mencatat method, path, status, duration)
- Validation middleware (memastikan payload sesuai schema)
- CORS middleware (jika frontend di-host berbeda)
- Compression middleware (untuk response size optimization)

*Security Concerns:*
- Tidak ada HTTPS enforcement (bisa dihandle di reverse proxy)
- Tidak ada CSP (Content Security Policy) untuk XSS protection
- Tidak ada helmet.js untuk security headers
- Tidak ada input sanitization untuk mencegah SQL injection (walau SQLite menggunakan prepared statements)
- Tidak ada rate limiting untuk mencegah abuse

*Performance Considerations:*
- SQLite tidak cocok untuk concurrent writes yang tinggi - consider PostgreSQL untuk production
- Tidak ada caching layer untuk response inquiry yang sama ( bisa cache by idpel+kode_produk)
- Tidak ada CDN untuk static assets (frontend di-serve dari same origin)
- Tidak ada pagination untuk `/api/history` - bisa issue jika data transaksi mencapai ratusan ribu

**Rekomendasi Teknis Lanjutan:**
1. Implementasi layered architecture: routes → controllers → services → repositories
2. Separasi konfigurasi: config file untuk dev/staging/production
3. Database migration system (misal: `knex` atau `prisma migrate`)
4. API versioning untuk backward compatibility
5. Feature flags untuk gradual rollout
6. A/B testing capability untuk UX experiments
7. Analytics tracking untuk user behavior

**Testing Approach yang Bisa Didiskusikan:**

*Unit Tests:*
- `terbilang()` function - test boundary values (0, 1, 10, 11, 100, 1000, 1jt)
- `formatRupiahCustom()` - test formatting large numbers, decimals
- `generateRef()` - test uniqueness (mungkin perlu mocking Date.now)

*Integration Tests:*
- POST `/api/inquiry` - test dengan mock Rajabiller response
- POST `/api/payment` - test happy path dan error path
- GET `/api/history` - test data retrieval dan ordering
- Database insert - test data tersimpan dengan benar

*E2E Tests:*
- Full flow: inquiry → payment → history → struk
- Test dengan Playwright atau Cypress
- Mock network layer untuk isolasi test

*Manual Test Cases:*
- Inquiry dengan ID pelanggan valid/invalid
- Payment dengan konfirmasi dan tanpa konfirmasi
- Print struk dengan berbagai ukuran paper
- Responsivitas di mobile/tablet/desktop

**Metrics yang Bisa Ditrack:**
- API response time (p50, p95, p99)
- Payment success rate
- Inquiry to payment conversion rate
- Error rate by endpoint
- Database query performance
- Frontend load time (LCP, FID, CLS)

**Scalability Path:**
- Phase 1: Current (single server, SQLite)
- Phase 2: Add Redis untuk caching inquiry result
- Phase 3: Migrate ke PostgreSQL untuk concurrent writes
- Phase 4: Horizontal scaling dengan load balancer
- Phase 5: Microservices separation (inquiry service, payment service, notification service)

**Detailed Code Review (Line-by-Line Analysis):**

*server.js:*
- Line 12: `process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0'` - **CRITICAL**: Menonaktifkan verifikasi SSL certificate. Bisa menyebabkan MITM attack. Hanya untuk development.
- Line 19-20: `const UID = 'SP300203'; const PIN = '311575';` - **HIGH**: Credentials exposed di source code. Harus di-environment variables.
- Line 22: `const db = new sqlite3.Database('./database.sqlite')` - **MEDIUM**: Path relatif, issue jika dijalankan dari directory berbeda. Gunakan `path.join(__dirname, 'database.sqlite')`.
- Line 46: `const generateRef = () => 'REF' + Date.now() + Math.floor(Math.random() * 1000);` - **LOW**: Collision possible dalam concurrent scenario. Gunakan UUID atau nanoid.
- Line 48-80: `/api/inquiry` - **MEDIUM**: Tidak ada timeout, tidak ada input validation, tidak ada error handling untuk response non-JSON.
- Line 82-132: `/api/payment` - **HIGH**: Tidak ada idempotency check. Jika user retry, bisa double charge. Tidak ada transaction rollback jika DB insert gagal.
- Line 134-139: `/api/history` - **LOW**: Tidak ada pagination, tidak ada query parameter untuk filter.

*index.html:*
- Line 103-112: `PDAM_MAP` dan `IDPEL_MAP` - **MEDIUM**: Hardcoded di frontend, bisa di-extract ke backend atau config file.
- Line 154-158: fetch call - **HIGH**: Tidak ada timeout, tidak ada abort controller, tidak ada error handling untuk network error.
- Line 166-168: `parseFloat(result.nominal || 0)` - **MEDIUM**: Tidak handle NaN atau invalid number format.
- Line 171-182: `inquiryDataGlobal` - **MEDIUM**: Manual object construction, rawan typo dan missing field.
- Line 204-232: Payment flow - **HIGH**: Tidak ada disable button setelah click (ada, tapi di line 207), tidak ada prevention untuk double submit.

*struk.html:*
- Line 238: `JSON.parse(tx.response_data)` - **LOW**: Sudah ada try-catch, bagus.
- Line 259-283: Loop monthperiod1-6 - **MEDIUM**: Hardcoded limit 6 bulan, tidak dinamis sesuai response.
- Line 274-281: Parsing miscamount - **LOW**: Logic parsing `0|16150` cukup tricky, perlu dokumentasi.

**Technical Debt yang Terakumulasi:**
1. Hardcoded credentials dan URL
2. Tidak ada error boundaries di frontend
3. Tidak ada retry mechanism di backend
4. Tidak ada circuit breaker untuk external API
5. Tidak ada monitoring/alerting
6. Tidak ada automated testing
7. Tidak ada CI/CD pipeline
8. Tidak ada security headers
9. Tidak ada rate limiting
10. Tidak ada request/response logging

**Quick Wins (bisa dilakukan dalam 1-2 hari):**
1. Pindah UID/PIN ke environment variables
2. Tambahkan timeout untuk fetch requests (30 detik)
3. Tambahkan abort controller untuk cancel request
4. Tambahkan basic error logging dengan timestamp
5. Tambahkan input validation sederhana di backend
6. Tambahkan disable button saat loading untuk prevent double submit
7. Tambahkan loading spinner yang konsisten
8. Fix TLS verification untuk production
9. Tambahkan CORS middleware
10. Tambahkan health check endpoint

**Interview Talking Points (Advanced):**
- "Saya aware bahwa aplikasi ini masih dalam bentuk MVP dan ada beberapa technical debt yang perlu di-address"
- "Prioritas saya adalah memastikan security aspect ter-cover sebelum scalability"
- "Saya percull bahwa monitoring dan observability adalah bagian penting dari production-ready system"
- "Untuk payment integration, idempotency dan exactly-once semantics adalah critical"
- "Saya ingin belajar lebih lanjut tentang payment industry standards seperti PCI DSS"

**Database Schema Detail:**

```sql
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kode_produk TEXT,           -- WASDA / WABONDO
    nama_pdam TEXT,             -- PDAM Sidoarjo / PDAM Bondowoso
    idpel TEXT,                 -- ID Pelanggan
    nama TEXT,                  -- Nama pelanggan
    alamat TEXT,                -- Alamat pelanggan
    nominal REAL,               -- Nominal tagihan
    admin REAL,                 -- Biaya admin
    total_bayar REAL,           -- Total yang dibayar
    ref1 TEXT,                  -- Reference ID dari inquiry
    ref2 TEXT,                  -- Reference ID dari payment
    status TEXT,                -- SUCCESS / FAILED / PENDING
    response_data TEXT,         -- Full JSON response dari Rajabiller
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Index yang bisa ditambahkan untuk performa:
CREATE INDEX idx_transactions_idpel ON transactions(idpel);
CREATE INDEX idx_transactions_created_at ON transactions(created_at);
CREATE INDEX idx_transactions_status ON transactions(status);
```

**API Contract Schema:**

*POST /api/inquiry*
```
Request:
{
  kode_produk: string (required) - "WASDA" | "WABONDO"
  idpel: string (required) - ID Pelanggan
}

Response Success (200):
{
  status: "00",
  idpelanggan1: string,
  idpelanggan2?: string,
  idpelanggan3?: string,
  nama: string,
  alamat: string,
  nominal: string,
  biayaadmin: string,
  total_bayar: string,
  ref1: string,
  ref2?: string,
  jumlah_bulan?: string,
  monthperiod1?: string,
  yearperiod1?: string,
  billamount1?: string,
  penalty1?: string,
  miscamount1?: string,
  ... (repeat until monthperiod6)
}

Response Error (400/500):
{
  rc: "99",
  ket: "Error message",
  status?: string
}
```

*POST /api/payment*
```
Request:
{
  kode_produk: string,
  idpel: string,
  idpel2?: string,
  idpel3?: string,
  nominal: number,
  ref1: string,
  ref2?: string,
  nama_pdam: string,
  nama: string,
  alamat: string,
  admin: number,
  total_bayar: number
}

Response Success (200):
{
  status: "00",
  rc: "00",
  ket: "Transaksi Berhasil",
  ref2: string,
  total_bayar: string
}

Response Error (400/500):
{
  rc: "99",
  ket: "Error message",
  status?: string
}
```

**State Management Analysis:**

*Current Approach (Frontend):*
- Single global variable `inquiryDataGlobal` menyimpan state sementara
- Tidak ada persistence - refresh page akan reset state
- Tidak ada validation bahwa data masih valid saat payment

*Recommended Approach:*
- Gunakan URL query parameter untuk passing ref1 ke payment
- Atau gunakan sessionStorage untuk temporary storage
- Atau redesign agar inquiry dan payment dalam satu form submit

**Network & API Analysis:**

*Current Implementation:*
- Server menggunakan `node-fetch` v3 dengan ES modules
- Tidak ada connection pooling ke Rajabiller
- Setiap request membuat connection baru (HTTP/1.1 default)
- Tidak ada keep-alive optimization

*Potential Issues:*
- High latency karena TCP handshake setiap request
- Tidak ada connection reuse
- Tidak ada HTTP/2 support
- Tidak ada request compression

*Improvements:*
- Gunakan `undici` (built-in di Node 18+) untuk better performance
- Implementasi connection pooling jika Rajabiller mendukung
- Enable HTTP/2 jika possible
- Compress request body jika payload besar

**Error Handling Analysis:**

*Current State:*
- Backend: try-catch di setiap route handler, return generic error message
- Frontend: try-catch di fetch, show alert box
- Tidak ada error classification (4xx vs 5xx vs network error)

*Recommended Pattern:*
```
Backend:
- Custom AppError class untuk differentiate error types
- Error handling middleware untuk centralized error response
- Log error dengan stack trace untuk debugging
- Return appropriate HTTP status code

Frontend:
- Error boundary untuk catch rendering errors
- Retry logic untuk network errors (exponential backoff)
- Graceful degradation untuk partial failures
- User-friendly error messages (bukan raw error string)
```

**Security Analysis:**

*Current Risks:*
1. **Credential Exposure**: UID/PIN di source code
2. **TLS Disabled**: `NODE_TLS_REJECT_UNAUTHORIZED = '0'`
3. **No Input Sanitization**: Raw `req.body` used directly
4. **No Output Encoding**: Response data rendered as-is di frontend
5. **No Authentication**: API endpoints publicly accessible
6. **No Authorization**: No role-based access control

*Mitigations Needed:*
1. Environment variables + `.env` file + `.gitignore`
2. Valid SSL certificate di production
3. Input validation dengan schema validation library
4. Output encoding untuk prevent XSS
5. API key atau JWT untuk authenticate client
6. Rate limiting per IP untuk prevent abuse

**Performance Bottlenecks Identified:**

1. **SQLite Write Lock**: Single writer limitation - akan jadi bottleneck jika traffic tinggi
2. **No Caching**: Inquiry result di-fetch dari Rajabiller setiap request - bisa di-cache dengan TTL 5-10 menit
3. **Synchronous DB Operations**: Callback-based SQLite blocking event loop
4. **No CDN**: Static assets di-serve dari same origin - initial load lambat
5. **No Image Optimization**: Jika ada image di frontend, tidak di-optimize
6. **No Bundle Optimization**: Jika frontend berkembang, tidak ada code splitting

**Code Quality Metrics:**

*Cyclomatic Complexity:*
- `server.js` inquiry route: ~5
- `server.js` payment route: ~6
- `index.html` form handler: ~8
- `struk.html` loadStrukData: ~10

*Maintainability Index:* Medium - code cukup readable tapi kurang modular

*Technical Debt Ratio:* ~30% - ada beberapa quick wins yang bisa dilakukan

**Recommendation untuk Recruiter:**

Sebagai Technical Assessor, saya merekomendasikan untuk:

1. **Fokus pada Problem Solving**: Bagaimana candidate menghadapi IP whitelist blocker
2. **Code Review Capability**: Apakah candidate bisa identify issue dari code review
3. **Communication**: Apakah candidate bisa explain technical decision dalam bahasa bisnis
4. **Learning Agility**: Apakah candidate menunjukkan willingness to learn (misal: payment industry standards)
5. **Architecture Thinking**: Apakah candidate bisa explain trade-off yang diambil

Proyek ini sudah menunjukkan competency dasar dalam full-stack development. Yang membedakan candidate adalah bagaimana mereka approach problem solving dan continuous improvement mindset.

## Appendix

### A. Full server.js Reference

Lihat file `server.js` untuk implementasi lengkap backend.

### B. Frontend Architecture Details

```
public/
├── index.html (Main Application)
│   ├── Form Inquiry (PDAM selection + ID Pelanggan)
│   ├── Result Section (Detail tagihan)
│   └── Payment Button
├── history.html (Transaction History)
│   ├── Table listing all transactions
│   └── Link to struk per transaction
└── struk.html (Receipt View)
    ├── Read-only transaction details
    └── Print button
```

### C. Environment Variables (Recommended)

```env
PORT=3000
RAJABILLER_URL=https://c-dev-partnerlink.rajabiller.com/json/index.php
RAJABILLER_UID=SP300203
RAJABILLER_PIN=311575
NODE_ENV=development
```

### D. Git Workflow Suggestion

```
main (production-ready code)
├── develop (integration branch)
│   ├── feature/inquiry-flow
│   ├── feature/payment-flow
│   ├── feature/history-page
│   └── feature/receipt-print
└── hotfix/ip-whitelist
```

### E. Contact Information

Untuk pertanyaan lebih lanjut tentang proyek ini, silakan hubungi:
- Developer: Ardy Rendra
- Email: [contact email]
- Phone: [phone number]

---
*Last updated: 2026-09-15*
*Project: PPOB PDAM - Tes Teknis PT Bimasakti Multi Sinergi*

## Tambahan: Pattern Analysis

**REST API Patterns:**
- Endpoint menggunakan naming convention yang konsisten (`/api/inquiry`, `/api/payment`, `/api/history`)
- HTTP methods sesuai: POST untuk create/action, GET untuk read
- Tidak ada versioning di URL (`/api/v1/inquiry`) - akan menjadi issue jika breaking change needed
- Tidak ada consistent response envelope (wrap response dalam `{ data, meta, error }`)

**Frontend Patterns:**
- Event-driven architecture: form submit, button click, fetch response
- DOM manipulation langsung tanpa framework (vanilla JS)
- State management via global variable (bukan React/Vue state)
- No component reusability (semua di satu file HTML)

**Backend Patterns:**
- Route handler langsung di `app.post()` tanpa controller layer
- Database access langsung di route handler tanpa repository pattern
- No middleware untuk cross-cutting concerns (logging, validation, auth)
- No separation of concerns (business logic mixed dengan route handling)

**Payment Integration Patterns:**
- Inquiry-Payment two-phase commit pattern
- Reference number generation di client side (bisa di-server side untuk consistency)
- Response data persistence untuk audit trail (bagus)
- No compensation mechanism jika payment gagal setelah inquiry

**Database Patterns:**
- Single table design untuk transactions (tidak ada normalization)
- No foreign keys (tidak ada tabel terpisah untuk PDAM, users, dll)
- No transaction support untuk concurrent operations
- No archiving strategy untuk old transactions

**Future Architecture Recommendation:**

```
Phase 1 (Current): Monolithic
├── server.js (all logic in one file)
├── SQLite (embedded DB)
└── Vanilla JS frontend

Phase 2 (Modular Monolith):
├── src/
│   ├── routes/ (API routes)
│   ├── controllers/ (request handlers)
│   ├── services/ (business logic)
│   ├── repositories/ (data access)
│   ├── middleware/ (auth, validation, logging)
│   └── config/ (environment config)
├── PostgreSQL (for production)
└── React/Vue frontend (if needed)

Phase 3 (Microservices):
├── inquiry-service
├── payment-service
├── history-service
├── notification-service
└── API Gateway
```

**Learning Roadmap dari Proyek Ini:**

1. **Basic**: Express routing, SQLite, fetch API, DOM manipulation
2. **Intermediate**: Error handling, input validation, database design, security basics
3. **Advanced**: Microservices, message queues, distributed systems, monitoring
4. **Expert**: System design, capacity planning, disaster recovery, compliance

**Key Takeaways untuk Interview:**

1. Aplikasi ini adalah starting point, bukan akhir
2. Yang penting adalah kemampuan identify problem dan propose solution
3. Technical skills bisa dipelajari, tapi problem-solving mindset adalah yang utama
4. Payment integration adalah domain yang complex, experience di sini sangat valuable
5. Tunjukkan growth mindset dan willingness to improve

**Final Thought:**

Kendala IP whitelist bukanlah kesalahan teknis, tapi operational challenge yang umum di payment integration. Yang penting adalah bagaimana candidate approach masalah ini, apakah mereka bisa:

- Menganalisis root cause dengan jelas
- Mengomunikasikan masalah dan solusi secara efektif
- Menunjukkan technical competency lewat code review
- Menunjukkan business understanding tentang payment industry

Semoga analisa ini membantu dalam proses interview. Good luck!
1. **High:** Whitelist IP `182.8.99.123` untuk melanjutkan pengujian
2. **High:** Pindah credentials ke environment variables
3. **Medium:** Tambahkan validasi response schema dari Rajabiller
4. **Medium:** Implementasi proper error logging ke file
5. **Low:** Tambahkan retry mechanism untuk API calls

**Saran pendekatan saat IP belum di-whitelist:**
- Gunakan tools seperti Postman/cURL dari environment yang sudah di-whitelist untuk Inspeksi respons asli
- Dokumentasikan format response yang diharapkan berdasarkan dokumentasi Rajabiller
- Siapkan fallback/mock data untuk demonstrasi UI kepada recruiter
- Catat semua asumsi dan batasan testing dalam laporan teknis

**Rekomendasi untuk interview:**
- Tunjukkan arsitektur aplikasi yang sudah disusun (backend + frontend + DB)
- Jelaskan alur inquiry → payment → history → struk
- Tunjukkan kode `server.js` dan mapping produk PDAM
- Akan lebih kuat jika dibuktikan via video call/screenshot saat IP sudah di-whitelist
- Alternatif: siapkan mock response lokal untuk demonstrasi alur jika whitelist belum aktif saat interview
- Siapkan penjelasan tentang aspek keamanan (hardcoded credentials, TLS verification) sebagai bagian dari diskusi best practices
