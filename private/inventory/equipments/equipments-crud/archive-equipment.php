<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['equipments.archive']);

// Verificar método
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
        
        // Validar dados
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        // Arquivar equipamento
        execute_query(
            "UPDATE Equipamento SET arquivado = 1 WHERE idEquipamento = :id",
            ['id' => $id]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Equipamento', $id, 'Edição', 'arquivado', '0', '1');

        // Inicializar mensagem sucesso
        $_SESSION['success_message'] = "Equipamento arquivado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao arquivar equipamento: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../equipment_list.php");
exit;
