(() => {
  'use strict';

  // ===== i18n =====
  let currentLang = localStorage.getItem('ftit-lang') || 'pt';
  let translations = {};

  async function loadLanguage(lang) {
    try {
      const res = await fetch(`lang/${lang}.json`);
      translations = await res.json();
      applyTranslations();
      currentLang = lang;
      localStorage.setItem('ftit-lang', lang);
      updateLangToggle();
      // Re-run typewriter with new language
      if (lang !== 'pt' || typewriterDone) {
        runTypewriter(translations['splash.subtitle'] || '');
      }
    } catch (e) {
      console.error('Failed to load language:', e);
    }
  }

  function applyTranslations() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      if (translations[key]) {
        el.textContent = translations[key];
      }
    });
  }

  function updateLangToggle() {
    const toggle = document.getElementById('langToggle');
    if (!toggle) return;
    const active = toggle.querySelector('.lang-active');
    const inactive = toggle.querySelector('.lang-inactive');
    if (currentLang === 'pt') {
      active.textContent = 'PT';
      inactive.textContent = 'EN';
    } else {
      active.textContent = 'EN';
      inactive.textContent = 'PT';
    }
  }

  // ===== TYPEWRITER =====
  let typewriterDone = false;
  let typewriterTimeout = null;

  function runTypewriter(text) {
    const el = document.getElementById('typewriter');
    if (!el || !text) return;

    // Clear previous
    if (typewriterTimeout) clearTimeout(typewriterTimeout);
    el.textContent = '';

    let i = 0;
    function type() {
      if (i < text.length) {
        el.textContent += text.charAt(i);
        i++;
        typewriterTimeout = setTimeout(type, 50);
      } else {
        typewriterDone = true;
      }
    }
    type();
  }

  // ===== MOBILE NAV =====
  function initMobileNav() {
    const toggle = document.getElementById('navToggle');
    const links = document.getElementById('navLinks');
    if (!toggle || !links) return;

    toggle.addEventListener('click', () => {
      toggle.classList.toggle('active');
      links.classList.toggle('open');
    });

    // Close menu on link click
    links.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        toggle.classList.remove('active');
        links.classList.remove('open');
      });
    });
  }

  // ===== SCROLL ANIMATIONS (Intersection Observer) =====
  function initScrollAnimations() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -50px 0px'
    });

    elements.forEach(el => observer.observe(el));
  }

  // ===== SMOOTH SCROLL =====
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', (e) => {
        const targetId = link.getAttribute('href');
        if (targetId === '#') return;
        const target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          const navHeight = document.getElementById('navbar').offsetHeight;
          const targetPos = target.getBoundingClientRect().top + window.scrollY - navHeight;
          window.scrollTo({ top: targetPos, behavior: 'smooth' });
        }
      });
    });
  }

  // ===== NAVBAR SCROLL EFFECT =====
  function initNavbarScroll() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    let lastScroll = 0;
    window.addEventListener('scroll', () => {
      const current = window.scrollY;
      if (current > 100) {
        navbar.style.background = 'rgba(13, 13, 15, 0.95)';
      } else {
        navbar.style.background = 'rgba(13, 13, 15, 0.85)';
      }
      lastScroll = current;
    }, { passive: true });
  }

  // ===== LANG TOGGLE =====
  function initLangToggle() {
    const toggle = document.getElementById('langToggle');
    if (!toggle) return;

    toggle.addEventListener('click', () => {
      const newLang = currentLang === 'pt' ? 'en' : 'pt';
      loadLanguage(newLang);
    });
  }

  // ===== INIT =====
  function init() {
    initMobileNav();
    initSmoothScroll();
    initScrollAnimations();
    initNavbarScroll();
    initLangToggle();

    // Load saved language and start typewriter
    loadLanguage(currentLang).then(() => {
      const subtitle = translations['splash.subtitle'] || 'Bem vindo, pronto para o próximo passo?';
      runTypewriter(subtitle);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
