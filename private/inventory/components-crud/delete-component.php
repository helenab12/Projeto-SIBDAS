<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['components.delete']);

// Processar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Desencriptar ID
        $encryptedId = $_POST['component-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        // Desativar componente
        execute_query(
            "UPDATE Componente SET ativo = 0 WHERE idComponente = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Componente', $id, 'Remoção', 'ativo', '1', '0');

        // Definir sucesso
        $_SESSION['success_message'] = "Componente eliminado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao eliminar componente: " . $e->getMessage();
    }
}

// Redirecionar utilizador
header("Location: ../components.php");
exit;
