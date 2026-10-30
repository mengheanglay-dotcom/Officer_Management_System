import './bootstrap';

const sidebar = document.querySelector('[data-sidebar]');
const overlay = document.querySelector('[data-sidebar-overlay]');
const openButton = document.querySelector('[data-sidebar-open]');
const closeButton = document.querySelector('[data-sidebar-close]');

if (sidebar && overlay && openButton && closeButton) {
    const setSidebarOpen = (isOpen) => {
        sidebar.classList.toggle('translate-x-0', isOpen);
        sidebar.classList.toggle('-translate-x-full', !isOpen);
        overlay.classList.toggle('hidden', !isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);
    };

    openButton.addEventListener('click', () => setSidebarOpen(true));
    closeButton.addEventListener('click', () => setSidebarOpen(false));
    overlay.addEventListener('click', () => setSidebarOpen(false));
}
