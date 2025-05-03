<?php
require '../db.php';
$tree = getFolders();

// 폴더 필터용 folder_id
$filterFolder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : null;

// POST 처리 (Create / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = intval($_POST['id'] ?? 0);
    $fid         = intval($_POST['folder_id']);
    $title       = trim($_POST['title']);
    $content     = trim($_POST['content']);
    $description = trim($_POST['description'] ?? '');

    if ($title && $content) {
        if ($id) {
            // 수정
            $pdo->prepare("
                UPDATE post
                   SET title       = :t,
                       content     = :c,
                       description = :d,
                       folder_id   = :fid
                 WHERE id          = :id
            ")->execute([
                ':t'   => $title,
                ':c'   => $content,
                ':d'   => $description,
                ':fid' => $fid,
                ':id'  => $id
            ]);
        } else {
            // 생성
            $pdo->prepare("
                INSERT INTO post (title, content, description, folder_id)
                VALUES (:t, :c, :d, :fid)
            ")->execute([
                ':t'   => $title,
                ':c'   => $content,
                ':d'   => $description,
                ':fid' => $fid
            ]);
        }
    }

    // 리다이렉트 (폴더 필터 유지)
    $loc = 'posts.php' . ($filterFolder ? "?folder_id=$filterFolder" : '');
    header("Location: $loc");
    exit;
}

// 리스트 조회 (폴더 필터 적용)
$sql = "SELECT p.*, f.name AS folder_name
          FROM post p
          JOIN folder f ON p.folder_id = f.id";
$params = [];
if ($filterFolder) {
    $sql .= " WHERE p.folder_id = :fid";
    $params[':fid'] = $filterFolder;
}
$sql .= " ORDER BY p.created_at DESC";
$stmt  = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// New/Edit 모드 판단
$isNew  = isset($_GET['new']);
$isEdit = !$isNew && !empty($_GET['id']);
if ($isNew || $isEdit) {
    if ($isNew) {
        $post = [
            'id'          => 0,
            'folder_id'   => $filterFolder,
            'title'       => '',
            'content'     => '',
            'description' => ''
        ];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM post WHERE id = ?");
        $stmt->execute([ intval($_GET['id']) ]);
        $post = $stmt->fetch();  // description 포함
    }
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>Manage Posts</title>
  <link rel="stylesheet" href="style.css">
  <script src="../SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>
</head>
<body>
  <h1>Manage Posts</h1>
  <a href="posts.php?new=1<?= $filterFolder ? "&folder_id=$filterFolder" : "" ?>">New Post</a>

  <?php if (isset($post)): ?>
  <form method="post" onsubmit="return submitContents(this);" novalidate>
    <input type="hidden" name="id" value="<?= $post['id'] ?>">

    <label>Folder:
      <select name="folder_id" required>
        <?php
        function renderFolderSelect($folders, $prefix = '') {
            global $filterFolder;
            foreach ($folders as $f) {
                $sel = $f['id']==$filterFolder ? 'selected' : '';
                echo "<option value=\"{$f['id']}\" $sel>"
                   . $prefix . htmlspecialchars($f['name'])
                   . "</option>";
                if ($f['children']) renderFolderSelect($f['children'], $prefix . '-- ');
            }
        }
        renderFolderSelect($tree);
        ?>
      </select>
    </label>

    <label>Title:
      <input type="text" name="title"
             value="<?= htmlspecialchars($post['title']) ?>" required>
    </label>

    <label>Description:
      <textarea name="description" rows="3"><?= htmlspecialchars($post['description']) ?></textarea>
    </label>

    <label>Content:
      <textarea name="content" id="editor" rows="10"><?= htmlspecialchars($post['content']) ?></textarea>
    </label>

    <button type="submit">Save</button>
  </form>

  <?php else: ?>
  <table border="1" cellpadding="5">
    <tr><th>ID</th><th>Title</th><th>Folder</th><th>Created</th><th>Actions</th></tr>
    <?php foreach ($posts as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><?= htmlspecialchars($p['folder_name']) ?></td>
      <td><?= $p['created_at'] ?></td>
      <td>
        <a href="posts.php?id=<?= $p['id'] ?>
          <?= $filterFolder ? "&folder_id=$filterFolder" : "" ?>">Edit</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>

  <script>
  var oEditors = [];
  nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
    elPlaceHolder: "editor",
    sSkinURI: "../SE2-2.8.2.3/SmartEditor2_noframe.html",
    fCreator: "createSEditor2"
  });
  function submitContents(form) {
    oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);
    return true;
  }
  </script>
</body>
</html>