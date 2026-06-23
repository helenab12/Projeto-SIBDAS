<?php
// Carregar dependências
require_once(__DIR__ . "/../config/funcoes.php");
require_once(__DIR__ . "/includes/dashboard_stats.php");

// Restringir acesso
redirect_if_not_logged();

// Carregar componentes
include_once 'includes/head.php';
include_once 'includes/sidebar-desktop.php';

// Inicializar erros de validação
$validation_errors = [];
if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}

// Inicializar erro de servidor
$server_error = null;
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

?>

<!-- Wrapper Principal -->
<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">
    <!-- Componente Headers -->
    <?php include_once 'includes/headers.php'; ?>

    <!-- Secção Dashboard -->
    <section class="gap-6 d-flex flex-column padding-6">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <!-- Títulos -->
            <div class="d-flex flex-column gap-1">
                <!-- Título -->
                <h1>Dashboard</h1>
                <!-- Subtítulo -->
                <p class="text-secondary fw-400">Visão geral do inventário hospitalar</p>
            </div>
            <!-- Botões de Ação -->
            <div class="d-flex gap-2">
                <!-- Botão Gerar QR -->
                <button class="btn btn-ghost-outline gap-2 btn-small" data-bs-toggle="modal"
                    data-bs-target="#qrSelectModal">
                    <!-- SVG QR -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-qr-code-icon lucide-qr-code">
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
                <!-- Botão Pesquisar QR -->
                <button class="btn btn-primary-outline gap-2 btn-small" data-bs-toggle="modal"
                    data-bs-target="#qrScanModal">
                    <!-- SVG Scan -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-scan-line-icon lucide-scan-line">
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

            <!-- Card Total Equipamentos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone e Trend -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Pacote -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-package-icon lucide-package dashboard-bento-icon dashboard-bento-icon-equipments">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                        <path d="M12 22V12" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <path d="m7.5 4.27 9 5.15" />
                    </svg>
                    <?php if ($dashboardStats['totalEquipamentos']['growth']): ?>
                        <!-- Wrapper Trend -->
                        <div
                            class="d-flex flex-row dashboard-bento-trend <?php echo $dashboardStats['totalEquipamentos']['growth']['isPositive'] ? 'dashboard-bento-trend-up' : 'dashboard-bento-trend-down'; ?> gap-1 align-items-center">
                            <?php if ($dashboardStats['totalEquipamentos']['growth']['isPositive']): ?>
                                <!-- SVG Trend Up -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up-icon lucide-trending-up ">
                                    <path d="M16 7h6v6" />
                                    <path d="m22 7-8.5 8.5-5-5L2 17" />
                                </svg>
                            <?php else: ?>
                                <!-- SVG Trend Down -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-down-icon lucide-trending-down">
                                    <path d="M16 17h6v-6" />
                                    <path d="m22 17-8.5-8.5-5 5L2 7" />
                                </svg>
                            <?php endif; ?>
                            <!-- Valor Trend -->
                            <span
                                class="fw-600"><?php echo ($dashboardStats['totalEquipamentos']['growth']['isPositive'] ? '+' : '') . $dashboardStats['totalEquipamentos']['growth']['value']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['totalEquipamentos']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Total de Equipamentos</span>
                </div>
            </div>

            <!-- Card Equipamentos Ativos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone e Trend -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Atividade -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-activity-icon lucide-activity dashboard-bento-icon dashboard-bento-icon-active">
                        <path
                            d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />
                    </svg>
                    <?php if ($dashboardStats['equipamentosAtivos']['growth']): ?>
                        <!-- Wrapper Trend -->
                        <div
                            class="d-flex flex-row dashboard-bento-trend <?php echo $dashboardStats['equipamentosAtivos']['growth']['isPositive'] ? 'dashboard-bento-trend-up' : 'dashboard-bento-trend-down'; ?> gap-1 align-items-center">
                            <?php if ($dashboardStats['equipamentosAtivos']['growth']['isPositive']): ?>
                                <!-- SVG Trend Up -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up-icon lucide-trending-up ">
                                    <path d="M16 7h6v6" />
                                    <path d="m22 7-8.5 8.5-5-5L2 17" />
                                </svg>
                            <?php else: ?>
                                <!-- SVG Trend Down -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-down-icon lucide-trending-down">
                                    <path d="M16 17h6v-6" />
                                    <path d="m22 17-8.5-8.5-5 5L2 7" />
                                </svg>
                            <?php endif; ?>
                            <!-- Valor Trend -->
                            <span
                                class="fw-600"><?php echo ($dashboardStats['equipamentosAtivos']['growth']['isPositive'] ? '+' : '') . $dashboardStats['equipamentosAtivos']['growth']['value']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['equipamentosAtivos']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Equipamentos ativos</span>
                </div>
            </div>

            <!-- Card Em Manutenção -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone e Trend -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Chave -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-wrench-icon lucide-wrench dashboard-bento-icon dashboard-bento-icon-maintenance">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />
                    </svg>
                    <?php if ($dashboardStats['emManutencao']['growth']): ?>
                        <!-- Wrapper Trend -->
                        <div
                            class="d-flex flex-row dashboard-bento-trend <?php echo $dashboardStats['emManutencao']['growth']['isPositive'] ? 'dashboard-bento-trend-up' : 'dashboard-bento-trend-down'; ?> gap-1 align-items-center">
                            <?php if ($dashboardStats['emManutencao']['growth']['isPositive']): ?>
                                <!-- SVG Trend Up -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up-icon lucide-trending-up ">
                                    <path d="M16 7h6v6" />
                                    <path d="m22 7-8.5 8.5-5-5L2 17" />
                                </svg>
                            <?php else: ?>
                                <!-- SVG Trend Down -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-down-icon lucide-trending-down">
                                    <path d="M16 17h6v-6" />
                                    <path d="m22 17-8.5-8.5-5 5L2 7" />
                                </svg>
                            <?php endif; ?>
                            <!-- Valor Trend -->
                            <span
                                class="fw-600"><?php echo ($dashboardStats['emManutencao']['growth']['isPositive'] ? '+' : '') . $dashboardStats['emManutencao']['growth']['value']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['emManutencao']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Em Manutenção</span>
                </div>
            </div>

            <!-- Card Garantias a Expirar -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone e Trend -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Alerta -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shield-alert-icon lucide-shield-alert dashboard-bento-icon dashboard-bento-icon-warranty-expiring">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                    <?php if ($dashboardStats['garantiasAExpirar']['growth']): ?>
                        <!-- Wrapper Trend -->
                        <div
                            class="d-flex flex-row dashboard-bento-trend <?php echo $dashboardStats['garantiasAExpirar']['growth']['isPositive'] ? 'dashboard-bento-trend-up' : 'dashboard-bento-trend-down'; ?> gap-1 align-items-center">
                            <?php if ($dashboardStats['garantiasAExpirar']['growth']['isPositive']): ?>
                                <!-- SVG Trend Up -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-up-icon lucide-trending-up ">
                                    <path d="M16 7h6v6" />
                                    <path d="m22 7-8.5 8.5-5-5L2 17" />
                                </svg>
                            <?php else: ?>
                                <!-- SVG Trend Down -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trending-down-icon lucide-trending-down">
                                    <path d="M16 17h6v-6" />
                                    <path d="m22 17-8.5-8.5-5 5L2 7" />
                                </svg>
                            <?php endif; ?>
                            <!-- Valor Trend -->
                            <span
                                class="fw-600"><?php echo ($dashboardStats['garantiasAExpirar']['growth']['isPositive'] ? '+' : '') . $dashboardStats['garantiasAExpirar']['growth']['value']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['garantiasAExpirar']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Garantia a Expirar</span>
                </div>
            </div>

            <!-- Card Equipamentos Inativos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Caixa X -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['equipamentosInativos']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Equipamentos Inativos</span>
                </div>
            </div>

            <!-- Card Garantias Expiradas -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Alerta Erro -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-triangle-alert-icon lucide-triangle-alert dashboard-bento-icon dashboard-bento-icon-warranty-expired">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['garantiasExpiradas']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Garantias Expiradas</span>
                </div>
            </div>

            <!-- Card Criticidade Elevada -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Raio -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-zap-icon lucide-zap dashboard-bento-icon dashboard-bento-icon-high-criticality">
                        <path
                            d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
                    </svg>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['criticidadeElevada']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Criticidade Elevada</span>
                </div>
            </div>

            <!-- Card Sem Documentos -->
            <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-4">
                <!-- Ícone -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- SVG Documento X -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-x-icon lucide-file-x dashboard-bento-icon dashboard-bento-icon-no-documents">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="m9 13 6 6" />
                        <path d="m15 13-6 6" />
                    </svg>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-1">
                    <!-- Valor -->
                    <h1><?php echo $dashboardStats['semDocumentos']['count']; ?></h1>
                    <!-- Rótulo -->
                    <span class="text-secondary fw-400">Sem Documentos</span>
                </div>
            </div>
        </div>

        <!-- Layout Gráficos -->
        <div class="d-flex flex-column gap-4">
            <!-- Gráficos de Distribuição -->
            <div class="d-flex flex-row gap-4 graphs-container">
                <!-- Gráfico Categorias -->
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6">
                    <!-- Títulos -->
                    <div class="d-flex flex-column gap-1">
                        <!-- Título -->
                        <h3>Distribuição por Categoria</h3>
                        <!-- Subtítulo -->
                        <p class="text-secondary fw-400">Quantidade de equipamentos por tipo</p>
                    </div>

                    <!-- Wrapper Gráfico -->
                    <div
                        class="d-flex flex-row justify-content-center flex-grow-1 w-100 position-relative graph-container">
                        <!-- Canvas Categoria -->
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>
                <!-- Gráfico Serviços -->
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6">
                    <!-- Títulos -->
                    <div class="d-flex flex-column gap-1">
                        <!-- Título -->
                        <h3>Equipamentos por Serviço</h3>
                        <!-- Subtítulo -->
                        <p class="text-secondary fw-400">Distribuição por localização/serviço</p>
                    </div>

                    <!-- Wrapper Gráfico -->
                    <div
                        class="d-flex flex-row justify-content-center flex-grow-1 w-100 position-relative graph-container">
                        <!-- Canvas Serviços -->
                        <canvas id="categoryDistributionChart2"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Tendência e Calendário -->
            <div class="d-flex flex-row gap-4 graphs-container">
                <!-- Gráfico Manutenção -->
                <div class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6">
                    <!-- Títulos -->
                    <div class="d-flex flex-column gap-1">
                        <!-- Título -->
                        <h3>Tendência de Manutenção</h3>
                        <!-- Subtítulo -->
                        <p class="text-secondary fw-400">Últimos 12 meses — preventiva vs. corretiva</p>
                    </div>

                    <!-- Wrapper Gráfico -->
                    <div
                        class="d-flex flex-row justify-content-center flex-grow-1 w-100 position-relative graph-container">
                        <!-- Canvas Tendência -->
                        <canvas id="maintenanceTrendChart"></canvas>
                    </div>
                </div>
                <!-- Widget Calendário -->
                <div
                    class="bento-card bento-card-move-up d-flex flex-column padding-6 gap-6 maintenance-calendar-section">
                    <!-- Cabeçalho -->
                    <div class="d-flex flex-row gap-3">
                        <!-- Títulos -->
                        <div class="d-flex flex-column gap-1">
                            <!-- Título -->
                            <h3>Próximas Manutenções</h3>
                            <!-- Subtítulo -->
                            <p class="text-secondary fw-400">Manutenções agendadas</p>
                        </div>
                    </div>

                    <!-- Lista Manutenções -->
                    <div class="d-flex flex-column gap-3 w-100 mt-2 h-100">
                        <?php
                        // Inicializar variáveis calendário
                        $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
                        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

                        // Obter próximas manutenções
                        $proximasManutencoes = $dashboardStats['proximasManutencoes'] ?? [];

                        // Verificar manutenções
                        if (empty($proximasManutencoes)): ?>
                            <!-- Wrapper Vazio -->
                            <div
                                class="d-flex flex-column align-items-center justify-content-center text-center padding-4 gap-3 h-100">
                                <!-- SVG Calendário -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-calendar-check-2-icon lucide-calendar-check-2 text-secondary">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8" />
                                    <path d="M3 10h18" />
                                    <path d="m16 20 2 2 4-4" />
                                </svg>
                                <!-- Mensagem Vazio -->
                                <span class="text-secondary fw-400">Nenhuma manutenção agendada.</span>
                            </div>
                        <?php else: ?>
                            <?php foreach ($proximasManutencoes as $manutencao):
                                // Calcular datas
                                $dataInicio = strtotime($manutencao['dataInicio']);
                                $diaSemana = $diasSemana[date('w', $dataInicio)];
                                $diaMes = date('d', $dataInicio);
                                $mes = $meses[date('n', $dataInicio) - 1];

                                // Encriptar ID
                                $encryptedId = aes_encrypt((string) $manutencao['idEquipamento']);

                                // Construir link
                                $url = "inventory/equipments/detailed_view.php?id=" . urlencode($encryptedId) . "&nav=manutencoes";
                                $servicoDesc = $manutencao['nomeServico'] ?: 'Geral';
                                ?>
                                <!-- Link Manutenção -->
                                <a href="<?= $url ?>"
                                    class="maintenance-widget-item overflow-hidden d-flex flex-row align-items-stretch w-100 text-decoration-none">
                                    <!-- Bloco Data -->
                                    <div
                                        class="maintenance-widget-date d-flex flex-column align-items-center justify-content-center padding-2">
                                        <!-- Dia Semana -->
                                        <span class="text-uppercase fw-600 text-secondary"><?= $diaSemana ?></span>
                                        <!-- Dia Mês -->
                                        <h2 class="fw-700 text-primary"><?= $diaMes ?></h2>
                                        <!-- Mês -->
                                        <span class="text-secondary fw-500"><?= $mes ?></span>
                                    </div>
                                    <!-- Bloco Detalhes -->
                                    <div class="mw-0 d-flex flex-column justify-content-center flex-grow-1 padding-3 gap-1">
                                        <!-- Nome Equipamento -->
                                        <span
                                            class="fw-600 text-primary-900"><?= htmlspecialchars($manutencao['designacao']) ?></span>
                                        <!-- Tipo e Serviço -->
                                        <span
                                            class="fw-400 text-secondary fs-sm text-truncate"><?= htmlspecialchars($manutencao['tipoManutencao']) ?>
                                            · <?= htmlspecialchars($servicoDesc) ?></span>
                                    </div>
                                    <!-- Seta -->
                                    <div class="d-flex align-items-center justify-content-center padding-3">
                                        <!-- SVG Seta -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-secondary">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
