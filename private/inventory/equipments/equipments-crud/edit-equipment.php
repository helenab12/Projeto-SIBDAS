<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['equipments.edit']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ligar à BD
        $ligacao = connect_to_db();
        $ligacao->beginTransaction();

        // Recolher dados do POST
        $encryptedId = trim($_POST['equipment-id'] ?? '');
        
        // Validar dados
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

        // Recolher formulário
        $codigoInterno = trim($_POST['equipment-code'] ?? '');
        $idCategoria = trim($_POST['equipment-category'] ?? '');
        $numeroSerie = trim($_POST['equipment-serial'] ?? '');
        $nome = trim($_POST['equipment-name'] ?? '');
        $idMarca = trim($_POST['equipment-brand'] ?? '');
        $modelo = trim($_POST['equipment-model'] ?? '');
        
        // Declarar função conversão data
        $formatDate = function($dateStr) {
            if (empty($dateStr)) return null;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) return $dateStr;
            
            $d = DateTime::createFromFormat('d/m/Y', $dateStr);
            if ($d && $d->format('d/m/Y') === $dateStr) {
                return $d->format('Y-m-d');
            }
            return null;
        };

        // Formatar e recolher restante formulário
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

        // Validar dados básicos
        if (empty($codigoInterno) || empty($idCategoria) || empty($numeroSerie) || empty($nome) || empty($idMarca) || empty($idLocalizacao)) {
            throw new Exception("Por favor preencha todos os campos obrigatórios da primeira página.");
        }

        // Verificar código único
        $stmt = execute_query(
            "SELECT idEquipamento FROM Equipamento WHERE codigoInterno = :codigo AND idEquipamento != :id", 
            ['codigo' => $codigoInterno, 'id' => $idEquipamento], 
            $ligacao
        );
        if ($stmt->fetch()) {
            throw new Exception("O código interno $codigoInterno já se encontra registado noutro equipamento.");
        }

        // Obter estado antigo
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

        $fornecedoresIds = array_unique($fornecedoresIds);

        // Atualizar fornecedores
        execute_query("DELETE FROM FornecedorEquipamento WHERE idEquipamento = :idEq", ['idEq' => $idEquipamento], $ligacao);
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
        
        // Atualizar componentes
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

        // Submeter transação
        $ligacao->commit();
        
        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Equipamento '$nome' atualizado com sucesso!";

        // Limpar variável de ligação
        $ligacao = null;

    } catch (Exception $e) {
        // Capturar erro
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao atualizar equipamento: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../equipment_list.php");
exit;
