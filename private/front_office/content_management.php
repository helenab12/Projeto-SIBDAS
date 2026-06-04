<?php
require_once(__DIR__ . "/../../config/config.php");
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
    <section class="content-container gap-6 content-management">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Gestão de Conteúdos</h1>
                <p class="text-secondary fw-400">Página única de edição. Expanda os módulos para editar os textos do
                    website público.</p>
            </div>
        </div>

        <!-- Dropdowns -->
        <div class="d-flex flex-column gap-4 w-100">
            <!-- Card 1: Edifício Principal -->
            <div class="d-flex flex-column gap-3 w-100">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level padding-6 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                        aria-controls="collapseOne">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex gap-3 align-items-center w-100 ">
                                <div class="table-icon-wrapper content-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-type-icon lucide-type">
                                        <path d="M12 4v16" />
                                        <path d="M4 7V5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2" />
                                        <path d="M9 20h6" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <h3 class="fw-700 text-decoration-none">Textos da Hero Section</h3>
                                    <p class="text-secondary text-decoration-none">Título de impacto principal,
                                        subtítutlo e os call-to-actions principais</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-down text-muted">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </button>

                    <div id="collapseOne" class="collapse w-100" aria-labelledby="headingOne">
                        <div class="card-body collapse-inner-level d-flex flex-column gap-4 padding-6">
                            <div class="d-flex flex-column form-item">
                                <label for="hero-title">Título de Impacto</label>
                                <input type="text" id="hero-title" name="hero-title"
                                    value="Gestão inteligente de equipamentos hospitalares">
                            </div>
                            <div class="d-flex flex-column form-item">
                                <label for="hero-subtitle">Subtítulo de Apresentação</label>
                                <textarea id="hero-subtitle" name="hero-subtitle" rows="3"
                                    class="form-control">Criado pela SIBDAS, o nosso software unifica inventário, manutenção e conformidade...</textarea>
                            </div>
                            <div class="d-flex flex-column form-item">
                                <label for="hero-cta">Texto do Botão CTA</label>
                                <input type="text" id="hero-cta" name="hero-cta" value="Pedir Demonstração">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Textos Informativos -->
            <div class="d-flex flex-column gap-3 w-100">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level padding-6 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex gap-3 align-items-center w-100">
                                <div class="table-icon-wrapper content-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                                        <line x1="4" x2="20" y1="12" y2="12" />
                                        <line x1="4" x2="20" y1="6" y2="6" />
                                        <line x1="4" x2="20" y1="18" y2="18" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <h3 class="fw-700 text-decoration-none">Textos Informativos</h3>
                                    <p class="text-secondary text-decoration-none">História da empresa, vantagens
                                        resumidas e perfil do público-alvo.</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-down text-muted">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </button>

                    <div id="collapseTwo" class="collapse w-100" aria-labelledby="headingTwo">
                        <div class="card-body collapse-inner-level d-flex flex-column gap-4 padding-6 w-100">
                            <!-- Secção "Sobre Nós" -->
                            <div class="d-flex flex-column form-item">
                                <label for="about-us" class="fw-700">Secção "Sobre Nós"</label>
                                <p class="text-secondary fw-400">A história e missão da empresa.</p>
                                <textarea id="about-us" name="about-us" rows="2"
                                    class="form-control">A SIBDAS atua há mais de uma década no coração da engenharia clínica.</textarea>
                            </div>

                            <!-- Secção "Vantagens" -->
                            <div class="d-flex flex-column form-item">
                                <label for="advantages" class="fw-700">Secção "Vantagens"</label>
                                <p class="text-secondary fw-400">Resumo dos benefícios diretos para os
                                    clientes.</p>
                                <textarea id="advantages" name="advantages" rows="2"
                                    class="form-control">Redução de 30% em falhas técnicas, conformidade garantida com normas ISO, e tracking em tempo real.</textarea>
                            </div>


                            <!-- Público-Alvo (Use-Cases) -->
                            <div class="d-flex flex-column gap-3">
                                <label class="fw-700">Público-Alvo (Use-Cases)</label>
                                <div class="d-flex flex-column flex-md-row gap-4 w-100">
                                    <div class="d-flex flex-column form-item w-100 w-md-50">
                                        <label for="hospitals" class="text-secondary fw-500">Solução para
                                            Hospitais</label>
                                        <textarea id="hospitals" name="hospitals" rows="3"
                                            class="form-control">A solução ideal para gerir milhares de ativos e escalar departamentos de engenharia clínica.</textarea>
                                    </div>
                                    <div class="d-flex flex-column form-item w-100 w-md-50">
                                        <label for="clinics" class="text-secondary fw-500">Solução para
                                            Clínicas</label>
                                        <textarea id="clinics" name="clinics" rows="3"
                                            class="form-control">Adapta-se a infraestruturas mais pequenas sem perder o rigor na documentação de manutenções.</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2.5: Cartões de Funcionalidades -->
            <div class="d-flex flex-column gap-3 w-100">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level padding-6 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseFeatures" aria-expanded="false"
                        aria-controls="collapseFeatures">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex gap-3 align-items-center w-100">
                                <div class="table-icon-wrapper content-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-layout-grid">
                                        <rect width="7" height="7" x="3" y="3" rx="1" />
                                        <rect width="7" height="7" x="14" y="3" rx="1" />
                                        <rect width="7" height="7" x="14" y="14" rx="1" />
                                        <rect width="7" height="7" x="3" y="14" rx="1" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <h3 class="fw-700 text-decoration-none">Cartões de Funcionalidades</h3>
                                    <p class="text-secondary text-decoration-none">Adicione, edite ou remova os cartões
                                        da Bento Grid pública.</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-down text-muted">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </button>

                    <div id="collapseFeatures" class="collapse w-100" aria-labelledby="headingFeatures">
                        <div class="card-body collapse-inner-level d-flex flex-column gap-4 padding-6 w-100">
                            <!-- Adicionar Cartão Button -->
                            <div class="d-flex justify-content-end w-100">
                                <button type="button" class="btn btn-ghost d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#card-creation-modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-plus">
                                        <path d="M5 12h14" />
                                        <path d="M12 5v14" />
                                    </svg>
                                    Adicionar Cartão
                                </button>
                            </div>

                            <!-- Tabela -->
                            <div class="bento-card w-100 p-0 overflow-hidden">
                                <table id="featuresTable" class="sibdas-table w-100 display border-0">
                                    <thead>
                                        <tr>
                                            <th>ESTADO</th>
                                            <th>ÍCONE</th>
                                            <th>TÍTULO</th>
                                            <th>DESCRIÇÃO</th>
                                            <th class="text-end">AÇÕES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Row 1 -->
                                        <tr>
                                            <td>
                                                <span class="text-success d-flex align-items-center gap-1 fw-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-eye">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    Ativo
                                                </span>
                                            </td>
                                            <td>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-package">
                                                    <path
                                                        d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                                                    <path d="M12 22V12" />
                                                    <polyline points="3.29 7 12 12 20.71 7" />
                                                    <path d="m7.5 4.27 9 5.15" />
                                                </svg>
                                            </td>
                                            <td>
                                                <span class="fw-700">Inventário Centralizado</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary fw-400">Registo exaustivo e
                                                    rastreio...</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                                    <button
                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                        type="button" data-bs-toggle="modal" data-bs-target="#card-edit-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="lucide lucide-pencil">
                                                            <path
                                                                d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                            <path d="m15 5 4 4" />
                                                        </svg>
                                                    </button>
                                                    <button
                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                        type="button" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash-2 text-secondary">
                                                            <path d="M3 6h18" />
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Row 2 -->
                                        <tr>
                                            <td>
                                                <span class="text-success d-flex align-items-center gap-1 fw-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-eye">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    Ativo
                                                </span>
                                            </td>
                                            <td>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-wrench">
                                                    <path
                                                        d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />
                                                </svg>
                                            </td>
                                            <td>
                                                <span class="fw-700">Gestão de Manutenção</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary fw-400">Planeamento de manutenções...</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                                    <button
                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                        type="button" data-bs-toggle="modal" data-bs-target="#card-edit-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="lucide lucide-pencil">
                                                            <path
                                                                d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                            <path d="m15 5 4 4" />
                                                        </svg>
                                                    </button>
                                                    <button
                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                        type="button" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash-2 text-secondary">
                                                            <path d="M3 6h18" />
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Row 3 -->
                                        <tr>
                                            <td>
                                                <span class="text-success d-flex align-items-center gap-1 fw-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-eye">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    Ativo
                                                </span>
                                            </td>
                                            <td>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-sparkles">
                                                    <path
                                                        d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z" />
                                                    <path d="M20 2v4" />
                                                    <path d="M22 4h-4" />
                                                    <circle cx="4" cy="20" r="2" />
                                                </svg>
                                            </td>
                                            <td>
                                                <span class="fw-700">Assistente IA</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary fw-400">Recomendações preditivas...</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                                    <button
                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                        type="button" data-bs-toggle="modal" data-bs-target="#card-edit-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="lucide lucide-pencil">
                                                            <path
                                                                d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                            <path d="m15 5 4 4" />
                                                        </svg>
                                                    </button>
                                                    <button
                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                        type="button" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-trash-2 text-secondary">
                                                            <path d="M3 6h18" />
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Definições de Contacto -->
            <div class="d-flex flex-column gap-3 w-100">
                <div class="card bento-card d-flex align-items-start overflow-hidden">
                    <button
                        class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button top-level padding-6 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                        aria-controls="collapseThree">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex gap-3 align-items-center w-100">
                                <div class="table-icon-wrapper content-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half text-primary">
                                    <h3 class="fw-700 text-decoration-none">Definições de Contacto</h3>
                                    <p class="text-secondary text-decoration-none">Dados de contacto e links sociais
                                        espelhados no Footer.</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-down text-muted">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </button>

                    <div id="collapseThree" class="collapse w-100" aria-labelledby="headingThree">
                        <div class="card-body collapse-inner-level d-flex flex-column gap-4 padding-6 w-100">
                            <div class="d-flex flex-column flex-md-row gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <label for="support-email" class="fw-700">Email de Suporte</label>
                                    <input type="email" id="support-email" name="support-email" value="demo@sibdas.pt">
                                </div>
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <label for="general-phone" class="fw-700">Telefone Geral</label>
                                    <input type="text" id="general-phone" name="general-phone" value="+351 912 345 678">
                                </div>
                            </div>

                            <!-- Middle row -->
                            <div class="d-flex flex-column form-item">
                                <label for="address" class="fw-700">Morada Completa</label>
                                <input type="text" id="address" name="address" value="Lisboa, Portugal">
                            </div>

                            <!-- Bottom row -->
                            <div class="d-flex flex-column form-item">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="social-links" class="fw-700 m-0">Links de Redes Sociais</label>
                                    <span class="text-secondary">Separados por vírgula</span>
                                </div>
                                <input type="text" id="social-links" name="social-links" class="font-monospace"
                                    value="linkedin.com/company/sibdas, twitter.com/sibdas">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<!-- Modal de Criação de Cartão -->
