<?
		// 관리자 사용자 변수 맞춤
		if ($b_code == "") $b_code	= $b;
		if ($b_no == "") $b_no= $bn;

		$arr_rs = selectBoard($conn, $b_code, $b_no);
		
		$rs_b_no						= trim($arr_rs[0]["B_NO"]); 
		$rs_b_po						= trim($arr_rs[0]["B_PO"]); 
		$rs_b_re						= trim($arr_rs[0]["B_RE"]); 
		
		$rs_b_code					= trim($arr_rs[0]["B_CODE"]); 
		$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]); 
		$rs_writer_id				= SetStringFromDB($arr_rs[0]["WRITER_ID"]);
		$rs_writer_nm				= SetStringFromDB($arr_rs[0]["WRITER_NM"]);
		$rs_writer_nick			= SetStringFromDB($arr_rs[0]["WRITER_NICK"]);
		$rs_writer_pw				= SetStringFromDB($arr_rs[0]["WRITER_PW"]);
		$rs_email						= trim($arr_rs[0]["EMAIL"]); 
		$rs_phone						= trim($arr_rs[0]["PHONE"]); 
		$rs_homepage				= SetStringFromDB($arr_rs[0]["HOMEPAGE"]); 
		$rs_contents				= SetStringFromDB($arr_rs[0]["CONTENTS"]);
		$rs_recomm					= trim($arr_rs[0]["RECOMM"]); 
		$rs_cate_01					= trim($arr_rs[0]["CATE_01"]); 
		$rs_cate_02					= trim($arr_rs[0]["CATE_02"]); 
		$rs_cate_03					= SetStringFromDB($arr_rs[0]["CATE_03"]); 
		$rs_cate_04					= trim($arr_rs[0]["CATE_04"]); 
		$rs_keyword					= trim($arr_rs[0]["KEYWORD"]); 
		$rs_reply						= trim($arr_rs[0]["REPLY"]);
		$rs_reply_state			= trim($arr_rs[0]["REPLY_STATE"]);
		$rs_reply_date			= trim($arr_rs[0]["REPLY_DATE"]);
		$rs_top_tf					= trim($arr_rs[0]["TOP_TF"]); 
		$rs_ref_tf					= trim($arr_rs[0]["REF_TF"]); 
		$rs_main_tf					= trim($arr_rs[0]["MAIN_TF"]); 
		$rs_comment_tf			= trim($arr_rs[0]["COMMENT_TF"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 

		$rs_start_date			= trim($arr_rs[0]["START_DATE"]); 
		$rs_end_date				= trim($arr_rs[0]["END_DATE"]); 
		$rs_date_use_tf			= trim($arr_rs[0]["DATE_USE_TF"]); 

		$rs_link01					= trim($arr_rs[0]["LINK01"]); 
		$rs_link02					= trim($arr_rs[0]["LINK02"]); 
		$rs_info_01					= trim($arr_rs[0]["INFO_01"]); 
		$rs_secret_tf				= trim($arr_rs[0]["SECRET_TF"]); 

		$rs_thumb_img				= trim($arr_rs[0]["THUMB_IMG"]); 

		$rs_file_nm					= trim($arr_rs[0]["FILE_NM"]); 
		$rs_file_rnm				= trim($arr_rs[0]["FILE_RNM"]); 

		$rs_ref_ip					= trim($arr_rs[0]["REF_IP"]); 
	
		if ($rs_start_date) {
			$arr_start_date			= explode(" ", $rs_start_date);
			$arr_end_date				= explode(" ", $rs_end_date);

			$arr_start_time			= explode(":", $arr_start_date[1]);
			$arr_end_time				= explode(":", $arr_end_date[1]);

			$rs_s_date = $arr_start_date[0];
			$rs_e_date = $arr_end_date[0];
				
			$rs_s_hour = $arr_start_time[0];
			$rs_e_hour = $arr_end_time[0];

			$rs_s_min = $arr_start_time[1];
			$rs_e_min = $arr_end_time[1];
		}

		if ($rs_reply_state == "Y")
			$str_reply_state = "<font color='navy'>답변완료</font>";
		else
			$str_reply_state = "<font color='red'>대기중</font>";

		$content  = $rs_contents;
		
		if ($rs_thumb_img)
			$str_thumb_img = $g_base_dir."/upload_data/board/simg/".$rs_thumb_img;
		
		$arr_rs_files = listBoardFile($conn, $b_code, $b_no);

		$result_read = viewChkBoardAsAdmin($conn, $b_code, $b_no, $_SESSION['s_adm_no']);
?>