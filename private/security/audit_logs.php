<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Restringir acesso
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
    // Ligar à BD
    $ligacao = connect_to_db();
    $whereConditions = ["1 = 1"];
    $params = [];

    if ($search_query !== '') {
        // Desencriptar ID
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "ha.idAuditoria = :searchId OR ha.idRegistoAfetado = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(ha.idAuditoria = :searchExact OR ha.idRegistoAfetado = :searchExact OR ha.acao LIKE :search OR p.nome LIKE :search OR ha.campoAfetado LIKE :search OR ha.valorAntigo LIKE :search OR ha.valorNovo LIKE :search OR ha.tabelaAfetada LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            $whereConditions[] = "(ha.acao LIKE :search OR p.nome LIKE :search OR ha.campoAfetado LIKE :search OR ha.valorAntigo LIKE :search OR ha.valorNovo LIKE :search OR ha.tabelaAfetada LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
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

    // Query BD
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

    // Query BD
    $stmt = execute_query($dataSql, $params, $ligacao);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data = $row['dataCriacao'] ? new DateTime($row['dataCriacao']) : new DateTime();

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

        if (!$searchUrl && in_array($row['tabelaAfetada'], ['Documento', 'Manutencao', 'GarantiaContrato'])) {
            $idEquipamento = null;
            $nav = '';
            $tabelaSub = $row['tabelaAfetada'];
            $idSub = $row['idRegistoAfetado'];

            try {
                $stmtSub = null;
                if ($tabelaSub === 'Documento') {
                    // Query BD
                    $stmtSub = execute_query("SELECT idEquipamento FROM Documento WHERE idDocumento = :id", ['id' => $idSub], $ligacao);
                    $nav = 'documentos';
                } elseif ($tabelaSub === 'Manutencao') {
                    // Query BD
                    $stmtSub = execute_query("SELECT idEquipamento FROM Manutencao WHERE idManutencao = :id", ['id' => $idSub], $ligacao);
                    $nav = 'manutencoes';
                } elseif ($tabelaSub === 'GarantiaContrato') {
                    // Query BD
                    $stmtSub = execute_query("SELECT idEquipamento FROM GarantiaContrato WHERE idGarantiaContrato = :id", ['id' => $idSub], $ligacao);
                    $nav = 'garantias';
                }

                if ($stmtSub) {
                    $rowSub = $stmtSub->fetch(PDO::FETCH_ASSOC);
                    if ($rowSub && !empty($rowSub['idEquipamento'])) {
                        $idEquipamento = $rowSub['idEquipamento'];
                        $encEqId = aes_encrypt((string) $idEquipamento);
                        $searchUrl = BASE_URL . "/private/inventory/equipments/detailed_view.php?id=" . urlencode($encEqId) . "&nav=" . $nav;
                    }
                }
            } catch (Exception $e) {
                // Capturar erro
// Silently handle if record doesn't exist or other error
            }
        }

        $idRender = htmlspecialchars($truncatedId);
        if ($searchUrl) {
            // Gerar link
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
    // Capturar erro
    $server_error = "Erro ao carregar histórico de auditoria: " . $e->getMessage();
}


// Carregar dependências
include_once BASE_PATH . 'private/includes/head.php';
// Carregar dependências
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php // Carregar dependências
    include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="padding-6 gap-6 d-flex flex-column padding-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <div class="d-flex flex-column gap-1">
                <!-- Título -->
                <h1>Logs de Auditoria</h1>
                <!-- Texto -->
                <p class="text-secondary fw-400"><?= htmlspecialchars($totalLogsFiltered ?? count($auditoria)) ?>
                    registos encontrados</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div
            class="bento-card padding-4 gap-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
            <!-- Formulário -->
            <form action="" method="GET" style="display: contents;">
                <div class="form-item position-relative flex-grow-1">
                    <!-- SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <!-- Input -->
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
                <div class="d-flex gap-2 equipment-list-search-bar-filters flex-column flex-md-row">
                    <!-- Select -->
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
                    <!-- SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell-off-icon lucide-bell-off">
                        <path d="M9 10h.01" />
                        <path d="M15 10h.01" />
                        <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem Registos</h3>
                    <!-- Texto -->
                    <p class="text-secondary m-0">De momento não existe nenhum registo de auditoria.</p>
                </div>
            </div>
        <?php elseif (empty($auditoria)): ?>
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG -->
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
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem resultados</h3>
                    <!-- Texto -->
                    <p class="text-secondary m-0">Nenhum registo encontrado correspondente à sua pesquisa.</p>
                </div>
            </div>
        <?php else: ?>

            <!-- Lista de Logs de Auditoria -->
            <div class="bento-card w-100 p-0 border-0">
                <div class="datatable-wrapper no-footer sortable fixed-columns">
                    <div class="datatable-container w-100 overflow-auto position-relative">
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
                        <!-- Tabela -->
                        <table id="globalAuditTable" class="heba-table w-100 display datatable-table">
                            <thead>
                                <!-- Linha -->
                                <tr>
                                    <!-- Coluna -->
                                    <th><a href="<?= $buildSortUrl('dataCriacao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">DATA<?= $getSortIcon('dataCriacao') ?></a>
                                    </th>
                                    <!-- Coluna -->
                                    <th><a href="<?= $buildSortUrl('acao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">AÇÃO<?= $getSortIcon('acao') ?></a>
                                    </th>
                                    <!-- Coluna -->
                                    <th><a href="<?= $buildSortUrl('utilizador') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">UTILIZADOR<?= $getSortIcon('utilizador') ?></a>
                                    </th>
                                    <!-- Coluna -->
                                    <th><a href="<?= $buildSortUrl('tabela') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">TABELA<?= $getSortIcon('tabela') ?></a>
                                    </th>
                                    <!-- Coluna -->
                                    <th>ID REGISTO</th>
                                    <!-- Coluna -->
                                    <th>CAMPO</th>
                                    <!-- Coluna -->
                                    <th>VALOR ANTIGO</th>
                                    <!-- Coluna -->
                                    <th>VALOR NOVO</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($auditoria as $item): ?>
                                    <!-- Linha -->
                                    <tr>
                                        <!-- Coluna -->
                                        <td>
                                            <!-- Texto -->
                                            <span class="text-secondary fw-400"><?= htmlspecialchars($item['data']) ?></span>
                                        </td>
                                        <!-- Coluna -->
                                        <td>
                                            <span
                                                class="badge <?= htmlspecialchars($item['badgeClass']) ?>"><?= htmlspecialchars($item['acao']) ?></span>
                                        </td>
                                        <!-- Coluna -->
                                        <td>
                                            <span
                                                class="text-secondary fw-400"><?= htmlspecialchars($item['utilizador']) ?></span>
                                        </td>
                                        <!-- Coluna -->
                                        <td>
                                            <span
                                                class="text-secondary fw-400 text-capitalize"><?= htmlspecialchars($item['tabela']) ?></span>
                                        </td>
                                        <!-- Coluna -->
                                        <td>
                                            <!-- Texto -->
                                            <span class="text-secondary fw-400"><?= $item['idRegisto'] ?></span>
                                        </td>
                                        <!-- Coluna -->
                                        <td>
                                            <!-- Texto -->
                                            <span class="text-secondary fw-400"><?= htmlspecialchars($item['campo']) ?></span>
                                        </td>
                                        <!-- Coluna -->
                                        <td>
                                            <span
                                                class="text-secondary fw-400"><?= htmlspecialchars($item['valorAntigo']) ?></span>
                                        </td>
                                        <!-- Coluna -->
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
                            <!-- Lista -->
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
                                    <!-- Item -->
                                    <li class="datatable-pagination-list-item pager"><a
                                            href="<?= $buildQueryString($current_page - 1) ?>">‹</a></li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $current_page - 2); $i <= min($totalPages, $current_page + 2); $i++): ?>
                                    <li
                                        class="datatable-pagination-list-item <?= $i === $current_page ? 'datatable-active' : '' ?>">
                                        <!-- Link -->
                                        <a href="<?= $buildQueryString($i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($current_page < $totalPages): ?>
                                    <!-- Item -->
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
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 9999;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <!-- Botão -->
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <!-- SVG -->
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
                <!-- Botão -->
                <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <!-- SVG -->
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
// Carregar dependências
include_once BASE_PATH . 'private/includes/footer.php';
?>