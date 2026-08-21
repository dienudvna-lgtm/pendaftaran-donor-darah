<?php
/**
 * Process Login Form
 * Handle user login
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
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email)) {
        $response['errors']['email'] = 'Email tidak boleh kosong.';
    } elseif (!isValidEmail($email)) {
        $response['errors']['email'] = 'Format email tidak valid.';
    }
    
    if (empty($password)) {
        $response['errors']['password'] = 'Password tidak boleh kosong.';
    }
    
    // If validation passed, find account and check credentials
    if (empty($response['errors'])) {
        try {
            $user = findUserByEmail($email);
            
            if ($user === null) {
                $response['errors']['email'] = 'Email belum terdaftar.';
            } else {
                // Verify password using password_verify() against hashed password
                if (!password_verify($password, $user['password'])) {
                    $response['errors']['password'] = 'Password yang Anda masukkan salah.';
                } else {
                    // Password correct -> Set PHP Session
                    setUserSession($user['id'], $user['email'], $user['username']);
                    
                    // Log login activity if database is active
                    global $conn;
                    if ($conn) {
                        try {
                            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
                            $log_stmt = $conn->prepare("INSERT INTO login_logs (user_id, ip_address) VALUES (?, ?)");
                            if ($log_stmt) {
                                $log_stmt->bind_param("is", $user['id'], $ip_address);
                                $log_stmt->execute();
                            }
                        } catch (Exception $e) {
                            // Non-blocking log error
                        }
                    }
                    
                    $response['success'] = true;
                    $response['message'] = 'Login berhasil!';
                    $response['redirect'] = 'home.php';
                    $response['user'] = [
                        'username' => $user['username'],
                        'email' => $user['email']
                    ];
                }
            }
        } catch (Exception $e) {
            $response['errors']['general'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);


