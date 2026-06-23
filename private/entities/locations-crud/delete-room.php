<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idLocalizacao = (int) aes_decrypt($_POST['room-id']);

        // Atualizar registo
        execute_query(
            "UPDATE Localizacao SET ativo = 0 WHERE idLocalizacao = :id",
            ['id' => $idLocalizacao]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Localizacao', $idLocalizacao, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Sala removida (desativada) com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao apagar sala: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
