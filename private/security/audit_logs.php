<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.audit.logs']);

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
$acao_filter = isset($_GET['acao']) ? trim($_GET['acao']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'dataCriacao';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'asc') ? 'asc' : 'desc'; // default desc
$items_per_page = 8;

$auditoria = [];
try {
    $ligacao = connect_to_db();
    $whereConditions = ["1 = 1"];
    $params = [];

    if ($search_query !== '') {
        $whereConditions[] = "(ha.acao LIKE :search OR p.nome LIKE :search OR ha.campoAfetado LIKE :search OR ha.valorAntigo LIKE :search OR ha.valorNovo LIKE :search OR ha.tabelaAfetada LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    if ($acao_filter !== '') {
        $whereConditions[] = "ha.acao = :acao";
        $params['acao'] = $acao_filter;
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total sem filtros
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM HistoricoAuditoria", [], $ligacao);
    $totalLogsAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total
    $countSql = "SELECT COUNT(ha.idAuditoria) as total
                 FROM HistoricoAuditoria ha
                 LEFT JOIN Utilizador u ON ha.idUtilizador = u.idUtilizador
                 LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalLogsFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalLogsFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'dataCriacao' => 'ha.dataCriacao',
        'acao' => 'ha.acao',
        'utilizador' => 'p.nome',
        'tabela' => 'ha.tabelaAfetada'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'ha.dataCriacao';
    $sort_dir = strtoupper($dir_param);

    $dataSql = "SELECT ha.*, p.nome AS nomeUtilizador 
         FROM HistoricoAuditoria ha
         LEFT JOIN Utilizador u ON ha.idUtilizador = u.idUtilizador
         LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa
         WHERE $whereSQL
         ORDER BY $sort_field $sort_dir
         LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmt = execute_query($dataSql, $params, $ligacao);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data = new DateTime($row['dataCriacao']);

        $badgeClass = 'badge-primary';
        if ($row['acao'] === 'Criação') {
            $badgeClass = 'badge-success';
        } else if ($row['acao'] === 'Remoção') {
            $badgeClass = 'badge-error';
        }

        $encryptedId = aes_encrypt((string) $row['idRegistoAfetado']);
        $truncatedId = substr($encryptedId, 0, 4) . '...';

        $searchUrl = match ($row['tabelaAfetada']) {
            'Equipamento' => BASE_URL . "/private/inventory/equipments/equipment_list.php?search=" . urlencode($encryptedId),
            'Componente' => BASE_URL . "/private/inventory/components.php?search=" . urlencode($encryptedId),
            'CategoriaEquipamento' => BASE_URL . "/private/inventory/categories.php?search=" . urlencode($encryptedId),
            'Fornecedor' => BASE_URL . "/private/entities/suppliers.php?search=" . urlencode($encryptedId),
            'PedidoDemonstracao' => BASE_URL . "/private/front_office/inbox.php?search=" . urlencode($encryptedId),
            'Pessoa' => BASE_URL . "/private/entities/people_management.php?search=" . urlencode($encryptedId),
            'Utilizador' => BASE_URL . "/private/security/users.php?search=" . urlencode($encryptedId),
            'Perfil' => BASE_URL . "/private/security/profiles.php?search=" . urlencode($encryptedId),
            'Permissao' => BASE_URL . "/private/security/permissions.php?search=" . urlencode($encryptedId),
            'Edificio', 'Piso', 'Servico', 'Localizacao' => BASE_URL . "/private/entities/locations.php?search=" . urlencode($encryptedId),
            default => null
        };

        $idRender = htmlspecialchars($truncatedId);
        if ($searchUrl) {
            $idRender = "<a href=\"" . htmlspecialchars($searchUrl) . "\" class=\"text-primary-500 text-decoration-none fw-700\">" . htmlspecialchars($truncatedId) . "</a>";
        }

        $auditoria[] = [
            'data' => $data->format('d/m/Y, H:i:s'),
            'acao' => $row['acao'],
            'badgeClass' => $badgeClass,
            'utilizador' => $row['nomeUtilizador'] ?? 'Sistema',
            'tabela' => $row['tabelaAfetada'],
            'idRegisto' => $idRender,
            'campo' => $row['campoAfetado'] ?? '-',
            'valorAntigo' => $row['valorAntigo'] ?? '-',
            'valorNovo' => $row['valorNovo'] ?? '-'
        ];
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar histórico de auditoria: " . $e->getMessage();
}


include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Logs de Auditoria</h1>
                <p class="text-secondary fw-400"><?= htmlspecialchars($totalLogsFiltered ?? count($auditoria)) ?>
                    registos encontrados</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-outline gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-download-icon lucide-download">
                        <path d="M12 15V3" />
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="m7 10 5 5 5-5" />
                    </svg>
                    Exportar Logs
                </button>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
            <form action="" method="GET" style="display: contents;">
                <div class="form-item position-relative flex-grow-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" name="search" id="search-input-field"
                        placeholder="Pesquisar por ação, utilizador ou detalhes..."
                        value="<?= htmlspecialchars($search_query) ?>">
                    <?php if ($search_query !== ''): ?>
                        <script>
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
                <div class="d-flex gap-2 equipment-list-search-bar-filters">
                    <select class="form-select" name="acao" aria-label="Filtro Ações" onchange="this.form.submit()">
                        <option value="" <?= $acao_filter === '' ? 'selected' : '' ?>>Todas as ações</option>
                        <option value="Criação" <?= $acao_filter === 'Criação' ? 'selected' : '' ?>>Criação</option>
                        <option value="Edição" <?= $acao_filter === 'Edição' ? 'selected' : '' ?>>Edição</option>
                        <option value="Remoção" <?= $acao_filter === 'Remoção' ? 'selected' : '' ?>>Remoção</option>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($totalLogsAll === 0): ?>
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
                    <h3 class="fw-700 m-0">Sem Registos</h3>
                    <p class="text-secondary m-0">De momento não existe nenhum registo de auditoria.</p>
                </div>
            </div>
        <?php elseif (empty($auditoria)): ?>
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

            <!-- Lista de Logs de Auditoria -->
            <div class="bento-card w-100 p-0 border-0">
                <div class="datatable-wrapper no-footer sortable fixed-columns">
                    <div class="datatable-container">
                        <?php
                        $buildSortUrl = function ($column) use ($search_query, $acao_filter, $sort_param, $dir_param) {
                            $params = [];
                            if ($search_query !== '')
                                $params['search'] = $search_query;
                            if ($acao_filter !== '')
                                $params['acao'] = $acao_filter;
                            $params['sort'] = $column;
                            $params['dir'] = ($sort_param === $column && $dir_param === 'asc') ? 'desc' : 'asc';
                            return '?' . http_build_query($params);
                        };

                        $getSortIcon = function ($column) use ($sort_param, $dir_param) {
                            if ($sort_param !== $column)
                                return '';
                            return $dir_param === 'asc' ? ' ↑' : ' ↓';
                        };
                        ?>
                        <table id="globalAuditTable" class="sibdas-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('dataCriacao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">DATA<?= $getSortIcon('dataCriacao') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('acao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">AÇÃO<?= $getSortIcon('acao') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('utilizador') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">UTILIZADOR<?= $getSortIcon('utilizador') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('tabela') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">TABELA<?= $getSortIcon('tabela') ?></a>
                                    </th>
                                    <th>ID REGISTO</th>
                                    <th>CAMPO</th>
                                    <th>VALOR ANTIGO</th>
                                    <th>VALOR NOVO</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($auditoria as $item): ?>
                                    <tr>
                                        <td>
                                            <span class="text-secondary fw-400"><?= htmlspecialchars($item['data']) ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge <?= htmlspecialchars($item['badgeClass']) ?>"><?= htmlspecialchars($item['acao']) ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary fw-400"><?= htmlspecialchars($item['utilizador']) ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary fw-400 text-capitalize"><?= htmlspecialchars($item['tabela']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary fw-400"><?= $item['idRegisto'] ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary fw-400"><?= htmlspecialchars($item['campo']) ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary fw-400"><?= htmlspecialchars($item['valorAntigo']) ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary fw-400"><?= htmlspecialchars($item['valorNovo']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                        <div class="datatable-info">
                            A mostrar
                            <?= $totalLogsFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalLogsFiltered) ?>
                            de <?= $totalLogsFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                $buildQueryString = function ($newPage) use ($search_query, $acao_filter, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($acao_filter !== '')
                                        $params['acao'] = $acao_filter;
                                    if ($sort_param !== 'dataCriacao')
                                        $params['sort'] = $sort_param;
                                    if ($dir_param !== 'desc')
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
                                        <a href="<?= $buildQueryString($i) ?>"><?= $i ?></a>
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