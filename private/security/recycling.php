<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

class RecycleType
{
    public string $name;
    public string $svgPath;
    public string $color;
}

$recycleTypes = [
    'equipment' => (function () {
        $type = new RecycleType();
        $type->name = 'Equipamento';
        $type->color = 'var(--primary-500)';
        $type->svgPath = '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path> <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline> <line x1="12" y1="22.08" x2="12" y2="12"></line>';
        return $type;
    })(),
    'supplier' => (function () {
        $type = new RecycleType();
        $type->name = 'Fornecedor';
        $type->color = 'var(--primary-500)';
        $type->svgPath = '<path d="M10 12h4" /> <path d="M10 8h4" /> <path d="M14 21v-3a2 2 0 0 0-4 0v3" /> <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" /> <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />';
        return $type;
    })(),
    'user' => (function () {
        $type = new RecycleType();
        $type->name = 'Utilizador';
        $type->color = '#a855f7';
        $type->svgPath = '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /> <path d="M16 3.128a4 4 0 0 1 0 7.744" /> <path d="M22 21v-2a4 4 0 0 0-3-3.87" /> <circle cx="9" cy="7" r="4" />';
        return $type;
    })(),
    'person' => (function () {
        $type = new RecycleType();
        $type->name = 'Pessoa';
        $type->color = 'var(--success)';
        $type->svgPath = '<path d="m14.305 19.53.923-.382" /> <path d="m15.228 16.852-.923-.383" /> <path d="m16.852 15.228-.383-.923" /> <path d="m16.852 20.772-.383.924" /> <path d="m19.148 15.228.383-.923" /> <path d="m19.53 21.696-.382-.924" /> <path d="M2 21a8 8 0 0 1 10.434-7.62" /> <path d="m20.772 16.852.924-.383" /> <path d="m20.772 19.148.924.383" /> <circle cx="10" cy="8" r="5" /> <circle cx="18" cy="18" r="3" />';
        return $type;
    })(),
];

class RecycledObject
{
    public RecycleType $type;
    public string $name;
    public string $description;
    public DateTime $removedAt;
    public string $removedBy;

    public function __construct(string $name, string $description, RecycleType $type, DateTime $removedAt, string $removedBy)
    {
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->removedAt = $removedAt;
        $this->removedBy = $removedBy;
    }
}

$recycledOjects = [
    new RecycledObject(
        'Autoclave ELV 3870',
        'EQ-2024-008 • Esterilizadores • Abatido',
        $recycleTypes['equipment'],
        new DateTime('2026-04-01'),
        'Eng. Rui Santos'
    ),
    new RecycledObject(
        'MedEquip Solutions, Lda.',
        'NIF: 509876543 • Tipo: Equipamento',
        $recycleTypes['supplier'],
        new DateTime('2026-03-28'),
        'Admin Sistema'
    ),
    new RecycledObject(
        'Dr. António Martins',
        'MED-003 • Cardiologia',
        $recycleTypes['person'],
        new DateTime('2026-03-20'),
        'Admin Sistema'
    ),
    new RecycledObject(
        'antonio.martins',
        'Dr. António Martins • Perfil: Consulta',
        $recycleTypes['user'],
        new DateTime('2026-03-20'),
        'Admin Sistema'
    ),
    new RecycledObject(
        'CardioTech Portugal',
        'NIF: 508765432 • Tipo: Consumíveis',
        $recycleTypes['supplier'],
        new DateTime('2026-03-15'),
        'Dr.ª Helena Barbosa'
    ),
    new RecycledObject(
        'Enf. Paula Sousa',
        'ENF-002 • Urgência',
        $recycleTypes['person'],
        new DateTime('2026-02-28'),
        'Dr.ª Helena Barbosa'
    ),
    new RecycledObject(
        'paula.sousa',
        'Enf. Paula Sousa • Perfil: Técnico',
        $recycleTypes['user'],
        new DateTime('2026-02-28'),
        'Dr.ª Helena Barbosa'
    ),
    new RecycledObject(
        'Monitor Datex-Ohmeda S/5',
        'EQ-2023-015 • Monitores • Obsoleto',
        $recycleTypes['equipment'],
        new DateTime('2026-01-12'),
        'Eng. Carlos Mendes'
    ),
    new RecycledObject(
        'BioSystems Ibéria',
        'NIF: 510987654 • Tipo: Serviços',
        $recycleTypes['supplier'],
        new DateTime('2026-01-05'),
        'Admin Sistema'
    )
];

