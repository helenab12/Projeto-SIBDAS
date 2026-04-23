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
        e.preventDefault();
        e.stopPropagation();

        const parentLi = toggle.parentElement;
        const sidebar = toggle.closest('aside');
        
        if (sidebar && sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
            
            if (!parentLi.classList.contains('open')) {
                parentLi.classList.add('open');
            }
        } else {
            parentLi.classList.toggle('open');
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
            const openDropdowns = sidebar.querySelectorAll('.nav-dropdown.open');
            openDropdowns.forEach(dd => dd.classList.remove('open'));
        }
    });
}

/* Mobile Sidebar */
const mobileSidebar = document.querySelector('.mobile-sidebar');

// Abrir menu mobile (clique no hamburguer)
const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
if (mobileMenuBtn && mobileSidebar) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileSidebar.classList.add('open');
    });
}

// Fechar menu mobile (clique no botão 'X')
const mobileCloseBtn = document.querySelector('.mobile-close-btn');
if (mobileCloseBtn && mobileSidebar) {
    mobileCloseBtn.addEventListener('click', () => {
        mobileSidebar.classList.remove('open');
    });
}

