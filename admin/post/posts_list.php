<?php
// admin/posts_list.php
require __DIR__ . '/../common/common_inc.php';    // → admin/common/common_inc.php
require __DIR__ . '/../com/biz/post.php';         // getPostList()
require __DIR__ . '/../com/biz/folder.php';         // getPostList()

$tree         = getFolders();

$filterFolder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : null;
$perPage      = 10;
$page         = max(1, intval($_GET['page'] ?? 1));
$offset       = ($page - 1) * $perPage;

// biz 레이어 호출로 전체 개수 + 페이지 데이터 받기
$result = getPostList($filterFolder, $perPage, $offset);
$total   = $result['total'];
$posts   = $result['rows'];
$totalPages = (int)ceil($total / $perPage);

include __DIR__ . '/../layout/header.php';

// ▶ 삭제 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  deletePost((int)$_POST['delete_id']);
  header('Location: posts_list.php?folder_id=' . $filterFolder . '&page=' . $page);
  exit;
}

?>

<h1>게시글 관리</h1>
<p>
  <a href="posts_form.php?folder_id=<?= $filterFolder ?>&page=<?= $page ?>">✚ New Post</a>
</p>

<table class="admin-table">
  <thead>
    <tr>
      <th>#</th>
      <th>ID</th>
      <th>제목</th>
      <th>폴더</th>
      <th>작성일</th>
      <th>대표</th>
      <th>댓글 허용</th>
      <th>액션</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($posts as $i => $p): ?>
    <tr>
      <td><?= $total - ($offset + $i) /* 역순 번호 */ ?></td>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><?= htmlspecialchars($p['folder_name'] ?? '(최상위)') ?></td>
      <td><?= $p['created_at'] ?></td>
      <td>
        <input type="checkbox"
              data-id="<?= $p['id'] ?>"
              class="feature-toggle"
              <?= $p['is_featured'] ? 'checked' : '' ?>>
      </td>
      <td><input
          type="checkbox"
          class="comment-toggle"
          data-id="<?= $p['id'] ?>"
          <?= $p['allow_comment'] === 'Y' ? 'checked' : '' ?>></td>
      <td>
        <a class="btn edit" href="posts_form.php?id=<?= $p['id'] ?>&folder_id=<?= $filterFolder ?>&page=<?= $page ?>">Edit</a>
        <!-- soft delete 폼 -->
        <form method="post" style="display:inline;" onsubmit="return confirm('정말 이 글을 삭제하시겠습니까?');">
          <input type="hidden" name="delete_id" value="<?= $p['id'] ?>">
          <button class="btn delete">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="pagination">
  <?php $baseQs = $filterFolder ? "folder_id=$filterFolder&" : ''; ?>
  <?php if ($page > 1): ?>
    <a href="?<?= $baseQs ?>page=<?= $page - 1 ?>">&laquo; Prev</a>
  <?php endif; ?>

  <?php for ($p = 1; $p <= $totalPages; $p++): ?>
    <?php if ($p === $page): ?>
      <span class="current"><?= $p ?></span>
    <?php else: ?>
      <a href="?<?= $baseQs ?>page=<?= $p ?>"><?= $p ?></a>
    <?php endif; ?>
  <?php endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="?<?= $baseQs ?>page=<?= $page + 1 ?>">Next &raquo;</a>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
