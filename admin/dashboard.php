<?php
// admin/dashboard.php
require_once __DIR__ . '/common/common_inc.php';  // 세션·로그인 체크, $pdo 준비
include __DIR__ . '/common/front_head.php';
require_once __DIR__ . '/layout/header.php';   // ↖ 여기를 추가

// DB에서 각 테이블 카운트 조회
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

<?php include __DIR__ . '/layout/footer.php'; ?>
