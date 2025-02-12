<? session_start(); ?>
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

// RSS 데이터 가져오는 함수
function fetchRss($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL 에러: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    return simplexml_load_string($response);
}

// RSS 피드 URL
$rss_feed_url = 'https://news.google.com/rss/search?q=%EC%9B%90%EC%A3%BC%EB%AF%B8%EB%9E%98%EC%82%B0%EC%97%85%EC%A7%84%ED%9D%A5%EC%9B%90&hl=ko&gl=KR&ceid=KR:ko';
$xml = fetchRss($rss_feed_url);

if ($xml === false) {
    echo "RSS 데이터를 가져오는 데 실패했습니다.";
    exit;
}

// 전체 게시물 개수
//$rssTotalCount = isset($xml->channel->item) ? count($xml->channel->item) : 0;
$rssTotalCount = 10;
//$_SESSION['rssTotalCount'] = $rssTotalCount;


$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
// 필터링된 아이템 배열
$filteredItems = [];

foreach ($xml->channel->item as $item) {
    // 제목에 검색어가 포함되어 있으면 필터링 배열에 추가
    if ($searchKeyword && stripos((string)$item->title, $searchKeyword) !== false) {
        $filteredItems[] = $item;
    } elseif (!$searchKeyword) {
        $filteredItems[] = $item;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////
//  페이징 처리
$items_per_page = 20; //20개 너무 많은거 같은디
$nPage = isset($_GET['nPage']) ? (int)$_GET['nPage'] : 1;
$start_index = ($nPage - 1) * $items_per_page;
$end_index = min($start_index + $items_per_page, $rssTotalCount);

//페이징 끝처리
$TPage = $rssTotalCount > 0 ? ceil($rssTotalCount / $items_per_page) : 0;
?>
<!-- 파라미터에 넣어줄 값 -->
<?
$URL = $_SERVER['PHP_SELF'];
$PBlock = 5;  
$nPage = isset($_GET['nPage']) ? (int)$_GET['nPage'] : 1;  

?>

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
        $count = $start_index + 1; 
        for ($i = $start_index; $i < $end_index; $i++) {
            if (!isset($xml->channel->item[$i])) break;
            $item = $xml->channel->item[$i];
            $title = htmlspecialchars((string)$item->title, ENT_QUOTES, 'UTF-8');
            $link = htmlspecialchars((string)$item->link, ENT_QUOTES, 'UTF-8');
            $pubDate = htmlspecialchars(date("Y.m.d H:i", strtotime((string)$item->pubDate)), ENT_QUOTES, 'UTF-8');
        ?>
            <tr>
                <td class="tbl-no"><p><?= $count++; ?></p></td>
                <td class="tbl-tit">
                    <p><a href="<?= $link; ?>" target="_blank"><?= $title; ?></a></p>
                </td>
                <td class="tbl-date"><?= $pubDate; ?></td>
            </tr>
        <? } ?>
        </tbody>
    </table>
</div>
<!-- CSS 수정 필요 -->
        <!-- Front_Image_PageList 함수 참고 바람.-->
<!-- 페이징 버튼 -->

<div class="pagination">
    <div class='board-page'>
        <?= Front_Image_PageList($URL, $nPage, $TPage, $PBlock, "b=B_1_5"); ?>
    </div>
</div>