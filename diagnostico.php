<?php
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv($line);
        }
    }
}
$whatsapp = getenv('WHATSAPP_NUMBER') ?: '5500000000000';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Express — f(t) it</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-primary: #0D0D0F;
            --bg-secondary: #1A1A1F;
            --bg-card: #242429;
            --accent-purple: #7C3AED;
            --accent-hover: #9D65F5;
            --accent-orange: #FF4500;
            --accent-orange-soft: #CC3700;
            --text-primary: #F4F4F5;
            --text-secondary: #A1A1AA;
            --border: #3F3F46;
            --success: #22c55e;
            --font-mono: 'JetBrains Mono', monospace;
            --font-body: 'Space Grotesk', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(124, 58, 237, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124, 58, 237, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 580px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* Header */
        .header {
            margin-bottom: 2.5rem;
            text-align: left;
        }

        .logo {
            font-family: var(--font-mono);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        .logo { text-decoration: none; display: inline-flex; align-items: center; gap: 0.6rem; }
        .logo .ft { color: var(--accent-purple); }
        .logo .it { color: var(--text-primary); }
        .logo .back-arrow { color: var(--text-secondary); font-size: 1rem; transition: color 0.2s; }
        .logo:hover .back-arrow { color: var(--accent-purple); }

        .header h1 {
            font-family: var(--font-mono);
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }

        .header h1 .highlight {
            color: var(--accent-purple);
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
        }

        .header p strong {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* Progress bar */
        .progress-container {
            margin-bottom: 2rem;
        }

        .progress-bar {
            height: 2px;
            background: var(--border);
            border-radius: 1px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--accent-purple);
            border-radius: 1px;
            transition: width 0.5s cubic-bezier(0.22, 1, 0.36, 1);
            width: 0%;
            box-shadow: 0 0 12px rgba(124, 58, 237, 0.4);
        }

        .progress-label {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        /* Steps */
        .step {
            display: none;
            animation: fadeSlideIn 0.4s ease;
        }

        .step.active {
            display: block;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-number {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: var(--accent-purple);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .step-question {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            line-height: 1.4;
        }

        .step-hint {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        /* Inputs */
        .input-field {
            width: 100%;
            padding: 0.875rem 1rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-field:focus {
            border-color: var(--accent-purple);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }

        .input-field::placeholder {
            color: #52525b;
        }

        .input-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0;
        }

        .input-row .input-group {
            flex: 1;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        .input-label {
            display: block;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        textarea.input-field {
            resize: vertical;
            min-height: 100px;
        }

        /* Option cards */
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .option-card {
            padding: 1rem 1.125rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .option-card:hover {
            border-color: var(--accent-purple);
            background: rgba(124, 58, 237, 0.05);
        }

        .option-card.selected {
            border-color: var(--accent-purple);
            background: rgba(124, 58, 237, 0.1);
            box-shadow: 0 0 0 1px var(--accent-purple);
        }

        .option-card.selected::after {
            content: '✓';
            position: absolute;
            top: 0.6rem;
            right: 0.75rem;
            color: var(--accent-purple);
            font-size: 0.875rem;
            font-weight: 700;
        }

        .option-card .option-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
        }

        .option-card .option-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.75rem;
        }

        .btn {
            padding: 0.8rem 1.75rem;
            border-radius: 8px;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: var(--accent-purple);
            color: white;
            box-shadow: 0 0 20px rgba(124, 58, 237, 0.2);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            box-shadow: 0 0 28px rgba(124, 58, 237, 0.35);
            transform: translateY(-1px);
        }

        .btn-primary:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            color: var(--text-primary);
            border-color: var(--text-secondary);
        }

        /* Success state */
        .success-screen {
            text-align: center;
            padding: 2rem 0;
        }

        .success-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(124, 58, 237, 0.15);
            border: 2px solid var(--accent-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem;
            animation: pulseIn 0.5s ease;
        }

        @keyframes pulseIn {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-screen h2 {
            font-family: var(--font-mono);
            font-size: 1.4rem;
            margin-bottom: 0.75rem;
        }

        .success-screen p {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }

        .success-screen .whatsapp-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            padding: 0.8rem 1.5rem;
            background: #25D366;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .success-screen .whatsapp-link:hover {
            background: #20bd5a;
            transform: translateY(-1px);
        }

        /* Summary */
        .summary-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.25rem;
            margin-top: 1.5rem;
            text-align: left;
        }

        .summary-card h3 {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: var(--accent-purple);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 0;
            border-bottom: 1px solid rgba(63, 63, 70, 0.4);
            font-size: 0.875rem;
        }

        .summary-item:last-child { border-bottom: none; }
        .summary-item .label { color: var(--text-secondary); }
        .summary-item .value { color: var(--text-primary); font-weight: 500; text-align: right; max-width: 60%; }

        /* Responsive */
        @media (max-width: 480px) {
            .header h1 { font-size: 1.3rem; }
            .options-grid { grid-template-columns: 1fr; }
            .input-row { flex-direction: column; gap: 0; }
            .btn-row { flex-direction: column-reverse; }
            .btn { width: 100%; text-align: center; }
        }
    </style>
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

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(4)" data-i18n="diag.back">← Voltar</button>
            <button class="btn btn-primary" onclick="submitForm()" id="btn4" data-i18n="diag.submit">Agendar diagnóstico →</button>
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
                    <span class="label" data-i18n="diag.sum.pain">Principal dor</span>
                    <span class="value" id="sumDor">—</span>
                </div>
            </div>

            <a href="https://wa.me/<?php echo htmlspecialchars($whatsapp); ?>?text=Oi!" class="whatsapp-link" id="whatsappLink">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span data-i18n="diag.whatsapp">Chamar no WhatsApp</span>
            </a>
        </div>
    </div>
</div>

<script>
    const WHATSAPP_NUMBER = '<?php echo htmlspecialchars($whatsapp); ?>';
    let currentLang = localStorage.getItem('ftit-lang') || 'pt';
    let translations = {};

    async function loadLanguage(lang) {
        try {
            const res = await fetch(`lang/${lang}.json`);
            translations = await res.json();
            applyTranslations();
        } catch (e) {
            console.error('Failed to load language:', e);
        }
    }

    function applyTranslations() {
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (translations[key] !== undefined) el.textContent = translations[key];
        });
        document.querySelectorAll('[data-i18n-html]').forEach(el => {
            const key = el.getAttribute('data-i18n-html');
            if (translations[key] !== undefined) el.innerHTML = translations[key];
        });
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (translations[key] !== undefined) el.placeholder = translations[key];
        });
        updateProgress();
    }

    const formData = {
        nome: '',
        negocio: '',
        segmento: '',
        temSite: '',
        dor: ''
    };

    let currentStep = 1;
    const totalSteps = 4;

    function updateProgress() {
        const pct = (currentStep / totalSteps) * 100;
        document.getElementById('progressFill').style.width = pct + '%';
        const tpl = translations['diag.progress'] || 'Etapa {current} de {total}';
        document.getElementById('progressLabel').textContent = tpl
            .replace('{current}', currentStep)
            .replace('{total}', totalSteps);
    }

    function showStep(n) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        const target = n > totalSteps ? 'stepSuccess' : `step${n}`;
        document.getElementById(target).classList.add('active');

        if (n > totalSteps) {
            document.querySelector('.progress-container').style.display = 'none';
        }
    }

    function nextStep(from) {
        if (from === 1) {
            const nome = document.getElementById('nome').value.trim();
            const negocio = document.getElementById('negocio').value.trim();
            if (!nome || !negocio) return;
            formData.nome = nome;
            formData.negocio = negocio;
        }

        currentStep = from + 1;
        updateProgress();
        showStep(currentStep);
    }

    function prevStep(from) {
        currentStep = from - 1;
        updateProgress();
        showStep(currentStep);
    }

    function selectOption(el, field) {
        const parent = el.parentElement;
        parent.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        formData[field] = el.querySelector('.option-title').textContent;

        const stepNum = field === 'segmento' ? 2 : 3;
        document.getElementById(`btn${stepNum}`).disabled = false;
    }

    async function submitForm() {
        formData.dor = document.getElementById('dor').value.trim();

        const btn = document.getElementById('btn4');
        btn.disabled = true;
        btn.textContent = translations['diag.submitting'] || 'Enviando...';

        try {
            const res = await fetch('submit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            const result = await res.json();
            if (!result.ok) throw new Error('Server error');
        } catch (e) {
            btn.disabled = false;
            btn.textContent = translations['diag.submit'] || 'Agendar diagnóstico →';
            alert(translations['diag.error'] || 'Erro ao enviar. Tente novamente ou chame no WhatsApp.');
            return;
        }

        // Populate success screen
        const firstName = formData.nome.split(' ')[0];
        const titleTpl = translations['diag.success.title'] || 'Pronto, {name}!';
        document.getElementById('successTitle').textContent = titleTpl.replace('{name}', firstName);

        const msg2Tpl = translations['diag.success.msg2'] || 'Conversa de 30 min, direto ao ponto, sobre o {biz}.';
        document.getElementById('successMsg2').textContent = msg2Tpl.replace('{biz}', formData.negocio);

        document.getElementById('sumNome').textContent = formData.nome;
        document.getElementById('sumNegocio').textContent = formData.negocio;
        document.getElementById('sumSegmento').textContent = formData.segmento;
        document.getElementById('sumSite').textContent = formData.temSite;
        document.getElementById('sumDor').textContent = formData.dor || translations['diag.sum.unknown'] || '(não informado)';

        // Update WhatsApp link with context
        const waMsgTpl = translations['diag.wa_msg'] || 'Oi! Sou {name} da {biz} ({seg}). Preenchi o diagnóstico no site e quero agendar a conversa.';
        const msg = encodeURIComponent(
            waMsgTpl.replace('{name}', formData.nome).replace('{biz}', formData.negocio).replace('{seg}', formData.segmento)
        );
        document.getElementById('whatsappLink').href = `https://wa.me/${WHATSAPP_NUMBER}?text=${msg}`;

        currentStep = totalSteps + 1;
        showStep(currentStep);
    }

    // Validate step 1 inputs
    ['nome', 'negocio'].forEach(id => {
        document.getElementById(id).addEventListener('input', () => {
            const nome = document.getElementById('nome').value.trim();
            const negocio = document.getElementById('negocio').value.trim();
            document.getElementById('btn1').disabled = !(nome && negocio);
        });
    });

    // Enter key navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && currentStep <= totalSteps) {
            const btn = document.getElementById(`btn${currentStep}`);
            if (btn && !btn.disabled) {
                if (currentStep === totalSteps) submitForm();
                else nextStep(currentStep);
            }
        }
    });

    // Init
    updateProgress();
    document.getElementById('btn1').disabled = true;
    loadLanguage(currentLang);
</script>

</body>
</html>
