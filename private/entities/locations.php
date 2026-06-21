<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['locations.view']);
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

// Mensagens de sucesso ou erro no caso do POST
$success_message = null;
$server_error = null;

if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Obter todas as localizações da base de dados
$edificios = [];
try {
    $stmt = execute_query("
    SELECT 
      e.idEdificio, e.nome AS edificioNome,
      p.idPiso, p.nome AS pisoNome,
      s.idServico, s.nome AS servicoNome,
      l.idLocalizacao, l.nomeSala AS salaNome
    FROM Edificio e
    LEFT JOIN Piso p ON e.idEdificio = p.idEdificio AND p.ativo = 1
    LEFT JOIN Servico s ON p.idPiso = s.idPiso AND s.ativo = 1
    LEFT JOIN Localizacao l ON s.idServico = l.idServico AND l.ativo = 1
    WHERE e.ativo = 1
    ORDER BY e.idEdificio, p.idPiso, s.idServico, l.idLocalizacao;
    ", []);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $eId = (int) $row['idEdificio'];
        if (!isset($edificios[$eId])) {
            $edificios[$eId] = new Edificio($eId, $row['edificioNome']);
        }
        $edificio = $edificios[$eId];

        $pId = $row['idPiso'] !== null ? (int) $row['idPiso'] : null;
        if ($pId !== null) {
            $pisoEncontrado = null;
            foreach ($edificio->getPisos() as $piso) {
                if ($piso->getIdPiso() === $pId) {
                    $pisoEncontrado = $piso;
                    break;
                }
            }
            if (!$pisoEncontrado) {
                $pisoEncontrado = new Piso($pId, $eId, $row['pisoNome']);
                $edificio->addPiso($pisoEncontrado);
            }

            $sId = $row['idServico'] !== null ? (int) $row['idServico'] : null;
            if ($sId !== null) {
                $servicoEncontrado = null;
                foreach ($pisoEncontrado->getServicos() as $servico) {
                    if ($servico->getIdServico() === $sId) {
                        $servicoEncontrado = $servico;
                        break;
                    }
                }
                if (!$servicoEncontrado) {
                    $servicoEncontrado = new Servico($sId, $pId, $row['servicoNome']);
                    $pisoEncontrado->addServico($servicoEncontrado);
                }

                $lId = $row['idLocalizacao'] !== null ? (int) $row['idLocalizacao'] : null;
                if ($lId !== null) {
                    $salaEncontrada = null;
                    foreach ($servicoEncontrado->getSalas() as $sala) {
                        if ($sala->getIdLocalizacao() === $lId) {
                            $salaEncontrada = $sala;
                            break;
                        }
                    }
                    if (!$salaEncontrada) {
                        $salaEncontrada = new Localizacao($lId, $sId, $row['salaNome']);
                        $servicoEncontrado->addSala($salaEncontrada);
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar localizações: " . $e->getMessage();
}

?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Localizações</h1>
                <p class="text-secondary fw-400">Gestão de edifícios, pisos, serviços e salas</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (tem_permissao('locations.create')): ?>
                <button id="btn-open-create-building-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#equipment-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Novo Edifício
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
            <form action="" class="flex-grow-1">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input type="search" name="search" class="form-item w-100 search-bar-input" placeholder="Pesquisar edificios..."
                        value="<?= htmlspecialchars($search_query) ?>">
                </div>
            </form>
        </div>

        <?php if (empty($edificios)): ?>
            <div
                class="bento-card padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell-off-icon lucide-bell-off">
                        <path d="M9 10h.01" />
                        <path d="M15 10h.01" />
                        <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="fw-700 m-0">Sem Edifícios</h3>
                    <p class="text-secondary m-0">De momento não existe nenhum edifício.</p>
                </div>
            </div>
        <?php else: ?>
            <div id="locations-empty-state"
                class="bento-card padding-6 flex-column align-items-center justify-content-center text-center gap-4 py-5 d-none">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-x">
                        <path d="m13.5 8.5-5 5" />
                        <path d="m8.5 8.5 5 5" />
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="fw-700 m-0">Sem resultados</h3>
                    <p class="text-secondary m-0">Nenhum registo encontrado correspondente à sua pesquisa.</p>
                </div>
            </div>

            <!-- Localizacoes -->
            <div class="d-flex flex-column gap-4">
                <?php foreach ($edificios as $edificio): ?>
                    <?php
                    $eId = $edificio->getIdEdificio();
                    $encryptedEdificioId = aes_encrypt($eId);
                    $pisoCount = count($edificio->getPisos());
                    $servicoCount = 0;
                    foreach ($edificio->getPisos() as $p) {
                        $servicoCount += count($p->getServicos());
                    }
                    ?>
                    <!-- Card 1: Edifício -->
                    <div class="d-flex flex-column gap-3 locations">
                        <div class="card bento-card d-flex flex-column align-items-start overflow-hidden">
                            <div class="d-flex justify-content-between w-100 pe-4 align-items-center location-row building-row">
                                <div class="btn btn-link text-decoration-none mw-0 d-flex flex-grow-1 accordion-button top-level level-1 collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseBuilding_<?= htmlspecialchars($encryptedEdificioId) ?>"
                                    aria-expanded="false"
                                    aria-controls="collapseBuilding_<?= htmlspecialchars($encryptedEdificioId) ?>"
                                    style="cursor: pointer;">
                                    <div class="d-flex gap-3 align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg>
                                        <div class="table-icon-wrapper equipment-icon-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                                                <path d="M10 12h4"></path>
                                                <path d="M10 8h4"></path>
                                                <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                                <path
                                                    d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                                </path>
                                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column gap-half text-primary align-items-start">
                                            <p class="fw-700 text-decoration-none m-0">
                                                <?= htmlspecialchars($edificio->getNome()) ?>
                                            </p>
                                            <span class="visually-hidden"><?= htmlspecialchars($encryptedEdificioId) ?></span>
                                            <span class="text-secondary text-decoration-none"><?= $pisoCount ?> pisos •
                                                <?= $servicoCount ?> serviços</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 text-muted align-items-center action-buttons z-3 position-relative">
                                    <?php if (tem_permissao('locations.create')): ?>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#create-floor-modal-<?= htmlspecialchars($encryptedEdificioId) ?>"
                                        class="text-muted d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus padding-2">
                                            <path d="M5 12h14"></path>
                                            <path d="M12 5v14"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (tem_permissao('locations.edit')): ?>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#building-edit-modal-<?= htmlspecialchars($encryptedEdificioId) ?>"
                                        class="text-muted d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                            <path
                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                            </path>
                                            <path d="m15 5 4 4"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (tem_permissao('locations.delete')): ?>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#delete-confirm-modal-<?= htmlspecialchars($encryptedEdificioId) ?>"
                                        class="text-danger d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2 padding-2">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                            </path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div id="collapseBuilding_<?= htmlspecialchars($encryptedEdificioId) ?>" class="collapse w-100"
                                aria-labelledby="headingBuilding_<?= htmlspecialchars($encryptedEdificioId) ?>">
                                <div class="card-body p-0 d-flex flex-column gap-3 collapse-inner-level padding-bottom-4">

                                    <?php foreach ($edificio->getPisos() as $piso): ?>
                                        <?php
                                        $pId = $piso->getIdPiso();
                                        $encryptedPisoId = aes_encrypt($pId);
                                        $pisoServicoCount = count($piso->getServicos());
                                        ?>
                                        <!-- Level 2: Piso Accordion -->
                                        <div class="d-flex flex-column w-100">
                                            <div class="d-flex justify-content-between w-100 pe-4 align-items-center location-row">
                                                <div class="btn btn-link text-decoration-none mw-0 d-flex flex-grow-1 accordion-button level-2 collapsed"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseFloor_<?= htmlspecialchars($encryptedPisoId) ?>"
                                                    aria-expanded="false"
                                                    aria-controls="collapseFloor_<?= htmlspecialchars($encryptedPisoId) ?>"
                                                    style="cursor: pointer;">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                            <path d="m9 18 6-6-6-6"></path>
                                                        </svg>
                                                        <div class="table-icon-wrapper floor-icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-layers-icon lucide-layers">
                                                                <path
                                                                    d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z" />
                                                                <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65" />
                                                                <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65" />
                                                            </svg>
                                                        </div>
                                                        <div class="d-flex flex-column gap-half text-primary align-items-start">
                                                            <p class="fw-600 m-0">
                                                                <?= htmlspecialchars($piso->getNome()) ?>
                                                            </p>
                                                            <span
                                                                class="text-secondary text-decoration-none"><?= $pisoServicoCount ?>
                                                                serviços</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex gap-2 text-muted align-items-center action-buttons z-3 position-relative">
                                                    <?php if (tem_permissao('locations.create')): ?>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#create-service-modal-<?= htmlspecialchars($encryptedPisoId) ?>"
                                                        class="text-muted d-flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                            <path d="M5 12h14"></path>
                                                            <path d="M12 5v14"></path>
                                                        </svg>
                                                    </a>
                                                    <?php endif; ?>
                                                    <?php if (tem_permissao('locations.edit')): ?>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#edit-floor-modal-<?= htmlspecialchars($encryptedPisoId) ?>"
                                                        class="text-muted d-flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                    </a>
                                                    <?php endif; ?>
                                                    <?php if (tem_permissao('locations.delete')): ?>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#delete-floor-modal-<?= htmlspecialchars($encryptedPisoId) ?>"
                                                        class="text-danger d-flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                        </svg>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Level 2 Body: Collapse Piso -->
                                            <div id="collapseFloor_<?= htmlspecialchars($encryptedPisoId) ?>"
                                                class="w-100 collapse">
                                                <div class="d-flex flex-column gap-3 collapse-inner-level">

                                                    <?php foreach ($piso->getServicos() as $servico): ?>
                                                        <?php
                                                        $sId = $servico->getIdServico();
                                                        $encryptedServicoId = aes_encrypt($sId);
                                                        $salaCount = count($servico->getSalas());
                                                        ?>
                                                        <!-- Level 3: Serviço Accordion -->
                                                        <div class="d-flex flex-column w-100">
                                                            <div
                                                                class="d-flex justify-content-between w-100 pe-4 align-items-center location-row">
                                                                <div class="btn btn-link text-decoration-none mw-0 d-flex flex-grow-1 accordion-button level-3 collapsed"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseService_<?= htmlspecialchars($encryptedServicoId) ?>"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapseService_<?= htmlspecialchars($encryptedServicoId) ?>"
                                                                    style="cursor: pointer;">
                                                                    <div class="d-flex gap-3 align-items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="lucide lucide-chevron-right-icon lucide-chevron-right text-muted">
                                                                            <path d="m9 18 6-6-6-6"></path>
                                                                        </svg>
                                                                        <div class="table-icon-wrapper text-success">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                                class="lucide lucide-briefcase-icon lucide-briefcase">
                                                                                <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16">
                                                                                </path>
                                                                                <rect width="20" height="14" x="2" y="6" rx="2">
                                                                                </rect>
                                                                            </svg>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex flex-column gap-half text-primary align-items-start">
                                                                            <p class="fw-500 text-decoration-none m-0">
                                                                                <?= htmlspecialchars($servico->getNome()) ?>
                                                                            </p>
                                                                            <span
                                                                                class="text-secondary text-decoration-none"><?= $salaCount ?>
                                                                                salas</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="d-flex gap-2 text-muted align-items-center action-buttons z-3 position-relative">
                                                                    <?php if (tem_permissao('locations.create')): ?>
                                                                    <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#create-room-modal-<?= htmlspecialchars($encryptedServicoId) ?>"
                                                                        class="text-muted d-flex">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="lucide lucide-plus-icon lucide-plus padding-2">
                                                                            <path d="M5 12h14"></path>
                                                                            <path d="M12 5v14"></path>
                                                                        </svg>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                    <?php if (tem_permissao('locations.edit')): ?>
                                                                    <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#edit-service-modal-<?= htmlspecialchars($encryptedServicoId) ?>"
                                                                        class="text-muted d-flex">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                            <path
                                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                            </path>
                                                                            <path d="m15 5 4 4"></path>
                                                                        </svg>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                    <?php if (tem_permissao('locations.delete')): ?>
                                                                    <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#delete-service-modal-<?= htmlspecialchars($encryptedServicoId) ?>"
                                                                        class="text-danger d-flex">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="lucide lucide-trash2-icon lucide-trash-2 padding-2">
                                                                            <path d="M3 6h18"></path>
                                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                            </path>
                                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                                                        </svg>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <!-- Level 3 Body: Collapse Serviço (Rooms List) -->
                                                            <div id="collapseService_<?= htmlspecialchars($encryptedServicoId) ?>"
                                                                class="collapse w-100">
                                                                <div class="d-flex flex-column gap-2 collapse-inner-level">

                                                                    <?php foreach ($servico->getSalas() as $sala): ?>
                                                                        <?php
                                                                        $lId = $sala->getIdLocalizacao();
                                                                        $encryptedSalaId = aes_encrypt($lId);
                                                                        ?>
                                                                        <!-- Room / Localizacao -->
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center room-item w-100 level-4">
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <div class="table-icon-wrapper text-secondary">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                                        height="20" viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor" stroke-width="2"
                                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                                        class="lucide lucide-door-open-icon lucide-door-open">
                                                                                        <path d="M11 20H2" />
                                                                                        <path
                                                                                            d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                                                                                        <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                                                                                        <path d="M14 12h.01" />
                                                                                        <path d="M22 20h-3" />
                                                                                    </svg>
                                                                                </div>
                                                                                <span
                                                                                    class="fw-600 text-primary"><?= htmlspecialchars($sala->getNomeSala()) ?></span>
                                                                            </div>
                                                                            <div class="d-flex gap-2 text-muted align-items-center action-buttons"
                                                                                onclick="event.stopPropagation();">
                                                                                <?php if (tem_permissao('locations.edit')): ?>
                                                                                <a href="#" data-bs-toggle="modal"
                                                                                    data-bs-target="#edit-room-modal-<?= htmlspecialchars($encryptedSalaId) ?>"
                                                                                    class="text-muted d-flex">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                                        height="14" viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor" stroke-width="2"
                                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                                        class="lucide lucide-pencil-icon lucide-pencil padding-2">
                                                                                        <path
                                                                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                                        </path>
                                                                                        <path d="m15 5 4 4"></path>
                                                                                    </svg>
                                                                                </a>
                                                                                <?php endif; ?>
                                                                                <?php if (tem_permissao('locations.delete')): ?>
                                                                                <a href="#" data-bs-toggle="modal"
                                                                                    data-bs-target="#delete-room-modal-<?= htmlspecialchars($encryptedSalaId) ?>"
                                                                                    class="text-danger d-flex">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                                        height="14" viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor" stroke-width="2"
                                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                                        class="lucide lucide-trash2-icon lucide-trash-2 padding-2">
                                                                                        <path d="M3 6h18"></path>
                                                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                                        </path>
                                                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                                        </path>
                                                                                        <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                                        <line x1="14" x2="14" y1="11" y2="17"></line>
                                                                                    </svg>
                                                                                </a>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>

                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Edifício -->
<?php if (tem_permissao('locations.create')): ?>
<div class="modal fade" id="equipment-creation-modal" tabindex="-1" aria-labelledby="equipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="equipmentModalLabel">Criar
                        Edifício</h2>
                </div>

                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x stroke-secondary">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body do Modal com scroll automático -->
            <div class="modal-body p-0">
                <form id="building-creation-form" method="POST" action="locations-crud/create-building.php"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Nome do Edifício -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="building-name" class="d-flex gap-1 align-items-center">Nome
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </label>
                        <input type="text" id="building-name" name="building-name" placeholder="Nome do edifício..."
                            required>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btn-submit-building-modal" name="criar_edificio"
                            class="btn btn-primary btn-glowing gap-2" disabled="true">
                            Criar
                        </button>
                    </div>
                    <?php if (SHOW_DEBUG_BUTTONS): ?>
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                            <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                            <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'building-name': 'Edifício D'}); setTimeout(() => { document.getElementById('building-name').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Edifício D</button>
                            <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'building-name': 'Edifício E'}); setTimeout(() => { document.getElementById('building-name').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Edifício E</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php foreach ($edificios as $edificio): ?>
    <?php $encryptedEdificioId = aes_encrypt($edificio->getIdEdificio()); ?>

    <?php if (tem_permissao('locations.edit')): ?>
    <!-- Modal de Edição de Edifício para <?= htmlspecialchars($edificio->getNome()) ?> -->
    <div class="modal fade" id="building-edit-modal-<?= htmlspecialchars($encryptedEdificioId) ?>" tabindex="-1"
        aria-labelledby="buildingEditModalLabel-<?= htmlspecialchars($encryptedEdificioId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="buildingEditModalLabel-<?= htmlspecialchars($encryptedEdificioId) ?>">
                            Editar Edifício
                        </h2>
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
                    <form id="building-edit-form-<?= htmlspecialchars($encryptedEdificioId) ?>" method="POST"
                        action="locations-crud/edit-building.php"
                        class="building-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                        <input type="hidden" name="building-id" value="<?= htmlspecialchars($encryptedEdificioId) ?>">

                        <!-- Nome do Edifício -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1 align-items-center">
                                <label for="building-name-<?= htmlspecialchars($encryptedEdificioId) ?>">Nome
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </label>
                            </div>
                            <input type="text" id="building-name-<?= htmlspecialchars($encryptedEdificioId) ?>"
                                name="building-name" placeholder="Nome do edifício..." class="building-edit-name"
                                value="<?= htmlspecialchars($edificio->getNome()) ?>" required>
                        </div>

                        <!-- Footer do Formulario -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="editar_edificio"
                                class="btn-edit-building-submit btn btn-primary btn-glowing">
                                Guardar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (tem_permissao('locations.delete')): ?>
    <!-- Modal de Eliminação de Edifício para <?= htmlspecialchars($edificio->getNome()) ?> -->
    <div class="modal fade" id="delete-confirm-modal-<?= htmlspecialchars($encryptedEdificioId) ?>" tabindex="-1"
        aria-labelledby="deleteModalLabel-<?= htmlspecialchars($encryptedEdificioId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="deleteModalLabel-<?= htmlspecialchars($encryptedEdificioId) ?>">
                            Apagar Definitivamente</h2>
                        <span class="text-secondary fw-400">Esta ação não pode ser
                            revertida.</span>
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
                    <form method="POST" action="locations-crud/delete-building.php">
                        <input type="hidden" name="building-id" value="<?= htmlspecialchars($encryptedEdificioId) ?>">
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                        <path
                                            d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                        <path d="M12 9v4" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary">
                                            Tem a certeza que deseja apagar
                                            permanentemente o edifício e todo o seu conteúdo?
                                        </p>
                                        <h2 class="fw-700">
                                            "<?= htmlspecialchars($edificio->getNome()) ?>"
                                        </h2>
                                        <span class="text-muted">Tipo: Edifício</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botoes -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="apagar_edificio" class="btn btn-danger btn-glowing text-white">
                                    Sim, Apagar.
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php if (tem_permissao('locations.create')): ?>
<?php foreach ($edificios as $edificio): ?>
    <?php $encryptedEdificioId = aes_encrypt($edificio->getIdEdificio()); ?>

    <!-- Modal de Criação de Piso para <?= htmlspecialchars($edificio->getNome()) ?> -->
    <div class="modal fade" id="create-floor-modal-<?= htmlspecialchars($encryptedEdificioId) ?>" tabindex="-1"
        aria-labelledby="createFloorModalLabel-<?= htmlspecialchars($encryptedEdificioId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="createFloorModalLabel-<?= htmlspecialchars($encryptedEdificioId) ?>">
                            Criar Piso
                        </h2>
                        <span class="text-secondary fw-400"><?= htmlspecialchars($edificio->getNome()) ?></span>
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
                    <form method="POST" action="locations-crud/create-floor.php"
                        class="floor-create-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                        <input type="hidden" name="building-id" value="<?= htmlspecialchars($encryptedEdificioId) ?>">

                        <!-- Nome do Piso -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="floor-name-create-<?= htmlspecialchars($encryptedEdificioId) ?>"
                                class="d-flex gap-1 align-items-center">Nome
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </label>
                            <input type="text" id="floor-name-create-<?= htmlspecialchars($encryptedEdificioId) ?>"
                                name="floor-name" placeholder="Nome do piso..." class="floor-create-name" required>
                        </div>

                        <!-- Footer do Formulario -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="criar_piso"
                                class="btn-create-floor-submit btn btn-primary btn-glowing gap-2" disabled="true">
                                Criar
                            </button>
                        </div>
                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'floor-name-create-<?= htmlspecialchars($encryptedEdificioId) ?>': 'Piso 1'}); setTimeout(() => { document.getElementById('floor-name-create-<?= htmlspecialchars($encryptedEdificioId) ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Piso 1</button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'floor-name-create-<?= htmlspecialchars($encryptedEdificioId) ?>': 'Piso 2'}); setTimeout(() => { document.getElementById('floor-name-create-<?= htmlspecialchars($encryptedEdificioId) ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Piso 2</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>

<?php foreach ($edificios as $edificio): ?>
    <?php foreach ($edificio->getPisos() as $piso): ?>
        <?php
        $encryptedPisoId = aes_encrypt($piso->getIdPiso());
        ?>

        <?php if (tem_permissao('locations.edit')): ?>
        <!-- Modal de Edição de Piso para <?= htmlspecialchars($piso->getNome()) ?> -->
        <div class="modal fade" id="edit-floor-modal-<?= htmlspecialchars($encryptedPisoId) ?>" tabindex="-1"
            aria-labelledby="editFloorModalLabel-<?= htmlspecialchars($encryptedPisoId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="editFloorModalLabel-<?= htmlspecialchars($encryptedPisoId) ?>">
                                Editar Piso
                            </h2>
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
                        <form id="floor-edit-form-<?= htmlspecialchars($encryptedPisoId) ?>" method="POST"
                            action="locations-crud/edit-floor.php"
                            class="floor-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                            <input type="hidden" name="floor-id" value="<?= htmlspecialchars($encryptedPisoId) ?>">

                            <!-- Nome do Piso -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="floor-name-<?= htmlspecialchars($encryptedPisoId) ?>">Nome
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </label>
                                </div>
                                <input type="text" id="floor-name-<?= htmlspecialchars($encryptedPisoId) ?>" name="floor-name"
                                    placeholder="Nome do piso..." class="floor-edit-name"
                                    value="<?= htmlspecialchars($piso->getNome()) ?>" required>
                            </div>

                            <!-- Footer do Formulario -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="editar_piso"
                                    class="btn-edit-floor-submit btn btn-primary btn-glowing">
                                    Guardar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (tem_permissao('locations.delete')): ?>
        <!-- Modal de Eliminação de Piso para <?= htmlspecialchars($piso->getNome()) ?> -->
        <div class="modal fade" id="delete-floor-modal-<?= htmlspecialchars($encryptedPisoId) ?>" tabindex="-1"
            aria-labelledby="deleteFloorModalLabel-<?= htmlspecialchars($encryptedPisoId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="deleteFloorModalLabel-<?= htmlspecialchars($encryptedPisoId) ?>">
                                Apagar Definitivamente</h2>
                            <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
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
                        <form method="POST" action="locations-crud/delete-floor.php">
                            <input type="hidden" name="floor-id" value="<?= htmlspecialchars($encryptedPisoId) ?>">
                            <div
                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                                <div class="d-flex flex-column align-items-center gap-4">
                                    <div class="d-flex padding-3 danger-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                            <path
                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                            <path d="M12 9v4" />
                                            <path d="M12 17h.01" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                            <p class="text-secondary">
                                                Tem a certeza que deseja apagar permanentemente o piso e todo o seu
                                                conteúdo?
                                            </p>
                                            <h2 class="fw-700">
                                                "<?= htmlspecialchars($piso->getNome()) ?>"
                                            </h2>
                                            <span class="text-muted">Tipo: Piso</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botoes -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="apagar_piso" class="btn btn-danger btn-glowing text-white">
                                        Sim, Apagar.
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php foreach ($edificios as $edificio): ?>
    <?php foreach ($edificio->getPisos() as $piso): ?>
        <?php $encryptedPisoId = aes_encrypt($piso->getIdPiso()); ?>

        <?php if (tem_permissao('locations.create')): ?>
        <!-- Modal de Criação de Serviço para <?= htmlspecialchars($piso->getNome()) ?> -->
        <div class="modal fade" id="create-service-modal-<?= htmlspecialchars($encryptedPisoId) ?>" tabindex="-1"
            aria-labelledby="createServiceModalLabel-<?= htmlspecialchars($encryptedPisoId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="createServiceModalLabel-<?= htmlspecialchars($encryptedPisoId) ?>">
                                Criar Serviço
                            </h2>
                            <span class="text-secondary fw-400"><?= htmlspecialchars($piso->getNome()) ?></span>
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
                    <div class="modal-body p-0">
                        <form method="POST" action="locations-crud/create-service.php"
                            class="service-create-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                            <input type="hidden" name="floor-id" value="<?= htmlspecialchars($encryptedPisoId) ?>">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <label for="service-name-create-<?= htmlspecialchars($encryptedPisoId) ?>"
                                    class="d-flex gap-1 align-items-center">Nome
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </label>
                                <input type="text" id="service-name-create-<?= htmlspecialchars($encryptedPisoId) ?>"
                                    name="service-name" placeholder="Nome do serviço..." class="service-create-name" required>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="criar_servico"
                                    class="btn-create-service-submit btn btn-primary btn-glowing gap-2" disabled="true">
                                    Criar
                                </button>
                            </div>
                            <?php if (SHOW_DEBUG_BUTTONS): ?>
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                    <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'service-name-create-<?= htmlspecialchars($encryptedPisoId) ?>': 'Informática'}); setTimeout(() => { document.getElementById('service-name-create-<?= htmlspecialchars($encryptedPisoId) ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Informática</button>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'service-name-create-<?= htmlspecialchars($encryptedPisoId) ?>': 'Recursos Humanos'}); setTimeout(() => { document.getElementById('service-name-create-<?= htmlspecialchars($encryptedPisoId) ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">RH</button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php foreach ($piso->getServicos() as $servico): ?>
            <?php $encryptedServicoId = aes_encrypt($servico->getIdServico()); ?>

            <?php if (tem_permissao('locations.edit')): ?>
            <!-- Modal de Edição de Serviço para <?= htmlspecialchars($servico->getNome()) ?> -->
            <div class="modal fade" id="edit-service-modal-<?= htmlspecialchars($encryptedServicoId) ?>" tabindex="-1"
                aria-labelledby="editServiceModalLabel-<?= htmlspecialchars($encryptedServicoId) ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                    <div class="modal-content custom-modal-content d-flex flex-column">
                        <div
                            class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                            <div class="d-flex flex-column">
                                <h2 class="equipment-creation-modal-title modal-title"
                                    id="editServiceModalLabel-<?= htmlspecialchars($encryptedServicoId) ?>">
                                    Editar Serviço
                                </h2>
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
                        <div class="modal-body p-0">
                            <form id="service-edit-form-<?= htmlspecialchars($encryptedServicoId) ?>" method="POST"
                                action="locations-crud/edit-service.php"
                                class="service-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                                <input type="hidden" name="service-id" value="<?= htmlspecialchars($encryptedServicoId) ?>">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1 align-items-center">
                                        <label for="service-name-<?= htmlspecialchars($encryptedServicoId) ?>">Nome
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                <path d="M12 6v12" />
                                                <path d="M17.196 9 6.804 15" />
                                                <path d="m6.804 9 10.392 6" />
                                            </svg>
                                        </label>
                                    </div>
                                    <input type="text" id="service-name-<?= htmlspecialchars($encryptedServicoId) ?>"
                                        name="service-name" placeholder="Nome do serviço..." class="service-edit-name"
                                        value="<?= htmlspecialchars($servico->getNome()) ?>" required>
                                </div>
                                <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="editar_servico"
                                        class="btn-edit-service-submit btn btn-primary btn-glowing">
                                        Guardar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (tem_permissao('locations.delete')): ?>
            <!-- Modal de Eliminação de Serviço para <?= htmlspecialchars($servico->getNome()) ?> -->
            <div class="modal fade" id="delete-service-modal-<?= htmlspecialchars($encryptedServicoId) ?>" tabindex="-1"
                aria-labelledby="deleteServiceModalLabel-<?= htmlspecialchars($encryptedServicoId) ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                    <div class="modal-content custom-modal-content d-flex flex-column">
                        <div
                            class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                            <div class="d-flex flex-column">
                                <h2 class="equipment-creation-modal-title modal-title"
                                    id="deleteServiceModalLabel-<?= htmlspecialchars($encryptedServicoId) ?>">
                                    Apagar Definitivamente</h2>
                                <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
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
                        <div class="modal-body p-0">
                            <form method="POST" action="locations-crud/delete-service.php">
                                <input type="hidden" name="service-id" value="<?= htmlspecialchars($encryptedServicoId) ?>">
                                <div
                                    class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                    <div class="d-flex flex-column align-items-center gap-4">
                                        <div class="d-flex padding-3 danger-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                                <path
                                                    d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                                <path d="M12 9v4" />
                                                <path d="M12 17h.01" />
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                            <p class="text-secondary">Tem a certeza que deseja apagar permanentemente o
                                                serviço e todo o seu conteúdo?</p>
                                            <h2 class="fw-700">"<?= htmlspecialchars($servico->getNome()) ?>"</h2>
                                            <span class="text-muted">Tipo: Serviço</span>
                                        </div>
                                    </div>
                                    <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="apagar_servico"
                                            class="btn btn-danger btn-glowing text-white">Sim, Apagar.</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (tem_permissao('locations.create')): ?>
            <!-- Modal de Criação de Sala para <?= htmlspecialchars($servico->getNome()) ?> -->
            <div class="modal fade" id="create-room-modal-<?= htmlspecialchars($encryptedServicoId) ?>" tabindex="-1"
                aria-labelledby="createRoomModalLabel-<?= htmlspecialchars($encryptedServicoId) ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                    <div class="modal-content custom-modal-content d-flex flex-column">
                        <div
                            class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                            <div class="d-flex flex-column">
                                <h2 class="equipment-creation-modal-title modal-title"
                                    id="createRoomModalLabel-<?= htmlspecialchars($encryptedServicoId) ?>">
                                    Criar Sala
                                </h2>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($servico->getNome()) ?></span>
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
                        <div class="modal-body p-0">
                            <form method="POST" action="locations-crud/create-room.php"
                                class="room-create-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                                <input type="hidden" name="service-id" value="<?= htmlspecialchars($encryptedServicoId) ?>">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <label for="room-name-create-<?= htmlspecialchars($encryptedServicoId) ?>"
                                        class="d-flex gap-1 align-items-center">Nome
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </label>
                                    <input type="text" id="room-name-create-<?= htmlspecialchars($encryptedServicoId) ?>"
                                        name="room-name" placeholder="Nome da sala..." class="room-create-name" required>
                                </div>
                                <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="criar_sala"
                                        class="btn-create-room-submit btn btn-primary btn-glowing gap-2" disabled="true">
                                        Criar
                                    </button>
                                </div>
                                <?php if (SHOW_DEBUG_BUTTONS): ?>
                                    <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                        <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                                        <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'room-name-create-<?= htmlspecialchars($encryptedServicoId) ?>': 'Sala 101'}); setTimeout(() => { document.getElementById('room-name-create-<?= htmlspecialchars($encryptedServicoId) ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Sala 101</button>
                                        <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'room-name-create-<?= htmlspecialchars($encryptedServicoId) ?>': 'Sala 102'}); setTimeout(() => { document.getElementById('room-name-create-<?= htmlspecialchars($encryptedServicoId) ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Sala 102</button>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php foreach ($servico->getSalas() as $sala): ?>
                <?php $encryptedSalaId = aes_encrypt($sala->getIdLocalizacao()); ?>

                <?php if (tem_permissao('locations.edit')): ?>
                <!-- Modal de Edição de Sala para <?= htmlspecialchars($sala->getNomeSala()) ?> -->
                <div class="modal fade" id="edit-room-modal-<?= htmlspecialchars($encryptedSalaId) ?>" tabindex="-1"
                    aria-labelledby="editRoomModalLabel-<?= htmlspecialchars($encryptedSalaId) ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                        <div class="modal-content custom-modal-content d-flex flex-column">
                            <div
                                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                                <div class="d-flex flex-column">
                                    <h2 class="equipment-creation-modal-title modal-title"
                                        id="editRoomModalLabel-<?= htmlspecialchars($encryptedSalaId) ?>">
                                        Editar Sala
                                    </h2>
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
                            <div class="modal-body p-0">
                                <form id="room-edit-form-<?= htmlspecialchars($encryptedSalaId) ?>" method="POST"
                                    action="locations-crud/edit-room.php"
                                    class="room-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                                    <input type="hidden" name="room-id" value="<?= htmlspecialchars($encryptedSalaId) ?>">
                                    <div class="d-flex flex-column form-item w-100 mw-0">
                                        <div class="d-flex gap-1 align-items-center">
                                            <label for="room-name-<?= htmlspecialchars($encryptedSalaId) ?>">Nome
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                                    <path d="M12 6v12" />
                                                    <path d="M17.196 9 6.804 15" />
                                                    <path d="m6.804 9 10.392 6" />
                                                </svg>
                                            </label>
                                        </div>
                                        <input type="text" id="room-name-<?= htmlspecialchars($encryptedSalaId) ?>" name="room-name"
                                            placeholder="Nome da sala..." class="room-edit-name"
                                            value="<?= htmlspecialchars($sala->getNomeSala()) ?>" required>
                                    </div>
                                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="editar_sala"
                                            class="btn-edit-room-submit btn btn-primary btn-glowing">
                                            Guardar Alterações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (tem_permissao('locations.delete')): ?>
                <!-- Modal de Eliminação de Sala para <?= htmlspecialchars($sala->getNomeSala()) ?> -->
                <div class="modal fade" id="delete-room-modal-<?= htmlspecialchars($encryptedSalaId) ?>" tabindex="-1"
                    aria-labelledby="deleteRoomModalLabel-<?= htmlspecialchars($encryptedSalaId) ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                        <div class="modal-content custom-modal-content d-flex flex-column">
                            <div
                                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                                <div class="d-flex flex-column">
                                    <h2 class="equipment-creation-modal-title modal-title"
                                        id="deleteRoomModalLabel-<?= htmlspecialchars($encryptedSalaId) ?>">
                                        Apagar Definitivamente</h2>
                                    <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
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
                            <div class="modal-body p-0">
                                <form method="POST" action="locations-crud/delete-room.php">
                                    <input type="hidden" name="room-id" value="<?= htmlspecialchars($encryptedSalaId) ?>">
                                    <div
                                        class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                        <div class="d-flex flex-column align-items-center gap-4">
                                            <div class="d-flex padding-3 danger-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                                    <path
                                                        d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                                    <path d="M12 9v4" />
                                                    <path d="M12 17h.01" />
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                                <p class="text-secondary">Tem a certeza que deseja apagar
                                                    permanentemente esta sala?</p>
                                                <h2 class="fw-700">"<?= htmlspecialchars($sala->getNomeSala()) ?>"</h2>
                                                <span class="text-muted">Tipo: Sala</span>
                                            </div>
                                        </div>
                                        <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" name="apagar_sala" class="btn btn-danger btn-glowing text-white">Sim,
                                                Apagar.</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- Toast Container -->

<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 100;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
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
    <?php endif; ?>

    <?php if (!empty($server_error)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($server_error) ?>
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
    <?php endif; ?>
</div>


<?php
include_once BASE_PATH . 'private/includes/footer.php';
?>