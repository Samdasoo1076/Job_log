<?php
require __DIR__ . '/../common/common_inc.php';

$pdo->exec("
    CREATE TABLE IF NOT EXISTS secret_note (
        id INT(11) NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        note_body TEXT NOT NULL,
        tags VARCHAR(255) DEFAULT '',
        is_important TINYINT(1) NOT NULL DEFAULT 0,
        use_tf CHAR(1) NOT NULL DEFAULT 'Y',
        del_tf CHAR(1) NOT NULL DEFAULT 'N',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_secret_note_important (is_important),
        KEY idx_secret_note_updated (updated_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

function secretRedirect(array $params = []): void
{
    $query = $params ? '?' . http_build_query($params) : '';
    header('Location: secret_note.php' . $query);
    exit;
}

function secretText(?string $value): string
{
    return trim((string)$value);
}

function secretSave(array $input): void
{
    global $pdo;

    $id = (int)($input['id'] ?? 0);
    $title = secretText($input['title'] ?? '');
    $body = secretText($input['note_body'] ?? '');

    if ($title === '') {
        throw new InvalidArgumentException('제목을 입력해 주세요.');
    }
    if ($body === '') {
        throw new InvalidArgumentException('내용을 입력해 주세요.');
    }

    $params = [
        ':title' => $title,
        ':note_body' => $body,
        ':tags' => secretText($input['tags'] ?? ''),
        ':is_important' => !empty($input['is_important']) ? 1 : 0,
    ];

    if ($id > 0) {
        $stmt = $pdo->prepare("
            UPDATE secret_note
               SET title = :title,
                   note_body = :note_body,
                   tags = :tags,
                   is_important = :is_important,
                   updated_at = NOW()
             WHERE id = :id
        ");
        $params[':id'] = $id;
        $stmt->execute($params);
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO secret_note (title, note_body, tags, is_important)
        VALUES (:title, :note_body, :tags, :is_important)
    ");
    $stmt->execute($params);
}

$message = '';
$error = '';

try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'save') {
            secretSave($_POST);
            secretRedirect(['saved' => '1']);
        }

        if ($action === 'delete') {
            $stmt = $pdo->prepare("UPDATE secret_note SET use_tf = 'N', del_tf = 'Y', updated_at = NOW() WHERE id = :id");
            $stmt->execute([':id' => (int)($_POST['id'] ?? 0)]);
            secretRedirect(['deleted' => '1']);
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

if (isset($_GET['saved'])) {
    $message = '비밀 메모가 저장되었습니다.';
} elseif (isset($_GET['deleted'])) {
    $message = '비밀 메모가 삭제되었습니다.';
}

$keyword = trim($_GET['keyword'] ?? '');
$important = ($_GET['important'] ?? '') === '1';

$where = ["use_tf = 'Y'", "del_tf = 'N'"];
$params = [];
if ($keyword !== '') {
    $where[] = '(title LIKE :keyword OR note_body LIKE :keyword OR tags LIKE :keyword)';
    $params[':keyword'] = '%' . $keyword . '%';
}
if ($important) {
    $where[] = 'is_important = 1';
}
$whereSql = 'WHERE ' . implode(' AND ', $where);

$stmt = $pdo->prepare("
    SELECT *
      FROM secret_note
      {$whereSql}
  ORDER BY is_important DESC, updated_at DESC, id DESC
     LIMIT 200
");
$stmt->execute($params);
$notes = $stmt->fetchAll();

$editNote = null;
if (!empty($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM secret_note WHERE id = :id AND del_tf = 'N'");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $editNote = $stmt->fetch() ?: null;
}

include __DIR__ . '/../layout/header.php';
?>

<section class="secret-note">
  <div class="secret-head">
    <div>
      <h1>비밀 메모장</h1>
      <p>운영 힌트, 설정 위치, 개인 메모를 관리자 안에서 따로 보관합니다.</p>
    </div>
  </div>

  <?php if ($message): ?>
    <div class="secret-notice"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="secret-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="secret-layout">
    <form class="secret-panel secret-form" method="post">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($editNote['id'] ?? 0) ?>">
      <h2><?= $editNote ? '메모 수정' : '새 메모' ?></h2>

      <label>
        <span>제목</span>
        <input type="text" name="title" maxlength="255" value="<?= htmlspecialchars($editNote['title'] ?? '') ?>" required>
      </label>

      <label>
        <span>태그</span>
        <input type="text" name="tags" maxlength="255" value="<?= htmlspecialchars($editNote['tags'] ?? '') ?>" placeholder="예: 서버, DB, 배포">
      </label>

      <label class="secret-check">
        <input type="checkbox" name="is_important" value="1" <?= (int)($editNote['is_important'] ?? 0) === 1 ? 'checked' : '' ?>>
        <span>중요 메모</span>
      </label>

      <label>
        <span>내용</span>
        <textarea name="note_body" rows="12" required><?= htmlspecialchars($editNote['note_body'] ?? '') ?></textarea>
      </label>

      <p class="secret-help">비밀번호 원문보다는 보관 위치, 갱신 방법, 접근 절차 같은 힌트 중심으로 적는 편이 안전합니다.</p>

      <div class="secret-actions">
        <button type="submit"><?= $editNote ? '수정 저장' : '저장' ?></button>
        <?php if ($editNote): ?>
          <a href="secret_note.php">취소</a>
        <?php endif; ?>
      </div>
    </form>

    <section class="secret-list-wrap">
      <form class="admin-filter secret-filter" method="get">
        <div class="filter-row">
          <label class="filter-keyword">
            검색
            <input type="search" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="제목, 내용, 태그">
          </label>
          <label class="secret-inline-check">
            <input type="checkbox" name="important" value="1" <?= $important ? 'checked' : '' ?>>
            중요만
          </label>
          <button type="submit" class="btn filter">검색</button>
          <a class="btn reset" href="secret_note.php">초기화</a>
        </div>
      </form>

      <div class="secret-list">
        <?php if (!$notes): ?>
          <div class="secret-empty">등록된 메모가 없습니다.</div>
        <?php endif; ?>

        <?php foreach ($notes as $note): ?>
          <article class="secret-card <?= (int)$note['is_important'] === 1 ? 'is-important' : '' ?>">
            <div class="secret-card-head">
              <h2><?= htmlspecialchars($note['title']) ?></h2>
              <?php if ((int)$note['is_important'] === 1): ?>
                <span>중요</span>
              <?php endif; ?>
            </div>
            <?php if ($note['tags'] !== ''): ?>
              <div class="secret-tags"><?= htmlspecialchars($note['tags']) ?></div>
            <?php endif; ?>
            <pre><?= htmlspecialchars($note['note_body']) ?></pre>
            <div class="secret-meta">수정일 <?= htmlspecialchars($note['updated_at']) ?></div>
            <div class="secret-card-actions">
              <a class="btn edit" href="?edit_id=<?= (int)$note['id'] ?>">수정</a>
              <form method="post" onsubmit="return confirm('이 메모를 삭제하시겠습니까?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$note['id'] ?>">
                <button type="submit" class="btn delete">삭제</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
