@php
    $page = $page ?? [
        'title' => 'Sites e Automação para Pequenas Empresas',
        'description' => 'Seu site e seus processos trabalhando por você. Desenvolvimento web e automação com 10+ anos de experiência.',
        'url' => config('ftit.base_url'),
    ];
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ e($page['title']) }} | FTIT</title>
<meta name="description" content="{{ e($page['description']) }}">
<meta name="author" content="Flávio Tassan — FTIT">
<link rel="canonical" href="{{ e($page['url']) }}">

<link rel="alternate" hreflang="pt-BR" href="{{ e($page['url']) }}">
<link rel="alternate" hreflang="en" href="{{ e($page['url']) }}?lang=en">

<meta property="og:type" content="website">
<meta property="og:url" content="{{ e($page['url']) }}">
<meta property="og:title" content="{{ e($page['title']) }} | FTIT">
<meta property="og:description" content="{{ e($page['description']) }}">
<meta property="og:image" content="{{ rtrim(config('ftit.base_url'), '/') }}/assets/img/og-image.png">
<meta property="og:locale" content="pt_BR">
<meta property="og:site_name" content="FTIT — f(t) it">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ e($page['title']) }} | FTIT">
<meta name="twitter:description" content="{{ e($page['description']) }}">
<meta name="twitter:image" content="{{ rtrim(config('ftit.base_url'), '/') }}/assets/img/og-image.png">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "FTIT — f(t) it",
  "description": "Desenvolvimento web e automação para pequenas empresas",
  "url": "{{ rtrim(config('ftit.base_url'), '/') }}",
  "email": "contato@ftit.com.br",
  "areaServed": "BR",
  "serviceType": ["Web Development", "Business Automation", "Digital Transformation"]
}
</script>

<link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/ft_logo.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

@stack('head')

