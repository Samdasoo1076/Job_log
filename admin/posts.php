<?php
// admin/posts.php
require '../db.php';
$tree = getFolders();

// 폴더 필터용 folder_id
$filterFolder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : null;

// 페이지네이션 설정
$perPage = 10;
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// 1) 전체 게시글 수 조회 (필터 적용)
$countSql = "SELECT COUNT(*) FROM post p";
$countParams = [];
if ($filterFolder) {
    $countSql .= " WHERE p.folder_id = :fid";
    $countParams[':fid'] = $filterFolder;
}
$totalPosts = $pdo->prepare($countSql);
$totalPosts->execute($countParams);
$totalCount = $totalPosts->fetchColumn();

// 전체 페이지 수
$totalPages = (int)ceil($totalCount / $perPage);
if ($page > $totalPages) $page = $totalPages;

// 2) 실제 목록 조회 (필터 + 정렬 + LIMIT/OFFSET)
$offset = ($page - 1) * $perPage;
$sql = "SELECT p.*, f.name AS folder_name
          FROM post p
          JOIN folder f ON p.folder_id = f.id";
$params = [];
if ($filterFolder) {
    $sql .= " WHERE p.folder_id = :fid";
    $params[':fid'] = $filterFolder;
}
$sql .= " ORDER BY p.created_at DESC
          LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
// PDO::PARAM_INT 로 바인딩해야 합니다
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_INT);
}
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
$stmt->execute();
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
        $post = $stmt->fetch();
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

  <a href="posts.php?new=1<?= $filterFolder ? "&folder_id=$filterFolder" : '' ?>">
    New Post
  </a>

  <?php if (isset($post)): ?>
    <!-- New/Edit Form -->
    <form method="post" action="posts.php<?= $filterFolder ? "?folder_id=$filterFolder" : '' ?>" onsubmit="return submitContents(this);" novalidate>
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
    <!-- List View -->
    <table border="1" cellpadding="5">
      <tr>
        <th>#</th>
        <th>ID</th>
        <th>Title</th>
        <th>Folder</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($posts as $idx => $p): ?>
        <tr>
          <!-- row number: 전체에서의 순서 -->
          <td><?= $totalCount - ($offset + $idx) ?></td>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['title']) ?></td>
          <td><?= htmlspecialchars($p['folder_name']) ?></td>
          <td><?= $p['created_at'] ?></td>
          <td>
            <a href="posts.php?id=<?= $p['id'] ?>
              <?= $filterFolder ? "&folder_id={$filterFolder}" : '' ?>">
              Edit
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <!-- 페이지네이션 -->
    <div class="pagination">
      <?php
        // 기본 쿼리스트링 (folder_id만)
        $baseQs = $filterFolder ? "folder_id={$filterFolder}&" : '';
        if ($page > 1):
      ?>
        <a href="posts.php?<?= $baseQs ?>page=<?= $page - 1 ?>">&laquo; Prev</a>
      <?php endif; ?>

      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <?php if ($p === $page): ?>
          <span class="current"><?= $p ?></span>
        <?php else: ?>
          <a href="posts.php?<?= $baseQs ?>page=<?= $p ?>"><?= $p ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <a href="posts.php?<?= $baseQs ?>page=<?= $page + 1 ?>">Next &raquo;</a>
      <?php endif; ?>
    </div>
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
    