<?

	$config_no = "";
	
	$b_code = isset($b_code) ? $b_code : "";
	if ($b_code == "") $b_code = $b;

	$arr_b_code = explode("_", $b_code);
	
	for ($i = 0; $i < sizeof($arr_b_code) ; $i++) {
		$config_no = $arr_b_code[$i];
	}
	
	//echo $config_no;
	// 게시판 설정을 구한다
	$arr_rs = selectBoardConfig($conn, $g_site_no, $config_no);

	$b_config_no				= trim($arr_rs[0]["CONFIG_NO"]); 
	$b_site_no					= trim($arr_rs[0]["SITE_NO"]); 
	$b_board_code				= trim($arr_rs[0]["BOARD_CODE"]); 
	$b_board_type				= trim($arr_rs[0]["BOARD_TYPE"]); 
	$b_board_cate				= trim($arr_rs[0]["BOARD_CATE"]); 
	$b_board_group				= trim($arr_rs[0]["BOARD_GROUP"]); 
	$b_list_group				= trim($arr_rs[0]["LIST_GROUP"]); 
	$b_read_group				= trim($arr_rs[0]["READ_GROUP"]); 
	$b_write_group			= trim($arr_rs[0]["WRITE_GROUP"]); 
	$b_reply_group			= trim($arr_rs[0]["REPLY_GROUP"]); 
	$b_comment_group		= trim($arr_rs[0]["COMMENT_GROUP"]); 
	$b_link_group				= trim($arr_rs[0]["LINK_GROUP"]); 
	$b_upload_group			= trim($arr_rs[0]["UPLOAD_GROUP"]); 
	$b_download_group		= trim($arr_rs[0]["DOWNLOAD_GROUP"]); 
	$b_secret_tf				= trim($arr_rs[0]["SECRET_TF"]); 
	$b_search_tf				= trim($arr_rs[0]["SEARCH_TF"]); 
	$b_like_tf					= trim($arr_rs[0]["LIKE_TF"]); 
	$b_unlike_tf				= trim($arr_rs[0]["UNLIKE_TF"]); 
	$b_realname_tf			= trim($arr_rs[0]["REALNAME_TF"]); 
	$b_ip_tf						= trim($arr_rs[0]["IP_TF"]); 
	$b_comment_tf				= trim($arr_rs[0]["COMMENT_TF"]); 
	$b_reply_tf					= trim($arr_rs[0]["REPLY_TF"]); 
	$b_html_tf					= trim($arr_rs[0]["HTML_TF"]); 
	$b_file_tf					= trim($arr_rs[0]["FILE_TF"]); 
	$b_file_cnt					= trim($arr_rs[0]["FILE_CNT"]); 
	$b_max_title				= trim($arr_rs[0]["MAX_TITLE"]); 
	$b_new_hour					= trim($arr_rs[0]["NEW_HOUR"]); 
	$b_reply_order			= trim($arr_rs[0]["REPLY_ORDER"]); 
	$b_hot_cnt					= trim($arr_rs[0]["HOT_CNT"]); 
	$b_board_nm					= SetStringFromDB($arr_rs[0]["BOARD_NM"]); 
	$b_board_memo				= SetStringFromDB($arr_rs[0]["BOARD_MEMO"]); 
	$b_board_badword		= SetStringFromDB($arr_rs[0]["BOARD_BADWORD"]); 
	$b_use_tf						= trim($arr_rs[0]["USE_TF"]); 
	$b_del_tf						= trim($arr_rs[0]["DEL_TF"]); 
	$b_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 

	if ($b_board_type == "AD") {
		$thumb_width = "161";
		$thumb_height = "228";
	} else if ($b_board_type == "GALLERY") {
		$thumb_width = "280";
		$thumb_height = "220";
	} else if ($b_board_type == "MOVIE") {
		$thumb_width = "280";
		$thumb_height = "220";
	} else {
		$thumb_width = "600";
		$thumb_height = "333";
	}
	
	$is_guest = false;

	// 게시판 관련 권한 부분이 들어 갑니다.
	if (isset($_SESSION['s_m_id']) && $_SESSION['s_m_id']) {
		
		//echo "회원";
		$is_guest = false;

		$mem_conn = db_connection_member("w");

		$arr_rs_mem		= selectMemberAsEmail($mem_conn, $_SESSION['s_m_id']);
		$m_m_level		= trim($arr_rs_mem[0]["M_LEVEL"]); 
		$m_m_password = trim($arr_rs_mem[0]["M_PASSWORD"]); 
		$m_m_nick			= trim($arr_rs_mem[0]["M_NICK"]); 
		$m_m_name			= trim($arr_rs_mem[0]["M_NAME"]); 

		mysql_close($mem_conn);

		/*
		echo "회원 ".$m_m_level."<br>";
		echo "목록 ".$b_list_group."<br>";
		echo "읽기 ".$b_read_group."<br>";
		echo "쓰기 ".$b_write_group."<br>";
		echo "답변 ".$b_reply_group."<br>";
		echo "답글 ".$b_comment_group."<br>";
		echo "링크 ".$b_link_group."<br>";
		echo "파일 업로드 ".$b_upload_group."<br>";
		echo "파일 다운로드 ".$b_download_group."<br>";
		*/

	} else {
		//echo "비회원";
		$is_guest = true;
		$m_m_level = 1;
	}

	$l_right = false;
	$r_right = false;
	$w_right = false;
	$re_right = false;
	$c_right = false;
	$link_right = false;
	$u_right = false;
	$d_right = false;

	//echo "w : ".$b_write_group." w";

	if ($m_m_level >= $b_list_group) $l_right = true;
	if ($m_m_level >= $b_read_group) $r_right = true;
	if ($m_m_level >= $b_write_group) $w_right = true;
	if ($m_m_level >= $b_reply_group) $re_right = true;
	if ($m_m_level >= $b_comment_group) $c_right = true;
	if ($m_m_level >= $b_link_group) $link_right = true;
	if ($m_m_level >= $b_upload_group) $u_right = true;
	if ($m_m_level >= $b_download_group) $d_right = true;

	if (isset($_SESSION['s_adm_no']) && $_SESSION['s_adm_no']) {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");
	} else {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "USER");
	}

	//$allow_file_size
	$upload_max_filesize = byteConvert($allow_file_size * 1024 * 1024);

?>