<?php
require __DIR__ . '/../common/common_inc.php';
function codeEscape($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$_SESSION['code_csrf'] ??= bin2hex(random_bytes(32));
$error = '';
$selected = (string)($_GET['pcode'] ?? '');
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!hash_equals($_SESSION['code_csrf'], (string)($_POST['csrf'] ?? ''))) throw new RuntimeException('페이지를 새로고침해 주세요.');
        $action = (string)($_POST['action'] ?? '');
        $parent = trim((string)($_POST['PCODE'] ?? ''));
        if ($parent === '' || mb_strlen($parent) > 50) throw new RuntimeException('그룹 코드를 확인해 주세요.');
        $selected = $parent;
        $admin = (int)$_SESSION['admin_id'];
        $pdo->beginTransaction();
        if ($action === 'parent_save') {
            $name = trim((string)($_POST['PCODE_NM'] ?? ''));
            if ($name === '' || mb_strlen($name) > 50) throw new RuntimeException('그룹명을 확인해 주세요.');
            $values = [$name, (string)($_POST['PCODE_MEMO'] ?? ''), (int)($_POST['PCODE_SEQ_NO'] ?? 0), isset($_POST['USE_TF']) ? 'Y' : 'N'];
            if (($_POST['editing'] ?? '') === '1') {
                $stmt = $pdo->prepare("UPDATE TBL_CODE_PARENT SET PCODE_NM=?, PCODE_MEMO=?, PCODE_SEQ_NO=?, USE_TF=?, UP_DATE=NOW(6) WHERE PCODE=? AND DEL_TF='N'");
                $stmt->execute([...$values, $parent]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO TBL_CODE_PARENT (PCODE_NM, PCODE_MEMO, PCODE_SEQ_NO, USE_TF, PCODE, REG_DATE) VALUES (?,?,?,?,?,NOW(6))");
                $stmt->execute([...$values, $parent]);
            }
        } elseif ($action === 'parent_delete') {
            $stmt = $pdo->prepare("UPDATE TBL_CODE_DETAIL SET DEL_TF='Y', USE_TF='N', DEL_DATE=NOW(), DEL_ADM=? WHERE PCODE=? AND DEL_TF='N'");
            $stmt->execute([$admin, $parent]);
            $stmt = $pdo->prepare("UPDATE TBL_CODE_PARENT SET DEL_TF='Y', USE_TF='N', DEL_DATE=NOW(6) WHERE PCODE=?");
            $stmt->execute([$parent]);
            $selected = '';
        } elseif (in_array($action, ['detail_save', 'detail_delete'], true)) {
            $stmt = $pdo->prepare("SELECT PCODE FROM TBL_CODE_PARENT WHERE PCODE=? AND DEL_TF='N' FOR UPDATE");
            $stmt->execute([$parent]);
            if (!$stmt->fetch()) throw new RuntimeException('그룹을 찾을 수 없습니다.');
            $id = (int)($_POST['DCODE_NO'] ?? 0);
            if ($action === 'detail_delete') {
                $stmt = $pdo->prepare("UPDATE TBL_CODE_DETAIL SET DEL_TF='Y', USE_TF='N', DEL_ADM=?, DEL_DATE=NOW() WHERE PCODE=? AND DCODE_NO=?");
                $stmt->execute([$admin, $parent, $id]);
            } else {
                $code = trim((string)($_POST['DCODE'] ?? ''));
                $name = trim((string)($_POST['DCODE_NM'] ?? ''));
                if ($parent === 'POST_BLOCK') {
                    if (!preg_match('/^dev-[a-z][a-z0-9-]{0,44}$/D', $code)) throw new RuntimeException('블록 클래스명은 dev-로 시작하는 영문 소문자·숫자·하이픈으로 입력해 주세요.');
                    foreach (['DCODE_EXT', 'DCODE_INFO01', 'DCODE_INFO02'] as $colorField) {
                        if (!preg_match('/^#[0-9a-fA-F]{6}$/D', (string)($_POST[$colorField] ?? ''))) throw new RuntimeException('색상은 #RRGGBB 형식으로 입력해 주세요.');
                    }
                }
                if ($code === '' || $name === '' || mb_strlen($code) > 50 || mb_strlen($name) > 50) throw new RuntimeException('상세 코드와 코드명을 확인해 주세요.');
                $values = [$code, $name, (string)($_POST['DCODE_EXT'] ?? ''), (int)($_POST['DCODE_SEQ_NO'] ?? 0), isset($_POST['USE_TF']) ? 'Y' : 'N'];
                foreach (['DCODE_INFO01', 'DCODE_INFO02', 'DCODE_INFO03'] as $field) {
                    $value = (string)($_POST[$field] ?? '');
                    if (mb_strlen($value) > 150) throw new RuntimeException('추가 정보는 150자까지 입력할 수 있습니다.');
                    $values[] = $value;
                }
                if (mb_strlen($values[2]) > 255) throw new RuntimeException('확장값은 255자까지 입력할 수 있습니다.');
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE TBL_CODE_DETAIL SET DCODE=?, DCODE_NM=?, DCODE_EXT=?, DCODE_SEQ_NO=?, USE_TF=?, DCODE_INFO01=?, DCODE_INFO02=?, DCODE_INFO03=?, UP_ADM=?, UP_DATE=NOW() WHERE PCODE=? AND DCODE_NO=? AND DEL_TF='N'");
                    $stmt->execute([...$values, $admin, $parent, $id]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO TBL_CODE_DETAIL (DCODE, DCODE_NM, DCODE_EXT, DCODE_SEQ_NO, USE_TF, DCODE_INFO01, DCODE_INFO02, DCODE_INFO03, REG_ADM, PCODE, REG_DATE) VALUES (?,?,?,?,?,?,?,?,?,?,NOW())");
                    $stmt->execute([...$values, $admin, $parent]);
                }
            }
        } else throw new RuntimeException('잘못된 요청입니다.');
        $pdo->commit();
        header('Location: code_manage.php?' . http_build_query(['pcode' => $selected, 'saved' => 1]));
        exit;
    }
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $error = $e instanceof PDOException ? '저장하지 못했습니다. 코드 중복 또는 입력값을 확인해 주세요.' : $e->getMessage();
}
$parents = $pdo->query("SELECT * FROM TBL_CODE_PARENT WHERE DEL_TF='N' ORDER BY PCODE_SEQ_NO, PCODE")->fetchAll();
$current = null;
foreach ($parents as $p) if ($p['PCODE'] === $selected) $current = $p;
if (!$current && $parents) { $current = $parents[0]; $selected = $current['PCODE']; }
$stmt = $pdo->prepare("SELECT * FROM TBL_CODE_DETAIL WHERE PCODE=? AND DEL_TF='N' ORDER BY DCODE_SEQ_NO, DCODE_NO");
$stmt->execute([$selected]);
$details = $stmt->fetchAll();
include __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/admin/css/code_manage.css?v=<?= filemtime(__DIR__ . '/../css/code_manage.css') ?>">
<section class="code-manager">
  <header><h1>공통 코드 관리</h1><button type="button" data-new-parent>+ 그룹 등록</button></header>
  <?php if ($error): ?><p role="alert" class="code-error"><?= codeEscape($error) ?></p><?php endif; ?>
  <?php if (isset($_GET['saved'])): ?><p role="status">처리되었습니다.</p><?php endif; ?>
  <div class="code-layout">
    <aside class="code-groups">
      <h2>코드 그룹 <small><?= count($parents) ?></small></h2>
      <?php foreach ($parents as $p): ?>
        <a class="<?= $p['PCODE'] === $selected ? 'selected' : '' ?>" href="?pcode=<?= urlencode($p['PCODE']) ?>">
          <strong><?= codeEscape($p['PCODE_NM']) ?></strong>
          <span><?= codeEscape($p['PCODE']) ?> · <?= $p['USE_TF'] === 'Y' ? '사용' : '미사용' ?></span>
        </a>
      <?php endforeach; ?>
      <?php if (!$parents): ?><p>등록된 그룹이 없습니다.</p><?php endif; ?>
    </aside>
    <section class="code-detail">
      <?php if ($current): ?>
      <header><div><h2><?= codeEscape($current['PCODE_NM']) ?></h2><small><?= codeEscape($selected) ?></small></div>
        <div class="code-actions"><button type="button" data-edit-parent>그룹 수정</button>
        <form method="post" data-delete><input type="hidden" name="csrf" value="<?= codeEscape($_SESSION['code_csrf']) ?>"><input type="hidden" name="PCODE" value="<?= codeEscape($selected) ?>"><input type="hidden" name="action" value="parent_delete"><button>그룹 삭제</button></form>
        <button type="button" data-new-detail>+ 상세 코드</button></div></header>
      <p class="code-memo"><?= nl2br(codeEscape($current['PCODE_MEMO'])) ?></p>
      <div class="code-table-wrap"><table><thead><tr><th>순번</th><th>코드</th><th>코드명</th><th>확장값</th><th>사용</th><th>관리</th></tr></thead><tbody>
      <?php foreach ($details as $d): ?>
        <tr><td><?= (int)$d['DCODE_SEQ_NO'] ?></td><td><?= codeEscape($d['DCODE']) ?></td><td><?= codeEscape($d['DCODE_NM']) ?></td><td><?= codeEscape($d['DCODE_EXT']) ?></td><td><?= $d['USE_TF'] === 'Y' ? '사용' : '미사용' ?></td><td><div class="code-actions">
          <button type="button" data-detail="<?= codeEscape(json_encode($d, JSON_UNESCAPED_UNICODE)) ?>">수정</button>
          <form method="post" data-delete><input type="hidden" name="csrf" value="<?= codeEscape($_SESSION['code_csrf']) ?>"><input type="hidden" name="PCODE" value="<?= codeEscape($selected) ?>"><input type="hidden" name="DCODE_NO" value="<?= (int)$d['DCODE_NO'] ?>"><input type="hidden" name="action" value="detail_delete"><button>삭제</button></form>
        </div></td></tr>
      <?php endforeach; ?>
      <?php if (!$details): ?><tr><td colspan="6">등록된 상세 코드가 없습니다.</td></tr><?php endif; ?>
      </tbody></table></div>
      <?php else: ?><p>코드 그룹을 등록해 주세요.</p><?php endif; ?>
    </section>
  </div>
