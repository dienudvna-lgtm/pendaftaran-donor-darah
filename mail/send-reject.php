<?php

require_once __DIR__ . '/common.php';

header('Content-Type: application/json; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request format.']);
    exit;
}

$required = ['id', 'full-name', 'email'];
foreach ($required as $field) {
    if (empty($body[$field])) {
        echo json_encode(['success' => false, 'message' => "Field {$field} is required."]);
        exit;
    }
}


$subject = 'BloodConnect - Registration Status';
$message = "Hello {$body['full-name']},<br><br>Thank you for registering.<br><br>Unfortunately your registration cannot be approved for the selected schedule.<br><br>Please register again for the next donation event.";
$details = [
    'Name' => $body['full-name'],
];
$htmlBody = buildEmailTemplate($subject, $message, $details);

$result = sendEmail($body['email'], $body['full-name'], $subject, $htmlBody);
if ($result !== true) {
    echo json_encode(['success' => false, 'message' => 'Failed to send confirmation email.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Confirmation email has been sent successfully.']);
