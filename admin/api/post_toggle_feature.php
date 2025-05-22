<?php
// admin/api/post_toggle_feature.php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/post.php';

$postId      = intval($_POST['post_id'] ?? 0);
$isFeatured  = intval($_POST['is_featured'] ?? 0);

$pdo->prepare("UPDATE post SET is_featured = :f WHERE id = :id")
    ->execute([':f'=>$isFeatured, ':id'=>$postId]);

echo json_encode(['success'=>true]);
exit;