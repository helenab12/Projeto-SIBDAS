<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

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

class TipoReciclagem
{
    public string $nome;
    public string $caminhoSvg;
    public string $cor;
}

$tiposReciclagem = [
    'equipment' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Equipamentos';
        $type->cor = 'var(--primary-500)';
        $type->caminhoSvg = '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path> <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline> <line x1="12" y1="22.08" x2="12" y2="12"></line>';
        return $type;
    })(),
    'supplier' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Fornecedores';
        $type->cor = '#f97316';
        $type->caminhoSvg = '<path d="M10 12h4" /> <path d="M10 8h4" /> <path d="M14 21v-3a2 2 0 0 0-4 0v3" /> <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" /> <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />';
        return $type;
    })(),
    'user' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Utilizadores';
        $type->cor = '#a855f7';
        $type->caminhoSvg = '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /> <path d="M16 3.128a4 4 0 0 1 0 7.744" /> <path d="M22 21v-2a4 4 0 0 0-3-3.87" /> <circle cx="9" cy="7" r="4" />';
        return $type;
    })(),
    'person' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Pessoas';
        $type->cor = 'var(--success)';
        $type->caminhoSvg = '<path d="m14.305 19.53.923-.382" /> <path d="m15.228 16.852-.923-.383" /> <path d="m16.852 15.228-.383-.923" /> <path d="m16.852 20.772-.383.924" /> <path d="m19.148 15.228.383-.923" /> <path d="m19.53 21.696-.382-.924" /> <path d="M2 21a8 8 0 0 1 10.434-7.62" /> <path d="m20.772 16.852.924-.383" /> <path d="m20.772 19.148.924.383" /> <circle cx="10" cy="8" r="5" /> <circle cx="18" cy="18" r="3" />';
        return $type;
    })(),
    'component' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Componentes';
        $type->cor = 'var(--text-secondary)';
        $type->caminhoSvg = '<path d="M5.5 8.5 9 12l-3.5 3.5L2 12l3.5-3.5Z" /><path d="m12 2 3.5 3.5L12 9 8.5 5.5 12 2Z" /><path d="M18.5 8.5 22 12l-3.5 3.5L15 12l3.5-3.5Z" /><path d="m12 15 3.5 3.5L12 22l-3.5-3.5L12 15Z" />';
        return $type;
    })(),
];

class ObjetoReciclado
{
    public string $idEncriptado;
    public string $nomeTabela;
    public TipoReciclagem $tipo;
    public string $nome;
    public string $descricao;
    public DateTime $removidoA;

    public function __construct(string $idEncriptado, string $nomeTabela, string $nome, string $descricao, TipoReciclagem $tipo, DateTime $removidoA)
    {
        $this->idEncriptado = $idEncriptado;
        $this->nomeTabela = $nomeTabela;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->removidoA = $removidoA;
    }
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$tipo_filter = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'removidoA';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'asc') ? 'asc' : 'desc'; // default desc
$items_per_page = 8;

$objetosReciclados = [];

