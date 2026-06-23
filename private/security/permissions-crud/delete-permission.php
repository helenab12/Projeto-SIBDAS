<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['permissions.delete']);

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

        // Executar query
        execute_query(
            "UPDATE Permissao SET ativo = 0 WHERE idPermissao = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Permissao', $id, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Permissão eliminada com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
$_SESSION['server_error'] = "Erro ao eliminar permissão: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../permissions.php");
exit;
