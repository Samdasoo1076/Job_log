document.addEventListener('DOMContentLoaded', () => {
  const toolbar = document.querySelector('.body-tools');
  const dialog = document.getElementById('body-insert-dialog');
  const text = document.getElementById('body-insert-text');
  const status = document.getElementById('body-tools-status');
  let range = null, selected = '', mode = '';
  const escape = value => value.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;');
  const editor = () => window.oEditors?.getById?.editor;
  function capture() {
    const app = editor();
    if (!app || app.getEditingMode() !== 'WYSIWYG') {
      status.textContent = '에디터 모드에서 사용해 주세요.';
      return false;
    }
    const doc = app.getWYSIWYGDocument();
    const selection = doc.defaultView.getSelection();
    if (!selection.rangeCount) { status.textContent = '본문 안에서 삽입 위치를 선택해 주세요.'; return false; }
    range = selection.getRangeAt(0).cloneRange();
    selected = selection.toString();
    return true;
  }
  toolbar.addEventListener('mousedown', event => {
    if (!event.target.closest('button')) return;
    event.preventDefault();
    capture();
  });
  function insert(html) {
    const app = editor();
    if (!app || !range) return;
    const doc = app.getWYSIWYGDocument();
    doc.defaultView.focus();
    const selection = doc.defaultView.getSelection();
    selection.removeAllRanges(); selection.addRange(range);
    app.exec('PASTE_HTML', [html]);
    app.exec('UPDATE_CONTENTS_FIELD', []);
    document.getElementById('pro-post-form').dispatchEvent(new Event('input', {bubbles:true}));
    status.textContent = '본문에 적용했습니다.';
    range = null;
  }
  toolbar.addEventListener('click', event => {
    const button = event.target.closest('button');
    if (!button) return;
    if (!range && !capture()) return;
    const style = button.dataset.bodyStyle;
    if (style) {
      if (!selected.trim()) { status.textContent = '먼저 본문에서 텍스트를 선택해 주세요.'; range = null; return; }
      const value = escape(selected);
      insert(style === 'heading' ? '<h2><span class="text-yellow">&lt;/&gt;</span> <span class="text-blue">' + value + '</span></h2>' : style === 'strong' ? '<strong>' + value + '</strong>' : '<span class="' + style + '">' + value + '</span>');
      return;
    }
    mode = button.id === 'body-code-open' ? 'code' : 'block';
    document.getElementById('body-language-label').hidden = mode !== 'code';
    document.getElementById('body-block-label').hidden = mode !== 'block';
    document.getElementById('body-insert-title').textContent = mode === 'code' ? '코드 블록' : '문장 블록';
    text.value = selected;
    dialog.showModal();
    text.focus();
  });
  document.getElementById('body-insert-cancel').onclick = () => dialog.close();
  document.getElementById('body-insert-form').addEventListener('submit', event => {
    event.preventDefault();
    if (!text.value.trim()) return;
    const value = escape(text.value);
    const block = document.getElementById('body-block-type').value;
    if (mode === 'block' && !/^dev-[a-z][a-z0-9-]*$/.test(block)) { status.textContent = '사용 가능한 문장 블록이 없습니다.'; return; }
    const html = mode === 'code' ? '<div class="code-block"><pre><code class="language-' + document.getElementById('body-code-language').value + '">' + value + '</code></pre></div><p><br></p>' : '<div class="' + block + '"><p>' + value.replaceAll('\n', '<br>') + '</p></div><p><br></p>';
    dialog.close();
    insert(html);
  });
});
