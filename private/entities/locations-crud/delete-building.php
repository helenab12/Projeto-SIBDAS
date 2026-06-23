<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idEdificio = (int) aes_decrypt($_POST['building-id']);

        // Atualizar registo
        execute_query(
            "UPDATE Edificio SET ativo = 0 WHERE idEdificio = :id",
            ['id' => $idEdificio]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Edificio', $idEdificio, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Edifício removido (desativado) com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao apagar edifício: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
