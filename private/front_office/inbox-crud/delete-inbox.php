<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

start_session();
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        
        $ligacao = connect_to_db();
        execute_query(
            "UPDATE PedidoDemonstracao SET ativo = 0, dataAtualizacao = NOW() WHERE idPedido = :id",
            ['id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Pedido de demonstração apagado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar pedido: " . $e->getMessage();
    }
}

header("Location: ../inbox.php");
exit;
