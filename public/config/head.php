<?php
/**
 * Shared <head> partial.
 *
 * Expects $page array:
 *   - title       (string)
 *   - description (string)
 *   - url         (string)
 *
 * Also expects $config array (loaded from config.php) for base_url.
 */

$pageTitle       = htmlspecialchars($page['title']       ?? 'FTIT');
$pageDescription = htmlspecialchars($page['description'] ?? '');
$pageUrl         = htmlspecialchars($page['url']         ?? ($config['base_url'] ?? 'https://ftit.com.br'));
$baseUrl         = rtrim($config['base_url'] ?? 'https://ftit.com.br', '/');
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?> | FTIT</title>
<meta name="description" content="<?= $pageDescription ?>">
<meta name="author" content="Flávio Tassan — FTIT">
<link rel="canonical" href="<?= $pageUrl ?>">

<link rel="alternate" hreflang="pt-BR" href="<?= $pageUrl ?>">
<link rel="alternate" hreflang="en"    href="<?= $pageUrl ?>?lang=en">

<meta property="og:type"        content="website">
<meta property="og:url"         content="<?= $pageUrl ?>">
<meta property="og:title"       content="<?= $pageTitle ?> | FTIT">
<meta property="og:description" content="<?= $pageDescription ?>">
<meta property="og:image"       content="<?= $baseUrl ?>/assets/img/og-image.png">
<meta property="og:locale"      content="pt_BR">
<meta property="og:site_name"   content="FTIT — f(t) it">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= $pageTitle ?> | FTIT">
<meta name="twitter:description" content="<?= $pageDescription ?>">

<link rel="icon" href="/favicon.ico" type="image/x-icon">
