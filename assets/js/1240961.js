/* Menu mobile — toggle hamburguer / X */
const menuToggle = document.getElementById('menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');

if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
        menuToggle.classList.toggle('open');
        mobileMenu.classList.toggle('open');
    });
}

/* Toggle de tema (dark/light) */
const themeToggles = document.querySelectorAll('.pa-theme-toggle');

function toggleTheme() {
    const root = document.documentElement;
    const current = root.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';

    root.setAttribute('data-theme', next);
    root.style.colorScheme = next;
    localStorage.setItem('theme', next);

    updateChartColors();
}

/* Inicializar tema guardado ou preferência do sistema */
(function () {
    const saved = localStorage.getItem('theme');
    const preferred = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

    document.documentElement.setAttribute('data-theme', preferred);
    document.documentElement.style.colorScheme = preferred;
})();

themeToggles.forEach(btn => btn.addEventListener('click', toggleTheme));

/* Navbar border ao fazer scroll */
const navbar = document.querySelector('.pa-navbar');

if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });
}

/* Simular envio de formulário */
const ctaForm = document.getElementById('cta-form');

if (ctaForm) {
    ctaForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert("Formulário enviado com sucesso!");
    });
}

/* Sidebar Dropdowns */
const dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');

dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
        const sidebar = toggle.closest('aside');

        if (sidebar && sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
        }
    });
});

/* Sidebar Collapse */
const collapseBtn = document.querySelector('.sidebar-collapse-btn');
if (collapseBtn) {
    collapseBtn.addEventListener('click', () => {
        const sidebar = collapseBtn.closest('aside');
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            const openCollapses = sidebar.querySelectorAll('.collapse.show');
            openCollapses.forEach(collapseEl => {
                if (typeof bootstrap !== 'undefined') {
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
const mobileSidebar = document.querySelector('.mobile-sidebar');
const sidebarBackground = document.querySelector('.sidebar-background');

// Funcionalidade utilitária para prender o scroll da página
function toggleBodyScroll(isOpen) {
    if (isOpen) {
        document.body.style.overflowY = 'hidden';
    } else {
        document.body.style.overflowY = '';
    }
}

// Abrir menu mobile (clique no hamburguer)
const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
if (mobileMenuBtn && mobileSidebar) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileSidebar.classList.add('open');
        toggleBodyScroll(true);
    });
}

// Fechar menu mobile (clique no botão 'X')
const mobileCloseBtn = document.querySelector('.mobile-close-btn');
if (mobileCloseBtn && mobileSidebar) {
    mobileCloseBtn.addEventListener('click', () => {
        mobileSidebar.classList.remove('open');
        toggleBodyScroll(false);
    });
}

// Fechar menu quando se carrega no fundo escuro (sidebar-background)
if (sidebarBackground && mobileSidebar) {
    sidebarBackground.addEventListener('click', () => {
        mobileSidebar.classList.remove('open');
        toggleBodyScroll(false);
    });
}

/* Gráficos de Estatísticas */

// Variáveis partilhadas de Dados e Cores para os Gráficos
const estatisticasLabels = [
    'Ventiladores',
    'Monitores de sinais vitais',
    'Bombas de infusão',
    'Desfibrilhadores',
    'Equipamentos de Imagem',
    'Equipamento Cirúrgico',
    'Equipamento de Laboratório',
    'Esterilizadores'
];

const estatisticasData = [42, 67, 89, 28, 15, 53, 38, 22];

const estatisticasColors = [
    '#3b82f6', // azul
    '#22c55e', // verde
    '#a855f7', // roxo
    '#ef4444', // vermelho
    '#f97316', // laranja
    '#06b6d4', // ciano
    '#ff69b4', // rosa choque
    '#7fffd4'  // verde água
];

function getCalculatedCssColor(varName) { // getCalculatedColor("--primary-500") -> "rgb(59, 130, 246)"
    const div = document.createElement('div');
    div.style.color = `var(${varName})`;
    div.style.display = 'none';
    document.body.appendChild(div);
    const finalColor = getComputedStyle(div).color;
    document.body.removeChild(div);
    return finalColor;
}

function updateChartColors() {
    const newTextColor = getCalculatedCssColor('--text-secondary');
    const newGridColor = getCalculatedCssColor('--border-light');

    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = newTextColor;

        if (Chart.instances) {
            for (let id in Chart.instances) {
                const chart = Chart.instances[id];

                chart.options.color = newTextColor;
                if (chart.options.scales) {
                    for (let scaleId in chart.options.scales) {
                        if (!chart.options.scales[scaleId].ticks) chart.options.scales[scaleId].ticks = {};
                        chart.options.scales[scaleId].ticks.color = newTextColor;

                        if (!chart.options.scales[scaleId].grid) chart.options.scales[scaleId].grid = {};
                        chart.options.scales[scaleId].grid.color = newGridColor;
                    }
                }
                chart.update();
            }
        }
    }
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateChartColors);
window.addEventListener('load', updateChartColors);

// Chamar uma vez de imediato
updateChartColors();

// Grafico de Barras
const canvas1 = document.getElementById('categoryDistributionChart');
if (canvas1) {
    const ctx = canvas1.getContext('2d');
    const categoryDistributionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: estatisticasLabels,
            datasets: [{
                label: 'Quantidade',
                data: estatisticasData,
                backgroundColor: estatisticasColors,
                borderRadius: 4
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    font: {
                        size: 7
                    }
                },
            }
        }
    });
}

