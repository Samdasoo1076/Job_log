<?php
require __DIR__ . '/../db.php';
$tree   = getFolders();
$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;

// 컴포넌트 로드
require __DIR__ . '/components/sidebar.php';
require __DIR__ . '/components/post_detail.php';

$currentPost     = $postId ? getPost($postId) : null;
$currentComments = $postId ? getComments($postId) : [];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/layout.css">
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/post.css">
  
</head>
<body>
  <div id="sidebar">
    <?php renderSidebar($tree, $postId ? null : array_key_first($tree)); ?>
  </div>\

  <div id="content">
    <?php if ($currentPost): ?>
      <?php renderPostDetail($currentPost, $currentComments); ?>
    <?php else: ?>
      <h1>Welcome!</h1>
      <p>Select a folder or a post from the sidebar.</p>
    <?php endif; ?>
  </div>

  <script src="js/sidebar.js"></script>
  <script src="js/post.js"></script>
</body>
</html>
