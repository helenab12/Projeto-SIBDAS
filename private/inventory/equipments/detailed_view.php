<?php
require_once(__DIR__ . "/../../../config/config.php");
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

// Dados do equipamento
$serialNumber = 'DRG-V500-78234';
$category = 'Ventiladores';
$purchaseDate = '15/03/2022';
$supplier = 'Dräger Portugal, Lda.';
$lastMaintenance = '20/11/2025';
$nextMaintenance = '20/05/2026';
$notes = 'Equipamento principal da UCI. Última calibração em conformidade.';
$warrantyExpirationDate = '15/03/2026';

// Cálculo da garantia
$today = new DateTime('now');
$expiration = DateTime::createFromFormat('d/m/Y', $warrantyExpirationDate);
$isExpired = $today > $expiration;
$daysRemaining = 0;
if (!$isExpired) {
    $diff = $today->diff($expiration);
    $daysRemaining = $diff->days;
}
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 equipment-detailed-view">

        <!-- Titulo -->
        <div
            class="d-flex flex-column align-items-start gap-2 flex-md-row align-items-md-center gap-md-1 dashboard-title">
            <a href="equipment_list.php"
                class="d-flex align-items-center gap-2 text-decoration-none text-secondary opacity-75 hover-opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-left">
                    <path d="M19 12H5" />
                    <path d="m12 19-7-7 7-7" />
                </svg>
                <p class="fw-500 m-0">Equipamentos</p>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-right text-secondary opacity-50 d-none d-md-inline-block">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <h3 class="fw-600 text-primary mb-0">Ventilador Mecânico V500</h3>
        </div>

        <!-- Bento Card de Detalhes -->
        <div class="card bento-card padding-6 detailed-main-card d-grid gap-4">
            <!-- Icon Wrapper -->
            <div class="table-icon-wrapper equipment-icon-wrapper padding-8 detailed-main-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-box text-primary">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>

            <!-- Title & Badges Wrapper -->
            <div class="detailed-header-info d-flex flex-column justify-content-center gap-2">
                <h2 class="fw-700 text-primary mb-0">Ventilador Mecânico V500</h2>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                    <span class="equipment-badge equipment-badge-criticality-critical equipment-badge-tooltip"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Equipamento vital — falha pode resultar em risco de vida para o paciente.">Crítico</span>

                    <!-- QR Code Badge -->
                    <button class="equipment-badge equipment-badge-status-inactive btn-qr-code border-0 gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-qr-code">
                            <rect width="5" height="5" x="3" y="3" rx="1" />
                            <rect width="5" height="5" x="16" y="3" rx="1" />
                            <rect width="5" height="5" x="3" y="16" rx="1" />
                            <path d="M21 16h-3a2 2 0 0 0-2 2v3" />
                            <path d="M21 21v.01" />
                            <path d="M12 7v3a2 2 0 0 1-2 2H7" />
                            <path d="M3 12h.01" />
                            <path d="M12 3h.01" />
                            <path d="M12 16v.01" />
                            <path d="M16 12h1" />
                            <path d="M21 12v.01" />
                            <path d="M12 21h.01" />
                        </svg>
                        <span class="fw-600">QR Code</span>
                    </button>
                </div>
            </div>

            <!-- Row: Metadata Columns -->
            <div class="detailed-main-metadata d-flex flex-wrap gap-4 justify-content-between ">
                <!-- ID -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-tag">
                            <path d="M12 2H2v10l9.29 9.29c.94.94 2.56.94 3.5 0l5.5-5.5c.94-.94.94-2.56 0-3.5L12 2z" />
                            <path d="m7 7-.01-.01" />
                        </svg>
                        <label class="text-secondary fw-500">ID</label>
                    </div>
                    <p class="fw-700 text-primary m-0">EQ-2024-001</p>
                </div>

                <!-- Marca -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-building-2">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h2" />
                            <path d="M18 18h2a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-2" />
                            <path d="M10 6h4" />
                            <path d="M10 10h4" />
                            <path d="M10 14h4" />
                            <path d="M10 18h4" />
                        </svg>
                        <label class="text-secondary fw-500">Marca</label>
                    </div>
                    <p class="fw-700 text-primary m-0">Dräger</p>
                </div>

                <!-- Modelo -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-box">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                            <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg>
                        <label class="text-secondary fw-500">Modelo</label>
                    </div>
                    <p class="fw-700 text-primary m-0">Evita V500</p>
                </div>

                <!-- Localização -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-map-pin">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <label class="text-secondary fw-500">Localização</label>
                    </div>
                    <p class="fw-700 text-primary m-0">UCI - Piso 3</p>
                </div>

                <!-- Tipo de Entrada -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-file-text">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                        <label class="text-secondary fw-500">Tipo de Entrada</label>
                    </div>
                    <p class="fw-700 text-primary m-0">Compra</p>
                </div>

                <!-- Data de Fabrico -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2 text-secondary opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <label class="text-secondary fw-500">Data de Fabrico</label>
                    </div>
                    <p class="fw-700 text-primary m-0">20/08/2021</p>
                </div>
            </div>
        </div>

        <!-- Menu de Navegação por Separadores (Tabs) -->
        <nav>
            <div class="bento-card d-flex gap-2 padding-1 flex-wrap" id="nav-tab" role="tablist">
                <button class="filter-bar-badge active d-flex align-items-center gap-2 border-0"
                    id="nav-visao-geral-tab" data-bs-toggle="tab" data-bs-target="#nav-visao-geral" type="button"
                    role="tab" aria-controls="nav-visao-geral" aria-selected="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                        </path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <p class="d-none d-md-inline m-0">Visão Geral</p>
                </button>
                <button class="filter-bar-badge d-flex align-items-center gap-2 border-0" id="nav-documentos-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-documentos" type="button" role="tab"
                    aria-controls="nav-documentos" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="M10 9H8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Documentos</p>
                </button>
                <button class="filter-bar-badge d-flex align-items-center gap-2 border-0" id="nav-fornecedores-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-fornecedores" type="button" role="tab"
                    aria-controls="nav-fornecedores" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-building-2">
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h2" />
                        <path d="M18 18h2a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-2" />
                        <path d="M10 6h4" />
                        <path d="M10 10h4" />
                        <path d="M10 14h4" />
                        <path d="M10 18h4" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Fornecedores</p>
                </button>
                <button class="filter-bar-badge d-flex align-items-center gap-2 border-0" id="nav-garantias-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-garantias" type="button" role="tab"
                    aria-controls="nav-garantias" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shield">
                        <path
                            d="M20 13c0 5-8 7-8 7s-8-2-8-7V5a1 1 0 0 1 1-1c2.4 0 5.4-1.2 7-2.5 1.6 1.3 4.6 2.5 7 2.5a1 1 0 0 1 1 1v8z" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Garantias & Contratos</p>
                </button>
                <button class="filter-bar-badge d-flex align-items-center gap-2 border-0" id="nav-componentes-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-componentes" type="button" role="tab"
                    aria-controls="nav-componentes" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-puzzle-icon lucide-puzzle">
                        <path
                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Componentes</p>
                </button>
                <button class="filter-bar-badge d-flex align-items-center gap-2 border-0" id="nav-manutencoes-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-manutencoes" type="button" role="tab"
                    aria-controls="nav-manutencoes" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-wrench">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Manutenções</p>
                </button>
                <button class="filter-bar-badge d-flex align-items-center gap-2 border-0" id="nav-auditoria-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-auditoria" type="button" role="tab"
                    aria-controls="nav-auditoria" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-history">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                    <p class="d-none d-md-inline m-0">Auditoria</p>
                </button>
            </div>
        </nav>

        <!-- Tab Content Panes -->
        <div class="tab-content w-100" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-visao-geral" role="tabpanel"
                aria-labelledby="nav-visao-geral-tab">
                <div class="visao-geral-container d-flex gap-4 w-100">
                    <!-- Detalhes do Equipamento -->
                    <div class="card bento-card details-card padding-6 d-flex flex-column gap-4">
                        <h2 class="fw-700 text-primary">Detalhes do Equipamento</h2>

                        <div class="d-flex flex-column gap-6">
                            <div class="d-flex">
                                <div class="col-6 d-flex flex-column gap-1">
                                    <label class="text-secondary fw-500 opacity-75">Número de Série</label>
                                    <p class="fw-700 text-primary"><?= $serialNumber ?></p>
                                </div>
                                <div class="col-6 d-flex flex-column gap-1">
                                    <label class="text-secondary fw-500 opacity-75">Categoria</label>
                                    <p class="fw-700 text-primary"><?= $category ?></p>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="col-6 d-flex flex-column gap-1">
                                    <label class="text-secondary fw-500 opacity-75">Data de Compra</label>
                                    <p class="fw-700 text-primary"><?= $purchaseDate ?></p>
                                </div>
                                <div class="col-6 d-flex flex-column gap-1">
                                    <label class="text-secondary fw-500 opacity-75">Fornecedor Principal</label>
                                    <p class="fw-700 text-primary"><?= $supplier ?></p>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="col-6 d-flex flex-column gap-1">
                                    <label class="text-secondary fw-500 opacity-75">Última Manutenção</label>
                                    <p class="fw-700 text-primary"><?= $lastMaintenance ?></p>
                                </div>
                                <div class="col-6 d-flex flex-column gap-1">
                                    <label class="text-secondary fw-500 opacity-75">Próxima Manutenção</label>
                                    <p class="fw-700 text-primary"><?= $nextMaintenance ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-1 detailed-view-divider">
                            <label class="text-secondary fw-500 opacity-75">Notas</label>
                            <p class="text-secondary fw-500"><?= $notes ?></p>
                        </div>
                    </div>

                    <!-- Estado da Garantia -->
                    <div class="card bento-card warranty-card padding-6 d-flex flex-column gap-4">
                        <h2 class="fw-700 text-primary">Estado da Garantia</h2>

                        <?php if ($isExpired): ?>
                            <div
                                class="warranty-banner expired padding-5 d-flex flex-column align-items-center justify-content-center text-error gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-alert-triangle">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                                <p class="fw-700 m-0">Garantia Expirada</p>
                            </div>
                        <?php else: ?>
                            <div
                                class="warranty-banner active padding-5 d-flex flex-column align-items-center justify-content-center text-error gap-2">
                                <h1 class="fw-700 text-primary section-title"><?= $daysRemaining ?></h1>
                                <p class="fw-500 text-primary">dias restantes</p>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex flex-column gap-1">
                            <label class="text-secondary fw-500 opacity-75">Data de Expiração</label>
                            <p class="fw-700 text-primary"><?= $warrantyExpirationDate ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="nav-documentos" role="tabpanel" aria-labelledby="nav-documentos-tab">
                <div class="d-flex flex-column gap-6 w-100">

                    <!-- Card 1: Documentos em Falta -->
                    <div class="card bento-card padding-6 d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-2 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-alert-circle">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <h2 class="fw-700 m-0 text-primary">Documentos em Falta (5 de 7)</h2>
                        </div>

                        <div class="document-grid d-grid gap-4">
                            <!-- Item 1: Manual de Utilizador -->
                            <div class="missing-doc-card d-flex align-items-center justify-content-between padding-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="missing-doc-icon-wrapper d-flex align-items-center justify-content-center text-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                            <path d="M10 9H8" />
                                            <path d="M16 13H8" />
                                            <path d="M16 17H8" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column gap-half">
                                        <p class="fw-700">Manual de Utilizador</p>
                                        <span class="fw-600 text-warning">Pendente</span>
                                    </div>
                                </div>
                                <button
                                    class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                                    data-bs-toggle="modal" data-bs-target="#add-document-modal"
                                    data-doc-name="Manual de Utilizador">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Item 2: Certificado CE -->
                            <div class="missing-doc-card d-flex align-items-center justify-content-between padding-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="missing-doc-icon-wrapper d-flex align-items-center justify-content-center text-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                            <path d="M10 9H8" />
                                            <path d="M16 13H8" />
                                            <path d="M16 17H8" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column gap-half">
                                        <p class="fw-700">Certificado CE</p>
                                        <span class="fw-600 text-warning">Pendente</span>
                                    </div>
                                </div>
                                <button
                                    class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                                    data-bs-toggle="modal" data-bs-target="#add-document-modal"
                                    data-doc-name="Certificado CE">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Item 3: Certificado de Calibração -->
                            <div class="missing-doc-card d-flex align-items-center justify-content-between padding-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="missing-doc-icon-wrapper d-flex align-items-center justify-content-center text-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                            <path d="M10 9H8" />
                                            <path d="M16 13H8" />
                                            <path d="M16 17H8" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column gap-half">
                                        <p class="fw-700">Certificado de Calibração</p>
                                        <span class="fw-600 text-warning">Pendente</span>
                                    </div>
                                </div>
                                <button
                                    class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                                    data-bs-toggle="modal" data-bs-target="#add-document-modal"
                                    data-doc-name="Certificado de Calibração">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Item 4: Contrato de Manutenção -->
                            <div class="missing-doc-card d-flex align-items-center justify-content-between padding-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="missing-doc-icon-wrapper d-flex align-items-center justify-content-center text-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                            <path d="M10 9H8" />
                                            <path d="M16 13H8" />
                                            <path d="M16 17H8" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column gap-half">
                                        <p class="fw-700">Contrato de Manutenção</p>
                                        <span class="fw-600 text-warning">Pendente</span>
                                    </div>
                                </div>
                                <button
                                    class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                                    data-bs-toggle="modal" data-bs-target="#add-document-modal"
                                    data-doc-name="Contrato de Manutenção">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Item 5: Ficha de Segurança / Risco -->
                            <div class="missing-doc-card d-flex align-items-center justify-content-between padding-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="missing-doc-icon-wrapper d-flex align-items-center justify-content-center text-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                            <path d="M10 9H8" />
                                            <path d="M16 13H8" />
                                            <path d="M16 17H8" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column gap-half">
                                        <p class="fw-700">Ficha de Segurança / Risco</p>
                                        <span class="fw-600 text-warning">Pendente</span>
                                    </div>
                                </div>
                                <button
                                    class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                                    data-bs-toggle="modal" data-bs-target="#add-document-modal"
                                    data-doc-name="Ficha de Segurança / Risco">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Documentos Associados -->
                    <div class="card bento-card padding-6 d-flex flex-column gap-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="fw-700 m-0 text-primary">Documentos Associados</h2>
                            <button class="btn btn-primary-outline d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#add-document-modal" data-doc-name="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                <span>Adicionar</span>
                            </button>
                        </div>

                        <table id="documentsTable" class="sibdas-table w-100 display border-0">
                            <thead>
                                <tr>
                                    <th>NOME</th>
                                    <th>TIPO</th>
                                    <th>DATA</th>
                                    <th>FORNECEDOR</th>
                                    <th class="text-end">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="fw-700">Manual Técnico</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400">Manual Técnico / Serviço</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400">10/01/2023</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400">Philips Iberica, S.A.</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-3 align-items-center">
                                            <a href="#"
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                title="Download">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-download">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="7 10 12 15 17 10" />
                                                    <line x1="12" y1="15" x2="12" y2="3" />
                                                </svg>
                                            </a>
                                            <button
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                type="button" title="Editar" data-bs-toggle="modal"
                                                data-bs-target="#edit-document-modal" data-doc-id="1"
                                                data-doc-name="Manual Técnico" data-doc-type="Manual Técnico / Serviço"
                                                data-doc-supplier="Philips Iberica, S.A.">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pencil">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                    <path d="m15 5 4 4" />
                                                </svg>
                                            </button>
                                            <button
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                type="button" title="Eliminar" data-bs-toggle="modal"
                                                data-bs-target="#delete-document-modal" data-doc-id="1"
                                                data-doc-name="Manual Técnico">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-trash-2 text-secondary">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="fw-700">Guia de Instalação</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400">Relatório de Instalação</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400">10/01/2023</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400">Philips Iberica, S.A.</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-3 align-items-center">
                                            <a href="#"
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                title="Download">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-download">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="7 10 12 15 17 10" />
                                                    <line x1="12" y1="15" x2="12" y2="3" />
                                                </svg>
                                            </a>
                                            <button
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                type="button" title="Editar" data-bs-toggle="modal"
                                                data-bs-target="#edit-document-modal" data-doc-id="2"
                                                data-doc-name="Guia de Instalação"
                                                data-doc-type="Relatório de Instalação"
                                                data-doc-supplier="Philips Iberica, S.A.">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pencil">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                    <path d="m15 5 4 4" />
                                                </svg>
                                            </button>
                                            <button
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                type="button" title="Eliminar" data-bs-toggle="modal"
                                                data-bs-target="#delete-document-modal" data-doc-id="2"
                                                data-doc-name="Guia de Instalação">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-trash-2 text-secondary">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-fornecedores" role="tabpanel" aria-labelledby="nav-fornecedores-tab">
                <div class="bento-card padding-6">
                    <p class="text-secondary fw-400">Detalhes sobre o fornecedor e assistência técnica.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-garantias" role="tabpanel" aria-labelledby="nav-garantias-tab">
                <div class="bento-card padding-6">
                    <p class="text-secondary fw-400">Informações de garantia, contratos de manutenção e seguros.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-componentes" role="tabpanel" aria-labelledby="nav-componentes-tab">
                <div class="bento-card padding-6">
                    <p class="text-secondary fw-400">Componentes e consumíveis integrados ou sobressalentes.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-manutencoes" role="tabpanel" aria-labelledby="nav-manutencoes-tab">
                <div class="bento-card padding-6">
                    <p class="text-secondary fw-400">Histórico de manutenções preventivas, corretivas e calibrações.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-auditoria" role="tabpanel" aria-labelledby="nav-auditoria-tab">
                <div class="bento-card padding-6">
                    <p class="text-secondary fw-400">Registo de auditoria e logs de alterações no equipamento.</p>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- Modal de Adicionar Documento -->
