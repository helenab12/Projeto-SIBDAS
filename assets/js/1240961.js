// ===================
// 1. UI Core e Layout
// ===================

// - Toggle Menu Mobile (Hamburguer / X)
const menuToggle = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");

if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", () => {
        menuToggle.classList.toggle("open");
        mobileMenu.classList.toggle("open");
    });
}

// - Toggle de Tema (Dark/Light mode) e Inicialização de Tema
const themeToggles = document.querySelectorAll(".pa-theme-toggle");

function toggleTheme() {
    const root = document.documentElement;
    const current = root.getAttribute("data-theme");
    const next = current === "dark" ? "light" : "dark";

    root.setAttribute("data-theme", next);
    root.style.colorScheme = next;
    localStorage.setItem("theme", next);

    updateChartColors();
}

(function () {
    const saved = localStorage.getItem("theme");
    const preferred =
        saved ||
        (window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light");

    document.documentElement.setAttribute("data-theme", preferred);
    document.documentElement.style.colorScheme = preferred;
})();

themeToggles.forEach((btn) => btn.addEventListener("click", toggleTheme));

// - Sidebar
const dropdownToggles = document.querySelectorAll(".nav-dropdown-toggle");

dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", (e) => {
        const sidebar = toggle.closest("aside");

        if (sidebar && sidebar.classList.contains("collapsed")) {
            sidebar.classList.remove("collapsed");
        }
    });
});

// - Sidebar Collapse
const collapseBtn = document.querySelector(".sidebar-collapse-btn");
if (collapseBtn) {
    collapseBtn.addEventListener("click", () => {
        const sidebar = document.querySelector(".desktop-sidebar");
        if (sidebar) {
            sidebar.classList.toggle("collapsed");

            if (sidebar.classList.contains("collapsed")) {
                const openCollapses = sidebar.querySelectorAll(".collapse.show");
                openCollapses.forEach((collapseEl) => {
                    if (typeof bootstrap !== "undefined") {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapseEl);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        } else {
                            new bootstrap.Collapse(collapseEl, { toggle: false }).hide();
                        }
                    }
                });
            }
        }
    });
}

// Expandir a sidebar ao clicar no dropdown
const sidebarDropdownToggles = document.querySelectorAll(".desktop-sidebar .nav-collapse-toggle");
if (sidebarDropdownToggles) {
    sidebarDropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const sidebar = document.querySelector(".desktop-sidebar");
            if (sidebar && sidebar.classList.contains("collapsed")) {
                sidebar.classList.remove("collapsed");
            }
        });
    });
}

const mobileSidebar = document.querySelector(".mobile-sidebar");
const sidebarBackground = document.querySelector(".sidebar-background");

// Abrir menu mobile (clique no hamburguer)
const mobileMenuBtn = document.getElementById("mobile-menu-toggle");
if (mobileMenuBtn && mobileSidebar) {
    mobileMenuBtn.addEventListener("click", () => {
        mobileSidebar.classList.toggle("open");
        toggleBodyScroll(mobileSidebar.classList.contains("open"));
    });
}

// Fechar menu mobile (clique no botão 'X')
const mobileCloseBtn = document.querySelector(".mobile-close-btn");
if (mobileCloseBtn && mobileSidebar) {
    mobileCloseBtn.addEventListener("click", () => {
        mobileSidebar.classList.remove("open");
        toggleBodyScroll(false);
    });
}

// Fechar menu quando se carrega no fundo escuro (sidebar-background)
if (sidebarBackground && mobileSidebar) {
    sidebarBackground.addEventListener("click", () => {
        mobileSidebar.classList.remove("open");
        toggleBodyScroll(false);
    });
}

// - Header Semi-transparente (Scroll)
const navbar = document.querySelector(".pa-navbar");

if (navbar) {
    window.addEventListener("scroll", () => {
        navbar.classList.toggle("scrolled", window.scrollY > 50);
    });
}

const privateAreaHeader = document.querySelectorAll(".private-area header");

if (privateAreaHeader) {
    window.addEventListener("scroll", () => {
        privateAreaHeader.forEach((header) => {
            header.classList.toggle("header-scrolled", window.scrollY > 50);
        });
    });
}

// - Funcionalidade para prender scroll do body (toggleBodyScroll)
function toggleBodyScroll(isOpen) {
    if (isOpen) {
        document.body.style.setProperty("overflow", "hidden", "important");
    } else {
        document.body.style.removeProperty("overflow");
    }
}

// ===================
// 2. Gráficos e Estatísticas
// ===================

// - Variáveis partilhadas de Cores Chart.js
function getCalculatedCssColor(varName) {
    const div = document.createElement("div");
    div.style.color = `var(${varName})`;
    div.style.display = "none";
    document.body.appendChild(div);
    const finalColor = getComputedStyle(div).color;
    document.body.removeChild(div);
    return finalColor;
}

// - Lógica de updateChartColors
function updateChartColors() {
    const newTextColor = getCalculatedCssColor("--text-secondary");
    const newGridColor = getCalculatedCssColor("--border-light");

    if (typeof Chart !== "undefined") {
        Chart.defaults.color = newTextColor;

        if (Chart.instances) {
            for (let id in Chart.instances) {
                const chart = Chart.instances[id];

                chart.options.color = newTextColor;
                if (chart.options.scales) {
                    for (let scaleId in chart.options.scales) {
                        if (!chart.options.scales[scaleId].ticks)
                            chart.options.scales[scaleId].ticks = {};
                        chart.options.scales[scaleId].ticks.color = newTextColor;

                        if (!chart.options.scales[scaleId].grid)
                            chart.options.scales[scaleId].grid = {};
                        chart.options.scales[scaleId].grid.color = newGridColor;
                    }
                }
                chart.update();
            }
        }
    }
}

window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", updateChartColors);
window.addEventListener("load", updateChartColors);

updateChartColors();

// - Gráfico de Barras (Status)
const estatisticasLabels = window.DashboardData?.graficoCategoria?.labels || [
    "Ventiladores",
    "Desfibrilhadores",
    "Bombas Infusão",
    "Monitores Paciente",
    "Máquinas Anestesia",
    "Incubadoras",
    "Eletrocardiógrafos",
    "Aspiradores",
];

const estatisticasData = window.DashboardData?.graficoCategoria?.data || [42, 67, 89, 28, 15, 53, 38, 22];

const estatisticasColors = [
    "#3b82f6", // azul
    "#22c55e", // verde
    "#a855f7", // roxo
    "#ef4444", // vermelho
    "#f97316", // laranja
    "#06b6d4", // ciano
    "#ff69b4", // rosa choque
    "#7fffd4", // verde água
];

const canvas1 = document.getElementById("categoryDistributionChart");
if (canvas1) {
    const ctx = canvas1.getContext("2d");
    const categoryDistributionChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: estatisticasLabels,
            datasets: [
                {
                    label: "Quantidade",
                    data: estatisticasData,
                    backgroundColor: estatisticasColors,
                    borderRadius: 4,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    font: {
                        size: 7,
                    },
                },
            },
        },
    });
}

// - Gráfico de Donut (Serviços)
const servicosLabels = window.DashboardData?.graficoServico?.labels || [
    "UCI",
    "Bloco Operatório",
    "Urgência",
    "Imagiologia",
    "Laboratório",
    "Esterilização",
];

const servicosData = window.DashboardData?.graficoServico?.data || [2, 2, 3, 1, 1, 1];
const servicosColors = [
    "#3b82f6",
    "#22c55e",
    "#a855f7",
    "#ef4444",
    "#f97316",
    "#06b6d4",
];

// Grafico de Donut
const canvas2 = document.getElementById("categoryDistributionChart2");
if (canvas2) {
    const ctx2 = canvas2.getContext("2d");
    const categoryDistributionChart2 = new Chart(ctx2, {
        type: "doughnut",
        data: {
            labels: servicosLabels,
            datasets: [
                {
                    data: servicosData,
                    backgroundColor: servicosColors,
                    borderWidth: 1,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "right",
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11,
                        },
                    },
                },
            },
        },
    });
}

// - Gráfico de Tendência (Manutenções)
const tendenciaLabels = window.DashboardData?.graficoManutencao?.labels || [
    "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"
];

const preventivaData = window.DashboardData?.graficoManutencao?.preventiva || [8, 10, 12, 9, 11, 14, 10, 18, 15, 12, 16, 20];
const corretivaData = window.DashboardData?.graficoManutencao?.corretiva || [3, 4, 5, 2, 6, 4, 7, 5, 3, 6, 4, 5];

// Grafico de Tendencia de Manuntencoes (Linhas)
const ctx3 = document.getElementById("maintenanceTrendChart");
if (ctx3) {
    new Chart(ctx3.getContext("2d"), {
        type: "line",
        data: {
            labels: tendenciaLabels,
            datasets: [
                {
                    label: "Preventiva",
                    data: preventivaData,
                    borderColor: "#3B82F6",
                    backgroundColor: "#3B82F6",
                    cubicInterpolationMode: "monotone",
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false,
                },
                {
                    label: "Corretiva",
                    data: corretivaData,
                    borderColor: "#F59E0B",
                    backgroundColor: "#F59E0B",
                    cubicInterpolationMode: "monotone",
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                x: {
                    grid: {
                        display: true,
                        drawBorder: false,
                    },
                },
                y: {
                    grid: {
                        display: true,
                        drawBorder: false,
                    },
                    beginAtZero: true,
                },
            },
        },
    });
}

// ===================
// 3. Tabelas e Pesquisas Simples
// ===================

// - Auto-submit global (keyup debounce) e disparo de input
document.addEventListener("DOMContentLoaded", function () {
    const searchInputs = document.querySelectorAll(".equipment-list-search-bar .search-bar-input");
    let debounceTimer;
    searchInputs.forEach((input) => {
        input.addEventListener("keyup", function (e) {
            // Ignorar teclas que não mudam o texto (setas, shift, control, etc)
            const ignoredKeys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Shift', 'Control', 'Alt', 'Meta', 'Escape', 'Tab'];
            if (ignoredKeys.includes(e.key)) return;

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (input.form) {
                    input.form.submit();
                }
            }, 600);
        });
    });
});

// - Esconder page-loading-overlay após o DOM carregar
window.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".search-bar-input, .person-search-input").forEach(input => {
        if (input.value.trim() !== "") {
            input.dispatchEvent(new Event("input", { bubbles: true }));
        }
    });

    const overlay = document.getElementById('page-loading-overlay');
    if (overlay) {
        overlay.classList.add('hidden');
        overlay.remove();
        document.body.classList.remove("overflow-hidden");
    }
});

// Pesquisa de Edifícios
const locationsSearchInput = document.querySelector(".equipment-list-search-bar .search-bar-input");
const locationsSearchForm = document.querySelector(".equipment-list-search-bar form");

if (locationsSearchForm) {
    locationsSearchForm.addEventListener("submit", function (e) {
        e.preventDefault();
    });
}

if (locationsSearchInput) {
    function applyLocationsFilter() {
        const query = locationsSearchInput.value.trim().toLowerCase();
        const locationsBuildingCards = document.querySelectorAll(".locations");
        let anyVisible = false;
        locationsBuildingCards.forEach(function (card) {
            const nameEl = card.querySelector(".building-row p.fw-700");
            const hiddenIdEl = card.querySelector(".building-row .visually-hidden");
            if (nameEl) {
                const name = nameEl.textContent.trim().toLowerCase();
                const hiddenId = hiddenIdEl ? hiddenIdEl.textContent.trim().toLowerCase() : "";
                const isMatch = !query || name.includes(query) || hiddenId.includes(query);
                card.classList.toggle("d-none", !isMatch);
                if (isMatch) {
                    anyVisible = true;
                }
            }
        });
        const emptyState = document.getElementById("locations-empty-state");
        if (emptyState) {
            emptyState.classList.toggle("d-none", anyVisible);
        }
    }

    applyLocationsFilter();
}

