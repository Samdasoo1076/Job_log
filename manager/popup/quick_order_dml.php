<?session_start();?>
<?
# =============================================================================
# File Name    : quick_order_dml.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-10-21
# Modify Date  : 
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
	require "../../_classes/biz/popup/quick.php";
	
	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$quick_seq_no			= isset($_POST["quick_seq_no"]) && $_POST["quick_seq_no"] !== '' ? $_POST["quick_seq_no"] : (isset($_GET["quick_seq_no"]) ? $_GET["quick_seq_no"] : '');

#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {
		
		$row_cnt = is_null($quick_seq_no) ? 0 : count($quick_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_quick_no = $quick_seq_no[$k];

			$result = updateOrderQuick($conn, $k, $tmp_quick_no);
		}
	}

	db_close($conn);

?>
