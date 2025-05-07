<?php
// admin/layout/header.php
require_once __DIR__ . '/../common/config.php';
require_once __DIR__ . '/../common/common_inc.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= SITE_TITLE ?></title>
  <link rel="stylesheet" href="/admin/css/header.css">
  <link rel="stylesheet" href="/admin/css/main.css">
  <link rel="stylesheet" href="/admin/css/footer.css">
</head>
<body>
  <aside class="sidebar">
    <h1><a href="/admin/dashboard.php"><?= SITE_TITLE ?></a></h1>
    <nav>
      <ul>
        <li><a href="/admin/dashboard.php">대시보드</a></li>
        <li><a href="/admin/folders.php">폴더 관리</a></li>
        <li><a href="/admin/posts.php">게시글 관리</a></li>
        <li><a href="/admin/logout.php">로그아웃</a></li>
      </ul>
    </nav>
  </aside>
  <main class="admin-container">
