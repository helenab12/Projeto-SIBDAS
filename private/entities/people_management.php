<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['view.pessoas']);

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
$role_filter = isset($_GET['role']) ? trim($_GET['role']) : '';

$listaPessoas = [];
try {
    $ligacao = connect_to_db();
    $stmt = execute_query("SELECT * FROM Pessoa WHERE ativo = 1 ORDER BY nome ASC", [], $ligacao);
    $pessoasDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($pessoasDb as $row) {
        $listaPessoas[] = new Pessoa(
            (string) $row['idPessoa'],
            (string) $row['nome'],
            (string) $row['email'],
            (string) $row['contactoTelefonico'],
            (string) $row['nif'],
            $row['funcao'] ? Funcao::tryFrom((string) $row['funcao']) : null,
            $row['departamento'] ? (string) $row['departamento'] : null,
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            $row['dataAtualizacao'] ? new DateTime($row['dataAtualizacao']) : new DateTime()
        );
    }
    $ligacao = null;
} catch (Exception $e) {
    error_log("Erro ao carregar pessoas: " . $e->getMessage());
    $_SESSION['server_error'] = "Não foi possível carregar a lista de pessoas.";
}

include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="padding-6 gap-6 d-flex flex-column padding-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
            <div class="d-flex flex-column gap-1">
                <h1>Gestão de Pessoas</h1>
                <p class="text-secondary fw-400"><?= count($listaPessoas) ?> pessoas ativas</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (tem_permissao('people.create')): ?>
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
                <?php endif; ?>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div
            class="bento-card padding-4 gap-4 d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center w-100 equipment-list-search-bar">
            <form action="" method="GET" style="display: contents;">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="search" name="search" class="form-item w-100 search-bar-input person-search-input"
                        placeholder="Pesquisar por nome ou email..." value="<?= htmlspecialchars($search_query) ?>">
                </div>
                <div class="d-flex gap-2 equipment-list-search-bar-filters flex-column flex-md-row">
                    <select class="form-select" name="role" aria-label="Filtro Função" id="filter-role"
                        onchange="this.form.submit()">
                        <option value="" <?= $role_filter === '' ? 'selected' : '' ?>>Todas as Funções</option>
                        <?php foreach (Funcao::cases() as $funcao): ?>
                            <option value="<?= $funcao->value ?>" <?= $role_filter === $funcao->value ? 'selected' : '' ?>>
                                <?= $funcao->value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if (empty($listaPessoas)): ?>
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
                    <h3 class="fw-700 m-0">Sem Pessoas</h3>
                    <p class="text-secondary m-0">De momento não existe nenhuma pessoa.</p>
                </div>
            </div>
        <?php else: ?>
            <div id="people-empty-state"
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

            <!-- Conteudo -->
            <div class="bento-grid people-management gap-4">
                <?php foreach ($listaPessoas as $pessoa): ?>
                    <?php

                    $initials = get_user_initials($pessoa->getNome());

                    $colors = ['pink', 'cyan', 'blue', 'yellow', 'green', 'purple'];
                    $colorIndex = array_search($pessoa, $listaPessoas);
                    $color = $colors[$colorIndex % count($colors)];

                    ?>
                    <!-- Card Pessoa -->
                    <div class="bento-card padding-6 d-flex flex-column gap-6">
                        <!-- Row 1: Nome -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-3 align-items-center">
                                <p
                                    class="user-icon d-flex align-items-center justify-content-center fw-700 text-white <?= $color ?>">
                                    <?= htmlspecialchars($initials) ?>
                                </p>
                                <div class="d-flex flex-column gap-half">
                                    <p class="fw-700 person-name"><?= htmlspecialchars($pessoa->getNome()) ?></p>
                                    <span class="visually-hidden"><?= htmlspecialchars(aes_encrypt($pessoa->getId())) ?></span>
                                    <span
                                        class="text-secondary person-role"><?= htmlspecialchars($pessoa->getFuncao()?->value ?? 'Sem Função') ?></span>
                                </div>
                            </div>
                            <?php if (tem_permissao('people.edit') || tem_permissao('people.delete')): ?>
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
                                    <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu padding-2">
                                        <?php if (tem_permissao('people.edit')): ?>
                                            <li>
                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-primary"
                                                    href="#" data-bs-toggle="modal"
                                                    data-bs-target="#person-edit-modal-<?= htmlspecialchars(aes_encrypt($pessoa->getId())) ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                        <path d="m15 5 4 4" />
                                                    </svg>
                                                    Editar
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (tem_permissao('people.delete')): ?>
                                            <li>
                                                <a class="dropdown-item action-dropdown-item d-flex align-items-center gap-2 padding-3 fw-500 decoration-none  text-error"
                                                    href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete-confirm-modal-<?= htmlspecialchars(aes_encrypt($pessoa->getId())) ?>">
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
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
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
                                <span
                                    class="fw-400"><?= htmlspecialchars($pessoa->getDepartamento() ?? 'Sem Departamento') ?></span>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-mail-icon lucide-mail">
                                    <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                </svg>
                                <span class="fw-400 person-email"><?= htmlspecialchars($pessoa->getEmail()) ?></span>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-phone-icon lucide-phone">
                                    <path
                                        d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                                </svg>
                                <span class="fw-400"><?= htmlspecialchars($pessoa->getContactoTelefonico()) ?></span>
                            </div>
                        </div>

                        <!-- Row 3: Detalhes Adicionais -->
                        <div class="d-flex justify-content-between align-items-center text-muted additional-details">
                            <span class="text-uppercase font-mono">NIF: <?= htmlspecialchars($pessoa->getNif()) ?></span>
                            <div class="d-flex gap-1 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-calendar-icon lucide-calendar">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <path d="M3 10h18" />
                                </svg>
                                <span>Desde <?= htmlspecialchars($pessoa->getDataCriacao()->format('m/Y')) ?></span>
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

