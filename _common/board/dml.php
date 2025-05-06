<?
	if ($mode == "I") {

		if (substr_count($contents, "&#") > 50) {
			alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
			exit;
		}

		// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글은 사용일 경우에만 가능해야 함
		// 기존 소스 참고
		if (($_SESSION['s_is_adm'] == "") && ($b_secret_tf == "N") && ($secret_tf == "Y"))
			alert("비밀글 미사용 게시판 이므로 비밀글로 등록할 수 없습니다.");
		
		// 쓰기 권한 체크
		if ($_SESSION['s_is_adm'] == "") {
			
			if (!$w_right)
				alert("글을 쓸 권한이 없습니다.");
			
			// 쓰기 시간 체크
			$times = mktime();
			
			if ($_SESSION["s_write_time"] >= ($times - $g_site_re_write))
				alert("너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.");

			$_SESSION["s_write_time"] = $times;

			// 동일내용 연속 등록 불가
			if (checkRepeatBoard($conn, $title, $contents)) 
				alert("동일한 내용을 연속해서 등록할 수 없습니다.");

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);
				
				/*
				for ($i = 0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$title)) {
						alert("제목에 금지어가 포함되어 있습니다.");
					}
				}

				for ($i = 0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						alert("내용에 금지어가 포함되어 있습니다.");
					}
				}
				*/
			}
		}
		#====================================================================
		$savedir1 = $g_physical_path."upload_data/board/".$b_code;
		#====================================================================
		
		//echo $_FILES["pdf_nm"]["name"];
		$board_file_nm = "";
		$board_file_rnm = "";

		if (isset($_FILES["pdf_nm"]) && $_FILES["pdf_nm"]["name"] != "") {
			$board_file_nm				= upload($_FILES["pdf_nm"], $savedir1, 1000 , array('pdf'));
			$board_file_rnm				= $_FILES["pdf_nm"]["name"];
		}
		
		if (isset($_FILES["thumb_nm"]) && $_FILES["thumb_nm"]["name"] != "") {
			$board_file_nm				= upload($_FILES["thumb_nm"], $savedir1, 1000 , array('jpg','jpeg','gif','png','bmp'));
			$board_file_rnm				= $_FILES["thumb_nm"]["name"];
		}

		if (is_null($file_nm)) {
			$file_cnt = 0;
		} elseif (is_countable($file_nm)) {
			$file_cnt = count($file_nm);
		} else {
			// $file_nm이 문자열일 경우 처리
			$file_cnt = 1;
		}
		
		if ($_SESSION['s_adm_no']) {
			$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");
		} else {
			$allow_file_size = getDcodeName($conn, "FILE_SIZE", "USER");
		}

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		//echo byteConvert($max_allow_file_size);

		for($i=0; $i <= $file_cnt; $i++) {

			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES["file_nm"]["size"][$i] > $max_allow_file_size) {
					alert("첨부파일은 ".byteConvert($allow_file_size)."MByte 을 넘을 수 없습니다.");
					exit;
				}
			}
		}

		if ($_SESSION['s_adm_no']) {
			
			$writer_pw		= $_SESSION['s_adm_pw'];
			$writer_id		= $_SESSION['s_adm_id'];
			$writer_nick	= trim($writer_nm);
			$writer_nm		= trim($writer_nm);

		} else {

			if ($_SESSION['s_m_id']) {
				$writer_id		= $_SESSION['s_m_id'];
				$writer_pw		= $m_m_password;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;
			} else {
				$writer_id		= "";
				$writer_pw		= sql_password($conn, trim($writer_pw));
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);
			}
		}
		
		$b_re = getBoardNextRe($conn);
		$b_po = "";
		
		$str_reg_date = $reg_date_ymd." ".$reg_date_time;
		
		if ($top_tf <> "Y") $top_tf = "N";

		$arr_data = array("B_CODE"=>$b_code,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"CATE_01"=>$cate_01,
											"CATE_02"=>$cate_02,
											"CATE_03"=>$cate_03,
											"CATE_04"=>$cate_04,
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$writer_pw,
											"EMAIL"=>$email,
											"PHONE"=>$phone,
											"HOMEPAGE"=>$homepage,
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"KEYWORD"=>$keyword,
											"LINK01"=>$link01,
											"LINK02"=>$link02,
											"INFO_01"=>$info_01,
											"THUMB_IMG"=>$thumb_img,
											"SECRET_TF"=>$secret_tf,
											"MAIN_TF"=>$main_tf,
											"TOP_TF"=>$top_tf,
											"REF_TF"=>$ref_tf,
											"COMMENT_TF"=>$comment_tf,
											"USE_TF"=>$use_tf,
											"START_DATE"=>$start_date,
											"END_DATE"=>$end_date,
											"DATE_USE_TF"=>$date_use_tf,
											"FILE_NM"=>$board_file_nm,
											"FILE_RNM"=>$board_file_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"REG_DATE"=>$str_reg_date);
	
		$new_b_no =  insertBoard($conn, $arr_data);

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);

		for($i=0; $i <= $file_cnt; $i++) {

			if (isset($_POST["file_flag"][$i]) && $_POST["file_flag"][$i] == "insert") {

				$file_nm					= multiupload($_FILES["file_nm"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','ai','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID','AI'));
				
				$file_rnm					= $_FILES["file_nm"]["name"][$i];
				$file_size				= $_FILES["file_nm"]["size"][$i];
				$file_path				= "";
				$file_ext					= explode('.', $_FILES["file_nm"]["name"][$i]);
				$file_ext					= end($file_ext);
				
				//echo "file_nm : ".$file_nm;
				//echo "file_rnm : ".$file_rnm;

				//exit;

				$use_tf = "Y";
				
				//=========================================================================================================================================================
				if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
					
					//echo "Insert File 03";

					if ($thumb_img == "") {

						$img_link = $g_site_url.$g_base_dir."/upload_data/board/".$file_nm;

						//echo $img_link;

						if ($file_nm != "") {
							//if (create_thumbnail($img_link, $g_physical_path."/upload_data/board/simg/".$file_nm,$thumb_width,$thumb_height)) {
							//	$thumb_img = $file_nm;
							//}
						}
					}

				}
				//==========================================================================================================================================================



				if ($file_nm <> "") {
					$result_file = insertBoardFile($conn, $b_code, $new_b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $_SESSION['s_adm_no']);
				}
			}
		}


	
		// 첨부 파일에서 대표 이미지 구할 수 없었다면.. 글 중에 있는지 확인해 보까??
		//===============================================================================================================================================
		$contents = SetStringFromDB($contents);
		$contents = str_replace("&quot;","\"", $contents);
		$thumb_tmp_img = getContentImagesThumb($contents);


		if (!empty($thumb_tmp_img) && file_exists($thumb_tmp_img)) {

			if (@getimagesize($thumb_tmp_img)) {
				$sizesource = getimagesize($thumb_tmp_img);

				switch($sizesource[2]){//image type에 따라 이미지 이름을 생성한다.
					case 1 : //gif
						$thumb_img = $b_code."_".$new_b_no.".gif";
						break;
					case 2 : //jpg
						$thumb_img = $b_code."_".$new_b_no.".jpg";
						break;
					case 3 : //png
						$thumb_img = $b_code."_".$new_b_no.".png";
						break;
				}

				//if ($thumb_img) {
				//	create_thumbnail($thumb_tmp_img,$g_physical_path."upload_data/board/simg/".$thumb_img,$thumb_width,$thumb_height);
				//}
			}

			// 대표 이미지 있다면 업데이트 한번 해주까요..
			if ($thumb_img != "") {
				updateThumbnailImg($conn, $b_code, $new_b_no, $thumb_img);
			}
		}

		//=====================================================================================================================================================
		if ($new_b_no) {
			$result = true;
			$_SESSION['s_encrypt_str'] = "";
		}
	}

	if ($mode == "U") {
		
	#====================================================================
	# Board Config Start
	#====================================================================

		if ($arr_page_nm[1] == "manager") {
			require "../../_common/board/read.php";
		} else {
			require "../_common/board/read.php";
		}
	
	#====================================================================
	# Board Config End
	#====================================================================
	
		if ($rs_b_no == "")  alert("글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."); 

		if (substr_count($contents, "&#") > 50) {
			alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
			exit;
		}

		// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글은 사용일 경우에만 가능해야 함
		// 기존 소스 참고
		if (($_SESSION['s_is_adm'] == "") && ($b_secret_tf == "N") && ($secret_tf == "Y"))
			alert("비밀글 미사용 게시판 이므로 비밀글로 등록할 수 없습니다.");


		if ($_SESSION['s_adm_no']) {
			// 관리자 수정 인경우
		} else {
			// 프론트 수정 일때..
			if ($_SESSION['s_m_id']) {
				if ($_SESSION['s_m_id'] != $rs_writer_id) alert("작성자만 수정 할 수 있습니다.");
			} else {
				// 비회원 수정인 경우
				if (strpos($_SESSION['s_tmp_allow_bn'],"|".$b_no."|") > 0) {
				} else {
					alert("작성자만 수정 할 수 있습니다.");
				}

				//if ($arr_allow_bn)

			}

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);
				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						alert("금지어가 포함되어 있습니다.");
					}
				}
			}
		}

		#====================================================================
		$savedir1 = $g_physical_path."upload_data/board/".$b_code;
		#====================================================================
		
		$board_file_nm = "";
		$board_file_rnm = "";

		# file업로드
		switch ($flag01) {
			case "insert" :
				
				if (isset($_FILES["pdf_nm"]["name"]) && $_FILES["pdf_nm"]["name"] != "") {
					$board_file_nm		= upload($_FILES["pdf_nm"], $savedir1, 1000 , array('pdf'));
					$board_file_rnm	= $_FILES["pdf_nm"]["name"];
				}
		
				if (isset($_FILES["thumb_nm"]["name"]) && $_FILES["thumb_nm"]["name"] != "") {
					$board_file_nm		= upload($_FILES["thumb_nm"], $savedir1, 1000 , array('jpg','jpeg','gif','png','bmp'));
					$board_file_rnm		= $_FILES["thumb_nm"]["name"];
				}

			break;
			case "keep" :

				if ($old_thumb_nm <> "") {
					$board_file_nm			= $old_thumb_nm;
					$board_file_rnm			= $old_thumb_rnm;
				} else {
					$board_file_nm			= $old_file_nm;
					$board_file_rnm			= $old_file_rnm;
				}

			break;
			case "delete" :

				$board_file_nm	= "";
				$board_file_rnm = "";

			break;
			case "update" :

				if (isset($_FILES["pdf_nm"]["name"]) && $_FILES["pdf_nm"]["name"] != "") {
					$board_file_nm		= upload($_FILES["pdf_nm"], $savedir1, 1000 , array('pdf'));
					$board_file_rnm		= $_FILES["pdf_nm"]["name"];
				}
		
				if (isset($_FILES["thumb_nm"]["name"]) && $_FILES["thumb_nm"]["name"] != "") {
					$board_file_nm		= upload($_FILES["thumb_nm"], $savedir1, 1000 , array('jpg','jpeg','gif','png','bmp'));
					$board_file_rnm		= $_FILES["thumb_nm"]["name"];
				}

			break;
		}

		//chmod($savedir1, 0707);
		
		if (is_null($file_nm)) {
			$file_cnt = 0;
		} elseif (is_countable($file_nm)) {
			$file_cnt = count($file_nm);
		} else {
			// $file_nm이 문자열일 경우 처리
			$file_cnt = 1;
		}
	
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		//echo byteConvert($max_allow_file_size);

		for($i=0; $i <= $file_cnt; $i++) {
			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES["file_nm"]["size"][$i] > $max_allow_file_size) {
					alert("첨부파일은 ".byteConvert($allow_file_size)."MByte 을 넘을 수 없습니다.");
					exit;
				}
			}
		}
		

		// 관리자 페이지 인지 확인
		if ($_SESSION['s_adm_no']) { // 관리자 페이지
			
			// 관리자 수정인 경우 사용 정보 수정 하지 않는다.
			$writer_pw		= $rs_writer_pw;
			$writer_id		= $rs_writer_id;
			$writer_nick	= $rs_writer_nick;
			$writer_nm		= $rs_writer_nm;

		} else { // 관리자 페이지 아닌 경우
			
			if ($_SESSION['s_m_id']) { // 로그인한 회원 인지 확인

				$writer_pw		= $m_m_password;
				$writer_id		= $_SESSION['s_m_id'];;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;

			} else {	// 비회원인 경우 

				$writer_id		= "";

				if ($writer_pw) { // 비밀번호를 바구지 않았을 경우
					$writer_pw		= sql_password($conn, trim($writer_pw));
				} else {
					$writer_pw		= $rs_writer_pw;
				}
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);

			}
		}

		if ($_SESSION['s_adm_no']) {
			$ref_ip = $rs_ref_ip;
		} else {
			$ref_ip = $_SERVER['REMOTE_ADDR'];
		}

		$str_reg_date = $reg_date_ymd." ".$reg_date_time;

		if ($top_tf <> "Y") $top_tf = "N";

		//echo $date_use_tf;

		$arr_data = array("CATE_01"=>$cate_01,
											"CATE_02"=>$cate_02,
											"CATE_03"=>$cate_03,
											"CATE_04"=>$cate_04,
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$writer_pw,
											"EMAIL"=>$email,
											"PHONE"=>$phone,
											"HOMEPAGE"=>$homepage,
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"KEYWORD"=>$keyword,
											"LINK01"=>$link01,
											"LINK02"=>$link02,
											"INFO_01"=>$info_01,
											"THUMB_IMG"=>$thumb_img,
											"SECRET_TF"=>$secret_tf,
											"MAIN_TF"=>$main_tf,
											"TOP_TF"=>$top_tf,
											"REF_TF"=>$ref_tf,
											"COMMENT_TF"=>$comment_tf,
											"USE_TF"=>$use_tf,
											"START_DATE"=>$start_date,
											"END_DATE"=>$end_date,
											"DATE_USE_TF"=>$date_use_tf,
											"FILE_NM"=>$board_file_nm,
											"FILE_RNM"=>$board_file_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"REG_DATE"=>$str_reg_date);


		$result = updateBoard($conn, $arr_data, $b_code, $b_no);

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);

	//echo $mode." 0 ". date("Y-m-d H:i:s",strtotime("0 day"))."<br>";


		for($i=0; $i <= $file_cnt; $i++) {

			if (isset($_POST["file_flag"][$i]) && isset($_FILES["file_nm"]["name"][$i])) {

				if (($_POST["file_flag"][$i] == "insert") && ($_FILES["file_nm"]["name"][$i] <> "")) {
				
					$file_nm					= multiupload($_FILES["file_nm"], $i, $savedir1, $allow_file_size, array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','ai','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID','AI'));

					$file_rnm						= $_FILES["file_nm"]["name"][$i];
					$file_path					= "";
					$file_size					= $_FILES["file_nm"]["size"][$i];
					$file_ext						= explode('.', $_FILES["file_nm"]["name"][$i]);
					$file_ext						= end($file_ext);

					//echo "file_dir" . $savedir1 . "<br>";
					//echo "file_nm" . $file_nm . "<br>";

					//exit;

					//=========================================================================================================================================================
					if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
						if ($thumb_img == "") {

							$img_link = $g_site_url.$g_base_dir."/upload_data/board/".$file_nm;

							if ($file_nm != "") {
								//if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg/".$file_nm,$thumb_width,$thumb_height)) {
								//	$thumb_img = $file_nm;
								//}
							}

						}
					}
					//==========================================================================================================================================================


					if ($file_nm <> "") {
						$result_file = insertBoardFile($conn, $b_code, $b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $_SESSION['s_adm_no']);
					}
				}

				if (($_POST["file_flag"][$i] == "update") && ($_FILES["file_nm"]["name"][$i] <> "")) {

					//$result_file_delete = deleteBoardFile($conn, $_POST["old_file_no"][$i]);

					$file_nm					= multiupload($_FILES["file_nm"], $i, $savedir1, $allow_file_size, array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID'));
					$file_rnm					= $_FILES["file_nm"]["name"][$i];
					$file_size				= $_FILES["file_nm"]["size"][$i];
					$file_path				= "";
					$file_ext					= explode('.', $_FILES["file_nm"]["name"][$i]);
					$file_ext					= end($file_ext);

					//=========================================================================================================================================================
					if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
						if ($thumb_img == "") {

							$img_link = $g_site_url.$g_base_dir."/upload_data/board/".$file_nm;

							if ($file_nm != "") {
								//if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg/".$file_nm,$thumb_width,$thumb_height)) {
								//	$thumb_img = $file_nm;
								//}
							}
						}
					}
					//==========================================================================================================================================================

					if ($file_nm <> "") {

						$arr_data = array("B_CODE"=>$b_code,
															"B_NO"=>$b_no,
															"FILE_NM"=>$file_nm,
															"FILE_RNM"=>$file_rnm,
															"FILE_PATH"=>$file_path,
															"FILE_SIZE"=>$file_size,
															"FILE_EXT"=>$file_ext,
															"REG_ADM"=>$_SESSION["s_adm_no"]
														 );

						$result_file = updateBoardFile($conn, $arr_data, $_POST["old_file_no"][$i]);
						//$result_file = insertBoardFile($conn, $b_code, $b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $_SESSION['s_adm_no']);
					}
				}

				if ($_POST["file_flag"][$i] == "delete") {
					$result_file_delete = deleteBoardFile($conn, $_POST["old_file_no"][$i]);
				}
			}
		}

	//echo $mode."8". date("Y-m-d H:i:s",strtotime("0 day"))."<br>";

		// 첨부 파일에서 대표 이미지 구할 수 없었다면.. 글 중에 있는지 확인해 보까??
		//===============================================================================================================================================
		$contents = SetStringFromDB($contents);
		$contents = str_replace("&quot;","\"", $contents);
		$contents = stripslashes($contents);
		$thumb_tmp_img = getContentImagesThumb($contents);

		if (!empty($thumb_tmp_img) && file_exists($thumb_tmp_img)) {

			if (@getimagesize($thumb_tmp_img)) {

				$sizesource = getimagesize($thumb_tmp_img);

				switch($sizesource[2]){//image type에 따라 이미지 이름을 생성한다.
					case 1 : //gif
						$thumb_img = $b_code."_".$b_no.".gif";
						break;
					case 2 : //jpg
						$thumb_img = $b_code."_".$b_no.".jpg";
						break;
					case 3 : //png
						$thumb_img = $b_code."_".$b_no.".png";
						break;
				}
			
				if ($thumb_img) {
					//create_thumbnail($thumb_tmp_img, $g_physical_path."upload_data/board/simg/".$thumb_img,$thumb_width,$thumb_height);
				}
			}
		
			// 대표 이미지 있다면 업데이트 한번 해주까요..
			if ($thumb_img != "") {
				updateThumbnailImg($conn, $b_code, $b_no, $thumb_img);
			}
			//=====================================================================================================================================================
			//exit;
		}
	}

	if ($mode == "IR") {

		if ($arr_page_nm[1] == "manager") {
			require "../../_common/board/read.php";
		} else {
			require "../_common/board/read.php";
		}

	#====================================================================
	# Board Config End
	#====================================================================
	
		if ($rs_b_no == "")  alert("글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."); 

		// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글은 사용일 경우에만 가능해야 함
		// 기존 소스 참고
		if (($_SESSION['s_is_adm'] == "") && ($b_secret_tf == "N") && ($secret_tf == "Y"))
			alert("비밀글 미사용 게시판 이므로 비밀글로 등록할 수 없습니다.");
		
		// 쓰기 권한 체크
		if ($_SESSION['s_is_adm'] == "") {
			if (!$re_right)
				alert("답글을 쓸 권한이 없습니다.");

			// 쓰기 시간 체크
			$times = mktime();
			
			if ($_SESSION["s_write_time"] >= ($times - $g_site_re_write))
				alert("너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.");

			$_SESSION["s_write_time"] = $times;

			// 동일내용 연속 등록 불가
			if (checkRepeatBoard($conn, $title, $contents)) 
				alert("동일한 내용을 연속해서 등록할 수 없습니다.");

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);

				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						alert("금지어가 포함되어 있습니다.");
					}
				}
			}
		}

		// 게시글 배열 참조
		if (strlen($rs_b_po) == 10)
			alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 10단계 까지만 가능합니다.");

		$reply_len = strlen($rs_b_po) + 1;

		if ($b_reply_order == "1") {
			$begin_reply_char = "A";
			$end_reply_char = "Z";
			$reply_number = +1;

			$sql = " SELECT MAX(SUBSTRING(B_PO, $reply_len, 1)) as reply FROM TBL_BOARD WHERE B_RE = '$rs_b_re' AND SUBSTRING(B_PO, $reply_len, 1) <> '' ";

		} else {
			$begin_reply_char = "Z";
			$end_reply_char = "A";
			$reply_number = -1;

			$sql = " SELECT MIN(SUBSTRING(B_PO, $reply_len, 1)) as reply FROM TBL_BOARD where B_RE = '$rs_b_re' and SUBSTRING(B_PO, $reply_len, 1) <> '' ";

		}
		
		if ($rs_b_po) $sql .= " AND B_PO like '$rs_b_po%' ";
		
		//echo $sql;

		$result = mysql_query($sql,$conn);
		$row   = mysql_fetch_array($result);

		if (!$row[reply])
			$reply_char = $begin_reply_char;
		else if ($row[reply] == $end_reply_char) // A~Z은 26 입니다.
			alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 26개 까지만 가능합니다.");
		else
			$reply_char = chr(ord($row[reply]) + $reply_number);

		$reply = $rs_b_po . $reply_char;

		if ($_SESSION['s_adm_no']) {
			
			$writer_pw		= $_SESSION['s_adm_pw'];
			$writer_id		= $_SESSION['s_adm_id'];
			$writer_nick	= trim($writer_nm);
			$writer_nm		= trim($writer_nm);

		} else {

			if ($_SESSION['s_m_id']) {
				$writer_id		= $_SESSION['s_m_id'];
				$writer_pw		= $m_m_password;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;
			} else {
				$writer_id		= "";
				$writer_pw		= sql_password($conn, trim($writer_pw));
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);
			}
		}

		// 답변의 원글이 비밀글이라면 패스워드는 원글과 동일하게 넣는다.
		if ($secret_tf == "Y")
			$writer_pw = $rs_writer_pw;

		//$wr_id = $wr_id . $reply;
		$b_re = $rs_b_re;
		$b_po = $reply;

		$arr_data = array("B_CODE"=>$b_code,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"CATE_01"=>$cate_01,
											"CATE_02"=>$cate_02,
											"CATE_03"=>$cate_03,
											"CATE_04"=>$cate_04,
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$writer_pw,
											"EMAIL"=>$email,
											"PHONE"=>$phone,
											"HOMEPAGE"=>$homepage,
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"KEYWORD"=>$keyword,
											"LINK01"=>$link01,
											"LINK02"=>$link02,
											"INFO_01"=>$info_01,
											"THUMB_IMG"=>$thumb_img,
											"SECRET_TF"=>$secret_tf,
											"MAIN_TF"=>$main_tf,
											"TOP_TF"=>$top_tf,
											"REF_TF"=>$ref_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION['s_adm_no']);

		$new_b_no =  insertBoard($conn, $arr_data);

		#====================================================================
		$savedir1 = $g_physical_path."upload_data/board";
		#====================================================================

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);

		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		//echo byteConvert($max_allow_file_size);

		for($i=0; $i <= $file_cnt; $i++) {
			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES[file_nm]['size'][$i] > $max_allow_file_size) {
					alert("첨부파일은 ".byteConvert($allow_file_size)."MByte 을 넘을 수 없습니다.");
					exit;
				}
			}
		}


		for($i=0; $i <= $file_cnt; $i++) {
			
			if ($_POST["file_flag"][$i] == "insert") {

				$file_nm					= multiupload($_FILES[file_nm], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID'));
				
				$file_rnm						= $_FILES["file_nm"]["name"][$i];
				$file_size					= $_FILES["file_nm"]["size"][$i];
				$file_path					= "";
				$file_ext						= end(explode('.', $_FILES["file_nm"]["name"][$i]));
			
				$use_tf = "Y";
				
				//=========================================================================================================================================================
				if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
					
					//echo "Insert File 03";

					if ($thumb_img == "") {

						$img_link = $g_site_url.$g_base_dir."/upload_data/board/".$file_nm;

						//echo $img_link;

						if ($file_nm != "") {
							//if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg/".$file_nm,$thumb_width,$thumb_height)) {
							//	$thumb_img = $file_nm;
							//}
						}
					}
				}
				//==========================================================================================================================================================
				

				if ($file_nm <> "") {
					$result_file = insertBoardFile($conn, $b_code, $new_b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $_SESSION['s_adm_no']);
				}
			}
		}
	
		// 첨부 파일에서 대표 이미지 구할 수 없었다면.. 글 중에 있는지 확인해 보까??
		//===============================================================================================================================================
		$contents = SetStringFromDB($contents);
		$contents = str_replace("&quot;","\"", $contents);
		$thumb_tmp_img = getContentImagesThumb($contents);

		if (!empty($thumb_tmp_img) && file_exists($thumb_tmp_img)) {
			if (@getimagesize($thumb_tmp_img)) {
				$sizesource = getimagesize($thumb_tmp_img);

				switch($sizesource[2]){//image type에 따라 이미지 이름을 생성한다.
					case 1 : //gif
						$thumb_img = $b_code."_".$new_b_no.".gif";
						break;
					case 2 : //jpg
						$thumb_img = $b_code."_".$new_b_no.".jpg";
						break;
					case 3 : //png
						$thumb_img = $b_code."_".$new_b_no.".png";
						break;
				}

				if ($thumb_img) {
					//create_thumbnail($thumb_tmp_img,$g_physical_path."upload_data/board/simg/".$thumb_img,$thumb_width,$thumb_height);
				}
			}

			// 대표 이미지 있다면 업데이트 한번 해주까요..
			if ($thumb_img != "") {
				updateThumbnailImg($conn, $b_code, $new_b_no, $thumb_img);
			}
		}
		//=====================================================================================================================================================
		if ($new_b_no) {
			$result = true;
		}
	}

?>