<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

start_session();
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['inbox.manage']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ligar à BD
        $ligacao = connect_to_db();
        $ligacao->beginTransaction();

        // Recolher dados do POST
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

            // Consultar registos
            $stmtSelect = execute_query("SELECT estado FROM PedidoDemonstracao WHERE idPedido = :id", ['id' => $id], $ligacao);
            $antigo = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($antigo && $antigo['estado'] !== $state) {
                // Atualizar registo
                execute_query(
                    "UPDATE PedidoDemonstracao SET estado = :estado, dataAtualizacao = NOW() WHERE idPedido = :id AND ativo = 1",
                    ['estado' => $state, 'id' => $id],
                    $ligacao
                );

                // Registar auditoria
                registar_auditoria_edicao(
                    $ligacao,
                    'PedidoDemonstracao',
                    $id,
                    ['estado' => $antigo['estado']],
                    ['estado' => $state]
                );
            }
        }

        $ligacao->commit();
        $_SESSION['success_message'] = "Alterações guardadas com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao guardar alterações: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../inbox.php");
exit;
