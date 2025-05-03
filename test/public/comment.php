<?php
// public/comment.php
require __DIR__ . '/../db.php';  // public/ 하위에 있으므로 한 단계 위로 올라가서 db.php 불러오기

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId  = intval($_POST['post_id'] ?? 0);
    $author  = trim($_POST['author']   ?? '');
    $content = trim($_POST['content']  ?? '');

    if ($postId && $author !== '' && $content !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO comment (post_id, author, content)
            VALUES (:pid, :auth, :cont)
        ");
        $stmt->execute([
            ':pid'  => $postId,
            ':auth' => $author,
            ':cont' => $content
        ]);
    }
}

// 댓글 작성 후 다시 해당 글 보기 페이지로 리다이렉트
header('Location: index.php?post_id=' . $postId);
exit;
