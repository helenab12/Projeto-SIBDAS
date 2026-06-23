<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['maintenances.create']);

// Verificar método
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ligar à BD
        $ligacao = connect_to_db();

        // Recolher dados do POST
        $encryptedId = trim($_POST['equipment-id'] ?? '');
        
        // Validar dados
        if (empty($encryptedId)) {
            throw new Exception("ID de equipamento não fornecido.");
        }

        // Desencriptar ID
        $idEquipamento = aes_decrypt($encryptedId);
        
        // Validar dados
        if ($idEquipamento === false) {
            throw new Exception("ID de equipamento inválido.");
        }
        $idEquipamento = (int) $idEquipamento;

        // Recolher dados do POST
        $tipoManutencaoRaw = trim($_POST['maintenance-type'] ?? '');
        $dataInicioRaw = trim($_POST['maintenance-start-date'] ?? '');
        $dataFimRaw = trim($_POST['maintenance-end-date'] ?? '');
        $idPessoaResponsavel = trim($_POST['maintenance-responsible'] ?? '');
        $idFornecedor = trim($_POST['maintenance-supplier'] ?? '');
        $custoManutencaoRaw = trim($_POST['maintenance-cost'] ?? '');
        $observacoes = trim($_POST['maintenance-notes'] ?? '');

        // Inicializar variáveis
        $dInicio = DateTime::createFromFormat('d/m/Y', $dataInicioRaw);
        $dataInicio = $dInicio ? $dInicio->format('Y-m-d') : '';

        $dFim = DateTime::createFromFormat('d/m/Y', $dataFimRaw);
        $dataFim = $dFim ? $dFim->format('Y-m-d') : '';

        if (empty($idFornecedor)) {
            $idFornecedor = null;
        }

        $custoManutencao = $custoManutencaoRaw !== '' ? (float) $custoManutencaoRaw : null;

        // Sanitizar dados
        $dadosSanitizados = Manutencao::sanitizarDados([
            'idManutencao' => '-1',
            'tipoManutencao' => $tipoManutencaoRaw,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'idPessoaResponsavel' => $idPessoaResponsavel,
            'custoManutencao' => $custoManutencao
        ]);
        
        // Inicializar variáveis
        $tipoManutencaoRaw = $dadosSanitizados['tipoManutencao'] ?? $tipoManutencaoRaw;
        $dataInicio = $dadosSanitizados['dataInicio'] ?? $dataInicio;
        $dataFim = $dadosSanitizados['dataFim'] ?? $dataFim;
        $idPessoaResponsavel = $dadosSanitizados['idPessoaResponsavel'] ?? $idPessoaResponsavel;
        $custoManutencao = $dadosSanitizados['custoManutencao'] ?? $custoManutencao;
        
        // Validar dados
        $erros = Manutencao::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode("<br>", $erros));
        }

        // Inserir manutenção
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

        // Registar auditoria
        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Manutencao', $novoId, 'Criação');

        // Inicializar mensagem sucesso
        $_SESSION['success_message'] = "Registo de manutenção adicionado com sucesso!";

        // Desligar da BD
        $ligacao = null;

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Inicializar variáveis
$redirectUrl = isset($encryptedId) && !empty($encryptedId)
    ? "../detailed_view.php?id=" . urlencode($encryptedId) . "&nav=manutencoes"
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
