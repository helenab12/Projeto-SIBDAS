<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['equipments.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();
        $ligacao->beginTransaction();

        $encryptedId = trim($_POST['equipment-id'] ?? '');
        if (empty($encryptedId)) {
            throw new Exception("ID de equipamento não fornecido.");
        }
        
        $idEquipamento = aes_decrypt($encryptedId);
        if ($idEquipamento === false) {
            throw new Exception("ID de equipamento inválido.");
        }
        $idEquipamento = (int) $idEquipamento;

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

        // Verificar se codigoInterno já existe noutro equipamento
        $stmt = execute_query(
            "SELECT idEquipamento FROM Equipamento WHERE codigoInterno = :codigo AND idEquipamento != :id", 
            ['codigo' => $codigoInterno, 'id' => $idEquipamento], 
            $ligacao
        );
        if ($stmt->fetch()) {
            throw new Exception("O código interno $codigoInterno já se encontra registado noutro equipamento.");
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT idCategoria, codigoInterno, designacao, idMarca, modelo, numeroSerie, dataAquisicao, dataFabrico, custoAquisicao, tipoEntrada, estadoAtual, criticidade, idLocalizacao, observacoes FROM Equipamento WHERE idEquipamento = :id",
            ['id' => $idEquipamento],
            $ligacao
        );
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar Equipamento
        execute_query(
            "UPDATE Equipamento SET
                idCategoria = :idCategoria,
                codigoInterno = :codigoInterno,
                designacao = :designacao,
                idMarca = :idMarca,
                modelo = :modelo,
                numeroSerie = :numeroSerie,
                dataAquisicao = :dataAquisicao,
                dataFabrico = :dataFabrico,
                custoAquisicao = :custoAquisicao,
                tipoEntrada = :tipoEntrada,
                estadoAtual = :estadoAtual,
                criticidade = :criticidade,
                idLocalizacao = :idLocalizacao,
                observacoes = :observacoes,
                dataAtualizacao = CURRENT_TIMESTAMP
            WHERE idEquipamento = :idEquipamento",
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
                'observacoes' => $observacoes,
                'idEquipamento' => $idEquipamento
            ],
            $ligacao
        );

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Equipamento', $idEquipamento, $antigo, [
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
        ]);

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

        $fornecedoresIds = array_unique($fornecedoresIds);

        // Remover antigas associações e inserir novas
        execute_query("DELETE FROM FornecedorEquipamento WHERE idEquipamento = :idEq", ['idEq' => $idEquipamento], $ligacao);
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
        
        // Remover antigas associações e inserir novas
        execute_query("DELETE FROM ComponenteEquipamento WHERE idEquipamento = :idEq", ['idEq' => $idEquipamento], $ligacao);
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

        $ligacao->commit();
        $_SESSION['success_message'] = "Equipamento '$nome' atualizado com sucesso!";

    } catch (Exception $e) {
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao atualizar equipamento: " . $e->getMessage();
    }
}

header("Location: ../equipment_list.php");
exit;
