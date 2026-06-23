<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['equipments.unarchive']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['equipment-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        
        // Desencriptar ID
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        // Desarquivar equipamento
        execute_query(
            "UPDATE Equipamento SET arquivado = 0, ativo = 1 WHERE idEquipamento = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Equipamento', $id, 'Edição', 'arquivado', '1', '0');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Equipamento desarquivado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao desarquivar equipamento: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../equipment_archive.php");
exit;
