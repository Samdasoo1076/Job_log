<?php
// admin/com/biz/Integration.php
require_once __DIR__ . '/../../db.php';

/**
 * 폴더 트리를 재귀로 가져오면서, 각 폴더별 게시글 리스트도 포함합니다.
 *
 * @param int|null $parentId
 * @return array
 */
function getFolderPostTree(?int $parentId = null): array {
    global $pdo;
    // 1) 폴더 가져오기 (sort_order 순)
    if ($parentId === null) {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id IS NULL ORDER BY sort_order");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id = :pid ORDER BY sort_order");
        $stmt->execute([':pid' => $parentId]);
    }
    $folders = $stmt->fetchAll();

    foreach ($folders as &$f) {
        // 하위 폴더
        $f['children'] = getFolderPostTree($f['id']);
        // 이 폴더의 게시글 (sort_order 순, use_tf='Y' & del_tf='N')
        $pstmt = $pdo->prepare("
            SELECT * FROM post
             WHERE folder_id = :fid
               AND use_tf = 'Y'
               AND del_tf = 'N'
             ORDER BY sort_order
        ");
        $pstmt->execute([':fid' => $f['id']]);
        $f['posts'] = $pstmt->fetchAll();
    }
    return $folders;
}

// 폴더 CRUD
function createFolder(array $data): void { /* ... 기존 createFolder ... */ }
function updateFolder(int $id, array $data): void { /* ... 기존 updateFolder ... */ }
function deleteFolder(int $id): void { /* ... 기존 deleteFolder ... */ }
function moveFolder(int $id, ?int $parentId, int $order): void {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE folder
           SET parent_id  = :pid,
               sort_order = :ord
         WHERE id         = :id
    ");
    $stmt->execute([':pid'=>$parentId,':ord'=>$order,':id'=>$id]);
}

// 게시글 CRUD
function createPost(array $data): void { /* POST INSERT */ }
function updatePost(int $id, array $data): void { /* POST UPDATE */ }
function deletePost(int $id): void { /* soft-delete: set del_tf='Y' */ }
function movePost(int $id, ?int $folderId, int $order): void {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE post
           SET folder_id  = :fid,
               sort_order = :ord
         WHERE id         = :id
    ");
    $stmt->execute([':fid'=>$folderId,':ord'=>$order,':id'=>$id]);
}
