<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idPiso = (int) aes_decrypt($_POST['floor-id']);

        // Atualizar registo
        execute_query(
            "UPDATE Piso SET ativo = 0 WHERE idPiso = :id",
            ['id' => $idPiso]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Piso', $idPiso, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Piso removido (desativado) com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao apagar piso: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
