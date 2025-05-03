// public/js/post.js

// 글 불러오는 공용 함수
function loadPost(postId) {
    fetch(`api/post.php?post_id=${postId}`)
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
          <h2>Comments</h2>
          ${data.comments.map(c => `
            <div class="comment">
              <strong>${c.author}</strong> (${c.created_at})
              <p>${c.content}</p>
            </div>
          `).join('')}
          <section class="comment-form">
            <h3>Add Comment</h3>
            <form id="comment-form">
              <input type="hidden" name="post_id" value="${postId}">
              <input name="author" placeholder="Your name" required><br>
              <textarea name="content" rows="4" placeholder="Comment" required></textarea><br>
              <button type="submit">Submit</button>
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
                loadPost(postId);  // 댓글 업데이트 후 다시 불러오기
              } else {
                alert(json.error || '댓글 작성에 실패했습니다.');
              }
            });
        });
      })
      .catch(err => console.error(err));
  }
  
  window.addEventListener('DOMContentLoaded', () => {
    // 1) 포스트 링크 클릭 바인딩
    document.querySelectorAll('.post-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const postId = link.dataset.postId;
        loadPost(postId);
        history.pushState(null, '', `?post_id=${postId}`);
      });
    });
  
    // 2) 새로고침·직접 URL 입력 시 처리
    const params = new URLSearchParams(window.location.search);
    const pid = params.get('post_id');
    if (pid) {
      loadPost(pid);
    }
  });
  
  window.addEventListener('popstate', () => {
    const params = new URLSearchParams(window.location.search);
    const pid = params.get('post_id');
    if (pid) {
      loadPost(pid);
    } else {
      // 기본 화면 복원
      document.getElementById('content').innerHTML = `
        <h1>Welcome!</h1>
        <p>Select a folder or a post from the sidebar.</p>
      `;
    }
  });
  