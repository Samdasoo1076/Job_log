// admin/js/folder.js
document.addEventListener('DOMContentLoaded', () => {
    // #folder-list 와 그 안의 <ul>들 전부를 Sortable로 묶어 줍니다
    document.querySelectorAll('#folder-list, #folder-list ul').forEach(ul => {
        new Sortable(ul, {
            group: 'nested',
            handle: '.handle',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.6,
            ghostClass: 'sortable-ghost',
            onEnd: evt => {
                const movedId = evt.item.dataset.id;
                const newParent = evt.to.closest('li')?.dataset.id || '';
                const newIndex = evt.newIndex;
                fetch('folder_list2.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        move_id: movedId,
                        parent_id: newParent,
                        sort_order: newIndex
                    })
                })
                    .then(r => r.json())
                    .then(json => {
                        if (!json.success) alert('순서 저장에 실패했습니다.');
                    });
            }
        });
    });

    // 인라인 수정
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const li = btn.closest('li');
            const id = li.dataset.id;
            const span = li.querySelector('.name');
            const oldName = span.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = oldName;
            input.className = 'inline-edit';
            span.replaceWith(input);
            input.focus();
            function save() {
                const v = input.value.trim();
                if (v && v !== oldName) {
                    fetch('folder_list2.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `edit_id=${id}&name=${encodeURIComponent(v)}`
                    }).then(() => location.reload());
                } else {
                    input.replaceWith(span);
                }
            }
            input.addEventListener('blur', save);
            input.addEventListener('keydown', e => { if (e.key === 'Enter') input.blur() });
        });
    });

    // 인라인 삭제
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('정말 삭제하시겠습니까?')) return;
            const id = btn.closest('li').dataset.id;
            fetch('folder_list2.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `delete_id=${id}`
            }).then(() => location.reload());
        });
    });
});
