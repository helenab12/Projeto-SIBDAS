<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['documents.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedDocId = trim($_POST['document-id'] ?? '');

        if (empty($encryptedEqId) || empty($encryptedDocId)) {
            throw new Exception("IDs não fornecidos.");
        }
        
        $idEquipamento = aes_decrypt($encryptedEqId);
        $idDocumento = aes_decrypt($encryptedDocId);

        if ($idEquipamento === false || $idDocumento === false) {
            throw new Exception("IDs inválidos.");
        }

        $idDocumento = (int) $idDocumento;

        execute_query(
            "UPDATE Documento SET ativo = 0, dataAtualizacao = CURRENT_TIMESTAMP WHERE idDocumento = :idDoc",
            ['idDoc' => $idDocumento],
            $ligacao
        );

        $_SESSION['success_message'] = "Documento movido para o arquivo com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId) 
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=documentos" 
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
