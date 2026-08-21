# Setup Guide - Sistem Autentikasi PMI Connect

Panduan lengkap untuk setup dan menggunakan sistem autentikasi PMI Connect.

## 📋 Prasyarat

- PHP 7.4+ dengan mysqli extension
- MySQL/MariaDB Server
- Web Server (Apache/Nginx)
- Browser modern

## 🚀 Langkah-Langkah Setup

### 1. Database Setup

#### Opsi A: Import SQL File

1. Buka phpMyAdmin atau MySQL CLI
2. Jalankan file `pmi_connect.sql` untuk membuat database dan tabel:
   ```bash
   mysql -u root -p < pmi_connect.sql
   ```

#### Opsi B: Manual

1. Buat database baru:
   ```sql
   CREATE DATABASE pmi_connect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Gunakan database:
   ```sql
   USE pmi_connect;
   ```

3. Jalankan query SQL dari file `pmi_connect.sql`

### 2. Konfigurasi Database

Edit file `config/database.php` dan sesuaikan:

```php
define('DB_HOST', 'localhost');     // Host database
define('DB_USER', 'root');          // Username database
define('DB_PASS', '');              // Password database
define('DB_NAME', 'pmi_connect');   // Nama database
```

### 3. File Structure

Pastikan struktur folder sudah benar:

```
Pendaftaran Donor Darah/
├── config/
│   └── database.php          # Konfigurasi database
├── auth/
│   ├── middleware.php        # Fungsi autentikasi
│   ├── process_login.php     # Process form login
│   ├── process_register.php  # Process form register
│   └── logout.php            # Proses logout
├── css/
│   ├── auth.css              # Styling login/register
│   └── style.css             # Main styles
├── js/
│   └── auth.js               # JavaScript autentikasi
├── login.html                # Halaman login
├── register.html             # Halaman register
├── home.php                  # Halaman utama (dengan session check)
├── profile.php               # Halaman profil user
└── pmi_connect.sql           # File SQL database
```

## 📝 Fitur Autentikasi

### 1. Register (Pendaftaran)

**URL:** `/register.html`

**Form Fields:**
- Username (4+ karakter)
- Email (valid & unique)
- Password (8+ karakter, huruf + angka)
- Konfirmasi Password (harus sama)
- Checkbox syarat & ketentuan

**Validasi:**
- Username tidak boleh kosong, minimal 4 karakter
- Email harus valid dan belum terdaftar
- Password minimal 8 karakter, mengandung huruf dan angka
- Password dan konfirmasi password harus sama
- Checkbox syarat & ketentuan harus dicentang

**Proses:**
1. Client mengirim form ke `/auth/process_register.php`
2. Server validasi input
3. Cek apakah email/username sudah terdaftar
4. Hash password menggunakan `password_hash()`
5. Insert ke database
6. Return JSON response
7. Redirect ke halaman login

### 2. Login

**URL:** `/login.html`

**Form Fields:**
- Email
- Password
- Remember Me (optional)

**Validasi:**
- Email tidak boleh kosong dan harus valid
- Password tidak boleh kosong

**Proses:**
1. Client mengirim form ke `/auth/process_login.php`
2. Server cari user berdasarkan email
3. Verify password menggunakan `password_verify()`
4. Jika valid, buat session dan login log
5. Return JSON response dengan redirect URL
6. Redirect ke halaman home

### 3. Session Management

**Session Variables:**
- `$_SESSION['user_id']` - ID user
- `$_SESSION['user_email']` - Email user
- `$_SESSION['username']` - Username user
- `$_SESSION['login_time']` - Waktu login

**Session Timeout:** 3600 detik (1 jam)

### 4. Profile

**URL:** `/profile.php`

**Menampilkan:**
- Avatar/Foto profil (default)
- Username
- Email
- Tanggal pendaftaran
- Statistik aktivitas (placeholder)

**Fitur:**
- Logout dengan konfirmasi
- Informasi keamanan akun

### 5. Logout

**URL:** `/auth/logout.php`

**Proses:**
1. Destroy session
2. Clear cookies
3. Redirect ke login

## 🔒 Keamanan

### Password Hashing
```php
// Saat register
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Saat login
password_verify($password, $user['password'])
```

### Prepared Statements
Semua query menggunakan prepared statements untuk mencegah SQL injection:
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

### Input Validation & Sanitization
```php
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
```

## 🎨 Desain & Styling

### Tema PMI Connect
- **Primary Color:** #c1121f (Merah PMI)
- **Secondary Color:** #fdf0f3 (Merah Muda)
- **Accent Color:** #ff6b6b (Merah Terang)

### Komponen UI
- **Form Input:** Custom styled dengan fokus state
- **Password Strength Meter:** Visual indicator untuk kekuatan password
- **Error Messages:** Alert merah yang jelas
- **Success Messages:** Alert hijau untuk konfirmasi
- **Loading State:** Spinner overlay saat proses

### Responsive Design
- Mobile-first approach
- Breakpoints: 480px, 768px, 1024px
- Touch-friendly buttons (min 44px)

## 🔗 Integration dengan Page Lain

### Home Page (home.php)
- Check apakah user sudah login
- Tampilkan greeting dengan username
- Tampilkan user dropdown menu
- Tampilkan logout button

### Profile Link
- Ubah dari `profile.html` ke `profile.php` (jika perlu)
- Require login sebelum akses
- Tampilkan data user dari database

### Navigation
```html
<!-- Jika belum login -->
<a href="login.html">Login</a>

