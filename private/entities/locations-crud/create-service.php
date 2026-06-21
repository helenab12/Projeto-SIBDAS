<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idPiso = (int) aes_decrypt($_POST['floor-id']);
        $nomeServico = trim($_POST['service-name'] ?? '');

        // Sanitização usando a classe Servico
        $dadosSanitizados = Servico::sanitizarDados(['nome' => $nomeServico]);
        $nomeServico = $dadosSanitizados['nome'] ?? $nomeServico;

        // Validação
        if (empty($nomeServico)) {
            throw new Exception("O nome do serviço não pode estar vazio.");
        }

        $ligacao = connect_to_db();

        // Verificar se já existe um Serviço com o mesmo nome no mesmo Piso
        $stmtVerificar = execute_query(
            "SELECT idServico FROM Servico WHERE nome = :nome AND idPiso = :idPiso",
            ['nome' => $nomeServico, 'idPiso' => $idPiso],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um serviço (ativo ou inativo) com este nome neste piso.");
        }

        // Inserir o novo serviço
        execute_query(
            "INSERT INTO Servico (idPiso, nome, ativo) VALUES (:idPiso, :nome, 1)",
            ['idPiso' => $idPiso, 'nome' => $nomeServico],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Servico', $novoId, 'Criação');

        $_SESSION['success_message'] = "Serviço criado com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar serviço: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
