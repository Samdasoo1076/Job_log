<?session_start();?>
<?
// 절대 결코 윗부분 칸띄우지마세요
header("Content-Type: text/plain; charset=utf-8"); 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SY002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	include "../../_classes/com/util/Util.php";
	include "../../_classes/biz/syscode/syscode.php";

#====================================================================
# Request Parameter
#====================================================================
	$keyword		= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];
	$type				= $_POST['type']!=''?$_POST['type']:$_GET['type'];

	if (trim($keyword) !="") {
		
		$result = dupPcode($conn, $keyword, $type);

		print($result);
	}

	db_close($conn);
?>