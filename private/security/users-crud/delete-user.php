<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['users.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedUserId = $_POST['user-id'] ?? '';

        if (empty(trim($encryptedUserId))) {
            throw new Exception("ID de utilizador não fornecido.");
        }

        $idUtilizador = aes_decrypt($encryptedUserId);
        if (!$idUtilizador) {
            throw new Exception("ID de utilizador inválido.");
        }

        $ligacao = connect_to_db();

        // Soft delete: set ativo = 0
        execute_query(
            "UPDATE Utilizador SET ativo = 0, dataAtualizacao = CURRENT_TIMESTAMP WHERE idUtilizador = :id",
            ['id' => $idUtilizador],
            $ligacao
        );

        $_SESSION['success_message'] = "Utilizador desativado com sucesso (movido para a reciclagem).";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao desativar utilizador: " . $e->getMessage();
    }
}

header("Location: ../users.php");
exit;
