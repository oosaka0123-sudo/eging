<?php
require __DIR__.'/_runtime.php';
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
function egi_h($value): string {return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');}
$models=require __DIR__.'/_comparison_egis.php';
$slug=trim((string)($_GET['model']??''));
$egi=$models[$slug]??null;
if(!$egi){http_response_code(404);}
$pageTitle=$egi?($egi['brand'].' '.$egi['name'].' 3.5号 比較詳細｜エギングギアラボ'):'商品が見つかりません｜エギングギアラボ';
$pageDescription=$egi?$egi['reason']:'指定された比較商品が見つかりません。';
require __DIR__.'/partials/header.php';
?>
<main id="main">
<?php if(!$egi):?>
<section class="section"><span class="kicker">404 / NOT FOUND</span><h1>比較商品が見つかりません</h1><p><a class="cta" href="/compare.php">← 比較一覧へ</a></p></section>
<?php else:?>
<section class="page-hero"><img class="page-art" src="/assets/visuals/gear-orbit.svg" alt=""><div><span class="kicker"><?=egi_h($egi['brand'])?> / EGI 3.5 GO</span><h1><?=egi_h($egi['name'])?></h1><p><?=egi_h($egi['use'])?></p></div></section>
<section class="section compare-detail-section">
<div class="section__head"><div><span class="kicker">MODEL DETAILS</span><h2>スペックと<br>適合条件</h2></div><p>メーカーの一次情報をもとに、フォール速度と構造の違いを整理。実釣レビューや釣果の保証ではありません。</p></div>
<dl class="compare-detail-specs">
<div><dt>メーカー</dt><dd><?=egi_h($egi['brand'])?></dd></div>
<div><dt>モデル</dt><dd><?=egi_h($egi['name'])?></dd></div>
<div><dt>サイズ</dt><dd><?=egi_h($egi['size'])?></dd></div>
<div><dt>タイプ</dt><dd><?=egi_h($egi['type'])?></dd></div>
<div><dt>重量（公称値）</dt><dd><?=egi_h(rtrim(rtrim(sprintf('%.2f',$egi['weight']),'0'),'.'))?> g</dd></div>
<div><dt>沈下速度（参考値）</dt><dd><?=egi_h($egi['sink'])?> 秒/m</dd></div>
</dl>
<div class="compare-detail-insights">
<article><span class="kicker">DESIGN</span><h3>モデルの特徴</h3><p><?=egi_h($egi['feature'])?></p></article>
<article><span class="kicker">FIELD CONDITIONS</span><h3>どういう条件で候補になる？</h3><p><?=egi_h($egi['reason'])?></p></article>
</div>
<div class="compare-detail-disclaimer"><strong>商品写真について</strong><p>このページでは未許諾のメーカー写真を複製・転載していません。公式製品ページでは写真と詳しい仕様を確認できます。</p></div>
<div class="compare-detail-actions"><a class="cta cta--primary" href="<?=egi_h($egi['source'])?>" target="_blank" rel="noopener noreferrer">メーカー公式ページで写真を確認 ↗</a><a class="cta" href="/compare.php">← 5モデル比較に戻る</a></div>
<p class="gear-note">メーカー公表仕様の確認日：2026年10月13日。沈下速度は海況やタックルにより変動します。実売価格・在庫は含めていません。</p>
</section>
<?php endif;?>
</main>
<?php require __DIR__.'/partials/footer.php';
