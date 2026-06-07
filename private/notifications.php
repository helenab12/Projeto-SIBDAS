<?php
require_once(__DIR__ . "/../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

class NotificationType
{
    public string $name;
    public string $color;
    public string $svgPath;

    public function __construct(string $name, string $color, string $svgPath)
    {
        $this->name = $name;
        $this->color = $color;
        $this->svgPath = $svgPath;
    }
}

class Notification
{
    public NotificationType $type;
    public string $title;
    public string $description;
    public DateTime $timestamp;
    public bool $isRead;

    public function __construct(NotificationType $type, string $title, string $description, DateTime $timestamp, bool $isRead)
    {
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->timestamp = $timestamp;
        $this->isRead = $isRead;
    }
}

$notificationTypes = [
    'alert' => new NotificationType(
        'Alerta',
        'var(--error)',
        '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>'
    ),
];

$notifications = [
    new Notification(
        $notificationTypes['alert'],
        'Garantia a expirar',
        'Bomba de Infusão Volumétrica (EQ-2024-003) — garantia expira em 15 dias',
        new DateTime('2026-06-03 18:00:00'),
        false
    ),
    new Notification(
        $notificationTypes['alert'],
        'Manutenção concluída',
        'Monitor IntelliVue (EQ-2024-002) — manutenção preventiva finalizada',
        new DateTime('2026-06-03 17:30:00'),
        false
    ),
    new Notification(
        $notificationTypes['alert'],
        'Stock baixo',
        'Bateria Li-Ion — stock atual (6) perto do mínimo (4)',
        new DateTime('2026-06-03 15:45:00'),
        false
    ),
    new Notification(
        $notificationTypes['alert'],
        'Calibração aprovada',
        'Ventilador V500 (EQ-2024-001) — calibração em conformidade',
        new DateTime('2026-06-03 10:15:00'),
        true
    ),
];

$unreadCount = count(array_filter($notifications, function ($n) {
    return !$n->isRead; }));
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 notifications">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Notificações</h1>
                <p class="text-secondary fw-400"><span id="unread-count"><?php echo $unreadCount; ?></span> notificações
                    por ler</p>
            </div>
            <div class="d-flex gap-2">
                <button id="mark-all-read-btn" class="btn btn-primary-outline btn-small gap-2" <?php echo $unreadCount === 0 ? 'style="display: none;"' : ''; ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-check-icon lucide-check">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    Marcar Tudo como Lido
                </button>
            </div>
        </div>

        <!-- Lista de Notificações -->
        <div class="d-flex flex-column gap-3">
            <?php foreach ($notifications as $index => $notification): ?>
                <div id="notification-<?= $index ?>"
                    class="bento-card <?php echo !$notification->isRead ? '' : 'unread'; ?> d-flex flex-row align-items-center justify-content-between gap-4 padding-4 recycle-card"
                    onclick="markAsRead(<?= $index ?>)" style="cursor: pointer;">
                    <div class="d-flex flex-wrap gap-4 justify-content-between w-100 align-items-start">
                        <div class="d-flex flex-row align-items-start gap-4">
                            <div class="recycle-type-icon d-flex align-items-center justify-content-center padding-2"
                                style="background-color: color-mix(in srgb, <?php echo $notification->type->color; ?> 10%, transparent); color: <?php echo $notification->type->color; ?>;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide">
                                    <?php echo $notification->type->svgPath; ?>
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex gap-1 align-items-center align-middle">
                                    <p class="fw-700">
                                        <?php echo $notification->title; ?>
                                        <?php if (!$notification->isRead): ?>
                                            <span class="padding-1 text-primary-500 font-bold">&bull;</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <span class="text-secondary"><?php echo $notification->description; ?></span>
                                <div class="d-flex flex-row gap-2">
                                    <div class="d-flex gap-1 text-muted align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 6v6l4 2" />
                                        </svg>
                                        <span
                                            class="fst-normal"><?php echo $notification->timestamp->format('d/m/Y, H:i'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <label class="text-capitalize d-flex align-middle"
                                style="background-color: color-mix(in srgb, <?php echo $notification->type->color; ?> 10%, transparent); color: <?php echo $notification->type->color; ?>;">
                                <?php echo $notification->type->name; ?>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>