// Carregar componente mobile
include_once 'includes/sidebar-mobile.php';
?>

<!-- Container Toasts -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4 error-toast"
    style="z-index: 9999;">
    <?php if (!empty($validation_errors) || !empty($server_error)): ?>

        <?php
        // Agrupar erros
        $all_errors = [];
        if (!empty($validation_errors)) {
            $all_errors = array_merge($all_errors, $validation_errors);
        }
        if (!empty($server_error)) {
            $all_errors[] = $server_error;
        }
        ?>
        <?php foreach ($all_errors as $error): ?>
            <!-- Toast Erro -->
            <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <!-- Wrapper Mensagem -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Texto Erro -->
                    <div class="toast-body fw-500 p-0">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <!-- Botão Fechar -->
                    <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                        aria-label="Close">
                        <!-- SVG X -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Script Dados Dashboard -->
<script>
    window.DashboardData = {
        graficoCategoria: <?php echo json_encode($dashboardStats['graficoCategoria']); ?>,
        graficoServico: <?php echo json_encode($dashboardStats['graficoServico']); ?>,
        graficoManutencao: <?php echo json_encode($dashboardStats['graficoManutencao']); ?>
    };
</script>

<?php
// Carregar componentes modais
include_once 'includes/modals/qr_print_modal.php';
include_once 'includes/modals/qr_select_modal.php';
include_once 'includes/modals/qr_scan_modal.php';

// Carregar rodapé
include_once 'includes/footer.php';
?>