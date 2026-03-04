# FTIT — Reestruturação do Projeto

## Objetivo

Reestruturar o repositório do site `ftit.com.br` seguindo boas práticas de organização, segurança e SEO. A estrutura atual tem todos os arquivos na raiz. A nova estrutura separa código-fonte de arquivos públicos e organiza assets corretamente. Ao final, o diagnóstico existente deve ser substituído pelo novo diagnóstico com IA.

---

## Estrutura atual (raiz do repositório)

```
/
├── .claude/
├── .github/
├── assets/
├── config/
├── lang/
├── .env
├── .gitignore
├── .htaccess
├── docker-compose.yml
├── Dockerfile
├── CLAUDE.md
├── diagnostico.php
├── ft_logo.svg
├── index.php
├── script.js
├── style.css
└── submit.php
```

---

## Estrutura alvo

```
/
├── .claude/
├── .github/
│   └── workflows/
│       └── deploy.yml
├── .env                          ← não alterar
├── .env.example                  ← criar
├── .gitignore                    ← atualizar
├── docker-compose.yml
├── Dockerfile
├── CLAUDE.md
├── index.php
│
└── public/                       ← WEB ROOT
    ├── index.php
    ├── diagnostico.php
    ├── obrigado.php              ← criar
    ├── robots.txt                ← criar
    ├── sitemap.xml               ← criar
    ├── .htaccess                 ← criar
    │
    ├── assets/
    │   ├── css/
    │   │   ├── style.css
    │   │   └── diagnostico.css  ← criar (placeholder vazio)
    │   ├── js/
    │   │   ├── script.js
    │   │   └── diagnostico.js   ← criar (placeholder vazio)
    │   └── img/
    │       └── ft_logo.svg
    │
    ├── api/
    │   ├── .htaccess             ← criar
    │   ├── submit.php
    │   └── diagnostico-ia.php   ← criar
    │
    ├── config/
    │   ├── .htaccess             ← criar
    │   ├── config.php            ← criar
    │   └── head.php              ← criar
    │
    └── lang/
        ├── .htaccess             ← criar
        ├── pt.json
        └── en.json
```

---

## Formulário do Diagnóstico — especificação completa

O `diagnostico.php` deve ser reescrito com os seguintes campos, organizados em blocos:

### Bloco 1 — Identificação
| Campo | Tipo | Obrigatório |
|---|---|---|
| Nome / Empresa | text | sim |
| Email | email | sim |
| WhatsApp | tel | sim |
| Cidade | text | sim |

### Bloco 2 — Segmento

Campo do tipo `select` agrupado com `<optgroup>`. Ao selecionar "Outro", exibir um `<input type="text">` adicional via JavaScript.

```html
<select name="segmento" id="segmento" required>
  <option value="" disabled selected>Selecione seu segmento</option>

  <optgroup label="Saúde & Bem-estar">
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
    <option value="Banho & Tosa">Banho & Tosa</option>
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

<!-- Exibido apenas quando "Outro" é selecionado -->
<input type="text" name="segmento_outro" id="segmento_outro"
       placeholder="Qual é o seu negócio?"
       style="display:none">
```

JavaScript para controlar o textbox:
```javascript
document.getElementById('segmento').addEventListener('change', function () {
  const outro = document.getElementById('segmento_outro');
  if (this.value === 'outro') {
    outro.style.display = 'block';
    outro.required = true;
  } else {
    outro.style.display = 'none';
    outro.required = false;
    outro.value = '';
  }
});
```

No `diagnostico-ia.php`, o valor real do segmento é resolvido assim:
```php
$segmento = ($input['segmento'] ?? '') === 'outro'
    ? htmlspecialchars(trim($input['segmento_outro'] ?? 'Outro'), ENT_QUOTES, 'UTF-8')
    : htmlspecialchars(trim($input['segmento'] ?? ''), ENT_QUOTES, 'UTF-8');
```

