<?php
// admin/login.php
require_once __DIR__ . '/common/common_inc.php';  // session_start(), DB($pdo) 준비

// POST 요청이면 로그인 시도
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 사용자 조회
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 비밀번호 검증 (DB에 평문으로 저장되어 있다면 == 으로, 해시된 경우엔 password_verify 로)
    if ($user && $user['password_hash'] === $password) {
        $_SESSION['admin_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = '아이디 또는 비밀번호가 올바르지 않습니다.';
    }
}

include __DIR__ . '/common/front_head.php';
?>
<h1>관리자 로그인</h1>
<?php if (!empty($error)): ?>
  <p class="error"><?=htmlspecialchars($error)?></p>
<?php endif; ?>
<form method="post" action="">
  <label>
    아이디<br>
    <input type="text" name="username" required>
  </label><br><br>
  <label>
    비밀번호<br>
    <input type="password" name="password" required>
  </label><br><br>
  <button type="submit">로그인</button>
</form>
</body>
</html>
