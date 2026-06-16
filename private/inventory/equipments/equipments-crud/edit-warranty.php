<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        // 1. Validar e Desencriptar os IDs
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

        // 2. Recolher dados do formulário
        $tipoRegisto = trim($_POST['warranty-type'] ?? '');
        $dataInicioRaw = trim($_POST['warranty-start-date'] ?? '');
        $dataFimRaw = trim($_POST['warranty-end-date'] ?? '');
        $periodicidade = trim($_POST['warranty-periodicity'] ?? '');
        $idFornecedor = trim($_POST['warranty-supplier'] ?? '');
        $observacoes = trim($_POST['warranty-notes'] ?? '');

        // Converter datas de d/m/Y para Y-m-d
        $dInicio = DateTime::createFromFormat('d/m/Y', $dataInicioRaw);
        $dataInicio = $dInicio ? $dInicio->format('Y-m-d') : '';

        $dFim = DateTime::createFromFormat('d/m/Y', $dataFimRaw);
        $dataFim = $dFim ? $dFim->format('Y-m-d') : '';

        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        // Validar dados com a classe GarantiaContrato
        $erros = GarantiaContrato::validarDados([
            'idGarantiaContrato' => $idGarantia,
            'tipoRegisto' => $tipoRegisto,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'periodicidade' => $periodicidade,
        ]);

        if (!empty($erros)) {
            throw new Exception(implode("<br>", $erros));
        }

        // Buscar dados atuais da Garantia para saber se já tem documento
        $stmtG = $ligacao->prepare("SELECT idDocumento FROM GarantiaContrato WHERE idGarantiaContrato = :id");
        $stmtG->execute(['id' => $idGarantia]);
        $rowG = $stmtG->fetch(PDO::FETCH_ASSOC);
        $idDocAntigo = $rowG ? $rowG['idDocumento'] : null;

        // 3. Tratamento de Ficheiro (se houver novo)
        $idDocumentoNovo = $idDocAntigo;

        if (isset($_FILES['edit-warranty-file']) && $_FILES['edit-warranty-file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['edit-warranty-file'];
            $maxFileSize = 25 * 1024 * 1024; // 25MB
            $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $file['tmp_name']);
            finfo_close($fileInfo);

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($mimeType, $allowedMimeTypes) || !in_array($ext, $allowedExtensions)) {
                throw new Exception("Formato de ficheiro inválido. Apenas PDF, JPG e PNG são permitidos.");
            }

            if ($file['size'] > $maxFileSize) {
                throw new Exception("O ficheiro excede o tamanho máximo de 25MB.");
            }

            // Gerar nome único
            $newFileName = uniqid('garantia_') . '_' . time() . '.' . $ext;
            
            // Usamos dirname de forma a chegar à raiz e entrar em files/documents
            $uploadDir = __DIR__ . "/../../../../files/documents/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $uploadPath = $uploadDir . $newFileName;
            $dbPath = "files/documents/" . $newFileName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception("Falha ao mover o ficheiro para o diretório de destino.");
            }

            // Mapeamento do tipo de documento
            $tipoDoc = "Outros";
            if ($tipoRegisto === "Garantia") {
                $tipoDoc = "Garantia";
            } elseif ($tipoRegisto === "Contrato") {
                $tipoDoc = "Contrato de Manutenção";
            }

            // Se existia um documento anterior, apaga fisicamente
            if ($idDocAntigo) {
                $stmtDocAntigo = $ligacao->prepare("SELECT caminhoFicheiro FROM Documento WHERE idDocumento = :id");
                $stmtDocAntigo->execute(['id' => $idDocAntigo]);
                $docAntigo = $stmtDocAntigo->fetch(PDO::FETCH_ASSOC);

                if ($docAntigo && !empty($docAntigo['caminhoFicheiro'])) {
                    $oldFilePath = __DIR__ . "/../../../../" . $docAntigo['caminhoFicheiro'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Atualizar o registo do documento
                execute_query(
                    "UPDATE Documento 
                     SET tipo = :tipo, nome = :nome, caminhoFicheiro = :caminho, dataDocumento = :dataDoc, ativo = 1, dataAtualizacao = CURRENT_TIMESTAMP
                     WHERE idDocumento = :idDocAntigo",
                    [
                        'tipo' => $tipoDoc,
                        'nome' => "Documento de " . $tipoRegisto,
                        'caminho' => $dbPath,
                        'dataDoc' => $dataInicio,
                        'idDocAntigo' => $idDocAntigo
                    ],
                    $ligacao
                );
            } else {
                // Inserir novo documento
                execute_query(
                    "INSERT INTO Documento (tipo, nome, caminhoFicheiro, dataDocumento, idEquipamento, idFornecedor, ativo)
                     VALUES (:tipo, :nome, :caminho, :dataDoc, :idEq, :idForn, 1)",
                    [
                        'tipo' => $tipoDoc,
                        'nome' => "Documento de " . $tipoRegisto,
                        'caminho' => $dbPath,
                        'dataDoc' => $dataInicio,
                        'idEq' => $idEquipamento,
                        'idForn' => $idFornecedor
                    ],
                    $ligacao
                );
                $idDocumentoNovo = $ligacao->lastInsertId();
            }
        }

        // 4. Inserir na tabela GarantiaContrato
        execute_query(
            "UPDATE GarantiaContrato
             SET tipoRegisto = :tipo, dataInicio = :dInicio, dataFim = :dFim, periodicidade = :periodicidade, observacoes = :obs, idFornecedor = :idForn, idDocumento = :idDoc, dataAtualizacao = CURRENT_TIMESTAMP
             WHERE idGarantiaContrato = :idGarantia",
            [
                'tipo' => $tipoRegisto,
                'dInicio' => $dataInicio,
                'dFim' => $dataFim ?: null,
                'periodicidade' => $periodicidade,
                'obs' => empty($observacoes) ? null : $observacoes,
                'idForn' => $idFornecedor,
                'idDoc' => $idDocumentoNovo,
                'idGarantia' => $idGarantia
            ],
            $ligacao
        );

        $_SESSION['success_message'] = "Registo atualizado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId) 
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=garantias" 
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
