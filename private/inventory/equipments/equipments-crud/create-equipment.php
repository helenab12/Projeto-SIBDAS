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
                criticidade, idLocalizacao, ativo
            ) VALUES (
                :idCategoria, :codigoInterno, :designacao, :idMarca, :modelo, :numeroSerie,
                :dataAquisicao, :dataFabrico, :custoAquisicao, :tipoEntrada, :estadoAtual,
                :criticidade, :idLocalizacao, 1
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
                'idLocalizacao' => $idLocalizacao
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
