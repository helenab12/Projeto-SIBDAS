<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['documents.delete']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedDocId = trim($_POST['document-id'] ?? '');

        // Validar dados
        if (empty($encryptedEqId) || empty($encryptedDocId)) {
            throw new Exception("IDs não fornecidos.");
        }
        
        // Desencriptar IDs
        $idEquipamento = aes_decrypt($encryptedEqId);
        $idDocumento = aes_decrypt($encryptedDocId);

        // Validar desencriptação
        if ($idEquipamento === false || $idDocumento === false) {
            throw new Exception("IDs inválidos.");
        }

        // Converter para número
        $idDocumento = (int) $idDocumento;

        // Atualizar documento
        execute_query(
            "UPDATE Documento SET ativo = 0, dataAtualizacao = CURRENT_TIMESTAMP WHERE idDocumento = :idDoc",
            ['idDoc' => $idDocumento]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Documento', $idDocumento, 'Remoção', 'ativo', '1', '0');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Documento movido para o arquivo com sucesso!";

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Construir link
$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId) 
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=documentos" 
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
