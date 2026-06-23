<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['documents.edit']);

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

        // Recolher formulário
        $nome = trim($_POST['doc-name'] ?? '');
        $tipo = trim($_POST['doc-type'] ?? '');
        $idFornecedor = trim($_POST['doc-supplier'] ?? '');
        
        // Tratar ID do Fornecedor
        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        // Sanitizar dados
        $dadosSanitizados = Documento::sanitizarDados([
            'nome' => $nome,
            'tipo' => $tipo
        ]);
        $nome = $dadosSanitizados['nome'] ?? $nome;
        $tipo = $dadosSanitizados['tipo'] ?? $tipo;
        
        // Validar dados sanitizados
        $erros = Documento::validarDados($dadosSanitizados);

        // Lançar erro
        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Obter estado antigo
        $stmtAntigo = execute_query(
            "SELECT nome, tipo, idFornecedor FROM Documento WHERE idDocumento = :idDoc AND ativo = 1",
            ['idDoc' => $idDocumento]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar documento
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

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Documento '$nome' editado com sucesso!";

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
