<?php
// admin/folder/folder_list.php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/Integration.php';

// JSON 요청 처리
if ($_SERVER['REQUEST_METHOD']==='POST') {
    header('Content-Type: application/json; charset=utf-8');
    // 1) 폴더 이동
    if (!empty($_POST['move_folder_id'])) {
        moveFolder(
            intval($_POST['move_folder_id']),
            ($_POST['parent_folder_id']!=='' ? intval($_POST['parent_folder_id']) : null),
            intval($_POST['folder_order'])
        );
        echo json_encode(['success'=>true]); exit;
    }
    // 2) 게시글 이동
    if (!empty($_POST['move_post_id'])) {
        movePost(
            intval($_POST['move_post_id']),
            ($_POST['target_folder_id']!=='' ? intval($_POST['target_folder_id']) : null),
            intval($_POST['post_order'])
        );
        echo json_encode(['success'=>true]); exit;
    }
    // 3) 폴더 인라인 수정
    if (!empty($_POST['edit_folder_id'])) {
        updateFolder(
            intval($_POST['edit_folder_id']),
            ['parent_id'=>null, 'name'=>trim($_POST['folder_name'])]
        );
        echo json_encode(['success'=>true]); exit;
    }
    // 4) 게시글 인라인 수정
    if (!empty($_POST['edit_post_id'])) {
        updatePost(
            intval($_POST['edit_post_id']),
            ['title'=>trim($_POST['post_title']), 'content'=>'', 'description'=>'','folder_id'=>null]
        );
        echo json_encode(['success'=>true]); exit;
    }
    // 5) 폴더 삭제
    if (!empty($_POST['delete_folder_id'])) {
        deleteFolder(intval($_POST['delete_folder_id']));
        echo json_encode(['success'=>true]); exit;
    }
    // 6) 게시글 삭제(soft)
    if (!empty($_POST['delete_post_id'])) {
        deletePost(intval($_POST['delete_post_id']));
        echo json_encode(['success'=>true]); exit;
    }
    // 7) 신규 폴더/게시글 생성 (폼 제출)
    if (!empty($_POST['new_folder_name'])) {
        createFolder([
            'parent_id'=>($_POST['new_folder_parent']!==''?intval($_POST['new_folder_parent']):null),
            'name'=>trim($_POST['new_folder_name'])
        ]);
        echo json_encode(['success'=>true]); exit;
    }
    if (!empty($_POST['new_post_title'])) {
        createPost([
            'folder_id'=>($_POST['new_post_parent']!==''?intval($_POST['new_post_parent']):null),
            'title'=>trim($_POST['new_post_title']), 'content'=>'', 'description'=>''
        ]);
        echo json_encode(['success'=>true]); exit;
    }
}

// 트리+게시글 데이터
$tree = getFolderPostTree();
include __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/admin/css/folder_post.css">

<main class="admin-container">
  <h1>폴더·게시글 통합 관리</h1>
  <div id="folder-list-container">
    <?php renderListWithPosts($tree); ?>
  </div>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
