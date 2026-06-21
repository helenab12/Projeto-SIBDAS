<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['suppliers.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apagar_fornecedor'])) {
    try {
        $encryptedId = $_POST['supplier-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        execute_query(
            "UPDATE Fornecedor SET ativo = 0, dataAtualizacao = NOW() WHERE idFornecedor = :id",
            ['id' => $id]);

        registar_auditoria($ligacao, 'Fornecedor', $id, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Fornecedor movido para a reciclagem com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar fornecedor: " . $e->getMessage();
    }
}

header("Location: ../suppliers.php");
exit;
