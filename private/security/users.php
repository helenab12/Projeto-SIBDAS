<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.users']);

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
$perfil_filter = isset($_GET['perfil']) ? trim($_GET['perfil']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$sort_param = isset($_GET['sort']) ? trim($_GET['sort']) : 'nome';
$dir_param = (isset($_GET['dir']) && strtolower(trim($_GET['dir'])) === 'desc') ? 'desc' : 'asc';
$items_per_page = 8;

$listaUtilizadores = [];
try {
    $ligacao = connect_to_db();
    // Condições de Pesquisa
    $whereConditions = ["u.ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        $decryptedId = aes_decrypt($search_query);
        if ($decryptedId !== false && is_numeric($decryptedId)) {
            $whereConditions[] = "u.idUtilizador = :searchId OR p.idPessoa = :searchId";
            $params['searchId'] = (int) $decryptedId;
        } elseif (is_numeric($search_query)) {
            $whereConditions[] = "(u.idUtilizador = :searchExact OR p.idPessoa = :searchExact OR p.nome LIKE :search OR u.emailAutenticacao LIKE :search OR p.email LIKE :search)";
            $params['searchExact'] = (int) $search_query;
            $params['search'] = '%' . $search_query . '%';
        } else {
            $whereConditions[] = "(p.nome LIKE :search OR u.emailAutenticacao LIKE :search OR p.email LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }
    }

    if ($perfil_filter !== '') {
        $whereConditions[] = "pf.nome = :perfil";
        $params['perfil'] = $perfil_filter;
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // Contar total sem filtros
    $stmtTotal = execute_query("SELECT COUNT(*) as total FROM Utilizador WHERE ativo = 1", [], $ligacao);
    $totalUtilizadoresAll = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar total
    $countSql = "SELECT COUNT(u.idUtilizador) as total
                 FROM Utilizador u
                 INNER JOIN Pessoa p ON u.idPessoa = p.idPessoa
                 LEFT JOIN Perfil pf ON u.idPerfil = pf.idPerfil
                 WHERE $whereSQL";

    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalUtilizadoresFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalUtilizadoresFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    // Definição de Sort
    $allowed_sorts = [
        'nome' => 'p.nome',
        'perfil' => 'pf.nome'
    ];
    $sort_field = isset($allowed_sorts[$sort_param]) ? $allowed_sorts[$sort_param] : 'p.nome';
    $sort_dir = strtoupper($dir_param);

    $dataSql = "SELECT u.idUtilizador, u.idPessoa, u.emailAutenticacao, u.password, u.idPerfil, u.ativo as utilizador_ativo, u.dataCriacao as utilizador_dataCriacao, u.dataAtualizacao as utilizador_dataAtualizacao,
                p.nome as pessoa_nome, p.email as pessoa_email, p.contactoTelefonico as pessoa_contacto, p.nif as pessoa_nif, p.funcao as pessoa_funcao, p.departamento as pessoa_departamento, p.ativo as pessoa_ativo, p.dataCriacao as pessoa_dataCriacao, p.dataAtualizacao as pessoa_dataAtualizacao,
                pf.idPerfil as perfil_id, pf.nome as perfil_nome, pf.dataCriacao as perfil_dataCriacao, pf.dataAtualizacao as perfil_dataAtualizacao
        FROM Utilizador u
        INNER JOIN Pessoa p ON u.idPessoa = p.idPessoa
        LEFT JOIN Perfil pf ON u.idPerfil = pf.idPerfil
        WHERE $whereSQL
        ORDER BY $sort_field $sort_dir
        LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;

    $stmt = execute_query($dataSql, $params, $ligacao);

    $utilizadoresDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($utilizadoresDb as $row) {
        $pessoa = new Pessoa(
            (string) $row['idPessoa'],
            (string) $row['pessoa_nome'],
            (string) $row['pessoa_email'],
            (string) $row['pessoa_contacto'],
            (string) $row['pessoa_nif'],
            $row['pessoa_funcao'] ? Funcao::tryFrom((string) $row['pessoa_funcao']) : null,
            $row['pessoa_departamento'] ? (string) $row['pessoa_departamento'] : null,
            (bool) $row['pessoa_ativo'],
            new DateTime($row['pessoa_dataCriacao']),
            $row['pessoa_dataAtualizacao'] ? new DateTime($row['pessoa_dataAtualizacao']) : new DateTime()
        );

        $perfil = new Perfil(
            (string) $row['perfil_id'],
            (string) $row['perfil_nome'],
            new DateTime($row['perfil_dataCriacao']),
            $row['perfil_dataAtualizacao'] ? new DateTime($row['perfil_dataAtualizacao']) : new DateTime()
        );

        $utilizador = new Utilizador(
            (string) $row['idUtilizador'],
            (string) $row['idPessoa'],
            (string) $row['emailAutenticacao'],
            (string) $row['password'],
            (string) $row['idPerfil'],
            (bool) $row['utilizador_ativo'],
            new DateTime($row['utilizador_dataCriacao']),
            $row['utilizador_dataAtualizacao'] ? new DateTime($row['utilizador_dataAtualizacao']) : new DateTime(),
            $perfil
        );

        $listaUtilizadores[] = [
            'utilizador' => $utilizador,
            'pessoa' => $pessoa
        ];
    }

    $stmtPerfis = execute_query("SELECT idPerfil, nome FROM Perfil WHERE ativo = 1");
    $perfisDisponiveis = $stmtPerfis->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $server_error = "Erro ao carregar utilizadores: " . $e->getMessage();
}

$profileBadges = [
    'Administrador' => [
        'class' => 'admin',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-alert-icon lucide-shield-alert"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>'
    ],
    'Aprovisionamento' => [
        'class' => 'inv-manager',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" /><path d="m9 12 2 2 4-4" /></svg>'
    ],
    'Engenheiro Biomédico' => [
        'class' => 'senior-tech',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" /></svg>'
    ],
    'Técnico de Manutenção' => [
        'class' => 'tech',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-icon lucide-key"><path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4" /><path d="m21 2-9.6 9.6" /><circle cx="7.5" cy="15.5" r="5.5" /></svg>'
    ],
    'Consulta' => [
        'class' => 'consultant',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><path d="M16 3.128a4 4 0 0 1 0 7.744" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><circle cx="9" cy="7" r="4" /></svg>'
    ]
];

function get_profile_badge($perfilNome, $profileBadges)
{
    if (isset($profileBadges[$perfilNome])) {
        return $profileBadges[$perfilNome];
    }
    return [
        'class' => 'consultant',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
    ];
}

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 security-users">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Utilizadores</h1>
                <p class="text-secondary fw-400"><?= count($listaUtilizadores) ?> utilizadores ativos</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (tem_permissao('users.create')): ?>
                    <button id="btn-open-create-user-modal" class="btn btn-primary btn-glowing gap-2" data-bs-toggle="modal"
                        data-bs-target="#user-creation-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus-icon lucide-plus">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Novo Utilizador
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
                        placeholder="Pesquisar por nome, username ou email..."
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
                    <select class="form-select" name="perfil" aria-label="Filtro Perfil" onchange="this.form.submit()">
                        <option value="" <?= $perfil_filter === '' ? 'selected' : '' ?>>Todos os Perfis</option>
                        <option value="Administrador" <?= $perfil_filter === 'Administrador' ? 'selected' : '' ?>>
                            Administrador</option>
                        <option value="Engenheiro Biomédico" <?= $perfil_filter === 'Engenheiro Biomédico' ? 'selected' : '' ?>>Engenheiro Biomédico</option>
                        <option value="Técnico de Manutenção" <?= $perfil_filter === 'Técnico de Manutenção' ? 'selected' : '' ?>>Técnico de Manutenção</option>
                        <option value="Aprovisionamento" <?= $perfil_filter === 'Aprovisionamento' ? 'selected' : '' ?>>
                            Aprovisionamento</option>
                        <option value="Consulta" <?= $perfil_filter === 'Consulta' ? 'selected' : '' ?>>Consulta</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <?php if ($totalUtilizadoresAll === 0): ?>
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
                        <h3 class="fw-700 m-0">Sem Utilizadores</h3>
                        <p class="text-secondary m-0">De momento não existe nenhum utilizador registado.</p>
                    </div>
                </div>
            <?php elseif (empty($listaUtilizadores)): ?>
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
                <div class="datatable-wrapper no-footer sortable fixed-columns">
                    <div class="datatable-container">
                        <?php
                        $buildSortUrl = function ($column) use ($search_query, $perfil_filter, $sort_param, $dir_param) {
                            $params = [];
                            if ($search_query !== '')
                                $params['search'] = $search_query;
                            if ($perfil_filter !== '')
                                $params['perfil'] = $perfil_filter;
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
                        <table id="usersTable" class="sibdas-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th><a href="<?= $buildSortUrl('nome') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">UTILIZADOR<?= $getSortIcon('nome') ?></a>
                                    </th>
                                    <th><a href="<?= $buildSortUrl('perfil') ?>"
                                            class="datatable-sorter text-decoration-none text-inherit">PERFIL<?= $getSortIcon('perfil') ?></a>
                                    </th>
                                    <?php if (tem_permissao('users.edit') || tem_permissao('users.delete')): ?>
                                        <th class="text-end">AÇÕES</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaUtilizadores as $item): ?>
                                    <?php
                                    $utilizador = $item['utilizador'];
                                    $pessoa = $item['pessoa'];
                                    $encryptedUserId = aes_encrypt($utilizador->getIdUtilizador());
                                    $badge = get_profile_badge($utilizador->getPerfil()->getNome(), $profileBadges);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                                    <?= htmlspecialchars(get_user_initials($pessoa->getNome())) ?>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <p class="equipment-title fw-700 mb-0">
                                                        <?= htmlspecialchars($pessoa->getNome()) ?>
                                                    </p>
                                                    <span
                                                        class="visually-hidden"><?= htmlspecialchars($encryptedUserId) ?></span>
                                                    <span
                                                        class="equipment-subtitle text-secondary fw-400 font-mono"><?= htmlspecialchars($utilizador->getEmailAutenticacao()) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="equipment-badge <?= $badge['class'] ?> gap-1 align-items-center">
                                                <?= $badge['icon'] ?>
                                                <?= htmlspecialchars($utilizador->getPerfil()->getNome()) ?>
                                            </span>
                                        </td>
                                        <?php if (tem_permissao('users.edit') || tem_permissao('users.delete')): ?>
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
                                                        <?php if (tem_permissao('users.edit')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item text-primary" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#user-edit-modal-<?= htmlspecialchars($encryptedUserId) ?>">
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
                                                        <?php if (tem_permissao('users.delete')): ?>
                                                            <li>
                                                                <a class="dropdown-item action-dropdown-item text-error" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#delete-confirm-modal-<?= htmlspecialchars($encryptedUserId) ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                        class="lucide lucide-archive">
                                                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                                        <path d="M10 12h4" />
                                                                    </svg>
                                                                    Desativar (Reciclagem)
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
                            <?= $totalUtilizadoresFiltered > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $items_per_page, $totalUtilizadoresFiltered) ?>
                            de <?= $totalUtilizadoresFiltered ?> registos
                        </div>
                        <nav class="datatable-pagination">
                            <ul class="datatable-pagination-list">
                                <?php
                                $buildQueryString = function ($newPage) use ($search_query, $perfil_filter, $sort_param, $dir_param) {
                                    $params = ['page' => $newPage];
                                    if ($search_query !== '')
                                        $params['search'] = $search_query;
                                    if ($perfil_filter !== '')
                                        $params['perfil'] = $perfil_filter;
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
                <?php endif; ?>
            </div>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<?php if (tem_permissao('users.create')): ?>
    <!-- Modal de Criação de Utilizador -->
    <div class="modal fade" id="user-creation-modal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title" id="userModalLabel">Novo Utilizador</h2>
                        <span class="text-secondary fw-400">Credenciais e permissões de acesso</span>
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
                    <form id="user-creation-form" method="POST" action="users-crud/create-user.php"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                        <!-- Row 1: Email da Pessoa -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1 align-items-center">
                                <label for="user-email">Email (Funcionário)</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Este email deve pertencer a uma pessoa já registada na gestão de pessoas."
                                    class="ms-1 text-secondary" style="cursor: help;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-info">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 16v-4" />
                                        <path d="M12 8h.01" />
                                    </svg>
                                </span>
                            </div>
                            <input type="email" id="user-email" name="user-email" placeholder="funcionario@hospital.pt"
                                required>
                        </div>

                        <!-- Row 2: Email de Autenticacao -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="user-auth-email">Email de Autenticação</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="email" id="user-auth-email" name="user-auth-email" placeholder="login@hospital.pt"
                                required>
                        </div>

                        <!-- Row 3: Password -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="user-password">Password</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="password" id="user-password" name="user-password" placeholder="••••••••" required>
                        </div>

                        <!-- Row 3: Perfil de Acesso -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="user-role">Perfil de Acesso</label>
                            <select id="user-role" name="user-role" class="form-select w-100" required>
                                <?php foreach ($perfisDisponiveis ?? [] as $perfilOption): ?>
                                    <option value="<?= htmlspecialchars($perfilOption['idPerfil']) ?>">
                                        <?= htmlspecialchars($perfilOption['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Footer do Formulario -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-submit-user-modal" name="criar_utilizador"
                                class="btn btn-primary btn-glowing" disabled>
                                Criar Utilizador
                            </button>
                        </div>
                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                    (Debug)</span>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'user-email': 'admin@heba.pt', 'user-auth-email': 'admin@heba.pt', 'user-password': 'password123'}); setTimeout(() => { const r = document.getElementById('user-role'); if(r && r.options.length > 1) r.selectedIndex=1; r.dispatchEvent(new Event('change')); }, 100);">Admin</button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'user-email': 'tecnico@heba.pt', 'user-auth-email': 'tecnico@heba.pt', 'user-password': 'password123'}); setTimeout(() => { const r = document.getElementById('user-role'); if(r && r.options.length > 2) r.selectedIndex=2; r.dispatchEvent(new Event('change')); }, 100);">Técnico</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($listaUtilizadores as $item): ?>
    <?php
    $utilizador = $item['utilizador'];
    $pessoa = $item['pessoa'];
    $encryptedUserId = aes_encrypt($utilizador->getIdUtilizador());
    ?>

    <?php if (tem_permissao('users.edit')): ?>
        <!-- Modal de Edição de Utilizador para <?= htmlspecialchars($pessoa->getNome()) ?> -->
        <div class="modal fade" id="user-edit-modal-<?= htmlspecialchars($encryptedUserId) ?>" tabindex="-1"
            aria-labelledby="userEditModalLabel-<?= htmlspecialchars($encryptedUserId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="userEditModalLabel-<?= htmlspecialchars($encryptedUserId) ?>">
                                Editar Utilizador
                            </h2>
                            <span class="text-secondary fw-400">Edite as credenciais e acessos</span>
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
                        <form id="user-edit-form-<?= htmlspecialchars($encryptedUserId) ?>" method="POST"
                            action="users-crud/edit-user.php"
                            class="user-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                            <input type="hidden" name="user-id" value="<?= htmlspecialchars($encryptedUserId) ?>">

                            <!-- Row 1: Email de Autenticação -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="edit-auth-email-<?= htmlspecialchars($encryptedUserId) ?>">Email de Autenticação
                                        <span class="text-error">*</span></label>
                                </div>
                                <input type="email" id="edit-auth-email-<?= htmlspecialchars($encryptedUserId) ?>"
                                    name="user-auth-email" placeholder="login@hospital.pt" class="user-edit-email-input"
                                    value="<?= htmlspecialchars($utilizador->getEmailAutenticacao()) ?>" required>
                            </div>

                            <!-- Row 2: Nova Password -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="edit-password-<?= htmlspecialchars($encryptedUserId) ?>">Nova Password</label>
                                </div>
                                <input type="password" id="edit-password-<?= htmlspecialchars($encryptedUserId) ?>"
                                    name="user-password" placeholder="Deixe em branco para não alterar"
                                    class="user-edit-password-input">
                            </div>

                            <!-- Row 3: Perfil de Acesso -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="edit-role-<?= htmlspecialchars($encryptedUserId) ?>">Perfil de Acesso
                                    <span class="text-error">*</span></label>
                                <select id="edit-role-<?= htmlspecialchars($encryptedUserId) ?>" name="user-role"
                                    class="form-select w-100 user-edit-role-input" required>
                                    <?php foreach ($perfisDisponiveis ?? [] as $perfilOption): ?>
                                        <option value="<?= htmlspecialchars($perfilOption['idPerfil']) ?>"
                                            <?= ($perfilOption['idPerfil'] === $utilizador->getIdPerfil()) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($perfilOption['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Footer do Formulario -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="edit_utilizador"
                                    class="user-edit-submit-btn btn btn-primary btn-glowing" disabled>
                                    Guardar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (tem_permissao('users.delete')): ?>
        <!-- Modal de Desativação de Utilizador para <?= htmlspecialchars($pessoa->getNome()) ?> -->
        <div class="modal fade" id="delete-confirm-modal-<?= htmlspecialchars($encryptedUserId) ?>" tabindex="-1"
            aria-labelledby="deleteUserModalLabel-<?= htmlspecialchars($encryptedUserId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="deleteUserModalLabel-<?= htmlspecialchars($encryptedUserId) ?>">
                                Desativar Utilizador</h2>
                            <span class="text-secondary fw-400">Esta ação moverá o utilizador para a reciclagem.</span>
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
                        <form method="POST" action="users-crud/delete-user.php">
                            <input type="hidden" name="user-id" value="<?= htmlspecialchars($encryptedUserId) ?>">
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
                                                Tem a certeza que deseja desativar o utilizador
                                            </p>
                                            <h2 class="fw-700">
                                                <?= htmlspecialchars($pessoa->getNome()) ?>
                                            </h2>
                                            <span class="text-muted">Email:
                                                <?= htmlspecialchars($utilizador->getEmailAutenticacao()) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botoes -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="apagar_utilizador"
                                        class="btn btn-danger btn-glowing text-white">
                                        Sim, Desativar.
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