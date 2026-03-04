@extends('layouts.app')

@push('head')
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/privacidade.css') }}">
@endpush

@section('body')
<div class="container">
    <div class="header">
        <a href="{{ route('landing') }}" class="logo">
            <span class="back-arrow">←</span>
            <span class="ft">f(t)</span><span class="it"> it</span>
        </a>
        <h1>Política de <span class="highlight">Privacidade</span></h1>
        <p class="subtitle">Última atualização: {{ now()->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
    </div>

    <div class="policy-content">
        <section class="policy-section">
            <h2>1. Quem somos</h2>
            <p>A <strong>FTIT — f(t) it</strong>, pessoa jurídica de direito privado, inscrita no CNPJ sob o nº <strong>55.191.137/0001-62</strong>, é a controladora dos seus dados pessoais conforme a Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 — LGPD).</p>
            <p>Responsável: <strong>Flávio Tassan</strong></p>
            <p>Contato: <a href="mailto:contato@ftit.com.br">contato@ftit.com.br</a></p>
        </section>

        <section class="policy-section">
            <h2>2. Quais dados coletamos e para quê</h2>
            <p>Coletamos apenas os dados que você fornece voluntariamente no formulário de <strong>Diagnóstico Express</strong>:</p>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Dado coletado</th>
                        <th>Finalidade</th>
                        <th>Obrigatório?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nome</td>
                        <td>Identificação para o agendamento</td>
                        <td>Sim</td>
                    </tr>
                    <tr>
                        <td>Nome do negócio</td>
                        <td>Contexto e preparação para a conversa</td>
                        <td>Sim</td>
                    </tr>
                    <tr>
                        <td>WhatsApp / Telefone</td>
                        <td>Contato para confirmar o diagnóstico</td>
                        <td>Sim</td>
                    </tr>
                    <tr>
                        <td>E-mail</td>
                        <td>Canal alternativo de contato</td>
                        <td>Não</td>
                    </tr>
                    <tr>
                        <td>Segmento do negócio</td>
                        <td>Preparação de referências relevantes</td>
                        <td>Sim</td>
                    </tr>
                    <tr>
                        <td>Situação atual do site</td>
                        <td>Diagnóstico do ponto de partida</td>
                        <td>Sim</td>
                    </tr>
                    <tr>
                        <td>Principal dor / desafio</td>
                        <td>Preparação para a conversa de diagnóstico</td>
                        <td>Não</td>
                    </tr>
                </tbody>
            </table>
            <p class="note">Não coletamos dados sensíveis conforme definidos no art. 11 da LGPD. Não utilizamos cookies de rastreamento, pixels de anúncio ou qualquer tecnologia de coleta automática de dados de navegação.</p>
        </section>

        <section class="policy-section">
            <h2>3. Base legal e finalidade do tratamento</h2>
            <p>O tratamento dos seus dados pessoais se baseia nas seguintes hipóteses previstas na LGPD:</p>
            <ul>
                <li><strong>Consentimento (art. 7º, I)</strong> — você manifesta seu consentimento ao marcar o checkbox antes de enviar o formulário. O consentimento pode ser revogado a qualquer tempo.</li>
                <li><strong>Legítimo interesse (art. 7º, IX)</strong> — para a realização do diagnóstico gratuito solicitado por você e o contato comercial decorrente desse pedido.</li>
            </ul>
            <p>Os dados são utilizados exclusivamente para: agendamento e condução do diagnóstico de 30 minutos; contato comercial posterior relacionado aos serviços solicitados; e melhoria contínua dos serviços oferecidos pela FTIT.</p>
        </section>

        <section class="policy-section">
            <h2>4. Compartilhamento e armazenamento</h2>
            <p>Seus dados são transmitidos de forma segura (HTTPS) para nossa ferramenta de automação de processos internos (<strong>Make.com</strong>), utilizada exclusivamente para organização dos agendamentos e gestão de contatos comerciais.</p>
            <p><strong>Não vendemos, alugamos nem compartilhamos seus dados com terceiros para fins de marketing ou publicidade.</strong></p>
            <p>Não mantemos banco de dados próprio com suas informações pessoais. Os dados são retidos pelo período necessário à relação comercial e, após seu encerramento, são excluídos ou anonimizados.</p>
        </section>

        <section class="policy-section">
            <h2>5. Seus direitos como titular (art. 18 da LGPD)</h2>
            <p>Você tem direito a, a qualquer momento, solicitar:</p>
            <ul>
                <li><strong>Confirmação</strong> da existência de tratamento dos seus dados</li>
                <li><strong>Acesso</strong> aos dados que temos sobre você</li>
                <li><strong>Correção</strong> de dados incompletos, inexatos ou desatualizados</li>
                <li><strong>Anonimização, bloqueio ou eliminação</strong> dos seus dados desnecessários ou excessivos</li>
                <li><strong>Portabilidade</strong> dos dados para outro fornecedor de serviço</li>
                <li><strong>Eliminação</strong> dos dados tratados com base em consentimento</li>
                <li><strong>Revogação do consentimento</strong> a qualquer momento, sem prejuízo do tratamento já realizado</li>
            </ul>
            <p>Para exercer qualquer um desses direitos, entre em contato pelo e-mail <a href="mailto:contato@ftit.com.br">contato@ftit.com.br</a>. Responderemos em até 15 dias úteis.</p>
        </section>

        <section class="policy-section">
            <h2>6. Segurança dos dados</h2>
            <p>Adotamos medidas técnicas adequadas para proteger seus dados, incluindo transmissão via HTTPS e autenticação por chave de API nas integrações externas. Em caso de incidente de segurança que possa afetar seus direitos, você será notificado nos termos previstos na LGPD e nas normas da ANPD.</p>
        </section>

        <section class="policy-section">
            <h2>7. Alterações nesta política</h2>
            <p>Esta política pode ser atualizada periodicamente. Em caso de alterações relevantes, publicaremos a versão atualizada nesta página com a nova data de revisão. Recomendamos que você a consulte periodicamente.</p>
        </section>

        <section class="policy-section">
            <h2>8. Contato</h2>
            <p>Para dúvidas, solicitações ou reclamações relacionadas à privacidade e proteção de dados, entre em contato com o responsável pelo tratamento de dados da FTIT:</p>
            <div class="contact-card">
                <p><strong>Flávio Tassan — FTIT</strong></p>
                <p>E-mail: <a href="mailto:contato@ftit.com.br">contato@ftit.com.br</a></p>
                <p>CNPJ: 55.191.137/0001-62</p>
            </div>
        </section>
    </div>

    <div class="policy-footer">
        <a href="{{ route('landing') }}" class="back-link">← Voltar ao site</a>
        <p class="footer-info">CNPJ: 55.191.137/0001-62 &nbsp;·&nbsp; &copy; {{ date('Y') }} FTIT</p>
    </div>
</div>
@endsection

