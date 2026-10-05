<?php
declare(strict_types=1);

ini_set('display_errors', '0');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Tokyo');

$projectRoot = dirname(__DIR__);
require_once __DIR__ . '/security.php';
[$sessionDir, $loginRateDir] = eging_prepare_private_runtime($projectRoot);

$configFile = $projectRoot . '/config/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Application is not configured.');
}
$config = require $configFile;

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
    $config['db']['host'],
    $config['db']['port'] ?? 3306,
    $config['db']['name']
);
try {
    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $pdo->exec("SET time_zone = '+09:00'");
} catch (PDOException $e) {
    error_log('EGING database connection failed.');
    throw new RuntimeException('Database unavailable.', 0, $e);
}

$isAdminRequest = str_starts_with((string)($_SERVER['REQUEST_URI'] ?? ''), '/admin/');

if ($isAdminRequest && session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    if (is_dir($sessionDir) && is_writable($sessionDir)) {
        session_save_path($sessionDir);
    }
    session_name('eging_admin');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => eging_is_https(),
        'samesite' => 'Strict',
        'path' => '/',
    ]);
    session_start();
}

if ($isAdminRequest) {
    header('Cache-Control: no-store, private');
    header('Pragma: no-cache');
}

function require_admin(): void {
    if (empty($_SESSION['admin_authenticated'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(string $token): void {
    $stored = (string)($_SESSION['csrf'] ?? '');
    if ($stored === '' || $token === '' || !hash_equals($stored, $token)) {
        http_response_code(403);
        exit('Invalid CSRF token.');
    }
}
