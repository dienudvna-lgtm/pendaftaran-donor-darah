# 🚀 Quick Start Guide - PMI Connect Authentication System

Panduan cepat untuk mulai menggunakan sistem autentikasi PMI Connect.

## ⚡ Setup 5 Menit

### 1. Import Database

Buka terminal dan jalankan:

```bash
mysql -u root -p < pmi_connect.sql
```

Atau via phpMyAdmin:
1. Buka phpMyAdmin
2. Klik `Import`
3. Upload file `pmi_connect.sql`
4. Klik `Go`

### 2. Konfigurasi Database

Edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');      // Sesuaikan host
define('DB_USER', 'root');           // Username database
define('DB_PASS', '');               // Password database
define('DB_NAME', 'pmi_connect');    // Database name
```

### 3. Test Connection

Akses di browser:
- Login: `http://localhost/login.html`
- Register: `http://localhost/register.html`

## 📝 Cara Menggunakan

### Registrasi Akun Baru

1. Klik **Sign Up** atau akses `/register.html`
2. Isi form:
   - **Username**: Pilih username (minimal 4 karakter)
   - **Email**: Masukkan email valid
   - **Password**: Minimal 8 karakter (huruf + angka)
   - **Konfirmasi Password**: Ulangi password
   - **Syarat & Ketentuan**: Centang checkbox
3. Klik **Sign Up**
4. Jika berhasil, redirect ke login

### Login

1. Klik **Login** atau akses `/login.html`
2. Isi form:
   - **Email**: Email yang terdaftar
   - **Password**: Password Anda
3. Klik **Login**
4. Jika berhasil, redirect ke home dengan greeting nama Anda

### Akses Profile

1. Setelah login, klik **Profile** di navbar
2. Lihat data user Anda:
   - Username
   - Email
   - Tanggal pendaftaran
   - Foto profil (default)

### Logout

1. Klik dropdown user di navbar (icon user profile)
2. Pilih **Logout**
3. Atau klik **Logout** di halaman profile
4. Konfirmasi logout
5. Redirect ke halaman login

## 🔒 Keamanan Login

### Password Requirements
✅ Minimal 8 karakter
✅ Mengandung huruf (a-z, A-Z)
✅ Mengandung angka (0-9)
✅ Tidak boleh sama dengan confirm password

### Password Strength Meter
Saat mengetik password, indicator akan menunjukkan:
- 🔴 **Lemah** - Password tidak cukup kuat
- 🟡 **Sedang** - Password cukup kuat
- 🟢 **Kuat** - Password sangat kuat

## 🎨 File Penting

| File | Fungsi |
|------|--------|
| `config/database.php` | Koneksi database |
| `auth/middleware.php` | Fungsi autentikasi |
| `auth/process_login.php` | Handle login |
| `auth/process_register.php` | Handle register |
| `auth/logout.php` | Handle logout |
| `login.html` | Halaman login |
| `register.html` | Halaman register |
| `home.php` | Halaman utama |
| `profile.php` | Halaman profil |
| `css/auth.css` | Styling auth pages |
| `js/auth.js` | JavaScript validation |

## 🐛 Troubleshoot

### Database Connection Error
**Masalah:** "Koneksi database gagal"

**Solusi:**
1. Buka `config/database.php`
2. Cek username & password database
3. Pastikan MySQL running: `mysql -u root`
4. Cek database exist: `SHOW DATABASES;`

### Email Already Registered
**Masalah:** "Email sudah terdaftar"

**Solusi:**
- Gunakan email berbeda
- Atau login dengan email tersebut jika ada akun

### Password Validation Error
**Masalah:** "Password harus mengandung huruf dan angka"

**Solusi:**
- Pastikan password: `Pass123456`
  - Huruf: P, a, s, s
  - Angka: 1, 2, 3, 4, 5, 6

### Can't Access Profile
**Masalah:** Redirect ke login saat akses profile

**Solusi:**
- Login terlebih dahulu
- Bersihkan cookies: Clear cache browser
- Cek session PHP enabled

## 📁 Directory Structure

```
Pendaftaran Donor Darah/
├── config/
│   └── database.php          ⚙️ Config koneksi
├── auth/
│   ├── middleware.php        🔐 Auth functions
│   ├── process_login.php     📥 Handle login
│   ├── process_register.php  📝 Handle register
│   └── logout.php            🚪 Handle logout
├── css/
│   └── auth.css              🎨 Auth styling
├── js/
│   └── auth.js               ✨ Form handling
├── login.html                🔑 Login page
├── register.html             📝 Register page
├── home.php                  🏠 Home (with session)
├── profile.php               👤 User profile
└── pmi_connect.sql           🗄️ Database
```

## 🔄 User Flow

```
Start
  ↓
[Access Website]
  ↓
┌─────────────────┐
│ Already Logged? │
└────┬────────┬───┘
     Yes      No
     ↓        ↓
   Home    Register?
   ↓          ↓ Yes
   ↓      Register Form
   ↓          ↓
   ↓      Validate Input
   ↓          ↓
   ↓      Hash Password
   ↓          ↓
   ↓      Save to DB
   ↓          ↓
   ↓      Redirect Login
   ↓          ↓
   └──────→ Login Form
             ↓
         Validate Email
             ↓
         Verify Password
             ↓
         Create Session
             ↓
         Redirect Home
             ↓
         View Profile
             ↓
         Logout → Login Page
```

## ✅ Testing Checklist

Sebelum deploy ke production:

- [ ] Database berhasil di-import
- [ ] Config database benar
- [ ] Register page works & buat user baru
- [ ] Login page works & login sukses
- [ ] Profile page shows data correct
- [ ] Logout works & session cleared
- [ ] Responsive di mobile
- [ ] Error messages display
- [ ] All links working
- [ ] No console errors

## 📞 Common Questions

**Q: Bagaimana jika lupa password?**
A: Saat ini belum ada reset password. Buat akun baru dengan email lain.

**Q: Bisakah upload foto profil?**
A: Fitur ini belum ada di v1. Akan ditambahkan di update berikutnya.

**Q: Apakah aman?**
A: Ya! Password di-hash dengan BCRYPT, semua query pakai prepared statements, input di-sanitize.

**Q: Berapa lama session berlaku?**
A: 1 jam (3600 detik). Jika tidak aktif, harus login lagi.

**Q: Bisa multiple login dari device berbeda?**
A: Ya! Session terpisah di setiap device.

## 🚀 Next Steps

Untuk developer yang ingin enhance:

1. **Reset Password** - Tambah email reset
2. **Email Verification** - Verifikasi email saat daftar
3. **2FA** - Two-factor authentication
4. **Social Login** - Google/Facebook login
5. **Admin Panel** - Manage users
6. **Activity Logging** - Track user activities
7. **Profile Edit** - Update user info
8. **Photo Upload** - Upload profile picture

## 📚 Dokumentasi Lengkap

Untuk detail lebih lengkap, baca:
- `SETUP_AUTENTIKASI.md` - Setup guide komprehensif
- `IMPLEMENTATION_CHECKLIST.md` - Fitur checklist

## 📧 Support

Jika ada masalah:
1. Cek dokumentasi
2. Check browser console (F12)
3. Check server error logs
4. Hubungi tim development

---

**Selamat menggunakan PMI Connect! 🎉**

Jika ada pertanyaan atau masalah, jangan ragu untuk bertanya.

Happy coding! 💻
