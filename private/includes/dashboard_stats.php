<?php
// Helper para formatar crescimento (variação percentual)
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

$dashboardStats = [];

try {
    $ligacaoStats = connect_to_db();

    // 1. Total de Equipamentos
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1", [], $ligacaoStats);
    $totalEquipamentos = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND dataCriacao < DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')", [], $ligacaoStats);
    $totalEquipamentosPassado = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $dashboardStats['totalEquipamentos'] = [
        'count' => $totalEquipamentos,
        'growth' => get_growth_array($totalEquipamentos, $totalEquipamentosPassado)
    ];

    // 2. Equipamentos Ativos
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND estadoAtual = 'Ativo'", [], $ligacaoStats);
    $dashboardStats['equipamentosAtivos'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'], 'growth' => null];

    // 3. Em Manutenção
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

    // 4. Garantias a Expirar
    $stmt = execute_query("SELECT COUNT(*) as count FROM GarantiaContrato WHERE ativo = 1 AND dataFim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)", [], $ligacaoStats);
    $dashboardStats['garantiasAExpirar'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'], 'growth' => null];

    // 5. Equipamentos Inativos
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND estadoAtual = 'Inativo'", [], $ligacaoStats);
    $dashboardStats['equipamentosInativos'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // 6. Garantias Expiradas
    $stmt = execute_query("SELECT COUNT(*) as count FROM GarantiaContrato WHERE ativo = 1 AND dataFim < CURDATE()", [], $ligacaoStats);
    $dashboardStats['garantiasExpiradas'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // 7. Criticidade Elevada
    $stmt = execute_query("SELECT COUNT(*) as count FROM Equipamento WHERE ativo = 1 AND criticidade IN ('Alta', 'Crítico')", [], $ligacaoStats);
    $dashboardStats['criticidadeElevada'] = ['count' => (int) $stmt->fetch(PDO::FETCH_ASSOC)['count']];

    // 8. Sem Documentos
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

    // GRÁFICOS

    // G1: Distribuição por Categoria
    $stmt = execute_query("SELECT c.nome, COUNT(e.idEquipamento) as total FROM CategoriaEquipamento c LEFT JOIN Equipamento e ON c.idCategoria = e.idCategoria AND e.ativo = 1 WHERE c.ativo = 1 GROUP BY c.idCategoria, c.nome ORDER BY total DESC", [], $ligacaoStats);
    $rowsCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dashboardStats['graficoCategoria'] = [
        'labels' => array_column($rowsCat, 'nome'),
        'data' => array_column($rowsCat, 'total')
    ];

    // G2: Equipamentos por Serviço
    $stmt = execute_query("SELECT s.nome, COUNT(e.idEquipamento) as total FROM Servico s LEFT JOIN Localizacao l ON s.idServico = l.idServico AND l.ativo = 1 LEFT JOIN Equipamento e ON l.idLocalizacao = e.idLocalizacao AND e.ativo = 1 WHERE s.ativo = 1 GROUP BY s.idServico, s.nome ORDER BY total DESC", [], $ligacaoStats);
    $rowsServ = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dashboardStats['graficoServico'] = [
        'labels' => array_column($rowsServ, 'nome'),
        'data' => array_column($rowsServ, 'total')
    ];

    // G3: Tendência de Manutenção (Últimos 12 meses)
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

    $mesesNomes = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    $labels = [];
    $preventivaDataDict = [];
    $corretivaDataDict = [];

    // Pre-fill the last 12 months
    for ($i = 11; $i >= 0; $i--) {
        $timestamp = strtotime("-$i months");
        $mesNum = (int) date('n', $timestamp);
        $anoNum = (int) date('Y', $timestamp);

        $key = $anoNum . '-' . $mesNum;
        $labels[] = $mesesNomes[$mesNum - 1];
        $preventivaDataDict[$key] = 0;
        $corretivaDataDict[$key] = 0;
    }

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

} catch (Exception $e) {
    error_log("Erro ao calcular estatísticas do dashboard: " . $e->getMessage());
    $dashboardStats = [];
}
?>