<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['warranties.delete']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedWarId = trim($_POST['warranty-id'] ?? '');

        // Validar dados
        if (empty($encryptedEqId) || empty($encryptedWarId)) {
            throw new Exception("IDs não fornecidos.");
        }
        
        // Desencriptar IDs
        $idEquipamento = aes_decrypt($encryptedEqId);
        $idGarantia = aes_decrypt($encryptedWarId);

        // Validar desencriptação
        if ($idEquipamento === false || $idGarantia === false) {
            throw new Exception("IDs inválidos.");
        }

        // Converter para número
        $idGarantia = (int) $idGarantia;

        // Atualizar garantia
        execute_query(
            "UPDATE GarantiaContrato SET ativo = 0, dataAtualizacao = CURRENT_TIMESTAMP WHERE idGarantiaContrato = :idGarantia",
            ['idGarantia' => $idGarantia]);

        // Registar auditoria
        registar_auditoria($ligacao, 'GarantiaContrato', $idGarantia, 'Remoção', 'ativo', '1', '0');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Registo movido para o arquivo com sucesso!";

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Construir link
$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId) 
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=garantias" 
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
