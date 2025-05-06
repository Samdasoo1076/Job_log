<?
	# =============================================================================
	# File Name    : apply.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2023-05-05
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listApply($db, $app_flag, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * ,@rownum:= @rownum - 1  as rn
								FROM TBL_APPLY
							 WHERE 1 = 1 ";

		if ($app_flag <> "") {
			$query .= " AND APP_FLAG = '$app_flag' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (APP_NM like '%".$search_str."%' OR PRO_NM like '%".$search_str."%' OR SCH_NM like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " REG_DATE ";
		}

		if ($order_str == "") {
			$order_str = " DESC ";
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
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

	function totalCntApply($db, $app_flag, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY
							WHERE 1 = 1 ";

		if ($app_flag <> "") {
			$query .= " AND APP_FLAG = '$app_flag' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (APP_NM like '%".$search_str."%' OR PRO_NM like '%".$search_str."%' OR SCH_NM like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertApply($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_APPLY (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_app_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_app_no;
		}
	}

	function confirmApply($db, $app_nm, $app_hphone) {

		$query = "SELECT APP_NO
							FROM TBL_APPLY WHERE APP_NM = '".$app_nm."' AND APP_HPHONE = '".$app_hphone."' AND DEL_TF = 'N' ";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateApply($db, $arr_data, $app_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_APPLY SET ".$set_query_str." ";
		$query .= "APP_NO = '$app_no' WHERE APP_NO = '$app_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectApply($db, $app_no) {

		$query = "SELECT * FROM TBL_APPLY WHERE APP_NO='$app_no' AND DEL_TF='N'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function getApplySchoolCnt($db, $sch_nm, $app_no) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY
							WHERE DEL_TF = 'N' ";

		if ($sch_nm <> "") {
			$query .= " AND SCH_NM = '$sch_nm' ";
		}

		if ($app_no <> "") {
			$query .= " AND APP_NO <> '$app_no' ";
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	function getApplyHphoneCnt($db, $app_hphone, $app_no) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY
							WHERE DEL_TF = 'N' ";

		if ($app_hphone <> "") {
			$query .= " AND APP_HPHONE = '$app_hphone' ";
		}

		if ($app_no <> "") {
			$query .= " AND APP_NO <> '$app_no' ";
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function searchProgram($db, $search_text, $app_no) {

		$query = "SELECT PRO_NO, PRO_NM, PRO_CNT, 
										 (SELECT COUNT(APP_NO) AS APP_CNT FROM TBL_APPLY WHERE DEL_TF = 'N' AND PRO_NO = TBL_APPLY_PROGRAM.PRO_NO AND APP_NO <> '".$app_no."' ) AS APP_CNT
							FROM TBL_APPLY_PROGRAM WHERE 1 = 1 ";

		if ($search_text <> "") {
			$query .= " AND PRO_NO = '".$search_text."' ";
		}
		
		$query .= " LIMIT 1";

		//echo $query;

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function getApplyTimeCnt($db, $app_date, $app_hh, $pro_no, $app_no) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY
							WHERE DEL_TF = 'N' ";

		if ($app_date <> "") {
			$query .= " AND APP_DATE = '$app_date' ";
		}

		if ($app_hh <> "") {
			$query .= " AND APP_HH = '$app_hh' ";
		}

		if ($pro_no <> "") {
			$query .= " AND pro_no = '$pro_no' ";
		}

		if ($app_no <> "") {
			$query .= " AND APP_NO <> '$app_no' ";
		}

		//echo $query."<br>";
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function applyTimeAbleList($db, $app_date, $pro_no, $app_no) {

		$query = "SELECT DCODE, DCODE_NM,
										(SELECT COUNT(APP_NO) FROM TBL_APPLY 
											WHERE DEL_TF ='N' AND USE_TF ='Y' AND APP_HH = TBL_CODE_DETAIL.DCODE AND PRO_NO = '".$pro_no."' AND APP_DATE = '".$app_date."' AND APP_NO <> '".$app_no."') AS APP_CNT
							 FROM TBL_CODE_DETAIL WHERE PCODE = 'APPLY_TIME' AND DEL_TF ='N' AND USE_TF ='Y' ORDER BY DCODE_SEQ_NO ASC ";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;

	}

	// 교사 연수

	function listApplyTeacher($db, $app_sch_no, $app_flag, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * ,@rownum:= @rownum - 1  as rn
								FROM TBL_APPLY_TEACHER A, TBL_APPLY_TEACHER_SCH B
							 WHERE A.APP_SCH_NO = B.APP_SCH_NO ";

		if ($app_sch_no <> "") {
			$query .= " AND A.APP_SCH_NO = '$app_sch_no' ";
		}

		if ($app_flag <> "") {
			$query .= " AND A.APP_FLAG = '$app_flag' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (A.APP_NM like '%".$search_str."%' OR A.SCH_NM like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " A.REG_DATE ";
		}

		if ($order_str == "") {
			$order_str = " DESC ";
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
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



	function totalCntApplyTeacher($db, $app_sch_no, $app_flag, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY_TEACHER
							WHERE 1 = 1 ";

		if ($app_sch_no <> "") {
			$query .= " AND APP_SCH_NO = '$app_sch_no' ";
		}


		if ($app_flag <> "") {
			$query .= " AND APP_FLAG = '$app_flag' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (APP_NM like '%".$search_str."%' OR SCH_NM like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listApplyTeacherSch($db, $app_no) {

		$query = "SELECT * ,
										(SELECT COUNT(APP_NO) FROM TBL_APPLY_TEACHER WHERE DEL_TF = 'N' AND APP_SCH_NO = TBL_APPLY_TEACHER_SCH.APP_SCH_NO AND APP_NO <> '$app_no') AS APP_CNT
								FROM TBL_APPLY_TEACHER_SCH
							 WHERE 1 = 1 ";

		$query .= " ORDER BY APP_SCH_NO ASC ";
		
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


	function getApplyTeacherSchoolCnt($db, $app_sch_no, $sch_code, $app_no) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY_TEACHER
							WHERE DEL_TF = 'N' ";

		if ($app_sch_no <> "") {
			$query .= " AND APP_SCH_NO = '$app_sch_no' ";
		}

		if ($sch_code <> "") {
			$query .= " AND SCH_CODE = '$sch_code' ";
		}

		if ($app_no <> "") {
			$query .= " AND APP_NO <> '$app_no' ";
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function selectApplyTeacher($db, $app_no) {

		$query = "SELECT * 
								FROM TBL_APPLY_TEACHER A, TBL_APPLY_TEACHER_SCH B WHERE A.APP_SCH_NO = B.APP_SCH_NO AND A.APP_NO='$app_no' AND A.DEL_TF='N'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function getApplyTeacherHphoneCnt($db, $app_hphone, $app_no) {

		$query ="SELECT COUNT(APP_NO) CNT 
							 FROM TBL_APPLY_TEACHER
							WHERE DEL_TF = 'N' ";

		if ($app_hphone <> "") {
			$query .= " AND APP_HPHONE = '$app_hphone' ";
		}

		if ($app_no <> "") {
			$query .= " AND APP_NO <> '$app_no' ";
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertApplyTeacher($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_APPLY_TEACHER (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_app_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_app_no;
		}
	}

	function searchTeacherProgram($db, $search_text, $app_no) {

		$query = "SELECT APP_SCH_NO, APP_SCH_CNT, 
										 (SELECT COUNT(APP_NO) AS APP_CNT FROM TBL_APPLY_TEACHER WHERE DEL_TF = 'N' AND APP_SCH_NO = TBL_APPLY_TEACHER_SCH.APP_SCH_NO AND APP_NO <> '".$app_no."' ) AS APP_CNT
							FROM TBL_APPLY_TEACHER_SCH WHERE 1 = 1 ";

		if ($search_text <> "") {
			$query .= " AND APP_SCH_NO = '".$search_text."' ";
		}
		
		$query .= " LIMIT 1";

		//echo $query;

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function confirmApplyTeacher($db, $app_nm, $app_hphone) {

		$query = "SELECT APP_NO
							FROM TBL_APPLY_TEACHER WHERE APP_NM = '".$app_nm."' AND APP_HPHONE = '".$app_hphone."' AND DEL_TF = 'N' ";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function updateApplyTeacher($db, $arr_data, $app_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_APPLY_TEACHER SET ".$set_query_str." ";
		$query .= "APP_NO = '$app_no' WHERE APP_NO = '$app_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


?>