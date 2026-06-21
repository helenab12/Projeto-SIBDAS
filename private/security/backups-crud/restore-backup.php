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
    $_SESSION['server_error'] = "Ficheiro de backup inválido.";
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Executar o comando mysql para importar o ficheiro .sql
$cmd = sprintf(
    '/Applications/XAMPP/xamppfiles/bin/mysql -h %s -P %s -u %s -p%s %s < %s',
    escapeshellarg(MYSQL_HOST),
    escapeshellarg(MYSQL_PORT),
    escapeshellarg(MYSQL_USERNAME),
    escapeshellarg(MYSQL_PASSWORD),
    escapeshellarg(MYSQL_DATABASE),
    escapeshellarg($filepath)
);

exec($cmd, $output, $return_var);

if ($return_var === 0) {
    try {
        $ligacao = connect_to_db();
        registar_auditoria($ligacao, 'Backup', null, 'Edição', 'Restauro', null, $filename);

        $stmtNotif = execute_query(
            "INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia) VALUES ('Sistema', 'Backup Restaurado', :mensagem, NULL, NULL)",
            ['mensagem' => "A base de dados foi restaurada a partir da cópia de segurança: $filename."],
            $ligacao
        );
        $idNotificacao = $ligacao->lastInsertId();
        execute_query(
            "INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao) SELECT :idNotif, idUtilizador, 0, CURRENT_TIMESTAMP FROM Utilizador WHERE ativo = 1 AND idPerfil = 1",
            ['idNotif' => $idNotificacao],
            $ligacao
        );
        $ligacao = null;
    } catch (Exception $e) {
    }

    // Forçar logout por segurança após restauro global
    session_destroy();
    session_start();
    $_SESSION['success_message'] = "Base de dados restaurada com sucesso. Por favor inicie sessão novamente.";
    header("Location: " . BASE_URL . "private/login/login.php");
    exit;
} else {
    $_SESSION['server_error'] = "Erro ao restaurar cópia de segurança (Código: $return_var).";
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}
