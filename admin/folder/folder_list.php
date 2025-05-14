<?php
// admin/folder/folder_list.php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/folder.php';



// 1) 드래그&드롭 순서 변경 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $order = json_decode($_POST['order'], true);
    if (is_array($order)) {
        updateOrder($order);
        echo json_encode(['success' => true]);
        exit;
    }
}

// 2) 생성/수정/삭제 처리
// if ($_SERVER['REQUEST_METHOD']==='POST') {
//     if (isset($_POST['delete_id'])) {
//         deleteFolder((int)$_POST['delete_id']);
//     } elseif (!empty($_POST['edit_id'])) {
//         updateFolder((int)$_POST['edit_id'], [
//             'parent_id' => $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null,
//             'name'      => trim($_POST['name'])
//         ]);
//     } elseif (!empty($_POST['new_name'])) {
//         createFolder([
//             'parent_id' => $_POST['new_parent'] !== '' ? (int)$_POST['new_parent'] : null,
//             'name'      => trim($_POST['new_name'])
//         ]);
//     }
//     header('Location: folder_list.php');
//     exit;
// }

// 3) 화면용 데이터
$tree = getFolderTree();

?>
<?php include __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/admin/vendor/nestable/jquery.nestable.css">

<main class="folder-container">
  <h1>폴더 관리</h1>

  <!-- 신규 폴더 생성 -->
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

  <!-- 드래그&드롭 가능 트리 -->
  <div class="dd" id="folder-nestable">
  <?php renderNestable($tree); ?>
</div>

</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
