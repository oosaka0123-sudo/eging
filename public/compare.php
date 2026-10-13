<?php
require __DIR__.'/_runtime.php';
// The product catalog is dynamic; prevent Lolipop Accelerator from serving stale HTML.
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
$pageTitle='3.5号エギ5モデルのスペック比較｜エギングギアラボ';
$pageDescription='エギ王K・セフィアクリンチ・アモラスジョイントなど3.5号エギ5モデルのメーカー公称重量・沈下速度・用途を比較。';
$items=[];$unavailable=false;
try {
  if(!is_file(eging_config_file())){throw new RuntimeException('Database is not configured');}
  eging_require_bootstrap();
  $items=$pdo->query("SELECT slug,type,brand,name,summary,price_yen,weight_g,sink_sec_per_m,specs,source_url,source_checked_at FROM gear_items WHERE status='published' ORDER BY type,brand,name")->fetchAll(PDO::FETCH_ASSOC);
} catch(Throwable $e){$unavailable=true;error_log('EGING compare: '.$e->getMessage());}
function gear_photo($row){
  $s=json_decode((string)($row['specs']??''),true);
  $url=trim((string)($s['image_url']??''));
  return filter_var($url,FILTER_VALIDATE_URL)&&parse_url($url,PHP_URL_SCHEME)==='https'?$url:'';
}
function gh($text){return htmlspecialchars((string)$text,ENT_QUOTES,'UTF-8');}
$types=['egi'=>'エギ','rod'=>'ロッド','reel'=>'リール','pe'=>'PEライン','leader'=>'リーダー','snap'=>'スナップ','landing'=>'ランディング','bag'=>'バッグ','light'=>'ライト','lifejacket'=>'ライフジャケット','other'=>'その他'];
$verifiedEgis=require __DIR__.'/_comparison_egis.php';
require __DIR__.'/partials/header.php';
?>
<main id="main">
<section class="page-hero"><img class="page-art" src="/assets/visuals/gear-orbit.svg" alt=""><div><span class="kicker">COMPARE / VERIFIED SPECIFICATIONS</span><h1>条件から、<br>比べる。</h1><p>まずは3.5号エギ5モデル。メーカー公表スペックを同じ基準で比較し、違いが分かるよう整理しました。</p></div></section>
<section class="section compare-verified" aria-labelledby="egis-compare-title">
<div class="section__head"><div><span class="kicker">SPECIFICATION / 3.5 GO</span><h2 id="egis-compare-title">3.5号エギ<br>5モデル比較</h2></div><p>YAMASHITA・SHIMANO・DAIWAのメーカー公式情報を照合。<strong>重量と沈下速度</strong>を揃えて比較できます。</p></div>
<div class="compare-verified__intro"><p>公開情報の確認日：2026年10月13日。<strong>メーカー公称値</strong>であり実測結果・釣果ランキングではありません。風・潮流・ライン・姿勢によって実際の沈み方は異なります。</p></div>
<div class="verified-egi-grid">
<?php foreach($verifiedEgis as $slug=>$egi): ?>
<article class="verified-egi-card">
  <a class="verified-egi-card__main" href="/gear-compare-item.php?model=<?=rawurlencode($slug)?>" aria-label="<?=gh($egi['brand'].' '.$egi['name'])?>の商品比較詳細を表示">
    <span class="verified-egi-card__brand"><?=gh($egi['brand'])?></span>
    <span class="verified-egi-card__category"><?=gh($egi['type'])?></span>
    <h3><?=gh($egi['name'])?></h3>
    <p><?=gh($egi['use'])?></p>
    <dl class="verified-egi-card__numbers"><div><dt>重量</dt><dd><?=gh(rtrim(rtrim(sprintf('%.2f',$egi['weight']),'0'),'.'))?> <small>g</small></dd></div><div><dt>沈下速度</dt><dd><?=gh($egi['sink'])?> <small>秒/m</small></dd></div></dl>
    <div class="verified-egi-card__meter"><span>沈下にかかる時間（1mあたり）</span><div class="verified-egi-card__track"><span style="width:<?=min(100,max(1,($egi['sink']/8)*100))?>%"></span></div></div>
    <span class="verified-egi-card__details">詳しい適合条件を見る <span aria-hidden="true">→</span></span>
  </a>
  <a class="verified-egi-card__source" href="<?=gh($egi['source'])?>" target="_blank" rel="noopener noreferrer">メーカー公式スペック ↗</a>
