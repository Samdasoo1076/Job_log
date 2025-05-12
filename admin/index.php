<?php
// admin/index.php
session_start();

// 이미 로그인된 상태라면 대시보드로
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

// 로그인되지 않은 상태라면 로그인 페이지로
header('Location: login.php');
exit;
