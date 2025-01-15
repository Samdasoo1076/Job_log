<?session_start();?>
<?
# =============================================================================
# File Name    : pop_menu_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.12.10
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");


#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#==============================================================================
# Confirm right
#==============================================================================

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";


#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/menu/menu.php";
	require "../../_classes/biz/admin/admin.php";


#====================================================================
# Request Parameter
#====================================================================

	$group_name				= isset($_POST["group_name"]) && $_POST["group_name"] !== '' ? $_POST["group_name"] : (isset($_GET["group_name"]) ? $_GET["group_name"] : '');
	$group_no					= isset($_POST["group_no"]) && $_POST["group_no"] !== '' ? $_POST["group_no"] : (isset($_GET["group_no"]) ? $_GET["group_no"] : '');
	$menu_cd					= isset($_POST["menu_cd"]) && $_POST["menu_cd"] !== '' ? $_POST["menu_cd"] : (isset($_GET["menu_cd"]) ? $_GET["menu_cd"] : '');
	$read_chk					= isset($_POST["read_chk"]) && $_POST["read_chk"] !== '' ? $_POST["read_chk"] : (isset($_GET["read_chk"]) ? $_GET["read_chk"] : '');
	$reg_chk					= isset($_POST["reg_chk"]) && $_POST["reg_chk"] !== '' ? $_POST["reg_chk"] : (isset($_GET["reg_chk"]) ? $_GET["reg_chk"] : '');
	$upd_chk					= isset($_POST["upd_chk"]) && $_POST["upd_chk"] !== '' ? $_POST["upd_chk"] : (isset($_GET["upd_chk"]) ? $_GET["upd_chk"] : '');
	$del_chk					= isset($_POST["del_chk"]) && $_POST["del_chk"] !== '' ? $_POST["del_chk"] : (isset($_GET["del_chk"]) ? $_GET["del_chk"] : '');
	$file_chk					= isset($_POST["file_chk"]) && $_POST["file_chk"] !== '' ? $_POST["file_chk"] : (isset($_GET["file_chk"]) ? $_GET["file_chk"] : '');
	$top_chk					= isset($_POST["top_chk"]) && $_POST["top_chk"] !== '' ? $_POST["top_chk"] : (isset($_GET["top_chk"]) ? $_GET["top_chk"] : '');
	$main_chk					= isset($_POST["main_chk"]) && $_POST["main_chk"] !== '' ? $_POST["main_chk"] : (isset($_GET["main_chk"]) ? $_GET["main_chk"] : '');

	$group_no		= trim($group_no);
	$group_no		= (int)$group_no;

	$error_flag = "0";

	$result = deleteAdminGroupMenuRight($conn, (int)$group_no);

	#$search_field		= SetStringToDB($search_field);

	$row_cnt = is_null($menu_cd) ? 0 : count($menu_cd);

	for ($k = 0; $k < $row_cnt; $k++) {
		
		$temp_menu_cd			=  SetStringToDB($menu_cd[$k]);
		$temp_read_chk		=  SetStringToDB($read_chk[$k]);
		$temp_reg_chk			=  SetStringToDB($reg_chk[$k]);
		$temp_upd_chk			=  SetStringToDB($upd_chk[$k]);
		$temp_del_chk			=  SetStringToDB($del_chk[$k]);
		$temp_file_chk		=  SetStringToDB($file_chk[$k]);

		//$temp_top_chk			=  $top_chk[$k];
		//$temp_main_chk		=  $main_chk[$k];
		$temp_top_chk			= "";
		$temp_main_chk		= "";

		If	(($temp_read_chk == "Y") ||
				 ($temp_reg_chk == "Y") ||
				 ($temp_upd_chk == "Y") ||
				 ($temp_del_chk == "Y") ||
				 ($temp_file_chk == "Y") ||
				 ($temp_top_chk == "Y") ||
				 ($temp_main_chk == "Y")) {
		
			$result = insertAdminGroupMenuRight($conn, (int)$group_no, $temp_menu_cd, $temp_read_chk, $temp_reg_chk, $temp_upd_chk, $temp_del_chk, $temp_file_chk, $temp_top_chk, $temp_main_chk);
		}
	}

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 권한 수정 (관리자 아이디 : ".(int)$group_no.") ", "Update");

#====================================================================
# DB Close
#====================================================================

	db_close($conn);

?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=<?=$g_charset?>'>
<title><?=$g_title?></title>
<script type="text/javascript">
<!--
	function init() {
		alert("저장 되었습니다."); //저장 되었습니다.
		document.frm.submit();
	}
//-->
</script>

</head>
<!--<body>-->
<body onLoad="init();">
<form name="frm" action="pop_menu_list.php" method="post">
<input type="hidden" name="group_no" value="<?=$group_no?>">
</form>
</body>
</html>