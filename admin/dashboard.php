<?php
// admin/dashboard.php
require_once __DIR__ . '/common/common_inc.php';
// header.php 안에서 front_head.php 까지 포함하므로 이 한 줄만 있으면 됩니다.
require_once __DIR__ . '/layout/header.php';

// DB에서 카운트
$totalFolders  = $pdo->query('SELECT COUNT(*) FROM folder')->fetchColumn();
$totalPosts    = $pdo->query('SELECT COUNT(*) FROM post')->fetchColumn();
$totalComments = $pdo->query('SELECT COUNT(*) FROM comment')->fetchColumn();
?>
<h1>관리자 대시보드</h1>
<div class="dashboard-cards">
  <div class="card">
    <h3>카테고리 수</h3>
    <p><?= $totalFolders ?></p>
  </div>
  <div class="card">
    <h3>게시글 수</h3>
    <p><?= $totalPosts ?></p>
  </div>
  <div class="card">
    <h3>댓글 수</h3>
    <p><?= $totalComments ?></p>
  </div>
</div>
<?php
require_once __DIR__ . '/layout/footer.php';
