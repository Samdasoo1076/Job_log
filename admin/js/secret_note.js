document.addEventListener('DOMContentLoaded', () => {
  window.lucide?.createIcons();
  const editor = document.getElementById('secret-editor');
  const form = editor.querySelector('form');
  const tag = document.getElementById('secret-tag');
  const custom = document.getElementById('secret-custom-tag');
  const account = document.getElementById('secret-account-fields');
  const history = document.getElementById('secret-history');
  const historyContent = document.getElementById('secret-history-content');
  function updateFields() {
    custom.hidden = tag.value !== 'custom';
    custom.required = tag.value === 'custom';
    account.hidden = tag.value !== '계정';
    form.elements.note_body.required = tag.value !== '계정';
  }
  tag.addEventListener('change', updateFields);
  updateFields();
  if (editor.dataset.open === '1') editor.showModal();
  document.getElementById('secret-create').addEventListener('click', () => {
    form.reset();
    form.elements.id.value = '0';
    for (const name of ['title', 'note_body', 'account_id', 'account_password', 'custom_tag']) form.elements[name].value = '';
    form.elements.is_important.checked = false;
    tag.value = [...tag.options].some(option => option.value === '일반') ? '일반' : 'custom';
    form.querySelector('h2').textContent = '새 메모';
    form.querySelector('[type="submit"]').textContent = '저장';
    updateFields();
    editor.showModal();
  });
  document.querySelectorAll('[data-close-dialog]').forEach(button => {
    button.addEventListener('click', () => button.closest('dialog').close());
  });
  document.querySelectorAll('[data-copy]').forEach(button => {
    button.addEventListener('click', async () => {
      const label = button.textContent;
      try {
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(button.dataset.copy);
        } else {
          const input = document.createElement('textarea');
          input.value = button.dataset.copy;
          input.style.position = 'fixed';
          input.style.opacity = '0';
          document.body.append(input);
          input.select();
          const copied = document.execCommand('copy');
          input.remove();
          if (!copied) throw new Error('copy');
        }
        button.textContent = '복사됨';
      } catch (_) {
        button.textContent = '복사 실패';
      }
      setTimeout(() => { button.textContent = label; }, 1500);
    });
  });
  let historyRequest = 0;
  document.querySelectorAll('[data-history]').forEach(button => {
    button.addEventListener('click', async () => {
      const request = ++historyRequest;
      historyContent.textContent = '불러오는 중...';
      history.showModal();
      try {
        const response = await fetch(`?history_id=${encodeURIComponent(button.dataset.history)}`, { cache: 'no-store' });
        if (!response.ok) throw new Error('history');
        const rows = await response.json();
        if (request !== historyRequest) return;
        historyContent.replaceChildren();
        if (!rows.length) historyContent.textContent = '계정 정보 변경 이력이 없습니다.';
        rows.forEach(row => {
          const entry = document.createElement('section');
          entry.className = 'secret-history-entry';
          for (const [label, value] of [['수정 시각', row.changed_at], ['이전 ID', row.previous_id], ['이전 비밀번호', row.previous_password]]) {
            const line = document.createElement('p');
            const heading = document.createElement('strong');
            heading.textContent = `${label}: `;
            line.append(heading, document.createTextNode(value || '(없음)'));
            entry.append(line);
          }
          historyContent.append(entry);
        });
      } catch (_) {
        if (request === historyRequest) historyContent.textContent = '이력을 불러오지 못했습니다. 다시 시도해 주세요.';
      }
    });
  });
  history.addEventListener('close', () => {
    historyRequest++;
    historyContent.replaceChildren();
  });
});
