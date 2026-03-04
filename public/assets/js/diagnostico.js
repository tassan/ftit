const TOTAL_STEPS = 6;
let currentStep = 1;
const ganchoFired = {};

const formData = {
    nome: '', negocio: '', email: '', telefone: '', cidade: '',
    segmento: '', segmento_outro: '',
    faturamento: '', funcionarios: '',
    tem_site: '', google_meu_negocio: '', instagram: '', como_acham: '',
    agendamento: '', followup: '', horas_admin: '',
    problema: '', objetivo: ''
};

// ─── Ganchos ──────────────────────────────────────────────────────────────────

function exibirGancho(emoji, texto, fonte) {
    const stepAtivo = document.querySelector('.step.active');
    if (!stepAtivo) return;
    const el = stepAtivo.querySelector('.gancho-inline');
    if (!el) return;

    el.querySelector('.gancho-emoji').textContent = emoji;
    el.querySelector('.gancho-texto').textContent = texto;
    const fonteEl = el.querySelector('.gancho-fonte');
    if (fonte) {
        fonteEl.textContent = '📎 ' + fonte;
        fonteEl.style.display = 'block';
    } else {
        fonteEl.textContent = '';
        fonteEl.style.display = 'none';
    }
    el.style.display = 'flex';
    setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
}

const GANCHOS_SEGMENTO = {
    'Dentista / Odontologia': {
        emoji: '🦷',
        texto: 'Clínicas odontológicas perdem em média 25% a 30% dos agendamentos por no-show. Lembretes automáticos via WhatsApp reduzem esse número em até 35%.',
        fonte: 'Doctoralia, Panorama das Clínicas e Hospitais 2025 / Tactium, 2025'
    },
    'Clínica estética / Beleza': {
        emoji: '💆',
        texto: '96% dos consumidores leem avaliações no Google antes de escolher onde fazer um procedimento estético. Sua reputação digital vale mais do que sua fachada.',
        fonte: 'Reclame Aqui, 2025'
    },
    'Psicólogo / Terapeuta': {
        emoji: '🧠',
        texto: '79% dos brasileiros pesquisam online antes de contratar qualquer serviço. Para serviços de saúde mental, a confiança começa na presença digital.',
        fonte: 'PwC, Global Consumer Insights Survey'
    },
    'Academia / Personal trainer': {
        emoji: '💪',
        texto: '93% dos consumidores pesquisam no Google antes de contratar um serviço local. Quem não aparece online, não é considerado.',
        fonte: 'Hedgehog Digital + Opinion Box, State of Search Brasil'
    },
    'Nutricionista': {
        emoji: '🥗',
        texto: '93% dos consumidores pesquisam online antes de contratar serviços de saúde. Sem presença digital, você depende 100% de indicação.',
        fonte: 'Hedgehog Digital + Opinion Box, State of Search Brasil'
    },
    'Fisioterapeuta': {
        emoji: '🏥',
        texto: '9 em cada 10 brasileiros pesquisam na internet antes de decidir onde comprar ou contratar um serviço.',
        fonte: 'Sebrae / Offerwise + Google'
    },
    'Veterinário / Clínica veterinária': {
        emoji: '🐾',
        texto: 'O mercado pet brasileiro faturou R$ 75,4 bilhões em 2024 — crescimento de 9,6%. Serviços veterinários cresceram 15% no mesmo período. O cliente está disposto a pagar. Mas ele precisa te encontrar.',
        fonte: 'Abinpet + Instituto Pet Brasil, 2024'
    },
    'Pet shop': {
        emoji: '🐶',
        texto: 'Pequenos e médios pet shops representam quase 50% do faturamento do setor pet no Brasil — R$ 36,6 bilhões. Mas a maioria ainda não tem presença digital estruturada.',
        fonte: 'Abinpet + Instituto Pet Brasil, 2024'
    },
    'Banho & Tosa': {
        emoji: '✂️',
        texto: 'O mercado pet brasileiro é o 3º maior do mundo, com 1,8 animal de estimação por residência. Donos de pets buscam serviços confiáveis — e a primeira busca começa no Google.',
        fonte: 'Abinpet + IBGE, 2024'
    },
    'Hotel / Day care para pets': {
        emoji: '✂️',
        texto: 'O mercado pet brasileiro é o 3º maior do mundo, com 1,8 animal de estimação por residência. Donos de pets buscam serviços confiáveis — e a primeira busca começa no Google.',
        fonte: 'Abinpet + IBGE, 2024'
    },
    'Adestrador': {
        emoji: '✂️',
        texto: 'O mercado pet brasileiro é o 3º maior do mundo, com 1,8 animal de estimação por residência. Donos de pets buscam serviços confiáveis — e a primeira busca começa no Google.',
        fonte: 'Abinpet + IBGE, 2024'
    },
    'Restaurante / Cafeteria / Lanchonete': {
        emoji: '🍽️',
        texto: '48% dos brasileiros pesquisam alimentação online antes de decidir onde comer. Restaurantes sem presença digital perdem para quem aparece no Google Maps.',
        fonte: 'Hedgehog Digital + Opinion Box, State of Search Brasil'
    },
    'Loja física / Moda': {
        emoji: '👗',
        texto: '96% dos brasileiros pesquisam online antes de comprar. Para lojas físicas, isso significa que a vitrine digital importa tanto quanto a vitrine da rua.',
        fonte: 'Provokers + Google, 2018'
    },
    'E-commerce / Loja online': {
        emoji: '🛒',
        texto: '53% do tráfego de e-commerces vem de buscas orgânicas. SEO bem feito gera visitas constantes sem pagar por anúncio.',
        fonte: 'Dados de mercado SEO Brasil'
    },
    'Advogado / Jurídico': {
        emoji: '⚖️',
        texto: '62% dos brasileiros buscam advogados online antes de pedir indicação para amigos. Sem site profissional, você não existe para esse público.',
        fonte: 'PwC, Global Consumer Insights Survey'
    },
    'Contador / Financeiro': {
        emoji: '📊',
        texto: '79% dos brasileiros pesquisam online antes de contratar qualquer serviço profissional. Contabilidade não é exceção.',
        fonte: 'PwC, Global Consumer Insights Survey'
    },
    'outro': {
        emoji: '💡',
        texto: 'Independente do setor, 9 em cada 10 brasileiros pesquisam na internet antes de decidir onde comprar ou contratar. Seu negócio precisa aparecer nessa pesquisa.',
        fonte: 'Sebrae / Offerwise + Google'
    }
};

