<?php
require __DIR__.'/_runtime.php';
$pageTitle='写真で比較｜エギングギアラボ';
$pageDescription='エギングギアを実物写真・スペック・用途別に比較。';
$items=[];$unavailable=false;
try {
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
require __DIR__.'/partials/header.php';
?>
<main id="main"><section class="page-hero"><img class="page-art" src="/assets/visuals/gear-orbit.svg" alt=""><div><span class="kicker">COMPARE / PRODUCT GALLERY</span><h1>写真から、<br>比べる。</h1><p>実物写真と確認済みスペックを並べて比較。掲載写真はメーカー提供など利用可能なものに限ります。</p></div></section>
<section class="section"><div class="section__head"><div><span class="kicker">PRODUCTS</span><h2>ギア比較</h2></div><p>写真をクリックすると商品詳細へ。表示価格は参考価格で、最新の販売価格ではありません。</p></div>
<?php if($unavailable):?><p role="status">現在、商品データに接続できません。</p>
<?php elseif(!$items):?><p>比較対象の商品は現在準備中です。</p>
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
