<?session_start();?>
<?
# =============================================================================
# File Name    : board_faq_order_dml.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2021-12-23
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
	require "../../_classes/biz/board/board.php";
	
	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$b_no							= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];
	$faq_seq_no				= $_POST['faq_seq_no']!=''?$_POST['faq_seq_no']:$_GET['faq_seq_no'];
	$seq							= $_POST['seq']!=''?$_POST['seq']:$_GET['seq'];
	
#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {

		$row_cnt = is_null($faq_seq_no) ? 0 : count($faq_seq_no);

		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_no = $faq_seq_no[$k];
			$result = updateFaqBoardOrder($conn, $k, $tmp_no);
		}

	}

	db_close($conn);

?>
