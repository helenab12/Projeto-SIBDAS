<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['security.backups']);
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

$backups_dir = BASE_PATH . 'files/backups/';
$backups = [];

if (is_dir($backups_dir)) {
    $files = scandir($backups_dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            $filepath = $backups_dir . $file;
            $backups[] = [
                'filename' => $file,
                'created_at' => filemtime($filepath),
                'size' => filesize($filepath)
            ];
        }
    }
}

// Sort backups by created_at descending
usort($backups, function ($a, $b) {
    return $b['created_at'] - $a['created_at'];
});

$current_page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$items_per_page = 8;
$total_backups = count($backups);
$totalPages = max(1, ceil($total_backups / $items_per_page));

if ($current_page > $totalPages) {
    $current_page = $totalPages;
}
$offset = ($current_page - 1) * $items_per_page;
$paginated_backups = array_slice($backups, $offset, $items_per_page);

function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">
    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <section class="content-container gap-6 backups">
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-wrap gap-4">
            <div class="d-flex flex-column gap-1">
                <h1>Backups do Sistema</h1>
                <p class="text-secondary fw-400">Cópias de segurança da base de dados do SIBDAS.</p>
            </div>

            <form action="<?= BASE_URL ?>private/security/backups-crud/create-backup.php" method="POST" class="m-0">
                <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-database-backup">
                        <ellipse cx="12" cy="5" rx="9" ry="3" />
                        <path d="M3 12a9 3 0 0 0 5 2.69" />
                        <path d="M21 9.3V5" />
                        <path d="M3 5v14a9 3 0 0 0 6.47 2.88" />
                        <path d="M12 12v4h4" />
                        <path d="M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16" />
                    </svg>
                    Criar Backup
                </button>
            </form>
        </div>

        <?php if ($total_backups === 0): ?>
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-database-backup">
                        <ellipse cx="12" cy="5" rx="9" ry="3" />
                        <path d="M3 12a9 3 0 0 0 5 2.69" />
                        <path d="M21 9.3V5" />
                        <path d="M3 5v14a9 3 0 0 0 6.47 2.88" />
                        <path d="M12 12v4h4" />
                        <path d="M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="fw-700 m-0">Sem Backups</h3>
                    <p class="text-secondary m-0">Ainda não foi criada nenhuma cópia de segurança.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="bento-card w-100 p-0 border-0">
                <div class="datatable-wrapper no-footer fixed-columns">
                    <div class="datatable-container">
                        <table class="sibdas-table w-100 display datatable-table">
                            <thead>
                                <tr>
                                    <th>Nome do Ficheiro</th>
                                    <th>Data de Criação</th>
                                    <th>Tamanho</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paginated_backups as $backup): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-600"><?= htmlspecialchars($backup['filename']) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-secondary"><?= date('d/m/Y, H:i', $backup['created_at']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary"><?= formatBytes($backup['size']) ?></span>
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
                                                        <a class="dropdown-item action-dropdown-item text-primary"
                                                            href="<?= BASE_URL ?>private/security/backups-crud/download-backup.php?file=<?= urlencode($backup['filename']) ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-download">
                                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                                <polyline points="7 10 12 15 17 10" />
                                                                <line x1="12" x2="12" y1="15" y2="3" />
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item action-dropdown-item text-success" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#restore-modal-<?= md5($backup['filename']) ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-undo-2">
                                                                <path d="M9 14 4 9l5-5" />
                                                                <path
                                                                    d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                                                            </svg>
                                                            Restaurar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item action-dropdown-item text-error" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete-modal-<?= md5($backup['filename']) ?>">
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
                            A mostrar <?= $offset + 1 ?>–<?= min($offset + $items_per_page, $total_backups) ?> de
                            <?= $total_backups ?> registos
                        </div>
                        <?php if ($totalPages > 1): ?>
                            <nav class="datatable-pagination">
                                <ul class="datatable-pagination-list">
                                    <?php if ($current_page > 1): ?>
                                        <li class="datatable-pagination-list-item pager"><a
                                                href="?page=<?= $current_page - 1 ?>">‹</a></li>
                                    <?php endif; ?>
                                    <?php for ($i = max(1, $current_page - 2); $i <= min($totalPages, $current_page + 2); $i++): ?>
                                        <li
                                            class="datatable-pagination-list-item <?= $i === $current_page ? 'datatable-active' : '' ?>">
                                            <a href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <?php if ($current_page < $totalPages): ?>
                                        <li class="datatable-pagination-list-item pager"><a
                                                href="?page=<?= $current_page + 1 ?>">›</a></li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php foreach ($paginated_backups as $backup): ?>
    <!-- Modal de Restauro -->
    <div class="modal fade" id="restore-modal-<?= md5($backup['filename']) ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title">Restaurar Backup</h2>
                        <span class="text-secondary fw-400">O sistema atual será substituído por este backup.</span>
                    </div>
                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                        data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon stroke-secondary">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="<?= BASE_URL ?>private/security/backups-crud/restore-backup.php">
                        <input type="hidden" name="file" value="<?= htmlspecialchars($backup['filename']) ?>">
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon"
                                    style="background-color: var(--primary-100); color: var(--primary-500);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-database-backup">
                                        <ellipse cx="12" cy="5" rx="9" ry="3" />
                                        <path d="M3 12a9 3 0 0 0 5 2.69" />
                                        <path d="M21 9.3V5" />
                                        <path d="M3 5v14a9 3 0 0 0 6.47 2.88" />
                                        <path d="M12 12v4h4" />
                                        <path
                                            d="M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary m-0">Tem a certeza que deseja restaurar a base de dados a
                                            partir deste ficheiro?</p>
                                        <h2 class="fw-700 m-0">"<?= htmlspecialchars($backup['filename']) ?>"</h2>
                                        <span class="text-muted fw-600 mt-2 text-danger">⚠️ Todos os dados posteriores a
                                            este backup serão perdidos!</span>
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

    <!-- Modal de Eliminar -->
    <div class="modal fade" id="delete-modal-<?= md5($backup['filename']) ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title">Eliminar Backup</h2>
                        <span class="text-secondary fw-400">Esta ação irá apagar o ficheiro permanentemente.</span>
                    </div>
                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                        data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon stroke-secondary">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="<?= BASE_URL ?>private/security/backups-crud/delete-backup.php">
                        <input type="hidden" name="file" value="<?= htmlspecialchars($backup['filename']) ?>">
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon"
                                    style="background-color: var(--error-100); color: var(--error);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-trash-2">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary m-0">Tem a certeza que deseja eliminar esta cópia de
                                            segurança?</p>
                                        <h2 class="fw-700 m-0">"<?= htmlspecialchars($backup['filename']) ?>"</h2>
                                        <span class="text-muted fw-600 mt-2 text-error">⚠️ Esta ação é irreversível!</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger btn-glowing ">Sim, Eliminar</button>
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
                <div class="toast-body fw-500 p-0"><?= htmlspecialchars($success_message) ?></div>
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($server_error)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0"><?= htmlspecialchars($server_error) ?></div>
                <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>