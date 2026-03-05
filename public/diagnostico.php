<?php
$config = require __DIR__ . '/config/config.php';

$page = [
    'title'       => 'Diagnóstico Digital Gratuito',
    'description' => 'Descubra em 2 minutos os principais gaps digitais do seu negócio e receba um parecer personalizado com IA.',
    'url'         => rtrim($config['base_url'], '/') . '/diagnostico',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include __DIR__ . '/config/head.php'; ?>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/diagnostico.css">
</head>
<body>
<div class="container">
    <div class="header">
        <a href="/" class="logo">
            <span class="back-arrow">←</span>
            <span class="ft">f(t)</span><span class="it"> it</span>
        </a>
        <h1>Diagnóstico <span class="highlight">Digital</span></h1>
        <p>6 blocos rápidos. Depois, um <strong>parecer personalizado com IA</strong> sobre o seu negócio.</p>
    </div>

    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-label" id="progressLabel">Etapa 1 de 6</div>
    </div>

    <div class="step active" id="step1">
        <div class="step-number">01_</div>
        <div class="step-question">Quem é você e qual seu negócio?</div>
        <div class="step-hint">Só o básico pra gente não começar do zero no parecer.</div>

        <div class="input-row">
            <div class="input-group">
                <label class="input-label" for="nome">Seu nome</label>
                <input type="text" id="nome" class="input-field" placeholder="Ex: Ana Silva" required>
            </div>
            <div class="input-group">
                <label class="input-label" for="negocio">Nome do negócio</label>
                <input type="text" id="negocio" class="input-field" placeholder="Ex: Clínica Renova" required>
            </div>
        </div>

        <div class="input-row">
            <div class="input-group">
                <label class="input-label" for="email">E-mail</label>
                <input type="email" id="email" class="input-field" placeholder="Ex: ana@clinica.com.br" required>
            </div>
            <div class="input-group">
                <label class="input-label" for="telefone">WhatsApp</label>
                <input type="tel" id="telefone" class="input-field" placeholder="Ex: (11) 99999-9999" required>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label" for="cidade">Cidade</label>
            <input type="text" id="cidade" class="input-field" placeholder="Ex: São Paulo – SP" required>
        </div>

        <div id="gancho-1" class="gancho gancho-inline" aria-live="polite" style="display:none">
            <span class="gancho-emoji"></span>
            <p class="gancho-texto"></p>
            <small class="gancho-fonte"></small>
        </div>

        <div class="btn-row">
            <button class="btn btn-primary" onclick="nextStep(1)" id="btn1" disabled>Próximo →</button>
        </div>
    </div>

    <div class="step" id="step2">
        <div class="step-number">02_</div>
        <div class="step-question">Qual o segmento do seu negócio?</div>
        <div class="step-hint">Isso me ajuda a trazer referências de mercado relevantes no parecer.</div>

        <div class="input-group">
            <label class="input-label" for="segmento">Segmento</label>
            <select id="segmento" class="input-field" required>
                <option value="" disabled selected>Selecione seu segmento</option>

                <optgroup label="Saúde &amp; Bem-estar">
                    <option value="Clínica estética / Beleza">Clínica estética / Beleza</option>
                    <option value="Dentista / Odontologia">Dentista / Odontologia</option>
                    <option value="Psicólogo / Terapeuta">Psicólogo / Terapeuta</option>
                    <option value="Academia / Personal trainer">Academia / Personal trainer</option>
                    <option value="Nutricionista">Nutricionista</option>
                    <option value="Fisioterapeuta">Fisioterapeuta</option>
                </optgroup>

                <optgroup label="Pets">
                    <option value="Veterinário / Clínica veterinária">Veterinário / Clínica veterinária</option>
                    <option value="Pet shop">Pet shop</option>
                    <option value="Banho &amp; Tosa">Banho &amp; Tosa</option>
                    <option value="Hotel / Day care para pets">Hotel / Day care para pets</option>
                    <option value="Adestrador">Adestrador</option>
                </optgroup>

                <optgroup label="Varejo">
                    <option value="Loja física / Moda">Loja física / Moda</option>
                    <option value="Loja de eletrônicos / Informática">Loja de eletrônicos / Informática</option>
                    <option value="Farmácia / Perfumaria">Farmácia / Perfumaria</option>
                    <option value="Mercado / Mercearia">Mercado / Mercearia</option>
                    <option value="E-commerce / Loja online">E-commerce / Loja online</option>
                    <option value="Restaurante / Cafeteria / Lanchonete">Restaurante / Cafeteria / Lanchonete</option>
                    <option value="Oficina / Serviço automotivo">Oficina / Serviço automotivo</option>
                </optgroup>

                <optgroup label="Serviços Especializados">
                    <option value="Advogado / Jurídico">Advogado / Jurídico</option>
                    <option value="Contador / Financeiro">Contador / Financeiro</option>
                    <option value="Arquiteto / Designer de interiores">Arquiteto / Designer de interiores</option>
                    <option value="Consultor / Coach">Consultor / Coach</option>
                    <option value="Escola / Curso / Treinamento">Escola / Curso / Treinamento</option>
                    <option value="Agência / Marketing">Agência / Marketing</option>
                    <option value="TI / Tecnologia">TI / Tecnologia</option>
                </optgroup>

                <option value="outro">Outro</option>
            </select>
        </div>

        <div class="input-group" id="segmento-outro-group" style="display:none">
            <label class="input-label" for="segmento_outro">Qual é o seu negócio?</label>
            <input type="text" id="segmento_outro" class="input-field" placeholder="Descreva brevemente">
        </div>

        <div id="gancho-2" class="gancho gancho-inline" aria-live="polite" style="display:none">
            <span class="gancho-emoji"></span>
            <p class="gancho-texto"></p>
            <small class="gancho-fonte"></small>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(2)">← Voltar</button>
            <button class="btn btn-primary" onclick="nextStep(2)" id="btn2" disabled>Próximo →</button>
        </div>
    </div>

    <div class="step" id="step3">
        <div class="step-number">03_</div>
        <div class="step-question">Qual o porte do seu negócio?</div>
        <div class="step-hint">Sem compromisso — só pra calibrar o parecer.</div>

        <div class="input-group">
            <label class="input-label">Faturamento mensal estimado</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="faturamento" value="Até R$10k"><span>Até R$ 10k</span></label>
                <label class="radio-option"><input type="radio" name="faturamento" value="R$10k–30k"><span>R$ 10k – 30k</span></label>
                <label class="radio-option"><input type="radio" name="faturamento" value="R$30k–80k"><span>R$ 30k – 80k</span></label>
                <label class="radio-option"><input type="radio" name="faturamento" value="Acima de R$80k"><span>Acima de R$ 80k</span></label>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label">Número de funcionários</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="funcionarios" value="Só eu"><span>Só eu</span></label>
                <label class="radio-option"><input type="radio" name="funcionarios" value="2–5 pessoas"><span>2–5 pessoas</span></label>
                <label class="radio-option"><input type="radio" name="funcionarios" value="6–15 pessoas"><span>6–15 pessoas</span></label>
                <label class="radio-option"><input type="radio" name="funcionarios" value="Mais de 15"><span>Mais de 15</span></label>
            </div>
        </div>

        <div id="gancho-3" class="gancho gancho-inline" aria-live="polite" style="display:none">
            <span class="gancho-emoji"></span>
            <p class="gancho-texto"></p>
            <small class="gancho-fonte"></small>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(3)">← Voltar</button>
            <button class="btn btn-primary" onclick="nextStep(3)" id="btn3" disabled>Próximo →</button>
        </div>
    </div>

    <div class="step" id="step4">
        <div class="step-number">04_</div>
        <div class="step-question">Como está sua presença digital?</div>
        <div class="step-hint">4 perguntas rápidas — clique na opção que melhor descreve sua situação.</div>

        <div class="input-group">
            <label class="input-label">Tem site?</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="tem_site" value="Sim"><span>Sim</span></label>
                <label class="radio-option"><input type="radio" name="tem_site" value="Não"><span>Não</span></label>
                <label class="radio-option"><input type="radio" name="tem_site" value="Tenho, mas não funciona direito"><span>Tenho, mas não funciona direito</span></label>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label">Está no Google Meu Negócio?</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="google_meu_negocio" value="Sim"><span>Sim</span></label>
                <label class="radio-option"><input type="radio" name="google_meu_negocio" value="Não"><span>Não</span></label>
                <label class="radio-option"><input type="radio" name="google_meu_negocio" value="Não sei o que é isso"><span>Não sei o que é isso</span></label>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label">Tem Instagram ativo?</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="instagram" value="Sim, posto regularmente"><span>Sim, posto regularmente</span></label>
                <label class="radio-option"><input type="radio" name="instagram" value="Tenho, mas não posto"><span>Tenho, mas não posto</span></label>
                <label class="radio-option"><input type="radio" name="instagram" value="Não tenho"><span>Não tenho</span></label>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label">Como a maioria dos clientes te encontra?</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="como_acham" value="Indicação de amigos"><span>Indicação de amigos</span></label>
                <label class="radio-option"><input type="radio" name="como_acham" value="Google"><span>Google</span></label>
                <label class="radio-option"><input type="radio" name="como_acham" value="Instagram"><span>Instagram</span></label>
                <label class="radio-option"><input type="radio" name="como_acham" value="Passando na frente"><span>Passando na frente</span></label>
                <label class="radio-option"><input type="radio" name="como_acham" value="Outro"><span>Outro</span></label>
            </div>
        </div>

        <div id="gancho-4" class="gancho gancho-inline" aria-live="polite" style="display:none">
            <span class="gancho-emoji"></span>
            <p class="gancho-texto"></p>
            <small class="gancho-fonte"></small>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(4)">← Voltar</button>
            <button class="btn btn-primary" onclick="nextStep(4)" id="btn4" disabled>Próximo →</button>
        </div>
    </div>

    <div class="step" id="step5">
        <div class="step-number">05_</div>
        <div class="step-question">Como funciona sua operação?</div>
        <div class="step-hint">Isso ajuda a identificar onde a automação pode poupar seu tempo.</div>

        <div class="input-group">
            <label class="input-label">Como você agenda atendimentos hoje?</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="agendamento" value="WhatsApp manual"><span>WhatsApp manual</span></label>
                <label class="radio-option"><input type="radio" name="agendamento" value="Telefone"><span>Telefone</span></label>
                <label class="radio-option"><input type="radio" name="agendamento" value="Sistema"><span>Sistema próprio / app</span></label>
                <label class="radio-option"><input type="radio" name="agendamento" value="Planilha"><span>Planilha</span></label>
                <label class="radio-option"><input type="radio" name="agendamento" value="Não agendo (presencial)"><span>Não agendo (presencial)</span></label>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label">Faz acompanhamento de clientes após o atendimento?</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="followup" value="Sim, sempre"><span>Sim, sempre</span></label>
                <label class="radio-option"><input type="radio" name="followup" value="Às vezes"><span>Às vezes</span></label>
                <label class="radio-option"><input type="radio" name="followup" value="Não faço"><span>Não faço</span></label>
            </div>
        </div>

        <div class="input-group">
            <label class="input-label">Horas por semana em tarefas administrativas repetitivas</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="horas_admin" value="Menos de 2h"><span>Menos de 2h</span></label>
                <label class="radio-option"><input type="radio" name="horas_admin" value="2–5h"><span>2–5h</span></label>
                <label class="radio-option"><input type="radio" name="horas_admin" value="5–10h"><span>5–10h</span></label>
                <label class="radio-option"><input type="radio" name="horas_admin" value="Mais de 10h"><span>Mais de 10h</span></label>
            </div>
        </div>

        <div id="gancho-5" class="gancho gancho-inline" aria-live="polite" style="display:none">
            <span class="gancho-emoji"></span>
            <p class="gancho-texto"></p>
            <small class="gancho-fonte"></small>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(5)">← Voltar</button>
            <button class="btn btn-primary" onclick="nextStep(5)" id="btn5" disabled>Próximo →</button>
        </div>
    </div>

    <div class="step" id="step6">
        <div class="step-number">06_</div>
        <div class="step-question">Qual é a dor real do seu negócio?</div>
        <div class="step-hint">2 perguntas finais. Quanto mais sincero, mais útil o parecer.</div>

        <div class="input-group">
            <label class="input-label" for="problema">Qual é o maior problema com sua presença digital hoje?</label>
            <textarea id="problema" class="input-field" placeholder="Ex: Perco clientes porque não apareço no Google..." rows="3"></textarea>
        </div>

        <div class="input-group">
            <label class="input-label" for="objetivo">O que você quer resolver nos próximos 3 meses?</label>
            <textarea id="objetivo" class="input-field" placeholder="Ex: Quero ter um site que converta e automatizar meu agendamento..." rows="3"></textarea>
        </div>

        <div class="consent-wrapper">
            <label class="consent-label">
                <input type="checkbox" id="lgpd-consent">
                <span>Li e concordo com a <a href="/privacidade" target="_blank">Política de Privacidade</a></span>
            </label>
        </div>

        <div id="gancho-6" class="gancho gancho-inline" aria-live="polite" style="display:none">
            <span class="gancho-emoji"></span>
            <p class="gancho-texto"></p>
            <small class="gancho-fonte"></small>
        </div>

        <div class="btn-row">
            <button class="btn btn-secondary" onclick="prevStep(6)">← Voltar</button>
            <button class="btn btn-primary" onclick="submitForm()" id="btn6" disabled>Gerar parecer →</button>
        </div>
    </div>

    <div class="step" id="stepLoading">
        <div class="loading-screen">
            <div class="loading-spinner"></div>
            <p class="loading-text">Analisando seu negócio...</p>
            <p class="loading-sub">Nossa IA está preparando um parecer personalizado com base nas suas respostas.</p>
        </div>
    </div>

    <div class="step" id="stepResult">
        <div id="parecerCard" class="parecer-card"></div>
        <div class="result-actions">
            <a href="#" id="whatsappLink" class="whatsapp-link" target="_blank" rel="noopener">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Agendar call gratuita de 30 min →
            </a>
            <a href="/" class="btn-home">← Voltar ao início</a>
        </div>
    </div>
</div>

<script>
    window.FTIT = {
        whatsapp: '<?= addslashes($config['whatsapp']) ?>'
    };
</script>
<script src="/assets/js/diagnostico.js"></script>
</body>
</html>
