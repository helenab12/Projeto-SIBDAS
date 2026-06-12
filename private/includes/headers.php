<!-- Header Desktop + Tablet -->
<header class="d-flex flex-row w-100 padding-4 desktop-header justify-content-between">
    <form action="" class="d-flex flex-column" style="width: 400px;">
        <div class="form-item nav-search-bar">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-search-icon lucide-search">
                <path d="m21 21-4.34-4.34" />
                <circle cx="11" cy="11" r="8" />
            </svg>
            <input type="search" id="search" name="search" placeholder="Pesquisar equipamentos, fornecedores..."
                data-bs-toggle="modal" data-bs-target="#search-modal" readonly>

            <div class="search-shortcut">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-command-icon lucide-command">
                    <path d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3" />
                </svg>
                <span>K</span>
            </div>
        </div>
    </form>
    <div class="d-flex flex-row align-items-center gap-6 ">
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
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-bell-icon lucide-bell stroke-secondary">
            <path d="M10.268 21a2 2 0 0 0 3.464 0" />
            <path
                d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
        </svg>
        <div class="dropdown">
            <div class="d-flex flex-row align-items-center navbar-user gap-3 dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false" style="cursor: pointer;">
                <div class="d-flex flex-column gap-1 text-end">
                    <p class="fw-600 text-primary mb-0">
                        <?= htmlspecialchars(isset($_SESSION['pessoaAtual']) ? $_SESSION['pessoaAtual']->getNome() : 'Utilizador Desconhecido') ?>
                    </p>
                    <span class="text-secondary">
                        <?= htmlspecialchars(isset($_SESSION['userAtual']) ? $_SESSION['userAtual']->getPerfil()->getNome() : 'Sem Perfil') ?>
                    </span>
                </div>
                <p class="user-icon btn-glowing d-flex align-items-center justify-content-center fw-700 mb-0">
                    <?= get_user_initials(isset($_SESSION['pessoaAtual']) ? $_SESSION['pessoaAtual']->getNome() : 'Utilizador') ?>
                </p>
            </div>
            <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                <li>
                    <a class="dropdown-item action-dropdown-item text-error"
                        href="<?= BASE_URL ?>private/login/logout.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-out-icon lucide-log-out">
                            <path d="m16 17 5-5-5-5" />
                            <path d="M21 12H9" />
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        </svg>
                        Terminar Sessão
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Header Mobile -->
<header class="d-flex flex-row w-100 padding-4 mobile-header">
    <div class="d-flex flex-row align-items-center justify-content-between w-100">


        <div class="d-flex flex-row align-items-center gap-6 ">
            <button class="pa-hamburger" id="mobile-menu-toggle" aria-label="Menu">
                <svg class="icon-menu stroke-secondary" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12" />
                    <line x1="4" x2="20" y1="6" y2="6" />
                    <line x1="4" x2="20" y1="18" y2="18" />
                </svg>
            </button>

            <button class="btn p-0 border-0 bg-transparent d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#search-modal" aria-label="Pesquisar">
                <svg class="search-icon stroke-secondary m-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
            </button>
        </div>

        <div class="d-flex flex-row align-items-center gap-6 ">
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
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-bell-icon lucide-bell stroke-secondary">
                <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                <path
                    d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
            </svg>
            <div class="dropdown">
                <div class="d-flex flex-row align-items-center navbar-user gap-3 dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <p class="user-icon btn-glowing d-flex align-items-center justify-content-center fw-700 mb-0">
                        <?= get_user_initials(isset($_SESSION['pessoaAtual']) ? $_SESSION['pessoaAtual']->getNome() : 'Utilizador') ?>
                    </p>
                </div>
                <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                    <li>
                        <a class="dropdown-item action-dropdown-item text-error"
                            href="<?= BASE_URL ?>private/login/logout.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out">
                                <path d="m16 17 5-5-5-5" />
                                <path d="M21 12H9" />
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            </svg>
                            Terminar Sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Global Search Modal -->
<?php
$quickAccessItems = [
    [
        'label' => 'Dashboard',
        'url' => BASE_URL . 'private/index.php',
        'icon' => '<rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />'
    ],
    [
        'label' => 'Lista de Equipamentos',
        'url' => BASE_URL . 'private/inventory/equipments/equipment_list.php',
        'icon' => '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" /><path d="M12 22V12" /><polyline points="3.29 7 12 12 20.71 7" /><path d="m7.5 4.27 9 5.15" />'
    ],
    [
        'label' => 'Fornecedores',
        'url' => BASE_URL . 'private/entities/suppliers.php',
        'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><path d="M16 3.128a4 4 0 0 1 0 7.744" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><circle cx="9" cy="7" r="4" />'
    ],
    [
        'label' => 'Gestão de Pessoas',
        'url' => BASE_URL . 'private/entities/people_management.php',
        'icon' => '<path d="m14.305 19.53.923-.382" /><path d="m15.228 16.852-.923-.383" /><path d="m16.852 15.228-.383-.923" /><path d="m16.852 20.772-.383.924" /><path d="m19.148 15.228.383-.923" /><path d="m19.53 21.696-.382-.924" /><path d="M2 21a8 8 0 0 1 10.434-7.62" /><path d="m20.772 16.852.924-.383" /><path d="m20.772 19.148.924.383" /><circle cx="10" cy="8" r="5" /><circle cx="18" cy="18" r="3" />'
    ],
    [
        'label' => 'Utilizadores',
        'url' => BASE_URL . 'private/security/users.php',
        'icon' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />'
    ],
    [
        'label' => 'Categorias',
        'url' => BASE_URL . 'private/inventory/categories.php',
        'icon' => '<path d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" /><path d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" /><path d="M3 5a2 2 0 0 0 2 2h3" /><path d="M3 3v13a2 2 0 0 0 2 2h3" />'
    ]
];

