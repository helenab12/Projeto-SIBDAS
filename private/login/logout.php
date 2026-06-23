<?php
// Carregar dependências
require_once __DIR__ . '/../../config/funcoes.php';

// Iniciar sessão
start_session();

// Limpar variáveis de sessão
$_SESSION['pessoaAtual'] = null;
$_SESSION['userAtual'] = null;

// Terminar sessão
logout_and_redirect('private/login/login.php');
