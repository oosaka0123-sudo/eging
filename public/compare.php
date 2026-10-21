<?php
require __DIR__.'/partials/egi-functions.php';
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
$models=egl_egis();
$scenes=egl_scenes();
$selected=isset($_GET['scene']) && is_string($_GET['scene']) ? $_GET['scene'] : 'all';
if(!array_key_exists($selected,$scenes))$selected='all';
$current=$scenes[$selected];
$pageTitle=($selected==='all'?'3.5号エギ5モデル比較':$current['label'].'で比較').'｜エギングギアラボ';
$pageDescription='エギ王K・セフィアクリンチ・アモラスジョイントなどを、メーカー公表の重量・沈下速度・用途で比較。';
require __DIR__.'/partials/header.php';
?>
<main id="main" class="egl-page">
<section class="egl-detail-hero">
 <div class="egl-container">
  <nav class="egl-breadcrumbs" aria-label="パンくず"><a href="/">トップ</a><span>›</span><span>エギを比較</span></nav>
  <span class="egl-kicker">SPEC COMPARISON / 3.5 GO</span>
  <h1>3.5号エギを、<br>条件で比べる。</h1>
  <p class="egl-lead">浅場・堤防・潮の速い場所。近い釣り場を選ぶと、確認済みの5モデルから3モデルを並べて比較できます。</p>
 </div>
</section>
<section class="egl-section">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">01 / FIELD CONDITIONS</span><h2>釣り場の条件を変更</h2></div><p>選び直しても、比較したいモデルの情報はメーカー公表値で統一しています。</p></div>
  <div class="egl-scenes egl-scenes--compact">
   <?php foreach(['shallow','standard','current'] as $key):$s=$scenes[$key];?>
   <a class="egl-scene<?=$selected===$key?' is-selected':''?>" href="/compare.php?scene=<?=egl_h($key)?>" <?=$selected===$key?'aria-current="page"':''?>>
     <span><b><?=egl_h($s['label'])?></b><small><?=egl_h($s['detail'])?></small></span><span class="egl-scene__arrow" aria-hidden="true">↗</span>
   </a><?php endforeach;?>
  </div>
  <p class="egl-note"><a href="/compare.php" style="color:var(--egl-mint);text-decoration:underline">全5モデルを表示する →</a></p>
 </div>
</section>
<section class="egl-section egl-section--tight">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">02 / <?=count($current['slugs'])?> MODELS</span><h2><?=egl_h($current['label'])?>の比較候補</h2></div><p><?=egl_h($current['detail'])?></p></div>
  <div class="egl-explain"><strong>選び方の目安</strong>：<?=egl_h($current['reason'])?><span class="egl-explain__notice"><?=egl_h($current['note'])?></span></div>
  <div class="egl-products">
  <?php foreach($current['slugs'] as $slug):$model=$models[$slug];require __DIR__.'/partials/egi-card.php';endforeach;?>
  </div>
  <p class="egl-note">掲載順は「売れ筋」や「釣れる順位」ではありません。モデルの重量・沈下速度と設計上の違いを確認してください。実際の沈み方は潮流・ライン等で変化します。</p>
 </div>
</section>
<section class="egl-section">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">03 / SPEC TABLE</span><h2>数値を横並びで比較</h2></div><p>すべて3.5号。沈下速度は参考値で、秒数が長いほどゆっくり沈む目安です。</p></div>
  <p class="egl-note egl-swipe-hint">← 表は左右にスクロールできます →</p>
  <div class="egl-spec-table-wrap" role="region" aria-label="3.5号エギ重量沈下速度一覧" tabindex="0">
   <table class="egl-spec-table">
    <caption>公称スペックの一覧（掲載5モデル）</caption>
    <thead><tr><th scope="col">モデル</th><th scope="col">重量</th><th scope="col">沈下速度</th><th scope="col">タイプ</th><th scope="col">詳細</th></tr></thead>
    <tbody><?php foreach($models as $slug=>$model):?><tr><th scope="row"><?=egl_h($model['brand'].' '.$model['name'])?></th><td><?=egl_h($model['weight'])?>g</td><td>約<?=egl_h($model['sink'])?>秒/m</td><td><?=egl_h($model['type'])?></td><td><a href="/gear-compare-item.php?model=<?=rawurlencode($slug)?>">詳細 →</a></td></tr><?php endforeach;?></tbody>
   </table>
  </div>
  <p class="egl-note">出典：<a href="https://www.yamaria.co.jp/yamashita/product/detail/522" target="_blank" rel="noopener noreferrer" style="text-decoration:underline;color:var(--egl-mint)">YAMASHITA</a>、<a href="https://fish.shimano.com/ja-JP/product/lure/shoreeging/egi/a155f00000c5d08qaf.html" target="_blank" rel="noopener noreferrer" style="text-decoration:underline;color:var(--egl-mint)">SHIMANO</a>、<a href="https://www.daiwa.com/jp/product/2023/11/06/01/32/thtjj6t" target="_blank" rel="noopener noreferrer" style="text-decoration:underline;color:var(--egl-mint)">DAIWA</a>の製品情報（2026年10月21日再確認）。メーカーによって測定条件が異なる場合があります。</p>
 </div>
</section>
<section class="egl-section egl-section--tight"><div class="egl-container"><div class="egl-banner"><div><h2>沈下速度の違いが気になる？</h2><p>浅場・標準タイプの選び分けや、購入前の注意点を説明します。</p></div><a class="egl-btn egl-btn--primary" href="/guide.php">選び方ガイド →</a></div></div></section>
</main>
<?php require __DIR__.'/partials/footer.php';
