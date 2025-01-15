<?session_start();?>
<?
# =============================================================================
# File Name    : services_order_dml.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-10-21
# Modify Date  : 2021-05-07
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/enter/enter.php";
	
	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$enterinfo_seq_no		= $_POST['enterinfo_seq_no']!=''?$_POST['enterinfo_seq_no']:$_GET['enterinfo_seq_no'];

	
#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {
		
		$row_cnt = is_null($enterinfo_seq_no) ? 0 : count($enterinfo_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_enterinfo_no = $enterinfo_seq_no[$k];

			$result = updateOrderEnter($conn, $k, $tmp_enterinfo_no);
		}
	}

	db_close($conn);

?>