### Bloco 3 — Porte
| Campo | Tipo | Opções |
|---|---|---|
| Faturamento mensal estimado | radio ou select | Até R$10k / R$10k–30k / R$30k–80k / Acima de R$80k |
| Número de funcionários | radio ou select | Só eu / 2–5 pessoas / 6–15 pessoas / Mais de 15 |

### Bloco 4 — Presença digital
| Campo | Tipo | Opções |
|---|---|---|
| Tem site? | radio | Sim / Não / Tenho, mas não funciona direito |
| Está no Google Meu Negócio? | radio | Sim / Não / Não sei o que é isso |
| Tem Instagram ativo? | radio | Sim, posto regularmente / Tenho, mas não posto / Não tenho |
| Como a maioria dos clientes te encontra? | radio | Indicação de amigos / Google / Instagram / Passando na frente / Outro |

### Bloco 5 — Operação
| Campo | Tipo | Opções |
|---|---|---|
| Como você agenda atendimentos hoje? | radio | WhatsApp manual / Telefone / Sistema / Planilha / Não agendo (presencial) |
| Faz acompanhamento de clientes após o atendimento? | radio | Sim, sempre / Às vezes / Não faço |
| Horas por semana em tarefas administrativas repetitivas | radio | Menos de 2h / 2–5h / 5–10h / Mais de 10h |

### Bloco 6 — Dor e objetivo
| Campo | Tipo |
|---|---|
| Qual é o maior problema com sua presença digital hoje? | textarea |
| O que você quer resolver nos próximos 3 meses? | textarea |

---

## Ganchos de Contexto — mensagens dinâmicas no formulário

O formulário exibe mensagens personalizadas à medida que o lead preenche cada campo. Toda a lógica é client-side — sem chamada de API. O JS escuta eventos `input`, `change` e `blur` e injeta as mensagens em um elemento `<div id="gancho-feedback">` fixado entre os blocos.

### Estrutura do elemento de feedback

```html
<div id="gancho-feedback" class="gancho" aria-live="polite" style="display:none">
  <span class="gancho-emoji"></span>
  <p class="gancho-texto"></p>
  <small class="gancho-fonte"></small>
</div>
```

Função JS base:
```javascript
function exibirGancho(emoji, texto, fonte) {
  const el = document.getElementById('gancho-feedback');
  el.querySelector('.gancho-emoji').textContent = emoji;
  el.querySelector('.gancho-texto').textContent = texto;
  el.querySelector('.gancho-fonte').textContent = fonte ? '📎 ' + fonte : '';
  el.style.display = 'block';
  el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
```

---

### Ganchos por campo e condição

Todos os textos, condições de disparo e fontes verificadas estão listados abaixo. Implementar cada um como listener no campo correspondente.

#### Bloco 1 — Após preencher Nome + Empresa (blur no campo empresa)
```
Emoji: 👋
Texto: "Olá, {nome}! Boa decisão trazer a {empresa} para cá. Vamos descobrir exatamente onde estão as oportunidades digitais do seu negócio."
Fonte: (sem fonte — mensagem de boas-vindas)
```

#### Bloco 2 — Após selecionar Segmento

