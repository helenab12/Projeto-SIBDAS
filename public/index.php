<?php
// Carregar dependências
require_once dirname(__DIR__) . '/config/funcoes.php';
// Iniciar sessão
start_session();

// Inicializar variáveis
$validation_errors = [];
$server_error = null;
$success_message = null;

// Carregar dados da sessão
if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}
if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Verificar submissão
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolher dados do POST
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $organization = $_POST['organization'] ?? '';
    $message = $_POST['message'] ?? '';

    // Sanitizar dados
    $dados_sanitizados = sanitizar_array_dados([
        'nome' => $name,
        'fabricante' => $organization,
        'email' => $email,
        'mensagem' => $message
    ]);

    // Extrair dados sanitizados
    $name_sanitized = $dados_sanitizados['nome'];
    $organization_sanitized = $dados_sanitizados['fabricante'];
    $email_sanitized = $dados_sanitizados['email'];
    $message_sanitized = $dados_sanitizados['mensagem'];

    try {
        // Validar dados
        $validation_errors = PedidoDemonstracao::validarDados([
            'id' => 1,
            'name' => $name,
            'email' => $email,
            'institution' => $organization,
            'message' => $message,
        ]);

        // Guardar na base de dados
        if (empty($validation_errors)) {
            // Ligar à BD
            $ligacao = connect_to_db();
            // Inserir registo
            execute_query(
                "INSERT INTO PedidoDemonstracao (nomeContacto, emailContacto, organizacao, mensagem, estado, ativo, dataCriacao) 
                 VALUES (:nome, :email, :organizacao, :mensagem, 'Novo', 1, NOW())",
                [
                    'nome' => $name_sanitized,
                    'email' => $email_sanitized,
                    'organizacao' => $organization_sanitized,
                    'mensagem' => $message_sanitized
                ],
                $ligacao
            );

            // Obter ID inserido
            $idPedido = $ligacao->lastInsertId();

            // Registar auditoria
            registar_auditoria(
                $ligacao,
                'PedidoDemonstracao',
                $idPedido,
                'Criação',
                null,
                null,
                null
            );

            // Definir mensagem de sucesso
            $_SESSION['success_message'] = "Pedido de demonstração enviado com sucesso! Entraremos em contacto brevemente.";

            // Redirecionar página
            header("Location: index.php?success=1#pa-cta");
            exit;
        }
    } catch (Exception $e) {
        // Capturar erro
        $server_error = "Erro ao guardar o seu pedido: " . $e->getMessage();
    }
}

// Inicializar ligação
$ligacao = null;
try {
    // Ligar à BD
    $ligacao = connect_to_db();
} catch (Exception $e) {
    // Capturar erro
    $server_error = "Erro ao conectar à base de dados: " . $e->getMessage();
}

