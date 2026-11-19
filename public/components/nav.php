<?php
// public/components/nav.php

/**
 * 네비 아이콘 바 렌더
 */
function renderNav() {
    echo '<div id="nav-bar">';
    echo '  <button id="btn-folders" class="nav-btn active" title="Folders">';
    echo '    <img src="../assets/icon/folder-menu.svg" alt="Folders">';
    echo '  </button>';
    echo '  <button id="btn-search" class="nav-btn" title="Search">';
    echo '    <img src="../assets/icon/magnify.svg" alt="Search">';
    echo '  </button>';
    echo '  <div class="nav-spacer"></div>';
    echo '<button type="button" class="blog-theme-switch" role="switch" aria-checked="false" aria-label="라이트 모드로 전환" title="라이트 모드로 전환"></button>';
    echo '<a href="https://money.leejimin.kr" class="nav-btn" target="_blank" rel="noopener noreferrer" title="가계부" aria-label="가계부 앱 열기"><img src="/assets/icon/circle-dollar-sign.svg" alt="가계부"></a>';
    echo '  <a href="https://github.com/Samdasoo1076" class="nav-btn" target="_blank" title="GitHub">';
    echo '    <img src="../assets/images/github.png" alt="GitHub">';
    echo '  </a>';
    echo '  <a href="https://linkedin.com/in/yourprofile" class="nav-btn" target="_blank" title="LinkedIn">';
    echo '    <img src="../assets/icon/Linked.svg" alt="LinkedIn">';
    echo '  </a>';
    echo '</div>';
}
