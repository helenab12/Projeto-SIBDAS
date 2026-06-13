<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idLocalizacao = (int) aes_decrypt($_POST['room-id']);

        $ligacao = connect_to_db();

        // Soft-delete da sala definindo ativo = 0
        execute_query(
            "UPDATE Localizacao SET ativo = 0 WHERE idLocalizacao = :id",
            ['id' => $idLocalizacao],
            $ligacao
        );

        $_SESSION['success_message'] = "Sala removida (desativada) com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar sala: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
