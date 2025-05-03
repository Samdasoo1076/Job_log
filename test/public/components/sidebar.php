<?php
// public/components/sidebar.php

function renderSidebar(array $folders, $expandedRoot = null) {
    echo '<ul class="sidebar-tree">';
    foreach ($folders as $f) {
        $hasChildren = !empty($f['children']);
        $expanded    = ($f['id'] === $expandedRoot) ? 'expanded' : '';
        echo "<li data-id=\"{$f['id']}\" class=\"{$expanded}\">";
          // 폴더
          echo '<div class="folder">';
          if ($hasChildren) {
              echo '<span class="arrow"></span>';
          } else {
              echo '<span class="arrow"></span>';
          }
          echo '<span class="folder-name">' . htmlspecialchars($f['name']) . '</span>';
          echo '</div>';

          // ▶▶ 자식 폴더 재귀
          if ($hasChildren) {
              renderSidebar($f['children'], $expandedRoot);
          }

          // ▶▶ 이 폴더의 글 리스트 (post-link 클래스 필수)
          $posts = getPostsByFolder($f['id']);
          if ($posts) {
              echo '<ul class="posts-list">';
              foreach ($posts as $p) {
                  echo '<li>';
                  echo '<a href="#" class="post-link" data-post-id="' . $p['id'] . '">'
                       . htmlspecialchars($p['title'])
                       . '</a>';
                  echo '</li>';
              }
              echo '</ul>';
          }

        echo '</li>';
    }
    echo '</ul>';
}
