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

function getCalculatedCssColor(varName) {
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

    Chart.defaults.color = newTextColor;

    console.log("Cores atualizadas dinamicamente: Text=", newTextColor, "Grid=", newGridColor);

    if (typeof Chart !== 'undefined' && Chart.instances) {
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

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateChartColors);
window.addEventListener('load', updateChartColors);

// Chamar uma vez de imediato
updateChartColors();

// Grafico de Barras
const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
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

// Dados para Gráfico de Donut (Serviços)
const servicosLabels = ['UCI', 'Bloco Operatório', 'Urgência', 'Imagiologia', 'Laboratório', 'Esterilização'];
const servicosData = [2, 2, 3, 1, 1, 1];
const servicosColors = ['#3b82f6', '#22c55e', '#a855f7', '#ef4444', '#f97316', '#06b6d4'];

// Grafico de Donut
const ctx2 = document.getElementById('categoryDistributionChart2').getContext('2d');
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