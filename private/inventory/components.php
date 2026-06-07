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
                <h1>Componentes</h1>
                <p class="text-secondary fw-400">Gestão de componentes em stock</p>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-open-create-equipment-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#equipment-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Criar Componente
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
                    <input type="text" class="form-item w-100 search-bar-input" placeholder="Pesquisar componentes...">
                </div>
            </form>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>COMPONENTE</th>
                        <th>SKU</th>
                        <th>CATEGORIA</th>
                        <th>STOCK</th>
                        <th>PREÇO UNIT.</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 (Sensor de Fluxo) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Sensor de Fluxo</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-001</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Componentes associados a ventiladores pulmonares e suporte respiratório.">Ventiladores</span>
                        </td>
                        <td class="fw-700">24</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 2 (Válvula de Expiração) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Válvula de Expiração</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-002</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Componentes associados a ventiladores pulmonares e suporte respiratório.">Ventiladores</span>
                        </td>
                        <td class="fw-700">15</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 3 (Filtro HEPA) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Filtro HEPA</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-003</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Componentes associados a ventiladores pulmonares e suporte respiratório.">Ventiladores</span>
                        </td>
                        <td class="fw-700">48</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 4 (Cabo de ECG (5 derivações)) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Cabo de ECG (5 derivações)</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-004</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Sensores, cabos e acessórios para monitorização de parâmetros fisiológicos.">Monitores
                                de Sinais Vitais</span>
                        </td>
                        <td class="fw-700">32</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 5 (Sensor SpO2 reutilizável) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Sensor SpO2 reutilizável</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-005</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Sensores, cabos e acessórios para monitorização de parâmetros fisiológicos.">Monitores
                                de Sinais Vitais</span>
                        </td>
                        <td class="fw-700">18</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 6 (Braçadeira PNI (adulto)) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Braçadeira PNI (adulto)</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-006</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Sensores, cabos e acessórios para monitorização de parâmetros fisiológicos.">Monitores
                                de Sinais Vitais</span>
                        </td>
                        <td class="fw-700">40</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 7 (Cassete de Infusão) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Cassete de Infusão</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-007</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Acessórios, seringas e consumíveis para bombas infusoras e perfusoras.">Bombas
                                de Infusão</span>
                        </td>
                        <td class="fw-700">120</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 8 (Seringa de 50ml (BD)) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Seringa de 50ml (BD)</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-008</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Acessórios, seringas e consumíveis para bombas infusoras e perfusoras.">Bombas
                                de Infusão</span>
                        </td>
                        <td class="fw-700">200</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 9 (Bateria Li-Ion) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Bateria Li-Ion</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-009</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Acessórios, seringas e consumíveis para bombas infusoras e perfusoras.">Bombas
                                de Infusão</span>
                        </td>
                        <td class="fw-700">6</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 10 (Pás de Desfibrilhação (adulto)) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Pás de Desfibrilhação (adulto)</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-010</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Elétrodos, pás e baterias para equipamentos de desfibrilhação e cardioversão.">Desfibrilhadores</span>
                        </td>
                        <td class="fw-700">30</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 11 (Elétrodos de Desfibrilhação) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Elétrodos de Desfibrilhação</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-011</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Elétrodos, pás e baterias para equipamentos de desfibrilhação e cardioversão.">Desfibrilhadores</span>
                        </td>
                        <td class="fw-700">50</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 12 (Tubo de Rx) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Tubo de Rx</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-012</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Componentes e tubos para sistemas de diagnóstico por imagem (Raio-X, Eco, etc.).">Equipamento
                                de Imagiologia</span>
                        </td>
                        <td class="fw-700">3</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 13 (Lâmpada de foco cirúrgico) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Lâmpada de foco cirúrgico</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-013</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Acessórios e lâmpadas para blocos operatórios e procedimentos cirúrgicos.">Equipamento
                                Cirúrgico</span>
                        </td>
                        <td class="fw-700">12</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 14 (Reagentes bioquímicos) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Reagentes bioquímicos</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-014</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Consumíveis e reagentes para análise laboratorial e de diagnóstico.">Equipamento
                                Laboratorial</span>
                        </td>
                        <td class="fw-700">8</td>
                        <td class="fw-700">€0.00</td>
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
                    <!-- Row 15 (Indicador biológico) -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper component-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-puzzle-icon lucide-puzzle">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>
                                </div>
                                <p class="equipment-title fw-700 mb-0">Indicador biológico</p>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge component-sku-badge font-mono">CMP-015</span>
                        </td>
                        <td>
                            <span class="category-tooltip-trigger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Indicadores biológicos, químicos e consumíveis para autoclave e esterilização.">Esterilizadores</span>
                        </td>
                        <td class="fw-700">60</td>
                        <td class="fw-700">€0.00</td>
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

<!-- Modal de Criação de Componente -->
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="componentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="componentModalLabel">Novo
                        Componente</h2>
                    <span class="text-secondary fw-400">Componentes e peças associadas a equipamentos.</span>
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
                    <!-- Row 1: Nome e SKU -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="component-name">Nome *</label>
                            </div>
                            <input type="text" id="component-name" name="component-name"
                                placeholder="Ex: Sensor SpO2 Neonatal" required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="component-sku">SKU *</label>
                            </div>
                            <input type="text" id="component-sku" name="component-sku" placeholder="Ex: CMP-SP02-N01"
                                required>
                        </div>
                    </div>

                    <!-- Row 2: Categoria -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="component-category">Categoria</label>
                        <select id="component-category" name="component-category" class="form-select">
                            <option value="" disabled selected>Selecionar categoria...</option>
                            <option value="ventilador">Ventiladores</option>
                            <option value="monitor">Monitores de Sinais Vitais</option>
                            <option value="bomba">Bombas de Infusão</option>
                            <option value="desfribilhador">Desfibrilhadores</option>
                            <option value="imagiologia">Equipamento de Imagiologia</option>
                            <option value="cirurgico">Equipamento Cirúrgico</option>
                            <option value="laboratorial">Equipamento Laboratorial</option>
                            <option value="esterilizadores">Esterilizadores</option>
                        </select>
                    </div>

                    <!-- Row 3: Stock Atual, Stock Mínimo e Preço Unitário -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="component-stock-actual">Stock Atual</label>
                            <input type="number" id="component-stock-actual" name="component-stock-actual" value="0"
                                min="0" placeholder="0">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="component-stock-min">Stock Mínimo</label>
                            <input type="number" id="component-stock-min" name="component-stock-min" value="0" min="0"
                                placeholder="0">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="component-price">Preço Unitário (€)</label>
                            <input type="text" id="component-price" name="component-price" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Button Row / Footer -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4 pt-4 border-top">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                            Criar Componente
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