| Segmento selecionado | Emoji | Texto | Fonte |
|---|---|---|---|
| Dentista / Odontologia | 🦷 | "Clínicas odontológicas perdem em média 25% a 30% dos agendamentos por no-show. Lembretes automáticos via WhatsApp reduzem esse número em até 35%." | Doctoralia, Panorama das Clínicas e Hospitais 2025 / Tactium, 2025 |
| Clínica estética / Beleza | 💆 | "96% dos consumidores leem avaliações no Google antes de escolher onde fazer um procedimento estético. Sua reputação digital vale mais do que sua fachada." | Reclame Aqui, 2025 |
| Psicólogo / Terapeuta | 🧠 | "79% dos brasileiros pesquisam online antes de contratar qualquer serviço. Para serviços de saúde mental, a confiança começa na presença digital." | PwC, Global Consumer Insights Survey |
| Academia / Personal trainer | 💪 | "93% dos consumidores pesquisam no Google antes de contratar um serviço local. Quem não aparece online, não é considerado." | Hedgehog Digital + Opinion Box, State of Search Brasil |
| Nutricionista | 🥗 | "93% dos consumidores pesquisam online antes de contratar serviços de saúde. Sem presença digital, você depende 100% de indicação." | Hedgehog Digital + Opinion Box, State of Search Brasil |
| Fisioterapeuta | 🏥 | "9 em cada 10 brasileiros pesquisam na internet antes de decidir onde comprar ou contratar um serviço." | Sebrae / Offerwise + Google |
| Veterinário / Clínica veterinária | 🐾 | "O mercado pet brasileiro faturou R$ 75,4 bilhões em 2024 — crescimento de 9,6%. Serviços veterinários cresceram 15% no mesmo período. O cliente está disposto a pagar. Mas ele precisa te encontrar." | Abinpet + Instituto Pet Brasil, 2024 |
| Pet shop | 🐶 | "Pequenos e médios pet shops representam quase 50% do faturamento do setor pet no Brasil — R$ 36,6 bilhões. Mas a maioria ainda não tem presença digital estruturada." | Abinpet + Instituto Pet Brasil, 2024 |
| Banho & Tosa / Hotel / Adestrador | ✂️ | "O mercado pet brasileiro é o 3º maior do mundo, com 1,8 animal de estimação por residência. Donos de pets buscam serviços confiáveis — e a primeira busca começa no Google." | Abinpet + IBGE, 2024 |
| Restaurante / Cafeteria / Lanchonete | 🍽️ | "48% dos brasileiros pesquisam alimentação online antes de decidir onde comer. Restaurantes sem presença digital perdem para quem aparece no Google Maps." | Hedgehog Digital + Opinion Box, State of Search Brasil |
| Loja física / Moda | 👗 | "96% dos brasileiros pesquisam online antes de comprar. Para lojas físicas, isso significa que a vitrine digital importa tanto quanto a vitrine da rua." | Provokers + Google, 2018 |
| E-commerce / Loja online | 🛒 | "53% do tráfego de e-commerces vem de buscas orgânicas. SEO bem feito gera visitas constantes sem pagar por anúncio." | Dados de mercado SEO Brasil |
| Advogado / Jurídico | ⚖️ | "62% dos brasileiros buscam advogados online antes de pedir indicação para amigos. Sem site profissional, você não existe para esse público." | PwC, Global Consumer Insights Survey |
| Contador / Financeiro | 📊 | "79% dos brasileiros pesquisam online antes de contratar qualquer serviço profissional. Contabilidade não é exceção." | PwC, Global Consumer Insights Survey |
| Qualquer segmento em "Outro" | 💡 | "Independente do setor, 9 em cada 10 brasileiros pesquisam na internet antes de decidir onde comprar ou contratar. Seu negócio precisa aparecer nessa pesquisa." | Sebrae / Offerwise + Google |

#### Bloco 4 — Após responder "Tem site?"

| Resposta | Emoji | Texto | Fonte |
|---|---|---|---|
| Não | 🔍 | "Sem site, você é invisível para 93% dos consumidores que pesquisam online antes de contratar um serviço. Isso tem solução." | Hedgehog Digital + Opinion Box, State of Search Brasil |
| Tenho, mas não funciona direito | ⚠️ | "Um site que não converte é pior do que não ter site — transmite abandono. 96% dos consumidores leem avaliações e verificam a presença digital antes de contratar." | Reclame Aqui, 2025 |
| Sim | ✅ | "Ótimo que você já tem presença. Agora vamos entender se ela está trabalhando por você ou só ocupando espaço." | (sem fonte) |

#### Bloco 4 — Após responder "Google Meu Negócio?"

| Resposta | Emoji | Texto | Fonte |
|---|---|---|---|
| Não | 📍 | "Negócios no Google Meu Negócio aparecem diretamente nos resultados de busca e no Google Maps. Quem não está lá, some para clientes que buscam por serviços próximos." | Google / Provokers, pesquisa encomendada pelo Google |
| Não sei o que é isso | 📍 | "Google Meu Negócio é gratuito e coloca seu negócio no mapa — literalmente. Empresas listadas têm muito mais chances de ser encontradas por clientes na sua cidade." | Google / Provokers |

