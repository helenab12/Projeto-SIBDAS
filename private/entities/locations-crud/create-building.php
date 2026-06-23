<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $nomeEdificio = trim($_POST['building-name'] ?? '');

        // Sanitizar dados
        $nomeEdificio = capitalize_name($nomeEdificio);
        $dadosSanitizados = Edificio::sanitizarDados([
            'nome' => $nomeEdificio
        ]);
        $nomeEdificio = $dadosSanitizados['nome'] ?? $nomeEdificio;

        // Validar dados
        $erros = Edificio::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Ligar à BD
        $ligacao = connect_to_db();

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idEdificio FROM Edificio WHERE nome = :nome",
            ['nome' => $nomeEdificio],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um edifício (ativo ou inativo) com este nome.");
        }

        // Inserir registo
        execute_query(
            "INSERT INTO Edificio (nome, ativo) VALUES (:nome, 1)",
            ['nome' => $nomeEdificio],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        // Registar auditoria
        registar_auditoria($ligacao, 'Edificio', $novoId, 'Criação');

        $_SESSION['success_message'] = "Edifício criado com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar edifício: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
