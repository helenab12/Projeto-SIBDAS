/* Menu mobile — toggle hamburguer / X */
const menuToggle = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");

if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", () => {
        menuToggle.classList.toggle("open");
        mobileMenu.classList.toggle("open");
    });
}

/* Toggle de tema (dark/light) */
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

/* Inicializar tema guardado ou preferência do sistema */
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

/* Navbar border ao fazer scroll */
const navbar = document.querySelector(".pa-navbar");

if (navbar) {
    window.addEventListener("scroll", () => {
        navbar.classList.toggle("scrolled", window.scrollY > 50);
    });
}

/* Sidebar Dropdowns */
const dropdownToggles = document.querySelectorAll(".nav-dropdown-toggle");

dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", (e) => {
        const sidebar = toggle.closest("aside");

        if (sidebar && sidebar.classList.contains("collapsed")) {
            sidebar.classList.remove("collapsed");
        }
    });
});

/* Sidebar Collapse */
const collapseBtn = document.querySelector(".sidebar-collapse-btn");
if (collapseBtn) {
    collapseBtn.addEventListener("click", () => {
        const sidebar = document.querySelector(".desktop-sidebar");
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
    });
}

/* Mobile Sidebar */
const mobileSidebar = document.querySelector(".mobile-sidebar");
const sidebarBackground = document.querySelector(".sidebar-background");

// Funcionalidade utilitária para prender o scroll da página
function toggleBodyScroll(isOpen) {
    if (isOpen) {
        document.body.style.overflowY = "hidden";
    } else {
        document.body.style.overflowY = "";
    }
}

