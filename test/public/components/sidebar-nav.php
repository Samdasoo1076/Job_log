<?php
// public/components/sidebar-nav.php

function sidebarNav() {
    echo '<div id="sidebar-nav">';
    echo '  <button id="nav-folders" class="nav-btn active" title="Folders">';
    echo '    <img src="assets/icon/folder.svg" alt="Folders">';
    echo '  </button>';
    echo '  <button id="nav-search" class="nav-btn" title="Search">';
    echo '    <img src="assets/icon/search.svg" alt="Search">';
    echo '  </button>';
    echo '  <div class="nav-spacer"></div>';
    echo '  <a href="https://github.com/yourusername" class="nav-btn" target="_blank">';
    echo '    <img src="assets/icon/github.svg" alt="GitHub">';
    echo '  </a>';
    echo '  <a href="https://linkedin.com/in/yourprofile" class="nav-btn" target="_blank">';
    echo '    <img src="assets/icon/linkedin.svg" alt="LinkedIn">';
    echo '  </a>';
    echo '</div>';
}

?>