// Dados para Gráfico de Donut (Serviços)
const servicosLabels = ['UCI', 'Bloco Operatório', 'Urgência', 'Imagiologia', 'Laboratório', 'Esterilização'];
const servicosData = [2, 2, 3, 1, 1, 1];
const servicosColors = ['#3b82f6', '#22c55e', '#a855f7', '#ef4444', '#f97316', '#06b6d4'];

// Grafico de Donut
const canvas2 = document.getElementById('categoryDistributionChart2');
if (canvas2) {
    const ctx2 = canvas2.getContext('2d');
    const categoryDistributionChart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: servicosLabels,
            datasets: [{
                data: servicosData,
                backgroundColor: servicosColors,
                borderWidth: 1
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                },
            }
        }
    });
}

// Dados para Gráfico de Tendência (Manutenções)
const tendenciaLabels = ['Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
const preventivaData = [8, 10, 12, 9, 11, 14, 10, 18, 15, 12, 16, 20];
const corretivaData = [3, 4, 5, 2, 6, 4, 7, 5, 3, 6, 4, 5];

// Grafico de Tendencia de Manuntencoes (Linhas)
const ctx3 = document.getElementById('maintenanceTrendChart');
if (ctx3) {
    new Chart(ctx3.getContext('2d'), {
        type: 'line',
        data: {
            labels: tendenciaLabels,
            datasets: [
                {
                    label: 'Preventiva',
                    data: preventivaData,
                    borderColor: '#3B82F6',
                    backgroundColor: '#3B82F6',
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false
                },
                {
                    label: 'Corretiva',
                    data: corretivaData,
                    borderColor: '#F59E0B',
                    backgroundColor: '#F59E0B',
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                y: {
                    grid: {
                        display: true,
                        drawBorder: false
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

// Header Semi-transparente ao dar scroll 
const privateAreaHeader = document.querySelectorAll('.private-area header');

if (privateAreaHeader) {
    window.addEventListener('scroll', () => {
        privateAreaHeader.forEach(header => {
            header.classList.toggle('header-scrolled', window.scrollY > 50);
        });
    });
}

// Inicializar DataTables (Equipamentos)
if (document.getElementById('equipmentsTable') && typeof simpleDatatables !== 'undefined') {
    const table = new simpleDatatables.DataTable("#equipmentsTable", {
        searchable: true,
        perPage: 8,
        perPageSelect: false,
        labels: {
            placeholder: "Pesquisar...",
            perPage: "entradas por página",
            noRows: "Nenhum registo encontrado",
            info: "A mostrar {start}–{end} de {rows}"
        }
    });

    // Custom search binding
    let searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            let dtInput = document.querySelector('.datatable-input');
            if (dtInput) {
                dtInput.value = this.value;
                dtInput.dispatchEvent(new Event('keyup'));
            }
        });
    }
}

// Inicializar Flatpickr (Datas)
if (typeof flatpickr !== 'undefined') {
    flatpickr("#purchase-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today"
    });
    flatpickr("#manufacture-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today"
    });
    flatpickr("#last-maintenance-start-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today"
    });
    flatpickr("#last-maintenance-end-date", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today"
    });
}

// Cascata para Localização (Edifício -> Piso -> Serviço -> Sala)
const buildingSelect = document.getElementById('building');
const floorSelect = document.getElementById('floor');
const serviceSelect = document.getElementById('service');
const roomSelect = document.getElementById('room');

if (buildingSelect && floorSelect && serviceSelect && roomSelect) {
    buildingSelect.addEventListener('change', () => {
        floorSelect.removeAttribute('disabled');

        // Reset dos seguintes
        floorSelect.value = "";
        serviceSelect.setAttribute('disabled', 'true');
        serviceSelect.value = "";
        roomSelect.setAttribute('disabled', 'true');
        roomSelect.value = "";
    });

    floorSelect.addEventListener('change', () => {
        serviceSelect.removeAttribute('disabled');

        // Reset dos seguintes
        serviceSelect.value = "";
        roomSelect.setAttribute('disabled', 'true');
        roomSelect.value = "";
    });

    serviceSelect.addEventListener('change', () => {
        roomSelect.removeAttribute('disabled');

        // Reset do seguinte
        roomSelect.value = "";
    });
}

// Lógica de Abertura, Fecho e Submissão do Modal
const createEquipmentModal = document.getElementById('equipment-creation-modal');
const btnOpenCreateModal = document.getElementById('btn-open-create-equipment-modal');
const btnSubmitModal = document.getElementById('btn-submit-modal');

// Lógica de Paginação e Validação do Modal de Criação de Equipamento
const btnNextPage = document.getElementById('btn-next-page');
const btnPrevPage = document.getElementById('btn-prev-page');
const modalPage1 = document.getElementById('modal-page-1');
const modalPage2 = document.getElementById('modal-page-2');

let bsCreateEquipmentModal = null;
if (createEquipmentModal) {
    bsCreateEquipmentModal = new bootstrap.Modal(createEquipmentModal);

    // Reset à primeira página do modal quando fechado
    createEquipmentModal.addEventListener('hidden.bs.modal', () => {
        if (modalPage1 && modalPage2) {
            modalPage1.classList.remove('d-none');
            modalPage1.classList.add('d-flex');
            modalPage2.classList.remove('d-flex');
            modalPage2.classList.add('d-none');
        }
    });
}

if (btnOpenCreateModal && bsCreateEquipmentModal) {
    btnOpenCreateModal.addEventListener('click', () => {
        bsCreateEquipmentModal.show();
    });
}

if (btnSubmitModal) {
    btnSubmitModal.addEventListener('click', () => {
        const buttonText = btnSubmitModal.textContent.trim();
        if (buttonText === 'Criar Componente') {
            alert('Componente criado com sucesso!');
        } else {
            alert('Equipamento criado com sucesso!');
        }
        if (bsCreateEquipmentModal) {
            bsCreateEquipmentModal.hide();
        }
    });
}

// Campos obrigatórios do Componente
const componentNameInput = document.getElementById('component-name');
const componentSkuInput = document.getElementById('component-sku');

if (componentNameInput && componentSkuInput && btnSubmitModal) {
    const validateComponentForm = () => {
        if (componentNameInput.value.trim() !== "" && componentSkuInput.value.trim() !== "") {
            btnSubmitModal.removeAttribute('disabled');
        } else {
            btnSubmitModal.setAttribute('disabled', 'true');
        }
    };

    validateComponentForm();

    componentNameInput.addEventListener('input', validateComponentForm);
    componentSkuInput.addEventListener('input', validateComponentForm);
}

// Campos obrigatórios da Página 1
const serialNumberInput = document.getElementById('serial-number');
const categorySelect = document.getElementById('category');
const equipmentNameInput = document.getElementById('equipment-name');
const brandInput = document.getElementById('brand');

if (btnNextPage && btnPrevPage && modalPage1 && modalPage2) {
    // Validação da Página 1
    const validatePage1 = () => {
        if (serialNumberInput.value.trim() !== "" &&
            categorySelect.value !== "" &&
            equipmentNameInput.value.trim() !== "" &&
            brandInput.value.trim() !== "") {
            btnNextPage.removeAttribute('disabled');
        } else {
            btnNextPage.setAttribute('disabled', 'true');
        }
    };

    if (serialNumberInput) {
        serialNumberInput.addEventListener('input', validatePage1);
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', validatePage1);
    }

    if (equipmentNameInput) {
        equipmentNameInput.addEventListener('input', validatePage1);
    }

    if (brandInput) {
        brandInput.addEventListener('input', validatePage1);
    }

    // Navegação
    btnNextPage.addEventListener('click', () => {
        modalPage1.classList.add('d-none');
        modalPage2.classList.remove('d-none');
    });

    btnPrevPage.addEventListener('click', () => {
        modalPage2.classList.add('d-none');
        modalPage1.classList.remove('d-none');
    });
}

// Lógica para Checkboxes Múltiplos com Quantidade 
const multiSelectCheckboxes = document.querySelectorAll('.multi-select-form input[type="checkbox"]');
multiSelectCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        // Tenta encontrar o contentor de quantidade no item do componente
        const item = this.closest('.multi-select-item');
        if (item) {
            const qtyContainer = item.querySelector('.multi-select-qty-container');
            if (qtyContainer) {
                if (this.checked) {
                    qtyContainer.classList.remove('d-none');
                } else {
                    qtyContainer.classList.add('d-none');
                }
            }
        }
    });
});

