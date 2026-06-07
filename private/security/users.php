<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 security-users">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Utilizadores</h1>
                <p class="text-secondary fw-400">8 utilizadores ativos</p>
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
                    Novo Utilizador
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
                        placeholder="Pesquisar por nome, username ou email...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Perfil" id="filter-type">
                    <option value="" selected>Todos os Perfis</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Gestor de Inventário">Gestor de Inventário</option>
                    <option value="Técnico Senior">Técnico Senior</option>
                    <option value="Técnico">Técnico</option>
                    <option value="Consulta">Consulta</option>
                </select>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>UTILIZADOR</th>
                        <th>PERFIL</th>
                        <th>ESTADO</th>
                        <th>ÚLTIMO LOGIN</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    DH
                                    <div class="online-status active position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Dr.ª Helena Barbosa</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400 font-mono">@helena.barbosa</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge admin gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-shield-alert-icon lucide-shield-alert">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="M12 8v4" />
                                    <path d="M12 16h.01" />
                                </svg>
                                Administrador
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge online justify-content-center">&bull; &nbsp; Online</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>07/04, 13:45</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    DM
                                    <div class="online-status active position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Dr. Manuel Costa</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400 font-mono">@manuel.costa</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge inv-manager gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                                Gestor de Inventário
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge online justify-content-center">&bull; &nbsp; Online</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>07/04, 12:30</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    EA
                                    <div class="online-status inactive position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Eng.ª Ana Ferreira</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400 font-mono">@ana.ferreira</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge senior-tech gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                </svg>
                                Técnico Sénior
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge offline justify-content-center">&bull; &nbsp; Offline</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>06/04, 16:45</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    EC
                                    <div class="online-status active position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Eng. Carlos Mendes</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400 font-mono">@carlos.mendes</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge tech gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-key-icon lucide-key">
                                    <path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4" />
                                    <path d="m21 2-9.6 9.6" />
                                    <circle cx="7.5" cy="15.5" r="5.5" />
                                </svg>
                                Técnico
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge online justify-content-center">&bull; &nbsp; Online</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>07/04, 10:00</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    ER
                                    <div class="online-status inactive position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Eng. Rui Santos</p>
                                    <span class="equipment-subtitle text-secondary fw-400 font-mono">@rui.santos</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge tech gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-key-icon lucide-key">
                                    <path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4" />
                                    <path d="m21 2-9.6 9.6" />
                                    <circle cx="7.5" cy="15.5" r="5.5" />
                                </svg>
                                Técnico
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge offline justify-content-center">&bull; &nbsp; Offline</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>05/04, 17:00</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    SO
                                    <div class="online-status active position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Sofia Oliveira</p>
                                    <span
                                        class="equipment-subtitle text-secondary fw-400 font-mono">@sofia.oliveira</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge consultant gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                Consulta
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge online justify-content-center">&bull; &nbsp; Online</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>07/04, 09:30</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    DJ
                                    <div class="online-status inactive position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Dr. João Silva</p>
                                    <span class="equipment-subtitle text-secondary fw-400 font-mono">@joao.silva</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge inv-manager gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                                Gestor de Inventário
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge offline justify-content-center">&bull; &nbsp; Offline</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>06/04, 14:20</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
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
                                <div
                                    class="user-icon d-flex justify-content-center align-items-center text-secondary fw-700 position-relative">
                                    EM
                                    <div class="online-status inactive position-absolute"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Enf.ª Marta Lopes</p>
                                    <span class="equipment-subtitle text-secondary fw-400 font-mono">@marta.lopes</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="equipment-badge consultant gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                Consulta
                            </span>
                        </td>
                        <td>
                            <span class="equipment-badge offline justify-content-center">&bull; &nbsp; Offline</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 text-muted align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>04/04, 15:30</span>
                            </div>
                        </td>
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
                                                class="lucide lucide-archive">
                                                <rect width="20" height="5" x="2" y="3" rx="1" />
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                <path d="M10 12h4" />
                                            </svg>
                                            Desativar (Reciclagem)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                </tbody>
            </table>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Utilizador -->
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="equipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Novo Utilizador</h2>
                    <span class="text-secondary fw-400">Credenciais e permissões de acesso</span>
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

            <!-- Body do Modal com scroll automático -->
            <div class="modal-body p-0">
                <form id="user-creation-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Nome Completo e Username -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="user-fullname">Nome Completo</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="user-fullname" name="user-fullname"
                                placeholder="Ex: Dr. Manuel Costa" required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="user-username">Username</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="user-username" name="user-username" placeholder="Ex: manuel.costa"
                                required>
                        </div>
                    </div>

                    <!-- Row 2: Email -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <div class="d-flex gap-1">
                            <label for="user-email">Email</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="email" id="user-email" name="user-email" placeholder="email@hospital.pt" required>
                    </div>

                    <!-- Row 3: Perfil de Acesso -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="user-role">Perfil de Acesso</label>
                        <select id="user-role" name="user-role" class="form-select w-100">
                            <option value="Administrador">Administrador</option>
                            <option value="Gestor de Inventário">Gestor de Inventário</option>
                            <option value="Técnico Sénior">Técnico Sénior</option>
                            <option value="Técnico">Técnico</option>
                            <option value="Consulta" selected>Consulta</option>
                        </select>
                    </div>

                    <!-- Row 4: Password -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <div class="d-flex gap-1">
                            <label for="user-password">Password</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="password" id="user-password" name="user-password" placeholder="••••••••" required>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing" disabled>
                            Criar Utilizador
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