$searchResults = [
    [
        'title' => 'Equipamentos (3)',
        'icon' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" /><polyline points="3.27 6.96 12 12.01 20.73 6.96" /><line x1="12" y1="22.08" x2="12" y2="12" />',
        'bg' => 'var(--primary-50)',
        'color' => 'var(--primary-500)',
        'items' => [
            [
                'title' => 'Tomógrafo Computorizado',
                'subtitle' => 'EQ-2024-005 &bull; Siemens &bull; Equipamento de Imagiologia',
                'url' => '#'
            ],
            [
                'title' => 'Analisador Bioquímico',
                'subtitle' => 'EQ-2024-007 &bull; Beckman Coulter &bull; Equipamento Laboratorial',
                'url' => '#'
            ],
            [
                'title' => 'Oxímetro de Pulso',
                'subtitle' => 'EQ-2023-051 &bull; Masimo &bull; Monitores de Sinais Vitais',
                'url' => '#'
            ]
        ]
    ],
    [
        'title' => 'Pessoas (2)',
        'icon' => '<path d="m14.305 19.53.923-.382" /><path d="m15.228 16.852-.923-.383" /><path d="m16.852 15.228-.383-.923" /><path d="m16.852 20.772-.383.924" /><path d="m19.148 15.228.383-.923" /><path d="m19.53 21.696-.382-.924" /><path d="M2 21a8 8 0 0 1 10.434-7.62" /><path d="m20.772 16.852.924-.383" /><path d="m20.772 19.148.924.383" /><circle cx="10" cy="8" r="5" /><circle cx="18" cy="18" r="3" />',
        'bg' => 'color-mix(in srgb, var(--success) 10%, transparent)',
        'color' => 'var(--success)',
        'items' => [
            [
                'title' => 'Dr. Manuel Costa',
                'subtitle' => 'Médico',
                'url' => '#'
            ],
            [
                'title' => 'Eng. Carlos Mendes',
                'subtitle' => 'Técnico de Manutenção',
                'url' => '#'
            ]
        ]
    ]
];
?>

<div class="modal fade global-search-modal" id="search-modal" tabindex="-1" aria-labelledby="searchModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg overflow-hidden">
        <div class="modal-content">
            <!-- Header Search Bar -->
            <div class="modal-header-search d-flex align-items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-search text-secondary">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
                <input type="text" id="global-search-input"
                    placeholder="Pesquisar equipamentos, fornecedores, pessoas..." autocomplete="off"
                    class="border-0 bg-transparent w-100 outline-0 fw-500 text-primary" style="">
                <label class="esc-badge fw-700">ESC</label>
            </div>

            <!-- Body Container -->
            <div class="modal-body padding-6" style="min-height: 100px;">
                <!-- Quick Access Content -->
                <div class="d-flex flex-column gap-2" id="search-quick-access">
                    <span class="search-section-title fw-700 text-muted text-uppercase">Acesso Rápido</span>
                    <div class="d-flex flex-column gap-1">
                        <?php foreach ($quickAccessItems as $item): ?>
                            <a href="<?= $item['url'] ?>"
                                class="search-item d-flex align-items-center gap-3 padding-3 cursor-pointer text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="search-item-icon">
                                    <?= $item['icon'] ?>
                                </svg>
                                <p><?= htmlspecialchars($item['label']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Search Results Content -->
                <div class="d-flex flex-column gap-4 d-none" id="search-results">
                    <?php foreach ($searchResults as $section): ?>
                        <div class="d-flex flex-column gap-2">
                            <span
                                class="search-section-title fw-700 text-muted text-uppercase"><?= htmlspecialchars($section['title']) ?></span>
                            <div class="d-flex flex-column gap-1">
                                <?php foreach ($section['items'] as $item): ?>
                                    <a href="<?= htmlspecialchars($item['url']) ?>"
                                        class="search-item d-flex align-items-center gap-3 padding-3 text-decoration-none">
                                        <div class="d-flex align-items-center justify-content-center rounded padding-2"
                                            style="background-color: <?= $section['bg'] ?>; color: <?= $section['color'] ?>;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <?= $section['icon'] ?>
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column gap-half">
                                            <p class="fw-700 m-0 text-primary"><?= htmlspecialchars($item['title']) ?></p>
                                            <span class="text-secondary"><?= $item['subtitle'] ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>