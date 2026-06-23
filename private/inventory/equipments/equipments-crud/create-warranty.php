<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['warranties.create']);

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
        $tipoRegisto = trim($_POST['warranty-type'] ?? '');
        $dataInicioRaw = trim($_POST['warranty-start-date'] ?? '');
        $dataFimRaw = trim($_POST['warranty-end-date'] ?? '');
        $periodicidade = trim($_POST['warranty-periodicity'] ?? '');
        $idFornecedor = trim($_POST['warranty-supplier'] ?? '');
        $observacoes = trim($_POST['warranty-notes'] ?? '');

        // Converter datas
        $dInicio = DateTime::createFromFormat('d/m/Y', $dataInicioRaw);
        $dataInicio = $dInicio ? $dInicio->format('Y-m-d') : '';

        $dFim = DateTime::createFromFormat('d/m/Y', $dataFimRaw);
        $dataFim = $dFim ? $dFim->format('Y-m-d') : '';

        // Tratar ID de fornecedor
        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        $idDocumento = null;
        
        // Tratar ficheiro
        if (isset($_FILES['warranty-file']) && $_FILES['warranty-file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['warranty-file'];
            $maxSize = 25 * 1024 * 1024;
            
            if ($file['size'] > $maxSize) {
                throw new Exception("O ficheiro excede o tamanho máximo permitido de 25MB.");
            }
            
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                throw new Exception("Apenas ficheiros PDF, JPG, JPEG e PNG são permitidos.");
            }
            
            // Definir diretório
            $uploadDir = BASE_PATH . 'files/documents/';
            
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception("Erro ao criar diretório para guardar o ficheiro.");
                }
            }
            
            // Gerar nome único
            $newFileName = uniqid('gar_') . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', basename($file['name']));
            $dest = $uploadDir . $newFileName;
            
            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                throw new Exception("Erro ao guardar o ficheiro no servidor.");
            }
            
            // Mapear tipo de documento
            $dbPath = 'files/documents/' . $newFileName;
            $tipoDoc = ($tipoRegisto === 'Garantia de Fábrica') ? 'Garantia' : 'Contrato de Manutenção';
            
            // Inserir documento
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
            $idDocumento = $ligacao->lastInsertId();
        }

        // Sanitizar dados
        $dadosSanitizados = GarantiaContrato::sanitizarDados([
            'idGarantiaContrato' => '-1',
            'tipoRegisto' => $tipoRegisto,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'periodicidade' => $periodicidade,
        ]);
        
        $tipoRegisto = $dadosSanitizados['tipoRegisto'] ?? $tipoRegisto;
        $dataInicio = $dadosSanitizados['dataInicio'] ?? $dataInicio;
        $dataFim = $dadosSanitizados['dataFim'] ?? $dataFim;
        $periodicidade = $dadosSanitizados['periodicidade'] ?? $periodicidade;
        
        // Validar dados sanitizados
        $erros = GarantiaContrato::validarDados($dadosSanitizados);

        // Lançar erro
        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Inserir garantia ou contrato
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

        // Registar auditoria
        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'GarantiaContrato', $novoId, 'Criação');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Registo adicionado com sucesso!";

        // Desligar da BD
        $ligacao = null;

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Construir link
$redirectUrl = isset($encryptedId) && !empty($encryptedId)
    ? "../detailed_view.php?id=" . urlencode($encryptedId) . "&nav=garantias"
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
