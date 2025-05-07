<?php
// admin/folders.php
require_once __DIR__ . '/common/common_inc.php';

// 생성 또는 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = $_POST['id'] ?? '';
    $name      = trim($_POST['name']);
    $parent_id = $_POST['parent_id'] ?: null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE folder SET name = ?, parent_id = ? WHERE id = ?");
        $stmt->execute([$name, $parent_id, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO folder (name, parent_id) VALUES (?, ?)");
        $stmt->execute([$name, $parent_id]);
    }
    header('Location: folders.php');
    exit;
}

// 삭제 처리
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM folder WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: folders.php');
    exit;
}

// 데이터 조회
$folders = $pdo->query("SELECT * FROM folder ORDER BY parent_id, id")->fetchAll(PDO::FETCH_ASSOC);

// HTML 출력
include __DIR__ . '/common/front_head.php';
?>
<h1>폴더 관리</h1>

<!-- 폴더 추가/수정 폼 -->
<?php
$edit = isset($_GET['edit']);
$folder = ['id'=>'','name'=>'','parent_id'=>null];
if ($edit) {
    foreach ($folders as $f) {
        if ($f['id'] == $_GET['edit']) {
            $folder = $f;
            break;
        }
    }
}
?>
<form method="post">
    <input type="hidden" name="id" value="<?=htmlspecialchars($folder['id'])?>">
    <label>이름:
      <input type="text" name="name" value="<?=htmlspecialchars($folder['name'])?>" required>
    </label>
    <label>상위폴더:
      <select name="parent_id">
        <option value="">없음</option>
        <?php foreach ($folders as $f): ?>
          <?php if ($f['id'] == $folder['id']) continue; ?>
          <option value="<?=$f['id']?>" <?=($f['id']==$folder['parent_id'])?'selected':''?>>
            <?=htmlspecialchars($f['name'])?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <button type="submit"><?= $edit ? '수정' : '추가' ?></button>
</form>

<!-- 폴더 목록 렌더링 -->
<h2>폴더 목록</h2>
<?php
function renderTree($items, $parent = null, $depth = 0) {
    echo '<ul>';
    foreach ($items as $item) {
        if ($item['parent_id'] == $parent) {
            echo '<li>' . str_repeat('&nbsp;&nbsp;', $depth) . htmlspecialchars($item['name']);
            echo ' <a href="?edit=' . $item['id'] . '">[수정]</a>';
            echo ' <a href="?delete=' . $item['id'] . '" onclick="return confirm(' . "'삭제할까요?'" . ')">[삭제]</a>';
            renderTree($items, $item['id'], $depth + 1);
            echo '</li>';
        }
    }
    echo '</ul>';
}
renderTree($folders);
?>

<?php include __DIR__ . '/common/footer.php'; ?>