<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if (!function_exists('isDropdownActive')) {
    function isDropdownActive($pages)
    {
        global $currentPage;
        return in_array($currentPage, $pages);
    }
}
?>
<!-- Sidebar Mobile -->
<aside class="mobile-sidebar d-flex d-md-none position-fixed top-0 left-0 bottom-0 vh-100 flex-column overflow-auto">
    <div class="navbar-brand d-flex align-items-center justify-content-between nav-brand padding-4">
        <div class="d-flex align-items-center gap-3">
            <img src="<?= BASE_URL ?>assets/img/logo.png" alt="HEBA Logo" width="36" height="36">
            <div class="d-flex flex-column">
                <h2 class="text-primary m-0">HEBA</h2>
                <label class="text-secondary text-uppercase m-0">Biomédica</label>
            </div>
        </div>
        <button class="bg-transparent border-0 mobile-close-btn pa-hamburger" aria-label="Fechar Menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="stroke-secondary">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </div>

    <nav class="padding-4">
        <ul class="d-flex flex-column gap-1">
            <li class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>"><a
                    href="<?= BASE_URL ?>private/index.php"
                    class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1" />
                        <rect width="7" height="5" x="14" y="3" rx="1" />
                        <rect width="7" height="9" x="14" y="12" rx="1" />
                        <rect width="7" height="5" x="3" y="16" rx="1" />
                    </svg>Dashboard</a></li>

            <!-- Inventário (dropdown) -->
            <li class="nav-dropdown ">
                <a href="#mobileCollapseInventario"
                    class="nav-dropdown-toggle text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500 <?php echo !isDropdownActive(['equipment_list.php', 'equipment_archive.php', 'detailed_view.php', 'components.php', 'categories.php', 'transfers.php']) ? 'collapsed' : ''; ?>"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="<?php echo isDropdownActive(['equipment_list.php', 'equipment_archive.php', 'detailed_view.php', 'components.php', 'categories.php', 'transfers.php']) ? 'true' : 'false'; ?>"
                    aria-controls="mobileCollapseInventario">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                        <path d="M12 22V12" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <path d="m7.5 4.27 9 5.15" />
                    </svg>
                    Inventário
                    <svg class="nav-chevron ms-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="18"
                        height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </a>
                <div class="collapse <?php echo isDropdownActive(['equipment_list.php', 'equipment_archive.php', 'detailed_view.php', 'components.php', 'categories.php', 'transfers.php']) ? 'show' : ''; ?>"
                    id="mobileCollapseInventario">
                    <ul class="nav-dropdown-menu d-flex flex-column gap-1 list-unstyled">
                        <?php if (tem_permissao('view.equipments') || tem_permissao('view.equipment_archive')): ?>
                            <!-- Equipamentos (dropdown) -->
                            <li class="nav-dropdown">
                                <a href="#mobileCollapseEquipamentos"
                                    class="nav-dropdown-toggle text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500 <?php echo !isDropdownActive(['equipment_list.php', 'equipment_archive.php', 'detailed_view.php']) ? 'collapsed' : ''; ?>"
                                    data-bs-toggle="collapse" role="button"
                                    aria-expanded="<?php echo isDropdownActive(['equipment_list.php', 'equipment_archive.php', 'detailed_view.php']) ? 'true' : 'false'; ?>"
                                    aria-controls="mobileCollapseEquipamentos">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-cog-icon lucide-cog">
                                        <path d="M11 10.27 7 3.34" />
                                        <path d="m11 13.73-4 6.93" />
                                        <path d="M12 22v-2" />
                                        <path d="M12 2v2" />
                                        <path d="M14 12h8" />
                                        <path d="m17 20.66-1-1.73" />
                                        <path d="m17 3.34-1 1.73" />
                                        <path d="M2 12h2" />
                                        <path d="m20.66 17-1.73-1" />
                                        <path d="m20.66 7-1.73 1" />
                                        <path d="m3.34 17 1.73-1" />
                                        <path d="m3.34 7 1.73 1" />
                                        <circle cx="12" cy="12" r="2" />
                                        <circle cx="12" cy="12" r="8" />
                                    </svg>
                                    Equipamentos
                                    <svg class="nav-chevron ms-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                        width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </a>
                                <div class="collapse <?php echo isDropdownActive(['equipment_list.php', 'equipment_archive.php', 'detailed_view.php']) ? 'show' : ''; ?>"
                                    id="mobileCollapseEquipamentos">
                                    <ul class="nav-dropdown-menu d-flex flex-column gap-1 list-unstyled">
                                        <?php if (tem_permissao('view.equipments')): ?>
                                            <li
                                                class="<?php echo ($currentPage == 'equipment_list.php' || $currentPage == 'detailed_view.php') ? 'active' : ''; ?>">
                                                <a href="<?= BASE_URL ?>private/inventory/equipments/equipment_list.php"
                                                    class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 5h.01" />
                                                        <path d="M3 12h.01" />
                                                        <path d="M3 19h.01" />
                                                        <path d="M8 5h13" />
                                                        <path d="M8 12h13" />
                                                        <path d="M8 19h13" />
                                                    </svg>Lista Geral</a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (tem_permissao('view.equipment_archive')): ?>
                                            <li
                                                class="<?php echo ($currentPage == 'equipment_archive.php') ? 'active' : ''; ?>">
                                                <a href="<?= BASE_URL ?>private/inventory/equipments/equipment_archive.php"
                                                    class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M15 12h-5" />
                                                        <path d="M15 8h-5" />
                                                        <path d="M19 17V5a2 2 0 0 0-2-2H4" />
                                                        <path
                                                            d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3" />
                                                    </svg>Equipamentos Arquivados</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php if (tem_permissao('view.components')): ?>
                            <li class="<?php echo ($currentPage == 'components.php') ? 'active' : ''; ?>"><a
                                    href="<?= BASE_URL ?>private/inventory/components.php"
                                    class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                                    </svg>Componentes</a></li>
                        <?php endif; ?>
                        <?php if (tem_permissao('view.categorias')): ?>
                            <li class="<?php echo ($currentPage == 'categories.php') ? 'active' : ''; ?>"><a
                                    href="<?= BASE_URL ?>private/inventory/categories.php"
                                    class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path
                                            d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" />
                                        <path d="M3 5a2 2 0 0 0 2 2h3" />
                                        <path d="M3 3v13a2 2 0 0 0 2 2h3" />
                                    </svg>Categorias</a>
                            </li>
                        <?php endif; ?>
                        <?php if (tem_permissao('inventory.view.transfers')): ?>
                            <li class="<?php echo ($currentPage == 'transfers.php') ? 'active' : ''; ?>"><a
                                    href="<?= BASE_URL ?>private/inventory/transfers.php"
                                    class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-arrow-right-left">
                                        <path d="m16 3 4 4-4 4" />
                                        <path d="M20 7H4" />
                                        <path d="m8 21-4-4 4-4" />
                                        <path d="M4 17h16" />
                                    </svg>Transferências</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>

            <?php if (tem_permissao('view.fornecedores') || tem_permissao('view.pessoas')): ?>
                <!-- Entidades (dropdown) -->
                <li class="nav-dropdown">
                    <a href="#mobileCollapseEntidades"
                        class="nav-dropdown-toggle text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500 <?php echo !isDropdownActive(['suppliers.php', 'people_management.php', 'locations.php']) ? 'collapsed' : ''; ?>"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="<?php echo isDropdownActive(['suppliers.php', 'people_management.php', 'locations.php']) ? 'true' : 'false'; ?>"
                        aria-controls="mobileCollapseEntidades">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 12h4" />
                            <path d="M10 8h4" />
                            <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                            <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                            <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                        </svg>
                        Entidades
                        <svg class="nav-chevron ms-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="18"
                            height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </a>
                    <div class="collapse <?php echo isDropdownActive(['suppliers.php', 'people_management.php', 'locations.php']) ? 'show' : ''; ?>"
                        id="mobileCollapseEntidades">
                        <ul class="nav-dropdown-menu d-flex flex-column gap-1 list-unstyled">
                            <?php if (tem_permissao('view.fornecedores')): ?>
                                <li class="<?php echo ($currentPage == 'suppliers.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/entities/suppliers.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>Fornecedores</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('view.pessoas')): ?>
                                <li class="<?php echo ($currentPage == 'people_management.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/entities/people_management.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-user-round-cog-icon lucide-user-round-cog">
                                            <path d="m14.305 19.53.923-.382" />
                                            <path d="m15.228 16.852-.923-.383" />
                                            <path d="m16.852 15.228-.383-.923" />
                                            <path d="m16.852 20.772-.383.924" />
                                            <path d="m19.148 15.228.383-.923" />
                                            <path d="m19.53 21.696-.382-.924" />
                                            <path d="M2 21a8 8 0 0 1 10.434-7.62" />
                                            <path d="m20.772 16.852.924-.383" />
                                            <path d="m20.772 19.148.924.383" />
                                            <circle cx="10" cy="8" r="5" />
                                            <circle cx="18" cy="18" r="3" />
                                        </svg>Gestão de Pessoas</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('locations.view')): ?>
                                <li class="<?php echo ($currentPage == 'locations.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/entities/locations.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                                            <path
                                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>Localizações</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Segurança (dropdown) -->
            <?php if (tem_permissao('view.safety')): ?>
                <li class="nav-dropdown">
                    <a href="#mobileCollapseSeguranca"
                        class="nav-dropdown-toggle text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500 <?php echo !isDropdownActive(['users.php', 'profiles.php', 'permissions.php', 'recycling.php', 'backups.php', 'audit_logs.php']) ? 'collapsed' : ''; ?>"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="<?php echo isDropdownActive(['users.php', 'profiles.php', 'permissions.php', 'recycling.php', 'backups.php', 'audit_logs.php']) ? 'true' : 'false'; ?>"
                        aria-controls="mobileCollapseSeguranca">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                        </svg>
                        Segurança
                        <svg class="nav-chevron ms-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="18"
                            height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </a>
                    <div class="collapse <?php echo isDropdownActive(['users.php', 'profiles.php', 'permissions.php', 'recycling.php', 'backups.php', 'audit_logs.php']) ? 'show' : ''; ?>"
                        id="mobileCollapseSeguranca">
                        <ul class="nav-dropdown-menu d-flex flex-column gap-1 list-unstyled">
                            <?php if (tem_permissao('view.users')): ?>
                                <li class="<?php echo ($currentPage == 'users.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/security/users.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>Utilizadores</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('view.profiles')): ?>
                                <li class="<?php echo ($currentPage == 'profiles.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/security/profiles.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>Perfis</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('view.permissions')): ?>
                                <li class="<?php echo ($currentPage == 'permissions.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/security/permissions.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-lock-icon lucide-lock">
                                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                        </svg>Permissões</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('view.recycling')): ?>
                                <li class="<?php echo ($currentPage == 'recycling.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/security/recycling.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-recycle-icon lucide-recycle">
                                            <path
                                                d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5" />
                                            <path
                                                d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12" />
                                            <path d="m14 16-3 3 3 3" />
                                            <path d="M8.293 13.596 7.196 9.5 3.1 10.598" />
                                            <path
                                                d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843" />
                                            <path d="m13.378 9.633 4.096 1.098 1.097-4.096" />
                                        </svg>Reciclagem</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('security.backups')): ?>
                                <li class="<?php echo ($currentPage == 'backups.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/security/backups.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-database-backup-icon lucide-database-backup">
                                            <ellipse cx="12" cy="5" rx="9" ry="3" />
                                            <path d="M3 12a9 3 0 0 0 5 2.69" />
                                            <path d="M21 9.3V5" />
                                            <path d="M3 5v14a9 3 0 0 0 6.47 2.88" />
                                            <path d="M12 12v4h4" />
                                            <path
                                                d="M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16" />
                                        </svg>Backups</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('view.audit.logs')): ?>
                                <li class="<?php echo ($currentPage == 'audit_logs.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/security/audit_logs.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-scroll-text-icon lucide-scroll-text">
                                            <path d="M15 12h-5" />
                                            <path d="M15 8h-5" />
                                            <path d="M19 17V5a2 2 0 0 0-2-2H4" />
                                            <path
                                                d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3" />
                                        </svg>Logs de Auditoria</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Gestão do Front-Office (dropdown) -->
            <?php if (tem_permissao('view.front.office.management')): ?>
                <li class="nav-dropdown">
                    <a href="#mobileCollapseGestao"
                        class="nav-dropdown-toggle text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500 <?php echo !isDropdownActive(['content_management.php', 'inbox.php']) ? 'collapsed' : ''; ?>"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="<?php echo isDropdownActive(['content_management.php', 'inbox.php']) ? 'true' : 'false'; ?>"
                        aria-controls="mobileCollapseGestao">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                            <path d="M2 12h20" />
                        </svg>
                        Gestão do Front-Office
                        <svg class="nav-chevron ms-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="18"
                            height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </a>
                    <div class="collapse <?php echo isDropdownActive(['content_management.php', 'inbox.php']) ? 'show' : ''; ?>"
                        id="mobileCollapseGestao">
                        <ul class="nav-dropdown-menu d-flex flex-column gap-1 list-unstyled">
                            <?php if (tem_permissao('view.content.management')): ?>
                                <li class="<?php echo ($currentPage == 'content_management.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/front_office/content_management.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-type-icon lucide-type">
                                            <path d="M12 4v16" />
                                            <path d="M4 7V5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2" />
                                            <path d="M9 20h6" />
                                        </svg>Gestão de Conteúdos</a></li>
                            <?php endif; ?>
                            <?php if (tem_permissao('view.inbox')): ?>
                                <li class="<?php echo ($currentPage == 'inbox.php') ? 'active' : ''; ?>"><a
                                        href="<?= BASE_URL ?>private/front_office/inbox.php"
                                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-inbox-icon lucide-inbox">
                                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                                            <path
                                                d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                                        </svg>Caixa de Entrada</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php if (tem_permissao('view.notifications')): ?>
                <li class="<?php echo ($currentPage == 'notifications.php') ? 'active' : ''; ?>"><a
                        href="<?= BASE_URL ?>private/notifications.php"
                        class="text-decoration-none text-secondary d-flex align-items-center gap-3 fw-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                            <path
                                d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                        </svg>Notificações</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>

<!-- Sidebar Background -->
<div class="sidebar-background position-fixed top-0 left-0 vw-100 vh-100 d-md-none"></div>
</div>