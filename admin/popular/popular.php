<?php
require __DIR__ . '/../common/common_inc.php';

$today = date('Y-m-d');
$defaultFrom = date('Y-m-d', strtotime('-6 days'));

$filters = [
    'date_from' => trim($_GET['date_from'] ?? $defaultFrom),
    'date_to' => trim($_GET['date_to'] ?? $today),
    'country_code' => trim($_GET['country_code'] ?? ''),
    'device_type' => trim($_GET['device_type'] ?? ''),
    'keyword' => trim($_GET['keyword'] ?? ''),
];

$limitOptions = [10, 20, 50, 100];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
if (!in_array($limit, $limitOptions, true)) {
    $limit = 20;
}

$where = [
    'u.post_id IS NOT NULL',
    "p.use_tf = 'Y'",
    "p.del_tf = 'N'",
];
$params = [];

if ($filters['date_from'] !== '') {
    $where[] = 'u.log_date >= :date_from';
    $params[':date_from'] = $filters['date_from'];
}

if ($filters['date_to'] !== '') {
    $where[] = 'u.log_date <= :date_to';
    $params[':date_to'] = $filters['date_to'];
}

if ($filters['country_code'] !== '') {
    $where[] = 'u.country_code = :country_code';
    $params[':country_code'] = $filters['country_code'];
}

if (in_array($filters['device_type'], ['PC', 'MO'], true)) {
    $where[] = 'u.device_type = :device_type';
    $params[':device_type'] = $filters['device_type'];
}

if ($filters['keyword'] !== '') {
    $where[] = '(p.title LIKE :keyword OR p.description LIKE :keyword OR u.url LIKE :keyword)';
    $params[':keyword'] = '%' . $filters['keyword'] . '%';
}

$whereSql = 'WHERE ' . implode(' AND ', $where);

