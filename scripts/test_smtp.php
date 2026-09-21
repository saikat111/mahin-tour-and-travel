<?php
$host = 'ssl://mail.mahintravelandtours.com';
$port = 465;
$ctx = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
]);
$errno = 0;
$errstr = '';
echo "Connecting to $host:$port...\n";
$fp = @stream_socket_client("$host:$port", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $ctx);
if (!$fp) {
    echo "Connection failed: $errstr ($errno)\n";
    exit(1);
}
echo "Connected!\n";
$banner = fgets($fp, 512);
echo "Banner: $banner\n";

function sendCmd($fp, $cmd) {
    fwrite($fp, $cmd . "\r\n");
    $res = '';
    while ($str = fgets($fp, 512)) {
        $res .= $str;
        if (substr($str, 3, 1) === ' ') break;
    }
    return $res;
}

echo "EHLO: " . sendCmd($fp, "EHLO localhost");
echo "AUTH LOGIN: " . sendCmd($fp, "AUTH LOGIN");
echo "USER: " . sendCmd($fp, base64_encode("support@mahintravelandtours.com"));
echo "PASS: " . sendCmd($fp, base64_encode("zzAD%1G}IeGz8l5J"));
echo "QUIT: " . sendCmd($fp, "QUIT");
fclose($fp);
