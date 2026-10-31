<?php
require __DIR__ . '/includes/layout.php';
header('Content-Type: text/plain; charset=utf-8');
echo "User-agent: *\nAllow: /\nDisallow: /thank-you/\nDisallow: /lead.php\n\nSitemap: " . abs_url('/sitemap.xml') . "\n";
