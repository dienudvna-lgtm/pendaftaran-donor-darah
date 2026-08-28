<?php
/**
 * Simple .env loader (tanpa Composer/library eksternal)
 * Membaca file .env di root project dan memasukkan nilainya ke $_ENV
 */

function loadEnv($path) {
    if (!file_exists($path)) {
        return; // kalau .env tidak ada, biarkan pakai nilai default di database.php
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue; // skip baris kosong / komentar
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        $value = trim($value, "\"'"); // buang tanda kutip kalau ada

        if (!array_key_exists($key, $_ENV)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}
