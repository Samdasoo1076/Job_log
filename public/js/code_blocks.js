(function () {
  function enhanceTermTips(root) {
    const scope = root || document;
    scope.querySelectorAll('.term-tip[data-tip]').forEach((tip) => {
      if (tip.dataset.tipReady === '1') return;

      tip.tabIndex = 0;
      tip.setAttribute('role', 'button');
      tip.dataset.tipReady = '1';

      tip.addEventListener('click', (event) => {
        event.stopPropagation();
        document.querySelectorAll('.term-tip.is-open').forEach((openTip) => {
          if (openTip !== tip) openTip.classList.remove('is-open');
        });
        tip.classList.toggle('is-open');
      });

      tip.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          tip.click();
        }
        if (event.key === 'Escape') {
          tip.classList.remove('is-open');
        }
      });
    });
  }

  function enhance(root) {
    const scope = root || document;
    enhanceTermTips(scope);

    if (window.Prism) {
      scope.querySelectorAll('.code-block code[class*="language-"], .code-block code[class*="lang-"]').forEach((code) => {
        const pre = code.closest('pre');
        if (pre) {
          code.classList.forEach((className) => {
            if (className.startsWith('language-') || className.startsWith('lang-')) {
              pre.classList.add(className);
            }
          });
        }
        window.Prism.highlightElement(code);
      });
    }
  }

  window.CodeBlocks = { enhance };

  document.addEventListener('click', () => {
    document.querySelectorAll('.term-tip.is-open').forEach((tip) => tip.classList.remove('is-open'));
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => enhance(document));
  } else {
    enhance(document);
  }
})();
