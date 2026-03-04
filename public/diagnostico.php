<?php
$config = require __DIR__ . '/config/config.php';
$page = [
    'title'       => 'Diagnóstico Digital Gratuito',
    'description' => 'Descubra em 2 minutos os principais gaps digitais do seu negócio e receba um parecer personalizado.',
    'url'         => BASE_URL . '/diagnostico',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require_once __DIR__ . '/config/head.php'; ?>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/diagnostico.css">
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <a href="/" class="logo">
            <span class="back-arrow">←</span>
            <span class="ft">f(t)</span><span class="it"> it</span>
        </a>
        <h1 data-i18n-html="diag.title">Diagnóstico <span class="highlight">Express</span></h1>
        <p data-i18n-html="diag.subtitle">4 perguntas rápidas. Depois, <strong>30 minutos de conversa direta</strong> sobre seu negócio.</p>
    </div>

    <!-- Progress -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-label" id="progressLabel">Etapa 1 de 4</div>
    </div>

    <!-- Step 1: Name & Business -->
    <div class="step active" id="step1">
        <div class="step-number">01_</div>
        <div class="step-question" data-i18n="diag.step1.question">Quem é você e qual seu negócio?</div>
        <div class="step-hint" data-i18n="diag.step1.hint">Só o básico pra gente não começar do zero na conversa.</div>

        <div class="input-row">
            <div class="input-group">
                <label class="input-label" for="nome" data-i18n="diag.step1.name_label">Seu nome</label>
                <input type="text" id="nome" class="input-field" placeholder="Ex: Ana Silva" data-i18n-placeholder="diag.step1.name_placeholder">
            </div>
            <div class="input-group">
                <label class="input-label" for="negocio" data-i18n="diag.step1.biz_label">Nome do negócio</label>
                <input type="text" id="negocio" class="input-field" placeholder="Ex: Clínica Renova" data-i18n-placeholder="diag.step1.biz_placeholder">
            </div>
        </div>

        <div class="input-row">
            <div class="input-group">
                <label class="input-label" for="telefone" data-i18n="diag.step1.phone_label">WhatsApp / Telefone</label>
                <input type="tel" id="telefone" class="input-field" placeholder="Ex: (11) 99999-9999" data-i18n-placeholder="diag.step1.phone_placeholder">
            </div>
            <div class="input-group">
                <label class="input-label" for="email" data-i18n="diag.step1.email_label">E-mail (opcional)</label>
                <input type="email" id="email" class="input-field" placeholder="Ex: ana@clinica.com.br" data-i18n-placeholder="diag.step1.email_placeholder">
            </div>
        </div>

        <div class="btn-row">
            <button class="btn btn-primary" onclick="nextStep(1)" id="btn1" data-i18n="diag.next">Próximo →</button>
        </div>
    </div>

    <!-- Step 2: Segment -->
    <div class="step" id="step2">
        <div class="step-number">02_</div>
        <div class="step-question" data-i18n="diag.step2.question">Qual o segmento do seu negócio?</div>
        <div class="step-hint" data-i18n="diag.step2.hint">Isso me ajuda a trazer referências relevantes na conversa.</div>

        <div class="options-grid">
            <div class="option-card" onclick="selectOption(this, 'segmento')">
                <div class="option-title" data-i18n="diag.seg.health.title">Saúde</div>
                <div class="option-desc" data-i18n="diag.seg.health.desc">Clínica, consultório, laboratório</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'segmento')">
                <div class="option-title" data-i18n="diag.seg.beauty.title">Beleza & Estética</div>
                <div class="option-desc" data-i18n="diag.seg.beauty.desc">Salão, studio, spa, barbearia</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'segmento')">
                <div class="option-title" data-i18n="diag.seg.retail.title">Varejo</div>
                <div class="option-desc" data-i18n="diag.seg.retail.desc">Loja física, e-commerce, atacado</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'segmento')">
                <div class="option-title" data-i18n="diag.seg.services.title">Serviços</div>
                <div class="option-desc" data-i18n="diag.seg.services.desc">Advocacia, contabilidade, consultoria</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'segmento')">
                <div class="option-title" data-i18n="diag.seg.food.title">Alimentação</div>
                <div class="option-desc" data-i18n="diag.seg.food.desc">Restaurante, delivery, confeitaria</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'segmento')">
                <div class="option-title" data-i18n="diag.seg.other.title">Outro</div>
                <div class="option-desc" data-i18n="diag.seg.other.desc">Me conta na conversa</div>
            </div>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(2)" data-i18n="diag.back">← Voltar</button>
            <button class="btn btn-primary" onclick="nextStep(2)" id="btn2" disabled data-i18n="diag.next">Próximo →</button>
        </div>
    </div>

    <!-- Step 3: Has website? -->
    <div class="step" id="step3">
        <div class="step-number">03_</div>
        <div class="step-question" data-i18n="diag.step3.question">Você já tem site?</div>
        <div class="step-hint" data-i18n="diag.step3.hint">Sem julgamento. A maioria dos negócios que atendo não tem — e tudo bem.</div>

        <div class="options-grid">
            <div class="option-card" onclick="selectOption(this, 'temSite')">
                <div class="option-title" data-i18n="diag.site.none.title">Não tenho</div>
                <div class="option-desc" data-i18n="diag.site.none.desc">Nunca tive</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'temSite')">
                <div class="option-title" data-i18n="diag.site.bad.title">Tenho, mas...</div>
                <div class="option-desc" data-i18n="diag.site.bad.desc">Tá desatualizado ou feio</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'temSite')">
                <div class="option-title" data-i18n="diag.site.good.title">Tenho e funciona</div>
                <div class="option-desc" data-i18n="diag.site.good.desc">Mas quero melhorar</div>
            </div>
            <div class="option-card" onclick="selectOption(this, 'temSite')">
                <div class="option-title" data-i18n="diag.site.ig.title">Só Instagram</div>
                <div class="option-desc" data-i18n="diag.site.ig.desc">É minha presença digital</div>
            </div>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(3)" data-i18n="diag.back">← Voltar</button>
            <button class="btn btn-primary" onclick="nextStep(3)" id="btn3" disabled data-i18n="diag.next">Próximo →</button>
        </div>
    </div>

    <!-- Step 4: Pain point -->
    <div class="step" id="step4">
        <div class="step-number">04_</div>
        <div class="step-question" data-i18n="diag.step4.question">O que mais te incomoda hoje?</div>
        <div class="step-hint" data-i18n="diag.step4.hint">Sobre sua presença digital, atendimento online, ou qualquer processo do negócio. 1-2 frases tá ótimo.</div>

        <div class="input-group">
            <textarea id="dor" class="input-field" placeholder="Ex: Perco clientes porque não apareço no Google..." data-i18n-placeholder="diag.step4.placeholder"></textarea>
        </div>

        <div class="consent-wrapper">
            <label class="consent-label">
                <input type="checkbox" id="lgpd-consent">
                <span data-i18n-html="diag.consent">Li e concordo com a <a href="/privacidade.php" target="_blank">Política de Privacidade</a></span>
            </label>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(4)" data-i18n="diag.back">← Voltar</button>
            <button class="btn btn-primary" onclick="submitForm()" id="btn4" disabled data-i18n="diag.submit">Agendar diagnóstico →</button>
        </div>
    </div>

    <!-- Success -->
    <div class="step" id="stepSuccess">
        <div class="success-screen">
            <div class="success-icon">✓</div>
            <h2 id="successTitle">Pronto!</h2>
            <p data-i18n="diag.success.msg1">Recebi suas respostas. Vou te chamar no WhatsApp pra confirmar o melhor horário pro diagnóstico.</p>
            <p style="font-size: 0.875rem;" id="successMsg2"></p>

            <div class="summary-card">
                <h3 data-i18n="diag.success.summary">// Resumo</h3>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.name">Nome</span>
                    <span class="value" id="sumNome">—</span>
                </div>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.biz">Negócio</span>
                    <span class="value" id="sumNegocio">—</span>
                </div>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.seg">Segmento</span>
                    <span class="value" id="sumSegmento">—</span>
                </div>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.site">Site atual</span>
                    <span class="value" id="sumSite">—</span>
                </div>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.phone">Telefone</span>
                    <span class="value" id="sumTelefone">—</span>
                </div>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.email">E-mail</span>
                    <span class="value" id="sumEmail">—</span>
                </div>
                <div class="summary-item">
                    <span class="label" data-i18n="diag.sum.pain">Principal dor</span>
                    <span class="value" id="sumDor">—</span>
                </div>
            </div>

            <a href="https://wa.me/<?php echo htmlspecialchars($config['whatsapp'], ENT_QUOTES, 'UTF-8'); ?>?text=Oi!" class="whatsapp-link" id="whatsappLink">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span data-i18n="diag.whatsapp">Chamar no WhatsApp</span>
            </a>
        </div>
    </div>
</div>

<script>
window.FTIT = {
    whatsapp: '<?php echo htmlspecialchars($config['whatsapp'], ENT_QUOTES, 'UTF-8'); ?>'
};
</script>
<script src="/assets/js/diagnostico.js"></script>

</body>
</html>
