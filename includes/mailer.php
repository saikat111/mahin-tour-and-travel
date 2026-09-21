<?php
/**
 * Mahin Travel & Tours - Master Authenticated SMTP Mailer
 * Zero-framework, high-reliability native PHP socket SMTP client supporting SSL/TLS
 * Specifically configured for cPanel mail server (mail.mahintravelandtours.com:465).
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

class SMTPMailer {
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private string $secure;
    private int $timeout;
    private string $lastError = '';

    public function __construct(
        ?string $host = null,
        ?int $port = null,
        ?string $user = null,
        ?string $pass = null,
        ?string $secure = null,
        int $timeout = 8
    ) {
        $this->host = $host ?? (function_exists('getSetting') ? getSetting('smtp_host', SMTP_HOST) : SMTP_HOST);
        $this->port = $port ?? (int)(function_exists('getSetting') ? getSetting('smtp_port', (string)SMTP_PORT) : SMTP_PORT);
        $this->user = $user ?? (function_exists('getSetting') ? getSetting('smtp_user', SMTP_USER) : SMTP_USER);
        $this->pass = $pass ?? (function_exists('getSetting') ? getSetting('smtp_pass', SMTP_PASS) : SMTP_PASS);
        $this->secure = strtolower($secure ?? (function_exists('getSetting') ? getSetting('smtp_secure', SMTP_SECURE) : SMTP_SECURE));
        $this->timeout = $timeout;
    }

    public function getLastError(): string {
        return $this->lastError;
    }

    /**
     * Send an HTML email via Authenticated SMTP Socket
     */
    public function send(
        string $to,
        string $subject,
        string $htmlMessage,
        ?string $replyTo = null,
        ?string $fromEmail = null,
        ?string $fromName = null
    ): bool {
        $fromEmail = $fromEmail ?? (function_exists('getSetting') ? getSetting('smtp_from_email', SMTP_FROM_EMAIL) : SMTP_FROM_EMAIL);
        $fromName = $fromName ?? (function_exists('getSetting') ? getSetting('smtp_from_name', SMTP_FROM_NAME) : SMTP_FROM_NAME);

        $to = trim($to);
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->lastError = "Invalid recipient email format: {$to}";
            error_log("SMTPMailer error: {$this->lastError}");
            return false;
        }

        // Connect using SSL socket prefix when port is 465
        $socketPrefix = ($this->secure === 'ssl' || $this->port === 465) ? 'ssl://' : '';
        $remoteAddress = $socketPrefix . $this->host . ':' . $this->port;

        $ctx = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            ]
        ]);

        $errno = 0;
        $errstr = '';
        $socket = @stream_socket_client(
            $remoteAddress,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $ctx
        );

        if (!$socket) {
            $this->lastError = "Failed to connect to SMTP server ({$remoteAddress}): {$errstr} ({$errno})";
            error_log("SMTPMailer connection failure: {$this->lastError}");
            return false;
        }

        stream_set_timeout($socket, $this->timeout);

        try {
            // 1. Initial Service Banner
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '220') {
                throw new Exception("Unexpected server banner: {$res}");
            }

            // 2. EHLO Handshake
            $clientHost = $_SERVER['SERVER_NAME'] ?? 'mahintravelandtours.com';
            $this->sendCommand($socket, "EHLO {$clientHost}");
            $res = $this->readResponse($socket);

            // 3. STARTTLS (if TLS requested)
            if ($this->secure === 'tls' && $this->port !== 465) {
                $this->sendCommand($socket, "STARTTLS");
                $res = $this->readResponse($socket);
                if (substr($res, 0, 3) !== '220') {
                    throw new Exception("STARTTLS command rejected: {$res}");
                }
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new Exception("Failed to establish TLS encryption handshake");
                }
                $this->sendCommand($socket, "EHLO {$clientHost}");
                $this->readResponse($socket);
            }

            // 4. AUTH LOGIN
            $this->sendCommand($socket, "AUTH LOGIN");
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '334') {
                throw new Exception("AUTH LOGIN rejected: {$res}");
            }

            // Send Base64 Username
            $this->sendCommand($socket, base64_encode($this->user));
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '334') {
                throw new Exception("Username rejected by server: {$res}");
            }

            // Send Base64 Password
            $this->sendCommand($socket, base64_encode($this->pass));
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '235') {
                throw new Exception("Authentication failed: {$res}");
            }

            // 5. Envelope Sender (MAIL FROM)
            $this->sendCommand($socket, "MAIL FROM:<{$fromEmail}>");
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '250') {
                throw new Exception("MAIL FROM rejected: {$res}");
            }

            // 6. Envelope Recipient (RCPT TO)
            $this->sendCommand($socket, "RCPT TO:<{$to}>");
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '250' && substr($res, 0, 3) !== '251') {
                throw new Exception("RCPT TO rejected for {$to}: {$res}");
            }

            // 7. Data Transfer Phase (DATA)
            $this->sendCommand($socket, "DATA");
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '354') {
                throw new Exception("DATA command rejected: {$res}");
            }

            // 8. Headers & Body
            $date = date('r');
            $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

            $headers = [];
            $headers[] = "Date: {$date}";
            $headers[] = "From: {$encodedFromName} <{$fromEmail}>";
            $headers[] = "To: <{$to}>";
            if (!empty($replyTo) && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $headers[] = "Reply-To: <{$replyTo}>";
            }
            $headers[] = "Subject: {$encodedSubject}";
            $headers[] = "Message-ID: <" . md5(uniqid((string)microtime(), true)) . "@mahintravelandtours.com>";
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-Type: text/html; charset=UTF-8";
            $headers[] = "Content-Transfer-Encoding: 8bit";
            $headers[] = "X-Mailer: MahinTravelAuthenticatedSMTP/2.0";

            $rawMessage = implode("\r\n", $headers) . "\r\n\r\n" . $htmlMessage . "\r\n.\r\n";

            fwrite($socket, $rawMessage);
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '250') {
                throw new Exception("Message content rejected by server: {$res}");
            }

            // 9. Graceful Disconnect (QUIT)
            $this->sendCommand($socket, "QUIT");
            @fclose($socket);
            return true;

        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("SMTPMailer error: " . $this->lastError);
            @$this->sendCommand($socket, "QUIT");
            @fclose($socket);
            return false;
        }
    }

    private function sendCommand($socket, string $cmd): void {
        fwrite($socket, $cmd . "\r\n");
    }

    private function readResponse($socket): string {
        $response = '';
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return trim($response);
    }
}

