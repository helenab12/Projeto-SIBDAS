<?php
require_once(__DIR__ . "/../../../config/config.php");
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
                <h1>Lista de Equipamentos</h1>
                <p class="text-secondary fw-400">9 equipamentos cadastrados</p>
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
                    Criar Equipamento
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
                        placeholder="Pesquisar por nome, nº série, marca, modelo...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Estado">
                    <option selected>Estado</option>
                    <option value="1">Ativo</option>
                    <option value="2">Em Manutenção</option>
                    <option value="3">Inativo</option>
                    <option value="4">Abatido</option>
                </select>
                <select class="form-select" aria-label="Filtro Criticidade">
                    <option selected>Criticidade</option>
                    <option value="1">Baixa</option>
                    <option value="2">Média</option>
                    <option value="3">Alta</option>
                    <option value="4">Crítica</option>
                </select>
                <select class="form-select" aria-label="Filtro Categoria">
                    <option selected>Categoria</option>
                    <option value="1">Ventiladores</option>
                    <option value="2">Monitores de Sinais Vitais</option>
                    <option value="3">Bombas de Infusão</option>
                    <option value="4">Desfibrilhadores</option>
                    <option value="5">Equipamento de Imagiologia</option>
                    <option value="6">Equipamento Cirúrgico</option>
                    <option value="7">Equipamento Laboratorial</option>
                    <option value="8">Esterilizadores</option>
                </select>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>EQUIPAMENTO</th>
                        <th>CATEGORIA</th>
                        <th>LOCALIZAÇÃO</th>
                        <th>ESTADO</th>
                        <th>CRITICIDADE</th>
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Ventilador Mecânico V500</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Dräger Evita V500
                                        &bull; DRG-V500-78234</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamentos de ventilação mecânica para suporte respiratório.">Ventiladores</span>
                        </td>
                        <td class="equipment-location">UCI - Piso 3</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-critical equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento vital — falha pode resultar em risco de vida para o paciente.">Crítico</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Monitor Multiparamétrico
                                        IntelliVue</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Philips IntelliVue
                                        MX800 &bull; PHL-MX800-45621</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Monitores multiparamétricos de sinais vitais (ECG, SpO2, PNI, etc.).">Monitores
                                de Sinais Vitais</span></td>
                        <td class="equipment-location">Bloco Operatório 2</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-critical equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento vital — falha pode resultar em risco de vida para o paciente.">Crítico</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Bomba de Infusão Volumétrica</p>
                                    <span class="equipment-subtitle text-secondary fw-400">B. Braun Infusomat
                                        Space &bull; BBR-ISP-89123</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Bombas volumétricas e seringas para administração controlada de fármacos.">Bombas
                                de Infusão</span></td>
                        <td class="equipment-location">UCI - Box 2</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-maintenance equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento temporariamente indisponível por estar em manutenção preventiva ou corretiva.">Em
                                Manutenção</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-high equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento importante — falha impacta significativamente o serviço clínico.">Alto</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Tomógrafo Computorizado</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Siemens SOMATOM
                                        go.Top &bull; SMN-SGT-12890</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="TC, RM, Rx, Ecografia e outros equipamentos de diagnóstico por imagem.">Equipamento
                                de Imagiologia</span></td>
                        <td class="equipment-location">Imagiologia - Sala TC</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-critical equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento vital — falha pode resultar em risco de vida para o paciente.">Crítico</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Mesa Cirúrgica Elétrica</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Stryker Tombo &bull;
                                        STR-TRN-66789</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Mesas cirúrgicas, focos, bisturis elétricos e equipamento de suporte.">Equipamento
                                Cirúrgico</span></td>
                        <td class="equipment-location">BO 1</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-high equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento importante — falha impacta significativamente o serviço clínico.">Alto</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Analisador Bioquímico</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Beckman Coulter
                                        AU5800 &bull; BCK-AU58-43210</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Analisadores, centrifugadoras e equipamento de diagnóstico in vitro.">Equipamento
                                Laboratorial</span></td>
                        <td class="equipment-location">Lab. Bioquímica</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-high equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento importante — falha impacta significativamente o serviço clínico.">Alto</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Autoclave a Vapor</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Getinge GSS 6713
                                        &bull; GTG-6713-98765</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Autoclaves e equipamento de esterilização a vapor e químico.">Esterilizadores</span>
                        </td>
                        <td class="equipment-location">Esterilização</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-high equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento importante — falha impacta significativamente o serviço clínico.">Alto</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Máquina de Anestesia Perseus
                                        A500</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Dräger Perseus A500
                                        &bull; DRG-PA500-11223</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Máquinas e estações de anestesia para controlo operatório.">Equipamento
                                de Anestesia</span></td>
                        <td class="equipment-location">BO 2</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-maintenance equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento temporariamente indisponível por estar em manutenção preventiva ou corretiva.">Em
                                Manutenção</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-high equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento importante — falha impacta significativamente o serviço clínico.">Alto</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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
                                        class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Ecógrafo EPIQ Elite</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Philips EPIQ Elite
                                        &bull; PHL-EPIQ-44556</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="TC, RM, Rx, Ecografia e outros equipamentos de diagnóstico por imagem.">Equipamento
                                de Imagiologia</span></td>
                        <td class="equipment-location">Imagiologia - Sala Eco</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-active equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento operacional e disponível para uso clínico.">Ativo</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-critical equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento vital — falha pode resultar em risco de vida para o paciente.">Crítico</span>
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
                                            Arquivar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-dropdown-item text-error" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash-2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            Apagar
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

