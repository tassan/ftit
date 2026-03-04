<?php
// Em produção: configure o web root para apontar diretamente para public/
// Em desenvolvimento local (XAMPP): este arquivo redireciona para public/
header('Location: public/', true, 302);
exit;
