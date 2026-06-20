<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nomeEdificio = trim($_POST['building-name'] ?? '');

        // Sanitização
        $nomeEdificio = capitalize_name($nomeEdificio);

        // Validação usando a classe
        $erros = Edificio::validarDados([
            'nome' => $nomeEdificio
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se já existe um edifício com o mesmo nome
        $stmtVerificar = execute_query(
            "SELECT idEdificio FROM Edificio WHERE nome = :nome",
            ['nome' => $nomeEdificio],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um edifício (ativo ou inativo) com este nome.");
        }

        // Inserir o novo edifício
        execute_query(
            "INSERT INTO Edificio (nome, ativo) VALUES (:nome, 1)",
            ['nome' => $nomeEdificio],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Edificio', $novoId, 'Criação');

        $_SESSION['success_message'] = "Edifício criado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar edifício: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
