<?php
// public/components/post_detail.php

/**
 * 선택된 포스트 상세 + 댓글 폼 렌더링
 * @param array $post     getPost() 결과
 * @param array $comments getComments() 결과
 */
function renderPostDetail(array $post, array $comments) {
    echo '<article class="post-detail">';
    echo '  <h1>' . htmlspecialchars($post['title']) . '</h1>';
    echo '  <div class="meta">Views: ' . $post['view_count'] . '</div>';
    if (!empty($post['description'])) {
        echo '<p class="description">' . htmlspecialchars($post['description']) . '</p>';
    }
    echo '  <div class="content">' . nl2br(htmlspecialchars($post['content'])) . '</div>';
    echo '</article>';

    echo '<section class="comments">';
    echo '  <h2>댓글</h2>';
    foreach ($comments as $c) {
        echo '<div class="comment">';
        echo '  <strong>' . htmlspecialchars($c['author']) . '</strong>';
        echo '  <time>(' . $c['created_at'] . ')</time>';
        echo '  <p>' . nl2br(htmlspecialchars($c['content'])) . '</p>';
        echo '</div>';
    }
    echo '</section>';

    echo '<section class="comment-form">';
    echo '  <h3>댓글 달기</h3>';
    echo '  <form id="comment-form">';
    echo '    <input type="hidden" name="post_id" value="' . $post['id'] . '">';
    echo '    <input name="author" placeholder="Names" required><br>';
    echo '    <textarea name="content" rows="4" placeholder="do it comment" required></textarea><br>';
    echo '    <button type="submit">Submit</button>';
    echo '  </form>';
    echo '</section>';
}
