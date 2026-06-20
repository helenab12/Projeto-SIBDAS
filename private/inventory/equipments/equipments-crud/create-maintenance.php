<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['maintenances.create']);

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
        $tipoManutencaoRaw = trim($_POST['maintenance-type'] ?? '');
        $dataInicioRaw = trim($_POST['maintenance-start-date'] ?? '');
        $dataFimRaw = trim($_POST['maintenance-end-date'] ?? '');
        $idPessoaResponsavel = trim($_POST['maintenance-responsible'] ?? '');
        $idFornecedor = trim($_POST['maintenance-supplier'] ?? '');
        $custoManutencaoRaw = trim($_POST['maintenance-cost'] ?? '');
        $observacoes = trim($_POST['maintenance-notes'] ?? '');

        // Converter datas de d/m/Y para Y-m-d
        $dInicio = DateTime::createFromFormat('d/m/Y', $dataInicioRaw);
        $dataInicio = $dInicio ? $dInicio->format('Y-m-d') : '';

        $dFim = DateTime::createFromFormat('d/m/Y', $dataFimRaw);
        $dataFim = $dFim ? $dFim->format('Y-m-d') : '';

        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        $custoManutencao = $custoManutencaoRaw !== '' ? (float) $custoManutencaoRaw : null;

        // 3. Validar dados com a classe Manutencao
        $erros = Manutencao::validarDados([
            'idManutencao' => '-1',  // ID fictício para validação
            'tipoManutencao' => $tipoManutencaoRaw,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'idPessoaResponsavel' => $idPessoaResponsavel,
            'custoManutencao' => $custoManutencao
        ]);

        if (!empty($erros)) {
            throw new Exception(implode("<br>", $erros));
        }

        // 4. Inserir na base de dados
        execute_query(
            "INSERT INTO Manutencao (idEquipamento, tipoManutencao, dataInicio, dataFim, idPessoaResponsavel, idFornecedor, custoManutencao, observacoes, ativo)
             VALUES (:idEq, :tipo, :dataInicio, :dataFim, :idPessoa, :idForn, :custo, :obs, 1)",
            [
                'idEq' => $idEquipamento,
                'tipo' => $tipoManutencaoRaw,
                'dataInicio' => $dataInicio,
                'dataFim' => !empty($dataFim) ? $dataFim : null,
                'idPessoa' => $idPessoaResponsavel,
                'idForn' => $idFornecedor,
                'custo' => $custoManutencao,
                'obs' => !empty($observacoes) ? $observacoes : null
            ],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Manutencao', $novoId, 'Criação');

        $_SESSION['success_message'] = "Registo de manutenção adicionado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Redirecionar para a vista detalhada, tab de manutenções
$redirectUrl = isset($encryptedId) && !empty($encryptedId)
    ? "../detailed_view.php?id=" . urlencode($encryptedId) . "&nav=manutencoes"
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
