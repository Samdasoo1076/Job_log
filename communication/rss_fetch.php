<? session_start(); ?>
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

// RSS ������ �������� �Լ�
function fetchRss($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL ����: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    return simplexml_load_string($response);
}

// RSS �ǵ� URL
$rss_feed_url = 'https://news.google.com/rss/search?q=%EC%9B%90%EC%A3%BC%EB%AF%B8%EB%9E%98%EC%82%B0%EC%97%85%EC%A7%84%ED%9D%A5%EC%9B%90&hl=ko&gl=KR&ceid=KR:ko';
$xml = fetchRss($rss_feed_url);

if ($xml === false) {
    echo "RSS �����͸� �������� �� �����߽��ϴ�.";
    exit;
}

// ��ü �Խù� ����
$rssTotalCount = isset($xml->channel->item) ? count($xml->channel->item) : 0;
//$_SESSION['rssTotalCount'] = $rssTotalCount;

$queryString = $_SERVER['QUERY_STRING'];
$params = array_merge($_GET, ['rssTotalCount' => $rssTotalCount]);
$updatedQuery = http_build_query(array_merge($_GET, ['rssTotalCount' => $rssTotalCount]));
$newUrl = $_SERVER['PHP_SELF'] . '?' . $updatedQuery;
echo "Updated URL: $newUrl";


$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
// ���͸��� ������ �迭
$filteredItems = [];

foreach ($xml->channel->item as $item) {
    // ���� �˻�� ���ԵǾ� ������ ���͸� �迭�� �߰�
    if ($searchKeyword && stripos((string)$item->title, $searchKeyword) !== false) {
        $filteredItems[] = $item;
    } elseif (!$searchKeyword) {
        $filteredItems[] = $item;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////
//  ����¡ ó��
$items_per_page = 20; //20�� �ʹ� ������ ������
$nPage = isset($_GET['nPage']) ? (int)$_GET['nPage'] : 1;
$start_index = ($nPage - 1) * $items_per_page;
$end_index = min($start_index + $items_per_page, $rssTotalCount);

//����¡ ��ó��
$TPage = $rssTotalCount > 0 ? ceil($rssTotalCount / $items_per_page) : 0;
?>
<!-- �Ķ���Ϳ� �־��� �� -->
<?
$URL = $_SERVER['PHP_SELF'];
$PBlock = 5;  
$nPage = isset($_GET['nPage']) ? (int)$_GET['nPage'] : 1;  

?>

<div class="board-list">
    <table class="tbl">
        <caption>RSS ���� ���</caption>
        <colgroup>
            <col style="width:10%;">
            <col style="width:auto;">
            <col style="width:20%;">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">����</th>
                <th scope="col">�����</th>
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
<!-- CSS ���� �ʿ� -->
        <!-- Front_Image_PageList �Լ� ���� �ٶ�.-->
<!-- ����¡ ��ư -->

<div class="pagination">
    <div class='board-page'>
        <?= Front_Image_PageList($URL, $nPage, $TPage, $PBlock, "b=B_1_5"); ?>
    </div>
</div>

<!--
<div class="pagination">
 <div class='board-page'>
    <? if ($page > 1) { ?>
        <button type='button' class='btn-page-first' disabled><span class='blind'>����".$PBlock."��</span></button>
        <button type='button' class='btn-page-prev' disabled><span class='blind'>���� ������</span></button>
        <a href="?b=B_1_5&page=<?= $page - 1 ?>">&laquo; ����</a>
    <? } ?>
    <? for ($i = 1; $i <= $total_pages; $i++) { ?>
        <a href="?b=B_1_5&page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
        <button type='button' class='btn-page-number is-current' aria-current='page' onClick='return false;'><?= $i?><span class='blind'>&nbsp;������</span></button>
    <? } ?>
    <? if ($page < $total_pages) { ?>
        <a href="?b=B_1_5&page=<?= $page + 1 ?>">���� &raquo;</a>
        <button type='button' class='btn-page-next' disabled onClick='return false;'><span class='blind'>���� ������</span></button>
        <button type='button' class='btn-page-last' disabled><span class='blind'>����".$PBlock."��</span></button>
    <? } ?>
    </div>
</div>
-->