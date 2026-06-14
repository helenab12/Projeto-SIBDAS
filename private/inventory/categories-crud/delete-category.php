<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['category-id'] ?? null;
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
            "UPDATE CategoriaEquipamento SET ativo = 0 WHERE idCategoria = :id",
            ['id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Categoria eliminada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao eliminar categoria: " . $e->getMessage();
    }
}

header("Location: ../categories.php");
exit;
