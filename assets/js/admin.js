/**
 * Mahin Travel & Tours - Admin Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Confirm delete on destructive actions
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const msg = btn.getAttribute('data-confirm') || 'Are you sure you want to delete this item? This action cannot be undone.';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // 2. Mobile Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // 3. Image URL copy helper
    document.querySelectorAll('[data-copy-url]').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.getAttribute('data-copy-url');
            navigator.clipboard.writeText(url).then(() => {
                const orig = btn.innerText;
                btn.innerText = 'Copied!';
                setTimeout(() => { btn.innerText = orig; }, 1500);
            });
        });
    });
});
