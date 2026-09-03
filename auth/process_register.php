<?php
/**
 * Process Register Form
 * Handle user registration
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/middleware.php';

$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $agree_terms = isset($_POST['agree_terms']) && ($_POST['agree_terms'] === '1' || $_POST['agree_terms'] === 'true' || $_POST['agree_terms'] === 'on');
    
    // Validation
    if (empty($username)) {
        $response['errors']['username'] = 'Username tidak boleh kosong.';
    } elseif (strlen($username) < 4) {
        $response['errors']['username'] = 'Username minimal 4 karakter.';
    }
    
    if (empty($email)) {
        $response['errors']['email'] = 'Email tidak boleh kosong.';
    } elseif (!isValidEmail($email)) {
        $response['errors']['email'] = 'Format email tidak valid.';
    }
    
    if (empty($password)) {
        $response['errors']['password'] = 'Password tidak boleh kosong.';
    } elseif (strlen($password) < 8) {
        $response['errors']['password'] = 'Password minimal 8 karakter.';
    }
    
    if (empty($password_confirm)) {
        $response['errors']['password_confirm'] = 'Konfirmasi password tidak boleh kosong.';
    } elseif ($password !== $password_confirm) {
        $response['errors']['password_confirm'] = 'Password dan konfirmasi password harus sama.';
    }
    
    if (!$agree_terms) {
        $response['errors']['agree_terms'] = 'Anda harus menyetujui syarat dan ketentuan.';
    }
    
    // Check if email already exists
    if (empty($response['errors']['email']) && !empty($email)) {
        $existingEmailUser = findUserByEmail($email);
        if ($existingEmailUser !== null) {
            $response['errors']['email'] = 'Email sudah terdaftar. Silakan gunakan email lain.';
        }
    }
    
    // Check if username already exists
    if (empty($response['errors']['username']) && !empty($username)) {
        $existingUsernameUser = findUserByUsername($username);
        if ($existingUsernameUser !== null) {
            $response['errors']['username'] = 'Username sudah digunakan. Silakan pilih username lain.';
        }
    }
    
    // If no validation errors, create user account
    if (empty($response['errors'])) {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $newUser = saveNewUser($username, $email, $hashed_password);
            
            if ($newUser) {
                $response['success'] = true;
                $response['message'] = 'Registrasi berhasil! Silakan Login.';
                $response['redirect'] = 'login.html';
                $response['user'] = [
                    'username' => $username,
                    'email' => $email
                ];
            } else {
                $response['errors']['general'] = 'Gagal menyimpan data akun. Silakan coba lagi.';
            }
        } catch (Exception $e) {
            $response['errors']['general'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
