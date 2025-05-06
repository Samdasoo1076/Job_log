// public/js/post.js

// (1) loadPost가 데이터를 불러오고 화면만 렌더링하도록 변경
export function loadPost(postId) {
  return fetch(`api/post.php?post_id=${postId}`)
    .then(res => {
      if (!res.ok) throw new Error(res.statusText);
      return res.json();
    })
    .then(data => {
      const content = document.getElementById('content');
      content.innerHTML = `
        <h1>${data.title}</h1>
        ${data.description ? `<p class="description">${data.description}</p>` : ''}
        <div>${data.content}</div>
        <p>Views: ${data.views}</p>
        <h2>댓글</h2>
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

      // 댓글 폼 AJAX 바인딩
      const form = document.getElementById('comment-form');
      form.addEventListener('submit', ev => {
        ev.preventDefault();
        const fd = new FormData(form);
        fetch('api/comment.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(json => {
            if (json.success) {
              loadPost(postId);
            } else {
              alert(json.error || '댓글 작성에 실패했습니다.');
            }
          });
      });

      return data;  // 호출한 쪽에서 .then(data=>…) 으로 후속 처리할 수 있게 리턴
    });
}

// 전역으로도 쓰일 수 있게 간단히 노출
window.loadPost = loadPost;


// 페이지 로드 & 사이드바 링크 바인딩
window.addEventListener('DOMContentLoaded', () => {
  // 1) 사이드바의 .post-link 클릭 시
  document.querySelectorAll('.post-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const id = +link.dataset.postId;      // 숫자로 변환
      const title = link.dataset.title;     // sidebar.php 에 data-title 속성 추가 필요
      loadPost(id).then(() => {
        window.openTab(id, title);
        history.pushState(null, '', `?post_id=${id}`);
      });
    });
  });

  // 2) 새로고침·직접 URL 진입 시
  const pid = +new URLSearchParams(window.location.search).get('post_id');
  if (pid) {
    loadPost(pid);
  }
});
