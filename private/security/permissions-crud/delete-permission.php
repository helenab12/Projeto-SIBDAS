<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

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
        $ligacao->beginTransaction();

        // 1. Eliminar associações na tabela intermédia PerfilPermissao
        execute_query(
            "DELETE FROM PerfilPermissao WHERE idPermissao = :id",
            ['id' => $id],
            $ligacao
        );

        // 2. Eliminar a permissão da tabela Permissao
        execute_query(
            "DELETE FROM Permissao WHERE idPermissao = :id",
            ['id' => $id],
            $ligacao
        );

        $ligacao->commit();
        $_SESSION['success_message'] = "Permissão eliminada com sucesso!";
    } catch (Exception $e) {
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao eliminar permissão: " . $e->getMessage();
    }
}

header("Location: ../permissions.php");
exit;
