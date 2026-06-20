<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['permissions.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $chave = strtolower(trim($_POST['permission-key'] ?? ''));
        $descricao = ucfirst(trim($_POST['permission-description'] ?? ''));

        // Validação básica
        $erros = Permissao::validarDados([
            'idPermissao' => 0, // ID fictício para validação de criação
            'chave' => $chave,
            'descricao' => $descricao
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se a chave já existe
        $stmt = execute_query("SELECT idPermissao FROM Permissao WHERE chave = :chave", ['chave' => $chave], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("A chave da permissão já existe.");
        }

        execute_query(
            "INSERT INTO Permissao (chave, descricao) VALUES (:chave, :descricao)",
            ['chave' => $chave, 'descricao' => $descricao],
            $ligacao
        );

        $_SESSION['success_message'] = "Permissão criada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar permissão: " . $e->getMessage();
    }
}

header("Location: ../permissions.php");
exit;
