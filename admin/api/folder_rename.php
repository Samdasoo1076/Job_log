<?php
// admin/api/folder_rename.php
header('Content-Type: application/json');
require __DIR__ . '/../../db.php';
$body = json_decode(file_get_contents('php://input'), true);
if (!empty($body['id']) && isset($body['name'])) {
    $pdo->prepare("UPDATE folder SET name = :name WHERE id = :id")
        ->execute([':name'=>trim($body['name']), ':id'=>$body['id']]);
    echo json_encode(['success'=>true]);
    exit;
}
http_response_code(400);
echo json_encode(['error'=>'Missing fields']);
