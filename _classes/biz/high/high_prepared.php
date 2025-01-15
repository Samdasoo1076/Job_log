<?
	# =============================================================================
	# File Name    : high.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-11-02
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listHigh($db, $h_no, $h_who, $h_type, $h_program, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntHigh($db, $h_no, $h_who, $h_type, $h_program, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db,$query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, H_NO, H_WHO, H_NM, H_TYPE, H_PROGRAM, H_TITLE, H_TARGET, H_PERIOD, H_AGENDA, H_CONTENTS, H_NUMBERS, H_VENUE, APPLY_LINK, CONFIRM_LINK,
							APPLY_S_DATE, APPLY_S_HOUR, APPLY_S_MIN, APPLY_E_DATE, APPLY_E_HOUR, APPLY_E_MIN, EVENT_S_DATE, EVENT_S_HOUR, EVENT_S_MIN, EVENT_E_DATE, EVENT_E_HOUR, EVENT_E_MIN,
							H_FILE, H_FILE_NM, H_DATE, DISP_SEQ,
						  APPLY_TF, APPLY_USE_TF, EVENT_USE_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_HIGH WHERE 1 = 1 ";

		if ($h_who <> "") {
			$query .= " AND H_WHO LIKE '%".$h_who."%' ";
		}

		if ($h_type <> "") {
			$query .= " AND H_TYPE LIKE '%".$h_type."%' ";
		}

		if ($h_program <> "") {
			$query .= " AND H_PROGRAM LIKE '%".$h_program."%' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((H_NM like '%".$search_str."%') or (H_TITLE like '%".$search_str."%') or (H_CONTENTS like '%".$search_str."%')) ";
			} else { 
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ ASC, H_NO DESC";  //limit ".$offset.", ".$nRowCount;

		//echo $query."<br />";
		//exit;

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntHigh($db, $h_no, $h_who, $h_type, $h_program, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_HIGH WHERE 1 = 1 ";

		if ($h_who <> "") {
			$query .= " AND H_WHO LIKE '%".$h_who."%' ";
		}

		if ($h_type <> "") {
			$query .= " AND H_TYPE LIKE '%".$h_type."%' ";
		}

		if ($h_program <> "") {
			$query .= " AND H_PROGRAM LIKE '%".$h_program."%' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((H_NM like '%".$search_str."%') or (H_TITLE like '%".$search_str."%') or (H_CONTENTS like '%".$search_str."%')) ";
			} else { 
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		//echo $query."<br />";

		$result = mysqli_query($db,$query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function insertHigh($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_HIGH (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_h_no = mysqli_insert_id($db);  //insert 후 h_no값을 알아오기 위한 구분
			return $new_h_no;
		}
	}

	function selectHigh($db, $h_no) {

		$query = "SELECT * FROM TBL_HIGH WHERE H_NO='$h_no'";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateHigh($db, $arr_data, $h_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_HIGH SET ".$set_query_str." ";
		$query .= "h_no = '$h_no' WHERE H_NO = '$h_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateHighUseTF($db, $use_tf, $up_adm, $h_no) {
		
		$query = "UPDATE TBL_HIGH SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE H_NO			= '$h_no' ";

//echo $query;
//exit;
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateHighRefTF($db, $ref_tf, $up_adm, $h_no) {

		$query = "UPDATE TBL_HIGH SET 
							REF_TF		= 'N',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE H_NO			<> '$h_no' ";

		$result = mysqli_query($db,$query);

		$query = "UPDATE TBL_HIGH SET 
							REF_TF		= '$ref_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE H_NO			= '$h_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateOrderHigh($db, $disp_seq_no, $h_no) {

	    $query="UPDATE TBL_HIGH SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE H_NO	= '$h_no'";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function deleteHigh($db, $adm_no, $h_no) {

		$query = "UPDATE TBL_HIGH SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE H_NO = '$h_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

# 이전글
	function selectPreHighAsDate($db, $h_no, $reg_date, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT H_NO, H_TYPE, H_PROGRAM, H_TITLE, REG_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
					FROM TBL_HIGH WHERE REG_DATE > '".$reg_date."' ";
					//FROM TBL_BOARD WHERE CONCAT(REG_DATE, BB_NO) > '".$reg_date.$bb_no."' ";

//echo $query;
//exit;

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((H_TITLE like '%".$search_str."%') or (H_CONTENTS like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
								
		//$query .= " ORDER BY BB_PO DESC limit 1";
		$query .= " ORDER BY REG_DATE ASC limit 1";
		
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

# 다음글
	function selectPostHighAsDate($db, $h_no, $reg_date, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT H_NO, H_TYPE, H_PROGRAM, H_TITLE, REG_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
					FROM TBL_HIGH WHERE REG_DATE < '".$reg_date."' ";
					//FROM TBL_BOARD WHERE CONCAT(REG_DATE, BB_NO) < '".$reg_date.$bb_no."' ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((H_TITLE like '%".$search_str."%') or (H_CONTENTS like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
								
		//$query .= " ORDER BY BB_PO DESC limit 1";
		$query .= " ORDER BY REG_DATE ASC limit 1";
		
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


	// 신청자

	function listHighApplication($db, $h_no, $app_who, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

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

		$query = "SELECT @rownum:= @rownum - 1  as rn, APP_NO, H_NO, APP_WHO, APP_WHO_ETC, APP_NAME, APP_GRADE, APP_GRADE_ETC, APPLY_SCHOOL, 
										 SCH_CODE, SCH_AREA, APP_HPHONE, APP_EMAIL, APP_MEMO, APP_PLACE, APP_DATE, APP_TIME, APP_CNT, APP_FLAG, 
										 APP_INFO_01, APP_INFO_02, APP_INFO_03, APP_INFO_04, APP_INFO_05, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_HIGH_APPLICATION WHERE 1 = 1 ";

		if ($h_no <> "") {
			$query .= " AND H_NO = ? ";
			$types .= "s";
			$bindParams[] = $h_no;
		}

		if ($app_who <> "") {
			$query .= " AND APP_WHO = ? ";
			$types .= "s";
			$bindParams[] = $app_who;
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
				$query .= " AND ((APPLY_SCHOOL like CONCAT('%',?,'%')) or (APP_NAME like CONCAT('%',?,'%'))) ";
		
				$types .= "ss";
				$bindParams[] = $search_str;
				$bindParams[] = $search_str;

			}else{
				$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";

				$types .= "s";
				$bindParams[] = $search_str;

			}
		}

		$query .= " ORDER BY REG_DATE DESC limit ".$offset.", ".$nRowCount;

		//echo $query;

		$stmt = $db->prepare($query);
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


	function totalCntHighApplication($db, $h_no, $app_who, $use_tf, $del_tf, $search_field, $search_str){

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_HIGH_APPLICATION WHERE 1 = 1 ";

		if ($h_no <> "") {
			$query .= " AND H_NO = ? ";
			$types .= "s";
			$bindParams[] = $h_no;
		}

		if ($app_who <> "") {
			$query .= " AND APP_WHO = ? ";
			$types .= "s";
			$bindParams[] = $app_who;
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
				$query .= " AND ((APPLY_SCHOOL like CONCAT('%',?,'%')) or (APP_NAME like CONCAT('%',?,'%'))) ";
		
				$types .= "ss";
				$bindParams[] = $search_str;
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


	function insertHighApplication($db, $arr_data) {

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

		$query = "INSERT INTO TBL_HIGH_APPLICATION (".$set_field." REG_DATE) 
					values (".$set_value." now());";

//echo $query;
//exit;
/*
		foreach ($arr_data as $key => $value) {
				$str_set_field .= $key.","; 
				$str_set_value .= "'".$value."',"; 
		}
*/
//echo $str_set_value;

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {

			$stmt->close();
			$new_app_no = mysqli_insert_id($db);
			return $new_app_no;
			exit;
		} else {
			
			//echo $db->error;
			$stmt->close();
			return false;
			exit;
		}
	}

	function selectHighApplication($db, $app_no) {

		$query = "SELECT * FROM TBL_HIGH_APPLICATION WHERE DEL_TF = 'N' AND APP_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $app_no);
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

	function updateHighApplication($db, $arr_data, $app_no) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		foreach ($arr_data as $key => $value) {
			$value = ($value === null) ? '' : $value;
			$set_query_str .= $key." = ?,";
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "UPDATE TBL_HIGH_APPLICATION SET ".$set_query_str. "
							UP_DATE				=	now()
				WHERE APP_NO = ? ";

		$types .= "s";
		$bindParams[] = $app_no;

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			echo $db->error;
			$stmt->close();
			return false;
		}
	}

	function getApplicationHphoneCnt($db, $h_no, $app_hphone, $app_no) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_HIGH_APPLICATION
							WHERE DEL_TF = 'N' ";

		if ($h_no <> "") {
			$query .= " AND H_NO = ? ";
			$types .= "s";
			$bindParams[] = $h_no;
		}

		if ($app_hphone <> "") {
			$query .= " AND APP_HPHONE = ? ";
			$types .= "s";
			$bindParams[] = $app_hphone;
		}

		if ($app_no <> "") {
			$query .= " AND APP_NO <> ? ";
			$types .= "s";
			$bindParams[] = $app_no;
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
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

	function confirmHighApplication($db, $app_name, $app_hphone, $h_no) {

		$query = "SELECT H_NO, APP_NO
							FROM TBL_HIGH_APPLICATION WHERE APP_NAME = ? AND APP_HPHONE = ? AND H_NO = ? AND DEL_TF = 'N' ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $app_name, $app_hphone, $h_no);
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