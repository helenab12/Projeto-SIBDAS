<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
redirect_if_not_logged();

$success_message = null;
$server_error = null;

if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

$encryptedId = $_GET['id'] ?? null;
if (!$encryptedId) {
    $_SESSION['server_error'] = "ID do equipamento não fornecido.";
    header("Location: equipment_list.php");
    exit;
}

$id = aes_decrypt($encryptedId);
if ($id === false) {
    $_SESSION['server_error'] = "ID do equipamento inválido.";
    header("Location: equipment_list.php");
    exit;
}
$id = (int) $id;

try {
    $ligacao = connect_to_db();

    // Query Equipamento + Marca
    $stmt = execute_query(
        "SELECT e.*, m.nome as marcaNome 
         FROM Equipamento e 
         LEFT JOIN Marca m ON e.idMarca = m.idMarca 
         WHERE e.idEquipamento = :id",
        ['id' => $id],
        $ligacao
    );

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $_SESSION['server_error'] = "Equipamento não encontrado.";
        header("Location: equipment_list.php");
        exit;
    }

    $equipamento = new Equipamento(
        (string) $row['idEquipamento'],
        $row['idCategoria'],
        $row['codigoInterno'],
        $row['designacao'],
        $row['idMarca'],
        $row['modelo'],
        $row['numeroSerie'],
        $row['dataAquisicao'] ? new DateTime($row['dataAquisicao']) : null,
        $row['dataFabrico'] ? new DateTime($row['dataFabrico']) : null,
        (float) $row['custoAquisicao'],
        TipoEntrada::from($row['tipoEntrada']),
        EstadoEquipamento::from($row['estadoAtual']),
        CriticidadeEquipamento::from($row['criticidade']),
        $row['observacoes'] ?? '',
        $row['idLocalizacao'],
        (bool) $row['arquivado'],
        (bool) $row['ativo'],
        new DateTime($row['dataCriacao']),
        new DateTime($row['dataAtualizacao']),
        $row['marcaNome']
    );

    // Get Categoria
    $catNome = "Sem Categoria";
    if ($equipamento->getIdCategoria()) {
        $stmtCat = execute_query(
            "SELECT nome FROM CategoriaEquipamento WHERE idCategoria = :idCat",
            ['idCat' => $equipamento->getIdCategoria()],
            $ligacao
        );
        $catRow = $stmtCat->fetch(PDO::FETCH_ASSOC);
        if ($catRow) {
            $catNome = $catRow['nome'];
        }
    }

    // Get Localizacao
    $locNome = "Desconhecida";
    if ($equipamento->getIdLocalizacao()) {
        $stmtLoc = execute_query(
            "SELECT 
                l.idLocalizacao,
                e.nome AS edificioNome,
                p.nome AS pisoNome,
                s.nome AS servicoNome,
                l.nomeSala AS salaNome
             FROM Localizacao l
             JOIN Servico s ON l.idServico = s.idServico
             JOIN Piso p ON s.idPiso = p.idPiso
             JOIN Edificio e ON p.idEdificio = e.idEdificio
             WHERE l.idLocalizacao = :idLoc",
            ['idLoc' => $equipamento->getIdLocalizacao()],
            $ligacao
        );
        $locRow = $stmtLoc->fetch(PDO::FETCH_ASSOC);
        if ($locRow) {
            $locNome = $locRow['edificioNome'] . ', ' . $locRow['pisoNome'] . ', ' . $locRow['servicoNome'] . ', ' . $locRow['salaNome'];
        }
    }

    // Obter Fornecedor Fabricante
    $supplier = '—';
    $stmtFornecedor = execute_query(
        "SELECT f.nome 
         FROM Fornecedor f
         INNER JOIN FornecedorEquipamento fe ON f.idFornecedor = fe.idFornecedor
         WHERE fe.idEquipamento = :id AND f.tipoFornecedor = 'Fabricante' AND f.ativo = 1 AND fe.ativo = 1
         LIMIT 1",
        ['id' => $id],
        $ligacao
    );
    if ($fornRow = $stmtFornecedor->fetch(PDO::FETCH_ASSOC)) {
        $supplier = $fornRow['nome'];
    }

    // Dados para a Tab Documentos 

    // Obter Documentos Associados ao Equipamento
    $stmtDocs = execute_query(
        "SELECT d.*, f.nome as fornecedorNome
         FROM Documento d
         LEFT JOIN Fornecedor f ON d.idFornecedor = f.idFornecedor
         WHERE d.idEquipamento = :id AND d.ativo = 1
         ORDER BY d.dataCriacao DESC",
        ['id' => $id],
        $ligacao
    );
    $documentos = [];
    while ($docRow = $stmtDocs->fetch(PDO::FETCH_ASSOC)) {
        $documentos[] = new Documento(
            (string) $docRow['idDocumento'],
            TipoDocumento::from($docRow['tipo']),
            $docRow['nome'],
            $docRow['caminhoFicheiro'],
            $docRow['dataDocumento'] ? new DateTime($docRow['dataDocumento']) : null,
            $docRow['dataValidade'] ? new DateTime($docRow['dataValidade']) : null,
            (string) $docRow['idEquipamento'],
            $docRow['idFornecedor'] ? (string) $docRow['idFornecedor'] : null,
            (bool) $docRow['ativo'],
            new DateTime($docRow['dataCriacao']),
            new DateTime($docRow['dataAtualizacao']),
            $docRow['fornecedorNome']
        );
    }

    // Calcular Documentos em Falta
    $tiposExistentes = array_map(fn($d) => $d->getTipo()->value, $documentos);
    $tiposEmFalta = [];
    foreach (TipoDocumento::cases() as $tipo) {
        if (!in_array($tipo->value, $tiposExistentes)) {
            $tiposEmFalta[] = $tipo;
        }
    }
    $totalTipos = count(TipoDocumento::cases());
    $totalEmFalta = count($tiposEmFalta);

    // Buscar Fornecedores (para os modais)
    $stmtFornecedoresDoc = execute_query(
        "SELECT idFornecedor, nome FROM Fornecedor WHERE ativo = 1 ORDER BY nome ASC",
        [],
        $ligacao
    );
    $fornecedoresDisponiveis = $stmtFornecedoresDoc->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['server_error'] = "Erro ao carregar os dados do equipamento: " . $e->getMessage();
    header("Location: equipment_list.php");
    exit;
}

