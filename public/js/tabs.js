// public/js/tabs.js

window.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('tabs-area');
  const contentEl = document.getElementById('content');
  if (!container || !contentEl) return;

  // ── 로컬 저장소에서 복원 ──
  let openTabs = [];
  let activeId = null;
  const saved = localStorage.getItem('tabs-state');
  if (saved) {
    try {
      const { tabs, active } = JSON.parse(saved);
      openTabs = tabs;
      activeId = active;
      drawTabs();
      if (activeId) window.loadPost(activeId);
    } catch { }
  }

  function saveState() {
    localStorage.setItem('tabs-state',
      JSON.stringify({ tabs: openTabs, active: activeId })
    );
  }

  function drawTabs() {
    container.innerHTML = openTabs.map(tab => `
      <div class="tab${tab.id === activeId ? ' active' : ''}"
           data-id="${tab.id}">
        <span class="title">${tab.title}</span>
        <button class="close-btn" data-id="${tab.id}">&times;</button>
      </div>
    `).join('');
    saveState();
  }

  // 탭 열기 API (post.js 에서 호출)
  window.openTab = (id, title) => {
    id = +id;  // 숫자로 타입 통일
    if (!openTabs.find(t => t.id === id)) {
      openTabs.push({ id, title });
    }
    activeId = id;
    drawTabs();
  };

  // 닫기 & 전환 처리
  container.addEventListener('click', e => {
    // --- 닫기 버튼 ---
    const closeBtn = e.target.closest('.close-btn');
    if (closeBtn) {
      e.stopPropagation();
      const id = +closeBtn.dataset.id;
      openTabs = openTabs.filter(t => t.id !== id);

      if (activeId === id) {
        const last = openTabs[openTabs.length - 1];
        if (last) {
          activeId = last.id;
          window.loadPost(activeId);
          history.pushState(null, '', `?post_id=${activeId}`);
        } else {
          activeId = null;
          contentEl.innerHTML = `
            <h1>Welcome!</h1>
            <p>Select a folder or search for a post.</p>`;
        }
      }

      drawTabs();
      return;
    }

    // --- 탭 클릭 (전환) ---
    const tabDiv = e.target.closest('.tab');
    if (tabDiv) {
      const id = +tabDiv.dataset.id;
      activeId = id;
      window.loadPost(id);
      history.pushState(null, '', `?post_id=${id}`);
      drawTabs();
    }
  });
});
