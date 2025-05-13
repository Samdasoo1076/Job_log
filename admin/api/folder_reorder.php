<?php
// admin/api/folder_reorder.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../common/common_inc.php';  // 세션·로그인
require __DIR__ . '/../../com/biz/folder.php';     // $pdo

// 1) JSON 디코드
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['folders']) || !is_array($input['folders'])) {
    http_response_code(400);
    echo json_encode(['success'=>false, 'error'=>'Invalid payload']);
    exit;
}

// 2) 재귀 업데이트 함수
function updateOrder(array $nodes, int $parentId = null) {
    global $pdo;
    foreach ($nodes as $index => $node) {
        // id 가 유효한지 검사
        $id = intval($node['id']);
        // parent_id, sort_order 업데이트
        $stmt = $pdo->prepare("
            UPDATE folder
               SET parent_id  = :pid,
                   sort_order = :ord
             WHERE id         = :id
        ");
        $stmt->execute([
            ':pid'  => $parentId,
            ':ord'  => $index,
            ':id'   => $id,
        ]);

        // 자식이 있으면 재귀
        if (!empty($node['children']) && is_array($node['children'])) {
            updateOrder($node['children'], $id);
        }
    }
}

// 3) 실행
try {
    $pdo->beginTransaction();
    updateOrder($input['folders'], null);
    $pdo->commit();
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}