// Variáveis para o template
$designacao = $equipamento->getDesignacao();
$codigoInterno = $equipamento->getCodigoInterno();
$marcaNome = $equipamento->getMarcaNome() ?? '—';
$modelo = $equipamento->getModelo();
$tipoEntrada = $equipamento->getTipoEntrada()->value;
$dataFabricoObj = $equipamento->getDataFabrico();
$dataFabricoFormatada = $dataFabricoObj ? $dataFabricoObj->format('d/m/Y') : 'Desconhecida';
$serialNumber = $equipamento->getNumeroSerie();
$category = $catNome;
$purchaseDateObj = $equipamento->getDataAquisicao();
$purchaseDate = $purchaseDateObj ? $purchaseDateObj->format('d/m/Y') : 'Desconhecida';
$notes = $equipamento->getObservacoes() ?: 'Sem observações.';

// TODO: Implementar no futuro - Placeholder para ja
$lastMaintenance = '—';
$nextMaintenance = '—';
$warrantyExpirationDate = null;

// Cálculo da garantia
$isExpired = false;
$daysRemaining = 0;
if ($warrantyExpirationDate !== null) {
    $today = new DateTime('now');
    $expiration = DateTime::createFromFormat('d/m/Y', $warrantyExpirationDate);
    $isExpired = $today > $expiration;
    if (!$isExpired) {
        $diff = $today->diff($expiration);
        $daysRemaining = $diff->days;
    }
}

// Tooltips e Classes de Badges
$estado = $equipamento->getEstadoAtual()->value;
$statusClass = match ($estado) {
    'Ativo' => 'equipment-badge-status-active',
    'Manutenção' => 'equipment-badge-status-maintenance',
    'Inativo' => 'equipment-badge-status-inactive',
    'Abatido' => 'equipment-badge-status-scrapped',
    default => 'bg-secondary',
};
$estadoTooltip = match ($estado) {
    'Ativo' => "Equipamento operacional e disponível para uso clínico.",
    'Manutenção' => "Equipamento em reparação ou calibração.",
    'Inativo' => "Equipamento parado, a aguardar decisão.",
    'Abatido' => "Equipamento retirado definitivamente do serviço.",
    default => "Estado: " . $estado,
};

