<?

#====================================================================
# Request Parameter
#====================================================================

	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$con_cate_01 = trim($con_cate_01);
	$con_cate_02 = trim($con_cate_02);
	$con_cate_03 = trim($con_cate_03);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt =totalCntCommBoard($conn, $comm_no, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $con_ref_ip, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listCommBoard($conn, $comm_no, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $con_ref_ip, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);
	
	$arr_rs_top = listCommBoardTop($conn, $comm_no, $main_tf, $top_tf, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

?>