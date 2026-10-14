<?php
require __DIR__.'/partials/egi-functions.php';
$scenes=egl_scenes();
$pageTitle='釣り場の条件からエギを選ぶ｜エギングギアラボ';
$pageDescription='浅場・藻場、堤防・中層、深め・潮流から釣り場に近い条件を選び、3.5号エギの重量と沈下速度を比較。';
require __DIR__.'/partials/header.php';
?>
<main id="main" class="egl-page">
<section class="egl-detail-hero"><div class="egl-container"><nav class="egl-breadcrumbs" aria-label="パンくず"><a href="/">トップ</a><span>›</span><span>条件から選ぶ</span></nav><span class="egl-kicker">CONDITIONS / CHOOSE YOUR FIELD</span><h1>釣り場を選んで、<br>候補を絞る。</h1><p class="egl-lead">難しい7問診断は不要。まずは今の釣り場に近い条件を選んでください。比較したいエギを3モデルまで絞れます。</p></div></section>
<section class="egl-section"><div class="egl-container">
<div class="egl-heading"><div><span class="egl-kicker">01 / YOUR CONDITIONS</span><h2>条件をひとつ選ぶ</h2></div><p>掲載する5モデルの中でスペックを比較する入口です。実際の適合を保証する判定ではありません。</p></div>
<div class="egl-scenes">
<?php foreach(['shallow','standard','current'] as $key):$scene=$scenes[$key];?>
<a class="egl-scene" href="/compare.php?scene=<?=egl_h($key)?>">
 <span><b><?=egl_h($scene['label'])?></b><small><?=egl_h($scene['reason'])?></small></span><span class="egl-scene__arrow" aria-hidden="true">↗</span>
</a><?php endforeach;?>
</div>
<p class="egl-note">※ 季節やイカのサイズも選択には影響しますが、現在は3.5号の5モデルのみを掲載しています。潮流が速い場所や深場用の専用モデルは別途確認してください。</p>
<div class="egl-inline-links"><a class="egl-btn egl-btn--quiet" href="/compare.php">条件を決めず5モデルを比較する →</a></div>
</div></section>
<section class="egl-section egl-section--tight"><div class="egl-container"><div class="egl-banner"><div><h2>どのタイプを選ぶか迷うときは</h2><p>「約3秒/m」と「約8秒/m」で何が違うのか、数値の読み方から説明します。</p></div><a class="egl-btn egl-btn--primary" href="/guide.php">選び方を読む →</a></div></div></section>
</main>
<?php require __DIR__.'/partials/footer.php';