// Abrir menu mobile (clique no hamburguer)
const mobileMenuBtn = document.getElementById("mobile-menu-toggle");
if (mobileMenuBtn && mobileSidebar) {
    mobileMenuBtn.addEventListener("click", () => {
        mobileSidebar.classList.add("open");
        toggleBodyScroll(true);
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

/* Gráficos de Estatísticas */

// Variáveis partilhadas de Dados e Cores para os Gráficos
const estatisticasLabels = [
    "Ventiladores",
    "Monitores de sinais vitais",
    "Bombas de infusão",
    "Desfibrilhadores",
    "Equipamentos de Imagem",
    "Equipamento Cirúrgico",
    "Equipamento de Laboratório",
    "Esterilizadores",
];

const estatisticasData = [42, 67, 89, 28, 15, 53, 38, 22];

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

function getCalculatedCssColor(varName) {
    // getCalculatedColor("--primary-500") -> "rgb(59, 130, 246)"
    const div = document.createElement("div");
    div.style.color = `var(${varName})`;
    div.style.display = "none";
    document.body.appendChild(div);
    const finalColor = getComputedStyle(div).color;
    document.body.removeChild(div);
    return finalColor;
}

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

// Chamar uma vez de imediato
updateChartColors();

// Grafico de Barras
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

// Dados para Gráfico de Donut (Serviços)
const servicosLabels = [
    "UCI",
    "Bloco Operatório",
    "Urgência",
    "Imagiologia",
    "Laboratório",
    "Esterilização",
];
const servicosData = [2, 2, 3, 1, 1, 1];
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

// Dados para Gráfico de Tendência (Manutenções)
const tendenciaLabels = [
    "Abr",
    "Mai",
    "Jun",
    "Jul",
    "Ago",
    "Set",
    "Out",
    "Nov",
    "Dez",
    "Jan",
    "Fev",
    "Mar",
];
const preventivaData = [8, 10, 12, 9, 11, 14, 10, 18, 15, 12, 16, 20];
const corretivaData = [3, 4, 5, 2, 6, 4, 7, 5, 3, 6, 4, 5];

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

// Header Semi-transparente ao dar scroll
const privateAreaHeader = document.querySelectorAll(".private-area header");

if (privateAreaHeader) {
    window.addEventListener("scroll", () => {
        privateAreaHeader.forEach((header) => {
            header.classList.toggle("header-scrolled", window.scrollY > 50);
        });
    });
}

// Inicializar DataTables (Equipamentos)
if (
    document.getElementById("equipmentsTable") &&
    typeof simpleDatatables !== "undefined"
) {
    const table = new simpleDatatables.DataTable("#equipmentsTable", {
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

    table.on('datatable.page', initTooltips);
    table.on('datatable.sort', initTooltips);
    table.on('datatable.search', initTooltips);
    table.on('datatable.update', initTooltips);

    // Custom search binding
    const searchInputs = document.querySelectorAll(".search-bar-input");
    const filterEstado = document.getElementById("filter-estado");
    const filterCriticidade = document.getElementById("filter-criticidade");
    const filterCategoria = document.getElementById("filter-categoria");

    function applyFilters() {
        const searchVal = searchInputs.length > 0 ? searchInputs[0].value.trim() : "";
        const estadoVal = filterEstado ? filterEstado.value : "";
        const critVal = filterCriticidade ? filterCriticidade.value : "";
        const catVal = filterCategoria ? filterCategoria.value : "";

        if (typeof table.multiSearch === 'function') {
            let queries = [];
            if (searchVal) queries.push({ terms: [searchVal] });
            if (catVal) queries.push({ terms: [catVal], columns: [1] });
            if (estadoVal) queries.push({ terms: [estadoVal], columns: [3] });
            if (critVal) queries.push({ terms: [critVal], columns: [4] });

            if (queries.length > 0) {
                table.multiSearch(queries);
            } else {
                table.search("");
            }
        } else {
            let terms = [];
            if (searchVal) terms.push(searchVal);
            if (estadoVal) terms.push(estadoVal);
            if (critVal) terms.push(critVal);
            if (catVal) terms.push(catVal);
            table.search(terms.join(" "));
        }
    }

    searchInputs.forEach((input) => {
        input.addEventListener("input", applyFilters);
    });

    [filterEstado, filterCriticidade, filterCategoria].forEach(select => {
        if (select) select.addEventListener("change", applyFilters);
    });
}

// Inicializar DataTables (Fornecedores)
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

// Inicializar DataTables (Utilizadores)
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

// Inicializar DataTables (Funcionalidades)
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

// Inicializar DataTables (Garantias & Contratos)
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

// Inicializar DataTables (Auditoria)
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

// Inicializar Flatpickr (Datas)
if (typeof flatpickr !== "undefined") {
    flatpickr("#purchase-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today",
    });
    flatpickr("#manufacture-date", {
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
    flatpickr("#warranty-date", {
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

// Cascata para Localização (Edifício -> Piso -> Serviço -> Sala)
const buildingSelect = document.getElementById("building");
const floorSelect = document.getElementById("floor");
const serviceSelect = document.getElementById("service");
const roomSelect = document.getElementById("room");

if (buildingSelect && floorSelect && serviceSelect && roomSelect) {
    buildingSelect.addEventListener("change", () => {
        floorSelect.removeAttribute("disabled");

        // Reset dos seguintes
        floorSelect.value = "";
        serviceSelect.setAttribute("disabled", "true");
        serviceSelect.value = "";
        roomSelect.setAttribute("disabled", "true");
        roomSelect.value = "";
    });

    floorSelect.addEventListener("change", () => {
        serviceSelect.removeAttribute("disabled");

        // Reset dos seguintes
        serviceSelect.value = "";
        roomSelect.setAttribute("disabled", "true");
        roomSelect.value = "";
    });

    serviceSelect.addEventListener("change", () => {
        roomSelect.removeAttribute("disabled");

        // Reset do seguinte
        roomSelect.value = "";
    });
}

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

// Campos obrigatórios do Componente
const componentNameInput = document.getElementById("component-name");
const componentSkuInput = document.getElementById("component-sku");

if (componentNameInput && componentSkuInput && btnSubmitModal) {
    const validateComponentForm = () => {
        if (
            componentNameInput.value.trim() !== "" &&
            componentSkuInput.value.trim() !== ""
        ) {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validateComponentForm();

    componentNameInput.addEventListener("input", validateComponentForm);
    componentSkuInput.addEventListener("input", validateComponentForm);
}

// Campos obrigatórios da Categoria
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

// Campos obrigatórios da Categoria (Editar)
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

// Campos obrigatórios da Página 1
const equipmentCodeInput = document.getElementById("equipment-code");
const equipmentCategorySelect = document.getElementById("equipment-category");
const equipmentSerialInput = document.getElementById("equipment-serial");
const equipmentNameInput = document.getElementById("equipment-name");
const equipmentBrandSelect = document.getElementById("equipment-brand");
const equipmentStatusSelect = document.getElementById("equipment-status");
const equipmentLocationSelect = document.getElementById("equipment-location");

if (btnNextPage && btnPrevPage && modalPage1 && modalPage2) {
    // Validação da Página 1
    const validatePage1 = () => {
        if (
            equipmentCodeInput?.value.trim() !== "" &&
            equipmentCategorySelect?.value !== "" &&
            equipmentSerialInput?.value.trim() !== "" &&
            equipmentNameInput?.value.trim() !== "" &&
            equipmentBrandSelect?.value !== "" &&
            equipmentStatusSelect?.value !== "" &&
            equipmentLocationSelect?.value !== ""
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

    // Filtragem de componentes pela categoria selecionada
    if (equipmentCategorySelect) {
        equipmentCategorySelect.addEventListener("change", (e) => {
            const selectedCategoryId = e.target.value;
            const componentItems = document.querySelectorAll(".multi-select-item[data-category-id]");
            const noComponentsMsg = document.getElementById("no-components-msg");
            let visibleCount = 0;

            componentItems.forEach(item => {
                const itemCategoryId = item.getAttribute("data-category-id");
                // Se o componente não tiver categoria (vazio) ou pertencer à selecionada, mostra
                if (selectedCategoryId === "" || itemCategoryId === selectedCategoryId || itemCategoryId === "") {
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
    }

    // Navegação
    btnNextPage.addEventListener("click", () => {
        modalPage1.classList.add("d-none");
        modalPage2.classList.remove("d-none");
    });

    btnPrevPage.addEventListener("click", () => {
        modalPage2.classList.add("d-none");
        modalPage1.classList.remove("d-none");
    });

    // Validação de Datas de Manutenção
    const maintenanceStartDate = document.getElementById("last-maintenance-start-date");
    const maintenanceEndDate = document.getElementById("last-maintenance-end-date");
    
    const validateMaintenanceDates = () => {
        if (maintenanceStartDate && maintenanceEndDate && maintenanceStartDate.value && maintenanceEndDate.value) {
            const startDate = new Date(maintenanceStartDate.value);
            const endDate = new Date(maintenanceEndDate.value);
            
            if (startDate > endDate) {
                maintenanceEndDate.setCustomValidity("A data de fim não pode ser anterior à data de início.");
                maintenanceEndDate.reportValidity();
            } else {
                maintenanceEndDate.setCustomValidity("");
            }
        } else if (maintenanceEndDate) {
            maintenanceEndDate.setCustomValidity("");
        }
    };

    if (maintenanceStartDate) maintenanceStartDate.addEventListener("change", validateMaintenanceDates);
    if (maintenanceEndDate) maintenanceEndDate.addEventListener("change", validateMaintenanceDates);
}

// Lógica para Checkboxes Múltiplos com Quantidade
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
    });
});

// Lógica de Upload de Documentos (Manutenção & Garantia)
const uploadZone = document.querySelector(".file-upload-zone");
const fileInput = document.getElementById("document-upload-input");
const uploadContainer = document.getElementById("uploaded-files-container");
const uploadTemplate = document.getElementById("uploaded-file-template");

if (uploadZone && fileInput && uploadContainer && uploadTemplate) {
    // Abrir o file picker ao clicar na zona
    uploadZone.addEventListener("click", () => {
        fileInput.click();
    });

    // Lidar com a seleção de ficheiros via input
    fileInput.addEventListener("change", (e) => {
        handleFiles(e.target.files);
        fileInput.value = "";
    });

    // Drag and Drop events
    uploadZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = "var(--primary-500)";
        uploadZone.style.backgroundColor = "var(--primary-50)";
    });

    uploadZone.addEventListener("dragleave", (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = "";
        uploadZone.style.backgroundColor = "";
    });

    uploadZone.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = "";
        uploadZone.style.backgroundColor = "";
        handleFiles(e.dataTransfer.files);
    });

    // Função para processar os ficheiros e criar os cards
    function handleFiles(files) {
        const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];
        const maxSize = 10 * 1024 * 1024; // 10MB

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

            // Validação de tamanho (máx 10MB)
            if (file.size > maxSize) {
                alert(`O ficheiro "${file.name}" excede o tamanho máximo de 10MB.`);
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

            // Lógica de remoção
            closeBtn.addEventListener("click", () => {
                card.remove();
            });

            // Adicionar ao container
            uploadContainer.appendChild(clone);
        }
    }
}

// Inicializar Tooltips do Bootstrap
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

// Campos obrigatórios do Fornecedor (Nome da Empresa, NIF)
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

// Campos obrigatórios do Colaborador / Pessoa (Nome Completo, Nº Funcionário)
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



// Campos obrigatórios do Edifício (Nome)
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

// Impedir que os botões de ação disparem o colapso do accordion nas localizações
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

// Pesquisa de Edifícios
const locationsSearchInput = document.querySelector(".equipment-list-search-bar .search-bar-input");
const locationsSearchForm = document.querySelector(".equipment-list-search-bar form");

if (locationsSearchForm) {
    locationsSearchForm.addEventListener("submit", function (e) {
        e.preventDefault();
    });
}

if (locationsSearchInput) {
    locationsSearchInput.addEventListener("input", function () {
        const query = this.value.trim().toLowerCase();
        const locationsBuildingCards = document.querySelectorAll(".locations");
        let anyVisible = false;
        locationsBuildingCards.forEach(function (card) {
            const nameEl = card.querySelector(".building-row p.fw-700");
            if (nameEl) {
                const name = nameEl.textContent.trim().toLowerCase();
                const isMatch = !query || name.includes(query);
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
    });
}

// Campos obrigatórios do Utilizador (Nome Completo, Username, Email, Password)
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
            if (userRoleSelect) userRoleSelect.value = "Consulta";
            validateUserForm();
        });
    }
}

// Campos obrigatórios da Permissão (Chave, Descrição)
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

// Validação dos formulários de Edição de Permissão
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

// Perfis
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

// Reciclagem
function changeSelectedTyoe(buttonId) {
    const btn = document.getElementById(buttonId);
    if (btn) {
        const currentlySelected = document.querySelector(
            ".recycling .filter-bar-badge.active",
        );
        if (currentlySelected) {
            currentlySelected.classList.remove("active");
        }
        btn.classList.add("active");
    } else {
        console.warn(`Botão com ID "${buttonId}" não encontrado.`);
    }
}

// Notificações
function markAsRead(index) {
    const card = document.getElementById(`notification-${index}`);
    if (card && !card.classList.contains("unread")) {
        card.classList.add("unread");
        const dot = card.querySelector(".text-primary-500");
        if (dot) {
            dot.remove();
        }

        const countEl = document.getElementById("unread-count");
        if (countEl) {
            let count = parseInt(countEl.textContent);
            if (count > 0) {
                const newCount = count - 1;
                countEl.textContent = newCount;
                if (newCount === 0) {
                    const markAllBtn = document.getElementById("mark-all-read-btn");
                    if (markAllBtn) {
                        markAllBtn.style.display = "none";
                    }
                }
            }
        }
    }
}

const markAllBtn = document.getElementById("mark-all-read-btn");
if (markAllBtn) {
    markAllBtn.addEventListener("click", () => {
        const unreadCards = document.querySelectorAll(".notifications .bento-card:not(.unread)");
        unreadCards.forEach((card) => {
            card.classList.add("unread");
            const dot = card.querySelector(".text-primary-500");
            if (dot) {
                dot.remove();
            }
        });
        const countEl = document.getElementById("unread-count");
        if (countEl) {
            countEl.textContent = "0";
        }
        markAllBtn.style.display = "none";
    });
}

// Inbox 
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

// Lógica de Modais de Documentos (Equipamento Detailed View)
document.addEventListener("DOMContentLoaded", () => {
    // Character count for public page message textarea
    const msgInput = document.getElementById("message");
    const charCount = document.getElementById("message-char-count");
    if (msgInput && charCount) {
        msgInput.addEventListener("input", () => {
            const currentLength = msgInput.value.length;
            charCount.textContent = `${currentLength} / 400`;
        });
    }

    // 1. Drag & Drop Upload Handlers
    const addDropzone = document.getElementById("add-dropzone");
    const addFileInput = document.getElementById("doc-file");
    const addDropzoneText = document.getElementById("add-dropzone-text");

    const editDropzone = document.getElementById("edit-dropzone");
    const editFileInput = document.getElementById("edit-doc-file");
    const editDropzoneText = document.getElementById("edit-dropzone-text");

    const setupDropzone = (dropzone, fileInput, textEl) => {
        if (!dropzone || !fileInput || !textEl) return;
        dropzone.addEventListener("click", () => fileInput.click());
        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length > 0) {
                textEl.textContent = `Ficheiro selecionado: ${e.target.files[0].name}`;
                textEl.style.color = "var(--primary-500)";
            }
        });
        dropzone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropzone.style.borderColor = "var(--primary-500)";
            dropzone.style.backgroundColor = "light-dark(var(--primary-50), color-mix(in srgb, var(--primary-500) 10%, transparent))";
        });
        dropzone.addEventListener("dragleave", () => {
            dropzone.style.borderColor = "";
            dropzone.style.backgroundColor = "";
        });
        dropzone.addEventListener("drop", (e) => {
            e.preventDefault();
            dropzone.style.borderColor = "";
            dropzone.style.backgroundColor = "";
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                textEl.textContent = `Ficheiro selecionado: ${e.dataTransfer.files[0].name}`;
                textEl.style.color = "var(--primary-500)";
            }
        });
    };

    setupDropzone(addDropzone, addFileInput, addDropzoneText);
    setupDropzone(editDropzone, editFileInput, editDropzoneText);
});

// Lógica do Modal de Pesquisa Global (Foco automático e alternância de estado)
document.addEventListener("DOMContentLoaded", () => {
    const searchModal = document.getElementById("search-modal");
    if (searchModal) {
        const searchInput = document.getElementById("global-search-input");
        const quickAccess = document.getElementById("search-quick-access");
        const searchResults = document.getElementById("search-results");

        searchModal.addEventListener("shown.bs.modal", () => {
            if (searchInput) {
                searchInput.focus();
            }
        });

        // Alternar estados ao escrever no input
        if (searchInput && quickAccess && searchResults) {
            searchInput.addEventListener("input", () => {
                if (searchInput.value.trim().length > 0) {
                    quickAccess.classList.add("d-none");
                    searchResults.classList.remove("d-none");
                } else {
                    quickAccess.classList.remove("d-none");
                    searchResults.classList.add("d-none");
                }
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
            }
        });
    }
});

// Inicializar toasts na pagina de login
document.addEventListener("DOMContentLoaded", function () {
    const toastElList = document.querySelectorAll('.toast-container .toast');
    const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
    toastList.forEach(toast => toast.show());
});

// Validação no frontend para o contact form
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

// Customização de Ícones SVG dos Cartões
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
                // Password can be empty (meaning no change) or >= 8 chars
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

                const name = nameEl ? nameEl.textContent.toLowerCase() : "";
                const email = emailEl ? emailEl.textContent.toLowerCase() : "";
                const role = roleEl ? roleEl.textContent.toLowerCase() : "";

                const matchesSearch = searchTerm === "" || name.includes(searchTerm) || email.includes(searchTerm);
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

        if (personSearchInput) personSearchInput.addEventListener("input", applyPersonFilters);
        if (personRoleFilter) personRoleFilter.addEventListener("change", applyPersonFilters);
    }
});

// Validação Modais de Edição de Pessoa
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

// Lógica de Fornecedores
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

                inputs.forEach(input => {
                    if (!input.value.trim() || !input.checkValidity()) {
                        isValid = false;
                    }
                });

                if (stockActualInput && stockMinInput) {
                    const actual = parseInt(stockActualInput.value || "0", 10);
                    const min = parseInt(stockMinInput.value || "0", 10);
                    if (min > actual) {
                        isValid = false;
                        stockMinInput.setCustomValidity("O stock mínimo não pode ser superior ao stock atual.");
                    } else {
                        stockMinInput.setCustomValidity("");
                    }
                }

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
});

// Lógica de Paginação e Validação para Modais de Edição de Equipamento
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

        const validatePage1 = () => {
            if (
                codeInput?.value.trim() !== "" &&
                categorySelect?.value !== "" &&
                serialInput?.value.trim() !== "" &&
                nameInput?.value.trim() !== "" &&
                brandSelect?.value !== "" &&
                statusSelect?.value !== "" &&
                locationSelect?.value !== ""
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
                    const itemCategoryId = item.getAttribute("data-category-id");
                    if (selectedCategoryId === "" || itemCategoryId === selectedCategoryId || itemCategoryId === "") {
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
        }
    });
});
