<?php
require dirname(__DIR__) . '/_runtime.php';

try {
    $configFile = eging_config_file();
} catch (Throwable $e) {
    $configFile = '';
}
if ($configFile === '' || !is_file($configFile)) {
    http_response_code(503);
    header('Cache-Control: no-store, private');
    header('Pragma: no-cache');
    ?><!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>CMS Setup Required</title></head>
    <body><main style="max-width:520px;margin:40px auto;font-family:system-ui;padding:20px">
    <h1>エギングギアラボ CMS</h1>
    <p>現在、管理機能の初期設定中です。公開サイトは通常どおり利用できます。</p>
    </main></body></html><?php
    exit;
}

try {
    eging_require_bootstrap();
} catch (Throwable $e) {
    error_log('EGING admin bootstrap unavailable.');
    http_response_code(503);
    header('Cache-Control: no-store, private');
    header('Pragma: no-cache');
    ?><!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>CMS Unavailable</title></head>
    <body><main style="max-width:520px;margin:40px auto;font-family:system-ui;padding:20px">
    <h1>エギングギアラボ CMS</h1>
    <p>現在、管理機能を利用できません。時間をおいて再度お試しください。</p>
    </main></body></html><?php
    exit;
}

if (!empty($_SESSION['admin_authenticated'])) {
    header('Location: /admin/');
    exit;
}

$error = '';
$now = time();
$rate = eging_login_rate_status($loginRateDir);
$lockUntil = (int)$rate['lock_until'];

if ($lockUntil > $now) {
    http_response_code(429);
    $error = 'ログイン試行回数が多すぎます。しばらくしてから再度お試しください。';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf((string)($_POST['csrf'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (password_verify($password, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        eging_login_rate_clear($loginRateDir);
        header('Location: /admin/');
        exit;
    }

    $lockUntil = eging_login_rate_fail($loginRateDir);
    if ($lockUntil > $now) {
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