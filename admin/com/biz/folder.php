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

function moveFolder(int $id, ?int $parentId, int $sortOrder): void {
    global $pdo;
    $stmt = $pdo->prepare("
      UPDATE folder
         SET parent_id  = :pid
           , sort_order = :ord
       WHERE id         = :id
    ");
    $stmt->execute([
      ':pid' => $parentId,
      ':ord' => $sortOrder,
      ':id'  => $id,
    ]);
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
 * 폴더 “소프트 삭제”(use_tf=N, del_tf=Y)
 */
function softDeleteFolder(int $id): void {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE folder
           SET use_tf = 'N',
               del_tf = 'Y'
         WHERE id     = ?
    ");
    $stmt->execute([$id]);
}

/**
 * 하드 삭제: 레코드 완전 삭제
 */
function hardDeleteFolder(int $id): void {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM folder WHERE id = ?");
    $stmt->execute([$id]);
}

/**
 * 삭제된 폴더를 복구합니다.
 *
 * @param int $id
 */
function restoreFolder(int $id): void {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE folder
           SET use_tf = 'Y',
               del_tf = 'N'
         WHERE id     = :id
    ");
    $stmt->execute([':id' => $id]);
}



/**
 * renderNestable: 네스터블용 OL/LI + 인라인 버튼 렌더러
 */
function renderNestable(array $folders): bool {
    echo '<ol class="dd-list">';
    foreach ($folders as $f) {
        $hasChildren = !empty($f['children']);
        
        // 자식 버퍼링
        $childHtml = '';
        if ($hasChildren) {
            ob_start();
            renderNestable($f['children']);
            $childHtml = ob_get_clean();
        }

        echo '<li class="dd-item" data-id="'. $f['id'] .'">';
          // 핸들(드래그 영역)
          echo '<div class="dd-handle">';
          echo '  <span class="folder-name">'.htmlspecialchars($f['name']).'</span>';
          echo '  <button class="edit-btn"    data-nodrag="true" title="수정">✎</button>';
          echo '  <button class="delete-btn"  data-nodrag="true" title="삭제">🗑</button>';
          echo '</div>';

          if ($hasChildren) {
            echo $childHtml;
          }
        echo '</li>';
    }
    echo '</ol>';
    return true;
}

/**
 * SortableJS 용 폴더 트리 렌더러
 *
 * @param array $folders getFolderTree() 결과
 */
function renderList(array $folders): void {
    echo '<ul id="folder-list">';
    _renderItems($folders);
    echo '</ul>';
}

function _renderItems(array $folders): void {
    foreach($folders as $f) {
        $hasChildren = ! empty($f['children']);
        $deleted     = ($f['del_tf'] ?? 'N') === 'Y';

        // use_tf, del_tf 플래그에 따라 'deleted' 클래스
        $cls = ($f['use_tf'] === 'N' || $f['del_tf'] === 'Y') ? 'deleted' : '';
        // data-parent-id 추가
        $pid = $f['parent_id'] !== null ? $f['parent_id'] : '';

        echo "<li data-id=\"{$f['id']}\" data-parent-id=\"{$pid}\" class=\"{$cls}\">";
        echo '<span class="handle">☰</span>';
        echo '<span class="name">'.htmlspecialchars($f['name']).'</span>';
        echo '<button class="create-btn" title="하위 폴더 추가">＋</button>';
        echo '<button class="edit-btn"   title="수정">✎</button>';
        if ($deleted) {
            // 하드 삭제 & 복원 버튼
            echo '<button class="hard-delete-btn" title="완전 삭제">✖</button>';
            echo '<button class="restore-btn" title="복구">🛟</button>';
        } else {
            // 기존 삭제 버튼
            echo '<button class="delete-btn" title="삭제">🗑</button>';
        }
        // 항상 빈 <ul>을 찍어 줍니다
        echo '  <ul class="nested-list">';  
        if ($hasChildren) {
            _renderItems($f['children']);
        } else if(($f['children'])) {
            _renderItems($f['children']);
        }
        echo '  </ul>';
      echo '</li>';
    }
}