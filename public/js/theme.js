(() => {
  let theme = 'dark';
  try { theme = localStorage.getItem('blog-theme') === 'light' ? 'light' : 'dark'; } catch (_) {}
  document.documentElement.dataset.theme = theme;
  function sync() {
    document.querySelectorAll('.blog-theme-switch').forEach(button => {
      const light = document.documentElement.dataset.theme === 'light';
      button.setAttribute('aria-checked', String(light));
      button.title = light ? '다크 모드로 전환' : '라이트 모드로 전환';
      button.setAttribute('aria-label', button.title);
    });
  }
  document.addEventListener('DOMContentLoaded', () => {
    const original = document.querySelector('.blog-theme-switch');
    const sidebar = document.getElementById('sidebar');
    if (original && sidebar) {
      const mobile = document.createElement('div'); mobile.className = 'mobile-theme-setting';
      const label = document.createElement('span'); label.textContent = '라이트 모드';
      mobile.append(label, original.cloneNode(true)); sidebar.append(mobile);
    }
    sync();
    document.querySelectorAll('.blog-theme-switch').forEach(button => button.addEventListener('click', () => {
      const next = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
      document.documentElement.dataset.theme = next;
      try { localStorage.setItem('blog-theme', next); } catch (_) {}
      sync();
    }));
  });
  window.addEventListener('storage', event => {
    if (event.key === 'blog-theme') { document.documentElement.dataset.theme = event.newValue === 'light' ? 'light' : 'dark'; sync(); }
  });
})();
