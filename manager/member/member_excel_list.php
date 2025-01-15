<?
ini_set('memory_limit', -1);
ini_set('max_execution_time', 60);
session_start();

header("Content-Type: text/html; charset=utf-8");

# =============================================================================
# File Name    : member_excel_list.php
# Modlue       : 
# Writer       : Seo Hyun Seok 
# Create Date  : 2024-11-26
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
$menu_right = "ME001"; // 메뉴마다 셋팅 해 주어야 합니다

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
require "../../_classes/biz/online/online.php";
require "../../_classes/biz/member/member.php";
require "../../_classes/biz/admin/admin.php";

#==============================================================================
# Request Parameter
#==============================================================================
// 검색 조건 
$mode =                     isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$m_no =                     isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$m_gubun =                  isset($_POST["m_gubun"]) && $_POST["m_gubun"] !== '' ? $_POST["m_gubun"] : (isset($_GET["m_gubun"]) ? $_GET["m_gubun"] : '');
$m_ksic =                   isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');
$email_tf =                 isset($_POST["email_tf"]) && $_POST["email_tf"] !== '' ? $_POST["email_tf"] : (isset($_GET["email_tf"]) ? $_GET["email_tf"] : '');
$message_tf =               isset($_POST["message_tf"]) && $_POST["message_tf"] !== '' ? $_POST["message_tf"] : (isset($_GET["message_tf"]) ? $_GET["message_tf"] : '');
$use_tf =                   isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

# 페이징 관련
$nPage                    = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize                = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

$search_field          = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str            = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');



$str_title = /*$str_divice*/ "회원 관리 리스트";

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

$nListCnt = totalCntMembers($conn, $m_no, $m_gubun, $m_ksic, $email_tf, $message_tf, $use_tf, $del_tf, $search_field, $search_str);

$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1);

if ((int)($nTotalPage) < (int)($nPage)) {
    $nPage = $nTotalPage;
}
$arr_rs = listMember($conn, $m_gubun, $m_ksic, $email_tf, $message_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt)

//$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "페이지별 접속통계 엑셀 조회", "Excel");

?>
<font size=3><b>회원관리 리스트</b></font> <br>
<br>
출력 일자 : [<?= date("Y년 m월 d일") ?> ]
<br>
<!-- 조회 조건 : [<?= $m_gubun . "~" . str_replace("-", ".", $m_ksic) ?> ] <?=$email_tf ?> <?=$message_tf ?> -->
<br>
<table border="1">
    <tr>
        <td align='center' bgcolor='#F4F1EF'>번호</td>
        <td align='center' bgcolor='#F4F1EF'>회원아이디</td>
        <td align='center' bgcolor='#F4F1EF'>회원구분</td>
        <td align='center' bgcolor='#F4F1EF'>산업분류</td>
        <td align='center' bgcolor='#F4F1EF'>주소</td>
        <td align='center' bgcolor='#F4F1EF'>전화번호</td>
        <td align='center' bgcolor='#F4F1EF'>메일주소</td>
        <td align='center' bgcolor='#F4F1EF'>메일수신</td>
        <td align='center' bgcolor='#F4F1EF'>문자수신</td>
        <td align='center' bgcolor='#F4F1EF'>사용여부</td>
    </tr>
    <?
    $nCnt           = 0;
    $CNT            = 0;
    $TOT_CNT        = 0;

    if (sizeof($arr_rs) > 0) {

        for ($j = 0; $j < sizeof($arr_rs); $j++) {

            #$rn                         = trim($arr_rs[$j]["rn"]);
            $M_NO                       = trim($arr_rs[$j]["M_NO"]);
            $M_ID                       = trim($arr_rs[$j]["M_ID"]);
            $M_GUBUN                    = trim($arr_rs[$j]["M_GUBUN"]);
            $M_KSIC                     = trim($arr_rs[$j]["M_KSIC"]);
            $M_KSIC_DETAIL              = trim($arr_rs[$j]["M_KSIC_DETAIL"]);

            $M_ADDR                     = trim($arr_rs[$j]["M_ADDR"]);
            $m_addr_dec                 = decrypt($key, $iv, $M_ADDR);

            $M_POST_CD                  = trim($arr_rs[$j]["M_POST_CD"]);
            $post_cd_dec                = decrypt($key, $iv, $M_POST_CD);

            $M_ADDR_DETAIL              = trim($arr_rs[$j]["M_ADDR_DETAIL"]);
            $m_addr_detail_dec          = decrypt($key, $iv, $M_ADDR_DETAIL);

            $M_PHONE                    = trim($arr_rs[$j]["M_PHONE"]);
            $m_phone_dec                = decrypt($key, $iv, $M_PHONE);

            $M_EMAIL                    = trim($arr_rs[$j]["M_EMAIL"]);
            $m_email_dec                = decrypt($key, $iv, $M_EMAIL);

            $EMAIL_TF                   = trim($arr_rs[$j]["EMAIL_TF"]);
            $MESSAGE_TF                 = trim($arr_rs[$j]["MESSAGE_TF"]);
            $USE_TF                     = trim($arr_rs[$j]["USE_TF"]);
    ?>
            <tr>
                <td><?=$M_NO ?></td>
                <td><?=$M_ID ?></td>
                <td style="text-align:center">
                <?
                    if ($M_GUBUN === "P") {
                        echo "개인회원";
                    } else if ($M_GUBUN === "C") {
                        echo "기업회원";
                    } 
                ?>
                </td>
                <td><?=$M_KSIC_DETAIL ?></td>
                <td><?=$m_addr_dec ?></td>
                <td><?=$m_phone_dec ?></td>
                <td><?=$m_email_dec ?></td>
                <td><?=$EMAIL_TF ?></td>
                <td><?=$MESSAGE_TF ?></td>
                <td><?=$USE_TF ?></td>

            </tr>
        <?
        }
        ?>
    <?
    } else {
    ?>
        <tr>
            <td colspan="9">
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