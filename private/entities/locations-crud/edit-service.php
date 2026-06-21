<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idServico = (int) aes_decrypt($_POST['service-id']);
        $nomeServico = trim($_POST['service-name'] ?? '');

        // Sanitização usando a classe Servico
        $dadosSanitizados = Servico::sanitizarDados(['nome' => $nomeServico]);
        $nomeServico = $dadosSanitizados['nome'] ?? $nomeServico;

        // Validação
        if (empty($nomeServico)) {
            throw new Exception("O nome do serviço não pode estar vazio.");
        }

        // Buscar o idPiso deste serviço para verificação de duplicados
        $stmtServico = execute_query(
            "SELECT idPiso FROM Servico WHERE idServico = :id",
            ['id' => $idServico]);
        $servicoRow = $stmtServico->fetch(PDO::FETCH_ASSOC);

        if (!$servicoRow) {
            throw new Exception("Serviço não encontrado.");
        }

        $idPiso = $servicoRow['idPiso'];

        // Verificar se já existe outro Serviço com o mesmo nome no mesmo Piso
        $stmtVerificar = execute_query(
            "SELECT idServico FROM Servico WHERE nome = :nome AND idPiso = :idPiso AND idServico != :id",
            ['nome' => $nomeServico, 'idPiso' => $idPiso, 'id' => $idServico]);

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um serviço (ativo ou inativo) com este nome neste piso.");
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT nome FROM Servico WHERE idServico = :id",
            ['id' => $idServico]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Fazer o update
        execute_query(
            "UPDATE Servico SET nome = :nome WHERE idServico = :id",
            ['nome' => $nomeServico, 'id' => $idServico]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Servico', $idServico, $antigo, [
            'nome' => $nomeServico
        ]);

        $_SESSION['success_message'] = "Serviço editado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar serviço: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
