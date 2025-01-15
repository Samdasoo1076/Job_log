<?session_start();?>
<?
# =============================================================================
# File Name    : intro_order_dml.php
# Modlue       : 
# Writer       : Lee Ji Min.php
# Create Date  : 2025-01-09
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
	require "../../_classes/biz/popup/intro.php";
	
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$intro_seq_no		= $_POST['intro_seq_no']!=''?$_POST['intro_seq_no']:$_GET['intro_seq_no'];

	
#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {
		
		$row_cnt = is_null($intro_seq_no) ? 0 : count($intro_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_intro_no = $intro_seq_no[$k];

			$result = updateOrderIntro($conn, $k, $tmp_intro_no);
		}
	}

	db_close($conn);

?>