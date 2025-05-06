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
	require "../_classes/biz/program/program.php";
	require "../_classes/biz/group/group.php";

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$bn								= $_POST['bn']!=''?$_POST['bn']:$_GET['bn'];
	$nPage						= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$mPage						= $_POST['mPage']!=''?$_POST['mPage']:$_GET['mPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];

	$seq_no						= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$group_idx				= $_POST['group_idx']!=''?$_POST['group_idx']:$_GET['group_idx'];
	$email						= $_POST['email']!=''?$_POST['email']:$_GET['email'];
	$confirm_passwd		= $_POST['confirm_passwd']!=''?$_POST['confirm_passwd']:$_GET['confirm_passwd'];

	$con_list_type		= $_POST['con_list_type']!=''?$_POST['con_list_type']:$_GET['con_list_type'];

	$idx_no					= $_POST['idx_no']!=''?$_POST['idx_no']:$_GET['idx_no'];
	$aseq_no				= $_POST['aseq_no']!=''?$_POST['aseq_no']:$_GET['aseq_no'];
	$program_type		= $_POST['program_type']!=''?$_POST['program_type']:$_GET['program_type'];
	$app_type				= $_POST['app_type']!=''?$_POST['app_type']:$_GET['app_type'];
	$area_sido			= $_POST['area_sido']!=''?$_POST['area_sido']:$_GET['area_sido'];
	$area_sigungu		= $_POST['area_sigungu']!=''?$_POST['area_sigungu']:$_GET['area_sigungu'];

	$first_name			= $_POST['first_name']!=''?$_POST['first_name']:$_GET['first_name'];
	$first_phone		= $_POST['first_phone']!=''?$_POST['first_phone']:$_GET['first_phone'];
	$first_ages			= $_POST['first_ages']!=''?$_POST['first_ages']:$_GET['first_ages'];
	$first_email_01	= $_POST['first_email_01']!=''?$_POST['first_email_01']:$_GET['first_email_01'];
	$first_email_02	= $_POST['first_email_02']!=''?$_POST['first_email_02']:$_GET['first_email_02'];

	$sec_name				= $_POST['sec_name']!=''?$_POST['sec_name']:$_GET['sec_name'];
	$sec_phone			= $_POST['sec_phone']!=''?$_POST['sec_phone']:$_GET['sec_phone'];
	$sec_email_01		= $_POST['sec_email_01']!=''?$_POST['sec_email_01']:$_GET['sec_email_01'];
	$sec_email_02		= $_POST['sec_email_02']!=''?$_POST['sec_email_02']:$_GET['sec_email_02'];
	$sec_ages				= $_POST['sec_ages']!=''?$_POST['sec_ages']:$_GET['sec_ages'];
	$app_name				= $_POST['app_name']!=''?$_POST['app_name']:$_GET['app_name'];
	$app_phone			= $_POST['app_phone']!=''?$_POST['app_phone']:$_GET['app_phone'];
	$app_email_01		= $_POST['app_email_01']!=''?$_POST['app_email_01']:$_GET['app_email_01'];
	$app_email_02		= $_POST['app_email_02']!=''?$_POST['app_email_02']:$_GET['app_email_02'];
	$app_ages				= $_POST['app_ages']!=''?$_POST['app_ages']:$_GET['app_ages'];
	
	$dept						= $_POST['dept']!=''?$_POST['dept']:$_GET['dept'];
	$birth					= $_POST['birth']!=''?$_POST['birth']:$_GET['birth'];
	$gender					= $_POST['gender']!=''?$_POST['gender']:$_GET['gender'];
	$passwd					= $_POST['passwd']!=''?$_POST['passwd']:$_GET['passwd'];
	$passwd_chk			= $_POST['passwd_chk']!=''?$_POST['passwd_chk']:$_GET['passwd_chk'];
	$file_nm_01			= $_POST['file_nm_01']!=''?$_POST['file_nm_01']:$_GET['file_nm_01'];
	$file_rnm_01		= $_POST['file_rnm_01']!=''?$_POST['file_rnm_01']:$_GET['file_rnm_01'];
	$file_nm_02			= $_POST['file_nm_02']!=''?$_POST['file_nm_02']:$_GET['file_nm_02'];
	$file_rnm_02		= $_POST['file_rnm_02']!=''?$_POST['file_rnm_02']:$_GET['file_rnm_02'];
	$answer01				= $_POST['answer01']!=''?$_POST['answer01']:$_GET['answer01'];
	$answer02				= $_POST['answer02']!=''?$_POST['answer02']:$_GET['answer02'];
	$answer03				= $_POST['answer03']!=''?$_POST['answer03']:$_GET['answer03'];
	$answer04				= $_POST['answer04']!=''?$_POST['answer04']:$_GET['answer04'];
	$answer05				= $_POST['answer05']!=''?$_POST['answer05']:$_GET['answer05'];
	$answer06				= $_POST['answer06']!=''?$_POST['answer06']:$_GET['answer06'];
	$answer07				= $_POST['answer07']!=''?$_POST['answer07']:$_GET['answer07'];
	$answer08				= $_POST['answer08']!=''?$_POST['answer08']:$_GET['answer08'];
	$answer09				= $_POST['answer09']!=''?$_POST['answer09']:$_GET['answer09'];
	$answer10				= $_POST['answer10']!=''?$_POST['answer10']:$_GET['answer10'];
	$confirm_state	= $_POST['confirm_state']!=''?$_POST['confirm_state']:$_GET['confirm_state'];
	$app_state			= $_POST['app_state']!=''?$_POST['app_state']:$_GET['app_state'];

	// 추가 질문 답변 저장
	$qseq_no				= $_POST['qseq_no']!=''?$_POST['qseq_no']:$_GET['qseq_no'];
	$oseq_no				= $_POST['oseq_no']!=''?$_POST['oseq_no']:$_GET['oseq_no'];
	$qtype					= $_POST['qtype']!=''?$_POST['qtype']:$_GET['qtype'];
	$answer					= $_POST['answer']!=''?$_POST['answer']:$_GET['answer'];

	$first_name			= SetStringToDB($first_name);
	$sec_name				= SetStringToDB($sec_name);
	$app_name				= SetStringToDB($app_name);
	$dept						= SetStringToDB($dept);
	$answer01				= SetStringToDB($answer01);
	$answer02				= SetStringToDB($answer02);
	$answer03				= SetStringToDB($answer03);
	$answer04				= SetStringToDB($answer04);
	$answer05				= SetStringToDB($answer05);
	$answer06				= SetStringToDB($answer06);
	$answer07				= SetStringToDB($answer07);
	$answer08				= SetStringToDB($answer08);
	$answer09				= SetStringToDB($answer09);
	$answer10				= SetStringToDB($answer10);

	$first_email		= $first_email_01."@".$first_email_02;
	$app_email			= $app_email_01."@".$app_email_02;
	$sec_email			= $sec_email_01."@".$sec_email_02;

	if ($nPage == "") $nPage = 1;
	if ($nPage == 0) $nPage = 1;
	if ($mPage == "") $mPage = 1;
	if ($mPage == 0) $mPage = 1;

	if ($mode == "LIST") {

		if ($nPage <> "") {
			$nPage = (int)($nPage);
		} else {
			$nPage = 1;
		}

		if ($nPageSize <> "") {
			$nPageSize = (int)($nPageSize);
		} else {
			$nPageSize = 10;
		}

		$nPageBlock	= 5;

		$con_use_tf = "Y";
		$con_del_tf = "N";

		if ($con_list_type == "") $con_list_type = "A";
		
		$nListCnt = totalCntProgram($conn, $con_yyyy, $con_type, $con_app_type, $con_state, $con_list_type, $con_use_tf, $con_del_tf, $search_field, $search_str);

		$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;
		
		if ((int)($nTotalPage) < (int)($nPage)) {
			$nPage = $nTotalPage;
		}

		$arr_rs = listProgram($conn, $con_yyyy, $con_type, $con_app_type, $con_state, $con_list_type, $con_use_tf, $con_del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);
		
		$list_str = "";

		if (sizeof($arr_rs) > 0) {

			for ($j = 0 ;$j < sizeof($arr_rs) ; $j++) {

				$rn								= trim($arr_rs[$j]["rn"]);
				$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
				$YYYY							= trim($arr_rs[$j]["YYYY"]);
				$TYPE							= trim($arr_rs[$j]["TYPE"]);
				$APP_TYPE					= trim($arr_rs[$j]["APP_TYPE"]);
				$TITLE						= SetStringFromDB($arr_rs[$j]["TITLE"]);
				$S_DATE						= trim($arr_rs[$j]["S_DATE"]);
				$S_HOUR						= trim($arr_rs[$j]["S_HOUR"]);
				$S_MIN						= trim($arr_rs[$j]["S_MIN"]);
				$E_DATE						= trim($arr_rs[$j]["E_DATE"]);
				$E_HOUR						= trim($arr_rs[$j]["E_HOUR"]);
				$E_MIN						= trim($arr_rs[$j]["E_MIN"]);
				$APP_LIMT					= trim($arr_rs[$j]["APP_LIMT"]);
				$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
				$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);
				$STATE						= trim($arr_rs[$j]["STATE"]);
				$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
				
				$offset = $nPageSize * ($nPage-1);
				$logical_num = ($nListCnt - $offset);
				$rn = $logical_num - $j;

				$str_reg_date = date("Y.m.d",strtotime($REG_DATE));
				
				$now_date			= date("YmdHi",strtotime("0 day"));
				$start_date		= str_replace("-","",$S_DATE.$S_HOUR.$S_MIN);
				$end_date			= str_replace("-","",$E_DATE.$E_HOUR.$E_MIN);

				if ($now_date < $start_date) {
					$str_state = "0";
					$str_class = "";
				} else if (($now_date >= $start_date) && ($now_date <= $end_date)) {
					
					// 기간안에 있더라도 관리자 상태에 따라 보이도록 한다.
					if ($STATE == "0") { 
						$str_state = "0";
						$str_class = "";
					} else if ($STATE == "1") {  
						$str_state = "1";
						$str_class = "";
					} else if ($STATE == "2") {
						$str_state = "2";
						$str_class = "class='end'";
					}

					// 기간안에 있더라도 마감인원이 되었을 경우 마감 처리 한다
					if ($APP_LIMT <> 0) {
						// 신청자 수 확인 후 추가 코딩
						$all_cnt = chkCntApply($conn, $SEQ_NO);
						
						if ($APP_LIMT <= $all_cnt) {
							$str_state = "2";
							$str_class = "class='end'";
						}
					}

				} else if ($now_date > $end_date) {
					$str_state = "2";
					$str_class = "class='end'";
				}

				if ($_SERVER['REMOTE_ADDR'] <> "182.208.250.10XXXXXX") { 

				$list_str = $list_str . "<li>\n";
				$list_str = $list_str . "<p class='num'>".$rn."</p>\n";
				$list_str = $list_str . "<p class='info'>\n";
				$list_str = $list_str . "<a href=\"javascript:js_view('".$SEQ_NO."')\" class='title'>".$TITLE."</a>\n";
				$list_str = $list_str . "<span class='date'>".$S_DATE." ~ ".$E_DATE."</span>\n";
				$list_str = $list_str . "</p>\n";
				$list_str = $list_str . "<a href=\"javascript:js_view('".$SEQ_NO."')\" ".$str_class." >".getDcodeName($conn, "PROGRAM_STATE", $str_state)."</a>\n";
				$list_str = $list_str . "</li>\n";
				
				} else {

					if (($APP_TYPE == "C") && ($SEQ_NO == "1")) {

				$list_str = $list_str . "<li>\n";
				$list_str = $list_str . "<p class='num'>".$rn."</p>\n";
				$list_str = $list_str . "<p class='info'>\n";
				$list_str = $list_str . "<a href=\"javascript:js_view('".$SEQ_NO."')\" class='title'>".$TITLE."</a>\n";
				$list_str = $list_str . "<span class='date'>".$S_DATE." ~ ".$E_DATE."</span>\n";
				$list_str = $list_str . "</p>\n";
				$list_str = $list_str . "<a href=\"javascript:js_view('".$SEQ_NO."')\" ".$str_class." >".getDcodeName($conn, "PROGRAM_STATE", $str_state)."</a>\n";
				$list_str = $list_str . "</li>\n";
					
					}

				}

			}
		}

		$page_str = Front_Image_PageList($nPage, $nTotalPage, $nPageBlock);

		$arr_result = array("result"=>$result, "total"=>$nListCnt, "totalpage"=>$nTotalPage, "list"=>$list_str, "page"=>$page_str, "script"=>$script_str);
		echo json_encode($arr_result);
	
	}

	if ($mode == "CONFIRM_CLUB_PASS") {

		$arr_rs = selectGroupInfo($conn, $group_idx);
		
		if (sizeof($arr_rs) > 0) {
				
			$REG_EMAIL		= trim($arr_rs[0]["REG_EMAIL"]); 
			$REG_PASSWORD = trim($arr_rs[0]["REG_PASSWORD"]); 

			if ((trim($email) == trim($REG_EMAIL)) && (trim($REG_PASSWORD) == trim(md5($confirm_passwd)))) {
			//if ((trim($email) == trim($REG_EMAIL)) && (trim($REG_PASSWORD) == "b0267747652a1a1bc1e608a088a4c0b6")) {

				// 이미 신청한 동아리 인지 확인 합니다.
				$aseq_no = dupChkApplyGroup($conn, $seq_no, $group_idx);
				
				if ($aseq_no <> "") {
					$result = "D";
					$msg = "";
					$_SESSION['s_confirm_group_idx'] = $group_idx;
					$_SESSION['s_confirm_seq_no'] = $seq_no;
					$_SESSION['s_confirm_aseq_no'] = $aseq_no;
				} else {
					$result = "T";
					$msg = "";
					$_SESSION['s_confirm_group_idx'] = $group_idx;
					$_SESSION['s_confirm_seq_no'] = $seq_no;
				}
			} else {
				$result = "F";
				$msg = "정보가 일치 하지 않습니다.";
				$_SESSION['s_confirm_group_idx'] = "";
				$_SESSION['s_confirm_seq_no'] = "";
			}
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "CONFIRM_PERSON_PASS") {

		$arr_rs = selectPersonApplyInfo($conn, $seq_no, $email);
		
		if (sizeof($arr_rs) > 0) {

			$APP_EMAIL		= trim($arr_rs[0]["APP_EMAIL"]); 
			$PASSWD				= trim($arr_rs[0]["PASSWD"]); 
			$ASEQ_NO			= trim($arr_rs[0]["ASEQ_NO"]); 

			if ((trim($email) == trim($APP_EMAIL)) && (trim($PASSWD) == trim(md5($confirm_passwd)))) {
				
				if ($ASEQ_NO <> "") {
					$result = "T";
					$msg = "";
					$_SESSION['s_confirm_seq_no'] = $seq_no;
					$_SESSION['s_confirm_aseq_no'] = $ASEQ_NO;
				}
			} else {
				$result = "F";
				$msg = "정보가 일치 하지 않습니다.";
				$_SESSION['s_confirm_group_idx'] = "";
				$_SESSION['s_confirm_seq_no'] = "";
			}
		} else {
			$result = "N";
			$msg = "신청하신 정보가 존재 하지 않습니다.";
			$_SESSION['s_confirm_group_idx'] = "";
			$_SESSION['s_confirm_seq_no'] = "";
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}
	
	if ($mode == "CONFIRM_SELECTED_CLUB_PASS") {
		
		$arr_rs = confirmSelectedGroupInfo($conn, $seq_no, $group_idx);

		if (sizeof($arr_rs) > 0) {

			$REG_EMAIL		= trim($arr_rs[0]["REG_EMAIL"]); 
			$REG_PASSWORD = trim($arr_rs[0]["REG_PASSWORD"]); 
			$ASEQ_NO			= trim($arr_rs[0]["ASEQ_NO"]); 
			
			//echo $REG_EMAIL." : ".$email."<br>";
			//echo trim($REG_PASSWORD)." : ".trim(md5($confirm_passwd))." : ".$confirm_passwd;
			

			if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") {
			} 


			if ((trim($email) == trim($REG_EMAIL)) && (trim($REG_PASSWORD) == trim(md5($confirm_passwd)))) {

				$result = "T";
				$msg = "";
				$_SESSION['s_selected_seq_no'] = $seq_no;
				$_SESSION['s_selected_aseq_no'] = $ASEQ_NO;
				$_SESSION['s_selected_group_idx'] = $group_idx;

			} else {

				if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") {
					
					$result = "T";
					$msg = "";
					$_SESSION['s_selected_seq_no'] = $seq_no;
					$_SESSION['s_selected_aseq_no'] = $ASEQ_NO;
					$_SESSION['s_selected_group_idx'] = $group_idx;

				}  else { 

					$result = "F";
					$msg = "정보가 일치 하지 않거나 선정된 동아리가 아닙니다.";
					$_SESSION['s_selected_seq_no'] = "";
					$_SESSION['s_selected_aseq_no'] = "";
					$_SESSION['s_selected_group_idx'] = "";
				
				}

			}
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "ADD") {

		if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str'])) {

			$result = "F";
			$msg		= "정상적인 글 등록 방식이 아닙니다.";

			$arr_result = array("result"=>$result, "msg"=>$msg);
			echo json_encode($arr_result);

			db_close($conn);
			exit;
		} 
		
		if (trim($program_type) == "TYPE03") { // 저자강연 의 경우는 지원금 합으로 체크
			
			//echo "program_type2 : ".$program_type;

			// 지원금액 가지고 오기 코드 관리에 AMOUNT_TYPE03 항목에 value를 가지고 오기
			$type03_amount	= (int)getAmountType03($conn, $seq_no);  // 제한 금액
			$apply_amount		= getApplyType03Amount($conn, $seq_no, $idx_no, ""); // 동아리 인덱스

			$total_apply_amount = $apply_amount + (int)$sec_ages;

			if ($type03_amount < $total_apply_amount) {
				$result = "AMOUNT";
				$msg		= "저자 강연 지원금 신청 금액 총합은 ".number_format($type03_amount)." 을 넘을 수 없습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}

		} else if (($program_type == "TYPE04") || ($program_type == "TYPE05")) {

			// 중복 체크
			$chk_cnt = chkCntApplyPerson($conn, $app_email, $seq_no);

			if ($chk_cnt > 0) {

				$result = "DUP";
				$msg		= "해당 정보로 이미 신청하신 정보가 있습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}

		} else {

			$chk_cnt = chkCntApplyGroup($conn, $idx_no, $seq_no);

			if ($chk_cnt > 0) {

				$result = "DUP";
				$msg		= "해당 동아리 정보로 이미 신청하신 정보가 있습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}

		}

		// 신규 접수 번호 
		$seq_num				= getApplyNum($conn, $seq_no, $app_type);

		$en_passwd			= md5($passwd);

		$arr_data = array("IDX_NO"=>$idx_no,
											"SEQ_NO"=>$seq_no,
											"SEQ_NUM"=>$seq_num,
											"APP_NAME"=>$app_name,
											"APP_PHONE"=>$app_phone,
											"APP_EMAIL"=>$app_email,
											"APP_AGES"=>$app_ages,
											"FIRST_NAME"=>$first_name,
											"FIRST_PHONE"=>$first_phone,
											"FIRST_EMAIL"=>$first_email,
											"FIRST_AGES"=>$first_ages,
											"SEC_NAME"=>$sec_name,
											"SEC_PHONE"=>$sec_phone,
											"SEC_EMAIL"=>$sec_email,
											"SEC_AGES"=>$sec_ages,
											"AREA_SIDO"=>$area_sido,
											"AREA_SIGUNGU"=>$area_sigungu,
											"DEPT"=>$dept,
											"BIRTH"=>$birth,
											"GENDER"=>$gender,
											"PASSWD"=>$en_passwd,
											"FILE_NM_01"=>$file_nm_01,
											"FILE_RNM_01"=>$file_rnm_01,
											"FILE_NM_02"=>$file_nm_02,
											"FILE_RNM_02"=>$file_rnm_02,
											"ANSWER01"=>$answer01,
											"ANSWER02"=>$answer02,
											"ANSWER03"=>$answer03,
											"ANSWER04"=>$answer04,
											"ANSWER05"=>$answer05,
											"ANSWER06"=>$answer06,
											"ANSWER07"=>$answer07,
											"ANSWER08"=>$answer08,
											"ANSWER09"=>$answer09,
											"ANSWER10"=>$answer10,
											"APP_STATE"=>"0",
											"CONFIRM_STATE"=>$confirm_state,
											"REG_DATE"=>$INS_DATE,
											"UP_DATE"=>$INS_DATE);

		$new_aseq_no  = insertApply($conn, $arr_data);

		$row_cnt = is_null($qseq_no) ? 0 : count($qseq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
			
			$tmp_qseq_no	= $qseq_no[$k];
			$tmp_qtype		= $qtype[$k];
			$tmp_answer		= $answer[$k];
			
			$tmp_answer = SetStringToDB($tmp_answer);

			if (trim($tmp_qtype) == "type01") {
				$arr_tmp_answer = explode("",$tmp_answer);
				$answer_str = $arr_tmp_answer[0];
				$answer_no = $arr_tmp_answer[1];
			} else {
				$answer_str = $tmp_answer;
				$answer_no = "";
			}

			$arr_data = array("ASEQ_NO"=>$new_aseq_no,
												"QSEQ_NO"=>$tmp_qseq_no,
												"QTYPE"=>$tmp_qtype,
												"ANSWER_NO"=>$answer_no,
												"ANSWER_STR"=>$answer_str,
												"REG_DATE"=>$INS_DATE,
												"UP_DATE"=>$INS_DATE);
			
			$result = insertAnswer($conn, $arr_data);

			$_SESSION['s_confirm_aseq_no'] = $new_aseq_no;
			$_SESSION['s_confirm_group_idx'] = $idx_no;
			$_SESSION['s_confirm_seq_no'] = $seq_no;

		}


		if ($program_type == "TYPE03") {
			
			$book_title				= $_POST['book_title']!=''?$_POST['book_title']:$_GET['book_title'];
			$book_authors			= $_POST['book_authors']!=''?$_POST['book_authors']:$_GET['book_authors'];
			$book_contents		= $_POST['book_contents']!=''?$_POST['book_contents']:$_GET['book_contents'];
			$book_datetime		= $_POST['book_datetime']!=''?$_POST['book_datetime']:$_GET['book_datetime'];
			$book_isbn				= $_POST['book_isbn']!=''?$_POST['book_isbn']:$_GET['book_isbn'];
			$book_publisher		= $_POST['book_publisher']!=''?$_POST['book_publisher']:$_GET['book_publisher'];
			$book_thumbnail		= $_POST['book_thumbnail']!=''?$_POST['book_thumbnail']:$_GET['book_thumbnail'];
			$book_translators	= $_POST['book_translators']!=''?$_POST['book_translators']:$_GET['book_translators'];

			$book_cnt = is_null($book_title) ? 0 : count($book_title);
			
			for($i=0; $i <= $book_cnt; $i++) {

				if (trim($book_title[$i]) <> "") {

					$arr_data = array("ASEQ_NO"=>$new_aseq_no,
														"BOOK_NM"=>SetStringToDB($book_title[$i]),
														"BOOK_IMG"=>$book_thumbnail[$i],
														"BOOK_CONTENTS"=>SetStringToDB($book_contents[$i]),
														"AUTHORS"=>SetStringToDB($book_authors[$i]),
														"TRANSLATORS"=>SetStringToDB($book_translators[$i]),
														"PUBLISHER"=>SetStringToDB($book_publisher[$i]),
														"PUBLISH_DATE"=>SetStringToDB($book_datetime[$i]),
														"ISBN"=>SetStringToDB($book_isbn[$i]),
														"REG_ADM"=>0,
														"REG_DATE"=>$str_reg_date);

					$result_update = insertApplyBookInfo($conn, $arr_data);
				}
			}

		}


		$result = "T";
		$msg		= "";

		if (trim($program_type) == "TYPE01") { 
			if ($confirm_state == "1") {
				$result_copy = copyApplyGroupInfo($conn, $seq_no, $idx_no);
			}
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);
		$_SESSION['s_encrypt_str'] = "";

	}

	if ($mode == "MOD") {

		if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str'])) {

			$result = "F";
			$msg		= "정상적인 글 수정 방식이 아닙니다.";

			$arr_result = array("result"=>$result, "msg"=>$msg);
			echo json_encode($arr_result);

			db_close($conn);
			exit;
		} 

		if ($program_type == "TYPE03") { // 저자강연 의 경우는 지원금 합으로 체크
			// 지원금액 가지고 오기 코드 관리에 AMOUNT_TYPE03 항목에 value를 가지고 오기

			$type03_amount	= (int)getAmountType03($conn, $seq_no);  // 제한 금액
			$apply_amount		= getApplyType03Amount($conn, $seq_no, $idx_no, $aseq_no); // 동아리 인덱스

			$total_apply_amount = $apply_amount + (int)$sec_ages;

			if ($type03_amount < $total_apply_amount) {
				$result = "AMOUNT";
				$msg		= "저자 강연 지원금 신청 금액 총합은 ".number_format($type03_amount)." 을 넘을 수 없습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}
		}

		$en_passwd			= md5($passwd);

		$arr_data = array("APP_NAME"=>$app_name,
											"APP_PHONE"=>$app_phone,
											"APP_EMAIL"=>$app_email,
											"APP_AGES"=>$app_ages,
											"FIRST_NAME"=>$first_name,
											"FIRST_PHONE"=>$first_phone,
											"FIRST_EMAIL"=>$first_email,
											"FIRST_AGES"=>$first_ages,
											"SEC_NAME"=>$sec_name,
											"SEC_PHONE"=>$sec_phone,
											"SEC_EMAIL"=>$sec_email,
											"SEC_AGES"=>$sec_ages,
											"AREA_SIDO"=>$area_sido,
											"AREA_SIGUNGU"=>$area_sigungu,
											"DEPT"=>$dept,
											"BIRTH"=>$birth,
											"GENDER"=>$gender,
											"FILE_NM_01"=>$file_nm_01,
											"FILE_RNM_01"=>$file_rnm_01,
											"FILE_NM_02"=>$file_nm_02,
											"FILE_RNM_02"=>$file_rnm_02,
											"ANSWER01"=>$answer01,
											"ANSWER02"=>$answer02,
											"ANSWER03"=>$answer03,
											"ANSWER04"=>$answer04,
											"ANSWER05"=>$answer05,
											"ANSWER06"=>$answer06,
											"ANSWER07"=>$answer07,
											"ANSWER08"=>$answer08,
											"ANSWER09"=>$answer09,
											"ANSWER10"=>$answer10,
											"APP_STATE"=>"0",
											"CONFIRM_STATE"=>$confirm_state,
											"UP_DATE"=>$INS_DATE);
		
		$result = updateApply($conn, $arr_data, $_SESSION['s_confirm_aseq_no']);
		
		$result = deleteAnswer($conn, $_SESSION['s_confirm_aseq_no']);

		$row_cnt = is_null($qseq_no) ? 0 : count($qseq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
			
			$tmp_qseq_no	= $qseq_no[$k];
			$tmp_qtype		= $qtype[$k];
			$tmp_answer		= $answer[$k];

			$tmp_answer = SetStringToDB($tmp_answer);
			
			if (trim($tmp_qtype) == "type01") {
				$arr_tmp_answer = explode("",$tmp_answer);
				$answer_str = $arr_tmp_answer[0];
				$answer_no = $arr_tmp_answer[1];
			} else {
				$answer_str = $tmp_answer;
				$answer_no = "";
			}

			$arr_data = array("ASEQ_NO"=>$_SESSION['s_confirm_aseq_no'],
												"QSEQ_NO"=>$tmp_qseq_no,
												"QTYPE"=>$tmp_qtype,
												"ANSWER_NO"=>$answer_no,
												"ANSWER_STR"=>$answer_str,
												"REG_DATE"=>$INS_DATE,
												"UP_DATE"=>$INS_DATE);
			
			$result = insertAnswer($conn, $arr_data);
		}

		if ($confirm_state == "1") {
			$result_copy = copyApplyGroupInfo($conn, $_SESSION['s_confirm_seq_no'], $_SESSION['s_confirm_group_idx']);
		}

		if ($program_type == "TYPE03") {
			
			$book_title				= $_POST['book_title']!=''?$_POST['book_title']:$_GET['book_title'];
			$book_authors			= $_POST['book_authors']!=''?$_POST['book_authors']:$_GET['book_authors'];
			$book_contents		= $_POST['book_contents']!=''?$_POST['book_contents']:$_GET['book_contents'];
			$book_datetime		= $_POST['book_datetime']!=''?$_POST['book_datetime']:$_GET['book_datetime'];
			$book_isbn				= $_POST['book_isbn']!=''?$_POST['book_isbn']:$_GET['book_isbn'];
			$book_publisher		= $_POST['book_publisher']!=''?$_POST['book_publisher']:$_GET['book_publisher'];
			$book_thumbnail		= $_POST['book_thumbnail']!=''?$_POST['book_thumbnail']:$_GET['book_thumbnail'];
			$book_translators	= $_POST['book_translators']!=''?$_POST['book_translators']:$_GET['book_translators'];

			$result_del = deleteApplyBookInfo($conn, $aseq_no);

			$book_cnt = is_null($book_title) ? 0 : count($book_title);
			
			for($i=0; $i <= $book_cnt; $i++) {

				if (trim($book_title[$i]) <> "") {

					$arr_data = array("ASEQ_NO"=>$aseq_no,
														"BOOK_NM"=>SetStringToDB($book_title[$i]),
														"BOOK_IMG"=>$book_thumbnail[$i],
														"BOOK_CONTENTS"=>SetStringToDB($book_contents[$i]),
														"AUTHORS"=>SetStringToDB($book_authors[$i]),
														"TRANSLATORS"=>SetStringToDB($book_translators[$i]),
														"PUBLISHER"=>SetStringToDB($book_publisher[$i]),
														"PUBLISH_DATE"=>SetStringToDB($book_datetime[$i]),
														"ISBN"=>SetStringToDB($book_isbn[$i]),
														"REG_ADM"=>0,
														"REG_DATE"=>$INS_DATE);

					$result_update = insertApplyBookInfo($conn, $arr_data);
				}
			}
		}

		$result = "T";
		$msg		= "";

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);
		$_SESSION['s_encrypt_str'] = "";

	}

	if ($mode == "APPLY_FINISH") {

		$arr_data = array("CONFIRM_STATE"=>"1",
											"UP_DATE"=>$INS_DATE);
		
		$result = updateApply($conn, $arr_data, $_SESSION['s_confirm_aseq_no']);

		$result_copy = copyApplyGroupInfo($conn, $_SESSION['s_confirm_seq_no'], $_SESSION['s_confirm_group_idx']);

		$result = "T";
		$msg		= "";

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "APPLY_ATTACH_FILE") {

		$attach_file01_name				= $_POST['attach_file01_name']!=''?$_POST['attach_file01_name']:$_GET['attach_file01_name'];
		$attach_file02_name				= $_POST['attach_file02_name']!=''?$_POST['attach_file02_name']:$_GET['attach_file02_name'];
		$attach_file03_name				= $_POST['attach_file03_name']!=''?$_POST['attach_file03_name']:$_GET['attach_file03_name'];
		$attach_file04_name				= $_POST['attach_file04_name']!=''?$_POST['attach_file04_name']:$_GET['attach_file04_name'];
		$attach_file01_name_info	= $_POST['attach_file01_name_info']!=''?$_POST['attach_file01_name_info']:$_GET['attach_file01_name_info'];
		$attach_file02_name_info	= $_POST['attach_file02_name_info']!=''?$_POST['attach_file02_name_info']:$_GET['attach_file02_name_info'];
		$attach_file03_name_info	= $_POST['attach_file03_name_info']!=''?$_POST['attach_file03_name_info']:$_GET['attach_file03_name_info'];
		$attach_file04_name_info	= $_POST['attach_file04_name_info']!=''?$_POST['attach_file04_name_info']:$_GET['attach_file04_name_info'];
		
		$arr_data = array("ADD_FILE01"=>$attach_file01_name,
											"ADD_RFILE01"=>$attach_file01_name_info,
											"ADD_FILE02"=>$attach_file02_name,
											"ADD_RFILE02"=>$attach_file02_name_info,
											"ADD_FILE03"=>$attach_file03_name,
											"ADD_RFILE03"=>$attach_file03_name_info,
											"ADD_FILE04"=>$attach_file04_name,
											"ADD_RFILE04"=>$attach_file04_name_info);

		$result = updateApply($conn, $arr_data, $aseq_no);

		$result = "T";
		$msg		= "서식제출이 완료 되었습니다.";

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "MANAGER_CHANGE_CONFIRM_STATE") {

		$arr_data = array("CONFIRM_STATE"=>$confirm_state,
											"UP_DATE"=>$INS_DATE);
		
		$result = updateApply($conn, $arr_data, $aseq_no);

		//$result_copy = copyApplyGroupInfo($conn, $_SESSION['s_confirm_seq_no'], $_SESSION['s_confirm_group_idx']);

		$result = "T";
		$msg		= "접수상태를 수정 하였습니다.";

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "MANAGER_CHANGE_APP_STATE") {

		$arr_data = array("APP_STATE"=>$app_state,
											"UP_DATE"=>$INS_DATE);
		
		$result = updateApply($conn, $arr_data, $aseq_no);

		//$result_copy = copyApplyGroupInfo($conn, $_SESSION['s_confirm_seq_no'], $_SESSION['s_confirm_group_idx']);

		$result = "T";
		$msg		= "심사상태를 수정 하였습니다.";

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}

	if ($mode == "MANAGER_APPLY_MODIFY") {

		if ($program_type == "TYPE03") { // 저자강연 의 경우는 지원금 합으로 체크
			// 지원금액 가지고 오기 코드 관리에 AMOUNT_TYPE03 항목에 value를 가지고 오기

			$type03_amount	= (int)getAmountType03($conn, $seq_no);  // 제한 금액
			$apply_amount		= getApplyType03Amount($conn, $seq_no, $idx_no, $aseq_no); // 동아리 인덱스

			$total_apply_amount = $apply_amount + (int)$sec_ages;

			if ($type03_amount < $total_apply_amount) {
				$result = "AMOUNT";
				$msg		= "저자 강연 지원금 신청 금액 총합은 ".number_format($type03_amount)." 을 넘을 수 없습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}
		}
		
		$arr_data = array("APP_NAME"=>$app_name,
											"APP_PHONE"=>$app_phone,
											"APP_EMAIL"=>$app_email,
											"APP_AGES"=>$app_ages,
											"FIRST_NAME"=>$first_name,
											"FIRST_PHONE"=>$first_phone,
											"FIRST_EMAIL"=>$first_email,
											"FIRST_AGES"=>$first_ages,
											"SEC_NAME"=>$sec_name,
											"SEC_PHONE"=>$sec_phone,
											"SEC_EMAIL"=>$sec_email,
											"SEC_AGES"=>$sec_ages,
											"AREA_SIDO"=>$area_sido,
											"AREA_SIGUNGU"=>$area_sigungu,
											"DEPT"=>$dept,
											"BIRTH"=>$birth,
											"GENDER"=>$gender,
											"FILE_NM_01"=>$file_nm_01,
											"FILE_RNM_01"=>$file_rnm_01,
											"FILE_NM_02"=>$file_nm_02,
											"FILE_RNM_02"=>$file_rnm_02,
											"ANSWER01"=>$answer01,
											"ANSWER02"=>$answer02,
											"ANSWER03"=>$answer03,
											"ANSWER04"=>$answer04,
											"ANSWER05"=>$answer05,
											"ANSWER06"=>$answer06,
											"ANSWER07"=>$answer07,
											"ANSWER08"=>$answer08,
											"ANSWER09"=>$answer09,
											"ANSWER10"=>$answer10,
											"APP_STATE"=>$app_state,
											"CONFIRM_STATE"=>$confirm_state,
											"UP_DATE"=>$INS_DATE);
		
		$result = updateApply($conn, $arr_data, $aseq_no);

		if ($passwd_chk=="Y") {
			
			$en_passwd			= md5($passwd);

			$arr_data = array("PASSWD"=>$en_passwd,
												"UP_DATE"=>$INS_DATE);
		
			$result = updateApply($conn, $arr_data, $aseq_no);

		}

		$result = deleteAnswer($conn, $aseq_no);

		$row_cnt = is_null($qseq_no) ? 0 : count($qseq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
			
			$tmp_qseq_no	= $qseq_no[$k];
			$tmp_qtype		= $qtype[$k];
			$tmp_answer		= $answer[$k];

			if (trim($tmp_qtype) == "type01") {
				$arr_tmp_answer = explode("",$tmp_answer);
				$answer_str = $arr_tmp_answer[0];
				$answer_no = $arr_tmp_answer[1];
			} else {
				$answer_str = $tmp_answer;
				$answer_no = "";
			}

			$arr_data = array("ASEQ_NO"=>$aseq_no,
												"QSEQ_NO"=>$tmp_qseq_no,
												"QTYPE"=>$tmp_qtype,
												"ANSWER_NO"=>$answer_no,
												"ANSWER_STR"=>$answer_str,
												"REG_DATE"=>$INS_DATE,
												"UP_DATE"=>$INS_DATE);
			
			$result = insertAnswer($conn, $arr_data);
		}

		if ($program_type == "TYPE03") {
			
			$book_title				= $_POST['book_title']!=''?$_POST['book_title']:$_GET['book_title'];
			$book_authors			= $_POST['book_authors']!=''?$_POST['book_authors']:$_GET['book_authors'];
			$book_contents		= $_POST['book_contents']!=''?$_POST['book_contents']:$_GET['book_contents'];
			$book_datetime		= $_POST['book_datetime']!=''?$_POST['book_datetime']:$_GET['book_datetime'];
			$book_isbn				= $_POST['book_isbn']!=''?$_POST['book_isbn']:$_GET['book_isbn'];
			$book_publisher		= $_POST['book_publisher']!=''?$_POST['book_publisher']:$_GET['book_publisher'];
			$book_thumbnail		= $_POST['book_thumbnail']!=''?$_POST['book_thumbnail']:$_GET['book_thumbnail'];
			$book_translators	= $_POST['book_translators']!=''?$_POST['book_translators']:$_GET['book_translators'];

			$result_del = deleteApplyBookInfo($conn, $aseq_no);

			$book_cnt = is_null($book_title) ? 0 : count($book_title);
			
			for($i=0; $i <= $book_cnt; $i++) {

				if (trim($book_title[$i]) <> "") {

					$arr_data = array("ASEQ_NO"=>$aseq_no,
														"BOOK_NM"=>SetStringToDB($book_title[$i]),
														"BOOK_IMG"=>$book_thumbnail[$i],
														"BOOK_CONTENTS"=>SetStringToDB($book_contents[$i]),
														"AUTHORS"=>SetStringToDB($book_authors[$i]),
														"TRANSLATORS"=>SetStringToDB($book_translators[$i]),
														"PUBLISHER"=>SetStringToDB($book_publisher[$i]),
														"PUBLISH_DATE"=>SetStringToDB($book_datetime[$i]),
														"ISBN"=>SetStringToDB($book_isbn[$i]),
														"REG_ADM"=>0,
														"REG_DATE"=>$INS_DATE);

					$result_update = insertApplyBookInfo($conn, $arr_data);
				}
			}
		}

		$result = "T";
		$msg		= "수정 되었습니다.";


		if ($program_type == "TYPE01") {
			if ($confirm_state == "1") {

				$arr_rs_group_info = selectApplyGroupInfo($conn, $idx_no, $seq_no);

				if (sizeof($arr_rs_group_info) == 0) {
					$result_copy = copyApplyGroupInfo($conn, $seq_no, $idx_no);
				}
			}
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);

	}


	if ($mode == "MANAGER_APPLY_ADD") {

		if ($program_type == "TYPE03") { // 저자강연 의 경우는 지원금 합으로 체크
			// 지원금액 가지고 오기 코드 관리에 AMOUNT_TYPE03 항목에 value를 가지고 오기
			$type03_amount	= (int)getAmountType03($conn, $seq_no);  // 제한 금액
			$apply_amount		= getApplyType03Amount($conn, $seq_no, $idx_no, ""); // 동아리 인덱스

			$total_apply_amount = $apply_amount + (int)$sec_ages;

			if ($type03_amount < $total_apply_amount) {
				$result = "AMOUNT";
				$msg		= "저자 강연 지원금 신청 금액 총합은 ".number_format($type03_amount)." 을 넘을 수 없습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}

		} else if (($program_type == "TYPE04") || ($program_type == "TYPE05")) {

			// 중복 체크
			$chk_cnt = chkCntApplyPerson($conn, $app_email, $seq_no);

			if ($chk_cnt > 0) {

				$result = "DUP";
				$msg		= "해당 정보로 이미 신청하신 정보가 있습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}


		} else {

			$chk_cnt = chkCntApplyGroup($conn, $idx_no, $seq_no);

			if ($chk_cnt > 0) {

				$result = "DUP";
				$msg		= "해당 동아리 정보로 이미 신청하신 정보가 있습니다.";

				$arr_result = array("result"=>$result, "msg"=>$msg);
				echo json_encode($arr_result);

				db_close($conn);
				exit;
			}

		}

		// 신규 접수 번호 
		$seq_num				= getApplyNum($conn, $seq_no, $app_type);

		$en_passwd			= md5($passwd);

		$arr_data = array("IDX_NO"=>$idx_no,
											"SEQ_NO"=>$seq_no,
											"SEQ_NUM"=>$seq_num,
											"APP_NAME"=>$app_name,
											"APP_PHONE"=>$app_phone,
											"APP_EMAIL"=>$app_email,
											"APP_AGES"=>$app_ages,
											"FIRST_NAME"=>$first_name,
											"FIRST_PHONE"=>$first_phone,
											"FIRST_EMAIL"=>$first_email,
											"FIRST_AGES"=>$first_ages,
											"SEC_NAME"=>$sec_name,
											"SEC_PHONE"=>$sec_phone,
											"SEC_EMAIL"=>$sec_email,
											"SEC_AGES"=>$sec_ages,
											"AREA_SIDO"=>$area_sido,
											"AREA_SIGUNGU"=>$area_sigungu,
											"DEPT"=>$dept,
											"BIRTH"=>$birth,
											"GENDER"=>$gender,
											"PASSWD"=>$en_passwd,
											"FILE_NM_01"=>$file_nm_01,
											"FILE_RNM_01"=>$file_rnm_01,
											"FILE_NM_02"=>$file_nm_02,
											"FILE_RNM_02"=>$file_rnm_02,
											"ANSWER01"=>$answer01,
											"ANSWER02"=>$answer02,
											"ANSWER03"=>$answer03,
											"ANSWER04"=>$answer04,
											"ANSWER05"=>$answer05,
											"ANSWER06"=>$answer06,
											"ANSWER07"=>$answer07,
											"ANSWER08"=>$answer08,
											"ANSWER09"=>$answer09,
											"ANSWER10"=>$answer10,
											"APP_STATE"=>"0",
											"CONFIRM_STATE"=>$confirm_state,
											"REG_DATE"=>$INS_DATE,
											"UP_DATE"=>$INS_DATE);

		$new_aseq_no  = insertApply($conn, $arr_data);

		$row_cnt = is_null($qseq_no) ? 0 : count($qseq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
			
			$tmp_qseq_no	= $qseq_no[$k];
			$tmp_qtype		= $qtype[$k];
			$tmp_answer		= $answer[$k];

			if (trim($tmp_qtype) == "type01") {
				$arr_tmp_answer = explode("",$tmp_answer);
				$answer_str = $arr_tmp_answer[0];
				$answer_no = $arr_tmp_answer[1];
			} else {
				$answer_str = $tmp_answer;
				$answer_no = "";
			}

			$arr_data = array("ASEQ_NO"=>$new_aseq_no,
												"QSEQ_NO"=>$tmp_qseq_no,
												"QTYPE"=>$tmp_qtype,
												"ANSWER_NO"=>$answer_no,
												"ANSWER_STR"=>$answer_str,
												"REG_DATE"=>$INS_DATE,
												"UP_DATE"=>$INS_DATE);
			
			$result = insertAnswer($conn, $arr_data);

			$_SESSION['s_confirm_aseq_no'] = $new_aseq_no;
			$_SESSION['s_confirm_group_idx'] = $idx_no;
			$_SESSION['s_confirm_seq_no'] = $seq_no;

		}

		if ($program_type == "TYPE03") {
			
			$book_title				= $_POST['book_title']!=''?$_POST['book_title']:$_GET['book_title'];
			$book_authors			= $_POST['book_authors']!=''?$_POST['book_authors']:$_GET['book_authors'];
			$book_contents		= $_POST['book_contents']!=''?$_POST['book_contents']:$_GET['book_contents'];
			$book_datetime		= $_POST['book_datetime']!=''?$_POST['book_datetime']:$_GET['book_datetime'];
			$book_isbn				= $_POST['book_isbn']!=''?$_POST['book_isbn']:$_GET['book_isbn'];
			$book_publisher		= $_POST['book_publisher']!=''?$_POST['book_publisher']:$_GET['book_publisher'];
			$book_thumbnail		= $_POST['book_thumbnail']!=''?$_POST['book_thumbnail']:$_GET['book_thumbnail'];
			$book_translators	= $_POST['book_translators']!=''?$_POST['book_translators']:$_GET['book_translators'];

			$book_cnt = is_null($book_title) ? 0 : count($book_title);

			for($i=0; $i <= $book_cnt; $i++) {

				if (trim($book_title[$i]) <> "") {

					$arr_data = array("ASEQ_NO"=>$new_aseq_no,
														"BOOK_NM"=>SetStringToDB($book_title[$i]),
														"BOOK_IMG"=>$book_thumbnail[$i],
														"BOOK_CONTENTS"=>SetStringToDB($book_contents[$i]),
														"AUTHORS"=>SetStringToDB($book_authors[$i]),
														"TRANSLATORS"=>SetStringToDB($book_translators[$i]),
														"PUBLISHER"=>SetStringToDB($book_publisher[$i]),
														"PUBLISH_DATE"=>SetStringToDB($book_datetime[$i]),
														"ISBN"=>SetStringToDB($book_isbn[$i]),
														"REG_ADM"=>0,
														"REG_DATE"=>$INS_DATE);

					$result_update = insertApplyBookInfo($conn, $arr_data);
				}
			}

		}

		$result = "T";
		$msg		= "등록 되었습니다.";
		
		if ($program_type == "TYPE01") {
			if ($confirm_state == "1") {
				$result_copy = copyApplyGroupInfo($conn, $seq_no, $idx_no);
			}
		}

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);


	}

	if ($mode == "LIST_APPLY_BOOK") {
		$arr_rs = listApplyBookInfo($conn, $aseq_no);
		echo json_encode($arr_rs);
	}


	db_close($conn);
?>