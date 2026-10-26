<?php
require __DIR__ . '/../common/common_inc.php';

function contentJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function contentFolders(?int $parentId = null): array
{
    global $pdo;

    if ($parentId === null) {
        $stmt = $pdo->prepare("
            SELECT id, name, parent_id, sort_order, use_tf, del_tf
              FROM folder
             WHERE parent_id IS NULL
          ORDER BY sort_order ASC, name ASC
        ");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("
            SELECT id, name, parent_id, sort_order, use_tf, del_tf
              FROM folder
             WHERE parent_id = :parent_id
          ORDER BY sort_order ASC, name ASC
        ");
        $stmt->execute([':parent_id' => $parentId]);
    }

    $folders = $stmt->fetchAll();
    foreach ($folders as &$folder) {
        $folder['children'] = contentFolders((int)$folder['id']);
    }
    return $folders;
}

function contentRenderFolderTree(array $folders): void
{
    echo '<ul class="content-folder-list">';
    foreach ($folders as $folder) {
        $deleted = ($folder['del_tf'] ?? 'N') === 'Y';
        $hidden = !$deleted && ($folder['use_tf'] ?? 'Y') === 'N';
        $class = trim(($deleted ? 'is-deleted ' : '') . ($hidden ? 'is-hidden' : ''));
        $isVisible = ($folder['use_tf'] ?? 'Y') === 'Y';
        $visibilityClass = $isVisible ? ' is-visible' : ' is-hidden';
        $visibilityLabel = $isVisible ? '폴더 비공개로 변경' : '폴더 공개로 변경';
        $visibilityValue = ($folder['use_tf'] ?? 'Y') === 'Y' ? 'N' : 'Y';
        $hasChildren = !empty($folder['children']);
        echo '<li class="' . $class . '">';
        echo '<div class="folder-node-row">';
        echo '<button type="button" class="folder-expand-toggle' . ($hasChildren ? '' : ' is-empty') . '" aria-label="하위 폴더 열기"><span class="folder-caret"></span></button>';
        echo '<button type="button" class="folder-node" data-folder-id="' . (int)$folder['id'] . '">';
        echo '<span class="folder-name">' . htmlspecialchars($folder['name']) . '</span>';
        echo '<span class="folder-id">#' . (int)$folder['id'] . '</span>';
        echo '</button>';
        if (!$deleted) {
            echo '<button type="button" class="folder-visibility-toggle' . $visibilityClass . '" data-folder-id="' . (int)$folder['id'] . '" data-use-tf="' . $visibilityValue . '" title="' . $visibilityLabel . '" aria-label="' . $visibilityLabel . '"><span class="eye-icon" aria-hidden="true"></span></button>';
        }
        echo '</div>';
        if (!empty($folder['children'])) {
            contentRenderFolderTree($folder['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}

function contentPostWhere(array $filters, array &$params): string
{
    $where = ['1 = 1'];

    if (($filters['folder_id'] ?? '') !== '') {
        if ($filters['folder_id'] === 'root') {
            $where[] = 'p.folder_id IS NULL';
        } else {
            $where[] = 'p.folder_id = :folder_id';
            $params[':folder_id'] = (int)$filters['folder_id'];
        }
    }

    if (($filters['status'] ?? 'active') === 'active') {
        $where[] = "p.use_tf = 'Y'";
        $where[] = "p.del_tf = 'N'";
    } elseif ($filters['status'] === 'deleted') {
        $where[] = "(p.use_tf = 'N' OR p.del_tf = 'Y')";
    }

    if (($filters['featured'] ?? '') !== '') {
        $where[] = 'p.is_featured = :featured';
        $params[':featured'] = (int)$filters['featured'];
    }

    if (($filters['allow_comment'] ?? '') !== '') {
        $where[] = 'p.allow_comment = :allow_comment';
        $params[':allow_comment'] = $filters['allow_comment'];
    }

    if (($filters['keyword'] ?? '') !== '') {
        $where[] = '(p.title LIKE :keyword OR p.description LIKE :keyword)';
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }

    return 'WHERE ' . implode(' AND ', $where);
}

function contentFetchPosts(array $filters): array
{
    global $pdo;

    $params = [];
    $whereSql = contentPostWhere($filters, $params);
    $sortMap = [
        'created' => 'p.created_at DESC',
        'updated' => 'p.updated_at DESC',
        'views' => 'p.view_count DESC',
        'comments' => 'comment_count DESC',
        'title' => 'p.title ASC',
    ];
    $sort = $sortMap[$filters['sort'] ?? 'updated'] ?? $sortMap['updated'];
    $limit = min(100, max(10, (int)($filters['limit'] ?? 30)));
    $page = max(1, (int)($filters['page'] ?? 1));
    $offset = ($page - 1) * $limit;

    $stmt = $pdo->prepare("
        SELECT p.id, p.folder_id, p.title, p.description, p.use_tf, p.del_tf,
               p.is_featured, p.allow_comment, p.created_at, p.updated_at,
               p.view_count, f.name AS folder_name,
               COUNT(c.id) AS comment_count
          FROM post p
     LEFT JOIN folder f ON f.id = p.folder_id
     LEFT JOIN comment c ON c.post_id = p.id AND c.del_tf = 'N'
          {$whereSql}
      GROUP BY p.id, p.folder_id, p.title, p.description, p.use_tf, p.del_tf,
               p.is_featured, p.allow_comment, p.created_at, p.updated_at,
               p.view_count, f.name
      ORDER BY {$sort}, p.id DESC
         LIMIT :limit OFFSET :offset
    ");

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $countStmt = $pdo->prepare("
        SELECT COUNT(*) FROM (
            SELECT p.id
              FROM post p
         LEFT JOIN folder f ON f.id = p.folder_id
         LEFT JOIN comment c ON c.post_id = p.id AND c.del_tf = 'N'
              {$whereSql}
          GROUP BY p.id
        ) counted_posts
    ");
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $countStmt->execute();
    $total = (int)$countStmt->fetchColumn();

    return [
        'rows' => $stmt->fetchAll(),
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'total_pages' => max(1, (int)ceil($total / $limit)),
        ],
    ];
}

function contentFetchPostDetail(int $postId): array
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT p.id, p.folder_id, p.sort_order, p.use_tf, p.del_tf,
               p.is_featured, p.allow_comment, p.title, p.description,
               p.created_at, p.updated_at, p.view_count,
               f.name AS folder_name,
               COUNT(c.id) AS comment_count
          FROM post p
     LEFT JOIN folder f ON f.id = p.folder_id
     LEFT JOIN comment c ON c.post_id = p.id AND c.del_tf = 'N'
         WHERE p.id = :id
      GROUP BY p.id, p.folder_id, p.sort_order, p.use_tf, p.del_tf,
               p.is_featured, p.allow_comment, p.title, p.description,
               p.created_at, p.updated_at, p.view_count, f.name
    ");
    $stmt->execute([':id' => $postId]);
    $post = $stmt->fetch();
    if (!$post) {
        throw new RuntimeException('게시글을 찾을 수 없습니다.');
    }

    $commentStmt = $pdo->prepare("
        SELECT id, author, content, use_tf, del_tf, created_at, updated_at
          FROM comment
         WHERE post_id = :post_id
      ORDER BY created_at DESC
         LIMIT 30
    ");
    $commentStmt->execute([':post_id' => $postId]);

    $logStmt = $pdo->prepare("
        SELECT COUNT(*) AS views,
               COUNT(DISTINCT session_id) AS sessions,
               COUNT(DISTINCT ip_address) AS ips,
               MAX(reg_datetime) AS last_viewed_at
          FROM user_activity_log
         WHERE post_id = :post_id
           AND log_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ");
    $logStmt->execute([':post_id' => $postId]);

    return [
        'post' => $post,
        'comments' => $commentStmt->fetchAll(),
        'stats' => $logStmt->fetch() ?: [],
    ];
}

if (($_GET['ajax'] ?? '') === 'posts') {
    $result = contentFetchPosts($_GET);
    contentJson([
        'success' => true,
        'posts' => $result['rows'],
        'pagination' => $result['pagination'],
    ]);
}

if (($_GET['ajax'] ?? '') === 'detail') {
    try {
        contentJson(['success' => true, 'detail' => contentFetchPostDetail((int)($_GET['post_id'] ?? 0))]);
    } catch (Throwable $e) {
        contentJson(['success' => false, 'message' => $e->getMessage()], 404);
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        $postId = (int)($_POST['post_id'] ?? 0);

        if ($action === 'toggle_feature') {
            $stmt = $pdo->prepare("UPDATE post SET is_featured = :value WHERE id = :id");
            $stmt->execute([':value' => (int)$_POST['value'], ':id' => $postId]);
            contentJson(['success' => true]);
        }

        if ($action === 'toggle_comment') {
            $value = $_POST['value'] === 'Y' ? 'Y' : 'N';
            $stmt = $pdo->prepare("UPDATE post SET allow_comment = :value WHERE id = :id");
            $stmt->execute([':value' => $value, ':id' => $postId]);
            contentJson(['success' => true]);
        }

        if ($action === 'toggle_folder_visibility') {
            $value = ($_POST['use_tf'] ?? 'N') === 'Y' ? 'Y' : 'N';
            $folderId = (int)($_POST['folder_id'] ?? 0);
            if ($folderId <= 0) {
                throw new InvalidArgumentException('폴더 ID가 없습니다.');
            }
            $stmt = $pdo->prepare("UPDATE folder SET use_tf = :use_tf WHERE id = :id AND del_tf = 'N'");
            $stmt->execute([':use_tf' => $value, ':id' => $folderId]);
            contentJson(['success' => true]);
        }

        if ($action === 'move_posts') {
            $ids = array_filter(array_map('intval', explode(',', $_POST['post_ids'] ?? '')));
            $folderId = ($_POST['folder_id'] ?? '') === '' ? null : (int)$_POST['folder_id'];
            $stmt = $pdo->prepare("UPDATE post SET folder_id = :folder_id, updated_at = NOW() WHERE id = :id");
            foreach ($ids as $id) {
                $stmt->execute([':folder_id' => $folderId, ':id' => $id]);
            }
            contentJson(['success' => true]);
        }

        if ($action === 'delete_posts' || $action === 'restore_posts') {
            $ids = array_filter(array_map('intval', explode(',', $_POST['post_ids'] ?? '')));
            $use = $action === 'restore_posts' ? 'Y' : 'N';
            $del = $action === 'restore_posts' ? 'N' : 'Y';
            $stmt = $pdo->prepare("UPDATE post SET use_tf = :use_tf, del_tf = :del_tf, updated_at = NOW() WHERE id = :id");
            foreach ($ids as $id) {
                $stmt->execute([':use_tf' => $use, ':del_tf' => $del, ':id' => $id]);
            }
            contentJson(['success' => true]);
        }

        if ($action === 'update_comment_status') {
            $stmt = $pdo->prepare("UPDATE comment SET use_tf = :use_tf, del_tf = :del_tf, updated_at = NOW() WHERE id = :id");
            $stmt->execute([
                ':use_tf' => $_POST['use_tf'] === 'Y' ? 'Y' : 'N',
                ':del_tf' => $_POST['del_tf'] === 'Y' ? 'Y' : 'N',
                ':id' => (int)$_POST['comment_id'],
            ]);
            contentJson(['success' => true]);
        }

        throw new InvalidArgumentException('알 수 없는 요청입니다.');
    } catch (Throwable $e) {
        contentJson(['success' => false, 'message' => $e->getMessage()], 400);
    }
}

$folders = contentFolders();
$initialResult = contentFetchPosts(['status' => 'active', 'sort' => 'updated', 'limit' => 30, 'page' => 1]);
$initialPosts = $initialResult['rows'];
$initialPagination = $initialResult['pagination'];

include __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/admin/css/content_manage.css">

<div class="content-manager">
  <section class="content-sidebar">
    <div class="panel-head">
      <h2>폴더</h2>
      <button type="button" class="folder-node all active" data-folder-id="">전체</button>
      <button type="button" class="folder-node root" data-folder-id="root">최상위 글</button>
    </div>
    <?php contentRenderFolderTree($folders); ?>
  </section>

  <section class="content-posts">
    <div class="content-toolbar">
      <h1>콘텐츠 관리</h1>
      <div class="toolbar-actions">
        <a class="btn new" href="/admin/post/posts_form_pro.php">새 게시글</a>
        <button type="button" id="btn-move-selected">선택 이동</button>
        <button type="button" id="btn-delete-selected">선택 삭제</button>
        <button type="button" id="btn-restore-selected">선택 복구</button>
      </div>
    </div>

    <form id="post-filter" class="content-filter">
      <input type="hidden" name="folder_id" value="">
      <input type="hidden" name="page" value="1">
      <input type="search" name="keyword" placeholder="제목 또는 설명 검색">
      <select name="status">
        <option value="active">정상</option>
        <option value="deleted">삭제됨</option>
        <option value="">전체</option>
      </select>
      <select name="featured">
        <option value="">대표 전체</option>
        <option value="1">대표글</option>
        <option value="0">일반글</option>
      </select>
      <select name="allow_comment">
        <option value="">댓글 전체</option>
        <option value="Y">댓글 허용</option>
        <option value="N">댓글 차단</option>
      </select>
      <select name="sort">
        <option value="updated">수정일순</option>
        <option value="created">작성일순</option>
        <option value="views">조회순</option>
        <option value="comments">댓글순</option>
        <option value="title">제목순</option>
      </select>
      <select name="limit">
        <option value="30">30개</option>
        <option value="50">50개</option>
        <option value="100">100개</option>
      </select>
      <button type="submit">검색</button>
    </form>

    <div class="post-table-wrap">
      <table class="admin-table content-table">
        <thead>
          <tr>
            <th><input type="checkbox" id="check-all-posts"></th>
            <th>제목</th>
            <th>폴더</th>
            <th>상태</th>
            <th>조회</th>
            <th>댓글</th>
            <th>대표</th>
            <th>댓글허용</th>
            <th>수정일</th>
          </tr>
        </thead>
        <tbody id="post-list-body"></tbody>
      </table>
    </div>
    <div id="content-pagination" class="content-pagination"></div>
  </section>

  <aside class="content-detail" id="post-detail-panel">
    <div class="empty-detail">게시글을 선택하면 상세, 댓글, 최근 통계가 표시됩니다.</div>
  </aside>
</div>

<select id="folder-move-options" hidden>
  <option value="">최상위</option>
  <?php
  $renderOptions = function (array $nodes, string $prefix = '') use (&$renderOptions): void {
      foreach ($nodes as $node) {
          echo '<option value="' . (int)$node['id'] . '">' . htmlspecialchars($prefix . $node['name']) . '</option>';
          $renderOptions($node['children'] ?? [], $prefix . '-- ');
      }
  };
  $renderOptions($folders);
  ?>
</select>

<script>
window.initialContentPosts = <?= json_encode($initialPosts, JSON_UNESCAPED_UNICODE) ?>;
window.initialContentPagination = <?= json_encode($initialPagination, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="/admin/js/content_manage.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
