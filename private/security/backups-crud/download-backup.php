<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['security.backups']);

if (!isset($_GET['file'])) {
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

$filename = basename($_GET['file']);
$filepath = BASE_PATH . 'files/backups/' . $filename;

if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) === 'sql') {
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
    $_SESSION['server_error'] = "Ficheiro de backup não encontrado.";
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}
