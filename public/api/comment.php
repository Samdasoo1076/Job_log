<?php
// public/api/comment.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../db.php';

$postId  = intval($_POST['post_id'] ?? 0);
$author  = trim($_POST['author']   ?? '');
$content = trim($_POST['content']  ?? '');

if (!$postId || $author === '' || $content === '') {
    http_response_code(400);
    echo json_encode(['error'=>'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO comment (post_id, author, content)
    VALUES (:pid, :auth, :cont)
");
$stmt->execute([
    ':pid'  => $postId,
    ':auth' => $author,
    ':cont' => $content
]);

echo json_encode(['success'=>true]);
