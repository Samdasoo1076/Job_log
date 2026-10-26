document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('integration-tree');
  if (!root || typeof Sortable === 'undefined') return;

  const post = (payload) => {
    return fetch('/admin/Integration/list.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(payload).toString()
    }).then(async (response) => {
      const json = await response.json();
      if (!response.ok || !json.success) {
        throw new Error(json.message || '처리에 실패했습니다.');
      }
      return json;
    });
  };

  const reloadAfter = (request) => {
    request.then(() => location.reload()).catch((error) => alert(error.message));
  };

  const getItem = (target) => target.closest('.integration-item');
  const getFolderIdFromList = (list) => list.dataset.folderId || '';

  function initSortable(list) {
    Sortable.create(list, {
      group: 'integration-manager',
      handle: '.drag-handle',
      draggable: '.integration-item',
      animation: 150,
      fallbackOnBody: true,
      ghostClass: 'sortable-ghost',
      emptyInsertThreshold: 16,
      onEnd(event) {
        const item = event.item;
        const type = item.dataset.type;
        const id = item.dataset.id;
        const parentId = getFolderIdFromList(event.to);
        const sortOrder = event.newIndex;

        if (type === 'folder') {
          reloadAfter(post({
            action: 'move_folder',
            id,
            parent_id: parentId,
            sort_order: sortOrder
          }));
          return;
        }

        reloadAfter(post({
          action: 'move_post',
          id,
          folder_id: parentId,
          sort_order: sortOrder
        }));
      }
    });
  }

  document.querySelectorAll('.integration-list').forEach(initSortable);

  document.addEventListener('click', (event) => {
    const control = event.target.closest('[data-action]');
    if (!control) return;

    const action = control.dataset.action;
    const item = getItem(control);
    const id = item?.dataset.id || '';

    if (action === 'add-root-folder') {
      const name = prompt('최상위 폴더명');
      if (!name) return;
      reloadAfter(post({ action: 'create_folder', parent_id: '', name }));
      return;
    }

    if (action === 'add-root-post') {
      const title = prompt('최상위 게시글 제목');
      if (!title) return;
      reloadAfter(post({ action: 'create_post', folder_id: '', title }));
      return;
    }

    if (!item) return;

    if (action === 'add-folder') {
      const name = prompt('하위 폴더명');
      if (!name) return;
      reloadAfter(post({ action: 'create_folder', parent_id: id, name }));
      return;
    }

    if (action === 'add-post') {
      const title = prompt('새 게시글 제목');
      if (!title) return;
      reloadAfter(post({ action: 'create_post', folder_id: id, title }));
      return;
    }

    if (action === 'rename-folder') {
      const oldName = item.querySelector('.item-title')?.textContent.trim() || '';
      const name = prompt('폴더명 수정', oldName);
      if (!name || name === oldName) return;
      reloadAfter(post({ action: 'rename_folder', id, name }));
      return;
    }

    if (action === 'rename-post') {
      const oldTitle = item.querySelector('.item-title')?.textContent.trim() || '';
      const title = prompt('게시글 제목 수정', oldTitle);
      if (!title || title === oldTitle) return;
      reloadAfter(post({ action: 'rename_post', id, title }));
      return;
    }

    if (action === 'delete-folder') {
      if (!confirm('이 폴더를 삭제 처리하시겠습니까? 하위 항목도 화면에서 삭제 상태로 보일 수 있습니다.')) return;
      reloadAfter(post({ action: 'delete_folder', id }));
      return;
    }

    if (action === 'restore-folder') {
      reloadAfter(post({ action: 'restore_folder', id }));
      return;
    }

    if (action === 'delete-post') {
      if (!confirm('이 게시글을 삭제 처리하시겠습니까?')) return;
      reloadAfter(post({ action: 'delete_post', id }));
      return;
    }

    if (action === 'restore-post') {
      reloadAfter(post({ action: 'restore_post', id }));
    }
  });
});