#### Bloco 5 — Após responder "Como agenda atendimentos?"

| Resposta | Emoji | Texto | Fonte |
|---|---|---|---|
| WhatsApp manual | 💬 | "Agenda manual pelo WhatsApp funciona — até escalar. Clínicas que automatizam confirmações reduzem no-show em até 35% e liberam a equipe para o que realmente importa." | Tactium, 2025 |
| Telefone | 📞 | "92% dos brasileiros preferem o WhatsApp para se comunicar com empresas. Quem ainda depende de ligação está perdendo contatos que nunca vão ligar." | CETIC.br |
| Planilha | 📋 | "Planilha funciona para controle. Mas não agenda, não confirma e não lembra o cliente. A automação faz as três coisas enquanto você atende." | (sem fonte — argumento lógico) |

#### Bloco 5 — Após responder "Horas em tarefas admin"

| Resposta | Emoji | Texto | Fonte |
|---|---|---|---|
| 5–10h | ⏱️ | "5 horas por semana em tarefas repetitivas = 260 horas por ano. São 6 semanas de trabalho que poderiam ir para atendimento, vendas ou descanso." | (cálculo próprio — transparente) |
| Mais de 10h | 🚨 | "Mais de 10 horas semanais em tarefas administrativas é um sinal claro: seu negócio precisa de automação. Esse tempo está custando crescimento." | (cálculo próprio — transparente) |

---

### Regras de implementação

1. Cada gancho dispara **uma única vez** por sessão — não repetir ao voltar ao campo
2. A mensagem fica visível até o próximo gancho substituí-la
3. A `<small class="gancho-fonte">` só é exibida quando há fonte — nunca exibir campo vazio
4. No mobile, o scroll para o gancho deve ser suave e não forçar o teclado a fechar
5. O elemento `#gancho-feedback` deve ter `aria-live="polite"` para acessibilidade

---



### 1. Criar diretórios

```bash
mkdir -p public/assets/css
mkdir -p public/assets/js
mkdir -p public/assets/img
mkdir -p public/api
mkdir -p public/config
mkdir -p public/lang
```

### 2. Mover arquivos existentes

```bash
mv index.php        public/index.php
mv diagnostico.php  public/diagnostico.php
mv style.css        public/assets/css/style.css
mv script.js        public/assets/js/script.js
mv ft_logo.svg      public/assets/img/ft_logo.svg
mv submit.php       public/api/submit.php
cp -r config/*      public/config/
cp -r lang/*        public/lang/
```

### 3. Atualizar referências de path em `public/index.php` e `public/diagnostico.php`

Substituir:
- `/style.css` → `/assets/css/style.css`
- `/script.js` → `/assets/js/script.js`
- `/ft_logo.svg` → `/assets/img/ft_logo.svg`
- `/submit.php` → `/api/submit.php`
- Qualquer `include`/`require` para `/config/` → `__DIR__ . '/config/'`
- Qualquer fetch para `/lang/` → verificar se o path continua correto

### 4. Criar `public/.htaccess`

```apache
Options -Indexes
ServerSignature Off

RewriteEngine On

# URLs limpas — remove extensão .php
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.php -f
RewriteRule ^([^\.]+)$ $1.php [NC,L]

# Redirect www → non-www
RewriteCond %{HTTP_HOST} ^www\.ftit\.com\.br [NC]
RewriteRule ^ https://ftit.com.br%{REQUEST_URI} [R=301,L]

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]

# Cache de assets estáticos
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/x-icon "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
  ExpiresByType text/html "access plus 1 hour"
</IfModule>

# Compressão gzip
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css application/javascript application/json
</IfModule>

# Headers de segurança
<IfModule mod_headers.c>
  Header always set X-Frame-Options "SAMEORIGIN"
  Header always set X-Content-Type-Options "nosniff"
  Header always set Referrer-Policy "strict-origin-when-cross-origin"
  Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>
```

