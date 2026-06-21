<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.fornecedores']);

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
$tipo_filter = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'nome';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

$listaFornecedores = [];
$pessoasDisponiveis = [];

try {
    $ligacao = connect_to_db();

    // Obter Pessoas para os dropdowns
    $stmtPessoas = execute_query(
        "SELECT idPessoa, nome, email, nif, contactoTelefonico, funcao, departamento, ativo, dataCriacao, dataAtualizacao 
         FROM Pessoa WHERE ativo = 1 AND funcao IN ('Fornecedor', 'Outro') ORDER BY nome ASC",
        [],
        $ligacao
    );
    while ($row = $stmtPessoas->fetch(PDO::FETCH_ASSOC)) {
        $pessoasDisponiveis[] = new Pessoa(
            (string) $row['idPessoa'],
            $row['nome'],
            $row['email'],
            $row['contactoTelefonico'],
            $row['nif'],
            $row['funcao'] ? Funcao::tryFrom($row['funcao']) : null,
            $row['departamento'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao'])
        );
    }

    // Condições de Pesquisa
    $whereConditions = ["f.ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "f.idFornecedor = :searchId";
            $params['searchId'] = (int)$decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(f.idFornecedor = :searchExact OR f.nome LIKE :search OR f.nifFornecedor LIKE :search OR f.email LIKE :search OR p.nome LIKE :search)";
            $params['searchExact'] = (int)$search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            $whereConditions[] = "(f.nome LIKE :search OR f.nifFornecedor LIKE :search OR f.email LIKE :search OR p.nome LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
    }

    if ($tipo_filter !== '') {
        $whereConditions[] = "f.tipoFornecedor = :tipo";
        $params['tipo'] = $tipo_filter;
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total sem filtros
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM Fornecedor WHERE ativo = 1", [], $ligacao);
    $totalFornecedoresAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total filtrado
    $countSql = "SELECT COUNT(f.idFornecedor) as total 
                 FROM Fornecedor f 
                 LEFT JOIN Pessoa p ON f.idPessoaResponsavel = p.idPessoa 
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalFornecedoresFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalFornecedoresFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'nome' => 'f.nome',
        'tipo' => 'f.tipoFornecedor',
        'contacto' => 'p.nome',
        'telefone' => 'f.contactoTelefonico',
        'website' => 'f.website'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'f.nome';
    $sort_dir = strtoupper($dir_param);

    // Obter Fornecedores com LIMIT, OFFSET e ORDER BY
    $dataSql = "SELECT f.*, p.nome as pessoa_nome, p.email as pessoa_email, p.contactoTelefonico as pessoa_contacto, 
                p.nif as pessoa_nif, p.funcao as pessoa_funcao, p.departamento as pessoa_departamento, 
                p.ativo as pessoa_ativo, p.dataCriacao as pessoa_dataCriacao, p.dataAtualizacao as pessoa_dataAtualizacao 
         FROM Fornecedor f 
         LEFT JOIN Pessoa p ON f.idPessoaResponsavel = p.idPessoa 
         WHERE $whereSQL 
         ORDER BY $sort_field $sort_dir 
         LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmtFornecedores = execute_query($dataSql, $params, $ligacao);

    while ($row = $stmtFornecedores->fetch(PDO::FETCH_ASSOC)) {
        $pessoa = null;
        if ($row['idPessoaResponsavel']) {
            $pessoa = new Pessoa(
                (string) $row['idPessoaResponsavel'],
                $row['pessoa_nome'],
                $row['pessoa_email'],
                $row['pessoa_contacto'],
                $row['pessoa_nif'],
                $row['pessoa_funcao'] ? Funcao::tryFrom($row['pessoa_funcao']) : null,
                $row['pessoa_departamento'],
                (bool) $row['pessoa_ativo'],
                new DateTime($row['pessoa_dataCriacao']),
                new DateTime($row['pessoa_dataAtualizacao'])
            );
        }

        $listaFornecedores[] = new Fornecedor(
            (string) $row['idFornecedor'],
            $row['nome'],
            $row['nifFornecedor'],
            $row['contactoTelefonico'],
            $row['email'],
            $row['website'] ?? '',
            $row['idPessoaResponsavel'] ? (string) $row['idPessoaResponsavel'] : null,
            TipoFornecedor::from($row['tipoFornecedor']),
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao']),
            $pessoa
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
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Fornecedores</h1>
                <p class="text-secondary fw-400">Gestão de fornecedores</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (tem_permissao('suppliers.create')): ?>
                <button id="btn-open-create-supplier-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#supplier-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Criar Fornecedor
                </button>
                <?php endif; ?>
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
                        placeholder="Pesquisar por nome, email ou pessoa de contacto..."
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
                    <select class="form-select" name="tipo" aria-label="Filtro Tipo" onchange="this.form.submit()">
                        <option value="" <?= $tipo_filter === '' ? 'selected' : '' ?>>Todos os Tipos</option>
                        <option value="Fabricante" <?= $tipo_filter === 'Fabricante' ? 'selected' : '' ?>>Fabricante
                        </option>
                        <option value="Distribuidor" <?= $tipo_filter === 'Distribuidor' ? 'selected' : '' ?>>Distribuidor
                        </option>
                        <option value="Assistência Técnica" <?= $tipo_filter === 'Assistência Técnica' ? 'selected' : '' ?>>Assistência Técnica</option>
                        <option value="Consumíveis" <?= $tipo_filter === 'Consumíveis' ? 'selected' : '' ?>>Consumíveis
                        </option>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($totalFornecedoresAll === 0): ?>
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
                    <h3 class="fw-700 m-0">Sem Fornecedores</h3>
                    <p class="text-secondary m-0">De momento não existe nenhum fornecedor.</p>
                </div>
            </div>
        <?php elseif (empty($listaFornecedores)): ?>
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
            <div class="bento-card w-100 p-0 border-0" id="table-container">
                <div class="datatable-wrapper no-footer sortable fixed-columns">
                    <div class="datatable-container">
                        <?php
                        // Função auxiliar para criar links de ordenação
                        $buildSortUrl = function ($column) use ($search_query, $tipo_filter, $sort_param, $dir_param) {
                            $params = [];
                            if ($search_query !== '')
                                $params['search'] = $search_query;
                            if ($tipo_filter !== '')
                                $params['tipo'] = $tipo_filter;

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
                        <table id="suppliersTable" class="sibdas-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('nome') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">FORNECEDOR<?= $getSortIcon('nome') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('tipo') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">TIPO<?= $getSortIcon('tipo') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('contacto') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">CONTACTO<?= $getSortIcon('contacto') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('telefone') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">TELEFONE<?= $getSortIcon('telefone') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('website') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">WEBSITE<?= $getSortIcon('website') ?></a>
                                    </th>
                                    <?php if (tem_permissao('suppliers.edit') || tem_permissao('suppliers.delete')): ?>
                                    <th class="text-end">AÇÕES</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($listaFornecedores as $fornecedor):
                                    $encryptedId = aes_encrypt($fornecedor->getIdFornecedor());
                                    $tipoBadgeClass = '';
                                    switch ($fornecedor->getTipoFornecedor()->value) {
                                        case 'Fabricante':
                                            $tipoBadgeClass = 'supplier-badge-supplier';
                                            break;
                                        case 'Distribuidor':
                                            $tipoBadgeClass = 'supplier-badge-distributor';
                                            break;
                                        case 'Assistência Técnica':
                                            $tipoBadgeClass = 'supplier-badge-tech-assistance';
                                            break;
                                        case 'Consumíveis':
                                            $tipoBadgeClass = 'supplier-badge-consumable-supplier';
                                            break;
                                    }
                                    ?>
                                    <tr class="searchable-row"
                                        data-search="<?= htmlspecialchars(strtolower($fornecedor->getNome() . ' ' . $fornecedor->getNifFornecedor() . ' ' . $fornecedor->getEmail() . ' ' . $fornecedor->getTipoFornecedor()->value)) ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-building2-icon lucide-building-2">
                                                        <path d="M10 12h4" />
                                                        <path d="M10 8h4" />
                                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                                        <path
                                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                                    </svg>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <p class="equipment-title fw-700 mb-0">
                                                        <?= htmlspecialchars($fornecedor->getNome()) ?>
                                                    </p>
                                                    <span
                                                        class="equipment-subtitle text-secondary fw-400"><?= htmlspecialchars($fornecedor->getEmail()) ?></span>
                                                    <span class="visually-hidden"><?= htmlspecialchars($encryptedId) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="equipment-badge <?= $tipoBadgeClass ?>">
                                                <?= htmlspecialchars($fornecedor->getTipoFornecedor()->value) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($fornecedor->getPessoaResponsavel()): ?>
                                                <?= htmlspecialchars($fornecedor->getPessoaResponsavel()->getNome()) ?>
                                            <?php else: ?>
                                                <span class="fst-italic text-muted">Sem contacto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a
                                                href="tel:<?= htmlspecialchars($fornecedor->getContactoTelefonico()) ?>"><?= htmlspecialchars($fornecedor->getContactoTelefonico()) ?></a>
                                        </td>
                                        <td>
                                            <?php if (!empty($fornecedor->getWebsite())): ?>
                                                <a href="<?= htmlspecialchars($fornecedor->getWebsite()) ?>" target="_blank"
                                                    class="d-flex gap-1 align-items-center text-primary-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                                        <path d="M2 12h20" />
                                                    </svg>
                                                    <span>Website</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (tem_permissao('suppliers.edit') || tem_permissao('suppliers.delete')): ?>
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
                                                    <?php if (tem_permissao('suppliers.edit')): ?>
                                                    <li>
                                                        <a class="dropdown-item action-dropdown-item text-primary" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#supplier-edit-modal-<?= $encryptedId ?>">
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
                                                    <?php if (tem_permissao('suppliers.delete')): ?>
                                                    <li>
                                                        <a class="dropdown-item action-dropdown-item text-error" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete-confirm-modal-<?= $encryptedId ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-archive">
                                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                                <path d="M10 12h4" />
                                                            </svg>
                                                            Mover para Reciclagem
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
                            <?= $totalFornecedoresFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalFornecedoresFiltered) ?>
                            de <?= $totalFornecedoresFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                // Função auxiliar para criar a query string mantendo os outros filtros
                                $buildQueryString = function ($newPage) use ($search_query, $tipo_filter, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($tipo_filter !== '')
                                        $params['tipo'] = $tipo_filter;
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
            </div>

        <?php endif; ?>

    </section>
</div>

<?php include_once BASE_PATH . 'private/includes/sidebar-mobile.php'; ?>

<!-- Modal de Criação de Fornecedor -->
<?php if (tem_permissao('suppliers.create')): ?>
<div class="modal fade" id="supplier-creation-modal" tabindex="-1" aria-labelledby="supplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="supplierModalLabel">Novo Fornecedor</h2>
                    <span class="text-secondary fw-400">Informações do fornecedor</span>
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
                <form id="supplier-creation-form" action="suppliers-crud/create-supplier.php" method="POST"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Nome da Empresa e NIF -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-name">Nome da Empresa</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-name" name="supplier-name"
                                placeholder="Ex: Dräger Portugal, Lda." required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-nif">NIF</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-nif" name="supplier-nif" placeholder="501234567" required
                                pattern="[0-9]{9}" title="O NIF deve conter 9 dígitos">
                        </div>
                    </div>

                    <!-- Row 2: Tipo -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <div class="d-flex gap-1">
                            <label for="supplier-type">Tipo</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <select id="supplier-type" name="supplier-type" class="form-select w-100" required>
                            <option value="Fabricante" selected>Fabricante</option>
                            <option value="Distribuidor">Distribuidor</option>
                            <option value="Assistência Técnica">Assistência Técnica</option>
                            <option value="Consumíveis">Consumíveis</option>
                        </select>
                    </div>

                    <!-- Row 3: Email e Telefone -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-email">Email</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="email" id="supplier-email" name="supplier-email"
                                placeholder="email@empresa.com" required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-phone">Telefone de Contacto</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-phone" name="supplier-phone" placeholder="+351 21X XXX XXX"
                                required>
                        </div>
                    </div>

                    <!-- Row 4: Website -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-website">Website</label>
                        <input type="url" id="supplier-website" name="supplier-website"
                            placeholder="https://www.empresa.pt">
                    </div>

                    <!-- Row 5: Pessoa Responsável -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-contact-person">Pessoa Responsável</label>
                        <select id="supplier-contact-person" name="supplier-contact-person" class="form-select w-100">
                            <option value="" selected>Sem pessoa associada</option>
                            <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                <option value="<?= htmlspecialchars($pessoa->getId()) ?>">
                                    <?= htmlspecialchars($pessoa->getNome() . ' (' . ($pessoa->getFuncao() ? $pessoa->getFuncao()->value : 'Sem função') . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="criar_fornecedor" id="btn-submit-modal"
                            class="btn btn-primary btn-glowing" disabled>
                            Criar Fornecedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php foreach ($listaFornecedores as $fornecedor):
    $encryptedId = aes_encrypt($fornecedor->getIdFornecedor());
    ?>
    <?php if (tem_permissao('suppliers.edit')): ?>
    <!-- Modal de Edição para <?= htmlspecialchars($fornecedor->getNome()) ?> -->
    <div class="modal fade" id="supplier-edit-modal-<?= $encryptedId ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title">Editar Fornecedor</h2>
                        <span class="text-secondary fw-400">Atualizar informações do fornecedor</span>
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
                    <form action="suppliers-crud/edit-supplier.php" method="POST"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                        <input type="hidden" name="supplier-id" value="<?= $encryptedId ?>">

                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-name-<?= $encryptedId ?>">Nome da Empresa</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="supplier-name-<?= $encryptedId ?>" name="supplier-name"
                                    value="<?= htmlspecialchars($fornecedor->getNome()) ?>" required>
                            </div>
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-nif-<?= $encryptedId ?>">NIF</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="supplier-nif-<?= $encryptedId ?>" name="supplier-nif"
                                    value="<?= htmlspecialchars($fornecedor->getNifFornecedor()) ?>" required
                                    pattern="[0-9]{9}">
                            </div>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-type-<?= $encryptedId ?>">Tipo</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <select id="supplier-type-<?= $encryptedId ?>" name="supplier-type" class="form-select w-100"
                                required>
                                <option value="Fabricante" <?= $fornecedor->getTipoFornecedor()->value === 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                <option value="Distribuidor" <?= $fornecedor->getTipoFornecedor()->value === 'Distribuidor' ? 'selected' : '' ?>>Distribuidor</option>
                                <option value="Assistência Técnica"
                                    <?= $fornecedor->getTipoFornecedor()->value === 'Assistência Técnica' ? 'selected' : '' ?>>
                                    Assistência Técnica</option>
                                <option value="Consumíveis" <?= $fornecedor->getTipoFornecedor()->value === 'Consumíveis' ? 'selected' : '' ?>>Consumíveis</option>
                            </select>
                        </div>

                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-email-<?= $encryptedId ?>">Email</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="email" id="supplier-email-<?= $encryptedId ?>" name="supplier-email"
                                    value="<?= htmlspecialchars($fornecedor->getEmail()) ?>" required>
                            </div>
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-phone-<?= $encryptedId ?>">Telefone</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="supplier-phone-<?= $encryptedId ?>" name="supplier-phone"
                                    value="<?= htmlspecialchars($fornecedor->getContactoTelefonico()) ?>" required>
                            </div>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="supplier-website-<?= $encryptedId ?>">Website</label>
                            <input type="url" id="supplier-website-<?= $encryptedId ?>" name="supplier-website"
                                value="<?= htmlspecialchars($fornecedor->getWebsite()) ?>">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="supplier-contact-person-<?= $encryptedId ?>">Pessoa Responsável</label>
                            <select id="supplier-contact-person-<?= $encryptedId ?>" name="supplier-contact-person"
                                class="form-select w-100">
                                <option value="" <?= !$fornecedor->getIdPessoaResponsavel() ? 'selected' : '' ?>>Sem pessoa
                                    associada</option>
                                <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                    <option value="<?= htmlspecialchars($pessoa->getId()) ?>"
                                        <?= $fornecedor->getIdPessoaResponsavel() == $pessoa->getId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pessoa->getNome() . ' (' . ($pessoa->getFuncao() ? $pessoa->getFuncao()->value : 'Sem função') . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="editar_fornecedor"
                                class="btn btn-primary btn-glowing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (tem_permissao('suppliers.delete')): ?>
    <!-- Modal de Eliminação de Fornecedor para <?= htmlspecialchars($fornecedor->getNome()) ?> -->
    <div class="modal fade" id="delete-confirm-modal-<?= $encryptedId ?>" tabindex="-1"
        aria-labelledby="deleteModalLabel-<?= $encryptedId ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title" id="deleteModalLabel-<?= $encryptedId ?>">
                            Eliminar Fornecedor</h2>
                        <span class="text-secondary fw-400">O fornecedor será movido para a reciclagem.</span>
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
                    <form method="POST" action="suppliers-crud/delete-supplier.php">
                        <input type="hidden" name="supplier-id" value="<?= $encryptedId ?>">
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
                                            Tem a certeza que deseja eliminar o fornecedor?
                                        </p>
                                        <h2 class="fw-700">
                                            "<?= htmlspecialchars($fornecedor->getNome()) ?>"
                                        </h2>
                                        <span class="text-muted">Tipo: Fornecedor</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botoes -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="apagar_fornecedor"
                                    class="btn btn-danger btn-glowing text-white">
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

<?php include_once BASE_PATH . 'private/includes/footer.php'; ?>