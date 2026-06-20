<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['documents.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        $encryptedId = trim($_POST['equipment-id'] ?? '');
        if (empty($encryptedId)) {
            throw new Exception("ID de equipamento não fornecido.");
        }

        $idEquipamento = aes_decrypt($encryptedId);
        if ($idEquipamento === false) {
            throw new Exception("ID de equipamento inválido.");
        }
        $idEquipamento = (int) $idEquipamento;

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

        // Lógica de Upload do Ficheiro
        if (!isset($_FILES['doc-file']) || $_FILES['doc-file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro ao fazer upload do ficheiro.");
        }

        $file = $_FILES['doc-file'];

        // Validação de Tamanho (25MB)
        $maxSize = 25 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception("O ficheiro excede o tamanho máximo permitido de 25MB.");
        }

        // Validação de Formato
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension'] ?? '');
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Apenas são permitidos ficheiros PDF, JPG, JPEG e PNG.");
        }

        // Criar diretório caso não exista
        $uploadDir = BASE_PATH . 'files/documents/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Erro ao criar diretório para guardar o ficheiro.");
            }
        }

        // Gerar nome único
        $newFileName = uniqid('doc_') . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $fileInfo['basename']);
        $destinationPath = $uploadDir . $newFileName;
        $dbPath = 'files/documents/' . $newFileName; // Caminho relativo para a DB e URLs

        if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
            throw new Exception("Erro ao guardar o ficheiro no servidor.");
        }

        execute_query(
            "INSERT INTO Documento (tipo, nome, caminhoFicheiro, dataDocumento, idEquipamento, idFornecedor, ativo)
             VALUES (:tipo, :nome, :caminho, CURDATE(), :idEq, :idForn, 1)",
            [
                'tipo' => $tipo,
                'nome' => $nome,
                'caminho' => $dbPath,
                'idEq' => $idEquipamento,
                'idForn' => $idFornecedor
            ],
            $ligacao
        );

        $_SESSION['success_message'] = "Documento '$nome' adicionado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Em caso de sucesso ou erro, redirecionar para a vista detalhada e abrir a tab de documentos
$redirectUrl = isset($encryptedId) && !empty($encryptedId)
    ? "../detailed_view.php?id=" . urlencode($encryptedId) . "&nav=documentos"
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