// Lógica de Upload de Documentos (Manutenção & Garantia)
const uploadZone = document.querySelector('.file-upload-zone');
const fileInput = document.getElementById('document-upload-input');
const uploadContainer = document.getElementById('uploaded-files-container');
const uploadTemplate = document.getElementById('uploaded-file-template');

if (uploadZone && fileInput && uploadContainer && uploadTemplate) {
    // Abrir o file picker ao clicar na zona
    uploadZone.addEventListener('click', () => {
        fileInput.click();
    });

    // Lidar com a seleção de ficheiros via input
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
        fileInput.value = '';
    });

    // Drag and Drop events
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = 'var(--primary-500)';
        uploadZone.style.backgroundColor = 'var(--primary-50)';
    });

    uploadZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = '';
        uploadZone.style.backgroundColor = '';
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = '';
        uploadZone.style.backgroundColor = '';
        handleFiles(e.dataTransfer.files);
    });

    // Função para processar os ficheiros e criar os cards
    function handleFiles(files) {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Validação de tipo
            if (!allowedTypes.includes(file.type) && !file.name.match(/\.(pdf|jpe?g|png)$/i)) {
                alert(`O ficheiro "${file.name}" tem um formato inválido. Apenas PDF, JPG e PNG são permitidos.`);
                continue;
            }

            // Validação de tamanho (máx 10MB)
            if (file.size > maxSize) {
                alert(`O ficheiro "${file.name}" excede o tamanho máximo de 10MB.`);
                continue;
            }

            // Criar o card do ficheiro a partir do template
            const clone = uploadTemplate.content.cloneNode(true);
            const card = clone.querySelector('.uploaded-file-card');
            const nameDisplay = clone.querySelector('.file-name-display');
            const closeBtn = clone.querySelector('.btn-close-file');

            // Atualizar os dados
            nameDisplay.textContent = file.name;
            nameDisplay.title = file.name;

            // Lógica de remoção
            closeBtn.addEventListener('click', () => {
                card.remove();
            });

            // Adicionar ao container
            uploadContainer.appendChild(clone);
        }
    }
}

// Inicializar Tooltips do Bootstrap
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});