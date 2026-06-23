<?php
require_once(__DIR__ . "/../config/funcoes.php");
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

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

// Obter notificações da base de dados
$tiposNotificacao = [
    'Garantia' => new TipoNotificacao(
        'Garantia',
        'var(--warning)',
        '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'
    ),
    'Manutenção' => new TipoNotificacao(
        'Manutenção',
        'var(--primary-500)',
        '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>'
    ),
    'Stock' => new TipoNotificacao(
        'Stock',
        'var(--error)',
        '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>'
    ),
    'Calibração' => new TipoNotificacao(
        'Calibração',
        'var(--success)',
        '<path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'
    ),
    'Sistema' => new TipoNotificacao(
        'Sistema',
        'var(--secondary)',
        '<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>'
    )
];

$notificacoes = [];
try {
    $ligacao = connect_to_db();
    $stmt = execute_query(
        "SELECT n.idNotificacao, n.tipo, n.titulo, n.mensagem, n.dataCriacao, nu.lida 
         FROM Notificacao n
         INNER JOIN NotificacaoUtilizador nu ON n.idNotificacao = nu.idNotificacao
         WHERE nu.idUtilizador = :idUtilizador
         ORDER BY nu.lida ASC, n.dataCriacao DESC
         LIMIT 25",
        ['idUtilizador' => $_SESSION['id_utilizador']],
        $ligacao
    );

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tipoObj = $tiposNotificacao[$row['tipo']] ?? $tiposNotificacao['Sistema'];
        $notificacoes[] = new Notificacao(
            (int) $row['idNotificacao'],
            $tipoObj,
            $row['titulo'],
            $row['mensagem'],
            new DateTime($row['dataCriacao']),
            (bool) $row['lida']
        );
    }
    $ligacao = null;
} catch (Exception $e) {
    $server_error = "Erro ao carregar notificações: " . $e->getMessage();
}

$unreadCount = count(array_filter($notificacoes, function ($n) {
    return !$n->lida;
}));
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="padding-6 gap-6 d-flex flex-column padding-6 notifications">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <div class="d-flex flex-column gap-1">
                <h1>Notificações</h1>
                <p class="text-secondary fw-400"><span id="unread-count"><?php echo $unreadCount; ?></span> notificações
                    por ler</p>
            </div>
            <div class="d-flex gap-2">
                <?php if ($unreadCount > 0): ?>
                    <form action="<?php echo BASE_URL; ?>/private/read_all_notifications.php" method="POST" class="m-0">
                        <button type="submit" id="mark-all-read-btn" class="btn btn-primary-outline btn-small gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-icon lucide-check">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            Marcar Tudo como Lido
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lista de Notificações -->
        <div class="d-flex flex-column gap-3">
            <?php if (empty($notificacoes)): ?>
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
                        <h3 class="fw-700 m-0">Sem Notificações</h3>
                        <p class="text-secondary m-0">De momento não existe nenhuma notificação por ler.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($notificacoes as $notificacao): ?>
                    <?php $encryptedNotifId = aes_encrypt((string) $notificacao->idNotificacao); ?>
                    <a href="<?php echo BASE_URL; ?>/private/read_notification.php?id=<?php echo urlencode($encryptedNotifId); ?>"
                        class="text-decoration-none text-body">
                        <div id="notification-<?= htmlspecialchars($encryptedNotifId) ?>"
                            class="bento-card <?php echo !$notificacao->lida ? '' : 'unread'; ?> d-flex flex-row align-items-center justify-content-between gap-4 padding-4 recycle-card"
                            style="cursor: pointer;">
                            <div class="d-flex flex-wrap gap-4 justify-content-between w-100 align-items-start">
                                <div class="d-flex flex-row align-items-start gap-4">
                                    <div class="recycle-type-icon d-flex align-items-center justify-content-center padding-2"
                                        style="background-color: color-mix(in srgb, <?php echo $notificacao->tipo->cor; ?> 10%, transparent); color: <?php echo $notificacao->tipo->cor; ?>;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide">
                                            <?php echo $notificacao->tipo->caminhoSvg; ?>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex gap-1 align-items-center align-middle">
                                            <p class="fw-700">
                                                <?php echo htmlspecialchars($notificacao->titulo); ?>
                                                <?php if (!$notificacao->lida): ?>
                                                    <span class="padding-1 text-primary-500 font-bold">&bull;</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <span
                                            class="text-secondary"><?php echo htmlspecialchars($notificacao->mensagem); ?></span>
                                        <div class="d-flex flex-row gap-2">
                                            <div class="d-flex gap-1 text-muted align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock-icon lucide-clock">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="M12 6v6l4 2" />
                                                </svg>
                                                <span
                                                    class="fst-normal"><?php echo $notificacao->dataCriacao->format('d/m/Y, H:i'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <label class="text-capitalize d-flex align-middle"
                                        style="background-color: color-mix(in srgb, <?php echo $notificacao->tipo->cor; ?> 10%, transparent); color: <?php echo $notificacao->tipo->cor; ?>;">
                                        <?php echo htmlspecialchars($notificacao->tipo->nome); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
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