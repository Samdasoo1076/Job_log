<?php
require __DIR__ . '/../db.php';
$tree   = getFolders();
$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;
$currentPost     = $postId ? getPost($postId) : null;
$currentComments = $postId ? getComments($postId) : [];
// 컴포넌트 로드
require __DIR__ . '/components/nav.php';
require __DIR__ . '/components/sidebar.php';
require __DIR__ . '/components/post_detail.php';
require __DIR__ . '/components/tabs.php';
require __DIR__ . '/components/sidebar_header.php';
require __DIR__ . '/components/footer.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/layout.css">
  <link rel="stylesheet" href="css/content.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/post.css">
  <link rel="stylesheet" href="css/comment.css">
  <link rel="stylesheet" href="css/tabs.css">
  <link rel="stylesheet" href="css/sidebar_header.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="shortcut icon" href="/assets/icon/favicon.svg" />
  <link rel="apple-touch-icon" href="/assets/icon/favicon.svg" />

  <link rel="icon" href="/assets/icon/favicon.svg" type="image/png">
  <link rel="shortcut icon" href="/assets/icon/favicon.svg" type="image/png">
</head>
<body>
  <div id="app">
    <?php renderNav(); ?>

    <div id="sidebar">
    <?php renderSidebarHeader('EXPLORER', 'test'); ?>  <!-- 여기입니다 -->

    <div class="sidebar-body">
      <div id="tree-view">
        <?php renderSidebar($tree, $postId ? null : array_key_first($tree), $postId); ?>
      </div>
      <div id="search-view">
        <label>SEARCH</label><br>
        <input id="search-input" type="text" placeholder="Search..." autocomplete="off"><br>
        <div id="search-tree"></div>
        <!-- <ul id="search-results"></ul> -->
      </div>
    </div>
</div>
    <div id="main">
    <?php renderTabsContainer(); ?>

    <div id="content">
      <?php if ($currentPost): ?>
        <?php renderPostDetail($currentPost, $currentComments); ?>
      <?php else: ?>
        <h1>Welcome!</h1>
        <p>Select a folder or search for a post.</p>
      <?php endif; ?>
    </div>
  </div>
      </div>

      <!-- 여기에 job log를 붙여 줍니다 -->
<div id="footer">
  <span class= "code"> 
    <img src="../assets/icon/code.svg" class="code-icon">
  </span>
  <?php renderJobLog('Samdasoo1076'); ?>
</div>

      <script src="js/sidebar.js" defer></script>
<script type="module" src="js/post.js"></script>
<script type="module" src="js/nav.js"></script>
<script type="module" src="js/tabs.js"></script>
</body>
</html>
