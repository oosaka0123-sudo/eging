<?php
require dirname(__DIR__) . '/_runtime.php';
eging_require_bootstrap();
require_admin();

$schemaFile = eging_schema_file();
$message = '';
$error = '';

$requiredTables = ['articles','gear_items','simulator_rules','contact_messages'];

function table_exists(PDO $pdo, string $table): bool {
    $stmt = $pdo->prepare('SHOW TABLES LIKE ?');
    $stmt->execute([$table]);
    return (bool)$stmt->fetchColumn();
}

function column_exists(PDO $pdo, string $table, string $column): bool {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    return (bool)$stmt->fetchColumn();
}

function ensure_simulator_columns(PDO $pdo): void {
    if (!table_exists($pdo, 'simulator_rules')) {
        return;
    }
    $columns = [
        'tide_phase' => "ALTER TABLE simulator_rules ADD COLUMN tide_phase VARCHAR(40) NULL AFTER current_band",
        'time_of_day' => "ALTER TABLE simulator_rules ADD COLUMN time_of_day VARCHAR(40) NULL AFTER target_size",
    ];
    foreach ($columns as $column => $sql) {
        if (!column_exists($pdo, 'simulator_rules', $column)) {
            $pdo->exec($sql);
        }
    }
}

$status = [];
foreach ($requiredTables as $table) {
    $status[$table] = table_exists($pdo, $table);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf((string)($_POST['csrf'] ?? ''));

    if (!is_file($schemaFile)) {
        $error = 'schema.sql がサーバーにありません。';
    } else {
        try {
            $sql = file_get_contents($schemaFile);
            if ($sql === false) {
                throw new RuntimeException('schema.sql を読み込めません。');
            }
            $statements = preg_split('/;\s*(?:\r?\n|$)/', $sql, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if ($statement !== '') {
                    $pdo->exec($statement);
                }
            }
            ensure_simulator_columns($pdo);
            $message = 'データベース初期化・更新が完了しました。';
            foreach ($requiredTables as $table) {
                $status[$table] = table_exists($pdo, $table);
            }
        } catch (Throwable $e) {
            $error = '初期化に失敗しました。サーバーログを確認してください。';
            error_log('EGING install error: '.$e->getMessage());
        }
    }
}

if (table_exists($pdo, 'simulator_rules')) {
    ensure_simulator_columns($pdo);
}
$allReady = !in_array(false, $status, true)
    && column_exists($pdo, 'simulator_rules', 'tide_phase')
    && column_exists($pdo, 'simulator_rules', 'time_of_day');
?>
<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>初期セットアップ</title>
<style>body{font-family:system-ui;background:#f4f5f6;margin:0}main{max-width:760px;margin:auto;padding:20px}.panel{background:#fff;border:1px solid #ddd;border-radius:12px;padding:18px;margin:20px 0}.ok{color:#087f5b}.ng{color:#c92a2a}button{padding:12px 16px;font:inherit}</style></head>
<body><main><?php require __DIR__.'/_nav.php';?><h1>初期セットアップ</h1>
<?php if($message):?><p class="ok"><?=htmlspecialchars($message,ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<?php if($error):?><p class="ng"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<div class="panel"><h2>DB状態</h2><ul>
<?php foreach($status as $table=>$ready):?><li><?=htmlspecialchars($table,ENT_QUOTES,'UTF-8')?>：<strong class="<?=$ready?'ok':'ng'?>"><?=$ready?'OK':'未作成'?></strong></li><?php endforeach;?>
</ul></div>
<?php if(!$allReady):?>
<form method="post" class="panel"><input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
<p>既存データを削除せず、存在しないテーブルだけを作成します。</p>
<button type="submit">データベースを初期化</button></form>
<?php else:?><p class="ok">必要なテーブルはすべて準備済みです。</p><?php endif;?>
</main></body></html>