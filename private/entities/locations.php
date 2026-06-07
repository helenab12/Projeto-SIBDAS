<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Localizações</h1>
                <p class="text-secondary fw-400">Gestão de edifícios, pisos, serviços e salas</p>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-open-create-equipment-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#equipment-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Novo Edifício
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
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" placeholder="Pesquisar localizações...">
                </div>
            </form>
        </div>

        <!-- Localizacoes -->
        <div class="d-flex flex-column gap-4">

            <!-- Card 1: Edifício Principal -->
            <div class="d-flex flex-column gap-3 locations">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level level-1 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                        aria-controls="collapseOne">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex gap-3 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4"></path>
                                        <path d="M10 8h4"></path>
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                        </path>
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <p class="fw-700 text-decoration-none">Edifício Principal</p>
                                    <span class="text-secondary text-decoration-none">4 pisos • 8
                                        serviços</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus padding-2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                    <path
                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                    </path>
                                    <path d="m15 5 4 4"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                    </path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                            </div>
                        </div>
                    </button>

                    <div id="collapseOne" class="collapse w-100" aria-labelledby="headingOne">
                        <div class="card-body p-0 d-flex flex-column gap-3 collapse-inner-level padding-bottom-4">

                            <!-- Level 2: Floor (Piso) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFloorZero" aria-expanded="false"
                                    aria-controls="collapseFloorZero">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 0</p>
                                                <span class="text-secondary text-decoration-none">2
                                                    serviços</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                </path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>

                                <!-- Level 2 Body: Collapse Piso 0 -->
                                <div id="collapseFloorZero" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">

                                        <!-- Level 3: Service (Serviço) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapseServiceUrgence"
                                                aria-expanded="false" aria-controls="collapseServiceUrgence">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Urgência
                                                            </p>
                                                            <span class="text-secondary text-decoration-none">4
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>

                                            <!-- Level 3 Body: Collapse Serviço (Rooms List) -->
                                            <div id="collapseServiceUrgence" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">

                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Triagem</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Reanimação</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 3 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Box 1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 4 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Box 2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level 3: Service (Serviço) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapseServiceImagiology"
                                                aria-expanded="false" aria-controls="collapseServiceImagiology">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">
                                                                Imagiologia
                                                            </p>
                                                            <span class="text-secondary text-decoration-none">3
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>

                                            <!-- Level 3 Body: Collapse Serviço (Rooms List) -->
                                            <div id="collapseServiceImagiology" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">

                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de TC</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de RM</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 3 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Raio-X</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Level 2: Floor (Piso 1) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFloorOne" aria-expanded="false"
                                    aria-controls="collapseFloorOne">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 1</p>
                                                <span class="text-secondary text-decoration-none">2
                                                    serviços</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>

                                <!-- Level 2 Body: Collapse Piso 1 -->
                                <div id="collapseFloorOne" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">

                                        <!-- Level 3: Service (Bloco Operatório) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseServiceBlocoOperatorio" aria-expanded="false"
                                                aria-controls="collapseServiceBlocoOperatorio">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Bloco
                                                                Operatório</p>
                                                            <span class="text-secondary text-decoration-none">3
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>

                                            <!-- Level 3 Body: Collapse Bloco Operatório (Rooms List) -->
                                            <div id="collapseServiceBlocoOperatorio" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">BO1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">BO2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 3 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Recobro</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level 3: Service (Esterilização) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapseServiceEsterilizacao"
                                                aria-expanded="false" aria-controls="collapseServiceEsterilizacao">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">
                                                                Esterilização</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>

                                            <!-- Level 3 Body: Collapse Esterilização (Rooms List) -->
                                            <div id="collapseServiceEsterilizacao" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Lavagem</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Esterilização</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Level 2: Floor (Piso 2) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFloorTwo" aria-expanded="false"
                                    aria-controls="collapseFloorTwo">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 2</p>
                                                <span class="text-secondary text-decoration-none">2
                                                    serviços</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                <!-- Level 2 Body: Collapse Piso 2 -->
                                <div id="collapseFloorTwo" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">
                                        <!-- Level 3: Service (Medicina Interna) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseServiceMedicinaInterna" aria-expanded="false"
                                                aria-controls="collapseServiceMedicinaInterna">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Medicina
                                                                Interna</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Medicina Interna (Rooms List) -->
                                            <div id="collapseServiceMedicinaInterna" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Enfermaria
                                                                A</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Enfermaria
                                                                B</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level 3: Service (Cardiologia) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapseServiceCardiologia"
                                                aria-expanded="false" aria-controls="collapseServiceCardiologia">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">
                                                                Cardiologia</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Cardiologia (Rooms List) -->
                                            <div id="collapseServiceCardiologia" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de
                                                                Hemodinâmica</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Enfermaria
                                                                C</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Level 2: Floor (Piso 3) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFloorThree" aria-expanded="false"
                                    aria-controls="collapseFloorThree">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 3</p>
                                                <span class="text-secondary text-decoration-none">2
                                                    serviços</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                <!-- Level 2 Body: Collapse Piso 3 -->
                                <div id="collapseFloorThree" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">
                                        <!-- Level 3: Service (UCI) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapseServiceUCI"
                                                aria-expanded="false" aria-controls="collapseServiceUCI">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">UCI</p>
                                                            <span class="text-secondary text-decoration-none">4
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse UCI (Rooms List) -->
                                            <div id="collapseServiceUCI" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">UCI Box 1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">UCI Box 2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 3 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">UCI Box 3</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 4 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">UCI Box 4</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level 3: Service (Neonatologia) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapseServiceNeonatologia"
                                                aria-expanded="false" aria-controls="collapseServiceNeonatologia">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">
                                                                Neonatologia</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Neonatologia (Rooms List) -->
                                            <div id="collapseServiceNeonatologia" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">UCIN Box 1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">UCIN Box 2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Edifício Ambulatório -->
            <div class="d-flex flex-column gap-3 locations">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level level-1 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex gap-3 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4"></path>
                                        <path d="M10 8h4"></path>
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                        </path>
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <p class="fw-700 text-decoration-none m-0">Edifício Ambulatório</p>
                                    <span class="text-secondary text-decoration-none">2 pisos • 3
                                        serviços</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus padding-2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                    <path
                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                    </path>
                                    <path d="m15 5 4 4"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                    </path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                            </div>
                        </div>
                    </button>

                    <div id="collapseTwo" class="collapse w-100" aria-labelledby="headingTwo">
                        <div class="card-body p-0 d-flex flex-column gap-3 collapse-inner-level padding-bottom-4">
                            <!-- Level 2: Floor (Piso 0) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseAmbulatorioFloorZero"
                                    aria-expanded="false" aria-controls="collapseAmbulatorioFloorZero">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 0</p>
                                                <span class="text-secondary text-decoration-none">2
                                                    serviços</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                <!-- Level 2 Body: Collapse Piso 0 -->
                                <div id="collapseAmbulatorioFloorZero" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">
                                        <!-- Level 3: Service (Consultas Externas) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseAmbulatorioServiceConsultasExternas"
                                                aria-expanded="false"
                                                aria-controls="collapseAmbulatorioServiceConsultasExternas">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Consultas
                                                                Externas</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Consultas Externas (Rooms List) -->
                                            <div id="collapseAmbulatorioServiceConsultasExternas"
                                                class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Consultório
                                                                1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Consultório
                                                                2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level 3: Service (Laboratório) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseAmbulatorioServiceLaboratorio"
                                                aria-expanded="false"
                                                aria-controls="collapseAmbulatorioServiceLaboratorio">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">
                                                                Laboratório</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Laboratório (Rooms List) -->
                                            <div id="collapseAmbulatorioServiceLaboratorio" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Lab.
                                                                Bioquímica</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Lab.
                                                                Hematologia</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Level 2: Floor (Piso 1) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseAmbulatorioFloorOne"
                                    aria-expanded="false" aria-controls="collapseAmbulatorioFloorOne">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 1</p>
                                                <span class="text-secondary text-decoration-none">1
                                                    serviço</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                <!-- Level 2 Body: Collapse Piso 1 -->
                                <div id="collapseAmbulatorioFloorOne" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">
                                        <!-- Level 3: Service (Hospital de Dia) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseAmbulatorioServiceHospitalDeDia"
                                                aria-expanded="false"
                                                aria-controls="collapseAmbulatorioServiceHospitalDeDia">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Hospital
                                                                de Dia</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Hospital de Dia (Rooms List) -->
                                            <div id="collapseAmbulatorioServiceHospitalDeDia" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de Tratamento
                                                                1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Sala de Tratamento
                                                                2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Edifício Logístico -->
            <div class="d-flex flex-column gap-3 locations">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level level-1 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                        aria-controls="collapseThree">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex gap-3 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4"></path>
                                        <path d="M10 8h4"></path>
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                        </path>
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <p class="fw-700 text-decoration-none m-0">Edifício Logístico</p>
                                    <span class="text-secondary text-decoration-none">1 piso • 2 serviços</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus padding-2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                    <path
                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                    </path>
                                    <path d="m15 5 4 4"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                    </path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                            </div>
                        </div>
                    </button>

                    <div id="collapseThree" class="collapse w-100" aria-labelledby="headingTwo">
                        <div class="card-body p-0 d-flex flex-column gap-3 collapse-inner-level padding-bottom-4">
                            <!-- Level 2: Floor (Piso 0) Accordion -->
                            <div class="d-flex flex-column w-100">
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-2 collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#collapseLogisticoFloorZero"
                                    aria-expanded="false" aria-controls="collapseLogisticoFloorZero">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex gap-3 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                            <div class="table-icon-wrapper text-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-layers-icon lucide-layers">
                                                    <path d="m12 3-10 5 10 5 10-5-10-5Z"></path>
                                                    <path d="m2 17 10 5 10-5"></path>
                                                    <path d="m2 12 10 5 10-5"></path>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                <p class="fw-700 text-decoration-none m-0">Piso 0</p>
                                                <span class="text-secondary text-decoration-none">2
                                                    serviços</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 text-muted align-items-center action-buttons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus-icon lucide-plus padding-2">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                <!-- Level 2 Body: Collapse Piso 0 -->
                                <div id="collapseLogisticoFloorZero" class="w-100 collapse">
                                    <div class="d-flex flex-column gap-3 collapse-inner-level">
                                        <!-- Level 3: Service (Armazém Central) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseLogisticoServiceArmazemCentral"
                                                aria-expanded="false"
                                                aria-controls="collapseLogisticoServiceArmazemCentral">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Armazém
                                                                Central</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Armazém Central (Rooms List) -->
                                            <div id="collapseLogisticoServiceArmazemCentral" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Armazém A</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Armazém B</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level 3: Service (Oficina de Manutenção) Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <button
                                                class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button level-3 collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseLogisticoServiceOficinaManutencao"
                                                aria-expanded="false"
                                                aria-controls="collapseLogisticoServiceOficinaManutencao">
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper text-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                </rect>
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-700 text-decoration-none m-0">Oficina
                                                                de Manutenção</p>
                                                            <span class="text-secondary text-decoration-none">2
                                                                salas</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </button>
                                            <!-- Level 3 Body: Collapse Oficina de Manutenção (Rooms List) -->
                                            <div id="collapseLogisticoServiceOficinaManutencao" class="collapse w-100">
                                                <div class="d-flex flex-column gap-2 collapse-inner-level">
                                                    <!-- Room 1 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Bancada 1</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Room 2 -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <div class="table-icon-wrapper text-secondary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="lucide lucide-door-open-icon lucide-door-open">
                                                                    <path d="M11 20H2" />
                                                                    <path
                                                                        d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                    <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                    <path d="M14 12h.01" />
                                                                    <path d="M22 20h-3" />
                                                                </svg>
                                                            </div>
                                                            <span class="fw-600 text-primary">Bancada 2</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 text-muted align-items-center action-buttons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                <path
                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                </path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2-icon lucide-trash-2 padding-2 text-danger">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                </path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                </path>
                                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Edifício -->
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="equipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Criar
                        Edifício</h2>
                </div>

                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x stroke-secondary">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body do Modal com scroll automático -->
            <div class="modal-body p-0">
                <form id="building-creation-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Nome do Edifício -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="building-name">Nome <span class="text-danger">*</span></label>
                        <input type="text" id="building-name" name="building-name" placeholder="Nome do edifício..."
                            required="">
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing gap-2"
                            disabled="true">
                            Criar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once BASE_PATH . 'private/includes/footer.php';
?>