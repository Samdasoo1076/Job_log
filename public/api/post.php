<?php
// public/api/post.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../db.php';

$postId = intval($_GET['post_id'] ?? 0);
if (!$postId) {
    echo json_encode(['error'=>'Invalid post_id']);
    exit;
}

// 글 정보
$stmt = $pdo->prepare("UPDATE post SET view_count = view_count + 1 WHERE id = ?");
$stmt->execute([$postId]);

$stmt = $pdo->prepare("SELECT id, title, description, content, created_at, updated_at, view_count FROM post WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();
if (!$post) {
    echo json_encode(['error'=>'Not found']);
    exit;
}

// 댓글 정보
$stmt = $pdo->prepare("SELECT id, author, content, created_at, updated_at FROM comment WHERE post_id = ? AND use_tf = 'Y' AND del_tf = 'N' ORDER BY created_at");
$stmt->execute([$postId]);
$comments = $stmt->fetchAll();

echo json_encode([
    'title'    => $post['title'],
    'description'    => $post['description'],
    'content'  => $post['content'],
    'views'    => $post['view_count'],
    'created_at'    => $post['created_at'],
    'updated_at'    => $post['updated_at'],
    'comments' => $comments
]);
