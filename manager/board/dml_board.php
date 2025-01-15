<?
	require "../../../_classes/community/util/ImgUtilResize.php";

	function setCcommunityReadFlag($db, $comm_no) {

		$query = "UPDATE CTBL_COMM_MEM_READ SET READ_FLAG = 'N' 
							 WHERE COMM_NO = '$comm_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	if ($mode == "T") {
		updateCommBoardUseTF($conn, $use_tf, $reg_user_no, $bb_code, $bb_no);
	}

	if ($mode == "MD") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_bb_no = $chk[$k];
			$result= deleteCommBoard($conn, $reg_user_no, $comm_no, $bb_code, $tmp_bb_no);
		}
	}

	if ($mode == "D") {
		$result = deleteCommBoard($conn, $reg_user_no, $comm_no, $bb_code, $bb_no);
	}

	$ref_ip = $_SERVER['REMOTE_ADDR'];

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

#====================================================================
	$savedir1 = $g_physical_path."upload_data/board/"
#====================================================================
		
		$result_read_flag = setCcommunityReadFlag($conn, $comm_no);

		$file_cnt = is_null($file_nm) ? 0 : count($file_nm);

		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		for($i=0; $i <= $file_cnt; $i++) {
			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES[file_nm]['size'][$i] > $max_allow_file_size) {
	?>
					<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
					<SCRIPT LANGUAGE="JavaScript">
					<!--
						alert('첨부파일은 <?=$allow_file_size?> MByte 을 넘을 수 없습니다.');
						history.back();
					//-->
					</SCRIPT>
	<?
					exit;
				}
			}
		}

		// 대표 썸네일 이미지 등록 관련 변수
		$thumb_img = "";

		$file_nm		= upload($_FILES[file_nm], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
		$file_rnm		= $_FILES[file_nm][name];
		$file_size	= $_FILES[file_nm]['size'];
		$file_ext		= end(explode('.', $_FILES[file_nm][name]));

		//=========================================================================================================================================================
		if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
			if ($thumb_img == "") {
				$img_link = $g_site_url."/upload_data/board/".$file_nm;
				
				if ($file_nm != "") {
					if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg_180/".$file_nm,"180","100")) {
						$thumb_img = $file_nm;
					}
				}
			}
		}
		//==========================================================================================================================================================

		$title		= SetStringToDB($title);
		$contents = SetStringToDB($contents);

		$new_bb_no =  insertCommBoard($conn, $comm_no, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $phone, $homepage, $title, $ref_ip, $recomm, $recommno, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $main_tf, $top_tf, $use_tf, $reg_user_no);
						
		//$send_res = sendMail("orion70kr@gmail.com", "HiCiel", $title, $contents, "arani71@hotmail.com");
		//$send_res = sendMail("arani71@hotmail.com", "HiCiel", $title, $contents, "lisa@audio.co.kr");

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);

		for($i=0; $i <= $file_cnt; $i++) {

			if ($file_flag[$i] == "insert") {

				$file_nm					= multiupload($_FILES[file_nm], $i, $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID'));
				$file_rnm					= $_FILES[file_nm][name][$i];

				$file_size					= $_FILES[file_nm][size][$i];
				$file_ext						= end(explode('.', $_FILES[file_nm][name][$i]));
			
				$use_tf = "Y";

				//=========================================================================================================================================================
				if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
					if ($thumb_img == "") {
						$img_link = $g_site_url."/upload_data/board/".$file_nm;
						
						if ($file_nm != "") {
							if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg_180".$file_nm,"180","100")) {
								$thumb_img = $file_nm;
							}
						}
					}
				}
				//==========================================================================================================================================================

				if ($file_nm <> "") {
					$result_file = insertCommBoardFile($conn, $bb_code, $new_bb_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_user_no);
				}
			}
		}

		$update_result = updateCommBaordFileCnt($conn, $comm_no, $bb_code, $new_bb_no);

		// 첨부 파일에서 대표 이미지 구할 수 없었다면.. 글 중에 있는지 확인해 보까??
		//===============================================================================================================================================
		$contents = SetStringFromDB($contents);
		$contents = str_replace("&quot;","\"", $contents);
		$thumb_tmp_img = getContentImagesThumb($contents);

		if (@getimagesize($thumb_tmp_img)) {
			$sizesource = getimagesize($thumb_tmp_img);

			switch($sizesource[2]){//image type에 따라 이미지 이름을 생성한다.
				case 1 : //gif
					$thumb_img = $bb_code."_".$new_bb_no.".gif";
					break;
				case 2 : //jpg
					$thumb_img = $bb_code."_".$new_bb_no.".jpg";
					break;
				case 3 : //png
					$thumb_img = $bb_code."_".$new_bb_no.".png";
					break;
			}

			if ($thumb_img) {
				create_thumbnail($thumb_tmp_img,"/home/httpd/goupp/upload_data/".$b_code."/".$thumb_img,"180","100");
			}
		}

		// 대표 이미지 있다면 업데이트 한번 해주까요..
		if ($thumb_img != "") {
			updateComThumbnailImg($conn, $bb_code, $new_bb_no, $thumb_img);
		}
		//=====================================================================================================================================================

		if ($new_bb_no) {
			$result = true;
		}
	}

	if ($mode == "U") {

#====================================================================
		$savedir1 = $g_physical_path."upload_data/board/".$b_code."/".;
#====================================================================

		$file_cnt = is_null($file_nm) ? 0 : count($file_nm);

		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		for($i=0; $i <= $file_cnt; $i++) {
			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES[file_nm]['size'][$i] > $max_allow_file_size) {
	?>
					<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
					<SCRIPT LANGUAGE="JavaScript">
					<!--
						alert('첨부파일은 <?=$allow_file_size?> MByte 을 넘을 수 없습니다.');
						history.back();
					//-->
					</SCRIPT>
	<?
					exit;
				}
			}
		}

		// 대표 썸네일 이미지 등록 관련 변수
		$thumb_img = "";

		switch ($flag01) {
			case "insert" :

				$file_nm		= upload($_FILES[file_nm], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
				$file_rnm		= $_FILES[file_nm][name];
				$file_size	= $_FILES[file_nm]['size'];
				$file_ext		= end(explode('.', $_FILES[file_nm][name]));

			break;
			case "keep" :

				$file_nm		= $old_file_nm;
				$file_rnm		= $old_file_rnm;
				$file_size	= $old_file_size;
				$file_ext		= $old_file_ext;

			break;
			case "delete" :

				$file_nm	= "";
				$file_rnm	= "";
				$file_size = "";
				$file_ext  = "";

			break;
			case "update" :

				$file_nm		= upload($_FILES[file_nm], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
				$file_rnm		= $_FILES[file_nm][name];
				$file_size	= $_FILES[file_nm]['size'];
				$file_ext		= end(explode('.', $_FILES[file_nm][name]));

			break;
		}

		//=========================================================================================================================================================
		if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
			if ($thumb_img == "") {
				$img_link = $g_site_url."/upload_data/board/".$file_nm;
				
				if ($file_nm != "") {
					if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg_180/".$file_nm,"180","100")) {
						$thumb_img = $file_nm;
					}
				}
			}
		}
		//==========================================================================================================================================================

		$title		= SetStringToDB($title);
		$contents = SetStringToDB($contents);

		$result = updateCommBoard($conn, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $phone, $homepage, $title, $ref_ip, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $main_tf, $top_tf, $use_tf, $reg_user_no, $comm_no, $bb_code, $bb_no);

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);

		for($i=0; $i <= $file_cnt; $i++) {
			

			if ($file_flag[$i] == "insert") {

				$file_nm					= multiupload($_FILES[file_nm], $i, $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID'));
				$file_rnm					= $_FILES[file_nm][name][$i];

				$file_size					= $_FILES[file_nm][size][$i];
				$file_ext						= end(explode('.', $_FILES[file_nm][name][$i]));
			
				//=========================================================================================================================================================
				if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
					if ($thumb_img == "") {
						$img_link = $g_site_url."/upload_data/board/".$file_nm;
						
						if ($file_nm != "") {
							if (create_thumbnail($img_link, $g_physical_path."upload_data/board/".$b_code."/".$file_nm,"180","100")) {
								$thumb_img = $file_nm;
							}
						}
					}
				}
				//==========================================================================================================================================================

				if ($file_nm <> "") {
					$result_file = insertCommBoardFile($conn, $bb_code, $bb_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_user_no);
				}
			}

			if ($file_flag[$i] == "update") {
				
				$result_file_delete = deleteCommBoardFile($conn, $old_file_no[$i]);

				$file_nm					= multiupload($_FILES[file_nm], $i, $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID'));
				$file_rnm					= $_FILES[file_nm][name][$i];
				$file_size					= $_FILES[file_nm][size][$i];
				$file_ext						= end(explode('.', $_FILES[file_nm][name][$i]));

				//=========================================================================================================================================================
				if ((strtoupper($file_ext) == "GIF") || (strtoupper($file_ext) == "JPG") || (strtoupper($file_ext) == "JPEG") || (strtoupper($file_ext) == "PNG")) {
					if ($thumb_img == "") {
						$img_link = $g_site_url."/upload_data/board/".$file_name;
						
						if ($file_name != "") {
							if (create_thumbnail($img_link, $g_physical_path."upload_data/board/simg_180/".$file_nm,"180","100")) {
								$thumb_img = $file_nm;
							}
						}
					}
				}
				//==========================================================================================================================================================

				if ($file_nm <> "") {
					$result_file = insertCommBoardFile($conn, $bb_code, $bb_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_user_no);
				}
			}

			if ($file_flag[$i] == "delete") {
				$result_file_delete = deleteCommBoardFile($conn, $old_file_no[$i]);
			}
		}

		$update_result = updateCommBaordFileCnt($conn, $comm_no, $bb_code, $bb_no);


		// 첨부 파일에서 대표 이미지 구할 수 없었다면.. 글 중에 있는지 확인해 보까??
		//===============================================================================================================================================
		$contents = SetStringFromDB($contents);
		$contents = str_replace("&quot;","\"", $contents);
		$thumb_tmp_img = getContentImagesThumb($contents);

		if (@getimagesize($thumb_tmp_img)) {
			$sizesource = getimagesize($thumb_tmp_img);

			switch($sizesource[2]){//image type에 따라 이미지 이름을 생성한다.
				case 1 : //gif
					$thumb_img = $bb_code."_".$bb_no.".gif";
					break;
				case 2 : //jpg
					$thumb_img = $bb_code."_".$bb_no.".jpg";
					break;
				case 3 : //png
					$thumb_img = $bb_code."_".$bb_no.".png";
					break;
			}

			if ($thumb_img) {
				create_thumbnail($thumb_tmp_img,"/home/httpd/goupp/upload_data/board/simg_180/".$thumb_img,"180","100");
			}
		}

		// 대표 이미지 있다면 업데이트 한번 해주까요..
		//if ($thumb_img != "") {
		updateComThumbnailImg($conn, $bb_code, $bb_no, $thumb_img);
		//}
		//=====================================================================================================================================================

	}

?>