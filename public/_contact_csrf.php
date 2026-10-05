<?php
declare(strict_types=1);

function eging_contact_csrf_token(): string {
    $token = (string)($_COOKIE['eging_contact_csrf'] ?? '');
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        $token = bin2hex(random_bytes(32));
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https'
            || (string)($_SERVER['SERVER_PORT'] ?? '') === '443';
        setcookie('eging_contact_csrf', $token, [
            'expires' => time() + 7200,
            'path' => '/contact.php',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
    }
    return $token;
}

function eging_verify_contact_csrf(string $expected, string $provided): bool {
    return $expected !== '' && $provided !== '' && hash_equals($expected, $provided);
}
