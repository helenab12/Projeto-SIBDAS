<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['equipments.create']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ligar à BD
        $ligacao = connect_to_db();
        // Iniciar transação
        $ligacao->beginTransaction();

        // Recolher dados do POST
        $codigoInterno = trim($_POST['equipment-code'] ?? '');
        $idCategoria = trim($_POST['equipment-category'] ?? '');
        $numeroSerie = trim($_POST['equipment-serial'] ?? '');
        $nome = trim($_POST['equipment-name'] ?? '');
        $idMarca = trim($_POST['equipment-brand'] ?? '');
        $modelo = trim($_POST['equipment-model'] ?? '');
        // Criar função auxiliar
        $formatDate = function($dateStr) {
            if (empty($dateStr)) return null;
            
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
        
        // Sanitizar dados
        $dadosSanitizados = Equipamento::sanitizarDados([
            'codigoInterno' => $codigoInterno,
            'idCategoria' => $idCategoria,
            'numeroSerie' => $numeroSerie,
            'designacao' => $nome,
            'idMarca' => $idMarca,
            'modelo' => $modelo,
            'dataAquisicao' => $dataAquisicao,
            'dataFabrico' => $dataFabrico,
            'custoAquisicao' => $custo,
            'tipoEntrada' => $tipoEntrada,
            'estadoAtual' => $estadoAtual,
            'criticidade' => $criticidade,
            'idLocalizacao' => $idLocalizacao,
            'observacoes' => $observacoes
        ]);

        $codigoInterno = $dadosSanitizados['codigoInterno'] ?? $codigoInterno;
        $idCategoria = $dadosSanitizados['idCategoria'] ?? $idCategoria;
        $numeroSerie = $dadosSanitizados['numeroSerie'] ?? $numeroSerie;
        $nome = $dadosSanitizados['designacao'] ?? $nome;
        $idMarca = $dadosSanitizados['idMarca'] ?? $idMarca;
        $modelo = $dadosSanitizados['modelo'] ?? $modelo;
        $dataAquisicao = $dadosSanitizados['dataAquisicao'] ?? $dataAquisicao;
        $dataFabrico = $dadosSanitizados['dataFabrico'] ?? $dataFabrico;
        $custo = $dadosSanitizados['custoAquisicao'] ?? $custo;
        $tipoEntrada = $dadosSanitizados['tipoEntrada'] ?? $tipoEntrada;
        $estadoAtual = $dadosSanitizados['estadoAtual'] ?? $estadoAtual;
        $criticidade = $dadosSanitizados['criticidade'] ?? $criticidade;
        $idLocalizacao = $dadosSanitizados['idLocalizacao'] ?? $idLocalizacao;
        $observacoes = $dadosSanitizados['observacoes'] ?? $observacoes;

        // Validar campos obrigatórios
        if (empty($codigoInterno) || empty($idCategoria) || empty($numeroSerie) || empty($nome) || empty($idMarca) || empty($idLocalizacao)) {
            throw new Exception("Por favor preencha todos os campos obrigatórios da primeira página.");
        }

        // Verificar duplicados
        $stmt = execute_query("SELECT idEquipamento FROM Equipamento WHERE codigoInterno = :codigo", ['codigo' => $codigoInterno], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("O código interno $codigoInterno já se encontra registado noutro equipamento.");
        }

        // Inserir equipamento
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
        // Registar auditoria
        registar_auditoria($ligacao, 'Equipamento', $idEquipamento, 'Criação');

        // Recolher fornecedores
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

        // Remover fornecedores duplicados
        $fornecedoresIds = array_unique($fornecedoresIds);

        // Inserir fornecedores
        foreach ($fornecedoresIds as $idForn) {
            execute_query(
                "INSERT INTO FornecedorEquipamento (idEquipamento, idFornecedor, ativo) VALUES (:idEq, :idForn, 1)",
                ['idEq' => $idEquipamento, 'idForn' => $idForn],
                $ligacao
            );
        }

        // Recolher componentes
        $componentes = $_POST['equipment-components'] ?? [];
        $componentesQty = $_POST['equipment-components-qty'] ?? [];
        
        // Inserir componentes
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

        // Recolher manutenção inicial
        $maintStart = $formatDate(trim($_POST['last-maintenance-start-date'] ?? ''));
        $maintEnd = $formatDate(trim($_POST['last-maintenance-end-date'] ?? ''));
        
        // Inserir manutenção
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

        // Tratar ficheiros
        if (isset($_FILES['doc-files']) && is_array($_FILES['doc-files']['name'])) {
            // Recolher dados do POST
            $docTypes = $_POST['doc-type'] ?? [];
            $docSuppliers = $_POST['doc-supplier'] ?? [];
            
            // Iterar ficheiros
            $fileCount = count($_FILES['doc-files']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['doc-files']['error'][$i] === UPLOAD_ERR_OK) {
                    $tipo = trim($docTypes[$i] ?? '');
                    $idFornecedor = trim($docSuppliers[$i] ?? '');
                    if (empty($idFornecedor)) $idFornecedor = null;
                    
                    $nomeDoc = $_FILES['doc-files']['name'][$i];
                    
                    $dadosSanitizados = Documento::sanitizarDados([
                        'nome' => $nomeDoc,
                        'tipo' => $tipo
                    ]);
                    
                    $nomeDoc = $dadosSanitizados['nome'] ?? $nomeDoc;
                    $tipo = $dadosSanitizados['tipo'] ?? $tipo;
                    
                    // Validar dados sanitizados
                    $erros = Documento::validarDados($dadosSanitizados);

                    if (!empty($erros)) {
                        throw new Exception("Erro no documento '$nomeDoc': " . implode(", ", $erros));
                    }
                    
                    // Validar tamanho do ficheiro
                    $maxSize = 25 * 1024 * 1024;
                    if ($_FILES['doc-files']['size'][$i] > $maxSize) {
                        throw new Exception("O documento '$nomeDoc' excede o tamanho máximo permitido de 25MB.");
                    }

                    // Validar formato do ficheiro
                    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                    $fileInfo = pathinfo($nomeDoc);
                    $extension = strtolower($fileInfo['extension'] ?? '');
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new Exception("O documento '$nomeDoc' tem um formato inválido. Apenas PDF, JPG, JPEG e PNG.");
                    }

                    // Definir diretório
                    $uploadDir = BASE_PATH . 'files/documents/';
                    
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            throw new Exception("Erro ao criar diretório para documentos.");
                        }
                    }

                    // Gerar nome único
                    $newFileName = uniqid('doc_') . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $fileInfo['basename']);
                    $destinationPath = $uploadDir . $newFileName;
                    $dbPath = 'files/documents/' . $newFileName;

                    // Mover ficheiro
                    if (!move_uploaded_file($_FILES['doc-files']['tmp_name'][$i], $destinationPath)) {
                        throw new Exception("Erro ao guardar o ficheiro '$nomeDoc' no servidor.");
                    }

                    // Inserir documento
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

        // Confirmar transação
        $ligacao->commit();
        
        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Equipamento '$nome' criado com sucesso!";

        // Desligar da BD
        $ligacao = null;

    } catch (Exception $e) {
        // Reverter transação
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar equipamento: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../equipment_list.php");
exit;
