<?php
declare(strict_types=1);

function eging_is_https(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https'
        || (string)($_SERVER['SERVER_PORT'] ?? '') === '443';
}

function eging_prepare_private_runtime(string $root): array {
    $var = $root . '/var';
    $sessions = $var . '/sessions';
    $rate = $var . '/login-rate';
    foreach ([$var, $sessions, $rate] as $dir) {
        if (!is_dir($dir) && !mkdir($dir, 0700, true) && !is_dir($dir)) {
            error_log('EGING runtime directory unavailable: ' . $dir);
        }
    }
    return [$sessions, $rate];
}

function eging_login_rate_file(string $dir): string {
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    return $dir . '/' . hash('sha256', $ip) . '.json';
}

function eging_login_rate_status(string $dir): array {
    $now = time();
    $data = ['failures'=>0,'window_started'=>$now,'lock_until'=>0];
    $path = eging_login_rate_file($dir);
    if (is_file($path)) {
        $decoded = json_decode((string)file_get_contents($path), true);
        if (is_array($decoded)) $data = array_merge($data, $decoded);
    }
    if ((int)$data['lock_until'] <= $now && $now - (int)$data['window_started'] > 600) {
        $data = ['failures'=>0,'window_started'=>$now,'lock_until'=>0];
    }
    return $data;
}

function eging_login_rate_fail(string $dir): int {
    $path = eging_login_rate_file($dir);
    $now = time();
    $data = eging_login_rate_status($dir);
    $data['failures'] = (int)$data['failures'] + 1;
    if ($data['failures'] >= 5) {
        $data['failures'] = 0;
        $data['window_started'] = $now;
        $data['lock_until'] = $now + 600;
    }
    file_put_contents($path, json_encode($data, JSON_UNESCAPED_SLASHES), LOCK_EX);
    return (int)$data['lock_until'];
}

function eging_login_rate_clear(string $dir): void {
    $path = eging_login_rate_file($dir);
    if (is_file($path)) @unlink($path);
}