</section>
<dialog id="code-parent-dialog"><form method="post">
  <h2>코드 그룹</h2><input type="hidden" name="csrf" value="<?= codeEscape($_SESSION['code_csrf']) ?>"><input type="hidden" name="action" value="parent_save"><input type="hidden" name="editing" value="0">
  <label>그룹 코드<input name="PCODE" maxlength="50" required></label>
  <label>그룹명<input name="PCODE_NM" maxlength="50" required></label>
  <label>설명<textarea name="PCODE_MEMO" rows="3"></textarea></label>
  <label>순번<input type="number" name="PCODE_SEQ_NO" value="0"></label>
  <label class="code-check"><input type="checkbox" name="USE_TF" checked>사용</label>
  <footer><button type="button" data-close>취소</button><button>저장</button></footer>
</form></dialog>
<dialog id="code-detail-dialog"><form method="post">
  <h2>상세 코드</h2><input type="hidden" name="csrf" value="<?= codeEscape($_SESSION['code_csrf']) ?>"><input type="hidden" name="action" value="detail_save"><input type="hidden" name="PCODE" value="<?= codeEscape($selected) ?>"><input type="hidden" name="DCODE_NO" value="0">
  <label>코드<input name="DCODE" maxlength="50" required></label>
  <label>코드명<input name="DCODE_NM" maxlength="50" required></label>
  <label><?= $selected === 'POST_BLOCK' ? '배경색' : '확장값' ?><input type="<?= $selected === 'POST_BLOCK' ? 'color' : 'text' ?>" name="DCODE_EXT" maxlength="255"></label>
  <label>순번<input type="number" name="DCODE_SEQ_NO" value="0"></label>
  <?php foreach (['01', '02', '03'] as $n): ?><label><?= $selected === 'POST_BLOCK' ? (['01' => '테두리색', '02' => '글자색', '03' => '추가 정보'][$n]) : '추가 정보 ' . $n ?><input type="<?= $selected === 'POST_BLOCK' && $n !== '03' ? 'color' : 'text' ?>" name="DCODE_INFO<?= $n ?>" maxlength="150"></label><?php endforeach; ?>
  <label class="code-check"><input type="checkbox" name="USE_TF" checked>사용</label>
  <footer><button type="button" data-close>취소</button><button>저장</button></footer>
</form></dialog>
<script type="application/json" id="code-current"><?= json_encode($current, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<script src="/admin/js/code_manage.js" defer></script>
<?php include __DIR__ . '/../layout/footer.php'; ?>
