(function () {
  const key = 'nova-theme';
  const root = document.documentElement;
  const saved = localStorage.getItem(key);
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

  function applyTheme(theme) {
    const dark = theme === 'dark';
    root.classList.toggle('theme-dark', dark);
    document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
      button.setAttribute('aria-pressed', dark ? 'true' : 'false');
      button.setAttribute('title', dark ? 'تفعيل الوضع النهاري' : 'تفعيل الوضع الليلي');
      button.innerHTML = dark ? '<span aria-hidden="true">☀</span><span class="theme-label">نهاري</span>' : '<span aria-hidden="true">☾</span><span class="theme-label">ليلي</span>';
    });
  }

  applyTheme(saved || (prefersDark ? 'dark' : 'light'));
  document.addEventListener('DOMContentLoaded', function () {
    applyTheme(localStorage.getItem(key) || (prefersDark ? 'dark' : 'light'));
    document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
      button.addEventListener('click', function () {
        const next = root.classList.contains('theme-dark') ? 'light' : 'dark';
        localStorage.setItem(key, next);
        applyTheme(next);
      });
    });
  });
})();
