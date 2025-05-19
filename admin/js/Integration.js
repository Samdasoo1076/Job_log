// admin/js/folder_post.js
document.addEventListener('DOMContentLoaded', () => {
    // 재귀적 Sortable 초기화
    function initNested(ul) {
        Sortable.create(ul, {
            group: 'nested',
            handle: '.handle',
            animation: 150,
            fallbackOnBody: true,
            ghostClass: 'sortable-ghost',
            swapThreshold: 0.6,
            emptyInsert: true,
            onEnd(evt) {
                const item = evt.item;
                const isFolder = item.dataset.type === 'folder';
                const id = item.dataset.id;
                const parentLi = evt.to.closest('li');
                const parentId = parentLi ? parentLi.dataset.id : '';
                const order = evt.newIndex;
                const body = new URLSearchParams();
                if (isFolder) {
                    body.set('move_folder_id', id);
                    body.set('parent_folder_id', parentId);
                    body.set('folder_order', order);
                } else {
                    body.set('move_post_id', id);
                    body.set('target_folder_id', parentId);
                    body.set('post_order', order);
                }
                fetch('folder_list.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: body.toString()
                }).then(r => r.json()).then(json => {
                    if (!json.success) alert('저장 실패');
                });
            }
        });
        ul.querySelectorAll(':scope > li > ul').forEach(initNested);
    }
    initNested(document.getElementById('folder-list'));

    // 인라인 편집/삭제 공통 처리
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const li = btn.closest('li');
            const isFolder = li.dataset.type === 'folder';
            const id = li.dataset.id;
            const span = li.querySelector(isFolder ? '.folder-name' : '.post-name');
            const old = span.textContent;
            const input = document.createElement('input');
            input.type = 'text'; input.value = old; input.className = 'inline-edit';
            span.replaceWith(input); input.focus();

            function save() {
                const v = input.value.trim();
                if (v && v !== old) {
                    const body = new URLSearchParams();
                    body.set(isFolder ? 'edit_folder_id' : 'edit_post_id', id);
                    body.set(isFolder ? 'folder_name' : 'post_title', v);
                    fetch('list.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: body.toString()
                    }).then(() => location.reload());
                } else {
                    input.replaceWith(span);
                }
            }
            input.addEventListener('blur', save);
            input.addEventListener('keydown', e => { if (e.key === 'Enter') input.blur() });
        });
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('정말 삭제?')) return;
            const li = btn.closest('li');
            const isFolder = li.dataset.type === 'folder';
            const id = li.dataset.id;
            const body = new URLSearchParams();
            body.set(isFolder ? 'delete_folder_id' : 'delete_post_id', id);
            fetch('list.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            }).then(() => location.reload());
        });
    });

    // 인라인 생성 버튼
    document.getElementById('btn-new-folder').addEventListener('click', () => {
        const name = prompt('새 폴더명');
        if (!name) return;
        const parent = prompt('상위 폴더ID (빈칸=최상위)');
        const body = new URLSearchParams({
            new_folder_name: name,
            new_folder_parent: parent || ''
        });
        fetch('list.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        }).then(() => location.reload());
    });
    document.getElementById('btn-new-post').addEventListener('click', () => {
        const title = prompt('새 게시글 제목');
        if (!title) return;
        const parent = prompt('폴더ID (빈칸=최상위)');
        const body = new URLSearchParams({
            new_post_title: title,
            new_post_parent: parent || ''
        });
        fetch('list.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        }).then(() => location.reload());
    });
});
