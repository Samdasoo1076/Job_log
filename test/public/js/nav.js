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

  function activate(mode) {
    btnFolders.classList.toggle('active', mode === 'folders');
    btnSearch.classList.toggle('active', mode === 'search');
    treeView.style.display = mode === 'folders' ? 'block' : 'none';
    searchView.style.display = mode === 'search' ? 'block' : 'none';
    if (mode === 'search') input.focus();
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
    if (initPostId) {
      // 검색 이후 바로 포스트 로드
      loadPost(initPostId);
    }
  } else if (initPostId) {
    // 검색 없이 바로 포스트 로드
    loadPost(initPostId);
  } else {
    activate('folders');
  }

  // 검색 실행
  input.addEventListener('input', () => {
    const q = input.value.trim();
    currentQuery = q;
    searchTree.innerHTML = '';
    if (!q) return;

    fetch(`api/search.php?q=${encodeURIComponent(q)}`)
      .then(res => res.json())
      .then(list => {
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
                  <a href="#" class="post-link" data-post-id="${data.postId}">
                    ${name}
                  </a>
                </li>`;
            } else {
              html += `
                <li class="expanded">
                  <div class="folder">
                    <span class="arrow"></span>
                    <span class="folder-name">${name}</span>
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
            e.preventDefault();
            const id = link.dataset.postId;
            loadPost(id);
            // URL에 search + post_id 동시 반영
            history.pushState(null, '',
              `?search=${encodeURIComponent(currentQuery)}&post_id=${id}`
            );
          });
        });
      });
  });

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
