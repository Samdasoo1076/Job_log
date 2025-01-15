<?
$rss_feed_url = 'https://news.google.com/rss?hl=ko&gl=KR&ceid=KR:ko';

$xml = simplexml_load_file($rss_feed_url);

if ($xml === false) {
    echo "Failed to load XML\n";
    foreach (libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
} ?>

<div class="board-list">
        <table class="tbl">
            <caption>RSS 뉴스 목록</caption>
            <colgroup>
                <col style="width:10%;">
                <col style="width:auto;">
                <col style="width:20%;">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">제목</th>
                    <th scope="col">등록일</th>
                </tr>
            </thead>
            <tbody>
            <?
            $items = $xml->channel->item; 
            if (!empty($items)) {
                $count = 1; // 정렬 
                foreach ($items as $item) {
                    $title = htmlspecialchars((string)$item->title, ENT_QUOTES, 'UTF-8');
                    $link = htmlspecialchars((string)$item->link, ENT_QUOTES, 'UTF-8');
                    $pubDate = htmlspecialchars(date("Y.m.d H:i", strtotime((string)$item->pubDate)), ENT_QUOTES, 'UTF-8');
					$highlightedTitle = highlightKeyword($title, $s); // 검색어 강조 적용
            ?>
                <tr>
                    <td class="tbl-no"><p><?= $count++; ?></p></td>
                    <td class="tbl-tit">
                        <p><a href="<?= $link; ?>" target="_blank"><?= $highlightedTitle; ?></a></p>
                    </td>
                    <td class="tbl-date"><?= $pubDate; ?></td>
                </tr>
            <?
                }
            } else {
            ?>
                <tr>
                    <td colspan="3" class="no-list">
                        <p>검색결과가 없습니다.</p>
                    </td>
                </tr>
            <? } ?>
            </tbody>
        </table>
    </div>