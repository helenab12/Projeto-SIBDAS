<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['view.categorias']);

// Inicializar variáveis
$success_message = null;
$server_error = null;

// Recolher mensagens da sessão
if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

// Recolher parâmetros
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'nome';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

// Inicializar variáveis
$listaCategorias = [];

try {
    // Ligar à BD
    $ligacao = connect_to_db();

    // Preparar condições de pesquisa
    $whereConditions = ["ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "idCategoria = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(idCategoria = :searchExact OR nome LIKE :search OR codigoPrefix LIKE :search OR descricao LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            $whereConditions[] = "(nome LIKE :search OR codigoPrefix LIKE :search OR descricao LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total sem filtros
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

    // Definir ordenação
    $allowed_sorts = [
        'nome' => 'nome',
        'codigo' => 'codigoPrefix',
        'descricao' => 'descricao'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'nome';
    $sort_dir = strtoupper($dir_param);

    // Obter categorias
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
    $ligacao = null;
} catch (Exception $e) {
    // Capturar erro
    $server_error = "Erro ao carregar dados: " . $e->getMessage();
}

// Carregar dependências
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

?>

<!-- Wrapper Principal -->
<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php
    // Carregar cabeçalhos
    include_once BASE_PATH . 'private/includes/headers.php';
    ?>

    <!-- Conteúdo Principal -->
    <section class="padding-6 gap-6 d-flex flex-column padding-6">
        <!-- Título de Secção -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <!-- Títulos -->
            <div class="d-flex flex-column gap-1">
                <!-- Título Principal -->
                <h1>Categorias</h1>
                <!-- Subtítulo -->
                <p class="text-secondary fw-400">Gestão de categorias</p>
            </div>
            <?php if (tem_permissao('categories.create')): ?>
                <!-- Ações -->
                <div class="d-flex gap-2">
                    <!-- Botão Nova Categoria -->
                    <button id="btn-open-create-category-modal" class="btn btn-primary btn-glowing gap-2"
                        data-bs-toggle="modal" data-bs-target="#category-creation-modal">
                        <!-- SVG Plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus-icon lucide-plus">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Nova Categoria
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Barra de Pesquisa -->
        <div
            class="bento-card padding-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
            <!-- Formulário de Pesquisa -->
            <form action="" method="GET" style="display: contents;">
                <!-- Wrapper Input -->
                <div class="form-item w-100 position-relative">
                    <!-- SVG Lupa -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <!-- Input Pesquisa -->
                    <input type="text" class="form-item w-100 search-bar-input" name="search" id="search-input-field"
                        placeholder="Pesquisar categorias..." value="<?= htmlspecialchars($search_query) ?>">
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


        <?php if ($totalCategoriasAll === 0): ?>
            <!-- Estado Vazio (Sem Categorias) -->
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <!-- Wrapper Ícone -->
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG Bell Off -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell-off-icon lucide-bell-off">
                        <path d="M9 10h.01" />
                        <path d="M15 10h.01" />
                        <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" />
                    </svg>
                </div>
                <!-- Textos de Estado -->
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem Categorias</h3>
                    <!-- Mensagem -->
                    <p class="text-secondary m-0">De momento não existe nenhuma categoria.</p>
                </div>
            </div>
        <?php elseif (empty($listaCategorias)): ?>
            <!-- Estado Vazio (Sem Resultados) -->
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <!-- Wrapper Ícone -->
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG Search Off -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-x">
                        <path d="m13.5 8.5-5 5" />
                        <path d="m8.5 8.5 5 5" />
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <!-- Textos de Estado -->
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem resultados</h3>
                    <!-- Mensagem -->
                    <p class="text-secondary m-0">Nenhum registo encontrado correspondente à sua pesquisa.</p>
                </div>
            </div>
        <?php else: ?>

            <!-- Tabela -->
            <div class="bento-card w-100 p-0 border-0">
                <div class="datatable-wrapper no-footer sortable fixed-columns">
                    <div class="datatable-container w-100 overflow-auto position-relative">
                        <?php
                        // Definir função auxiliar para criar links de ordenação
                        $buildSortUrl = function ($column) use ($search_query, $sort_param, $dir_param) {
                            $params = [];
                            if ($search_query !== '')
                                $params['search'] = $search_query;

                            $params['sort'] = $column;
                            // Inverte a direção se estiver a clicar na mesma coluna, senão default para asc
                            $params['dir'] = ($sort_param === $column && $dir_param === 'asc') ? 'desc' : 'asc';

                            return '?' . http_build_query($params);
                        };

                        // Definir função auxiliar para mostrar o ícone/seta
                        $getSortIcon = function ($column) use ($sort_param, $dir_param) {
                            if ($sort_param !== $column)
                                return '';
                            return $dir_param === 'asc' ? ' ↑' : ' ↓';
                        };
                        ?>
                        <!-- Tabela Categorias -->
                        <table id="categoriesTable" class="heba-table w-100 display datatable-table">
                            <!-- Cabeçalho da Tabela -->
                            <thead>
                                <tr>
                                    <!-- Coluna Categoria -->
                                    <th>
                                        <!-- Link Ordenação Categoria -->
                                        <a href="<?= $buildSortUrl('nome') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CATEGORIA<?= $getSortIcon('nome') ?></a>
                                    </th>
                                    <!-- Coluna Código -->
                                    <th>
                                        <!-- Link Ordenação Código -->
                                        <a href="<?= $buildSortUrl('codigo') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CÓDIGO<?= $getSortIcon('codigo') ?></a>
                                    </th>
                                    <!-- Coluna Descrição -->
                                    <th>
                                        <!-- Link Ordenação Descrição -->
                                        <a href="<?= $buildSortUrl('descricao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">DESCRIÇÃO<?= $getSortIcon('descricao') ?></a>
                                    </th>
                                    <!-- Coluna Equipamentos -->
                                    <th>EQUIPAMENTOS</th>
                                    <?php if (tem_permissao('categories.edit') || tem_permissao('categories.delete')): ?>
                                        <!-- Coluna Ações -->
                                        <th class="text-end">AÇÕES</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <!-- Corpo da Tabela -->
                            <tbody>
                                <?php foreach ($listaCategorias as $categoria): ?>
                                    <?php $encryptedCatId = aes_encrypt($categoria->getIdCategoria()); ?>
                                    <tr>
                                        <!-- Coluna Categoria -->
                                        <td>
                                            <!-- Wrapper Categoria -->
                                            <div class="d-flex align-items-center gap-3">
                                                <!-- Ícone da Categoria -->
                                                <div
                                                    class="table-icon-wrapper padding-2 d-flex align-items-center justify-content-center flex-shrink-0 equipment-icon-wrapper">
                                                    <!-- SVG Pasta -->
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
                                                <!-- Nome da Categoria -->
                                                <p class="equipment-title fw-700 mb-0">
                                                    <?= htmlspecialchars($categoria->getNome()) ?>
                                                </p>
                                                <!-- ID Oculto -->
                                                <span class="visually-hidden"><?= htmlspecialchars($encryptedCatId) ?></span>
                                            </div>
                                        </td>
                                        <!-- Coluna Código -->
                                        <td>
                                            <!-- Badge Código -->
                                            <span
                                                class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  component-sku-badge text-secondary font-mono">
                                                <?= htmlspecialchars($categoria->getCodigo()) ?>
                                            </span>
                                        </td>
                                        <!-- Coluna Descrição -->
                                        <td>
                                            <!-- Texto Descrição -->
                                            <span class="text-sm text-secondary">
                                                <?= htmlspecialchars($categoria->getDescricao()) ?>
                                            </span>
                                        </td>
                                        <!-- Coluna Contagem de Equipamentos -->
                                        <td class="fw-700">
                                            <?= $categoria->getEquipamentosCount() ?>
                                        </td>
                                        <?php if (tem_permissao('categories.edit') || tem_permissao('categories.delete')): ?>
                                            <!-- Coluna Ações -->
                                            <td class="text-end equipment-actions">
                                                <!-- Dropdown Ações -->
                                                <div class="dropdown">
                                                    <!-- Botão Dropdown -->
                                                    <button
                                                        class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <!-- SVG Dots -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="1" />
                                                            <circle cx="19" cy="12" r="1" />
                                                            <circle cx="5" cy="12" r="1" />
                                                        </svg>
                                                    </button>
                                                    <!-- Menu Dropdown -->
                                                    <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu padding-2">
                                                        <?php if (tem_permissao('categories.edit')): ?>
                                                            <!-- Item Editar -->
                                                            <li>
                                                                <!-- Link Editar -->
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-primary"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#category-edit-modal-<?= htmlspecialchars($encryptedCatId) ?>">
                                                                    <!-- SVG Lápis -->
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
                                                        <?php if (tem_permissao('categories.delete')): ?>
                                                            <!-- Item Apagar -->
                                                            <li>
                                                                <!-- Link Apagar -->
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#category-delete-modal-<?= htmlspecialchars($encryptedCatId) ?>">
                                                                    <!-- SVG Lixo -->
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

                    <!-- Rodapé da Tabela -->
                    <div class="d-flex justify-content-between align-items-center padding-4 datatable-bottom">
                        <!-- Informação da Tabela -->
                        <div class="datatable-info">
                            A mostrar
                            <?= $totalCategoriasFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalCategoriasFiltered) ?>
                            de <?= $totalCategoriasFiltered ?> registos
                        </div>
                        <!-- Paginação -->
                        <nav class="datatable-pagination">
                            <!-- Lista de Paginação -->
                            <ul class="datatable-pagination-list">
                                <?php
                                // Definir função auxiliar para criar a query string mantendo os outros filtros
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
                                    <!-- Item Paginação Anterior -->
                                    <li class="datatable-pagination-list-item pager"><a
                                            href="<?= $buildQueryString($current_page - 1) ?>">‹</a></li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $current_page - 2); $i <= min($totalPages, $current_page + 2); $i++): ?>
                                    <!-- Item Paginação Número -->
                                    <li
                                        class="datatable-pagination-list-item <?= $i === $current_page ? 'datatable-active' : '' ?>">
                                        <a href="<?= $buildQueryString($i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($current_page < $totalPages): ?>
                                    <!-- Item Paginação Seguinte -->
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
// Carregar dependências
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Categoria -->
<?php if (tem_permissao('categories.create')): ?>
    <!-- Wrapper Modal -->
    <div class="modal fade" id="category-creation-modal" tabindex="-1" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Cabeçalho Modal -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <!-- Título Modal -->
                        <h2 class="equipment-creation-modal-title modal-title" id="categoryModalLabel">Nova
                            Categoria</h2>
                        <!-- Descrição Modal -->
                        <span class="text-secondary fw-400">As categorias organizam os equipamentos por tipo.</span>
                    </div>

                    <!-- Botão Fechar -->
                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                        data-bs-dismiss="modal" aria-label="Close">
                        <!-- SVG X -->
                        <!-- SVG X -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x stroke-secondary">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>

                </div>

                <!-- Corpo Modal -->
                <div class="modal-body p-0">
                    <!-- Formulário de Criação -->
                    <form action="categories-crud/create-category.php" method="POST">
                        <div class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                            <!-- Campo Nome da Categoria -->
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <!-- Label Nome -->
                                    <label for="category-name">Nome da Categoria</label>
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <!-- Input Nome -->
                                <input type="text" id="category-name" name="category-name" placeholder="Ex: Ventiladores"
                                    required>
                            </div>

                            <!-- Campo Código -->
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <!-- Label Código -->
                                    <label for="category-code">Código</label>
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <!-- Input Código -->
                                <input type="text" id="category-code" name="category-code" placeholder="Ex: VENT"
                                    maxlength="5" required>
                            </div>

                            <!-- Campo Descrição -->
                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <!-- Label Descrição -->
                                    <label for="category-description">Descrição</label>
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <!-- Textarea Descrição -->
                                <textarea id="category-description" name="category-description" rows="4"
                                    placeholder="Descrição breve da categoria..."></textarea>
                            </div>

                            <!-- Rodapé do Formulário -->
                            <div
                                class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4 pt-4 border-top">
                                <!-- Botão Cancelar -->
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <!-- Botão Submeter -->
                                <button type="submit" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                                    Criar Categoria
                                </button>
                            </div>
                            <?php if (SHOW_DEBUG_BUTTONS): ?>
                                <!-- Wrapper Debug -->
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                    <!-- Texto Debug -->
                                    <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento
                                        Rápido
                                        (Debug)</span>
                                    <!-- Botão Debug Ventiladores -->
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                        onclick="prefillFields({'category-name': 'Ventiladores', 'category-code': 'VENT', 'category-description': 'Equipamentos de ventilação mecânica assistida.'})">Ventiladores</button>
                                    <!-- Botão Debug Desfibrilhadores -->
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                        onclick="prefillFields({'category-name': 'Desfibrilhadores', 'category-code': 'DESF', 'category-description': 'Equipamentos de desfibrilhação cardíaca.'})">Desfibrilhadores</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<!-- Wrapper Toast -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 9999;">
    <?php if (!empty($success_message)): ?>
        <!-- Toast Sucesso -->
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <!-- Wrapper Conteúdo -->
            <div class="d-flex align-items-center gap-2">
                <!-- Mensagem Sucesso -->
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <!-- Botão Fechar -->
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <!-- SVG X -->
                    <!-- SVG X -->
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
            <!-- Wrapper Conteúdo -->
            <div class="d-flex align-items-center gap-2">
                <!-- Mensagem Erro -->
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($server_error) ?>
                </div>
                <!-- Botão Fechar -->
                <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <!-- SVG X -->
                    <!-- SVG X -->
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
    <?php
    // Encriptar ID
    $encryptedCatId = aes_encrypt($categoria->getIdCategoria());
    ?>

    <?php if (tem_permissao('categories.edit')): ?>
        <!-- Modal Edição -->
        <div class="modal fade" id="category-edit-modal-<?= htmlspecialchars($encryptedCatId) ?>" tabindex="-1"
            aria-labelledby="categoryEditModalLabel-<?= htmlspecialchars($encryptedCatId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Cabeçalho Modal -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <!-- Título Modal -->
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="categoryEditModalLabel-<?= htmlspecialchars($encryptedCatId) ?>">
                                Editar Categoria
                            </h2>
                        </div>

                        <!-- Botão Fechar -->
                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <!-- SVG X -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Corpo Modal -->
                    <div class="modal-body p-0">
                        <!-- Formulário de Edição -->
                        <form action="categories-crud/edit-category.php" method="POST" class="edit-category-form">
                            <!-- Input ID -->
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
                                    <!-- Input Nome -->
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
                                    <!-- Input Código -->
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
                                    <!-- Textarea Descrição -->
                                    <textarea id="category-description-<?= htmlspecialchars($encryptedCatId) ?>"
                                        name="category-description" rows="4" placeholder="Descrição breve da categoria..."
                                        required><?= htmlspecialchars($categoria->getDescricao()) ?></textarea>
                                </div>

                                <!-- Button Row / Footer -->
                                <div
                                    class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4 pt-4 border-top">
                                    <!-- Botão Cancelar -->
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <!-- Botão Guardar -->
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
    <?php endif; ?>

    <?php if (tem_permissao('categories.delete')): ?>
        <!-- Modal Eliminação -->
        <div class="modal fade" id="category-delete-modal-<?= htmlspecialchars($encryptedCatId) ?>" tabindex="-1"
            aria-labelledby="categoryDeleteModalLabel-<?= htmlspecialchars($encryptedCatId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Cabeçalho Modal -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <!-- Título Modal -->
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="categoryDeleteModalLabel-<?= htmlspecialchars($encryptedCatId) ?>">
                                Eliminar Categoria</h2>
                            <!-- Subtítulo Modal -->
                            <span class="text-secondary fw-400">A categoria será movida para a reciclagem.</span>
                        </div>

                        <!-- Botão Fechar -->
                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <!-- SVG X -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Corpo Modal -->
                    <div class="modal-body p-0">
                        <!-- Formulário de Eliminação -->
                        <form method="POST" action="categories-crud/delete-category.php">
                            <!-- Input ID -->
                            <input type="hidden" name="category-id" value="<?= htmlspecialchars($encryptedCatId) ?>">
                            <div
                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                                <!-- Container Aviso -->
                                <div class="d-flex flex-column align-items-center gap-4">
                                    <!-- Ícone Perigo -->
                                    <div class="d-flex padding-3 danger-icon">
                                        <!-- SVG Alerta -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                            <path
                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                            <path d="M12 9v4" />
                                            <path d="M12 17h.01" />
                                        </svg>
                                    </div>
                                    <!-- Wrapper Texto -->
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                        <!-- Container Texto -->
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                            <!-- Texto Confirmação -->
                                            <p class="text-secondary">
                                                Tem a certeza que deseja eliminar a categoria?
                                            </p>
                                            <!-- Título Categoria -->
                                            <h2 class="fw-700">
                                                "<?= htmlspecialchars($categoria->getNome()) ?>"
                                            </h2>
                                            <!-- Texto Tipo -->
                                            <span class="text-muted">Tipo: Categoria</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botoes -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <!-- Botão Cancelar -->
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <!-- Botão Eliminar -->
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
    <?php endif; ?>
<?php endforeach; ?>

<?php
// Carregar dependências
include_once BASE_PATH . 'private/includes/footer.php';
?>