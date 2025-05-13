<?php
// admin/api/folder_delete.php
header('Content-Type: application/json');
require __DIR__ . '/../../db.php';
$body = json_decode(file_get_contents('php://input'), true);
if (!empty($body['ids']) && is_array($body['ids'])) {
    $stmt = $pdo->prepare("DELETE FROM folder WHERE id = ?");
    foreach ($body['ids'] as $id) {
        $stmt->execute([(int)$id]);
    }
    echo json_encode(['success'=>true]);
    exit;
}
http_response_code(400);
echo json_encode(['error'=>'Missing ids']);
