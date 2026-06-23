<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idPiso = (int) aes_decrypt($_POST['floor-id']);
        $nomeServico = trim($_POST['service-name'] ?? '');

        // Sanitizar dados
        $dadosSanitizados = Servico::sanitizarDados(['nome' => $nomeServico]);
        $nomeServico = $dadosSanitizados['nome'] ?? $nomeServico;

        // Validar dados
        if (empty($nomeServico)) {
            throw new Exception("O nome do serviço não pode estar vazio.");
        }

        // Ligar à BD
        $ligacao = connect_to_db();

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idServico FROM Servico WHERE nome = :nome AND idPiso = :idPiso",
            ['nome' => $nomeServico, 'idPiso' => $idPiso],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um serviço (ativo ou inativo) com este nome neste piso.");
        }

        // Inserir registo
        execute_query(
            "INSERT INTO Servico (idPiso, nome, ativo) VALUES (:idPiso, :nome, 1)",
            ['idPiso' => $idPiso, 'nome' => $nomeServico],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        // Registar auditoria
        registar_auditoria($ligacao, 'Servico', $novoId, 'Criação');

        $_SESSION['success_message'] = "Serviço criado com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar serviço: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