<div class="modal fade" id="card-creation-modal" tabindex="-1" aria-labelledby="cardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="cardModalLabel">Novo Cartão</h2>
                    <span class="text-secondary fw-400">Configura o módulo a embutir na landing page.</span>
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
                <form id="card-creation-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Título do Funcionalidade -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="card-title">Título do Funcionalidade</label>
                        <input type="text" id="card-title" name="card-title" required>
                    </div>

                    <!-- Row 2: Descrição Curta -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="card-desc">Descrição Curta</label>
                        <textarea id="card-desc" name="card-desc" rows="3" class="form-control" required></textarea>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="card-icon">Ícone Símbolo</label>
                            <select id="card-icon" name="card-icon" class="form-select w-100">
                                <option value="document" selected>📄 Documento</option>
                                <option value="wrench">🔧 Chave Inglesa</option>
                                <option value="package">📦 Caixa / Pacote</option>
                                <option value="sparkles">✨ Estrelas (IA)</option>
                            </select>
                        </div>

                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label>Estado na Página</label>
                            <div class="d-flex align-items-center gap-2 switch-wrapper">
                                <div class="form-check form-switch p-0 m-0 d-flex align-items-center gap-3">
                                    <input class="form-check-input m-0 switch-input" type="checkbox" id="card-status"
                                        name="card-status" checked>
                                    <label class="form-check-label m-0 fw-500 text-secondary"
                                        for="card-status">Visível</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4 align-items-center">
                        <button type="button" class="btn btn-link text-secondary text-decoration-none p-0"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btn-submit-modal" class="btn btn-primary btn-glowing">
                            Guardar Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Cartão -->
