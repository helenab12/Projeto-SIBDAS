<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

class AuditLogType
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

class AuditLog
{
    public AuditLogType $type;
    public string $entityText;
    public string $entityType;
    public string $description;
    public DateTime $timestamp;
    public string $responsible;

    public function __construct(AuditLogType $type, string $entityText, string $entityType, string $description, DateTime $timestamp, string $responsible)
    {
        $this->type = $type;
        $this->entityText = $entityText;
        $this->entityType = $entityType;
        $this->description = $description;
        $this->timestamp = $timestamp;
        $this->responsible = $responsible;
    }
}

$auditLogTypes = [
    'update' => new AuditLogType(
        'Atualização',
        'var(--primary-500)',
        '<circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>'
    ),
    'maintenance' => new AuditLogType(
        'Manutenção Registada',
        'var(--success)',
        '<circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path>'
    ),
    'abatement' => new AuditLogType(
        'Abate de Equipamento',
        'var(--warning)',
        '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path>'
    ),
    'creation' => new AuditLogType(
        'Criação',
        'var(--success)',
        '<circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path>'
    ),
    'soft_delete' => new AuditLogType(
        'Soft Delete',
        'var(--error)',
        '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="M12 8v4"></path><path d="M12 16h.01"></path>'
    ),
    'edition' => new AuditLogType(
        'Edição',
        'var(--primary-500)',
        '<circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>'
    ),
    'document' => new AuditLogType(
        'Documento Adicionado',
        'var(--primary-500)',
        '<circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>'
    ),
];

$auditLogs = [
    new AuditLog(
        $auditLogTypes['update'],
        'Equipamento: EQ-2024-001',
        'Equipamento',
        'Campo atualizado de 2025-09-20 para 2025-11-20',
        new DateTime('2026-04-07 13:45:00'),
        'Dr. Manuel Costa'
    ),
    new AuditLog(
        $auditLogTypes['maintenance'],
        'Equipamento: EQ-2024-002',
        'Equipamento',
        'Manutenção preventiva concluída',
        new DateTime('2026-04-07 12:30:00'),
        'Eng.ª Ana Ferreira'
    ),
    new AuditLog(
        $auditLogTypes['abatement'],
        'Equipamento: EQ-2023-050',
        'Equipamento',
        'Equipamento marcado como abatido por obsolescência',
        new DateTime('2026-04-07 11:15:00'),
        'Admin Sistema'
    ),
    new AuditLog(
        $auditLogTypes['creation'],
        'Fornecedor: 12',
        'Fornecedor',
        'Novo fornecedor de consumíveis registado',
        new DateTime('2026-04-06 16:20:00'),
        'Sofia Oliveira'
    ),
    new AuditLog(
        $auditLogTypes['soft_delete'],
        'Pessoa: 7',
        'Pessoa',
        'Dr.ª Maria Lopes marcada como inativa',
        new DateTime('2026-04-06 10:00:00'),
        'Admin Sistema'
    ),
    new AuditLog(
        $auditLogTypes['edition'],
        'Componente: 1',
        'Componente',
        'Stock atualizado de 26 para 24 (consumo em EQ-2024-001)',
        new DateTime('2026-04-05 15:30:00'),
        'Eng. Pedro Santos'
    ),
    new AuditLog(
        $auditLogTypes['document'],
        'Equipamento: EQ-2024-007',
        'Equipamento',
        'Certificado de Calibração adicionado',
        new DateTime('2026-04-05 09:00:00'),
        'Eng.ª Ana Ferreira'
    ),
];
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Logs de Auditoria</h1>
                <p class="text-secondary fw-400"><?php echo count($auditLogs); ?> registos encontrados</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-outline gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-download-icon lucide-download">
                        <path d="M12 15V3" />
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="m7 10 5 5 5-5" />
                    </svg>
                    Exportar Logs
                </button>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
            <form action="" class="flex-grow-1">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input"
                        placeholder="Pesquisar por ação, utilizador ou entidade...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Ações" id="filter-type">
                    <option value="" selected>Todas as ações</option>
                    <option value="Atualização">Atualização</option>
                    <option value="Manutenção">Manutenção</option>
                    <option value="Abate">Abate</option>
                    <option value="Criação">Criação</option>
                    <option value="Edição">Edição</option>
                    <option value="Soft Delete">Soft Delete</option>
                    <option value="Documento">Documento</option>
                </select>
            </div>
        </div>

        <!-- Lista de Logs de Auditoria -->
        <div class="d-flex flex-column gap-3">
            <?php foreach ($auditLogs as $log): ?>
                <div
                    class="bento-card d-flex flex-row align-items-center justify-content-between gap-4 padding-4 recycle-card">
                    <div class="d-flex flex-wrap gap-4 justify-content-between w-100 align-items-start">
                        <div class="d-flex flex-row align-items-start gap-4">
                            <div class="recycle-type-icon d-flex align-items-center justify-content-center padding-2"
                                style="background-color: color-mix(in srgb, <?php echo $log->type->color; ?> 10%, transparent); color: <?php echo $log->type->color; ?>;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide">
                                    <?php echo $log->type->svgPath; ?>
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex gap-1 align-items-center align-middle">
                                    <p class="fw-700"><?php echo $log->type->name; ?></p>
                                    <p class="d-flex flex">&bull;</p>
                                    <p><?php echo $log->entityText; ?></p>
                                </div>
                                <span class="text-secondary"><?php echo $log->description; ?></span>
                                <div class="d-flex flex-row gap-2">
                                    <div class="d-flex gap-1 text-muted align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 6v6l4 2" />
                                        </svg>
                                        <span class="fst-normal"><?php echo $log->timestamp->format('d/m/Y, H:i'); ?></span>
                                    </div>
                                    <div class="d-flex gap-1 text-muted align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <span class="fst-normal"><?php echo $log->responsible; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <label class="text-capitalize d-flex align-middle"
                                style="background-color: color-mix(in srgb, <?php echo $log->type->color; ?> 10%, transparent); color: <?php echo $log->type->color; ?>;">
                                <?php echo $log->entityType; ?>
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