<?
ini_set('memory_limit', -1);
ini_set('max_execution_time', 60);
session_start();

header("Content-Type: text/html; charset=utf-8");

#==============================================================================
# DB Include, DB Connection
#==============================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
$menu_right = "RE002"; // 메뉴마다 셋팅 해 주어야 합니다

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
require "../../_classes/com/util/AES2.php";
require "../../_classes/com/etc/etc.php";
require "../../_classes/biz/reservation/reservation.php";
require "../../_classes/biz/online/online.php";
require "../../_classes/biz/admin/admin.php";

#==============================================================================
# Request Parameter
#==============================================================================
#==============================================================================
# Request Parameter
#==============================================================================
$m_no =                     isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');

# 공통 컬럼 정의
$mode   =                   isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf =                   isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
$chk                      = isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

# 페이징 관련
$nPage                    = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize                = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_field          = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str            = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');


#============================================================
# Page process
#============================================================
if ($nPage == 0) $nPage = "1";

#List Parameter

$nPage                  = SetStringToDB($nPage);
$nPageSize              = SetStringToDB($nPageSize);
$nPage                  = trim($nPage);
$nPageSize              = trim($nPageSize);

# 검색 조건 추가
$search_field           = SetStringToDB($search_field);
$search_str             = SetStringToDB($search_str);
$search_field           = trim($search_field);
$search_str             = trim($search_str);

$mode                   = SetStringToDB($mode);
$m_no                   = SetStringToDB($m_no);
$use_tf                 = SetStringToDB($use_tf);
$del_tf = "N";

$str_title = /*$str_divice*/ "시설 예약관리 리스트";

$file_name = $str_title . ".xls";

header("Content-type: application/vnd.ms-excel"); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
header("Content-Disposition: attachment; filename=$file_name");


#====================================================================
# Request Parameter
#====================================================================
if ($nPage == 0) $nPage = "1";

#List Parameter
$nPage                = SetStringToDB($nPage);
$nPageSize            = SetStringToDB($nPageSize);
$nPage                = trim($nPage);
$nPageSize            = trim($nPageSize);

# 검색 조건 추가
$search_field        = SetStringToDB($search_field);
$search_str          = SetStringToDB($search_str);
$search_field        = trim($search_field);
$search_str          = trim($search_str);

$mode                = SetStringToDB($mode);
$m_no                = SetStringToDB($m_no);
$use_tf              = SetStringToDB($use_tf);
$del_tf = "N";
#============================================================
# Page process
#============================================================

if ($nPage <> "" && $nPageSize <> 0) {
    $nPage = (int)($nPage);
} else {
    $nPage = 1;
}

if ($nPage <> "" && $nPageSize <> 0) {
    $nPageSize = (int)($nPageSize);
} else {
    $nPageSize = 20;
}

if ($nPageSize == 0) {
    $nPageSize = 20;
}

$nPageBlock    = 10;

#===============================================================
# Get Search list count
#===============================================================

$nListCnt = totalCntReservationAdmin($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, null);

$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1);

if ((int)($nTotalPage) < (int)($nPage)) {
    $nPage = $nTotalPage;
}

$arr_rs = listReservations2($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, null, $nPage, $nPageSize, $nListCnt);

$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "시설 예약관리 엑셀 조회", "Excel");

?>
<font size=3><b>시설 예약관리 리스트</b></font> <br>
<br>
출력 일자 : [<?= date("Y년 m월 d일") ?> ]
<br>
<!-- 조회 조건 : [<?= $m_gubun . "~" . str_replace("-", ".", $m_ksic) ?> ] <?=$email_tf ?> <?=$message_tf ?> -->
<br>
<table border="1">
    <tr>
        <td align='center' bgcolor='#F4F1EF'>예약번호</td>
        <td align='center' bgcolor='#F4F1EF'>회원아이디</td>
        <td align='center' bgcolor='#F4F1EF'>시설명</td>
        <td align='center' bgcolor='#F4F1EF'>사용목적</td>
        <td align='center' bgcolor='#F4F1EF'>예약날짜</td>
        <td align='center' bgcolor='#F4F1EF'>이용시간</td>
        <td align='center' bgcolor='#F4F1EF'>시설대여기자재</td>
        <td align='center' bgcolor='#F4F1EF'>사용인원</td>
        <td align='center' bgcolor='#F4F1EF'>이용료</td>
        <td align='center' bgcolor='#F4F1EF'>예약관련메모</td>
        <td align='center' bgcolor='#F4F1EF'>예약여부</td>
    </tr>
    <?
    $nCnt           = 0;
    $CNT            = 0;
    $TOT_CNT        = 0;

    if (sizeof($arr_rs) > 0) {

        for ($j = 0; $j < sizeof($arr_rs); $j++) {

            #$rn                        = trim($arr_rs[$j]["rn"]);
            $RV_NO                      = trim($arr_rs[$j]["R_NO"]);
            $M_ID                       = trim($arr_rs[$j]["M_ID"]);
            $ROOM_NAME                  = trim($arr_rs[$j]["ROOM_NAME"]);
            $RV_PURPOSE                 = trim($arr_rs[$j]["RV_PURPOSE"]);
            $RV_DATE                    = trim($arr_rs[$j]["RV_DATE"]);
            $RV_START_TIME              = trim($arr_rs[$j]["RV_START_TIME"]);
            $RV_END_TIME                = trim($arr_rs[$j]["RV_END_TIME"]);
            $RV_EQUIPMENT               = trim($arr_rs[$j]["RV_EQUIPMENT"]);
            $RV_USE_COUNT               = trim($arr_rs[$j]["RV_USE_COUNT"]);
            $RV_COST                    = trim($arr_rs[$j]["RV_COST"]);
            $RV_MEMO                    = trim($arr_rs[$j]["RV_MEMO"]);
            $RV_AGREE_TF                = trim($arr_rs[$j]["RV_AGREE_TF"]);

						if ($RV_AGREE_TF == "0") $STR_RV_AGREE_TF = "승인대기";
						if ($RV_AGREE_TF == "1") $STR_RV_AGREE_TF = "승인";
						if ($RV_AGREE_TF == "2") $STR_RV_AGREE_TF = "미승인";
						if ($RV_AGREE_TF == "3") $STR_RV_AGREE_TF = "취소";
						if ($RV_AGREE_TF == "4") $STR_RV_AGREE_TF = "회원취소";

    ?>
            <tr>
                <td><?=$RV_NO ?></td>
                <td><?=$M_ID ?></td>
                <td><?=$ROOM_NAME ?></td>
                <td><?=$RV_PURPOSE ?></td>
                <td><?=$RV_DATE ?></td>
                <td><?= $RV_END_TIME ?></td>
                <td><?=$RV_EQUIPMENT ?></td>
                <td><?=$RV_USE_COUNT ?></td>
                <td><?=$RV_COST ?></td>
                <td><?=$RV_MEMO ?></td>
                <td>
					<?=$STR_RV_AGREE_TF ?>
				</td>
            </tr>
        <?
        }
        ?>
    <?
    } else {
    ?>
        <tr>
            <td colspan="11">
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