// - DataTables: Equipamentos, Fornecedores, Utilizadores
if (
    document.getElementById("equipmentsTable") &&
    typeof simpleDatatables !== "undefined"
) {
    const table = new simpleDatatables.DataTable("#equipmentsTable", {
        searchable: false,
        paging: false,
        sortable: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });

    table.on('datatable.page', initTooltips);
    table.on('datatable.sort', initTooltips);
    table.on('datatable.search', initTooltips);
}

if (
    document.getElementById("suppliersTable") &&
    typeof simpleDatatables !== "undefined"
) {
    const table = new simpleDatatables.DataTable("#suppliersTable", {
        searchable: true,
        perPage: 8,
        perPageSelect: false,
        labels: {
            placeholder: "Pesquisar...",
            perPage: "entradas por página",
            noRows: "Nenhum registo encontrado",
            noResults: "Nenhum resultado corresponde à sua pesquisa",
            info: "A mostrar {start}–{end} de {rows}",
        },
    });

    const searchInput = document.getElementById("search-input-suppliers");
    const filterType = document.getElementById("filter-type-suppliers");

    function applySupplierFilters() {
        const searchVal = searchInput ? searchInput.value.trim() : "";
        const typeVal = filterType ? filterType.value : "";

        if (typeof table.multiSearch === 'function') {
            let queries = [];
            if (searchVal) queries.push({ terms: [searchVal] });
            if (typeVal) queries.push({ terms: [typeVal], columns: [1] });

            if (queries.length > 0) {
                table.multiSearch(queries);
            } else {
                table.search("");
            }
        } else {
            let terms = [];
            if (searchVal) terms.push(searchVal);
            if (typeVal) terms.push(typeVal);
            table.search(terms.join(" "));
        }
    }

    if (searchInput) searchInput.addEventListener("input", applySupplierFilters);
    if (filterType) filterType.addEventListener("change", applySupplierFilters);
}

if (
    document.getElementById("usersTable") &&
    typeof simpleDatatables !== "undefined"
) {
    const table = new simpleDatatables.DataTable("#usersTable", {
        searchable: true,
        perPage: 8,
        perPageSelect: false,
        labels: {
            placeholder: "Pesquisar...",
            perPage: "entradas por página",
            noRows: "Nenhum registo encontrado",
            noResults: "Nenhum resultado corresponde à sua pesquisa",
            info: "A mostrar {start}–{end} de {rows}",
        },
    });

    const searchInput = document.getElementById("search-input-users");
    const filterType = document.getElementById("filter-type-users");

    function applyUserFilters() {
        const searchVal = searchInput ? searchInput.value.trim() : "";
        const typeVal = filterType ? filterType.value : "";

        if (typeof table.multiSearch === 'function') {
            let queries = [];
            if (searchVal) queries.push({ terms: [searchVal] });
            if (typeVal) queries.push({ terms: [typeVal], columns: [1] });

            if (queries.length > 0) {
                table.multiSearch(queries);
            } else {
                table.search("");
            }
        } else {
            let terms = [];
            if (searchVal) terms.push(searchVal);
            if (typeVal) terms.push(typeVal);
            table.search(terms.join(" "));
        }
    }

    if (searchInput) searchInput.addEventListener("input", applyUserFilters);
    if (filterType) filterType.addEventListener("change", applyUserFilters);
}

// - DataTables: Funcionalidades, Documentos, Componentes
if (
    document.getElementById("featuresTable") &&
    typeof simpleDatatables !== "undefined"
) {
    new simpleDatatables.DataTable("#featuresTable", {
        searchable: false,
        perPage: 10,
        perPageSelect: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });
}

// Inicializar DataTables (Documentos)
if (
    document.getElementById("documentsTable") &&
    typeof simpleDatatables !== "undefined"
) {
    new simpleDatatables.DataTable("#documentsTable", {
        searchable: false,
        perPage: 10,
        perPageSelect: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });
}

// Inicializar DataTables (Componentes)
if (
    document.getElementById("componentsTable") &&
    typeof simpleDatatables !== "undefined"
) {
    new simpleDatatables.DataTable("#componentsTable", {
        searchable: false,
        perPage: 10,
        perPageSelect: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });
}

// - DataTables: Garantias, Manutenções
if (
    document.getElementById("warrantiesTable") &&
    typeof simpleDatatables !== "undefined"
) {
    new simpleDatatables.DataTable("#warrantiesTable", {
        searchable: false,
        perPage: 10,
        perPageSelect: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });
}


// Inicializar DataTables (Manutenções)
if (
    document.getElementById("maintenancesTable") &&
    typeof simpleDatatables !== "undefined"
) {
    new simpleDatatables.DataTable("#maintenancesTable", {
        searchable: false,
        perPage: 10,
        perPageSelect: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });
}

// - DataTables: Auditoria, Global Audit Logs, Reciclagem
if (document.getElementById("auditTable") && typeof simpleDatatables !== "undefined") {
    new simpleDatatables.DataTable("#auditTable", {
        searchable: false,
        perPage: 10,
        perPageSelect: false,
        labels: {
            noRows: "Nenhum registo encontrado",
            info: "",
        },
    });
}

// Inicializar DataTables (Global Audit Logs)
if (
    document.getElementById("globalAuditTable") &&
    typeof simpleDatatables !== "undefined"
) {
    const table = new simpleDatatables.DataTable("#globalAuditTable", {
        searchable: true,
        perPage: 10,
        perPageSelect: [10, 25, 50, 100],
        labels: {
            placeholder: "Pesquisar...",
            perPage: "entradas por página",
            noRows: "Nenhum registo encontrado",
            noResults: "Nenhum resultado corresponde à sua pesquisa",
            info: "A mostrar {start}–{end} de {rows}",
        },
    });

    table.on('datatable.page', initTooltips);
    table.on('datatable.sort', initTooltips);
    table.on('datatable.search', initTooltips);
    table.on('datatable.update', initTooltips);

    // Pesquisa personalizada global audit logs
    const searchInput = document.getElementById("search-global-audit");
    const filterType = document.getElementById("filter-global-audit-type");

    function applyGlobalAuditFilters() {
        const searchVal = searchInput ? searchInput.value.trim() : "";
        const typeVal = filterType ? filterType.value : "";

        if (typeof table.multiSearch === 'function') {
            let queries = [];
            if (searchVal) queries.push({ terms: [searchVal] });
            if (typeVal) queries.push({ terms: [typeVal], columns: [1] });

            if (queries.length > 0) {
                table.multiSearch(queries);
            } else {
                table.search("");
            }
        } else {
            let terms = [];
            if (searchVal) terms.push(searchVal);
            if (typeVal) terms.push(typeVal);
            table.search(terms.join(" "));
        }
    }

    if (searchInput) {
        searchInput.addEventListener("input", applyGlobalAuditFilters);
    }

    if (filterType) {
        filterType.addEventListener("change", applyGlobalAuditFilters);
    }
}

// Inicializar DataTables (Reciclagem)
if (document.getElementById("recyclingTable") && typeof simpleDatatables !== "undefined") {
    const table = new simpleDatatables.DataTable("#recyclingTable", {
        searchable: true,
        perPage: 10,
        perPageSelect: false,
        labels: {
            placeholder: "Pesquisar...",
            perPage: "entradas por página",
            noRows: "Nenhum registo encontrado",
            noResults: "Nenhum resultado corresponde à sua pesquisa",
            info: "A mostrar {start}–{end} de {rows}",
        },
    });

    table.on('datatable.page', initTooltips);
    table.on('datatable.sort', initTooltips);
    table.on('datatable.search', initTooltips);
    table.on('datatable.update', initTooltips);

    const searchInput = document.getElementById("search-input-recycling");
    const filterType = document.getElementById("filter-type-recycling");

    function applyRecyclingFilters() {
        const searchVal = searchInput ? searchInput.value.trim() : "";
        const typeVal = filterType ? filterType.value : "";

        if (typeof table.multiSearch === 'function') {
            let queries = [];
            if (searchVal) queries.push({ terms: [searchVal] });
            if (typeVal) queries.push({ terms: [typeVal], columns: [1] });

            if (queries.length > 0) {
                table.multiSearch(queries);
            } else {
                table.search("");
            }
        } else {
            let terms = [];
            if (searchVal) terms.push(searchVal);
            if (typeVal) terms.push(typeVal);
            table.search(terms.join(" "));
        }
    }

    if (searchInput) searchInput.addEventListener("input", applyRecyclingFilters);
    if (filterType) filterType.addEventListener("change", applyRecyclingFilters);
}

// ===================
// 4. Componentes Extra e Plugins
// ===================

// - Inicialização do Flatpickr (Datas)
if (typeof flatpickr !== "undefined") {
    flatpickr("#equipment-purchase-date, [id^='equipment-purchase-date-']", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today",
    });
    flatpickr("#equipment-manufacture-date, [id^='equipment-manufacture-date-']", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today",
    });
    flatpickr("#last-maintenance-start-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today",
    });
    flatpickr("#last-maintenance-end-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today",
    });
    flatpickr("#person-start-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#warranty-start-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#warranty-end-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#edit-warranty-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#maintenance-start-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#maintenance-end-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#edit-maintenance-start-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
    flatpickr("#edit-maintenance-end-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
    });
}

// - Inicialização de Tooltips (Bootstrap)
function initTooltips() {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        // Apenas inicializa se ainda não estiver inicializado
        if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initTooltips();
});

// - Inicialização de Toasts 
document.addEventListener("DOMContentLoaded", function () {
    const toastElList = document.querySelectorAll('.toast-container .toast');
    const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
    toastList.forEach(toast => toast.show());
});

