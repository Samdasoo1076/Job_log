<?php
// admin/common/config.php
// 세션 시작 및 전역 설정
if (PHP_SAPI !== 'cli') header('X-Robots-Tag: noindex, nofollow, noarchive');
$adminSessionLifetime = 10 * 365 * 24 * 60 * 60;
// Isolate admin sessions from PHP's shared session cleanup policy.
$adminSessionPath = sys_get_temp_dir() . '/cms-admin-sessions-' . substr(hash('sha256', __DIR__), 0, 16);
if (!is_dir($adminSessionPath) && !mkdir($adminSessionPath, 0700, true) && !is_dir($adminSessionPath)) {
    throw new RuntimeException('관리자 세션 저장 경로를 생성하지 못했습니다.');
}
ini_set('session.gc_maxlifetime', (string)$adminSessionLifetime);
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '100');
ini_set('session.use_strict_mode', '1');
ini_set('session.lazy_write', '0');
session_save_path($adminSessionPath);
session_name('CMS_ADMIN_SESSION');
session_set_cookie_params([
    'lifetime' => $adminSessionLifetime,
    'path' => '/admin',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();
setcookie(session_name(), session_id(), [
    'expires' => time() + $adminSessionLifetime,
    'path' => '/admin',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
define('SITE_TITLE', 'CMS Blog Admin');
