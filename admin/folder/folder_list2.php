<?php
// admin/folder/folder_list.php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/folder.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['move_id'])) {
    $id        = (int)$_POST['move_id'];
    $parent_id = $_POST['parent_id'] === '' ? null : (int)$_POST['parent_id'];
    $ord       = (int)$_POST['sort_order'];

    $stmt = $pdo->prepare("
        UPDATE folder
           SET parent_id  = :pid,
               sort_order = :ord
         WHERE id         = :id");
    $stmt->execute([
      ':pid' => $parent_id,
      ':ord' => $ord,
      ':id'  => $id,
    ]);
    echo json_encode(['success'=>true]);
    exit;
}


if ($_SERVER['REQUEST_METHOD']==='POST') {
  // 1) drag & drop 이동 요청
  if (isset($_POST['move_id'])) {
    moveFolder(
      intval($_POST['move_id']),
      ($_POST['parent_id']!=='' ? intval($_POST['parent_id']) : null),
      intval($_POST['sort_order'])
    );
    echo json_encode(['success'=>true]);
    exit;
  }
  // 2) 인라인 수정
  if (!empty($_POST['edit_id'])) {
    updateFolder(
      intval($_POST['edit_id']),
      ['parent_id'=>null, 'name'=>trim($_POST['name'])]
    );
    echo json_encode(['success'=>true]);
    exit;
  }
  // 3) 삭제
  if (!empty($_POST['delete_id'])) {
    deleteFolder(intval($_POST['delete_id']));
    echo json_encode(['success'=>true]);
    exit;
  }
  // 4) 신규
  if (!empty($_POST['new_name'])) {
    createFolder([
      'parent_id'=>($_POST['new_parent']!==''?intval($_POST['new_parent']):null),
      'name'=>trim($_POST['new_name'])
    ]);
    header('Location: folder_list.php');
    exit;
  }
}

// 3) 화면용 데이터
$tree = getFolderTree();

?>
<?php include __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/admin/vendor/nestable/jquery.nestable.css">

<div class="folder-container">
  <h1>폴더 관리</h1>

  <form method="post" class="folder-form">
  <select name="new_parent">
      <option value="">-- 상위 폴더 선택 --</option>
      <?php 
        function opt($folders, $prefix='') {
          foreach($folders as $f) {
            echo "<option value=\"{$f['id']}\">{$prefix}" . htmlspecialchars($f['name']) . "</option>";
            if ($f['children']) opt($f['children'], $prefix.'-- ');
          }
        }
        opt($tree);
      ?>
    </select>
    <input type="text" name="new_name" placeholder="새 폴더명" required>
    <button type="submit">추가</button>
  </form>
 <!-- 새로 렌더링된 트리 -->
 <?php renderList($tree); ?>
</div>

</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
