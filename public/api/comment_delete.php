<?php
// public/api/comment_delete.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../db.php';

$commentId = intval($_POST['comment_id']  ?? 0);
$password  = trim($_POST['password']     ?? '');

if (!$commentId || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

// 1) 비밀번호 및 상태 검증
$stmt = $pdo->prepare("SELECT password, del_tf, use_tf FROM comment WHERE id = :id");
$stmt->execute([':id' => $commentId]);
$c = $stmt->fetch();

if (
    !$c
    || $c['del_tf'] === 'Y'
    || $c['use_tf'] === 'N'
    || $c['password'] !== $password
) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// 2) 삭제 플래그 업데이트
$stmt = $pdo->prepare("
    UPDATE comment
       SET del_tf     = 'Y',
           updated_at = NOW()
     WHERE id         = :id
");
$stmt->execute([':id' => $commentId]);

echo json_encode(['success' => true]);
exit;
