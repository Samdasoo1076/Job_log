document.addEventListener('DOMContentLoaded', () => {
  const filterForm = document.getElementById('post-filter');
  const postBody = document.getElementById('post-list-body');
  const detailPanel = document.getElementById('post-detail-panel');
  const checkAll = document.getElementById('check-all-posts');
  const folderInput = filterForm.querySelector('[name="folder_id"]');
  const pageInput = filterForm.querySelector('[name="page"]');
  const paginationEl = document.getElementById('content-pagination');

  let posts = window.initialContentPosts || [];
  let pagination = window.initialContentPagination || { page: 1, limit: 30, total: posts.length, total_pages: 1 };
  let selectedPostId = null;

  const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

  const request = (payload) => fetch('/admin/content/content_manage.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(payload).toString()
  }).then(async (response) => {
    const json = await response.json();
    if (!response.ok || !json.success) throw new Error(json.message || '처리에 실패했습니다.');
    return json;
  });

  const selectedIds = () => Array.from(document.querySelectorAll('.post-check:checked')).map((el) => el.value);

  function renderPosts() {
    if (!posts.length) {
      postBody.innerHTML = '<tr><td colspan="9" class="empty">조건에 맞는 게시글이 없습니다.</td></tr>';
      renderPagination();
      return;
    }

    postBody.innerHTML = posts.map((post) => {
      const deleted = post.use_tf === 'N' || post.del_tf === 'Y';
      const active = Number(post.id) === Number(selectedPostId) ? ' selected' : '';
      return `
        <tr class="post-row${deleted ? ' is-deleted' : ''}${active}" data-post-id="${post.id}">
          <td><input type="checkbox" class="post-check" value="${post.id}"></td>
          <td>
            <button type="button" class="post-title-button" data-action="show-detail" data-post-id="${post.id}">
              ${escapeHtml(post.title)}
            </button>
            <div class="muted">ID ${post.id}</div>
          </td>
          <td>${escapeHtml(post.folder_name || '최상위')}</td>
          <td>${deleted ? '삭제됨' : '정상'}</td>
          <td>${Number(post.view_count || 0).toLocaleString()}</td>
          <td>${Number(post.comment_count || 0).toLocaleString()}</td>
          <td><input type="checkbox" class="content-feature-toggle" data-post-id="${post.id}" ${Number(post.is_featured) === 1 ? 'checked' : ''}></td>
          <td><input type="checkbox" class="content-comment-toggle" data-post-id="${post.id}" ${post.allow_comment === 'Y' ? 'checked' : ''}></td>
          <td class="nowrap">${escapeHtml(post.updated_at || '-')}</td>
        </tr>
      `;
    }).join('');
    renderPagination();
  }

  function renderPagination() {
    const page = Number(pagination.page || 1);
    const totalPages = Number(pagination.total_pages || 1);
    const total = Number(pagination.total || 0);
    if (totalPages <= 1) {
      paginationEl.innerHTML = `<span>${total.toLocaleString()}개</span>`;
      return;
    }

    const start = Math.max(1, page - 2);
    const end = Math.min(totalPages, page + 2);
    const buttons = [];

    buttons.push(`<button type="button" data-page="${page - 1}" ${page <= 1 ? 'disabled' : ''}>이전</button>`);
    for (let i = start; i <= end; i += 1) {
      buttons.push(`<button type="button" data-page="${i}" class="${i === page ? 'active' : ''}">${i}</button>`);
    }
    buttons.push(`<button type="button" data-page="${page + 1}" ${page >= totalPages ? 'disabled' : ''}>다음</button>`);

    paginationEl.innerHTML = `
      <span>${total.toLocaleString()}개 · ${page} / ${totalPages}</span>
      <div>${buttons.join('')}</div>
    `;
  }

  function loadPosts() {
    const params = new URLSearchParams(new FormData(filterForm));
    return fetch(`/admin/content/content_manage.php?ajax=posts&${params.toString()}`)
      .then((response) => response.json())
      .then((json) => {
        if (!json.success) throw new Error(json.message || '게시글을 불러오지 못했습니다.');
        posts = json.posts;
        pagination = json.pagination || pagination;
        renderPosts();
      })
      .catch((error) => alert(error.message));
  }

  function renderDetail(detail) {
    const post = detail.post;
    const stats = detail.stats || {};
    const comments = detail.comments || [];
    const deleted = post.use_tf === 'N' || post.del_tf === 'Y';

    detailPanel.innerHTML = `
      <div class="detail-head">
        <h2>${escapeHtml(post.title)}</h2>
        <a href="/admin/post/posts_form_pro.php?id=${post.id}">본문 편집</a>
      </div>
      <dl class="detail-meta">
        <div><dt>폴더</dt><dd>${escapeHtml(post.folder_name || '최상위')}</dd></div>
        <div><dt>상태</dt><dd>${deleted ? '삭제됨' : '정상'}</dd></div>
        <div><dt>누적 조회</dt><dd>${Number(post.view_count || 0).toLocaleString()}</dd></div>
        <div><dt>댓글</dt><dd>${Number(post.comment_count || 0).toLocaleString()}</dd></div>
        <div><dt>작성일</dt><dd>${escapeHtml(post.created_at || '-')}</dd></div>
        <div><dt>수정일</dt><dd>${escapeHtml(post.updated_at || '-')}</dd></div>
      </dl>
      <div class="detail-stats">
        <div><strong>${Number(stats.views || 0).toLocaleString()}</strong><span>30일 조회</span></div>
        <div><strong>${Number(stats.sessions || 0).toLocaleString()}</strong><span>세션</span></div>
        <div><strong>${Number(stats.ips || 0).toLocaleString()}</strong><span>IP</span></div>
      </div>
      <p class="last-view">마지막 조회: ${escapeHtml(stats.last_viewed_at || '-')}</p>
      <h3>최근 댓글</h3>
      <div class="comment-list">
        ${comments.length ? comments.map((comment) => `
          <article class="comment-item${comment.del_tf === 'Y' ? ' is-deleted' : ''}">
            <div class="comment-top">
              <strong>${escapeHtml(comment.author || '익명')}</strong>
              <span>${escapeHtml(comment.created_at || '')}</span>
            </div>
            <p>${escapeHtml(comment.content).replaceAll('\n', '<br>')}</p>
            <div class="comment-actions">
              <button type="button" data-action="comment-show" data-comment-id="${comment.id}">공개</button>
              <button type="button" data-action="comment-hide" data-comment-id="${comment.id}">숨김</button>
              <button type="button" data-action="comment-delete" data-comment-id="${comment.id}">삭제</button>
            </div>
          </article>
        `).join('') : '<p class="empty-detail">댓글이 없습니다.</p>'}
      </div>
    `;
  }

  function loadDetail(postId) {
    selectedPostId = postId;
    renderPosts();
    fetch(`/admin/content/content_manage.php?ajax=detail&post_id=${postId}`)
      .then((response) => response.json())
      .then((json) => {
        if (!json.success) throw new Error(json.message || '상세 정보를 불러오지 못했습니다.');
        renderDetail(json.detail);
      })
      .catch((error) => alert(error.message));
  }

  document.querySelectorAll('.folder-node').forEach((button) => {
    button.addEventListener('click', () => {
      document.querySelectorAll('.folder-node').forEach((node) => node.classList.remove('active'));
      button.classList.add('active');
      folderInput.value = button.dataset.folderId;
      pageInput.value = '1';
      loadPosts();
    });
  });

  document.querySelectorAll('.folder-expand-toggle').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.stopPropagation();
      if (button.classList.contains('is-empty')) return;

      const item = button.closest('li');
      const expanded = item.classList.toggle('is-expanded');
      button.setAttribute('aria-label', expanded ? '하위 폴더 닫기' : '하위 폴더 열기');
    });
  });

  document.querySelectorAll('.folder-visibility-toggle').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.stopPropagation();
      request({
        action: 'toggle_folder_visibility',
        folder_id: button.dataset.folderId,
        use_tf: button.dataset.useTf
      })
        .then(() => location.reload())
        .catch((error) => alert(error.message));
    });
  });

  filterForm.addEventListener('submit', (event) => {
    event.preventDefault();
    pageInput.value = '1';
    loadPosts();
  });

  paginationEl.addEventListener('click', (event) => {
    const button = event.target.closest('[data-page]');
    if (!button || button.disabled) return;
    pageInput.value = button.dataset.page;
    loadPosts();
  });

  postBody.addEventListener('click', (event) => {
    const button = event.target.closest('[data-action="show-detail"]');
    if (button) loadDetail(button.dataset.postId);
  });

  postBody.addEventListener('change', (event) => {
    const feature = event.target.closest('.content-feature-toggle');
    if (feature) {
      request({ action: 'toggle_feature', post_id: feature.dataset.postId, value: feature.checked ? 1 : 0 })
        .catch((error) => {
          alert(error.message);
          feature.checked = !feature.checked;
        });
      return;
    }

    const comment = event.target.closest('.content-comment-toggle');
    if (comment) {
      request({ action: 'toggle_comment', post_id: comment.dataset.postId, value: comment.checked ? 'Y' : 'N' })
        .catch((error) => {
          alert(error.message);
          comment.checked = !comment.checked;
        });
    }
  });

  checkAll.addEventListener('change', () => {
    document.querySelectorAll('.post-check').forEach((checkbox) => {
      checkbox.checked = checkAll.checked;
    });
  });

  document.getElementById('btn-move-selected').addEventListener('click', () => {
    const ids = selectedIds();
    if (!ids.length) return alert('이동할 게시글을 선택해 주세요.');

    const options = Array.from(document.getElementById('folder-move-options').options);
    const message = options.map((option) => `${option.value || 'root'}: ${option.textContent}`).join('\n');
    const folderId = prompt(`이동할 폴더 ID를 입력하세요. 최상위는 빈칸입니다.\n\n${message}`);
    if (folderId === null) return;

    const targetFolderId = folderId.trim().toLowerCase() === 'root' ? '' : folderId.trim();
    request({ action: 'move_posts', post_ids: ids.join(','), folder_id: targetFolderId })
      .then(loadPosts)
      .catch((error) => alert(error.message));
  });

  document.getElementById('btn-delete-selected').addEventListener('click', () => {
    const ids = selectedIds();
    if (!ids.length) return alert('삭제할 게시글을 선택해 주세요.');
    if (!confirm(`${ids.length}개 게시글을 삭제 처리하시겠습니까?`)) return;
    request({ action: 'delete_posts', post_ids: ids.join(',') }).then(loadPosts).catch((error) => alert(error.message));
  });

  document.getElementById('btn-restore-selected').addEventListener('click', () => {
    const ids = selectedIds();
    if (!ids.length) return alert('복구할 게시글을 선택해 주세요.');
    request({ action: 'restore_posts', post_ids: ids.join(',') }).then(loadPosts).catch((error) => alert(error.message));
  });

  detailPanel.addEventListener('click', (event) => {
    const button = event.target.closest('[data-comment-id]');
    if (!button || !selectedPostId) return;

    const action = button.dataset.action;
    const payload = {
      action: 'update_comment_status',
      comment_id: button.dataset.commentId,
      use_tf: action === 'comment-hide' || action === 'comment-delete' ? 'N' : 'Y',
      del_tf: action === 'comment-delete' ? 'Y' : 'N'
    };

    request(payload).then(() => loadDetail(selectedPostId)).catch((error) => alert(error.message));
  });

  renderPosts();
});
