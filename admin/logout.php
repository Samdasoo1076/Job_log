<?php
// admin/logout.php
require_once __DIR__ . '/common/config.php';  // session_start()

// 세션 정리 후 로그인 화면으로
session_unset();
session_destroy();
header('Location: login.php');
exit;
