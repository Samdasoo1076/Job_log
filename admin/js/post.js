// public/js/admin-posts.js
// 대표 글 토글
document.querySelectorAll('.feature-toggle').forEach(cb => {
    cb.addEventListener('change', () => {
      const params = new URLSearchParams();
      params.append('post_id', cb.dataset.id);
      params.append('is_featured', cb.checked ? 1 : 0);
  
      fetch('/admin/api/post_toggle_feature.php', {
        method: 'POST',
        body: params
      })
      .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(json => {
        if (json.success) {
          alert('대표 글 설정이 성공적으로 변경되었습니다.');
        } else {
          alert('대표 글 설정에 실패했습니다.');
          cb.checked = !cb.checked;
        }
      })
      .catch(err => {
        console.error(err);
        alert('대표 글 설정 중 오류가 발생했습니다.');
        cb.checked = !cb.checked;
      });
    });
  });
  

// 댓글 허용 토글 (수정)
document.querySelectorAll('.comment-toggle').forEach(cb => {
    cb.addEventListener('change', () => {
      fetch('/admin/api/post_toggle_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `post_id=${cb.dataset.id}&allow_comment=${cb.checked ? 'Y' : 'N'}`
      })
      .then(r => r.json())
      .then(json => {
        if (!json.success) {
          alert('댓글 허용 설정에 실패했습니다');
          cb.checked = !cb.checked;
        } else if(json.success) {
          alert('댓글 허용 설정에 성공했습니다');
        }
      })
      .catch(() => {
        alert('네트워크 오류');
        cb.checked = !cb.checked;
      });
    });
  });
  