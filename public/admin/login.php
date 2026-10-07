<?php
require dirname(__DIR__) . '/_runtime.php';
eging_require_bootstrap();

if (!empty($_SESSION['admin_authenticated'])) {
    header('Location: /admin/');
    exit;
}

$error = '';
$pdo->exec("CREATE TABLE IF NOT EXISTS admin_login_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_hash CHAR(64) NOT NULL,
    attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX(ip_hash, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
$ipHash = hash_hmac('sha256', $ip, (string)$config['admin_password_hash']);

$cleanup = $pdo->prepare("DELETE FROM admin_login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 DAY)");
$cleanup->execute();

$count = $pdo->prepare("SELECT COUNT(*) FROM admin_login_attempts WHERE ip_hash=? AND attempted_at > (NOW() - INTERVAL 10 MINUTE)");
$count->execute([$ipHash]);
$recentFailures = (int)$count->fetchColumn();
$locked = $recentFailures >= 5;

if ($locked) {
    http_response_code(429);
    $error = 'ログイン試行回数が多すぎます。10分後に再度お試しください。';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf((string)($_POST['csrf'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (password_verify($password, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        $pdo->prepare("DELETE FROM admin_login_attempts WHERE ip_hash=?")->execute([$ipHash]);
        header('Location: /admin/');
        exit;
    }

    $pdo->prepare("INSERT INTO admin_login_attempts(ip_hash) VALUES(?)")->execute([$ipHash]);

    $count->execute([$ipHash]);
    $recentFailures = (int)$count->fetchColumn();
    if ($recentFailures >= 5) {
        http_response_code(429);
        $error = 'ログイン試行回数が多すぎます。10分後に再度お試しください。';
        $locked = true;
    } else {
        usleep(400000);
        $error = 'ログイン情報を確認してください。';
    }
}
?>
<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>CMS Login</title></head>
<body><main style="max-width:420px;margin:40px auto;font-family:system-ui;padding:20px">
<h1>エギングギアラボ CMS</h1>
<?php if ($error): ?><p role="alert"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></p><?php endif; ?>
<?php if (!$locked): ?>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')?>">
<label>パスワード<input name="password" type="password" required autocomplete="current-password" style="display:block;width:100%;padding:12px;margin-top:8px"></label>
<button style="margin-top:16px;padding:12px;width:100%">ログイン</button></form>
<?php endif; ?>
</main></body></html>