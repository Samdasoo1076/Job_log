<?php
// admin/common/front_head.php
// HTML head 시작부 (공통 CSS/메타 등 로드)
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
  <link rel="stylesheet" href="/admin/css/dashboard.css"> <!-- 추가 -->
</head>
<body>
