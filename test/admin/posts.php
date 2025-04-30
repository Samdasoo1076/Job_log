<?php
require '../db.php';
$tree = getFolders();

// ── 수정: 현재 폴더 필터링을 위한 folder_id
$filterFolder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : null;

// POST 처리 (생성/수정 로직은 기존과 동일)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = intval($_POST['id'] ?? 0);
    $fid     = intval($_POST['folder_id']);
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title && $content) {
        if ($id) {
            $pdo->prepare(
                "UPDATE post SET title=:t, content=:c, folder_id=:fid WHERE id=:id"
            )->execute([':t'=>$title,':c'=>$content,':fid'=>$fid,':id'=>$id]);
        } else {
            $pdo->prepare(
                "INSERT INTO post (title, content, folder_id) VALUES (:t,:c,:fid)"
            )->execute([':t'=>$title,':c'=>$content,':fid'=>$fid]);
        }
    }
    // 필터 폴더 유지하며 리다이렉트
    $loc = 'posts.php' . ($filterFolder ? "?folder_id=$filterFolder" : '');
    header("Location: $loc");
    exit;
}

// 리스트 조회, 폴더 필터 적용
$sql = "SELECT p.*, f.name AS folder_name FROM post p JOIN folder f ON p.folder_id=f.id";
$params = [];
if ($filterFolder) {
    $sql .= " WHERE p.folder_id = :fid";
    $params[':fid'] = $filterFolder;
}
$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// New/Edit 모드 판단
$isNew  = isset($_GET['new']);
$isEdit = !$isNew && !empty($_GET['id']);
if ($isNew || $isEdit) {
    if ($isNew) {
        $post = ['id'=>0, 'folder_id'=>$filterFolder, 'title'=>'', 'content'=>''];
    } else {
        $post = $pdo->prepare("SELECT * FROM post WHERE id = ?");
        $post->execute([ intval($_GET['id']) ]);
        $post = $post->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>Manage Posts</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Manage Posts</h1>

  <!-- ── 수정: 현재 폴더 유지하며 New Post 링크 -->
  <a href="posts.php?new=1<?= $filterFolder ? "&folder_id=$filterFolder" : "" ?>">New Post</a>

  <?php if (isset($post)): ?>
    <form method="post">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">
      <label>Folder:
        <select name="folder_id" required>
          <?php
          // ── 수정: 트리 드롭다운 옵션
          function renderFolderSelect($folders, $prefix = '') {
              global $filterFolder;
              foreach ($folders as $f) {
                  $sel = $f['id'] == $filterFolder ? 'selected' : '';
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
      <label>Title: <input type="text" name="title"
        value="<?= htmlspecialchars($post['title']) ?>" required></label>
      <label>Content:
        <textarea name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
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
</body>
</html>
