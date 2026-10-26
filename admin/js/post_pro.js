document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('pro-post-form');
  const editorId = 'editor';
  const state = document.getElementById('autosave-state');
  const previewDialog = document.getElementById('preview-dialog');
  const previewContent = document.getElementById('preview-content');
  const description = form.elements.description;
  const descriptionCount = document.getElementById('description-count');
  const checklist = document.getElementById('publish-checklist');
  const aiMode = document.getElementById('ai-helper-mode');
  const aiNotes = document.getElementById('ai-helper-notes');
  const aiStyle = document.getElementById('ai-helper-style');
  const aiState = document.getElementById('ai-helper-state');
  const aiResult = document.getElementById('ai-helper-result');
  const aiGenerate = document.getElementById('btn-ai-generate');
  const codexGenerate = document.getElementById('btn-codex-generate');
  const codexState = document.getElementById('codex-helper-state');
  const aiInsert = document.getElementById('btn-ai-insert');
  const aiReplace = document.getElementById('btn-ai-replace');
  const config = window.proPostEditorConfig || {};
  const editors = [];
  let autosaveTimer = null;
  let lastPayload = '';
  let editorReady = false;
  let lastAiHtml = '';

  window.oEditors = editors;

  if (aiGenerate) {
    aiGenerate.addEventListener('click', generateAiDraft);
  }

  if (codexGenerate) {
    codexGenerate.addEventListener('click', enqueueCodexDraft);
  }

  function syncEditor() {
    try {
      if (editorReady && editors.getById?.[editorId]) {
        editors.getById[editorId].exec('UPDATE_CONTENTS_FIELD', []);
      }
    } catch (error) {
      editorReady = false;
    }
  }

  function getPlainContent() {
    syncEditor();
    const html = form.elements.content.value || '';
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.textContent.replace(/\s+/g, ' ').trim();
  }

  function getEditorHtml() {
    syncEditor();
    return form.elements.content.value || '';
  }

  function setEditorHtml(html) {
    form.elements.content.value = html;
    if (editorReady && editors.getById?.[editorId]) {
      editors.getById[editorId].exec('SET_IR', [html]);
    }
    updateChecklist();
    scheduleAutosave();
  }

  function insertEditorHtml(html) {
    if (editorReady && editors.getById?.[editorId]) {
      editors.getById[editorId].exec('PASTE_HTML', [html]);
      syncEditor();
    } else {
      form.elements.content.value = `${form.elements.content.value || ''}\n${html}`;
    }
    updateChecklist();
    scheduleAutosave();
  }

  function payload(action) {
    syncEditor();
    const data = new FormData(form);
    data.set('action', action);
    data.set('post_id', form.elements.id.value || '0');
    data.set('is_featured', form.elements.is_featured.checked ? '1' : '0');
    data.set('allow_comment', form.elements.allow_comment.checked ? 'Y' : 'N');
    data.set('use_tf', form.elements.use_tf.checked ? 'Y' : 'N');
    return new URLSearchParams(data);
  }

  function setState(text, mode = '') {
    state.textContent = text;
    state.dataset.mode = mode;
  }

  function saveDraft(manual = false) {
    const body = payload('save_draft');
    const serialized = body.toString();
    if (!manual && serialized === lastPayload) return Promise.resolve();
    lastPayload = serialized;
    setState('자동저장 중...', 'saving');

    return fetch('/admin/post/posts_form_pro.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: serialized
    })
      .then((response) => response.json())
      .then((json) => {
        if (!json.success) throw new Error(json.message || '임시저장 실패');
        setState(`임시저장 ${json.saved_at}`, 'saved');
      })
      .catch((error) => {
        setState(error.message, 'error');
        if (manual) alert(error.message);
      });
  }

  function scheduleAutosave() {
    clearTimeout(autosaveTimer);
    autosaveTimer = setTimeout(() => saveDraft(false), 1200);
  }

  function updateDescriptionCount() {
    descriptionCount.textContent = `${description.value.length} / 255`;
  }

  function setCheck(name, ok) {
    const item = checklist.querySelector(`[data-check="${name}"]`);
    if (item) item.classList.toggle('ok', ok);
  }

  function updateChecklist() {
    const title = form.elements.title.value.trim();
    const desc = description.value.trim();
    const content = getPlainContent();
    setCheck('title', title.length > 0);
    setCheck('description', desc.length > 0);
    setCheck('content', content.length > 0);
    setCheck('status', true);
    updateDescriptionCount();
  }

  function renderPreview() {
    syncEditor();
    const title = form.elements.title.value.trim() || '(제목 없음)';
    const desc = description.value.trim();
    const content = form.elements.content.value || '';
    const status = form.elements.use_tf.checked ? '공개' : '비공개';
    const comment = form.elements.allow_comment.checked ? '댓글 허용' : '댓글 차단';
    previewContent.innerHTML = `
      <h1>${escapeHtml(title)}</h1>
      <p class="preview-meta">${status} · ${comment}</p>
      ${desc ? `<p class="preview-description">${escapeHtml(desc)}</p>` : ''}
      <div class="preview-body">${content}</div>
    `;
    window.CodeBlocks?.enhance(previewContent);
    previewDialog.showModal();
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function setAiState(text, mode = '') {
    aiState.textContent = text;
    aiState.dataset.mode = mode;
  }

  function setCodexState(text, mode = '') {
    codexState.hidden = false;
    codexState.textContent = text;
    codexState.dataset.mode = mode;
  }

  function enqueueCodexDraft() {
    const notes = aiNotes.value.trim();
    if (!notes) {
      alert('생성할 경험이나 내용을 입력해 주세요.');
      aiNotes.focus();
      return;
    }

    codexGenerate.disabled = true;
    setCodexState('CLI 생성 요청 등록 중...', 'saving');
    const data = new URLSearchParams();
    data.set('action', 'enqueue');
    data.set('post_id', form.elements.id.value || '0');
    data.set('mode', aiMode.value);
    data.set('notes', notes);
    data.set('style', aiStyle.value.trim());
    data.set('title', form.elements.title.value.trim());
    data.set('description', description.value.trim());
    data.set('content', getEditorHtml());

    fetch('/admin/api/codex_writer.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: data.toString()
    })
      .then((response) => response.json().then((json) => {
        if (!response.ok || !json.success) throw new Error(json.message || 'CLI 요청 등록 실패');
        return json;
      }))
      .then((json) => {
        setCodexState(`작업 #${json.job_id} 대기 중. 백그라운드에서 생성합니다.`, 'saving');
        pollCodexJob(json.job_id);
      })
      .catch((error) => {
        codexGenerate.disabled = false;
        setCodexState(error.message, 'error');
      });
  }

  function pollCodexJob(jobId) {
    fetch(`/admin/api/codex_writer.php?action=status&job_id=${encodeURIComponent(jobId)}`)
      .then((response) => response.json())
      .then((json) => {
        if (!json.success) throw new Error(json.message || 'CLI 작업 상태 확인 실패');
        const job = json.job;
        if (job.status === 'queued') {
          setCodexState(`작업 #${jobId} 대기 중. 백그라운드에서 생성합니다.`, 'saving');
          setTimeout(() => pollCodexJob(jobId), 3000);
          return;
        }
        if (job.status === 'processing') {
          setCodexState(`작업 #${jobId} 생성 중...`, 'saving');
          setTimeout(() => pollCodexJob(jobId), 3000);
          return;
        }
        codexGenerate.disabled = false;
        if (job.status === 'failed') {
          setCodexState(`생성 실패: ${job.error_message || '알 수 없는 오류'}`, 'error');
          return;
        }
        const postId = job.post_id ? `&id=${encodeURIComponent(job.post_id)}` : '';
        const url = `/admin/post/posts_form_pro.php?draft_key=${encodeURIComponent(job.draft_key)}${postId}`;
        codexState.innerHTML = `생성 완료 · <a href="${url}">임시저장 글 열기</a>`;
        codexState.hidden = false;
        codexState.dataset.mode = 'saved';
      })
      .catch((error) => {
        codexGenerate.disabled = false;
        setCodexState(error.message, 'error');
      });
  }

  function setAiButtons(enabled) {
    aiInsert.disabled = !enabled;
    aiReplace.disabled = !enabled;
  }

  function generateAiDraft() {
    const notes = aiNotes.value.trim();
    if (!notes) {
      alert('오늘 겪은 일이나 초안에 넣을 내용을 입력해 주세요.');
      aiNotes.focus();
      return;
    }

    setAiState('초안 생성 중...', 'saving');
    setAiButtons(false);
    lastAiHtml = '';

    const data = new URLSearchParams();
    data.set('mode', aiMode.value);
    data.set('notes', notes);
    data.set('style', aiStyle.value.trim());
    data.set('title', form.elements.title.value.trim());
    data.set('description', description.value.trim());
    data.set('content', getEditorHtml());

    fetch('/admin/api/write_helper.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: data.toString()
    })
      .then((response) => response.json().then((json) => {
        if (!response.ok || !json.success) {
          throw new Error(json.message || 'AI 초안 생성 실패');
        }
        return json;
      }))
      .then((json) => {
        lastAiHtml = json.html || '';
        aiResult.innerHTML = lastAiHtml;
        aiResult.hidden = false;
        setAiButtons(Boolean(lastAiHtml));
        setAiState(`완료 · 남은 세션 사용 ${json.remaining}`, 'saved');
      })
      .catch((error) => {
        aiResult.hidden = true;
        setAiState(error.message, 'error');
      });
  }

  try {
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: editors,
      elPlaceHolder: editorId,
      sSkinURI: config.editorSkin,
      fCreator: 'createSEditor2',
      fOnAppLoad() {
        editorReady = true;
        updateChecklist();
        scheduleAutosave();
      }
    });
  } catch (error) {
    editorReady = false;
    setAiState('에디터 초기화 오류가 있어도 AI 초안 생성은 사용할 수 있습니다.', 'error');
  }

  form.addEventListener('input', () => {
    updateChecklist();
    scheduleAutosave();
  });

  form.addEventListener('change', () => {
    updateChecklist();
    scheduleAutosave();
  });

  form.addEventListener('submit', (event) => {
    syncEditor();
    updateChecklist();
    if (!form.elements.title.value.trim()) {
      event.preventDefault();
      alert('제목을 입력해 주세요.');
      form.elements.title.focus();
      return;
    }
    if (!getPlainContent()) {
      event.preventDefault();
      alert('본문을 입력해 주세요.');
    }
  });

  document.getElementById('btn-save-draft').addEventListener('click', () => saveDraft(true));
  document.getElementById('btn-preview').addEventListener('click', renderPreview);
  document.getElementById('btn-close-preview').addEventListener('click', () => previewDialog.close());
  aiInsert.addEventListener('click', () => {
    if (!lastAiHtml) return;
    insertEditorHtml(lastAiHtml);
    setAiState('에디터에 삽입됨', 'saved');
  });
  aiReplace.addEventListener('click', () => {
    if (!lastAiHtml) return;
    if (!confirm('현재 본문을 AI 초안으로 교체할까요?')) return;
    setEditorHtml(lastAiHtml);
    setAiState('본문이 교체됨', 'saved');
  });

  if (config.draftSavedAt) {
    setState(`임시저장본 ${config.draftSavedAt}`, 'saved');
  }
  updateDescriptionCount();
});
