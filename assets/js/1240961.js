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

/* Simular envio de formulário */
const ctaForm = document.getElementById("cta-form");

if (ctaForm) {
    ctaForm.addEventListener("submit", (e) => {
        e.preventDefault();
        alert("Formulário enviado com sucesso!");
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
            info: "A mostrar {start}–{end} de {rows}",
        },
    });

    // Custom search binding
    const searchInputs = document.querySelectorAll("#search, .search-bar-input");
    searchInputs.forEach((input) => {
        input.addEventListener("input", function () {
            let dtInput = document.querySelector(".datatable-input");
            if (dtInput) {
                dtInput.value = this.value;
                dtInput.dispatchEvent(new Event("keyup"));
            }
        });
    });
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

if (btnSubmitModal) {
    btnSubmitModal.addEventListener("click", () => {
        const buttonText = btnSubmitModal.textContent.trim();
        if (buttonText === "Criar Componente") {
            alert("Componente criado com sucesso!");
        } else if (buttonText === "Criar Categoria") {
            alert("Categoria criada com sucesso!");
        } else if (buttonText === "Criar Fornecedor") {
            alert("Fornecedor criado com sucesso!");
        } else if (buttonText === "Criar Pessoa") {
            alert("Pessoa criada com sucesso!");
        } else if (buttonText === "Criar Utilizador") {
            alert("Utilizador criado com sucesso!");
        } else if (buttonText === "Guardar Alterações") {
            alert("Alterações guardadas com sucesso!");
        } else if (document.getElementById("building-name")) {
            alert("Edifício criado com sucesso!");
        } else {
            alert("Equipamento criado com sucesso!");
        }

        // Fechar qualquer modal ativo na página
        const activeModalEl = document.querySelector(".modal.show");
        if (activeModalEl) {
            const bsActiveModal =
                bootstrap.Modal.getInstance(activeModalEl) ||
                new bootstrap.Modal(activeModalEl);
            bsActiveModal.hide();
        }
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

if (categoryNameInput && categoryCodeInput && btnSubmitModal) {
    const validateCategoryForm = () => {
        if (
            categoryNameInput.value.trim() !== "" &&
            categoryCodeInput.value.trim() !== ""
        ) {
            btnSubmitModal.removeAttribute("disabled");
        } else {
            btnSubmitModal.setAttribute("disabled", "true");
        }
    };

    validateCategoryForm();

    categoryNameInput.addEventListener("input", validateCategoryForm);
    categoryCodeInput.addEventListener("input", validateCategoryForm);

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

// Campos obrigatórios da Página 1
const serialNumberInput = document.getElementById("serial-number");
const categorySelect = document.getElementById("category");
const equipmentNameInput = document.getElementById("equipment-name");
const brandInput = document.getElementById("brand");

if (btnNextPage && btnPrevPage && modalPage1 && modalPage2) {
    // Validação da Página 1
    const validatePage1 = () => {
        if (
            serialNumberInput.value.trim() !== "" &&
            categorySelect.value !== "" &&
            equipmentNameInput.value.trim() !== "" &&
            brandInput.value.trim() !== ""
        ) {
            btnNextPage.removeAttribute("disabled");
        } else {
            btnNextPage.setAttribute("disabled", "true");
        }
    };

    if (serialNumberInput) {
        serialNumberInput.addEventListener("input", validatePage1);
    }

    if (categorySelect) {
        categorySelect.addEventListener("change", validatePage1);
    }

    if (equipmentNameInput) {
        equipmentNameInput.addEventListener("input", validatePage1);
    }

    if (brandInput) {
        brandInput.addEventListener("input", validatePage1);
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
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
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

// Lógica para Editar Pessoa a partir do Bento-Card
document.addEventListener("click", function (e) {
    const editBtn = e.target.closest(".dropdown-item");
    if (!editBtn || !editBtn.textContent.includes("Editar")) return;

    // Garantir que estamos na página de gestão de pessoas
    const container = document.querySelector(".people-management");
    if (!container) return;

    e.preventDefault();

    const card = editBtn.closest(".bento-card");
    if (!card) return;

    // Extrair dados do card
    const nameEl = card.querySelector(".fw-700:not(.user-icon)");
    const name = nameEl ? nameEl.textContent.trim() : "";

    const roleEl = card.querySelector(".text-secondary");
    const role = roleEl ? roleEl.textContent.trim() : "";

    let department = "";
    let email = "";
    let phone = "";

    const briefcaseSvg = card.querySelector(".lucide-briefcase");
    if (briefcaseSvg) {
        const div = briefcaseSvg.closest("div");
        const span = div ? div.querySelector("span") : null;
        if (span) department = span.textContent.trim();
    }

    const mailSvg = card.querySelector(".lucide-mail");
    if (mailSvg) {
        const div = mailSvg.closest("div");
        const span = div ? div.querySelector("span") : null;
        if (span) email = span.textContent.trim();
    }

    const phoneSvg = card.querySelector(".lucide-phone");
    if (phoneSvg) {
        const div = phoneSvg.closest("div");
        const span = div ? div.querySelector("span") : null;
        if (span) phone = span.textContent.trim();
    }

    const idEl = card.querySelector(".font-mono");
    const id = idEl ? idEl.textContent.trim() : "";

    const calendarSvg = card.querySelector(".lucide-calendar");
    let startDate = "";
    if (calendarSvg) {
        const div = calendarSvg.closest("div");
        const span = div ? div.querySelector("span") : null;
        if (span) {
            const rawDate = span.textContent.replace("Desde", "").trim();
            // Formatar de MM/YYYY para DD/MM/YYYY
            if (rawDate.includes("/")) {
                const parts = rawDate.split("/");
                startDate = `15/${parts[0]}/${parts[1]}`;
            } else {
                startDate = rawDate;
            }
        }
    }

    // Preencher os inputs do Modal
    if (personNameInput) personNameInput.value = name;
    if (personIdInput) personIdInput.value = id;
    if (personRoleInput) personRoleInput.value = role;
    if (personDepartmentInput) personDepartmentInput.value = department;
    if (personEmailInput) personEmailInput.value = email;
    if (personPhoneInput) personPhoneInput.value = phone;
    if (personStartDateInput) personStartDateInput.value = startDate;

    // Alterar os textos do Modal
    const modalTitleEl = document.getElementById("equipmentModalLabel");
    const modalSubtitleEl = modalTitleEl ? modalTitleEl.nextElementSibling : null;
    if (modalTitleEl) modalTitleEl.textContent = "Editar Pessoa";
    if (modalSubtitleEl)
        modalSubtitleEl.textContent = "Informações do colaborador";
    if (btnSubmitModal) {
        btnSubmitModal.textContent = "Guardar Alterações";
        btnSubmitModal.removeAttribute("disabled");
    }

    // Abrir o Modal
    if (bsCreateEquipmentModal) {
        bsCreateEquipmentModal.show();
    }
});

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

// Impedir que os botões de ação disparem o colapso do accordion
const locationsContainer = document.querySelector(".locations");
if (locationsContainer) {
    locationsContainer.addEventListener(
        "click",
        (e) => {
            const actionBtn = e.target.closest(
                ".action-buttons, .action-buttons svg, .action-buttons path",
            );
            if (actionBtn) {
                e.stopPropagation();
                e.preventDefault();
            }
        },
        true,
    );
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

    if (permissionForm) {
        btnSubmitPermission.addEventListener("click", (e) => {
            e.preventDefault();
            alert("Permissão criada com sucesso!");

            const modalInstance = bootstrap.Modal.getInstance(permissionModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    }
}

// Perfis
function togglePermission(permissionId) {
    const permissionBadge = document.getElementById(
        `permission-badge-${permissionId}`,
    );
    if (permissionBadge) {
        permissionBadge.classList.toggle("has-permission");
    }

    const changesCard = document.querySelector(
        ".security-profiles .changes-card",
    );
    if (changesCard) {
        changesCard.classList.add("has-changes");
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
}

// Lógica de Modais de Documentos (Equipamento Detailed View)
document.addEventListener("DOMContentLoaded", () => {
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
    const toastElList = document.querySelectorAll('.toast.toast-error');
    const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
    toastList.forEach(toast => toast.show());
});