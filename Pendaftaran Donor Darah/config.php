<?php
/**
 * BloodConnect SMTP configuration.
 *
 * Update these values with your Gmail SMTP credentials.
 */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-gmail-app-password');
define('SMTP_SENDER_EMAIL', 'your-email@gmail.com');
define('SMTP_SENDER_NAME', 'BloodConnect Committee');

define('MAIL_STORAGE_FILE', __DIR__ . '/mail/registrations.json');
