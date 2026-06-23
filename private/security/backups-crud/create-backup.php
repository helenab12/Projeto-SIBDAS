<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['security.backups']);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Inicializar variáveis
$filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$filepath = BASE_PATH . 'files/backups/' . $filename;

// Exportar BD
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

// Validar sucesso
if ($return_var === 0 && file_exists($filepath)) {
    try {
        // Ligar à BD
$ligacao = connect_to_db();
        
        // Registar auditoria
        registar_auditoria($ligacao, 'Backup', null, 'Criação', 'Ficheiro', null, $filename);
        
        // Inserir notificação
        $stmtNotif = execute_query(
            "INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia) VALUES ('Sistema', 'Backup Criado', :mensagem, NULL, NULL)",
            ['mensagem' => "Foi criada uma nova cópia de segurança da base de dados ($filename)."],
            $ligacao
        );
        $idNotificacao = $ligacao->lastInsertId();
        
        // Notificar utilizadores
        execute_query(
            "INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao) SELECT :idNotif, idUtilizador, 0, CURRENT_TIMESTAMP FROM Utilizador WHERE ativo = 1 AND idPerfil = 1",
            ['idNotif' => $idNotificacao],
            $ligacao
        );
        
        // Definir mensagem sucesso
        $_SESSION['success_message'] = "Cópia de segurança criada com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
// Definir mensagem aviso
        $_SESSION['success_message'] = "Backup criado, mas falhou ao notificar administradores.";
    }
} else {
    // Definir mensagem erro
    $_SESSION['server_error'] = "Erro ao criar cópia de segurança (Código: $return_var).";
}

// Redirecionar
header("Location: " . BASE_URL . "private/security/backups.php");
exit;