### 5. Criar `public/api/.htaccess`

```apache
Options -Indexes

RewriteEngine On
RewriteCond %{HTTP_X_REQUESTED_WITH} !XMLHttpRequest [NC]
RewriteCond %{HTTP_REFERER} !^https://ftit\.com\.br [NC]
RewriteRule ^ - [F,L]
```

### 6. Criar `public/config/.htaccess` e `public/lang/.htaccess`

```apache
Order deny,allow
Deny from all
```

### 7. Criar `public/config/config.php`

```php
<?php
$envFile = dirname(__DIR__, 2) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
        putenv(trim($key) . '=' . trim($value));
    }
}

define('ANTHROPIC_API_KEY', getenv('ANTHROPIC_API_KEY') ?: '');
define('MAKE_WEBHOOK_URL',  getenv('MAKE_WEBHOOK_URL')  ?: '');
define('WHATSAPP_NUMBER',   getenv('WHATSAPP_NUMBER')   ?: '5511999999999');
define('APP_ENV',           getenv('APP_ENV')           ?: 'production');
define('BASE_URL',          'https://ftit.com.br');
```

> Se já existir conteúdo em `/config/config.php`, preservar e apenas adicionar as constantes que faltarem.

### 8. Criar `public/config/head.php`

```php
<?php
/**
 * Uso em cada página:
 * $page = [
 *   'title'       => '...',
 *   'description' => '...',
 *   'url'         => BASE_URL . '/...',
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

<link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
<link rel="apple-touch-icon" href="/assets/img/favicon/apple-touch-icon.png">
```

### 9. Integrar `head.php` nas páginas

Em `public/index.php`, adicionar no topo antes do DOCTYPE:
```php
<?php
require_once __DIR__ . '/config/config.php';
$page = [
  'title'       => 'Sites e Automação para Pequenas Empresas',
  'description' => 'Seu site e seus processos trabalhando por você. Desenvolvimento web e automação com 10+ anos de experiência.',
  'url'         => BASE_URL,
];
?>
```
Dentro do `<head>`, substituir as meta tags existentes por:
```php
<?php require_once __DIR__ . '/config/head.php'; ?>
```

Em `public/diagnostico.php`, adicionar no topo:
```php
<?php
require_once __DIR__ . '/config/config.php';
$page = [
  'title'       => 'Diagnóstico Digital Gratuito',
  'description' => 'Descubra em 2 minutos os principais gaps digitais do seu negócio e receba um parecer personalizado com IA.',
  'url'         => BASE_URL . '/diagnostico',
];
?>
```
E substituir as meta tags existentes por:
```php
<?php require_once __DIR__ . '/config/head.php'; ?>
```

### 10. Reescrever `public/diagnostico.php` — formulário completo

O formulário deve conter todos os campos dos 6 blocos especificados na seção "Formulário do Diagnóstico". Requisitos:

- Visual consistente com o `style.css` existente (dark background `#0D0D0F`, accent `#7C3AED`)
- Bloco de segmento usando `<select>` com `<optgroup>` conforme HTML especificado acima
- JavaScript para exibir/ocultar o textbox quando "Outro" é selecionado
- Ao submeter, o form faz `fetch` para `/api/diagnostico-ia.php` com os dados em JSON
- Enquanto aguarda a resposta, exibir estado de loading ("Analisando seu negócio...")
- Ao receber o parecer, esconder o formulário e renderizar o resultado na mesma página
- O parecer renderizado deve exibir: título, situação atual, gaps (lista), potencial, próximos passos e CTA
- O CTA deve abrir WhatsApp via `https://wa.me/${WHATSAPP_NUMBER}` com mensagem pré-preenchida: "Olá! Fiz o diagnóstico digital no site da FTIT e gostaria de agendar a call de 30 minutos."

### 11. Criar `public/api/diagnostico-ia.php`

