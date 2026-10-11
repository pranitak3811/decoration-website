<?php
// ============================================
// EMAIL CONFIGURATION
// ============================================
// Edit these 3 settings with YOUR Gmail details

define('SMTP_USER',   'yourgmail@gmail.com');   // ← Your Gmail address
define('SMTP_PASS',   'xxxx xxxx xxxx xxxx');   // ← Your Gmail App Password (16 chars)
define('OWNER_EMAIL', 'yourgmail@gmail.com');   // ← Where to receive booking alerts

// ============================================
// sendEmail() — sends HTML email via Gmail SMTP
// ============================================
function sendEmail($toEmail, $toName, $subject, $htmlBody) {
    $from     = SMTP_USER;
    $fromName = 'Aura Décor';
    $smtpHost = 'ssl://smtp.gmail.com';
    $smtpPort = 465;
    $smtpUser = SMTP_USER;
    $smtpPass = SMTP_PASS;

    // Build raw MIME email
    $boundary = md5(time());
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $fromName <$from>\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Connect to Gmail SMTP via socket
    $socket = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 15);
    if (!$socket) {
        error_log("Mail Error: Cannot connect to SMTP - $errstr ($errno)");
        return false;
    }

    $read = fgets($socket, 515);

    // EHLO
    fputs($socket, "EHLO localhost\r\n");
    $read = '';
    while (strpos($read, '250 ') === false) $read .= fgets($socket, 515);

    // AUTH LOGIN
    fputs($socket, "AUTH LOGIN\r\n");
    fgets($socket, 515);
    fputs($socket, base64_encode($smtpUser) . "\r\n");
    fgets($socket, 515);
    fputs($socket, base64_encode($smtpPass) . "\r\n");
    $auth = fgets($socket, 515);

    if (strpos($auth, '235') === false) {
        error_log("Mail Error: Authentication failed - $auth");
        fclose($socket);
        return false;
    }

    // MAIL FROM
    fputs($socket, "MAIL FROM: <$from>\r\n");
    fgets($socket, 515);

    // RCPT TO
    fputs($socket, "RCPT TO: <$toEmail>\r\n");
    fgets($socket, 515);

    // DATA
    fputs($socket, "DATA\r\n");
    fgets($socket, 515);

    $msg  = "To: $toName <$toEmail>\r\n";
    $msg .= "Subject: $subject\r\n";
    $msg .= $headers . "\r\n";
    $msg .= $htmlBody . "\r\n.\r\n";

    fputs($socket, $msg);
    fgets($socket, 515);

    // QUIT
    fputs($socket, "QUIT\r\n");
    fclose($socket);

    return true;
}
?>
