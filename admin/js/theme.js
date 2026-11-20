document.addEventListener('DOMContentLoaded', () => {
  function syncFrames(root = document) {
    root.querySelectorAll('iframe').forEach(frame => {
      try {
        const doc = frame.contentDocument;
        if (!doc?.head || !doc.body) return;
        doc.documentElement.dataset.adminTheme = document.documentElement.dataset.adminTheme;
        if (!doc.getElementById('admin-editor-theme')) {
          const style = doc.createElement('style'); style.id = 'admin-editor-theme';
          style.textContent = 'html[data-admin-theme="light"] body,html[data-admin-theme="light"] body.se2_inputarea {background:#fffefa!important;color:#303631!important}html[data-admin-theme="light"] body.se2_inputarea :is(h1,h2,h3,p){color:#303631!important}html[data-admin-theme="light"] :is(.se2_tool,.se2_text_tool,.se2_conversion_mode,.se2_inputarea_controller){background-color:#eeeee7!important}';
          doc.head.append(style);
        }
        syncFrames(doc);
      } catch (_) {}
    });
  }
  syncFrames();
  setInterval(syncFrames, 1500);
  const select = document.getElementById('admin-theme');
  if (!select) return;
  select.value = document.documentElement.dataset.adminTheme;
  select.addEventListener('change', () => {
    document.documentElement.dataset.adminTheme = select.value;
    syncFrames();
    try { localStorage.setItem('admin-theme', select.value); } catch (_) {}
  });
});
