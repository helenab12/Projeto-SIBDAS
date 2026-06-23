<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['security.backups']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['file'])) {
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Recolher dados do POST
$filename = basename($_POST['file']);
$filepath = BASE_PATH . 'files/backups/' . $filename;

// Validar dados
if (!file_exists($filepath) || pathinfo($filepath, PATHINFO_EXTENSION) !== 'sql') {
    $_SESSION['server_error'] = "Ficheiro de backup inválido.";
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}

// Construir comando
$cmd = sprintf(
    '/Applications/XAMPP/xamppfiles/bin/mysql -h %s -P %s -u %s -p%s %s < %s',
    escapeshellarg(MYSQL_HOST),
    escapeshellarg(MYSQL_PORT),
    escapeshellarg(MYSQL_USERNAME),
    escapeshellarg(MYSQL_PASSWORD),
    escapeshellarg(MYSQL_DATABASE),
    escapeshellarg($filepath)
);

// Executar comando
exec($cmd, $output, $return_var);

// Verificar sucesso
if ($return_var === 0) {
    try {
        // Ligar à BD
$ligacao = connect_to_db();
        
        // Registar auditoria
        registar_auditoria($ligacao, 'Backup', null, 'Edição', 'Restauro', null, $filename);

        // Query Notificação
        $stmtNotif = execute_query(
            "INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia) VALUES ('Sistema', 'Backup Restaurado', :mensagem, NULL, NULL)",
            ['mensagem' => "A base de dados foi restaurada a partir da cópia de segurança: $filename."],
            $ligacao
        );
        $idNotificacao = $ligacao->lastInsertId();
        
        // Query Notificação Utilizador
        execute_query(
            "INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao) SELECT :idNotif, idUtilizador, 0, CURRENT_TIMESTAMP FROM Utilizador WHERE ativo = 1 AND idPerfil = 1",
            ['idNotif' => $idNotificacao],
            $ligacao
        );
        
        // Fechar ligação
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
}

    // Terminar sessão
    session_destroy();
    // Iniciar sessão
    session_start();
    
    // Definir sucesso e redirecionar
    $_SESSION['success_message'] = "Base de dados restaurada com sucesso. Por favor inicie sessão novamente.";
    // Redirecionar
    header("Location: " . BASE_URL . "private/login/login.php");
    exit;
} else {
    $_SESSION['server_error'] = "Erro ao restaurar cópia de segurança (Código: $return_var).";
    // Redirecionar
    header("Location: " . BASE_URL . "private/security/backups.php");
    exit;
}
