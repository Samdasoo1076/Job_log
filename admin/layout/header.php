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
        <li><a href="/admin/content/content_manage.php">콘텐츠 관리</a></li>
        <li><a href="/admin/folder/folder_list2.php">폴더 관리</a></li>
        <li><a href="/admin/post/posts_list.php">게시글 관리</a></li>
        <li><a href="/admin/guide/html_guide.php">본문 HTML 가이드</a></li>
        <li><a href="/admin/comment/comment_list.php">댓글 관리</a></li>
        <li><a href="/admin/activity/activity_log.php">접속 로그</a></li>
        <li><a href="/admin/Integration/list.php">폴더 & 게시글 관리</a></li>
        <li><a href="/admin/popular/popular.php">인기 게시글</a></li>
        <li><a href="/admin/accounting/account_book.php">가계부</a></li>
        <li><a href="/admin/secret/secret_note.php">비밀 메모장</a></li>
        <li><a href="/admin/logout.php">로그아웃</a></li>
      </ul>
    </nav>
  </aside>
  <main class="admin-container">
