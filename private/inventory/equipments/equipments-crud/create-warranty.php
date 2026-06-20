<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['warranties.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        // 1. Desencriptar e validar o ID do Equipamento
        $encryptedId = trim($_POST['equipment-id'] ?? '');
        if (empty($encryptedId)) {
            throw new Exception("ID de equipamento não fornecido.");
        }

        $idEquipamento = aes_decrypt($encryptedId);
        if ($idEquipamento === false) {
            throw new Exception("ID de equipamento inválido.");
        }
        $idEquipamento = (int) $idEquipamento;

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

        // 3. Validação e Upload de Ficheiro (se existir)
        $idDocumento = null;
        if (isset($_FILES['warranty-file']) && $_FILES['warranty-file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['warranty-file'];
            $maxSize = 25 * 1024 * 1024; // 25MB
            if ($file['size'] > $maxSize) {
                throw new Exception("O ficheiro excede o tamanho máximo permitido de 25MB.");
            }
            
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                throw new Exception("Apenas ficheiros PDF, JPG, JPEG e PNG são permitidos.");
            }
            
            // Upload dir
            $uploadDir = BASE_PATH . 'files/documents/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception("Erro ao criar diretório para guardar o ficheiro.");
                }
            }
            
            $newFileName = uniqid('gar_') . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', basename($file['name']));
            $dest = $uploadDir . $newFileName;
            
            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                throw new Exception("Erro ao guardar o ficheiro no servidor.");
            }
            
            // Inserir na BD (tabela Documento)
            $dbPath = 'files/documents/' . $newFileName;
            $tipoDoc = ($tipoRegisto === 'Garantia de Fábrica') ? 'Garantia' : 'Contrato de Manutenção';
            
            execute_query(
                "INSERT INTO Documento (tipo, nome, caminhoFicheiro, dataDocumento, idEquipamento, idFornecedor, ativo)
                 VALUES (:tipo, :nome, :caminho, :dataDoc, :idEq, :idForn, 1)",
                [
                    'tipo' => $tipoDoc,
                    'nome' => "Documento de " . $tipoRegisto,
                    'caminho' => $dbPath,
                    'dataDoc' => $dataInicio, // Usar a data de início da garantia como data do documento
                    'idEq' => $idEquipamento,
                    'idForn' => $idFornecedor
                ],
                $ligacao
            );
            $idDocumento = $ligacao->lastInsertId();
        }

        // 4. Validar dados com a classe GarantiaContrato
        $erros = GarantiaContrato::validarDados([
            'idGarantiaContrato' => '-1',  // ID fictício para validação
            'tipoRegisto' => $tipoRegisto,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'periodicidade' => $periodicidade,
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // 5. Inserir na base de dados
        execute_query(
            "INSERT INTO GarantiaContrato (idEquipamento, idFornecedor, idDocumento, tipoRegisto, dataInicio, dataFim, periodicidade, observacoes, ativo)
             VALUES (:idEq, :idForn, :idDoc, :tipo, :dataInicio, :dataFim, :periodicidade, :obs, 1)",
            [
                'idEq' => $idEquipamento,
                'idForn' => $idFornecedor,
                'idDoc' => $idDocumento,
                'tipo' => $tipoRegisto,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim,
                'periodicidade' => $periodicidade,
                'obs' => !empty($observacoes) ? $observacoes : null
            ],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'GarantiaContrato', $novoId, 'Criação');

        $_SESSION['success_message'] = "Registo adicionado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Redirecionar para a vista detalhada, tab de garantias
$redirectUrl = isset($encryptedId) && !empty($encryptedId)
    ? "../detailed_view.php?id=" . urlencode($encryptedId) . "&nav=garantias"
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
