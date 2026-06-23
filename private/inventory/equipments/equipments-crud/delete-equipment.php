<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['equipments.delete']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['equipment-id'] ?? null;
        
        // Validar dados
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        
        // Desencriptar ID
        $id = aes_decrypt($encryptedId);
        
        // Validar desencriptação
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        
        // Converter para número
        $id = (int) $id;

        // Atualizar equipamento
        execute_query(
            "UPDATE Equipamento SET ativo = 0 WHERE idEquipamento = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Equipamento', $id, 'Remoção', 'ativo', '1', '0');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Equipamento eliminado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao eliminar equipamento: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../equipment_list.php");
exit;
