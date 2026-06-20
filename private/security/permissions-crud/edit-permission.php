<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['permissions.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['permission-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        $chave = $_POST['permission-key'] ?? '';
        $descricao = $_POST['permission-description'] ?? '';

        // Validação básica
        $erros = Permissao::validarDados([
            'idPermissao' => $id,
            'chave' => $chave,
            'descricao' => $descricao
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se a chave já existe noutra permissão
        $stmt = execute_query(
            "SELECT idPermissao FROM Permissao WHERE chave = :chave AND idPermissao != :id",
            ['chave' => $chave, 'id' => $id],
            $ligacao
        );
        if ($stmt->fetch()) {
            throw new Exception("A chave da permissão já existe noutro registo.");
        }

        execute_query(
            "UPDATE Permissao SET chave = :chave, descricao = :descricao WHERE idPermissao = :id",
            ['chave' => $chave, 'descricao' => $descricao, 'id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Permissão editada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar permissão: " . $e->getMessage();
    }
}

header("Location: ../permissions.php");
exit;
