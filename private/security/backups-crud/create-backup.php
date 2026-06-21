<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['security.backups']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

$filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$filepath = BASE_PATH . 'files/backups/' . $filename;

// Exportar BD sem tablespaces para evitar erro de permissão
$cmd = sprintf(
    '/Applications/XAMPP/xamppfiles/bin/mysqldump --no-tablespaces -h %s -P %s -u %s -p%s %s > %s',
    escapeshellarg(MYSQL_HOST),
    escapeshellarg(MYSQL_PORT),
    escapeshellarg(MYSQL_USERNAME),
    escapeshellarg(MYSQL_PASSWORD),
    escapeshellarg(MYSQL_DATABASE),
    escapeshellarg($filepath)
);

exec($cmd, $output, $return_var);

if ($return_var === 0 && file_exists($filepath)) {
    // Notificar os administradores
    try {
        $ligacao = connect_to_db();
        
        registar_auditoria($ligacao, 'Backup', null, 'Criação', 'Ficheiro', null, $filename);
        
        $stmtNotif = execute_query(
            "INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia) VALUES ('Sistema', 'Backup Criado', :mensagem, NULL, NULL)",
            ['mensagem' => "Foi criada uma nova cópia de segurança da base de dados ($filename)."],
            $ligacao
        );
        $idNotificacao = $ligacao->lastInsertId();
        
        execute_query(
            "INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao) SELECT :idNotif, idUtilizador, 0, CURRENT_TIMESTAMP FROM Utilizador WHERE ativo = 1 AND idPerfil = 1",
            ['idNotif' => $idNotificacao],
            $ligacao
        );
        
        $_SESSION['success_message'] = "Cópia de segurança criada com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        $_SESSION['success_message'] = "Backup criado, mas falhou ao notificar administradores.";
    }
} else {
    $_SESSION['server_error'] = "Erro ao criar cópia de segurança (Código: $return_var).";
}

header("Location: " . BASE_URL . "private/security/backups.php");
exit;
