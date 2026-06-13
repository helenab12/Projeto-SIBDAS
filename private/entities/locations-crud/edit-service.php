<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idServico = (int) aes_decrypt($_POST['service-id']);
        $nomeServico = trim($_POST['service-name'] ?? '');

        // Sanitização
        $nomeServico = capitalize_name($nomeServico);

        // Validação
        if (empty($nomeServico)) {
            throw new Exception("O nome do serviço não pode estar vazio.");
        }

        $ligacao = connect_to_db();

        // Buscar o idPiso deste serviço para verificação de duplicados
        $stmtServico = execute_query(
            "SELECT idPiso FROM Servico WHERE idServico = :id",
            ['id' => $idServico],
            $ligacao
        );
        $servicoRow = $stmtServico->fetch(PDO::FETCH_ASSOC);

        if (!$servicoRow) {
            throw new Exception("Serviço não encontrado.");
        }

        $idPiso = $servicoRow['idPiso'];

        // Verificar se já existe outro Serviço com o mesmo nome no mesmo Piso
        $stmtVerificar = execute_query(
            "SELECT idServico FROM Servico WHERE nome = :nome AND idPiso = :idPiso AND idServico != :id",
            ['nome' => $nomeServico, 'idPiso' => $idPiso, 'id' => $idServico],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um serviço (ativo ou inativo) com este nome neste piso.");
        }

        // Fazer o update
        execute_query(
            "UPDATE Servico SET nome = :nome WHERE idServico = :id",
            ['nome' => $nomeServico, 'id' => $idServico],
            $ligacao
        );

        $_SESSION['success_message'] = "Serviço editado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar serviço: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
