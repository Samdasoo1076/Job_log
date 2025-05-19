<?php
// File: admin/comments.php
require __DIR__ . '/../common/common_inc.php';  // header, session, charset 등

// 페이지네이션 설정
$perPage = 20;
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset  = ($page - 1) * $perPage;

// 총 댓글 수 조회
$totalStmt = $pdo->query("SELECT COUNT(*) FROM comment");
$total      = $totalStmt->fetchColumn();

// 댓글 리스트 조회 (post 제목 조인)
$stmt = $pdo->prepare("
    SELECT c.id, c.author, c.content, c.use_tf, c.del_tf,
           c.created_at, p.title AS post_title
      FROM comment c
 LEFT JOIN post p ON p.id = c.post_id
  ORDER BY c.updated_at DESC
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll();
?>

  <?php include __DIR__ . '/../layout/header.php'; // 관리자 네비 ?>

    <h1 style="color: #fff;">댓글 관리</h1>
    <table style="color: #fff;">
      <thead>
        <tr>
          <th>#</th>
          <th>게시글</th>
          <th>작성자</th>
          <th>내용</th>
          <th>공개여부</th>
          <th>삭제여부</th>
          <th>작성시간</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($comments as $idx => $c): ?>
        <tr>
          <td><?= $total - ($offset + $idx) ?></td>
          <td><?= htmlspecialchars($c['post_title'] ?? '삭제된 글') ?></td>
          <td><?= htmlspecialchars($c['author']) ?></td>
          <td><?= nl2br(htmlspecialchars($c['content'])) ?></td>
          <td><?= $c['use_tf'] === 'Y' ? 'Y' : 'N' ?></td>
          <td><?= $c['del_tf'] === 'Y' ? 'Y' : 'N' ?></td>
          <td><?= $c['created_at'] ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <div class="pagination">
    <?php
      $totalPages = ceil($total / $perPage);
      for ($p = 1; $p <= $totalPages; $p++):
        if ($p === $page): ?>
          <span class="current"><?= $p ?></span>
        <?php else: ?>
          <a href="?page=<?= $p ?>"><?= $p ?></a>
        <?php endif;
      endfor;
    ?>
    </div>
