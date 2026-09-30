<?php
require dirname(__DIR__) . '/_runtime.php';
eging_require_bootstrap();
require_admin();

function cms_table_exists(PDO $pdo, string $table): bool {
    $stmt = $pdo->prepare('SHOW TABLES LIKE ?');
    $stmt->execute([$table]);
    return (bool)$stmt->fetchColumn();
}

$tables = [
    'articles' => '記事',
    'gear_items' => 'ギア',
    'simulator_rules' => '診断ルール',
    'contact_messages' => 'お問い合わせ',
];

$counts = [];
$missing = [];
foreach ($tables as $table => $label) {
    if (cms_table_exists($pdo, $table)) {
        $counts[$table] = (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    } else {
        $counts[$table] = null;
        $missing[] = $label;
    }
}
?>
<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>CMS</title>
<style>
body{font-family:system-ui;margin:0;background:#f5f6f7;color:#171717}
main{max-width:980px;margin:auto;padding:20px}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.card{display:block;background:#fff;border:1px solid #ddd;border-radius:14px;padding:18px;color:inherit;text-decoration:none}
.card strong{font-size:.9rem}.card b{display:block;font-size:2rem;margin-top:12px}
.notice{background:#fff8db;border:1px solid #ead37a;border-radius:12px;padding:16px;margin:18px 0}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0}
.actions a{padding:10px 14px;background:#111;color:#fff;border-radius:999px;text-decoration:none}
@media(max-width:760px){.grid{grid-template-columns:1fr 1fr}}
@media(max-width:440px){.grid{grid-template-columns:1fr}}
</style></head>
<body><main><?php require __DIR__.'/_nav.php';?><h1>エギングギアラボ CMS</h1>

<?php if($missing):?>
<div class="notice"><strong>初期セットアップが必要です。</strong><p>未作成：<?=htmlspecialchars(implode('・',$missing),ENT_QUOTES,'UTF-8')?></p><a href="/admin/install.php">データベースを初期化する</a></div>
<?php endif;?>

<div class="grid">
<a class="card" href="/admin/articles.php"><strong>記事</strong><b><?=$counts['articles']===null?'—':$counts['articles']?></b></a>
<a class="card" href="/admin/gear.php"><strong>ギア</strong><b><?=$counts['gear_items']===null?'—':$counts['gear_items']?></b></a>
<a class="card" href="/admin/rules.php"><strong>診断ルール</strong><b><?=$counts['simulator_rules']===null?'—':$counts['simulator_rules']?></b></a>
<a class="card" href="/admin/contacts.php"><strong>お問い合わせ</strong><b><?=$counts['contact_messages']===null?'—':$counts['contact_messages']?></b></a>
</div>

<div class="actions"><a href="/admin/install.php">初期セットアップ</a><a href="/" target="_blank" rel="noopener">公開サイトを見る</a></div>
</main></body></html>