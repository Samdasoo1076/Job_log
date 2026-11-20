<?php
// public/api/post.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../db.php';
require __DIR__ . '/../../common/logger.php';
require __DIR__ . '/../../common/com/etc.php';      // getBrowserName()

$postId = intval($_GET['post_id'] ?? 0);
if (!$postId) {
    http_response_code(404);
    echo json_encode(['error'=>'Invalid post_id']);
    exit;
}

// 뷰 카운트 증가


// 2) 포스트 조회
$stmt = $pdo->prepare("SELECT id, title, description, content, created_at, updated_at, view_count, allow_comment FROM post WHERE id = ? AND del_tf = 'N'");
$stmt->execute([$postId]);
$post = $stmt->fetch();
if (!$post) {
    http_response_code(404);
    echo json_encode(['error'=>'Not found']);
    exit;
}
$stmt = $pdo->prepare("UPDATE post SET view_count = view_count + 1 WHERE id = ?");
$stmt->execute([$postId]);
$post['view_count']++;


// 3) 로그 남기기 (글 조회 이벤트)
log_user_activity($postId);

// 댓글 정보 (allow_comment 이 'Y' 일 때만)
if ($post['allow_comment'] === 'Y') {
    $stmt = $pdo->prepare("
        SELECT id, author, content, created_at, updated_at
          FROM comment
         WHERE post_id = ?
           AND use_tf = 'Y'
           AND del_tf = 'N'
         ORDER BY created_at
    ");
    $stmt->execute([$postId]);
    $comments = $stmt->fetchAll();
} else {
    $comments = [];
}

echo json_encode([
    'title'    => $post['title'],
    'description'    => $post['description'],
    'content'  => $post['content'],
    'views'    => $post['view_count'],
    'created_at'    => $post['created_at'],
    'updated_at'    => $post['updated_at'],
    'allow_comment' => $post['allow_comment'],
    'comments' => $comments
]);
