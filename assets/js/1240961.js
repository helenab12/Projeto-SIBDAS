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
