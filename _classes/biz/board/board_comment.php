<?
	function getBoardCommentNextRe($db, $b_no) {

		$query = "SELECT min(B_RE) as min_b_re FROM TBL_BOARD_COMMENT WHERE B_NO = '$b_no' ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = (int)($rows[0]-1);

		return $record;

	}

	function insertBoardComment($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "B_NO") $b_no = $value;
			if ($key == "REF_IP") $value = $_SERVER['REMOTE_ADDR'];
			$set_field .= $key.","; 
			$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_BOARD_COMMENT (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";
		
		//echo $query;
		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_c_no = mysqli_insert_id($db);
			

			$query = "UPDATE TBL_BOARD set COMMENT_CNT = (SELECT COUNT(*) FROM TBL_BOARD_COMMENT WHERE DEL_TF = 'N' AND  B_NO = '$b_no') WHERE B_NO = '$b_no' ";
			mysqli_query($db, $query);

			$query = "UPDATE TBL_BOARD_COMMENT set PARENT_NO = '$new_c_no' where C_NO = '$new_c_no' ";
			mysqli_query($db, $query);

			//deleteTemporarySave($db, $b_code, $writer_id);
			return $new_c_no;
		}
	}

	function listManagerBoardComment($db, $b_no, $writer_id, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, C_NO, A.B_NO, A.B_PO, A.B_RE, A.WRITER_ID, A.WRITER_NM, A.WRITER_NICK, 
										 A.WRITER_PW, A.TITLE, A.HIT_CNT, A.REF_IP, A.RECOMM, A.RECOMMNO, A.FILE_CNT, 
										 A.CONTENTS, A.SECRET_TF, A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.TITLE AS BTITLE
								FROM TBL_BOARD_COMMENT A, TBL_BOARD B WHERE A.B_NO = B.B_NO AND B.USE_TF = 'Y' AND B.DEL_TF = 'N' ";


		if ($b_no <> "") {
			$query .= " AND A.B_NO = '".$b_no."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND A.WRITER_ID = '".$writer_id."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((A.CONTENTS like '%".$search_str."%') or (A.TITLE like '%".$search_str."%') or (A.WRITER_ID like '%".$search_str."%') or (A.WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((A.CONTENTS like '%".$search_str."%') or (A.TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "result") {
				$query .= " AND (A.TITLE like '%".$search_str."%') ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND A.WRITER_ID = '".$search_str."' ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY REG_DATE DESC limit ".$offset.", ".$nRowCount;

		//echo $query;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntManagerBoardComment($db, $b_no, $writer_id, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_COMMENT A, TBL_BOARD B WHERE A.B_NO = B.B_NO AND B.USE_TF = 'Y' AND B.DEL_TF = 'N' ";

		if ($b_no <> "") {
			$query .= " AND A.B_NO = '".$b_no."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND A.WRITER_ID = '".$writer_id."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((A.CONTENTS like '%".$search_str."%') or (A.TITLE like '%".$search_str."%') or (A.WRITER_ID like '%".$search_str."%') or (A.WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((A.CONTENTS like '%".$search_str."%') or (A.TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "result") {
				$query .= " AND (A.TITLE like '%".$search_str."%') ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND A.WRITER_ID = '".$search_str."' ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listBoardComment($db, $b_no, $writer_id, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, C_NO, B_NO, B_PO, B_RE, WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, 
										 CONTENTS, SECRET_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD_COMMENT WHERE 1 = 1 ";


		if ($b_no <> "") {
			$query .= " AND B_NO = '".$b_no."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "result") {
				$query .= " AND (TITLE like '%".$search_str."%') ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = '".$search_str."' ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY B_RE ASC, B_PO limit ".$offset.", ".$nRowCount;

		//echo $query;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntBoardComment($db, $b_no, $writer_id, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_COMMENT WHERE 1 = 1 ";

		if ($b_no <> "") {
			$query .= " AND B_NO = '".$b_no."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "result") {
				$query .= " AND (TITLE like '%".$search_str."%') ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = '".$search_str."' ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectBoardComment($db, $c_no) {

		$query = "SELECT * FROM TBL_BOARD_COMMENT WHERE C_NO = '$c_no' ";
		//echo $query;
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateBoardComment($db, $up_user, $c_no, $secret_tf, $contents) {

		$query = "UPDATE TBL_BOARD_COMMENT SET 
									SECRET_TF = '$secret_tf', 
									CONTENTS	= '$contents',
									UP_DATE		= now()
								WHERE C_NO		= '$c_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function deleteBoardComment($db, $del_adm, $c_no, $mag_title, $mag_contents) {

		$query =  "SELECT B_NO, B_PO, B_RE FROM TBL_BOARD_COMMENT 
							  WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND C_NO		= '$c_no' ";
		
		$result = mysqli_query($db, $query);
		$list = mysqli_fetch_array($result);
		
		$spo					= $list[B_PO];
		$sre					= $list[B_RE];
		$b_no					= $list[B_NO];

		$len = strlen($spo);
		if ($len < 0) $len = 0; 
		$reply = substr($spo, 0, $len);

		// 원글만 구한다.
		$query = "SELECT count(*) as cnt from TBL_BOARD_COMMENT
							 WHERE B_PO like '$reply%'
								 AND USE_TF = 'Y' 
								 AND DEL_TF = 'N'
								 AND B_NO = '$b_no'
								 AND C_NO <> '$c_no'
								 AND B_RE = '$sre' ";
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$list		= mysqli_fetch_array($result);
		$re_flag = $list[cnt];
		
		$del_flag = "Y";

		if ($re_flag) {
			$del_flag = "N";
		}

		//echo $del_flag;
		//exit;

		if ($del_flag == "N") { 
			
			$query = "UPDATE TBL_BOARD_COMMENT SET 
									TITLE = '$mag_title', 
									CONTENTS = '$mag_contents'
								WHERE C_NO		= '$c_no' ";
		} else {

			$query = "UPDATE TBL_BOARD_COMMENT SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
						WHERE C_NO			= '$c_no' ";

			
		}

		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "UPDATE TBL_BOARD set COMMENT_CNT = (SELECT COUNT(*) FROM TBL_BOARD_COMMENT WHERE DEL_TF = 'N' AND B_NO = '$b_no') WHERE B_NO = '$b_no' ";
			mysqli_query($db, $query);

			return true;
		}
	}

	function updateBoardCommentRecomm($db, $c_no) {

		$query = "UPDATE TBL_BOARD_COMMENT SET 
									RECOMM = RECOMM + 1
								WHERE C_NO		= '$c_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}
?>