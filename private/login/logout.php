<?php
require_once __DIR__ . '/../../config/funcoes.php';

start_session();
$_SESSION['pessoaAtual'] = null;
$_SESSION['userAtual'] = null;

logout_and_redirect('private/login/login.php');
