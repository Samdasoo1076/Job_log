document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('admin-menu-toggle');
  if (!toggle) return;
  const sidebar = document.getElementById('admin-sidebar');
  const backdrop = document.getElementById('admin-menu-backdrop');
  const main = document.querySelector('.admin-container');
  const media = matchMedia('(max-width:800px)');
  function setOpen(open) {
    document.body.classList.toggle('admin-menu-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? '관리자 메뉴 닫기' : '관리자 메뉴 열기');
    backdrop.hidden = !open;
    main.inert = open;
    if (open) sidebar.querySelector('a')?.focus();
    else toggle.focus();
  }
  toggle.onclick = () => setOpen(!document.body.classList.contains('admin-menu-open'));
  backdrop.onclick = () => setOpen(false);
  sidebar.addEventListener('click', event => { if (media.matches && event.target.closest('a')) setOpen(false); });
  document.addEventListener('keydown', event => {
    if (!document.body.classList.contains('admin-menu-open')) return;
    if (event.key === 'Escape') { setOpen(false); return; }
    if (event.key !== 'Tab') return;
    const items = [toggle, ...sidebar.querySelectorAll('a,button,select')].filter(el => el.getClientRects().length);
    if (event.shiftKey && document.activeElement === items[0]) { event.preventDefault(); items.at(-1).focus(); }
    else if (!event.shiftKey && document.activeElement === items.at(-1)) { event.preventDefault(); items[0].focus(); }
  });
  media.addEventListener('change', () => { if (document.body.classList.contains('admin-menu-open')) setOpen(false); });
});
