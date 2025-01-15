<?
ini_set('memory_limit', -1);
ini_set('max_execution_time', 60);
session_start();

header("Content-Type: text/html; charset=utf-8");

# =============================================================================
# File Name    : period_page_excel_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2023-10-16
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#==============================================================================
# DB Include, DB Connection
#==============================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
$menu_right = "ST002"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# common_header Check Session
#==============================================================================
require "../../_common/common_header.php";

#===============================================================================
# common function, login_function
#===============================================================================
require "../../_common/config.php";
require "../../_classes/com/util/Util.php";
require "../../_classes/com/util/ImgUtil.php";
require "../../_classes/com/util/ImgUtilResize.php";
require "../../_classes/com/etc/etc.php";
require "../../_classes/biz/statistic/statistic.php";
require "../../_classes/biz/admin/admin.php";

#==============================================================================
# Request Parameter
#==============================================================================

$mode                                = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');

$start_date                    = isset($_POST["start_date"]) && $_POST["start_date"] !== '' ? $_POST["start_date"] : (isset($_GET["start_date"]) ? $_GET["start_date"] : '');
$end_date                        = isset($_POST["end_date"]) && $_POST["end_date"] !== '' ? $_POST["end_date"] : (isset($_GET["end_date"]) ? $_GET["end_date"] : '');

$con_divicetype            = isset($_POST["con_divicetype"]) && $_POST["con_divicetype"] !== '' ? $_POST["con_divicetype"] : (isset($_GET["con_divicetype"]) ? $_GET["con_divicetype"] : '');


if ($con_divicetype == "P") {
    $str_divice = "PC";
} else if ($con_divicetype == "M") {
    $str_divice = "모바일";
} else {
    $str_divice = "기기전체";
}

$str_title = $str_divice . " 기간별 페이지별 접속통계";

$file_name = $str_title . "(" . str_replace("-", ".", $start_date) . "-" . str_replace("-", ".", $end_date) . ").xls";
header("Content-type: application/vnd.ms-excel"); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
header("Content-Disposition: attachment; filename=$file_name");


$mode            = SetStringToDB($mode);

#==============================================================================
# Request Parameter
#==============================================================================
$nPage = "1";

if ($start_date == "") $start_date = date("Y-m-d", strtotime("-1 month"));
if ($end_date == "") $end_date = date("Y-m-d", strtotime("0 day"));

#==============================================================================
# Page process
#==============================================================================

$nPageBlock    = 10;

#==============================================================================
# Get Search list count
#==============================================================================

$arr_rs = listPagePeriod($conn, $start_date, $end_date, $con_divicetype);

$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "페이지별 접속통계 엑셀 조회", "Excel");

?>
<font size=3><b>페이지별 접속통계</b></font> <br>
<br>
출력 일자 : [<?= date("Y년 m월 d일") ?> ]
<br>
조회 조건 : [<?= str_replace("-", ".", $start_date) . "~" . str_replace("-", ".", $end_date) ?> ] <?= $str_divice ?>
<br>
<table border="1">
    <tr>
        <td align='center' bgcolor='#F4F1EF'>구분</td>
        <td align='center' bgcolor='#F4F1EF'>페이지명</td>
        <td align='center' bgcolor='#F4F1EF'>조회수</td>
    </tr>
    <?
    $nCnt            = 0;
    $CNT            = 0;
    $TOT_CNT    = 0;

    if (sizeof($arr_rs) > 0) {

        for ($j = 0; $j < sizeof($arr_rs); $j++) {

            $PAGE_NAME            = trim($arr_rs[$j]["PAGE_NAME"]);
            $CATE                        = trim($arr_rs[$j]["CATE"]);
            $CNT                        = trim($arr_rs[$j]["CNT"]);

            $TOT_CNT                = $TOT_CNT + $CNT;
    ?>
            <tr>
                <td><?= $CATE ?></td>
                <td><?= $PAGE_NAME ?></td>
                <td style="text-align:right;padding-right:10px"><?= number_format($CNT) ?></td>
            </tr>
        <?
        }
        ?>
        <tr bgcolor="#EFEFEF">
            <td colspan="2">합계</td>
            <td style="text-align:right;padding-right:10px"><?= number_format($TOT_CNT) ?></td>
        </tr>
    <?
    } else {
    ?>
        <tr>
            <td colspan="4">
                <div class="nodata">검색 결과가 없습니다.</div>
            </td>
        </tr>
    <?
    }
    ?>
</table>
</body>

</html>
<?
#====================================================================
# DB Close
#====================================================================

db_close($conn);
?>