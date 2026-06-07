<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

start_session();
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();
        $ligacao->beginTransaction();

        $newStates = $_POST['states'] ?? [];
        $allowedStates = ['Novo', 'Em Contacto', 'Fechado'];

        foreach ($newStates as $encryptedId => $state) {
            $id = aes_decrypt($encryptedId);
            if ($id === false) {
                throw new Exception("Dados de alteração inválidos.");
            }
            $id = (int)$id;
            if ($id <= 0 || !in_array($state, $allowedStates)) {
                throw new Exception("Dados de alteração inválidos.");
            }

            execute_query(
                "UPDATE PedidoDemonstracao SET estado = :estado, dataAtualizacao = NOW() WHERE idPedido = :id AND estado != :estado_check AND ativo = 1",
                ['estado' => $state, 'id' => $id, 'estado_check' => $state],
                $ligacao
            );
        }

        $ligacao->commit();
        $_SESSION['success_message'] = "Alterações guardadas com sucesso!";
    } catch (Exception $e) {
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao guardar alterações: " . $e->getMessage();
    }
}

header("Location: ../inbox.php");
exit;
