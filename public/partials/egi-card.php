<?php
// Required: $slug (catalog key), $model (verified catalog row)
$cardPhoto=egl_photo($model);
$cardPurchase=egl_purchase($model);
$detailHref='/gear-compare-item.php?model='.rawurlencode((string)$slug);
?>
<article class="egl-product">
  <a class="egl-product__grid" href="<?=egl_h($detailHref)?>" aria-label="<?=egl_h($model['brand'].' '.$model['name'])?>の詳細を見る">
    <div class="egl-product__content">
      <div class="egl-product__meta"><span><?=egl_h($model['brand'])?></span><span class="egl-tag"><?=egl_h($model['type'])?> / 3.5号</span></div>
      <h3><?=egl_h($model['name'])?></h3>
      <p class="egl-product__desc"><?=egl_h($model['use'])?></p>
      <dl class="egl-product__facts">
        <div><dt>重量</dt><dd><?=egl_h($model['weight'])?><small>g</small></dd></div>
        <div><dt>沈下速度</dt><dd>約<?=egl_h($model['sink'])?><small>秒/m</small></dd></div>
      </dl>
      <span class="egl-product__more">詳細・向く条件を見る →</span>
    </div>
    <div class="egl-product__visual">
      <?php if($cardPhoto):?><img src="<?=egl_h($cardPhoto)?>" alt="<?=egl_h($model['brand'].' '.$model['name'])?>の製品写真" loading="lazy" decoding="async">
      <?php else:?><span class="egl-product__visual-mark" aria-hidden="true">3.5</span><span class="egl-product__visual-label">この商品の写真は未登録<br>下のメーカー公式写真リンクから確認できます</span><?php endif;?>
    </div>
  </a>
  <div class="egl-product__footer">
    <span>メーカー公表の参考値</span>
    <a href="<?=egl_h($model['source'])?>" target="_blank" rel="noopener noreferrer">メーカー公式の製品写真を見る ↗</a>
  </div>
</article>