const GANCHO_SEGMENTO_DEFAULT = {
    emoji: '💡',
    texto: 'Independente do setor, 9 em cada 10 brasileiros pesquisam na internet antes de decidir onde comprar ou contratar. Seu negócio precisa aparecer nessa pesquisa.',
    fonte: 'Sebrae / Offerwise + Google'
};

// ─── Progress & navigation ────────────────────────────────────────────────────

function updateProgress() {
    const pct = (currentStep / TOTAL_STEPS) * 100;
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressLabel').textContent = `Etapa ${currentStep} de ${TOTAL_STEPS}`;
}

function showStep(n) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    let target;
    if (n === 'loading') target = 'stepLoading';
    else if (n === 'result') target = 'stepResult';
    else target = `step${n}`;
    document.getElementById(target).classList.add('active');

    if (n === 'loading' || n === 'result') {
        document.querySelector('.progress-container').style.display = 'none';
    }
}

function nextStep(from) {
    document.querySelectorAll('.gancho-inline').forEach(el => { el.style.display = 'none'; });

    if (from === 1) {
        formData.nome     = document.getElementById('nome').value.trim();
        formData.negocio  = document.getElementById('negocio').value.trim();
        formData.email    = document.getElementById('email').value.trim();
        formData.telefone = document.getElementById('telefone').value.trim();
        formData.cidade   = document.getElementById('cidade').value.trim();
    } else if (from === 2) {
        formData.segmento       = document.getElementById('segmento').value;
        formData.segmento_outro = document.getElementById('segmento_outro').value.trim();
    }

    currentStep = from + 1;
    updateProgress();
    showStep(currentStep);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(from) {
    document.querySelectorAll('.gancho-inline').forEach(el => { el.style.display = 'none'; });

    currentStep = from - 1;
    updateProgress();
    showStep(currentStep);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ─── Validation ───────────────────────────────────────────────────────────────

function validateStep1() {
    const ok = document.getElementById('nome').value.trim()
        && document.getElementById('negocio').value.trim()
        && document.getElementById('email').value.trim()
        && document.getElementById('telefone').value.trim()
        && document.getElementById('cidade').value.trim();
    document.getElementById('btn1').disabled = !ok;
}

function validateStep3() {
    const fat = document.querySelector('input[name="faturamento"]:checked');
    const fun = document.querySelector('input[name="funcionarios"]:checked');
    document.getElementById('btn3').disabled = !(fat && fun);
}

function validateStep4() {
    const site  = document.querySelector('input[name="tem_site"]:checked');
    const gmn   = document.querySelector('input[name="google_meu_negocio"]:checked');
    const ig    = document.querySelector('input[name="instagram"]:checked');
    const acham = document.querySelector('input[name="como_acham"]:checked');
    document.getElementById('btn4').disabled = !(site && gmn && ig && acham);
}

function validateStep5() {
    const ag = document.querySelector('input[name="agendamento"]:checked');
    const fu = document.querySelector('input[name="followup"]:checked');
    const ha = document.querySelector('input[name="horas_admin"]:checked');
    document.getElementById('btn5').disabled = !(ag && fu && ha);
}

// ─── Submit & render ──────────────────────────────────────────────────────────

async function submitForm() {
    formData.problema = document.getElementById('problema').value.trim();
    formData.objetivo = document.getElementById('objetivo').value.trim();

    formData.faturamento        = document.querySelector('input[name="faturamento"]:checked')?.value || '';
    formData.funcionarios       = document.querySelector('input[name="funcionarios"]:checked')?.value || '';
    formData.tem_site           = document.querySelector('input[name="tem_site"]:checked')?.value || '';
    formData.google_meu_negocio = document.querySelector('input[name="google_meu_negocio"]:checked')?.value || '';
    formData.instagram          = document.querySelector('input[name="instagram"]:checked')?.value || '';
    formData.como_acham         = document.querySelector('input[name="como_acham"]:checked')?.value || '';
    formData.agendamento        = document.querySelector('input[name="agendamento"]:checked')?.value || '';
    formData.followup           = document.querySelector('input[name="followup"]:checked')?.value || '';
    formData.horas_admin        = document.querySelector('input[name="horas_admin"]:checked')?.value || '';

    showStep('loading');

    try {
        const res = await fetch('/api/diagnostico-ia', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await res.json();
        if (!result.success) throw new Error('Server error');
        renderParecer(result.parecer);
    } catch (_) {
        document.getElementById('parecerCard').innerHTML = `
            <div class="parecer-error">
                <p>Não conseguimos gerar o parecer automático agora, mas recebemos suas informações!</p>
                <p>Entraremos em contato pelo WhatsApp em até 24h.</p>
            </div>`;
        const msg = encodeURIComponent('Olá! Fiz o diagnóstico digital no site da FTIT e gostaria de agendar a call de 30 minutos.');
        document.getElementById('whatsappLink').href = `https://wa.me/${window.FTIT.whatsapp}?text=${msg}`;
    }

    showStep('result');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function renderParecer(parecer) {
    const urgenciaLabel = { alta: 'Alta', media: 'Média', baixa: 'Baixa' }[parecer.urgencia] || '';
    const urgenciaClass = parecer.urgencia ? `urgencia-${parecer.urgencia}` : '';

    const gapsHtml = (parecer.gaps || []).map(g => `
        <div class="gap-item">
            <div class="gap-problema">${escHtml(g.problema)}</div>
            <div class="gap-impacto">${escHtml(g.impacto)}</div>
        </div>`).join('');

    document.getElementById('parecerCard').innerHTML = `
        <div class="parecer-header">
            <h2 class="parecer-titulo">${escHtml(parecer.titulo)}</h2>
            ${urgenciaLabel ? `<span class="urgencia-badge ${urgenciaClass}">Urgência: ${urgenciaLabel}</span>` : ''}
        </div>
        <div class="parecer-section">
            <div class="parecer-label">// Situação atual</div>
            <p>${escHtml(parecer.situacao_atual)}</p>
        </div>
        <div class="parecer-section">
            <div class="parecer-label">// Gaps identificados</div>
            <div class="gaps-list">${gapsHtml}</div>
        </div>
        <div class="parecer-section">
            <div class="parecer-label">// Potencial</div>
            <p>${escHtml(parecer.potencial)}</p>
        </div>
        <div class="parecer-section">
            <div class="parecer-label">// Próximos passos</div>
            <p>${escHtml(parecer.proximos_passos)}</p>
        </div>
        <p class="parecer-cta">${escHtml(parecer.cta_texto)}</p>`;

    const msg = encodeURIComponent('Olá! Fiz o diagnóstico digital no site da FTIT e gostaria de agendar a call de 30 minutos.');
    document.getElementById('whatsappLink').href = `https://wa.me/${window.FTIT.whatsapp}?text=${msg}`;
}

function escHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ─── Event listeners ──────────────────────────────────────────────────────────

// Step 1 — validate all required fields
['nome', 'negocio', 'email', 'telefone', 'cidade'].forEach(id => {
    document.getElementById(id).addEventListener('input', validateStep1);
});

// Step 1 — gancho on empresa blur
document.getElementById('negocio').addEventListener('blur', function () {
    if (ganchoFired.empresa) return;
    const nome    = document.getElementById('nome').value.trim().split(' ')[0];
    const empresa = this.value.trim();
    if (!nome || !empresa) return;
    ganchoFired.empresa = true;
    exibirGancho('👋', `Olá, ${nome}! Boa decisão trazer a ${empresa} para cá. Vamos descobrir exatamente onde estão as oportunidades digitais do seu negócio.`, null);
});

// Step 2 — segmento select
document.getElementById('segmento').addEventListener('change', function () {
    const val        = this.value;
    const outroGroup = document.getElementById('segmento-outro-group');
    const outroInput = document.getElementById('segmento_outro');

    if (val === 'outro') {
        outroGroup.style.display = 'block';
        outroInput.required      = true;
        document.getElementById('btn2').disabled = true; // re-enable when outro is filled
    } else {
        outroGroup.style.display = 'none';
        outroInput.required      = false;
        outroInput.value         = '';
        document.getElementById('btn2').disabled = false;
    }

    if (!ganchoFired.segmento && val) {
        ganchoFired.segmento = true;
        const g = GANCHOS_SEGMENTO[val] || GANCHO_SEGMENTO_DEFAULT;
        exibirGancho(g.emoji, g.texto, g.fonte);
    }
});

document.getElementById('segmento_outro').addEventListener('input', function () {
    document.getElementById('btn2').disabled = !this.value.trim();
});

// Step 3 — porte radios
document.querySelectorAll('input[name="faturamento"], input[name="funcionarios"]').forEach(r => {
    r.addEventListener('change', validateStep3);
});

// Step 4 — presença digital radios + ganchos
document.querySelectorAll('input[name="tem_site"]').forEach(r => {
    r.addEventListener('change', function () {
        if (!ganchoFired.tem_site) {
            const ganchos = {
                'Não': { emoji: '🔍', texto: 'Sem site, você é invisível para 93% dos consumidores que pesquisam online antes de contratar um serviço. Isso tem solução.', fonte: 'Hedgehog Digital + Opinion Box, State of Search Brasil' },
                'Tenho, mas não funciona direito': { emoji: '⚠️', texto: 'Um site que não converte é pior do que não ter site — transmite abandono. 96% dos consumidores leem avaliações e verificam a presença digital antes de contratar.', fonte: 'Reclame Aqui, 2025' },
                'Sim': { emoji: '✅', texto: 'Ótimo que você já tem presença. Agora vamos entender se ela está trabalhando por você ou só ocupando espaço.', fonte: null }
            };
            const g = ganchos[this.value];
            if (g) { ganchoFired.tem_site = true; exibirGancho(g.emoji, g.texto, g.fonte); }
        }
        validateStep4();
    });
});

document.querySelectorAll('input[name="google_meu_negocio"]').forEach(r => {
    r.addEventListener('change', function () {
        if (!ganchoFired.gmn && (this.value === 'Não' || this.value === 'Não sei o que é isso')) {
            ganchoFired.gmn = true;
            const texto = this.value === 'Não'
                ? 'Negócios no Google Meu Negócio aparecem diretamente nos resultados de busca e no Google Maps. Quem não está lá, some para clientes que buscam por serviços próximos.'
                : 'Google Meu Negócio é gratuito e coloca seu negócio no mapa — literalmente. Empresas listadas têm muito mais chances de ser encontradas por clientes na sua cidade.';
            exibirGancho('📍', texto, 'Google / Provokers');
        }
        validateStep4();
    });
});

document.querySelectorAll('input[name="instagram"], input[name="como_acham"]').forEach(r => {
    r.addEventListener('change', validateStep4);
});

// Step 5 — operação radios + ganchos
document.querySelectorAll('input[name="agendamento"]').forEach(r => {
    r.addEventListener('change', function () {
        if (!ganchoFired.agendamento) {
            const ganchos = {
                'WhatsApp manual': { emoji: '💬', texto: 'Agenda manual pelo WhatsApp funciona — até escalar. Clínicas que automatizam confirmações reduzem no-show em até 35% e liberam a equipe para o que realmente importa.', fonte: 'Tactium, 2025' },
                'Telefone':        { emoji: '📞', texto: '92% dos brasileiros preferem o WhatsApp para se comunicar com empresas. Quem ainda depende de ligação está perdendo contatos que nunca vão ligar.', fonte: 'CETIC.br' },
                'Planilha':        { emoji: '📋', texto: 'Planilha funciona para controle. Mas não agenda, não confirma e não lembra o cliente. A automação faz as três coisas enquanto você atende.', fonte: null }
            };
            const g = ganchos[this.value];
            if (g) { ganchoFired.agendamento = true; exibirGancho(g.emoji, g.texto, g.fonte); }
        }
        validateStep5();
    });
});

document.querySelectorAll('input[name="horas_admin"]').forEach(r => {
    r.addEventListener('change', function () {
        if (!ganchoFired.horas_admin) {
            const ganchos = {
                '5–10h':       { emoji: '⏱️', texto: '5 horas por semana em tarefas repetitivas = 260 horas por ano. São 6 semanas de trabalho que poderiam ir para atendimento, vendas ou descanso.', fonte: '(cálculo próprio — transparente)' },
                'Mais de 10h': { emoji: '🚨', texto: 'Mais de 10 horas semanais em tarefas administrativas é um sinal claro: seu negócio precisa de automação. Esse tempo está custando crescimento.', fonte: '(cálculo próprio — transparente)' }
            };
            const g = ganchos[this.value];
            if (g) { ganchoFired.horas_admin = true; exibirGancho(g.emoji, g.texto, g.fonte); }
        }
        validateStep5();
    });
});

document.querySelectorAll('input[name="followup"]').forEach(r => {
    r.addEventListener('change', validateStep5);
});

// Step 6 — LGPD consent enables submit
document.getElementById('lgpd-consent').addEventListener('change', function () {
    document.getElementById('btn6').disabled = !this.checked;
});

// Enter key navigation (skip textareas)
document.addEventListener('keydown', e => {
    if (e.key !== 'Enter' || currentStep > TOTAL_STEPS) return;
    if (e.target.tagName === 'TEXTAREA') return;
    const btn = document.getElementById(`btn${currentStep}`);
    if (btn && !btn.disabled) {
        if (currentStep === TOTAL_STEPS) submitForm();
        else nextStep(currentStep);
    }
});

// ─── Init ─────────────────────────────────────────────────────────────────────
updateProgress();
document.getElementById('btn1').disabled = true;

