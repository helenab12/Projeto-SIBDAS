<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['documents.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

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

        $dadosSanitizados = Documento::sanitizarDados([
            'nome' => $nome,
            'tipo' => $tipo
        ]);
        $nome = $dadosSanitizados['nome'] ?? $nome;
        $tipo = $dadosSanitizados['tipo'] ?? $tipo;
        $erros = Documento::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT nome, tipo, idFornecedor FROM Documento WHERE idDocumento = :idDoc AND ativo = 1",
            ['idDoc' => $idDocumento]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

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
            ]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Documento', $idDocumento, $antigo, [
            'nome' => $nome,
            'tipo' => $tipo,
            'idFornecedor' => $idFornecedor
        ]);

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
