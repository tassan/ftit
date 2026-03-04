# FTIT — f(t) it

Website institucional da FTIT, consultoria de transformação digital para pequenas empresas.

## Stack

- PHP 8.2 (Apache)
- HTML, CSS, JavaScript vanilla
- JSON i18n (PT/EN)
- cURL para integrações externas (OpenAI, Make.com)

## Estrutura

```
public/              Web root (servida pelo Apache)
├── index.php        Landing page
├── diagnostico.php  Diagnóstico Digital (formulário multi-etapas + IA)
├── privacidade.php  Política de Privacidade
├── obrigado.php     Página de agradecimento
├── api/
│   └── diagnostico-ia.php  API: gera parecer com OpenAI
├── assets/
│   ├── css/         Estilos
│   └── js/          Scripts
├── config/
│   ├── config.php   Carrega variáveis de ambiente
│   └── head.php     Partial de <head> compartilhado
└── lang/
    ├── pt.json      Tradução PT
    └── en.json      Tradução EN
```

## Configuração

Copie `.env.example` para `.env` e preencha as variáveis:

```bash
cp .env.example .env
```

| Variável                  | Descrição                                     |
|---------------------------|-----------------------------------------------|
| `APP_ENV`                 | `production` ou `local`                       |
| `APP_URL`                 | URL base do site                              |
| `WHATSAPP_NUMBER`         | Número WhatsApp (somente dígitos, com DDI)    |
| `EMAIL_TO`                | E-mail de contato                             |
| `MAKE_WEBHOOK_URL`        | URL do webhook Make.com                       |
| `WEBHOOK_API_KEY`         | Chave de autenticação do webhook              |
| `OPENAI_API_KEY`          | Chave da API OpenAI                           |
| `OPENAI_MODEL_DIAGNOSTICO`| Modelo OpenAI (padrão: `gpt-4.1-mini`)       |

## Rodar localmente com Docker

```bash
docker compose up --build
```

O site ficará disponível em <http://localhost:8080>.

## URLs

| URL            | Arquivo               |
|----------------|-----------------------|
| `/`            | `public/index.php`    |
| `/diagnostico` | `public/diagnostico.php` |
| `/privacidade` | `public/privacidade.php` |
| `/obrigado`    | `public/obrigado.php` |

## Licença

Proprietário — Flávio Tassan / FTIT
