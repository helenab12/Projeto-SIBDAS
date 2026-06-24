<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['inventory.view.transfers']);

// Inicializar variáveis
$success_message = null;
$server_error = null;

// Recolher dados da sessão
if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

// Recolher parâmetros da query string
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'dataCriacao';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'asc') ? 'asc' : 'desc'; // default desc

// Definir paginação
$items_per_page = 8;

// Inicializar variáveis
$transferencias = [];
try {
    // Ligar à BD
    $ligacao = connect_to_db();

    // Preparar filtros
    $whereConditions = [
        "ha.tabelaAfetada = 'Equipamento'",
        "ha.campoAfetado = 'idLocalizacao'"
    ];
    $params = [];

    if ($search_query !== '') {
        $whereConditions[] = "(e.designacao LIKE :search OR e.codigoInterno LIKE :search OR p.nome LIKE :search OR loc_antiga.nomeSala LIKE :search OR s_antigo.nome LIKE :search OR loc_nova.nomeSala LIKE :search OR s_novo.nome LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total sem filtros
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM HistoricoAuditoria WHERE tabelaAfetada = 'Equipamento' AND campoAfetado = 'idLocalizacao'", [], $ligacao);
    $totalTransfersAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total filtrado
    $countSql = "SELECT COUNT(ha.idAuditoria) as total
                 FROM HistoricoAuditoria ha
                 LEFT JOIN Utilizador u ON ha.idUtilizador = u.idUtilizador
                 LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa
                 INNER JOIN Equipamento e ON ha.idRegistoAfetado = e.idEquipamento
                 LEFT JOIN Localizacao loc_antiga ON ha.valorAntigo = loc_antiga.idLocalizacao
                 LEFT JOIN Servico s_antigo ON loc_antiga.idServico = s_antigo.idServico
                 LEFT JOIN Localizacao loc_nova ON ha.valorNovo = loc_nova.idLocalizacao
                 LEFT JOIN Servico s_novo ON loc_nova.idServico = s_novo.idServico
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalLogsFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    // Calcular paginação
    $totalPages = max(1, ceil($totalLogsFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Configurar ordenação
    $allowed_sorts = [
        'dataCriacao' => 'ha.dataCriacao',
        'utilizador' => 'p.nome',
        'equipamento' => 'e.designacao'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'ha.dataCriacao';
    $sort_dir = strtoupper($dir_param);

    // Query de dados
    $dataSql = "SELECT ha.idAuditoria, ha.dataCriacao, ha.acao, p.nome AS nomeUtilizador, 
                       e.idEquipamento, e.designacao, e.codigoInterno,
                       loc_antiga.nomeSala AS salaAntiga, s_antigo.nome AS servicoAntigo,
                       loc_nova.nomeSala AS salaNova, s_novo.nome AS servicoNovo
                 FROM HistoricoAuditoria ha
                 LEFT JOIN Utilizador u ON ha.idUtilizador = u.idUtilizador
                 LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa
                 INNER JOIN Equipamento e ON ha.idRegistoAfetado = e.idEquipamento
                 LEFT JOIN Localizacao loc_antiga ON ha.valorAntigo = loc_antiga.idLocalizacao
                 LEFT JOIN Servico s_antigo ON loc_antiga.idServico = s_antigo.idServico
                 LEFT JOIN Localizacao loc_nova ON ha.valorNovo = loc_nova.idLocalizacao
                 LEFT JOIN Servico s_novo ON loc_nova.idServico = s_novo.idServico
                 WHERE $whereSQL
                 ORDER BY $sort_field $sort_dir
                 LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmt = execute_query($dataSql, $params, $ligacao);

    // Processar resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Formatar data
        $data = $row['dataCriacao'] ? new DateTime($row['dataCriacao']) : new DateTime();

        // Encriptar ID
        $encryptedEqId = aes_encrypt((string) $row['idEquipamento']);

        // Construir link
        $searchUrl = BASE_URL . "/private/inventory/equipments/detailed_view.php?id=" . urlencode($encryptedEqId);

        // Formatar valores de exibição
        $equipamentoNome = htmlspecialchars(($row['codigoInterno'] ?? '') . ' — ' . ($row['designacao'] ?? ''));

        $localizacaoAntiga = $row['salaAntiga'] ? htmlspecialchars(($row['servicoAntigo'] ?? '') . ' — ' . ($row['salaAntiga'] ?? '')) : 'Desconhecida';
        $localizacaoNova = $row['salaNova'] ? htmlspecialchars(($row['servicoNovo'] ?? '') . ' — ' . ($row['salaNova'] ?? '')) : 'Desconhecida';

        if ($row['acao'] === 'Criação') {
            $localizacaoAntiga = 'N/A (Registo Inicial)';
        }

        // Preencher array
        $transferencias[] = [
            'data' => $data->format('d/m/Y, H:i:s'),
            'utilizador' => $row['nomeUtilizador'] ?? 'Sistema',
            'equipamento' => $equipamentoNome,
            'url' => $searchUrl,
            'localizacaoAntiga' => $localizacaoAntiga,
            'localizacaoNova' => $localizacaoNova
        ];
    }

    // Fechar ligação
    $ligacao = null;
} catch (Exception $e) {
    // Capturar erro
    $server_error = "Erro ao carregar histórico de transferências: " . $e->getMessage();
}


// Carregar dependências
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<!-- Wrapper Layout Principal -->
<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php
    // Carregar cabeçalhos
    include_once BASE_PATH . 'private/includes/headers.php';
    ?>

    <!-- Secção de Conteúdo -->
    <section class="padding-6 gap-6 d-flex flex-column padding-6">
        <!-- Wrapper Título -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <!-- Título e Subtítulo -->
            <div class="d-flex flex-column gap-1">
                <!-- Título -->
                <h1>Transferências de Equipamentos</h1>
                <!-- Subtítulo -->
                <p class="text-secondary fw-400"><?= htmlspecialchars($totalLogsFiltered ?? count($transferencias)) ?>
                    registos encontrados</p>
            </div>

        </div>

        <!-- Barra de Pesquisa -->
        <div
            class="bento-card padding-4 gap-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
            <!-- Formulário Pesquisa -->
            <form action="" method="GET" style="display: contents;">
                <!-- Wrapper Input -->
                <div class="form-item position-relative flex-grow-1">
                    <!-- SVG Lupa -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <!-- Input Pesquisa -->
                    <input type="text" class="form-item w-100 search-bar-input" name="search" id="search-input-field"
                        placeholder="Pesquisar por equipamento, utilizador ou serviço..."
                        value="<?= htmlspecialchars($search_query) ?>">
                    <?php if ($search_query !== ''): ?>
                            <!-- Script Reposicionar Cursor -->
                            <script>
                                // Reposicionar cursor
                                document.addEventListener("DOMContentLoaded", function () {
                                    const searchInput = document.getElementById('search-input-field');
                                    if (searchInput) {
                                        searchInput.focus();
                                        const val = searchInput.value;
                                        searchInput.value = '';
                                        searchInput.value = val;
                                    }
                                });
                            </script>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if ($totalTransfersAll === 0): ?>
                <!-- Card Estado Vazio Geral -->
                <div
                    class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                    <!-- Wrapper Ícone -->
                    <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                        <!-- SVG Movimentações -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-arrow-right-left">
                            <path d="m16 3 4 4-4 4" />
                            <path d="M20 7H4" />
                            <path d="m8 21-4-4 4-4" />
                            <path d="M4 17h16" />
                        </svg>
                    </div>
                    <!-- Wrapper Textos -->
                    <div class="d-flex flex-column gap-2">
                        <!-- Título -->
                        <h3 class="fw-700 m-0">Sem Movimentações</h3>
                        <!-- Descrição -->
                        <p class="text-secondary m-0">Ainda não existem transferências de equipamentos registadas.</p>
                    </div>
                </div>
        <?php elseif (empty($transferencias)): ?>
                <!-- Card Estado Vazio Pesquisa -->
                <div
                    class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                    <!-- Wrapper Ícone -->
                    <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                        <!-- SVG Pesquisa -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search-x">
                            <path d="m13.5 8.5-5 5" />
                            <path d="m8.5 8.5 5 5" />
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <!-- Wrapper Textos -->
                    <div class="d-flex flex-column gap-2">
                        <!-- Título -->
                        <h3 class="fw-700 m-0">Sem resultados</h3>
                        <!-- Descrição -->
                        <p class="text-secondary m-0">Nenhum registo encontrado correspondente à sua pesquisa.</p>
                    </div>
                </div>
        <?php else: ?>

                <!-- Card Tabela -->
                <div class="bento-card w-100 p-0 border-0">
                    <!-- Wrapper Datatable -->
                    <div class="datatable-wrapper no-footer sortable fixed-columns">
                        <!-- Container Tabela -->
                        <div class="datatable-container w-100 overflow-auto position-relative">
                            <?php
                            // Construir link ordenação
                            $buildSortUrl = function ($column) use ($search_query, $sort_param, $dir_param) {
                                $params = [];
                                if ($search_query !== '')
                                    $params['search'] = $search_query;
                                $params['sort'] = $column;
                                $params['dir'] = ($sort_param === $column && $dir_param === 'asc') ? 'desc' : 'asc';
                                return '?' . http_build_query($params);
                            };

                            // Obter ícone ordenação
                            $getSortIcon = function ($column) use ($sort_param, $dir_param) {
                                if ($sort_param !== $column)
                                    return '';
                                return $dir_param === 'asc' ? ' ↑' : ' ↓';
                            };
                            ?>
                            <!-- Tabela Transferências -->
                            <table id="globalTransfersTable" class="heba-table w-100 display datatable-table">
                                <!-- Cabeçalho -->
                                <thead>
                                    <tr>
                                        <!-- Coluna Data -->
                                        <th><a href="<?= $buildSortUrl('dataCriacao') ?>"
                                                class="datatable-sorter text-decoration-none text-inherit">DATA<?= $getSortIcon('dataCriacao') ?></a>
                                        </th>
                                        <!-- Coluna Responsável -->
                                        <th><a href="<?= $buildSortUrl('utilizador') ?>"
                                                class="datatable-sorter text-decoration-none text-inherit">RESPONSÁVEL<?= $getSortIcon('utilizador') ?></a>
                                        </th>
                                        <!-- Coluna Equipamento -->
                                        <th><a href="<?= $buildSortUrl('equipamento') ?>"
                                                class="datatable-sorter text-decoration-none text-inherit">EQUIPAMENTO<?= $getSortIcon('equipamento') ?></a>
                                        </th>
                                        <!-- Coluna Serviço Antigo -->
                                        <th>SERVIÇO ANTIGO</th>
                                        <!-- Coluna Serviço Novo -->
                                        <th>NOVO SERVIÇO</th>
                                    </tr>
                                </thead>
                                <!-- Corpo -->
                                <tbody>

                                    <?php foreach ($transferencias as $item): ?>
                                            <!-- Linha Transferência -->
                                            <tr>
                                                <!-- Célula Data -->
                                                <td>
                                                    <!-- Texto Data -->
                                                    <span class="text-secondary fw-400"><?= htmlspecialchars($item['data']) ?></span>
                                                </td>
                                                <!-- Célula Responsável -->
                                                <td>
                                                    <!-- Texto Responsável -->
                                                    <span
                                                        class="text-secondary fw-400"><?= htmlspecialchars($item['utilizador']) ?></span>
                                                </td>
                                                <!-- Célula Equipamento -->
                                                <td>
                                                    <!-- Link Equipamento -->
                                                    <a href="<?= htmlspecialchars($item['url']) ?>"
                                                        class="text-primary-500 text-decoration-none fw-700">
                                                        <?= $item['equipamento'] ?>
                                                    </a>
                                                </td>
                                                <!-- Célula Serviço Antigo -->
                                                <td>
                                                    <!-- Texto Serviço Antigo -->
                                                    <span class="text-secondary fw-400"><?= $item['localizacaoAntiga'] ?></span>
                                                </td>
                                                <!-- Célula Serviço Novo -->
                                                <td>
                                                    <!-- Texto Serviço Novo -->
                                                    <span class="fw-500"><?= $item['localizacaoNova'] ?></span>
                                                </td>
                                            </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                        <!-- Wrapper Paginação -->
                        <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                            <!-- Info Paginação -->
                            <div class="datatable-info">
                                A mostrar
                                <?= $totalLogsFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalLogsFiltered) ?>
                                de <?= $totalLogsFiltered ?> registos
                            </div>
                            <!-- Navegação Paginação -->
                            <nav class="datatable-pagination">
                                <!-- Lista Paginação -->
                                <ul class="datatable-pagination-list">
                                    <?php
                                    // Construir link paginação
                                    $buildQueryString = function ($newPage) use ($search_query, $sort_param, $dir_param) {
                                        $params = ['page' => $newPage];
                                        if ($search_query !== '')
                                            $params['search'] = $search_query;
                                        if ($sort_param !== 'dataCriacao')
                                            $params['sort'] = $sort_param;
                                        if ($dir_param !== 'desc')
                                            $params['dir'] = $dir_param;
                                        return '?' . http_build_query($params);
                                    };
                                    ?>

                                    <?php if ($current_page > 1): ?>
                                            <!-- Página Anterior -->
                                            <li class="datatable-pagination-list-item pager"><a
                                                    href="<?= $buildQueryString($current_page - 1) ?>">‹</a></li>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $current_page - 2); $i <= min($totalPages, $current_page + 2); $i++): ?>
                                            <!-- Número Página -->
                                            <li
                                                class="datatable-pagination-list-item <?= $i === $current_page ? 'datatable-active' : '' ?>">
                                                <a href="<?= $buildQueryString($i) ?>"><?= $i ?></a>
                                            </li>
                                    <?php endfor; ?>

                                    <?php if ($current_page < $totalPages): ?>
                                            <!-- Próxima Página -->
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


<!-- Wrapper Toasts -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 9999;">
    <?php if (!empty($success_message)): ?>
            <!-- Toast Sucesso -->
            <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <!-- Wrapper Toast -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Corpo Toast -->
                    <div class="toast-body fw-500 p-0">
                        <?= htmlspecialchars($success_message) ?>
                    </div>
                    <!-- Botão Fechar -->
                    <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                        aria-label="Close">
                        <!-- SVG Fechar -->
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
            <!-- Toast Erro -->
            <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4 show" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <!-- Wrapper Toast -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Corpo Toast -->
                    <div class="toast-body fw-500 p-0">
                        <?= htmlspecialchars($server_error) ?>
                    </div>
                    <!-- Botão Fechar -->
                    <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                        aria-label="Close">
                        <!-- SVG Fechar -->
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
// Carregar dependências
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>