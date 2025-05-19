<?php
// public/components/sidebar.php

/**
 * 폴더 트리(사이드바) 렌더링 (폴더/포스트 카운트 포함)
 *
 * @param array    $folders        getFolders() 결과 트리 배열
 * @param int|null $expandedRoot   초기 확장할 폴더 ID
 * @param int|null $currentPostId  현재 보고 있는 포스트 ID
 * @return bool   이 폴더(또는 하위)에 currentPost 가 있으면 true
 */
function renderSidebar(array $folders, $expandedRoot = null, $currentPostId = null): bool {

    
    
    echo '<ul class="sidebar-tree">';

    $tops = getTopPosts();
    if ($tops) {
        echo '<li class="expanded">';       // ← expanded 붙이기!
        echo '<ul class="posts-list">';
        foreach ($tops as $p) {
            $id    = (int) $p['id'];
            $title = htmlspecialchars($p['title']);
            $isAct = $id === $currentPostId ? ' active' : '';
            echo '<li>';
            echo "  <a href=\"#\" class=\"post-link{$isAct}\" data-post-id=\"{$id}\" data-title=\"{$title}\">";
            echo $title;
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</li>';
    }
    $thisLevelHasActive = false;

    foreach ($folders as $f) {
        $hasChildren = ! empty($f['children']);

        // 이 폴더 안의 직접 포스트들
        $posts     = getPostsByFolder($f['id']);
        $postCount = count($posts);

        // 자식 폴더 재귀 호출 전 버퍼링
        $childHtml      = '';
        $childHasActive = false;
        if ($hasChildren) {
            ob_start();
            $childHasActive = renderSidebar($f['children'], $expandedRoot, $currentPostId);
            $childHtml      = ob_get_clean();
        }

        // 이 폴더를 펼칠지 결정
        $hasActivePost = in_array($currentPostId, array_column($posts, 'id'), true);
        $expanded = (
            $f['id'] === $expandedRoot ||
            $hasActivePost ||
            $childHasActive
        ) ? 'expanded' : '';

        // 자식 폴더 수
        $folderCount = count($f['children']);
        $AllCount = $folderCount + $postCount;

        echo "<li data-id=\"{$f['id']}\" class=\"{$expanded}\">";

          // ── 폴더 헤더 ──
          echo '<div class="folder">';
          echo '  <span class="arrow"></span>';
          // 폴더명 + (폴더수 / 포스트수)
          echo '  <span class="folder-name">'
               . htmlspecialchars($f['name'])
               . ' <span class="count">('
               . $AllCount
               . ')</span>'
               . '</span>';
          echo '</div>';

          // ── 자식 폴더 ──
          if ($hasChildren) {
              echo $childHtml;
          }

          // ── 이 폴더의 글 리스트 ──
          if ($postCount) {
              echo '<ul class="posts-list">';
              foreach ($posts as $p) {
                  $id    = (int) $p['id'];
                  $title = htmlspecialchars($p['title']);
                  $isAct = $id === $currentPostId ? ' active' : '';
                  echo '<li>';
                  echo "  <a href=\"#\" class=\"post-link{$isAct}\""
                     . " data-post-id=\"{$id}\" data-title=\"{$title}\">"
                     . $title
                     . '</a>';
                  echo '</li>';
              }
              echo '</ul>';
          }

        echo '</li>';

        if ($hasActivePost || $childHasActive) {
            $thisLevelHasActive = true;
        }
    }

    echo '</ul>';
    return $thisLevelHasActive;
}
