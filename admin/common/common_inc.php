<?php
// admin/common/common_inc.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../db.php';

// 로그인 체크 (login.php, logout.php 제외)
$self = basename($_SERVER['PHP_SELF']);
if (!in_array($self, ['login.php', 'logout.php'])) {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

