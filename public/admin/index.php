<?php
require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$counts = [];
foreach (['articles','gear_items','simulator_rules'] as $table) {
    $counts[$table] = (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}
?>
<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CMS</title>
<style>body{font-family:system-ui;margin:0;background:#f5f6f7}main{max-width:900px;margin:auto;padding:20px}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.card{background:#fff;border:1px solid #ddd;border-radius:14px;padding:18px}@media(max-width:640px){.grid{grid-template-columns:1fr}}</style></head>
<body><main><p><a href="/admin/logout.php">ログアウト</a></p><h1>エギングギアラボ CMS</h1>
<div class="grid">
<div class="card"><strong>記事</strong><p><?= $counts['articles'] ?>件</p></div>
<div class="card"><strong>ギア</strong><p><?= $counts['gear_items'] ?>件</p></div>
<div class="card"><strong>診断ルール</strong><p><?= $counts['simulator_rules'] ?>件</p></div>
</div>
<p>次段階で、記事・ギア・診断ルールの編集画面をこの管理画面へ追加します。</p>
</main></body></html>
