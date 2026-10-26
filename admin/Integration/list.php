<?php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/Integration.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'create_folder':
                $name = trim($_POST['name'] ?? '');
                if ($name === '') {
                    throw new InvalidArgumentException('폴더명을 입력해 주세요.');
                }
                $id = integratedCreateFolder(
                    ($_POST['parent_id'] ?? '') === '' ? null : (int)$_POST['parent_id'],
                    $name
                );
                echo json_encode(['success' => true, 'id' => $id]);
                exit;

            case 'rename_folder':
                $name = trim($_POST['name'] ?? '');
                if ($name === '') {
                    throw new InvalidArgumentException('폴더명을 입력해 주세요.');
                }
                integratedRenameFolder((int)$_POST['id'], $name);
                echo json_encode(['success' => true]);
                exit;

            case 'delete_folder':
                integratedSoftDeleteFolder((int)$_POST['id']);
                echo json_encode(['success' => true]);
                exit;

            case 'restore_folder':
                integratedRestoreFolder((int)$_POST['id']);
                echo json_encode(['success' => true]);
                exit;

            case 'move_folder':
                integratedMoveFolder(
                    (int)$_POST['id'],
                    ($_POST['parent_id'] ?? '') === '' ? null : (int)$_POST['parent_id'],
                    (int)($_POST['sort_order'] ?? 0)
                );
                echo json_encode(['success' => true]);
                exit;

            case 'create_post':
                $title = trim($_POST['title'] ?? '');
                if ($title === '') {
                    throw new InvalidArgumentException('게시글 제목을 입력해 주세요.');
                }
                $id = integratedCreatePost(
                    ($_POST['folder_id'] ?? '') === '' ? null : (int)$_POST['folder_id'],
                    $title
                );
                echo json_encode(['success' => true, 'id' => $id]);
                exit;

            case 'rename_post':
                $title = trim($_POST['title'] ?? '');
                if ($title === '') {
                    throw new InvalidArgumentException('게시글 제목을 입력해 주세요.');
                }
                integratedRenamePost((int)$_POST['id'], $title);
                echo json_encode(['success' => true]);
                exit;

            case 'delete_post':
                integratedSoftDeletePost((int)$_POST['id']);
                echo json_encode(['success' => true]);
                exit;

            case 'restore_post':
                integratedRestorePost((int)$_POST['id']);
                echo json_encode(['success' => true]);
                exit;

            case 'move_post':
                integratedMovePost(
                    (int)$_POST['id'],
                    ($_POST['folder_id'] ?? '') === '' ? null : (int)$_POST['folder_id'],
                    (int)($_POST['sort_order'] ?? 0)
                );
                echo json_encode(['success' => true]);
                exit;
        }

        throw new InvalidArgumentException('알 수 없는 요청입니다.');
    } catch (Throwable $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
        exit;
    }
}

$folders = getIntegratedManagerTree();
$rootPosts = getIntegratedPostsByFolder(null);

include __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/admin/css/Integration.css">

<div class="integration-manager">
  <div class="integration-header">
    <div>
      <h1>폴더 & 게시글 관리</h1>
      <p>폴더와 게시글을 한 화면에서 생성, 이동, 수정, 삭제합니다.</p>
    </div>
    <div class="integration-actions">
      <button type="button" data-action="add-root-folder">최상위 폴더 추가</button>
      <button type="button" data-action="add-root-post">최상위 글 추가</button>
    </div>
  </div>

  <div class="integration-guide">
    <span>드래그로 폴더/게시글 위치 변경</span>
    <span>게시글의 본문은 “본문 편집”에서 수정</span>
    <span>삭제는 소프트 삭제이며 복구 가능</span>
  </div>

  <div class="integration-tree-wrap">
    <?php renderIntegratedManager($folders, $rootPosts); ?>
  </div>
</div>

<script src="/admin/js/Integration.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
