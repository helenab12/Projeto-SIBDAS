<?php

define('APP_NAME', 'HEBA');
define('APP_DESCRIPTION', 'Health Base');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', '© 2026 ISEP');
define('BASE_URL', '/Projeto-SIBDAS/');
// define('BASE_URL', '/'); // TODO: Trocar, apenas usar com live server
define('BASE_PATH', dirname(__DIR__) . '/');
define('SHOW_DEBUG_BUTTONS', true);


// Conexão com a Base de Dados
// define('MYSQL_HOST', 'localhost');
define('MYSQL_HOST', '127.0.0.1'); // TODO: Trocar, apenas usar com live server
define('MYSQL_DATABASE', 'heba-db');
define('MYSQL_USERNAME', 'heba');
define('MYSQL_PASSWORD', '3LduNkJe55lVk0ia0RXvVQ1tZpA7OW5');
define('MYSQL_AES_KEY', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD');

// Encriptação dos dados sensíveis
define('OPENSSL_METHOD', 'AES-256-CBC');
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa');
define('OPENSSL_IV', 'BzKAbjuREsHgnw56');