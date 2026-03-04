<?php
// Para produção, configure o web root do servidor para apontar diretamente
// para "laravel-backend/public". Em desenvolvimento local (XAMPP), este
// arquivo redireciona para o front controller do Laravel.
header('Location: laravel-backend/public/', true, 302);
exit;
