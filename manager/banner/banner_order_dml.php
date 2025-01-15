<?session_start();?>
<?
# =============================================================================
# File Name    : banner_order_dml.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-10-16
# Modify Date  : 2021-04-29
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
	require "../../_classes/biz/banner/banner.php";
	
	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$banner_no				= isset($_POST["banner_no"]) && $_POST["banner_no"] !== '' ? $_POST["banner_no"] : (isset($_GET["banner_no"]) ? $_GET["banner_no"] : '');
	$seq_banner_no		= isset($_POST["seq_banner_no"]) && $_POST["seq_banner_no"] !== '' ? $_POST["seq_banner_no"] : (isset($_GET["seq_banner_no"]) ? $_GET["seq_banner_no"] : '');
	$banner_seq_no		= isset($_POST["banner_seq_no"]) && $_POST["banner_seq_no"] !== '' ? $_POST["banner_seq_no"] : (isset($_GET["banner_seq_no"]) ? $_GET["banner_seq_no"] : '');
	$seq							= isset($_POST["seq"]) && $_POST["seq"] !== '' ? $_POST["seq"] : (isset($_GET["seq"]) ? $_GET["seq"] : '');

	
#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {

		$row_cnt = is_null($banner_seq_no) ? 0 : count($banner_seq_no);

		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_banner_no = $banner_seq_no[$k];
			$result = updateOrderBanner($conn, $k, $tmp_banner_no);
		}

	}

	db_close($conn);

?>
