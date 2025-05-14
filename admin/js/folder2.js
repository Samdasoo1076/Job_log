// admin/js/folder.js
document.addEventListener('DOMContentLoaded', () => {
    /**
     * 주어진 <ul>에 Sortable 을 붙이고,
     * 하위의 <ul>에도 재귀 적용
     */
    function initNestedSortable(ul) {
        Sortable.create(ul, {
            group: 'nested',
            handle: '.handle, .name',       // ☰ 아이콘만 drag
            // draggable: 'li',         // li 전체가 draggable
            animation: 150,
            fallbackOnBody: true,
            ghostClass: 'sortable-ghost',
            swapThreshold: 0.6,
            emptyInsert: true,       // 빈 ul에도 drop 허용
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

        ul.querySelectorAll(':scope > li > ul.nested-list')
            .forEach(initNestedSortable);
    }

    // 최상위부터 시작
    const root = document.getElementById('folder-list');
    initNestedSortable(root);


    // ─── 인라인 수정 ───
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

            function saveOrCancel() {
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

            input.addEventListener('blur', saveOrCancel);
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') input.blur();
            });
        });
    });

    // 인라인 삭제(소프트)
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('정말 이 폴더를 삭제하시겠습니까?')) return;
            const id = btn.closest('li').dataset.id;
            fetch('folder_list2.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `delete_id=${id}`
            }).then(() => location.reload());
        });
    });

    // … 기존 코드 …

    // ─── 인라인 복원 ───
    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.closest('li').dataset.id;
            fetch('folder_list2.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `restore_id=${id}`
            })
                .then(r => r.json())
                .then(json => {
                    if (json.success) location.reload();
                    else alert('복구에 실패했습니다.');
                });
        });
    });



    // ─── 인라인 생성 ───
    document.querySelectorAll('.create-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const li = btn.closest('li');
            const id = li.dataset.id;
            // 이미 입력중이면 중복 방지
            if (li.querySelector('.inline-create')) return;

            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = '하위 폴더명';
            input.className = 'inline-create';
            btn.after(input);
            input.focus();

            function saveOrCancel() {
                const name = input.value.trim();
                if (name) {
                    fetch('folder_list2.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            create_parent_id: id,
                            create_name: name
                        })
                    })
                        .then(r => r.json())
                        .then(json => {
                            if (json.success) location.reload();
                            else alert('생성에 실패했습니다.');
                        });
                } else {
                    input.remove();
                }
            }

            input.addEventListener('blur', saveOrCancel);
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') input.blur();
            });
        });
    });
});
