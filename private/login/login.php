<?php

require_once(__DIR__ . "/../../config/funcoes.php");

start_session();

if (check_session()) {
    header('Location: ' . BASE_URL . 'private/index.php');
    exit;
}

$validation_errors = [];
if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}

$server_error = null;
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEBA - Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/1240961.css">
</head>

<body>
    <main class="login-page-container vh-100">
        <div class="d-flex vh-100">
            <button class="pa-theme-toggle" aria-label="Alternar tema">
                <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401" />
                </svg>
                <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <div
                class="col-6 d-flex flex-column align-items-start justify-content-center pa-informative-section padding-16 gap-8 overflow-hidden">
                <div class="background-decorations">
                    <div class="background-decoration-item background-decoration-1"></div>
                    <div class="background-decoration-item background-decoration-2"></div>
                    <div class="background-decoration-item background-decoration-3"></div>
                    <div class="background-decoration-item background-decoration-4"></div>
                </div>
                <div class="d-flex gap-4">
                    <div class="d-flex align-items-center justify-content-center svg-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-heart-pulse-icon lucide-heart-pulse stroke-white">
                            <path
                                d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            <path d="M3.22 13H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27" />
                        </svg>
                    </div>
                    <div class="d-flex flex-column">
                        <h1 class="text-white text-uppercase pa-informative-section-title">HEBA</h1>
                        <p class="text-uppercase pa-informative-section-subtitle">Health Base</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-6">
                    <h1 class="text-white pa-informative-section-header-title">Gestão Inteligente de <div
                            style="color: #54EAFD;">Inventário
                            Hospitalar</div>
                    </h1>
                    <h2 class="fw-400 pa-informative-section-header-description">
                        Plataforma integrada para gestão, monitorização e manutenção de equipamento biomédico hospitalar
                        com assistente de IA.
                    </h2>
                </div>
                <div class="d-flex flex-row flex-wrap gap-3">
                    <p class="badge fw-400">
                        Inventário em Tempo Real
                    </p>
                    <p class="badge fw-400">
                        Manutenção Preventiva
                    </p>
                    <p class="badge fw-400">
                        IA Integrada
                    </p>
                    <p class="badge fw-400">
                        Rastreabilidade
                    </p>

                </div>

            </div>
            <div class="col-6 d-flex flex-column justify-content-center padding-16 login-section">
                <div class="background-decorations">
                    <div class="background-decoration-item background-decoration-1"></div>
                    <div class="background-decoration-item background-decoration-2"></div>
                    <div class="background-decoration-item background-decoration-3"></div>
                    <div class="background-decoration-item background-decoration-4"></div>
                </div>
                <div class="d-flex align-self-center justify-content-center flex-column gap-8 login-section-content">
                    <div class="d-flex flex-column gap-1">
                        <h1>Bem-vindo de volta</h1>
                        <p class="text-secondary fw-400">Introduza as suas credenciais para aceder ao sistema.</p>
                    </div>
                    <form action="process_login.php" class="d-flex flex-column gap-6" method="POST" novalidate>
                        <div class="d-flex flex-column gap-4">
                            <div class="d-flex flex-column form-item">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="nome@hospital.pt" required>
                            </div>
                            <div class="d-flex flex-column form-item">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="********" required>
                            </div>
                        </div>

                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillLogin('admin@hospital.pt', 'admin123')">
                                    Admin
                                </button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillLogin('bioeng@hospital.pt', 'bioeng123')">
                                    Eng. Biomédico
                                </button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillLogin('tech@hospital.pt', 'tech123')">
                                    Técnico
                                </button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillLogin('medico@hospital.pt', 'medico123')">
                                    Médico
                                </button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillLogin('enfermeiro@hospital.pt', 'enfermeiro123')">
                                    Enfermeiro
                                </button>
                            </div>
                            <script>
                                function prefillLogin(email, password) {
                                    document.getElementById('email').value = email;
                                    document.getElementById('password').value = password;
                                }
                            </script>
                        <?php endif; ?>


                        <button type="submit" class="btn btn-primary btn-large btn-glowing fw-700 gap-1">
                            Entrar
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4 error-toast"
        style="z-index: 100;">
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
            <?php foreach ($all_errors as $error): ?>
                <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4" role="alert"
                    aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="d-flex align-items-center gap-2">
                        <div class="toast-body fw-500 p-0">
                            <?= htmlspecialchars($error) ?>
                        </div>
                        <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                            aria-label="Close">
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

    <!-- Bootstrap JS e custom JS -->
    <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/1240961.js"></script>
</body>

</html>