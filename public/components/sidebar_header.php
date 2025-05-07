<?php
// public/components/sidebar_header.php

/**
 * 사이드바 최상단 헤더 렌더링 (접힘/폄 기능 포함)
 *
 * @param string $title      대문자 텍스트 (예: “EXPLORER”)
 * @param string $subtitle   그 밑에 작은 텍스트 (예: “LOCAL (seholee.com)”)
 */
function renderSidebarHeader(string $title, string $subtitle) {
    ?>
    <div class="sidebar-header">
      <button class="header-btn" id="sidebar-toggle">
        <span class="header-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
      </button>
    </div>
    <div class="sidebar-sub">
        <span class="header-arrow"></span>
      <span class><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <?php
}
