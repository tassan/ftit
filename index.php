<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="FTIT — Consultoria de transformação digital. Sites profissionais, consultoria e suporte para seu negócio crescer online.">
  <title>FTIT — f(t) it</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/svg+xml" href="ft_logo.svg">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<?php
$config = require __DIR__ . '/config/env.php';
$whatsapp_url = 'https://wa.me/' . htmlspecialchars($config['whatsapp'], ENT_QUOTES, 'UTF-8');
$email_to     = $config['email'];
?>
<body>

<!--
⠈⠻⡽⣿⣿⣿⣷⡄⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⡀⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠸⢦⡻⢿⣿⣿⣶⣦⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣄⣴⣴⣶⡶⠾⠿⣛⣋⣭⣿⠯⣙⠀⠀⠀⠀⠀⠀⠀⢀⣀⠀⢀⡀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠈⠛⣮⡹⣿⣿⣿⣷⣿⣆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣾⣿⣿⣶⣶⣶⣾⣿⣿⣿⣿⢿⢟⣷⠆⠀⠀⠀⠀⣠⣰⣼⣿⣿⣿⣿⣿⣿⣷⣾⣶⣷⣦⡄⠀
⠀⠀⠀⠀⠀⠈⠱⣼⣿⢻⣽⡿⣯⡽⣷⡄⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣤⣾⣿⣿⣿⣿⡿⣿⢿⣿⢿⡻⣟⠝⣱⣾⠛⠀⡀⣀⣶⣿⣿⣿⣿⣿⣽⣿⣷⣿⣾⣿⣾⣿⣾⣿⣿⣷
⠀⠀⠀⠀⠀⠀⠀⠙⢻⡆⢿⣿⣟⣿⡭⠿⡗⠀⠀⠀⠀⠀⠀⠀⡀⣀⣠⣴⣿⣿⣿⣿⣿⡿⣽⣻⡾⡫⡪⣢⣾⠿⢫⣴⣶⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣳⡿
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⢾⣿⣯⣿⠉⣻⣾⣟⢦⣄⠀⢀⣠⣶⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣳⡿⣿⢡⣿⣟⣿⢶⠾⣿⣿⣿⣿⣿⣻⣿⣿⣿⣿⣿⣿⣿⣿⣿⢿⡿⣿⣻⣾⡑⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠐⢿⠿⠽⣄⢿⣿⣻⣷⣖⣽⠟⣻⣿⣿⣿⣿⢿⡿⣿⣻⡷⠯⠿⢷⡻⢿⣿⣿⣿⣿⣿⣿⣳⣿⣿⣿⣿⣿⣿⣿⡿⣟⡿⣾⣽⠾⢹⣯⣽⣾⣿⣿⣇⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⣿⣾⣿⣾⣿⡱⣼⣿⣟⢟⣻⣯⣻⣿⣶⣿⣿⣿⣿⣿⣿⠿⡟⣛⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⢿⣽⡻⡽⣣⣽⣿⣿⣿⣿⣿⣿⣿⣿⣿⡀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢙⡝⣿⣿⣿⡟⢻⡖⢉⣼⣿⣿⣿⣿⣿⡿⠟⣩⢑⣢⣷⣿⣿⣿⣿⣿⣿⣿⣿⣻⣟⡿⣞⡿⣲⣵⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣻⣿⡇
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⣧⣭⣓⣋⣶⣿⣶⣾⣿⣿⣿⣿⢿⣿⣷⣼⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣾⣯⢷⣯⢿⢍⣾⠿⢿⣟⢯⡻⣿⣿⣿⣿⣿⣿⣿⣿⢿⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣾⣿⣿⣻⣿⣿⣿⣿⣿⣟⣿⠿⠗⢋⣩⣟⣋⣻⣿⣻⣯⡿⡽⢯⡿⣽⢯⡿⣽⣾⢟⢞⣩⣾⡟⠀⠀⠹⡞⣵⢫⣿⢿⣿⡿⣿⡽⢯⡏⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣼⣿⣿⣿⣿⣏⣴⣦⣤⣤⢤⣂⣌⣴⣯⡙⣽⣠⡿⣵⣿⣿⣿⣷⣶⣶⣿⢯⡿⣝⢿⣪⣶⣿⣿⣿⣿⡄⠀⠀⠘⢧⡻⣜⢯⡻⣝⢶⣛⡟⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⢻⣿⣿⣿⣿⣿⣿⣯⠷⣭⠇⡰⣾⣿⣿⣜⢡⣿⣿⡩⠿⠿⡿⠉⣹⣿⣿⣿⣿⣷⣾⡿⣿⣿⣿⡿⣟⡯⢿⡤⠀⠀⠈⢷⢭⢞⡱⣏⢾⡹⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣮⡁⠻⣿⣿⣿⣿⣿⣿⣽⣶⣿⠽⣿⣿⣷⣿⣿⣿⡏⢻⣾⣿⣷⣾⣿⣿⣿⣿⣿⡋⢉⢻⣿⣯⣯⠳⣝⠺⣭⠻⡥⠀⠀⠀⠻⣊⠷⣙⢮⡇⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠿⣆⠌⠻⣿⣿⣿⣿⣿⣿⣿⣷⣽⣿⣿⣿⣿⣿⣧⢀⢻⣿⣿⣿⣿⣿⣟⣿⣟⠡⠀⠄⠙⢿⣿⣷⣬⠳⡭⡄⠹⡥⠀⠀⠀⠙⡵⣩⢎⡇⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠹⢷⣅⠌⠿⠿⣿⣿⣿⣿⣿⣿⣿⢿⡟⢿⣿⣻⣤⣼⣿⣷⠟⣨⡿⢮⣿⣿⢀⠈⠀⠘⠠⢹⣟⣿⣷⣱⠃⠀⠹⡥⠀⠀⠀⠈⠑⠉⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⠿⣷⡀⢂⠄⠠⢀⠂⣒⣼⣿⡘⡇⢌⣫⣿⣿⣿⢻⣆⣼⢿⡹⣿⢿⠃⠀⠀⠀⠀⠀⢸⣯⡜⣿⢿⣧⡀⠀⠻⡥⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠻⠿⠶⡬⣤⣥⣂⣿⣏⢿⣟⣵⣿⣿⡿⡻⣵⡿⢿⣿⣦⣿⣿⣏⣀⠀⠠⠀⠁⢀⠸⡞⠽⣎⢿⣟⣿⣦⡀⠹⣥⡀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢿⡞⣽⣻⣿⣻⣿⣱⡟⠋⣠⠟⠿⠿⣿⣿⣿⣿⣿⣷⣄⠀⠀⠀⢻⡈⢚⡯⡆⠳⢻⣧⠀⠈⠳⣴⣤⣀⣀⣀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣿⠹⣿⡻⢯⡿⡼⢀⡼⠃⢌⠠⢁⠒⣌⢻⡹⣏⡿⣿⣷⡀⠁⠈⣇⠀⠱⡃⠀⢍⣿⡄⠀⢀⡟⠁⠠⢸⣿⣷⣦
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠨⣷⣟⢦⡛⣧⢽⢣⡿⢿⣧⡂⡐⠈⡔⣌⠶⣑⣿⣟⣯⡿⢭⣽⣶⣼⣀⡀⠈⠀⠀⢻⣿⣷⣌⣇⠀⠀⣸⣿⣿⣿
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠰⡏⢻⣷⡟⣬⡏⣾⠇⠈⠛⢏⣉⣍⡽⡖⠟⠋⠀⠈⠀⠀⠀⠀⠀⠈⠉⠛⠛⣶⣦⣄⡙⡿⠹⣿⣦⠀⢺⣿⣿⡏
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣰⠛⠈⣿⣟⣲⢱⡃⠀⠀⠀⠀⠀⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⡮⠿⣿⣷⣄⠐⣿⣆⣸⣿⣿⡇
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⠆⠀⢿⣇⡏⣞⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠛⢿⣜⠿⣿⣷⣌⣿⣿⣿⡟⣘
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢠⢷⠀⠀⢸⣷⡿⠅⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠛⢿⣯⣻⣿⣿⣿⣿⠐⡌
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⠄⠀⣀⣽⣿⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠙⠻⣮⣟⠻⢏⡜⡐
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠸⣷⣿⣷⢿⠏⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠙⠿⢞⡦⣵
      ┌─────────────────────────────────────────────────────────────────────┐
      │   Will you enter in the f*cking robot?                              │
      │   Send your resume to contato@ftit.com.br                          │
      └─────────────────────────────────────────────────────────────────────┘
