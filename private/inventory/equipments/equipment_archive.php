<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex flex-column gap-1">
            <h1>Equipamentos Arquivados</h1>
            <p class="text-secondary fw-400">2 equipamentos arquivados</p>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Bomba de Seringa Space</p>
                                    <span class="equipment-subtitle text-secondary fw-400">B. Braun Perfusor
                                        Space
                                        &bull; BBR-PSP-11223</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Bombas volumétricas e seringas para administração controlada de fármacos.">Bombas
                                de Infusão</span>
                        </td>
                        <td class="equipment-location">Armazém Central</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-abated equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento abatido e permanentemente retirado de serviço.">Abatido</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-low equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento de suporte geral — falha tem baixo impacto imediato.">Baixo</span>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye-icon lucide-eye">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Ver Detalhe
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-box text-primary">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="equipment-title fw-700 mb-0">Oxímetro de Pulso</p>
                                    <span class="equipment-subtitle text-secondary fw-400">Masimo Radical-7
                                        &bull; MAS-R7-44556</span>
                                </div>
                            </div>
                        </td>
                        <td class="equipment-category"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Monitores multiparamétricos de sinais vitais (ECG, SpO2, PNI, etc.).">Monitores
                                de Sinais Vitais</span>
                        </td>
                        <td class="equipment-location">Armazém Central</td>
                        <td class="equipment-status"><span
                                class="equipment-badge equipment-badge-status-abated equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento permanentemente retirado de serviço.">Abatido</span>
                        </td>
                        <td class="equipment-criticality"><span
                                class="equipment-badge equipment-badge-criticality-low equipment-badge-tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Equipamento de suporte geral — falha tem baixo impacto imediato.">Baixo</span>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye-icon lucide-eye">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Ver Detalhe
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
include_once BASE_PATH . 'private/includes/footer.php';
?>