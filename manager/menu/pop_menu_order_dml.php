<?session_start();?>
<?
# =============================================================================
# File Name    : pop_menu_order.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-20
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
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

#====================================================================
# Request Parameter
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$order_field			= isset($_POST["order_field"]) && $_POST["order_field"] !== '' ? $_POST["order_field"] : (isset($_GET["order_field"]) ? $_GET["order_field"] : '');
	$order_str				= isset($_POST["order_str"]) && $_POST["order_str"] !== '' ? $_POST["order_str"] : (isset($_GET["order_str"]) ? $_GET["order_str"] : '');

	$m_level					= isset($_POST["m_level"]) && $_POST["m_level"] !== '' ? $_POST["m_level"] : (isset($_GET["m_level"]) ? $_GET["m_level"] : '');
	$catid						= isset($_POST["catid"]) && $_POST["catid"] !== '' ? $_POST["catid"] : (isset($_GET["catid"]) ? $_GET["catid"] : '');

	$arr_menu_no			= isset($_POST["arr_menu_no"]) && $_POST["arr_menu_no"] !== '' ? $_POST["arr_menu_no"] : (isset($_GET["arr_menu_no"]) ? $_GET["arr_menu_no"] : '');

	# System Parameter
	$nPage				= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage				= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$order_field		= SetStringToDB($order_field);
	$order_str			= SetStringToDB($order_str);

	$use_tf					= SetStringToDB($use_tf);

	$m_level				= SetStringToDB($m_level);

	$i = 1;

	$error_flag = "0";

	$row_cnt = is_null($catid) ? 0 : count($catid);

	for ($k = 0; $k < $row_cnt; $k++) {

		if (strlen($m_level) == 0) {
			$menu_level = "MENU_SEQ01";
		}

		if (strlen($m_level) == 2) {
			$menu_level = "MENU_SEQ02";
		}

		if (strlen($m_level) == 4) {
			$menu_level = "MENU_SEQ03";
		}

		$str_seq = "0".$i;
		$str_seq = substr($str_seq, -2);

		$temp_menu_no =  $arr_menu_no[$k];

		$temp_menu_no = "(" . str_replace("^",",", $temp_menu_no) . ")";

		#echo $temp_menu_no."<br>";

		$result = updateAdminMenuOrder($conn, $temp_menu_no, $menu_level, $str_seq);
		
		$i++;
#		'response.write arr_menu_no & "<br>"

#		.ActiveConnection = objDbCon
#		.CommandType = adCmdStoredProc
#		.CommandText = "AUpd_Menu_Order"
#		.Parameters.Append .CreateParameter("RETURN_VALUE"		,adInteger	,adParamReturnValue)
#		.Parameters.Append .CreateParameter("@sMenu_no"				,adVarChar	,adParamInput	,100	,arr_menu_no)
#		.Parameters.Append .CreateParameter("@sLevel"					,adVarChar	,adParamInput	,15		,s_level)
#		.Parameters.Append .CreateParameter("@sSeq"						,adVarChar	,adParamInput	,2		,str_seq)
	
	}

?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=<?=$g_charset?>'>
<title><?=$g_title?></title>
<script type="text/javascript">
<!--
	function init() {
		menu_cd=parent.opener.document.frm.menu_cd.value;
		parent.opener.document.location = "menu_list.php?menu_cd="+menu_cd;
	}
//-->
</script>

</head>
<!--<body>-->
<body onLoad="init();">
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>