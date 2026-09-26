<?php
require dirname(__DIR__, 2) . '/app/bootstrap.php';

if (!empty($_SESSION['admin_authenticated'])) {
    header('Location: /admin/');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf((string)($_POST['csrf'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (password_verify($password, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        header('Location: /admin/');
        exit;
    }
    $error = 'ログイン情報を確認してください。';
}
?>
<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CMS Login</title></head>
<body><main style="max-width:420px;margin:40px auto;font-family:system-ui;padding:20px">
<h1>エギングギアラボ CMS</h1>
<?php if ($error): ?><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
<label>パスワード<input name="password" type="password" required autocomplete="current-password" style="display:block;width:100%;padding:12px;margin-top:8px"></label>
<button style="margin-top:16px;padding:12px;width:100%">ログイン</button></form>
</main></body></html>
