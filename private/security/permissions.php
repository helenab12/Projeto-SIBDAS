<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.permissions']);

// Mensagens de sucesso ou erro no caso do POST
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
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'chave';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

// Obter todas as permissões da base de dados com paginação
$permissoes = [];
try {
    $ligacao = connect_to_db();
    $whereConditions = ["ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "idPermissao = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(idPermissao = :searchExact OR chave LIKE :search OR descricao LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            $whereConditions[] = "(chave LIKE :search OR descricao LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total sem filtros
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM Permissao WHERE ativo = 1", [], $ligacao);
    $totalPermissoesAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total
    $countSql = "SELECT COUNT(idPermissao) as total FROM Permissao WHERE $whereSQL";
    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalPermissoesFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalPermissoesFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'chave' => 'chave',
        'descricao' => 'descricao'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'chave';
    $sort_dir = strtoupper($dir_param);

    $dataSql = "SELECT idPermissao, chave, descricao FROM Permissao WHERE $whereSQL ORDER BY $sort_field $sort_dir LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmt = execute_query($dataSql, $params, $ligacao);
    $permissoesDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($permissoesDb as $row) {
        $permissoes[] = new Permissao((int) $row['idPermissao'], $row['chave'], $row['descricao']);
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar permissões: " . $e->getMessage();
}

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="gap-6 d-flex  flex-column padding-6 security-users">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <div class="d-flex flex-column gap-1">
                <h1>Permissões</h1>
                <p class="text-secondary fw-400">Gestão de permissões de utilizadores</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (tem_permissao('permissions.create')): ?>
                    <button id="btn-open-create-permission-modal" class="btn btn-primary btn-glowing gap-2"
                        data-bs-toggle="modal" data-bs-target="#permission-creation-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus-icon lucide-plus">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Nova Permissão
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
                        placeholder="Pesquisar por permissão..." value="<?= htmlspecialchars($search_query) ?>">
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
            </form>
        </div>

        <?php if ($totalPermissoesAll === 0): ?>
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
                    <h3 class="fw-700 m-0">Sem Permissões</h3>
                    <p class="text-secondary m-0">De momento não existe nenhuma permissão.</p>
                </div>
            </div>
        <?php elseif (empty($permissoes)): ?>
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
                        <table id="equipmentsTable" class="heba-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('chave') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CHAVE<?= $getSortIcon('chave') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('descricao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">DESCRIÇÃO<?= $getSortIcon('descricao') ?></a>
                                    </th>
                                    <?php if (tem_permissao('permissions.edit') || tem_permissao('permissions.delete')): ?>
                                        <th class="text-end">AÇÕES</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($permissoes as $permissao): ?>
                                    <?php $encryptedPermId = aes_encrypt($permissao->getIdPermissao()); ?>
                                    <tr>
                                        <td>
                                            <span
                                                class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  supplier-badge-supplier text-primary-500 font-mono fw-700">
                                                <?= htmlspecialchars($permissao->getChave()) ?>
                                            </span>
                                            <span class="visually-hidden"><?= htmlspecialchars($encryptedPermId) ?></span>
                                        </td>
                                        <td>
                                            <p class="fw-400"><?= htmlspecialchars($permissao->getDescricao()) ?></p>
                                        </td>
                                        <?php if (tem_permissao('permissions.edit') || tem_permissao('permissions.delete')): ?>
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
                                                        <?php if (tem_permissao('permissions.edit')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-primary"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#permission-edit-modal-<?= htmlspecialchars($encryptedPermId) ?>">
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
                                                        <?php if (tem_permissao('permissions.delete')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#delete-confirm-modal-<?= htmlspecialchars($encryptedPermId) ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                        class="lucide lucide-trash-2">
                                                                        <path d="M3 6h18" />
                                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
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

                    <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                        <div class="datatable-info">
                            A mostrar
                            <?= $totalPermissoesFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalPermissoesFiltered) ?>
                            de <?= $totalPermissoesFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                $buildQueryString = function ($newPage) use ($search_query, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($sort_param !== 'chave')
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

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<?php if (tem_permissao('permissions.create')): ?>
    <!-- Modal de Criação de Permissão -->
    <div class="modal fade" id="permission-creation-modal" tabindex="-1" aria-labelledby="permissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title" id="permissionModalLabel">Nova
                            Permissão</h2>
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
                    <form id="permission-creation-form" method="POST" action="permissions-crud/create-permission.php"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                        <!-- Row 1: Chave da Permissão -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="permission-key">Chave da Permissão <span class="text-error">*</span></label>
                            </div>
                            <input type="text" id="permission-key" name="permission-key" placeholder="ex: equipment.create"
                                required>
                        </div>

                        <!-- Row 2: Descrição -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="permission-description">Descrição <span class="text-error">*</span></label>
                            </div>
                            <textarea id="permission-description" name="permission-description" rows="4"
                                placeholder="Permite criar novos equipamentos no sistema." required></textarea>
                        </div>

                        <!-- Footer do Formulario -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-submit-permission" name="criar_permissao"
                                class="btn btn-primary btn-glowing" disabled>
                                Guardar Permissão
                            </button>
                        </div>
                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                    (Debug)</span>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'permission-key': 'equipment.create', 'permission-description': 'Permite criar novos equipamentos no sistema.'})">Criar
                                    Eq.</button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'permission-key': 'equipment.edit', 'permission-description': 'Permite editar equipamentos existentes no sistema.'})">Editar
                                    Eq.</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($permissoes as $permissao): ?>
    <?php $encryptedPermId = aes_encrypt($permissao->getIdPermissao()); ?>

    <?php if (tem_permissao('permissions.edit')): ?>
        <!-- Modal de Edição de Permissão para <?= htmlspecialchars($permissao->getChave()) ?> -->
        <div class="modal fade" id="permission-edit-modal-<?= htmlspecialchars($encryptedPermId) ?>" tabindex="-1"
            aria-labelledby="permissionEditModalLabel-<?= htmlspecialchars($encryptedPermId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="permissionEditModalLabel-<?= htmlspecialchars($encryptedPermId) ?>">
                                Editar Permissão
                            </h2>
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
                        <form id="permission-edit-form-<?= htmlspecialchars($encryptedPermId) ?>" method="POST"
                            action="permissions-crud/edit-permission.php"
                            class="permission-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                            <input type="hidden" name="permission-id" value="<?= htmlspecialchars($encryptedPermId) ?>">

                            <!-- Row 1: Chave da Permissão -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="permission-key-<?= htmlspecialchars($encryptedPermId) ?>">Chave da Permissão
                                        <span class="text-error">*</span></label>
                                </div>
                                <input type="text" id="permission-key-<?= htmlspecialchars($encryptedPermId) ?>"
                                    name="permission-key" placeholder="ex: equipment.create" class="permission-edit-key"
                                    value="<?= htmlspecialchars($permissao->getChave()) ?>" required>
                            </div>

                            <!-- Row 2: Descrição -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="permission-description-<?= htmlspecialchars($encryptedPermId) ?>">Descrição
                                        <span class="text-error">*</span></label>
                                </div>
                                <textarea id="permission-description-<?= htmlspecialchars($encryptedPermId) ?>"
                                    name="permission-description" rows="4" class="permission-edit-description"
                                    placeholder="Permite criar novos equipamentos no sistema."
                                    required><?= htmlspecialchars($permissao->getDescricao()) ?></textarea>
                            </div>

                            <!-- Footer do Formulario -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="editar_permissao"
                                    class="btn-edit-submit btn btn-primary btn-glowing">
                                    Guardar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (tem_permissao('permissions.delete')): ?>
        <!-- Modal de Eliminação de Permissão para <?= htmlspecialchars($permissao->getChave()) ?> -->
        <div class="modal fade" id="delete-confirm-modal-<?= htmlspecialchars($encryptedPermId) ?>" tabindex="-1"
            aria-labelledby="deleteModalLabel-<?= htmlspecialchars($encryptedPermId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="deleteModalLabel-<?= htmlspecialchars($encryptedPermId) ?>">
                                Apagar Definitivamente</h2>
                            <span class="text-secondary fw-400">Esta ação não pode ser
                                revertida.</span>
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

                    <!-- Body do Modal -->
                    <div class="modal-body p-0">
                        <form method="POST" action="permissions-crud/delete-permission.php">
                            <input type="hidden" name="permission-id" value="<?= htmlspecialchars($encryptedPermId) ?>">
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
                                            <p class="text-secondary">
                                                Tem a certeza que deseja apagar
                                                permanentemente a permissão
                                            </p>
                                            <h2 class="fw-700">
                                                "<?= htmlspecialchars($permissao->getChave()) ?>"
                                            </h2>
                                            <span class="text-muted">Tipo: Permissão</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botoes -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="apagar_permissao" class="btn btn-danger btn-glowing text-white">
                                        Sim, Apagar.
                                    </button>
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