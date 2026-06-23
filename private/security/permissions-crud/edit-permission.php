<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['permissions.edit']);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['permission-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }

        // Desencriptar ID
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        // Recolher dados do POST
        $chave = $_POST['permission-key'] ?? '';
        $descricao = $_POST['permission-description'] ?? '';

        // Sanitizar dados
        $dadosSanitizados = Permissao::sanitizarDados([
            'idPermissao' => $id,
            'chave' => $chave,
            'descricao' => $descricao
        ]);
        $id = $dadosSanitizados['idPermissao'] ?? $id;
        $chave = $dadosSanitizados['chave'] ?? $chave;
        $descricao = $dadosSanitizados['descricao'] ?? $descricao;
        
        // Validar dados
        $erros = Permissao::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Query verificar chave
        $stmt = execute_query(
            "SELECT idPermissao FROM Permissao WHERE chave = :chave AND idPermissao != :id",
            ['chave' => $chave, 'id' => $id]);
        if ($stmt->fetch()) {
            throw new Exception("A chave da permissão já existe noutro registo.");
        }

        // Query ler estado antigo
        $stmtAntigo = execute_query(
            "SELECT chave, descricao FROM Permissao WHERE idPermissao = :id",
            ['id' => $id]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Query atualizar permissão
        execute_query(
            "UPDATE Permissao SET chave = :chave, descricao = :descricao WHERE idPermissao = :id",
            ['chave' => $chave, 'descricao' => $descricao, 'id' => $id]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Permissao', $id, $antigo, [
            'chave' => $chave,
            'descricao' => $descricao
        ]);

        // Definir mensagem sucesso
        $_SESSION['success_message'] = "Permissão editada com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
$_SESSION['server_error'] = "Erro ao editar permissão: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../permissions.php");
exit;
