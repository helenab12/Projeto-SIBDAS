<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['security.backups']);

// Validar dados
if (!isset($_GET['file'])) {
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Inicializar variáveis
$filename = basename($_GET['file']);
$filepath = BASE_PATH . 'files/backups/' . $filename;

// Validar ficheiro
if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) === 'sql') {
    // Forçar download
    header('Content-Description: File Transfer');
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
} else {
    // Reportar erro
    $_SESSION['server_error'] = "Ficheiro de backup não encontrado.";
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}
