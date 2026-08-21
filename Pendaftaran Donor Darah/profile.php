<?php
/**
 * User Profile Page
 * Display user information after login
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/auth/middleware.php';

// Require login
requireLogin();

// Get current user data
$user = getCurrentUser();

// If user data not found, logout
if (!$user) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Profile - PMI Connect" />
    <meta name="description" content="Halaman profil pengguna PMI Connect yang menampilkan informasi akun dan kontak pribadi." />
    <title>Profile - PMI Connect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
</head>
<body>
    <header class="navbar">
        <div class="container nav-shell">
            <a href="home.php" class="brand"><span class="brand-mark"><i class="fa-solid fa-shield-heart"></i></span> PMI Connect</a>
            <nav class="nav-links">
                <a href="home.php">Home</a>
                <a href="about-pmi.html">About</a>
                <a href="event.html">Event</a>
                <a href="saran.html">Saran</a>
                <a href="contact.html">Kontak</a>
                <a class="active" href="profile.php">Profile</a>
                <a href="auth/logout.php" class="logout-link" onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
            </nav>
            <div class="nav-actions">
                <button class="icon-btn" id="themeToggle" aria-label="Toggle dark mode"><i class="fa-regular fa-moon"></i></button>
                <button class="icon-btn mobile-toggle" aria-label="Toggle nav"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
    </header>

    <main class="container" style="padding:40px 0 80px;">
        <div class="section-title" data-aos="fade-up">
            <h2>Profile Anda</h2>
            <p>Informasi akun pengguna PMI Connect dan data pribadi Anda.</p>
        </div>

        <div class="info-grid">
            <!-- Profile Picture Card -->
            <article class="card info-card" data-aos="fade-up">
                <div class="icon-wrap" style="width: 120px; height: 120px; margin: 0 auto 16px;">
                    <i class="fa-solid fa-user-circle" style="font-size: 3rem;"></i>
                </div>
                <h3>Foto Profil</h3>
                <p style="text-align: center;">
                    <span class="chip" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: rgba(193, 18, 31, 0.1); color: #c1121f; border-radius: 999px; font-size: 0.9rem;">
                        <i class="fa-solid fa-user"></i> Default Avatar
                    </span>
                </p>
            </article>

            <!-- Account Details Card -->
            <article class="card info-card" data-aos="fade-up" data-aos-delay="80">
                <div class="icon-wrap"><i class="fa-solid fa-circle-info"></i></div>
                <h3>Detail Akun</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 4px;">Username</p>
                        <p style="font-weight: 600; font-size: 1.05rem;"><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>
                    <div>
                        <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 4px;">Email</p>
                        <p style="font-weight: 600; font-size: 1.05rem;"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div>
                        <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 4px;">Tanggal Pembuatan Akun</p>
                        <p style="font-weight: 600; font-size: 1.05rem;">
                            <i class="fa-solid fa-calendar-days" style="color: #c1121f; margin-right: 6px;"></i>
                            <?php 
                                $date = new DateTime($user['created_at']);
                                echo $date->format('d F Y');
                            ?>
                        </p>
                    </div>
                </div>
            </article>
        </div>

        <!-- Account Summary -->
        <div class="card" data-aos="fade-up" style="padding: 32px; max-width: 720px; margin: 32px auto;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div style="width: 80px; height: 80px; border-radius: 16px; background: linear-gradient(135deg, #c1121f, #ff6b6b); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h3 style="margin-bottom: 4px;"><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p style="color: var(--muted); margin-bottom: 4px;"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p style="font-size: 0.85rem; color: #c1121f;">
                        <i class="fa-solid fa-check-circle"></i> Akun Terverifikasi
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 24px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                <div style="text-align: center;">
                    <p style="font-size: 1.4rem; font-weight: 700; color: #c1121f;">
                        <?php
                            // Count user activities (optional - can be enhanced later)
                            echo "1";
                        ?>
                    </p>
                    <p style="color: var(--muted); font-size: 0.9rem;">Akun Aktif</p>
                </div>
                <div style="text-align: center;">
                    <p style="font-size: 1.4rem; font-weight: 700; color: #c1121f;">0</p>
                    <p style="color: var(--muted); font-size: 0.9rem;">Event Diikuti</p>
                </div>
                <div style="text-align: center;">
                    <p style="font-size: 1.4rem; font-weight: 700; color: #c1121f;">0</p>
                    <p style="color: var(--muted); font-size: 0.9rem;">Donasi Darah</p>
                </div>
            </div>

            <div style="padding-top: 24px;">
                <h4 style="margin-bottom: 16px; font-size: 1.1rem;">Keamanan Akun</h4>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
                    <div>
                        <p style="font-weight: 500;">Password</p>
                        <p style="font-size: 0.9rem; color: var(--muted);">Ubah password Anda secara berkala</p>
                    </div>
                    <button class="btn btn-outline" onclick="alert('Fitur ubah password akan segera hadir');">Ubah</button>
                </div>
            </div>

            <div style="padding-top: 24px;">
                <a href="auth/logout.php" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin keluar?');" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <h3 style="color: white; margin-bottom: 12px;">PMI Connect</h3>
                <p>Portal resmi Palang Merah Indonesia untuk layanan kemanusiaan dan donor darah.</p>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 12px;">Navigasi</h4>
                <ul class="list-check" style="color: #d1d5db;">
                    <li><a href="home.php" style="color: #d1d5db; text-decoration: none;">Home</a></li>
                    <li><a href="about-pmi.html" style="color: #d1d5db; text-decoration: none;">About PMI</a></li>
                    <li><a href="event.html" style="color: #d1d5db; text-decoration: none;">Event</a></li>
                    <li><a href="contact.html" style="color: #d1d5db; text-decoration: none;">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 12px;">Kontak</h4>
                <p style="color: #d1d5db;">Email: info@pmiconnect.org</p>
                <p style="color: #d1d5db;">Telepon: +62 (0)21 123-4567</p>
                <p style="color: #d1d5db;">Alamat: Jl. Jenderal Gatot Subroto No. 40, Jakarta</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 80 });
    </script>
</body>
</html>
