<?
	if ($mode == "S") {

		$arr_rs = selectCommBoard($conn, $bb_code, $bb_no);
		
		$rs_bb_no						= trim($arr_rs[0]["BB_NO"]); 
		$rs_bb_code					= trim($arr_rs[0]["BB_CODE"]); 
		$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]); 
		$rs_writer_nm				= SetStringFromDB($arr_rs[0]["WRITER_NM"]);
		$rs_writer_pw				= trim($arr_rs[0]["WRITER_PW"]);
		$rs_writer_id				= trim($arr_rs[0]["WRITER_ID"]); 
		$rs_email						= trim($arr_rs[0]["EMAIL"]); 
		$rs_homepage				= trim($arr_rs[0]["HOMEPAGE"]); 
		$rs_contents				= SetStringFromDB($arr_rs[0]["CONTENTS"]);
		$rs_recomm					= trim($arr_rs[0]["RECOMM"]); 
		$rs_recommno				= trim($arr_rs[0]["RECOMMNO"]); 
		$rs_cate_01					= trim($arr_rs[0]["CATE_01"]); 
		$rs_cate_02					= trim($arr_rs[0]["CATE_02"]); 
		$rs_cate_03					= trim($arr_rs[0]["CATE_03"]); 
		$rs_cate_04					= trim($arr_rs[0]["CATE_04"]); 
		$rs_keyword					= trim($arr_rs[0]["KEYWORD"]); 
		$rs_reply						= SetStringFromDB($arr_rs[0]["REPLY"]);
		$rs_main_tf					= trim($arr_rs[0]["MAIN_TF"]); 
		$rs_top_tf					= trim($arr_rs[0]["TOP_TF"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
		$rs_file_nm					= trim($arr_rs[0]["FILE_NM"]); 
		$rs_file_rnm				= trim($arr_rs[0]["FILE_RNM"]); 
		$rs_file_path				= trim($arr_rs[0]["FILE_PATH"]); 
		$rs_file_size				= trim($arr_rs[0]["FILE_SIZE"]); 
		$rs_file_ext				= trim($arr_rs[0]["FILE_EXT"]); 
		$rs_ref_ip					= trim($arr_rs[0]["REF_IP"]); 

		$content  = $rs_contents;

		$arr_rs_files = listCommBoardFile($conn, $bb_code, $bb_no);

	} else {

		$rs_writer_nm = $bbs_writer_nm;
		$rs_email			= $s_comm_adm_email;
		$rs_writer_pw = $s_comm_adm_com_no;
	}
?>