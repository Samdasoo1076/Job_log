<?php
// admin/logout.php
require_once __DIR__ . '/common/config.php';  // session_start()

// 세션 정리 후 로그인 화면으로
session_unset();
session_destroy();
setcookie(session_name(), '', [
    'expires' => time() - 3600,
    'path' => '/admin',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);

header('Location: login.php');
exit;
