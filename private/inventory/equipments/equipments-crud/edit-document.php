<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['documents.edit']);

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

        $nome = trim($_POST['doc-name'] ?? '');
        $tipo = trim($_POST['doc-type'] ?? '');
        $idFornecedor = trim($_POST['doc-supplier'] ?? '');
        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        $erros = Documento::validarDados([
            'nome' => $nome,
            'tipo' => $tipo
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        execute_query(
            "UPDATE Documento SET
                nome = :nome,
                tipo = :tipo,
                idFornecedor = :idForn,
                dataAtualizacao = CURRENT_TIMESTAMP
             WHERE idDocumento = :idDoc AND ativo = 1",
            [
                'nome' => $nome,
                'tipo' => $tipo,
                'idForn' => $idFornecedor,
                'idDoc' => $idDocumento
            ],
            $ligacao
        );

        $_SESSION['success_message'] = "Documento '$nome' editado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId)
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=documentos"
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
