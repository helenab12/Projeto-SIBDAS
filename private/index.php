<?php
require_once(__DIR__ . "/../config/config.php");
include_once 'includes/head.php';
include_once 'includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">
    <?php include_once 'includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Dashboard</h1>
                <p class="text-secondary fw-400">Visão geral do inventário hospitalar — abril de 2026</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-ghost-outline gap-2 btn-small">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-qr-code-icon lucide-qr-code">
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
                        <path d="M12 21v-1" />
                    </svg>
                    Gerar QR
                </button>
                <button class="btn btn-primary-outline gap-2 btn-small">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-scan-line-icon lucide-scan-line">
                        <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                        <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                        <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                        <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                        <path d="M7 12h10" />
                    </svg>
                    Pesquisar QR
                </button>
            </div>
        </div>

        <!-- Bento Grid de Estatísticas -->
        <div class="bento-grid gap-4 dashboard-bento-grid">

            <!-- Total Equipamentos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-package-icon lucide-package dashboard-bento-icon dashboard-bento-icon-equipments">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                        <path d="M12 22V12" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <path d="m7.5 4.27 9 5.15" />
                    </svg>
                    <div
                        class="d-flex flex-row dashboard-bento-trend dashboard-bento-trend-up gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up-icon lucide-trending-up ">
                            <path d="M16 7h6v6" />
                            <path d="m22 7-8.5 8.5-5-5L2 17" />
                        </svg>
                        <span class="fw-600">+12</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>13</h1>
                    <span class="text-secondary fw-400">Total de Equipamentos</span>
                </div>
            </div>

            <!-- Equipamentos Ativos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-activity-icon lucide-activity dashboard-bento-icon dashboard-bento-icon-active">
                        <path
                            d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />
                    </svg>
                    <div
                        class="d-flex flex-row dashboard-bento-trend dashboard-bento-trend-up gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up-icon lucide-trending-up ">
                            <path d="M16 7h6v6" />
                            <path d="m22 7-8.5 8.5-5-5L2 17" />
                        </svg>
                        <span class="fw-600">+8</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>8</h1>
                    <span class="text-secondary fw-400">Equipamentos ativos</span>
                </div>
            </div>

            <!-- Em Manutencao -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-wrench-icon lucide-wrench dashboard-bento-icon dashboard-bento-icon-maintenance">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />
                    </svg>
                    <div
                        class="d-flex flex-row dashboard-bento-trend dashboard-bento-trend-down gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-trending-down-icon lucide-trending-down">
                            <path d="M16 17h6v-6" />
                            <path d="m22 17-8.5-8.5-5 5L2 7" />
                        </svg>
                        <span class="fw-600">-3</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>1</h1>
                    <span class="text-secondary fw-400">Em Manutenção</span>
                </div>
            </div>

            <!-- Garantias A Expirar -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-shield-alert-icon lucide-shield-alert dashboard-bento-icon dashboard-bento-icon-warranty-expiring">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                    <div
                        class="d-flex flex-row dashboard-bento-trend dashboard-bento-trend-up gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up-icon lucide-trending-up ">
                            <path d="M16 7h6v6" />
                            <path d="m22 7-8.5 8.5-5-5L2 17" />
                        </svg>
                        <span class="fw-600">+2</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>3</h1>
                    <span class="text-secondary fw-400">Garantia a Expirar</span>
                </div>
            </div>

            <!-- Equipamentos Inativos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-package-x-icon lucide-package-x dashboard-bento-icon dashboard-bento-icon-inactive">
                        <path d="M12 22V12" />
                        <path d="m16.5 14.5 5 5" />
                        <path d="m16.5 19.5 5-5" />
                        <path
                            d="M21 10.5V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l.13-.074" />
                        <path d="M3.29 7 12 12l8.71-5" />
                        <path d="m7.5 4.27 8.997 5.148" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>1</h1>
                    <span class="text-secondary fw-400">Equipamentos Inativos</span>
                </div>
            </div>

            <!-- Garantias Expiradas -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-triangle-alert-icon lucide-triangle-alert dashboard-bento-icon dashboard-bento-icon-warranty-expired">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                    <div
                        class="d-flex flex-row dashboard-bento-trend dashboard-bento-trend-up gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up-icon lucide-trending-up ">
                            <path d="M16 7h6v6" />
                            <path d="m22 7-8.5 8.5-5-5L2 17" />
                        </svg>
                        <span class="fw-600">+2</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>6</h1>
                    <span class="text-secondary fw-400">Garantias Expiradas</span>
                </div>
            </div>

            <!-- Criticidade Elevada -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-zap-icon lucide-zap dashboard-bento-icon dashboard-bento-icon-high-criticality">
                        <path
                            d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>5</h1>
                    <span class="text-secondary fw-400">Criticidade Elevada</span>
                </div>
            </div>

            <!-- Sem Documentos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-file-x-icon lucide-file-x dashboard-bento-icon dashboard-bento-icon-no-documents">
                        <path
                            d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                        <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                        <path d="m14.5 12.5-5 5" />
                        <path d="m9.5 12.5 5 5" />
                    </svg>
                    <div
                        class="d-flex flex-row dashboard-bento-trend dashboard-bento-trend-down gap-1 align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-trending-down-icon lucide-trending-down">
                            <path d="M16 17h6v-6" />
                            <path d="m22 17-8.5-8.5-5 5L2 7" />
                        </svg>
                        <span class="fw-600">-2</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <h1>5</h1>
                    <span class="text-secondary fw-400">Sem Documentos</span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-4">
            <!-- Graficos de Estatísticas -->
            <div class="d-flex flex-row gap-4 graphs-container">
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6">
                    <div class="d-flex flex-column gap-1">
                        <h3>Distribuição por Categoria</h3>
                        <p class="text-secondary fw-400">Quantidade de equipamentos por tipo</p>
                    </div>

                    <div class="d-flex flex-row justify-content-center graph-container">
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6">
                    <div class="d-flex flex-column gap-1">
                        <h3>Equipamentos por Serviço</h3>
                        <p class="text-secondary fw-400">Distribuição por localização/serviço</p>
                    </div>

                    <div class="d-flex flex-row justify-content-center graph-container">
                        <canvas id="categoryDistributionChart2"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graficos de Tendencias de Manutencoes + Chat AI -->
            <div class="d-flex flex-row gap-4 graphs-container">
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6">
                    <div class="d-flex flex-column gap-1">
                        <h3>Tendência de Manutenção</h3>
                        <p class="text-secondary fw-400">Últimos 12 meses — preventiva vs. corretiva</p>
                    </div>

                    <div class="d-flex flex-row justify-content-center graph-container">
                        <canvas id="maintenanceTrendChart"></canvas>
                    </div>
                </div>
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6 ai-section">
                    <div class="d-flex flex-row gap-3">
                        <div class="ai-chat d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-bot-icon lucide-bot text-white">
                                <path d="M12 8V4H8" />
                                <rect width="16" height="12" x="4" y="8" rx="2" />
                                <path d="M2 14h2" />
                                <path d="M20 14h2" />
                                <path d="M15 13v2" />
                                <path d="M9 13v2" />
                            </svg>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <h3>BioMedical AI Assistant</h3>
                            <p class="text-secondary fw-400">Análise inteligente do inventário</p>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 w-100">
                        <div
                            class="ai-card ai-card-warning d-flex flex-row gap-2 align-items-start justify-items-start padding-3 w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-triangle-alert-icon lucide-triangle-alert">
                                <path
                                    d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                            </svg>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-700">Manutenção Atrasada</span>
                                <label class="fw-400 ai-description text-lowercase">3 equipamentos críticos com
                                    manutenção
                                    próxima.</label>
                            </div>
                        </div>
                        <div
                            class="ai-card ai-card-success d-flex flex-row gap-2 align-items-start justify-items-start padding-3 w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-circle-check-icon lucide-circle-check">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-700">Calibrações OK</span>
                                <label class="fw-400 ai-description text-lowercase">Todos os ventiladores com
                                    calibração
                                    em dia.</label>
                            </div>
                        </div>
                        <div
                            class="ai-card ai-card-info d-flex flex-row gap-2 align-items-start justify-items-start padding-3 w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-info-icon lucide-info">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-700">Stock Baixo</span>
                                <label class="fw-400 ai-description text-lowercase">Baterias Li-Ion abaixo do
                                    stock
                                    mínimo (6/4).</label>
                            </div>
                        </div>

                        <div
                            class="ai-card ai-card-error d-flex flex-row gap-2 align-items-start justify-items-start padding-3 w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-lightbulb-icon lucide-lightbulb">
                                <path
                                    d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5" />
                                <path d="M9 18h6" />
                                <path d="M10 22h4" />
                            </svg>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-700">Equipamentos sem Garantia</span>
                                <label class="fw-400 ai-description text-lowercase">3 equipamentos sem garantia.
                                    Recomendado
                                    contratar garantia.</label>
                            </div>
                        </div>
                    </div>
                    <form action="" class="d-flex flex-row w-100">
                        <div class="form-item w-100 flex-row d-flex align-items-center gap-2">
                            <input type="search" id="search" name="search"
                                placeholder="Perguntar ao assistente..." required class="w-100 input-small">

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-send-icon lucide-send">
                                <path
                                    d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                                <path d="m21.854 2.147-10.94 10.939" />
                            </svg>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Atividade Recente -->
        <div class="bento-card bento-card-move-up d-flex flex-column gap-6 padding-6">
            <div class="d-flex flex-row align-items-center justify-content-between w-100">
                <h2 class="text-primary m-0">Atividade Recente</h2>
                <a href="#" class="text-primary d-flex flex-row gap-1 align-items-center text-primary-500">
                    <p class="fw-400">Ver tudo</p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-arrow-up-right-icon lucide-arrow-up-right">
                        <path d="M7 7h10v10" />
                        <path d="M7 17 17 7" />
                    </svg>
                </a>
            </div>

            <!-- Lista de Atividades -->
            <div class="d-flex flex-column gap-2 w-100">
                <div class="d-flex flex-row align-items-center gap-3 activity-item w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-500">Atualização: Campo atualizado de 2025-09-20 para 2025-11-20</span>
                        <label class="fw-400 text-secondary">Dr. Manuel Costa &bull; 07/04/2026</label>
                    </div>
                </div>

                <div class="d-flex flex-row align-items-center gap-3 activity-item w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-500">Manutenção Registada: Manutenção preventiva concluída</span>
                        <label class="fw-400 text-secondary">Eng.ª Ana Ferreira &bull; 07/04/2026</label>
                    </div>
                </div>

                <div class="d-flex flex-row align-items-center gap-3 activity-item w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-500">Abate de Equipamento: Equipamento marcado como abatido por
                            obsolescência</span>
                        <label class="fw-400 text-secondary">Admin Sistema &bull; 07/04/2026</label>
                    </div>
                </div>

                <div class="d-flex flex-row align-items-center gap-3 activity-item w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-500">Criação: Novo fornecedor de consumíveis registado</span>
                        <label class="fw-400 text-secondary">Sofia Oliveira &bull; 06/04/2026</label>
                    </div>
                </div>

                <div class="d-flex flex-row align-items-center gap-3 activity-item w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-500">Soft Delete: Dr.ª Maria Lopes marcada como inativa</span>
                        <label class="fw-400 text-secondary">Admin Sistema &bull; 06/04/2026</label>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<?php
include_once 'includes/sidebar-mobile.php';
include_once 'includes/footer.php';
?>