$countryOptionWhere = [
    'log_date >= :country_date_from',
    'log_date <= :country_date_to',
    "country_code IS NOT NULL",
    "country_code <> ''",
];
$countryOptionParams = [
    ':country_date_from' => $filters['date_from'],
    ':country_date_to' => $filters['date_to'],
];
$countryOptionsStmt = $pdo->prepare("
    SELECT country_code, MIN(COALESCE(country, country_code, 'Unknown')) AS country, COUNT(*) AS cnt
      FROM user_activity_log
     WHERE " . implode(' AND ', $countryOptionWhere) . "
  GROUP BY country_code
  ORDER BY cnt DESC, country ASC
");
$countryOptionsStmt->execute($countryOptionParams);
$countryOptions = $countryOptionsStmt->fetchAll();

$summaryStmt = $pdo->prepare("
    SELECT
        COUNT(*) AS total_views,
        COUNT(DISTINCT u.session_id) AS total_sessions,
        COUNT(DISTINCT u.ip_address) AS total_ips,
        COUNT(DISTINCT u.post_id) AS total_posts,
        SUM(CASE WHEN u.device_type = 'PC' THEN 1 ELSE 0 END) AS pc_views,
        SUM(CASE WHEN u.device_type = 'MO' THEN 1 ELSE 0 END) AS mo_views
      FROM user_activity_log u
      JOIN post p ON p.id = u.post_id
      {$whereSql}
");
$summaryStmt->execute($params);
$summary = $summaryStmt->fetch() ?: [];

$rankStmt = $pdo->prepare("
    SELECT
        p.id,
        p.title,
        p.view_count,
        p.is_featured,
        COUNT(*) AS views,
        COUNT(DISTINCT u.session_id) AS sessions,
        COUNT(DISTINCT u.ip_address) AS ips,
        SUM(CASE WHEN u.device_type = 'PC' THEN 1 ELSE 0 END) AS pc_views,
        SUM(CASE WHEN u.device_type = 'MO' THEN 1 ELSE 0 END) AS mo_views,
        ROUND(AVG(u.response_time_ms)) AS avg_response_time,
        MAX(u.reg_datetime) AS last_viewed_at
      FROM user_activity_log u
      JOIN post p ON p.id = u.post_id
      {$whereSql}
  GROUP BY p.id, p.title, p.view_count, p.is_featured
  ORDER BY views DESC, sessions DESC, ips DESC, last_viewed_at DESC
     LIMIT :limit
");
foreach ($params as $key => $value) {
    $rankStmt->bindValue($key, $value);
}
$rankStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$rankStmt->execute();
$popularPosts = $rankStmt->fetchAll();

$trendStmt = $pdo->prepare("
    SELECT u.log_date, COUNT(*) AS views, COUNT(DISTINCT u.session_id) AS sessions
      FROM user_activity_log u
      JOIN post p ON p.id = u.post_id
      {$whereSql}
  GROUP BY u.log_date
  ORDER BY u.log_date ASC
");
$trendStmt->execute($params);
$dailyTrend = $trendStmt->fetchAll();
$maxTrendViews = max(array_column($dailyTrend ?: [['views' => 0]], 'views'));

$countryStmt = $pdo->prepare("
    SELECT
        COALESCE(u.country_code, '-') AS country_code,
        MIN(COALESCE(u.country, 'Unknown')) AS country,
        COUNT(*) AS views
      FROM user_activity_log u
      JOIN post p ON p.id = u.post_id
      {$whereSql}
  GROUP BY u.country_code
  ORDER BY views DESC
     LIMIT 10
");
$countryStmt->execute($params);
$countryRank = $countryStmt->fetchAll();

$queryBase = $_GET;
unset($queryBase['date_from'], $queryBase['date_to']);
$presetUrl = static function (string $from, string $to) use ($queryBase): string {
    return '?' . http_build_query(array_merge($queryBase, [
        'date_from' => $from,
        'date_to' => $to,
    ]));
};
$countryPresetUrl = static function (string $countryCode) use ($filters, $limit): string {
    return '?' . http_build_query([
        'date_from' => $filters['date_from'],
        'date_to' => $filters['date_to'],
        'country_code' => $countryCode,
        'device_type' => $filters['device_type'],
        'keyword' => $filters['keyword'],
        'limit' => $limit,
    ]);
};

include __DIR__ . '/../layout/header.php';
?>

<h1>인기 게시글</h1>

<form class="popular-filter" method="get">
  <div class="filter-row">
    <label>
      시작일
      <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>">
    </label>
    <label>
      종료일
      <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>">
    </label>
    <label>
      국가 코드
      <select name="country_code">
        <option value="">전체</option>
        <?php foreach ($countryOptions as $country): ?>
          <option value="<?= htmlspecialchars($country['country_code']) ?>" <?= $filters['country_code'] === $country['country_code'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($country['country'] . ' (' . $country['country_code'] . ')') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      기기
      <select name="device_type">
        <option value="">전체</option>
        <option value="PC" <?= $filters['device_type'] === 'PC' ? 'selected' : '' ?>>PC</option>
        <option value="MO" <?= $filters['device_type'] === 'MO' ? 'selected' : '' ?>>Mobile</option>
      </select>
    </label>
    <label>
      표시 개수
      <select name="limit">
        <?php foreach ($limitOptions as $option): ?>
          <option value="<?= $option ?>" <?= $limit === $option ? 'selected' : '' ?>><?= $option ?></option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>
  <div class="filter-row">
    <label class="filter-keyword">
      검색
      <input type="search" name="keyword" placeholder="게시글 제목, 설명, URL" value="<?= htmlspecialchars($filters['keyword']) ?>">
    </label>
    <button class="btn filter" type="submit">검색</button>
    <a class="btn filter" href="<?= htmlspecialchars($countryPresetUrl('KR')) ?>">한국 데이터</a>
    <a class="btn filter" href="<?= htmlspecialchars($presetUrl($today, $today)) ?>">오늘</a>
    <a class="btn filter" href="<?= htmlspecialchars($presetUrl($defaultFrom, $today)) ?>">최근 7일</a>
    <a class="btn filter" href="<?= htmlspecialchars($presetUrl(date('Y-m-d', strtotime('-29 days')), $today)) ?>">최근 30일</a>
    <a class="btn reset" href="popular.php">초기화</a>
  </div>
</form>

<div class="popular-summary">
  <div><strong><?= number_format((int)($summary['total_views'] ?? 0)) ?></strong><span>조회</span></div>
  <div><strong><?= number_format((int)($summary['total_sessions'] ?? 0)) ?></strong><span>세션</span></div>
  <div><strong><?= number_format((int)($summary['total_ips'] ?? 0)) ?></strong><span>IP</span></div>
  <div><strong><?= number_format((int)($summary['total_posts'] ?? 0)) ?></strong><span>조회된 글</span></div>
  <div><strong><?= number_format((int)($summary['pc_views'] ?? 0)) ?></strong><span>PC</span></div>
  <div><strong><?= number_format((int)($summary['mo_views'] ?? 0)) ?></strong><span>Mobile</span></div>
</div>

<section class="popular-section">
  <h2>인기 게시글 순위</h2>
  <div class="table-scroll">
    <table class="admin-table popular-table">
      <thead>
        <tr>
          <th>순위</th>
          <th>게시글</th>
          <th>기간 조회</th>
          <th>세션</th>
          <th>IP</th>
          <th>PC</th>
          <th>MO</th>
          <th>평균 응답</th>
          <th>누적 조회</th>
          <th>대표</th>
          <th>마지막 조회</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$popularPosts): ?>
        <tr>
          <td colspan="11" class="empty">조건에 맞는 인기 게시글 데이터가 없습니다.</td>
        </tr>
      <?php endif; ?>
      <?php foreach ($popularPosts as $idx => $post): ?>
        <tr>
          <td><?= $idx + 1 ?></td>
          <td>
            <a href="/admin/post/posts_form.php?id=<?= (int)$post['id'] ?>">
              <?= htmlspecialchars($post['title']) ?>
            </a>
            <div class="muted">ID <?= (int)$post['id'] ?></div>
          </td>
          <td><?= number_format((int)$post['views']) ?></td>
          <td><?= number_format((int)$post['sessions']) ?></td>
          <td><?= number_format((int)$post['ips']) ?></td>
          <td><?= number_format((int)$post['pc_views']) ?></td>
          <td><?= number_format((int)$post['mo_views']) ?></td>
          <td><?= $post['avg_response_time'] !== null ? number_format((int)$post['avg_response_time']) . 'ms' : '-' ?></td>
          <td><?= number_format((int)$post['view_count']) ?></td>
          <td>
            <input type="checkbox"
                   class="feature-toggle"
                   data-id="<?= (int)$post['id'] ?>"
                   <?= (int)$post['is_featured'] === 1 ? 'checked' : '' ?>>
          </td>
          <td class="nowrap"><?= htmlspecialchars($post['last_viewed_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<div class="popular-panels">
  <section class="popular-section">
    <h2>일자별 추이</h2>
    <?php if (!$dailyTrend): ?>
      <p class="muted">추이 데이터가 없습니다.</p>
    <?php endif; ?>
    <?php foreach ($dailyTrend as $row): ?>
      <?php $width = $maxTrendViews > 0 ? max(3, ((int)$row['views'] / $maxTrendViews) * 100) : 0; ?>
      <div class="trend-row">
        <span class="trend-label"><?= htmlspecialchars($row['log_date']) ?></span>
        <span class="trend-bar"><i style="width: <?= $width ?>%"></i></span>
        <span class="trend-value"><?= number_format((int)$row['views']) ?> / <?= number_format((int)$row['sessions']) ?>세션</span>
      </div>
    <?php endforeach; ?>
  </section>

  <section class="popular-section">
    <h2>국가별 조회</h2>
    <table class="admin-table compact-table">
      <thead>
        <tr>
          <th>국가</th>
          <th>조회</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$countryRank): ?>
        <tr><td colspan="2" class="empty">국가 데이터가 없습니다.</td></tr>
      <?php endif; ?>
      <?php foreach ($countryRank as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['country'] . ' (' . $row['country_code'] . ')') ?></td>
          <td><?= number_format((int)$row['views']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