<!-- Modal de Criação de Equipamento -->
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="equipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Novo
                        Equipamento</h2>
                    <span class="text-secondary fw-400">Preencha os dados para registar um novo
                        equipamento.</span>
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
                <!-- Conteudo Pagina 1 -->
                <div id="modal-page-1"
                    class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column">
                    <div class="d-flex flex-row gap-3 align-items-center">
                        <div
                            class="d-flex flex-row equipment-creation-modal-page current-page justify-content-start align-items-center gap-3 padding-3">
                            <h3 class="text-white padding-2 d-flex align-items-center justify-content-center">1
                            </h3>
                            <p class="text-primary-700">Dados gerais</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right stroke-secondary">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <div
                            class="d-flex flex-row equipment-creation-modal-page justify-content-start align-items-center gap-3 padding-3">
                            <h3
                                class="text-secondary padding-2 d-flex align-items-center justify-content-center">
                                2</h3>
                            <p class="text-secondary">Relações & Docs</p>
                        </div>
                    </div>

                    <!-- Row 1: Numero de Serie e Categoria -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="serial-number">Número de Série</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="serial-number" name="serial-number"
                                placeholder="Ex: DRG-V500-7239" required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="category">Categoria</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <div class="d-flex w-100 gap-2">
                                <select id="category" name="category" class="form-select mw-0" required>
                                    <option value="" disabled selected>Selecionar categoria</option>
                                    <option value="ventilador">Ventiladores</option>
                                    <option value="monitor">Monitores de Sinais Vitais</option>
                                    <option value="bomba">Bombas de Infusão</option>
                                    <option value="desfribilhador">Desfribilhadores</option>
                                    <option value="imagiologia">Equipamento de Imagiologia</option>
                                    <option value="cirurgico">Equipamento Cirúrgico</option>
                                    <option value="laboratorial">Equipamento Laboratorial</option>
                                    <option value="esterilizadores">Esterilizadores</option>
                                </select>

                                <button class="btn btn-primary-outline btn-small w-auto text-nowrap gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-external-link-icon lucide-external-link">
                                        <path d="M15 3h6v6" />
                                        <path d="M10 14 21 3" />
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                    </svg>
                                    Criar novo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Designação / Nome do Equipamento -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <div class="d-flex gap-1">
                            <label for="equipment-name">Designação / Nome do Equipamento</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="text" id="equipment-name" name="equipment-name"
                            placeholder="Ex: Ventilador Dräger V500" required>
                    </div>

                    <!-- Row 3: Marca e Modelo -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="brand">Marca</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="brand" name="brand" placeholder="Ex: Dräger" required>
                        </div>

                        <div class="d-flex flex-column form-item w-100">
                            <label for="model">Modelo</label>
                            <input type="text" id="model" name="model" placeholder="Ex: Evita V500" required>
                        </div>
                    </div>

                    <!-- Row 4: Datas e Entradas -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="purchase-date">Data de Compra</label>
                            <input type="date" id="purchase-date" name="purchase-date" placeholder="dd/mm/yyyy">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="manufacture-date">Data de Fabrico</label>
                            <input type="date" id="manufacture-date" name="manufacture-date"
                                placeholder="dd/mm/yyyy">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="entry-type">Tipo de Entrada</label>
                            <select id="entry-type" name="entry-type" class="form-select">
                                <option value="compra" selected>Compra</option>
                                <option value="doacao">Doação</option>
                                <option value="leasing">Leasing</option>
                                <option value="transferencia">Transferência</option>
                                <option value="emprestimo">Empréstimo</option>
                            </select>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="criticality">Criticidade</label>
                            <select id="criticality" name="criticality" class="form-select">
                                <option value="critico">Crítico</option>
                                <option value="alto">Alto</option>
                                <option value="medio" selected>Médio</option>
                                <option value="baixo">Baixo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 5: Localização -->
                    <div class="d-flex flex-column gap-3 w-100">
                        <h3 class="text-primary">Localização</h3>
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="building">Edifício</label>
                                <select id="building" name="building" class="form-select">
                                    <option value="" disabled selected>Selecionar...</option>
                                    <option value="principal">Edifício Principal</option>
                                    <option value="ambulatorio">Edifício Ambulatório</option>
                                    <option value="logistico">Edifício Logístico</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="floor">Piso</label>
                                <select id="floor" name="floor" class="form-select" disabled>
                                    <option value="" disabled selected>Selecionar...</option>
                                    <option value="0">Piso 0</option>
                                    <option value="1">Piso 1</option>
                                    <option value="2">Piso 2</option>
                                    <option value="3">Piso 3</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="service">Serviço</label>
                                <select id="service" name="service" class="form-select" disabled>
                                    <option value="" disabled selected>Selecionar...</option>
                                    <option value="bloco-operatorio">Bloco Operatório</option>
                                    <option value="esterilizacao">Esterilização</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="room">Sala</label>
                                <select id="room" name="room" class="form-select" disabled>
                                    <option value="" disabled selected>Selecionar...</option>
                                    <option value="bo1">BO1</option>
                                    <option value="bo2">BO2</option>
                                    <option value="recobro">Sala de Recobro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Button Row -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-auto">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-next-page" class="btn btn-primary btn-glowing gap-1"
                            disabled>
                            Próximo
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-right">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Conteudo Pagina 2 -->
                <div id="modal-page-2"
                    class="equipment-creation-modal-content padding-6 gap-6 d-flex flex-column d-none">
                    <div class="d-flex flex-row gap-3 align-items-center">
                        <div
                            class="d-flex flex-row equipment-creation-modal-page justify-content-start align-items-center gap-3 padding-3 page-completed">
                            <h3
                                class="text-secondary padding-2 d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-check stroke-white">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </h3>
                            <p>Dados gerais</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right stroke-secondary">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <div
                            class="d-flex flex-row equipment-creation-modal-page current-page justify-content-start align-items-center gap-3 padding-3">
                            <h3 class="text-white padding-2 d-flex align-items-center justify-content-center">2
                            </h3>
                            <p class="text-primary-700">Relações & Docs</p>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-grow-1 w-100 pt-4 gap-6">
                        <!-- Seccao 1: Fornecedores -->
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-building2-icon lucide-building-2 stroke-primary-500">
                                    <path d="M10 12h4" />
                                    <path d="M10 8h4" />
                                    <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                    <path
                                        d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                    <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                </svg>
                                <h3>Fornecedores</h3>
                            </div>

                            <div class="d-flex flex-column gap-4 w-100">
                                <!-- Fabricante -->
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="manufacturer">Fabricante (1)</label>
                                    </div>
                                    <div class="d-flex w-100 gap-2">
                                        <select id="manufacturer" name="manufacturer" class="form-select mw-0">
                                            <option value="" disabled selected>Selecionar fabricante</option>
                                            <option value="drager">Dräger Portugal Lda</option>
                                            <option value="philips">Philips Ibérica S.A.</option>
                                            <option value="bbraun">B. Braun Medical Lda</option>
                                            <option value="stryker">Stryker Portugal</option>
                                            <option value="ge">GE Healthcare Portugal</option>
                                            <option value="medtronic">Medtronic Portugal</option>
                                            <option value="siemens">Siemens Healthineers</option>
                                        </select>

                                        <button
                                            class="btn btn-primary-outline btn-small w-auto text-nowrap gap-2"
                                            title="Criar novo fabricante">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-external-link-icon lucide-external-link">
                                                <path d="M15 3h6v6" />
                                                <path d="M10 14 21 3" />
                                                <path
                                                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                            </svg>
                                            Criar Novo
                                        </button>
                                    </div>
                                </div>

                                <!-- Distribuidor -->
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="distributor">Distribuidor (1)</label>
                                    </div>
                                    <div class="d-flex w-100 gap-2">
                                        <select id="distributor" name="distributor" class="form-select mw-0">
                                            <option value="" disabled selected>Selecionar distribuidor</option>
                                            <option value="medicaltech">Medical Tech Distribuição Lda</option>
                                            <option value="equip">EquipHospital SA</option>
                                        </select>

                                        <button
                                            class="btn btn-primary-outline btn-small w-auto text-nowrap gap-2"
                                            title="Criar novo distribuidor">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-external-link-icon lucide-external-link">
                                                <path d="M15 3h6v6" />
                                                <path d="M10 14 21 3" />
                                                <path
                                                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                            </svg>
                                            Criar Novo
                                        </button>
                                    </div>
                                </div>

                                <!-- Assistentes Técnicos -->
                                <div class="d-flex flex-column form-item gap-2 w-100">
                                    <div class="d-flex gap-1">
                                        <label for="distributor">Assistentes Técnicos (Múltiplos)</label>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="text-muted">0 selecionado(s)</span>
                                        <a href="#"
                                            class="text-primary d-flex align-items-center gap-1 text-decoration-none fw-bold">
                                            <p class="text-primary-500 d-flex align-items-center gap-1 fw-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-external-link">
                                                    <path d="M15 3h6v6"></path>
                                                    <path d="M10 14 21 3"></path>
                                                    <path
                                                        d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                    </path>
                                                </svg>
                                                Criar Novo
                                            </p>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-column gap-3 padding-3 multi-select-form">
                                        <div class="form-check d-flex align-items-center gap-2 m-0">
                                            <input class="form-check-input m-0" type="checkbox"
                                                value="bioservicos" id="check-bioservicos">
                                            <label
                                                class="form-check-label text-secondary m-0 multi-select-label"
                                                for="check-bioservicos">
                                                BioServiços - Assistência Técnica
                                            </label>
                                        </div>
                                        <div class="form-check d-flex align-items-center gap-2 m-0">
                                            <input class="form-check-input m-0" type="checkbox" value="tecnomed"
                                                id="check-tecnomed">
                                            <label
                                                class="form-check-label text-secondary m-0 multi-select-label"
                                                for="check-tecnomed">
                                                TecnoMed Assistência
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fornecedores de Consumíveis -->
                                <div class="d-flex flex-column form-item gap-2 w-100">
                                    <div class="d-flex gap-1">
                                        <label for="consumables">Fornecedores de Consumíveis (múltiplos)</label>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="text-muted">0 selecionado(s)</span>
                                        <a href="#"
                                            class="text-primary d-flex align-items-center gap-1 text-decoration-none fw-bold">
                                            <p class="text-primary-500 d-flex align-items-center gap-1 fw-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-external-link">
                                                    <path d="M15 3h6v6"></path>
                                                    <path d="M10 14 21 3"></path>
                                                    <path
                                                        d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                    </path>
                                                </svg>
                                                Criar Novo
                                            </p>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-column gap-3 padding-3 multi-select-form">
                                        <div class="form-check d-flex align-items-center gap-2 m-0">
                                            <input class="form-check-input m-0" type="checkbox"
                                                value="consumiveis_hospitalares"
                                                id="check-consumiveis_hospitalares">
                                            <label
                                                class="form-check-label text-secondary m-0 multi-select-label"
                                                for="check-consumiveis_hospitalares">
                                                Consumíveis Hospitalares, Lda.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Seccao 2: Componentes -->
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-2 components-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-puzzle-icon lucide-puzzle stroke-primary-500">
                                    <path
                                        d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                </svg>
                                <h3>Componentes</h3>
                                <span class="text-muted">(filtrados pela categoria selecionada)</span>
                            </div>

                            <div class="d-flex flex-column gap-4 w-100">
                                <div class="d-flex flex-column form-item gap-2 w-100">
                                    <div class="d-flex flex-column gap-3 padding-4 multi-select-form">
                                        <!-- Item 1 -->
                                        <div class="d-flex align-items-start gap-3 w-100 multi-select-item">
                                            <div class="form-check m-0 pt-1">
                                                <input class="form-check-input m-0" type="checkbox"
                                                    value="cassete" id="check-cassete" checked>
                                            </div>
                                            <div class="d-flex flex-column gap-2 flex-grow-1">
                                                <div
                                                    class="d-flex justify-content-between align-items-center w-100 multi-select-details-row">
                                                    <div
                                                        class="d-flex align-items-center gap-3 multi-select-info">
                                                        <label for="check-cassete"
                                                            class="fw-400 m-0 cursor-pointer">Cassete de
                                                            Infusão</label>
                                                        <span class="text-muted multi-select-stock-badge">Em
                                                            Stock: 120
                                                            un.</span>
                                                    </div>
                                                    <!-- Quantidade -->
                                                    <div
                                                        class="d-flex align-items-center gap-2 multi-select-qty-container">
                                                        <span
                                                            class="text-secondary multi-select-qty-label">Qtd:</span>
                                                        <input type="number"
                                                            class="form-control text-center p-0 multi-select-qty-input"
                                                            value="1" min="1" max="120">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Item 2 -->
                                        <div class="d-flex align-items-start gap-3 w-100 multi-select-item">
                                            <div class="form-check m-0 pt-1">
                                                <input class="form-check-input m-0" type="checkbox"
                                                    value="seringa" id="check-seringa">
                                            </div>
                                            <div class="d-flex flex-column gap-2 flex-grow-1">
                                                <div
                                                    class="d-flex justify-content-between align-items-center w-100 multi-select-details-row">
                                                    <div
                                                        class="d-flex align-items-center gap-3 multi-select-info">
                                                        <label for="check-seringa"
                                                            class="fw-400 m-0 cursor-pointer">Seringa de 50ml
                                                            (BD)</label>
                                                        <span class="text-muted multi-select-stock-badge">Em
                                                            Stock: 200
                                                            un.</span>
                                                    </div>
                                                    <!-- Quantidade -->
                                                    <div
                                                        class="d-flex align-items-center gap-2 multi-select-qty-container d-none">
                                                        <span
                                                            class="text-secondary multi-select-qty-label">Qtd:</span>
                                                        <input type="number"
                                                            class="form-control text-center p-0 multi-select-qty-input"
                                                            value="1" min="1" max="200">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Item 3 -->
                                        <div class="d-flex align-items-start gap-3 w-100 multi-select-item">
                                            <div class="form-check m-0 pt-1">
                                                <input class="form-check-input m-0" type="checkbox"
                                                    value="bateria" id="check-bateria">
                                            </div>
                                            <div class="d-flex flex-column gap-2 flex-grow-1">
                                                <div
                                                    class="d-flex justify-content-between align-items-center w-100 multi-select-details-row">
                                                    <div
                                                        class="d-flex align-items-center gap-3 multi-select-info">
                                                        <label for="check-bateria"
                                                            class="fw-400 m-0 cursor-pointer">Bateria
                                                            Li-Ion</label>
                                                        <span class="text-muted multi-select-stock-badge">Em
                                                            Stock: 6
                                                            un.</span>
                                                    </div>
                                                    <!-- Quantidade -->
                                                    <div
                                                        class="d-flex align-items-center gap-2 multi-select-qty-container d-none">
                                                        <span
                                                            class="text-secondary multi-select-qty-label">Qtd:</span>
                                                        <input type="number"
                                                            class="form-control text-center p-0 multi-select-qty-input"
                                                            value="1" min="1" max="6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Seccao 3: Manutencao & Garantia -->
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-wrench-icon lucide-wrench stroke-primary-500">
                                    <path
                                        d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />
                                </svg>
                                <h3>Manutenção & Garantia </h3>
                            </div>

                            <div class="d-flex flex-column gap-2 w-100">
                                <div class="d-flex gap-4 w-100">
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <label for="last-maintenance-start-date">Data de Início Última
                                            Manutenção</label>
                                        <input type="date" id="last-maintenance-start-date"
                                            name="last-maintenance-start-date" placeholder="dd/mm/yyyy">
                                    </div>

                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <label for="last-maintenance-end-date">Data de Fim Última
                                            Manutenção</label>
                                        <input type="date" id="last-maintenance-end-date"
                                            name="last-maintenance-end-date" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>
                                <span class="text-muted fst-italic">A configuração completa de garantias e
                                    contratos de
                                    assistência será efetuada na aba de "Manutenções & Garantias" após a criação
                                    do
                                    equipamento.</span>

                                <div class="d-flex flex-column form-item w-100 mt-2">
                                    <label>Documentos</label>
                                    <div
                                        class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-upload file-upload-icon">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="17 8 12 3 7 8" />
                                            <line x1="12" x2="12" y1="3" y2="15" />
                                        </svg>
                                        <p class="file-upload-text">Arraste ficheiros ou
                                            <span class="file-upload-text-action text-primary-500">clique para
                                                selecionar</span>
                                        </p>
                                        <span class="m-0 text-muted">PDF, JPG, PNG — máx. 10MB</span>
                                    </div>

                                    <!-- Input de Ficheiro escondido + Container de upload -->
                                    <input type="file" id="document-upload-input" class="d-none"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <div id="uploaded-files-container"
                                        class="w-100 d-flex flex-column gap-2 mt-2">
                                    </div>

                                    <!-- Template: Ficheiro Carregado -->
                                    <template id="uploaded-file-template">
                                        <div class="uploaded-file-card mt-2 padding-3 d-flex flex-column gap-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                        height="18" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-file text-primary-500">
                                                        <path
                                                            d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                                    </svg>
                                                    <p class="fw-500 m-0 file-name-display"
                                                        style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        filename.pdf</p>
                                                </div>
                                                <button type="button"
                                                    class="btn-close-file padding-1 d-flex align-items-center justify-content-center"
                                                    title="Remover Ficheiro">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-x">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <select class="form-select select-sm w-100">
                                                    <option value="" disabled selected>Tipo de Documento...
                                                    </option>
                                                    <option value="manual-utilizador">Manual do utilizador
                                                    </option>
                                                    <option value="manual-tecnico">Manual técnico/serviço
                                                    </option>
                                                    <option value="certificado-ce">Certificado CE</option>
                                                    <option value="relatorio-instalacao">Relatório de instalação
                                                    </option>
                                                    <option value="certificado-calibracao">Certificado de
                                                        calibração
                                                    </option>
                                                    <option value="contrato-manutencao">Contrato de manutenção
                                                    </option>
                                                    <option value="ficha-seguranca">Ficha de segurança/risco
                                                    </option>
                                                </select>
                                                <select class="form-select select-sm w-100">
                                                    <option value="" disabled selected>Associar Fornecedor...
                                                    </option>
                                                    <optgroup label="Fabricantes">
                                                        <option value="drager">Dräger Portugal Lda</option>
                                                        <option value="philips">Philips Ibérica S.A.</option>
                                                        <option value="bbraun">B. Braun Medical Lda</option>
                                                        <option value="stryker">Stryker Portugal</option>
                                                        <option value="ge">GE Healthcare Portugal</option>
                                                        <option value="medtronic">Medtronic Portugal</option>
                                                        <option value="siemens">Siemens Healthineers</option>
                                                    </optgroup>
                                                    <optgroup label="Distribuidores">
                                                        <option value="medicaltech">Medical Tech Distribuição
                                                            Lda
                                                        </option>
                                                        <option value="equip">EquipHospital SA</option>
                                                    </optgroup>
                                                    <optgroup label="Assistentes Técnicos">
                                                        <option value="bioservicos">BioServiços - Assistência
                                                            Técnica
                                                        </option>
                                                        <option value="tecnomed">TecnoMed Assistência</option>
                                                    </optgroup>
                                                    <optgroup label="Fornecedores de Consumíveis">
                                                        <option value="consumiveis_hospitalares">Consumíveis
                                                            Hospitalares, Lda.</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Notas Adicionais -->
                                <div class="d-flex flex-column form-item w-100 mt-2">
                                    <label for="additional-notes">Notas Adicionais</label>
                                    <textarea id="additional-notes" name="additional-notes"
                                        class="form-control w-100 no-resize" rows="4"
                                        placeholder="Observações, condições especiais..."></textarea>
                                </div>

                                <div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Button Row -->
                    <div class="d-flex w-100 justify-content-between gap-4 button-row mt-auto">
                        <button type="button" id="btn-prev-page" class="btn btn-ghost gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-left">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                            Voltar
                        </button>
                        <div class="d-flex gap-4">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                                Criar Equipamento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once BASE_PATH . 'private/includes/footer.php';
?>