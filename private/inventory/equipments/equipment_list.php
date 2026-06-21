<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.equipments']);

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

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$estado_filter = isset($_GET['estado']) ? trim($_GET['estado']) : '';
$criticidade_filter = isset($_GET['criticidade']) ? trim($_GET['criticidade']) : '';
$categoria_filter = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'equipamento';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

$listaEquipamentos = [];
$categoriasDisponiveis = [];
$localizacoesDisponiveis = [];
$marcasDisponiveis = [];
$fornecedoresDisponiveis = [];

$totalEquipamentosAll = 0;
$totalEquipamentosFiltered = 0;
$totalPages = 1;

try {
    $ligacao = connect_to_db();

    // Obter Marcas para os dropdowns
    $stmtMarcas = execute_query("SELECT idMarca, nome FROM Marca WHERE ativo = 1 ORDER BY nome ASC", [], $ligacao);
    $marcasDisponiveis = $stmtMarcas->fetchAll(PDO::FETCH_ASSOC);

    // Obter Fornecedores
    $stmtFornecedores = execute_query("SELECT idFornecedor, nome, tipoFornecedor FROM Fornecedor WHERE ativo = 1 ORDER BY nome ASC", [], $ligacao);
    $fornecedoresDisponiveis = $stmtFornecedores->fetchAll(PDO::FETCH_ASSOC);

    // Obter Componentes para os dropdowns e checkboxes
    $stmtComponentes = execute_query(
        "SELECT c.idComponente, c.descricao, c.stock, cc.idCategoria 
         FROM Componente c 
         LEFT JOIN ComponenteCategoria cc ON c.idComponente = cc.idComponente 
         WHERE c.ativo = 1 
         ORDER BY c.descricao ASC",
        [],
        $ligacao
    );
    $componentesDisponiveis = $stmtComponentes->fetchAll(PDO::FETCH_ASSOC);

    // Obter Categorias para os dropdowns
    $stmtCategorias = execute_query(
        "SELECT * FROM CategoriaEquipamento WHERE ativo = 1 ORDER BY nome ASC",
        [],
        $ligacao
    );
    while ($row = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
        $categoriasDisponiveis[] = new Categoria(
            (string) $row['idCategoria'],
            $row['nome'],
            $row['codigoPrefix'],
            $row['descricao'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao'])
        );
    }

    // Obter Localizações para os dropdowns
    $stmtLocalizacoes = execute_query(
        "SELECT 
            l.idLocalizacao,
            l.idServico,
            e.nome AS edificioNome,
            p.nome AS pisoNome,
            s.nome AS servicoNome,
            l.nomeSala AS salaNome
         FROM Localizacao l
         JOIN Servico s ON l.idServico = s.idServico
         JOIN Piso p ON s.idPiso = p.idPiso
         JOIN Edificio e ON p.idEdificio = e.idEdificio
         WHERE l.ativo = 1 AND s.ativo = 1 AND p.ativo = 1 AND e.ativo = 1
         ORDER BY e.nome, p.nome, s.nome, l.nomeSala ASC",
        [],
        $ligacao
    );
    while ($row = $stmtLocalizacoes->fetch(PDO::FETCH_ASSOC)) {
        $nomeCompleto = $row['edificioNome'] . ', ' . $row['pisoNome'] . ', ' . $row['servicoNome'] . ', ' . $row['salaNome'];
        $localizacoesDisponiveis[] = new Localizacao(
            (int) $row['idLocalizacao'],
            (int) $row['idServico'],
            $nomeCompleto
        );
    }

    // Preparar as queries de filtragem
    $whereClauses = ["e.ativo = 1", "e.arquivado = 0"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereClauses[] = "e.idEquipamento = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereClauses[] = "(e.idEquipamento = :searchExact OR e.designacao LIKE :search OR e.numeroSerie LIKE :search OR m.nome LIKE :search OR e.modelo LIKE :search OR e.codigoInterno LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = "%$search_query%";
        } else {
            $whereClauses[] = "(e.designacao LIKE :search OR e.numeroSerie LIKE :search OR m.nome LIKE :search OR e.modelo LIKE :search OR e.codigoInterno LIKE :search)";
            $params['search'] = "%$search_query%";
        }
    }
    if ($estado_filter !== '') {
        $whereClauses[] = "e.estadoAtual = :estado";
        $params['estado'] = $estado_filter;
    }
    if ($criticidade_filter !== '') {
        $whereClauses[] = "e.criticidade = :criticidade";
        $params['criticidade'] = $criticidade_filter;
    }
    if ($categoria_filter !== '') {
        $whereClauses[] = "c.nome = :categoria";
        $params['categoria'] = $categoria_filter;
    }

    $whereSQL = implode(" AND ", $whereClauses);

    // Contar total de equipamentos (sem filtros)
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM Equipamento WHERE ativo = 1 AND arquivado = 0", [], $ligacao);
    $totalEquipamentosAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total filtrado
    $countSql = "SELECT COUNT(e.idEquipamento) as total 
                 FROM Equipamento e 
                 LEFT JOIN Marca m ON e.idMarca = m.idMarca 
                 LEFT JOIN CategoriaEquipamento c ON e.idCategoria = c.idCategoria 
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalEquipamentosFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalEquipamentosFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'equipamento' => 'e.designacao',
        'categoria' => 'c.nome',
        'localizacao' => 'l.nomeSala',
        'estado' => 'e.estadoAtual',
        'criticidade' => 'e.criticidade'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'e.designacao';
    $sort_dir = strtoupper($dir_param);

    if ($sort_param === 'localizacao') {
        $sort_field = 'e.idLocalizacao';
    }

    // Obter Equipamentos com LIMIT, OFFSET e ORDER BY
    $dataSql = "SELECT e.*, m.nome as marcaNome 
                FROM Equipamento e 
                LEFT JOIN Marca m ON e.idMarca = m.idMarca 
                LEFT JOIN CategoriaEquipamento c ON e.idCategoria = c.idCategoria 
                WHERE $whereSQL 
                ORDER BY $sort_field $sort_dir 
                LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmtEquipamentos = execute_query($dataSql, $params, $ligacao);

    while ($row = $stmtEquipamentos->fetch(PDO::FETCH_ASSOC)) {
        $listaEquipamentos[] = new Equipamento(
            (string) $row['idEquipamento'],
            $row['idCategoria'] ? (string) $row['idCategoria'] : null,
            $row['codigoInterno'],
            $row['designacao'],
            $row['idMarca'] ? (string) $row['idMarca'] : null,
            $row['modelo'],
            $row['numeroSerie'],
            $row['dataAquisicao'] ? new DateTime($row['dataAquisicao']) : null,
            $row['dataFabrico'] ? new DateTime($row['dataFabrico']) : null,
            (float) $row['custoAquisicao'],
            TipoEntrada::from($row['tipoEntrada']),
            EstadoEquipamento::from($row['estadoAtual']),
            CriticidadeEquipamento::from($row['criticidade']),
            $row['observacoes'] ?? '',
            $row['idLocalizacao'] ? (string) $row['idLocalizacao'] : null,
            (bool) $row['arquivado'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao']),
            $row['marcaNome']
        );
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar dados: " . $e->getMessage();
}

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="padding-6 gap-6 d-flex flex-column padding-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <div class="d-flex flex-column gap-1">
                <h1>Lista de Equipamentos</h1>
                <p class="text-secondary fw-400"><?= $totalEquipamentosAll ?> equipamentos cadastrados
                </p>
            </div>
            <div class="d-flex gap-2">
                <?php if (tem_permissao('equipments.create')): ?>
                    <button id="btn-open-create-equipment-modal" class="btn btn-primary btn-glowing gap-2"
                        data-bs-toggle="modal" data-bs-target="#equipment-creation-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus-icon lucide-plus">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Criar Equipamento
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div
            class="bento-card padding-4 gap-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
            <form action="" method="GET" style="display: contents;">
                <div class="form-item position-relative flex-grow-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" name="search" id="search-input-field"
                        placeholder="Pesquisar por nome, nº série, marca, modelo..."
                        value="<?= htmlspecialchars($search_query) ?>">
                </div>
                <div class="d-flex gap-2 equipment-list-search-bar-filters flex-column flex-md-row">
                    <select class="form-select" name="estado" aria-label="Filtro Estado" onchange="this.form.submit()">
                        <option value="" <?= $estado_filter === '' ? 'selected' : '' ?>>Estado</option>
                        <?php foreach (EstadoEquipamento::cases() as $estado): ?>
                            <option value="<?= htmlspecialchars($estado->value) ?>" <?= $estado_filter === $estado->value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($estado->value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-select" name="criticidade" aria-label="Filtro Criticidade"
                        onchange="this.form.submit()">
                        <option value="" <?= $criticidade_filter === '' ? 'selected' : '' ?>>Criticidade</option>
                        <?php foreach (CriticidadeEquipamento::cases() as $criticidade): ?>
                            <option value="<?= htmlspecialchars($criticidade->value) ?>"
                                <?= $criticidade_filter === $criticidade->value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($criticidade->value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-select" name="categoria" aria-label="Filtro Categoria"
                        onchange="this.form.submit()">
                        <option value="" <?= $categoria_filter === '' ? 'selected' : '' ?>>Categoria</option>
                        <?php foreach ($categoriasDisponiveis as $catDisp): ?>
                            <option value="<?= htmlspecialchars($catDisp->getNome()) ?>"
                                <?= $categoria_filter === $catDisp->getNome() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($catDisp->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary d-none">Filtrar</button>
                </div>
            </form>
        </div>

        <?php if ($totalEquipamentosAll === 0): ?>
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell-off-icon lucide-bell-off">
                        <path d="M9 10h.01" />
                        <path d="M15 10h.01" />
                        <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="fw-700 m-0">Sem Equipamentos</h3>
                    <p class="text-secondary m-0">De momento não existe nenhum equipamento.</p>
                </div>
            </div>
        <?php elseif (empty($listaEquipamentos)): ?>
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-x">
                        <path d="m13.5 8.5-5 5" />
                        <path d="m8.5 8.5 5 5" />
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="fw-700 m-0">Sem resultados</h3>
                    <p class="text-secondary m-0">Nenhum registo encontrado correspondente à sua pesquisa.</p>
                </div>
            </div>
        <?php else: ?>


            <!-- Tabela -->
            <div class="bento-card w-100 p-0 border-0">
                <div class="datatable-wrapper no-footer sortable fixed-columns">
                    <div class="datatable-container w-100 overflow-auto position-relative">
                        <?php
                        // Função auxiliar para criar links de ordenação
                        $buildSortUrl = function ($column) use ($search_query, $estado_filter, $criticidade_filter, $categoria_filter, $sort_param, $dir_param) {
                            $params = [];
                            if ($search_query !== '')
                                $params['search'] = $search_query;
                            if ($estado_filter !== '')
                                $params['estado'] = $estado_filter;
                            if ($criticidade_filter !== '')
                                $params['criticidade'] = $criticidade_filter;
                            if ($categoria_filter !== '')
                                $params['categoria'] = $categoria_filter;

                            $params['sort'] = $column;
                            // Inverte a direção se estiver a clicar na mesma coluna, senão default para asc
                            $params['dir'] = ($sort_param === $column && $dir_param === 'asc') ? 'desc' : 'asc';

                            return '?' . http_build_query($params);
                        };

                        // Funço auxiliar para mostrar o ícone/seta
                        $getSortIcon = function ($column) use ($sort_param, $dir_param) {
                            if ($sort_param !== $column)
                                return '';
                            return $dir_param === 'asc' ? ' ↑' : ' ↓';
                        };
                        ?>
                        <table id="equipmentsTable" class="heba-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('equipamento') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">EQUIPAMENTO
                                            <?= $getSortIcon('equipamento') ?>
                                        </a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('categoria') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CATEGORIA
                                            <?= $getSortIcon('categoria') ?>
                                        </a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('localizacao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">LOCALIZAÇÃO
                                            <?= $getSortIcon('localizacao') ?>
                                        </a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('estado') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">ESTADO
                                            <?= $getSortIcon('estado') ?>
                                        </a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('criticidade') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CRITICIDADE
                                            <?= $getSortIcon('criticidade') ?>
                                        </a>
                                    </th>
                                    <?php if (tem_permissao('equipments.edit') || tem_permissao('equipments.delete') || tem_permissao('equipments.archive')): ?>
                                        <th class="text-end">AÇÕES</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaEquipamentos as $equipamento): ?>
                                    <?php
                                    $encryptedEqId = aes_encrypt((string) $equipamento->getIdEquipamento());

                                    $catNome = "Sem Categoria";
                                    $catDescricao = "";
                                    $idCat = $equipamento->getIdCategoria();
                                    if ($idCat !== null) {
                                        foreach ($categoriasDisponiveis as $catDisp) {
                                            if ($catDisp->getIdCategoria() === $idCat) {
                                                $catNome = $catDisp->getNome();
                                                $catDescricao = $catDisp->getDescricao();
                                                break;
                                            }
                                        }
                                    }

                                    $locNome = "Desconhecida";
                                    $idLoc = $equipamento->getIdLocalizacao();
                                    if ($idLoc !== null) {
                                        foreach ($localizacoesDisponiveis as $locDisp) {
                                            if ((string) $locDisp->getIdLocalizacao() === $idLoc) {
                                                $locNome = $locDisp->getNomeSala(); // A localizacao tem getNomeSala que na verdade usamos para passar a string inteira
                                                break;
                                            }
                                        }
                                    }

                                    // Estado Badge Class
                                    $estado = $equipamento->getEstadoAtual()->value;
                                    $statusClass = match ($estado) {
                                        EstadoEquipamento::EM_MANUTENCAO->value, EstadoEquipamento::EM_CALIBRACAO->value => 'equipment-badge-status-maintenance',
                                        EstadoEquipamento::INATIVO->value, EstadoEquipamento::EM_QUARENTENA->value => 'equipment-badge-status-inactive',
                                        EstadoEquipamento::ABATIDO->value => 'equipment-badge-status-abated',
                                        default => 'equipment-badge-status-active',
                                    };

                                    // Criticidade Badge Class
                                    $criticidade = $equipamento->getCriticidade()->value;
                                    $critClass = match ($criticidade) {
                                        CriticidadeEquipamento::MEDIA->value => 'equipment-badge-criticality-medium',
                                        CriticidadeEquipamento::ALTA->value => 'equipment-badge-criticality-high',
                                        CriticidadeEquipamento::CRITICO->value => 'equipment-badge-criticality-critical',
                                        default => 'equipment-badge-criticality-low',
                                    };

                                    $marcaModeloString = trim($equipamento->getMarcaNome() . ' ' . $equipamento->getModelo());
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="table-icon-wrapper padding-2 d-flex align-items-center justify-content-center flex-shrink-0 equipment-icon-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-box text-primary">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                                        </path>
                                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                    </svg>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="detailed_view.php?id=<?= htmlspecialchars($encryptedEqId) ?>"
                                                        class="text-primary">
                                                        <p class="equipment-title fw-700 mb-0">
                                                            <?= htmlspecialchars($equipamento->getDesignacao()) ?>
                                                        </p>
                                                        <span class="visually-hidden">
                                                            <?= htmlspecialchars($encryptedEqId) ?>
                                                        </span>
                                                    </a>
                                                    <span class="equipment-subtitle text-secondary fw-400">
                                                        <?= htmlspecialchars($marcaModeloString) ?>
                                                        &bull;
                                                        <?= htmlspecialchars($equipamento->getNumeroSerie()) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="equipment-category">
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="<?= htmlspecialchars($catDescricao) ?>">
                                                <?= htmlspecialchars($catNome) ?>
                                            </span>
                                        </td>
                                        <td class="equipment-location">
                                            <?= htmlspecialchars($locNome) ?>
                                        </td>
                                        <td class="equipment-status">
                                            <?php
                                            $estadoTooltip = match ($estado) {
                                                'Ativo' => "Equipamento operacional e disponível para uso clínico.",
                                                'Em manutenção' => "Equipamento temporariamente indisponível por estar em manutenção preventiva ou corretiva.",
                                                'Inativo' => "Equipamento fora de serviço temporariamente.",
                                                'Em calibração' => "Equipamento em processo de calibração para garantir precisão.",
                                                'Em Quarentena' => "Equipamento retido aguardando inspeção ou decisão técnica.",
                                                'Abatido' => "Equipamento desativado definitivamente (em fim de vida útil).",
                                                default => "Estado: " . $estado,
                                            };
                                            ?>
                                            <span
                                                class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  <?= $statusClass ?> equipment-badge-tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="<?= htmlspecialchars($estadoTooltip) ?>">
                                                <?= htmlspecialchars($estado) ?>
                                            </span>
                                        </td>
                                        <td class="equipment-criticality">
                                            <?php
                                            $criticidadeTooltip = match ($criticidade) {
                                                'Crítico' => "Equipamento vital — falha pode resultar em risco de vida para o paciente.",
                                                'Alta' => "Equipamento importante — falha impacta significativamente o serviço clínico.",
                                                'Média' => "Equipamento de impacto moderado — existem alternativas para suprir a falha.",
                                                'Baixa' => "Equipamento de apoio — falha com impacto mínimo no serviço.",
                                                default => "Criticidade: " . $criticidade,
                                            };
                                            ?>
                                            <span
                                                class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  <?= $critClass ?> equipment-badge-tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="<?= htmlspecialchars($criticidadeTooltip) ?>">
                                                <?= htmlspecialchars($criticidade) ?>
                                            </span>
                                        </td>
                                        <?php if (tem_permissao('equipments.edit') || tem_permissao('equipments.delete') || tem_permissao('equipments.archive')): ?>
                                            <td class="text-end equipment-actions">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="1" />
                                                            <circle cx="19" cy="12" r="1" />
                                                            <circle cx="5" cy="12" r="1" />
                                                        </svg>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu padding-2">
                                                        <?php if (tem_permissao('equipments.edit')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-primary"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#equipment-edit-modal-<?= htmlspecialchars($encryptedEqId) ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                        class="lucide lucide-pencil">
                                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                                        <path d="m15 5 4 4" />
                                                                    </svg>
                                                                    Editar
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if (tem_permissao('equipments.archive')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#equipment-archive-modal-<?= htmlspecialchars($encryptedEqId) ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                        class="lucide lucide-archive">
                                                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                                        <path d="M10 12h4" />
                                                                    </svg>
                                                                    Arquivar
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if (tem_permissao('equipments.delete')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#equipment-delete-modal-<?= htmlspecialchars($encryptedEqId) ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                        class="lucide lucide-trash-2">
                                                                        <path d="M3 6h18" />
                                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                                        <line x1="10" x2="10" y1="11" y2="17" />
                                                                        <line x1="14" x2="14" y1="11" y2="17" />
                                                                    </svg>
                                                                    Apagar
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>



                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação HTML Customizada -->
                    <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                        <div class="datatable-info">
                            A mostrar
                            <?= $totalEquipamentosFiltered > 0 ? $offset + 1 : 0 ?>–
                            <?= min($offset + $items_per_page, $totalEquipamentosFiltered) ?>
                            de
                            <?= $totalEquipamentosFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                // Função auxiliar para criar a query string mantendo os outros filtros
                                $buildQueryString = function ($newPage) use ($search_query, $estado_filter, $criticidade_filter, $categoria_filter, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($estado_filter !== '')
                                        $params['estado'] = $estado_filter;
                                    if ($criticidade_filter !== '')
                                        $params['criticidade'] = $criticidade_filter;
                                    if ($categoria_filter !== '')
                                        $params['categoria'] = $categoria_filter;
                                    if ($sort_param !== 'equipamento')
                                        $params['sort'] = $sort_param;
                                    if ($dir_param !== 'asc')
                                        $params['dir'] = $dir_param;
                                    return '?' . http_build_query($params);
                                };
                                ?>

                                <?php if ($current_page > 1): ?>
                                    <li class="datatable-pagination-list-item pager"><a
                                            href="<?= $buildQueryString($current_page - 1) ?>">‹</a></li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $current_page - 2); $i <= min($totalPages, $current_page + 2); $i++): ?>
                                    <li
                                        class="datatable-pagination-list-item <?= $i === $current_page ? 'datatable-active' : '' ?>">
                                        <a href="<?= $buildQueryString($i) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($current_page < $totalPages): ?>
                                    <li class="datatable-pagination-list-item pager"><a
                                            href="<?= $buildQueryString($current_page + 1) ?>">›</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Equipamento -->
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="equipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Novo
                        Equipamento</h2>
                    <span class="text-secondary fw-400">Preencha os dados para registar um novo
                        equipamento.</span>
                </div>

                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x stroke-secondary">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>

            </div>

            <!-- Body do Modal com scroll automático -->
            <div class="modal-body p-0">
                <form id="equipment-creation-form" action="equipments-crud/create-equipment.php" method="POST"
                    enctype="multipart/form-data" class="d-flex flex-column h-100 m-0 w-100">
                    <!-- Conteudo Pagina 1 -->
                    <div id="modal-page-1" class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                        <div class="d-flex flex-row gap-3 align-items-center">
                            <div
                                class="d-flex flex-row equipment-creation-modal-page current-page justify-content-start align-items-center gap-3 padding-3">
                                <h3 class="text-white padding-2 d-flex align-items-center justify-content-center">1</h3>
                                <p class="text-primary-700">Dados gerais</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-right-icon lucide-chevron-right stroke-secondary">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                            <div
                                class="d-flex flex-row equipment-creation-modal-page justify-content-start align-items-center gap-3 padding-3">
                                <h3 class="text-secondary padding-2 d-flex align-items-center justify-content-center">2
                                </h3>
                                <p class="text-secondary">Relações & Docs</p>
                            </div>
                        </div>

                        <!-- Row 1: Codigo Interno e Categoria -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="equipment-code">Código Interno</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="equipment-code" name="equipment-code" placeholder="Ex: EQ-001"
                                    maxlength="20" required>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="equipment-category">Categoria</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <div class="d-flex w-100 gap-2">
                                    <select id="equipment-category" name="equipment-category" class="form-select mw-0"
                                        required>
                                        <option value="" disabled selected>Selecionar categoria</option>
                                        <?php foreach ($categoriasDisponiveis as $catDisp): ?>
                                            <option value="<?= htmlspecialchars($catDisp->getIdCategoria()) ?>">
                                                <?= htmlspecialchars($catDisp->getNome()) ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                    <a href="<?= BASE_URL ?>private/inventory/categories.php" target="_blank"
                                        class="btn btn-primary-outline btn-small w-auto text-nowrap gap-2 text-decoration-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-external-link-icon lucide-external-link">
                                            <path d="M15 3h6v6" />
                                            <path d="M10 14 21 3" />
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                        </svg>
                                        Criar novo
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Numero de Serie e Designação / Nome do Equipamento -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="equipment-serial">Número de Série</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="equipment-serial" name="equipment-serial"
                                    placeholder="Ex: DRG-V500-7239" required>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="equipment-name">Designação / Nome do Equipamento</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="equipment-name" name="equipment-name"
                                    placeholder="Ex: Ventilador Dräger V500" required>
                            </div>
                        </div>

                        <!-- Row 3: Marca e Modelo -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <label for="equipment-brand">Marca</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <select id="equipment-brand" name="equipment-brand" class="form-select" required>
                                    <option value="" disabled selected>Selecionar marca</option>
                                    <?php foreach ($marcasDisponiveis as $marca): ?>
                                        <option value="<?= htmlspecialchars($marca['idMarca']) ?>">
                                            <?= htmlspecialchars($marca['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100">
                                <label for="equipment-model">Modelo</label>
                                <input type="text" id="equipment-model" name="equipment-model"
                                    placeholder="Ex: Evita V500">
                            </div>
                        </div>

                        <!-- Row 4: Datas -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="equipment-purchase-date">Data de Aquisição</label>
                                <input type="date" id="equipment-purchase-date" name="equipment-purchase-date"
                                    placeholder="dd/mm/yyyy">
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="equipment-manufacture-date">Data de Fabrico</label>
                                <input type="date" id="equipment-manufacture-date" name="equipment-manufacture-date"
                                    placeholder="dd/mm/yyyy">
                            </div>
                        </div>

                        <!-- Row 5: Custos e Entradas -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="equipment-cost">Custo de Aquisição (€)</label>
                                <input type="number" id="equipment-cost" name="equipment-cost" placeholder="0.00"
                                    step="0.01" min="0">
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="equipment-entry-type">Tipo de Entrada</label>
                                <select id="equipment-entry-type" name="equipment-entry-type" class="form-select">
                                    <option value="" disabled selected>Selecionar...</option>
                                    <?php foreach (TipoEntrada::cases() as $tipo): ?>
                                        <option value="<?= $tipo->value ?>"><?= $tipo->value ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <!-- Row 6: Classificação e Estado -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="equipment-criticality">Criticidade</label>
                                <select id="equipment-criticality" name="equipment-criticality" class="form-select">
                                    <?php foreach (CriticidadeEquipamento::cases() as $crit): ?>
                                        <option value="<?= $crit->value ?>" <?= $crit === CriticidadeEquipamento::MEDIA ? 'selected' : '' ?>>
                                            <?= $crit->value ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="equipment-status">Estado Atual</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <select id="equipment-status" name="equipment-status" class="form-select" required>
                                    <?php foreach (EstadoEquipamento::cases() as $estado): ?>
                                        <option value="<?= $estado->value ?>" <?= $estado === EstadoEquipamento::ATIVO ? 'selected' : '' ?>>
                                            <?= $estado->value ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <!-- Row 7: Localização -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="equipment-location">Localização</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <select id="equipment-location" name="equipment-location" class="form-select" required>
                                <option value="" disabled selected>Selecionar localização...</option>
                                <?php foreach ($localizacoesDisponiveis as $loc): ?>
                                    <option value="<?= htmlspecialchars($loc->getIdLocalizacao()) ?>">
                                        <?= htmlspecialchars($loc->getNomeSala()) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <!-- Row 8: Observações -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="equipment-notes">Observações</label>
                            <textarea id="equipment-notes" name="equipment-notes" class="form-control" rows="3"
                                placeholder="Insira observações relevantes sobre o equipamento..."></textarea>
                        </div>

                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillEquipment('Ventilador Evita V500', 'Compra', 'Alta', 'Ativo')">
                                    Ventilador (Alta Crit)
                                </button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillEquipment('Bomba Infusora Alaris', 'Doação', 'Média', 'Ativo')">
                                    Bomba (Média Crit)
                                </button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillEquipment('Monitor Sinais Vitais X3', 'Empréstimo', 'Baixa', 'Ativo')">
                                    Monitor (Baixa Crit)
                                </button>
                            </div>
                            <script>
                                function prefillEquipment(nome, entrada, criticidade, estado) {
                                    document.getElementById('equipment-code').value = 'EQ-' + Math.floor(Math.random() * 10000);
                                    document.getElementById('equipment-name').value = nome;
                                    document.getElementById('equipment-model').value = 'Modelo Standard';
                                    document.getElementById('equipment-serial').value = 'SN-' + Math.floor(Math.random() * 10000);
                                    document.getElementById('equipment-purchase-date').value = '2023-01-01';
                                    document.getElementById('equipment-manufacture-date').value = '2022-01-01';
                                    document.getElementById('equipment-cost').value = '5000.00';
                                    document.getElementById('equipment-notes').value = 'Equipamento gerado automaticamente para testes de debug.';

                                    const catSelect = document.getElementById('equipment-category');
                                    if (catSelect && catSelect.options.length > 1) catSelect.selectedIndex = 1;

                                    const brandSelect = document.getElementById('equipment-brand');
                                    if (brandSelect && brandSelect.options.length > 1) brandSelect.selectedIndex = 1;

                                    const entryType = document.getElementById('equipment-entry-type');
                                    if (entryType) entryType.value = entrada;

                                    const crit = document.getElementById('equipment-criticality');
                                    if (crit) crit.value = criticidade;

                                    const stat = document.getElementById('equipment-status');
                                    if (stat) stat.value = estado;

                                    const locSelect = document.getElementById('equipment-location');
                                    if (locSelect && locSelect.options.length > 1) {
                                        locSelect.selectedIndex = 1;
                                    }

                                    // Disparar eventos para garantir que as validações do JS atualizam o estado do botão
                                    document.querySelectorAll('#equipment-creation-form input, #equipment-creation-form select, #equipment-creation-form textarea').forEach(el => {
                                        el.dispatchEvent(new Event('change', { bubbles: true }));
                                        el.dispatchEvent(new Event('input', { bubbles: true }));
                                    });
                                }
                            </script>
                        <?php endif; ?>

                        <!-- Linha de Botões -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-auto">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="btn-next-page" class="btn btn-primary btn-glowing gap-1" disabled>
                                Próximo
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-right">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Conteudo Pagina 2 -->
                    <div id="modal-page-2"
                        class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column d-none">
                        <div class="d-flex flex-row gap-3 align-items-center">
                            <div
                                class="d-flex flex-row equipment-creation-modal-page justify-content-start align-items-center gap-3 padding-3 page-completed">
                                <h3 class="text-secondary padding-2 d-flex align-items-center justify-content-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check stroke-white">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </h3>
                                <p>Dados gerais</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-right-icon lucide-chevron-right stroke-secondary">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                            <div
                                class="d-flex flex-row equipment-creation-modal-page current-page justify-content-start align-items-center gap-3 padding-3">
                                <h3 class="text-white padding-2 d-flex align-items-center justify-content-center">2
                                </h3>
                                <p class="text-primary-700">Relações & Docs</p>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-grow-1 w-100 pt-4 gap-6">
                            <!-- Seccao 1: Fornecedores -->
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2 stroke-primary-500">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                    <h3>Fornecedores</h3>
                                </div>

                                <div class="d-flex flex-column gap-4 w-100">
                                    <!-- Fabricante -->
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label for="equipment-manufacturer">Fabricante</label>
                                        </div>
                                        <div class="d-flex w-100 gap-2">
                                            <select id="equipment-manufacturer" name="equipment-manufacturer"
                                                class="form-select mw-0">
                                                <option value="" selected>Sem fabricante</option>
                                                <?php foreach ($fornecedoresDisponiveis as $forn): ?>
                                                    <?php if ($forn['tipoFornecedor'] === 'Fabricante'): ?>
                                                        <option value="<?= htmlspecialchars($forn['idFornecedor']) ?>">
                                                            <?= htmlspecialchars($forn['nome']) ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>

                                            </select>
                                            <a href="<?= BASE_URL ?>private/entities/suppliers.php" target="_blank"
                                                class="btn btn-primary-outline btn-small w-auto text-nowrap gap-2 text-decoration-none"
                                                title="Criar novo fabricante">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-external-link-icon lucide-external-link">
                                                    <path d="M15 3h6v6" />
                                                    <path d="M10 14 21 3" />
                                                    <path
                                                        d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                                </svg>
                                                Criar Novo
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Distribuidor -->
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label for="equipment-distributor">Distribuidor</label>
                                        </div>
                                        <div class="d-flex w-100 gap-2">
                                            <select id="equipment-distributor" name="equipment-distributor"
                                                class="form-select mw-0">
                                                <option value="" selected>Sem distribuidor</option>
                                                <?php foreach ($fornecedoresDisponiveis as $forn): ?>
                                                    <?php if ($forn['tipoFornecedor'] === 'Distribuidor'): ?>
                                                        <option value="<?= htmlspecialchars($forn['idFornecedor']) ?>">
                                                            <?= htmlspecialchars($forn['nome']) ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>

                                            </select>
                                            <a href="<?= BASE_URL ?>private/entities/suppliers.php" target="_blank"
                                                class="btn btn-primary-outline btn-small w-auto text-nowrap gap-2 text-decoration-none"
                                                title="Criar novo distribuidor">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-external-link-icon lucide-external-link">
                                                    <path d="M15 3h6v6" />
                                                    <path d="M10 14 21 3" />
                                                    <path
                                                        d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                                </svg>
                                                Criar Novo
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Assistentes Técnicos -->
                                    <div class="d-flex flex-column form-item gap-2 w-100">
                                        <div class="d-flex gap-1">
                                            <label>Assistentes Técnicos (Múltiplos)</label>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <span class="text-muted multi-select-count-label">0 selecionado(s)</span>
                                            <a href="<?= BASE_URL ?>private/entities/suppliers.php" target="_blank"
                                                class="text-primary d-flex align-items-center gap-1 text-decoration-none fw-bold">
                                                <p class="text-primary-500 d-flex align-items-center gap-1 fw-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-external-link">
                                                        <path d="M15 3h6v6"></path>
                                                        <path d="M10 14 21 3"></path>
                                                        <path
                                                            d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                        </path>
                                                    </svg>
                                                    Criar Novo
                                                </p>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column gap-3 padding-3 multi-select-form">
                                            <?php
                                            $hasAssistants = false;
                                            foreach ($fornecedoresDisponiveis as $forn):
                                                if ($forn['tipoFornecedor'] === 'Assistência Técnica'):
                                                    $hasAssistants = true;
                                                    ?>
                                                    <div class="form-check d-flex align-items-center gap-2 m-0">
                                                        <input class="form-check-input m-0" type="checkbox"
                                                            name="equipment-tech-assistants[]"
                                                            value="<?= htmlspecialchars($forn['idFornecedor']) ?>"
                                                            id="check-ta-<?= $forn['idFornecedor'] ?>">
                                                        <label class="form-check-label text-secondary m-0 multi-select-label"
                                                            for="check-ta-<?= $forn['idFornecedor'] ?>">
                                                            <?= htmlspecialchars($forn['nome']) ?>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach;

                                            if (!$hasAssistants):
                                                ?>
                                                <span class="text-muted fst-italic">Nenhum assistente técnico
                                                    disponível.</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Fornecedores de Consumíveis -->
                                    <div class="d-flex flex-column form-item gap-2 w-100">
                                        <div class="d-flex gap-1">
                                            <label>Fornecedores de Consumíveis (múltiplos)</label>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <span class="text-muted multi-select-count-label">0 selecionado(s)</span>
                                            <a href="<?= BASE_URL ?>private/entities/suppliers.php" target="_blank"
                                                class="text-primary d-flex align-items-center gap-1 text-decoration-none fw-bold">
                                                <p class="text-primary-500 d-flex align-items-center gap-1 fw-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-external-link">
                                                        <path d="M15 3h6v6"></path>
                                                        <path d="M10 14 21 3"></path>
                                                        <path
                                                            d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                        </path>
                                                    </svg>
                                                    Criar Novo
                                                </p>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column gap-3 padding-3 multi-select-form">
                                            <?php
                                            $hasConsumables = false;
                                            foreach ($fornecedoresDisponiveis as $forn):
                                                if ($forn['tipoFornecedor'] === 'Consumíveis'):
                                                    $hasConsumables = true;
                                                    ?>
                                                    <div class="form-check d-flex align-items-center gap-2 m-0">
                                                        <input class="form-check-input m-0" type="checkbox"
                                                            name="equipment-consumable-suppliers[]"
                                                            value="<?= htmlspecialchars($forn['idFornecedor']) ?>"
                                                            id="check-con-<?= $forn['idFornecedor'] ?>">
                                                        <label class="form-check-label text-secondary m-0 multi-select-label"
                                                            for="check-con-<?= $forn['idFornecedor'] ?>">
                                                            <?= htmlspecialchars($forn['nome']) ?>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach;

                                            if (!$hasConsumables):
                                                ?>
                                                <span class="text-muted fst-italic">Nenhum fornecedor de consumíveis
                                                    disponível.</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Seccao 2: Componentes -->
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-2 components-header flex-column flex-md-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-puzzle-icon lucide-puzzle stroke-primary-500">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                    <h3>Componentes</h3>
                                    <span class="text-muted">(filtrados pela categoria selecionada)</span>
                                </div>

                                <div class="d-flex flex-column gap-4 w-100">
                                    <div class="d-flex flex-column form-item gap-2 w-100">
                                        <div class="d-flex flex-column gap-3 padding-4 multi-select-form">
                                            <span id="no-components-msg" class="text-muted d-none">Nenhum componente
                                                disponível para a categoria selecionada.</span>
                                            <?php foreach ($componentesDisponiveis as $comp):
                                                $stock = (int) $comp['stock'];
                                                $disabled = $stock <= 0 ? 'disabled' : '';
                                                $minValue = $stock <= 0 ? 0 : 1;
                                                ?>
                                                <div class="d-flex align-items-start gap-3 w-100 multi-select-item <?= $stock <= 0 ? 'opacity-50' : '' ?>"
                                                    data-category-id="<?= htmlspecialchars($comp['idCategoria'] ?? '') ?>">
                                                    <div class="form-check m-0 pt-1">
                                                        <input class="form-check-input m-0" type="checkbox"
                                                            name="equipment-components[]"
                                                            value="<?= htmlspecialchars($comp['idComponente']) ?>"
                                                            id="check-comp-<?= htmlspecialchars($comp['idComponente']) ?>"
                                                            <?= $disabled ?>>
                                                    </div>
                                                    <div class="d-flex flex-column gap-2 flex-grow-1">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center w-100 multi-select-details-rowflex-column flex-md-row">
                                                            <div
                                                                class="d-flex align-items-center gap-3 multi-select-details-row flex-column flex-md-row">
                                                                <label
                                                                    for="check-comp-<?= htmlspecialchars($comp['idComponente']) ?>"
                                                                    class="fw-400 m-0 cursor-pointer"><?= htmlspecialchars($comp['descricao']) ?></label>
                                                                <span
                                                                    class="text-muted multi-select-stock-badge <?= $stock <= 0 ? 'text-error' : '' ?>">Em
                                                                    Stock:
                                                                    <?= htmlspecialchars($comp['stock']) ?> un.</span>
                                                            </div>
                                                            <!-- Quantidade -->
                                                            <div
                                                                class="d-flex align-items-center gap-2 multi-select-qty-container d-none">
                                                                <span
                                                                    class="text-secondary multi-select-qty-label">Qtd:</span>
                                                                <input type="number"
                                                                    name="equipment-components-qty[<?= htmlspecialchars($comp['idComponente']) ?>]"
                                                                    class="form-control text-center p-0 multi-select-qty-input"
                                                                    value="<?= $minValue ?>" min="<?= $minValue ?>"
                                                                    max="<?= $stock ?>" <?= $disabled ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Seccao 3: Manutencao & Garantia -->
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-wrench-icon lucide-wrench stroke-primary-500">
                                        <path
                                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />
                                    </svg>
                                    <h3>Manutenção & Garantia </h3>
                                </div>

                                <div class="d-flex flex-column gap-2 w-100">
                                    <div class="d-flex gap-4 w-100">
                                        <div class="d-flex flex-column form-item w-100 mw-0">
                                            <label for="last-maintenance-start-date">Data de Início Última
                                                Manutenção</label>
                                            <input type="date" id="last-maintenance-start-date"
                                                name="last-maintenance-start-date" placeholder="dd/mm/yyyy">
                                        </div>

                                        <div class="d-flex flex-column form-item w-100 mw-0">
                                            <label for="last-maintenance-end-date">Data de Fim Última
                                                Manutenção</label>
                                            <input type="date" id="last-maintenance-end-date"
                                                name="last-maintenance-end-date" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                    <span class="text-muted fst-italic">A configuração completa de garantias e
                                        contratos de
                                        assistência será efetuada na aba de "Manutenções & Garantias" após a criação
                                        do
                                        equipamento.</span>

                                    <div class="d-flex flex-column form-item w-100 mt-2">
                                        <label>Documentos</label>
                                        <div
                                            class="file-upload-zone w-100 cursor-pointer bg-transparent  d-flex flex-column align-items-center justify-content-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-upload file-upload-icon ">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="17 8 12 3 7 8" />
                                                <line x1="12" x2="12" y1="3" y2="15" />
                                            </svg>
                                            <p class="file-upload-textfw-500 text-secondary m-0">Arraste ficheiros ou
                                                <span
                                                    class="file-upload-text-action text-primary-500text-primary-500">clique
                                                    para
                                                    selecionar</span>
                                            </p>
                                            <span class="m-0 text-muted">PDF, JPG, PNG — máx. 10MB</span>
                                        </div>

                                        <!-- Input de Ficheiro escondido + Container de upload -->
                                        <input type="file" id="document-upload-input" class="d-none"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <div id="uploaded-files-container" class="w-100 d-flex flex-column gap-2 mt-2">
                                        </div>

                                        <!-- Template: Ficheiro Carregado -->
                                        <template id="uploaded-file-template">
                                            <div class="uploaded-file-card mt-2 padding-3 d-flex flex-column gap-3">
                                                <input type="file" name="doc-files[]" class="d-none real-file-input">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-file text-primary-500">
                                                            <path
                                                                d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                                        </svg>
                                                        <p class="fw-500 m-0 file-name-display"
                                                            style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            filename.pdf</p>
                                                    </div>
                                                    <button type="button"
                                                        class="btn-close-file bg-transparent border-0 cursor-pointer padding-1 d-flex align-items-center justify-content-center"
                                                        title="Remover Ficheiro">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="lucide lucide-x">
                                                            <path d="M18 6 6 18" />
                                                            <path d="m6 6 12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="d-flex gap-3">
                                                    <div class="d-flex flex-column w-100 gap-1">
                                                        <div class="d-flex gap-1">
                                                            <label class="text-secondary fw-500"
                                                                style="font-size: 0.85rem;">Tipo de Documento</label>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                height="12" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                <path d="M12 6v12" />
                                                                <path d="M17.196 9 6.804 15" />
                                                                <path d="m6.804 9 10.392 6" />
                                                            </svg>
                                                        </div>
                                                        <select class="form-select select-sm w-100 doc-type-select"
                                                            name="doc-type[]" required>
                                                            <option value="" disabled selected>Selecionar Tipo...
                                                            </option>
                                                            <?php foreach (TipoDocumento::cases() as $t): ?>
                                                                                <option value="<?= htmlspecialchars($t->value) ?>">
                                                                                    <?= htmlspecialchars($t->value) ?>
                                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <select class="form-select select-sm w-100"
                                                            name="doc-supplier[]">
                                                            <option value="" selected>Sem Fornecedor</option>
                                                            <?php
                                                            $fornecedoresPorTipo = [];
                                                            foreach ($fornecedoresDisponiveis as $fornecedor) {
                                                                $fornecedoresPorTipo[$fornecedor['tipoFornecedor']][] = $fornecedor;
                                                            }
                                                            foreach ($fornecedoresPorTipo as $tipoForn => $fornecedores) {
                                                                echo '<optgroup label="' . htmlspecialchars($tipoForn) . '">';
                                                                foreach ($fornecedores as $forn) {
                                                                    echo '<option value="' . htmlspecialchars($forn['idFornecedor']) . '">' . htmlspecialchars($forn['nome']) . '</option>';
                                                                }
                                                                echo '</optgroup>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                        </template>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- Linha de Botões -->
                        <div
                            class="d-flex w-100 justify-content-between gap-4 button-row flex-column flex-md-row  mt-auto">
                            <button type="button" id="btn-prev-page" class="btn btn-ghost gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-left">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Voltar
                            </button>
                            <div class="d-flex gap-4">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                                    Criar Equipamento
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php foreach ($listaEquipamentos as $equipamento): ?>
                    <?php
                    $encryptedEqId = aes_encrypt((string) $equipamento->getIdEquipamento());

                    $fornFabricanteId = null;
                    $fornDistribuidorId = null;
                    $fornAssistentesIds = [];
                    $fornConsumiveisIds = [];

                    // Busca fornecedores
                    $stmtFornEq = execute_query(
                        "SELECT fe.idFornecedor, f.tipoFornecedor 
         FROM FornecedorEquipamento fe 
         JOIN Fornecedor f ON fe.idFornecedor = f.idFornecedor 
         WHERE fe.idEquipamento = :id AND fe.ativo = 1",
                        ['id' => $equipamento->getIdEquipamento()],
                        $ligacao
                    );
                    while ($row = $stmtFornEq->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['tipoFornecedor'] === 'Fabricante')
                            $fornFabricanteId = $row['idFornecedor'];
                        if ($row['tipoFornecedor'] === 'Distribuidor')
                            $fornDistribuidorId = $row['idFornecedor'];
                        if ($row['tipoFornecedor'] === 'Assistência Técnica')
                            $fornAssistentesIds[] = $row['idFornecedor'];
                        if ($row['tipoFornecedor'] === 'Consumíveis')
                            $fornConsumiveisIds[] = $row['idFornecedor'];
                    }

                    // Busca componentes
                    $stmtCompEq = execute_query(
                        "SELECT idComponente, quantidade 
         FROM ComponenteEquipamento 
         WHERE idEquipamento = :id",
                        ['id' => $equipamento->getIdEquipamento()],
                        $ligacao
                    );
                    $componentesEq = [];
                    while ($row = $stmtCompEq->fetch(PDO::FETCH_ASSOC)) {
                        $componentesEq[$row['idComponente']] = $row['quantidade'];
                    }
                    $ligacao = null;
                    ?>

                    <?php if (tem_permissao('equipments.edit')): ?>
                                        <!-- Modal de Edição de Equipamento para <?= htmlspecialchars($equipamento->getDesignacao()) ?> -->
                                        <div class="modal fade" id="equipment-edit-modal-<?= htmlspecialchars($encryptedEqId) ?>" tabindex="-1"
                                            aria-labelledby="equipmentEditModalLabel-<?= htmlspecialchars($encryptedEqId) ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                                                <div class="modal-content custom-modal-content d-flex flex-column">
                                                    <!-- Titulo -->
                                                    <div
                                                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                                                        <div class="d-flex flex-column">
                                                            <h2 class="equipment-creation-modal-title modal-title"
                                                                id="equipmentEditModalLabel-<?= htmlspecialchars($encryptedEqId) ?>">Editar
                                                                Equipamento</h2>
                                                            <span class="text-secondary fw-400">Edite os dados do equipamento
                                                                <?= htmlspecialchars($equipamento->getDesignacao()) ?>.</span>
                                                        </div>

                                                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Body do Modal com scroll automático -->
                                                    <div class="modal-body p-0">
                                                        <form action="equipments-crud/edit-equipment.php" method="POST"
                                                            class="d-flex flex-column h-100 m-0 w-100 form-edit-equipment">
                                                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedEqId) ?>">

                                                            <!-- Conteudo Pagina 1 -->
                                                            <div id="modal-page-1-edit-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                                                                <div class="d-flex flex-row gap-3 align-items-center">
                                                                    <div
                                                                        class="d-flex flex-row equipment-creation-modal-page current-page justify-content-start align-items-center gap-3 padding-3">
                                                                        <h3 class="text-white padding-2 d-flex align-items-center justify-content-center">1</h3>
                                                                        <p class="text-primary-700">Dados gerais</p>
                                                                    </div>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="lucide lucide-chevron-right-icon lucide-chevron-right stroke-secondary">
                                                                        <path d="m9 18 6-6-6-6" />
                                                                    </svg>
                                                                    <div
                                                                        class="d-flex flex-row equipment-creation-modal-page justify-content-start align-items-center gap-3 padding-3">
                                                                        <h3 class="text-secondary padding-2 d-flex align-items-center justify-content-center">2
                                                                        </h3>
                                                                        <p class="text-secondary">Relações</p>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 1: Codigo Interno e Categoria -->
                                                                <div class="d-flex gap-4 w-100">
                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <div class="d-flex gap-1">
                                                                            <label for="equipment-code-<?= htmlspecialchars($encryptedEqId) ?>">Código
                                                                                Interno</label>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                                <path d="M12 6v12" />
                                                                                <path d="M17.196 9 6.804 15" />
                                                                                <path d="m6.804 9 10.392 6" />
                                                                            </svg>
                                                                        </div>
                                                                        <input type="text" id="equipment-code-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-code" placeholder="Ex: EQ-001" maxlength="20"
                                                                            value="<?= htmlspecialchars($equipamento->getCodigoInterno()) ?>" required>
                                                                    </div>

                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <div class="d-flex gap-1">
                                                                            <label
                                                                                for="equipment-category-<?= htmlspecialchars($encryptedEqId) ?>">Categoria</label>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                                <path d="M12 6v12" />
                                                                                <path d="M17.196 9 6.804 15" />
                                                                                <path d="m6.804 9 10.392 6" />
                                                                            </svg>
                                                                        </div>
                                                                        <div class="d-flex w-100 gap-2">
                                                                            <select id="equipment-category-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                                name="equipment-category" class="form-select mw-0" required>
                                                                                <option value="" disabled>Selecionar categoria</option>
                                                                                <?php foreach ($categoriasDisponiveis as $catDisp): ?>
                                                                                                    <option value="<?= htmlspecialchars($catDisp->getIdCategoria()) ?>"
                                                                                                        <?= $equipamento->getIdCategoria() == $catDisp->getIdCategoria() ? 'selected' : '' ?>>
                                                                                                        <?= htmlspecialchars($catDisp->getNome()) ?>
                                                                                                    </option>
                                                                                <?php endforeach; ?>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 2: Numero de Serie e Designação / Nome do Equipamento -->
                                                                <div class="d-flex gap-4 w-100">
                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <div class="d-flex gap-1">
                                                                            <label for="equipment-serial-<?= htmlspecialchars($encryptedEqId) ?>">Número de
                                                                                Série</label>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                                <path d="M12 6v12" />
                                                                                <path d="M17.196 9 6.804 15" />
                                                                                <path d="m6.804 9 10.392 6" />
                                                                            </svg>
                                                                        </div>
                                                                        <input type="text" id="equipment-serial-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-serial" placeholder="Ex: DRG-V500-7239"
                                                                            value="<?= htmlspecialchars($equipamento->getNumeroSerie()) ?>" required>
                                                                    </div>

                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <div class="d-flex gap-1">
                                                                            <label for="equipment-name-<?= htmlspecialchars($encryptedEqId) ?>">Designação /
                                                                                Nome do Equipamento</label>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                                <path d="M12 6v12" />
                                                                                <path d="M17.196 9 6.804 15" />
                                                                                <path d="m6.804 9 10.392 6" />
                                                                            </svg>
                                                                        </div>
                                                                        <input type="text" id="equipment-name-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-name" placeholder="Ex: Ventilador Dräger V500"
                                                                            value="<?= htmlspecialchars($equipamento->getDesignacao()) ?>" required>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 3: Marca e Modelo -->
                                                                <div class="d-flex gap-4 w-100">
                                                                    <div class="d-flex flex-column form-item w-100">
                                                                        <div class="d-flex gap-1">
                                                                            <label for="equipment-brand-<?= htmlspecialchars($encryptedEqId) ?>">Marca</label>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                                <path d="M12 6v12" />
                                                                                <path d="M17.196 9 6.804 15" />
                                                                                <path d="m6.804 9 10.392 6" />
                                                                            </svg>
                                                                        </div>
                                                                        <select id="equipment-brand-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-brand" class="form-select" required>
                                                                            <option value="" disabled>Selecionar marca</option>
                                                                            <?php foreach ($marcasDisponiveis as $marca): ?>
                                                                                                <option value="<?= htmlspecialchars($marca['idMarca']) ?>"
                                                                                                    <?= $equipamento->getIdMarca() == $marca['idMarca'] ? 'selected' : '' ?>>
                                                                                                    <?= htmlspecialchars($marca['nome']) ?>
                                                                                                </option>
                                                                            <?php endforeach; ?>

                                                                        </select>
                                                                    </div>

                                                                    <div class="d-flex flex-column form-item w-100">
                                                                        <label for="equipment-model-<?= htmlspecialchars($encryptedEqId) ?>">Modelo</label>
                                                                        <input type="text" id="equipment-model-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-model" placeholder="Ex: Evita V500"
                                                                            value="<?= htmlspecialchars($equipamento->getModelo()) ?>">
                                                                    </div>
                                                                </div>

                                                                <!-- Row 4: Datas -->
                                                                <div class="d-flex gap-4 w-100">
                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <label for="equipment-purchase-date-<?= htmlspecialchars($encryptedEqId) ?>">Data de
                                                                            Aquisição</label>
                                                                        <input type="date" id="equipment-purchase-date-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-purchase-date"
                                                                            value="<?= $equipamento->getDataAquisicao() ? $equipamento->getDataAquisicao()->format('Y-m-d') : '' ?>">
                                                                    </div>

                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <label for="equipment-manufacture-date-<?= htmlspecialchars($encryptedEqId) ?>">Data de
                                                                            Fabrico</label>
                                                                        <input type="date"
                                                                            id="equipment-manufacture-date-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-manufacture-date"
                                                                            value="<?= $equipamento->getDataFabrico() ? $equipamento->getDataFabrico()->format('Y-m-d') : '' ?>">
                                                                    </div>
                                                                </div>

                                                                <!-- Row 5: Custos e Entradas -->
                                                                <div class="d-flex gap-4 w-100">
                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <label for="equipment-cost-<?= htmlspecialchars($encryptedEqId) ?>">Custo de Aquisição
                                                                            (€)</label>
                                                                        <input type="number" id="equipment-cost-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-cost" placeholder="0.00" step="0.01" min="0"
                                                                            value="<?= htmlspecialchars($equipamento->getCustoAquisicao()) ?>">
                                                                    </div>

                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <label for="equipment-entry-type-<?= htmlspecialchars($encryptedEqId) ?>">Tipo de
                                                                            Entrada</label>
                                                                        <select id="equipment-entry-type-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-entry-type" class="form-select">
                                                                            <option value="" disabled>Selecionar...</option>
                                                                            <?php foreach (TipoEntrada::cases() as $tipo): ?>
                                                                                                <option value="<?= $tipo->value ?>"
                                                                                                    <?= $equipamento->getTipoEntrada()->value === $tipo->value ? 'selected' : '' ?>>
                                                                                                    <?= $tipo->value ?>
                                                                                                </option>
                                                                            <?php endforeach; ?>

                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 6: Classificação e Estado -->
                                                                <div class="d-flex gap-4 w-100">
                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <label
                                                                            for="equipment-criticality-<?= htmlspecialchars($encryptedEqId) ?>">Criticidade</label>
                                                                        <select id="equipment-criticality-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-criticality" class="form-select">
                                                                            <?php foreach (CriticidadeEquipamento::cases() as $crit): ?>
                                                                                                <option value="<?= $crit->value ?>"
                                                                                                    <?= $equipamento->getCriticidade()->value === $crit->value ? 'selected' : '' ?>>
                                                                                                    <?= $crit->value ?>
                                                                                                </option>
                                                                            <?php endforeach; ?>

                                                                        </select>
                                                                    </div>

                                                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                                                        <div class="d-flex gap-1">
                                                                            <label for="equipment-status-<?= htmlspecialchars($encryptedEqId) ?>">Estado
                                                                                Atual</label>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                                <path d="M12 6v12" />
                                                                                <path d="M17.196 9 6.804 15" />
                                                                                <path d="m6.804 9 10.392 6" />
                                                                            </svg>
                                                                        </div>
                                                                        <select id="equipment-status-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                            name="equipment-status" class="form-select" required>
                                                                            <?php foreach (EstadoEquipamento::cases() as $estado): ?>
                                                                                                <option value="<?= $estado->value ?>"
                                                                                                    <?= $equipamento->getEstadoAtual()->value === $estado->value ? 'selected' : '' ?>>
                                                                                                    <?= $estado->value ?>
                                                                                                </option>
                                                                            <?php endforeach; ?>

                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 7: Localização -->
                                                                <div class="d-flex flex-column form-item w-100 mw-0">
                                                                    <div class="d-flex gap-1">
                                                                        <label
                                                                            for="equipment-location-<?= htmlspecialchars($encryptedEqId) ?>">Localização</label>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                                            <path d="M12 6v12" />
                                                                            <path d="M17.196 9 6.804 15" />
                                                                            <path d="m6.804 9 10.392 6" />
                                                                        </svg>
                                                                    </div>
                                                                    <select id="equipment-location-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                        name="equipment-location" class="form-select" required>
                                                                        <option value="" disabled>Selecionar localização...</option>
                                                                        <?php foreach ($localizacoesDisponiveis as $loc): ?>
                                                                                            <option value="<?= htmlspecialchars($loc->getIdLocalizacao()) ?>"
                                                                                                <?= $equipamento->getIdLocalizacao() == $loc->getIdLocalizacao() ? 'selected' : '' ?>>
                                                                                                <?= htmlspecialchars($loc->getNomeSala()) ?>
                                                                                            </option>
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                </div>

                                                                <!-- Row 8: Observações -->
                                                                <div class="d-flex flex-column form-item w-100 mw-0">
                                                                    <label for="equipment-notes-<?= htmlspecialchars($encryptedEqId) ?>">Observações</label>
                                                                    <textarea id="equipment-notes-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                        name="equipment-notes" class="form-control" rows="3"
                                                                        placeholder="Insira observações relevantes sobre o equipamento..."><?= htmlspecialchars($equipamento->getObservacoes()) ?></textarea>
                                                                </div>

                                                                <!-- Linha de Botões -->
                                                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-auto">
                                                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="button" id="btn-next-page-edit-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                        class="btn btn-primary btn-glowing gap-1 btn-next-page-edit">
                                                                        Próximo
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" class="lucide lucide-chevron-right">
                                                                            <path d="m9 18 6-6-6-6" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <?php if (SHOW_DEBUG_BUTTONS): ?>
                                                                                    <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                                                                        <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                                                                            (Debug)</span>
                                                                                        <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                                                                            onclick="prefillFields({'equipment-name-<?= htmlspecialchars($encryptedEqId) ?>': 'Equipamento Modificado', 'equipment-status-<?= htmlspecialchars($encryptedEqId) ?>': 'Em manutenção'}); setTimeout(() => { document.getElementById('equipment-name-<?= htmlspecialchars($encryptedEqId) ?>').dispatchEvent(new Event('input', { bubbles: true })); document.getElementById('equipment-status-<?= htmlspecialchars($encryptedEqId) ?>').dispatchEvent(new Event('change', { bubbles: true })); }, 100);">Alterar
                                                                                            para Manutenção</button>
                                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>

                                                            <!-- Conteudo Pagina 2 -->
                                                            <div id="modal-page-2-edit-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column d-none">
                                                                <div class="d-flex flex-row gap-3 align-items-center">
                                                                    <div
                                                                        class="d-flex flex-row equipment-creation-modal-page justify-content-start align-items-center gap-3 padding-3 page-completed">
                                                                        <h3 class="text-secondary padding-2 d-flex align-items-center justify-content-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round" class="lucide lucide-check stroke-white">
                                                                                <polyline points="20 6 9 17 4 12" />
                                                                            </svg>
                                                                        </h3>
                                                                        <p>Dados gerais</p>
                                                                    </div>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="lucide lucide-chevron-right-icon lucide-chevron-right stroke-secondary">
                                                                        <path d="m9 18 6-6-6-6" />
                                                                    </svg>
                                                                    <div
                                                                        class="d-flex flex-row equipment-creation-modal-page current-page justify-content-start align-items-center gap-3 padding-3">
                                                                        <h3 class="text-white padding-2 d-flex align-items-center justify-content-center">2</h3>
                                                                        <p class="text-primary-700">Relações</p>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex flex-column flex-grow-1 w-100 pt-4 gap-6">
                                                                    <!-- Seccao 1: Fornecedores -->
                                                                    <div class="d-flex flex-column gap-3">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-building2-icon lucide-building-2 stroke-primary-500">
                                                                                <path d="M10 12h4" />
                                                                                <path d="M10 8h4" />
                                                                                <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                                                                <path
                                                                                    d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                                                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                                                            </svg>
                                                                            <h3>Fornecedores</h3>
                                                                        </div>

                                                                        <div class="d-flex flex-column gap-4 w-100">
                                                                            <!-- Fabricante -->
                                                                            <div class="d-flex flex-column form-item w-100 mw-0">
                                                                                <div class="d-flex gap-1">
                                                                                    <label
                                                                                        for="equipment-manufacturer-<?= htmlspecialchars($encryptedEqId) ?>">Fabricante</label>
                                                                                </div>
                                                                                <div class="d-flex w-100 gap-2">
                                                                                    <select id="equipment-manufacturer-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                                        name="equipment-manufacturer" class="form-select mw-0">
                                                                                        <option value="" <?= empty($fornFabricanteId) ? 'selected' : '' ?>>Sem
                                                                                            fabricante</option>
                                                                                        <?php foreach ($fornecedoresDisponiveis as $forn): ?>
                                                                                                            <?php if ($forn['tipoFornecedor'] === 'Fabricante'): ?>
                                                                                                                                <option value="<?= htmlspecialchars($forn['idFornecedor']) ?>"
                                                                                                                                    <?= $forn['idFornecedor'] == $fornFabricanteId ? 'selected' : '' ?>>
                                                                                                                                    <?= htmlspecialchars($forn['nome']) ?>
                                                                                                                                </option>
                                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; ?>

                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Distribuidor -->
                                                                            <div class="d-flex flex-column form-item w-100 mw-0">
                                                                                <div class="d-flex gap-1">
                                                                                    <label
                                                                                        for="equipment-distributor-<?= htmlspecialchars($encryptedEqId) ?>">Distribuidor</label>
                                                                                </div>
                                                                                <div class="d-flex w-100 gap-2">
                                                                                    <select id="equipment-distributor-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                                        name="equipment-distributor" class="form-select mw-0">
                                                                                        <option value="" <?= empty($fornDistribuidorId) ? 'selected' : '' ?>>Sem
                                                                                            distribuidor</option>
                                                                                        <?php foreach ($fornecedoresDisponiveis as $forn): ?>
                                                                                                            <?php if ($forn['tipoFornecedor'] === 'Distribuidor'): ?>
                                                                                                                                <option value="<?= htmlspecialchars($forn['idFornecedor']) ?>"
                                                                                                                                    <?= $forn['idFornecedor'] == $fornDistribuidorId ? 'selected' : '' ?>>
                                                                                                                                    <?= htmlspecialchars($forn['nome']) ?>
                                                                                                                                </option>
                                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; ?>

                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Assistentes Técnicos -->
                                                                            <div class="d-flex flex-column form-item gap-2 w-100">
                                                                                <div class="d-flex gap-1">
                                                                                    <label>Assistentes Técnicos (Múltiplos)</label>
                                                                                </div>
                                                                                <div class="d-flex flex-column gap-3 padding-3 multi-select-form">
                                                                                    <?php
                                                                                    $hasAssistants = false;
                                                                                    foreach ($fornecedoresDisponiveis as $forn):
                                                                                        if ($forn['tipoFornecedor'] === 'Assistência Técnica'):
                                                                                            $hasAssistants = true;
                                                                                            ?>
                                                                                                                            <div class="form-check d-flex align-items-center gap-2 m-0">
                                                                                                                                <input class="form-check-input m-0" type="checkbox"
                                                                                                                                    name="equipment-tech-assistants[]"
                                                                                                                                    value="<?= htmlspecialchars($forn['idFornecedor']) ?>"
                                                                                                                                    id="check-ta-<?= $encryptedEqId ?>-<?= $forn['idFornecedor'] ?>"
                                                                                                                                    <?= in_array($forn['idFornecedor'], $fornAssistentesIds) ? 'checked' : '' ?>>
                                                                                                                                <label class="form-check-label text-secondary m-0 multi-select-label"
                                                                                                                                    for="check-ta-<?= $encryptedEqId ?>-<?= $forn['idFornecedor'] ?>">
                                                                                                                                    <?= htmlspecialchars($forn['nome']) ?>
                                                                                                                                </label>
                                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                    <?php endforeach;
                                                                                    if (!$hasAssistants): ?>
                                                                                                        <span class="text-muted fst-italic">Nenhum assistente técnico
                                                                                                            disponível.</span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Fornecedores de Consumíveis -->
                                                                            <div class="d-flex flex-column form-item gap-2 w-100">
                                                                                <div class="d-flex gap-1">
                                                                                    <label>Fornecedores de Consumíveis (múltiplos)</label>
                                                                                </div>
                                                                                <div class="d-flex flex-column gap-3 padding-3 multi-select-form">
                                                                                    <?php
                                                                                    $hasConsumables = false;
                                                                                    foreach ($fornecedoresDisponiveis as $forn):
                                                                                        if ($forn['tipoFornecedor'] === 'Consumíveis'):
                                                                                            $hasConsumables = true;
                                                                                            ?>
                                                                                                                            <div class="form-check d-flex align-items-center gap-2 m-0">
                                                                                                                                <input class="form-check-input m-0" type="checkbox"
                                                                                                                                    name="equipment-consumable-suppliers[]"
                                                                                                                                    value="<?= htmlspecialchars($forn['idFornecedor']) ?>"
                                                                                                                                    id="check-con-<?= $encryptedEqId ?>-<?= $forn['idFornecedor'] ?>"
                                                                                                                                    <?= in_array($forn['idFornecedor'], $fornConsumiveisIds) ? 'checked' : '' ?>>
                                                                                                                                <label class="form-check-label text-secondary m-0 multi-select-label"
                                                                                                                                    for="check-con-<?= $encryptedEqId ?>-<?= $forn['idFornecedor'] ?>">
                                                                                                                                    <?= htmlspecialchars($forn['nome']) ?>
                                                                                                                                </label>
                                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                    <?php endforeach;
                                                                                    if (!$hasConsumables): ?>
                                                                                                        <span class="text-muted fst-italic">Nenhum fornecedor de consumíveis
                                                                                                            disponível.</span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Seccao 2: Componentes -->
                                                                    <div class="d-flex flex-column gap-3">
                                                                        <div class="d-flex align-items-center gap-2 components-header flex-column flex-md-row">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="lucide lucide-puzzle-icon lucide-puzzle stroke-primary-500">
                                                                                <path
                                                                                    d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                                                            </svg>
                                                                            <h3>Componentes</h3>
                                                                            <span class="text-muted">(filtrados pela categoria selecionada)</span>
                                                                        </div>

                                                                        <div class="d-flex flex-column gap-4 w-100">
                                                                            <div class="d-flex flex-column form-item gap-2 w-100">
                                                                                <div class="d-flex flex-column gap-3 padding-4 multi-select-form">
                                                                                    <span id="no-components-msg-edit-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                                        class="text-muted d-none">Nenhum componente disponível para a categoria
                                                                                        selecionada.</span>
                                                                                    <?php foreach ($componentesDisponiveis as $comp):
                                                                                        $isChecked = isset($componentesEq[$comp['idComponente']]);
                                                                                        $qty = $isChecked ? $componentesEq[$comp['idComponente']] : 1;

                                                                                        // Se não estiver checked e não houver stock, desativar
                                                                                        $stockDisponivel = (int) $comp['stock'];
                                                                                        $maxStock = $stockDisponivel + ($isChecked ? $qty : 0);
                                                                                        $disabled = (!$isChecked && $stockDisponivel <= 0) ? 'disabled' : '';
                                                                                        $minValue = $maxStock <= 0 ? 0 : 1;
                                                                                        if (!$isChecked && $stockDisponivel <= 0)
                                                                                            $qty = 0;
                                                                                        ?>
                                                                                                        <div class="d-flex align-items-start gap-3 w-100 multi-select-item <?= ($disabled && !$isChecked) ? 'opacity-50' : '' ?>"
                                                                                                            data-category-id="<?= htmlspecialchars($comp['idCategoria'] ?? '') ?>"
                                                                                                            style="<?= ($equipamento->getIdCategoria() == $comp['idCategoria'] || $isChecked) ? '' : 'display: none !important;' ?>">
                                                                                                            <div class="form-check m-0 pt-1">
                                                                                                                <input class="form-check-input m-0" type="checkbox"
                                                                                                                    name="equipment-components[]"
                                                                                                                    value="<?= htmlspecialchars($comp['idComponente']) ?>"
                                                                                                                    id="check-comp-<?= $encryptedEqId ?>-<?= htmlspecialchars($comp['idComponente']) ?>"
                                                                                                                    <?= $isChecked ? 'checked' : '' ?>                                                             <?= $disabled ?>>
                                                                                                            </div>
                                                                                                            <div class="d-flex flex-column gap-2 flex-grow-1">
                                                                                                                <div
                                                                                                                    class="d-flex justify-content-between align-items-center w-100 multi-select-details-row flex-column flex-md-row">
                                                                                                                    <div
                                                                                                                        class="d-flex align-items-center gap-3 multi-select-details-row flex-column flex-md-row">
                                                                                                                        <label
                                                                                                                            for="check-comp-<?= $encryptedEqId ?>-<?= htmlspecialchars($comp['idComponente']) ?>"
                                                                                                                            class="fw-400 m-0 cursor-pointer">
                                                                                                                            <?= htmlspecialchars($comp['descricao']) ?>
                                                                                                                        </label>
                                                                                                                        <span
                                                                                                                            class="text-muted multi-select-stock-badge <?= ($stockDisponivel <= 0 && !$isChecked) ? 'text-error' : '' ?>">Em
                                                                                                                            Stock:
                                                                                                                            <?= htmlspecialchars($comp['stock']) ?> un.</span>
                                                                                                                    </div>
                                                                                                                    <!-- Quantidade -->
                                                                                                                    <div
                                                                                                                        class="d-flex align-items-center gap-2 multi-select-qty-container <?= $isChecked ? '' : 'd-none' ?>">
                                                                                                                        <span
                                                                                                                            class="text-secondary multi-select-qty-label">Qtd:</span>
                                                                                                                        <input type="number"
                                                                                                                            name="equipment-components-qty[<?= htmlspecialchars($comp['idComponente']) ?>]"
                                                                                                                            class="form-control text-center p-0 multi-select-qty-input"
                                                                                                                            value="<?= htmlspecialchars($qty) ?>"
                                                                                                                            min="<?= $minValue ?>" max="<?= $maxStock ?>"
                                                                                                                            <?= $disabled ?>>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                    <?php endforeach; ?>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Linha de Botões -->
                                                                <div
                                                                    class="d-flex w-100 justify-content-between gap-4 button-row flex-column flex-md-row  mt-auto pt-4">
                                                                    <button type="button" id="btn-prev-page-edit-<?= htmlspecialchars($encryptedEqId) ?>"
                                                                        class="btn btn-ghost gap-2 btn-prev-page-edit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" class="lucide lucide-chevron-left">
                                                                            <path d="m15 18-6-6 6-6" />
                                                                        </svg>
                                                                        Voltar
                                                                    </button>
                                                                    <div class="d-flex gap-4">
                                                                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-primary btn-glowing">
                                                                            Guardar Alterações
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                    <?php endif; ?>

                    <?php if (tem_permissao('equipments.delete')): ?>
                                        <!-- Modal de Eliminação de Equipamento -->
                                        <div class="modal fade" id="equipment-delete-modal-<?= htmlspecialchars($encryptedEqId) ?>" tabindex="-1"
                                            aria-labelledby="equipmentDeleteModalLabel-<?= htmlspecialchars($encryptedEqId) ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                                                <div class="modal-content custom-modal-content d-flex flex-column">
                                                    <div
                                                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                                                        <div class="d-flex flex-column">
                                                            <h2 class="equipment-creation-modal-title modal-title"
                                                                id="equipmentDeleteModalLabel-<?= htmlspecialchars($encryptedEqId) ?>">
                                                                Eliminar Equipamento</h2>
                                                            <span class="text-secondary fw-400">O equipamento será movido para a reciclagem.</span>
                                                        </div>
                                                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <form method="POST" action="equipments-crud/delete-equipment.php">
                                                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedEqId) ?>">
                                                            <div
                                                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                                                <div class="d-flex flex-column align-items-center gap-4">
                                                                    <div class="d-flex padding-3 danger-icon">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                                                            <path
                                                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                                                            <path d="M12 9v4" />
                                                                            <path d="M12 17h.01" />
                                                                        </svg>
                                                                    </div>
                                                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                                                        <div
                                                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                                                            <p class="text-secondary">Tem a certeza que deseja eliminar o equipamento?</p>
                                                                            <h2 class="fw-700">"<?= htmlspecialchars($equipamento->getDesignacao()) ?>"</h2>
                                                                            <span class="text-muted">Tipo: Equipamento</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-danger btn-glowing text-white">Sim, Eliminar.</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                    <?php endif; ?>

                    <?php if (tem_permissao('equipments.archive')): ?>
                                        <!-- Modal de Arquivo de Equipamento -->
                                        <div class="modal fade" id="equipment-archive-modal-<?= htmlspecialchars($encryptedEqId) ?>" tabindex="-1"
                                            aria-labelledby="equipmentArchiveModalLabel-<?= htmlspecialchars($encryptedEqId) ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                                                <div class="modal-content custom-modal-content d-flex flex-column">
                                                    <div
                                                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                                                        <div class="d-flex flex-column">
                                                            <h2 class="equipment-creation-modal-title modal-title"
                                                                id="equipmentArchiveModalLabel-<?= htmlspecialchars($encryptedEqId) ?>">
                                                                Arquivar Equipamento</h2>
                                                            <span class="text-secondary fw-400">O equipamento será movido para o arquivo.</span>
                                                        </div>
                                                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <form method="POST" action="equipments-crud/archive-equipment.php">
                                                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedEqId) ?>">
                                                            <div
                                                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                                                <div class="d-flex flex-column align-items-center gap-4">
                                                                    <div class="d-flex padding-3 danger-icon">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" class="lucide lucide-archive">
                                                                            <rect width="20" height="5" x="2" y="3" rx="1" />
                                                                            <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                                            <path d="M10 12h4" />
                                                                        </svg>
                                                                    </div>
                                                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                                                        <div
                                                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                                                            <p class="text-secondary">Tem a certeza que deseja arquivar o equipamento?</p>
                                                                            <h2 class="fw-700">"<?= htmlspecialchars($equipamento->getDesignacao()) ?>"</h2>
                                                                            <span class="text-muted">Tipo: Equipamento</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-danger btn-glowing text-white">Sim, Arquivar.</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    <?php endif; ?>
<?php endforeach; ?>

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
include_once BASE_PATH . 'private/includes/footer.php';
?>