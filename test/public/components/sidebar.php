<?php
// public/components/sidebar.php

/**
 * 폴더 트리(사이드바) 렌더링
 *
 * @param array    $folders        getFolders() 결과 트리 배열
 * @param int|null $expandedRoot   초기 확장할 폴더 ID
 * @param int|null $currentPostId  현재 보고 있는 포스트 ID
 * @return bool   이 폴더(또는 하위)에 currentPost 가 있으면 true
 */
function renderSidebar(array $folders, $expandedRoot = null, $currentPostId = null) {
    echo '<ul class="sidebar-tree">';
    // 이 레벨에 활성 포스트가 있는지 누적 플래그
    $thisLevelHasActive = false;

    foreach ($folders as $f) {
        $hasChildren = ! empty($f['children']);

        // 1) 이 폴더 안의 직접 포스트들 중 활성 포스트가 있는지
        $posts       = getPostsByFolder($f['id']);
        $hasActivePost = false;
        foreach ($posts as $p) {
            if ($p['id'] === $currentPostId) {
                $hasActivePost = true;
                break;
            }
        }

        // 2) 하위 폴더 재귀 호출 전 버퍼링
        $childHtml       = '';
        $childHasActive  = false;
        if ($hasChildren) {
            ob_start();
            $childHasActive = renderSidebar($f['children'], $expandedRoot, $currentPostId);
            $childHtml      = ob_get_clean();
        }

        // 이 폴더를 펼칠지 (root 지정, 직접 포스트, 자식에 포스트)
        $expanded = (
            $f['id'] === $expandedRoot ||
            $hasActivePost ||
            $childHasActive
        ) ? 'expanded' : '';

        echo "<li data-id=\"{$f['id']}\" class=\"{$expanded}\">";

          // ── 폴더 헤더 ──
          echo '<div class="folder">';
          echo '  <span class="arrow"></span>';
          echo '  <span class="folder-name">'.htmlspecialchars($f['name']).'</span>';
          echo '</div>';

          // ── 자식 폴더(버퍼에서 바로 출력) ──
          if ($hasChildren) {
              echo $childHtml;
          }

          // ── 이 폴더의 글 리스트 ──
          if ($posts) {
              echo '<ul class="posts-list">';
              foreach ($posts as $p) {
                  $id     = (int) $p['id'];
                  $title  = htmlspecialchars($p['title']);
                  $isActive = ($id === $currentPostId) ? ' active' : '';
                  echo '<li>';
                  echo "  <a href=\"#\""
                      . " class=\"post-link{$isActive}\""
                      . " data-post-id=\"{$id}\""
                      . " data-title=\"{$title}\">"
                      . $title
                      . '</a>';
                  echo '</li>';
              }
              echo '</ul>';
          }

        echo '</li>';

        // 이 레벨에 활성 포스트가 발견되었으면 플래그 올려두기
        if ($hasActivePost || $childHasActive) {
            $thisLevelHasActive = true;
        }
    }

    echo '</ul>';
    // 이 폴더(또는 하위)에 활성 포스트가 있었는지 상위에 알려주기
    return $thisLevelHasActive;
}
