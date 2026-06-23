<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['security.backups']);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['file'])) {
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Recolher dados do POST
$filename = basename($_POST['file']);
$filepath = BASE_PATH . 'files/backups/' . $filename;

// Validar ficheiro
if (!file_exists($filepath) || pathinfo($filepath, PATHINFO_EXTENSION) !== 'sql') {
    $_SESSION['server_error'] = "Ficheiro de backup não encontrado.";
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Eliminar backup
if (unlink($filepath)) {
    $_SESSION['success_message'] = "Backup eliminado com sucesso.";
    try {
        // Registar auditoria
        registar_auditoria($ligacao, 'Backup', null, 'Remoção', 'Ficheiro', $filename, null);
    } catch (Exception $e) {
        // Capturar erro
}
} else {
    $_SESSION['server_error'] = "Erro ao eliminar backup. Verifique as permissões de ficheiro.";
}

// Redirecionar
header("Location: " . BASE_URL . "private/security/backups.php");
exit;
