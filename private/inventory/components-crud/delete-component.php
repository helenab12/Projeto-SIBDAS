<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['components.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['component-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        $ligacao = connect_to_db();
        execute_query(
            "UPDATE Componente SET ativo = 0 WHERE idComponente = :id",
            ['id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Componente eliminado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao eliminar componente: " . $e->getMessage();
    }
}

header("Location: ../components.php");
exit;
