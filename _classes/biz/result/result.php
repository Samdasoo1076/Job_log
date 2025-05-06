<?
	# =============================================================================
	# File Name    : result.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-05-13
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listMonthReport($db, $start_date, $end_date, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * , @rownum:= @rownum - 1  as rn
								FROM TBL_MONTH_REPORT
							 WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND REPORT_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND REPORT_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($idx_no <> "") {
			$query .= " AND IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (REPORT_MEMO like '%".$search_str."%')";
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

	function totalCntMonthReport($db, $start_date, $end_date, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(MR_NO) CNT 
							 FROM TBL_MONTH_REPORT
							WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND REPORT_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND REPORT_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($idx_no <> "") {
			$query .= " AND IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (REPORT_MEMO like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	function insertMonthReport($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_MONTH_REPORT (".$set_field.") 
					values (".$set_value."); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_mr_no = mysqli_insert_id($db);
			return $new_mr_no;
		}
	}

	function selectMonthReport($db, $mr_no) {

		$query = "SELECT * FROM TBL_MONTH_REPORT WHERE MR_NO = '$mr_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateMonthReport($db, $arr_data, $mr_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_MONTH_REPORT SET ".$set_query_str." ";
		$query .= "MR_NO = '$mr_no' WHERE MR_NO = '$mr_no' ";

		//echo $query."<br>";

		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMonthReport($db, $adm_no, $mr_no) {

		$query = "UPDATE TBL_MONTH_REPORT SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE MR_NO = '$mr_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listPersonReport($db, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * , @rownum:= @rownum - 1  as rn
								FROM TBL_PERSON_REPORT
							 WHERE 1 = 1 ";

		if ($idx_no <> "") {
			$query .= " AND IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (PR_NAME like '%".$search_str."%' OR PR_MEMO like '%".$search_str."%')";
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

	function totalCntPersonReport($db, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(PR_NO) CNT 
							 FROM TBL_PERSON_REPORT
							WHERE 1 = 1 ";

		if ($idx_no <> "") {
			$query .= " AND IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (PR_NAME like '%".$search_str."%' OR PR_MEMO like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	function insertPersonReport($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_PERSON_REPORT (".$set_field.") 
					values (".$set_value."); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectPersonReport($db, $pr_no) {

		$query = "SELECT * FROM TBL_PERSON_REPORT WHERE PR_NO = '$pr_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updatePersonReport($db, $arr_data, $pr_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_PERSON_REPORT SET ".$set_query_str." ";
		$query .= "PR_NO = '$pr_no' WHERE PR_NO = '$pr_no' ";

		//echo $query."<br>";

		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deletePersonReport($db, $adm_no, $pr_no) {

		$query = "UPDATE TBL_PERSON_REPORT SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE PR_NO = '$pr_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listMonthReportBook($db, $mr_no) {

		$query = "SELECT * 
								FROM TBL_MONTH_REPORT_BOOK
							 WHERE MR_NO = '$mr_no' ";

		$query .= " ORDER BY MR_BOOK_NO ASC";
		
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
	
	function insertMonthReportBook($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_MONTH_REPORT_BOOK (".$set_field.") 
					values (".$set_value."); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMonthReportBook($db, $mr_no) {

		$query = "DELETE FROM TBL_MONTH_REPORT_BOOK WHERE MR_NO = '$mr_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}



	function insertAuthorReport($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_AUTHOR_REPORT (".$set_field.") 
					values (".$set_value."); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateAuthorReport($db, $arr_data, $ar_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_AUTHOR_REPORT SET ".$set_query_str." ";
		$query .= "AR_NO = '$ar_no' WHERE AR_NO = '$ar_no' ";

		//echo $query."<br>";
		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectAuthorReport($db, $ar_no) {

		$query = "SELECT * FROM TBL_AUTHOR_REPORT WHERE AR_NO = '$ar_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectAuthorReportWithIdxNo($db, $aseq_no, $idx_no) {

		$query = "SELECT * FROM TBL_AUTHOR_REPORT WHERE ASEQ_NO = '$aseq_no' AND IDX_NO = '$idx_no' AND DEL_TF = 'N' ";
		
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

	function listManagerMonthReport($db, $manager_no, $start_date, $end_date, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * , B.REG_DATE, @rownum:= @rownum - 1  as rn
								FROM TBL_APPLY A, TBL_MONTH_REPORT B, TBL_GROUPS_INFO C
							WHERE A.SEQ_NO = B.SEQ_NO
								AND A.IDX_NO = B.IDX_NO
								AND A.IDX_NO = C.IDX 
								AND A.DEL_TF = 'N' 
								AND B.DEL_TF = 'N' 
								AND C.DEL_TF = 'N'  ";

		if ($manager_no <> "") {
			$query .= " AND A.MANAGER_NO = '".$manager_no."' ";
		}

		if ($start_date <> "") {
			$query .= " AND B.REPORT_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND B.REPORT_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($idx_no <> "") {
			$query .= " AND B.IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND B.SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND B.ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (C.GROUP_NAME like '%".$search_str."%' OR B.REPORT_MEMO like '%".$search_str."%' OR C.GROUP_RUNNER like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR A.APP_PHONE like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " B.REG_DATE ";
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

	function totalCntManagerMonthReport($db, $manager_no, $start_date, $end_date, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(MR_NO) CNT 
							 FROM TBL_APPLY A, TBL_MONTH_REPORT B, TBL_GROUPS_INFO C
							WHERE A.SEQ_NO = B.SEQ_NO
								AND A.IDX_NO = B.IDX_NO
								AND A.IDX_NO = C.IDX 
								AND A.DEL_TF = 'N' 
								AND B.DEL_TF = 'N' 
								AND C.DEL_TF = 'N' ";

		if ($manager_no <> "") {
			$query .= " AND A.MANAGER_NO = '".$manager_no."' ";
		}

		if ($start_date <> "") {
			$query .= " AND B.REPORT_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND B.REPORT_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($idx_no <> "") {
			$query .= " AND B.IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND B.SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND B.ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (C.GROUP_NAME like '%".$search_str."%' OR B.REPORT_MEMO like '%".$search_str."%' OR C.GROUP_RUNNER like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR A.APP_PHONE like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listManagerPersonReport($db, $manager_no, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * , B.REG_DATE, @rownum:= @rownum - 1  as rn
								FROM TBL_APPLY A, TBL_PERSON_REPORT B, TBL_GROUPS_INFO C
							WHERE A.SEQ_NO = B.SEQ_NO
								AND A.IDX_NO = B.IDX_NO
								AND A.IDX_NO = C.IDX 
								AND A.DEL_TF = 'N' 
								AND B.DEL_TF = 'N' 
								AND C.DEL_TF = 'N' ";

		if ($manager_no <> "") {
			$query .= " AND A.MANAGER_NO = '".$manager_no."' ";
		}

		if ($idx_no <> "") {
			$query .= " AND B.IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND B.SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND B.ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (B.PR_NAME like '%".$search_str."%' OR B.PR_MEMO like '%".$search_str."%' OR C.GROUP_NAME like '%".$search_str."%' OR C.GROUP_RUNNER like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR A.APP_PHONE like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' )";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " B.REG_DATE ";
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

	function totalCntManagerPersonReport($db, $manager_no, $idx_no, $seq_no, $aseq_no, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(PR_NO) CNT 
							 FROM TBL_APPLY A, TBL_PERSON_REPORT B, TBL_GROUPS_INFO C
							WHERE A.SEQ_NO = B.SEQ_NO
								AND A.IDX_NO = B.IDX_NO
								AND A.IDX_NO = C.IDX 
								AND A.DEL_TF = 'N' 
								AND B.DEL_TF = 'N' 
								AND C.DEL_TF = 'N'  ";

		if ($manager_no <> "") {
			$query .= " AND A.MANAGER_NO = '".$manager_no."' ";
		}

		if ($idx_no <> "") {
			$query .= " AND B.IDX_NO = '$idx_no' ";
		}

		if ($seq_no <> "") {
			$query .= " AND B.SEQ_NO = '$seq_no' ";
		}

		if ($aseq_no <> "") {
			$query .= " AND B.ASEQ_NO = '$aseq_no' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (B.PR_NAME like '%".$search_str."%' OR B.PR_MEMO like '%".$search_str."%' OR C.GROUP_NAME like '%".$search_str."%' OR C.GROUP_RUNNER like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR A.APP_PHONE like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' )";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


?>