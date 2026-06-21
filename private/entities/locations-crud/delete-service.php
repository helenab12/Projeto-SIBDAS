<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idServico = (int) aes_decrypt($_POST['service-id']);

        // Soft-delete do serviço definindo ativo = 0
        execute_query(
            "UPDATE Servico SET ativo = 0 WHERE idServico = :id",
            ['id' => $idServico]);

        registar_auditoria($ligacao, 'Servico', $idServico, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Serviço removido (desativado) com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao apagar serviço: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
