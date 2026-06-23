<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idServico = (int) aes_decrypt($_POST['service-id']);

        // Atualizar registo
        execute_query(
            "UPDATE Servico SET ativo = 0 WHERE idServico = :id",
            ['id' => $idServico]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Servico', $idServico, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Serviço removido (desativado) com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao apagar serviço: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
