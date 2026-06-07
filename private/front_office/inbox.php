<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

class InboxState
{
    public string $name;
    public string $class;

    public function __construct(string $name, string $class)
    {
        $this->name = $name;
        $this->class = $class;
    }
}

class InboxRequest
{
    public int $id;
    public InboxState $state;
    public string $date;
    public string $name;
    public string $institution;
    public string $email;
    public string $message;

    public function __construct(int $id, InboxState $state, string $date, string $name, string $institution, string $email, string $message)
    {
        $this->id = $id;
        $this->state = $state;
        $this->date = $date;
        $this->name = $name;
        $this->institution = $institution;
        $this->email = $email;
        $this->message = $message;
    }
}

$inboxStates = [
    'novo' => new InboxState('Novo', 'new'),
    'em_contacto' => new InboxState('Em Contacto', 'in-contact'),
    'fechado' => new InboxState('Fechado', 'concluded')
];

$inboxRequests = [
    new InboxRequest(
        1,
        $inboxStates['novo'],
        '14 Abr 2026, 14:30',
        'Dra. Leonor Santos',
        'Hospital de Santa Maria',
        'leonor.santos@exemplo.pt',
        'Gostaria de agendar uma demonstração presencial da vossa plataforma HEBA para o serviço de imunoalergologia.'
    ),
    new InboxRequest(
        2,
        $inboxStates['novo'],
        '14 Abr 2026, 09:15',
        'Eng. Filipe Costa',
        'Clínica Luz',
        'filipe.costa@exemplo.pt',
        'Solicito agendamento de uma chamada para esclarecimento de dúvidas sobre a integração do HEBA.'
    ),
    new InboxRequest(
        3,
        $inboxStates['em_contacto'],
        '13 Abr 2026, 16:45',
        'Dr. António Guedes',
        'Hospital de São João',
        'aguedes@exemplo.pt',
        'Gostaria de agendar uma demonstração das funcionalidades de inventário de equipamentos médicos.'
    ),
    new InboxRequest(
        4,
        $inboxStates['fechado'],
        '10 Abr 2026, 11:10',
        'Marta Ribeiro',
        'Centro Laboratorial Vida',
        'marta.ribeiro@exemplo.pt',
        'Gostaria de obter um orçamento detalhado para a implementação da vossa solução de gestão.'
    )
];
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 inbox">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Caixa de Entrada</h1>
                <p class="text-secondary fw-400">Gestão dos pedidos de demonstração do Website.</p>
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
                        placeholder="Pesquisar por nome ou organização...">
                </div>
            </form>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>AÇÕES</th>
                        <th>ESTADO</th>
                        <th>DATA</th>
                        <th>NOME CONTACTO</th>
                        <th>INSTITUIÇÃO</th>
                        <th>EMAIL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inboxRequests as $request): ?>
                        <tr>
                            <td>
                                <button class="border-0 table-icon-wrapper equipment-icon-wrapper" type="button"
                                    data-bs-toggle="modal" data-bs-target="#inbox-detail-modal-<?php echo $request->id; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-message-square-icon lucide-message-square">
                                        <path
                                            d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" />
                                    </svg>
                                </button>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button id="inbox-state-btn-<?php echo $request->id; ?>"
                                        class="d-inline-flex align-items-center equipment-badge <?php echo $request->state->class; ?> gap-1 mw-0 border-0"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span><?php echo $request->state->name; ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu action-dropdown-menu">
                                        <li>
                                            <a class="dropdown-item action-dropdown-item" href="#"
                                                onclick="changeInboxState(<?php echo $request->id; ?>, 'Novo', 'new')">Novo</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item action-dropdown-item" href="#"
                                                onclick="changeInboxState(<?php echo $request->id; ?>, 'Em Contacto', 'in-contact')">Em
                                                Contacto</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item action-dropdown-item" href="#"
                                                onclick="changeInboxState(<?php echo $request->id; ?>, 'Fechado', 'concluded')">Fechado</a>
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
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?php echo $request->institution; ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?php echo $request->email; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modais de Detalhes dos Pedidos de Demonstração -->
        <?php foreach ($inboxRequests as $request): ?>
            <div class="modal fade" id="inbox-detail-modal-<?php echo $request->id; ?>" tabindex="-1"
                aria-labelledby="inboxDetailModalLabel-<?php echo $request->id; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                    <div class="modal-content custom-modal-content d-flex flex-column">
                        <!-- Titulo -->
                        <div
                            class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                            <div class="d-flex flex-column">
                                <h2 class="equipment-creation-modal-title modal-title"
                                    id="inboxDetailModalLabel-<?php echo $request->id; ?>">Detalhes do Pedido de
                                    Demonstração</h2>
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
                                <span id="inbox-modal-badge-<?php echo $request->id; ?>"
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
        <?php endforeach; ?>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>