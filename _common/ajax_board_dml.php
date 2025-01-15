<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 
	error_reporting(E_ALL & ~E_NOTICE);

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/board/board.php";
	require "../_classes/biz/board/block_ip.php";
	require "../_classes/biz/online/online.php";

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$bn							= isset($_POST["bn"]) && $_POST["bn"] !== '' ? $_POST["bn"] : (isset($_GET["bn"]) ? $_GET["bn"] : '');
	$b							= isset($_POST["b"]) && $_POST["b"] !== '' ? $_POST["b"] : (isset($_GET["b"]) ? $_GET["b"] : '');
	$cate_01				= isset($_POST["cate_01"]) && $_POST["cate_01"] !== '' ? $_POST["cate_01"] : (isset($_GET["cate_01"]) ? $_GET["cate_01"] : '');

	if ($mode == "MAIN_NOTICE_LIST") {
		
		$list_str = "";
		$cnt			= 6;

		$arr_notice = getMainBoardListWithCate($conn, $b, $cate_01, $cnt);

		if(sizeof($arr_notice) > 0) {
			for ($j = 0 ; $j < sizeof($arr_notice) ; $j++){
				$B_NO						= trim($arr_notice[$j]["B_NO"]);
				$B_CODE					= trim($arr_notice[$j]["B_CODE"]);
				$CATE_01				= trim($arr_notice[$j]["CATE_01"]);
				$TITLE					= SetStringFromDB($arr_notice[$j]["TITLE"]);
				$REG_DATE		= trim($arr_notice[$j]["REG_DATE"]);
				$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

				switch ($CATE_01) {
				case "":
					$F_COLOR = "gray";
				case "ALL":
					$F_COLOR = "gray";
					break;
				case "SUSI":
					$F_COLOR = "orange";
					break;
				case "JEONGSI":
					$F_COLOR = "red";
					break;
				case "JEOEGUK":
					$F_COLOR = "purple";
					break;
				case "PYEONIP":
					$F_COLOR = "blue";
					break;
				case "ETC":
					$F_COLOR = "green";
					break;
				}
				
				$list_str = $list_str . "<li>";
				$list_str = $list_str . "<a href='/help/notice_view.php?bn=".$B_NO."&m_type=".$CATE_01."'>";
				$list_str = $list_str . "<span class='".$F_COLOR."'>".getDcodeName($conn,"MOJIB_TYPE", $CATE_01)."</span>";
				$list_str = $list_str . "<div class='innerbox'>";
				$list_str = $list_str . "<p class='title'>".$TITLE."</p>";
				$list_str = $list_str . "<span class='date'>".$REG_DATE."</span>";
				$list_str = $list_str . "</div>";
				$list_str = $list_str . "</a>";
				$list_str = $list_str . "</li>";

			}

		} else {
				$list_str = "<li><a href='javascript:void(0)'><div class='innerbox'><p class='title'>결과가 없습니다.</p></div></a></li>";
		}

		$result = "T";

		$arr_result = array("result"=>$result, "list_str"=>$list_str);
		echo json_encode($arr_result);

	}

	if ($mode == "MAIN_PDS_LIST") {
		
		$list_str = "";
		$cnt			= 6;

		$arr_notice = getMainBoardListWithCate($conn, $b, $cate_01, $cnt);

		if(sizeof($arr_notice) > 0) {
			for ($j = 0 ; $j < sizeof($arr_notice) ; $j++){
				$B_NO						= trim($arr_notice[$j]["B_NO"]);
				$B_CODE					= trim($arr_notice[$j]["B_CODE"]);
				$CATE_01				= trim($arr_notice[$j]["CATE_01"]);
				$TITLE					= SetStringFromDB($arr_notice[$j]["TITLE"]);
				$REG_DATE		= trim($arr_notice[$j]["REG_DATE"]);
				$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

				if ($CATE_01 == "학생부종합전형") { 
					$STR_CATE_01 = "<div style='line-height:32px;'>학생부<div style='line-height:0px;'>종합전형</div></div>" ;
				} else {
					$STR_CATE_01 = getDcodeName($conn,"MOJIB_TYPE", $CATE_01);
				}

				switch ($CATE_01) {
				case "":
					$F_COLOR = "gray";
				case "ALL":
					$F_COLOR = "gray";
					break;
				case "SUSI":
					$F_COLOR = "orange";
					break;
				case "JEONGSI":
					$F_COLOR = "red";
					break;
				case "JEOEGUK":
					$F_COLOR = "purple";
					break;
				case "PYEONIP":
					$F_COLOR = "blue";
					break;
				case "ETC":
					$F_COLOR = "green";
					break;
				case "학생부종합전형":
					$F_COLOR = "orange";
					break;
				}

				$list_str = $list_str . "<li>";
				$list_str = $list_str . "<a href='/help/resources_view.php?bn=".$B_NO."&m_type=".$CATE_01."'>";
				$list_str = $list_str . "<span class='".$F_COLOR."'>".$STR_CATE_01."</span>";
				$list_str = $list_str . "<div class='innerbox'>";
				$list_str = $list_str . "<p class='title'>".$TITLE."</p>";
				$list_str = $list_str . "<span class='date'>".$REG_DATE."</span>";
				$list_str = $list_str . "</div>";
				$list_str = $list_str . "</a>";
				$list_str = $list_str . "</li>";

			}

		} else {
				$list_str = "<li><a href='javascript:void(0)'><div class='innerbox'><p class='title'>결과가 없습니다.</p></div></a></li>";
		}

		$result = "T";

		$arr_result = array("result"=>$result, "list_str"=>$list_str);
		echo json_encode($arr_result);

	}


	if ($mode == "ADM_REPLY_SAVE") {

		$reply					= $_POST['reply']!=''?$_POST['reply']:$_GET['reply'];
		$reply_state		= $_POST['reply_state']!=''?$_POST['reply_state']:$_GET['reply_state'];
		
		$reply					= SetStringToDB($reply);
		
		$str_reg_date	= date("Y-m-d H:i:s",strtotime("0 day"));

		$arr_data = array("REPLY"=>$reply,
											"REPLY_STATE"=>$reply_state,
											"REPLY_DATE"=>$str_reg_date,
											"REPLY_ADM"=>$_SESSION['s_adm_no']);
		
		$result = updateQnaBoard($conn, $arr_data, $b, $bn);
		
		$result = "Y";

		$arr_result = array("result"=>$result);
		echo json_encode($arr_result);

	}

	if ($mode == "CONFIRM_PASSWORD") {

		$confirm_passwd	= isset($_POST["confirm_passwd"]) && $_POST["confirm_passwd"] !== '' ? $_POST["confirm_passwd"] : (isset($_GET["confirm_passwd"]) ? $_GET["confirm_passwd"] : '');
		$confirm_mode		= isset($_POST["confirm_mode"]) && $_POST["confirm_mode"] !== '' ? $_POST["confirm_mode"] : (isset($_GET["confirm_mode"]) ? $_GET["confirm_mode"] : '');

		$arr_rs = selectBoard($conn, $b, $bn);

		$en_confirm_passwd = base64_encode(hash('sha256', $confirm_passwd, true));

		$confirm_passwd2 = dehack_check($confirm_passwd);

		$en2_confirm_passwd = base64_encode(hash('sha256', $confirm_passwd2, true));
		
		if (sizeof($arr_rs) > 0) {

			if ((trim($arr_rs[0]["WRITER_PW"]) == $en_confirm_passwd) || (trim($arr_rs[0]["WRITER_PW"]) == $en2_confirm_passwd)) {
				$result = "T";
				$msg		= "";
				$_SESSION['s_board_bn'] = encrypt($key, $iv, $bn);
			} else {
				$result = "F";
				$msg		= "비밀번호를 확인 하세요.";
			}
		} else {
			$result = "F";
			$msg		= "비밀번호를 확인 하세요.";
		}

		if (($confirm_mode == "del") && ($result = "T")) {
			$result_del = deleteBoard($conn, "0", $b, $bn);
			$msg		= "삭제되었습니다.";
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);
		
	}

	if ($mode == "VIEW_ADD_CNT") {
		
		$read_ip = $_SERVER['REMOTE_ADDR'];
		$result = viewChkBoardAsIp($conn,$b, $bn, $read_ip);

		$arr_result = array("result"=>$result);
		echo json_encode($arr_result);

	}

	if ($mode == "BOARD_REGISTRATION") {

		$title				= isset($_POST["title"]) && $_POST["title"] !== '' ? $_POST["title"] : (isset($_GET["title"]) ? $_GET["title"] : '');
		$cate_01			= isset($_POST["cate_01"]) && $_POST["cate_01"] !== '' ? $_POST["cate_01"] : (isset($_GET["cate_01"]) ? $_GET["cate_01"] : '');
		$writer_nm		= isset($_POST["writer_nm"]) && $_POST["writer_nm"] !== '' ? $_POST["writer_nm"] : (isset($_GET["writer_nm"]) ? $_GET["writer_nm"] : '');
		$writer_pw		= isset($_POST["writer_pw"]) && $_POST["writer_pw"] !== '' ? $_POST["writer_pw"] : (isset($_GET["writer_pw"]) ? $_GET["writer_pw"] : '');
		$contents			= isset($_POST["contents"]) && $_POST["contents"] !== '' ? $_POST["contents"] : (isset($_GET["contents"]) ? $_GET["contents"] : '');
		$agree_tf			= isset($_POST["agree_tf"]) && $_POST["agree_tf"] !== '' ? $_POST["agree_tf"] : (isset($_GET["agree_tf"]) ? $_GET["agree_tf"] : '');
		$secret_tf		= isset($_POST["secret_tf"]) && $_POST["secret_tf"] !== '' ? $_POST["secret_tf"] : (isset($_GET["secret_tf"]) ? $_GET["secret_tf"] : '');
		$date_use_tf	= isset($_POST["date_use_tf"]) && $_POST["date_use_tf"] !== '' ? $_POST["date_use_tf"] : (isset($_GET["date_use_tf"]) ? $_GET["date_use_tf"] : '');
		$encrypt_str	= isset($_POST["encrypt_str"]) && $_POST["encrypt_str"] !== '' ? $_POST["encrypt_str"] : (isset($_GET["encrypt_str"]) ? $_GET["encrypt_str"] : '');

		$temp_str = decrypt($key, $iv, $_SESSION['s_encrypt_str']);

		require "../_common/board/config_info.php";

		if ($encrypt_str <> $temp_str) exit;

		// 쓰기 시간 체크
		$currentTimestamp = time();

		$times = date('Y-m-d H:i:s', $currentTimestamp);
			
		if (isset($_SESSION["s_write_time"])) {
			$sessionWriteTime = strtotime($_SESSION["s_write_time"]);
			$comparisonTime = strtotime($times) - 60;

			if ($sessionWriteTime >= $comparisonTime) {
				exit;
			}
		}

		$_SESSION["s_write_time"] = $times;

		if ($secret_tf <> "Y") $secret_tf = "N";
		
		$writer_id		= "";
		$writer_nm		= SetStringToDB($writer_nm);
		$title				= SetStringToDB($title);
		$contents			= SetStringToDB($contents);
		$en_writer_pw = base64_encode(hash('sha256', $writer_pw, true));
		$writer_nick	= trim($writer_nm);

		$b_re = getBoardNextRe($conn);
		$b_po = "";


		if (substr_count($contents, "&#") > 50) {
			exit;
		}

		#====================================================================
		$savedir1 = $g_physical_path."upload_data/board/".$b;
		#====================================================================

		$file_nm		= upload($_FILES["pds_name"], $savedir1, 1000 , array('jpg','png','gif','jpeg','pdf','zip','hwp','doc','docx','ppt','pptx','xls','xlsx'));
		$file_rnm		= $_FILES["pds_name"]["name"];
		$ref_ip			= $_SERVER["REMOTE_ADDR"];

		$str_reg_date	= date("Y-m-d H:i:s",strtotime("0 day"));

		$arr_data = array("B_CODE"=>$b,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"CATE_01"=>$cate_01,
											"CATE_02"=>"",
											"CATE_03"=>"",
											"CATE_04"=>"",
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$en_writer_pw,
											"EMAIL"=>"",
											"PHONE"=>"",
											"HOMEPAGE"=>"",
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"REPLY"=>"",
											"KEYWORD"=>"",
											"LINK01"=>"",
											"LINK02"=>"",
											"SECRET_TF"=>$secret_tf,
											"DATE_USE_TF"=>$date_use_tf,
											"USE_TF"=>"Y",
											"FILE_NM"=>$file_nm,
											"FILE_RNM"=>$file_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"REG_DATE"=>$str_reg_date);

		$block_flag = chkBoardBlockIP($conn, $_SERVER["REMOTE_ADDR"]);

		if ($block_flag > 0) {
			$result = insertBoardBlockData($conn, $arr_data);
			db_close($conn);
			exit;
		}

		$chk_flag = chkSystemBlockIP($conn, $_SERVER["REMOTE_ADDR"]);

		$mailTo		=	"park@ucomp.co.kr";	// 받는 메일 주소;
		$mailFrom	=	"admin@kku.ac.kr";	// 보내는 메일 주소;

		$mailSubject = "[Q&A] 신규 문의가 접수 되었습니다.";
		$mailContent = "제목 : ".$title."<br /><br /><br />내용 : ".nl2br($contents)."<br/><br/>";

		$mailHeader = "From: $mailFrom\r\n";
		$mailHeader .= "MIME-Version: 1.0\r\n";
		$mailHeader .= "Content-type: text/html; charset=utf-8\r\n";

		//mail($mailto, $subject, $message, $header, '-f'.$admin_email);

		$mailResult = mail ($mailTo, "=?UTF-8?B?".base64_encode($mailSubject)."?=", $mailContent, $mailHeader,'-f'.$mailFrom);

		if ($b_board_badword) {
			$filter_word = explode(",",$b_board_badword);

			for ($u=0;$u<sizeof($filter_word);$u++) {
				if (strpos(strtolower($title), $filter_word[$u])) {
					$result = insertBoardBlockData($conn, $arr_data);
					db_close($conn);
					exit;
				}
			}

			for ($u=0;$u<sizeof($filter_word);$u++) {
				if (strpos(strtolower($contents), $filter_word[$u])) {
					$result = insertBoardBlockData($conn, $arr_data);
					db_close($conn);
					exit;
				}
			}
		}

		if ($chk_flag == true) {
			$result = insertBoardBlockData($conn, $arr_data);
			db_close($conn);
			exit;
		}

		$result = insertBoard($conn, $arr_data);

		$mailTo		=	"kradio19@jinhak.com";	// 받는 메일 주소;
		$mailFrom	=	"admin@kku.ac.kr";	// 보내는 메일 주소;

		$mailSubject = "[Q&A] 신규 문의가 접수 되었습니다.";
		$mailContent = "제목 : ".$title."<br /><br /><br />내용 : ".nl2br($contents)."<br/><br/>";

		$mailHeader = "From: $mailFrom\r\n";
		$mailHeader .= "MIME-Version: 1.0\r\n";
		$mailHeader .= "Content-type: text/html; charset=utf-8\r\n";

		//mail($mailto, $subject, $message, $header, '-f'.$admin_email);

		$mailResult = mail ($mailTo, "=?UTF-8?B?".base64_encode($mailSubject)."?=", $mailContent, $mailHeader,'-f'.$mailFrom);


		if ($b == "B_1_4") {

			if ($cate_01 == "PYEONIP") {

				$mailTo		=	"ihseo22@kku.ac.kr";	// 받는 메일 주소;
				$mailFrom	=	"admin@kku.ac.kr";	// 보내는 메일 주소;

				$mailSubject = "[편입학 Q&A] 신규 문의가 접수 되었습니다.";
				$mailContent = "제목 : ".$title."<br /><br /><br />내용 : ".nl2br($contents)."<br/><br/>";

				$mailHeader = "From: $mailFrom\r\n";
				$mailHeader .= "MIME-Version: 1.0\r\n";
				$mailHeader .= "Content-type: text/html; charset=utf-8\r\n";

				//mail($mailto, $subject, $message, $header, '-f'.$admin_email);

				$mailResult = mail ($mailTo, "=?UTF-8?B?".base64_encode($mailSubject)."?=", $mailContent, $mailHeader,'-f'.$mailFrom);


			} else {

				$mailTo		=	"enter@kku.ac.kr";	// 받는 메일 주소;
				$mailFrom	=	"admin@kku.ac.kr";	// 보내는 메일 주소;

				$mailSubject = "[Q&A] 신규 문의가 접수 되었습니다.";
				$mailContent = "제목 : ".$title."<br /><br /><br />내용 : ".nl2br($contents)."<br/><br/>";

				$mailHeader = "From: $mailFrom\r\n";
				$mailHeader .= "MIME-Version: 1.0\r\n";
				$mailHeader .= "Content-type: text/html; charset=utf-8\r\n";

				//mail($mailto, $subject, $message, $header, '-f'.$admin_email);

				$mailResult = mail ($mailTo, "=?UTF-8?B?".base64_encode($mailSubject)."?=", $mailContent, $mailHeader,'-f'.$mailFrom);

				/*
				$mailTo		=	"park@ucomp.co.kr";
				$mailFrom	=	"admin@kku.ac.kr";

				$mailSubject = "[Q&A] 신규 문의가 접수 되었습니다.";
				$mailContent = "제목 : ".$title."<br /><br /><br />내용 : ".nl2br($contents)."<br/><br/>";

				$mailHeader = "From: $mailFrom\r\n";
				$mailHeader .= "MIME-Version: 1.0\r\n";
				$mailHeader .= "Content-type: text/html; charset=utf-8\r\n";

				$mailResult = mail ($mailTo, "=?UTF-8?B?".base64_encode($mailSubject)."?=", $mailContent, $mailHeader,'-f'.$mailFrom);
				*/
			}
		}


		$result = "T";
		$msg = "";
		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "BOARD_MOD") {

		$title				= isset($_POST["title"]) && $_POST["title"] !== '' ? $_POST["title"] : (isset($_GET["title"]) ? $_GET["title"] : '');
		$cate_01			= isset($_POST["cate_01"]) && $_POST["cate_01"] !== '' ? $_POST["cate_01"] : (isset($_GET["cate_01"]) ? $_GET["cate_01"] : '');
		$writer_nm		= isset($_POST["writer_nm"]) && $_POST["writer_nm"] !== '' ? $_POST["writer_nm"] : (isset($_GET["writer_nm"]) ? $_GET["writer_nm"] : '');
		$writer_pw		= isset($_POST["writer_pw"]) && $_POST["writer_pw"] !== '' ? $_POST["writer_pw"] : (isset($_GET["writer_pw"]) ? $_GET["writer_pw"] : '');
		$contents			= isset($_POST["contents"]) && $_POST["contents"] !== '' ? $_POST["contents"] : (isset($_GET["contents"]) ? $_GET["contents"] : '');
		$agree_tf			= isset($_POST["agree_tf"]) && $_POST["agree_tf"] !== '' ? $_POST["agree_tf"] : (isset($_GET["agree_tf"]) ? $_GET["agree_tf"] : '');
		$secret_tf		= isset($_POST["secret_tf"]) && $_POST["secret_tf"] !== '' ? $_POST["secret_tf"] : (isset($_GET["secret_tf"]) ? $_GET["secret_tf"] : '');
		$date_use_tf	= isset($_POST["date_use_tf"]) && $_POST["date_use_tf"] !== '' ? $_POST["date_use_tf"] : (isset($_GET["date_use_tf"]) ? $_GET["date_use_tf"] : '');

		if ($secret_tf <> "Y") $secret_tf = "N";
		
		$writer_id		= "";
		$write_nm			= SetStringToDB($write_nm);
		$title				= SetStringToDB($title);
		$contents			= SetStringToDB($contents);
		$en_writer_pw = base64_encode(hash('sha256', $writer_pw, true));
		$writer_nick	= trim($writer_nm);

		if (substr_count($contents, "&#") > 50) {
			alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
			exit;
		}

		require "../_common/board/config_info.php";

		if ($b_board_badword) {
			$filter_word = explode(",",$b_board_badword);

			for ($u=0;$u<sizeof($filter_word);$u++) {
				if (strpos(strtolower($title), $filter_word[$u])) exit;
			}

			for ($u=0;$u<sizeof($filter_word);$u++) {
				if (strpos(strtolower($contents), $filter_word[$u])) exit;
			}
		}


		$filter_word2 = array(".com",".org",".kr",".비아그라");

		for ($u=0;$u<sizeof($filter_word2);$u++) {
			if (strpos(strtolower($title), $filter_word2[$u])) exit;
		}

		for ($u=0;$u<sizeof($filter_word2);$u++) {
			if (strpos(strtolower($contents), $filter_word2[$u])) exit;
		}

		#====================================================================
		$savedir1 = $g_physical_path."upload_data/board/".$b;
		#====================================================================

		$ref_ip			= $_SERVER["REMOTE_ADDR"];
		$str_reg_date	= date("Y-m-d H:i:s",strtotime("0 day"));
		
		if ($_FILES["pds_name"]["name"] <> "") { 
			$file_nm		= upload($_FILES["pds_name"], $savedir1, 1000 , array('jpg','png','gif','jpeg','pdf','zip','hwp','doc','docx','ppt','pptx','xls','xlsx'));
			$file_rnm		= $_FILES["pds_name"]["name"];

			$arr_data = array("CATE_01"=>$cate_01,
												"WRITER_ID"=>$writer_id,
												"WRITER_NM"=>$writer_nm,
												"WRITER_NICK"=>$writer_nick,
												"WRITER_PW"=>$en_writer_pw,
												"TITLE"=>$title,
												"REF_IP"=>$ref_ip,
												"CONTENTS"=>$contents,
												"SECRET_TF"=>$secret_tf,
												"FILE_NM"=>$file_nm,
												"FILE_RNM"=>$file_rnm,
												"UP_DATE"=>$str_reg_date);

		} else {

			$arr_data = array("CATE_01"=>$cate_01,
												"WRITER_ID"=>$writer_id,
												"WRITER_NM"=>$writer_nm,
												"WRITER_NICK"=>$writer_nick,
												"WRITER_PW"=>$en_writer_pw,
												"TITLE"=>$title,
												"REF_IP"=>$ref_ip,
												"CONTENTS"=>$contents,
												"SECRET_TF"=>$secret_tf,
												"UP_DATE"=>$str_reg_date);
		
		}


		$result = updateBoard($conn, $arr_data, $b, $bn);

		$result = "T";
		$msg = "";
		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "ONLINE_UPDATE") {
	
		$on_title		= $_POST['on_title']!=''?$_POST['on_title']:$_GET['on_title'];
		$on_info		= $_POST['on_info']!=''?$_POST['on_info']:$_GET['on_info'];

		$arr_data = array("ON_TITLE"=>$on_title,
											"ON_INFO"=>$on_info);


		$result = updateOnline($conn, $arr_data, "1");

		$result = "T";
		$msg = "수정 되었습니다.";
		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);
		

	}

	db_close($conn);
?>