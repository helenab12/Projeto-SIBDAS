<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

start_session();
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['inbox.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }

        // Atualizar registo
        execute_query(
            "UPDATE PedidoDemonstracao SET ativo = 0 WHERE idPedido = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria(
            $ligacao,
            'PedidoDemonstracao',
            $id,
            'Remoção',
            'ativo',
            1,
            0
        );

        $_SESSION['success_message'] = "Pedido de demonstração apagado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao apagar pedido: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../inbox.php");
exit;
