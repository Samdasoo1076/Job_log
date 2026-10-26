<?php
require __DIR__ . '/../common/common_inc.php';

$perPageOptions = [20, 50, 100];
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
if (!in_array($perPage, $perPageOptions, true)) {
    $perPage = 20;
}

$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$today = date('Y-m-d');
$defaultFrom = date('Y-m-d', strtotime('-6 days'));

$filters = [
    'date_from' => trim($_GET['date_from'] ?? $defaultFrom),
    'date_to' => trim($_GET['date_to'] ?? $today),
    'device_type' => trim($_GET['device_type'] ?? ''),
    'country_code' => trim($_GET['country_code'] ?? ''),
    'region' => trim($_GET['region'] ?? ''),
    'city' => trim($_GET['city'] ?? ''),
    'keyword' => trim($_GET['keyword'] ?? ''),
    'post_id' => trim($_GET['post_id'] ?? ''),
];

$where = [];
$params = [];

if ($filters['date_from'] !== '') {
    $where[] = 'ual.log_date >= :date_from';
    $params[':date_from'] = $filters['date_from'];
}

if ($filters['date_to'] !== '') {
    $where[] = 'ual.log_date <= :date_to';
    $params[':date_to'] = $filters['date_to'];
}

if (in_array($filters['device_type'], ['PC', 'MO'], true)) {
    $where[] = 'ual.device_type = :device_type';
    $params[':device_type'] = $filters['device_type'];
}

if ($filters['country_code'] !== '') {
    $where[] = 'ual.country_code = :country_code';
    $params[':country_code'] = $filters['country_code'];
}

if ($filters['region'] !== '') {
    $where[] = 'ual.region = :region';
    $params[':region'] = $filters['region'];
}

if ($filters['city'] !== '') {
    $where[] = 'ual.city = :city';
    $params[':city'] = $filters['city'];
}

if ($filters['post_id'] !== '' && ctype_digit($filters['post_id'])) {
    $where[] = 'ual.post_id = :post_id';
    $params[':post_id'] = (int)$filters['post_id'];
}

