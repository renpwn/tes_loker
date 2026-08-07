# 🚀 Genesys Integrated Indonesia – Technical Test

![Laravel](https://img.shields.io/badge/Laravel-10%2B-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.x-blue?logo=php)
![Tailwind](https://img.shields.io/badge/TailwindCSS-3.x-38BDF8?logo=tailwindcss)
![Vite](https://img.shields.io/badge/Vite-4.x-646CFF?logo=vite)
![License](https://img.shields.io/badge/License-Private-lightgrey)

---

Project ini merupakan **technical test** berbasis **Laravel** yang dikembangkan untuk kebutuhan  
**Genesys Integrated Indonesia**.

Aplikasi telah terintegrasi dengan:

- 🔐 **Laravel Breeze** (Authentication)
- 🎨 **Tailwind CSS**
- ⚡ **Vite**
- 🗄️ **MySQL / MariaDB**

---

## 📦 Tech Stack

| Layer      | Teknologi |
|-----------|-----------|
| Backend   | Laravel 10+ |
| Frontend | Blade + Tailwind CSS |
| Auth     | Laravel Breeze |
| Build    | Vite |
| Database | MySQL / MariaDB |
| Package  | Composer & NPM |

---

## 📁 Struktur Folder

> Struktur ini **AMAN** di GitHub (tidak akan rusak / tanpa spasi)

```text
test-GenesysIntegratedIndonesia-main/
└── Coding/
    ├── app/                # Core application logic
    ├── bootstrap/          # Laravel bootstrap files
    ├── config/             # Application configuration
    ├── database/
    │   ├── migrations/     # Database migrations
    │   └── seeders/        # Database seeders
    ├── public/             # Public assets
    ├── resources/
    │   ├── views/          # Blade templates
    │   └── css/
    │       └── js/         # Tailwind & JS resources
    ├── routes/             # Web & API routes
    ├── storage/            # Logs & cache
    ├── tests/              # Feature & unit tests
    ├── .env.example
    ├── composer.json
    ├── package.json
    └── vite.config.js
````

---

## ⚙️ Instalasi & Setup

### 1️⃣ Clone Repository

```bash
git clone https://github.com/username/test-GenesysIntegratedIndonesia.git
cd test-GenesysIntegratedIndonesia-main/Coding
```

### 2️⃣ Install Backend Dependency

```bash
composer install
```

### 3️⃣ Install Frontend Dependency

```bash
npm install
```

### 4️⃣ Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Atur database di `.env`:

```env
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5️⃣ Migrasi Database

```bash
php artisan migrate
```

Jika tersedia seeder:

```bash
php artisan db:seed
```

---

## ▶️ Menjalankan Aplikasi

### Backend

```bash
php artisan serve
```

### Frontend (Vite)

```bash
npm run dev
```

🌐 Akses:

```
http://127.0.0.1:8000
```

---

## 🔐 Autentikasi

Menggunakan **Laravel Breeze**, fitur tersedia:

* Login
* Register
* Logout
* Reset Password
* Email Verification

Lokasi controller:

```text
app/Http/Controllers/Auth
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 📝 Catatan Teknis

* Mengikuti **best practice Laravel**
* Mudah dikembangkan ke:

  * CRUD Module
  * ERP / Inventory System
  * REST API
* UI sudah siap **dark / light mode** via Tailwind
* Asset bundling via **Vite**

---

## 📄 License

🔒 Project ini dibuat **khusus untuk keperluan technical test**
Tidak untuk distribusi atau penggunaan komersial.

---

## 👨‍💻 Author

**Technical Test – Genesys Integrated Indonesia**
Built with ❤️ using Laravel
