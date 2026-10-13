<?php
require __DIR__.'/_runtime.php';
// The product catalog is dynamic; prevent Lolipop Accelerator from serving stale HTML.
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
$slug=trim((string)($_GET['slug']??''));
$item=null;$unavailable=false;
try{if(!is_file(eging_config_file())){throw new RuntimeException('Database is not configured');}eging_require_bootstrap();$q=$pdo->prepare("SELECT * FROM gear_items WHERE slug=? AND status='published' LIMIT 1");$q->execute([$slug]);$item=$q->fetch(PDO::FETCH_ASSOC);}catch(Throwable $e){$unavailable=true;error_log('EGING detail: '.$e->getMessage());}
if(!$item){http_response_code($unavailable?503:404);}
function gh($text){return htmlspecialchars((string)$text,ENT_QUOTES,'UTF-8');}
$specs=$item?json_decode((string)($item['specs']??''),true):[];
if(!is_array($specs)){$specs=[];}
$photo=trim((string)($specs['image_url']??''));
if(!filter_var($photo,FILTER_VALIDATE_URL)||parse_url($photo,PHP_URL_SCHEME)!=='https'){$photo='';}
$pageTitle=$item?($item['brand'].' '.$item['name'].'｜エギングギアラボ'):'商品情報｜エギングギアラボ';
$pageDescription=$item?((string)($item['summary']??'')):'商品が見つかりません。';
if($photo){$ogImage=$photo;}
require __DIR__.'/partials/header.php';
?>
<main id="main">
<?php if(!$item):?><section class="page-hero"><div><span class="kicker">GEAR</span><h1><?= $unavailable?'現在利用できません':'商品が見つかりません' ?></h1><p><a class="cta" href="/compare.php">比較ページへ戻る</a></p></div></section>
<?php else:?>
<section class="page-hero gear-detail-hero"><div><span class="kicker">GEAR DETAIL / <?=gh(strtoupper($item['type']))?></span><h1><?=gh($item['name'])?></h1><p><?=gh($item['brand'])?> — <?=gh($item['summary']??'')?></p></div><div class="gear-detail-photo"><?php if($photo):?><img src="<?=gh($photo)?>" alt="<?=gh($item['brand'].' '.$item['name'])?>の商品写真" decoding="async" referrerpolicy="no-referrer" onerror="this.hidden=true;this.parentElement.classList.add('gear-tile__missing')"><?php else:?><span>商品写真を確認中</span><?php endif;?></div></section>
<section class="section"><div class="section__head"><div><span class="kicker">SPECIFICATIONS</span><h2>スペック</h2></div><p>条件に合うかをスペックで判断してください。未確認の項目は掲載しません。</p></div><dl class="gear-specs"><?php foreach(['weight_g'=>'自重（g）','sink_sec_per_m'=>'沈下速度（秒/m）','egi_size_min'=>'適合エギ最小号数','egi_size_max'=>'適合エギ最大号数','pe_min'=>'PE最小号数','pe_max'=>'PE最大号数'] as $key=>$label):if($item[$key]!==null):?><div><dt><?=gh($label)?></dt><dd><?=gh($item[$key])?></dd></div><?php endif;endforeach;?><?php if($item['price_yen']!==null):?><div><dt>参考価格</dt><dd>¥<?=number_format((int)$item['price_yen'])?></dd></div><?php endif;?></dl>
<?php if($item['source_url'] && filter_var($item['source_url'],FILTER_VALIDATE_URL)):?><p><a class="cta" href="<?=gh($item['source_url'])?>" target="_blank" rel="noopener noreferrer">メーカー・情報出典を確認 ↗</a></p><?php endif;?>
<?php if($item['source_checked_at']):?><p class="gear-note">スペック確認日：<?=gh($item['source_checked_at'])?></p><?php endif;?>
<?php if(!empty($specs['image_source'])):?><p class="gear-note">写真出典：<?=gh($specs['image_source'])?></p><?php endif;?>
<p><a class="cta" href="/compare.php">← 比較一覧へ</a></p></section>
<?php endif;?></main>
<?php require __DIR__.'/partials/footer.php';?>