if ($filters['keyword'] !== '') {
    $where[] = '(ual.url LIKE :keyword OR ual.ip_address LIKE :keyword OR ual.session_id LIKE :keyword OR ual.city LIKE :keyword OR ual.country LIKE :keyword OR p.title LIKE :keyword)';
    $params[':keyword'] = '%' . $filters['keyword'] . '%';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$postJoinSql = $filters['keyword'] !== '' ? 'LEFT JOIN post p ON p.id = ual.post_id' : '';

$locationBaseWhere = [];
$locationParams = [];
if ($filters['date_from'] !== '') {
    $locationBaseWhere[] = 'log_date >= :location_date_from';
    $locationParams[':location_date_from'] = $filters['date_from'];
}
if ($filters['date_to'] !== '') {
    $locationBaseWhere[] = 'log_date <= :location_date_to';
    $locationParams[':location_date_to'] = $filters['date_to'];
}

$countryOptionWhere = $locationBaseWhere;
$countryOptionWhere[] = "country_code IS NOT NULL";
$countryOptionWhere[] = "country_code <> ''";
$countryStmt = $pdo->prepare("
    SELECT country_code, MIN(COALESCE(country, country_code, 'Unknown')) AS country, COUNT(*) AS cnt
      FROM user_activity_log
     WHERE " . implode(' AND ', $countryOptionWhere) . "
  GROUP BY country_code
  ORDER BY cnt DESC, country ASC
");
$countryStmt->execute($locationParams);
$countryOptions = $countryStmt->fetchAll();

$locationWhere = $locationBaseWhere;
if ($filters['country_code'] !== '') {
    $locationWhere[] = 'country_code = :location_country_code';
    $locationParams[':location_country_code'] = $filters['country_code'];
}
$locationWhereSql = $locationWhere ? 'WHERE ' . implode(' AND ', $locationWhere) : '';

$regionStmt = $pdo->prepare("
    SELECT region, COUNT(*) AS cnt
      FROM user_activity_log
      {$locationWhereSql}
     " . ($locationWhereSql ? 'AND' : 'WHERE') . " region IS NOT NULL
       AND region <> ''
  GROUP BY region
  ORDER BY cnt DESC, region ASC
");
$regionStmt->execute($locationParams);
$regionOptions = $regionStmt->fetchAll();

$cityParams = $locationParams;
$cityWhere = $locationWhere;
if ($filters['region'] !== '') {
    $cityWhere[] = 'region = :location_region';
    $cityParams[':location_region'] = $filters['region'];
}
$cityWhereSql = $cityWhere ? 'WHERE ' . implode(' AND ', $cityWhere) : '';

$cityStmt = $pdo->prepare("
    SELECT city, COUNT(*) AS cnt
      FROM user_activity_log
      {$cityWhereSql}
     " . ($cityWhereSql ? 'AND' : 'WHERE') . " city IS NOT NULL
       AND city <> ''
  GROUP BY city
  ORDER BY cnt DESC, city ASC
");
$cityStmt->execute($cityParams);
$cityOptions = $cityStmt->fetchAll();

$countStmt = $pdo->prepare("
    SELECT COUNT(*)
      FROM user_activity_log ual
      {$postJoinSql}
      {$whereSql}
");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));

if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$summaryStmt = $pdo->prepare("
    SELECT
        COUNT(*) AS total_count,
        COUNT(DISTINCT ual.session_id) AS session_count,
        COUNT(DISTINCT ual.ip_address) AS ip_count,
        SUM(CASE WHEN ual.device_type = 'PC' THEN 1 ELSE 0 END) AS pc_count,
        SUM(CASE WHEN ual.device_type = 'MO' THEN 1 ELSE 0 END) AS mo_count
      FROM user_activity_log ual
      {$postJoinSql}
      {$whereSql}
");
$summaryStmt->execute($params);
$summary = $summaryStmt->fetch() ?: [];

$stmt = $pdo->prepare("
    SELECT
        ual.id,
        ual.reg_datetime,
        ual.log_date,
        ual.log_hour,
        ual.log_minute,
        ual.post_id,
        p.title AS post_title,
        ual.url,
        ual.device_type,
        ual.referer,
        ual.session_id,
        ual.ip_address,
        ual.user_agent,
        ual.response_time_ms,
        ual.http_status,
        ual.country,
        ual.country_code,
        ual.region,
        ual.city,
        ual.isp
      FROM user_activity_log ual
 LEFT JOIN post p ON p.id = ual.post_id
      {$whereSql}
  ORDER BY ual.id DESC
     LIMIT :limit OFFSET :offset
");

foreach ($params as $key => $value) {
    $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($key, $value, $type);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll();

$queryBase = $_GET;
unset($queryBase['page']);
$baseQs = http_build_query($queryBase);
$pageUrl = static function (int $targetPage) use ($baseQs): string {
    return '?' . ($baseQs ? $baseQs . '&' : '') . 'page=' . $targetPage;
};

include __DIR__ . '/../layout/header.php';
?>

<h1>접속 로그</h1>

<form class="admin-filter" method="get">
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
      기기
      <select name="device_type">
        <option value="">전체</option>
        <option value="PC" <?= $filters['device_type'] === 'PC' ? 'selected' : '' ?>>PC</option>
        <option value="MO" <?= $filters['device_type'] === 'MO' ? 'selected' : '' ?>>Mobile</option>
      </select>
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
      지역
      <select name="region">
        <option value="">전체</option>
        <?php foreach ($regionOptions as $region): ?>
          <option value="<?= htmlspecialchars($region['region']) ?>" <?= $filters['region'] === $region['region'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($region['region']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      도시
      <select name="city">
        <option value="">전체</option>
        <?php foreach ($cityOptions as $city): ?>
          <option value="<?= htmlspecialchars($city['city']) ?>" <?= $filters['city'] === $city['city'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($city['city']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      게시글 ID
      <input type="number" name="post_id" min="1" value="<?= htmlspecialchars($filters['post_id']) ?>">
    </label>
    <label>
      표시 개수
      <select name="per_page">
        <?php foreach ($perPageOptions as $option): ?>
          <option value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?></option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>
  <div class="filter-row">
    <label class="filter-keyword">
      검색
      <input type="search" name="keyword" placeholder="URL, IP, 세션, 위치, 게시글 제목" value="<?= htmlspecialchars($filters['keyword']) ?>">
    </label>
    <button class="btn filter" type="submit">검색</button>
    <a class="btn filter" href="?country_code=KR&per_page=<?= $perPage ?>">한국 데이터</a>
    <a class="btn reset" href="activity_log.php">초기화</a>
  </div>
</form>

<div class="log-summary">
  <div><strong><?= number_format((int)($summary['total_count'] ?? 0)) ?></strong><span>조회 로그</span></div>
  <div><strong><?= number_format((int)($summary['session_count'] ?? 0)) ?></strong><span>세션</span></div>
  <div><strong><?= number_format((int)($summary['ip_count'] ?? 0)) ?></strong><span>IP</span></div>
  <div><strong><?= number_format((int)($summary['pc_count'] ?? 0)) ?></strong><span>PC</span></div>
  <div><strong><?= number_format((int)($summary['mo_count'] ?? 0)) ?></strong><span>Mobile</span></div>
</div>

<div class="table-scroll">
  <table class="admin-table activity-table">
    <thead>
      <tr>
        <th>#</th>
        <th>시간</th>
        <th>기기</th>
        <th>게시글</th>
        <th>URL</th>
        <th>IP / 위치</th>
        <th>상태</th>
        <th>응답</th>
        <th>Referer</th>
        <th>User Agent</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!$logs): ?>
      <tr>
        <td colspan="10" class="empty">조회된 로그가 없습니다.</td>
      </tr>
    <?php endif; ?>
    <?php foreach ($logs as $idx => $log): ?>
      <tr>
        <td><?= $total - ($offset + $idx) ?></td>
        <td class="nowrap"><?= htmlspecialchars($log['reg_datetime']) ?></td>
        <td><?= htmlspecialchars($log['device_type']) ?></td>
        <td>
          <?php if ($log['post_id']): ?>
            <div><?= (int)$log['post_id'] ?></div>
            <div class="muted"><?= htmlspecialchars($log['post_title'] ?? '삭제된 글') ?></div>
          <?php else: ?>
            <span class="muted">-</span>
          <?php endif; ?>
        </td>
        <td class="log-url" title="<?= htmlspecialchars($log['url']) ?>"><?= htmlspecialchars($log['url']) ?></td>
        <td>
          <div><?= htmlspecialchars($log['ip_address']) ?></div>
          <div class="muted">
            <?= htmlspecialchars(trim(($log['country_code'] ? $log['country_code'] . ' ' : '') . ($log['region'] ?? '') . ' ' . ($log['city'] ?? ''))) ?: '-' ?>
          </div>
          <?php if (!empty($log['isp'])): ?>
            <div class="muted"><?= htmlspecialchars($log['isp']) ?></div>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars((string)($log['http_status'] ?? '-')) ?></td>
        <td><?= $log['response_time_ms'] !== null ? number_format((int)$log['response_time_ms']) . 'ms' : '-' ?></td>
        <td class="log-url" title="<?= htmlspecialchars($log['referer'] ?? '') ?>"><?= htmlspecialchars($log['referer'] ?? '-') ?></td>
        <td class="log-agent" title="<?= htmlspecialchars($log['user_agent'] ?? '') ?>"><?= htmlspecialchars($log['user_agent'] ?? '-') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="<?= htmlspecialchars($pageUrl($page - 1)) ?>">&laquo; Prev</a>
  <?php endif; ?>

  <?php
  $startPage = max(1, $page - 5);
  $endPage = min($totalPages, $page + 5);
  for ($p = $startPage; $p <= $endPage; $p++):
  ?>
    <?php if ($p === $page): ?>
      <span class="current"><?= $p ?></span>
    <?php else: ?>
      <a href="<?= htmlspecialchars($pageUrl($p)) ?>"><?= $p ?></a>
    <?php endif; ?>
  <?php endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="<?= htmlspecialchars($pageUrl($page + 1)) ?>">Next &raquo;</a>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
