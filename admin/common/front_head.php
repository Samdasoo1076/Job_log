<?php
// admin/common/front_head.php
// 이 파일은 <head> 부분만 담당하도록 수정
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= SITE_TITLE ?></title>
  <link rel="stylesheet" href="/admin/css/header.css">
  <link rel="stylesheet" href="/admin/css/main.css">
  <link rel="stylesheet" href="/admin/css/dashboard.css">
  <link rel="stylesheet" href="/admin/css/post.css">
  <link rel="stylesheet" href="/admin/css/folder.css">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/admin/vendor/nestable/jquery.nestable.js"></script>
  <script src="/admin/js/post.js" defer></script>
  <!-- <script src="/admin/js/folder.js" defer></script> -->

  <!-- admin/folder/folder_list.php -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="/admin/js/folder2.js"></script>

