<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/PHPMailer.php';
require_once __DIR__ . '/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Load registration data from storage.
 *
 * @return array<int, array<string, mixed>>
 */
function loadRegistrations(): array
{
    if (!file_exists(MAIL_STORAGE_FILE)) {
        return [];
    }

    $content = file_get_contents(MAIL_STORAGE_FILE);
    if ($content === false) {
        return [];
    }

    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/**
 * Save registration data to storage.
 *
 * @param array<int, array<string, mixed>> $registrations
 * @return bool
 */
function saveRegistrations(array $registrations): bool
{
    $json = json_encode($registrations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }

    return file_put_contents(MAIL_STORAGE_FILE, $json) !== false;
}

/**
 * Send a styled HTML email through Gmail SMTP.
 *
 * @param string $recipientEmail
 * @param string $recipientName
 * @param string $subject
 * @param string $htmlBody
 * @return bool|string True on success, error message on failure.
 */
function sendEmail(string $recipientEmail, string $recipientName, string $subject, string $htmlBody)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(SMTP_SENDER_EMAIL, SMTP_SENDER_NAME);
        $mail->addAddress($recipientEmail, $recipientName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Build a branded email HTML template.
 */
function buildEmailTemplate(string $title, string $message, array $details): string
{
    $detailRows = '';
    foreach ($details as $label => $value) {
        $detailRows .= "<tr><td style=\"padding:10px 0; font-weight:700; color:#b71c1c; width:36%;\">{$label}</td><td style=\"padding:10px 0; color:#444;\">{$value}</td></tr>";
    }

    return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{$title}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;background:#f1f1f1;color:#333;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f1f1;padding:24px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 28px 80px rgba(0,0,0,0.12);">
          <tr style="background:#b71c1c;color:#ffffff;">
            <td style="padding:28px 32px;text-align:center;">
              <img src="https://via.placeholder.com/120x40.png?text=BloodConnect" alt="BloodConnect" style="display:block;margin:0 auto 16px;max-width:120px;" />
              <h1 style="margin:0;font-size:24px;letter-spacing:0.5px;">BloodConnect</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:32px;">
              <h2 style="margin-top:0;color:#b71c1c;">{$title}</h2>
              <p style="font-size:16px;line-height:1.75;">{$message}</p>
              <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:20px;border:1px solid #eee;background:#fff;border-radius:14px;">
                <tbody>
                  {$detailRows}
                </tbody>
              </table>
              <p style="margin-top:24px;font-size:15px;line-height:1.7;color:#555;">Mohon hadir 15 menit lebih awal dan bawa kartu identitas Anda.</p>
            </td>
          </tr>
          <tr style="background:#fafafa;">
            <td style="padding:24px 32px;color:#777;font-size:14px;">
              <p style="margin:0;">Terima kasih telah membantu menyelamatkan nyawa.</p>
              <p style="margin:8px 0 0;">Salam,<br />BloodConnect Committee</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
}
