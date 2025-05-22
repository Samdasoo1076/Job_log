<?php
require __DIR__ . '/db.php';
$tree   = getFolders();
$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;
$currentPost     = $postId ? getPost($postId) : null;
$currentComments = $postId ? getComments($postId) : [];
require __DIR__ . '/common/logger.php';


// 홈 화면 접속 로그
log_user_activity(0);

// ★ 여기서 SEO용 변수를 준비합니다.
if ($currentPost) {
  // 페이지 타이틀: 글 제목 + 사이트 이름
  $seo_title       = $currentPost['title'];

  // 설명(description): description 필드가 있으면, 없으면 내용의 앞부분
  if (!empty($currentPost['description'])) {
      $seo_description = $currentPost['description'];
  } else {
      // 본문에서 태그 제거 후 150자 자르기
      $snippet = mb_substr(strip_tags($currentPost['content']), 0, 150);
      $seo_description = $snippet . '…';
  }

  // 키워드(keywords): description 혹은 본문에서 주요 단어 뽑기 (예시로 첫 10개 단어)
  $words = preg_split('/\s+/', strip_tags($currentPost['content']));
  $words = array_filter($words, function($w){ return mb_strlen($w) > 2; });
  $seo_keywords = implode(',', array_slice($words, 0, 10));

  // OG:image: 대표 이미지가 있으면 설정
  // $seo_og_image = $currentPost['thumbnail_url'] ?? '';
} else {
  // 기본 홈/목록 페이지용 SEO
  $seo_title       = "이지민 블로그";
  $seo_description = '이지민 블로그에 오신 것을 환영합니다.';
  $seo_keywords    = '블로그,이지민';
  // $seo_og_image = '/path/to/default-image.png';
}


require $_SERVER['DOCUMENT_ROOT'] . '/common/common_inc.php';
require __DIR__ . '/admin/com/biz/post.php'; 

// 컴포넌트 로드
require __DIR__ . '/public/components/nav.php';
require __DIR__ . '/public/components/sidebar.php';
require __DIR__ . '/public/components/post_detail.php';
require __DIR__ . '/public/components/tabs.php';
require __DIR__ . '/public/components/sidebar_header.php';
require __DIR__ . '/public/components/footer.php';

$featured = getFeaturedPost();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="/public/css/layout.css">
  <link rel="stylesheet" href="/public/css/content.css">
  <link rel="stylesheet" href="/public/css/nav.css">
  <link rel="stylesheet" href="/public/css/sidebar.css">
  <link rel="stylesheet" href="/public/css/post.css">
  <link rel="stylesheet" href="/public/css/comment.css">
  <link rel="stylesheet" href="/public/css/tabs.css">
  <link rel="stylesheet" href="/public/css/sidebar_header.css">
  <link rel="stylesheet" href="/public/css/footer.css">

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
        <input id="search-input" type="text" placeholder="Search..."><br>
        <div id="search-tree"></div>
        <!-- <ul id="search-results"></ul> -->
      </div>
    </div>
</div>
    <div id="main">
    <?php renderTabsContainer(); ?>

    <div id="content">
      <?
      if ($featured) {
        // 대표 포스트가 있으면 이걸 렌더링
        echo "<article class=\"featured-post\">";
        echo "<h1>" . htmlspecialchars($featured['title']) . "</h1>";
        echo "<p class=\"meta\">작성: {$featured['created_at']} | 수정: {$featured['updated_at']}</p>";
        echo "<div class=\"content\">{$featured['content']}</div>";
        echo "</article>";
    } else {
      ?>
      <?php if ($currentPost): ?>
        <?php renderPostDetail($currentPost, $currentComments); ?>
      <?php else: ?>
        <h1>Welcome!gd</h1>
        <p>Select a folder or search for a post.</p>
      <?php endif; ?>
      <? }?>
    </div>
  </div>
      </div>

      <!-- 여기에 job log를 붙여 줍니다 -->
<div id="footer">
  <span class= "code"> 
    <img src="/assets/icon/code.svg" class="code-icon">
  </span>
  <?php renderJobLog('Samdasoo1076'); ?>
</div>

      <script src="/public/js/sidebar.js" defer></script>
<script type="module" src="/public/js/post.js"></script>
<script type="module" src="/public/js/nav.js"></script>
<script type="module" src="/public/js/tabs.js"></script>
</body>
</html>
