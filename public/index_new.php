<?php
// public/index.php
require __DIR__ . '/../db.php';

// 전체 폴더 트리 로드
$tree = getFolders();

// 재귀 렌더링 함수
function renderTree(array $folders) {
    echo '<ul class="tree">';
    foreach ($folders as $f) {
        // ▶▶ data-folder-id 속성 추가
        echo '<li data-folder-id="' . $f['id'] . '">';
          echo '<div class="folder">';
            echo '<span class="arrow"></span>';
            echo '<span class="folder-name">' . htmlspecialchars($f['name']) . '</span>';
          echo '</div>';
          // 서브폴더 재귀 렌더
          if (!empty($f['children'])) {
              renderTree($f['children']);
          }
          // 이 폴더의 글 목록
          $posts = getPostsByFolder($f['id']);
          if ($posts) {
              echo '<ul class="posts">';
              foreach ($posts as $p) {
                  echo '<li>';
                  echo '<a href="#" class="post-link" data-post-id="' . $p['id'] . '">'
                       . htmlspecialchars($p['title'])
                       . '</a>';
                  echo '</li>';
              }
              echo '</ul>';
          }
        echo '</li>';
    }
    echo '</ul>';
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>Blog</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div id="sidebar">
    <?php renderTree($tree); ?>
  </div>

  <div id="content">
    <h1>Welcome!</h1>
    <p>폴더를 클릭하거나 글을 선택해보세요.</p>
  </div>

  <script>
  // 폴더 아코디언 토글 & 상태 저장
  function bindFolders() {
    document.querySelectorAll('#sidebar .folder').forEach(folder => {
      folder.addEventListener('click', e => {
        e.stopPropagation();
        const li = folder.parentElement;
        li.classList.toggle('expanded');
        // 현재 열린 폴더 ID 목록 저장
        const opened = Array.from(
          document.querySelectorAll('.tree li.expanded')
        ).map(el => el.dataset.folderId);
        localStorage.setItem('expandedFolders', JSON.stringify(opened));
      });
    });
  }

  // 저장된 아코디언 상태 복원
  function restoreAccordion() {
    const opened = JSON.parse(localStorage.getItem('expandedFolders') || '[]');
    opened.forEach(id => {
      const li = document.querySelector(`.tree li[data-folder-id="${id}"]`);
      if (li) li.classList.add('expanded');
    });
  }

  // 포스트 로드 및 렌더링
  function loadPost(postId) {
    fetch(`api/post.php?post_id=${postId}`)
      .then(res => res.json())
      .then(data => {
        const content = document.getElementById('content');
        content.innerHTML = `
          <h1>${data.title}</h1>
          <div>${data.content}</div>
          <p>Views: ${data.views}</p>
          <h2>Comments</h2>
          ${data.comments.map(c =>
            `<div><strong>${c.author}</strong> (${c.created_at})<p>${c.content}</p></div>`
          ).join('')}
          <h3>Add Comment</h3>
          <form id="comment-form">
            <input type="hidden" name="post_id" value="${postId}">
            <input name="author" placeholder="Your name" required><br>
            <textarea name="content" rows="4" placeholder="Comment" required></textarea><br>
            <button type="submit">Submit</button>
          </form>
        `;
        // 댓글 AJAX 전송
        document.getElementById('comment-form').addEventListener('submit', ev => {
          ev.preventDefault();
          const fd = new FormData(ev.target);
          fetch('api/comment.php', { method: 'POST', body: fd })
            .then(() => loadPost(postId));
        });
      });
  }

  // URL 변경 시 처리
  function handleLocation() {
    const params = new URLSearchParams(window.location.search);
    const postId = params.get('post_id');
    if (postId) {
      loadPost(postId);
    } else {
      document.getElementById('content').innerHTML = `
        <h1>Welcome!</h1>
        <p>폴더를 클릭하거나 글을 선택해보세요.</p>
      `;
    }
  }

  // 포스트 링크 바인딩
  function bindPostLinks() {
    document.querySelectorAll('.post-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const postId = link.dataset.postId;
        loadPost(postId);
        history.pushState(null, '', `?post_id=${postId}`);
      });
    });
  }

  // 초기화
  window.addEventListener('DOMContentLoaded', () => {
    bindFolders();
    bindPostLinks();
    handleLocation();
    restoreAccordion();
  });

  // 뒤로/앞으로 가기 처리
  window.addEventListener('popstate', () => {
    handleLocation();
    restoreAccordion();
  });
  </script>
</body>
</html>
