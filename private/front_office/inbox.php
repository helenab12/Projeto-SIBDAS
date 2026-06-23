<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['view.inbox']);
// Carregar dependências
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

$EstadoPedidoDemonstracaos = [
    'Novo' => new EstadoPedidoDemonstracao('Novo', 'new'),
    'Em Contacto' => new EstadoPedidoDemonstracao('Em Contacto', 'in-contact'),
    'Fechado' => new EstadoPedidoDemonstracao('Fechado', 'concluded')
];

$PedidoDemonstracaos = [];
$validation_errors = [];
if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}

$server_error = null;
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

$success_message = null;
if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'dataCriacao';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'asc') ? 'asc' : 'desc'; // default desc
$items_per_page = 8;

try {
    // Ligar à BD
    $ligacao = connect_to_db();
    $whereConditions = ["ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);

        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "idPedido = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(idPedido = :searchExact OR nomeContacto LIKE :search OR emailContacto LIKE :search OR organizacao LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            // Construir query
            $whereConditions[] = "(nomeContacto LIKE :search OR emailContacto LIKE :search OR organizacao LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Consultar registos
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM PedidoDemonstracao WHERE ativo = 1", [], $ligacao);
    $totalPedidosAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    $countSql = "SELECT COUNT(idPedido) as total FROM PedidoDemonstracao WHERE $whereSQL";
    // Consultar registos
    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalPedidosFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalPedidosFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Inicializar variáveis
    $allowed_sorts = [
        'dataCriacao' => 'dataCriacao',
        'estado' => 'estado',
        'nomeContacto' => 'nomeContacto',
        'organizacao' => 'organizacao',
        'emailContacto' => 'emailContacto'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'dataCriacao';
    $sort_dir = strtoupper($dir_param);

    $dataSql = "SELECT * FROM PedidoDemonstracao WHERE $whereSQL ORDER BY $sort_field $sort_dir LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;
    // Consultar registos
    $stmt = execute_query($dataSql, $params, $ligacao);
    $requests = $stmt->fetchAll(PDO::FETCH_OBJ);

    $months = [
        'Jan',
        'Fev',
        'Mar',
        'Abr',
        'Mai',
        'Jun',
        'Jul',
        'Ago',
        'Set',
        'Out',
        'Nov',
        'Dez'
    ];

    foreach ($requests as $row) {
        $estadoStr = $row->estado;
        $stateObj = $EstadoPedidoDemonstracaos[$estadoStr] ?? new EstadoPedidoDemonstracao($estadoStr, 'new');

        $dateObj = new DateTime($row->dataCriacao);
        $day = $dateObj->format('d');
        $mNum = (int) $dateObj->format('n');
        $year = $dateObj->format('Y');
        $time = $dateObj->format('H:i');
        $formattedDate = "{$day} {$months[$mNum]} {$year}, {$time}";

        $PedidoDemonstracaos[] = new PedidoDemonstracao(
            (int) $row->idPedido,
            $stateObj,
            $formattedDate,
            $row->nomeContacto,
            $row->organizacao ?? '',
            $row->emailContacto,
            $row->mensagem ?? ''
        );
    }
    $ligacao = null;
} catch (Exception $e) {
    // Capturar erro
    $server_error = "Erro ao carregar dados do servidor: " . $e->getMessage();
}

?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php
    // Carregar dependências
    include_once BASE_PATH . 'private/includes/headers.php';
    ?>

    <!-- Conteúdo -->
    <section class="inbox d-flex flex-column flex-grow-1">
        <div class="d-flex flex-column padding-6 gap-6 flex-grow-1">
            <!-- Título -->
            <div
                class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
                <div class="d-flex flex-column gap-1">
                    <!-- Título -->
                    <h1>Caixa de Entrada</h1>
                    <!-- Texto -->
                    <p class="text-secondary fw-400">Gestão dos pedidos de demonstração do Website.</p>
                </div>
            </div>

            <!-- Barra de Pesquisa -->
            <div
                class="bento-card padding-4 gap-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
                <!-- Formulário -->
                <form action="" method="GET" style="display: contents;">
                    <div class="flex-grow-1">
                        <div class="form-item w-100 position-relative">
                            <!-- SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                            <!-- Input -->
                            <input type="text" class="form-item w-100 search-bar-input" name="search"
                                id="search-input-field" placeholder="Pesquisar por nome ou organização..."
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
                    </div>
                </form>
            </div>

            <?php if ($totalPedidosAll === 0): ?>
                <div
                    class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100 shadow-none">
                    <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                        <!-- SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-ghost">
                            <path d="M9 10h.01" />
                            <path d="M15 10h.01" />
                            <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" />
                        </svg>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <!-- Título -->
                        <h3 class="fw-700 m-0">Sem Pedidos de Demonstração</h3>
                        <!-- Texto -->
                        <p class="text-secondary m-0">De momento não existe nenhum pedido pendente.</p>
                    </div>
                </div>
            <?php elseif (empty($PedidoDemonstracaos)): ?>
                <div
                    class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100 shadow-none">
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

                <!-- Formulário -->
                <form method="POST" action="inbox-crud/update-inbox.php" class="d-flex flex-column flex-grow-1 mw-0">
                    <!-- Tabela -->
                    <div class="bento-card w-100 p-0 border-0">
                        <div class="datatable-wrapper no-footer sortable fixed-columns">
                            <div class="datatable-container w-100 overflow-auto position-relative">
                                <?php
                                $buildSortUrl = function ($column) use ($search_query, $sort_param, $dir_param) {
                                    $params = [];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
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
                                <table id="equipmentsTable" class="heba-table w-100 display datatable-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <!-- Link -->
                                                <a href="<?= $buildSortUrl('estado') ?>"
                                                    class="datatable-sorter text-decoration-none text-inherit">ESTADO<?= $getSortIcon('estado') ?></a>
                                            </th>
                                            <th>
                                                <!-- Link -->
                                                <a href="<?= $buildSortUrl('dataCriacao') ?>"
                                                    class="datatable-sorter text-decoration-none text-inherit">DATA<?= $getSortIcon('dataCriacao') ?></a>
                                            </th>
                                            <th>
                                                <!-- Link -->
                                                <a href="<?= $buildSortUrl('nomeContacto') ?>"
                                                    class="datatable-sorter text-decoration-none text-inherit">NOME
                                                    CONTACTO<?= $getSortIcon('nomeContacto') ?></a>
                                            </th>
                                            <th>
                                                <!-- Link -->
                                                <a href="<?= $buildSortUrl('organizacao') ?>"
                                                    class="datatable-sorter text-decoration-none text-inherit">INSTITUIÇÃO<?= $getSortIcon('organizacao') ?></a>
                                            </th>
                                            <th>
                                                <!-- Link -->
                                                <a href="<?= $buildSortUrl('emailContacto') ?>"
                                                    class="datatable-sorter text-decoration-none text-inherit">EMAIL<?= $getSortIcon('emailContacto') ?></a>
                                            </th>
                                            <th class="text-end">AÇÕES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($PedidoDemonstracaos as $request):
                                            $encryptedId = aes_encrypt($request->id);
                                            ?>
                                            <tr>
                                                <td>
                                                    <!-- Input -->
                                                    <input type="hidden" name="states[<?php echo $encryptedId; ?>]"
                                                        value="<?php echo $request->state->name; ?>"
                                                        id="inbox-state-input-<?php echo $encryptedId; ?>">
                                                    <div class="dropdown">
                                                        <!-- Botão -->
                                                        <button id="inbox-state-btn-<?php echo $encryptedId; ?>"
                                                            class="d-inline-flex align-items-center equipment-badge d-inline-flex align-items-center justify-content-center fw-500  <?php echo $request->state->class; ?> gap-1 mw-0 border-0 <?= tem_permissao('inbox.manage') ? '' : 'pe-none' ?>"
                                                            type="button" <?= tem_permissao('inbox.manage') ? 'data-bs-toggle="dropdown"' : '' ?> aria-expanded="false">
                                                            <span><?php echo $request->state->name; ?>
                                                                <span
                                                                    class="visually-hidden"><?= htmlspecialchars($encryptedId) ?></span></span>
                                                            <?php if (tem_permissao('inbox.manage')): ?>
                                                                <!-- SVG -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                                                    <path d="m6 9 6 6 6-6" />
                                                                </svg>
                                                            <?php endif; ?>
                                                        </button>
                                                        <?php if (tem_permissao('inbox.manage')): ?>
                                                            <ul class="dropdown-menu action-dropdown-menu padding-2">
                                                                <li>
                                                                    <!-- Link -->
                                                                    <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none "
                                                                        href="#"
                                                                        onclick="changeInboxState('<?php echo $encryptedId; ?>', 'Novo', 'new')">Novo</a>
                                                                </li>
                                                                <li>
                                                                    <!-- Link -->
                                                                    <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none "
                                                                        href="#"
                                                                        onclick="changeInboxState('<?php echo $encryptedId; ?>', 'Em Contacto', 'in-contact')">Em
                                                                        Contacto</a>
                                                                </li>
                                                                <li>
                                                                    <!-- Link -->
                                                                    <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none "
                                                                        href="#"
                                                                        onclick="changeInboxState('<?php echo $encryptedId; ?>', 'Fechado', 'concluded')">Fechado</a>
                                                                </li>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2 text-secondary align-items-center">
                                                        <p><?php echo $request->date; ?></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="d-flex flex-column">
                                                            <p class="equipment-title fw-700 mb-0"><?php echo $request->name; ?>
                                                            </p>
                                                            <span class="visually-hidden"><?php echo $encryptedId; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-secondary fw-400"><?php echo $request->institution; ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-secondary fw-400"><?php echo $request->email; ?></span>
                                                </td>
                                                <td class="text-end equipment-actions">
                                                    <div class="dropdown">
                                                        <!-- Botão -->
                                                        <button
                                                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <!-- SVG -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <circle cx="12" cy="12" r="1" />
                                                                <circle cx="19" cy="12" r="1" />
                                                                <circle cx="5" cy="12" r="1" />
                                                            </svg>
                                                        </button>
                                                        <ul
                                                            class="dropdown-menu dropdown-menu-end action-dropdown-menu padding-2">
                                                            <li>
                                                                <!-- Link -->
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-primary"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#inbox-detail-modal-<?php echo $encryptedId; ?>">
                                                                    <!-- SVG -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="lucide lucide-eye">
                                                                        <path
                                                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                                        <circle cx="12" cy="12" r="3" />
                                                                    </svg>
                                                                    Ver Detalhes
                                                                </a>
                                                            </li>
                                                            <?php if (tem_permissao('inbox.delete')): ?>
                                                                <li>
                                                                    <!-- Link -->
                                                                    <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                                        href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#delete-confirm-modal-<?php echo $encryptedId; ?>">
                                                                        <!-- SVG -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
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
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                            <div class="datatable-info">
                                A mostrar
                                <?= $totalPedidosFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalPedidosFiltered) ?>
                                de <?= $totalPedidosFiltered ?> registos
                            </div>
                            <nav class="datatable-pagination">
                                <ul class="datatable-pagination-list">
                                    <?php
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
        </div>

        <?php if (tem_permissao('inbox.manage')): ?>
            <!-- Wrapper Alterações -->
            <div class="inbox-changes-container position-sticky w-100 mt-auto justify-content-between align-items-center padding-6"
                style="display: none;">
                <!-- Texto -->
                <p class="text-muted">Existem alterações pendentes</p>
                <!-- Botão -->
                <button type="submit" class="btn btn-primary btn-glowing gap-2">
                    <!-- SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg>
                    Guardar alterações
                </button>
            </div>
        <?php endif; ?>
        </form>
    </section>

    <!-- Modal -->
    <?php foreach ($PedidoDemonstracaos as $request):
        $encryptedId = aes_encrypt($request->id);
        ?>
        <div class="modal fade" id="inbox-detail-modal-<?php echo $encryptedId; ?>" tabindex="-1"
            aria-labelledby="inboxDetailModalLabel-<?php echo $encryptedId; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Título -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <!-- Título -->
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="inboxDetailModalLabel-<?php echo $encryptedId; ?>">Detalhes do Pedido de
                                Demonstração</h2>
                        </div>
                        <!-- Botão -->
                        <button type="button" class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <!-- SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Wrapper Modal -->
                    <div class="modal-body padding-6 d-flex flex-column gap-4">
                        <!-- Contact info row -->
                        <div class="d-flex align-items-center gap-3">
                            <div
                                class="d-flex justify-content-center align-items-center text-secondary fw-700 position-relative inbox-modal-user-icon rounded-pill">
                                <!-- SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-user text-secondary">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <!-- Título -->
                                <h3 class="fw-700 mb-0 text-secondary"><?php echo $request->name; ?></h3>
                                <!-- Texto -->
                                <p class="text-primary d-flex align-items-center gap-1 text-primary-500">
                                    <!-- SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-building-2">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18" />
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                                        <path d="M10 6h4" />
                                        <path d="M10 10h4" />
                                        <path d="M10 14h4" />
                                        <path d="M10 18h4" />
                                    </svg>
                                    <?php echo $request->institution; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="inbox-info-grid flex-column flex-md-row d-flex gap-4 w-100">
                            <div class="inbox-info-card w-100 w-md-50 d-flex flex-column padding-3 gap-2">
                                <span class="text-secondary d-flex align-items-center gap-1 fw-500 text-uppercase">
                                    <!-- SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-mail">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                    </svg>
                                    Email Profissional
                                </span>
                                <p class="inbox-info-card-value fw-600 text-primary"><?php echo $request->email; ?></p>
                            </div>
                            <div class="inbox-info-card w-100 w-md-50 d-flex flex-column padding-3 gap-2">
                                <span class="text-secondary d-flex align-items-center gap-1 fw-500 text-uppercase">
                                    <!-- SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-calendar">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                        <line x1="16" x2="16" y1="2" y2="6" />
                                        <line x1="8" x2="8" y1="2" y2="6" />
                                        <line x1="3" x2="21" y1="10" y2="10" />
                                    </svg>
                                    Data Submissão
                                </span>
                                <p class="inbox-info-card-value fw-600 text-primary"><?php echo $request->date; ?></p>
                            </div>
                        </div>

                        <!-- Message Box -->
                        <div class="inbox-message-box padding-3 d-flex flex-column gap-2">
                            <span class="text-primary-500 d-flex align-items-center gap-1 fw-700 text-uppercase">
                                <!-- SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-message-square">
                                    <path
                                        d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" />
                                </svg>
                                Mensagem Original
                            </span>
                            <p class="mb-0 fst-italic text-primary">
                                "<?php echo $request->message; ?>"
                            </p>
                        </div>

                        <!-- Footer Actions -->
                        <div class="inbox-modal-footer d-flex w-100 justify-content-between align-items-center">
                            <span id="inbox-modal-badge-<?php echo $encryptedId; ?>"
                                class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  <?php echo $request->state->class; ?> inbox-modal-footer-badge fw-400">
                                Tratamento atual: <?php echo $request->state->name; ?>
                            </span>
                            <!-- Botão -->
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (tem_permissao('inbox.delete')): ?>
            <!-- Modal -->
            <div class="modal fade" id="delete-confirm-modal-<?php echo $encryptedId; ?>" tabindex="-1"
                aria-labelledby="deleteModalLabel-<?php echo $encryptedId; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                    <div class="modal-content custom-modal-content d-flex flex-column">
                        <!-- Título -->
                        <div
                            class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                            <div class="d-flex flex-column">
                                <!-- Título -->
                                <h2 class="equipment-creation-modal-title modal-title"
                                    id="deleteModalLabel-<?php echo $encryptedId; ?>">Eliminar Pedido</h2>
                                <span class="text-secondary fw-400">O pedido de demonstração será movido para a
                                    reciclagem.</span>
                            </div>
                            <!-- Botão -->
                            <button type="button" class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                                data-bs-dismiss="modal" aria-label="Close">
                                <!-- SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-x-icon lucide-x stroke-secondary">
                                    <path d="M18 6 6 18" />
                                    <path d="m6 6 12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Wrapper Modal -->
                        <div class="modal-body p-0">
                            <div
                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                <div class="d-flex flex-column align-items-center gap-4">
                                    <div class="d-flex padding-3 danger-icon">
                                        <!-- SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-triangle-alert-icon lucide-triangle-alert">
                                            <path
                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                            <path d="M12 9v4" />
                                            <path d="M12 17h.01" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                            <p class="text-secondary">Tem a certeza que deseja apagar este pedido?</p>
                                            <!-- Título -->
                                            <h2 class="fw-700">"<?php echo htmlspecialchars($request->name); ?>"?
                                            </h2>
                                            <!-- Texto -->
                                            <span class="text-muted">Tipo: Pedido de Demonstração</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botão -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <!-- Botão -->
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <!-- Formulário -->
                                    <form action="inbox-crud/delete-inbox.php" method="POST" class="m-0 p-0">
                                        <!-- Input -->
                                        <input type="hidden" name="id" value="<?php echo $encryptedId; ?>">
                                        <!-- Botão -->
                                        <button type="submit" class="btn btn-danger btn-glowing text-white">
                                            Sim, Apagar.
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 9999;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4" role="alert"
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

    <?php if (!empty($validation_errors) || !empty($server_error)): ?>

        <?php
        $all_errors = [];
        if (!empty($validation_errors)) {
            $all_errors = array_merge($all_errors, $validation_errors);
        }
        if (!empty($server_error)) {
            $all_errors[] = $server_error;
        }
        ?>
        <?php foreach ($all_errors as $error): ?>
            <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex align-items-center gap-2">
                    <div class="toast-body fw-500 p-0">
                        <?= htmlspecialchars($error) ?>
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
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
// Carregar dependências
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>