<?php
require dirname(__DIR__) . '/_runtime.php';
eging_require_bootstrap();
require_admin();
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf((string)($_POST['csrf']??''));
 $id=(int)($_POST['id']??0);
 $data=[
  ':slug'=>trim((string)$_POST['slug']), ':title'=>trim((string)$_POST['title']),
  ':description'=>trim((string)($_POST['description']??'')), ':body'=>(string)$_POST['body'],
  ':category'=>trim((string)$_POST['category']), ':status'=>in_array($_POST['status']??'draft',['draft','published'],true)?$_POST['status']:'draft'
 ];
 if($id){
  $data[':id']=$id;
  $pdo->prepare("UPDATE articles SET slug=:slug,title=:title,description=:description,body=:body,category=:category,status=:status,published_at=IF(:status='published',COALESCE(published_at,NOW()),NULL) WHERE id=:id")->execute($data);
 } else {
  $pdo->prepare("INSERT INTO articles(slug,title,description,body,category,status,published_at) VALUES(:slug,:title,:description,:body,:category,:status,IF(:status='published',NOW(),NULL))")->execute($data);
 }
 header('Location:/admin/articles.php'); exit;
}
if(isset($_GET['delete'])){verify_csrf((string)($_GET['token']??''));$pdo->prepare("DELETE FROM articles WHERE id=?")->execute([(int)$_GET['delete']]);header('Location:/admin/articles.php');exit;}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare("SELECT * FROM articles WHERE id=?");$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$rows=$pdo->query("SELECT id,slug,title,category,status,updated_at FROM articles ORDER BY updated_at DESC")->fetchAll();
?><!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>記事管理</title><style>body{font-family:system-ui;margin:0;background:#f4f5f6}main{max-width:1100px;margin:auto;padding:20px}form,.panel{background:#fff;padding:18px;border:1px solid #ddd;border-radius:12px;margin-bottom:20px}label{display:grid;gap:6px;margin:10px 0}input,textarea,select{padding:10px;font:inherit}textarea{min-height:220px}table{width:100%;border-collapse:collapse;background:#fff}th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}button{padding:10px 14px}</style></head><body><main><?php require __DIR__.'/_nav.php';?><h1>記事管理</h1>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<label>Slug<input required name="slug" value="<?=htmlspecialchars($edit['slug']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>タイトル<input required name="title" value="<?=htmlspecialchars($edit['title']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>説明<textarea name="description"><?=htmlspecialchars($edit['description']??'',ENT_QUOTES,'UTF-8')?></textarea></label>
<label>カテゴリ<input required name="category" value="<?=htmlspecialchars($edit['category']??'guide',ENT_QUOTES,'UTF-8')?>"></label>
<label>本文<textarea required name="body"><?=htmlspecialchars($edit['body']??'',ENT_QUOTES,'UTF-8')?></textarea></label>
<label>状態<select name="status"><option value="draft">下書き</option><option value="published" <?=($edit['status']??'')==='published'?'selected':''?>>公開</option></select></label>
<button>保存</button></form>
<table><tr><th>タイトル</th><th>カテゴリ</th><th>状態</th><th>更新</th><th></th></tr><?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['title'],ENT_QUOTES,'UTF-8')?></td><td><?=htmlspecialchars($r['category'],ENT_QUOTES,'UTF-8')?></td><td><?=$r['status']?></td><td><?=$r['updated_at']?></td><td><a href="?edit=<?=$r['id']?>">編集</a> / <a href="?delete=<?=$r['id']?>&token=<?=urlencode(csrf_token())?>" onclick="return confirm('削除しますか？')">削除</a></td></tr><?php endforeach;?></table>
</main></body></html>