document.addEventListener('DOMContentLoaded', () => {
  const content = document.getElementById('content');
  const main = document.getElementById('main');
  if (!content || !main) return;
  const map = document.createElement('div');
  map.className = 'reading-minimap';
  map.tabIndex = 0;
  map.setAttribute('role', 'scrollbar');
  map.setAttribute('aria-label', '본문 미니맵');
  map.setAttribute('aria-controls', 'content');
  map.setAttribute('aria-orientation', 'vertical');
  map.setAttribute('aria-valuemin', '0');
  map.setAttribute('aria-valuemax', '100');
  map.title = '클릭 또는 드래그해서 이동';
  const canvas = document.createElement('canvas'); canvas.setAttribute('aria-hidden', 'true');
  const viewport = document.createElement('div'); viewport.className = 'minimap-viewport';
  map.append(canvas, viewport); main.append(map);
  const media = matchMedia('(min-width: 1100px)');
  let scheduled = false, dragging = false, ratio = 1;
  function position() {
    const max = content.scrollHeight - content.clientHeight;
    const fraction = max > 0 ? content.scrollTop / max : 0;
    const height = Math.max(18, Math.min(map.clientHeight, content.clientHeight * ratio));
    viewport.style.height = height + 'px';
    viewport.style.transform = 'translateY(' + fraction * Math.max(0, map.clientHeight - height) + 'px)';
    map.setAttribute('aria-valuenow', String(Math.round(fraction * 100)));
  }
  function render() {
    scheduled = false;
    if (!media.matches) { main.classList.remove('has-reading-minimap'); return; }
    main.classList.add('has-reading-minimap');
    const scale = 84 / Math.max(1, content.clientWidth);
    const available = Math.max(0, main.clientHeight - 72);
    map.style.height = Math.min(available, Math.max(36, Math.round(content.scrollHeight * scale))) + 'px';
    const width = map.clientWidth, height = map.clientHeight;
    if (!width || !height) return;
    const dpr = Math.min(devicePixelRatio || 1, 2);
    canvas.width = width * dpr; canvas.height = height * dpr;
    const ctx = canvas.getContext('2d'); ctx.scale(dpr, dpr);
    ratio = height / Math.max(content.scrollHeight, content.clientHeight);
    const scaleX = (width - 12) / content.clientWidth;
    const bounds = content.getBoundingClientRect();
    // Draw text fragments at their document positions without cloning interactive HTML.
    const walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT);
    let node, count = 0;
    while ((node = walker.nextNode()) && count < 6000) {
      if (!node.textContent.trim() || node.parentElement.closest('script,style,textarea,button')) continue;
      const style = getComputedStyle(node.parentElement);
      if (style.visibility === 'hidden' || style.display === 'none') continue;
      const range = document.createRange(); range.selectNodeContents(node);
      const rects = range.getClientRects();
      const text = node.textContent.replace(/\s+/g, ' ').trim();
      let offset = 0;
      for (const rect of rects) {
        if (!rect.width || !rect.height) continue;
        const x = 5 + (rect.left - bounds.left + content.scrollLeft) * scaleX;
        const y = (rect.top - bounds.top + content.scrollTop) * ratio;
        const size = Math.max(0.7, Math.min(2, parseFloat(style.fontSize) * scaleX));
        const chars = Math.max(1, Math.round(rect.width / (parseFloat(style.fontSize) * 0.55)));
        ctx.fillStyle = style.color; ctx.globalAlpha = 0.55;
        ctx.font = size + 'px monospace';
        ctx.fillText(text.slice(offset, offset + chars), x, y + size, Math.max(1, rect.width * scaleX));
        offset += chars;
      }
      count++;
    }
    position();
  }
  function schedule() {
    if (!scheduled) { scheduled = true; requestAnimationFrame(render); }
  }
  function move(event) {
    const box = map.getBoundingClientRect();
    const fraction = Math.max(0, Math.min(1, (event.clientY - box.top) / box.height));
    content.scrollTop = fraction * content.scrollHeight - content.clientHeight / 2;
    position();
  }
  map.addEventListener('pointerdown', event => {
    if (event.button !== 0) return;
    dragging = true; map.setPointerCapture(event.pointerId); move(event); event.preventDefault(); map.focus();
  });
  map.addEventListener('pointermove', event => { if (dragging) move(event); });
  map.addEventListener('pointerup', () => { dragging = false; });
  map.addEventListener('pointercancel', () => { dragging = false; });
  map.addEventListener('keydown', event => {
    const steps = {ArrowDown:80, ArrowUp:-80, PageDown:content.clientHeight, PageUp:-content.clientHeight};
    if (event.key in steps) content.scrollTop += steps[event.key];
    else if (event.key === 'Home') content.scrollTop = 0;
    else if (event.key === 'End') content.scrollTop = content.scrollHeight;
    else return;
    event.preventDefault();
  });
  content.addEventListener('scroll', position, {passive:true});
  content.addEventListener('load', schedule, true);
  new MutationObserver(schedule).observe(content, {childList:true,subtree:true,characterData:true});
  new MutationObserver(schedule).observe(document.documentElement, {attributes:true,attributeFilter:['data-theme']});
  new ResizeObserver(schedule).observe(content);
  window.addEventListener('resize', schedule);
  media.addEventListener('change', schedule);
  document.fonts?.ready.then(schedule);
  schedule();
});