<div class="modal fade" id="add-document-modal" tabindex="-1" aria-labelledby="addDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="addDocumentModalLabel">
                    Adicionar Documento</h2>
                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x stroke-secondary">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body p-0">
                <form id="add-document-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Nome do Documento -->
                    <div class="d-flex flex-column form-item w-100">
                        <div class="d-flex gap-1">
                            <label for="doc-name">Nome do Documento</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="text" id="doc-name" name="doc-name" placeholder="Ex: Manual de Utilizador"
                            required>
                    </div>

                    <!-- Tipo -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="doc-type">Tipo</label>
                        <select id="doc-type" name="doc-type" class="form-select w-100">
                            <option value="" disabled selected>Selecionar tipo...</option>
                            <option value="Manual de Utilizador">Manual de Utilizador</option>
                            <option value="Certificado CE">Certificado CE</option>
                            <option value="Certificado de Calibração">Certificado de Calibração</option>
                            <option value="Contrato de Manutenção">Contrato de Manutenção</option>
                            <option value="Ficha de Segurança / Risco">Ficha de Segurança / Risco</option>
                            <option value="Manual Técnico / Serviço">Manual Técnico / Serviço</option>
                            <option value="Relatório de Instalação">Relatório de Instalação</option>
                        </select>
                    </div>

                    <!-- Fornecedor Associado -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="doc-supplier">Fornecedor Associado</label>
                        <select id="doc-supplier" name="doc-supplier" class="form-select w-100">
                            <option value="Nenhum" selected>Nenhum</option>
                            <option value="Dräger Portugal, Lda.">Dräger Portugal, Lda.</option>
                            <option value="Philips Iberica, S.A.">Philips Iberica, S.A.</option>
                            <option value="Siemens Healthineers">Siemens Healthineers</option>
                        </select>
                    </div>

                    <!-- File Upload Zone -->
                    <div class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2"
                        id="add-dropzone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload file-upload-icon">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" x2="12" y1="3" y2="15" />
                        </svg>
                        <p class="file-upload-text">Arraste ficheiros ou
                            <span class="file-upload-text-action text-primary-500">clique para selecionar</span>
                        </p>
                        <span class="m-0 text-muted" id="add-dropzone-text">PDF, JPG, PNG — máx. 10MB</span>
                        <input type="file" id="doc-file" name="doc-file" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                            required>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-check">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Editar Documento -->
