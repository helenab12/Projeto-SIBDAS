<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

$EstadoPedidoDemonstracaos = [
    'Novo' => new EstadoPedidoDemonstracao('Novo', 'new'),
    'Em Contacto' => new EstadoPedidoDemonstracao('Em Contacto', 'in-contact'),
    'Fechado' => new EstadoPedidoDemonstracao('Fechado', 'concluded')
];

$PedidoDemonstracaos = [];
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

$success_message = null;
if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $stmt = execute_query("SELECT * FROM PedidoDemonstracao WHERE ativo = 1 ORDER BY dataCriacao DESC");
    $requests = $stmt->fetchAll(PDO::FETCH_OBJ);

    $months = [
        'Jan',
        'Fev',
        'Mar',
        'Abr',
        'Mai',
        'Jun',
        'Jul',
        'Ago',
        'Set',
        'Out',
        'Nov',
        'Dez'
    ];

    foreach ($requests as $row) {
        $estadoStr = $row->estado;
        $stateObj = $EstadoPedidoDemonstracaos[$estadoStr] ?? new EstadoPedidoDemonstracao($estadoStr, 'new');

        $dateObj = new DateTime($row->dataCriacao);
        $day = $dateObj->format('d');
        $mNum = (int) $dateObj->format('n');
        $year = $dateObj->format('Y');
        $time = $dateObj->format('H:i');
        $formattedDate = "{$day} {$months[$mNum]} {$year}, {$time}";

        $PedidoDemonstracaos[] = new PedidoDemonstracao(
            (int) $row->idPedido,
            $stateObj,
            $formattedDate,
            $row->nomeContacto,
            $row->organizacao ?? '',
            $row->emailContacto,
            $row->mensagem ?? ''
        );
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar dados do servidor: " . $e->getMessage();
}

