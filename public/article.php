<?php
require dirname(__DIR__) . '/app/bootstrap.php';
$slug=trim((string)($_GET['slug']??''));
$stmt=$pdo->prepare("SELECT * FROM articles WHERE slug=? AND status='published' LIMIT 1");$stmt->execute([$slug]);$article=$stmt->fetch();
if(!$article){http_response_code(404);$pageTitle='記事が見つかりません｜エギングギアラボ';require __DIR__.'/partials/header.php';echo '<main id="main"><section class="page-hero"><div><span class="kicker">404</span><h1>見つかりません。</h1><p>この記事は存在しないか、現在非公開です。</p></div></section></main>';require __DIR__.'/partials/footer.php';exit;}
$pageTitle=$article['title'].'｜エギングギアラボ';$pageDescription=$article['description']?:mb_substr(strip_tags($article['body']),0,120);
require __DIR__.'/partials/header.php';
?><main id="main"><article><section class="page-hero"><div><span class="kicker"><?=htmlspecialchars(strtoupper($article['category']),ENT_QUOTES,'UTF-8')?></span><h1><?=htmlspecialchars($article['title'],ENT_QUOTES,'UTF-8')?></h1><p><?=htmlspecialchars($article['description']??'',ENT_QUOTES,'UTF-8')?></p></div></section><section class="section"><div class="article-shell"><div class="prose"><?=nl2br(htmlspecialchars($article['body'],ENT_QUOTES,'UTF-8'))?></div><aside class="side-note">UPDATED<br><?=htmlspecialchars($article['updated_at'],ENT_QUOTES,'UTF-8')?><br><br>広告リンクを含む場合は記事内で明示します。</aside></div></section></article></main><?php require __DIR__.'/partials/footer.php';?>