<!-- Jika sudah login -->
<a href="profile.php">Profile</a>
<a href="auth/logout.php">Logout</a>
```

## 📱 API Endpoints

### POST /auth/process_register.php
**Request:**
```json
{
    "username": "johndonor",
    "email": "john@example.com",
    "password": "StrongPass123",
    "password_confirm": "StrongPass123",
    "agree_terms": "1"
}
```

**Response Success:**
```json
{
    "success": true,
    "message": "Akun berhasil dibuat. Silakan Login."
}
```

**Response Error:**
```json
{
    "success": false,
    "message": "",
    "errors": {
        "username": "Username sudah digunakan.",
        "email": "Email sudah terdaftar.",
        "password": "Password harus mengandung huruf dan angka."
    }
}
```

### POST /auth/process_login.php
**Request:**
```json
{
    "email": "john@example.com",
    "password": "StrongPass123"
}
```

**Response Success:**
```json
{
    "success": true,
    "message": "Login berhasil!",
    "redirect": "/home.html"
}
```

**Response Error:**
```json
{
    "success": false,
    "message": "",
    "errors": {
        "email": "Email belum terdaftar.",
        "password": "Password yang Anda masukkan salah."
    }
}
```

## 🧪 Testing

### Test Register
1. Buka `/register.html`
2. Isi form dengan:
   - Username: `testuser` (min 4 karakter)
   - Email: `test@example.com` (valid email)
   - Password: `Test12345` (min 8 karakter, ada huruf & angka)
   - Konfirmasi: sama dengan password
   - Centang checkbox
3. Klik Sign Up
4. Seharusnya redirect ke login dengan pesan sukses

### Test Login
1. Buka `/login.html`
2. Isi form dengan:
   - Email: `test@example.com`
   - Password: `Test12345`
3. Klik Login
4. Seharusnya redirect ke home dan menampilkan greeting

### Test Profile
1. Pastikan sudah login
2. Klik menu Profile
3. Seharusnya menampilkan data user dari database
4. Klik Logout, seharusnya logout dan redirect ke login

### Test Session
1. Login dengan akun
2. Buka tab baru dan akses `/login.html`
3. Seharusnya auto-redirect ke home (sudah login)

## 🐛 Troubleshooting

### Masalah Koneksi Database
**Error:** "Koneksi database gagal"
- Cek credentials di `config/database.php`
- Pastikan MySQL/MariaDB server running
- Cek privileges user database

### Email Sudah Terdaftar
**Error:** "Email sudah terdaftar"
- Gunakan email yang berbeda
- Atau gunakan akun yang sudah ada untuk login

### Password Salah
**Error:** "Password yang Anda masukkan salah"
- Pastikan caps lock tidak aktif
- Cek lagi password yang dimasukkan
- Jika lupa password, buat akun baru dengan email lain

### Redirect Loop
**Masalah:** Login terus redirect ke login
- Cek apakah session PHP aktif
- Cek error log di server
- Pastikan cookies enabled di browser

## 📚 File Reference

| File | Deskripsi |
|------|-----------|
| `config/database.php` | Konfigurasi koneksi database |
| `auth/middleware.php` | Fungsi-fungsi autentikasi helper |
| `auth/process_register.php` | Handle form register |
| `auth/process_login.php` | Handle form login |
| `auth/logout.php` | Handle logout |
| `login.html` | UI halaman login |
| `register.html` | UI halaman register |
| `home.php` | Halaman utama dengan session check |
| `profile.php` | Halaman profil user (require login) |
| `css/auth.css` | Styling login/register pages |
| `js/auth.js` | JavaScript validation & form handling |
| `pmi_connect.sql` | Database schema & initial data |

## 🎯 Next Steps

Untuk enhancement lebih lanjut, Anda bisa menambahkan:
1. **Password Reset** - Email verification untuk reset password
2. **Email Verification** - Verifikasi email saat register
3. **Two-Factor Authentication** - Keamanan ekstra
4. **Profile Picture Upload** - Upload foto profil
5. **Edit Profile** - Update informasi user
6. **Remember Me** - Cookie-based persistent login
7. **Social Login** - Google/Facebook login
8. **Admin Dashboard** - Manage users & logs

## 📞 Support

Jika ada pertanyaan atau masalah, hubungi tim development atau buka issue di repository.

---

**Last Updated:** 2024
**Version:** 1.0
