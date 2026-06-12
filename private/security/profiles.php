<?php
require_once(__DIR__ . "/../../config/funcoes.php");
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

redirect_if_not_logged();

// Obter perfis e permissões da base de dados
try {
    $ligacao = connect_to_db();

    // Obter perfis
    $stmt = execute_query("SELECT idPerfil, nome FROM Perfil WHERE ativo = 1 ORDER BY idPerfil ASC", [], $ligacao);
    $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obter permissões
    $stmt = execute_query("SELECT idPermissao, chave, descricao FROM Permissao WHERE ativo = 1 ORDER BY idPermissao ASC", [], $ligacao);
    $permissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obter relações de PerfilPermissao
    $stmt = execute_query("SELECT idPerfil, idPermissao, possui FROM PerfilPermissao", [], $ligacao);
    $relacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $possuiPermissao = [];
    foreach ($relacoes as $rel) {
        $possuiPermissao[$rel['idPerfil']][$rel['idPermissao']] = (bool) $rel['possui'];
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar perfis e permissões: " . $e->getMessage();
    $perfis = [];
    $permissoes = [];
    $possuiPermissao = [];
}

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>
<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <form method="POST" action="profiles-crud/update-profiles.php" class="flex-grow-1 d-flex flex-column">
        <section class="content-container gap-6 security-profiles flex-grow-1 p-0">
            <div class="d-flex flex-column flex-grow-1 gap-6 padding-6">
                <!-- Titulo -->
                <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
                    <div class="d-flex flex-column gap-1">
                        <h1>Perfis</h1>
                        <p class="text-secondary fw-400">Gestão de perfis de utilizadores</p>
                    </div>
                </div>

                <!-- Barra de Pesquisa -->
                <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
                    <form action="" class="flex-grow-1">
                        <div class="form-item w-100 position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                            <input type="text" class="form-item w-100 search-bar-input"
                                placeholder="Pesquisar por permissão...">
                        </div>
                    </form>
                </div>


                <!-- Tabela -->
                <div class="bento-card w-100 p-0 border-0">
                    <table id="equipmentsTable" class="sibdas-table w-100 display">
                        <thead>
                            <tr>
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
                                    <th class="text-center align-middle">
                                        <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                            <?= htmlspecialchars(mb_strtoupper($perfil['nome'])) ?>
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
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column align-items-start gap-1">
                                            <p class="font-mono fw-700">
                                                <?= htmlspecialchars($permission['chave']) ?>
                                            </p>
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
                                        <td class="text-center align-middle">
                                            <input type="hidden"
                                                name="permissions[<?= aes_encrypt($perfil['idPerfil']) ?>][<?= aes_encrypt($permission['idPermissao']) ?>]"
                                                id="permission-input-<?= $perfil['idPerfil'] ?>-<?= $permission['idPermissao'] ?>"
                                                value="<?= $hasPerm ? '1' : '0' ?>">
                                            <button type="button" class="check-badge <?= $hasPerm ? 'has-permission' : '' ?>"
                                                id="<?= $badgeId ?>"
                                                onclick="togglePermission('<?= $perfil['idPerfil'] . '-' . $permission['idPermissao'] ?>')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-check-icon lucide-check padding-2">
                                                    <path d="M20 6 9 17l-5-5" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
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
            </div>

            <!-- Alterações pendentes -->
            <div class="inbox-changes-container justify-content-between align-items-center padding-6"
                style="display: none;">
                <p class="text-muted m-0">Existem alterações pendentes</p>
                <button type="submit" class="btn btn-primary btn-glowing gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg>
                    Guardar Alterações
                </button>
            </div>

        </section>
    </form>
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

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>