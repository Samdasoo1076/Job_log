<?php
// admin/popular.php
require __DIR__ . '/../common/common_inc.php';
include __DIR__ . '/../layout/header.php';

// 1) 이번 주 인기 게시글 (지난 7일간 조회 수 기준)
$weekStmt = $pdo->prepare("
    SELECT p.id, p.title, COUNT(*) AS cnt
      FROM user_activity_log u
      JOIN post p ON u.post_id = p.id
     WHERE u.log_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
       AND u.post_id IS NOT NULL
  GROUP BY u.post_id
  ORDER BY cnt DESC
  LIMIT 10
");
$weekStmt->execute();
$weekly = $weekStmt->fetchAll();

// 2) 오늘 인기 게시글 (오늘자 기준)
$dayStmt = $pdo->prepare("
    SELECT p.id, p.title, COUNT(*) AS cnt
      FROM user_activity_log u
      JOIN post p ON u.post_id = p.id
     WHERE u.log_date = CURDATE()
       AND u.post_id IS NOT NULL
  GROUP BY u.post_id
  ORDER BY cnt DESC
  LIMIT 10
");
$dayStmt->execute();
$daily = $dayStmt->fetchAll();

// 3) 신규 방문자 많이 본 게시글 (오늘 방문(session_id 첫 등장) 기준)
$newUsersStmt = $pdo->prepare("
    SELECT p.id, p.title, COUNT(*) AS cnt
      FROM (
        SELECT session_id, MIN(reg_datetime) AS first_view
          FROM user_activity_log
         WHERE log_date = CURDATE()
         GROUP BY session_id
      ) nu
      JOIN user_activity_log u 
        ON u.session_id = nu.session_id
       AND u.reg_datetime = nu.first_view
      JOIN post p ON u.post_id = p.id
  GROUP BY u.post_id
  ORDER BY cnt DESC
  LIMIT 10
");
$newUsersStmt->execute();
$newest = $newUsersStmt->fetchAll();
?>

<main class="admin-container">
  <h1>인기 게시글</h1>

  <ul class="tabs">
    <li class="active" data-tab="week">금주 인기</li>
    <li data-tab="day">금일 인기</li>
    <li data-tab="newest">신규 방문 인기</li>
  </ul>

  <div class="tab-content" id="week">
    <h2>지난 7일간 조회 수 TOP 10</h2>
    <ol>
      <?php foreach($weekly as $row): ?>
        <li>
          <a href="/admin/post/posts_form.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['title']) ?> (<?= $row['cnt'] ?>회)
          </a>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>

  <div class="tab-content" id="day" style="display:none">
    <h2>오늘 조회 수 TOP 10</h2>
    <ol>
      <?php foreach($daily as $row): ?>
        <li>
          <a href="/admin/post/posts_form.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['title']) ?> (<?= $row['cnt'] ?>회)
          </a>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>

  <div class="tab-content" id="newest" style="display:none">
    <h2>오늘 신규 방문자 첫 조회 TOP 10</h2>
    <ol>
      <?php foreach($newest as $row): ?>
        <li>
          <a href="/admin/post/posts_form.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['title']) ?> (<?= $row['cnt'] ?>명)
          </a>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