</article>
<?php endforeach;?>
</div>
<div class="verified-comparison-scroll" role="region" aria-label="3.5号エギの横並び比較表" tabindex="0">
<table class="verified-comparison-table"><caption>5モデルのスペック一覧（全て3.5号）</caption><thead><tr><th scope="col">モデル</th><th scope="col">重量</th><th scope="col">沈下速度</th><th scope="col">タイプ</th><th scope="col">詳細</th></tr></thead><tbody>
<?php foreach($verifiedEgis as $slug=>$egi):?><tr><th scope="row"><?=gh($egi['brand'].' '.$egi['name'])?></th><td><?=gh(rtrim(rtrim(sprintf('%.2f',$egi['weight']),'0'),'.'))?> g</td><td><?=gh($egi['sink'])?> 秒/m</td><td><?=gh($egi['type'])?></td><td><a href="/gear-compare-item.php?model=<?=rawurlencode($slug)?>">詳細 →</a></td></tr><?php endforeach;?>
</tbody></table></div>
<p class="verified-comparison-note">沈下速度は「秒/m」の数値が<strong>大きいほどゆっくり沈む</strong>ことを示します。商品写真は転載許諾を確認できたものから追加します。製品ごとの画像はまだ掲載していません。</p>
</section>
<section class="section"><div class="section__head"><div><span class="kicker">PRODUCT GALLERY / CMS</span><h2>商品写真・<br>追加ギア</h2></div><p>掲載を確認できた商品写真と追加情報を表示します。写真・商品名は個別ページにリンクします。</p></div>
<?php if($unavailable || !$items):?>
<p role="status"><?= $unavailable?'商品データを更新中です。':'比較対象の商品を準備中です。' ?> 以下は製品型番とは無関係の実物参考写真です。</p>
<div class="gear-compare-photo-guides">
<figure class="gear-reference-photo"><img src="https://upload.wikimedia.org/wikipedia/commons/4/48/Egi.jpg" loading="lazy" decoding="async" alt="カスタマイズされたエギの実物参考写真。特定の販売商品ではありません。"><figcaption><strong>エギの形を写真で見る</strong><span>ボディ・シンカー・カンナを確認。機種別の写真ではありません。</span><small><a href="https://commons.wikimedia.org/wiki/File:Egi.jpg" target="_blank" rel="noopener noreferrer">Rickygap / Wikimedia Commons</a> ・ <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank" rel="noopener noreferrer">CC BY-SA 3.0</a>（無加工）</small></figcaption></figure>
<figure class="gear-reference-photo"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/A_spinning_reel_on_a_rod.jpg/500px-A_spinning_reel_on_a_rod.jpg" loading="lazy" decoding="async" alt="竿に付いたスピニングリールの実物参考写真。特定の販売商品ではありません。"><figcaption><strong>ロッド・リールを写真で見る</strong><span>スプールとリールフットなど、基本構造を確認できます。</span><small><a href="https://commons.wikimedia.org/wiki/File:A_spinning_reel_on_a_rod.jpg" target="_blank" rel="noopener noreferrer">JaredMcKenzie / Wikimedia Commons</a> ・ <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank" rel="noopener noreferrer">CC0</a></small></figcaption></figure>
</div><p style="margin-top:32px"><a class="cta cta--primary" href="/gear.php">ギアの選び方へ →</a></p>
<?php else:?>
<div class="gear-gallery">
<?php foreach($items as $item):$photo=gear_photo($item); ?>
<article class="gear-tile">
<a class="gear-tile__link" href="/gear-item.php?slug=<?=rawurlencode($item['slug'])?>">
<div class="gear-tile__photo"><?php if($photo):?><img src="<?=gh($photo)?>" alt="<?=gh($item['brand'].' '.$item['name'])?>の商品写真" loading="lazy" decoding="async" referrerpolicy="no-referrer" onerror="this.hidden=true;this.parentElement.classList.add('gear-tile__missing')"><?php else:?><span>商品写真を確認中</span><?php endif;?></div>
<div class="gear-tile__body"><span class="kicker"><?=gh($types[$item['type']]??$item['type'])?></span><h3><?=gh($item['brand'].' '.$item['name'])?></h3><p><?=gh($item['summary']??'')?></p><dl><?php if($item['weight_g']!==null):?><div><dt>自重</dt><dd><?=gh($item['weight_g'])?> g</dd></div><?php endif;?><?php if($item['sink_sec_per_m']!==null):?><div><dt>沈下速度</dt><dd><?=gh($item['sink_sec_per_m'])?> 秒/m</dd></div><?php endif;?><?php if($item['price_yen']!==null):?><div><dt>参考価格</dt><dd>¥<?=number_format((int)$item['price_yen'])?></dd></div><?php endif;?></dl><span class="gear-tile__more">詳細・適合条件を見る →</span></div>
</a></article>
<?php endforeach;?></div>
<?php endif;?></section></main>
<?php require __DIR__.'/partials/footer.php';?>
