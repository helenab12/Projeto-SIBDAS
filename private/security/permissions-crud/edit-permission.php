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
        $dadosSanitizados = Permissao::sanitizarDados([
            'idPermissao' => $id,
            'chave' => $chave,
            'descricao' => $descricao
        ]);
        $id = $dadosSanitizados['idPermissao'] ?? $id;
        $chave = $dadosSanitizados['chave'] ?? $chave;
        $descricao = $dadosSanitizados['descricao'] ?? $descricao;
        $erros = Permissao::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Verificar se a chave já existe noutra permissão
        $stmt = execute_query(
            "SELECT idPermissao FROM Permissao WHERE chave = :chave AND idPermissao != :id",
            ['chave' => $chave, 'id' => $id]);
        if ($stmt->fetch()) {
            throw new Exception("A chave da permissão já existe noutro registo.");
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT chave, descricao FROM Permissao WHERE idPermissao = :id",
            ['id' => $id]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        execute_query(
            "UPDATE Permissao SET chave = :chave, descricao = :descricao WHERE idPermissao = :id",
            ['chave' => $chave, 'descricao' => $descricao, 'id' => $id]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Permissao', $id, $antigo, [
            'chave' => $chave,
            'descricao' => $descricao
        ]);

        $_SESSION['success_message'] = "Permissão editada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar permissão: " . $e->getMessage();
    }
}

header("Location: ../permissions.php");
exit;
