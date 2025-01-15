<?session_start();?>
<?
# =============================================================================
# File Name    : pop_menu_order.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-08-02
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");


#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/common_header.php"; 

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
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/page/page.php";

#====================================================================
# Request Parameter
#====================================================================
	
	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage						= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field			= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str				= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$order_field			= $_POST['order_field']!=''?$_POST['order_field']:$_GET['order_field'];
	$order_str				= $_POST['order_str']!=''?$_POST['order_str']:$_GET['order_str'];

	$m_level					= $_POST['m_level']!=''?$_POST['m_level']:$_GET['m_level'];
	$catid						= $_POST['catid']!=''?$_POST['catid']:$_GET['catid'];
	$arr_page_no			= $_POST['arr_page_no']!=''?$_POST['arr_page_no']:$_GET['arr_page_no'];

	# System Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$order_field		= SetStringToDB($order_field);
	$order_str			= SetStringToDB($order_str);

	$use_tf				= SetStringToDB($use_tf);

	$m_level = SetStringToDB($m_level);

	$i = 1;

	$error_flag = "0";

	$row_cnt = is_null($catid) ? 0 : count($catid);

	for ($k = 0; $k < $row_cnt; $k++) {

		if (strlen($m_level) == 0) {
			$page_level = "PAGE_SEQ01";
		}

		if (strlen($m_level) == 2) {
			$page_level = "PAGE_SEQ02";
		}

		if (strlen($m_level) == 4) {
			$page_level = "PAGE_SEQ03";
		}

		if (strlen($m_level) == 6) {
			$page_level = "PAGE_SEQ04";
		}

		if (strlen($m_level) == 8) {
			$page_level = "PAGE_SEQ05";
		}

		$str_seq = "0".$i;
		$str_seq = substr($str_seq, -2);

		$temp_page_no =  $arr_page_no[$k];

		$temp_page_no = "(" . str_replace("^",",", $temp_page_no) . ")";

		$result = updatePageOrder($conn, $temp_page_no, $page_level, $str_seq);
		
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
		page_cd=parent.opener.document.frm.page_cd.value;
		parent.opener.document.location = "page_list.php?sel_page_lang=<?=$sel_page_lang?>&page_cd="+page_cd;
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