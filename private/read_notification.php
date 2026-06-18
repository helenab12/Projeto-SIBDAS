<?php
require_once(__DIR__ . "/../config/funcoes.php");
redirect_if_not_logged();

// Verifica se o ID foi passado por GET e decripta o mesmo
if (isset($_GET['id'])) {
    $idStr = $_GET['id'];
    $id = aes_decrypt($idStr);

    if ($id !== false) {
        try {
            $ligacao = connect_to_db();

            // Marca a notificação como lida para o utilizador atual
            execute_query(
                "UPDATE NotificacaoUtilizador SET lida = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idNotificacao = :idNotificacao AND idUtilizador = :idUtilizador",
                ['idNotificacao' => $id, 'idUtilizador' => $_SESSION['id_utilizador']],
                $ligacao
            );
        } catch (Exception $e) {
            error_log("Erro ao marcar notificação como lida: " . $e->getMessage());
            $_SESSION['server_error'] = "Ocorreu um erro ao marcar a notificação como lida.";
        }
    } else {
        $_SESSION['server_error'] = "ID da notificação inválido.";
    }
}

// Redireciona o utilizador de volta para a página de notificações
header("Location: " . BASE_URL . "/private/notifications.php");
exit;
