<?php
// admin/layout/header.php
require_once __DIR__ . '/../common/common_inc.php'; // 세션 체크, DB
include_once __DIR__ . '/../common/front_head.php';
?>
  <aside class="sidebar">
    <h1><a href="/admin/dashboard.php"><?= SITE_TITLE ?></a></h1>
    <nav>
      <ul>
        <li><a href="/admin/dashboard.php">대시보드</a></li>
        <li><a href="/admin/folder/folder_list.php">폴더 관리</a></li>
        <li><a href="/admin/post/posts_list.php">게시글 관리</a></li>
        <li><a href="/admin/comment/comment_list.php">댓글 관리</a></li>
        <li><a href="/admin/logout.php">로그아웃</a></li>
      </ul>
    </nav>
  </aside>
  <main class="admin-container">
