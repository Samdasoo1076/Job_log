<?session_start();?>
<?
# =============================================================================
# File Name    : high_order_dml.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-03
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
	require "../../_classes/biz/high/high.php";
	
	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$high_seq_no		= $_POST['high_seq_no']!=''?$_POST['high_seq_no']:$_GET['high_seq_no'];

	
#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {

		$row_cnt = is_null($high_seq_no) ? 0 : count($high_seq_no);

		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_h_no = $high_seq_no[$k];

			$result = updateOrderHigh($conn, $k, $tmp_h_no);

		}
	}

	db_close($conn);

?>
