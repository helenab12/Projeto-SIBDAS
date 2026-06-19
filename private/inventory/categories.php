<?php
require_once(__DIR__ . "/../../config/funcoes.php");
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

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'nome';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

$listaCategorias = [];

try {
    $ligacao = connect_to_db();

    // Condições de Pesquisa
    $whereConditions = ["ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $whereConditions[] = "(nome LIKE :search OR codigoPrefix LIKE :search OR descricao LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total de categorias (sem filtros)
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM CategoriaEquipamento WHERE ativo = 1", [], $ligacao);
    $totalCategoriasAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total filtrado
    $countSql = "SELECT COUNT(idCategoria) as total 
                 FROM CategoriaEquipamento 
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalCategoriasFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalCategoriasFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'nome' => 'nome',
        'codigo' => 'codigoPrefix',
        'descricao' => 'descricao'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'nome';
    $sort_dir = strtoupper($dir_param);

    // Obter Categorias com LIMIT, OFFSET e ORDER BY
    $dataSql = "SELECT *
                FROM CategoriaEquipamento 
                WHERE $whereSQL 
                ORDER BY $sort_field $sort_dir 
                LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmtCategorias = execute_query($dataSql, $params, $ligacao);
    while ($row = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
        $listaCategorias[] = new Categoria(
            (string) $row['idCategoria'],
            $row['nome'],
            $row['codigoPrefix'],
            $row['descricao'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao'])
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
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Categorias</h1>
                <p class="text-secondary fw-400">Gestão de categorias</p>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-open-create-category-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#category-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Nova Categoria
                </button>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 equipment-list-search-bar">
            <form action="" method="GET" style="display: contents;">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" name="search" id="search-input-field"
                        placeholder="Pesquisar categorias..." value="<?= htmlspecialchars($search_query) ?>">
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


        <?php if ($totalCategoriasAll === 0): ?>
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
                    <h3 class="fw-700 m-0">Sem Categorias</h3>
                    <p class="text-secondary m-0">De momento não existe nenhuma categoria.</p>
                </div>
            </div>
        <?php elseif (empty($listaCategorias)): ?>
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
                    <div class="datatable-container">
                        <?php
                        // Função auxiliar para criar links de ordenação
                        $buildSortUrl = function ($column) use ($search_query, $sort_param, $dir_param) {
                            $params = [];
                            if ($search_query !== '')
                                $params['search'] = $search_query;

                            $params['sort'] = $column;
                            // Inverte a direção se estiver a clicar na mesma coluna, senão default para asc
                            $params['dir'] = ($sort_param === $column && $dir_param === 'asc') ? 'desc' : 'asc';

                            return '?' . http_build_query($params);
                        };

                        // Função auxiliar para mostrar o ícone/seta
                        $getSortIcon = function ($column) use ($sort_param, $dir_param) {
                            if ($sort_param !== $column)
                                return '';
                            return $dir_param === 'asc' ? ' ↑' : ' ↓';
                        };
                        ?>
                        <table id="categoriesTable" class="sibdas-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('nome') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CATEGORIA<?= $getSortIcon('nome') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('codigo') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CÓDIGO<?= $getSortIcon('codigo') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('descricao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">DESCRIÇÃO<?= $getSortIcon('descricao') ?></a>
                                    </th>
                                    <th>EQUIPAMENTOS</th>
                                    <th class="text-end">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaCategorias as $categoria): ?>
                                    <?php $encryptedCatId = aes_encrypt($categoria->getIdCategoria()); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                                        <path
                                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                                        <path
                                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                                    </svg>
                                                </div>
                                                <p class="equipment-title fw-700 mb-0">
                                                    <?= htmlspecialchars($categoria->getNome()) ?>
                                                </p>
                                                <span class="visually-hidden"><?= htmlspecialchars($encryptedCatId) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="equipment-badge component-sku-badge font-mono">
                                                <?= htmlspecialchars($categoria->getCodigo()) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-sm text-secondary">
                                                <?= htmlspecialchars($categoria->getDescricao()) ?>
                                            </span>
                                        </td>
                                        <td class="fw-700">
                                            <?= $categoria->getEquipamentosCount() ?>
                                        </td>
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
                                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item action-dropdown-item text-primary" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#category-edit-modal-<?= htmlspecialchars($encryptedCatId) ?>">
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
                                                    <li>
                                                        <a class="dropdown-item action-dropdown-item text-error" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#category-delete-modal-<?= htmlspecialchars($encryptedCatId) ?>">
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
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                        <div class="datatable-info">
                            A mostrar
                            <?= $totalCategoriasFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalCategoriasFiltered) ?>
                            de <?= $totalCategoriasFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                // Função auxiliar para criar a query string mantendo os outros filtros
                                $buildQueryString = function ($newPage) use ($search_query, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($sort_param !== 'nome')
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

            <?php endif; ?>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Categoria -->
<div class="modal fade" id="category-creation-modal" tabindex="-1" aria-labelledby="categoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="categoryModalLabel">Nova
                        Categoria</h2>
                    <span class="text-secondary fw-400">As categorias organizam os equipamentos por tipo.</span>
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
                <form action="categories-crud/create-category.php" method="POST">
                    <div class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                        <!-- Row 1: Nome da Categoria -->
                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="category-name">Nome da Categoria</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="category-name" name="category-name" placeholder="Ex: Ventiladores"
                                required>
                        </div>

                        <!-- Row 2: Código -->
                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="category-code">Código</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="category-code" name="category-code" placeholder="Ex: VENT"
                                maxlength="5" required>
                        </div>

                        <!-- Row 3: Descrição -->
                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="category-description">Descrição</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <textarea id="category-description" name="category-description" rows="4"
                                placeholder="Descrição breve da categoria..."></textarea>
                        </div>

                        <!-- Button Row / Footer -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4 pt-4 border-top">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                                Criar Categoria
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

<?php foreach ($listaCategorias as $categoria): ?>
    <?php $encryptedCatId = aes_encrypt($categoria->getIdCategoria()); ?>

    <!-- Modal de Edição de Categoria para <?= htmlspecialchars($categoria->getNome()) ?> -->
    <div class="modal fade" id="category-edit-modal-<?= htmlspecialchars($encryptedCatId) ?>" tabindex="-1"
        aria-labelledby="categoryEditModalLabel-<?= htmlspecialchars($encryptedCatId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="categoryEditModalLabel-<?= htmlspecialchars($encryptedCatId) ?>">
                            Editar Categoria
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
                    <form action="categories-crud/edit-category.php" method="POST" class="edit-category-form">
                        <input type="hidden" name="category-id" value="<?= htmlspecialchars($encryptedCatId) ?>">
                        <div class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                            <!-- Row 1: Nome da Categoria -->
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <label for="category-name-<?= htmlspecialchars($encryptedCatId) ?>">Nome da
                                        Categoria</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="category-name-<?= htmlspecialchars($encryptedCatId) ?>"
                                    name="category-name" placeholder="Ex: Ventiladores"
                                    value="<?= htmlspecialchars($categoria->getNome()) ?>" required>
                            </div>

                            <!-- Row 2: Código -->
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <label for="category-code-<?= htmlspecialchars($encryptedCatId) ?>">Código</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="category-code-<?= htmlspecialchars($encryptedCatId) ?>"
                                    name="category-code" placeholder="Ex: VENT"
                                    value="<?= htmlspecialchars($categoria->getCodigo()) ?>" maxlength="5" required>
                            </div>

                            <!-- Row 3: Descrição -->
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <label
                                        for="category-description-<?= htmlspecialchars($encryptedCatId) ?>">Descrição</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <textarea id="category-description-<?= htmlspecialchars($encryptedCatId) ?>"
                                    name="category-description" rows="4" placeholder="Descrição breve da categoria..."
                                    required><?= htmlspecialchars($categoria->getDescricao()) ?></textarea>
                            </div>

                            <!-- Button Row / Footer -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4 pt-4 border-top">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="editar_categoria" class="btn btn-primary btn-glowing">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminação de Categoria para <?= htmlspecialchars($categoria->getNome()) ?> -->
    <div class="modal fade" id="category-delete-modal-<?= htmlspecialchars($encryptedCatId) ?>" tabindex="-1"
        aria-labelledby="categoryDeleteModalLabel-<?= htmlspecialchars($encryptedCatId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="categoryDeleteModalLabel-<?= htmlspecialchars($encryptedCatId) ?>">
                            Eliminar Categoria</h2>
                        <span class="text-secondary fw-400">A categoria será movida para a reciclagem.</span>
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
                    <form method="POST" action="categories-crud/delete-category.php">
                        <input type="hidden" name="category-id" value="<?= htmlspecialchars($encryptedCatId) ?>">
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
                                            Tem a certeza que deseja eliminar a categoria?
                                        </p>
                                        <h2 class="fw-700">
                                            "<?= htmlspecialchars($categoria->getNome()) ?>"
                                        </h2>
                                        <span class="text-muted">Tipo: Categoria</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botoes -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="apagar_categoria" class="btn btn-danger btn-glowing text-white">
                                    Sim, Eliminar.
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
include_once BASE_PATH . 'private/includes/footer.php';
?>