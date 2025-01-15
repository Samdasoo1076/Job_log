<?session_start();?>
<?
// 절대 결코 윗부분 칸띄우지마세요
header("Content-Type: text/plain; charset=utf-8"); 


#====================================================================
# DB Include, DB Connection
#====================================================================
	include "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	include "../../_classes/com/util/Util.php";
	include "../../_classes/biz/admin/admin.php";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 
#====================================================================
# Request Parameter
#====================================================================
	$keyword					= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];

	$keyword			= SetStringToDB($keyword);
	if (trim($keyword) !="") {
		
		$result = dupAdmin($conn, $keyword);

		print($result);
	}

?>