-->

  <!-- Navigation -->
  <nav id="navbar">
    <div class="nav-container">
      <a href="#splash" class="nav-logo">
        <span class="logo-ft">f(t)</span><span class="logo-it"> it</span>
      </a>
      <button class="nav-toggle" id="navToggle" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <ul class="nav-links" id="navLinks">
        <li><a href="#services" data-i18n="nav.services">Serviços</a></li>
        <li><a href="#how" data-i18n="nav.how">Como Funciona</a></li>
        <li><a href="#differentials" data-i18n="nav.differentials">Diferenciais</a></li>
        <li><a href="#contact" data-i18n="nav.contact">Contato</a></li>
        <li>
          <button class="lang-toggle" id="langToggle">
            <span class="lang-active">PT</span> | <span class="lang-inactive" data-i18n="nav.lang_toggle">EN</span>
          </button>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Section 1 — Splash -->
  <section id="splash">
    <div class="hud-grid"></div>
    <div class="splash-content">
      <h1 class="splash-logo glitch" data-text="f(t) it">
        <span class="logo-ft">f(t)</span><span class="logo-it"> it</span>
      </h1>
      <p class="splash-subtitle" id="typewriter"></p>
      <a href="#hero" class="splash-cta" data-i18n="splash.cta">Conhecer</a>
      <div class="scroll-arrow">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </div>
    </div>
  </section>

  <!-- Section 2 — Hero -->
  <section id="hero">
    <div class="container">
      <div class="section-content animate-on-scroll">
        <h2 class="glitch-hover" data-i18n="hero.headline">Seu site e seus processos trabalhando por você (não o contrário).</h2>
        <p class="hero-subhead" data-i18n="hero.subhead">Implantamos site, automações e sistemas internos para reduzir retrabalho e aumentar conversão.</p>
        <p class="hero-description" data-i18n="hero.description">Atendemos negócios em crescimento que precisam de site + automação + sistema, sem dependência de agência. +10 anos construindo software e automações para negócio. Entregas rápidas com base sólida.</p>
        <div class="hero-ctas">
          <a href="/diagnostico.php" class="btn-primary" data-i18n="hero.cta_diagnosis">Agendar diagnóstico (30 min)</a>
          <a href="<?php echo $whatsapp_url; ?>" target="_blank" rel="noopener" class="btn-secondary" data-i18n="hero.cta_whatsapp">WhatsApp</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 3 — Services -->
  <section id="services">
    <div class="container">
      <h2 class="section-title glitch-hover animate-on-scroll" data-i18n="services.title">Pacotes</h2>
      <div class="cards-grid">
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="1.5">
              <rect x="2" y="3" width="20" height="14" rx="2"/>
              <line x1="8" y1="21" x2="16" y2="21"/>
              <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </div>
          <h3 data-i18n="services.websites.title">Site Estratégico</h3>
          <p data-i18n="services.websites.desc">Landing page ou site institucional orientado a conversão. SEO base, analytics e copy focada em resultado. Entregue em até 2 semanas.</p>
        </div>
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <line x1="2" y1="12" x2="22" y2="12"/>
              <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
          </div>
          <h3 data-i18n="services.consulting.title">Diagnóstico + Roadmap</h3>
          <p data-i18n="services.consulting.desc">Mapeamento de processos, oportunidades e plano de 90 dias com priorização e estimativa. Clareza antes de qualquer investimento.</p>
        </div>
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="1.5">
              <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
          </div>
          <h3 data-i18n="services.support.title">Evolução e Operação</h3>
          <p data-i18n="services.support.desc">Dashboards, integrações, automação e sistemas internos — com base sólida e evolução contínua. Processo claro, escopo bem definido, entrega por fase.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 4 — How It Works -->
  <section id="how">
    <div class="container">
      <h2 class="section-title glitch-hover animate-on-scroll" data-i18n="how.title">Como Funciona</h2>
      <div class="timeline">
        <div class="timeline-step animate-on-scroll">
          <div class="step-number">01</div>
          <div class="step-content">
            <h3 data-i18n="how.step1.title">01 _ Conversa</h3>
            <p data-i18n="how.step1.desc">Você nos conta sua ideia, seu negócio e seus objetivos. Sem questionários interminaveis — uma conversa direta.</p>
          </div>
        </div>
        <div class="timeline-step animate-on-scroll">
          <div class="step-number">02</div>
          <div class="step-content">
            <h3 data-i18n="how.step2.title">02 _ Construção</h3>
            <p data-i18n="how.step2.desc">Desenvolvemos seu projeto com atenção a cada detalhe. Você acompanha o progresso em tempo real.</p>
          </div>
        </div>
        <div class="timeline-step animate-on-scroll">
          <div class="step-number">03</div>
          <div class="step-content">
            <h3 data-i18n="how.step3.title">03 _ Entrega</h3>
            <p data-i18n="how.step3.desc">Seu site vai ao ar, pronto para receber clientes. Sem taxas mensais, sem surpresas.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 5 — Differentials -->
  <section id="differentials">
    <div class="container">
      <h2 class="section-title glitch-hover animate-on-scroll" data-i18n="differentials.title">Diferenciais</h2>
      <div class="cards-grid cards-grid-4">
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#FF4500" stroke-width="1.5">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
          </div>
          <h3 data-i18n="differentials.fast.title">Entrega Rápida</h3>
          <p data-i18n="differentials.fast.desc">Seu site pronto em até 2 semanas. Sem atrasos, sem desculpas.</p>
        </div>
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#FF4500" stroke-width="1.5">
              <line x1="12" y1="1" x2="12" y2="23"/>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
          </div>
          <h3 data-i18n="differentials.price.title">Preço Transparente</h3>
          <p data-i18n="differentials.price.desc">Você sabe exatamente quanto vai pagar desde o primeiro contato. Sem custos escondidos.</p>
        </div>
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#FF4500" stroke-width="1.5">
              <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
              <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
          </div>
          <h3 data-i18n="differentials.nomonthlyfee.title">Sem Mensalidade</h3>
          <p data-i18n="differentials.nomonthlyfee.desc">Pagou, é seu. Sem cobranças recorrentes ou contratos de fidelidade.</p>
        </div>
        <div class="card animate-on-scroll">
          <div class="card-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#FF4500" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <h3 data-i18n="differentials.human.title">Suporte Humano</h3>
          <p data-i18n="differentials.human.desc">Atendimento direto com quem entende do assunto. Nada de bots ou filas de espera.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 6 — Contact -->
  <section id="contact">
    <div class="container">
      <div class="contact-content animate-on-scroll">
        <h2 class="section-title glitch-hover" data-i18n="contact.title">Contato</h2>
        <p class="contact-subtitle" data-i18n="contact.subtitle">Pronto para dar o próximo passo? Fale com a gente.</p>
        <div class="contact-buttons">
          <a href="<?php echo $whatsapp_url; ?>" target="_blank" rel="noopener" class="btn-primary btn-whatsapp" data-i18n="contact.whatsapp">Chamar no WhatsApp</a>
          <a href="mailto:<?php echo $email_to; ?>" class="btn-secondary" data-i18n="contact.email"><span class="__cf_email__" data-cfemail="12717d7c6673667d5274667b663c717d7f3c7060">[email&#160;protected]</span></a>
        </div>
        <p class="terminal-line">> <span class="terminal-cursor">_</span></p>
      </div>
    </div>
  </section>

  <!-- Section 7 — Footer -->
  <footer id="footer">
    <div class="container">
      <div class="footer-logo">
        <span class="logo-ft">f(t)</span><span class="logo-it"> it</span>
      </div>
      <p class="footer-cnpj" data-i18n="footer.cnpj">CNPJ: 55.191.137/0001-62</p>
      <p class="footer-rights">&copy; <?php echo date('Y'); ?> FTIT. <span data-i18n="footer.rights">Todos os direitos reservados.</span></p>
    </div>
  </footer>

  <script src="assets/js/script.js"></script>

  <!-- Floating WhatsApp Button -->
  <a href="<?php echo $whatsapp_url; ?>" target="_blank" rel="noopener" class="floating-whatsapp" aria-label="WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="#fff">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.55