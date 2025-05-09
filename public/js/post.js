// public/js/post.js

// 글 불러오는 공용 함수 (메타 태그 동적 갱신 포함)
export function loadPost(postId) {
  return fetch(`/public/api/post.php?post_id=${postId}`)
    .then(res => {
      if (!res.ok) throw new Error(res.statusText);
      return res.json();
    })
    .then(data => {
      // 1) 본문 및 댓글 렌더링
      const content = document.getElementById('content');
      content.innerHTML = `
        <h2>${data.title}</h2>
        ${data.description ? `<h3 class="description">${data.description}</h3>` : ''}
        <div>${data.content}</div>
        <p>Views: ${data.views}</p>
        <h3>댓글</h3>
        ${data.comments.map(c => `
          <div class="comment" data-comment-id="${c.id}">
                <div class="comment-header">
                  <span class="author">${c.author}</span>
                  <span class="time">${c.updated_at}</span>
                  <button class="delete-btn" data-comment-id="${c.id}">×</button>
                </div>
            <p class="text">${c.content}</p>
           
            <div class="edit-form" style="display:none; margin-top:8px;">
              <textarea class="edit-content" rows="3">${c.content}</textarea><br>
              <input type="password" class="edit-pwd" placeholder="Password" required><br>
              <button class="save-btn">저장</button>
              <button class="cancel-btn">취소</button>
            </div>
          </div>
        `).join('')}
        <section class="comment-form">
          <form id="comment-form">
            <input type="hidden" name="post_id" value="${postId}">
            <input name="author"   placeholder="Names"    required><br>
            <input name="password" type="password" placeholder="Password" required><br>
            <textarea name="content" rows="4" placeholder="leave a comment" required></textarea><br>
            <button type="submit">댓글 달기</button>
          </form>
        </section>
      `;

      // 2) SEO 메타 태그 동적 갱신
      document.title = data.title;
      const head = document.head;
      function upsertMeta(selector, attr, value) {
        let meta = head.querySelector(selector);
        if (!meta) {
          meta = document.createElement('meta');
          const name = selector.match(/name="([^"]+)"/)?.[1];
          const prop = selector.match(/property="([^"]+)"/)?.[1];
          if (name)      meta.setAttribute('name', name);
          else if (prop) meta.setAttribute('property', prop);
          head.appendChild(meta);
        }
        meta.setAttribute(attr, value);
      }
      upsertMeta('meta[name="description"]', 'content', data.description || '');
      upsertMeta('meta[name="keywords"]',    'content', data.keywords    || '');
      upsertMeta('meta[property="og:title"]',       'content', data.title);
      upsertMeta('meta[property="og:description"]', 'content', data.description || '');
      upsertMeta('meta[property="og:url"]',         'content', location.href);
      upsertMeta('meta[name="twitter:title"]',      'content', data.title);
      upsertMeta('meta[name="twitter:description"]','content', data.description || '');

      // 3) 댓글 작성 바인딩
      document.getElementById('comment-form')
        .addEventListener('submit', ev => {
          ev.preventDefault();
          const fd = new FormData(ev.target);
          fetch('/public/api/comment.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(json => {
              if (json.success)  {
                alert('댓글이 성공적으로 작성되었습니다.');  // ← 이 한 줄 추가
                loadPost(postId);
              }
              else  {
                alert(json.error || '댓글 작성에 실패했습니다.');
              }
            });
        });

      // 4) 댓글 삭제
      document.querySelectorAll('.comment .delete-btn')
        .forEach(btn => btn.addEventListener('click', async () => {
          const commentEl = btn.closest('.comment');
          const commentId = commentEl.dataset.commentId;
          const pwd = prompt('댓글 삭제 비밀번호를 입력하세요');
          if (!pwd) return;
          const res = await fetch('/public/api/comment_delete.php', {
            method: 'POST',
            body: new URLSearchParams({ comment_id: commentId, password: pwd })
          });
          const json = await res.json();
          if (json.success)  {
            alert('댓글이 성공적으로 삭제되었습니다.');  // ← 이 한 줄 추가
            loadPost(postId);
          }
          else {
            alert(json.error);
          }
        }));

      // 5) 수정 폼 토글
      document.querySelectorAll('.comment .text')
        .forEach(btn => btn.addEventListener('click', () => {
          const commentEl = btn.closest('.comment');
          const formEl    = commentEl.querySelector('.edit-form');
          formEl.style.display = formEl.style.display === 'none' ? 'block' : 'none';
        }));

      // 6) 댓글 수정 저장
      document.querySelectorAll('.comment .save-btn')
        .forEach(btn => btn.addEventListener('click', async () => {
          const commentEl  = btn.closest('.comment');
          const commentId  = commentEl.dataset.commentId;
          const newContent = commentEl.querySelector('.edit-content').value.trim();
          const pwd        = commentEl.querySelector('.edit-pwd').value.trim();
          if (!newContent || !pwd) return alert('내용과 비밀번호를 모두 입력하세요.');

          const res = await fetch('/public/api/comment_update.php', {
            method: 'POST',
            body: new URLSearchParams({
              comment_id: commentId,
              password:   pwd,
              content:    newContent
            })
          });
          const json = await res.json();
          if (json.success) {
            alert('댓글이 성공적으로 수정되었습니다.');  // ← 이 한 줄 추가
            loadPost(postId);
          }
          else {
            alert(json.error);
          }
        }));

      // 7) 수정 취소
      document.querySelectorAll('.comment .cancel-btn')
        .forEach(btn => btn.addEventListener('click', () => {
          btn.closest('.edit-form').style.display = 'none';
        }));

      return data;
    })
    .catch(err => console.error(err));
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
  if (pid) loadPost(pid);
});
