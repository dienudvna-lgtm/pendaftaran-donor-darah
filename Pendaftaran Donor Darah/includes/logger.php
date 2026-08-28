<?php
function write_log($message, $type = 'INFO') {
    $logFile = __DIR__ . '/../logs/activity.log';
    $timestamp = date('Y-m-d H:i:s');
    $line = "[$timestamp] [$type] $message" . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND);
}