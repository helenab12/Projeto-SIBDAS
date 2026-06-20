<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['warranties.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedWarId = trim($_POST['warranty-id'] ?? '');

        if (empty($encryptedEqId) || empty($encryptedWarId)) {
            throw new Exception("IDs não fornecidos.");
        }
        
        $idEquipamento = aes_decrypt($encryptedEqId);
        $idGarantia = aes_decrypt($encryptedWarId);

        if ($idEquipamento === false || $idGarantia === false) {
            throw new Exception("IDs inválidos.");
        }

        $idGarantia = (int) $idGarantia;

        execute_query(
            "UPDATE GarantiaContrato SET ativo = 0, dataAtualizacao = CURRENT_TIMESTAMP WHERE idGarantiaContrato = :idGarantia",
            ['idGarantia' => $idGarantia],
            $ligacao
        );

        $_SESSION['success_message'] = "Registo movido para o arquivo com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId) 
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=garantias" 
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
