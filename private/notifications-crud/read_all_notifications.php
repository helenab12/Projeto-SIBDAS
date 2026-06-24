<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();
        
        // Iniciar transação para garantir que a atualização em massa ocorre em bloco
        $ligacao->beginTransaction();
        
        execute_query(
            "UPDATE NotificacaoUtilizador SET lida = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idUtilizador = :idUtilizador AND lida = 0",
            ['idUtilizador' => $_SESSION['id_utilizador']],
            $ligacao
        );
        
        // Confirmar transação na base de dados
        $ligacao->commit();
        $_SESSION['success_message'] = "Todas as notificações foram marcadas como lidas.";
    } catch (Exception $e) {
        // Em caso de erro, reverter todas as alterações da transação
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        error_log("Erro ao marcar todas as notificações como lidas: " . $e->getMessage());
        $_SESSION['server_error'] = "Erro ao marcar todas as notificações como lidas.";
    }
}

// Redireciona o utilizador de volta para a página de notificações
header("Location: " . BASE_URL . "/private/notifications.php");
exit;
