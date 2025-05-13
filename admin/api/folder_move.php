<?php
// admin/api/folder_move.php
header('Content-Type: application/json');
require __DIR__ . '/../../db.php';

$payload = json_decode(file_get_contents('php://input'), true);

// 1) 트리 구조 전체 업데이트 (drag&drop 시)
if (isset($payload['tree'])) {
    function walk($list, $parent=null, &$pdo){
        $order = 0;
        foreach ($list as $item) {
            $pdo->prepare("UPDATE folder SET parent_id = :pid, sort_order = :ord WHERE id = :id")
                ->execute([
                  ':pid' => $parent,
                  ':ord' => $order++,
                  ':id'  => $item['id']
                ]);
            if (!empty($item['children'])) {
                walk($item['children'], $item['id'], $pdo);
            }
        }
    }
    walk($payload['tree'], null, $pdo);
}

// 2) bulk move: override parent_id
if (!empty($payload['ids']) && isset($payload['parent_id'])) {
    $stmt = $pdo->prepare("UPDATE folder SET parent_id = :pid WHERE id = :id");
    foreach ($payload['ids'] as $id) {
        $stmt->execute([':pid'=>$payload['parent_id'], ':id'=>$id]);
    }
}

echo json_encode(['success'=>true]);
