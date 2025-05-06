<?

	function checkRepeatBoard($db, $title, $contents) {

		$query = "SELECT MD5(CONCAT(REF_IP, TITLE, CONTENTS)) as prev_md5 FROM TBL_BOARD ORDER BY B_NO desc limit 1 ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$curr_md5 = md5($_SERVER[REMOTE_ADDR].$title.$contents);

		if ($record == $curr_md5) {
			return true;
		} else {
			return false;
		}
	}

	function getBoardNextRe($db) {

		$query = "SELECT min(B_RE) as min_b_re FROM TBL_BOARD";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = (int)($rows[0]-1);

		return $record;

	}

	function listBoardMainDisp($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT *, datediff(NOW(), REG_DATE) AS BB_DATEDIFF,
						 (SELECT COUNT(FILE_NO) 
								FROM TBL_BOARD_FILE
							 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
								 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
								 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
				FROM TBL_BOARD WHERE MAIN_TF = 'Y' ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE desc ";

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function listBoardTop($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $cnt) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT *, datediff(NOW(), REG_DATE) AS BB_DATEDIFF,
						 (SELECT COUNT(FILE_NO) 
								FROM TBL_BOARD_FILE
							 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
								 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
								 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
				FROM TBL_BOARD WHERE TOP_TF = 'Y' ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE desc limit ".$cnt;

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function listBoard($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {
		
		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT @rownum:= @rownum - 1  as rn, B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT,
										 ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') AS VIEW_TF
								FROM TBL_BOARD WHERE 1 = 1 ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY TOP_TF DESC, REG_DATE DESC limit ".$offset.", ".$nRowCount;
	

		//echo $query;

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function totalCntBoard($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD WHERE 1 = 1 ";
		
		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function listBoard_faq($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT @rownum:= @rownum - 1  as rn, B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD WHERE 1 = 1 ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY USE_TF DESC, DISP_SEQ ASC, REG_DATE DESC limit ".$offset.", ".$nRowCount;
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}


	function selectPostBoard($db, $b_code, $b_po, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF
								FROM TBL_BOARD WHERE BB_PO > ? ";

		$types .= "s";
		$bindParams[] = $bb_po;

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE DESC limit 1";
				
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function selectPostBoardAsDate($db, $b_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF
								FROM TBL_BOARD WHERE REG_DATE < ? ";

		$types .= "s";
		$bindParams[] = $reg_date;

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE DESC limit 1";

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function selectPreBoard($db, $b_code, $bb_po, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
							 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF
					FROM TBL_BOARD WHERE BB_PO < ? ";

		$types .= "s";
		$bindParams[] = $bb_po;

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE ASC limit 1";
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function selectPreBoardAsDate($db, $b_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
							 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF
					FROM TBL_BOARD WHERE REG_DATE > ? ";

		$types .= "s";
		$bindParams[] = $reg_date;

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE ASC limit 1";
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function viewChkBoard($db, $b_code, $b_no) {
		
		$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = ? AND B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function viewChkBoardRef($db, $b_code, $b_no) {
		
		$query="UPDATE TBL_BOARD SET REF_HIT_CNT = REF_HIT_CNT + 1 WHERE B_CODE = ? AND B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function viewChkBoardTopic($db, $b_code, $b_no) {
		
		$query="UPDATE TBL_MAIN_LINK SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = ? AND B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function insertBoardRef($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		foreach ($arr_data as $key => $value) {
			if ($key == "REF_IP") $value = $_SERVER['REMOTE_ADDR'];
			$value = ($value === null) ? '' : $value;
			$set_field .= $key.","; 
			$set_value .= "?,"; 
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "INSERT INTO TBL_BOARD_REF_STATS (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {
			$stmt->close();
			$new_b_no = mysqli_insert_id($db);
			return $new_b_no;
		} else {
			$stmt->close();
			return false;
		}
	}

	function viewChkBoardAsMember($db, $b_code, $b_no, $member_id) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ_CNT WHERE B_CODE = ? AND B_NO = ? AND READ_MEMBER = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $b_code, $b_no, $member_id);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$stmt->close();
		
		if ($rows[0] == 0) {
			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = ? AND B_NO = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $b_code, $b_no);
			$stmt->execute();
			$stmt->close();

			$query="INSERT INTO TBL_BOARD_READ_CNT (B_CODE, B_NO, READ_MEMBER, REG_DATE) VALUES (?, ?, ?, now()) ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("sss", $b_code, $b_no, $member_id);
			$stmt->execute();

			$stmt->close();
			
		}
	}

	function viewChkBoardAsIp($db, $b_code, $b_no, $ip) {
		
		$query="SELECT COUNT(B_NO) AS CNT FROM TBL_BOARD_READ_CNT_IP WHERE B_CODE = ? AND B_NO = ? AND IP = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $b_code, $b_no, $ip);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$stmt->close();
		
		if ($rows[0] == 0) {
			$query="INSERT INTO TBL_BOARD_READ_CNT_IP (B_CODE, B_NO, IP, REG_DATE) VALUES (?, ?, ?, now()) ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("sss", $b_code, $b_no, $ip);
			$stmt->execute();

			$stmt->close();

			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = ? AND B_NO = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $b_code, $b_no);
			$stmt->execute();

			$stmt->close();
		}
	}

	function insertBoard($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		foreach ($arr_data as $key => $value) {
			if ($key == "REF_IP") $value = $_SERVER['REMOTE_ADDR'];
			$value = ($value === null) ? '' : $value;
			$set_field .= $key.","; 
			$set_value .= "?, "; 
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "INSERT INTO TBL_BOARD (".$set_field." UP_DATE) 
					values (".$set_value." now()); ";
		
		if ($stmt->execute()) {

			$stmt->close();
			$new_b_no = mysqli_insert_id($db);
			$query = "UPDATE TBL_BOARD set PARENT_NO = '$new_b_no' where B_NO = '$new_b_no' ";
			mysqli_query($db, $query);

			return $new_b_no;
			exit;
		} else {
			
			//echo $db->error;
			$stmt->close();
			return false;
			exit;
		}
	}

	function insertBoardReply($db, $b_code, $bb_no, $bb_po, $bb_re, $bb_de, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $reg_adm) {
		
		
		$query = "SELECT BB_RE, BB_DE, BB_PO FROM TBL_BOARD WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' ";
		
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);
		
		$bb_re = $row[0];
		$bb_de = $row[1];
		$bb_po = $row[2];
		$new_bb_de = $bb_de + 1;

		/*
		$query = "SELECT COUNT(BB_NO) AS CNT 
								FROM TBL_BOARD 
							 WHERE B_CODE = '$b_code' 
								 AND BB_RE = '$bb_re' 
								 AND BB_DE > '$bb_de' ";
		*/

		$query = "SELECT COUNT(BB_NO) AS CNT 
								FROM TBL_BOARD 
							 WHERE BB_RE = '$bb_re' 
								 AND BB_DE > '$bb_de' ";

		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);

		$plus_po = $row[0];

		$new_bb_po = $bb_po + $plus_po + 1;
		
		/*
		$query1 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 
							 WHERE B_CODE = '$b_code' 
								 AND BB_PO >= '$new_bb_po' ";
		*/
		$query1 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 
							 WHERE BB_PO >= '$new_bb_po' ";


		mysql_query($query1,$db);
		
		/*
		$query2 ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO 
								FROM TBL_BOARD 
							 WHERE B_CODE = '$b_code' ";
		*/
		$query2 ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO 
								FROM TBL_BOARD  ";

		$result2 = mysql_query($query2,$db);
		$rows2   = mysqli_fetch_array($result2);

		$new_bb_no = $rows2[0] + 1;
		

		//위변조 체크
		if($_SERVER['REMOTE_ADDR']!=$ref_ip){
			$check_ip = $_SERVER['REMOTE_ADDR'];
			$query_ip="INSERT INTO TBL_BOARD_CHECK_IP (B_CODE, BB_NO, TITLE, REF_IP, REG_DATE) values ('$b_code', '$new_bb_no', '$title', '$check_ip',  now()); ";
			
			@mysql_query($query_ip,$db);
		}
		$ref_ip = $_SERVER['REMOTE_ADDR'];
		
		$query5="INSERT INTO TBL_BOARD (B_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, 
							 CONTENTS, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, COMMENT_TF, TOP_TF, USE_TF, REG_ADM, REG_DATE) 
				values ('$b_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '$new_bb_po', '$bb_re', '$new_bb_de', '$writer_id', '$writer_nm', '$writer_pw', 
								'$email', '$homepage', '$title', '0', '$ref_ip', '$recomm', '$contents', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', 
								'$keyword', '$comment_tf', '$top_tf,', '$use_tf', '$reg_adm', now()); ";
		


		if(!mysql_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			deleteTemporarySave($db, $b_code, $writer_id);
			return $new_bb_no;
		}
	}


	function selectBoard($db, $b_code, $b_no) {

		$query = "SELECT * FROM TBL_BOARD WHERE  B_CODE = ? AND  B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();
		return $record;
	}

	function updateBoard($db, $arr_data, $b_code, $b_no) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "CATE_01") $CATE_01 = $value;
			$value = ($value === null) ? '' : $value;
			$set_query_str .= $key." = ?,";
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "UPDATE TBL_BOARD SET ".$set_query_str. "
							UP_DATE				=	now()
				WHERE B_CODE = ? AND B_NO = ? ";

		$types .= "s";
		$bindParams[] = $b_code;
		$types .= "s";
		$bindParams[] = $b_no;

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {

			$stmt->close();

			$query = " UPDATE TBL_BOARD SET CATE_01 = ? WHERE PARENT_NO = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $CATE_01, $b_no);

			if ($stmt->execute()) {
				$stmt->close();
				return true;
			} else {
				//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
				$stmt->close();
				return false;
			}

		} else {
			
			//echo $db->error;
			$stmt->close();
			return false;
			exit;
		}
	}

	function updateQnaBoard($db, $arr_data, $b_code, $b_no) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "CATE_01") $CATE_01 = $value;
			$value = ($value === null) ? '' : $value;
			$set_query_str .= $key." = ?,";
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "UPDATE TBL_BOARD SET ".$set_query_str. "
							UP_DATE = now()
				WHERE B_CODE = ? AND B_NO = ? ";

		$types .= "s";
		$bindParams[] = $b_code;
		$types .= "s";
		$bindParams[] = $b_no;


		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {

			$stmt->close();
			return true;
			exit;
		} else {
			//echo $db->error;
			$stmt->close();
			return false;
			exit;
		}
	}

	function updateFaqBoardOrder($db, $disp_seq_no, $b_no) {

		$query="UPDATE TBL_BOARD SET DISP_SEQ	= ? WHERE B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $disp_seq_no, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	/*
	===============================================================================================================================
	추천수 관련
	===============================================================================================================================
	*/
	
	function totalCntBoardRecomm($db, $gubun, $b_code, $b_no){

		if($gubun=="RECOMM"){
			$query ="SELECT RECOMM FROM TBL_BOARD WHERE B_CODE = ? AND B_NO = ? ";
		}else if($gubun=="RECOMMNO"){
			$query ="SELECT RECOMM FROM TBL_BOARD WHERE B_CODE = ? AND B_NO = ? ";
		}

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;

	}


	function updateBoardRecomm($db, $gubun, $b_code, $b_no) {

		if($gubun=="RECOMM"){
			$query="UPDATE TBL_BOARD SET RECOMM = RECOMM + 1 WHERE B_CODE = ? AND B_NO = ? ";
		}else if($gubun=="RECOMMNO"){
			$query="UPDATE TBL_BOARD SET RECOMMNO = RECOMMNO + 1 WHERE B_CODE = ? AND B_NO = ? ";
		}

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function updateBoardUseTF($db, $use_tf, $up_adm, $b_code, $b_no) {
		
		$query="UPDATE TBL_BOARD SET 
							USE_TF = ?,
							UP_ADM = ?,
							UP_DATE = now()
				 WHERE B_CODE = ? AND B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ssss", $use_tf, $up_adm, $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function updateBoardConfirmTF($db, $confirm_tf, $up_adm, $b_code, $b_no) {
		
		$query="UPDATE TBL_BOARD SET 
							REPLY_STATE = ?,
							UP_ADM = ?,
							UP_DATE = now()
				 WHERE B_CODE = ? AND BB_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ssss", $confirm_tf, $up_adm, $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function updateQnaAnswer($db, $reply, $reply_adm, $reply_state, $b_code, $b_no) {

		$query = "UPDATE TBL_BOARD SET 
						REPLY =	?,
						REPLY_ADM = ?,
						REPLY_STATE = ?,
						REPLY_DATE = now()
				WHERE B_CODE = ? AND B_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("sssss", $reply, $reply_adm, $reply_state, $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function deleteBoardTF($db, $del_tf, $del_adm, $b_code, $b_no) {

		$query = "UPDATE TBL_BOARD SET
						DEL_TF = ?,
						UP_ADM = ?,
						UP_DATE = now()
					WHERE B_CODE = ?' 
						AND BB_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("ssss", $del_tf, $del_adm, $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function deleteBoard($db, $del_adm, $b_code, $b_no) {

		$query =  "SELECT B_PO, B_RE, COMMENT_CNT FROM TBL_BOARD 
							  WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND B_CODE	= ? 
									AND B_NO		= ? ";
	
		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$spo					= $rows[0];
		$sre					= $rows[1];
		$scomment_cnt = $rows[2];

		$stmt->close();

		$len = strlen($spo);
		if ($len < 0) $len = 0; 
		$reply = substr($spo, 0, $len);

		// 원글만 구한다.
		$query = "SELECT count(*) as cnt from TBL_BOARD
							 WHERE B_PO like CONCAT(?,'%')
								 AND USE_TF = 'Y' 
								 AND DEL_TF = 'N'
								 AND B_NO <> ?
								 AND B_RE = ?
								 AND COMMENT_CNT = 0 ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $reply, $b_no, $sre);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows			= mysqli_fetch_array($result);
		$re_flag	= $rows[0];

		$stmt->close();

		$del_flag = "Y";

		if (($re_flag) || ($scomment_cnt)) {
			$del_flag = "N";
		}

		$query = "DELETE FROM TBL_BOARD_FILE WHERE B_CODE	= ? AND B_NO		= ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);

		$stmt->execute();
		$stmt->close();

		if ($del_flag == "N") { 
			
			$query = "UPDATE TBL_BOARD SET 
									THUMB_IMG = '',
									TITLE = '작성자 또는 관리자에 의해 삭제 되었습니다.', 
									CONTENTS = '답변글이 남아 있어 내용만 삭제 되었습니다.'
								WHERE B_CODE	= ?
									AND B_NO		= ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $b_code, $b_no);

		} else {

			$query = "UPDATE TBL_BOARD SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= ?,
							 DEL_DATE			= now()
						WHERE B_CODE		= ? 
							AND B_NO			= ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("sss", $del_adm, $b_code, $b_no);

		}

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function listBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $use_tf, $del_tf, $search_field, $search_str);

		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT @rownum:= @rownum - 1  as rn, B.CONFIG_NO, B.SITE_NO, B.BOARD_NM, B.BOARD_CODE, B.BOARD_TYPE, B.BOARD_CATE, B.BOARD_GROUP, B.LIST_GROUP, B.READ_GROUP, B.WRITE_GROUP, 
										 B.REPLY_GROUP, B.COMMENT_GROUP, B.LINK_GROUP, B.UPLOAD_GROUP, B.DOWNLOAD_GROUP, B.SECRET_TF, B.SEARCH_TF, B.LIKE_TF, B.UNLIKE_TF, 
										 B.REALNAME_TF, B.IP_TF, B.COMMENT_TF, B.REPLY_TF, B.HTML_TF, B.FILE_TF, B.FILE_CNT, 
										 B.MAX_TITLE, B.NEW_HOUR, B.HOT_CNT, B.BOARD_MEMO, B.BOARD_BADWORD, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE
								FROM TBL_BOARD_CONFIG B WHERE 1 = 1 ";

		if ($site_no <> "") {
			$query .= " AND B.SITE_NO = ? ";
			$types .= "s";
			$bindParams[] = $site_no;
		}

		if ($board_code <> "") {
			$query .= " AND B.BOARD_CODE = ? ";
			$types .= "s";
			$bindParams[] = $board_code;
		}

		if ($board_type <> "") {
			$query .= " AND B.BOARD_TYPE = ? ";
			$types .= "s";
			$bindParams[] = $board_type;
		}

		if ($board_cate <> "") {
			$query .= " AND B.BOARD_CATE = ? ";
			$types .= "s";
			$bindParams[] = $board_cate;
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";
			$types .= "s";
			$bindParams[] = $search_str;
		}
		
		$query .= " ORDER BY B.REG_DATE desc limit ".$offset.", ".$nRowCount;
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}


	function totalCntBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $use_tf, $del_tf, $search_field, $search_str){

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_CONFIG B WHERE 1 = 1 ";
		
		if ($site_no <> "") {
			$query .= " AND B.SITE_NO = ? ";
			$types .= "s";
			$bindParams[] = $site_no;
		}

		if ($board_code <> "") {
			$query .= " AND B.BOARD_CODE = ? ";
			$types .= "s";
			$bindParams[] = $board_code;
		}

		if ($board_type <> "") {
			$query .= " AND B.BOARD_TYPE = ? ";
			$types .= "s";
			$bindParams[] = $board_type;
		}

		if ($board_cate <> "") {
			$query .= " AND B.BOARD_CATE = ? ";
			$types .= "s";
			$bindParams[] = $board_cate;
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";
			$types .= "s";
			$bindParams[] = $search_str;
		}
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function insertBoardConfig($db, $site_no, $arr_data, $menu_right) {
		
		$query ="SELECT IFNULL(MAX(CONFIG_NO),0) AS MAX_NO FROM TBL_BOARD_CONFIG WHERE SITE_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $site_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$max_no  = $rows[0];

		$stmt->close();

		if ($rows[0] <> 0) {
			$new_config_no	= $rows[0] + 1;
		} else {		
			$new_config_no	= "1";
		}

		$new_board_code	= "B_".$site_no."_".$new_config_no;
		
		$set_field = "";
		$set_value = "";

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		foreach ($arr_data as $key => $value) {
			if ($key == "BOARD_NM") $board_nm = $value;
			if ($key == "BOARD_CODE") $value = $new_board_code;
			if ($key == "REG_ADM") $reg_adm = $value;
			$value = ($value === null) ? '' : $value;
			$set_field .= $key.","; 
			$set_value .= "?,"; 
			$types .= "s";
			$bindParams[] = $value;
		}

		$query5="INSERT INTO TBL_BOARD_CONFIG (CONFIG_NO, ".$set_field." REG_DATE) 
					values ('$new_config_no', ".$set_value." now()); ";

		$stmt = $db->prepare($query5);
		$stmt->bind_param($types, ...$bindParams);

		if(!$stmt->execute()) {
			$stmt->close();
			return false;
			exit;
		} else {

			// 관리자 메뉴에 추가 된 게시판을 추가 한다.
			//echo "menu_right-->".$menu_right."<br>";

			$query = "SELECT substring(MENU_CD,1,2) AS MENU_CD FROM TBL_ADMIN_MENU WHERE MENU_RIGHT = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("s", $menu_right);
			$stmt->execute();
			$result = $stmt->get_result();

			$rows   = mysqli_fetch_array($result);
			$MENU_CD = $rows[0];
			$stmt->close();

			$query = "SELECT MENU_SEQ01,MENU_SEQ02 FROM TBL_ADMIN_MENU WHERE MENU_CD = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("s", $MENU_CD);
			$stmt->execute();
			$result = $stmt->get_result();

			$rows   = mysqli_fetch_array($result);
			$MENU_SEQ01 = $rows[0];
			$MENU_SEQ02 = $rows[1];
			$stmt->close();

			$menu_url = "/manager/board/board_list.php?b_code=".$new_board_code;

			$result = insertAdminMenu($db, $MENU_CD, $MENU_SEQ01, $MENU_SEQ02, $board_nm, $menu_url, "Y", $new_board_code, $menu_img, $menu_img_over, "Y", $reg_adm);

			return true;
		}
	}

	function updateBoardConfig($db, $site_no, $arr_data, $config_no) {
		
		$new_board_code	= "B_".$site_no."_".$config_no;

		$set_query_str = "";

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		foreach ($arr_data as $key => $value) {
			if ($key == "BOARD_CODE") $value = $new_board_code;
			$value = ($value === null) ? '' : $value;
			$set_query_str .= $key." = ?,"; 
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "UPDATE TBL_BOARD_CONFIG SET ".$set_query_str. "
							UP_DATE =	now()
				WHERE SITE_NO =	? AND CONFIG_NO = ? ";

		$types .= "s";
		$bindParams[] = $site_no;
		$types .= "s";
		$bindParams[] = $config_no;

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
			exit;
		} else {
			//echo $db->error;
			$stmt->close();
			return false;
			exit;
		}
	}

	function selectBoardConfig($db, $site_no, $config_no) {

		$query = "SELECT * FROM TBL_BOARD_CONFIG WHERE SITE_NO = ? AND CONFIG_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $site_no, $config_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();
		return $record;
	}

	function deleteBoardConfig($db, $del_adm, $site_no, $config_no) {

		$query="UPDATE TBL_BOARD_CONFIG SET DEL_TF = 'Y', DEL_ADM = ?, DEL_DATE = now() WHERE SITE_NO = ? AND CONFIG_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $del_adm, $site_no, $config_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function updateBoardConfigUseTF($db, $use_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET USE_TF = ?, UP_ADM = '$up_adm', UP_DATE = now() WHERE SITE_NO = ? AND CONFIG_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ssss", $use_tf, $up_adm, $site_no, $config_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function updateBoardConfigRealTF($db, $real_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET REAL_TF = ?, UP_ADM = ?, UP_DATE = now() WHERE SITE_NO = ? AND CONFIG_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ssss", $real_tf, $up_adm, $site_no, $config_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function listBoardFile($db, $b_code, $b_no) {

		$query = "SELECT FILE_NO, B_CODE, B_NO,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT,
										 DEL_TF, REG_ADM, REG_DATE, DEL_ADM, DEL_DATE, wr_id, bo_table
								FROM TBL_BOARD_FILE WHERE 1 = 1 
								 AND B_CODE = ? AND B_NO = ? ";
		$query .= " ORDER BY FILE_NO asc ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();
		return $record;
	}

	function insertBoardFile($db, $b_code, $b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_adm) {
				
		$query = "INSERT INTO TBL_BOARD_FILE (B_CODE, B_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT, REG_ADM, REG_DATE) 
														values (?,?, ?, ?, ?, ?, ?, '0', ?, now()); ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ssssssss", $b_code, $b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_adm);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function deleteBoardFile($db, $file_no) {
				
		$query = "DELETE FROM TBL_BOARD_FILE WHERE FILE_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $file_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function updateBoardFileHitCnt($db, $file_no) {
				
		$query = "UPDATE TBL_BOARD_FILE SET HIT_CNT = HIT_CNT + 1 WHERE FILE_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $file_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function selectBoardFile($db, $file_no) {

		$query = "SELECT FILE_NO, B_CODE, B_NO,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT,
										 DEL_TF, REG_ADM, REG_DATE, DEL_ADM, DEL_DATE, bo_table
										 FROM TBL_BOARD_FILE WHERE 1 = 1 
										 AND FILE_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $file_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();
		return $record;
	}

	function getReplyCount($db, $b_code, $b_no) {

		$query = "SELECT COUNT(B_NO) AS CNT FROM TBL_BOARD_COMMENT WHERE B_NO = ? AND DEL_TF = 'N' ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $b_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function B_CODE_NAME($db, $b_code){

		$query ="SELECT BOARD_NM FROM TBL_BOARD_CONFIG WHERE BOARD_CODE = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $b_code);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function listBoardMain($db, $b_code, $cate_01, $use_tf, $del_tf, $nCnt) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, START_DATE, END_DATE, DATE_USE_TF
								FROM TBL_BOARD WHERE 1 = 1 ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		$query .= " ORDER BY TOP_TF desc, REG_DATE desc limit ? ";

		$types .= "i";
		$bindParams[] = $nCnt;

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열

		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function moveBoard($db, $pre_b_code, $next_b_code, $b_no) {
		
		$query = "UPDATE TBL_BOARD_READ_CNT_IP SET B_CODE = ? WHERE B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $next_b_code, $b_no);
		$stmt->execute();
		$stmt->close();

		$query = "UPDATE TBL_BOARD_FILE SET B_CODE = ? WHERE B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $next_b_code, $b_no);
		$stmt->execute();
		$stmt->close();

		$query = "UPDATE TBL_BOARD SET B_CODE = ? WHERE B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $next_b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function copyBoard($db, $pre_b_code, $next_b_code, $b_no) {
		
		$b_re = getBoardNextRe($db);
		$b_po = "";

		$query = "INSERT INTO TBL_BOARD (B_CODE, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, 
													WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, REF_IP, FILE_CNT, CONTENTS, 
													THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, LINK01, LINK02, SECRET_TF, TOP_TF, USE_TF, DEL_TF, REG_DATE, wr_id, bo_table, THUMB_IMG_CHK)
							SELECT ?, ?, ?, CATE_01, CATE_02, CATE_03, CATE_04, 
													WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, REF_IP, FILE_CNT, CONTENTS, 
													THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, LINK01, LINK02, SECRET_TF, TOP_TF, USE_TF, DEL_TF, REG_DATE, wr_id, bo_table, THUMB_IMG_CHK
										 FROM TBL_BOARD
										WHERE B_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("ssss", $next_b_code, $b_po, $b_re, $b_no);
		$stmt->execute();
		$stmt->close();

		$new_b_no = mysqli_insert_id($db);
		
		$query = "INSERT INTO TBL_BOARD_FILE (B_CODE, B_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, DEL_TF, REG_DATE, wr_id, bo_table)
								SELECT ?, ?,FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, DEL_TF, REG_DATE, wr_id, bo_table
									FROM TBL_BOARD_FILE 
								 WHERE B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $next_b_code, $new_b_no, $b_no);

		if ($stmt->execute()) {
			$stmt->close();

			$query = "UPDATE TBL_BOARD set PARENT_NO = ? where B_NO = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $new_b_no, $new_b_no);
			$stmt->execute();

			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function getAllBoardCnt($db, $search_str) {
	
		$query = "SELECT C.BOARD_NM, C.BOARD_CODE, IFNULL(BB.CNT,0) AS CNT
								FROM TBL_BOARD_CONFIG C LEFT OUTER JOIN 
										(SELECT COUNT(B_NO) AS CNT, B_CODE 
											 FROM TBL_BOARD B 
											WHERE B.USE_TF = 'Y'
												AND B.DEL_TF ='N' 
												AND B.B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ')
												AND ((B.CONTENTS like CONCAT('%',?,'%')) or (B.TITLE like CONCAT('%',?,'%')) or (B.WRITER_NM like CONCAT('%',?,'%')))
												GROUP BY B_CODE
										) BB ON C.BOARD_CODE = BB.B_CODE
							WHERE C.DEL_TF = 'N'
								AND C.BOARD_TYPE <> 'FAQ'
							ORDER BY C.CONFIG_NO ASC ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $search_str, $search_str, $search_str);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function getBoardName($db, $b_code) {

		$query = "SELECT BOARD_NM FROM TBL_BOARD_CONFIG WHERE BOARD_CODE = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $b_code);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function getBoardNameAsPageNo($db, $page_no) {

		$query = "SELECT PAGE_NAME FROM TBL_PAGES WHERE PAGE_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $page_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function countRecom($db, $b_code,$b_no){
		$query = "SELECT count(distinct WRITER_ID) FROM TBL_RECOM WHERE B_CODE = ? and B_NO = ? and RECOM_TF='Y'";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}
	
	function countNRecom($db, $b_code,$b_no){
		
		$query = "SELECT count(distinct WRITER_ID) FROM TBL_RECOM WHERE B_CODE = ? and B_NO = ? and RECOM_TF='N'";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function selectRecom($db, $b_code, $b_no, $write_id) {

		$query = "SELECT count(seq_no) FROM TBL_RECOM WHERE B_CODE = ? AND B_NO = ? and WRITER_ID = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $b_code, $b_no, $write_id);
		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function insertRecom($db, $b_code, $b_no, $writer_id, $re_tf) {
		
		$ref_ip = $_SERVER['REMOTE_ADDR'];

		$query5="INSERT INTO TBL_RECOM (B_CODE, B_NO, WRITER_ID, RECOM_TF, REG_DATE, REF_IP) 
														values (?, ?, ?, ?, now(), ?); ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sssss", $b_code, $b_no, $writer_id, $re_tf, $ref_ip);

		if ($stmt->execute()) {
			$stmt->close();
			return "Y";
		} else {
			$stmt->close();
			return false;
		}
	}

	function listConfigBoard($db) {

		$query = "SELECT BOARD_CODE, BOARD_TYPE,BOARD_NM FROM TBL_BOARD_CONFIG WHERE 1 = 1 ";
		$query .= " AND BOARD_TYPE != 'QNA' ";
		$query .= " AND BOARD_TYPE != 'FAQ' ";
		$query .= " AND USE_TF = 'Y' ";
		$query .= " AND DEL_TF = 'N' ";
		$query .= " ORDER BY CONFIG_NO  asc";
		
		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function MediaMain($db, $b_code, $cate_01, $use_tf, $del_tf, $nCnt) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD WHERE 1 = 1 ";

		$query .= " AND (B_CODE = 'GRBBS_1_17' or B_CODE = 'GRBBS_1_18')";

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		$query .= " ORDER BY TOP_TF desc, REG_DATE DESC limit 1, ?";

		$types .= "i";
		$bindParams[] = $nCnt;


		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function MediaTopMain($db, $b_code) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, CATE_01, CATE_02, CATE_03, CATE_04, B_NO, B_PO, B_RE, WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, 
							 HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS, 
							 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, REF_IP
					FROM TBL_BOARD WHERE TOP_TF='Y'";

		$query .= " AND (B_CODE = 'GRBBS_1_17' or B_CODE = 'GRBBS_1_18')";
		$query .= " ORDER BY REG_DATE desc limit 0,1";

		$stmt = $db->prepare($query);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//도배 방지 
*/

	function checkUserWriteTime($db, $writer_id, $min) {

		$query = "SELECT COUNT(BB_NO) AS CNT FROM TBL_BOARD WHERE REG_DATE > TIMESTAMPADD(MINUTE, - ?, NOW()) AND WRITER_ID = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("is", $min, $writer_id);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		if ($record == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function systemDelBoard($db, $writer_id) {
		
		if ($writer_id) {
			$query = "SELECT COUNT(BB_NO) AS CNT 
									FROM TBL_BOARD 
								 WHERE B_CODE IN ('GRBBS_1_7','GRBBS_1_6') 
									 AND REG_DATE > TIMESTAMPADD(MINUTE, -15, NOW()) AND WRITER_ID = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("s", $writer_id);

			$stmt->execute();
			$result = $stmt->get_result();

			$rows   = mysqli_fetch_array($result);
			$record  = $rows[0];

			$stmt->close();

			if ($record > 10) {
				$query = "UPDATE TBL_BOARD SET DEL_TF = 'Y', CATE_04 = 'system 자동 삭제' WHERE B_CODE IN ('GRBBS_1_7','GRBBS_1_6') 
								   AND REG_DATE > TIMESTAMPADD(MINUTE, -15, NOW()) AND WRITER_ID = ? ";

				$stmt = $db->prepare($query);
				$stmt->bind_param("s", $writer_id);
				$stmt->execute();

				$stmt->close();

				$query = "UPDATE TBL_MEMBER SET USE_TF = 'N' WHERE MEM_ID = ? ";

				$stmt = $db->prepare($query);
				$stmt->bind_param("s", $writer_id);
				$stmt->execute();

				$stmt->close();

			}
		}
	}

	function checkUserWrite($db, $writer_id) {
		
		if ($writer_id) {

			$query = "SELECT COUNT(MEM_ID) FROM TBL_MEMBER  WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND MEM_ID = ? ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("s", $writer_id);

			$stmt->execute();
			$result = $stmt->get_result();

			$rows   = mysqli_fetch_array($result);
			$record  = $rows[0];

			$stmt->close();
			
			if($record) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//금지어체크
	function checkUserWriteBadWord($check_words, $in_words) {
		
		$ret=true;//금지어가 없음
	
		$b_board_badword_arr		= explode(",",$check_words);
		
		for($bi=0;$bi<count($b_board_badword_arr);$bi++){
			if($bi>0)$b_board_badword2		.= ",";
				
			if (trim($b_board_badword_arr[$bi]) <> "") {
				$b_board_badword2		.= '"'.trim($b_board_badword_arr[$bi]).'"';
				$pos = strpos($in_words, trim($b_board_badword_arr[$bi]));

				if ($pos === false) {
					$ret=true;//금지어가 없음
				} else {
					$ret=false;//금지어가 있음
					break;
				}
			}
		}
		
		return $ret;
		
	}
/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//도배 방지 끝
*/






/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//임시 저장용 관련
*/


	function totalCntTemporarySave($db, $b_code, $writer_id, $search_field, $search_str){

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_TEMPORARY  WHERE 1 = 1 ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($search_str <> "") {
			
			if($search_field == "ALL") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			} else {
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$stmt = $db->prepare($query);
		$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}


	function listTemporarySave($db, $b_code, $writer_id, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntPdf($db, $use_tf, $del_tf, $search_field, $search_str);

		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT @rownum:= @rownum - 1  as rn, TEMP_NO, B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD_TEMPORARY WHERE 1 = 1 ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($search_str <> "") {
			
			if($search_field == "ALL") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			} else {
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
		$stmt = $db->prepare($query);
		$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function insertTemporarySave($db, $b_code, $writer_id, $title, $contents) {
		
		$ret_sel = selectTemporarySave($db, $b_code, $writer_id);

		if($ret_sel && $ret_sel[0]["TEMP_NO"]){

			return updateTemporarySave($db, $b_code, $writer_id, $title, $contents,$ret_sel[0]["TEMP_NO"]);

		}else{

			$query="INSERT INTO TBL_BOARD_TEMPORARY (B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE) 
			values (?, ?, ?, ?,  now()); ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("ssss", $b_code, $writer_id, $title, $contents);

			if ($stmt->execute()) {
				$stmt->close();
				return 1;
			} else {
				//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
				$stmt->close();
				return false;
			}
		}
	}

	function selectTemporarySave4no($db, $seq_no) {

		$query = "SELECT TEMP_NO, B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE
					FROM TBL_BOARD_TEMPORARY WHERE  TEMP_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $seq_no);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function selectTemporarySave($db, $b_code, $writer_id) {

		$query = "SELECT TEMP_NO, B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE
					FROM TBL_BOARD_TEMPORARY WHERE  B_CODE = ? and WRITER_ID = ? ";


		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $writer_id);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function updateTemporarySave($db, $b_code, $writer_id, $title, $contents, $seq_no) {

		$query = "UPDATE TBL_BOARD_TEMPORARY SET 
										 TITLE = ?,
										 CONTENTS = ?,
										 UP_DATE =	now()
							 WHERE TEMP_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $title, $contents, $seq_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function deleteTemporarySave4no($db, $seq_no) {
				
		$query = "DELETE FROM TBL_BOARD_TEMPORARY WHERE TEMP_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $seq_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function deleteTemporarySave($db, $b_code, $writer_id) {
				
		$query = "DELETE FROM TBL_BOARD_TEMPORARY WHERE B_CODE = ? and WRITER_ID = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $writer_id);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//임시 저장용 관련 끝
*/

/*
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 썸네일 이미지 등록 하는 기능.
*/

	function updateThumbnailImg($db, $b_code, $b_no, $file_name) {

		$query = "UPDATE TBL_BOARD SET 
							THUMB_IMG = ?
							WHERE B_CODE = ? AND B_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $file_name, $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function updateOldBoardThumbnailImg($db, $b_code, $b_no, $file_name) {

		$query = "UPDATE TBL_BOARD SET 
							THUMB_IMG = ?,
							THUMB_IMG_CHK = 'Y'
							WHERE B_CODE = ? AND B_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $file_name, $b_code, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

/*
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 썸네일 리스트
*/
	function listThumbImg($db, $b_code, $displaycnt) {

		$query = "SELECT B_CODE, B_NO, THUMB_IMG, TITLE
							FROM TBL_BOARD 
						 WHERE B_CODE = ?
							 AND THUMB_IMG <> ''
							 AND USE_TF = 'Y'
							 AND DEL_TF = 'N'
						 ORDER BY REG_DATE DESC limit 0, ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("si", $b_code, $displaycnt);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function getMainBoardList($db, $b_code, $cnt) {

		$query= "SELECT * FROM TBL_BOARD 
							WHERE B_CODE = ?
								AND USE_TF = 'Y' 
								AND DEL_TF = 'N' 
								AND B_PO = '' ORDER BY REG_DATE DESC limit 0, ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("si", $b_code, $cnt);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function getMainBoardListWithCate($db, $b_code, $cate_01, $cnt) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query= "SELECT * FROM TBL_BOARD 
							WHERE B_CODE = ? ";

		$types .= "s";
		$bindParams[] = $b_code;

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		$query .= " AND USE_TF = 'Y' 
								AND DEL_TF = 'N' 
								AND B_PO = '' ORDER BY REG_DATE DESC limit 0, ? ";
		
		$types .= "i";
		$bindParams[] = $cnt;

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function getMainMoSmsList($db, $cnt) {

		$query= "SELECT * FROM TBL_MOSMS
							WHERE USE_TF = 'Y' 
								AND DEL_TF = 'N' 
								AND FILE_NM <> ''
								ORDER BY SEQ_NO DESC limit 0, ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("i", $cnt);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function listAllBoard($db, $search_str, $nPage, $nRowCount, $total_cnt) {

		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.B_CODE, A.B_NO, A.CATE_01, A.TITLE, B.BOARD_GROUP, B.BOARD_TYPE, A.REG_DATE
								FROM TBL_BOARD A, TBL_BOARD_CONFIG B
							 WHERE A.B_CODE = B.BOARD_CODE
								 AND B.SEARCH_TF = 'Y'
								 AND B.USE_TF = 'Y'
								 AND B.DEL_TF = 'N'
								 AND A.USE_TF = 'Y'
								 AND A.DEL_TF = 'N'
								 AND A.SECRET_TF <> 'Y' ";

		if ($search_str <> "") {
			$query .= " AND ((WRITER_NICK like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (KEYWORD like CONCAT('%',?,'%'))) ";
			$types .= "ssss";
			$bindParams[] = $search_str;
			$bindParams[] = $search_str;
			$bindParams[] = $search_str;
			$bindParams[] = $search_str;

		}

		$query .= " ORDER BY A.REG_DATE DESC limit ".$offset.", ".$nRowCount;

		$stmt = $db->prepare($query);
		$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function totalCntAllBoard($db, $search_str){

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD A, TBL_BOARD_CONFIG B
							 WHERE A.B_CODE = B.BOARD_CODE
								 AND B.SEARCH_TF = 'Y'
								 AND B.USE_TF = 'Y'
								 AND B.DEL_TF = 'N'
								 AND A.USE_TF = 'Y'
								 AND A.DEL_TF = 'N'
								 AND A.SECRET_TF <> 'Y' ";

		if ($search_str <> "") {
			$query .= " AND ((WRITER_NICK like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (KEYWORD like CONCAT('%',?,'%'))) ";
			$types .= "ssss";
			$bindParams[] = $search_str;
			$bindParams[] = $search_str;
			$bindParams[] = $search_str;
			$bindParams[] = $search_str;

		}

		$stmt = $db->prepare($query);
		$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function selectMainBoard($db, $seq_no) {


		$query = "SELECT * FROM TBL_MAIN_BOARD_LIST WHERE SEQ_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $seq_no);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function updateMainBoard($db, $b_code_01, $b_code_02, $b_code_03, $b_code_04, $b_code_05, $b_code_06, $b_code_07, $b_code_08, $b_code_09, $b_code_10, $b_code_11, $b_code_12, $b_code_13, $b_code_14, $b_code_15, $seq_no) {

		$query = "UPDATE TBL_MAIN_BOARD_LIST SET 
							B_CODE_01	=	?,
							B_CODE_02	=	?,
							B_CODE_03	=	?,
							B_CODE_04	=	?,
							B_CODE_05	=	?,
							B_CODE_06	=	?,
							B_CODE_07	=	?,
							B_CODE_08	=	?,
							B_CODE_09	=	?,
							B_CODE_10	=	?,
							B_CODE_11	=	?,
							B_CODE_12	=	?,
							B_CODE_13	=	?,
							B_CODE_14	=	?,
							B_CODE_15	=	?
							WHERE SEQ_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("ssssssssssssssss", $b_code_01, $b_code_02, $b_code_03, $b_code_04, $b_code_05, $b_code_06, $b_code_07, $b_code_08, $b_code_09, $b_code_10, $b_code_11, $b_code_12, $b_code_13, $b_code_14, $b_code_15, $seq_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function listBoardWithDate($db, $b_code, $start_date, $end_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD WHERE B_NO NOT IN (SELECT B_NO FROM TBL_MAIN_LINK WHERE DEL_TF ='N') ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($start_date <> "") {
			$query .= " AND REG_DATE >= ? ";
			$types .= "s";
			$bindParams[] = $start_date;

		}

		if ($end_date <> "") {
			$query .= " AND REG_DATE <= ? ";
			$types .= "s";
			$bindParams[] = $end_date." 23:59:59";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY HIT_CNT DESC ";
		
		$stmt = $db->prepare($query);
		$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}


	function listBoardRef($db, $b_code, $cnt, $b_no) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = " SELECT * 
								 FROM (
											SELECT B_CODE, B_NO, PARENT_NO, B_PO, B_RE, CATE_01, CATE_02, 
														 CATE_03, CATE_04, WRITER_ID, WRITER_NM, WRITER_NICK, 
														 WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, 
														 REF_IP, RECOMM, RECOMMNO, COMMENT_CNT, FILE_CNT, 
														 CONTENTS, THUMB_IMG, FILE_NM, FILE_RNM, REF_TF, REG_DATE, USE_TF, COMMENT_TF
												FROM TBL_BOARD
											 WHERE 1 = 1
												 AND USE_TF = 'Y'
												 AND DEL_TF = 'N'
												 AND REF_TF = 'A'
												 AND CATE_01 <> '북적북적 와글와글'
												 AND B_NO <> ?
												 AND B_CODE= ?

											 UNION

											SELECT B_CODE, B_NO, PARENT_NO, B_PO, B_RE, CATE_01, CATE_02, 
														 CATE_03, CATE_04, WRITER_ID, WRITER_NM, WRITER_NICK, 
														 WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT,
														 REF_IP, RECOMM, RECOMMNO, COMMENT_CNT, FILE_CNT, 
														 CONTENTS, THUMB_IMG, FILE_NM, FILE_RNM, REF_TF, REG_DATE, USE_TF, COMMENT_TF
												FROM TBL_BOARD 
											 WHERE 1 = 1
												 AND USE_TF = 'Y'
												 AND DEL_TF = 'N'
												 AND REF_TF = 'B' 
												 AND CATE_01 <> '북적북적 와글와글'
												 AND B_NO <> ? ";

		$types .= "s";
		$bindParams[] = $b_no;
		$types .= "s";
		$bindParams[] = $b_code;
		$types .= "s";
		$bindParams[] = $b_no;

		if ($b_code == "B_1_1") {
			$query .= "AND REG_DATE >= date_sub(now(), interval 3 week) ";
		} else {
			$query .= "AND REG_DATE >= date_sub(now(), interval 2 month) ";
		}

		$query .= "					 AND B_CODE= ?
									 ORDER BY HIT_CNT DESC) AA
								ORDER BY REF_TF ASC
								limit ? ";

		$types .= "s";
		$bindParams[] = $b_code;
		$types .= "i";
		$bindParams[] = $cnt;


		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}


	function listRefBoard($db, $b_code, $start_date, $end_date, $writer_id, $ref_ip, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT DISTINCT A.B_CODE, A.B_NO, A.CATE_01, A.TITLE, A.WRITER_NM, A.HIT_CNT, A.REF_HIT_CNT, A.REG_DATE, A.REF_IP
								FROM TBL_BOARD A, TBL_BOARD_REF_STATS B
							 WHERE A.B_CODE = B.B_CODE
								 AND A.B_NO = B.B_NO ";
		
		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($start_date <> "") {
			$query .= " AND A.REG_DATE >= ? ";
			$types .= "s";
			$bindParams[] = $start_date;

		}

		if ($end_date <> "") {
			$query .= " AND A.REG_DATE <= ? ";
			$types .= "s";
			$bindParams[] = $end_date." 23:59:59";
		}

		if ($writer_id <> "") {
			$query .= " AND A.WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND A.REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((A.REPLY like CONCAT('%',?,'%')) or (A.CONTENTS like CONCAT('%',?,'%')) or (A.TITLE like CONCAT('%',?,'%')) or (A.WRITER_ID like CONCAT('%',?,'%')) or (A.WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((A.CONTENTS like CONCAT('%',?,'%')) or (A.TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (A.TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND A.WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REF_HIT_CNT DESC limit ".$offset.", ".$nRowCount;
		
		$stmt = $db->prepare($query);
		$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}


	function totalCntRefBoard($db, $b_code, $start_date, $end_date, $writer_id, $ref_ip, $use_tf, $del_tf, $search_field, $search_str){

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열
		
		$query = "SELECT COUNT(DISTINCT A.B_NO) AS CNT
								FROM TBL_BOARD A, TBL_BOARD_REF_STATS B
							 WHERE A.B_CODE = B.B_CODE
								 AND A.B_NO = B.B_NO ";

		if ($b_code <> "") {
			$query .= " AND A.B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND A.B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($start_date <> "") {
			$query .= " AND B.REG_DATE >= ? ";
			$types .= "s";
			$bindParams[] = $start_date;

		}

		if ($end_date <> "") {
			$query .= " AND B.REG_DATE <= ? ";
			$types .= "s";
			$bindParams[] = $end_date." 23:59:59";
		}

		if ($writer_id <> "") {
			$query .= " AND A.WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND A.REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((A.REPLY like CONCAT('%',?,'%')) or (A.CONTENTS like CONCAT('%',?,'%')) or (A.TITLE like CONCAT('%',?,'%')) or (A.WRITER_ID like CONCAT('%',?,'%')) or (A.WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((A.CONTENTS like CONCAT('%',?,'%')) or (A.TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (A.TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND A.WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function viewChkBoardAsAdmin($db, $b_code, $b_no, $adm_no) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ WHERE B_CODE = ? AND B_NO = ? AND ADM_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $b_code, $b_no, $adm_no);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$row_cnt  = $rows[0];

		$stmt->close();

		if ($row_cnt == 0) {

			$query="INSERT INTO TBL_BOARD_READ (B_CODE, B_NO, ADM_NO, REG_DATE) VALUES (?, ?, ?, now()) ";

			$stmt = $db->prepare($query);
			$stmt->bind_param("sss", $b_code, $b_no, $adm_no);
			$stmt->execute();
			$stmt->close();
	
			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = ? AND B_NO = ? ";
			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $b_code, $b_no);
			$stmt->execute();
			$stmt->close();
		}
	}

	function resetChkBoardAsAdmin($db, $b_code, $b_no) {
		
		$query="DELETE FROM TBL_BOARD_READ WHERE B_CODE = ? AND B_NO = ? ";
		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);
		$stmt->execute();
		$stmt->close();

	}

	function ChkBoardAsAdmin($db, $b_code, $b_no, $adm_no) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ WHERE B_CODE = ? AND B_NO = ? AND ADM_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $b_code, $b_no, $adm_no);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		if ($rows[0] == 0) {
			$stmt->close();
			return false;
		} else {
			$stmt->close();
			return true;
		}
	}

	function getAdminReadList($db, $b_code, $b_no) {
		
		$str = "";

		$query = "SELECT ADM_ID, ADM_NAME, ADM_NO,
										 (SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ WHERE B_CODE = ? AND B_NO = ? AND  ADM_NO = A.ADM_NO) CNT
								FROM TBL_ADMIN_INFO A
							 WHERE A.DEL_TF = 'N' 
							 ORDER BY A.ADM_NAME ASC  ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $b_code, $b_no);
			
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {

			if ($row['CNT'] == 0) { 
				$str = $str."(".$row['ADM_ID'].")".$row['ADM_NAME'];
			} else {
				$str = $str." <strong><font color='navy'>[".$row['ADM_ID']."]".$row['ADM_NAME']."</font></strong>";
			}
		}

		$stmt->close();

		return $str;
	}

	function listNewBoard($db, $b_code, $cnt) {
	
		$query = "SELECT *
								FROM TBL_BOARD
							 WHERE DEL_TF = 'N'
								 AND USE_TF = 'Y' 
								 AND REPLY_STATE = 'N'
								 AND B_CODE = ?
							 ORDER BY UP_DATE DESC LIMIT ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("si", $b_code, $cnt);
		
		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}


	function selectPostBoardAsTop($db, $b_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $top_tf, $use_tf, $del_tf, $search_field, $search_str) {

		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF
								FROM TBL_BOARD WHERE CONCAT(TOP_TF,REG_DATE) < CONCAT(?,?) AND ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

		$types .= "s";
		$bindParams[] = $top_tf;
		$types .= "s";
		$bindParams[] = $reg_date;

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY TOP_TF DESC, REG_DATE DESC limit 1";

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}



	function selectPreBoardAsTop($db, $b_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $top_tf, $use_tf, $del_tf, $search_field, $search_str) {
		
		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
							 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF
					FROM TBL_BOARD WHERE CONCAT(TOP_TF,REG_DATE) > CONCAT(?,?) AND ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

		$types .= "s";
		$bindParams[] = $top_tf;
		$types .= "s";
		$bindParams[] = $reg_date;

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}
								
		$query .= " ORDER BY TOP_TF ASC, REG_DATE ASC limit 1";
		
		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}




	function listBoardFront($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {
		
		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT @rownum:= @rownum - 1  as rn, B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF, INFO_01,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD 
							 WHERE 1 = 1 
								 AND ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY TOP_TF DESC, REG_DATE DESC limit ".$offset.", ".$nRowCount;
	

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function totalCntBoardFront($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD WHERE 1 = 1 AND ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";
		
		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = ? ";
			$types .= "s";
			$bindParams[] = $cate_02;
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = ? ";
			$types .= "s";
			$bindParams[] = $cate_03;
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = ? ";
			$types .= "s";
			$bindParams[] = $cate_04;
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = ? ";
			$types .= "s";
			$bindParams[] = $writer_id;
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = ? ";
			$types .= "s";
			$bindParams[] = $ref_ip;
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = ? ";
			$types .= "s";
			$bindParams[] = $reply_state;
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like CONCAT('%',?,'%')) or (CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%')) or (WRITER_ID like CONCAT('%',?,'%')) or (WRITER_NM like CONCAT('%',?,'%'))) ";
		
				$types .= "sssss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like CONCAT('%',?,'%')) or (TITLE like CONCAT('%',?,'%'))) ";

				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}elseif($search_field == "result") {
				$query .= " AND (TITLE like CONCAT('%',?,'%')) ";

				$types .= "s";
				$bindParams[] = $search_str;

			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = ? ";

				$types .= "s";
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;
	}

	function listNoticeBoard($db, $m_type) {
		
		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));
		
		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT * FROM (
							SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF, INFO_01,
										 DISP_SEQ, SUB_DISP_SEQ, 
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD 
							 WHERE DEL_TF = 'N' AND USE_TF = 'Y' 
								 AND ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

		if ($m_type == "ALL") { 
			$query .= " AND B_CODE IN ('B_1_1','B_1_7') ";
		} else if ($m_type == "SCHOOL") { 
			$query .= " AND B_CODE = 'B_1_7' ";
		} else {
			$query .= " AND B_CODE = 'B_1_1' ";
		}


		if ($m_type <> "") {
			if (($m_type <> "ALL") && ($m_type <> "SCHOOL")) {
				$query .= " AND CATE_01 = ? ";
				$types .= "s";
				$bindParams[] = $m_type;

			}
		}
		
		$query .= " ORDER BY MAIN_TF DESC, REG_DATE DESC limit 12 ) AA";

		if ($m_type == "ALL") { 
			$query .= " ORDER BY AA.DISP_SEQ ASC";
		} else {
			$query .= " ORDER BY AA.SUB_DISP_SEQ ASC";
		}

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function updateBoardMainTF($db, $main_tf, $adm_no, $b_no) {

		$query="UPDATE TBL_BOARD SET
										MAIN_TF = ?,
										UP_ADM = ?,
										UP_DATE = now()
							WHERE B_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $main_tf, $adm_no, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}

	function updateOrderBoard($db, $disp_seq_no, $b_no, $m_type) {

		if ($m_type == "ALL") {

			$query="UPDATE TBL_BOARD SET
											DISP_SEQ	=	?
								WHERE B_NO	= ? ";

		} else { 

			$query="UPDATE TBL_BOARD SET
											SUB_DISP_SEQ	=	?
								WHERE B_NO	= ? ";
		}
		

		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $disp_seq_no, $b_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			//echo "오류 발생: " . $e->getMessage(); // 오류 메시지 출력
			$stmt->close();
			return false;
		}
	}


	function getMainlistBoardFront($db, $b_code, $cate_01, $total_cnt) {
		
		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));
	
		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query = "SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF, INFO_01,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD 
							 WHERE USE_TF ='Y' AND DEL_TF = 'N'
								 AND ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = ? ";
			$types .= "s";
			$bindParams[] = $b_code;
			
			if ($b_code == "B_1_1") {
				//$query .= " AND MAIN_TF = 'Y' ";
			}

		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			if ($cate_01 == "수시") {
				$query .= " AND CATE_01 IN (? ,'학생부종합전형') ";
			} else {
				$query .= " AND CATE_01 = ? ";
			}
			$types .= "s";
			$bindParams[] = $cate_01;
		}
		
		$query .= " ORDER BY TOP_TF DESC, REG_DATE DESC limit ? ";
	
		$types .= "i";
		$bindParams[] = $total_cnt;

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

?>