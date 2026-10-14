<?php
require __DIR__.'/partials/egi-functions.php';
$pageTitle='エギングギアラボ｜釣り場の条件からエギを比べて選ぶ';
$pageDescription='浅場・堤防・深めの釣り場条件から、3.5号エギをメーカー公表の重量・沈下速度で比較。5モデルの違いと選ぶ理由が分かる。';
$models=egl_egis();
$scenes=egl_scenes();
require __DIR__.'/partials/header.php';
?>
<main id="main" class="egl-page">
<section class="egl-hero">
 <div class="egl-container egl-hero__grid">
  <div>
   <span class="egl-kicker">EGING GEAR LAB / CONDITIONS FIRST</span>
   <h1>釣り場に合うエギを、<br><em>比べて選ぶ。</em></h1>
   <p class="egl-lead">浅場か、堤防か、潮が速い場所か。<br>条件を選ぶと、3.5号エギの候補を3モデルまで絞り、重さ・沈下速度・違いを比較できます。</p>
   <div class="egl-hero__actions"><a class="egl-btn egl-btn--primary" href="#choose">釣り場の条件を選ぶ ↓</a><a class="egl-btn egl-btn--quiet" href="/compare.php">5モデルを全部比較 →</a></div>
  </div>
  <aside class="egl-hero__aside" aria-label="沈下速度の違いの例">
   <span class="egl-hero__aside-title">沈下速度が違えば、選び方も変わる。</span>
   <div class="egl-example"><b>ベーシック</b><div class="egl-example__bar"><span style="width:37.5%"></span></div><strong>約3秒/m</strong></div>
   <div class="egl-example"><b>シャロー</b><div class="egl-example__bar"><span style="width:75%"></span></div><strong>約6秒/m</strong></div>
   <div class="egl-example"><b>スーパーシャロー</b><div class="egl-example__bar"><span style="width:100%"></span></div><strong>約8秒/m</strong></div>
   <small class="egl-hero__aside-note">エギ王 K 3.5号のメーカー公表値。数値が大きいほど、1m沈むまでの時間が長くなります。<a href="https://www.yamaria.co.jp/yamashita/product/detail/523" target="_blank" rel="noopener noreferrer" style="text-decoration:underline">公称値の出典 ↗</a></small>
  </aside>
 </div>
</section>
<section class="egl-section" id="choose">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">01 / CHOOSE YOUR FIELD</span><h2>どこで釣りますか？</h2></div><p>難しい診断は不要。まずは近い条件をひとつ選んでください。</p></div>
  <div class="egl-scenes">
  <?php foreach(['shallow','standard','current'] as $key):$scene=$scenes[$key];?>
   <a class="egl-scene" href="/compare.php?scene=<?=egl_h($key)?>">
    <span><b><?=egl_h($scene['label'])?></b><small><?=egl_h($scene['detail'])?></small></span><span class="egl-scene__arrow" aria-hidden="true">↗</span>
   </a>
  <?php endforeach;?>
  </div>
  <p class="egl-note">※ 条件別の候補は、現在公開している3.5号5機種の中での比較です。深場などで適合機種が不足する場合は、その旨を明記しています。</p>
 </div>
</section>
<section class="egl-section egl-section--tight">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">02 / COMPARE</span><h2>まずは定番タイプの3モデル</h2></div><p>同じ3.5号でも、メーカーや構造によって違いがあります。商品名から詳細へ進めます。</p></div>
  <div class="egl-products">
  <?php foreach($scenes['standard']['slugs'] as $slug):$model=$models[$slug];require __DIR__.'/partials/egi-card.php';endforeach;?>
  </div>
  <div class="egl-inline-links"><a class="egl-btn egl-btn--primary" href="/compare.php">5モデルを比較する →</a></div>
 </div>
</section>
<section class="egl-section">
 <div class="egl-container">
  <div class="egl-heading"><div><span class="egl-kicker">03 / HOW IT WORKS</span><h2>3ステップで、選ぶ。</h2></div></div>
  <div class="egl-guides">
   <article class="egl-guide"><span class="egl-kicker">STEP 01</span><h3>釣り場の条件を選択</h3><p>浅場・堤防・深めから、釣り場に一番近い条件を選びます。</p></article>
   <article class="egl-guide"><span class="egl-kicker">STEP 02</span><h3>重量と沈下速度を比較</h3><p>メーカーが公開する数値と、向く条件・注意点を並べて判断します。</p></article>
   <article class="egl-guide"><span class="egl-kicker">STEP 03</span><h3>詳細と公式情報で確認</h3><p>製品ごとの違いを確認。購入先は正規提携リンクが整備された商品だけ表示します。</p></article>
  </div>
  <p class="egl-note">当サイトは実釣試験を行ったとの表示はしていません。広告報酬を理由に掲載順を変更しません。<a href="/concept.php" style="text-decoration:underline;color:var(--egl-mint)">編集方針を読む →</a></p>
 </div>
</section>
<section class="egl-section egl-section--tight"><div class="egl-container"><div class="egl-banner"><div><h2>重量と沈下速度の見方から知りたい方へ</h2><p>数値の読み方、シャローとベーシックの違いを図解的に説明しています。</p></div><a class="egl-btn egl-btn--primary" href="/guide.php">選び方ガイド →</a></div></div></section>
</main>
<?php require __DIR__.'/partials/footer.php';
