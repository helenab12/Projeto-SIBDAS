<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['security.backups']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['file'])) {
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

$filename = basename($_POST['file']);
$filepath = BASE_PATH . 'files/backups/' . $filename;

if (!file_exists($filepath) || pathinfo($filepath, PATHINFO_EXTENSION) !== 'sql') {
    $_SESSION['server_error'] = "Ficheiro de backup não encontrado.";
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

if (unlink($filepath)) {
    $_SESSION['success_message'] = "Backup eliminado com sucesso.";
    try {

        registar_auditoria($ligacao, 'Backup', null, 'Remoção', 'Ficheiro', $filename, null);
    } catch (Exception $e) {}
} else {
    $_SESSION['server_error'] = "Erro ao eliminar backup. Verifique as permissões de ficheiro.";
}

header("Location: " . BASE_URL . "private/security/backups.php");
exit;