$criticidade = $equipamento->getCriticidade()->value;
$critClass = match ($criticidade) {
    'Crítico' => 'equipment-badge-criticality-critical',
    'Alta' => 'equipment-badge-criticality-high',
    'Média' => 'equipment-badge-criticality-medium',
    'Baixa' => 'equipment-badge-criticality-low',
    default => 'bg-secondary',
};
$criticidadeTooltip = match ($criticidade) {
    'Crítico' => "Equipamento vital — falha pode resultar em risco de vida para o paciente.",
    'Alta' => "Equipamento de elevado impacto — falha compromete seriamente o serviço.",
    'Média' => "Equipamento de impacto moderado — existem alternativas para suprir a falha.",
    'Baixa' => "Equipamento de apoio — falha com impacto mínimo no serviço.",
    default => "Criticidade: " . $criticidade,
};

$activeTab = $_GET['nav'] ?? 'visao-geral';

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 equipment-detailed-view">

        <!-- Titulo -->
        <div
            class="d-flex flex-column align-items-start gap-2 flex-md-row align-items-md-center gap-md-1 dashboard-title">
            <a href="equipment_list.php"
                class="d-flex align-items-center gap-2 text-decoration-none text-secondary opacity-75 hover-opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-left">
                    <path d="M19 12H5" />
                    <path d="m12 19-7-7 7-7" />
                </svg>
                <p class="fw-500 m-0">Equipamentos</p>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-right text-secondary opacity-50 d-none d-md-inline-block">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <h3 class="fw-600 text-primary mb-0"><?= htmlspecialchars($designacao) ?></h3>
        </div>

        <!-- Bento Card de Detalhes -->
        <div class="card bento-card padding-6 detailed-main-card d-grid gap-4">
            <!-- Icon Wrapper -->
            <div class="table-icon-wrapper equipment-icon-wrapper padding-8 detailed-main-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-box text-primary">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>

            <!-- Title & Badges Wrapper -->
            <div class="detailed-header-info d-flex flex-column flex-md-row justify-content-start gap-2">
                <h2 class="fw-700 text-primary mb-0"><?= htmlspecialchars($designacao) ?></h2>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="equipment-badge <?= $statusClass ?> equipment-badge-tooltip" data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="<?= htmlspecialchars($estadoTooltip) ?>"><?= htmlspecialchars($estado) ?></span>
                    <span class="equipment-badge <?= $critClass ?> equipment-badge-tooltip" data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="<?= htmlspecialchars($criticidadeTooltip) ?>"><?= htmlspecialchars($criticidade) ?></span>

                    <!-- QR Code Badge -->
                    <button class="equipment-badge equipment-badge-status-inactive btn-qr-code border-0 gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-qr-code">
                            <rect width="5" height="5" x="3" y="3" rx="1" />
                            <rect width="5" height="5" x="16" y="3" rx="1" />
                            <rect width="5" height="5" x="3" y="16" rx="1" />
                            <path d="M21 16h-3a2 2 0 0 0-2 2v3" />
                            <path d="M21 21v.01" />
                            <path d="M12 7v3a2 2 0 0 1-2 2H7" />
                            <path d="M3 12h.01" />
                            <path d="M12 3h.01" />
                            <path d="M12 16v.01" />
                            <path d="M16 12h1" />
                            <path d="M21 12v.01" />
                            <path d="M12 21h.01" />
                        </svg>
                        <span class="fw-600">QR Code</span>
                    </button>
                </div>
            </div>

            <!-- Row: Metadata Columns -->
            <div class="detailed-main-metadata d-flex flex-wrap gap-4 justify-content-between ">
                <!-- ID -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-tag">
                            <path d="M12 2H2v10l9.29 9.29c.94.94 2.56.94 3.5 0l5.5-5.5c.94-.94.94-2.56 0-3.5L12 2z" />
                            <path d="m7 7-.01-.01" />
                        </svg>
                        <label class="text-secondary fw-500">ID</label>
                    </div>
                    <p class="fw-700 text-primary m-0"><?= htmlspecialchars($codigoInterno) ?></p>
                </div>

                <!-- Marca -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-building-2">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h2" />
                            <path d="M18 18h2a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-2" />
                            <path d="M10 6h4" />
                            <path d="M10 10h4" />
                            <path d="M10 14h4" />
                            <path d="M10 18h4" />
                        </svg>
                        <label class="text-secondary fw-500">Marca</label>
                    </div>
                    <p class="fw-700 text-primary m-0"><?= htmlspecialchars($marcaNome) ?></p>
                </div>

                <!-- Modelo -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-box">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                            <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg>
                        <label class="text-secondary fw-500">Modelo</label>
                    </div>
                    <p class="fw-700 text-primary m-0"><?= htmlspecialchars($modelo) ?></p>
                </div>

                <!-- Localização -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-map-pin">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <label class="text-secondary fw-500">Localização</label>
                    </div>
                    <p class="fw-700 text-primary m-0"><?= htmlspecialchars($locNome) ?></p>
                </div>

                <!-- Tipo de Entrada -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-file-text">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                        <label class="text-secondary fw-500">Tipo de Entrada</label>
                    </div>
                    <p class="fw-700 text-primary m-0"><?= htmlspecialchars($tipoEntrada) ?></p>
                </div>

                <!-- Data de Fabrico -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <label class="text-secondary fw-500">Data de Fabrico</label>
                    </div>
                    <p class="fw-700 text-primary m-0"><?= $dataFabricoFormatada ?></p>
                </div>
            </div>
        </div>

        <!-- Menu de Navegação por Separadores (Tabs) -->
        <nav>
            <div class="bento-card d-flex gap-2 padding-1 flex-wrap" id="nav-tab" role="tablist">
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'visao-geral' ? 'active' : '' ?>"
                    id="nav-visao-geral-tab" data-bs-toggle="tab" data-bs-target="#nav-visao-geral" type="button"
                    role="tab" aria-controls="nav-visao-geral"
                    aria-selected="<?= $activeTab === 'visao-geral' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                        </path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <p class="d-none d-md-inline m-0">Visão Geral</p>
                </button>
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'documentos' ? 'active' : '' ?>"
                    id="nav-documentos-tab" data-bs-toggle="tab" data-bs-target="#nav-documentos" type="button"
                    role="tab" aria-controls="nav-documentos"
                    aria-selected="<?= $activeTab === 'documentos' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="M10 9H8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Documentos</p>
                </button>
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'fornecedores' ? 'active' : '' ?>"
                    id="nav-fornecedores-tab" data-bs-toggle="tab" data-bs-target="#nav-fornecedores" type="button"
                    role="tab" aria-controls="nav-fornecedores"
                    aria-selected="<?= $activeTab === 'fornecedores' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-building-2">
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h2" />
                        <path d="M18 18h2a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-2" />
                        <path d="M10 6h4" />
                        <path d="M10 10h4" />
                        <path d="M10 14h4" />
                        <path d="M10 18h4" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Fornecedores</p>
                </button>
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'garantias' ? 'active' : '' ?>"
                    id="nav-garantias-tab" data-bs-toggle="tab" data-bs-target="#nav-garantias" type="button" role="tab"
                    aria-controls="nav-garantias" aria-selected="<?= $activeTab === 'garantias' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shield">
                        <path
                            d="M20 13c0 5-8 7-8 7s-8-2-8-7V5a1 1 0 0 1 1-1c2.4 0 5.4-1.2 7-2.5 1.6 1.3 4.6 2.5 7 2.5a1 1 0 0 1 1 1v8z" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Garantias & Contratos</p>
                </button>
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'componentes' ? 'active' : '' ?>"
                    id="nav-componentes-tab" data-bs-toggle="tab" data-bs-target="#nav-componentes" type="button"
                    role="tab" aria-controls="nav-componentes"
                    aria-selected="<?= $activeTab === 'componentes' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-puzzle-icon lucide-puzzle">
                        <path
                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Componentes</p>
                </button>
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'manutencoes' ? 'active' : '' ?>"
                    id="nav-manutencoes-tab" data-bs-toggle="tab" data-bs-target="#nav-manutencoes" type="button"
                    role="tab" aria-controls="nav-manutencoes"
                    aria-selected="<?= $activeTab === 'manutencoes' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-wrench">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Manutenções</p>
                </button>
                <button
                    class="filter-bar-badge d-flex align-items-center gap-2 border-0 <?= $activeTab === 'auditoria' ? 'active' : '' ?>"
                    id="nav-auditoria-tab" data-bs-toggle="tab" data-bs-target="#nav-auditoria" type="button" role="tab"
                    aria-controls="nav-auditoria" aria-selected="<?= $activeTab === 'auditoria' ? 'true' : 'false' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-history">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Auditoria</p>
                </button>
            </div>
        </nav>

        <!-- Tab Content Panes -->
        <div class="tab-content w-100" id="nav-tabContent">
            <?php include 'includes/tab_overview.php'; ?>
            <?php include 'includes/tab_documents.php'; ?>
            <?php include 'includes/tab_suppliers.php'; ?>
            <?php include 'includes/tab_warranties.php'; ?>
            <?php include 'includes/tab_components.php'; ?>
            <?php include 'includes/tab_maintenances.php'; ?>
            <?php include 'includes/tab_audit.php'; ?>
        </div>

    </section>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 100;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($server_error)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($server_error) ?>
                </div>
                <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>