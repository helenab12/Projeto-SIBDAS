<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idEdificio = (int) aes_decrypt($_POST['building-id']);

        $ligacao = connect_to_db();

        // Soft-delete do edifício definindo ativo = 0
        execute_query(
            "UPDATE Edificio SET ativo = 0 WHERE idEdificio = :id",
            ['id' => $idEdificio],
            $ligacao
        );

        $_SESSION['success_message'] = "Edifício removido (desativado) com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar edifício: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
