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
    echo '  <a href="https://github.com/yourusername" class="nav-btn" target="_blank" title="GitHub">';
    echo '    <img src="../assets/images/github.png" alt="GitHub">';
    echo '  </a>';
    echo '  <a href="https://linkedin.com/in/yourprofile" class="nav-btn" target="_blank" title="LinkedIn">';
    echo '    <img src="../assets/images/Linked.jpg" alt="LinkedIn">';
    echo '  </a>';
    echo '</div>';
}
