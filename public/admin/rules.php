<?php
require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$choices = [
    'season' => ['spring'=>'春','autumn'=>'秋'],
    'field_type' => ['port'=>'堤防・漁港','rock'=>'磯','surf'=>'サーフ'],
    'depth_band' => ['shallow'=>'浅場','mid'=>'中層','deep'=>'深場'],
    'wind_band' => ['low'=>'弱い','mid'=>'普通','strong'=>'強い'],
    'current_band' => ['slow'=>'緩い','normal'=>'普通','fast'=>'速い'],
    'tide_phase' => ['rising'=>'上げ','falling'=>'下げ','slack'=>'潮止まり'],
    'target_size' => ['small'=>'小型','medium'=>'中型','large'=>'大型'],
    'time_of_day' => ['dawn'=>'朝まずめ','day'=>'日中','dusk'=>'夕まずめ','night'=>'夜'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf((string)($_POST['csrf'] ?? ''));
    $id = (int)($_POST['id'] ?? 0);
    $output = json_encode([
        'rod' => trim((string)($_POST['rod'] ?? '')),
        'egi' => trim((string)($_POST['egi'] ?? '')),
        'pe' => trim((string)($_POST['pe'] ?? '')),
        'leader' => trim((string)($_POST['leader'] ?? '')),
    ], JSON_UNESCAPED_UNICODE);

    $condition = static function(string $name) use ($choices): ?string {
        $value = (string)($_POST[$name] ?? '');
        if ($value === '') return null;
        return isset($choices[$name][$value]) ? $value : null;
    };

    $data = [
        ':priority' => (int)($_POST['priority'] ?? 100),
        ':season' => $condition('season'),
        ':field' => $condition('field_type'),
        ':depth' => $condition('depth_band'),
        ':wind' => $condition('wind_band'),
        ':current' => $condition('current_band'),
        ':tide' => $condition('tide_phase'),
        ':size' => $condition('target_size'),
        ':time' => $condition('time_of_day'),
        ':output' => $output,
        ':rationale' => trim((string)($_POST['rationale'] ?? '')),
        ':source' => trim((string)($_POST['source_note'] ?? '')),
        ':enabled' => isset($_POST['enabled']) ? 1 : 0,
    ];

    if ($id) {
        $data[':id'] = $id;
        $pdo->prepare("UPDATE simulator_rules
            SET priority=:priority,season=:season,field_type=:field,depth_band=:depth,
                wind_band=:wind,current_band=:current,tide_phase=:tide,target_size=:size,
                time_of_day=:time,output=:output,rationale=:rationale,source_note=:source,enabled=:enabled
            WHERE id=:id")->execute($data);
    } else {
        $pdo->prepare("INSERT INTO simulator_rules
            (priority,season,field_type,depth_band,wind_band,current_band,tide_phase,target_size,time_of_day,output,rationale,source_note,enabled)
            VALUES(:priority,:season,:field,:depth,:wind,:current,:tide,:size,:time,:output,:rationale,:source,:enabled)")
            ->execute($data);
    }
    header('Location:/admin/rules.php');
    exit;
}

if (isset($_GET['delete'])) {
    verify_csrf((string)($_GET['token'] ?? ''));
    $pdo->prepare("DELETE FROM simulator_rules WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location:/admin/rules.php');
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare("SELECT * FROM simulator_rules WHERE id=?");
    $s->execute([(int)$_GET['edit']]);
    $edit = $s->fetch();
    if ($edit) $edit['out'] = json_decode($edit['output'], true) ?: [];
}

$rows = $pdo->query("SELECT id,priority,season,field_type,depth_band,wind_band,current_band,tide_phase,target_size,time_of_day,enabled,updated_at
                     FROM simulator_rules ORDER BY priority,id")->fetchAll();

function render_select(string $name, string $label, array $options, ?string $value): void {
    echo '<label>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'<select name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'">';
    echo '<option value="">共通（指定なし）</option>';
    foreach ($options as $key => $text) {
        $selected = $value === $key ? ' selected' : '';
        echo '<option value="'.htmlspecialchars($key, ENT_QUOTES, 'UTF-8').'"'.$selected.'>'.htmlspecialchars($text, ENT_QUOTES, 'UTF-8').'</option>';
    }
    echo '</select></label>';
}
?>
<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>診断ルール管理</title>
<style>
body{font-family:system-ui;background:#f4f5f6;margin:0}main{max-width:1180px;margin:auto;padding:20px}
form,.table-wrap{background:#fff}form{padding:18px;border:1px solid #ddd;border-radius:12px}
.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0 16px}
label{display:grid;gap:6px;margin:10px 0}input,textarea,select{padding:10px;font:inherit}
textarea{min-height:120px}.wide{grid-column:1/-1}.table-wrap{overflow-x:auto;margin-top:20px}
table{width:100%;border-collapse:collapse;min-width:980px}th,td{padding:9px;border-bottom:1px solid #eee;text-align:left;white-space:nowrap}
@media(max-width:700px){.form-grid{grid-template-columns:1fr}}
</style></head><body><main><?php require __DIR__.'/_nav.php';?>
<h1>診断ルール管理</h1>
<form method="post">
<input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
<input type="hidden" name="id" value="<?=(int)($edit['id']??0)?>">
<div class="form-grid">
<label>優先度<input type="number" name="priority" value="<?=htmlspecialchars((string)($edit['priority']??100),ENT_QUOTES,'UTF-8')?>"></label>
<?php render_select('season','季節',$choices['season'],$edit['season']??null); ?>
<?php render_select('field_type','フィールド',$choices['field_type'],$edit['field_type']??null); ?>
<?php render_select('depth_band','水深',$choices['depth_band'],$edit['depth_band']??null); ?>
<?php render_select('wind_band','風',$choices['wind_band'],$edit['wind_band']??null); ?>
<?php render_select('tide_phase','潮の動き',$choices['tide_phase'],$edit['tide_phase']??null); ?>
<?php render_select('target_size','対象サイズ',$choices['target_size'],$edit['target_size']??null); ?>
<?php render_select('time_of_day','時間帯',$choices['time_of_day'],$edit['time_of_day']??null); ?>
<?php render_select('current_band','潮流速度（詳細・任意）',$choices['current_band'],$edit['current_band']??null); ?>
<label>ロッド<input name="rod" value="<?=htmlspecialchars($edit['out']['rod']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>エギ<input name="egi" value="<?=htmlspecialchars($edit['out']['egi']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>PE<input name="pe" value="<?=htmlspecialchars($edit['out']['pe']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>リーダー<input name="leader" value="<?=htmlspecialchars($edit['out']['leader']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label class="wide">理由<textarea required name="rationale"><?=htmlspecialchars($edit['rationale']??'',ENT_QUOTES,'UTF-8')?></textarea></label>
<label class="wide">根拠メモ<textarea name="source_note"><?=htmlspecialchars($edit['source_note']??'',ENT_QUOTES,'UTF-8')?></textarea></label>
<label><span>状態</span><span><input type="checkbox" name="enabled" <?=($edit['enabled']??1)?'checked':''?>> 有効</span></label>
</div>
<button>保存</button>
</form>
<div class="table-wrap"><table>
<tr><th>優先</th><th>季節</th><th>場所</th><th>水深</th><th>風</th><th>潮</th><th>サイズ</th><th>時間</th><th>潮流</th><th>有効</th><th></th></tr>
<?php foreach($rows as $r):?><tr>
<td><?=$r['priority']?></td>
<td><?=htmlspecialchars($choices['season'][$r['season']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['field_type'][$r['field_type']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['depth_band'][$r['depth_band']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['wind_band'][$r['wind_band']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['tide_phase'][$r['tide_phase']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['target_size'][$r['target_size']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['time_of_day'][$r['time_of_day']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=htmlspecialchars($choices['current_band'][$r['current_band']]??'*',ENT_QUOTES,'UTF-8')?></td>
<td><?=$r['enabled']?'ON':'OFF'?></td>
<td><a href="?edit=<?=$r['id']?>">編集</a> / <a href="?delete=<?=$r['id']?>&token=<?=urlencode(csrf_token())?>" onclick="return confirm('削除しますか？')">削除</a></td>
</tr><?php endforeach;?>
</table></div></main></body></html>