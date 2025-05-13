// admin/js/folder.js
$(function(){
    $('#folder-nestable').nestable({
      maxDepth: 10
    }).on('change', function() {
      const order = window.JSON.stringify($('#folder-nestable').nestable('serialize'));
      // AJAX로 POST: { order }
      $.post('folder_list.php', { order }).done(()=>{
        console.log('순서 업데이트 완료');
      });
    });
  });
  
  // --- 인라인 수정 ---
  document
    .querySelectorAll('#folder-nestable .edit-btn')
    .forEach(btn => {
      btn.addEventListener('click', () => {
        const li       = btn.closest('.dd-item');
        const id       = li.dataset.id;
        const nameSpan = li.querySelector('.folder-name');
        const oldName  = nameSpan.textContent;
        const input    = document.createElement('input');

        // input 스타일
        input.type      = 'text';
        input.value     = oldName;
        input.className = 'inline-edit';
        nameSpan.replaceWith(input);
        input.focus();

        // 포커스 잃으면 저장 또는 복원
        input.addEventListener('blur', () => {
          const newName = input.value.trim();
          if (newName && newName !== oldName) {
            // AJAX POST
            fetch('folder_list.php', {
              method: 'POST',
              headers: {'Content-Type':'application/x-www-form-urlencoded'},
              body: `edit_id=${id}&parent_id=&name=${encodeURIComponent(newName)}`
            }).then(() => {
              location.reload();
            });
          } else {
            // 변경 없으면 원래대로
            input.replaceWith(nameSpan);
          }
        });

        // 엔터키로도 저장
        input.addEventListener('keydown', e => {
          if (e.key === 'Enter') {
            input.blur();
          }
        });
      });
    });

  // --- 인라인 삭제 ---
  document
    .querySelectorAll('#folder-nestable .delete-btn')
    .forEach(btn => {
      btn.addEventListener('click', () => {
        if (!confirm('정말 이 폴더를 삭제하시겠습니까?')) return;
        const id = btn.closest('.dd-item').dataset.id;
        fetch('folder_list.php', {
          method: 'POST',
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: `delete_id=${id}`
        }).then(() => {
          location.reload();
        });
      });
    });
