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
                <h1>Categorias</h1>
                <p class="text-secondary fw-400">Gestão de categorias</p>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-open-create-category-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#category-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Nova Categoria
                </button>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4">
            <form action="" class="flex-grow-1">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" placeholder="Pesquisar categorias...">
                </div>
            </form>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>CATEGORIA</th>
                        <th>CÓDIGO</th>
                        <th>DESCRIÇÃO</th>
                        <th>EQUIPAMENTOS</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 (Ventiladores) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Ventiladores</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">VENT</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Equipamentos de ventilação mecânica para
                                suporte respiratório.</span>
                        </td>
                        <td class="fw-700">42</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 (Monitores de Sinais Vitais) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Monitores de Sinais Vitais</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">MONI</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Monitores multiparamétricos de sinais vitais
                                (ECG, SpO2, PNI, etc.).</span>
                        </td>
                        <td class="fw-700">67</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 (Bombas de Infusão) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Bombas de Infusão</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">BOMB</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Bombas volumétricas e seringas para
                                administração controlada de fármacos.</span>
                        </td>
                        <td class="fw-700">89</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 (Desfibrilhadores) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Desfibrilhadores</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">DESF</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Dispositivos de desfibrilhação para paragem
                                cardíaca e arritmias.</span>
                        </td>
                        <td class="fw-700">28</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 (Equipamento de Imagiologia) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Equipamento de Imagiologia</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">EQUI</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">TC, RM, Rx, Ecografia e outros equipamentos de
                                diagnóstico por imagem.</span>
                        </td>
                        <td class="fw-700">15</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 6 (Equipamento Cirúrgico) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Equipamento Cirúrgico</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">EQUI</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Mesas cirúrgicas, focos, bisturis elétricos e
                                equipamento de suporte.</span>
                        </td>
                        <td class="fw-700">53</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 7 (Equipamento Laboratorial) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Equipamento Laboratorial</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">EQUI</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Analisadores, centrifugadoras e equipamento de
                                diagnóstico in vitro.</span>
                        </td>
                        <td class="fw-700">38</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 8 (Esterilizadores) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-folder-tree-icon lucide-folder-tree">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Esterilizadores</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">ESTE</span>
                        </td>
                        <td>
                            <span class="text-sm text-secondary">Autoclaves e equipamento de esterilização a
                                vapor e químico.</span>
                        </td>
                        <td class="fw-700">22</td>
                        <td class="text-end equipment-actions">
                            <div class="dropdown">
                                <button
                                    class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar Definitivamente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Categoria -->
<div class="modal fade" id="category-creation-modal" tabindex="-1" aria-labelledby="categoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="categoryModalLabel">Nova
                        Categoria</h2>
                    <span class="text-secondary fw-400">As categorias organizam os equipamentos por tipo.</span>
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
                <div class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                    <!-- Row 1: Nome da Categoria -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="category-name">Nome da Categoria *</label>
                        <input type="text" id="category-name" name="category-name" placeholder="Ex: Ventiladores"
                            required>
                    </div>

                    <!-- Row 2: Código -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="category-code">Código *</label>
                        <input type="text" id="category-code" name="category-code" placeholder="Ex: VENT" required>
                    </div>

                    <!-- Row 3: Descrição -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="category-description">Descrição</label>
                        <textarea id="category-description" name="category-description" rows="4"
                            placeholder="Descrição breve da categoria..."></textarea>
                    </div>

                    <!-- Button Row / Footer -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4 pt-4 border-top">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                            Criar Categoria
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