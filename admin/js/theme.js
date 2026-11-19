document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('admin-theme');
  if (!select) return;
  select.value = document.documentElement.dataset.adminTheme;
  select.addEventListener('change', () => {
    document.documentElement.dataset.adminTheme = select.value;
    try { localStorage.setItem('admin-theme', select.value); } catch (_) {}
  });
});
