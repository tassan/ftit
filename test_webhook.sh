#!/bin/bash
# ─────────────────────────────────────────────
#  FTIT — Webhook test script
#  Usage: bash test_webhook.sh
# ─────────────────────────────────────────────

# Read credentials from .env to avoid shell interpretation of special characters
ENV_FILE="$(dirname "$0")/.env"
WEBHOOK_URL=$(grep '^WEBHOOK_URL=' "$ENV_FILE" | cut -d'=' -f2-)
API_KEY=$(grep '^WEBHOOK_API_KEY=' "$ENV_FILE" | cut -d'=' -f2-)

PAYLOAD='{
  "nome": "Ana Silva",
  "negocio": "Clínica Renova",
  "email": "ana@clinica.com.br",
  "telefone": "(11) 99999-9999",
  "segmento": "Saúde",
  "temSite": "Não tenho",
  "dor": "Perco clientes porque não apareço no Google e não tenho como mostrar meu trabalho online."
}'

echo ""
echo "▶  Sending payload to webhook..."
echo "────────────────────────────────"
echo "$PAYLOAD"
echo "────────────────────────────────"
echo ""

HTTP_CODE=$(curl -s -o /tmp/webhook_response.json -w "%{http_code}" \
  -X POST "$WEBHOOK_URL" \
  -H "Content-Type: application/json" \
  -H "x-make-apikey: $API_KEY" \
  -d "$PAYLOAD")

BODY=$(cat /tmp/webhook_response.json)

echo "HTTP Status : $HTTP_CODE"
echo "Response    : $BODY"
echo ""

if [ "$HTTP_CODE" -lt 400 ]; then
  echo "✓ Webhook accepted the request."
else
  echo "✗ Webhook returned an error. Check the URL and API key."
fi

echo ""
read -n 1 -s -r -p "Press any key to exit..."
echo ""