<div class="modal fade" id="card-edit-modal" tabindex="-1" aria-labelledby="cardEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="cardEditModalLabel">Editar Cartão</h2>
                    <span class="text-secondary fw-400">Configura o módulo a embutir na landing page.</span>
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
                <form id="card-edit-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Título do Funcionalidade -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="edit-card-title">Título do Funcionalidade</label>
                        <input type="text" id="edit-card-title" name="card-title" value="Inventário Centralizado" required>
                    </div>

                    <!-- Row 2: Descrição Curta -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="edit-card-desc">Descrição Curta</label>
                        <textarea id="edit-card-desc" name="card-desc" rows="3" class="form-control" required>Registo exaustivo e rastreio...</textarea>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="edit-card-icon">Ícone Símbolo</label>
                            <select id="edit-card-icon" name="card-icon" class="form-select w-100">
                                <option value="document">📄 Documento</option>
                                <option value="wrench">🔧 Chave Inglesa</option>
                                <option value="package" selected>📦 Caixa / Pacote</option>
                                <option value="sparkles">✨ Estrelas (IA)</option>
                            </select>
                        </div>

                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label>Estado na Página</label>
                            <div class="d-flex align-items-center gap-2 switch-wrapper">
                                <div class="form-check form-switch p-0 m-0 d-flex align-items-center gap-3">
                                    <input class="form-check-input m-0 switch-input" type="checkbox" id="edit-card-status"
                                        name="card-status" checked>
                                    <label class="form-check-label m-0 fw-500 text-secondary"
                                        for="edit-card-status">Visível</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4 align-items-center">
                        <button type="button" class="btn btn-link text-secondary text-decoration-none p-0"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="edit-btn-submit-modal" class="btn btn-primary btn-glowing">
                            Guardar Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Remoção -->
<div class="modal fade" id="delete-confirm-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="deleteModalLabel">Apagar Definitivamente</h2>
                    <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
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
            <div class="modal-body p-0">
                <div class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                    <div class="d-flex flex-column align-items-center gap-4">
                        <div class="d-flex padding-3 danger-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                <p class="text-secondary">
                                    Tem a certeza que deseja apagar permanentemente
                                </p>
                                <h2 class="fw-700">"Inventário Centralizado"</h2>
                                <span class="text-muted">Tipo: Cartão de Funcionalidade</span>
                            </div>
                            <div class="danger-banner text-error text-center padding-3">
                                <span>⚠️ Este registo será eliminado permanentemente da base de dados. Todos os dados associados serão perdidos.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botoes -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-glowing text-white">
                            Sim, Apagar Definitivamente.
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once BASE_PATH . 'private/includes/footer.php';
?>