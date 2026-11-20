<?php
// admin/layout/header.php
require_once __DIR__ . '/../common/common_inc.php'; // 세션 체크, DB
include_once __DIR__ . '/../common/front_head.php';
?>
  <header class="admin-mobile-header">
    <button type="button" id="admin-menu-toggle" aria-controls="admin-sidebar" aria-expanded="false" aria-label="관리자 메뉴 열기"><span aria-hidden="true">☰</span></button>
    <a href="/admin/secret/secret_note.php">비밀 메모장</a>
    <a href="/" class="admin-mobile-home">블로그</a>
  </header>
  <button type="button" id="admin-menu-backdrop" aria-label="관리자 메뉴 닫기" hidden></button>
  <aside class="sidebar" id="admin-sidebar">
    <h1><a href="/"><?= SITE_TITLE ?></a></h1>
    <label class="admin-theme-control">테마
      <select id="admin-theme" aria-label="관리자 테마"><option value="light">라이트</option><option value="dark">다크</option></select>
    </label>
    <nav>
      <ul>
        <li><a href="/admin/dashboard.php">대시보드</a></li>
        <li><a href="/admin/content/content_manage.php">콘텐츠 관리</a></li>
        <li><a href="/admin/guide/html_guide.php">본문 HTML 가이드</a></li>
        <li><a href="/admin/comment/comment_list.php">댓글 관리</a></li>
        <li><a href="/admin/activity/activity_log.php">접속 로그</a></li>
        <li><a href="/admin/Integration/list.php">폴더 & 게시글 관리</a></li>
        <li><a href="/admin/popular/popular.php">인기 게시글</a></li>
        <li><a href="/admin/accounting/account_book.php">가계부</a></li>
        <li><a href="/admin/secret/secret_note.php">비밀 메모장</a></li>
        <li><a href="/admin/code/code_manage.php">공통 코드 관리</a></li>
        <li><a href="/admin/logout.php">로그아웃</a></li>
      </ul>
    </nav>
  </aside>
  <main class="admin-container">
