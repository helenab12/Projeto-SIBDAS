<?php
try {
    // Ligar à BD
    if (!isset($ligacao)) {
        $ligacao = connect_to_db();
    }

    // Pre-fetch IDs
    $stmtM = execute_query("SELECT idManutencao FROM Manutencao WHERE idEquipamento = :id", ['id' => $id], $ligacao);
    $idManutencoes = array_column($stmtM->fetchAll(PDO::FETCH_ASSOC), 'idManutencao');

    $stmtG = execute_query("SELECT idGarantiaContrato FROM GarantiaContrato WHERE idEquipamento = :id", ['id' => $id], $ligacao);
    $idGarantias = array_column($stmtG->fetchAll(PDO::FETCH_ASSOC), 'idGarantiaContrato');

    $stmtD = execute_query("SELECT idDocumento FROM Documento WHERE idEquipamento = :id", ['id' => $id], $ligacao);
    $idDocumentos = array_column($stmtD->fetchAll(PDO::FETCH_ASSOC), 'idDocumento');

    // Definir condições
    $whereConditions = ["(ha.tabelaAfetada = 'Equipamento' AND ha.idRegistoAfetado = :id)"];
    $params = ['id' => $id];

    if (!empty($idManutencoes)) {
        $whereConditions[] = "(ha.tabelaAfetada = 'Manutencao' AND ha.idRegistoAfetado IN (" . implode(',', array_map('intval', $idManutencoes)) . "))";
    }
    if (!empty($idGarantias)) {
        $whereConditions[] = "(ha.tabelaAfetada = 'GarantiaContrato' AND ha.idRegistoAfetado IN (" . implode(',', array_map('intval', $idGarantias)) . "))";
    }
    if (!empty($idDocumentos)) {
        $whereConditions[] = "(ha.tabelaAfetada = 'Documento' AND ha.idRegistoAfetado IN (" . implode(',', array_map('intval', $idDocumentos)) . "))";
    }

    $whereConditions[] = "(ha.tabelaAfetada = 'FornecedorEquipamento' AND ha.idRegistoAfetado = :id)";
    $whereConditions[] = "(ha.tabelaAfetada = 'ComponenteEquipamento' AND ha.idRegistoAfetado = :id)";

    $whereSQL = implode(" OR ", $whereConditions);

    // Consultar auditoria
    $stmtAuditoria = execute_query(
        "SELECT ha.*, p.nome AS nomeUtilizador 
         FROM HistoricoAuditoria ha
         LEFT JOIN Utilizador u ON ha.idUtilizador = u.idUtilizador
         LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa
         WHERE $whereSQL
         ORDER BY ha.dataCriacao DESC",
        $params,
        $ligacao
    );

    // Processar resultados
    $auditoria = [];
    while ($row = $stmtAuditoria->fetch(PDO::FETCH_ASSOC)) {
        $data = new DateTime($row['dataCriacao']);

        $detalhes = '';
        if ($row['acao'] === 'Edição') {
            $campo = $row['campoAfetado'] ?? 'Desconhecido';
            $antigo = $row['valorAntigo'] ?? 'vazio';
            $novo = $row['valorNovo'] ?? 'vazio';
            $detalhes = "Campo <strong>" . htmlspecialchars($campo) . "</strong> alterado de <em>\"" . htmlspecialchars($antigo) . "\"</em> para <em>\"" . htmlspecialchars($novo) . "\"</em>";
            if ($row['tabelaAfetada'] !== 'Equipamento') {
                $detalhes = "[Relacionamento: " . htmlspecialchars($row['tabelaAfetada']) . "] " . $detalhes;
            }
        } else if ($row['acao'] === 'Criação') {
            if ($row['tabelaAfetada'] === 'Equipamento') {
                $detalhes = "Registo de Equipamento criado no sistema.";
            } else {
                $detalhes = "Adicionado relacionamento com <strong>" . htmlspecialchars($row['tabelaAfetada']) . "</strong>";
                if (!empty($row['valorNovo'])) {
                    $detalhes .= " (ID: " . htmlspecialchars($row['valorNovo']) . ")";
                }
            }
        } else if ($row['acao'] === 'Remoção') {
            if ($row['tabelaAfetada'] === 'Equipamento') {
                $detalhes = "Registo de Equipamento removido/arquivado.";
            } else {
                $detalhes = "Removido relacionamento com <strong>" . htmlspecialchars($row['tabelaAfetada']) . "</strong>";
            }
        }

        $badgeClass = 'badge-primary';
        if ($row['acao'] === 'Criação') {
            $badgeClass = 'badge-success';
        } else if ($row['acao'] === 'Remoção') {
            $badgeClass = 'badge-error';
        }

        $auditoria[] = [
            'data' => $data->format('d/m/Y, H:i:s'),
            'acao' => $row['acao'],
            'badgeClass' => $badgeClass,
            'utilizador' => $row['nomeUtilizador'] ?? 'Sistema',
            'detalhes' => $detalhes
        ];
    }
} catch (Exception $e) {
    // Capturar erro
    $auditoria = [];
}
?>
<!-- Tab Auditoria -->
<div class="tab-pane fade <?= $activeTab === 'auditoria' ? 'show active' : '' ?>" id="nav-auditoria" role="tabpanel"
    aria-labelledby="nav-auditoria-tab">
    <!-- Card Histórico -->
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center">
            <!-- Título -->
            <h2 class="fw-700 m-0 text-primary">Histórico de Auditoria</h2>
        </div>

        <?php if (empty($auditoria)): ?>
            <!-- Estado Vazio -->
            <div class="padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100">
                <!-- Wrapper Ícone -->
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG Relógio -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-history">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem Registos de Auditoria</h3>
                    <!-- Texto -->
                    <p class="text-secondary m-0">Ainda não existem registos de auditoria associados a este equipamento.</p>
                </div>
            </div>
        <?php else: ?>
            <!-- Tabela de Auditoria -->
            <table id="auditTable" class="heba-table w-100 display border-0">
                <!-- Cabeçalho -->
                <thead>
                    <tr>
                        <!-- Coluna Data -->
                        <th>DATA</th>
                        <!-- Coluna Ação -->
                        <th>AÇÃO</th>
                        <!-- Coluna Utilizador -->
                        <th>UTILIZADOR</th>
                        <!-- Coluna Detalhes -->
                        <th>DETALHES</th>
                    </tr>
                </thead>
                <!-- Corpo da Tabela -->
                <tbody>
                    <?php foreach ($auditoria as $item): ?>
                        <!-- Linha de Registo -->
                        <tr>
                            <!-- Data -->
                            <td>
                                <!-- Texto -->
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['data']) ?></span>
                            </td>
                            <!-- Ação -->
                            <td>
                                <!-- Badge -->
                                <span
                                    class="badge <?= htmlspecialchars($item['badgeClass']) ?>"><?= htmlspecialchars($item['acao']) ?></span>
                            </td>
                            <!-- Utilizador -->
                            <td>
                                <!-- Texto -->
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['utilizador']) ?></span>
                            </td>
                            <!-- Detalhes -->
                            <td>
                                <!-- Texto -->
                                <span class="text-secondary fw-400"><?= $item['detalhes'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>