<!-- Modal de Criação de Pessoa -->
<?php if (tem_permissao('people.create')): ?>
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
                    <form id="person-creation-form" method="POST" action="people-crud/create-person.php"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                        <!-- Row 1: Nome Completo e NIF -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="person-name">Nome Completo</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="person-name" name="person-name" placeholder="Ex: Dr. Manuel Costa"
                                    required>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="person-nif">NIF</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="person-nif" name="person-nif" placeholder="Ex: 123456789" required>
                            </div>
                        </div>

                        <!-- Row 2: Função e Departamento -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="person-role">Função</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <select class="form-select" id="person-role" name="person-role" required>
                                    <option value="" disabled selected>Selecione uma Função</option>
                                    <?php foreach (Funcao::cases() as $funcao): ?>
                                        <option value="<?= $funcao->value ?>"><?= $funcao->value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="person-department">Departamento</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="person-department" name="person-department"
                                    placeholder="Ex: Servicio de Eng. Biomédica" required>
                            </div>
                        </div>

                        <!-- Row 3: Email e Telefone -->
                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="person-email">Email</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="email" id="person-email" name="person-email" placeholder="email@hospital.pt"
                                    required>
                            </div>

                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1 align-items-center">
                                    <label for="person-phone">Telefone</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="person-phone" name="person-phone" placeholder="+351 9XX XXX XXX"
                                    required>
                            </div>
                        </div>

                        <!-- Footer do Formulario -->
                        <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="criar_pessoa" id="btn-submit-modal"
                                class="btn btn-primary btn-glowing" disabled>
                                Criar Pessoa
                            </button>
                        </div>
                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                    (Debug)</span>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'person-name': 'Maria Inês Ribeiro', 'person-nif': '123456789', 'person-email': 'maria.ribeiro@hospital.pt', 'person-phone': '912345678', 'person-role': 'Médico', 'person-department': 'Urgência'})">Maria
                                    (Médica)</button>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'person-name': 'Tiago Faria Silva', 'person-nif': '987654321', 'person-email': 'tiago.silva@hospital.pt', 'person-phone': '913456789', 'person-role': 'Enfermeiro', 'person-department': 'Urgência'})">Tiago
                                    (Enfermeiro)</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($listaPessoas as $pessoa): ?>
    <?php $encryptedPersonId = aes_encrypt($pessoa->getId()); ?>

    <?php if (tem_permissao('people.edit')): ?>
        <!-- Modal de Edição de Pessoa para <?= htmlspecialchars($pessoa->getNome()) ?> -->
        <div class="modal fade" id="person-edit-modal-<?= htmlspecialchars($encryptedPersonId) ?>" tabindex="-1"
            aria-labelledby="personEditModalLabel-<?= htmlspecialchars($encryptedPersonId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="personEditModalLabel-<?= htmlspecialchars($encryptedPersonId) ?>">
                                Editar Pessoa
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
                        <form id="person-edit-form-<?= htmlspecialchars($encryptedPersonId) ?>" method="POST"
                            action="people-crud/edit-person.php"
                            class="person-edit-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                            <input type="hidden" name="person-id" value="<?= htmlspecialchars($encryptedPersonId) ?>">

                            <!-- Row 1: Nome Completo e NIF -->
                            <div class="d-flex w-100 gap-4">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="edit-person-name-<?= htmlspecialchars($encryptedPersonId) ?>">Nome
                                            Completo</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="text" id="edit-person-name-<?= htmlspecialchars($encryptedPersonId) ?>"
                                        name="person-name" value="<?= htmlspecialchars($pessoa->getNome()) ?>" required>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="edit-person-nif-<?= htmlspecialchars($encryptedPersonId) ?>">NIF</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="text" id="edit-person-nif-<?= htmlspecialchars($encryptedPersonId) ?>"
                                        name="person-nif" value="<?= htmlspecialchars($pessoa->getNif()) ?>" required>
                                </div>
                            </div>

                            <!-- Row 2: Função e Departamento -->
                            <div class="d-flex w-100 gap-4">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="edit-person-role-<?= htmlspecialchars($encryptedPersonId) ?>">Função</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <select class="form-select"
                                        id="edit-person-role-<?= htmlspecialchars($encryptedPersonId) ?>" name="person-role"
                                        required>
                                        <option value="" disabled>Selecione uma Função</option>
                                        <?php foreach (Funcao::cases() as $funcao): ?>
                                            <option value="<?= $funcao->value ?>" <?= $pessoa->getFuncao() === $funcao ? 'selected' : '' ?>><?= $funcao->value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label
                                            for="edit-person-department-<?= htmlspecialchars($encryptedPersonId) ?>">Departamento</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="text" id="edit-person-department-<?= htmlspecialchars($encryptedPersonId) ?>"
                                        name="person-department" value="<?= htmlspecialchars($pessoa->getDepartamento()) ?>"
                                        required>
                                </div>
                            </div>

                            <!-- Row 3: Email e Telefone -->
                            <div class="d-flex w-100 gap-4">
                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label for="edit-person-email-<?= htmlspecialchars($encryptedPersonId) ?>">Email</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="email" id="edit-person-email-<?= htmlspecialchars($encryptedPersonId) ?>"
                                        name="person-email" value="<?= htmlspecialchars($pessoa->getEmail()) ?>" required>
                                </div>

                                <div class="d-flex flex-column form-item w-100 mw-0">
                                    <div class="d-flex gap-1">
                                        <label
                                            for="edit-person-phone-<?= htmlspecialchars($encryptedPersonId) ?>">Telefone</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </div>
                                    <input type="text" id="edit-person-phone-<?= htmlspecialchars($encryptedPersonId) ?>"
                                        name="person-phone" value="<?= htmlspecialchars($pessoa->getContactoTelefonico()) ?>"
                                        required>
                                </div>
                            </div>

                            <!-- Footer do Formulario -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="editar_pessoa" class="btn-edit-submit btn btn-primary btn-glowing">
                                    Guardar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (tem_permissao('people.delete')): ?>
        <!-- Modal de Eliminação de Pessoa para <?= htmlspecialchars($pessoa->getNome()) ?> -->
        <div class="modal fade" id="delete-confirm-modal-<?= htmlspecialchars($encryptedPersonId) ?>" tabindex="-1"
            aria-labelledby="deleteModalLabel-<?= htmlspecialchars($encryptedPersonId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="deleteModalLabel-<?= htmlspecialchars($encryptedPersonId) ?>">
                                Mover para Reciclagem</h2>
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
                        <form method="POST" action="people-crud/delete-person.php">
                            <input type="hidden" name="person-id" value="<?= htmlspecialchars($encryptedPersonId) ?>">
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
                                                esta pessoa?
                                            </p>
                                            <h2 class="fw-700">
                                                <?= htmlspecialchars($pessoa->getNome()) ?>
                                            </h2>
                                            <span class="text-muted">NIF: <?= htmlspecialchars($pessoa->getNif()) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botoes -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="apagar_pessoa" class="btn btn-danger btn-glowing text-white">
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