// - Customização de Ícones SVG em tempo real (Card Preview)
document.addEventListener("DOMContentLoaded", () => {
    // Função para atualizar a visualização do ícone SVG
    function updateIconPreview(inputValue, previewElement) {
        if (!previewElement) return;
        previewElement.innerHTML = inputValue.trim();
    }

    // Escutar alterações nos dropdowns de seleção de ícone
    document.addEventListener("change", (event) => {
        if (event.target.classList.contains("card-icon-select")) {
            const select = event.target;
            const form = select.closest("form");
            if (!form) return;

            const textContainer = form.querySelector(".custom-textarea-container");
            const textarea = form.querySelector(".card-custom-icon-textarea");
            const preview = form.querySelector(".icon-preview-svg");

            if (select.value === "other") {
                if (textContainer) textContainer.classList.remove("d-none");
                if (textarea) {
                    textarea.required = true;
                    updateIconPreview(textarea.value, preview);
                }
            } else {
                if (textContainer) textContainer.classList.add("d-none");
                if (textarea) {
                    textarea.required = false;
                }
                const selectedOption = select.options[select.selectedIndex];
                const predefinedSvg = selectedOption ? selectedOption.getAttribute("data-svg") : "";
                updateIconPreview(predefinedSvg || "", preview);
            }
        }
    });

    // Escutar input de caminho SVG personalizado
    document.addEventListener("input", (event) => {
        if (event.target.classList.contains("card-custom-icon-textarea")) {
            const textarea = event.target;
            const form = textarea.closest("form");
            if (!form) return;

            const select = form.querySelector(".card-icon-select");
            if (select && select.value !== "other") return; // Apenas atualiza se for "other"

            const preview = form.querySelector(".icon-preview-svg");
            updateIconPreview(textarea.value, preview);
        }
    });

    // Lógica para mostrar a barra de alterações pendentes na gestão de conteúdos
    const contentForm = document.querySelector(".content-management form");
    const contentChangesBar = document.querySelector(".content-management .inbox-changes-container");
    if (contentForm && contentChangesBar) {
        contentForm.addEventListener("input", () => {
            contentChangesBar.style.setProperty("display", "flex", "important");
        });
        contentForm.addEventListener("change", () => {
            contentChangesBar.style.setProperty("display", "flex", "important");
        });
    }

    // Validação Modal Criação de Utilizador
    const userEmailInput = document.getElementById('user-email');
    const userAuthEmailInput = document.getElementById('user-auth-email');
    const userPasswordInput = document.getElementById('user-password');
    const userRoleInput = document.getElementById('user-role');
    const userBtnSubmit = document.getElementById('btn-submit-user-modal');

    if (userEmailInput && userAuthEmailInput && userPasswordInput && userRoleInput && userBtnSubmit) {
        function validateUserForm() {
            const isEmailValid = userEmailInput.value.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(userEmailInput.value);
            const isAuthEmailValid = userAuthEmailInput.value.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(userAuthEmailInput.value);
            const isPasswordValid = userPasswordInput.value.length >= 8;
            const isRoleValid = userRoleInput.value !== '';

            if (isEmailValid && isAuthEmailValid && isPasswordValid && isRoleValid) {
                userBtnSubmit.removeAttribute('disabled');
            } else {
                userBtnSubmit.setAttribute('disabled', 'true');
            }
        }

        userEmailInput.addEventListener('input', validateUserForm);
        userAuthEmailInput.addEventListener('input', validateUserForm);
        userPasswordInput.addEventListener('input', validateUserForm);
        userRoleInput.addEventListener('change', validateUserForm);
    }

    // Validação Modal Criação de Edifício
    const buildingNameInput = document.getElementById('building-name');
    const buildingBtnSubmit = document.getElementById('btn-submit-building-modal');

    if (buildingNameInput && buildingBtnSubmit) {
        function validateBuildingForm() {
            const isNameValid = buildingNameInput.value.trim() !== '';

            if (isNameValid) {
                buildingBtnSubmit.removeAttribute('disabled');
            } else {
                buildingBtnSubmit.setAttribute('disabled', 'true');
            }
        }

        buildingNameInput.addEventListener('input', validateBuildingForm);
    }

    // Validação Modais de Edição de Edifício
    const editBuildingForms = document.querySelectorAll('.building-edit-form');
    editBuildingForms.forEach(form => {
        const nameInput = form.querySelector('.building-edit-name');
        const submitBtn = form.querySelector('.btn-edit-building-submit');

        if (nameInput && submitBtn) {
            function validateEditBuildingForm() {
                const isNameValid = nameInput.value.trim() !== '';

                if (isNameValid) {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateEditBuildingForm);
        }
    });

    // Validação Modais de Criação de Piso
    const createFloorForms = document.querySelectorAll('.floor-create-form');
    createFloorForms.forEach(form => {
        const nameInput = form.querySelector('.floor-create-name');
        const submitBtn = form.querySelector('.btn-create-floor-submit');

        if (nameInput && submitBtn) {
            function validateCreateFloorForm() {
                if (nameInput.value.trim() !== '') {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateCreateFloorForm);
        }
    });

    // Validação Modais de Edição de Piso
    const editFloorForms = document.querySelectorAll('.floor-edit-form');
    editFloorForms.forEach(form => {
        const nameInput = form.querySelector('.floor-edit-name');
        const submitBtn = form.querySelector('.btn-edit-floor-submit');

        if (nameInput && submitBtn) {
            function validateEditFloorForm() {
                if (nameInput.value.trim() !== '') {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateEditFloorForm);
        }
    });

    // Validação Modais de Criação de Serviço
    const createServiceForms = document.querySelectorAll('.service-create-form');
    createServiceForms.forEach(form => {
        const nameInput = form.querySelector('.service-create-name');
        const submitBtn = form.querySelector('.btn-create-service-submit');

        if (nameInput && submitBtn) {
            function validateCreateServiceForm() {
                if (nameInput.value.trim() !== '') {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateCreateServiceForm);
        }
    });

    // Validação Modais de Edição de Serviço
    const editServiceForms = document.querySelectorAll('.service-edit-form');
    editServiceForms.forEach(form => {
        const nameInput = form.querySelector('.service-edit-name');
        const submitBtn = form.querySelector('.btn-edit-service-submit');

        if (nameInput && submitBtn) {
            function validateEditServiceForm() {
                if (nameInput.value.trim() !== '') {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateEditServiceForm);
        }
    });

    // Validação Modais de Criação de Sala
    const createRoomForms = document.querySelectorAll('.room-create-form');
    createRoomForms.forEach(form => {
        const nameInput = form.querySelector('.room-create-name');
        const submitBtn = form.querySelector('.btn-create-room-submit');

        if (nameInput && submitBtn) {
            function validateCreateRoomForm() {
                if (nameInput.value.trim() !== '') {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateCreateRoomForm);
        }
    });

    // Validação Modais de Edição de Sala
    const editRoomForms = document.querySelectorAll('.room-edit-form');
    editRoomForms.forEach(form => {
        const nameInput = form.querySelector('.room-edit-name');
        const submitBtn = form.querySelector('.btn-edit-room-submit');

        if (nameInput && submitBtn) {
            function validateEditRoomForm() {
                if (nameInput.value.trim() !== '') {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            nameInput.addEventListener('input', validateEditRoomForm);
        }
    });

    // Validação Modais de Edição de Utilizador
    const editUserForms = document.querySelectorAll('.user-edit-form');
    editUserForms.forEach(form => {
        const authEmailInput = form.querySelector('.user-edit-email-input');
        const passwordInput = form.querySelector('.user-edit-password-input');
        const roleInput = form.querySelector('.user-edit-role-input');
        const submitBtn = form.querySelector('.user-edit-submit-btn');

        if (authEmailInput && passwordInput && roleInput && submitBtn) {
            function validateEditForm() {
                const isAuthEmailValid = authEmailInput.value.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(authEmailInput.value);
                const isPasswordValid = passwordInput.value === '' || passwordInput.value.length >= 8;
                const isRoleValid = roleInput.value !== '';

                if (isAuthEmailValid && isPasswordValid && isRoleValid) {
                    submitBtn.removeAttribute('disabled');
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                }
            }

            authEmailInput.addEventListener('input', validateEditForm);
            passwordInput.addEventListener('input', validateEditForm);
            roleInput.addEventListener('change', validateEditForm);
        }
    });

    // Validação Modal Criação de Pessoa
    const personNameInput = document.getElementById('person-name');
    const personNifInput = document.getElementById('person-nif');
    const personRoleInput = document.getElementById('person-role');
    const personDeptInput = document.getElementById('person-department');
    const personEmailInput = document.getElementById('person-email');
    const personPhoneInput = document.getElementById('person-phone');
    const personBtnSubmit = document.getElementById('btn-submit-modal');

    if (personNameInput && personNifInput && personRoleInput && personDeptInput && personEmailInput && personPhoneInput && personBtnSubmit) {
        function validatePersonCreationForm() {
            const isNameValid = personNameInput.value.trim() !== '';
            const isNifValid = /^\d{9}$/.test(personNifInput.value.trim());
            const isRoleValid = personRoleInput.value !== '';
            const isDeptValid = personDeptInput.value.trim() !== '';
            const isEmailValid = personEmailInput.value.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(personEmailInput.value.trim());
            const isPhoneValid = personPhoneInput.value.trim() !== '';

            if (isNameValid && isNifValid && isRoleValid && isDeptValid && isEmailValid && isPhoneValid) {
                personBtnSubmit.removeAttribute('disabled');
            } else {
                personBtnSubmit.setAttribute('disabled', 'true');
            }
        }

        personNameInput.addEventListener('input', validatePersonCreationForm);
        personNifInput.addEventListener('input', validatePersonCreationForm);
        personRoleInput.addEventListener('change', validatePersonCreationForm);
        personDeptInput.addEventListener('input', validatePersonCreationForm);
        personEmailInput.addEventListener('input', validatePersonCreationForm);
        personPhoneInput.addEventListener('input', validatePersonCreationForm);
    }

    // Filtro e Pesquisa na Gestão de Pessoas
    const personSearchInput = document.querySelector(".person-search-input");
    const personRoleFilter = document.getElementById("filter-role");
    const personCards = document.querySelectorAll(".people-management .bento-card");

    if (personSearchInput || personRoleFilter) {
        function applyPersonFilters() {
            const searchTerm = personSearchInput ? personSearchInput.value.toLowerCase().trim() : "";
            const roleTerm = personRoleFilter ? personRoleFilter.value.toLowerCase().trim() : "";
            let anyVisible = false;

            personCards.forEach(card => {
                const nameEl = card.querySelector(".person-name");
                const emailEl = card.querySelector(".person-email");
                const roleEl = card.querySelector(".person-role");
                const hiddenIdEl = card.querySelector(".visually-hidden");

                const name = nameEl ? nameEl.textContent.toLowerCase() : "";
                const email = emailEl ? emailEl.textContent.toLowerCase() : "";
                const role = roleEl ? roleEl.textContent.toLowerCase() : "";
                const hiddenId = hiddenIdEl ? hiddenIdEl.textContent.toLowerCase() : "";

                const matchesSearch = searchTerm === "" || name.includes(searchTerm) || email.includes(searchTerm) || hiddenId.includes(searchTerm);
                const matchesRole = roleTerm === "" || role === roleTerm;

                if (matchesSearch && matchesRole) {
                    card.classList.remove("d-none");
                    anyVisible = true;
                } else {
                    card.classList.add("d-none");
                }
            });

            const emptyState = document.getElementById("people-empty-state");
            if (emptyState) {
                emptyState.classList.toggle("d-none", anyVisible);
            }
        }

        if (personRoleFilter) {
            personRoleFilter.addEventListener("change", applyPersonFilters);
        }
        applyPersonFilters();
    }
});

// ===================
// 5. Uploads de Ficheiros
// ===================

// - Lógica base de Drag & Drop de Documentos
function handleFiles(files) {
    if (!uploadTemplate || !localUploadContainer) return;
    const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];
    const maxSize = 25 * 1024 * 1024; // 25MB

    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Validação de tipo
        if (
            !allowedTypes.includes(file.type) &&
            !file.name.match(/\.(pdf|jpe?g|png)$/i)
        ) {
            alert(
                `O ficheiro "${file.name}" tem um formato inválido. Apenas PDF, JPG e PNG são permitidos.`,
            );
            continue;
        }

        // Validação de tamanho (máx 25MB)
        if (file.size > maxSize) {
            alert(`O ficheiro "${file.name}" excede o tamanho máximo de 25MB.`);
            continue;
        }

        // Criar o card do ficheiro a partir do template
        const clone = uploadTemplate.content.cloneNode(true);
        const card = clone.querySelector(".uploaded-file-card");
        const nameDisplay = clone.querySelector(".file-name-display");
        const closeBtn = clone.querySelector(".btn-close-file");

        // Atualizar os dados
        nameDisplay.textContent = file.name;
        nameDisplay.title = file.name;

        // Popular o input escondido do template com o ficheiro selecionado
        const hiddenInput = clone.querySelector(".real-file-input");
        if (hiddenInput) {
            const dt = new DataTransfer();
            dt.items.add(file);
            hiddenInput.files = dt.files;
        }

        // Lógica de remoção
        closeBtn.addEventListener("click", () => {
            card.remove();
            if (window.validatePage2) window.validatePage2();
        });

        // Adicionar ao container
        localUploadContainer.appendChild(clone);
        if (window.validatePage2) window.validatePage2();
    }
}

// - Input de Multi-Upload
const multiFileInput = document.getElementById("document-upload-input");
if (multiFileInput) {
    multiFileInput.addEventListener("change", (e) => {
        if (typeof handleFiles === 'function') handleFiles(e.target.files);
        multiFileInput.value = "";
    });
}

// Lógica de Upload de Documentos Genérica
const uploadTemplate = document.getElementById("uploaded-file-template");
const localUploadContainer = document.getElementById("uploaded-files-container");

document.querySelectorAll(".file-upload-zone").forEach(zone => {
    const targetId = zone.getAttribute("data-dropzone-target");
    const textTargetId = zone.getAttribute("data-text-target");
    let fileInput = targetId ? document.getElementById(targetId) : multiFileInput;
    const isMulti = !targetId;

    if (!fileInput) return;

    // Abrir o file picker ao clicar na zona
    zone.addEventListener("click", () => {
        fileInput.click();
    });

    // Eventos Drag Drop
    zone.addEventListener("dragover", (e) => {
        e.preventDefault();
        zone.style.borderColor = "var(--primary-500)";
        zone.style.backgroundColor = "var(--primary-50)";
    });

    zone.addEventListener("dragleave", (e) => {
        e.preventDefault();
        zone.style.borderColor = "";
        zone.style.backgroundColor = "";
    });

    zone.addEventListener("drop", (e) => {
        e.preventDefault();
        zone.style.borderColor = "";
        zone.style.backgroundColor = "";

        if (e.dataTransfer.files.length > 0) {
            if (isMulti) {
                if (typeof handleFiles === 'function') handleFiles(e.dataTransfer.files);
            } else {
                const file = e.dataTransfer.files[0];
                const maxSize = 25 * 1024 * 1024; // 25MB
                const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];

                if (!allowedTypes.includes(file.type) && !file.name.match(/\.(pdf|jpe?g|png)$/i)) {
                    alert(`O ficheiro "${file.name}" tem um formato inválido. Apenas PDF, JPG e PNG são permitidos.`);
                    return;
                }

                if (file.size > maxSize) {
                    alert(`O ficheiro "${file.name}" excede o tamanho máximo de 25MB.`);
                    return;
                }

                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });

    // Se for input único, atualiza o texto quando muda e valida
    if (!isMulti && textTargetId) {
        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const maxSize = 25 * 1024 * 1024; // 25MB
                const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];

                if (!allowedTypes.includes(file.type) && !file.name.match(/\.(pdf|jpe?g|png)$/i)) {
                    alert(`O ficheiro "${file.name}" tem um formato inválido. Apenas PDF, JPG e PNG são permitidos.`);
                    e.target.value = ""; // Limpa o input
                    return;
                }

                if (file.size > maxSize) {
                    alert(`O ficheiro "${file.name}" excede o tamanho máximo de 25MB.`);
                    e.target.value = ""; // Limpa o input
                    return;
                }

                const textEl = document.getElementById(textTargetId);
                if (textEl) {
                    textEl.textContent = file.name;
                    textEl.classList.remove("text-muted");
                    textEl.classList.add("text-primary-700", "fw-600");
                }
            }
        });
    }
});

// ===================
// 6. Validações e Formulários Base
// ===================

// Tab documentos
document.addEventListener("DOMContentLoaded", function () {
    const addDocModals = document.querySelectorAll('div[id^="add-document-modal-"]');

    addDocModals.forEach(modal => {
        const form = modal.querySelector('form');
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        const nameInput = form.querySelector('input[name="doc-name"]');
        const typeSelect = form.querySelector('select[name="doc-type"]');
        const fileInput = form.querySelector('input[type="file"][name="doc-file"]');

        if (!submitBtn || !nameInput || !typeSelect || !fileInput) return;

        const validateDocForm = () => {
            const isNameValid = nameInput.value.trim().length > 0;
            const isTypeValid = typeSelect.value !== "";
            const isFileValid = fileInput.files && fileInput.files.length > 0;

            if (isNameValid && isTypeValid && isFileValid) {
                submitBtn.removeAttribute("disabled");
            } else {
                submitBtn.setAttribute("disabled", "true");
            }
        };

        // Listeners
        nameInput.addEventListener("input", validateDocForm);
        typeSelect.addEventListener("change", validateDocForm);
        fileInput.addEventListener("change", validateDocForm);

        // Resetar o formulário quando o modal é fechado
        modal.addEventListener("hidden.bs.modal", () => {
            form.reset();

            // Resetar o texto de exibição do arquivo personalizado
            const dropzone = modal.querySelector('.file-upload-zone');
            if (dropzone) {
                const textTargetId = dropzone.getAttribute('data-text-target');
                if (textTargetId) {
                    const textEl = document.getElementById(textTargetId);
                    if (textEl) {
                        textEl.textContent = "PDF, JPG, PNG — máx. 25MB";
                        textEl.classList.remove("text-primary-700", "fw-600");
                        textEl.classList.add("text-muted");
                    }
                }
            }

            validateDocForm();
        });

        // Validação inicial
        validateDocForm();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editDocModals = document.querySelectorAll('div[id^="edit-document-modal-"]');

    editDocModals.forEach(modal => {
        const form = modal.querySelector('form');
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        const nameInput = form.querySelector('input[name="doc-name"]');
        const typeSelect = form.querySelector('select[name="doc-type"]');

        if (!submitBtn || !nameInput || !typeSelect) return;

        const validateDocEditForm = () => {
            const isNameValid = nameInput.value.trim().length > 0;
            const isTypeValid = typeSelect.value !== "";

            if (isNameValid && isTypeValid) {
                submitBtn.removeAttribute("disabled");
            } else {
                submitBtn.setAttribute("disabled", "true");
            }
        };

        // Listeners
        nameInput.addEventListener("input", validateDocEditForm);
        typeSelect.addEventListener("change", validateDocEditForm);

        // Resetar o formulário quando o modal é fechado
        modal.addEventListener("hidden.bs.modal", () => {
            form.reset();
            validateDocEditForm();
        });

        // Validação inicial
        validateDocEditForm();
    });
});

// Formulário de login
document.addEventListener("DOMContentLoaded", function () {
    const loginEmailInput = document.getElementById("email");
    const loginPasswordInput = document.getElementById("password");
    const loginSubmitBtn = document.getElementById("login-submit-btn");

    if (loginEmailInput && loginPasswordInput && loginSubmitBtn) {
        const validateLoginForm = () => {
            const isEmailValid = loginEmailInput.value.trim().length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(loginEmailInput.value);
            const isPasswordValid = loginPasswordInput.value.trim().length > 0;

            if (isEmailValid && isPasswordValid) {
                loginSubmitBtn.removeAttribute("disabled");
            } else {
                loginSubmitBtn.setAttribute("disabled", "true");
            }
        };

        [loginEmailInput, loginPasswordInput].forEach(input => {
            input.addEventListener("input", validateLoginForm);
            input.addEventListener("change", validateLoginForm);
        });

        validateLoginForm();
    }
});

// - Lógica Completa de Equipamentos (Paginação, Filtros, Checkboxes e Validações Criar/Editar)
// Lógica de Abertura, Fecho e Submissão do Modal
const createEquipmentModal = document.getElementById(
    "equipment-creation-modal",
);
const btnOpenCreateModal = document.getElementById(
    "btn-open-create-equipment-modal",
);
const btnSubmitModal = document.getElementById("btn-submit-modal");

// Lógica de Paginação e Validação do Modal de Criação de Equipamento
const btnNextPage = document.getElementById("btn-next-page");
const btnPrevPage = document.getElementById("btn-prev-page");
const modalPage1 = document.getElementById("modal-page-1");
const modalPage2 = document.getElementById("modal-page-2");

let bsCreateEquipmentModal = null;
if (createEquipmentModal) {
    bsCreateEquipmentModal = new bootstrap.Modal(createEquipmentModal);

    // Reset à primeira página do modal quando fechado
    createEquipmentModal.addEventListener("hidden.bs.modal", () => {
        if (modalPage1 && modalPage2) {
            modalPage1.classList.remove("d-none");
            modalPage1.classList.add("d-flex");
            modalPage2.classList.remove("d-flex");
            modalPage2.classList.add("d-none");
        }
    });
}

if (btnOpenCreateModal && bsCreateEquipmentModal) {
    btnOpenCreateModal.addEventListener("click", () => {
        bsCreateEquipmentModal.show();
    });
}


document.addEventListener("DOMContentLoaded", () => {
    const editForms = document.querySelectorAll(".form-edit-equipment");

    editForms.forEach((form) => {
        // Encontrar os elementos do form
        const eqId = form.querySelector('input[name="equipment-id"]').value;
        const btnNextPage = form.querySelector(`#btn-next-page-edit-${eqId}`);
        const btnPrevPage = form.querySelector(`#btn-prev-page-edit-${eqId}`);
        const modalPage1 = form.querySelector(`#modal-page-1-edit-${eqId}`);
        const modalPage2 = form.querySelector(`#modal-page-2-edit-${eqId}`);

        // Instância do Modal
        const modalEl = document.getElementById(`equipment-edit-modal-${eqId}`);
        if (modalEl) {
            modalEl.addEventListener("hidden.bs.modal", () => {
                if (modalPage1 && modalPage2) {
                    modalPage1.classList.remove("d-none");
                    modalPage1.classList.add("d-flex");
                    modalPage2.classList.remove("d-flex");
                    modalPage2.classList.add("d-none");
                }
            });
        }

        // Navegação
        if (btnNextPage && btnPrevPage && modalPage1 && modalPage2) {
            btnNextPage.addEventListener("click", () => {
                modalPage1.classList.add("d-none");
                modalPage2.classList.remove("d-none");
            });

            btnPrevPage.addEventListener("click", () => {
                modalPage2.classList.add("d-none");
                modalPage1.classList.remove("d-none");
            });
        }

        // Validação da Página 1
        const codeInput = form.querySelector(`#equipment-code-${eqId}`);
        const categorySelect = form.querySelector(`#equipment-category-${eqId}`);
        const serialInput = form.querySelector(`#equipment-serial-${eqId}`);
        const nameInput = form.querySelector(`#equipment-name-${eqId}`);
        const brandSelect = form.querySelector(`#equipment-brand-${eqId}`);
        const statusSelect = form.querySelector(`#equipment-status-${eqId}`);
        const locationSelect = form.querySelector(`#equipment-location-${eqId}`);
        const purchaseDateInput = form.querySelector(`#equipment-purchase-date-${eqId}`);
        const manufactureDateInput = form.querySelector(`#equipment-manufacture-date-${eqId}`);

        const validatePage1 = () => {
            const parseDMY = (dateStr) => {
                if (!dateStr) return null;
                const parts = dateStr.split("/");
                if (parts.length === 3) {
                    return new Date(parts[2], parts[1] - 1, parts[0]);
                }
                return null;
            };
            const pDate = purchaseDateInput ? parseDMY(purchaseDateInput.value) : null;
            const mDate = manufactureDateInput ? parseDMY(manufactureDateInput.value) : null;
            const isDateValid = !(pDate && mDate && mDate > pDate);

            if (
                codeInput?.value.trim() !== "" &&
                categorySelect?.value !== "" &&
                serialInput?.value.trim() !== "" &&
                nameInput?.value.trim() !== "" &&
                brandSelect?.value !== "" &&
                statusSelect?.value !== "" &&
                locationSelect?.value !== "" &&
                isDateValid
            ) {
                btnNextPage?.removeAttribute("disabled");
            } else {
                btnNextPage?.setAttribute("disabled", "true");
            }
        };

        const attachValidation = (element, eventType) => {
            if (element) {
                element.addEventListener(eventType, validatePage1);
            }
        };

        attachValidation(codeInput, "input");
        attachValidation(categorySelect, "change");
        attachValidation(serialInput, "input");
        attachValidation(nameInput, "input");
        attachValidation(brandSelect, "change");
        attachValidation(statusSelect, "change");
        attachValidation(locationSelect, "change");
        attachValidation(purchaseDateInput, "change");
        attachValidation(manufactureDateInput, "change");

        // Chamada inicial para preencher corretamente o estado ativado/desativado do form
        validatePage1();

        // Filtragem de componentes pela categoria
        if (categorySelect) {
            categorySelect.addEventListener("change", (e) => {
                const selectedCategoryId = e.target.value;
                const componentItems = form.querySelectorAll(".multi-select-item[data-category-id]");
                const noComponentsMsg = form.querySelector(`#no-components-msg-edit-${eqId}`);
                let visibleCount = 0;

                componentItems.forEach(item => {
                    const itemCategoryId = item.getAttribute("data-category-id") || "";
                    const categoryIds = itemCategoryId.split(",");
                    if (selectedCategoryId === "" || categoryIds.includes(selectedCategoryId) || itemCategoryId === "") {
                        item.style.setProperty("display", "flex", "important");
                        visibleCount++;
                    } else {
                        item.style.setProperty("display", "none", "important");
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        if (checkbox && checkbox.checked) {
                            checkbox.checked = false;
                            const qtyContainer = item.querySelector(".multi-select-qty-container");
                            if (qtyContainer) {
                                qtyContainer.classList.add("d-none");
                            }
                        }
                    }
                });

                if (noComponentsMsg) {
                    if (visibleCount === 0) {
                        noComponentsMsg.classList.remove("d-none");
                    } else {
                        noComponentsMsg.classList.add("d-none");
                    }
                }
            });

            // Trigger inicial para aplicar o filtro e mostrar o empty state se necessário
            categorySelect.dispatchEvent(new Event("change"));
        }
    });
});

const equipmentCodeInput = document.getElementById("equipment-code");
const equipmentCategorySelect = document.getElementById("equipment-category");
const equipmentSerialInput = document.getElementById("equipment-serial");
const equipmentNameInput = document.getElementById("equipment-name");
const equipmentBrandSelect = document.getElementById("equipment-brand");
const equipmentStatusSelect = document.getElementById("equipment-status");
const equipmentLocationSelect = document.getElementById("equipment-location");
const equipmentPurchaseDateInput = document.getElementById("equipment-purchase-date");
const equipmentManufactureDateInput = document.getElementById("equipment-manufacture-date");

if (btnNextPage && btnPrevPage && modalPage1 && modalPage2) {
    // Validação da Página 1
    const validatePage1 = () => {
        const parseDMY = (dateStr) => {
            if (!dateStr) return null;
            const parts = dateStr.split("/");
            if (parts.length === 3) {
                return new Date(parts[2], parts[1] - 1, parts[0]);
            }
            return null;
        };
        const pDate = equipmentPurchaseDateInput ? parseDMY(equipmentPurchaseDateInput.value) : null;
        const mDate = equipmentManufactureDateInput ? parseDMY(equipmentManufactureDateInput.value) : null;
        const isDateValid = !(pDate && mDate && mDate > pDate);

        if (
            equipmentCodeInput?.value.trim() !== "" &&
            equipmentCategorySelect?.value !== "" &&
            equipmentSerialInput?.value.trim() !== "" &&
            equipmentNameInput?.value.trim() !== "" &&
            equipmentBrandSelect?.value !== "" &&
            equipmentStatusSelect?.value !== "" &&
            equipmentLocationSelect?.value !== "" &&
            isDateValid
        ) {
            btnNextPage.removeAttribute("disabled");
        } else {
            btnNextPage.setAttribute("disabled", "true");
        }
    };

    const attachValidation = (element, eventType) => {
        if (element) {
            element.addEventListener(eventType, validatePage1);
        }
    };

    attachValidation(equipmentCodeInput, "input");
    attachValidation(equipmentCategorySelect, "change");
    attachValidation(equipmentSerialInput, "input");
    attachValidation(equipmentNameInput, "input");
    attachValidation(equipmentBrandSelect, "change");
    attachValidation(equipmentStatusSelect, "change");
    attachValidation(equipmentLocationSelect, "change");
    attachValidation(equipmentPurchaseDateInput, "change");
    attachValidation(equipmentManufactureDateInput, "change");

    // Filtragem de componentes pela categoria selecionada
    if (equipmentCategorySelect) {
        equipmentCategorySelect.addEventListener("change", (e) => {
            const selectedCategoryId = e.target.value;
            const componentItems = document.querySelectorAll(".multi-select-item[data-category-id]");
            const noComponentsMsg = document.getElementById("no-components-msg");
            let visibleCount = 0;

            componentItems.forEach(item => {
                const itemCategoryId = item.getAttribute("data-category-id") || "";
                const categoryIds = itemCategoryId.split(",");
                // Se o componente não tiver categoria (vazio) ou pertencer à selecionada, mostra
                if (selectedCategoryId === "" || categoryIds.includes(selectedCategoryId) || itemCategoryId === "") {
                    item.classList.remove("d-none");
                    item.classList.add("d-flex");
                    visibleCount++;
                } else {
                    item.classList.remove("d-flex");
                    item.classList.add("d-none");
                    // Desmarcar componente se for escondido
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (checkbox && checkbox.checked) {
                        checkbox.checked = false;
                        const qtyContainer = item.querySelector(".multi-select-qty-container");
                        if (qtyContainer) {
                            qtyContainer.classList.add("d-none");
                        }
                    }
                }
            });

            if (noComponentsMsg) {
                if (visibleCount === 0) {
                    noComponentsMsg.classList.remove("d-none");
                } else {
                    noComponentsMsg.classList.add("d-none");
                }
            }
        });

        // Trigger inicial para aplicar o filtro e mostrar o empty state se necessário
        equipmentCategorySelect.dispatchEvent(new Event("change"));
    }

    // Navegação
    btnNextPage.addEventListener("click", () => {
        modalPage1.classList.add("d-none");
        modalPage2.classList.remove("d-none");
        if (typeof validatePage2 === 'function') validatePage2();
    });

    btnPrevPage.addEventListener("click", () => {
        modalPage2.classList.add("d-none");
        modalPage1.classList.remove("d-none");
    });

    // Validação de Datas de Manutenção
    const maintenanceStartDate = document.getElementById("last-maintenance-start-date");
    const maintenanceEndDate = document.getElementById("last-maintenance-end-date");

    const validateMaintenanceDates = () => {
        let valid = true;
        if (maintenanceStartDate && maintenanceEndDate && maintenanceStartDate.value && maintenanceEndDate.value) {
            const startDate = new Date(maintenanceStartDate.value);
            const endDate = new Date(maintenanceEndDate.value);

            if (startDate > endDate) {
                maintenanceEndDate.setCustomValidity("A data de fim não pode ser anterior à data de início.");
                maintenanceEndDate.reportValidity();
                valid = false;
            } else {
                maintenanceEndDate.setCustomValidity("");
            }
        } else if (maintenanceEndDate) {
            maintenanceEndDate.setCustomValidity("");
        }
        return valid;
    };

    const validatePage2 = () => {
        let isPage2Valid = true;

        if (!validateMaintenanceDates()) {
            isPage2Valid = false;
        }

        const docTypes = document.querySelectorAll('#uploaded-files-container .doc-type-select');
        docTypes.forEach(select => {
            if (select.value === "") {
                isPage2Valid = false;
            }
        });

        if (btnSubmitModal) {
            if (isPage2Valid) {
                btnSubmitModal.removeAttribute("disabled");
            } else {
                btnSubmitModal.setAttribute("disabled", "true");
            }
        }
    };
    window.validatePage2 = validatePage2;

    if (maintenanceStartDate) maintenanceStartDate.addEventListener("change", validatePage2);
    if (maintenanceEndDate) maintenanceEndDate.addEventListener("change", validatePage2);

    const localUploadContainer = document.getElementById("uploaded-files-container");
    if (localUploadContainer) {
        localUploadContainer.addEventListener("change", (e) => {
            if (e.target.classList.contains("doc-type-select")) {
                validatePage2();
            }
        });
    }

    const editMaintenanceForms = document.querySelectorAll(".edit-maintenance-form");
    editMaintenanceForms.forEach((form) => {
        const typeInput = form.querySelector(".edit-maintenance-type");
        const responsibleInput = form.querySelector(".edit-maintenance-responsible");
        const startDateInput = form.querySelector(".edit-maintenance-start-date");
        const endDateInput = form.querySelector(".edit-maintenance-end-date");
        const btnSubmit = form.querySelector(".btn-submit-edit-maintenance");

        if (typeInput && responsibleInput && startDateInput && btnSubmit) {
            const parseDate = (dateString) => {
                if (!dateString) return null;
                const parts = dateString.split("/");
                if (parts.length === 3) {
                    return new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
                }
                return null;
            };

            const validateForm = () => {
                let isValid = false;
                if (typeInput.value && responsibleInput.value && startDateInput.value) {
                    isValid = true;

                    if (endDateInput && endDateInput.value) {
                        const startDate = parseDate(startDateInput.value);
                        const endDate = parseDate(endDateInput.value);
                        if (!startDate || !endDate || endDate <= startDate) {
                            isValid = false;
                        }
                    }
                }

                if (isValid) {
                    btnSubmit.removeAttribute("disabled");
                } else {
                    btnSubmit.setAttribute("disabled", "true");
                }
            };

            validateForm();

            typeInput.addEventListener("change", validateForm);
            responsibleInput.addEventListener("change", validateForm);
            startDateInput.addEventListener("change", validateForm);
            startDateInput.addEventListener("input", validateForm);
            if (endDateInput) {
                endDateInput.addEventListener("change", validateForm);
                endDateInput.addEventListener("input", validateForm);
            }
        }
    });

}

const multiSelectCheckboxes = document.querySelectorAll(
    '.multi-select-form input[type="checkbox"]',
);
multiSelectCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        // Tenta encontrar o contentor de quantidade no item do componente
        const item = this.closest(".multi-select-item");
        if (item) {
            const qtyContainer = item.querySelector(".multi-select-qty-container");
            if (qtyContainer) {
                if (this.checked) {
                    qtyContainer.classList.remove("d-none");
                } else {
                    qtyContainer.classList.add("d-none");
                }
            }
        }

        // Atualizar o texto de contagem de checkboxes selecionadas
        const formItem = this.closest(".form-item");
        if (formItem) {
            const countLabel = formItem.querySelector(".multi-select-count-label");
            if (countLabel) {
                const checkedCount = formItem.querySelectorAll('.multi-select-form input[type="checkbox"]:checked').length;
                countLabel.textContent = `${checkedCount} selecionado(s)`;
            }
        }
    });
});

document.querySelectorAll(".form-item").forEach(formItem => {
    const countLabel = formItem.querySelector(".multi-select-count-label");
    if (countLabel) {
        const checkedCount = formItem.querySelectorAll('.multi-select-form input[type="checkbox"]:checked').length;
        countLabel.textContent = `${checkedCount} selecionado(s)`;
    }
});

// - Validação de Criação/Edição de Pessoas
const personNameInput = document.getElementById("person-name");
const personIdInput = document.getElementById("person-id");
const personRoleInput = document.getElementById("person-role");
const personDepartmentInput = document.getElementById("person-department");
const personEmailInput = document.getElementById("person-email");
const personPhoneInput = document.getElementById("person-phone");
const personStartDateInput = document.getElementById("person-start-date");
const personModalEl = document.getElementById("equipment-creation-modal");

if (personNameInput && personIdInput && btnSubmitModal) {
    const validatePersonForm = () => {
        if (
            personNameInput.value.trim() !== "" &&
            personIdInput.value.trim() !== ""
        ) {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validatePersonForm();

    personNameInput.addEventListener("input", validatePersonForm);
    personIdInput.addEventListener("input", validatePersonForm);

    if (personModalEl) {
        personModalEl.addEventListener("hidden.bs.modal", () => {
            personNameInput.value = "";
            personIdInput.value = "";
            if (personRoleInput) personRoleInput.value = "";
            if (personDepartmentInput) personDepartmentInput.value = "";
            if (personEmailInput) personEmailInput.value = "";
            if (personPhoneInput) personPhoneInput.value = "";
            if (personStartDateInput) personStartDateInput.value = "";

            // Resetar títulos e textos do modal
            const modalTitleEl = document.getElementById("equipmentModalLabel");
            const modalSubtitleEl = modalTitleEl
                ? modalTitleEl.nextElementSibling
                : null;
            if (modalTitleEl) modalTitleEl.textContent = "Nova Pessoa";
            if (modalSubtitleEl)
                modalSubtitleEl.textContent = "Informações do colaborador";
            if (btnSubmitModal) btnSubmitModal.textContent = "Criar Pessoa";

            validatePersonForm();
        });
    }
}

const personEditForms = document.querySelectorAll('.person-edit-form');
personEditForms.forEach(form => {
    const nameInput = form.querySelector('[name="person-name"]');
    const nifInput = form.querySelector('[name="person-nif"]');
    const roleInput = form.querySelector('[name="person-role"]');
    const deptInput = form.querySelector('[name="person-department"]');
    const emailInput = form.querySelector('[name="person-email"]');
    const phoneInput = form.querySelector('[name="person-phone"]');
    const submitBtn = form.querySelector('.btn-edit-submit');

    if (nameInput && nifInput && roleInput && deptInput && emailInput && phoneInput && submitBtn) {
        function validateEditForm() {
            const isNameValid = nameInput.value.trim() !== '';
            const isNifValid = /^\d{9}$/.test(nifInput.value.trim());
            const isRoleValid = roleInput.value !== '';
            const isDeptValid = deptInput.value.trim() !== '';
            const isEmailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim());
            const isPhoneValid = phoneInput.value.trim() !== '';

            if (isNameValid && isNifValid && isRoleValid && isDeptValid && isEmailValid && isPhoneValid) {
                submitBtn.removeAttribute('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'true');
            }
        }

        // Validar no início
        validateEditForm();

        nameInput.addEventListener('input', validateEditForm);
        nifInput.addEventListener('input', validateEditForm);
        roleInput.addEventListener('change', validateEditForm);
        deptInput.addEventListener('input', validateEditForm);
        emailInput.addEventListener('input', validateEditForm);
        phoneInput.addEventListener('input', validateEditForm);
    }
});


// - Validação Criação de Fornecedores
const supplierNameInput = document.getElementById("supplier-name");
const supplierNifInput = document.getElementById("supplier-nif");
const supplierTypeSelect = document.getElementById("supplier-type");
const supplierEmailInput = document.getElementById("supplier-email");
const supplierPhoneInput = document.getElementById("supplier-phone");
const supplierWebsiteInput = document.getElementById("supplier-website");
const supplierContactPersonSelect = document.getElementById(
    "supplier-contact-person",
);
const supplierModalEl = document.getElementById("equipment-creation-modal");

if (supplierNameInput && supplierNifInput && btnSubmitModal) {
    const validateSupplierForm = () => {
        if (
            supplierNameInput.value.trim() !== "" &&
            supplierNifInput.value.trim() !== ""
        ) {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validateSupplierForm();

    supplierNameInput.addEventListener("input", validateSupplierForm);
    supplierNifInput.addEventListener("input", validateSupplierForm);

    if (supplierModalEl) {
        supplierModalEl.addEventListener("hidden.bs.modal", () => {
            supplierNameInput.value = "";
            supplierNifInput.value = "";
            if (supplierTypeSelect) supplierTypeSelect.value = "Fabricante";
            if (supplierEmailInput) supplierEmailInput.value = "";
            if (supplierPhoneInput) supplierPhoneInput.value = "";
            if (supplierWebsiteInput) supplierWebsiteInput.value = "";
            if (supplierContactPersonSelect) supplierContactPersonSelect.value = "";
            validateSupplierForm();
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const supplierForm = document.getElementById("supplier-creation-form");
    if (supplierForm) {
        const inputs = supplierForm.querySelectorAll("input[required], select[required]");
        const submitButton = document.getElementById("btn-submit-modal");

        if (submitButton) {
            function checkFormValidity() {
                let isValid = true;
                inputs.forEach(input => {
                    if (!input.value.trim() || !input.checkValidity()) {
                        isValid = false;
                    }
                });
                submitButton.disabled = !isValid;
            }

            inputs.forEach(input => {
                input.addEventListener("input", checkFormValidity);
                input.addEventListener("change", checkFormValidity);
            });
            checkFormValidity();
        }
    }
});

// - Validação Criação/Edição de Componentes e Categorias
// Validação Componentes (Criar e Editar)
const componentForms = document.querySelectorAll("#form-create-component, .form-edit-component");

componentForms.forEach(form => {
    const inputs = form.querySelectorAll("input[required], select[required]");
    const stockActualInput = form.querySelector(".stock-actual-input, #component-stock-actual");
    const stockMinInput = form.querySelector(".stock-min-input, #component-stock-min");
    const submitButton = form.querySelector(".btn-submit-edit, #btn-submit-modal");

    if (submitButton) {
        function checkComponentFormValidity() {
            let isValid = true;

            if (stockActualInput && stockMinInput) {
                const actual = parseInt(stockActualInput.value || "0", 10);
                const min = parseInt(stockMinInput.value || "0", 10);
                if (min > actual) {
                    stockMinInput.setCustomValidity("O stock mínimo não pode ser superior ao stock atual.");
                } else {
                    stockMinInput.setCustomValidity("");
                }
            }

            inputs.forEach(input => {
                if (!input.value.trim() || !input.checkValidity()) {
                    isValid = false;
                }
            });

            submitButton.disabled = !isValid;
        }

        inputs.forEach(input => {
            input.addEventListener("input", checkComponentFormValidity);
            input.addEventListener("change", checkComponentFormValidity);
        });
        if (stockActualInput) stockActualInput.addEventListener("input", checkComponentFormValidity);
        if (stockMinInput) stockMinInput.addEventListener("input", checkComponentFormValidity);

        checkComponentFormValidity();
    }
});

const categoryNameInput = document.getElementById("category-name");
const categoryCodeInput = document.getElementById("category-code");
const categoryDescriptionInput = document.getElementById(
    "category-description",
);
const categoryModalEl = document.getElementById("category-creation-modal");

if (categoryNameInput && categoryCodeInput && categoryDescriptionInput && btnSubmitModal) {
    const validateCategoryForm = () => {
        if (
            categoryNameInput.value.trim() !== "" &&
            categoryCodeInput.value.trim() !== "" &&
            categoryDescriptionInput.value.trim() !== ""
        ) {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validateCategoryForm();

    categoryNameInput.addEventListener("input", validateCategoryForm);
    categoryCodeInput.addEventListener("input", validateCategoryForm);
    categoryDescriptionInput.addEventListener("input", validateCategoryForm);

    if (categoryModalEl) {
        categoryModalEl.addEventListener("hidden.bs.modal", () => {
            categoryNameInput.value = "";
            categoryCodeInput.value = "";
            if (categoryDescriptionInput) {
                categoryDescriptionInput.value = "";
            }
            validateCategoryForm();
        });
    }
}

document.querySelectorAll(".edit-category-form").forEach(form => {
    const nameInput = form.querySelector('input[name="category-name"]');
    const codeInput = form.querySelector('input[name="category-code"]');
    const descInput = form.querySelector('textarea[name="category-description"]');
    const submitBtn = form.querySelector('button[name="editar_categoria"]');

    if (nameInput && codeInput && descInput && submitBtn) {
        const validateEditForm = () => {
            if (
                nameInput.value.trim() !== "" &&
                codeInput.value.trim() !== "" &&
                descInput.value.trim() !== ""
            ) {
                submitBtn.removeAttribute("disabled");
            } else {
                submitBtn.setAttribute("disabled", "true");
            }
        };

        validateEditForm();

        nameInput.addEventListener("input", validateEditForm);
        codeInput.addEventListener("input", validateEditForm);
        descInput.addEventListener("input", validateEditForm);
    }
});

// - Validação Criação/Edição de Edifícios, Pisos, Serviços, Salas
const buildingNameInput = document.getElementById("building-name");
const buildingModalEl = document.getElementById("equipment-creation-modal");

if (buildingNameInput && btnSubmitModal) {
    const validateBuildingForm = () => {
        if (buildingNameInput.value.trim() !== "") {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validateBuildingForm();

    buildingNameInput.addEventListener("input", validateBuildingForm);

    if (buildingModalEl) {
        buildingModalEl.addEventListener("hidden.bs.modal", () => {
            buildingNameInput.value = "";
            validateBuildingForm();
        });
    }
}

// - Validação Criação/Edição de Utilizadores
const userFullnameInput = document.getElementById("user-fullname");
const userUsernameInput = document.getElementById("user-username");
const userEmailInput = document.getElementById("user-email");
const userPasswordInput = document.getElementById("user-password");
const userRoleSelect = document.getElementById("user-role");
const userModalEl = document.getElementById("equipment-creation-modal");

if (
    userFullnameInput &&
    userUsernameInput &&
    userEmailInput &&
    userPasswordInput &&
    btnSubmitModal
) {
    const validateUserForm = () => {
        const fullnameValid = userFullnameInput.value.trim() !== "";
        const usernameValid = userUsernameInput.value.trim() !== "";
        const emailValue = userEmailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const emailValid = emailRegex.test(emailValue);
        const passwordValid = userPasswordInput.value.trim() !== "";

        if (fullnameValid && usernameValid && emailValid && passwordValid) {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validateUserForm();

    userFullnameInput.addEventListener("input", validateUserForm);
    userUsernameInput.addEventListener("input", validateUserForm);
    userEmailInput.addEventListener("input", validateUserForm);
    userPasswordInput.addEventListener("input", validateUserForm);

    if (userModalEl) {
        userModalEl.addEventListener("hidden.bs.modal", () => {
            userFullnameInput.value = "";
            userUsernameInput.value = "";
            userEmailInput.value = "";
            userPasswordInput.value = "";
            if (userRoleSelect) userRoleSelect.value = "Convidado";
            validateUserForm();
        });
    }
}


// - Validação Criação/Edição de Garantias/Contratos e Manutenções
const warrantyForm = document.getElementById("add-warranty-form");
if (warrantyForm) {
    const typeInput = document.getElementById("warranty-type");
    const periodicityInput = document.getElementById("warranty-periodicity");
    const startDateInput = document.getElementById("warranty-start-date");
    const endDateInput = document.getElementById("warranty-end-date");
    const btnSubmitWarranty = document.getElementById("btn-submit-warranty");

    if (typeInput && periodicityInput && startDateInput && endDateInput && btnSubmitWarranty) {
        // Função para converter dd/mm/yyyy para objeto Date
        const parseDate = (dateString) => {
            if (!dateString) return null;
            const parts = dateString.split("/");
            if (parts.length === 3) {
                return new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
            }
            return null;
        };

        const validateWarrantyForm = () => {
            let isValid = false;
            if (typeInput.value && periodicityInput.value && startDateInput.value && endDateInput.value) {
                const startDate = parseDate(startDateInput.value);
                const endDate = parseDate(endDateInput.value);

                if (startDate && endDate && endDate > startDate) {
                    isValid = true;
                }
            }

            if (isValid) {
                btnSubmitWarranty.removeAttribute("disabled");
            } else {
                btnSubmitWarranty.setAttribute("disabled", "true");
            }
        };

        validateWarrantyForm();

        typeInput.addEventListener("change", validateWarrantyForm);
        periodicityInput.addEventListener("change", validateWarrantyForm);
        startDateInput.addEventListener("change", validateWarrantyForm);
        endDateInput.addEventListener("change", validateWarrantyForm);
        startDateInput.addEventListener("input", validateWarrantyForm);
        endDateInput.addEventListener("input", validateWarrantyForm);
    }
}

const editWarrantyForms = document.querySelectorAll(".edit-warranty-form");
editWarrantyForms.forEach((form) => {
    const typeInput = form.querySelector(".edit-warranty-type");
    const periodicityInput = form.querySelector(".edit-warranty-periodicity");
    const startDateInput = form.querySelector(".edit-warranty-start-date");
    const endDateInput = form.querySelector(".edit-warranty-end-date");
    const btnSubmit = form.querySelector(".btn-submit-edit-warranty");

    if (typeInput && periodicityInput && startDateInput && endDateInput && btnSubmit) {
        const parseDate = (dateString) => {
            if (!dateString) return null;
            const parts = dateString.split("/");
            if (parts.length === 3) {
                return new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
            }
            return null;
        };

        const validateForm = () => {
            let isValid = false;
            if (typeInput.value && periodicityInput.value && startDateInput.value && endDateInput.value) {
                const startDate = parseDate(startDateInput.value);
                const endDate = parseDate(endDateInput.value);
                if (startDate && endDate && endDate > startDate) {
                    isValid = true;
                }
            }
            if (isValid) {
                btnSubmit.disabled = false;
            } else {
                btnSubmit.disabled = true;
            }
        };

        // Inicializar flatpickr neste formulário
        flatpickr(startDateInput, {
            dateFormat: "d/m/Y",
            allowInput: true,
            onChange: validateForm
        });
        flatpickr(endDateInput, {
            dateFormat: "d/m/Y",
            allowInput: true,
            onChange: validateForm
        });

        validateForm();

        typeInput.addEventListener("change", validateForm);
        periodicityInput.addEventListener("change", validateForm);
        startDateInput.addEventListener("input", validateForm);
        endDateInput.addEventListener("input", validateForm);
    }
});

const maintenanceForm = document.getElementById("add-maintenance-form");
if (maintenanceForm) {
    const typeInput = document.getElementById("maintenance-type");
    const responsibleInput = document.getElementById("maintenance-responsible");
    const startDateInput = document.getElementById("maintenance-start-date");
    const endDateInput = document.getElementById("maintenance-end-date");
    const btnSubmitMaintenance = document.getElementById("btn-submit-maintenance");

    if (typeInput && responsibleInput && startDateInput && btnSubmitMaintenance) {
        const parseDate = (dateString) => {
            if (!dateString) return null;
            const parts = dateString.split("/");
            if (parts.length === 3) {
                return new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
            }
            return null;
        };

        const validateMaintenanceForm = () => {
            let isValid = false;
            if (typeInput.value && responsibleInput.value && startDateInput.value) {
                isValid = true;

                // Se houver data de fim preenchida, tem de ser maior que a data de início
                if (endDateInput && endDateInput.value) {
                    const startDate = parseDate(startDateInput.value);
                    const endDate = parseDate(endDateInput.value);
                    if (!startDate || !endDate || endDate <= startDate) {
                        isValid = false;
                    }
                }
            }

            if (isValid) {
                btnSubmitMaintenance.removeAttribute("disabled");
            } else {
                btnSubmitMaintenance.setAttribute("disabled", "true");
            }
        };

        validateMaintenanceForm();

        typeInput.addEventListener("change", validateMaintenanceForm);
        responsibleInput.addEventListener("change", validateMaintenanceForm);
        startDateInput.addEventListener("change", validateMaintenanceForm);
        startDateInput.addEventListener("input", validateMaintenanceForm);
        if (endDateInput) {
            endDateInput.addEventListener("change", validateMaintenanceForm);
            endDateInput.addEventListener("input", validateMaintenanceForm);
        }
    }
}

// - Validação Edição de Permissões
const permissionKeyInput = document.getElementById("permission-key");
const permissionDescInput = document.getElementById("permission-description");
const btnSubmitPermission = document.getElementById("btn-submit-permission");
const permissionForm = document.getElementById("permission-creation-form");
const permissionModal = document.getElementById("permission-creation-modal");

if (permissionKeyInput && permissionDescInput && btnSubmitPermission) {
    const validatePermissionForm = () => {
        const keyValid = permissionKeyInput.value.trim() !== "";
        const descValid = permissionDescInput.value.trim() !== "";

        if (keyValid && descValid) {
            btnSubmitPermission.removeAttribute("disabled");
        } else {
            btnSubmitPermission.setAttribute("disabled", "true");
        }
    };

    validatePermissionForm();

    permissionKeyInput.addEventListener("input", validatePermissionForm);
    permissionDescInput.addEventListener("input", validatePermissionForm);

    if (permissionModal) {
        permissionModal.addEventListener("hidden.bs.modal", () => {
            permissionKeyInput.value = "";
            permissionDescInput.value = "";
            validatePermissionForm();
        });
    }
}

const editPermissionForms = document.querySelectorAll(".permission-edit-form");
if (editPermissionForms.length > 0) {
    editPermissionForms.forEach(form => {
        const keyInput = form.querySelector(".permission-edit-key");
        const descInput = form.querySelector(".permission-edit-description");
        const submitBtn = form.querySelector(".btn-edit-submit");

        if (keyInput && descInput && submitBtn) {
            const validateEditForm = () => {
                const keyValid = keyInput.value.trim() !== "";
                const descValid = descInput.value.trim() !== "";
                if (keyValid && descValid) {
                    submitBtn.removeAttribute("disabled");
                } else {
                    submitBtn.setAttribute("disabled", "true");
                }
            };

            // Executar inicialmente
            validateEditForm();

            // Escutar inputs
            keyInput.addEventListener("input", validateEditForm);
            descInput.addEventListener("input", validateEditForm);
        }
    });
}

function togglePermission(permissionId) {
    const permissionBadge = document.getElementById(
        `permission-badge-${permissionId}`,
    );
    if (permissionBadge) {
        permissionBadge.classList.toggle("has-permission");
    }

    const permissionInput = document.getElementById(
        `permission-input-${permissionId}`,
    );
    if (permissionInput) {
        permissionInput.value = permissionInput.value === "1" ? "0" : "1";
    }

    const bar = document.querySelector(".inbox-changes-container");
    if (bar) {
        bar.style.setProperty("display", "flex", "important");
    }
}


// - Contact Form (Área Pública)
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const organizationInput = document.getElementById("organization");
    const msgInput = document.getElementById("message");
    const submitBtn = document.getElementById("cta-submit-btn");

    if (nameInput && emailInput && organizationInput && msgInput && submitBtn) {
        const validateForm = () => {
            const isNameValid = nameInput.value.trim().length > 0 && !/\d/.test(nameInput.value);
            const isEmailValid = emailInput.value.trim().length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            const isOrgValid = organizationInput.value.trim().length > 0;
            const isMsgValid = msgInput.value.length <= 400;

            if (isNameValid && isEmailValid && isOrgValid && isMsgValid) {
                submitBtn.removeAttribute("disabled");
            } else {
                submitBtn.setAttribute("disabled", "true");
            }
        };

        [nameInput, emailInput, organizationInput, msgInput].forEach(input => {
            input.addEventListener("input", validateForm);
            input.addEventListener("change", validateForm);
        });

        // Executa a validação ao carregar a página para habilitar o botão se estiver preenchido corretamente
        // No caso de terem havidos erros de validação 
        validateForm();
    }
});

// Lógica de Contagem de Caracteres (Form Area Publica)
document.addEventListener("DOMContentLoaded", () => {
    const msgInput = document.getElementById("message");
    const charCount = document.getElementById("message-char-count");
    if (msgInput && charCount) {
        msgInput.addEventListener("input", () => {
            const currentLength = msgInput.value.length;
            charCount.textContent = `${currentLength} / 400`;
        });
    }
});

// ===================
// 7. Inbox e Gestão de Conteúdos
// ===================

function changeInboxState(requestId, stateName, stateClass) {
    const btn = document.getElementById(`inbox-state-btn-${requestId}`);
    if (btn) {
        btn.className = `d-inline-flex align-items-center equipment-badge ${stateClass} gap-1 mw-0 border-0`;
        const span = btn.querySelector("span");
        if (span) {
            span.textContent = stateName;
        }

        // Fechar o dropdown
        if (typeof bootstrap !== "undefined") {
            const bsDropdown = bootstrap.Dropdown.getInstance(btn);
            if (bsDropdown) {
                bsDropdown.hide();
            }
        }
    }
    const modalBadge = document.getElementById(`inbox-modal-badge-${requestId}`);
    if (modalBadge) {
        modalBadge.className = `equipment-badge ${stateClass} inbox-modal-footer-badge fw-400`;
        modalBadge.textContent = `Tratamento atual: ${stateName}`;
    }

    // Atualizar o input hidden do form
    const input = document.getElementById(`inbox-state-input-${requestId}`);
    if (input) {
        input.value = stateName;
    }

    // Mostrar a barra de alterações pendentes
    const bar = document.querySelector(".inbox-changes-container");
    if (bar) {
        bar.style.setProperty("display", "flex", "important");
    }
}

// ===================
// 8. Pesquisa Global (AJAX)
// ===================

// Lógica do Modal de Pesquisa Global (AJAX)
document.addEventListener("DOMContentLoaded", () => {
    const searchModal = document.getElementById("search-modal");
    if (searchModal) {
        const searchInput = document.getElementById("global-search-input");
        const quickAccess = document.getElementById("search-quick-access");
        const searchResults = document.getElementById("search-results");
        const searchEmpty = document.getElementById("search-empty");
        const searchLoading = document.getElementById("search-loading");
        const searchEmptyTerm = document.getElementById("search-empty-term");
        const searchUrl = searchModal.getAttribute("data-search-url");

        let debounceTimeout = null;

        // Foco automático ao abrir o modal
        searchModal.addEventListener("shown.bs.modal", () => {
            if (searchInput) {
                searchInput.focus();
            }
        });

        // Alternar estados e fazer fetch ao escrever no input
        if (searchInput && quickAccess && searchResults) {
            searchInput.addEventListener("input", () => {
                const term = searchInput.value.trim();

                clearTimeout(debounceTimeout);

                if (term.length > 0) {
                    quickAccess.classList.add("d-none");
                    searchResults.classList.add("d-none");
                    searchEmpty.classList.add("d-none");
                    searchLoading.classList.remove("d-none");

                    // Debounce: esperar 300ms antes de pesquisar
                    debounceTimeout = setTimeout(() => {
                        performSearch(term);
                    }, 300);
                } else {
                    quickAccess.classList.remove("d-none");
                    searchResults.classList.add("d-none");
                    searchEmpty.classList.add("d-none");
                    searchLoading.classList.add("d-none");
                    searchResults.innerHTML = "";
                }
            });
        }

        async function performSearch(term) {
            try {
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(term)}`);
                if (!response.ok) throw new Error("Erro de rede ao pesquisar");

                const data = await response.json();

                searchLoading.classList.add("d-none");

                if (data.error) {
                    throw new Error(data.error);
                }

                if (data.length === 0) {
                    if (searchEmptyTerm) searchEmptyTerm.textContent = term;
                    searchEmpty.classList.remove("d-none");
                    searchResults.classList.add("d-none");
                } else {
                    renderSearchResults(data);
                    searchResults.classList.remove("d-none");
                    searchEmpty.classList.add("d-none");
                }

            } catch (error) {
                console.error("Erro na pesquisa:", error);
                searchLoading.classList.add("d-none");
                if (searchEmptyTerm) searchEmptyTerm.textContent = term + " (Ocorreu um erro)";
                searchEmpty.classList.remove("d-none");
            }
        }

        function renderSearchResults(sections) {
            searchResults.innerHTML = "";
            const sectionTemplate = document.getElementById("search-section-template");
            const itemTemplate = document.getElementById("search-item-template");

            if (!sectionTemplate || !itemTemplate) {
                console.error("Templates de pesquisa não encontrados");
                return;
            }

            sections.forEach(section => {
                const sectionClone = sectionTemplate.content.cloneNode(true);
                sectionClone.querySelector(".section-title-text").textContent = section.title;
                const itemsContainer = sectionClone.querySelector(".section-items-container");

                section.items.forEach(item => {
                    const itemClone = itemTemplate.content.cloneNode(true);

                    const link = itemClone.querySelector(".item-link");
                    link.href = item.url;

                    const iconWrapper = itemClone.querySelector(".item-icon-wrapper");
                    iconWrapper.style.backgroundColor = section.bg;
                    iconWrapper.style.color = section.color;
                    iconWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${section.icon}</svg>`;

                    itemClone.querySelector(".item-title").textContent = item.title;
                    itemClone.querySelector(".item-subtitle").textContent = item.subtitle;

                    itemsContainer.appendChild(itemClone);
                });

                searchResults.appendChild(sectionClone);
            });
        }

        // Limpar pesquisa e restaurar estado inicial ao fechar o modal
        searchModal.addEventListener("hidden.bs.modal", () => {
            if (searchInput) {
                searchInput.value = "";
            }
            if (quickAccess && searchResults) {
                quickAccess.classList.remove("d-none");
                searchResults.classList.add("d-none");
                searchEmpty.classList.add("d-none");
                searchLoading.classList.add("d-none");
                searchResults.innerHTML = "";
            }
            clearTimeout(debounceTimeout);
        });
    }
});

// ===================
// 9. Exportação e Utilidades
// ===================

// - Lógica do Modal de Exportação (Seleção Visual)

document.addEventListener('DOMContentLoaded', () => {
    const exportModal = document.getElementById('exportModal');
    if (exportModal) {
        const optionCards = exportModal.querySelectorAll('.export-option-card');
        const exportTypeInput = document.getElementById('exportTypeInput');
        const btnConfirmExport = document.getElementById('btnConfirmExport');

        // Lógica de Seleção
        optionCards.forEach(card => {
            card.addEventListener('click', function () {
                // Limpar seleções anteriores
                optionCards.forEach(c => {
                    c.classList.remove('selected', 'selected-csv', 'selected-json', 'selected-pdf');
                });

                // Selecionar o atual
                this.classList.add('selected');

                // Mapear cores por tipo de exportação
                const type = this.getAttribute('data-export-type');
                exportTypeInput.value = type;

                if (type === 'csv') {
                    this.classList.add('selected-csv');
                } else if (type === 'json') {
                    this.classList.add('selected-json');
                } else if (type === 'pdf') {
                    this.classList.add('selected-pdf');
                }
            });
        });

        // Acionar a Exportação
        if (btnConfirmExport) {
            btnConfirmExport.addEventListener('click', function () {
                const currentUrl = window.location.pathname;
                const searchParams = new URLSearchParams(window.location.search);
                const format = exportTypeInput.value;
                searchParams.set('format', format);

                let targetEndpoint = '';
                if (currentUrl.includes('equipment_list.php')) {
                    targetEndpoint = 'export-equipments.php';
                } else if (currentUrl.includes('audit_logs.php')) {
                    targetEndpoint = 'export-audit-logs.php';
                } else if (currentUrl.includes('backups.php')) {
                    targetEndpoint = 'export-system.php';
                }

                if (targetEndpoint) {
                    const exportUrl = targetEndpoint + '?' + searchParams.toString();
                    window.location.href = exportUrl;

                    // Fechar modal opcionalmente
                    const modalInstance = bootstrap.Modal.getInstance(exportModal);
                    if (modalInstance) modalInstance.hide();
                }
            });
        }
    }
});


// - Função Utilitária prefillFields
function prefillFields(fields) {
    if (!fields || typeof fields !== 'object') return;

    for (const [idOrName, value] of Object.entries(fields)) {
        let el = document.getElementById(idOrName);
        if (!el) {
            el = document.querySelector(`[name="${idOrName}"]`);
        }

        if (el) {
            el.value = value;
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
        } else {
            console.warn(`prefillFields: Elemento '${idOrName}' não encontrado.`);
        }
    }
}

// - Bloqueio de Accions Collapse nas Localizações
document.addEventListener(
    "click",
    (e) => {
        const actionBtn = e.target.closest(
            ".locations .action-buttons, .locations .action-buttons svg, .locations .action-buttons path",
        );
        if (actionBtn) {
            e.stopPropagation();
            e.preventDefault();
        }
    },
    true,
);

// ===================
// 10. Hardware: QR Codes
// ===================

// Modal de visualização de QR Code para impressão
function openQRPrintModal(encryptedId, code, designation) {
    const codeElement = document.getElementById('qrEquipCode');
    const designationElement = document.getElementById('qrEquipDesignation');

    codeElement.textContent = code;
    designationElement.textContent = designation;

    // Usar SITE_BASE_URL se estiver definido, senao default
    const base = window.SITE_BASE_URL || '/sibdas/1240961/heba/';
    const urlBase = window.location.origin + base + 'private/inventory/equipments/detailed_view.php?id=';
    const finalUrl = urlBase + encodeURIComponent(encryptedId);

    if (typeof QRCode !== 'undefined') {
        QRCode.toDataURL(finalUrl, {
            width: 200,
            margin: 2,
            color: {
                dark: '#0F172A',
                light: '#FFFFFF'
            }
        }, function (error, url) {
            if (error) {
                console.error("Erro a gerar QR Code: ", error);
                alert("Ocorreu um erro ao gerar o QR Code.");
                return;
            }
            const img = document.getElementById('qrImage');
            if (img) {
                img.src = url;
            }
            const modal = new bootstrap.Modal(document.getElementById('qrPrintModal'));
            modal.show();
        });
    } else {
        console.error("QRCode library not loaded.");
    }
}

const btnDownloadQR = document.getElementById('btnDownloadQR');
if (btnDownloadQR) {
    btnDownloadQR.addEventListener('click', () => {
        const img = document.getElementById('qrImage');
        const equipCode = document.getElementById('qrEquipCode').textContent;
        if (img && img.src) {
            const a = document.createElement('a');
            a.href = img.src;
            a.download = `QR_${equipCode}.png`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    });
}

// Selecionar equipamento para gerar QR Code
const btnProceedQRPrint = document.getElementById('btnProceedQRPrint');
const selQR = document.getElementById('qrEquipamentoSelect');

if (btnProceedQRPrint && selQR) {
    // Ativar/desativar botão consoante a seleção
    selQR.addEventListener('change', () => {
        if (selQR.value) {
            btnProceedQRPrint.removeAttribute('disabled');
        } else {
            btnProceedQRPrint.setAttribute('disabled', 'true');
        }
    });

    btnProceedQRPrint.addEventListener('click', () => {
        if (!selQR.value) return; // Proteção adicional

        const opt = selQR.options[selQR.selectedIndex];

        const selectModalEl = document.getElementById('qrSelectModal');
        const selectModal = bootstrap.Modal.getOrCreateInstance(selectModalEl);
        selectModal.hide();

        setTimeout(() => {
            openQRPrintModal(selQR.value, opt.getAttribute('data-code'), opt.getAttribute('data-desc'));
        }, 400);
    });
}

// Scanner de QR Code pela câmara
let html5QrcodeScanner = null;
const scanModalEl = document.getElementById('qrScanModal');

if (scanModalEl) {
    scanModalEl.addEventListener('shown.bs.modal', function () {
        if (!html5QrcodeScanner) {
            if (typeof Html5QrcodeScanner !== 'undefined') {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader",
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    false);
                html5QrcodeScanner.render(
                    (decodedText, decodedResult) => {
                        if (decodedText.includes('detailed_view.php?id=')) {
                            try { if (html5QrcodeScanner) html5QrcodeScanner.pause(true); } catch (e) { /* file mode erro */ }
                            window.location.href = decodedText;
                        } else {
                            alert('Código QR não reconhecido ou inválido para este sistema.');
                            try {
                                if (html5QrcodeScanner) {
                                    html5QrcodeScanner.pause(true);
                                    setTimeout(() => { try { html5QrcodeScanner.resume(); } catch (e) { } }, 3000);
                                }
                            } catch (e) { /* file mode erro */ }
                        }
                    },
                    (error) => { /* ignora frames vazios */ }
                );

                // Traduzir textos gerados dinamicamente pela biblioteca html5-qrcode
                const readerElement = document.getElementById("reader");
                if (readerElement) {
                    const observer = new MutationObserver(() => {
                        const scanTypeBtn = document.getElementById("html5-qrcode-anchor-scan-type-change");
                        if (scanTypeBtn) {
                            if (scanTypeBtn.innerText.includes("Scan an Image File")) scanTypeBtn.innerText = "Carregar ficheiro de imagem";
                            if (scanTypeBtn.innerText.includes("Scan using camera directly")) scanTypeBtn.innerText = "Usar câmara diretamente";
                        }
                        const fileBtn = document.getElementById("html5-qrcode-button-file-selection");
                        if (fileBtn) {
                            if (fileBtn.innerText.includes("Choose Image")) fileBtn.innerText = "Escolher Imagem";
                            if (fileBtn.innerText.includes("Choose Another")) {
                                fileBtn.innerText = fileBtn.innerText.replace("Choose Another", "Escolher Outra");
                            }
                        }
                        const walkTextNodes = (node) => {
                            if (node.nodeType === 3) {
                                if (node.nodeValue.includes("Or drop an image to scan")) {
                                    node.nodeValue = node.nodeValue.replace("Or drop an image to scan", "Ou arraste uma imagem para aqui");
                                }
                                if (node.nodeValue.includes("Request Camera Permissions")) {
                                    node.nodeValue = node.nodeValue.replace("Request Camera Permissions", "Solicitar permissões da câmara");
                                }
                                if (node.nodeValue.includes("Stop Scanning")) {
                                    node.nodeValue = node.nodeValue.replace("Stop Scanning", "Parar câmara");
                                }
                                if (node.nodeValue.includes("Start Scanning")) {
                                    node.nodeValue = node.nodeValue.replace("Start Scanning", "Iniciar câmara");
                                }
                                if (node.nodeValue.includes("AbortError: Timeout starting video source")) {
                                    node.nodeValue = node.nodeValue.replace("AbortError: Timeout starting video source", "A câmara demorou demasiado tempo a responder ou não foi encontrada.");
                                }
                                if (node.nodeValue.includes("NotAllowedError: Permission denied")) {
                                    node.nodeValue = node.nodeValue.replace("NotAllowedError: Permission denied", "Acesso à câmara negado pelo navegador.");
                                }
                                if (node.nodeValue.includes("NotFoundError: Requested device not found")) {
                                    node.nodeValue = node.nodeValue.replace("NotFoundError: Requested device not found", "Nenhuma câmara detetada.");
                                }
                            } else if (node.nodeType === 1) {
                                node.childNodes.forEach(walkTextNodes);
                            }
                        };
                        walkTextNodes(readerElement);
                    });
                    observer.observe(readerElement, { childList: true, subtree: true });
                }
            } else {
                console.error("Html5QrcodeScanner library not loaded.");
            }
        }
    });

    scanModalEl.addEventListener('hidden.bs.modal', function () {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().catch(error => {
                console.error("Failed to clear html5QrcodeScanner. ", error);
            });
            html5QrcodeScanner = null;
        }
    });
}