?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 recycling">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Reciclagem</h1>
                <p class="text-secondary fw-400">Registos removidos do sistema (Soft Delete). Pode restaurar ou apagar
                    definitivamente.</p>
            </div>
        </div>

        <!-- Select de Tipos de Reciclagem -->
        <div class="bento-card d-flex gap-2 padding-1 flex-wrap">
            <button class="filter-bar-badge active d-flex align-items-center gap-2" id="type-all"
                onclick="changeSelectedTyoe('type-all')">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide">
                    <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5" />
                    <path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12" />
                    <path d="m14 16-3 3 3 3" />
                    <path d="M8.293 13.596 7.196 9.5 3.1 10.598" />
                    <path
                        d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843" />
                    <path d="m13.378 9.633 4.096 1.098 1.097-4.096" />
                </svg>
                <p>Todos</p>
                <span
                    class="type-count d-flex align-items-center justify-content-center fw-700"><?php echo count($recycledOjects); ?></span>
            </button>
            <?php foreach ($recycleTypes as $key): ?>
                <button class="filter-bar-badge d-flex align-items-center gap-2" id="type-<?= strtolower($key->name) ?>"
                    onclick="changeSelectedTyoe('type-<?= strtolower($key->name) ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide">
                        <?php echo $key->svgPath; ?>
                    </svg>
                    <p><?php echo $key->name; ?></p>
                    <span class="type-count d-flex align-items-center justify-content-center fw-700">
                        <?php
                        $count = 0;
                        foreach ($recycledOjects as $obj) {
                            if ($obj->type->name === $key->name) {
                                $count++;
                            }
                        }
                        echo $count;
                        ?>
                    </span>
                </button>
            <?php endforeach; ?>
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
                        placeholder="Pesquisar na reciclagem...">
                </div>
            </form>
        </div>

        <!-- Lista de Reciclagem -->
        <div class="d-flex flex-column gap-3">
            <?php foreach ($recycledOjects as $object): ?>
                <div
                    class="bento-card d-flex flex-row align-items-center justify-content-between gap-4 padding-4 recycle-card">
                    <div class="d-flex flex-wrap gap-4 justify-content-between w-100 align-items-center">
                        <div class="d-flex flex-row align-items-center gap-4">
                            <div class="recycle-type-icon d-flex align-items-center justify-content-center padding-2"
                                style="background-color: color-mix(in srgb, <?php echo $object->type->color; ?> 10%, transparent); color: <?php echo $object->type->color; ?>;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide">
                                    <?php echo $object->type->svgPath; ?>
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex gap-2 align-items-center">
                                    <p class="fw-600"><?php echo $object->name; ?></p>
                                    <label class="text-uppercase d-flex align-middle"
                                        style="background-color: color-mix(in srgb, <?php echo $object->type->color; ?> 10%, transparent); color: <?php echo $object->type->color; ?>;">
                                        <?php echo $object->type->name; ?>
                                    </label>
                                </div>
                                <span class="text-secondary"><?php echo $object->description; ?></span>
                                <div class="d-flex gap-1 text-muted align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    <span class="fst-normal"> Removido: <?php echo $object->removedAt->format('Y-m-d'); ?>
                                        por <?php echo $object->removedBy; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row action-buttons gap-2">
                            <button class="btn btn-small text-success d-flex align-items-center gap-2 restore">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2">
                                    <path d="M9 14 4 9l5-5" />
                                    <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                                </svg>
                                <span class="fw-700">Restaurar</span>
                            </button>
                            <button class="btn btn-small text-danger d-flex align-items-center gap-2 delete"
                                data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2">
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                </svg>
                                <span class="fw-700">Apagar</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Confirmação de Remoção -->
<div class="modal fade" id="delete-confirm-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="deleteModalLabel">Apagar Definitivamente
                    </h2>
                    <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
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
                <div
                    class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                    <div class="d-flex flex-column align-items-center gap-4">
                        <div class="d-flex padding-3 danger-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                            </svg>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                <p class="text-secondary">
                                    Tem a certeza que deseja apagar
                                </p>
                                <h2 class="fw-700">"Autoclave ELV 3870"?</p>
                                    <span class="text-muted">Tipo: Equipamento</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botoes -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-glowing text-white">
                            Sim, Apagar.
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once BASE_PATH . 'private/includes/footer.php';
?>