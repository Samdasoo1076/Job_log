<?
session_start(); // 세션 시작

// 세션 변수 모두 삭제
session_unset();

// 세션 종료
session_destroy();

// 로그아웃 후 리다이렉트할 페이지로 이동 (예: 로그인 페이지)
echo "<script>history.back();</script>";
exit;
?>