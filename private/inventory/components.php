<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.components']);
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
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'descricao';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

$listaComponentes = [];
$categoriasDisponiveis = [];
$localizacoesDisponiveis = [];

try {
    $ligacao = connect_to_db();

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

    // Obter Localizações para os dropdowns (Formato: Edifício, Piso, Serviço, Sala)
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

    // Condições de Pesquisa
    $whereConditions = ["c.ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "c.idComponente = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(c.idComponente = :searchExact OR c.descricao LIKE :search OR c.codigoInterno LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            $whereConditions[] = "(c.descricao LIKE :search OR c.codigoInterno LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total de componentes (sem filtros)
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM Componente WHERE ativo = 1", [], $ligacao);
    $totalComponentesAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total filtrado
    $countSql = "SELECT COUNT(DISTINCT c.idComponente) as total 
                 FROM Componente c 
                 LEFT JOIN ComponenteCategoria cc ON c.idComponente = cc.idComponente 
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalComponentesFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalComponentesFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'descricao' => 'c.descricao',
        'sku' => 'c.codigoInterno',
        'categoria' => 'idCategoria',
        'stock' => 'c.stock',
        'preco' => 'c.preco'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'c.descricao';
    $sort_dir = strtoupper($dir_param);

    // Obter Componentes com LIMIT, OFFSET e ORDER BY
    $dataSql = "SELECT c.*, MIN(cc.idCategoria) as idCategoria
                FROM Componente c 
                LEFT JOIN ComponenteCategoria cc ON c.idComponente = cc.idComponente
                WHERE $whereSQL 
                GROUP BY c.idComponente
                ORDER BY $sort_field $sort_dir 
                LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmtComponentes = execute_query($dataSql, $params, $ligacao);

    while ($row = $stmtComponentes->fetch(PDO::FETCH_ASSOC)) {
        $listaComponentes[] = new Componente(
            (string) $row['idComponente'],
            $row['codigoInterno'],
            $row['descricao'],
            (int) $row['stock'],
            (int) $row['stockMinimo'],
            (float) $row['preco'],
            (string) $row['idLocalizacao'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao']),
            $row['idCategoria']
        );
    }
    $ligacao = null;
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
                <h1>Componentes</h1>
                <p class="text-secondary fw-400">Gestão de componentes em stock</p>
            </div>
            <?php if (tem_permissao('components.create')): ?>
                <div class="d-flex gap-2">
                    <button id="btn-open-create-equipment-modal" class="btn btn-primary btn-glowing gap-2"
                        data-bs-toggle="modal" data-bs-target="#equipment-creation-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus-icon lucide-plus">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Criar Componente
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Barra de Pesquisa -->
        <div
            class="bento-card padding-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
            <form action="" method="GET" style="display: contents;">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" name="search" id="search-input-field"
                        placeholder="Pesquisar componentes..." value="<?= htmlspecialchars($search_query) ?>">
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

        <?php if ($totalComponentesAll === 0): ?>
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
                    <h3 class="fw-700 m-0">Sem Componentes</h3>
                    <p class="text-secondary m-0">De momento não existe nenhum componente.</p>
                </div>
            </div>
        <?php elseif (empty($listaComponentes)): ?>
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
                        <table id="componentsTable" class="heba-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('descricao') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">COMPONENTE<?= $getSortIcon('descricao') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('sku') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">SKU<?= $getSortIcon('sku') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('categoria') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CATEGORIA<?= $getSortIcon('categoria') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('stock') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">STOCK/MIN<?= $getSortIcon('stock') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('preco') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">PREÇO
                                            UNIT.<?= $getSortIcon('preco') ?></a></th>
                                    <?php if (tem_permissao('components.edit') || tem_permissao('components.delete')): ?>
                                        <th class="text-end">AÇÕES</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaComponentes as $componente): ?>
                                    <?php $encryptedCompId = aes_encrypt($componente->getIdComponente()); ?>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="table-icon-wrapper padding-2 d-flex align-items-center justify-content-center flex-shrink-0 component-icon-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-puzzle-icon lucide-puzzle">
                                                        <path
                                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                                    </svg>
                                                </div>
                                                <p class="equipment-title fw-700 mb-0">
                                                    <?= htmlspecialchars($componente->getDescricao()) ?>
                                                </p>
                                                <span class="visually-hidden"><?= htmlspecialchars($encryptedCompId) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  component-sku-badge text-secondary font-mono">
                                                <?= htmlspecialchars($componente->getCodigoInterno()) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $catNome = "Sem Categoria";
                                            $catDescricao = "";
                                            $idCatComponente = $componente->getIdCategoria();

                                            if ($idCatComponente !== null) {
                                                foreach ($categoriasDisponiveis as $catDisp) {
                                                    if ($catDisp->getIdCategoria() === $idCatComponente) {
                                                        $catNome = $catDisp->getNome();
                                                        $catDescricao = $catDisp->getDescricao();
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="<?= htmlspecialchars($catDescricao) ?>">
                                                <?= htmlspecialchars($catNome) ?>
                                            </span>
                                        </td>
                                        <td
                                            class="fw-700 <?= $componente->getStock() <= $componente->getStockMinimo() ? 'text-error' : ''; ?>">
                                            <?= htmlspecialchars($componente->getStock()) . '/' . htmlspecialchars($componente->getStockMinimo()) ?>
                                        </td>
                                        <td class="fw-700">
                                            €<?= htmlspecialchars(number_format($componente->getPreco(), 2, '.', '')) ?>
                                        </td>
                                        <?php if (tem_permissao('components.edit') || tem_permissao('components.delete')): ?>
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
                                                        <?php if (tem_permissao('components.edit')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-primary"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#component-edit-modal-<?= htmlspecialchars($encryptedCompId) ?>">
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
                                                        <?php if (tem_permissao('components.delete')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                                    href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#component-delete-modal-<?= htmlspecialchars($encryptedCompId) ?>">
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
                                                                    Eliminar
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
                            <?= $totalComponentesFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalComponentesFiltered) ?>
                            de <?= $totalComponentesFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                // Função auxiliar para criar a query string mantendo os outros filtros
                                $buildQueryString = function ($newPage) use ($search_query, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($sort_param !== 'descricao')
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

<!-- Modal de Criação de Componente -->
<?php if (tem_permissao('components.create')): ?>
    <div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="componentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title" id="componentModalLabel">Novo
                            Componente</h2>
                        <span class="text-secondary fw-400">Componentes e peças associadas a equipamentos.</span>
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
                    <form id="form-create-component" action="components-crud/create-component.php" method="POST">
                        <div class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                            <!-- Row 1: Nome e SKU -->
                            <div class="d-flex gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="component-name">Nome</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="text" id="component-name" name="component-name"
                                        placeholder="Ex: Sensor SpO2 Neonatal" required>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="component-sku">SKU</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="text" id="component-sku" name="component-sku"
                                        placeholder="Ex: CMP-SP02-N01" maxlength="20" required>
                                </div>
                            </div>

                            <!-- Row 2: Categoria e Localização -->
                            <div class="d-flex gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="component-category">Categoria</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <select id="component-category" name="component-category" class="form-select" required>
                                        <option value="" disabled selected>Selecionar categoria...</option>
                                        <?php foreach ($categoriasDisponiveis as $cat): ?>
                                            <option value="<?= htmlspecialchars($cat->getIdCategoria()) ?>">
                                                <?= htmlspecialchars($cat->getNome()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="component-location">Localização</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <select id="component-location" name="component-location" class="form-select" required>
                                        <option value="" disabled selected>Selecionar localização...</option>
                                        <?php foreach ($localizacoesDisponiveis as $loc): ?>
                                            <option value="<?= htmlspecialchars($loc->getIdLocalizacao()) ?>">
                                                <?= htmlspecialchars($loc->getNomeSala()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 3: Stock Atual, Stock Mínimo e Preço Unitário -->
                            <div class="d-flex gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="component-stock-actual">Stock Atual</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="number" id="component-stock-actual" name="component-stock-actual" value="0"
                                        min="0" placeholder="0" required>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="component-stock-min">Stock Mínimo</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="number" id="component-stock-min" name="component-stock-min" value="0"
                                        min="0" placeholder="0" required>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <label for="component-price">Preço Unitário (€)</label>
                                    <input type="number" step="0.01" id="component-price" name="component-price"
                                        placeholder="0.00" min="0">
                                </div>
                            </div>

                            <!-- Button Row / Footer -->
                            <div
                                class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4 pt-4 border-top">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                                    Criar Componente
                                </button>
                            </div>
                            <?php if (SHOW_DEBUG_BUTTONS): ?>
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                    <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento
                                        Rápido (Debug)</span>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                        onclick="prefillFields({'component-name': 'Tubo de Silicone 1.5m', 'component-sku': 'TS-9921', 'component-stock-actual': '15', 'component-stock-min': '5', 'component-price': '12.50'}); setTimeout(() => { const c = document.getElementById('component-category'); if(c && c.options.length > 1) c.selectedIndex=1; const l = document.getElementById('component-location'); if(l && l.options.length > 1) l.selectedIndex=1; }, 100);">Tubo
                                        Silicone</button>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                        onclick="prefillFields({'component-name': 'Sensor SpO2 Reutilizável', 'component-sku': 'SPO2-R2', 'component-stock-actual': '8', 'component-stock-min': '3', 'component-price': '45.00'}); setTimeout(() => { const c = document.getElementById('component-category'); if(c && c.options.length > 1) c.selectedIndex=1; const l = document.getElementById('component-location'); if(l && l.options.length > 1) l.selectedIndex=1; }, 100);">Sensor
                                        SpO2</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($listaComponentes as $componente): ?>
    <?php $encryptedCompId = aes_encrypt($componente->getIdComponente()); ?>

    <?php if (tem_permissao('components.edit')): ?>
        <!-- Modal de Edição de Componente para <?= htmlspecialchars($componente->getDescricao()) ?> -->
        <div class="modal fade" id="component-edit-modal-<?= htmlspecialchars($encryptedCompId) ?>" tabindex="-1"
            aria-labelledby="componentEditModalLabel-<?= htmlspecialchars($encryptedCompId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="componentEditModalLabel-<?= htmlspecialchars($encryptedCompId) ?>">
                                Editar Componente
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
                        <form action="components-crud/edit-component.php" method="POST" class="form-edit-component">
                            <input type="hidden" name="component-id" value="<?= htmlspecialchars($encryptedCompId) ?>">
                            <div class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                                <!-- Row 1: Nome e SKU -->
                                <div class="d-flex gap-4 w-100">
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label for="component-name-<?= htmlspecialchars($encryptedCompId) ?>">Nome</label>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                <path d="M12 6v12" />
                                                <path d="M17.196 9 6.804 15" />
                                                <path d="m6.804 9 10.392 6" />
                                            </svg>
                                        </div>
                                        <input type="text" id="component-name-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-name" placeholder="Ex: Sensor SpO2 Neonatal"
                                            value="<?= htmlspecialchars($componente->getDescricao()) ?>" required>
                                    </div>

                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label for="component-sku-<?= htmlspecialchars($encryptedCompId) ?>">SKU</label>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                <path d="M12 6v12" />
                                                <path d="M17.196 9 6.804 15" />
                                                <path d="m6.804 9 10.392 6" />
                                            </svg>
                                        </div>
                                        <input type="text" id="component-sku-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-sku" placeholder="Ex: CMP-SP02-N01"
                                            value="<?= htmlspecialchars($componente->getCodigoInterno()) ?>" maxlength="20"
                                            required>
                                    </div>
                                </div>

                                <!-- Row 2: Categoria e Localização -->
                                <div class="d-flex gap-4 w-100">
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label
                                                for="component-category-<?= htmlspecialchars($encryptedCompId) ?>">Categoria</label>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                <path d="M12 6v12" />
                                                <path d="M17.196 9 6.804 15" />
                                                <path d="m6.804 9 10.392 6" />
                                            </svg>
                                        </div>
                                        <select id="component-category-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-category" class="form-select" required>
                                            <option value="" disabled>Selecionar categoria...</option>
                                            <?php foreach ($categoriasDisponiveis as $cat): ?>
                                                <option value="<?= htmlspecialchars($cat->getIdCategoria()) ?>"
                                                    <?= $cat->getIdCategoria() === $componente->getIdCategoria() ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat->getNome()) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label
                                                for="component-location-<?= htmlspecialchars($encryptedCompId) ?>">Localização</label>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                <path d="M12 6v12" />
                                                <path d="M17.196 9 6.804 15" />
                                                <path d="m6.804 9 10.392 6" />
                                            </svg>
                                        </div>
                                        <select id="component-location-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-location" class="form-select" required>
                                            <option value="" disabled>Selecionar localização...</option>
                                            <?php foreach ($localizacoesDisponiveis as $loc): ?>
                                                <option value="<?= htmlspecialchars($loc->getIdLocalizacao()) ?>" <?= (string) $loc->getIdLocalizacao() === $componente->getIdLocalizacao() ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($loc->getNomeSala()) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Row 3: Stock Atual, Stock Mínimo e Preço Unitário -->
                                <div class="d-flex gap-4 w-100">
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label for="component-stock-actual-<?= htmlspecialchars($encryptedCompId) ?>">Stock
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
                                        <input type="number"
                                            id="component-stock-actual-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-stock-actual"
                                            value="<?= htmlspecialchars($componente->getStock()) ?>" min="0" placeholder="0"
                                            class="stock-actual-input" required>
                                    </div>

                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1">
                                            <label for="component-stock-min-<?= htmlspecialchars($encryptedCompId) ?>">Stock
                                                Mínimo</label>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                <path d="M12 6v12" />
                                                <path d="M17.196 9 6.804 15" />
                                                <path d="m6.804 9 10.392 6" />
                                            </svg>
                                        </div>
                                        <input type="number" id="component-stock-min-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-stock-min"
                                            value="<?= htmlspecialchars($componente->getStockMinimo()) ?>" min="0"
                                            placeholder="0" class="stock-min-input" required>
                                    </div>

                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <label for="component-price-<?= htmlspecialchars($encryptedCompId) ?>">Preço
                                            Unitário
                                            (€)</label>
                                        <input type="number" step="0.01"
                                            id="component-price-<?= htmlspecialchars($encryptedCompId) ?>"
                                            name="component-price" placeholder="0.00"
                                            value="<?= htmlspecialchars($componente->getPreco()) ?>" min="0">
                                    </div>
                                </div>

                                <!-- Button Row / Footer -->
                                <div
                                    class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4 pt-4 border-top">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="editar_componente"
                                        class="btn btn-primary btn-glowing btn-submit-edit">
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

    <?php if (tem_permissao('components.delete')): ?>
        <!-- Modal de Eliminação de Componente para <?= htmlspecialchars($componente->getDescricao()) ?> -->
        <div class="modal fade" id="component-delete-modal-<?= htmlspecialchars($encryptedCompId) ?>" tabindex="-1"
            aria-labelledby="componentDeleteModalLabel-<?= htmlspecialchars($encryptedCompId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="componentDeleteModalLabel-<?= htmlspecialchars($encryptedCompId) ?>">
                                Eliminar Componente</h2>
                            <span class="text-secondary fw-400">O componente será movido para a reciclagem.</span>
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
                        <form method="POST" action="components-crud/delete-component.php">
                            <input type="hidden" name="component-id" value="<?= htmlspecialchars($encryptedCompId) ?>">
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
                                                Tem a certeza que deseja eliminar o componente?
                                            </p>
                                            <h2 class="fw-700">
                                                "<?= htmlspecialchars($componente->getDescricao()) ?>"
                                            </h2>
                                            <span class="text-muted">Tipo: Componente</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botoes -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="apagar_componente"
                                        class="btn btn-danger btn-glowing text-white">
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