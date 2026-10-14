<?php
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');
require __DIR__.'/_runtime.php';
require __DIR__.'/partials/egi-functions.php';
$base='https://eging.rss7.net';
$static=['/','/concept.php','/gear.php','/conditions.php','/compare.php','/guide.php','/contact.php','/privacy.php'];
$urls=$static;
foreach(array_keys(egl_egis()) as $slug)$urls[]='/gear-compare-item.php?model='.rawurlencode($slug);
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
foreach($urls as $url)echo '<url><loc>'.htmlspecialchars($base.$url,ENT_XML1,'UTF-8').'</loc></url>';
try {
 if(is_file(eging_config_file())){
  eging_require_bootstrap();
  foreach($pdo->query("SELECT slug,updated_at FROM articles WHERE status='published'") as $row){
   echo '<url><loc>'.htmlspecialchars($base.'/article.php?slug='.rawurlencode($row['slug']),ENT_XML1,'UTF-8').'</loc><lastmod>'.date('Y-m-d',strtotime($row['updated_at'])).'</lastmod></url>';
  }
 }
} catch(Throwable $e){
 error_log('EGING sitemap: optional CMS items skipped due to unavailable database');
}
echo '</urlset>';
