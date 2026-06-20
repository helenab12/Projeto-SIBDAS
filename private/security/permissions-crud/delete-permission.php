<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['permissions.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['permission-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        $ligacao = connect_to_db();
        execute_query(
            "UPDATE Permissao SET ativo = 0 WHERE idPermissao = :id",
            ['id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Permissão eliminada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao eliminar permissão: " . $e->getMessage();
    }
}

header("Location: ../permissions.php");
exit;