```php
<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . BASE_URL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid input']));
}

// Resolve segmento: se "outro", usa o textbox
$segmento = ($input['segmento'] ?? '') === 'outro'
    ? htmlspecialchars(trim($input['segmento_outro'] ?? 'Outro'), ENT_QUOTES, 'UTF-8')
    : htmlspecialchars(trim($input['segmento'] ?? ''), ENT_QUOTES, 'UTF-8');

$campos = ['nome', 'email', 'cidade', 'faturamento', 'funcionarios',
           'tem_site', 'google_meu_negocio', 'instagram', 'como_acham',
           'agendamento', 'followup', 'horas_admin', 'problema', 'objetivo'];

$dados = ['segmento' => $segmento];
foreach ($campos as $campo) {
    $dados[$campo] = htmlspecialchars(trim($input[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}

$prompt = buildPrompt($dados);
$resultado = callAnthropic($prompt);

if ($resultado['success']) {
    dispatchWebhook($dados, $resultado['parecer']);
    echo json_encode(['success' => true, 'parecer' => $resultado['parecer']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao gerar diagnóstico. Tente novamente.']);
}

function buildPrompt(array $d): string {
    return <<<EOT
Você é um consultor sênior da FTIT, especializado em transformação digital para pequenos negócios no Brasil.

Analise os dados deste lead e gere um diagnóstico digital personalizado. Seu objetivo é demonstrar expertise real e criar desejo genuíno pelo serviço — sem pressão, sem exagero.

DADOS DO LEAD:
- Nome/Empresa: {$d['nome']}
- Segmento: {$d['segmento']}
- Cidade: {$d['cidade']}
- Faturamento mensal estimado: {$d['faturamento']}
- Número de funcionários: {$d['funcionarios']}
- Tem site? {$d['tem_site']}
- Está no Google Meu Negócio? {$d['google_meu_negocio']}
- Tem Instagram ativo? {$d['instagram']}
- Como clientes encontram o negócio: {$d['como_acham']}
- Como agenda atendimentos: {$d['agendamento']}
- Faz acompanhamento pós-atendimento? {$d['followup']}
- Horas semanais em tarefas administrativas: {$d['horas_admin']}
- Maior problema digital hoje: {$d['problema']}
- Objetivo nos próximos 3 meses: {$d['objetivo']}

REGRAS:
1. Comece validando o contexto — reconheça o que o negócio tem ou faz bem
2. Identifique 2 a 3 gaps concretos com impacto direto em faturamento ou captação
3. Use dados reais do mercado brasileiro quando possível
4. Se o negócio agenda manualmente ou gasta muitas horas em tarefas repetitivas, destaque o potencial de automação
5. Aponte próximos passos em direção natural aos serviços da FTIT: site estratégico e/ou automação de processos
6. Finalize com CTA personalizado e urgente para agendar uma call de 30 minutos com a FTIT
7. Tom: direto, especialista, humano — consultor experiente, não chatbot

Responda APENAS com JSON válido, sem markdown, sem texto antes ou depois:
{
  "titulo": "string — ex: Diagnóstico Digital — [Nome do negócio]",
  "situacao_atual": "string — 2 a 3 frases contextualizando o negócio",
  "gaps": [
    { "problema": "string", "impacto": "string — com número ou dado quando possível" }
  ],
  "potencial": "string — o que o negócio pode ganhar resolvendo esses gaps",
  "proximos_passos": "string — recomendação que aponta pro serviço FTIT adequado",
  "cta_texto": "string — frase personalizada para agendar a call",
  "urgencia": "alta|media|baixa"
}
EOT;
}

function callAnthropic(string $prompt): array {
    $apiKey = ANTHROPIC_API_KEY;
    if (!$apiKey) return ['success' => false];

    $payload = json_encode([
        'model'      => 'claude-sonnet-4-5',
        'max_tokens' => 1024,
        'messages'   => [['role' => 'user', 'content' => $prompt]]
    ]);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
        ],
    ]);

    $body     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$body) return ['success' => false];

    $response = json_decode($body, true);
    $text     = $response['content'][0]['text'] ?? '';
    $parecer  = json_decode($text, true);

    if (!$parecer) return ['success' => false];

    return ['success' => true, 'parecer' => $parecer];
}

function dispatchWebhook(array $dados, array $parecer): void {
    $url = MAKE_WEBHOOK_URL;
    if (!$url) return;

    $payload = json_encode([
        'lead'      => $dados,
        'parecer'   => $parecer,
        'timestamp' => date('c'),
        'source'    => 'diagnostico-ia',
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    ]);
    curl_exec($ch);
    curl_close($ch);
}
```

