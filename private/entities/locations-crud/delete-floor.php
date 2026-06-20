<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idPiso = (int) aes_decrypt($_POST['floor-id']);

        $ligacao = connect_to_db();

        // Soft-delete do piso definindo ativo = 0
        execute_query(
            "UPDATE Piso SET ativo = 0 WHERE idPiso = :id",
            ['id' => $idPiso],
            $ligacao
        );

        registar_auditoria($ligacao, 'Piso', $idPiso, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Piso removido (desativado) com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar piso: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
