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

foreach (['account_id', 'account_password'] as $column) {
    $check = $pdo->query("SHOW COLUMNS FROM secret_note LIKE '$column'");
    if (!$check->fetch()) {
        $pdo->exec("ALTER TABLE secret_note ADD COLUMN $column TEXT NULL");
    }
}
$pdo->exec("CREATE TABLE IF NOT EXISTS secret_note_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id INT NOT NULL,
    previous_id TEXT NULL,
    previous_password TEXT NULL,
    changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_note_history (note_id, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
header('Cache-Control: no-store');
if (empty($_SESSION['secret_csrf'])) {
    $_SESSION['secret_csrf'] = bin2hex(random_bytes(32));
}
if (isset($_GET['history_id'])) {
    $stmt = $pdo->prepare("SELECT h.previous_id, h.previous_password, h.changed_at FROM secret_note_history h JOIN secret_note n ON n.id=h.note_id WHERE n.del_tf='N' AND h.note_id=? ORDER BY h.id DESC");
    $stmt->execute([(int)$_GET['history_id']]);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
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
    $tag = ($input['tag_type'] ?? '일반') === 'custom' ? secretText($input['custom_tag'] ?? '') : (string)$input['tag_type'];
    if (!in_array($input['tag_type'] ?? '', ['계정', '일반', 'custom'], true) || $tag === '' || mb_strlen($tag) > 255) {
        throw new InvalidArgumentException('태그를 확인해 주세요.');
    }
    $accountId = $tag === '계정' ? (string)($input['account_id'] ?? '') : '';
    $accountPassword = $tag === '계정' ? (string)($input['account_password'] ?? '') : '';

    if ($title === '') {
        throw new InvalidArgumentException('제목을 입력해 주세요.');
    }
    if ($body === '' && $tag !== '계정') {
        throw new InvalidArgumentException('내용을 입력해 주세요.');
    }

    $params = [
        ':title' => $title,
        ':note_body' => $body,
        ':tags' => $tag,
        ':account_id' => $accountId,
        ':account_password' => $accountPassword,
        ':is_important' => !empty($input['is_important']) ? 1 : 0,
    ];

    if ($id > 0) {
        $pdo->beginTransaction();
        try {
        $oldStmt = $pdo->prepare("SELECT * FROM secret_note WHERE id=? AND del_tf='N' FOR UPDATE");
        $oldStmt->execute([$id]);
        $old = $oldStmt->fetch();
        if (!$old) throw new InvalidArgumentException('메모를 찾을 수 없습니다.');
        if ($old['tags'] === '계정' && ((string)$old['account_id'] !== $accountId || (string)$old['account_password'] !== $accountPassword)) {
            $history = $pdo->prepare('INSERT INTO secret_note_history (note_id, previous_id, previous_password) VALUES (?, ?, ?)');
            $history->execute([$id, $old['account_id'], $old['account_password']]);
        }
        $stmt = $pdo->prepare("
            UPDATE secret_note
               SET title = :title,
                   note_body = :note_body,
                   tags = :tags,
                   account_id = :account_id,
                   account_password = :account_password,
                   is_important = :is_important,
                   updated_at = NOW()
             WHERE id = :id
        ");
        $params[':id'] = $id;
        $stmt->execute($params);
        $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO secret_note (title, note_body, tags, is_important, account_id, account_password)
        VALUES (:title, :note_body, :tags, :is_important, :account_id, :account_password)
    ");
    $stmt->execute($params);
}

$message = '';
$error = '';

try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        if (!hash_equals($_SESSION['secret_csrf'], (string)($_POST['csrf'] ?? ''))) {
            throw new InvalidArgumentException('페이지를 새로고침한 뒤 다시 시도해 주세요.');
        }
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
$tagFilter = in_array($_GET['tag'] ?? '', ['계정', '일반'], true) ? $_GET['tag'] : '';

$where = ["use_tf = 'Y'", "del_tf = 'N'"];
$params = [];
if ($tagFilter !== '') {
    $where[] = 'tags = :tag';
    $params[':tag'] = $tagFilter;
}
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
if ($error && ($_POST['action'] ?? '') === 'save') {
    $editNote = $_POST;
    $editNote['tags'] = ($_POST['tag_type'] ?? '') === 'custom' ? ($_POST['custom_tag'] ?? '') : ($_POST['tag_type'] ?? '일반');
}

include __DIR__ . '/../layout/header.php';
?>

<section class="secret-note">
  <div class="secret-head">
    <div>
      <h1>비밀 메모장</h1>
    </div>
    <button type="button" class="btn" id="secret-create">+ 비밀 메모 등록</button>
  </div>

  <?php if ($message): ?>
    <div class="secret-notice"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="secret-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="secret-layout">
    <dialog id="secret-editor" class="secret-dialog" data-open="<?= ($editNote || $error) ? '1' : '0' ?>">
    <form class="secret-form" method="post">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['secret_csrf']) ?>">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($editNote['id'] ?? 0) ?>">
      <h2><?= $editNote ? '메모 수정' : '새 메모' ?></h2>

      <label>
        <span>제목</span>
        <input type="text" name="title" maxlength="255" value="<?= htmlspecialchars($editNote['title'] ?? '') ?>" required>
      </label>

      <label>
        <span>태그</span>
        <?php $tag = $editNote['tags'] ?? '일반'; $type = in_array($tag, ['계정', '일반'], true) ? $tag : 'custom'; ?>
        <select name="tag_type" id="secret-tag">
          <option value="일반" <?= $type === '일반' ? 'selected' : '' ?>>일반</option>
          <option value="계정" <?= $type === '계정' ? 'selected' : '' ?>>계정</option>
          <option value="custom" <?= $type === 'custom' ? 'selected' : '' ?>>직접 입력</option>
        </select>
        <input id="secret-custom-tag" type="text" name="custom_tag" maxlength="255" value="<?= htmlspecialchars($type === 'custom' ? $tag : '') ?>" aria-label="직접 입력 태그" placeholder="태그 입력">
      </label>
      <div id="secret-account-fields">
        <label><span>ID</span><input type="text" name="account_id" autocomplete="off" value="<?= htmlspecialchars($editNote['account_id'] ?? '') ?>"></label>
        <label><span>비밀번호</span><input type="password" name="account_password" autocomplete="new-password" value="<?= htmlspecialchars($editNote['account_password'] ?? '') ?>"></label>
      </div>

      <label class="secret-check">
        <input type="checkbox" name="is_important" value="1" <?= (int)($editNote['is_important'] ?? 0) === 1 ? 'checked' : '' ?>>
        <span>중요 메모</span>
      </label>

      <label>
        <span>내용</span>
        <textarea name="note_body" rows="6"><?= htmlspecialchars($editNote['note_body'] ?? '') ?></textarea>
      </label>


      <div class="secret-actions">
        <button type="button" data-close-dialog>취소</button>
        <button type="submit"><?= $editNote ? '수정 저장' : '저장' ?></button>
        <?php if ($editNote): ?>
          <a href="secret_note.php">취소</a>
        <?php endif; ?>
      </div>
    </form>
    </dialog>

    <section class="secret-list-wrap">
      <form class="admin-filter secret-filter" method="get">
        <div class="filter-row">
          <label>
            태그
            <select name="tag" class="secret-tag-filter">
              <option value="">전체</option>
              <option value="계정" <?= $tagFilter === '계정' ? 'selected' : '' ?>>계정</option>
              <option value="일반" <?= $tagFilter === '일반' ? 'selected' : '' ?>>일반</option>
            </select>
          </label>
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
            <div class="secret-identity">
            <div class="secret-card-head">
              <h2><?= htmlspecialchars($note['title']) ?></h2>
              <?php if ((int)$note['is_important'] === 1): ?>
                <span>중요</span>
              <?php endif; ?>
            </div>
            <?php if ($note['tags'] !== ''): ?>
              <div class="secret-tags"><?= htmlspecialchars($note['tags']) ?></div>
            <?php endif; ?>
            </div>
            <div class="secret-details">
            <?php if ($note['tags'] === '계정'): ?>
              <dl class="secret-account-values">
                <dt>ID</dt>
                <dd><button type="button" class="secret-copy-value" data-copy="<?= htmlspecialchars($note['account_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" title="ID 복사"><?= htmlspecialchars($note['account_id'] ?? '', ENT_QUOTES, 'UTF-8') ?></button></dd>
                <dt>비밀번호</dt>
                <dd><button type="button" class="secret-copy-value" data-copy="<?= htmlspecialchars($note['account_password'] ?? '', ENT_QUOTES, 'UTF-8') ?>" title="비밀번호 복사"><?= htmlspecialchars($note['account_password'] ?? '', ENT_QUOTES, 'UTF-8') ?></button></dd>
              </dl>
            <?php endif; ?>
            <?php if (trim($note['note_body']) !== ''): ?>
              <pre><?= htmlspecialchars($note['note_body']) ?></pre>
            <?php endif; ?>
            </div>
            <div class="secret-card-actions">
              <time class="secret-meta" datetime="<?= htmlspecialchars(str_replace(' ', 'T', $note['updated_at'])) ?>" title="수정 시각"><?= htmlspecialchars(substr($note['updated_at'], 0, 16)) ?></time>
              <?php if ($note['tags'] === '계정'): ?>
                <button type="button" class="btn" title="변경 이력" aria-label="변경 이력" data-history="<?= (int)$note['id'] ?>"><i data-lucide="history"></i></button>
              <?php endif; ?>
              <a class="btn edit" title="수정" aria-label="수정" href="?edit_id=<?= (int)$note['id'] ?>"><i data-lucide="pencil"></i></a>
              <form method="post" onsubmit="return confirm('이 메모를 삭제하시겠습니까?');">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['secret_csrf']) ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$note['id'] ?>">
                <button type="submit" class="btn delete" title="삭제" aria-label="삭제"><i data-lucide="trash-2"></i></button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</section>
<dialog id="secret-history" class="secret-dialog">
  <div class="secret-history-head"><h2>계정 변경 이력</h2><button type="button" data-close-dialog>닫기</button></div>
  <div id="secret-history-content" aria-live="polite"></div>
</dialog>
<script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js" defer></script>
<script src="/admin/js/secret_note.js?v=<?= filemtime(__DIR__ . '/../js/secret_note.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
