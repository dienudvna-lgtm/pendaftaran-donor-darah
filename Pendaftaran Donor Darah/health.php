<?php
/**
 * Health Check Endpoint
 * Mengecek status server & koneksi database
 * Akses: http://localhost:8000/health.php  (atau http://<ip-server>/health.php)
 */

header('Content-Type: application/json');

require_once __DIR__ . '/config/database.php';

$status = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'checks' => [
        'server' => 'up',
        'database' => 'unknown',
    ],
];

// $conn didefinisikan otomatis oleh config/database.php
global $conn;

if ($conn && !$conn->connect_error) {
    $status['checks']['database'] = 'up';
} else {
    $status['checks']['database'] = 'down';
    $status['status'] = 'error';
}

http_response_code($status['status'] === 'ok' ? 200 : 503);
echo json_encode($status, JSON_PRETTY_PRINT);
