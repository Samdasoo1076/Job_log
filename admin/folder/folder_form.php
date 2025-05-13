<?php
// admin/folder/folder_form.php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/folder.php';

$id = intval($_GET['id'] ?? 0);
$folder = $id ? getFolder($id) : null;
$tree   = getFolderTree();
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<link rel="stylesheet" href="/admin/css/folder.css">
<link rel="stylesheet" href="/admin/css/sidebar.css">

<main class="admin-container">
  <h1><?= $folder ? '폴더 수정' : '새 폴더' ?></h1>
  <form method="post" action="folder_list.php">
    <?php if ($folder): ?>
      <input type="hidden" name="edit_id" value="<?= $folder['id'] ?>">
    <?php endif; ?>
    <label>
      상위 폴더
      <select name="parent_id">
        <option value="">-- 없음 --</option>
        <?php 
          function opt2($folders, $prefix='') {
            global $folder;
            foreach($folders as $f) {
              $sel = $folder && $folder['parent_id']==$f['id'] ? 'selected' : '';
              echo "<option value=\"{$f['id']}\" $sel>{$prefix}" . htmlspecialchars($f['name']) . "</option>";
              if ($f['children']) opt2($f['children'], $prefix.'-- ');
            }
          }
          opt2($tree);
        ?>
      </select>
    </label>

    <label>
      폴더명
      <input type="text" name="name"
             value="<?= htmlspecialchars($folder['name'] ?? '') ?>" required>
    </label>

    <button type="submit"><?= $folder ? '수정' : '추가' ?></button>
    <a href="folder_list.php">취소</a>
  </form>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
