<?php
require __DIR__ . '/../common/common_inc.php';

$pdo->exec("
    CREATE TABLE IF NOT EXISTS account_book_entry (
        id INT(11) NOT NULL AUTO_INCREMENT,
        account_date DATE NOT NULL,
        transaction_type VARCHAR(10) NOT NULL DEFAULT 'expense',
        merchant VARCHAR(255) NOT NULL,
        amount DECIMAL(12,2) NOT NULL DEFAULT 0,
        category VARCHAR(100) DEFAULT '',
        payment_method VARCHAR(100) DEFAULT '',
        payment_channel VARCHAR(100) DEFAULT '',
        memo TEXT DEFAULT NULL,
        use_tf CHAR(1) NOT NULL DEFAULT 'Y',
        del_tf CHAR(1) NOT NULL DEFAULT 'N',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_account_book_date (account_date),
        KEY idx_account_book_type (transaction_type),
        KEY idx_account_book_category (category)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

function accountRedirect(array $params = []): void
{
    $query = $params ? '?' . http_build_query($params) : '';
    header('Location: account_book.php' . $query);
    exit;
}

function accountNormalizeText(?string $value): string
{
    $value = trim((string)$value);
    if ($value !== '' && function_exists('mb_check_encoding') && !mb_check_encoding($value, 'UTF-8')) {
        $converted = @mb_convert_encoding($value, 'UTF-8', 'CP949,EUC-KR,SJIS,UTF-8');
        if ($converted !== false) {
            $value = $converted;
        }
    }
    return $value;
}

function accountValidDate(string $date): bool
{
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return $dt && $dt->format('Y-m-d') === $date;
}

function accountNormalizeDate(string $date): string
{
    $date = trim($date);
    if (preg_match('/^(\d{4})[.\/-](\d{1,2})[.\/-](\d{1,2})/', $date, $matches)) {
        return sprintf('%04d-%02d-%02d', (int)$matches[1], (int)$matches[2], (int)$matches[3]);
    }
    return $date;
}

function accountAmount(string $value): float
{
    $value = str_replace([',', '원', ' '], '', $value);
    return abs((float)$value);
}

function accountSaveEntry(array $input): void
{
    global $pdo;

    $id = (int)($input['id'] ?? 0);
    $date = accountNormalizeDate($input['account_date'] ?? '');
    $type = ($input['transaction_type'] ?? 'expense') === 'income' ? 'income' : 'expense';
    $merchant = accountNormalizeText($input['merchant'] ?? '');
    $amount = accountAmount((string)($input['amount'] ?? '0'));

    if (!accountValidDate($date)) {
        throw new InvalidArgumentException('날짜 형식이 올바르지 않습니다.');
    }
    if ($merchant === '') {
        throw new InvalidArgumentException('사용처를 입력해 주세요.');
    }
    if ($amount <= 0) {
        throw new InvalidArgumentException('금액은 0보다 커야 합니다.');
    }

    $params = [
        ':account_date' => $date,
        ':transaction_type' => $type,
        ':merchant' => $merchant,
        ':amount' => $amount,
        ':category' => accountNormalizeText($input['category'] ?? ''),
        ':payment_method' => accountNormalizeText($input['payment_method'] ?? ''),
        ':payment_channel' => accountNormalizeText($input['payment_channel'] ?? ''),
        ':memo' => accountNormalizeText($input['memo'] ?? ''),
    ];

    if ($id > 0) {
        $stmt = $pdo->prepare("
            UPDATE account_book_entry
               SET account_date = :account_date,
                   transaction_type = :transaction_type,
                   merchant = :merchant,
                   amount = :amount,
                   category = :category,
                   payment_method = :payment_method,
                   payment_channel = :payment_channel,
                   memo = :memo,
                   updated_at = NOW()
             WHERE id = :id
        ");
        $params[':id'] = $id;
        $stmt->execute($params);
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO account_book_entry
            (account_date, transaction_type, merchant, amount, category, payment_method, payment_channel, memo)
        VALUES
            (:account_date, :transaction_type, :merchant, :amount, :category, :payment_method, :payment_channel, :memo)
    ");
    $stmt->execute($params);
}

function accountImportCsv(array $file): int
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('CSV 파일 업로드에 실패했습니다.');
    }

    $handle = fopen($file['tmp_name'], 'r');
    if (!$handle) {
        throw new RuntimeException('CSV 파일을 열 수 없습니다.');
    }

    $count = 0;
    $first = true;
    while (($row = fgetcsv($handle)) !== false) {
        if (!$row || count(array_filter($row, function ($value) {
            return trim((string)$value) !== '';
        })) === 0) {
            continue;
        }

        $row = array_map('accountNormalizeText', $row);
        if ($first) {
            $first = false;
            $headerText = implode(',', $row);
            if (strpos($headerText, '날짜') !== false || strpos(strtolower($headerText), 'date') !== false) {
                continue;
            }
        }

        $date = trim($row[0] ?? '');
        $merchant = trim($row[1] ?? '');
        $amount = trim($row[2] ?? '');
        if ($date === '' || $merchant === '' || $amount === '') {
            continue;
        }

        accountSaveEntry([
            'account_date' => $date,
            'merchant' => $merchant,
            'amount' => $amount,
            'transaction_type' => ($row[3] ?? 'expense') === 'income' || ($row[3] ?? '') === '수입' ? 'income' : 'expense',
            'category' => $row[4] ?? '',
            'payment_method' => $row[5] ?? '',
            'payment_channel' => $row[6] ?? '',
            'memo' => $row[7] ?? '',
        ]);
        $count++;
    }
    fclose($handle);

    return $count;
}

function accountFilterState(array $input): array
{
    $month = preg_match('/^\d{4}-\d{2}$/', $input['month'] ?? '') ? $input['month'] : date('Y-m');
    return [
        'month' => $month,
        'type' => in_array($input['type'] ?? '', ['expense', 'income'], true) ? $input['type'] : '',
        'keyword' => trim($input['keyword'] ?? ''),
        'category' => trim($input['category'] ?? ''),
    ];
}

function accountWhere(array $filters, array &$params): string
{
    $where = ["use_tf = 'Y'", "del_tf = 'N'", "account_date >= :start_date", "account_date < :end_date"];
    $params = [
        ':start_date' => $filters['month'] . '-01',
        ':end_date' => date('Y-m-d', strtotime($filters['month'] . '-01 +1 month')),
    ];

    if ($filters['type'] !== '') {
        $where[] = 'transaction_type = :type';
        $params[':type'] = $filters['type'];
    }
    if ($filters['keyword'] !== '') {
        $where[] = '(merchant LIKE :keyword OR memo LIKE :keyword OR payment_method LIKE :keyword OR payment_channel LIKE :keyword)';
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if ($filters['category'] !== '') {
        $where[] = 'category = :category';
        $params[':category'] = $filters['category'];
    }

    return 'WHERE ' . implode(' AND ', $where);
}

function accountFetchEntries(string $whereSql, array $params, ?int $limit = 300): array
{
    global $pdo;

    $limitSql = $limit === null ? '' : ' LIMIT ' . (int)$limit;
    $stmt = $pdo->prepare("
        SELECT *
          FROM account_book_entry
          {$whereSql}
      ORDER BY account_date DESC, id DESC
          {$limitSql}
    ");
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function accountExportCsv(array $filters, string $whereSql, array $params): void
{
    $entries = accountFetchEntries($whereSql, $params, null);
    $filename = 'account_book_' . $filters['month'] . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-store, no-cache, must-revalidate');

    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['날짜', '사용처', '금액', '구분', '카테고리', '카드/계좌', '결제채널', '메모']);

    foreach ($entries as $entry) {
        fputcsv($out, [
            $entry['account_date'],
            $entry['merchant'],
            (string)(float)$entry['amount'],
            $entry['transaction_type'] === 'income' ? '수입' : '지출',
            $entry['category'],
            $entry['payment_method'],
            $entry['payment_channel'],
            $entry['memo'],
        ]);
    }
    fclose($out);
    exit;
}

$message = '';
$error = '';

try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'save') {
            accountSaveEntry($_POST);
            accountRedirect(['saved' => '1']);
        }

        if ($action === 'delete') {
            $stmt = $pdo->prepare("UPDATE account_book_entry SET use_tf = 'N', del_tf = 'Y', updated_at = NOW() WHERE id = :id");
            $stmt->execute([':id' => (int)($_POST['id'] ?? 0)]);
            accountRedirect(['deleted' => '1']);
        }

        if ($action === 'import_csv') {
            $count = accountImportCsv($_FILES['csv_file'] ?? []);
            accountRedirect(['imported' => $count]);
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

if (isset($_GET['saved'])) {
    $message = '가계부 내역이 저장되었습니다.';
} elseif (isset($_GET['deleted'])) {
    $message = '가계부 내역이 삭제되었습니다.';
} elseif (isset($_GET['imported'])) {
    $message = number_format((int)$_GET['imported']) . '건을 가져왔습니다.';
}

$filters = accountFilterState($_GET);
$month = $filters['month'];
$type = $filters['type'];
$keyword = $filters['keyword'];
$category = $filters['category'];
$params = [];
$whereSql = accountWhere($filters, $params);

if (($_GET['action'] ?? '') === 'export_csv') {
    accountExportCsv($filters, $whereSql, $params);
}

$summaryStmt = $pdo->prepare("
    SELECT
        COALESCE(SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END), 0) AS income_total,
        COALESCE(SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END), 0) AS expense_total,
        COUNT(*) AS entry_count
      FROM account_book_entry
      {$whereSql}
");
$summaryStmt->execute($params);
$summary = $summaryStmt->fetch() ?: ['income_total' => 0, 'expense_total' => 0, 'entry_count' => 0];

$entries = accountFetchEntries($whereSql, $params, 300);

$categoryStmt = $pdo->query("
    SELECT DISTINCT category
      FROM account_book_entry
     WHERE category <> ''
       AND use_tf = 'Y'
       AND del_tf = 'N'
  ORDER BY category ASC
");
$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

$editEntry = null;
if (!empty($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM account_book_entry WHERE id = :id AND del_tf = 'N'");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $editEntry = $stmt->fetch() ?: null;
}

include __DIR__ . '/../layout/header.php';
?>

<section class="account-book">
  <div class="account-head">
    <div>
      <h1>가계부</h1>
      <p>카드 사용 내역을 직접 입력하거나 CSV로 가져와 월별 지출과 수입을 관리합니다.</p>
    </div>
  </div>

  <?php if ($message): ?>
    <div class="account-notice"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="account-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="account-summary">
    <div>
      <span>수입</span>
      <strong><?= number_format((float)$summary['income_total']) ?>원</strong>
    </div>
    <div>
      <span>지출</span>
      <strong><?= number_format((float)$summary['expense_total']) ?>원</strong>
    </div>
    <div>
      <span>잔액</span>
      <strong><?= number_format((float)$summary['income_total'] - (float)$summary['expense_total']) ?>원</strong>
    </div>
    <div>
      <span>건수</span>
      <strong><?= number_format((int)$summary['entry_count']) ?>건</strong>
    </div>
  </div>

  <div class="account-grid">
    <form class="account-panel account-form" method="post">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($editEntry['id'] ?? 0) ?>">
      <h2><?= $editEntry ? '내역 수정' : '내역 추가' ?></h2>
      <label>
        <span>날짜</span>
        <input type="date" name="account_date" value="<?= htmlspecialchars($editEntry['account_date'] ?? date('Y-m-d')) ?>" required>
      </label>
      <label>
        <span>구분</span>
        <select name="transaction_type">
          <?php $currentType = $editEntry['transaction_type'] ?? 'expense'; ?>
          <option value="expense" <?= $currentType === 'expense' ? 'selected' : '' ?>>지출</option>
          <option value="income" <?= $currentType === 'income' ? 'selected' : '' ?>>수입</option>
        </select>
      </label>
      <label>
        <span>사용처</span>
        <input type="text" name="merchant" value="<?= htmlspecialchars($editEntry['merchant'] ?? '') ?>" placeholder="예: 스타벅스" required>
      </label>
      <label>
        <span>금액</span>
        <input type="number" name="amount" min="0" step="1" value="<?= htmlspecialchars(isset($editEntry['amount']) ? (string)(float)$editEntry['amount'] : '') ?>" required>
      </label>
      <label>
        <span>카테고리</span>
        <input type="text" name="category" value="<?= htmlspecialchars($editEntry['category'] ?? '') ?>" placeholder="예: 식비, 교통, 구독">
      </label>
      <label>
        <span>카드/계좌</span>
        <input type="text" name="payment_method" value="<?= htmlspecialchars($editEntry['payment_method'] ?? '') ?>" placeholder="예: 토스카드">
      </label>
      <label>
        <span>결제 채널</span>
        <input type="text" name="payment_channel" value="<?= htmlspecialchars($editEntry['payment_channel'] ?? '') ?>" placeholder="예: 삼성페이, 온라인">
      </label>
      <label class="wide">
        <span>메모</span>
        <textarea name="memo" rows="3"><?= htmlspecialchars($editEntry['memo'] ?? '') ?></textarea>
      </label>
      <div class="account-actions">
        <button type="submit"><?= $editEntry ? '수정 저장' : '추가' ?></button>
        <?php if ($editEntry): ?>
          <a href="account_book.php">취소</a>
        <?php endif; ?>
      </div>
    </form>

    <div class="account-panel">
      <h2>CSV 가져오기</h2>
      <p class="account-help">첫 행은 헤더가 있어도 됩니다. 열 순서: 날짜, 사용처, 금액, 구분, 카테고리, 카드/계좌, 결제채널, 메모</p>
      <form method="post" enctype="multipart/form-data" class="csv-form">
        <input type="hidden" name="action" value="import_csv">
        <input type="file" name="csv_file" accept=".csv,text/csv" required>
        <button type="submit">CSV 업로드</button>
      </form>
    </div>
  </div>

  <form class="admin-filter account-filter" method="get">
    <div class="filter-row">
      <label>
        월
        <input type="month" name="month" value="<?= htmlspecialchars($month) ?>">
      </label>
      <label>
        구분
        <select name="type">
          <option value="">전체</option>
          <option value="expense" <?= $type === 'expense' ? 'selected' : '' ?>>지출</option>
          <option value="income" <?= $type === 'income' ? 'selected' : '' ?>>수입</option>
        </select>
      </label>
      <label>
        카테고리
        <select name="category">
          <option value="">전체</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label class="filter-keyword">
        검색
        <input type="search" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="사용처, 메모, 카드/계좌, 결제채널">
      </label>
      <button type="submit" class="btn filter">검색</button>
      <button type="submit" class="btn export" name="action" value="export_csv">CSV 내보내기</button>
      <a class="btn reset" href="account_book.php">초기화</a>
    </div>
  </form>

  <div class="table-scroll">
    <table class="admin-table account-table">
      <thead>
        <tr>
          <th>날짜</th>
          <th>구분</th>
          <th>사용처</th>
          <th>카테고리</th>
          <th>카드/계좌</th>
          <th>채널</th>
          <th>금액</th>
          <th>메모</th>
          <th>관리</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$entries): ?>
        <tr><td colspan="9" class="empty">등록된 내역이 없습니다.</td></tr>
      <?php endif; ?>
      <?php foreach ($entries as $entry): ?>
        <tr>
          <td><?= htmlspecialchars($entry['account_date']) ?></td>
          <td><?= $entry['transaction_type'] === 'income' ? '수입' : '지출' ?></td>
          <td><?= htmlspecialchars($entry['merchant']) ?></td>
          <td><?= htmlspecialchars($entry['category'] ?: '-') ?></td>
          <td><?= htmlspecialchars($entry['payment_method'] ?: '-') ?></td>
          <td><?= htmlspecialchars($entry['payment_channel'] ?: '-') ?></td>
          <td class="<?= $entry['transaction_type'] === 'income' ? 'amount-income' : 'amount-expense' ?>">
            <?= $entry['transaction_type'] === 'income' ? '+' : '-' ?><?= number_format((float)$entry['amount']) ?>원
          </td>
          <td><?= nl2br(htmlspecialchars($entry['memo'] ?: '')) ?></td>
          <td class="account-row-actions">
            <a class="btn edit" href="?edit_id=<?= (int)$entry['id'] ?>&month=<?= htmlspecialchars($month) ?>">수정</a>
            <form method="post" onsubmit="return confirm('이 내역을 삭제하시겠습니까?');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$entry['id'] ?>">
              <button class="btn delete" type="submit">삭제</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
