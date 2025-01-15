<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : dcode_order_dml.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-21
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

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
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/syscode/syscode.php";
	
	//$arr_rs = getSiteInfo($conn, $site_no);
	
#====================================================================
# DML Process
#====================================================================
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$pcode					= $_POST['pcode']!=''?$_POST['pcode']:$_GET['pcode'];
	$dcode_seq_no		= $_POST['dcode_seq_no']!=''?$_POST['dcode_seq_no']:$_GET['dcode_seq_no'];
	
	//echo "변경";

	if ($mode == "O") {

		$row_cnt = is_null($dcode_seq_no) ? 0 : count($dcode_seq_no);

		//echo "변경".$row_cnt;
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_dcode_no = (int)$dcode_seq_no[$k];

			$result = updateOrderDcode($conn, $k, $pcode, $tmp_dcode_no);
		
		}
	}

	db_close($conn);
?>
