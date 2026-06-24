<?php

// Definir constantes gerais
define('APP_NAME', 'HEBA');
define('APP_DESCRIPTION', 'Health Base');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', '© ' . date('Y') . ' ISEP');

// Definir caminhos base
define('BASE_URL', '/1240961/heba/');
// define('BASE_URL', '/'); // TODO: Trocar, apenas usar com live server
define('BASE_PATH', dirname(__DIR__) . '/');

// Definir modo debug
define('SHOW_DEBUG_BUTTONS', true);


// Ligar à BD
// define('MYSQL_HOST', 'localhost');
define('MYSQL_HOST', 'vsgate-s1.dei.isep.ipp.pt'); // TODO: Trocar, apenas usar com live server
define('MYSQL_PORT', '10464');
define('MYSQL_DATABASE', 'db1240961');
define('MYSQL_USERNAME', '1240961');
define('MYSQL_PASSWORD', 'barbosa_961');
define('MYSQL_AES_KEY', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD');

// Encriptar dados
define('OPENSSL_METHOD', 'AES-256-CBC');
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa');
define('OPENSSL_IV', 'BzKAbjuREsHgnw56');