<div class="modal fade" id="edit-document-modal" tabindex="-1" aria-labelledby="editDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="editDocumentModalLabel">
                    Editar Documento</h2>
                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x stroke-secondary">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body p-0">
                <form id="edit-document-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Nome do Documento -->
                    <div class="d-flex flex-column form-item w-100">
                        <div class="d-flex gap-1">
                            <label for="edit-doc-name">Nome do Documento</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="text" id="edit-doc-name" name="doc-name" placeholder="Ex: Manual de Utilizador"
                            required>
                    </div>

                    <!-- Tipo -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-doc-type">Tipo</label>
                        <select id="edit-doc-type" name="doc-type" class="form-select w-100">
                            <option value="" disabled>Selecionar tipo...</option>
                            <option value="Manual de Utilizador">Manual de Utilizador</option>
                            <option value="Certificado CE">Certificado CE</option>
                            <option value="Certificado de Calibração">Certificado de Calibração</option>
                            <option value="Contrato de Manutenção">Contrato de Manutenção</option>
                            <option value="Ficha de Segurança / Risco">Ficha de Segurança / Risco</option>
                            <option value="Manual Técnico / Serviço">Manual Técnico / Serviço</option>
                            <option value="Relatório de Instalação">Relatório de Instalação</option>
                        </select>
                    </div>

                    <!-- Fornecedor Associado -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-doc-supplier">Fornecedor Associado</label>
                        <select id="edit-doc-supplier" name="doc-supplier" class="form-select w-100">
                            <option value="Nenhum">Nenhum</option>
                            <option value="Dräger Portugal, Lda.">Dräger Portugal, Lda.</option>
                            <option value="Philips Iberica, S.A.">Philips Iberica, S.A.</option>
                            <option value="Siemens Healthineers">Siemens Healthineers</option>
                        </select>
                    </div>

                    <!-- File Upload Zone -->
                    <div class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2"
                        id="edit-dropzone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload file-upload-icon">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" x2="12" y1="3" y2="15" />
                        </svg>
                        <p class="file-upload-text">Arraste ficheiros ou
                            <span class="file-upload-text-action text-primary-500">clique para selecionar</span>
                        </p>
                        <span class="m-0 text-muted" id="edit-dropzone-text">PDF, JPG, PNG — máx. 10MB</span>
                        <input type="file" id="edit-doc-file" name="doc-file" class="d-none"
                            accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-check">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Remoção de Documento -->
<div class="modal fade" id="delete-document-modal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="deleteDocumentModalLabel">Eliminar Documento</h2>
                    <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
                </div>

                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x stroke-secondary">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
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
                                stroke-linejoin="round" class="lucide lucide-alert-triangle text-error">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                <line x1="12" y1="9" x2="12" y2="13" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                <p class="text-secondary m-0">Tem a certeza que deseja apagar permanentemente o
                                    documento</p>
                                <h2 class="fw-700 text-primary m-0" id="delete-doc-display-name">"Manual Técnico"</h2>
                            </div>
                            <div class="danger-banner text-error text-center padding-3">
                                <span>⚠️ Este ficheiro será eliminado permanentemente. Todos os dados associados serão
                                    perdidos.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botoes -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-glowing text-white">Sim, Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>