// Inicializar conteúdo
$conteudoPagina = null;
try {
    // Carregar conteúdo
    $conteudoPagina = ConteudoPagina::carregarDaBaseDeDados($ligacao);
} catch (Exception $e) {
    // Capturar erro
    $server_error = "Erro ao carregar dados do servidor: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título -->
    <title><?= $conteudoPagina['navbar.brand_name'] ?> - Área Pública</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/1240961.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/img/logo.png" type="image/x-icon">
</head>

<body class="pa-body overflow-hidden">
    <!-- Spinner de Carregamento -->
    <div id="page-loading-overlay"
        class="page-loading-overlay position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
        <!-- Spinner -->
        <div class="page-loading-spinner"></div>
    </div>
    <?php ob_flush();
    flush(); ?>

    <!-- Nav Bar -->
    <nav class="pa-navbar w-100 position-fixed ">
        <div class="pa-page-container w-100">
            <!-- Header (sempre visível) -->
            <div class="pa-nav-header d-flex align-items-center justify-content-between align-self-center">
                <div class="navbar-brand d-flex align-items-center gap-3">
                    <!-- Wrapper Logotipo -->
                    <div class="navbar-logo d-flex align-items-center justify-content-center padding-2-5 btn-glowing">
                        <!-- SVG Coração -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-heart-pulse-icon lucide-heart-pulse stroke-white">
                            <path
                                d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            <path d="M3.22 13H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27" />
                        </svg>
                    </div>
                    <!-- Wrapper Título -->
                    <div class="d-flex flex-column">
                        <!-- Título -->
                        <h1><?= $conteudoPagina['navbar.brand_name'] ?></h1>
                    </div>
                </div>

                <!-- Desktop: links + toggle + CTA -->
                <div class="d-none d-md-flex align-items-center gap-8">
                    <!-- Link Funcionalidades -->
                    <a href="#pa-features">
                        <!-- Texto -->
                        <p class="text-secondary">
                            <?= $conteudoPagina['navbar.link_funcionalidades'] ?>
                        </p>
                    </a>
                    <!-- Link Vantagens -->
                    <a href="#pa-advantages">
                        <!-- Texto -->
                        <p class="text-secondary"><?= $conteudoPagina['navbar.link_vantagens'] ?></p>
                    </a>
                    <!-- Botão Tema -->
                    <button
                        class="pa-theme-toggle cursor-pointer  border-0 bg-transparent p-0 d-inline-flex align-items-center text-secondary"
                        aria-label="Alternar tema">
                        <!-- SVG Lua -->
                        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401" />
                        </svg>
                        <!-- SVG Sol -->
                        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2" />
                            <path d="M12 20v2" />
                            <path d="m4.93 4.93 1.41 1.41" />
                            <path d="m17.66 17.66 1.41 1.41" />
                            <path d="M2 12h2" />
                            <path d="M20 12h2" />
                            <path d="m6.34 17.66-1.41 1.41" />
                            <path d="m19.07 4.93-1.41 1.41" />
                        </svg>
                    </button>
                    <!-- Botão Agendar Demonstração -->
                    <a href="#pa-cta" class="btn btn-primary">
                        <?= $conteudoPagina['navbar.btn_agendar_demo'] ?>
                    </a>
                </div>

                <!-- Mobile: toggle + hambúrguer -->
                <div class="pa-nav-mobile-controls align-items-center gap-4 d-flex d-md-none">
                    <!-- Botão Tema -->
                    <button
                        class="pa-theme-toggle cursor-pointer  border-0 bg-transparent p-0 d-inline-flex align-items-center text-secondary"
                        aria-label="Alternar tema">
                        <!-- SVG Lua -->
                        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401" />
                        </svg>
                        <!-- SVG Sol -->
                        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2" />
                            <path d="M12 20v2" />
                            <path d="m4.93 4.93 1.41 1.41" />
                            <path d="m17.66 17.66 1.41 1.41" />
                            <path d="M2 12h2" />
                            <path d="M20 12h2" />
                            <path d="m6.34 17.66-1.41 1.41" />
                            <path d="m19.07 4.93-1.41 1.41" />
                        </svg>
                    </button>
                    <!-- Botão Hambúrguer -->
                    <button
                        class="pa-hamburger bg-transparent border-0 cursor-pointer p-0 d-inline-flex align-items-center text-primary"
                        id="menu-toggle" aria-label="Menu">
                        <!-- SVG Menu -->
                        <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12" />
                            <line x1="4" x2="20" y1="6" y2="6" />
                            <line x1="4" x2="20" y1="18" y2="18" />
                        </svg>
                        <!-- SVG Fechar -->
                        <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu mobile (colapsável) -->
            <div class="pa-nav-mobile-menu flex-column gap-6" id="mobile-menu">
                <!-- Link Funcionalidades Mobile -->
                <a href="#pa-features">
                    <!-- Texto -->
                    <p class="text-secondary">
                        <?= $conteudoPagina['navbar.link_funcionalidades'] ?>
                    </p>
                </a>
                <!-- Link Vantagens Mobile -->
                <a href="#pa-advantages">
                    <!-- Texto -->
                    <p class="text-secondary">
                        <?= $conteudoPagina['navbar.link_vantagens'] ?>
                    </p>
                </a>
                <!-- Botão Agendar Demonstração Mobile -->
                <a href="#pa-cta" class="btn btn-primary w-100 text-center">
                    <?= $conteudoPagina['navbar.btn_agendar_demo'] ?>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main>
        <!-- main-section -->
        <section class="pa-main-section d-flex align-items-center justify-content-center" id="pa-main-section">
            <div class="pa-page-container w-100 d-flex gap-6 flex-column align-items-center text-center">
                <!-- Badge -->
                <p class="badge badge-primary">
                    <!-- SVG Badge -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="currentColor" stroke="none">
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                    <?= $conteudoPagina['hero.badge'] ?>
                </p>
                <!-- Título -->
                <h1 class="main-section m-0 fw-700">
                    <?= $conteudoPagina['hero.title'] ?>
                </h1>
                <!-- Subtítulo -->
                <h2 class="text-secondary fw-400">
                    <?= $conteudoPagina['hero.subtitle'] ?>
                </h2>
                <div class="d-flex main-section-buttons flex-column flex-md-row">
                    <!-- Botão Agendar -->
                    <a href="#pa-cta" class="btn btn-primary btn-large btn-glowing fw-600 ">
                        <?= $conteudoPagina['hero.btn_agendar'] ?>
                    </a>
                    <!-- Botão Explorar -->
                    <a href="#pa-features" class="btn btn-ghost btn-large fw-600">
                        <?= $conteudoPagina['hero.btn_explorar'] ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="pa-features" id="pa-features">
            <div class="pa-page-container w-100 d-flex flex-column align-items-center gap-16">
                <div class="gap-4 d-flex flex-column align-items-center text-center">
                    <!-- Título -->
                    <h1 class="section-title">
                        <?= $conteudoPagina['features.title'] ?>
                    </h1>
                    <!-- Subtítulo -->
                    <h2 class="text-secondary fw-400">
                        <?= $conteudoPagina['features.subtitle'] ?>
                    </h2>
                </div>
                <div class="bento-grid">
                    <?php

                    $cartoes = $conteudoPagina->getCartoes();

                    foreach ($cartoes as $cartao):
                        if ($cartao->getAtivo()):
                            ?>
                            <div class="bento-card padding-8 d-flex flex-column gap-3 align-items-start text-start">
                                <!-- SVG Ícone -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide text-primary-500 padding-3">
                                    <?= $cartao->getIcone() ?>
                                </svg>
                                <!-- Título Funcionalidade -->
                                <h2 class="font-mono">
                                    <?= $cartao->getTitulo() ?>
                                </h2>
                                <!-- Subtítulo Funcionalidade -->
                                <h3 class="text-secondary fw-400">
                                    <?= $cartao->getDescricao() ?>
                                </h3>
                            </div>
                            <?php
                        endif;
                    endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Vantagens -->
        <section class="pa-advantages" id="pa-advantages">
            <div class="pa-page-container w-100 d-flex flex-column flex-md-row align-items-stretch text-center gap-16">
                <div class="d-flex flex-column gap-6 align-items-start">
                    <!-- Badge Vantagens -->
                    <p class="badge badge-advantages text-success">
                        <!-- SVG Badge -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" stroke="none">
                            <circle cx="12" cy="12" r="4" />
                        </svg>
                        <?= $conteudoPagina['advantages.badge'] ?>
                    </p>
                    <!-- Título -->
                    <h1 class="section-title text-start">
                        <?= $conteudoPagina['advantages.title'] ?>
                    </h1>
                    <!-- Subtítulo -->
                    <h2 class="text-secondary fw-400 text-start">
                        <?= $conteudoPagina['advantages.subtitle'] ?>
                    </h2>
                    <div class="d-flex flex-column gap-8">
                        <div class="d-flex align-items-start gap-4">
                            <div class="pa-advantage-icon text-success">
                                <!-- SVG Check -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-shield-check-icon lucide-shield-check padding-3 rounded-pill">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-start">
                                <!-- Título Vantagem -->
                                <h2 class="text-start"><?= $conteudoPagina['advantages.item1_title'] ?></h2>
                                <!-- Subtítulo Vantagem -->
                                <h3 class="text-secondary fw-400 text-start">
                                    <?= $conteudoPagina['advantages.item1_desc'] ?>
                                </h3>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-4">
                            <div class="pa-advantage-icon text-success">
                                <!-- SVG Check Big -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-circle-check-big-icon lucide-circle-check-big padding-3 rounded-pill">
                                    <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                                    <path d="m9 11 3 3L22 4" />
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-start">
                                <!-- Título Vantagem -->
                                <h2 class="text-start">
                                    <?= $conteudoPagina['advantages.item2_title'] ?>
                                </h2>
                                <!-- Subtítulo Vantagem -->
                                <h3 class="text-secondary fw-400 text-start">
                                    <?= $conteudoPagina['advantages.item2_desc'] ?>
                                </h3>
                            </div>
                        </div>
                        <div class="d-flex align-items-start justify-items-start gap-4">
                            <div class="pa-advantage-icon text-success">
                                <!-- SVG Clock -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-clock-icon lucide-clock padding-3 rounded-pill">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-start">
                                <!-- Título Vantagem -->
                                <h2 class="text-start">
                                    <?= $conteudoPagina['advantages.item3_title'] ?>
                                </h2>
                                <!-- Subtítulo Vantagem -->
                                <h3 class="text-secondary fw-400 text-start">
                                    <?= $conteudoPagina['advantages.item3_desc'] ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center pa-advantages-image-background padding-8">
                    <!-- Imagem Vantagens -->
                    <img src="<?= BASE_URL . '/assets/img/dashboard.png' ?>" alt="Imagem de vantagens"
                        class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </section>

        <!-- Clientes -->
        <section class="pa-clients text-white" id="pa-clients">
            <div class="pa-page-container w-100 d-flex flex-column align-items-center gap-16">
                <!-- Título -->
                <h1 class="section-title text-center"><?= $conteudoPagina['clients.title'] ?></h1>
                <div class="bento-grid">
                    <div class="bento-card padding-8 d-flex flex-column gap-3 align-items-center text-start">
                        <!-- SVG Hospital -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-hospital-icon lucide-hospital">
                            <path d="M12 7v4" />
                            <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                            <path d="M14 9h-4" />
                            <path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                            <path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16" />
                        </svg>
                        <!-- Título Card -->
                        <h2 class="text-center"><?= $conteudoPagina['clients.card1_title'] ?></h2>
                        <!-- Subtítulo Card -->
                        <h3 class="fw-400 text-center text-muted">
                            <?= $conteudoPagina['clients.card1_desc'] ?>
                        </h3>
                    </div>
                    <div class="bento-card padding-8 d-flex flex-column gap-3 align-items-center text-start">
                        <!-- SVG Stethoscope -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-stethoscope-icon lucide-stethoscope">
                            <path d="M11 2v2" />
                            <path d="M5 2v2" />
                            <path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1" />
                            <path d="M8 15a6 6 0 0 0 12 0v-3" />
                            <circle cx="20" cy="10" r="2" />
                        </svg>
                        <!-- Título Card -->
                        <h2 class="text-center"><?= $conteudoPagina['clients.card2_title'] ?></h2>
                        <!-- Subtítulo Card -->
                        <h3 class="fw-400 text-center text-muted">
                            <?= $conteudoPagina['clients.card2_desc'] ?>
                        </h3>
                    </div>
                    <div class="bento-card padding-8 d-flex flex-column gap-3 align-items-center text-start">
                        <!-- SVG Microscope -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-microscope-icon lucide-microscope">
                            <path d="M6 18h8" />
                            <path d="M3 22h18" />
                            <path d="M14 22a7 7 0 1 0 0-14h-1" />
                            <path d="M9 14h2" />
                            <path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z" />
                            <path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3" />
                        </svg>
                        <!-- Título Card -->
                        <h2 class="text-center"><?= $conteudoPagina['clients.card3_title'] ?></h2>
                        <!-- Subtítulo Card -->
                        <h3 class="fw-400 text-center text-muted">
                            <?= $conteudoPagina['clients.card3_desc'] ?>
                        </h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="pa-cta" id="pa-cta">
            <div class="pa-page-container w-100 d-flex">
                <div class="bento-card d-flex flex-column gap-8 padding-16 w-100">
                    <div class="d-flex flex-column gap-4 align-items-center w-100">
                        <!-- Título -->
                        <h1 class="section-title text-center"><?= $conteudoPagina['cta.title'] ?></h1>
                        <!-- Subtítulo -->
                        <h3 class="fw-400 text-center text-secondary">
                            <?= $conteudoPagina['cta.subtitle'] ?>
                        </h3>
                    </div>
                    <div class="d-flex flex-column gap-6 w-100">
                        <form action="index.php#pa-cta" method="POST"
                            class="d-flex flex-column gap-6 align-items-stretch w-100" id="cta-form" novalidate>
                            <div class="pa-cta-form-grid d-grid gap-6">
                                <div class="d-flex flex-column align-items-start form-item justify-items-start">
                                    <!-- Label Nome -->
                                    <label for="name"><?= $conteudoPagina['cta.label_nome'] ?>
                                        <!-- Span Obrigatório -->
                                        <span class="text-error">*</span>
                                    </label>
                                    <!-- Input Nome -->
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="<?= $conteudoPagina['cta.placeholder_nome'] ?>"
                                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                                </div>
                                <div class="d-flex flex-column align-items-start form-item justify-items-start">
                                    <!-- Label Email -->
                                    <label for="email"><?= $conteudoPagina['cta.label_email'] ?>
                                        <!-- Span Obrigatório -->
                                        <span class="text-error">*</span>
                                    </label>
                                    <!-- Input Email -->
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="<?= $conteudoPagina['cta.placeholder_email'] ?>"
                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                </div>
                                <div class="d-flex flex-column align-items-start form-item justify-items-start">
                                    <!-- Label Organização -->
                                    <label for="organization"><?= $conteudoPagina['cta.label_organizacao'] ?>
                                        <!-- Span Obrigatório -->
                                        <span class="text-error">*</span>
                                    </label>
                                    <!-- Input Organização -->
                                    <input type="text" id="organization" name="organization" class="form-control"
                                        placeholder="<?= $conteudoPagina['cta.placeholder_organizacao'] ?>"
                                        value="<?= htmlspecialchars($_POST['organization'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-start form-item">
                                <!-- Label Mensagem -->
                                <label for="message"><?= $conteudoPagina['cta.label_mensagem'] ?? '' ?></label>
                                <!-- Textarea Mensagem -->
                                <textarea id="message" name="message" class="form-control" rows="4"
                                    placeholder="<?= $conteudoPagina['cta.placeholder_mensagem'] ?? '' ?>"
                                    maxlength="400"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                <div class="w-100 d-flex justify-content-end mt-1">
                                    <!-- Texto Contador -->
                                    <small class="text-secondary"
                                        id="message-char-count"><?= mb_strlen($_POST['message'] ?? '', 'UTF-8') ?> /
                                        400</small>
                                </div>
                            </div>
                            <!-- Botão Submeter -->
                            <button type="submit" class="btn btn-primary btn-large btn-glowing w-100 fw-700 gap-1"
                                id="cta-submit-btn" disabled>
                                <?= $conteudoPagina['cta.btn_submit'] ?>
                                <!-- SVG Seta -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="pa-footer">
        <div class="pa-page-container w-100 d-flex flex-column gap-10">
            <div class="d-flex gap-10 flex-column flex-md-row">
                <div class="d-flex flex-column gap-4 footer-col">
                    <div class="d-flex align-items-center gap-2">
                        <!-- SVG Atividade -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-activity-icon lucide-activity stroke-primary-500">
                            <path
                                d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            <path d="M3.22 13H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27" />
                        </svg>
                        <!-- Título Footer -->
                        <h1><?= $conteudoPagina['footer.brand_name'] ?></h1>
                    </div>
                    <!-- Subtítulo Footer -->
                    <h3 class="fw-400 text-secondary footer-description">
                        <?= $conteudoPagina['footer.description'] ?>
                    </h3>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <!-- SVG Localização -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-map-pin-icon lucide-map-pin stroke-primary-500">
                                <path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <!-- Texto Localização -->
                            <h3 class="fw-400 text-secondary"><?= $conteudoPagina['footer.location'] ?></h3>
                        </div>
                        <div class="d-flex gap-3">
                            <!-- SVG Email -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail stroke-primary-500">
                                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                            </svg>
                            <!-- Texto Email -->
                            <h3 class="fw-400 text-secondary"><?= $conteudoPagina['footer.email'] ?></h3>
                        </div>
                        <div class="d-flex gap-3">
                            <!-- SVG Telefone -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-phone-icon lucide-phone stroke-primary-500">
                                <path
                                    d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                            </svg>
                            <!-- Texto Telefone -->
                            <h3 class="fw-400 text-secondary"><?= $conteudoPagina['footer.phone'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-4 footer-col">
                    <!-- Título Acesso Rápido -->
                    <span class="fw-700 text-uppercase"><?= $conteudoPagina['footer.section_acesso_rapido'] ?></span>
                    <div class="d-flex flex-column gap-3">
                        <!-- Link Funcionalidades -->
                        <a href="#pa-features"
                            class="fw-400 decoration-none text-secondary"><?= $conteudoPagina['footer.link_funcionalidades'] ?></a>
                        <!-- Link Vantagens -->
                        <a href="#pa-advantages"
                            class="fw-400 decoration-none text-secondary"><?= $conteudoPagina['footer.link_vantagens'] ?></a>
                        <!-- Link Demo -->
                        <a href="#pa-cta"
                            class="fw-400 decoration-none text-secondary"><?= $conteudoPagina['footer.link_demo'] ?></a>
                    </div>
                </div>
                <div class="d-flex flex-column gap-4 footer-col">
                    <!-- Título Plataforma -->
                    <span class="fw-700 text-uppercase"><?= $conteudoPagina['footer.section_plataforma'] ?></span>
                    <div class="d-flex flex-column gap-3">
                        <!-- Link Backoffice -->
                        <a href="../private/login/login.php"
                            class="fw-400 decoration-none text-secondary d-flex align-items-center gap-2 text-primary-500"><?= $conteudoPagina['footer.link_backoffice'] ?>
                            <!-- SVG Seta Externa -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-square-arrow-out-up-right-icon lucide-square-arrow-out-up-right">
                                <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6" />
                                <path d="m21 3-9 9" />
                                <path d="M15 3h6v6" />
                            </svg>
                        </a>
                        <!-- Link Termos -->
                        <a href="#"
                            class="fw-400 decoration-none text-secondary"><?= $conteudoPagina['footer.link_termos'] ?></a>
                        <!-- Link Privacidade -->
                        <a href="#"
                            class="fw-400 decoration-none text-secondary"><?= $conteudoPagina['footer.link_privacidade'] ?></a>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row footer-copywright justify-content-between">
                <!-- Texto Copyright -->
                <p class="fw-400 text-secondary"><?= $conteudoPagina['footer.copyright'] ?></p>
                <!-- Texto Developer -->
                <p class="fw-400 text-secondary"><?= $conteudoPagina['footer.developer'] ?></p>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 9999;">
        <?php if (!empty($success_message)): ?>
            <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex align-items-center gap-2">
                    <!-- Texto Sucesso -->
                    <div class="toast-body fw-500 p-0">
                        <?= htmlspecialchars($success_message) ?>
                    </div>
                    <!-- Botão Fechar -->
                    <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                        aria-label="Close">
                        <!-- SVG Fechar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($validation_errors) || !empty($server_error)): ?>
            <?php
            $all_errors = [];
            if (!empty($validation_errors)) {
                $all_errors = array_merge($all_errors, $validation_errors);
            }
            if (!empty($server_error)) {
                $all_errors[] = $server_error;
            }
            ?>
            <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex align-items-center gap-2">
                    <!-- Texto Erro -->
                    <div class="toast-body fw-500 p-0 gap-2 d-flex flex-column">
                        <?php foreach ($all_errors as $error): ?>
                            <!-- Texto Mensagem de Erro -->
                            <p>
                                <?= htmlspecialchars($error) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                    <!-- Botão Fechar -->
                    <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                        aria-label="Close">
                        <!-- SVG Fechar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS e custom JS -->
    <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/1240961.js"></script>
</body>

</html>