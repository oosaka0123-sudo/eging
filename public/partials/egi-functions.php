<?php
// One public comparison data source while the private CMS authentication is being repaired.
// Do not put image URLs or affiliate URLs here until rights and partner URLs are verified.
if(!function_exists('egl_h')){
 function egl_h($x){return htmlspecialchars((string)$x,ENT_QUOTES,'UTF-8');}
}
function egl_egis(){return require __DIR__.'/../_comparison_egis.php';}
function egl_scenes(){
 return [
  'shallow'=>['label'=>'浅場・藻場','detail'=>'水深が浅い場所で、ゆっくり落とすタイプを比較。','reason'=>'シャロー／スーパーシャローを中心に、標準タイプを比較基準として加えています。根掛かりや流れの強さによって選択は変わります。','slugs'=>['yamashita-k-super-shallow','yamashita-k-shallow','yamashita-k-basic'],'note'=>'エギ王Kベーシックは浅場向けの推奨ではなく、沈下速度を比較するための基準です。'],
  'standard'=>['label'=>'堤防・中層','detail'=>'標準的な沈下速度の3モデルを比べる。','reason'=>'比較対象の中からノーマル／ベーシック系3モデルを抽出しました。水深・潮の流れで適合性は変わります。','slugs'=>['yamashita-k-basic','shimano-clinch-flashboost','daiwa-amorous-joint'],'note'=>'沈下速度が近くても動きや操作感まで同じとは限りません。'],
  'current'=>['label'=>'深め・潮流','detail'=>'沈下が比較的速い3モデルを比較の入口に。','reason'=>'今回の5モデルに専用ディープタイプは含まれていません。ベーシック／ノーマルから沈下速度を比較できますが、深場・速潮への適合を保証しません。','slugs'=>['yamashita-k-basic','shimano-clinch-flashboost','daiwa-amorous-joint'],'note'=>'深場・速潮ではディープ専用エギも候補です。今回の掲載モデルだけで適合製品を確定しないでください。'],
  'all'=>['label'=>'全5モデル','detail'=>'沈下速度の違いが見える5モデル比較。','reason'=>'3.5号の5モデルをメーカー公表の重量・沈下速度で横並びにしています。実釣ランキングではありません。','slugs'=>array_keys(egl_egis()),'note'=>'沈下速度の数値が大きいほど、静水時の目安としてはゆっくり沈みます。']
 ];
}
function egl_photo($model){
 $url=$model['photo_url']??'';
 $permit=$model['photo_permission']??false;
 if($permit!==true||!is_string($url)||!filter_var($url,FILTER_VALIDATE_URL))return '';
 return parse_url($url,PHP_URL_SCHEME)==='https'?$url:'';
}
function egl_purchase($model){
 $url=$model['affiliate_url']??'';
 if(($model['affiliate_verified']??false)!==true||!filter_var($url,FILTER_VALIDATE_URL))return '';
 return parse_url($url,PHP_URL_SCHEME)==='https'?$url:'';
}
