document.addEventListener('DOMContentLoaded', function () {
    const html = document.documentElement;
    const toggle = document.getElementById('theme-toggle');
    const saved = localStorage.getItem('vms-theme');
    if (saved) {
        html.setAttribute('data-theme', saved);
        if (toggle) toggle.textContent = saved === 'dark' ? '☀️' : '🌙';
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('vms-theme', next);
            toggle.textContent = next === 'dark' ? '☀️' : '🌙';
        });
    }

    // Auto-remove toast after animation ends
    document.querySelectorAll('.toast').forEach(function (toast) {
        setTimeout(function () { toast.remove(); }, 3200);
    });

    // Confirm before delete
    document.querySelectorAll('.confirm-delete').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm('Delete this visitor record? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
