// admin/js/folder.js
$(function () {
  // 네스터블 초기화
  $('#folder-nestable').nestable({
    maxDepth: 10,
    handleClass: 'dd-handle',
    excludeClass: 'edit-btn delete-btn'
  })
    .on('change', function () {
      const order = JSON.stringify($('#folder-nestable').nestable('serialize'));
      $.post('folder_list.php', { order })
        .done(() => console.log('순서 업데이트 완료'));
    });

  // 인라인 수정
  document.querySelectorAll('#folder-nestable .edit-btn')
    .forEach(btn => btn.addEventListener('click', () => {
      const li = btn.closest('.dd-item');
      const id = li.dataset.id;
      const span = li.querySelector('.folder-name');
      const oldName = span.textContent;
      const input = document.createElement('input');
      input.type = 'text';
      input.value = oldName;
      input.className = 'inline-edit';
      span.replaceWith(input);
      input.focus();

      function saveOrCancel() {
        const newName = input.value.trim();
        if (newName && newName !== oldName) {
          fetch('folder_list.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `edit_id=${id}&parent_id=&name=${encodeURIComponent(newName)}`
          }).then(() => location.reload());
        } else {
          input.replaceWith(span);
        }
      }

      input.addEventListener('blur', saveOrCancel);
      input.addEventListener('keydown', e => {
        if (e.key === 'Enter') input.blur();
      });
    }));

  // 인라인 삭제
  document.querySelectorAll('#folder-nestable .delete-btn')
    .forEach(btn => btn.addEventListener('click', () => {
      if (!confirm('정말 삭제하시겠습니까?')) return;
      const id = btn.closest('.dd-item').dataset.id;
      fetch('folder_list.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `delete_id=${id}`
      }).then(() => location.reload());
    }));
});
