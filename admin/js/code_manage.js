document.addEventListener('DOMContentLoaded', () => {
  const parent = document.getElementById('code-parent-dialog');
  const detail = document.getElementById('code-detail-dialog');
  function open(dialog, data = {}) {
    const form = dialog.querySelector('form');
    form.reset();
    for (const [key, value] of Object.entries(data)) {
      const input = form.elements.namedItem(key);
      if (!input) continue;
      if (input.type === 'checkbox') input.checked = value === 'Y';
      else input.value = value ?? '';
    }
    if (dialog === parent) form.elements.PCODE.readOnly = data.editing === '1';
    dialog.showModal();
  }
  document.querySelector('[data-new-parent]').onclick = () => open(parent);
  document.querySelector('[data-edit-parent]')?.addEventListener('click', () => open(parent, {...JSON.parse(document.getElementById('code-current').textContent), editing: '1'}));
  document.querySelector('[data-new-detail]')?.addEventListener('click', () => open(detail));
  document.querySelectorAll('[data-detail]').forEach(button => button.onclick = () => open(detail, JSON.parse(button.dataset.detail)));
  document.querySelectorAll('[data-close]').forEach(button => button.onclick = () => button.closest('dialog').close());
  document.querySelectorAll('[data-delete]').forEach(form => form.onsubmit = () => confirm(form.elements.action.value === 'parent_delete' ? '그룹과 하위 상세 코드를 삭제하시겠습니까?' : '상세 코드를 삭제하시겠습니까?'));
});
