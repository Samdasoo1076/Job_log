<?php
// public/sitemap.php

ob_start();

// 1) 사이트 전역 설정(경로, $g_site_url 등) 불러오기
require __DIR__ . '/common/config.php';

// 2) DB 연결 및 getAllPosts() 함수가 정의된 파일
require __DIR__ . '/db.php';

ob_clean();

// 3) Content-Type 헤더
header('Content-Type: application/xml; charset=UTF-8');

// 4) XML 시작 (주의: 반드시 첫 출력이 이 라인이어야 함)
echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";

// 5) 메인 페이지
$baseUrl = rtrim($g_site_url, '/');
echo "  <url>\n",
     "    <loc>{$baseUrl}/</loc>\n",
     "    <changefreq>daily</changefreq>\n",
     "    <priority>1.0</priority>\n",
     "  </url>\n";

// 6) 모든 포스트
$allPosts = getAllPosts();
foreach ($allPosts as $p) {
    $loc     = "{$baseUrl}/?post_id={$p['id']}";
    $lastmod = date('Y-m-d', strtotime($p['updated_at']));
    echo "  <url>\n",
         "    <loc>{$loc}</loc>\n",
         "    <lastmod>{$lastmod}</lastmod>\n",
         "    <changefreq>weekly</changefreq>\n",
         "    <priority>0.8</priority>\n",
         "  </url>\n";
}

// 7) 닫기
echo '</urlset>';

// 8) 스크립트 즉시 종료
exit;
