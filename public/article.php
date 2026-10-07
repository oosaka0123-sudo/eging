<?php
require __DIR__.'/_runtime.php';
try{$configFile=eging_config_file();}catch(Throwable $e){$configFile='';}
if(!is_file($configFile)){
 http_response_code(503);
 $pageTitle='記事サービスを利用できません｜エギングギアラボ';
 $pageDescription='現在、記事サービスを利用できません。';
 require __DIR__.'/partials/header.php';
 echo '<main id="main"><section class="page-hero"><div><span class="kicker">503 / CONTENT</span><h1>記事サービスを、<br>利用できません。</h1><p>一時的にデータベースへ接続できません。時間をおいて再度お試しください。</p></div></section></main>';
 require __DIR__.'/partials/footer.php';
 exit;
}
try{
 eging_require_bootstrap();
}catch(Throwable $e){
 error_log('EGING article bootstrap error: '.$e->getMessage());
 http_response_code(503);
 $pageTitle='記事サービスを利用できません｜エギングギアラボ';
 $pageDescription='現在、記事サービスを利用できません。';
 require __DIR__.'/partials/header.php';
 echo '<main id="main"><section class="page-hero"><div><span class="kicker">503 / CONTENT</span><h1>記事サービスを、<br>利用できません。</h1><p>一時的にデータベースへ接続できません。時間をおいて再度お試しください。</p></div></section></main>';
 require __DIR__.'/partials/footer.php';
 exit;
}
$slug=trim((string)($_GET['slug']??''));
$stmt=$pdo->prepare("SELECT * FROM articles WHERE slug=? AND status='published' LIMIT 1");$stmt->execute([$slug]);$article=$stmt->fetch();
if(!$article){http_response_code(404);$pageTitle='記事が見つかりません｜エギングギアラボ';require __DIR__.'/partials/header.php';echo '<main id="main"><section class="page-hero"><div><span class="kicker">404</span><h1>見つかりません。</h1><p>この記事は存在しないか、現在非公開です。</p></div></section></main>';require __DIR__.'/partials/footer.php';exit;}
$pageTitle=$article['title'].'｜エギングギアラボ';$pageDescription=$article['description']?:mb_substr(strip_tags($article['body']),0,120);
$heroImage=trim((string)($article['hero_image']??''));
$ogImage='https://eging.rss7.net/assets/brand/og-default.png';
if($heroImage!==''){
 if(preg_match('#^https?://#i',$heroImage)){$ogImage=$heroImage;}
 elseif(str_starts_with($heroImage,'/')){$ogImage='https://eging.rss7.net'.$heroImage;}
}
require __DIR__.'/partials/header.php';
$articleSchema=[
 '@context'=>'https://schema.org',
 '@type'=>'Article',
 'headline'=>$article['title'],
 'description'=>$pageDescription,
 'image'=>$ogImage,
 'mainEntityOfPage'=>'https://eging.rss7.net/article.php?slug='.rawurlencode($article['slug']),
 'datePublished'=>$article['published_at'] ? date(DATE_ATOM,strtotime($article['published_at'])) : null,
 'dateModified'=>date(DATE_ATOM,strtotime($article['updated_at'])),
 'author'=>['@type'=>'Organization','name'=>'エギングギアラボ'],
 'publisher'=>['@type'=>'Organization','name'=>'エギングギアラボ','url'=>'https://eging.rss7.net/'],
 'image'=>'https://eging.rss7.net/assets/brand/og-default.png'
];
?><script type="application/ld+json"><?=json_encode(array_filter($articleSchema,fn($v)=>$v!==null),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?></script><main id="main"><article><section class="page-hero"><?php if($heroImage!==''):?><img class="page-art" src="<?=htmlspecialchars($heroImage,ENT_QUOTES,'UTF-8')?>" alt=""><?php endif;?><div><span class="kicker"><?=htmlspecialchars(strtoupper($article['category']),ENT_QUOTES,'UTF-8')?></span><h1><?=htmlspecialchars($article['title'],ENT_QUOTES,'UTF-8')?></h1><p><?=htmlspecialchars($article['description']??'',ENT_QUOTES,'UTF-8')?></p></div></section><section class="section"><div class="article-shell"><div class="prose"><?=nl2br(htmlspecialchars($article['body'],ENT_QUOTES,'UTF-8'))?></div><aside class="side-note">UPDATED<br><?=htmlspecialchars($article['updated_at'],ENT_QUOTES,'UTF-8')?><br><br>広告リンクを含む場合は記事内で明示します。</aside></div></section></article></main><?php require __DIR__.'/partials/footer.php';?>