?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <form method="POST" action="inbox-crud/update-inbox.php" class="d-flex flex-column flex-grow-1 mw-0">
        <section class="content-container inbox flex-grow-1">
            <div class="d-flex flex-column padding-6 gap-6 flex-grow-1">
                <!-- Titulo -->
                <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
                    <div class="d-flex flex-column gap-1">
                        <h1>Caixa de Entrada</h1>
                        <p class="text-secondary fw-400">Gestão dos pedidos de demonstração do Website.</p>
                    </div>
                </div>

                <!-- Barra de Pesquisa -->
                <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
                    <div class="flex-grow-1">
                        <div class="form-item w-100 position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                            <input type="text" class="form-item w-100 search-bar-input"
                                placeholder="Pesquisar por nome ou organização..." value="<?= htmlspecialchars($search_query) ?>">
                        </div>
                    </div>
                </div>

                <!-- Tabela -->
                <div class="bento-card w-100 p-0 border-0">
                    <table id="equipmentsTable" class="sibdas-table w-100 display">
                        <thead>
                            <tr>
                                <th>ESTADO</th>
                                <th>DATA</th>
                                <th>NOME CONTACTO</th>
                                <th>INSTITUIÇÃO</th>
                                <th>EMAIL</th>
                                <th class="text-end">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($PedidoDemonstracaos as $request):
                                $encryptedId = aes_encrypt($request->id);
                                ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="states[<?php echo $encryptedId; ?>]"
                                            value="<?php echo $request->state->name; ?>"
                                            id="inbox-state-input-<?php echo $encryptedId; ?>">
                                        <div class="dropdown">
                                            <button id="inbox-state-btn-<?php echo $encryptedId; ?>"
                                                class="d-inline-flex align-items-center equipment-badge <?php echo $request->state->class; ?> gap-1 mw-0 border-0"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span><?php echo $request->state->name; ?></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                                    <path d="m6 9 6 6 6-6" />
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu action-dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item action-dropdown-item" href="#"
                                                        onclick="changeEstadoPedidoDemonstracao('<?php echo $encryptedId; ?>', 'Novo', 'new')">Novo</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item action-dropdown-item" href="#"
                                                        onclick="changeEstadoPedidoDemonstracao('<?php echo $encryptedId; ?>', 'Em Contacto', 'in-contact')">Em
                                                        Contacto</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item action-dropdown-item" href="#"
                                                        onclick="changeEstadoPedidoDemonstracao('<?php echo $encryptedId; ?>', 'Fechado', 'concluded')">Fechado</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 text-secondary align-items-center">
                                            <p><?php echo $request->date; ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex flex-column">
                                                <p class="equipment-title fw-700 mb-0"><?php echo $request->name; ?></p>
                                                <span class="visually-hidden"><?php echo $encryptedId; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400"><?php echo $request->institution; ?></span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-400"><?php echo $request->email; ?></span>
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
                                                    <a class="dropdown-item action-dropdown-item text-primary" href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#inbox-detail-modal-<?php echo $encryptedId; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-eye">
                                                            <path
                                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                            <circle cx="12" cy="12" r="3" />
                                                        </svg>
                                                        Ver Detalhes
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item action-dropdown-item text-error" href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete-confirm-modal-<?php echo $encryptedId; ?>">
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
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>


            </div>

            <!-- Alterações pendentes -->
            <div class="inbox-changes-container justify-content-between align-items-center padding-6"
                style="display: none;">
                <p class="text-muted">Existem alterações pendentes</p>
                <button type="submit" class="btn btn-primary btn-glowing gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg>
                    Guardar alterações
                </button>
            </div>
        </section>
    </form>

    <!-- Modais de Detalhes dos Pedidos de Demonstração -->
    <?php foreach ($PedidoDemonstracaos as $request):
        $encryptedId = aes_encrypt($request->id);
        ?>
        <div class="modal fade" id="inbox-detail-modal-<?php echo $encryptedId; ?>" tabindex="-1"
            aria-labelledby="inboxDetailModalLabel-<?php echo $encryptedId; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="inboxDetailModalLabel-<?php echo $encryptedId; ?>">Detalhes do Pedido de
                                Demonstração</h2>
                        </div>
                        <button type="button" class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
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
                    <div class="modal-body padding-6 d-flex flex-column gap-4">
                        <!-- Contact info row -->
                        <div class="d-flex align-items-center gap-3">
                            <div
                                class="d-flex justify-content-center align-items-center text-secondary fw-700 position-relative inbox-modal-user-icon ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-user text-secondary">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <h3 class="fw-700 mb-0 text-secondary"><?php echo $request->name; ?></h3>
                                <p class="text-primary d-flex align-items-center gap-1 text-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-building-2">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18" />
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                                        <path d="M10 6h4" />
                                        <path d="M10 10h4" />
                                        <path d="M10 14h4" />
                                        <path d="M10 18h4" />
                                    </svg>
                                    <?php echo $request->institution; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="inbox-info-grid d-flex gap-4 w-100">
                            <div class="inbox-info-card d-flex flex-column padding-3 gap-2">
                                <span class="text-secondary d-flex align-items-center gap-1 fw-500 text-uppercase">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-mail">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                    </svg>
                                    Email Profissional
                                </span>
                                <span class="inbox-info-card-value"><?php echo $request->email; ?></span>
                            </div>
                            <div class="inbox-info-card d-flex flex-column padding-3 gap-2">
                                <span class="text-secondary d-flex align-items-center gap-1 fw-500 text-uppercase">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-calendar">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                        <line x1="16" x2="16" y1="2" y2="6" />
                                        <line x1="8" x2="8" y1="2" y2="6" />
                                        <line x1="3" x2="21" y1="10" y2="10" />
                                    </svg>
                                    Data Submissão
                                </span>
                                <span class="inbox-info-card-value"><?php echo $request->date; ?></span>
                            </div>
                        </div>

                        <!-- Message Box -->
                        <div class="inbox-message-box padding-3 d-flex flex-column gap-2">
                            <span class="text-primary-500 d-flex align-items-center gap-1 fw-700 text-uppercase">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-message-square">
                                    <path
                                        d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" />
                                </svg>
                                Mensagem Original
                            </span>
                            <p class="mb-0 fst-italic text-primary">
                                "<?php echo $request->message; ?>"
                            </p>
                        </div>

                        <!-- Footer Actions -->
                        <div class="inbox-modal-footer d-flex w-100 justify-content-between align-items-center">
                            <span id="inbox-modal-badge-<?php echo $encryptedId; ?>"
                                class="equipment-badge <?php echo $request->state->class; ?> inbox-modal-footer-badge fw-400">
                                Tratamento atual: <?php echo $request->state->name; ?>
                            </span>
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação de Remoção -->
        <div class="modal fade" id="delete-confirm-modal-<?php echo $encryptedId; ?>" tabindex="-1"
            aria-labelledby="deleteModalLabel-<?php echo $encryptedId; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Titulo -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="deleteModalLabel-<?php echo $encryptedId; ?>">Apagar Definitivamente</h2>
                            <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
                        </div>
                        <button type="button" class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
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
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-triangle-alert-icon lucide-triangle-alert">
                                        <path
                                            d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                        <path d="M12 9v4" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary">Tem a certeza que deseja apagar
                                            permanentemente</p>
                                        <h2 class="fw-700">"<?php echo htmlspecialchars($request->name); ?>"?
                                        </h2>
                                        <span class="text-muted">Tipo: Pedido de Demonstração</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botoes -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <form action="inbox-crud/delete-inbox.php" method="POST" class="m-0 p-0">
                                    <input type="hidden" name="id" value="<?php echo $encryptedId; ?>">
                                    <button type="submit" class="btn btn-danger btn-glowing text-white">
                                        Sim, Apagar.
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 100;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4" role="alert"
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

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>