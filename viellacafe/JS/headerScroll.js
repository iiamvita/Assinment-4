function setupHeaderScroll() {
    const findHeader = () => document.querySelector('.header, header');
    const header = findHeader();

    if (!header) {
        const observer = new MutationObserver(() => {
            const readyHeader = findHeader();
            if (readyHeader) {
                observer.disconnect();
                setupHeaderScroll();
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });
        return;
    }

    if (header.dataset.scrollBound === 'true') {
        header.classList.toggle('scrolled', window.scrollY > 20);
        return;
    }

    const updateHeaderState = () => {
        header.classList.toggle('scrolled', window.scrollY > 20);
    };

    header.dataset.scrollBound = 'true';
    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });
}

function setupMobileMenu() {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('toggle-sidebar');
    const overlay = document.getElementById('overlay');

    if (!sidebar || !toggle || !overlay) return;

    if (toggle.dataset.bound === 'true') return;
    toggle.dataset.bound = 'true';

    const setMenuOpen = isOpen => {
        sidebar.classList.toggle('open', isOpen);
        overlay.classList.toggle('open', isOpen);
        toggle.setAttribute('aria-expanded', String(isOpen));
    };

    const currentPage = window.location.pathname.split('/').pop();
    sidebar.querySelectorAll('.nav-item').forEach(item => {
        const link = item.querySelector('a');
        const isCurrentPage = link && link.getAttribute('href') === currentPage;
        item.classList.toggle('active', Boolean(isCurrentPage));
        if (isCurrentPage) {
            link.setAttribute('aria-current', 'page');
        } else {
            link.removeAttribute('aria-current');
        }
    });

    toggle.setAttribute('aria-expanded', 'false');
    toggle.addEventListener('click', () => {
        setMenuOpen(!sidebar.classList.contains('open'));
    });
    overlay.addEventListener('click', () => setMenuOpen(false));
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => setMenuOpen(false));
    });
}
