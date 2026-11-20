// File: public/js/nav.js

import { loadPost } from './post.js';

window.addEventListener('DOMContentLoaded', () => {
  const btnFolders = document.getElementById('btn-folders');
  const btnSearch = document.getElementById('btn-search');
  const treeView = document.getElementById('tree-view');
  const searchView = document.getElementById('search-view');
  const input = document.getElementById('search-input');
  const searchTree = document.getElementById('search-tree');

  let currentQuery = '';
  let searchVersion = 0;
  const recentKey = 'blog.recentSearches';
  function recentSearches() {
    try {
      const values = JSON.parse(localStorage.getItem(recentKey) || '[]');
      return Array.isArray(values) ? values.filter(x => typeof x === 'string').slice(0, 10) : [];
    } catch (_) { return []; }
  }
  function storeSearches(values) {
    try { localStorage.setItem(recentKey, JSON.stringify(values)); } catch (_) {}
  }
  function rememberSearch() {
    const q = input.value.trim();
    if (q) storeSearches([q, ...recentSearches().filter(x => x !== q)].slice(0, 10));
  }
  async function showDefaults() {
    const version = ++searchVersion;
    searchTree.replaceChildren();
    const recent = recentSearches();
    const heading = document.createElement('div');
    heading.className = 'search-default-heading';
    heading.textContent = recent.length ? '최근 검색' : '최신 게시글';
    searchTree.append(heading);
    if (recent.length) {
      const clear = document.createElement('button');
      clear.type = 'button'; clear.textContent = '전체 삭제';
      clear.onclick = () => { storeSearches([]); showDefaults(); };
      heading.append(clear);
      recent.forEach(q => {
        const row = document.createElement('div'); row.className = 'search-recent-row';
        const term = document.createElement('button'); term.type = 'button'; term.textContent = q; term.title = q;
        term.onclick = () => { input.value = q; rememberSearch(); input.dispatchEvent(new Event('input')); };
        const remove = document.createElement('button'); remove.type = 'button'; remove.textContent = '×'; remove.setAttribute('aria-label', q + ' 검색 기록 삭제');
        remove.onclick = () => { storeSearches(recentSearches().filter(x => x !== q)); showDefaults(); };
        row.append(term, remove); searchTree.append(row);
      });
      return;
    }
    try {
      const response = await fetch('/public/api/search.php?mode=latest');
      if (!response.ok) throw new Error('latest');
      const rows = await response.json();
      if (version !== searchVersion || input.value.trim()) return;
      rows.forEach(post => {
        const link = document.createElement('a'); link.className = 'post-link'; link.dataset.postId = post.id;
        link.href = '?post_id=' + encodeURIComponent(post.id); link.textContent = post.title; link.title = post.title;
        link.onclick = e => { e.preventDefault(); loadPost(post.id); history.pushState(null, '', link.getAttribute('href')); };
        searchTree.append(link);
      });
      if (!rows.length) searchTree.append(document.createTextNode('게시글이 없습니다.'));
    } catch (_) {
      if (version === searchVersion) searchTree.append(document.createTextNode('목록을 불러오지 못했습니다.'));
    }
  }
  input.addEventListener('keydown', e => { if (e.key === 'Enter') rememberSearch(); });
  input.addEventListener('change', rememberSearch);

  function activate(mode) {
    btnFolders.classList.toggle('active', mode === 'folders');
    btnSearch.classList.toggle('active', mode === 'search');
    treeView.style.display = mode === 'folders' ? 'block' : 'none';
    searchView.style.display = mode === 'search' ? 'block' : 'none';
    if (mode === 'search') { input.focus(); if (!input.value.trim()) showDefaults(); }
  }

  btnFolders.addEventListener('click', () => activate('folders'));
  btnSearch.addEventListener('click', () => activate('search'));

  // URL 파라미터 읽기
  const params = new URLSearchParams(window.location.search);
  const initSearch = params.get('search') || '';
  const initPostId = params.get('post_id');

  // 초기 모드 셋팅
  if (initSearch) {
    activate('search');
    input.value = initSearch;
    currentQuery = initSearch;
    input.dispatchEvent(new Event('input'));
  } else if (initPostId) {
    activate('folders');
  } else {
    activate('folders');
  }

  // 검색 실행
  input.addEventListener('input', () => {
    const q = input.value.trim();
    currentQuery = q;
    searchTree.innerHTML = '';
    const version = ++searchVersion;
    if (!q) { showDefaults(); return; }

    fetch(`/public/api/search.php?q=${encodeURIComponent(q)}`)
      .then(res => res.json())
      .then(list => {
        if (version !== searchVersion) return;
        // flat → nested tree 구조로 변환
        const root = {};
        list.forEach(item => {
          const parts = item.path.split('/').filter(Boolean);
          let node = root;
          parts.forEach(folder => {
            node[folder] = node[folder] || { __children: {} };
            node = node[folder].__children;
          });
          node[item.title] = { postId: item.id };
        });

        // 재귀 렌더 함수
        function renderTree(obj) {
          let html = '<ul class="sidebar-tree">';
          for (const [name, data] of Object.entries(obj)) {
            if (data.postId) {
              html += `
                <li class="posts-list-item">
                  <a href="/?post_id=${encodeURIComponent(data.postId)}" class="post-link" data-post-id="${data.postId}">
                    ${escapeSearchHtml(name)}
                  </a>
                </li>`;
            } else {
              html += `
                <li class="expanded">
                  <div class="folder">
                    <span class="arrow"></span>
                    <span class="folder-name">${escapeSearchHtml(name)}</span>
                  </div>
                  ${renderTree(data.__children)}
                </li>`;
            }
          }
          html += '</ul>';
          return html;
        }

        searchTree.innerHTML = renderTree(root);

        // 클릭 바인딩 (검색 모드 그대로 유지)
        searchTree.querySelectorAll('.post-link').forEach(link => {
          link.addEventListener('click', e => {
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;
            e.preventDefault();
            const id = link.dataset.postId;
            rememberSearch();
            loadPost(id);
            // URL에 search + post_id 동시 반영
            history.pushState(null, '',
              `?search=${encodeURIComponent(currentQuery)}&post_id=${id}`
            );
          });
        });
      }).catch(() => { if (version === searchVersion) searchTree.textContent = '검색 결과를 불러오지 못했습니다.'; });
  });
  function escapeSearchHtml(value) {
    const el = document.createElement('span'); el.textContent = value; return el.innerHTML;
  }
  if (initSearch) input.dispatchEvent(new Event('input'));

  // 뒤로/앞으로 버튼 처리
  window.addEventListener('popstate', () => {
    const ps = new URLSearchParams(window.location.search);
    const s = ps.get('search');
    const pid = ps.get('post_id');

    if (s) {
      activate('search');
      input.value = s;
      currentQuery = s;
      input.dispatchEvent(new Event('input'));
      if (pid) loadPost(pid);
    } else {
      activate('folders');
      if (pid) loadPost(pid);
      else {
        // 기본 화면
        document.getElementById('content').innerHTML = `
          <h1>Welcome!</h1><p>Select a folder or search for a post.</p>`;
      }
    }
  });
});
