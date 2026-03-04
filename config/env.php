<?php
$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    foreach (file($envFile) as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv($line);
        }
    }
}

return [
    'whatsapp'    => getenv('WHATSAPP_NUMBER') ?: '5500000000000',
    'email'       => getenv('EMAIL_TO')        ?: 'contato@ftit.com.br',
    'webhook_url' => getenv('WEBHOOK_URL')     ?: '',
    'webhook_key' => getenv('WEBHOOK_API_KEY') ?: '',
];
