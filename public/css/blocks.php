<?php
require __DIR__ . '/../../admin/db.php';
require __DIR__ . '/../../admin/com/biz/common_code.php';
header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: no-store');
foreach (commonCodeOptions($pdo, 'POST_BLOCK') as $code) {
    $class = $code['DCODE'];
    $background = $code['DCODE_EXT'];
    $border = $code['DCODE_INFO01'];
    $text = $code['DCODE_INFO02'];
    if (!preg_match('/^dev-[a-z][a-z0-9-]{0,44}$/D', $class)) continue;
    foreach ([$background, $border, $text] as $color) {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/D', (string)$color)) continue 2;
    }
    echo ":is(.content,.preview-body,.preview-content,.featured-post,.ai-helper-result) .$class { margin:18px 0; padding:14px 16px; border:1px solid $border; border-left-width:4px; border-radius:6px; background:$background; color:$text; line-height:1.7; }\n";
    echo ":is(.content,.preview-body,.preview-content,.featured-post,.ai-helper-result) .$class > p { color:inherit; }\n";
    // Light mode keeps a light surface while retaining each configured accent.
    echo "html[data-theme=light] #content .$class { background:color-mix(in srgb, $background 8%, #fffefa); color:#303631; border-color:$border; }\n";
}
