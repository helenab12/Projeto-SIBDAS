<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['people.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['person-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        execute_query(
            "UPDATE Pessoa SET ativo = 0, dataAtualizacao = NOW() WHERE idPessoa = :id",
            ['id' => $id]);

        registar_auditoria($ligacao, 'Pessoa', $id, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Pessoa movida para a reciclagem com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar pessoa: " . $e->getMessage();
    }
}

header("Location: ../people_management.php");
exit;
