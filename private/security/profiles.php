<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Iniciar sessão
start_session();

$success_message = null;
if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$server_error = null;
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$items_per_page = 8;

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['view.profiles']);

// Obter perfis e permissões da base de dados
try {
    // Ligar à BD
    $ligacao = connect_to_db();

    // Obter perfis
    $stmt = execute_query("SELECT idPerfil, nome FROM Perfil WHERE ativo = 1 ORDER BY idPerfil ASC", [], $ligacao);
    $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obter permissões com paginação
    $whereConditions = ["ativo = 1"];
    $params = [];

    if ($search_query !== '') {
        // Desencriptar ID
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
    // Query BD
    $stmtCount = execute_query($countSql, $params, $ligacao);
    $totalPermissoesFiltered = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = max(1, ceil($totalPermissoesFiltered / $items_per_page));
    if ($current_page > $totalPages) {
        $current_page = $totalPages;
    }

    $offset = ($current_page - 1) * $items_per_page;

    $dataSql = "SELECT idPermissao, chave, descricao FROM Permissao WHERE $whereSQL ORDER BY idPermissao ASC LIMIT " . (int) $items_per_page . " OFFSET " . (int) $offset;
    // Query BD
    $stmt = execute_query($dataSql, $params, $ligacao);
    $permissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obter relações de PerfilPermissao
    $stmt = execute_query("SELECT idPerfil, idPermissao, possui FROM PerfilPermissao", [], $ligacao);
    $relacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $possuiPermissao = [];
    foreach ($relacoes as $rel) {
        $possuiPermissao[$rel['idPerfil']][$rel['idPermissao']] = (bool) $rel['possui'];
    }
} catch (Exception $e) {
    // Capturar erro
    $server_error = "Erro ao carregar perfis e permissões: " . $e->getMessage();
    $perfis = [];
    $permissoes = [];
    $possuiPermissao = [];
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
    <section class="gap-6 d-flex  flex-column padding-6 security-profiles flex-grow-1 p-0">
        <div class="d-flex flex-column flex-grow-1 gap-6 padding-6">
            <!-- Titulo -->
            <div
                class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
                <div class="d-flex flex-column gap-1">
                    <!-- Título -->
                    <h1>Perfis</h1>
                    <!-- Texto -->
                    <p class="text-secondary fw-400">Gestão de perfis de utilizadores</p>
                </div>
            </div>

            <!-- Barra de Pesquisa -->
            <div class="bento-card padding-4 gap-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
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
                        <input type="search" class="form-item w-100 search-bar-input" name="search"
                            id="search-input-field" placeholder="Pesquisar..."
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
                </form>
            </div>

            <?php if ($totalPermissoesAll === 0): ?>
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
                            <h3 class="fw-700 m-0">Sem Permissões</h3>
                            <!-- Texto -->
                            <p class="text-secondary m-0">De momento não existe nenhuma permissão.</p>
                        </div>
                    </div>
            <?php elseif (empty($permissoes)): ?>
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
                    <!-- Tabela form -->
                    <!-- Formulário -->
                    <form method="POST" action="profiles-crud/update-profiles.php" class="flex-grow-1 d-flex flex-column gap-6" novalidate>
                        <div class="bento-card w-100 p-0 border-0">
                            <div class="datatable-wrapper no-footer sortable fixed-columns">
                                <div class="datatable-container w-100 overflow-auto position-relative">
                                    <!-- Tabela -->
                                    <table class="heba-table w-100 display datatable-table">
                                        <thead>
                                            <!-- Linha -->
                                            <tr>
                                                <!-- Coluna -->
                                                <th class="text-center align-middle">
                                                    <div class="d-flex flex-column align-items-start justify-content-center">
                                                        PERMISSÃO
                                                    </div>
                                                </th>
                                                <?php foreach ($perfis as $perfil): ?>
                                                        <?php
                                                        $contagem = 0;
                                                        if (isset($possuiPermissao[$perfil['idPerfil']])) {
                                                            foreach ($possuiPermissao[$perfil['idPerfil']] as $permId => $possui) {
                                                                if ($possui)
                                                                    $contagem++;
                                                            }
                                                        }
                                                        ?>
                                                        <!-- Coluna -->
                                                        <th class="text-center align-middle">
                                                            <div
                                                                class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                                                <?= htmlspecialchars(mb_strtoupper($perfil['nome'])) ?>
                                                                <!-- Texto -->
                                                                <span class="text-muted m-0">
                                                                    <?= $contagem ?> perms.
                                                                </span>
                                                            </div>
                                                        </th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($permissoes as $permission): ?>
                                                    <!-- Linha -->
                                                    <tr>
                                                        <!-- Coluna -->
                                                        <td>
                                                            <div class="d-flex flex-column align-items-start gap-1">
                                                                <!-- Texto -->
                                                                <p class="font-mono fw-700">
                                                                    <?= htmlspecialchars($permission['chave']) ?>
                                                                </p>
                                                                <span
                                                                    class="visually-hidden"><?= htmlspecialchars(aes_encrypt($permission['idPermissao'])) ?></span>
                                                                <!-- Texto -->
                                                                <span class="text-muted">
                                                                    <?= htmlspecialchars($permission['descricao']) ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <?php foreach ($perfis as $perfil): ?>
                                                                <?php
                                                                $hasPerm = isset($possuiPermissao[$perfil['idPerfil']][$permission['idPermissao']]) && $possuiPermissao[$perfil['idPerfil']][$permission['idPermissao']];
                                                                $badgeId = "permission-badge-" . $perfil['idPerfil'] . "-" . $permission['idPermissao'];
                                                                ?>
                                                                <!-- Coluna -->
                                                                <td class="text-center align-middle">
                                                                    <!-- Input -->
                                                                    <input type="hidden"
                                                                        name="permissions[<?= aes_encrypt($perfil['idPerfil']) ?>][<?= aes_encrypt($permission['idPermissao']) ?>]"
                                                                        id="permission-input-<?= $perfil['idPerfil'] ?>-<?= $permission['idPermissao'] ?>"
                                                                        value="<?= $hasPerm ? '1' : '0' ?>">
                                                                    <!-- Botão -->
                                                                    <button type="button"
                                                                        class="check-badge bg-transparent border-0 p-0 m-0 cursor-pointer outline-none <?= $hasPerm ? 'has-permission' : '' ?> <?= tem_permissao('profiles.edit') ? '' : 'pe-none' ?>"
                                                                        id="<?= $badgeId ?>" <?= tem_permissao('profiles.edit') ? "onclick=\"togglePermission('{$perfil['idPerfil']}-{$permission['idPermissao']}')\"" : '' ?>>
                                                                        <!-- SVG -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                            class="lucide lucide-check-icon lucide-check padding-2">
                                                                            <path d="M20 6 9 17l-5-5" />
                                                                        </svg>
                                                                        <!-- SVG -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                            class="lucide lucide-x-icon lucide-x padding-2">
                                                                            <path d="M18 6 6 18" />
                                                                            <path d="m6 6 12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                        <?php endforeach; ?>
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
                                        <!-- Lista -->
                                        <ul class="datatable-pagination-list">
                                            <?php
                                            $buildQueryString = function ($newPage) use ($search_query) {
                                                $params = ['page' => $newPage];
                                                if ($search_query !== '')
                                                    $params['search'] = $search_query;
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

                            <?php if (tem_permissao('profiles.edit')): ?>
                                    <!-- Alterações pendentes -->
                                    <div class="inbox-changes-container position-sticky w-100  justify-content-between align-items-center padding-6"
                                        style="display: none;">
                                        <!-- Texto -->
                                        <p class="text-muted m-0">Existem alterações pendentes</p>
                                        <!-- Botão -->
                                        <button type="submit" class="btn btn-primary btn-glowing gap-2">
                                            <!-- SVG -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                                                <path
                                                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                                                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                                            </svg>
                                            Guardar Alterações
                                        </button>
                                    </div>
                            <?php endif; ?>
                    </form>
            <?php endif; ?>
        </div>
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