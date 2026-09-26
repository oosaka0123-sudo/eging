<?php
header('Content-Type: application/xml; charset=utf-8');
$base='https://example.com';
$static=['/','/concept.php','/gear.php','/conditions.php','/simulator.php','/compare.php','/guide.php','/contact.php','/privacy.php'];
echo '<?xml version="1.0" encoding="UTF-8"?>'."
";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
foreach($static as $u){echo '<url><loc>'.htmlspecialchars($base.$u,ENT_XML1).'</loc></url>';}
$configFile=dirname(__DIR__).'/config/config.php';
if(is_file($configFile)){
 require dirname(__DIR__).'/app/bootstrap.php';
 foreach($pdo->query("SELECT slug,updated_at FROM articles WHERE status='published'") as $r){
  echo '<url><loc>'.htmlspecialchars($base.'/article.php?slug='.rawurlencode($r['slug']),ENT_XML1).'</loc><lastmod>'.date('c',strtotime($r['updated_at'])).'</lastmod></url>';
 }
}
echo '</urlset>';