<?php
// admin/com/biz/folder.php
require_once __DIR__ . '/../../db.php';
 

function getFolders($parentId = null) {
    global $pdo;
    if ($parentId === null) {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id IS NULL ORDER BY name");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id = :pid ORDER BY name");
        $stmt->execute([':pid' => $parentId]);
    }
    $folders = $stmt->fetchAll();
    foreach ($folders as &$f) {
        $f['children'] = getFolders($f['id']);
    }
    return $folders;
}

/**
* 전체 폴더 트리를 재귀로 반환
* (각 parent_id 그룹 내에서 sort_order 순으로 정렬)
*
* @param int|null $parentId
* @return array
*/
function getFolderTree(?int $parentId = null): array {
    global $pdo;
    if ($parentId === null) {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id IS NULL ORDER BY sort_order");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id = :pid ORDER BY sort_order");
        $stmt->execute([':pid' => $parentId]);
    }
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['children'] = getFolderTree($r['id']);
    }
    return $rows;
}
 
/**
* 순서 변경(드래그&드롭) 후 DB에 반영
* @param array      $nodes    nestable('serialize') 결과
* @param int|null   $parentId 이 그룹의 parent_id
*/
function updateOrder(array $nodes, ?int $parentId = null): void {
    global $pdo;
    foreach ($nodes as $index => $node) {
        $stmt = $pdo->prepare("
            UPDATE folder
               SET parent_id  = :pid,
                   sort_order = :ord
             WHERE id         = :id
        ");
        $stmt->execute([
            ':pid' => $parentId,
            ':ord' => $index,
            ':id'  => $node['id'],
        ]);
        if (!empty($node['children'])) {
            updateOrder($node['children'], $node['id']);
        }
    }
}
 
/**
* 단일 폴더 조회
* @param int $id
* @return array|false
*/
function getFolder(int $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM folder WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
 
/**
* 새 폴더 생성
* @param array $data ['parent_id'=>int|null, 'name'=>string]
*/
function createFolder(array $data): void {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO folder (parent_id, name) VALUES (:pid, :name)");
    $stmt->execute([
        ':pid'  => $data['parent_id'],
        ':name' => $data['name'],
    ]);
}
 
/**
* 기존 폴더 수정
* @param int   $id
* @param array $data ['parent_id'=>int|null, 'name'=>string]
*/
function updateFolder(int $id, array $data): void {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE folder SET parent_id = :pid, name = :name WHERE id = :id");
    $stmt->execute([
        ':pid'  => $data['parent_id'],
        ':name' => $data['name'],
        ':id'   => $id,
    ]);
}
 
/**
* 폴더 삭제
* @param int $id
*/
function deleteFolder(int $id): void {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM folder WHERE id = ?");
    $stmt->execute([$id]);
}

/**
 * Nestable용 트리 렌더러
 * @param array      $folders  getFolderTree() 결과
 */
/**
 * 네스터블 트리 + 인라인 편집/삭제 버튼 렌더러
 */
function renderNestable(array $folders, ?int $expandedRoot = null, $currentPostId = null): bool {
    echo '<ol class="dd-list">';
    $thisLevelHasActive = false;

    foreach ($folders as $f) {
        $hasChildren = ! empty($f['children']);
        // 자식 HTML 버퍼링
        $childHtml      = '';
        $childHasActive = false;
        if ($hasChildren) {
            ob_start();
            $childHasActive = renderNestable($f['children'], $expandedRoot, $currentPostId);
            $childHtml      = ob_get_clean();
        }

        echo '<li class="dd-item" data-id="'. $f['id'] .'">';
          // handle 영역: 드래그 & 폴더명 + 버튼
          echo '<div class="dd-handle">';
          echo '  <span class="folder-name">'. htmlspecialchars($f['name']) .'</span>';
          // 인라인 버튼
          echo '  <button class="edit-btn" data-nodrag  title="수정">✎</button>';
          echo '  <button class="delete-btn" data-nodrag  title="삭제">🗑</button>';
          echo '</div>';

          // 자식 목록
          if ($hasChildren) {
              echo $childHtml;
          }
        echo '</li>';

        if ($childHasActive) $thisLevelHasActive = true;
    }

    echo '</ol>';
    return $thisLevelHasActive;
}
