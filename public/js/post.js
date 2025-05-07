// public/js/post.js

// 글 불러오는 공용 함수 (메타 태그 동적 갱신 포함)
export function loadPost(postId) {
  return fetch(`/public/api/post.php?post_id=${postId}`)
    .then(res => {
      if (!res.ok) throw new Error(res.statusText);
      return res.json();
    })
    .then(data => {
      // 1) 본문 렌더링
      const content = document.getElementById('content');
      content.innerHTML = `
        <h2>${data.title}</h2>
        ${data.description ? `<h3 class="description">${data.description}</h3>` : ''}
        <div>${data.content}</div>
        <p>Views: ${data.views}</p>
        <h3>댓글</h3>
        ${data.comments.map(c => `
          <div class="comment">
            <strong>${c.author}</strong> (${c.created_at})
            <p>${c.content}</p>
          </div>
        `).join('')}
        <section class="comment-form">
          <form id="comment-form">
            <input type="hidden" name="post_id" value="${postId}">
            <input name="author" placeholder="Names" required><br>
            <textarea name="content" rows="4" placeholder="leave a comment" required></textarea><br>
            <button type="submit">댓글 달기</button>
          </form>
        </section>
      `;

      // 2) SEO 메타 태그 동적 갱신
      // 전역에 SITE_TITLE 이 front_head.php 에서 설정되어 있어야 합니다.
      document.title = `${data.title}`;

      const head = document.head;
      // 메타 갱신 helper
      function upsertMeta(selector, attrName, value) {
        let meta = head.querySelector(selector);
        if (!meta) {
          meta = document.createElement('meta');
          // selector 예: 'meta[name="description"]'
          const [key, val] = selector.split(/[\[\]="]+/).filter(Boolean);
          meta.setAttribute(key, selector.match(/name/) ? val : '');
          head.appendChild(meta);
        }
        meta.setAttribute(attrName, value);
      }

      upsertMeta('meta[name="description"]', 'content', data.description || '');
      upsertMeta('meta[name="keywords"]',    'content', data.keywords    || '');
      upsertMeta('meta[property="og:title"]',       'content', data.title);
      upsertMeta('meta[property="og:description"]', 'content', data.description || '');
      upsertMeta('meta[property="og:url"]',         'content', location.href);
      upsertMeta('meta[name="twitter:title"]',      'content', data.title);
      upsertMeta('meta[name="twitter:description"]','content', data.description || '');

      // 3) 댓글 폼 AJAX 바인딩
      const form = document.getElementById('comment-form');
      form.addEventListener('submit', ev => {
        ev.preventDefault();
        const fd = new FormData(form);
        fetch('/public/api/comment.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(json => {
            if (json.success) {
              loadPost(postId);
            } else {
              alert(json.error || '댓글 작성에 실패했습니다.');
            }
          });
      });

      return data;
    });
}

// 전역 노출 (탭 컴포넌트 등에서 사용)
window.loadPost = loadPost;


// 페이지 로드 & 사이드바 링크 바인딩
window.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.post-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const id    = +link.dataset.postId;
      const title = link.dataset.title;
      loadPost(id).then(() => {
        window.openTab(id, title);
        history.pushState(null, '', `?post_id=${id}`);
      });
    });
  });

  // 새로고침/직접 URL 진입 시
  const pid = +new URLSearchParams(location.search).get('post_id');
  if (pid) {
    loadPost(pid);
  }
});
