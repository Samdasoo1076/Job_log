<?php
// admin/api/post_toggle_comment.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/post.php';

$postId       = intval($_POST['post_id'] ?? 0);
$allowComment = $_POST['allow_comment'] ?? '';

if ($postId <= 0 || !in_array($allowComment, ['Y', 'N'], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

$ok = updateAllowComment($postId, $allowComment);
echo json_encode(['success' => (bool) $ok]);
exit;  // 여분의 출력 방지