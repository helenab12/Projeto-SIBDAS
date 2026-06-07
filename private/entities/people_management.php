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
                <h1>Gestão de Pessoas</h1>
                <p class="text-secondary fw-400">8 pessoas ativas</p>
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
                    Nova Pessoa
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
                        placeholder="Pesquisar por nome, email ou nº funcionário...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Função" id="filter-role">
                    <option value="" selected>Todas as Funções</option>
                    <option value="Médico">Médico</option>
                    <option value="Eng. Biomédica">Eng. Biomédica</option>
                    <option value="Técnico">Técnico</option>
                    <option value="Direção">Direção</option>
                    <option value="Administrativa">Administrativa</option>
                    <option value="Enfermagem">Enfermagem</option>
                </select>
            </div>
        </div>

        <!-- Conteudo -->
        <div class="bento-grid people-management gap-4">
            <!-- Card 1: Dr. Manuel Costa -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white pink">
                            DM
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Dr. Manuel Costa</p>
                            <span class="text-secondary">Médico</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Medicina Interna</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">manuel.costa@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 912 345 678</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">MED-001</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 03/2018</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Eng.ª Ana Ferreira -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white cyan">
                            EA
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Eng. Ana Ferreira</p>
                            <span class="text-secondary">Engenheira Biomédica</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Serviço de Eng. Biomédica</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">ana.ferreira@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 913 456 789</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">ENG-001</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 07/2019</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Eng. Carlos Mendes -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white blue">
                            EC
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Eng. Carlos Mendes</p>
                            <span class="text-secondary">Técnico de Manutenção</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Serviço de Eng. Biomédica</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">carlos.mendes@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 914 567 890</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">TEC-001</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 01/2020</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Eng. Rui Santos -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white yellow">
                            ER
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Eng. Rui Santos</p>
                            <span class="text-secondary">Técnico de Manutenção</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Serviço de Eng. Biomédica</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">rui.santos@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 915 678 901</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">TEC-002</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 04/2021</span>
                    </div>
                </div>
            </div>

            <!-- Card 5: Dr.ª Helena Barbosa -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white green">
                            DH
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Dr.ª Helena Barbosa</p>
                            <span class="text-secondary">Diretora Clínica</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Administração</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">helena.barbosa@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 916 789 012</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">ADM-001</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 09/2015</span>
                    </div>
                </div>
            </div>

            <!-- Card 6: Sofia Oliveira -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white purple">
                            SO
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Sofia Oliveira</p>
                            <span class="text-secondary">Administrativa</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Gestão de Inventário</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">sofia.oliveira@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 917 890 123</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">ADM-002</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 06/2022</span>
                    </div>
                </div>
            </div>

            <!-- Card 7: Dr. João Silva -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white purple">
                            DJ
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Dr. João Silva</p>
                            <span class="text-secondary">Médico</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">UCI</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">joao.silva@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 918 901 234</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">MED-002</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 11/2020</span>
                    </div>
                </div>
            </div>

            <!-- Card 8: Enf.ª Marta Lopes -->
            <div class="bento-card padding-6 d-flex flex-column gap-6">
                <!-- Row 1: Nome -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <p class="user-icon d-flex align-items-center justify-content-center fw-700 text-white cyan">
                            EM
                        </p>
                        <div class="d-flex flex-column gap-half">
                            <p class="fw-700">Enf.ª Marta Lopes</p>
                            <span class="text-secondary">Enfermeira Chefe</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-primary"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                    Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item action-dropdown-item text-error" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                        <path d="M10 12h4" />
                                    </svg>
                                    Mover para Reciclagem
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Row 2: Contacto -->
                <div class="d-flex flex-column gap-3 text-secondary">
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-icon lucide-briefcase">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                        <span class="fw-400">Bloco Operatório</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail-icon lucide-mail">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                        <span class="fw-400">marta.lopes@hospital.pt</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-icon lucide-phone">
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span class="fw-400">+351 919 012 345</span>
                    </div>
                </div>

                <!-- Row 3: Detalhes Adicionais -->
                <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                    <span class="text-uppercase font-mono">ENF-001</span>
                    <div class="d-flex gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                        <span>Desde 02/2017</span>
                    </div>
                </div>
            </div>
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
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Nova Pessoa
                    </h2>
                    <span class="text-secondary fw-400">Informações do colaborador</span>
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
                <form id="person-creation-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Nome Completo e Nº Funcionário -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="person-name">Nome Completo *</label>
                            <input type="text" id="person-name" name="person-name" placeholder="Ex: Dr. Manuel Costa"
                                required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="person-id">Nº Funcionário *</label>
                            <input type="text" id="person-id" name="person-id" placeholder="Ex: MED-001" required>
                        </div>
                    </div>

                    <!-- Row 2: Função e Departamento -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="person-role">Função</label>
                            <input type="text" id="person-role" name="person-role"
                                placeholder="Ex: Técnico de Manutenção">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="person-department">Departamento</label>
                            <input type="text" id="person-department" name="person-department"
                                placeholder="Ex: Serviço de Eng. Biomédica">
                        </div>
                    </div>

                    <!-- Row 3: Email e Telefone -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="person-email">Email</label>
                            <input type="email" id="person-email" name="person-email" placeholder="email@hospital.pt">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="person-phone">Telefone</label>
                            <input type="text" id="person-phone" name="person-phone" placeholder="+351 9XX XXX XXX">
                        </div>
                    </div>

                    <!-- Row 4: Data de Início -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="person-start-date">Data de Início</label>
                        <div class="position-relative">
                            <input type="text" id="person-start-date" name="person-start-date" class="w-100"
                                placeholder="dd/mm/yyyy">
                        </div>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing" disabled>
                            Criar Pessoa
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