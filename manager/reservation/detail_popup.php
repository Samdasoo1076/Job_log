<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");

#====================================================================
# DB Include, DB Connection
#====================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================

$menu_right = "RE002"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header
#====================================================================
require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
require "../../_common/config.php";
require "../../_classes/com/util/Util.php";
require "../../_classes/com/util/ImgUtil.php";
require "../../_classes/com/util/ImgUtilResize.php";
require "../../_classes/com/util/AES2.php";
require "../../_classes/com/etc/etc.php";
require "../../_classes/biz/member/member.php";
require "../../_classes/biz/online/online.php";
require "../../_classes/biz/admin/admin.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/reservation/reservation.php";

#====================================================================
# Request Parameter
#====================================================================

$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$rv_no = isset($_POST["rv_no"]) && $_POST["rv_no"] !== '' ? $_POST["rv_no"] : (isset($_GET["rv_no"]) ? $_GET["rv_no"] : '');

# 공통 컬럼 정의
$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');


$m_no = setStringToDB($m_no);
$rv_no = setStringToDB($rv_no);
$mode = setStringToDB($mode);
$use_tf = setStringToDB($use_tf);

$result = false;

if ($mode == "I") {
		
  $pcode_seq_no = "0";
  

  $result = insertPcode($conn, $g_site_no, $pcode, $pcode_nm, $pcode_memo, $pcode_seq_no, $use_tf, $_SESSION['s_adm_no']);

  $result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "대분류코드 등록 (".$pcode.")", "Insert");

  //exit;

}