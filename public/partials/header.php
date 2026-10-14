<?php
$pageTitle=$pageTitle??'エギングギアラボ｜釣り場の条件からエギを選ぶ';
$pageDescription=$pageDescription??'エギの重量・沈下速度をメーカー公表値で比較。釣り場の条件から自分に合うエギを探せます。';
$ogImage=$ogImage??'https://eging.rss7.net/assets/brand/og-default.png';
$requestUri=$_SERVER['REQUEST_URI']??'/';
$parts=parse_url($requestUri);
$path=$parts['path']??'/';
$query=[];
if(!empty($parts['query']))parse_str($parts['query'],$query);
$canonical='https://eging.rss7.net'.$path;
if($path==='/gear-compare-item.php' && isset($query['model']) && is_scalar($query['model'])){
 $canonical.='?model='.rawurlencode((string)$query['model']);
}elseif(in_array($path,['article.php','/article.php','/gear-item.php'],true) && isset($query['slug']) && is_scalar($query['slug'])){
 $canonical.='?slug='.rawurlencode((string)$query['slug']);
}
function egl_header_escape($text){return htmlspecialchars((string)$text,ENT_QUOTES,'UTF-8');}
?><!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#07171d">
<title><?=egl_header_escape($pageTitle)?></title>
<meta name="description" content="<?=egl_header_escape($pageDescription)?>">
<link rel="canonical" href="<?=egl_header_escape($canonical)?>">
<meta property="og:type" content="website"><meta property="og:site_name" content="エギングギアラボ"><meta property="og:title" content="<?=egl_header_escape($pageTitle)?>"><meta property="og:description" content="<?=egl_header_escape($pageDescription)?>"><meta property="og:url" content="<?=egl_header_escape($canonical)?>"><meta property="og:image" content="<?=egl_header_escape($ogImage)?>"><meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="/assets/brand/favicon.svg" type="image/svg+xml"><link rel="manifest" href="/manifest.webmanifest">
<link rel="stylesheet" href="/assets/css/site.css"><link rel="stylesheet" href="/assets/css/experience.css?v=20261014r1">
<script type="application/ld+json"><?=json_encode(['@context'=>'https://schema.org','@type'=>'WebSite','name'=>'エギングギアラボ','alternateName'=>'EGING GEAR LAB','url'=>'https://eging.rss7.net/','description'=>$pageDescription],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)?></script>
</head>
<body>
<a class="skip-link" href="#main">本文へ移動</a>
<header class="site-header egl-header" id="siteHeader">
 <a class="brand egl-brand" href="/" aria-label="エギングギアラボ トップページ">
  <span class="egl-brand__mark" aria-hidden="true">E<span>.</span></span>
  <span class="egl-brand__copy">エギングギアラボ<small>EGING GEAR LAB</small></span>
 </a>
 <nav class="desktop-nav egl-nav" aria-label="メインナビ">
   <a href="/conditions.php">条件から選ぶ</a><a href="/compare.php">エギを比較</a><a href="/guide.php">選び方ガイド</a>
 </nav>
 <div class="egl-header__right"><button type="button" class="egl-icon-btn" id="shareSite" aria-label="サイトを共有" title="サイトを共有"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.4 11l7.2-4.7M8.4 13l7.2 4.7"/></svg></button><button type="button" class="menu-btn" id="menuBtn" aria-expanded="false" aria-controls="mobileMenu" aria-label="メニューを開く"><span></span></button></div>
</header>
<div class="mobile-menu egl-mobile-menu" id="mobileMenu" aria-hidden="true">
<nav aria-label="スマートフォン用メニュー">
<a href="/"><span class="index">01</span>トップ</a>
<a href="/conditions.php"><span class="index">02</span>条件から選ぶ</a>
<a href="/compare.php"><span class="index">03</span>エギを比較</a>
<a href="/guide.php"><span class="index">04</span>選び方ガイド</a>
<a href="/concept.php"><span class="index">05</span>編集方針</a>
</nav><p>釣り場から、道具を選ぶ。</p>
</div>
