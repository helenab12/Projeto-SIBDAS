<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['documents.create']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ligar à BD
        $ligacao = connect_to_db();

        // Recolher dados do POST
        $encryptedId = trim($_POST['equipment-id'] ?? '');
        
        // Validar ID
        if (empty($encryptedId)) {
            throw new Exception("ID de equipamento não fornecido.");
        }

        // Desencriptar ID
        $idEquipamento = aes_decrypt($encryptedId);
        
        // Validar desencriptação
        if ($idEquipamento === false) {
            throw new Exception("ID de equipamento inválido.");
        }
        
        // Converter para número
        $idEquipamento = (int) $idEquipamento;

        // Recolher dados do POST
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

        // Validar upload
        if (!isset($_FILES['doc-file']) || $_FILES['doc-file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro ao fazer upload do ficheiro.");
        }

        // Tratar ficheiro
        $file = $_FILES['doc-file'];

        // Validar tamanho
        $maxSize = 25 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception("O ficheiro excede o tamanho máximo permitido de 25MB.");
        }

        // Validar formato
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension'] ?? '');
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Apenas são permitidos ficheiros PDF, JPG, JPEG e PNG.");
        }

        // Definir diretório
        $uploadDir = BASE_PATH . 'files/documents/';
        
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Erro ao criar diretório para guardar o ficheiro.");
            }
        }

        // Gerar nome único
        $newFileName = uniqid('doc_') . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $fileInfo['basename']);
        $destinationPath = $uploadDir . $newFileName;
        $dbPath = 'files/documents/' . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
            throw new Exception("Erro ao guardar o ficheiro no servidor.");
        }

        // Inserir documento
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

        // Registar auditoria
        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Documento', $novoId, 'Criação');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Documento '$nome' adicionado com sucesso!";

        // Desligar da BD
        $ligacao = null;

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Construir link
$redirectUrl = isset($encryptedId) && !empty($encryptedId)
    ? "../detailed_view.php?id=" . urlencode($encryptedId) . "&nav=documentos"
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
