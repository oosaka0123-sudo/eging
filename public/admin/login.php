<?php
require dirname(__DIR__) . '/_runtime.php';
eging_require_bootstrap();

if (!empty($_SESSION['admin_authenticated'])) {
    header('Location: /admin/');
    exit;
}

$error = '';
$now = time();
$lockUntil = (int)($_SESSION['login_lock_until'] ?? 0);

if ($lockUntil > $now) {
    http_response_code(429);
    $error = 'ログイン試行回数が多すぎます。しばらくしてから再度お試しください。';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf((string)($_POST['csrf'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (password_verify($password, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        unset($_SESSION['login_failures'], $_SESSION['login_lock_until']);
        header('Location: /admin/');
        exit;
    }

    $failures = (int)($_SESSION['login_failures'] ?? 0) + 1;
    $_SESSION['login_failures'] = $failures;

    if ($failures >= 5) {
        $_SESSION['login_lock_until'] = $now + 600;
        $_SESSION['login_failures'] = 0;
        http_response_code(429);
        $error = 'ログイン試行回数が多すぎます。10分後に再度お試しください。';
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
<?php if ($lockUntil <= $now): ?>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')?>">
<label>パスワード<input name="password" type="password" required autocomplete="current-password" style="display:block;width:100%;padding:12px;margin-top:8px"></label>
<button style="margin-top:16px;padding:12px;width:100%">ログイン</button></form>
<?php endif; ?>
</main></body></html>