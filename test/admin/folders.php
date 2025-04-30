<?php
require '../db.php';

// 전체 폴더 트리 가져오기
$tree = getFolders();

// POST 처리: 생성(Create) / 수정(Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_id'])) {
        // 수정
        $id   = intval($_POST['update_id']);
        $name = trim($_POST['name']);
        if ($name !== '') {
            $pdo->prepare("UPDATE folder SET name = :name WHERE id = :id")
                ->execute([':name' => $name, ':id' => $id]);
        }
    } else {
        // 생성
        $parent = ($_POST['parent_id'] !== '') ? intval($_POST['parent_id']) : null;
        $name   = trim($_POST['name']);
        if ($name !== '') {
            $pdo->prepare("INSERT INTO folder (parent_id, name) VALUES (:pid, :name)")
                ->execute([':pid' => $parent, ':name' => $name]);
        }
    }
    header('Location: folders.php');
    exit;
}

// 편집 모드 판단
$editId     = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : null;
$editFolder = null;
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM folder WHERE id = ?");
    $stmt->execute([$editId]);
    $editFolder = $stmt->fetch();
}

// 드롭다운 옵션 렌더 (재귀)
function renderFolderOptions(array $folders, string $prefix = '') {
    foreach ($folders as $f) {
        echo '<option value="' . $f['id'] . '">' 
           . $prefix . htmlspecialchars($f['name']) 
           . '</option>';
        if (!empty($f['children'])) {
            renderFolderOptions($f['children'], $prefix . '-- ');
        }
    }
}

// 트리 렌더 (재귀)
function renderTree(array $folders) {
    echo '<ul>';
    foreach ($folders as $f) {
        echo '<li>';
        echo htmlspecialchars($f['name']);
        echo ' <span class="actions">';
        echo '<a href="?edit_id=' . $f['id'] . '">수정</a>';
        echo ' | <a href="?parent_id=' . $f['id'] . '">폴더 추가</a>';
        echo ' | <a href="posts.php?new=1&folder_id=' . $f['id'] . '">글 추가</a>';
        echo '</span>';
        if (!empty($f['children'])) {
            renderTree($f['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>Manage Folders</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Manage Folders</h1>

  <?php if ($editFolder): // 수정 폼 ?>
  <form method="post" action="folders.php">
    <input type="hidden" name="update_id" value="<?= $editFolder['id'] ?>">
    <label>폴더명 수정:
      <input type="text" name="name" value="<?= htmlspecialchars($editFolder['name']) ?>" required>
    </label>
    <button type="submit">수정</button>
    <a href="folders.php">취소</a>
  </form>
  <?php else: // 생성 폼 ?>
  <form method="post" action="folders.php">
    <label>Parent Folder:
      <select name="parent_id">
        <option value="">-- Top Level --</option>
        <?php renderFolderOptions($tree); ?>
      </select>
    </label>
    <label>Name:
      <input type="text" name="name" required>
    </label>
    <button type="submit">Create</button>
  </form>
  <?php endif; ?>

  <div id="folder-tree">
    <?php renderTree($tree); ?>
  </div>
</body>
</html>
