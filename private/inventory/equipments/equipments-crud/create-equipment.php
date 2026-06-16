<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();
        $ligacao->beginTransaction();

        // 1. Dados do Equipamento
        $codigoInterno = trim($_POST['equipment-code'] ?? '');
        $idCategoria = trim($_POST['equipment-category'] ?? '');
        $numeroSerie = trim($_POST['equipment-serial'] ?? '');
        $nome = trim($_POST['equipment-name'] ?? '');
        $idMarca = trim($_POST['equipment-brand'] ?? '');
        $modelo = trim($_POST['equipment-model'] ?? '');
        // Função auxiliar para conversão de datas (DD/MM/YYYY -> YYYY-MM-DD)
        $formatDate = function($dateStr) {
            if (empty($dateStr)) return null;
            // Se já vier no formato Y-m-d (dependendo do browser), retorna como está
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) return $dateStr;
            
            $d = DateTime::createFromFormat('d/m/Y', $dateStr);
            if ($d && $d->format('d/m/Y') === $dateStr) {
                return $d->format('Y-m-d');
            }
            return null;
        };

        $dataAquisicao = $formatDate(trim($_POST['equipment-purchase-date'] ?? ''));
        $dataFabrico = $formatDate(trim($_POST['equipment-manufacture-date'] ?? ''));
        $custo = isset($_POST['equipment-cost']) && $_POST['equipment-cost'] !== '' ? (float)$_POST['equipment-cost'] : 0.00;
        $tipoEntrada = trim($_POST['equipment-entry-type'] ?? 'Compra');
        $criticidade = trim($_POST['equipment-criticality'] ?? 'Baixa');
        $estadoAtual = trim($_POST['equipment-status'] ?? 'Ativo');
        $idLocalizacao = trim($_POST['equipment-location'] ?? '');
        $observacoes = trim($_POST['equipment-notes'] ?? '');
        
        // Validação básica
        if (empty($codigoInterno) || empty($idCategoria) || empty($numeroSerie) || empty($nome) || empty($idMarca) || empty($idLocalizacao)) {
            throw new Exception("Por favor preencha todos os campos obrigatórios da primeira página.");
        }

        // Verificar se codigoInterno já existe
        $stmt = execute_query("SELECT idEquipamento FROM Equipamento WHERE codigoInterno = :codigo", ['codigo' => $codigoInterno], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("O código interno $codigoInterno já se encontra registado noutro equipamento.");
        }

        // Insert Equipamento
        execute_query(
            "INSERT INTO Equipamento (
                idCategoria, codigoInterno, designacao, idMarca, modelo, numeroSerie,
                dataAquisicao, dataFabrico, custoAquisicao, tipoEntrada, estadoAtual,
                criticidade, idLocalizacao, observacoes, ativo
            ) VALUES (
                :idCategoria, :codigoInterno, :designacao, :idMarca, :modelo, :numeroSerie,
                :dataAquisicao, :dataFabrico, :custoAquisicao, :tipoEntrada, :estadoAtual,
                :criticidade, :idLocalizacao, :observacoes, 1
            )",
            [
                'idCategoria' => $idCategoria,
                'codigoInterno' => $codigoInterno,
                'designacao' => $nome,
                'idMarca' => $idMarca,
                'modelo' => $modelo,
                'numeroSerie' => $numeroSerie,
                'dataAquisicao' => $dataAquisicao,
                'dataFabrico' => $dataFabrico,
                'custoAquisicao' => $custo,
                'tipoEntrada' => $tipoEntrada,
                'estadoAtual' => $estadoAtual,
                'criticidade' => $criticidade,
                'idLocalizacao' => $idLocalizacao,
                'observacoes' => $observacoes
            ],
            $ligacao
        );

        $idEquipamento = $ligacao->lastInsertId();

        // 2. Fornecedores (Fabricante, Distribuidor, Assistentes, Consumíveis)
        $fornecedoresIds = [];
        
        $fabricante = trim($_POST['equipment-manufacturer'] ?? '');
        if (!empty($fabricante)) $fornecedoresIds[] = $fabricante;
        
        $distribuidor = trim($_POST['equipment-distributor'] ?? '');
        if (!empty($distribuidor)) $fornecedoresIds[] = $distribuidor;
        
        $assistentes = $_POST['equipment-tech-assistants'] ?? [];
        foreach ($assistentes as $ast) {
            if (!empty($ast)) $fornecedoresIds[] = $ast;
        }

        $consumiveis = $_POST['equipment-consumable-suppliers'] ?? [];
        foreach ($consumiveis as $con) {
            if (!empty($con)) $fornecedoresIds[] = $con;
        }

        // Remover duplicados
        $fornecedoresIds = array_unique($fornecedoresIds);

        foreach ($fornecedoresIds as $idForn) {
            execute_query(
                "INSERT INTO FornecedorEquipamento (idEquipamento, idFornecedor, ativo) VALUES (:idEq, :idForn, 1)",
                ['idEq' => $idEquipamento, 'idForn' => $idForn],
                $ligacao
            );
        }

        // 3. Componentes
        $componentes = $_POST['equipment-components'] ?? [];
        $componentesQty = $_POST['equipment-components-qty'] ?? [];
        
        foreach ($componentes as $idComp) {
            if (!empty($idComp)) {
                $qty = isset($componentesQty[$idComp]) ? (int)$componentesQty[$idComp] : 1;
                if ($qty > 0) {
                    execute_query(
                        "INSERT INTO ComponenteEquipamento (idComponente, idEquipamento, quantidade) VALUES (:idComp, :idEq, :qty)",
                        ['idComp' => $idComp, 'idEq' => $idEquipamento, 'qty' => $qty],
                        $ligacao
                    );
                }
            }
        }

        // 4. Manutenção
        $maintStart = $formatDate(trim($_POST['last-maintenance-start-date'] ?? ''));
        $maintEnd = $formatDate(trim($_POST['last-maintenance-end-date'] ?? ''));
        
        if (!empty($maintStart) && !empty($maintEnd)) {
            execute_query(
                "INSERT INTO Manutencao (idEquipamento, tipoManutencao, dataInicio, dataFim, observacoes, ativo)
                 VALUES (:idEq, 'Preventiva', :dInicio, :dFim, 'Manutenção inicial inserida na criação do equipamento', 1)",
                [
                    'idEq' => $idEquipamento,
                    'dInicio' => $maintStart,
                    'dFim' => $maintEnd
                ],
                $ligacao
            );
        }

        // 5. Documentos
        if (isset($_FILES['doc-files']) && is_array($_FILES['doc-files']['name'])) {
            $docTypes = $_POST['doc-type'] ?? [];
            $docSuppliers = $_POST['doc-supplier'] ?? [];
            
            $fileCount = count($_FILES['doc-files']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['doc-files']['error'][$i] === UPLOAD_ERR_OK) {
                    $tipo = trim($docTypes[$i] ?? '');
                    $idFornecedor = trim($docSuppliers[$i] ?? '');
                    if (empty($idFornecedor)) $idFornecedor = null;
                    
                    $nomeDoc = $_FILES['doc-files']['name'][$i];
                    
                    $erros = Documento::validarDados([
                        'nome' => $nomeDoc,
                        'tipo' => $tipo
                    ]);

                    if (!empty($erros)) {
                        throw new Exception("Erro no documento '$nomeDoc': " . implode(", ", $erros));
                    }
                    
                    // Validação de Tamanho (25MB)
                    $maxSize = 25 * 1024 * 1024;
                    if ($_FILES['doc-files']['size'][$i] > $maxSize) {
                        throw new Exception("O documento '$nomeDoc' excede o tamanho máximo permitido de 25MB.");
                    }

                    // Validação de Formato
                    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                    $fileInfo = pathinfo($nomeDoc);
                    $extension = strtolower($fileInfo['extension'] ?? '');
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new Exception("O documento '$nomeDoc' tem um formato inválido. Apenas PDF, JPG, JPEG e PNG.");
                    }

                    $uploadDir = BASE_PATH . 'files/documents/';
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            throw new Exception("Erro ao criar diretório para documentos.");
                        }
                    }

                    $newFileName = uniqid('doc_') . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $fileInfo['basename']);
                    $destinationPath = $uploadDir . $newFileName;
                    $dbPath = 'files/documents/' . $newFileName;

                    if (!move_uploaded_file($_FILES['doc-files']['tmp_name'][$i], $destinationPath)) {
                        throw new Exception("Erro ao guardar o ficheiro '$nomeDoc' no servidor.");
                    }

                    execute_query(
                        "INSERT INTO Documento (tipo, nome, caminhoFicheiro, dataDocumento, idEquipamento, idFornecedor, ativo)
                         VALUES (:tipo, :nome, :caminho, CURDATE(), :idEq, :idForn, 1)",
                        [
                            'tipo' => $tipo,
                            'nome' => $nomeDoc,
                            'caminho' => $dbPath,
                            'idEq' => $idEquipamento,
                            'idForn' => $idFornecedor
                        ],
                        $ligacao
                    );
                }
            }
        }

        $ligacao->commit();
        $_SESSION['success_message'] = "Equipamento '$nome' criado com sucesso!";

    } catch (Exception $e) {
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao criar equipamento: " . $e->getMessage();
    }
}

header("Location: ../equipment_list.php");
exit;
