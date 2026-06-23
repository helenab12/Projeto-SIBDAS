<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['categories.delete']);

// Processar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Desencriptar ID
        $encryptedId = $_POST['category-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        // Desativar categoria
        execute_query(
            "UPDATE CategoriaEquipamento SET ativo = 0 WHERE idCategoria = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'CategoriaEquipamento', $id, 'Remoção', 'ativo', '1', '0');

        // Definir sucesso
        $_SESSION['success_message'] = "Categoria eliminada com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao eliminar categoria: " . $e->getMessage();
    }
}

// Redirecionar utilizador
header("Location: ../categories.php");
exit;