/**
 * Helper: Send New Inquiry Alert to Staff / Admin
 */
function sendLeadNotificationToAdmin(array $data): bool {
    $recipient = function_exists('getSetting') ? getSetting('mail_notification_recipient', MAIL_NOTIFICATION_RECIPIENT) : MAIL_NOTIFICATION_RECIPIENT;
    $clientName = e($data['name'] ?? 'Prospective Client');
    $clientPhone = e($data['phone'] ?? 'N/A');
    $clientEmail = e($data['email'] ?? 'Not provided');
    $serviceType = e($data['service_type'] ?? 'General Consultation');
    $subject = "New Inquiry: {$serviceType} - {$clientName}";
    $messageBody = nl2br(e($data['message'] ?? ''));
    $clientIp = e($data['ip_address'] ?? 'Unknown');
    $timestamp = date('d M Y, h:i A');

    $cleanPhone = preg_replace('/[^0-9]/', '', $clientPhone);
    $waLink = "https://wa.me/{$cleanPhone}?text=" . urlencode("Hello {$clientName}! Thank you for contacting Mahin Travel & Tours regarding {$serviceType}.");

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #F4EFEB; margin: 0; padding: 20px; color: #0F1B2B; }
    .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #0A2240; padding: 24px 30px; text-align: center; color: #FFFFFF; }
    .header h1 { margin: 0; font-size: 20px; color: #DFB892; letter-spacing: 0.05em; }
    .header p { margin: 4px 0 0 0; font-size: 13px; color: #A0B2C6; }
    .body-content { padding: 30px; }
    .badge { display: inline-block; background: rgba(185,139,98,0.15); color: #B98B62; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; margin-bottom: 16px; }
    .lead-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .lead-table td { padding: 10px 12px; border-bottom: 1px solid #ECE7E1; font-size: 14px; }
    .lead-table td.label { width: 35%; color: #5A6878; font-weight: 600; }
    .message-box { background: #FDFBF7; border: 1px solid #DFB892; border-radius: 8px; padding: 16px; font-size: 14px; line-height: 1.6; margin-bottom: 24px; }
    .actions { display: flex; gap: 10px; text-align: center; }
    .btn { display: inline-block; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; }
    .btn-wa { background: #25D366; color: #FFFFFF !important; }
    .btn-email { background: #0A2240; color: #FFFFFF !important; }
    .footer { background: #F4EFEB; padding: 16px; text-align: center; font-size: 12px; color: #8A98A8; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>MAHIN TRAVEL & TOURS</h1>
        <p>New Client Consultation Request</p>
    </div>
    <div class="body-content">
        <span class="badge">{$serviceType}</span>
        <h2 style="margin: 0 0 16px 0; font-size: 18px; color: #0A2240;">A prospective client submitted an inquiry:</h2>
        
        <table class="lead-table">
            <tr><td class="label">Client Name:</td><td><strong>{$clientName}</strong></td></tr>
            <tr><td class="label">Phone / WhatsApp:</td><td><strong>{$clientPhone}</strong></td></tr>
            <tr><td class="label">Email Address:</td><td>{$clientEmail}</td></tr>
            <tr><td class="label">Service Required:</td><td><strong>{$serviceType}</strong></td></tr>
            <tr><td class="label">Submission Time:</td><td>{$timestamp}</td></tr>
            <tr><td class="label">Client IP:</td><td>{$clientIp}</td></tr>
        </table>

        <div style="font-weight: 600; font-size: 13px; color: #0A2240; margin-bottom: 6px;">Client Requirements / Message:</div>
        <div class="message-box">
            {$messageBody}
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{$waLink}" class="btn btn-wa" target="_blank">Connect via WhatsApp</a>
        </div>
    </div>
    <div class="footer">
        Mahin Travel & Tours &bull; Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh<br>
        This automated alert was generated via mahintravelandtours.com authenticated mail server.
    </div>
</div>
</body>
</html>
HTML;

    $mailer = new SMTPMailer();
    $replyTo = (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) ? $data['email'] : null;
    return $mailer->send($recipient, $subject, $html, $replyTo);
}

/**
 * Helper: Send Confirmation Email to Client
 */
function sendClientConfirmationEmail(array $data): bool {
    $clientEmail = trim($data['email'] ?? '');
    if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $clientName = e($data['name'] ?? 'Valued Client');
    $serviceType = e($data['service_type'] ?? 'Travel Consultation');
    $subject = "Thank You for Contacting Mahin Travel & Tours";

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #F4EFEB; margin: 0; padding: 20px; color: #0F1B2B; }
    .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #0A2240; padding: 28px 30px; text-align: center; color: #FFFFFF; }
    .header h1 { margin: 0; font-size: 22px; color: #DFB892; letter-spacing: 0.05em; }
    .header p { margin: 4px 0 0 0; font-size: 13px; color: #C0D0E4; }
    .body-content { padding: 30px; line-height: 1.7; font-size: 15px; }
    .highlight-card { background: #FDFBF7; border-left: 4px solid #B98B62; padding: 16px; margin: 20px 0; border-radius: 4px; }
    .footer { background: #F4EFEB; padding: 20px; text-align: center; font-size: 12px; color: #5A6878; line-height: 1.6; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>MAHIN TRAVEL & TOURS</h1>
        <p>Your Trusted Gateway to the World</p>
    </div>
    <div class="body-content">
        <p>Dear <strong>{$clientName}</strong>,</p>
        <p>Thank you for reaching out to <strong>Mahin Travel & Tours</strong>. We have successfully received your inquiry regarding <strong>{$serviceType}</strong>.</p>
        
        <div class="highlight-card">
            <strong>What happens next?</strong><br>
            Our Gazipur travel consultants are currently reviewing your request. We will connect with you via phone or WhatsApp shortly to provide verified document requirements, current embassy processing times, and tailored pricing.
        </div>

        <p>If you require immediate assistance or prefer to visit us in person, you can reach us directly:</p>
        <ul>
            <li><strong>Phone:</strong> +8801924713765 &nbsp;|&nbsp; +8801722203033</li>
            <li><strong>WhatsApp:</strong> +8801924713765</li>
            <li><strong>Office Address:</strong> Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh</li>
        </ul>

        <p style="margin-top: 24px;">Warm regards,<br>
        <strong>Mahin Travel & Tours Consultancy Team</strong><br>
        <span style="font-size: 13px; color: #8A98A8;">Gazipur, Bangladesh</span></p>
    </div>
    <div class="footer">
        &copy; Mahin Travel & Tours. All rights reserved.<br>
        Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh
    </div>
</div>
</body>
</html>
HTML;

    $mailer = new SMTPMailer();
    return $mailer->send($clientEmail, $subject, $html);
}
