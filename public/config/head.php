<?php
/**
 * Fragmento <head> reutilizável.
 * Cada página define $page antes de incluir:
 *
 * $page = [
 *   'title'       => 'Título da página',
 *   'description' => 'Descrição para SEO.',
 *   'url'         => BASE_URL . '/caminho',
 * ];
 * require_once __DIR__ . '/config/head.php';
 */
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($page['title']) ?> | FTIT</title>
<meta name="description" content="<?= htmlspecialchars($page['description']) ?>">
<meta name="author" content="Flávio Tassan — FTIT">
<link rel="canonical" href="<?= htmlspecialchars($page['url']) ?>">

<link rel="alternate" hreflang="pt-BR" href="<?= htmlspecialchars($page['url']) ?>">
<link rel="alternate" hreflang="en"    href="<?= htmlspecialchars($page['url']) ?>?lang=en">

<meta property="og:type"        content="website">
<meta property="og:url"         content="<?= htmlspecialchars($page['url']) ?>">
<meta property="og:title"       content="<?= htmlspecialchars($page['title']) ?> | FTIT">
<meta property="og:description" content="<?= htmlspecialchars($page['description']) ?>">
<meta property="og:image"       content="<?= BASE_URL ?>/assets/img/og-image.png">
<meta property="og:locale"      content="pt_BR">
<meta property="og:site_name"   content="FTIT — f(t) it">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= htmlspecialchars($page['title']) ?> | FTIT">
<meta name="twitter:description" content="<?= htmlspecialchars($page['description']) ?>">
<meta name="twitter:image"       content="<?= BASE_URL ?>/assets/img/og-image.png">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "FTIT — f(t) it",
  "description": "Desenvolvimento web e automação para pequenas empresas",
  "url": "https://ftit.com.br",
  "email": "contato@ftit.com.br",
  "areaServed": "BR",
  "serviceType": ["Web Development", "Business Automation", "Digital Transformation"]
}
</script>

<link rel="icon" type="image/svg+xml" href="/assets/img/ft_logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
