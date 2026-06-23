<?php
// Calcular variação percentual
function get_growth_array($atual, $passado)
{
    if ($passado == 0) {
        $growth = $atual > 0 ? 100 : 0;
    } else {
        $growth = (($atual - $passado) / $passado) * 100;
    }

    $isPositive = $growth >= 0;
    $growthFormatted = number_format(abs($growth), 0) . '%';

    return [
        'isPositive' => $isPositive,
        'value' => $growthFormatted,
        'raw' => $growth
    ];
}

// Inicializar variáveis
$dashboardStats = [];

try {
    // Ligar à BD
    $ligacaoStats = connect_to_db();

    // Obter total de equipamentos
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1", [], $ligacaoStats);
    $totalEquipamentos = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND dataCriacao < DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')", [], $ligacaoStats);
    $totalEquipamentosPassado = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $dashboardStats['totalEquipamentos'] = [
        'count' => $totalEquipamentos,
        'growth' => get_growth_array($totalEquipamentos, $totalEquipamentosPassado)
    ];

    // Obter equipamentos ativos
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND estadoAtual = 'Ativo'", [], $ligacaoStats);
    $dashboardStats['equipamentosAtivos'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'], 'growth' => null];

    // Obter equipamentos em manutenção
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND estadoAtual = 'Em manutenção'", [], $ligacaoStats);
    $equipamentosManutencao = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = execute_query("SELECT COUNT(*) as count FROM Manutencao WHERE ativo = 1 AND MONTH(dataInicio) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND YEAR(dataInicio) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)", [], $ligacaoStats);
    $manutencoesPassadas = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = execute_query("SELECT COUNT(*) as count FROM Manutencao WHERE ativo = 1 AND MONTH(dataInicio) = MONTH(CURRENT_DATE) AND YEAR(dataInicio) = YEAR(CURRENT_DATE)", [], $ligacaoStats);
    $manutencoesAtuais = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $dashboardStats['emManutencao'] = [
        'count' => $equipamentosManutencao,
        'growth' => get_growth_array($manutencoesAtuais, $manutencoesPassadas)
    ];

    // Obter garantias a expirar
    $stmt = execute_query("SELECT COUNT(*) as count FROM GarantiaContrato WHERE ativo = 1 AND dataFim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)", [], $ligacaoStats);
    $dashboardStats['garantiasAExpirar'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'], 'growth' => null];

    // Obter equipamentos inativos
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND estadoAtual = 'Inativo'", [], $ligacaoStats);
    $dashboardStats['equipamentosInativos'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // Obter garantias expiradas
    $stmt = execute_query("SELECT COUNT(*) as count FROM GarantiaContrato WHERE ativo = 1 AND dataFim < CURDATE()", [], $ligacaoStats);
    $dashboardStats['garantiasExpiradas'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // Obter equipamentos com criticidade elevada
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND criticidade IN ('Alta', 'Crítico')", [], $ligacaoStats);
    $dashboardStats['criticidadeElevada'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // Obter equipamentos sem documentos
    $stmt = execute_query("
        SELECT COUNT(*) as count FROM (
            SELECT e.idEquipamento
            FROM Equipamento e
            LEFT JOIN Documento d ON e.idEquipamento = d.idEquipamento AND d.ativo = 1
            WHERE e.ativo = 1
            GROUP BY e.idEquipamento
            HAVING COUNT(DISTINCT d.tipo) < 8
        ) as subquery
    ", [], $ligacaoStats);
    $dashboardStats['semDocumentos'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // Preparar dados para gráficos

    // Obter distribuição por categoria
    $stmt = execute_query("SELECT c.nome, COUNT(e.idEquipamento) as total FROM CategoriaEquipamento c LEFT JOIN Equipamento e ON c.idCategoria = e.idCategoria AND e.ativo = 1 WHERE c.ativo = 1 GROUP BY c.idCategoria, c.nome ORDER BY total DESC", [], $ligacaoStats);
    $rowsCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dashboardStats['graficoCategoria'] = [
        'labels' => array_column($rowsCat, 'nome'),
        'data' => array_column($rowsCat, 'total')
    ];

    // Obter equipamentos por serviço
    $stmt = execute_query("SELECT s.nome, COUNT(e.idEquipamento) as total FROM Servico s LEFT JOIN Localizacao l ON s.idServico = l.idServico AND l.ativo = 1 LEFT JOIN Equipamento e ON l.idLocalizacao = e.idLocalizacao AND e.ativo = 1 WHERE s.ativo = 1 GROUP BY s.idServico, s.nome ORDER BY total DESC", [], $ligacaoStats);
    $rowsServ = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dashboardStats['graficoServico'] = [
        'labels' => array_column($rowsServ, 'nome'),
        'data' => array_column($rowsServ, 'total')
    ];

    // Obter tendência de manutenção
    $stmt = execute_query("
        SELECT 
            MONTH(dataInicio) as mes, 
            YEAR(dataInicio) as ano, 
            tipoManutencao, 
            COUNT(*) as total 
        FROM Manutencao 
        WHERE ativo = 1 
          AND dataInicio >= DATE_SUB(LAST_DAY(CURDATE()), INTERVAL 12 MONTH)
        GROUP BY YEAR(dataInicio), MONTH(dataInicio), tipoManutencao
        ORDER BY ano ASC, mes ASC
    ", [], $ligacaoStats);
    $rowsMan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar variáveis do gráfico
    $mesesNomes = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    $labels = [];
    $preventivaDataDict = [];
    $corretivaDataDict = [];

    // Preencher últimos 12 meses
    for ($i = 11; $i >= 0; $i--) {
        $timestamp = strtotime("-$i months");
        $mesNum = (int) date('n', $timestamp);
        $anoNum = (int) date('Y', $timestamp);

        $key = $anoNum . '-' . $mesNum;
        $labels[] = $mesesNomes[$mesNum - 1];
        $preventivaDataDict[$key] = 0;
        $corretivaDataDict[$key] = 0;
    }

    // Processar dados de manutenção
    foreach ($rowsMan as $r) {
        $key = $r['ano'] . '-' . $r['mes'];
        if (isset($preventivaDataDict[$key])) {
            if ($r['tipoManutencao'] === 'Preventiva') {
                $preventivaDataDict[$key] = (int) $r['total'];
            } elseif ($r['tipoManutencao'] === 'Corretiva') {
                $corretivaDataDict[$key] = (int) $r['total'];
            }
        }
    }

    $dashboardStats['graficoManutencao'] = [
        'labels' => $labels,
        'preventiva' => array_values($preventivaDataDict),
        'corretiva' => array_values($corretivaDataDict)
    ];

    // Obter próximas manutenções agendadas
    $stmt = execute_query("
        SELECT 
            m.idManutencao,
            m.idEquipamento,
            m.tipoManutencao,
            m.dataInicio,
            e.designacao,
            s.nome as nomeServico
        FROM Manutencao m
        JOIN Equipamento e ON m.idEquipamento = e.idEquipamento
        LEFT JOIN Localizacao l ON e.idLocalizacao = l.idLocalizacao
        LEFT JOIN Servico s ON l.idServico = s.idServico
        WHERE m.ativo = 1 
          AND e.ativo = 1 
          AND m.dataInicio >= CURDATE()
        ORDER BY m.dataInicio ASC
        LIMIT 4
    ", [], $ligacaoStats);
    $dashboardStats['proximasManutencoes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Fechar ligação à BD
    $ligacaoStats = null;

}
// Capturar erro
catch (Exception $e) {
    // Capturar erro
    error_log("Erro ao calcular estatísticas do dashboard: " . $e->getMessage());
    $dashboardStats = [];
}
?>