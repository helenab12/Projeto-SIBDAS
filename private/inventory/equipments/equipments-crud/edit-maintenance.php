<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['maintenances.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        // 1. Desencriptar IDs
        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedManId = trim($_POST['maintenance-id'] ?? '');

        if (empty($encryptedEqId) || empty($encryptedManId)) {
            throw new Exception("IDs não fornecidos.");
        }

        $idEquipamento = aes_decrypt($encryptedEqId);
        $idManutencao = aes_decrypt($encryptedManId);

        if ($idEquipamento === false || $idManutencao === false) {
            throw new Exception("IDs inválidos.");
        }

        $idEquipamento = (int) $idEquipamento;
        $idManutencao = (int) $idManutencao;

        // 2. Recolher dados
        $tipoManutencaoRaw = trim($_POST['maintenance-type'] ?? '');
        $dataInicioRaw = trim($_POST['maintenance-start-date'] ?? '');
        $dataFimRaw = trim($_POST['maintenance-end-date'] ?? '');
        $idPessoaResponsavel = trim($_POST['maintenance-responsible'] ?? '');
        $idFornecedor = trim($_POST['maintenance-supplier'] ?? '');
        $custoManutencaoRaw = trim($_POST['maintenance-cost'] ?? '');
        $observacoes = trim($_POST['maintenance-notes'] ?? '');

        // Converter datas
        $dInicio = DateTime::createFromFormat('d/m/Y', $dataInicioRaw);
        $dataInicio = $dInicio ? $dInicio->format('Y-m-d') : '';

        $dFim = DateTime::createFromFormat('d/m/Y', $dataFimRaw);
        $dataFim = $dFim ? $dFim->format('Y-m-d') : '';

        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        $custoManutencao = $custoManutencaoRaw !== '' ? (float) $custoManutencaoRaw : null;

        // 3. Validar
        $erros = Manutencao::validarDados([
            'idManutencao' => (string)$idManutencao,
            'tipoManutencao' => $tipoManutencaoRaw,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'idPessoaResponsavel' => $idPessoaResponsavel,
            'custoManutencao' => $custoManutencao
        ]);

        if (!empty($erros)) {
            throw new Exception(implode("<br>", $erros));
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT tipoManutencao, dataInicio, dataFim, idPessoaResponsavel, idFornecedor, custoManutencao, observacoes FROM Manutencao WHERE idManutencao = :idMan AND idEquipamento = :idEq",
            ['idMan' => $idManutencao, 'idEq' => $idEquipamento],
            $ligacao
        );
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // 4. Atualizar DB
        execute_query(
            "UPDATE Manutencao SET 
                tipoManutencao = :tipo, 
                dataInicio = :dataInicio, 
                dataFim = :dataFim, 
                idPessoaResponsavel = :idPessoa, 
                idFornecedor = :idForn, 
                custoManutencao = :custo, 
                observacoes = :obs,
                dataAtualizacao = NOW()
             WHERE idManutencao = :idMan AND idEquipamento = :idEq",
            [
                'tipo' => $tipoManutencaoRaw,
                'dataInicio' => $dataInicio,
                'dataFim' => !empty($dataFim) ? $dataFim : null,
                'idPessoa' => $idPessoaResponsavel,
                'idForn' => $idFornecedor,
                'custo' => $custoManutencao,
                'obs' => !empty($observacoes) ? $observacoes : null,
                'idMan' => $idManutencao,
                'idEq' => $idEquipamento
            ],
            $ligacao
        );

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Manutencao', $idManutencao, $antigo, [
            'tipoManutencao' => $tipoManutencaoRaw,
            'dataInicio' => $dataInicio,
            'dataFim' => !empty($dataFim) ? $dataFim : null,
            'idPessoaResponsavel' => $idPessoaResponsavel,
            'idFornecedor' => $idFornecedor,
            'custoManutencao' => $custoManutencao,
            'observacoes' => !empty($observacoes) ? $observacoes : null
        ]);

        $_SESSION['success_message'] = "Registo de manutenção atualizado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Redirecionar
$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId)
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=manutencoes"
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
