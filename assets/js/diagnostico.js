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
    email: '',
    telefone: '',
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
        const telefone = document.getElementById('telefone').value.trim();
        if (!nome || !negocio || !telefone) return;
        formData.nome = nome;
        formData.negocio = negocio;
        formData.telefone = telefone;
        formData.email = document.getElementById('email').value.trim();
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

    const unknown = translations['diag.sum.unknown'] || '(não informado)';
    document.getElementById('sumNome').textContent = formData.nome;
    document.getElementById('sumNegocio').textContent = formData.negocio;
    document.getElementById('sumTelefone').textContent = formData.telefone;
    document.getElementById('sumEmail').textContent = formData.email || unknown;
    document.getElementById('sumSegmento').textContent = formData.segmento;
    document.getElementById('sumSite').textContent = formData.temSite;
    document.getElementById('sumDor').textContent = formData.dor || unknown;

    // Update WhatsApp link with context
    const waMsgTpl = translations['diag.wa_msg'] || 'Oi! Sou {name} da {biz} ({seg}). Preenchi o diagnóstico no site e quero agendar a conversa.';
    const msg = encodeURIComponent(
        waMsgTpl.replace('{name}', formData.nome).replace('{biz}', formData.negocio).replace('{seg}', formData.segmento)
    );
    document.getElementById('whatsappLink').href = `https://wa.me/${window.FTIT.whatsapp}?text=${msg}`;

    currentStep = totalSteps + 1;
    showStep(currentStep);
}

// Validate step 1 inputs
['nome', 'negocio', 'telefone'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const nome = document.getElementById('nome').value.trim();
        const negocio = document.getElementById('negocio').value.trim();
        const telefone = document.getElementById('telefone').value.trim();
        document.getElementById('btn1').disabled = !(nome && negocio && telefone);
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