### 12. Criar `public/robots.txt`

```
User-agent: *
Allow: /
Disallow: /api/
Disallow: /config/
Disallow: /lang/

Sitemap: https://ftit.com.br/sitemap.xml
```

### 13. Criar `public/sitemap.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">

  <url>
    <loc>https://ftit.com.br/</loc>
    <xhtml:link rel="alternate" hreflang="pt-BR" href="https://ftit.com.br/"/>
    <xhtml:link rel="alternate" hreflang="en"    href="https://ftit.com.br/?lang=en"/>
    <changefreq>monthly</changefreq>
    <priority>1.0</priority>
  </url>

  <url>
    <loc>https://ftit.com.br/diagnostico</loc>
    <xhtml:link rel="alternate" hreflang="pt-BR" href="https://ftit.com.br/diagnostico"/>
    <xhtml:link rel="alternate" hreflang="en"    href="https://ftit.com.br/diagnostico?lang=en"/>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>

</urlset>
```

### 14. Criar `public/obrigado.php`

```php
<?php
require_once __DIR__ . '/config/config.php';
$page = [
  'title'       => 'Diagnóstico Enviado',
  'description' => 'Seu diagnóstico foi gerado. Em breve entraremos em contato.',
  'url'         => BASE_URL . '/obrigado',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php require_once __DIR__ . '/config/head.php'; ?>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <main>
    <h1>Diagnóstico enviado!</h1>
    <p>Entraremos em contato pelo WhatsApp em até 24h.</p>
    <a href="/">Voltar para o início</a>
  </main>
</body>
</html>
```

### 15. Criar arquivos placeholder

```bash
touch public/assets/css/diagnostico.css
touch public/assets/js/diagnostico.js
```

### 16. Criar `.env.example`

```env
# Anthropic API
ANTHROPIC_API_KEY=sk-ant-...

# Make (Integromat) Webhook
MAKE_WEBHOOK_URL=https://hook.eu2.make.com/...

# WhatsApp
WHATSAPP_NUMBER=5511999999999

# Ambiente
APP_ENV=production
```

### 17. Atualizar `.gitignore`

Garantir que a linha abaixo existe:
```
.env
```

### 18. Atualizar `docker-compose.yml`

Verificar se o volume aponta para `./public`:
```yaml
volumes:
  - ./public:/var/www/html
```

### 19. Atualizar `.github/workflows/deploy.yml`

O rsync deve apontar para `./public/`:
```yaml
rsync -avz --delete ./public/ ${{ secrets.HOSTINGER_PATH }}/
```

---

## Validação final

Após executar todas as tarefas:

1. `public/index.php` carrega sem erro — CSS, JS e logo corretos
2. `public/diagnostico.php` renderiza o formulário completo com os 6 blocos
3. Campo "Outro" no segmento exibe o textbox ao ser selecionado
4. Submit do formulário chama `/api/diagnostico-ia.php` e renderiza o parecer na mesma página
5. `public/api/submit.php` continua funcionando para o form de contato existente
6. `https://ftit.com.br/robots.txt` acessível
7. `https://ftit.com.br/sitemap.xml` válido
8. Acesso direto a `/api/`, `/config/` e `/lang/` retorna 403
9. `.env` não está dentro de `public/`
10. `docker-compose up` sobe sem erros

---

## O que NÃO fazer

- Não apagar arquivos originais antes de confirmar que os novos paths funcionam
- Não commitar `.env` com valores reais
- Não mover `.env` para dentro de `public/`
- Não alterar a lógica de negócio do `submit.php` existente
- Não remover os arquivos de i18n `pt.json` e `en.json`