try {
    $ligacao = connect_to_db();

    $queries = [];

    if ($tipo_filter === '' || $tipo_filter === 'Equipamentos') {
        $queries[] = "SELECT idEquipamento as id, 'Equipamento' as nomeTabela, COALESCE(designacao, 'Equipamento S/N') as nome, 
                      CONCAT(COALESCE(codigoInterno, 'S/ Código'), IF(modelo IS NOT NULL AND modelo != '', CONCAT(' • ', modelo), '')) as descricao, 
                      COALESCE(dataAtualizacao, NOW()) as removidoA 
                      FROM Equipamento WHERE ativo = 0";
    }

    if ($tipo_filter === '' || $tipo_filter === 'Componentes') {
        $queries[] = "SELECT idComponente as id, 'Componente' as nomeTabela, COALESCE(descricao, 'Componente S/N') as nome, 
                      COALESCE(codigoInterno, 'S/ Código') as descricao, 
                      COALESCE(dataAtualizacao, NOW()) as removidoA 
                      FROM Componente WHERE ativo = 0";
    }

    if ($tipo_filter === '' || $tipo_filter === 'Fornecedores') {
        $queries[] = "SELECT idFornecedor as id, 'Fornecedor' as nomeTabela, COALESCE(nome, 'Fornecedor S/N') as nome, 
                      CONCAT('NIF: ', COALESCE(nifFornecedor, 'N/D')) as descricao, 
                      COALESCE(dataAtualizacao, NOW()) as removidoA 
                      FROM Fornecedor WHERE ativo = 0";
    }

    if ($tipo_filter === '' || $tipo_filter === 'Pessoas') {
        $queries[] = "SELECT idPessoa as id, 'Pessoa' as nomeTabela, COALESCE(nome, 'Pessoa S/N') as nome, 
                      CONCAT('Função: ', COALESCE(funcao, 'S/ Função')) as descricao, 
                      COALESCE(dataAtualizacao, NOW()) as removidoA 
                      FROM Pessoa WHERE ativo = 0";
    }

    if ($tipo_filter === '' || $tipo_filter === 'Utilizadores') {
        $queries[] = "SELECT u.idUtilizador as id, 'Utilizador' as nomeTabela, COALESCE(p.nome, 'Utilizador S/N') as nome, 
                      CONCAT('Email: ', COALESCE(u.emailAutenticacao, 'N/D')) as descricao, 
                      COALESCE(u.dataAtualizacao, NOW()) as removidoA 
                      FROM Utilizador u LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa WHERE u.ativo = 0";
    }

    $unionQuery = implode(" UNION ALL ", $queries);

    $outerWhere = "1=1";
    $params = [];
    if ($search_query !== '') {
        $outerWhere .= " AND (nome LIKE :search OR descricao LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    $countSql = "SELECT COUNT(*) as total FROM ($unionQuery) as combined WHERE $outerWhere";
    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalReciclados = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalReciclados / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }
    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'nome' => 'nome',
        'nomeTabela' => 'nomeTabela',
        'descricao' => 'descricao',
        'removidoA' => 'removidoA'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'removidoA';
    $sort_dir = strtoupper($dir_param);

    $dataSql = "SELECT * FROM ($unionQuery) as combined WHERE $outerWhere ORDER BY $sort_field $sort_dir LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;
    $stmt = execute_query($dataSql, $params, $ligacao);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $tipoStr = '';
        switch ($row['nomeTabela']) {
            case 'Equipamento':
                $tipoStr = 'equipment';
                break;
            case 'Componente':
                $tipoStr = 'component';
                break;
            case 'Fornecedor':
                $tipoStr = 'supplier';
                break;
            case 'Pessoa':
                $tipoStr = 'person';
                break;
            case 'Utilizador':
                $tipoStr = 'user';
                break;
        }

        $objetosReciclados[] = new ObjetoReciclado(
            aes_encrypt($row['id']),
            $row['nomeTabela'],
            $row['nome'],
            $row['descricao'],
            $tiposReciclagem[$tipoStr],
            new DateTime($row['removidoA'])
        );
    }

} catch (Exception $e) {
    error_log("Erro ao carregar reciclagem: " . $e->getMessage());
}
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 recycling">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Reciclagem</h1>
                <p class="text-secondary fw-400">Registos removidos do sistema (Soft Delete).</p>
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
                        placeholder="Pesquisar na reciclagem..." value="<?= htmlspecialchars($search_query) ?>">
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
                    <select class="form-select" name="tipo" aria-label="Filtro de Tipo" onchange="this.form.submit()">
                        <option value="" <?= $tipo_filter === '' ? 'selected' : '' ?>>Todos os Tipos</option>
                        <option value="Equipamentos" <?= $tipo_filter === 'Equipamentos' ? 'selected' : '' ?>>Equipamentos
                        </option>
                        <option value="Componentes" <?= $tipo_filter === 'Componentes' ? 'selected' : '' ?>>Componentes
                        </option>
                        <option value="Fornecedores" <?= $tipo_filter === 'Fornecedores' ? 'selected' : '' ?>>Fornecedores
                        </option>
                        <option value="Pessoas" <?= $tipo_filter === 'Pessoas' ? 'selected' : '' ?>>Pessoas</option>
                        <option value="Utilizadores" <?= $tipo_filter === 'Utilizadores' ? 'selected' : '' ?>>Utilizadores
                        </option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Tabela de Reciclagem -->
        <div class="bento-card w-100 p-0 border-0">
            <div class="datatable-wrapper no-footer fixed-columns">
                <div class="datatable-container">
                    <?php
                    $buildSortUrl = function ($column) use ($search_query, $tipo_filter, $sort_param, $dir_param) {
                        $params = [];
                        if ($search_query !== '')
                            $params['search'] = $search_query;
                        if ($tipo_filter !== '')
                            $params['tipo'] = $tipo_filter;
                        $params['sort'] = $column;
                        $params['dir'] = ($sort_param === $column && $dir_param === 'desc') ? 'asc' : 'desc';
                        return '?' . http_build_query($params);
                    };

                    $getSortIcon = function ($column) use ($sort_param, $dir_param) {
                        if ($sort_param !== $column)
                            return '';
                        return $dir_param === 'asc' ? ' ↑' : ' ↓';
                    };
                    ?>
                    <table id="recyclingTable" class="sibdas-table w-100 display datatable-table">
                        <thead>
                            <tr>
                                <th><a href="<?= $buildSortUrl('nome') ?>"
                                        class="datatable-sorter text-decoration-none text-inherit">Entidade<?= $getSortIcon('nome') ?></a>
                                </th>
                                <th><a href="<?= $buildSortUrl('nomeTabela') ?>"
                                        class="datatable-sorter text-decoration-none text-inherit">Tipo<?= $getSortIcon('nomeTabela') ?></a>
                                </th>
                                <th><a href="<?= $buildSortUrl('descricao') ?>"
                                        class="datatable-sorter text-decoration-none text-inherit">Descrição<?= $getSortIcon('descricao') ?></a>
                                </th>
                                <th><a href="<?= $buildSortUrl('removidoA') ?>"
                                        class="datatable-sorter text-decoration-none text-inherit">Removido
                                        a<?= $getSortIcon('removidoA') ?></a></th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($objetosReciclados)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Nenhum registo encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($objetosReciclados as $object): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="recycle-type-icon d-flex align-items-center justify-content-center padding-2"
                                                    style="background-color: color-mix(in srgb, <?php echo $object->tipo->cor; ?> 10%, transparent); color: <?php echo $object->tipo->cor; ?>; width: 36px; height: 36px; border-radius: 8px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" class="lucide">
                                                        <?php echo $object->tipo->caminhoSvg; ?>
                                                    </svg>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="fw-600 item-name"><?php echo htmlspecialchars($object->nome); ?></span>
                                                    <span
                                                        class="visually-hidden item-hidden-id"><?php echo htmlspecialchars($object->idEncriptado); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary fw-500"><?php echo htmlspecialchars($object->tipo->nome); ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary"><?php echo htmlspecialchars($object->descricao); ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary"><?php echo $object->removidoA->format('Y-m-d H:i'); ?></span>
                                        </td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#restore-modal-<?php echo htmlspecialchars($object->idEncriptado); ?>"
                                                class="btn btn-small text-success d-flex align-items-center gap-2 restore badge badge-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-undo2-icon lucide-undo-2">
                                                    <path d="M9 14 4 9l5-5" />
                                                    <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                                                </svg>
                                                <span class="fw-700 ">Restaurar</span>
                                            </button>
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
                        <?= $totalReciclados > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalReciclados) ?>
                        de <?= $totalReciclados ?> registos
                    </div>
                    <nav class="datatable-pagination">
                        <ul class="datatable-pagination-list">
                            <?php
                            $buildQueryString = function ($newPage) use ($search_query, $tipo_filter, $sort_param, $dir_param) {
                                $params = ['page' => $newPage];
                                if ($search_query !== '')
                                    $params['search'] = $search_query;
                                if ($tipo_filter !== '')
                                    $params['tipo'] = $tipo_filter;
                                if ($sort_param !== 'removidoA')
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

    </section>
</div>

<?php foreach ($objetosReciclados as $object): ?>
    <!-- Modal de Restauro de Registo -->
    <div class="modal fade" id="restore-modal-<?= htmlspecialchars($object->idEncriptado) ?>" tabindex="-1"
        aria-labelledby="restoreModalLabel-<?= htmlspecialchars($object->idEncriptado) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="restoreModalLabel-<?= htmlspecialchars($object->idEncriptado) ?>">
                            Restaurar Registo</h2>
                        <span class="text-secondary fw-400">O registo será restaurado no sistema principal.</span>
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
                    <form method="POST" action="<?php echo BASE_URL; ?>/private/security/restore_item.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($object->idEncriptado) ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($object->nomeTabela) ?>">
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon"
                                    style="background-color: var(--primary-100); color: var(--primary-500);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive-restore">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h2" />
                                        <path d="M20 8v11a2 2 0 0 1-2 2h-2" />
                                        <path d="m9 15 3-3 3 3" />
                                        <path d="M12 12v9" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary">Tem a certeza que deseja restaurar este registo?</p>
                                        <h2 class="fw-700">"<?= htmlspecialchars($object->nome) ?>"</h2>
                                        <span class="text-muted">Tipo: <?= htmlspecialchars($object->tipo->nome) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-glowing text-white">Sim, Restaurar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>