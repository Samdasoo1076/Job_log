document.addEventListener('DOMContentLoaded', () => {
  const media = matchMedia('(max-width: 800px)');
  const menu = document.getElementById('mobile-menu');
  const backdrop = document.getElementById('mobile-backdrop');
  const sidebar = document.getElementById('sidebar');
  function toggle(open, focus = false) {
    document.body.classList.toggle('mobile-menu-open', open);
    menu.setAttribute('aria-expanded', String(open));
    backdrop.hidden = !open;
    if (!open && focus) menu.focus();
  }
  menu.addEventListener('click', () => {
    const open = !document.body.classList.contains('mobile-menu-open');
    document.getElementById('btn-folders').click();
    toggle(open);
  });
  document.getElementById('mobile-search').addEventListener('click', () => {
    toggle(true);
    document.getElementById('btn-search').click();
    document.getElementById('search-input').focus();
  });
  backdrop.addEventListener('click', () => toggle(false, true));
  sidebar.addEventListener('click', e => {
    if (media.matches && e.target.closest('.post-link')) toggle(false);
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') toggle(false, true);
    if (e.key !== 'Tab' || !document.body.classList.contains('mobile-menu-open')) return;
    const items = [...document.querySelectorAll('.mobile-header button, .mobile-header a, #sidebar a, #sidebar button, #sidebar input')].filter(el => el.getClientRects().length);
    const first = items[0], last = items.at(-1);
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  });
  media.addEventListener('change', () => toggle(false));
  const tabs = document.getElementById('tabs-area');
  if (tabs) new MutationObserver(() => {
    if (media.matches) tabs.querySelector('.tab.active')?.scrollIntoView({block:'nearest', inline:'nearest'});
  }).observe(tabs, {childList:true, subtree:true, attributes:true, attributeFilter:['class']});
});
