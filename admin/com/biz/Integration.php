<?php
require_once __DIR__ . '/../../db.php';

function getIntegratedManagerTree(?int $parentId = null): array
{
    global $pdo;

    if ($parentId === null) {
        $stmt = $pdo->prepare("
            SELECT *
              FROM folder
             WHERE parent_id IS NULL
          ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("
            SELECT *
              FROM folder
             WHERE parent_id = :parent_id
          ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute([':parent_id' => $parentId]);
    }

    $folders = $stmt->fetchAll();
    foreach ($folders as &$folder) {
        $folder['children'] = getIntegratedManagerTree((int)$folder['id']);
        $folder['posts'] = getIntegratedPostsByFolder((int)$folder['id']);
    }

    return $folders;
}

function getIntegratedPostsByFolder(?int $folderId): array
{
    global $pdo;

    if ($folderId === null) {
        $stmt = $pdo->prepare("
            SELECT id, folder_id, sort_order, use_tf, del_tf, is_featured, allow_comment,
                   title, created_at, updated_at, view_count
              FROM post
             WHERE folder_id IS NULL
          ORDER BY sort_order ASC, created_at DESC, id DESC
        ");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("
            SELECT id, folder_id, sort_order, use_tf, del_tf, is_featured, allow_comment,
                   title, created_at, updated_at, view_count
              FROM post
             WHERE folder_id = :folder_id
          ORDER BY sort_order ASC, created_at DESC, id DESC
        ");
        $stmt->execute([':folder_id' => $folderId]);
    }

    return $stmt->fetchAll();
}

function integratedNextFolderOrder(?int $parentId): int
{
    global $pdo;

    if ($parentId === null) {
        $stmt = $pdo->query("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM folder WHERE parent_id IS NULL");
        return (int)$stmt->fetchColumn();
    }

    $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM folder WHERE parent_id = :parent_id");
    $stmt->execute([':parent_id' => $parentId]);
    return (int)$stmt->fetchColumn();
}

function integratedNextPostOrder(?int $folderId): int
{
    global $pdo;

    if ($folderId === null) {
        $stmt = $pdo->query("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM post WHERE folder_id IS NULL");
        return (int)$stmt->fetchColumn();
    }

    $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM post WHERE folder_id = :folder_id");
    $stmt->execute([':folder_id' => $folderId]);
    return (int)$stmt->fetchColumn();
}

function integratedCreateFolder(?int $parentId, string $name): int
{
    global $pdo;

    $stmt = $pdo->prepare("
        INSERT INTO folder (parent_id, name, sort_order, use_tf, del_tf)
        VALUES (:parent_id, :name, :sort_order, 'Y', 'N')
    ");
    $stmt->execute([
        ':parent_id' => $parentId,
        ':name' => $name,
        ':sort_order' => integratedNextFolderOrder($parentId),
    ]);

    return (int)$pdo->lastInsertId();
}

function integratedRenameFolder(int $id, string $name): void
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE folder SET name = :name WHERE id = :id");
    $stmt->execute([
        ':name' => $name,
        ':id' => $id,
    ]);
}

function integratedSoftDeleteFolder(int $id): void
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE folder SET use_tf = 'N', del_tf = 'Y' WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function integratedRestoreFolder(int $id): void
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE folder SET use_tf = 'Y', del_tf = 'N' WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function integratedFolderContains(int $folderId, int $possibleChildId): bool
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT parent_id FROM folder WHERE id = :id");
    $currentId = $possibleChildId;

    while ($currentId) {
        if ($currentId === $folderId) {
            return true;
        }

        $stmt->execute([':id' => $currentId]);
        $parentId = $stmt->fetchColumn();
        $currentId = $parentId === false || $parentId === null ? null : (int)$parentId;
    }

    return false;
}

function integratedMoveFolder(int $id, ?int $parentId, int $sortOrder): void
{
    global $pdo;

    if ($parentId !== null && ($parentId === $id || integratedFolderContains($id, $parentId))) {
        throw new RuntimeException('하위 폴더 안으로 자기 자신을 이동할 수 없습니다.');
    }

    $stmt = $pdo->prepare("
        UPDATE folder
           SET parent_id = :parent_id,
               sort_order = :sort_order
         WHERE id = :id
    ");
    $stmt->execute([
        ':parent_id' => $parentId,
        ':sort_order' => $sortOrder,
        ':id' => $id,
    ]);
}

function integratedCreatePost(?int $folderId, string $title): int
{
    global $pdo;

    $stmt = $pdo->prepare("
        INSERT INTO post (folder_id, sort_order, title, content, description, use_tf, del_tf)
        VALUES (:folder_id, :sort_order, :title, '', '', 'Y', 'N')
    ");
    $stmt->execute([
        ':folder_id' => $folderId,
        ':sort_order' => integratedNextPostOrder($folderId),
        ':title' => $title,
    ]);

    return (int)$pdo->lastInsertId();
}

function integratedRenamePost(int $id, string $title): void
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE post SET title = :title, updated_at = NOW() WHERE id = :id");
    $stmt->execute([
        ':title' => $title,
        ':id' => $id,
    ]);
}

function integratedSoftDeletePost(int $id): void
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE post SET use_tf = 'N', del_tf = 'Y', updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function integratedRestorePost(int $id): void
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE post SET use_tf = 'Y', del_tf = 'N', updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function integratedMovePost(int $id, ?int $folderId, int $sortOrder): void
{
    global $pdo;

    $stmt = $pdo->prepare("
        UPDATE post
           SET folder_id = :folder_id,
               sort_order = :sort_order,
               updated_at = NOW()
         WHERE id = :id
    ");
    $stmt->execute([
        ':folder_id' => $folderId,
        ':sort_order' => $sortOrder,
        ':id' => $id,
    ]);
}

function renderIntegratedManager(array $folders, array $rootPosts): void
{
    echo '<ul id="integration-tree" class="integration-list" data-folder-id="">';
    renderIntegratedFolderItems($folders);
    renderIntegratedPostItems($rootPosts);
    echo '</ul>';
}

function renderIntegratedFolderItems(array $folders): void
{
    foreach ($folders as $folder) {
        $id = (int)$folder['id'];
        $deleted = ($folder['use_tf'] ?? 'Y') === 'N' || ($folder['del_tf'] ?? 'N') === 'Y';
        $class = $deleted ? 'integration-item is-deleted' : 'integration-item';

        echo '<li class="' . $class . '" data-type="folder" data-id="' . $id . '">';
        echo '  <div class="integration-row folder-row">';
        echo '    <span class="drag-handle">☰</span>';
        echo '    <span class="item-kind">폴더</span>';
        echo '    <span class="item-title">' . htmlspecialchars($folder['name']) . '</span>';
        echo '    <span class="item-meta">ID ' . $id . '</span>';
        echo '    <button type="button" data-action="add-folder">하위 폴더</button>';
        echo '    <button type="button" data-action="add-post">글 추가</button>';
        echo '    <button type="button" data-action="rename-folder">이름 수정</button>';
        if ($deleted) {
            echo '    <button type="button" data-action="restore-folder">복구</button>';
        } else {
            echo '    <button type="button" class="danger" data-action="delete-folder">삭제</button>';
        }
        echo '  </div>';
        echo '  <ul class="integration-list" data-folder-id="' . $id . '">';
        renderIntegratedFolderItems($folder['children'] ?? []);
        renderIntegratedPostItems($folder['posts'] ?? []);
        echo '  </ul>';
        echo '</li>';
    }
}

function renderIntegratedPostItems(array $posts): void
{
    foreach ($posts as $post) {
        $id = (int)$post['id'];
        $deleted = ($post['use_tf'] ?? 'Y') === 'N' || ($post['del_tf'] ?? 'N') === 'Y';
        $class = $deleted ? 'integration-item is-deleted' : 'integration-item';

        echo '<li class="' . $class . '" data-type="post" data-id="' . $id . '">';
        echo '  <div class="integration-row post-row">';
        echo '    <span class="drag-handle">☰</span>';
        echo '    <span class="item-kind">게시글</span>';
        echo '    <span class="item-title">' . htmlspecialchars($post['title']) . '</span>';
        echo '    <span class="item-meta">ID ' . $id . ' · 조회 ' . number_format((int)$post['view_count']) . '</span>';
        echo '    <label class="toggle-label">대표 <input type="checkbox" class="feature-toggle" data-id="' . $id . '"' . ((int)$post['is_featured'] === 1 ? ' checked' : '') . '></label>';
        echo '    <label class="toggle-label">댓글 <input type="checkbox" class="comment-toggle" data-id="' . $id . '"' . (($post['allow_comment'] ?? 'Y') === 'Y' ? ' checked' : '') . '></label>';
        echo '    <a class="row-link" href="/admin/post/posts_form.php?id=' . $id . '">본문 편집</a>';
        echo '    <button type="button" data-action="rename-post">제목 수정</button>';
        if ($deleted) {
            echo '    <button type="button" data-action="restore-post">복구</button>';
        } else {
            echo '    <button type="button" class="danger" data-action="delete-post">삭제</button>';
        }
        echo '  </div>';
        echo '</li>';
    }
}
