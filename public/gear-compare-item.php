<?php
require __DIR__.'/partials/egi-functions.php';
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
$models=egl_egis();
$slug=isset($_GET['model'])&&is_string($_GET['model'])?$_GET['model']:'';
$model=$models[$slug]??null;
if(!$model)http_response_code(404);
$pageTitle=$model?($model['brand'].' '.$model['name'].'｜3.5号スペック・向く条件｜エギングギアラボ'):'商品が見つかりません｜エギングギアラボ';
$pageDescription=$model?$model['use'].'。重量とメーカー公表の沈下速度、向く条件と注意点を解説。':'指定された商品は見つかりません。';
require __DIR__.'/partials/header.php';
?>
<main id="main" class="egl-page">
<?php if(!$model):?>
<section class="egl-section"><div class="egl-container"><h1 class="egl-main-title">商品が見つかりません</h1><p class="egl-lead">URLをお確かめください。</p><a class="egl-btn egl-btn--primary" href="/compare.php">比較ページへ戻る →</a></div></section>
<?php else:
$photo=egl_photo($model);
$purchase=egl_purchase($model);
?>
<section class="egl-detail-hero">
 <div class="egl-container">
  <nav class="egl-breadcrumbs" aria-label="パンくず"><a href="/">トップ</a><span>›</span><a href="/compare.php">エギの比較</a><span>›</span><span><?=egl_h($model['name'])?></span></nav>
  <span class="egl-kicker"><?=egl_h($model['brand'])?> / 3.5 GO / MODEL FACTS</span>
  <h1><?=egl_h($model['name'])?></h1>
  <p class="egl-lead"><?=egl_h($model['use'])?></p>
 </div>
</section>
<section class="egl-section">
 <div class="egl-container egl-detail-grid">
  <div>
   <div class="egl-detail-card">
    <span class="egl-kicker">OFFICIAL SPECIFICATIONS</span>
    <h2>3.5号・メーカー公表スペック</h2>
    <dl class="egl-detail-specs">
     <div><dt>メーカー</dt><dd><?=egl_h($model['brand'])?></dd></div>
     <div><dt>モデル</dt><dd><?=egl_h($model['name'])?></dd></div>
     <div><dt>号数</dt><dd><?=egl_h($model['size'])?></dd></div>
     <div><dt>タイプ</dt><dd><?=egl_h($model['type'])?></dd></div>
     <div><dt>重量</dt><dd><?=egl_h($model['weight'])?>g</dd></div>
     <div><dt>沈下速度</dt><dd>約<?=egl_h($model['sink'])?>秒/m</dd></div>
    </dl>
    <p class="egl-note">メーカーの掲載値／2026年10月14日確認。実釣の測定値や釣果予測ではありません。</p>
   </div>
   <div class="egl-detail-actions">
    <?php if($purchase):?><a class="egl-btn egl-btn--primary" href="<?=egl_h($purchase)?>" target="_blank" rel="nofollow sponsored noopener noreferrer">Yahoo!ショッピングで見る（PR） ↗</a>
    <?php else:?><a class="egl-btn egl-btn--primary" href="<?=egl_h($model['source'])?>" target="_blank" rel="noopener noreferrer">メーカー公式ページで製品を確認 ↗</a><?php endif;?>
    <a class="egl-btn egl-btn--quiet" href="/compare.php">← 他のエギと比較</a>
   </div>
   <?php if(!$purchase):?><p class="egl-note">※ 当サイト経由の購入先リンクは現在掲載していません。Yahoo!等での販売価格・在庫はこのページでは案内していません。</p><?php endif;?>
  </div>
  <div class="egl-detail-photo">
    <?php if($photo):?><img src="<?=egl_h($photo)?>" alt="<?=egl_h($model['brand'].' '.$model['name'])?>の製品写真">
    <?php else:?><div class="egl-detail-photo__empty"><strong>3.5 GO</strong><span>このモデルの製品写真は未掲載です。<br>正確な写真はメーカー公式ページで確認できます。</span><a class="egl-btn egl-btn--sm" href="<?=egl_h($model['source'])?>" target="_blank" rel="noopener noreferrer">公式の製品写真を見る ↗</a></div><?php endif;?>
  </div>
 </div>
</section>
<section class="egl-section egl-section--tight">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">FIELD NOTES</span><h2>どんな条件で検討する？</h2></div><p>メーカー公表の構造と数値に基づく選択の考え方です。現場での比較試験結果ではありません。</p></div>
  <div class="egl-guides">
   <article class="egl-guide"><span class="egl-kicker">モデルの特徴</span><h3>設計上のポイント</h3><p><?=egl_h($model['feature'])?></p></article>
   <article class="egl-guide"><span class="egl-kicker">条件の目安</span><h3>候補になる理由</h3><p><?=egl_h($model['reason'])?></p></article>
   <article class="egl-guide"><span class="egl-kicker">購入前に確認</span><h3>数字だけでは決まらない</h3><p>潮の流れ、風、ラインの太さによってフォール姿勢や沈下時間は変わります。水深がある場所では専用ディープモデルも比較してください。</p></article>
  </div>
  <p class="egl-note">出典：<a href="<?=egl_h($model['source'])?>" target="_blank" rel="noopener noreferrer" style="color:var(--egl-mint);text-decoration:underline"><?=egl_h($model['brand'])?> 公式製品ページ ↗</a>。情報の更新が見つかった場合は公称値を再確認します。</p>
 </div>
</section>
<section class="egl-section egl-section--tight"><div class="egl-container"><div class="egl-banner"><div><h2>同じ号数でも、沈む速さが違います。</h2><p>5機種の重量・沈下速度を並べて比較できます。</p></div><a class="egl-btn egl-btn--primary" href="/compare.php">比較へ戻る →</a></div></div></section>
<?php endif;?>
</main>
<?php require __DIR__.'/partials/footer.php';
