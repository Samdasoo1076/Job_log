<?php
// admin/posts_form.php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/post.php';
require __DIR__ . '/../com/biz/folder.php';

$tree         = getFolders();
$filterFolder = intval($_GET['folder_id'] ?? 0);
$page         = max(1, intval($_GET['page'] ?? 1));

// POST 처리: savePost() 호출
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = intval($_POST['id'] ?? 0);
    $folderIdRaw   = $_POST['folder_id'] ?? '';
    $data = [
      'folder_id'   => $folderIdRaw === '' ? null : intval($folderIdRaw),
      'title'       => trim($_POST['title']),
      'description' => trim($_POST['description'] ?? ''),
      'content'     => trim($_POST['content']),
    ];

    if ($id) {
        updatePost($id, $data);
    } else {
        createPost($data);
    }

    header("Location: posts_list.php?folder_id={$filterFolder}&page={$page}");
    exit;
}

// 수정 모드: 기존 데이터 로드
$post = null;
if (!empty($_GET['id'])) {
    $post = getPost(intval($_GET['id']));
}

include __DIR__ . '/../layout/header.php';
?>

<h1><?= $post ? 'Edit Post' : 'New Post' ?></h1>
<form class="post-form" method="post" action="posts_form.php?folder_id=<?= $filterFolder ?>&page=<?= $page ?>"
      onsubmit="return submitContents(this);" novalidate>
  <input type="hidden" name="id" value="<?= $post['id'] ?? 0 ?>">

  <div class="form-group">
    <label>Folder:</label>
    <select name="folder_id">
      <option value="">-- 없음 (Top level) --</option>
      <?php
        function renderSelect(array $folders, string $prefix = '', $currentFolderId = null) {
          foreach ($folders as $f) {
            $sel = ($f['id'] === $currentFolderId) ? 'selected' : '';
            echo "<option value=\"{$f['id']}\" $sel>"
               . $prefix . htmlspecialchars($f['name'])
               . "</option>";
            if (!empty($f['children'])) {
              renderSelect($f['children'], $prefix . '-- ', $currentFolderId);
            }
          }
        }
        // 여기서 $post['folder_id'] 를 넘겨줘야 선택이 유지됩니다.
        renderSelect($tree, '', $post['folder_id'] ?? null);
      ?>
    </select>
  </div>

  <div class="form-group">
    <label>Title:</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
  </div>

  <div class="form-group">
    <label>Description:</label>
    <textarea name="description" rows="3"><?= htmlspecialchars($post['description'] ?? '') ?></textarea>
  </div>

  <div class="form-group">
    <label>Content:</label>
    <textarea name="content" id="editor" rows="10" required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn save">Save</button>
    <a href="posts_list.php?folder_id=<?= $filterFolder ?>&page=<?= $page ?>"
       class="btn cancel">Cancel</a>
  </div>
</form>

<script src="../../SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>
<script>
  var oEditors = [];
  nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
    elPlaceHolder: "editor",
    sSkinURI: "../../SE2-2.8.2.3/SmartEditor2_noframe.html",
    fCreator: "createSEditor2"
  });
  function submitContents(form) {
    oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);
    return true;
  }
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
