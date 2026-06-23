<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['people.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['person-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        // Atualizar registo
        execute_query(
            "UPDATE Pessoa SET ativo = 0, dataAtualizacao = NOW() WHERE idPessoa = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Pessoa', $id, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Pessoa movida para a reciclagem com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao apagar pessoa: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../people_management.php");
exit;
