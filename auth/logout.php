<?php
/**
 * Logout Process
 * Handle user logout
 */

require_once __DIR__ . '/../config/database.php';

// Destroy session
if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    session_unset();
    session_destroy();
}

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout - PMI Connect</title>
    <script>
        localStorage.removeItem('bloodconnect-auth');
        localStorage.removeItem('bloodconnect-user');
        localStorage.removeItem('bloodconnect-user-profile');
        window.location.href = '../login.html';
    </script>
</head>
<body style="font-family: sans-serif; display: grid; place-items: center; min-height: 100vh; background: #fafafa;">
    <p>Sedang keluar... <a href="../login.html">Klik di sini jika tidak otomatis dialihkan</a>.</p>
</body>
</html>

