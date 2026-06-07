<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

$permissionsList = [
    [
        'name' => 'equipment.view',
        'description' => 'Visualizar equipamentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => true,
    ],
    [
        'name' => 'equipment.create',
        'description' => 'Criar equipamentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'equipment.edit',
        'description' => 'Editar equipamentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'equipment.delete',
        'description' => 'Apagar equipamentos',
        'admin' => true,
        'biomed-eng' => false,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'equipment.archive',
        'description' => 'Arquivar/restaurar equipamentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'maintenance.view',
        'description' => 'Visualizar manutenções',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => false,
        'consult' => true,
    ],
    [
        'name' => 'maintenance.create',
        'description' => 'Registar manutenções',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'maintenance.edit',
        'description' => 'Editar manutenções',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'maintenance.finalize',
        'description' => 'Finalizar manutenções',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'documents.view',
        'description' => 'Visualizar documentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => true,
    ],
    [
        'name' => 'documents.upload',
        'description' => 'Carregar documentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => false,
    ],
    [
        'name' => 'documents.delete',
        'description' => 'Apagar documentos',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'suppliers.view',
        'description' => 'Visualizar fornecedores',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => true,
    ],
    [
        'name' => 'suppliers.manage',
        'description' => 'Gerir fornecedores (CRUD)',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => true,
        'consult' => false,
    ],
    [
        'name' => 'people.view',
        'description' => 'Visualizar pessoas',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => true,
    ],
    [
        'name' => 'people.manage',
        'description' => 'Gerir pessoas (CRUD)',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => true,
        'consult' => false,
    ],
    [
        'name' => 'components.view',
        'description' => 'Visualizar componentes/stock',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => true,
    ],
    [
        'name' => 'components.manage',
        'description' => 'Gerir componentes (CRUD)',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => false,
    ],
    [
        'name' => 'users.view',
        'description' => 'Visualizar utilizadores',
        'admin' => true,
        'biomed-eng' => false,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'users.manage',
        'description' => 'Gerir utilizadores (CRUD)',
        'admin' => true,
        'biomed-eng' => false,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'audit.view',
        'description' => 'Visualizar logs de auditoria',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'locations.view',
        'description' => 'Visualizar localizações',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => true,
        'prov' => true,
        'consult' => true,
    ],
    [
        'name' => 'locations.manage',
        'description' => 'Gerir localizações (CRUD)',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'permissions.manage',
        'description' => 'Gerir permissões e perfis',
        'admin' => true,
        'biomed-eng' => false,
        'tech' => false,
        'prov' => false,
        'consult' => false,
    ],
    [
        'name' => 'reports.generate',
        'description' => 'Gerar relatórios e exportar dados',
        'admin' => true,
        'biomed-eng' => true,
        'tech' => false,
        'prov' => true,
        'consult' => false,
    ],
];

?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 security-profiles">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Perfis</h1>
                <p class="text-secondary fw-400">Gestão de perfis de utilizadores</p>
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
                        placeholder="Pesquisar por permissão...">
                </div>
            </form>
        </div>

        <!-- Alteracoes -->
        <div class="bento-card changes-card padding-4 gap-4 d-flex flex-row align-items-center">
            <div class="d-flex gap-4">
                <div class="table-icon-wrapper equipment-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shield-icon lucide-shield">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                    <p class="text-primary-900 fw-700">
                        Alterações não guardadas
                    </p>
                    <span class="text-primary-500">Tem modificações pendentes na tabela de perfis.</span>
                </div>
            </div>
            <div class="d-flex gap-4">
                <button class="btn gap-2 btn-small fw-500" data-bs-toggle="modal"
                    data-bs-target="#equipment-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    Desfazer
                </button>
                <button class="btn btn-primary btn-glowing gap-2 btn-small fw-500" data-bs-toggle="modal"
                    data-bs-target="#equipment-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg>
                    Guardar Alterações
                </button>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th class="text-center align-middle">
                            <div class="d-flex flex-column align-items-start justify-content-center">
                                PERMISSÃO
                            </div>
                        </th>
                        <th class="text-center align-middle">
                            <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                ADMINISTRADOR
                                <span class="text-muted m-0">
                                    25 perms.
                                </span>
                            </div>
                        </th>
                        <th class="text-center align-middle">
                            <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                ENGENHEIRO BIOMÉDICO
                                <span class="text-muted m-0">
                                    21 perms.
                                </span>
                            </div>
                        </th>
                        <th class="text-center align-middle">
                            <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                TÉCNICO DE MANUTENÇÃO
                                <span class="text-muted m-0">
                                    13 perms.
                                </span>
                            </div>
                        </th>
                        <th class="text-center align-middle">
                            <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                APROVISIONAMENTO
                                <span class="text-muted m-0">
                                    11 perms.
                                </span>
                            </div>
                        </th>
                        <th class="text-center align-middle">
                            <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                CONSULTA
                                <span class="text-muted m-0">
                                    7 perms.
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permissionsList as $permission): ?>
                        <tr>
                            <td>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <p class="font-mono fw-700">
                                        <?= $permission['name'] ?>
                                    </p>
                                    <span class="text-muted">
                                        <?= $permission['description'] ?>
                                    </span>
                                </div>

                            </td>
                            <td class="text-center align-middle">
                                <button class="check-badge <?= $permission['admin'] ? 'has-permission' : '' ?>"
                                    id="permission-badge-admin-<?= array_search($permission, $permissionsList) ?>"
                                    onclick="togglePermission('admin-<?= array_search($permission, $permissionsList) ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check-icon lucide-check padding-2">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x padding-2">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </button>
                            </td>
                            <td class="text-center align-middle">
                                <button class="check-badge <?= $permission['biomed-eng'] ? 'has-permission' : '' ?>"
                                    id="permission-badge-biomed-<?= array_search($permission, $permissionsList) ?>"
                                    onclick="togglePermission('biomed-<?= array_search($permission, $permissionsList) ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check-icon lucide-check padding-2">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x padding-2">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </button>
                            </td>
                            <td class="text-center align-middle">
                                <button class="check-badge <?= $permission['tech'] ? 'has-permission' : '' ?>"
                                    id="permission-badge-tech-<?= array_search($permission, $permissionsList) ?>"
                                    onclick="togglePermission('tech-<?= array_search($permission, $permissionsList) ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check-icon lucide-check padding-2">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x padding-2">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </button>
                            </td>
                            <td class="text-center align-middle">
                                <button class="check-badge <?= $permission['prov'] ? 'has-permission' : '' ?>"
                                    id="permission-badge-prov-<?= array_search($permission, $permissionsList) ?>"
                                    onclick="togglePermission('prov-<?= array_search($permission, $permissionsList) ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check-icon lucide-check padding-2">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x padding-2">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </button>
                            </td>
                            <td class="text-center align-middle">
                                <button class="check-badge <?= $permission['consult'] ? 'has-permission' : '' ?>"
                                    id="permission-badge-consult-<?= array_search($permission, $permissionsList) ?>"
                                    onclick="togglePermission('consult-<?= array_search($permission, $permissionsList) ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check-icon lucide-check padding-2">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x padding-2">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>