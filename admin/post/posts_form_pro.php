<?php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/folder.php';

$pdo->exec("
    CREATE TABLE IF NOT EXISTS post_draft (
        id INT(11) NOT NULL AUTO_INCREMENT,
        draft_key VARCHAR(64) NOT NULL,
        post_id INT(11) DEFAULT NULL,
        admin_id VARCHAR(64) DEFAULT NULL,
        folder_id INT(11) DEFAULT NULL,
        title VARCHAR(255) DEFAULT '',
        description VARCHAR(255) DEFAULT '',
        content LONGTEXT DEFAULT NULL,
        is_featured TINYINT(1) NOT NULL DEFAULT 0,
        allow_comment CHAR(1) NOT NULL DEFAULT 'Y',
        use_tf CHAR(1) NOT NULL DEFAULT 'Y',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_post_draft_key (draft_key),
        KEY idx_post_draft_post_id (post_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

function proJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function proDraftPayload(): array
{
    $folderId = $_POST['folder_id'] ?? '';
    return [
        ':draft_key' => trim($_POST['draft_key'] ?? ''),
        ':post_id' => (int)($_POST['post_id'] ?? 0) ?: null,
        ':admin_id' => (string)($_SESSION['admin_id'] ?? ''),
        ':folder_id' => $folderId === '' ? null : (int)$folderId,
        ':title' => trim($_POST['title'] ?? ''),
        ':description' => trim($_POST['description'] ?? ''),
        ':content' => trim($_POST['content'] ?? ''),
        ':is_featured' => (int)($_POST['is_featured'] ?? 0),
        ':allow_comment' => ($_POST['allow_comment'] ?? 'N') === 'Y' ? 'Y' : 'N',
        ':use_tf' => ($_POST['use_tf'] ?? 'N') === 'Y' ? 'Y' : 'N',
    ];
}

function proGetDraft(string $draftKey): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM post_draft WHERE draft_key = :draft_key");
    $stmt->execute([':draft_key' => $draftKey]);
    $draft = $stmt->fetch();
    return $draft ?: null;
}

function proRenderFolderOptions(array $folders, string $prefix = '', ?int $currentId = null): void
{
    foreach ($folders as $folder) {
        $id = (int)$folder['id'];
        $selected = $id === $currentId ? 'selected' : '';
        echo '<option value="' . $id . '" ' . $selected . '>' . htmlspecialchars($prefix . $folder['name']) . '</option>';
        if (!empty($folder['children'])) {
            proRenderFolderOptions($folder['children'], $prefix . '-- ', $currentId);
        }
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'save_draft') {
    $payload = proDraftPayload();
    if ($payload[':draft_key'] === '') {
        proJson(['success' => false, 'message' => 'draft_key가 없습니다.'], 400);
    }

    $stmt = $pdo->prepare("
        INSERT INTO post_draft
            (draft_key, post_id, admin_id, folder_id, title, description, content,
             is_featured, allow_comment, use_tf)
        VALUES
            (:draft_key, :post_id, :admin_id, :folder_id, :title, :description, :content,
             :is_featured, :allow_comment, :use_tf)
        ON DUPLICATE KEY UPDATE
            post_id = VALUES(post_id),
            admin_id = VALUES(admin_id),
            folder_id = VALUES(folder_id),
            title = VALUES(title),
            description = VALUES(description),
            content = VALUES(content),
            is_featured = VALUES(is_featured),
            allow_comment = VALUES(allow_comment),
            use_tf = VALUES(use_tf),
            updated_at = NOW()
    ");
    $stmt->execute($payload);
    proJson(['success' => true, 'saved_at' => date('Y-m-d H:i:s')]);
}

$tree = getFolders();
$postId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$draftKey = trim($_GET['draft_key'] ?? $_POST['draft_key'] ?? '');
if ($draftKey === '') {
    $draftKey = $postId ? 'post-' . $postId . '-admin-' . ($_SESSION['admin_id'] ?? '0') : bin2hex(random_bytes(16));
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'publish') {
    $folderIdRaw = $_POST['folder_id'] ?? '';
    $data = [
        ':folder_id' => $folderIdRaw === '' ? null : (int)$folderIdRaw,
        ':title' => trim($_POST['title'] ?? ''),
        ':description' => trim($_POST['description'] ?? ''),
        ':content' => trim($_POST['content'] ?? ''),
        ':is_featured' => (int)($_POST['is_featured'] ?? 0),
        ':allow_comment' => ($_POST['allow_comment'] ?? 'N') === 'Y' ? 'Y' : 'N',
        ':use_tf' => ($_POST['use_tf'] ?? 'N') === 'Y' ? 'Y' : 'N',
    ];

    if ($postId > 0) {
        $stmt = $pdo->prepare("
            UPDATE post
               SET folder_id = :folder_id,
                   title = :title,
                   description = :description,
                   content = :content,
                   is_featured = :is_featured,
                   allow_comment = :allow_comment,
                   use_tf = :use_tf,
                   del_tf = 'N',
                   updated_at = NOW()
             WHERE id = :id
        ");
        $data[':id'] = $postId;
        $stmt->execute($data);
    } else {
        $orderStmt = $data[':folder_id'] === null
            ? $pdo->query("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM post WHERE folder_id IS NULL")
            : $pdo->prepare("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM post WHERE folder_id = :folder_id");
        if ($data[':folder_id'] !== null) {
            $orderStmt->execute([':folder_id' => $data[':folder_id']]);
        }
        $sortOrder = (int)$orderStmt->fetchColumn();

        $stmt = $pdo->prepare("
            INSERT INTO post
                (folder_id, sort_order, title, description, content, is_featured, allow_comment, use_tf, del_tf)
            VALUES
                (:folder_id, :sort_order, :title, :description, :content, :is_featured, :allow_comment, :use_tf, 'N')
        ");
        $data[':sort_order'] = $sortOrder;
        $stmt->execute($data);
        $postId = (int)$pdo->lastInsertId();
    }

    $deleteDraft = $pdo->prepare("DELETE FROM post_draft WHERE draft_key = :draft_key");
    $deleteDraft->execute([':draft_key' => $draftKey]);

    header('Location: posts_form_pro.php?id=' . $postId . '&saved=1');
    exit;
}

$post = null;
if ($postId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM post WHERE id = :id");
    $stmt->execute([':id' => $postId]);
    $post = $stmt->fetch();
}

$draft = proGetDraft($draftKey);
$source = $draft ?: $post ?: [
    'id' => 0,
    'folder_id' => null,
    'title' => '',
    'description' => '',
    'content' => '',
    'is_featured' => 0,
    'allow_comment' => 'Y',
    'use_tf' => 'Y',
];

include __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/public/css/post.css?v=<?= filemtime(__DIR__ . '/../../public/css/post.css') ?>">
<link rel="stylesheet" href="/admin/css/post_pro.css?v=<?= filemtime(__DIR__ . '/../css/post_pro.css') ?>">

<form id="pro-post-form" class="pro-editor" method="post" action="posts_form_pro.php<?= $postId ? '?id=' . $postId : '' ?>" novalidate>
  <input type="hidden" name="action" value="publish">
  <input type="hidden" name="id" value="<?= $postId ?>">
  <input type="hidden" name="draft_key" value="<?= htmlspecialchars($draftKey) ?>">

  <div class="pro-editor-head">
    <div>
      <h1><?= $postId ? '게시글 고급 수정' : '게시글 고급 작성' ?></h1>
      <p><?= $draft ? '저장되지 않은 임시저장본을 불러왔습니다.' : '상태 관리, 미리보기, 자동 임시저장을 지원합니다.' ?></p>
    </div>
    <div class="pro-actions">
      <span id="autosave-state">자동저장 대기</span>
      <button type="button" id="btn-preview">미리보기</button>
      <button type="button" id="btn-save-draft">임시저장</button>
      <button type="submit" class="primary">게시글 저장</button>
      <a href="/admin/content/content_manage.php">콘텐츠 관리</a>
    </div>
  </div>

  <?php if (isset($_GET['saved'])): ?>
    <div class="save-notice">게시글이 저장되었습니다.</div>
  <?php endif; ?>

  <div class="pro-layout">
    <section class="pro-main">
      <label class="field">
        <span>제목</span>
        <input type="text" name="title" maxlength="255" value="<?= htmlspecialchars($source['title'] ?? '') ?>" required>
      </label>

      <label class="field">
        <span>설명</span>
        <textarea name="description" maxlength="255" rows="3"><?= htmlspecialchars($source['description'] ?? '') ?></textarea>
        <em id="description-count">0 / 255</em>
      </label>

      <label class="field">
        <span>본문</span>
        <textarea name="content" id="editor" rows="16" required><?= htmlspecialchars($source['content'] ?? '') ?></textarea>
      </label>

      <section class="ai-helper" aria-labelledby="ai-helper-title">
        <div class="ai-helper-head">
          <div>
            <h2 id="ai-helper-title">AI 글쓰기 도우미</h2>
            <p>오늘 겪은 일이나 배운 내용을 입력하면 현재 블로그 스타일의 HTML 초안을 만듭니다.</p>
          </div>
          <select id="ai-helper-mode">
            <option value="daily">오늘 배운 것</option>
            <option value="cv">소개글/CV</option>
            <option value="project">프로젝트 경험</option>
            <option value="improve">본문 다듬기</option>
          </select>
        </div>
        <label class="field">
          <span>겪은 일 / 배운 것 / 넣고 싶은 내용</span>
          <textarea id="ai-helper-notes" rows="5" placeholder="예: 오늘 그룹웨어 권한 예외 케이스를 정리했고, 조건을 쪼개 테스트하면서 정책 이해가 먼저라는 걸 배웠다."></textarea>
        </label>
        <label class="field">
          <span>원하는 스타일</span>
          <input type="text" id="ai-helper-style" value="담백하게, 개발자로서 배운 점 중심으로, 과장 없이">
        </label>
        <div class="ai-helper-actions">
          <button type="button" id="btn-ai-generate">초안 만들기</button>
          <button type="button" id="btn-codex-generate">CLI로 생성 요청</button>
          <button type="button" id="btn-ai-insert" disabled>에디터에 삽입</button>
          <button type="button" id="btn-ai-replace" disabled>본문 교체</button>
          <span id="ai-helper-state">대기</span>
        </div>
        <p id="codex-helper-state" class="codex-helper-state" hidden></p>
        <div id="ai-helper-result" class="ai-helper-result" hidden></div>
      </section>
    </section>

    <aside class="pro-side">
      <div class="side-panel">
        <h2>게시 상태</h2>
        <label class="field">
          <span>폴더</span>
          <select name="folder_id">
            <option value="">최상위</option>
            <?php proRenderFolderOptions($tree, '', $source['folder_id'] !== null ? (int)$source['folder_id'] : null); ?>
          </select>
        </label>
        <label class="switch-line">
          <input type="checkbox" name="use_tf" value="Y" <?= ($source['use_tf'] ?? 'Y') === 'Y' ? 'checked' : '' ?>>
          <span>공개</span>
        </label>
        <label class="switch-line">
          <input type="checkbox" name="is_featured" value="1" <?= (int)($source['is_featured'] ?? 0) === 1 ? 'checked' : '' ?>>
          <span>대표글</span>
        </label>
        <label class="switch-line">
          <input type="checkbox" name="allow_comment" value="Y" <?= ($source['allow_comment'] ?? 'Y') === 'Y' ? 'checked' : '' ?>>
          <span>댓글 허용</span>
        </label>
      </div>

      <div class="side-panel">
        <h2>저장 체크</h2>
        <ul id="publish-checklist">
          <li data-check="title">제목 입력</li>
          <li data-check="description">설명 입력</li>
          <li data-check="content">본문 입력</li>
          <li data-check="status">공개/댓글 상태 확인</li>
        </ul>
      </div>
    </aside>
  </div>
</form>

<dialog id="preview-dialog" class="preview-dialog">
  <div class="preview-head">
    <strong>미리보기</strong>
    <button type="button" id="btn-close-preview">닫기</button>
  </div>
  <article id="preview-content" class="preview-content"></article>
</dialog>

<script src="/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" defer></script>
<script src="/public/js/code_blocks.js?v=<?= filemtime(__DIR__ . '/../../public/js/code_blocks.js') ?>" defer></script>
<script>
window.proPostEditorConfig = {
  editorSkin: "/SE2-2.8.2.3/SmartEditor2_noframe.html",
  postId: <?= (int)$postId ?>,
  draftSavedAt: <?= json_encode($draft['updated_at'] ?? null) ?>
};
</script>
<script src="/admin/js/post_pro.js?v=<?= filemtime(__DIR__ . '/../js/post_pro.js') ?>"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
