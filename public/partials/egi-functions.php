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
  'current'=>['label'=>'深め・潮流','detail'=>'ディープ専用と標準タイプの沈下速度を比較。','reason'=>'深場・潮流向けのディープ専用1モデルを、標準タイプ2モデルと比較します。水深・流速・タックルによって選択は変わります。','slugs'=>['shimano-clinch-deep-flashboost','yamashita-k-basic','shimano-clinch-flashboost'],'note'=>'ディープ専用は速く沈む参考値ですが、どの水深でも底が取れるとは限りません。'],
  'all'=>['label'=>'基本5モデル','detail'=>'5つの定番タイプを重量・沈下速度で比較。','reason'=>'基本5モデルをメーカー公表の重量・沈下速度で横並びにしています。ディープ専用モデルは「深め・潮流」から追加で比較できます。','slugs'=>['yamashita-k-basic','yamashita-k-shallow','yamashita-k-super-shallow','shimano-clinch-flashboost','daiwa-amorous-joint'],'note'=>'数値が大きいほど静水時の目安としてゆっくり沈みます。深場にはディープ専用モデルも検討してください。']
 ];
}
function egl_photo($model){
 // Never treat an arbitrary URL as proof of usage rights.
 // Product image publication requires a reviewed source, explicit permission and safe image path.
 $url=$model['photo_url']??'';
 if(($model['photo_permission']??false)!==true || empty($model['photo_rights_source']))return '';
 // Yahoo Shopping affiliate imagery can be shown only with a verified affiliate link.
 if(($model['photo_license_type']??'direct')==='yahoo-affiliate' && egl_purchase($model)==='')return '';
 if(!is_string($url))return '';
 if(preg_match('#^/assets/img/products/[a-z0-9-]+\\.(?:webp|jpg|jpeg|png)$#',$url)){
  $path=realpath(__DIR__.'/../'.ltrim($url,'/'));
  $root=realpath(__DIR__.'/../assets/img/products');
  if($root && $path && str_starts_with($path,$root.DIRECTORY_SEPARATOR) && is_file($path))return $url;
  return '';
 }
 if(!filter_var($url,FILTER_VALIDATE_URL) || parse_url($url,PHP_URL_SCHEME)!=='https')return '';
 return $url;
}
function egl_purchase($model){
 $url=$model['affiliate_url']??'';
 if(($model['affiliate_verified']??false)!==true||!filter_var($url,FILTER_VALIDATE_URL))return '';
 return parse_url($url,PHP_URL_SCHEME)==='https'?$url:'';
}
