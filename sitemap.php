<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/layout.php';
header('Content-Type: application/xml; charset=utf-8');
$urls = ['/' => '1.0'];
foreach (CITIES as $slug => $c) $urls["/$slug/"] = '0.8';
$urls['/guides/'] = '0.6';
foreach (GUIDES as $slug => $t) $urls["/guides/$slug/"] = '0.6';
$urls['/accessibility/'] = '0.2';
$urls['/privacy/'] = '0.2';
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u => $pr) echo '  <url><loc>' . e(abs_url($u)) . '</loc><priority>' . $pr . "</priority></url>\n";
echo "</urlset>\n";
