// public/js/sidebar.js

window.addEventListener('DOMContentLoaded', () => {
    // 1) 새로고침 시 복원
    const opened = JSON.parse(localStorage.getItem('sidebarOpened') || '[]');
    opened.forEach(id => {
      const li = document.querySelector(`.sidebar-tree li[data-id="${id}"]`);
      if (li) li.classList.add('expanded');
    });
  
    // 2) 클릭 시 토글 + 저장
    document.querySelectorAll('.sidebar-tree .folder').forEach(folder => {
      folder.addEventListener('click', e => {
        e.stopPropagation();
        const li = folder.parentElement;
        li.classList.toggle('expanded');
  
        const openIds = Array.from(
          document.querySelectorAll('.sidebar-tree li.expanded')
        ).map(el => el.dataset.id);
        localStorage.setItem('sidebarOpened', JSON.stringify(openIds));
      });
    });
  });
  