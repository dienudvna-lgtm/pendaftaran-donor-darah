<?php
/**
 * Home Page with Session Support
 * Check if user is logged in and display appropriate content
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/auth/middleware.php';

// Get current user if logged in
$currentUser = isLoggedIn() ? getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="PMI Connect - Portal Resmi Palang Merah Indonesia" />
    <meta name="description" content="PMI Connect menyediakan informasi kegiatan PMI, pendaftaran event, donor darah, relawan, edukasi kesehatan, dan layanan kemanusiaan." />
    <title>PMI Connect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
  </head>
  <body>
    <header class="navbar">
      <div class="container nav-shell">
        <a href="home.php" class="brand"><span class="brand-mark"><i class="fa-solid fa-shield-heart"></i></span> PMI Connect</a>
        <nav class="nav-links">
          <a href="home.php" class="active">Home</a>
          <a href="about-pmi.html">About</a>
          <a href="event.html">Event</a>
          <a href="saran.html">Saran</a>
          <a href="contact.html">Kontak</a>
          <?php if ($currentUser): ?>
            <a href="profile.php">Profile</a>
            <a href="auth/logout.php" class="logout-link" onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
          <?php else: ?>
            <a href="login.html">Login</a>
            <a href="register.html">Sign Up</a>
          <?php endif; ?>
        </nav>
        <div class="nav-actions">
          <!-- User Info Dropdown (if logged in) -->
          <?php if ($currentUser): ?>
            <div class="user-menu">
              <button class="icon-btn" id="userMenuToggle" aria-label="User menu">
                <i class="fa-solid fa-user-circle"></i>
              </button>
              <div class="dropdown-menu" id="userDropdown" style="display: none;">
                <a href="profile.php" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px;">
                  <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($currentUser['username']); ?>
                </a>
                <a href="auth/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; border-top: 1px solid #e5e7eb;">
                  <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
              </div>
            </div>
          <?php endif; ?>
          
          <button class="icon-btn" id="themeToggle" aria-label="Toggle dark mode"><i class="fa-regular fa-moon"></i></button>
          <button class="icon-btn mobile-toggle" aria-label="Toggle nav"><i class="fa-solid fa-bars"></i></button>
        </div>
      </div>
    </header>

    <main>
      <section class="hero">
        <div class="container hero-grid">
          <div data-aos="fade-right">
            <span class="badge">Portal Resmi Palang Merah Indonesia</span>
            <h1>Selamat Datang<?php if ($currentUser) echo " di PMI Connect, " . htmlspecialchars($currentUser['username']); ?>! 👋</h1>
            <p>PMI Connect merupakan website resmi yang menyediakan informasi kegiatan Palang Merah Indonesia, pelayanan kemanusiaan, pendaftaran kegiatan, donor darah, relawan, edukasi kesehatan, serta informasi sosial yang dapat diakses oleh seluruh masyarakat Indonesia.</p>
            <div class="hero-actions">
              <a class="btn btn-primary" href="event.html"><i class="fa-solid fa-calendar-days"></i> Lihat Event</a>
              <a class="btn btn-outline" href="about-pmi.html"><i class="fa-solid fa-circle-info"></i> Tentang PMI</a>
            </div>
          </div>
          <div class="hero-visual" data-aos="fade-left">
            <div class="glass" style="padding:32px;"><div style="display:grid;place-items:center;min-height:300px;"><div class="blood-drop"><svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><defs><filter id="glow"><feGaussianBlur stdDeviation="2" result="coloredBlur"/><feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge></filter></defs><path d="M100,30 C100,30 70,70 70,90 C70,110 85,130 100,140 C115,130 130,110 130,90 C130,70 100,30 100,30 Z" fill="url(#grad1)" filter="url(#glow)" style="filter:drop-shadow(0 8px 16px rgba(193,18,31,0.3))"/><defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#c1121f;stop-opacity:1"/><stop offset="100%" style="stop-color:#ff6b6b;stop-opacity:1"/></linearGradient></defs></svg></div></div><div style="display:flex;justify-content:center;margin-top:16px;gap:8px;"><span class="heartbeat"><i class="fa-solid fa-heart"></i> Bergabunglah Kami</span></div></div></div>
          </div>
        </div>
      </section>

      <!-- Donor Darah Section -->
      <section style="background: linear-gradient(135deg, rgba(193, 18, 31, 0.05), rgba(255, 107, 107, 0.05)); border-top: 2px solid rgba(193, 18, 31, 0.1); border-bottom: 2px solid rgba(193, 18, 31, 0.1);">
        <div class="container">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div data-aos="fade-right">
              <h2 style="font-size: 2.2rem; margin-bottom: 16px;">
                <i class="fa-solid fa-droplet" style="color: #c1121f; margin-right: 8px;"></i>
                Program Donor Darah
              </h2>
              <p style="color: var(--muted); font-size: 1.05rem; margin-bottom: 16px;">Darah Anda bisa menyelamatkan 3 nyawa! Jadilah bagian dari gerakan kemanusiaan dengan menjadi pendonor tetap.</p>
              <ul class="list-check" style="margin-bottom: 20px;">
                <li>Proses donor yang aman dan higienis</li>
                <li>Pemeriksaan kesehatan gratis</li>
                <li>Sertifikat pendonor</li>
                <li>Jadwal donor fleksibel</li>
              </ul>
              <a href="donor.html" class="btn btn-primary"><i class="fa-solid fa-hand-holding-heart"></i> Jadilah Pendonor</a>
            </div>
            <div data-aos="fade-left">
              <div class="glass" style="padding: 32px; border-radius: 32px;">
                <div style="text-align: center;">
                  <div style="display: inline-flex; align-items: center; justify-content: center; width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, rgba(193, 18, 31, 0.2), rgba(255, 107, 107, 0.2)); margin-bottom: 20px;">
                    <i class="fa-solid fa-droplet" style="font-size: 2.5rem; color: #c1121f;"></i>
                  </div>
                  <h3 style="margin-bottom: 12px;">BloodConnect</h3>
                  <p style="color: var(--muted); margin-bottom: 20px;">Sistem pencarian pendonor darurat terintegrasi dengan sistem PMI</p>
                  <a href="donor.html" class="btn btn-outline"><i class="fa-solid fa-search"></i> Cari Pendonor</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Features Section -->
      <section>
        <div class="container">
          <div class="section-title" data-aos="fade-up">
            <h2>Layanan PMI Connect</h2>
            <p>Berbagai layanan kemanusiaan dan informasi yang kami sediakan untuk masyarakat.</p>
          </div>
          <div class="feature-grid">
            <article class="card" data-aos="fade-up">
              <div class="icon-wrap"><i class="fa-solid fa-calendar-check"></i></div>
              <h3>Pendaftaran Event</h3>
              <p>Daftar dan ikuti berbagai event yang diselenggarakan oleh PMI di seluruh Indonesia.</p>
            </article>
            <article class="card" data-aos="fade-up" data-aos-delay="80">
              <div class="icon-wrap"><i class="fa-solid fa-book"></i></div>
              <h3>Edukasi Kesehatan</h3>
              <p>Pelajari informasi kesehatan dan pertolongan pertama dari ahli PMI.</p>
            </article>
            <article class="card" data-aos="fade-up" data-aos-delay="160">
              <div class="icon-wrap"><i class="fa-solid fa-people-group"></i></div>
              <h3>Relawan</h3>
              <p>Bergabunglah dengan ribuan relawan PMI untuk memberikan dampak positif.</p>
            </article>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section style="background: linear-gradient(135deg, #c1121f, #ff6b6b); color: white; padding: 80px 20px;">
        <div class="container" style="text-align: center;">
          <h2 style="font-size: 2.2rem; margin-bottom: 16px;">Bergabunglah dengan PMI Connect</h2>
          <p style="font-size: 1.1rem; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto;">Jadilah bagian dari gerakan kemanusiaan terbesar di Indonesia dan selamatkan nyawa bersama kami.</p>
          <?php if (!$currentUser): ?>
            <a href="register.html" class="btn" style="background: white; color: #c1121f; padding: 12px 28px;"><i class="fa-solid fa-user-plus"></i> Daftar Sekarang</a>
          <?php else: ?>
            <p style="opacity: 0.9;"><i class="fa-solid fa-check-circle"></i> Anda sudah terdaftar! Nikmati semua fitur PMI Connect.</p>
          <?php endif; ?>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container footer-grid">
        <div>
          <h3 style="color:white;margin-bottom:12px;"><span class="brand-mark" style="display:inline-flex;margin-right:8px;"><i class="fa-solid fa-shield-heart"></i></span> PMI Connect</h3>
          <p>Portal resmi Palang Merah Indonesia untuk informasi kegiatan, event, donor darah, dan layanan kemanusiaan.</p>
        </div>
        <div>
          <h4 style="color:white;margin-bottom:12px;">Navigasi</h4>
          <ul class="list-check" style="color:#d1d5db;"><li><a href="home.php" style="color:#d1d5db;">Home</a></li><li><a href="about-pmi.html" style="color:#d1d5db;">About PMI</a></li><li><a href="event.html" style="color:#d1d5db;">Event</a></li><li><a href="contact.html" style="color:#d1d5db;">Contact</a></li></ul>
        </div>
        <div>
          <h4 style="color:white;margin-bottom:12px;">Kontak</h4>
          <p style="color:#d1d5db;">Email: info@pmiconnect.org</p>
          <p style="color:#d1d5db;">Telepon: +62 (0)21 123-4567</p>
          <p style="color:#d1d5db;">Alamat: Jl. Jenderal Gatot Subroto No. 40, Jakarta</p>
        </div>
      </div>
    </footer>

    <!-- Scripts -->
    <script src="js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init({ duration: 800, once: true, offset: 80 });

      // User menu toggle
      const userMenuToggle = document.getElementById('userMenuToggle');
      const userDropdown = document.getElementById('userDropdown');
      
      if (userMenuToggle && userDropdown) {
        userMenuToggle.addEventListener('click', function() {
          if (userDropdown.style.display === 'none') {
            userDropdown.style.display = 'block';
          } else {
            userDropdown.style.display = 'none';
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!e.target.closest('.user-menu')) {
            userDropdown.style.display = 'none';
          }
        });
      }
    </script>
  </body>
</html>