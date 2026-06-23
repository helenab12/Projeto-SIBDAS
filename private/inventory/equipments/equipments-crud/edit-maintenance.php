<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['maintenances.edit']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Desencriptar IDs
        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedManId = trim($_POST['maintenance-id'] ?? '');

        // Validar IDs
        if (empty($encryptedEqId) || empty($encryptedManId)) {
            throw new Exception("IDs não fornecidos.");
        }

        $idEquipamento = aes_decrypt($encryptedEqId);
        $idManutencao = aes_decrypt($encryptedManId);

        // Validar desencriptação
        if ($idEquipamento === false || $idManutencao === false) {
            throw new Exception("IDs inválidos.");
        }

        // Converter para número
        $idEquipamento = (int) $idEquipamento;
        $idManutencao = (int) $idManutencao;

        // Recolher formulário
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

        // Tratar ID de fornecedor
        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        // Formatar custo
        $custoManutencao = $custoManutencaoRaw !== '' ? (float) $custoManutencaoRaw : null;

        // Sanitizar dados
        $dadosSanitizados = Manutencao::sanitizarDados([
            'idManutencao' => (string)$idManutencao,
            'tipoManutencao' => $tipoManutencaoRaw,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'idPessoaResponsavel' => $idPessoaResponsavel,
            'custoManutencao' => $custoManutencao
        ]);
        $tipoManutencaoRaw = $dadosSanitizados['tipoManutencao'] ?? $tipoManutencaoRaw;
        $dataInicio = $dadosSanitizados['dataInicio'] ?? $dataInicio;
        $dataFim = $dadosSanitizados['dataFim'] ?? $dataFim;
        $idPessoaResponsavel = $dadosSanitizados['idPessoaResponsavel'] ?? $idPessoaResponsavel;
        $custoManutencao = $dadosSanitizados['custoManutencao'] ?? $custoManutencao;
        
        // Validar dados sanitizados
        $erros = Manutencao::validarDados($dadosSanitizados);

        // Lançar erro
        if (!empty($erros)) {
            throw new Exception(implode("<br>", $erros));
        }

        // Obter estado antigo
        $stmtAntigo = execute_query(
            "SELECT tipoManutencao, dataInicio, dataFim, idPessoaResponsavel, idFornecedor, custoManutencao, observacoes FROM Manutencao WHERE idManutencao = :idMan AND idEquipamento = :idEq",
            ['idMan' => $idManutencao, 'idEq' => $idEquipamento]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar DB
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
            ]);

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

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Registo de manutenção atualizado com sucesso!";

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Construir link
$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId)
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=manutencoes"
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
