// public/js/nav.js
import { loadPost } from './post.js';  // 기존 loadPost 함수 재사용

window.addEventListener('DOMContentLoaded', () => {
  const btnFolders = document.getElementById('nav-folders');
  const btnSearch  = document.getElementById('nav-search');
  const treeCont   = document.getElementById('tree-container');
  const searchCont = document.getElementById('search-container');
  const input      = document.getElementById('search-input');
  const results    = document.getElementById('search-results');

  function activate(mode) {
    btnFolders.classList.toggle('active', mode==='folders');
    btnSearch .classList.toggle('active', mode==='search');
    treeCont  .style.display = mode==='folders' ? 'block' : 'none';
    searchCont.style.display = mode==='search'  ? 'block' : 'none';
    if (mode==='search') input.focus();
  }

  btnFolders.addEventListener('click', () => activate('folders'));
  btnSearch .addEventListener('click', () => activate('search'));

  // 검색 실행 (디바운스 적용 가능)
  input.addEventListener('input', () => {
    const q = input.value.trim();
    if (!q) {
      results.innerHTML = '';
      return;
    }
    fetch(`api/search.php?q=${encodeURIComponent(q)}`)
      .then(r=>r.json())
      .then(list => {
        results.innerHTML = list.map(item =>
          `<li data-id="${item.id}">
             <strong>${item.path}</strong> / ${item.title}
           </li>`
        ).join('');
      });
  });

  // 검색 결과 클릭
  results.addEventListener('click', e => {
    const li = e.target.closest('li[data-id]');
    if (!li) return;
    const id = li.dataset.id;
    loadPost(id);
    history.pushState(null, '', `?post_id=${id}`);
    activate('folders');  // 자동으로 폴더 모드로 전환
  });
});
