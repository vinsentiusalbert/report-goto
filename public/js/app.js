document.querySelector('[data-password-toggle]')?.addEventListener('click', (event) => {
    const input = document.querySelector('#password');
    const button = event.currentTarget;
    const shouldShow = input.type === 'password';

    input.type = shouldShow ? 'text' : 'password';
    button.setAttribute('aria-label', shouldShow ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
});

const sidebar = document.querySelector('[data-sidebar]');
const overlay = document.querySelector('[data-sidebar-overlay]');
const toggleSidebar = (open) => {
    sidebar?.classList.toggle('open', open);
    overlay?.classList.toggle('open', open);
};

document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => toggleSidebar(true));
document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => toggleSidebar(false));
overlay?.addEventListener('click', () => toggleSidebar(false));

