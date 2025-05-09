<?php
// public/api/comment.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../db.php';

$postId  = intval($_POST['post_id'] ?? 0);
$author  = trim($_POST['author']   ?? '');
$content = trim($_POST['content']  ?? '');
$password = trim($_POST['password']    ?? '');

if (!$postId || $author === '' || $content === '') {
    http_response_code(400);
    echo json_encode(['error'=>'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO comment (post_id, author, content, password)
    VALUES (:pid, :auth, :cont, :pwd)
");
$stmt->execute([
    ':pid'  => $postId,
    ':auth' => $author,
    ':cont' => $content,
    ':pwd'  => $password,
]);

echo json_encode(['success'=>true]);
