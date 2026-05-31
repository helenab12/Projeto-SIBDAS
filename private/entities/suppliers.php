<?php
require_once(__DIR__ . "/../../config/config.php");
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
                <h1>Fornecedores</h1>
                <p class="text-secondary fw-400">Gestão de fornecedores</p>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-open-create-equipment-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#equipment-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Criar Fornecedor
                </button>
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
                        placeholder="Pesquisar por nome, NIF ou email...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Tipo" id="filter-type">
                    <option value="" selected>Todos os Tipos</option>
                    <option value="Fabricante">Fabricante</option>
                    <option value="Distribuidor">Distribuidor</option>
                    <option value="Assistência Técnica">Assistência Técnica</option>
                    <option value="Consumíveis">Consumíveis</option>
                </select>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>FORNECEDOR</th>
                        <th>TIPO</th>
                        <th>CONTACTO</th>
                        <th>TELEFONE</th>
                        <th>WEBSITE</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Dräger Portugal, Lda.</p>
                                    <span class="equipment-subtitle text-secondary fw-400">info@drager.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td>Dr. Manuel Costa</td>
                        <td>
                            <a href="tel:+351214567890">+351 214 567 890</a>
                        </td>
                        <td>
                            <a href="https://www.drager.com/pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Philips Iberica, S.A.</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">info@philips.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td>Eng.ª Ana Ferreira</td>
                        <td>
                            <a href="tel:+351213456789">+351 213 456 789</a>
                        </td>
                        <td>
                            <a href="https://www.philips.pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">B. Braun Medical, Lda.</p>
                                    <span class="equipment-subtitle text-secondary fw-400">info@bbraun.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351212345678">+351 212 345 678</a>
                        </td>
                        <td>
                            <a href="https://www.bbraun.pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Stryker Portugal</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">info@stryker.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351211234567">+351 211 234 567</a>
                        </td>
                        <td>
                            <a href="https://www.stryker.com/pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 5 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">GE Healthcare Portugal</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">info@gehealthcare.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td>Eng. Carlos Mendes</td>
                        <td>
                            <a href="tel:+351210123456">+351 210 123 456</a>
                        </td>
                        <td>
                            <a href="https://www.gehealthcare.com" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 6 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Medtronic Portugal</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">info@medtronic.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351219876543">+351 219 876 543</a>
                        </td>
                        <td>
                            <a href="https://www.medtronic.com/pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 7 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Siemens Healthineers</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">info@siemens-healthineers.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-supplier">Fabricante</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351218765432">+351 218 765 432</a>
                        </td>
                        <td>
                            <a href="https://www.siemens-healthineers.com/pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 8 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">MedicalTech Distribuição, Lda.</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">geral@medtech.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-distribuitor">Distribuidor</span>
                        </td>
                        <td>Dr.ª Helena Barbosa</td>
                        <td>
                            <a href="tel:+351217654321">+351 217 654 321</a>
                        </td>
                        <td>
                            <a href="https://www.medtech.pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 9 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">EquipHospital, S.A.</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">info@equiphospital.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-distribuitor">Distribuidor</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351216543210">+351 216 543 210</a>
                        </td>
                        <td>
                            <a href="https://www.equiphospital.pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 10 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">BioServiços - Assistência Técnica</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">suporte@bioservicos.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-tech-assistance">Assistência
                                Técnica</span>
                        </td>
                        <td>Sofia Oliveira</td>
                        <td>
                            <a href="tel:+351215432109">+351 215 432 109</a>
                        </td>
                        <td>
                            <a href="https://www.bioservicos.pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 11 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">TecnoMed Assistência</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">assistencia@tecnomed.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-tech-assistance">Assistência
                                Técnica</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351214321098">+351 214 321 098</a>
                        </td>
                        <td class="text-muted">
                            &mdash;
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 12 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-icon-wrapper equipment-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path
                                            d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Consumíveis Hospitalares, Lda.</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400">encomendas@consumhosp.pt</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge supplier-badge-consumable-supplier">Fornecedor de
                                Consumíveis</span>
                        </td>
                        <td class="fst-italic text-muted">Sem contacto</td>
                        <td>
                            <a href="tel:+351213210987">+351 213 210 987</a>
                        </td>
                        <td>
                            <a href="https://www.consumhosp.pt" target="_blank"
                                class="d-flex gap-1 align-items-center text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Website</span>
                            </a>
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
                                        <a class="dropdown-item action-dropdown-item text-primary" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Mover para Reciclagem
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
<!-- Modal de Criação de Fornecedor -->
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="equipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Novo
                        Fornecedor</h2>
                    <span class="text-secondary fw-400">Informações do fornecedor</span>
                </div>

                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x stroke-secondary">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body do Modal com scroll automático -->
            <div class="modal-body p-0">
                <form id="supplier-creation-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Nome da Empresa e NIF -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-name">Nome da Empresa</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-name" name="supplier-name"
                                placeholder="Ex: Dräger Portugal, Lda." required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-nif">NIF</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-nif" name="supplier-nif" placeholder="501234567"
                                required>
                        </div>
                    </div>

                    <!-- Row 2: Tipo -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-type">Tipo</label>
                        <select id="supplier-type" name="supplier-type" class="form-select w-100">
                            <option value="Fabricante" selected>Fabricante</option>
                            <option value="Distribuidor">Distribuidor</option>
                            <option value="Assistência Técnica">Assistência Técnica</option>
                            <option value="Consumíveis">Consumíveis</option>
                        </select>
                    </div>

                    <!-- Row 3: Email e Telefone -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="supplier-email">Email</label>
                            <input type="email" id="supplier-email" name="supplier-email"
                                placeholder="email@empresa.com">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="supplier-phone">Telefone de Contacto (Empresa)</label>
                            <input type="text" id="supplier-phone" name="supplier-phone"
                                placeholder="+351 21X XXX XXX">
                        </div>
                    </div>

                    <!-- Row 4: Website -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-website">Website</label>
                        <input type="url" id="supplier-website" name="supplier-website"
                            placeholder="https://www.empresa.pt">
                    </div>

                    <!-- Row 5: Pessoa Responsável e Criar Novo -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-contact-person">Pessoa Responsável</label>
                        <div class="d-flex w-100 gap-2 align-items-stretch">
                            <select id="supplier-contact-person" name="supplier-contact-person"
                                class="form-select mw-0">
                                <option value="" disabled selected>Selecionar pessoa...</option>
                                <option value="Ana Silva">Ana Silva (Diretora Comercial)</option>
                                <option value="Carlos Santos">Carlos Santos (Gestor de Contas)</option>
                                <option value="Mariana Costa">Mariana Costa (Suporte Técnico)</option>
                            </select>
                            <button type="button"
                                class="btn btn-primary-outline w-auto text-nowrap gap-2 d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Criar Novo
                            </button>
                        </div>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing"
                            disabled>
                            Criar Fornecedor
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