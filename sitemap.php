<?
header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://leejimin.kr/</loc>
        <lastmod><? echo date("Y-m-d"); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>
    <?
    $dirs = ["comm", "communication", "facility", "intro", "member", "task"]; 
    foreach ($dirs as $dir) {
        echo "
    <url>
        <loc>https://leejimin.kr/$dir/</loc>
        <lastmod>".date("Y-m-d")."</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>";
    }
    ?>
</urlset>