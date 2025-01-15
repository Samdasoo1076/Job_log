<?
	function listFind($db, $candidate, $job, $age, $order_field, $order_str, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntFind($db, $candidate, $job, $age, $use_tf, $del_tf, $search_field, $search_str);
		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;
		
		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, CANDIDATE, RE_NAME, RE_TEL01, RE_TEL02, RE_TEL03, NAME, 
								RELATION, AGE, AREA, TEL01, TEL02, TEL03, HP01, HP02, HP03, EMAIL01, EMAIL02, JOB, CAREER, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_FIND WHERE 1=1 ";
		
		if ($candidate <> "") {
			$query .= " AND CANDIDATE = '".$candidate."' ";
		}
		if ($job <> "") {
			$query .= " AND JOB = '".$job."' ";
		}

		if ($age <> "") {
			$query .= " AND AGE = '".$age."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (NAME like '%".$search_str."%' OR AREA like '%".$search_str."%') ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
	
		
		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntFind($db, $candidate, $job, $age, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_FIND WHERE 1=1";
		
		
		if ($candidate <> "") {
			$query .= " AND CANDIDATE = '".$candidate."' ";
		}
		if ($job <> "") {
			$query .= " AND JOB = '".$job."' ";
		}

		if ($age <> "") {
			$query .= " AND AGE = '".$age."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (NAME like '%".$search_str."%' OR AREA like '%".$search_str."%') ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listFind_xls($db, $candidate, $job, $age, $order_field, $order_str, $con_use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT SEQ_NO, CANDIDATE, RE_NAME, RE_TEL01, RE_TEL02, RE_TEL03, NAME, 
								RELATION, AGE, AREA, TEL01, TEL02, TEL03, HP01, HP02, HP03, EMAIL01, EMAIL02, JOB, CAREER, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE FROM TBL_FIND WHERE 1 = 1 ";

		if ($candidate <> "") {
			$query .= " AND CANDIDATE = '".$candidate."' ";
		}
		if ($job <> "") {
			$query .= " AND JOB = '".$job."' ";
		}

		if ($age <> "") {
			$query .= " AND AGE = '".$age."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (NAME like '%".$search_str."%' OR AREA like '%".$search_str."%') ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		$query .= " ORDER BY ".$order_field." ".$order_str;
		

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectFind($db, $seq_no) {

		$query = "SELECT SEQ_NO, CANDIDATE, RE_NAME, RE_TEL01, RE_TEL02, RE_TEL03, NAME, 
								RELATION, AGE, AREA, TEL01, TEL02, TEL03, HP01, HP02, HP03, EMAIL01, EMAIL02, JOB, CAREER, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_FIND WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}
	
	$use_tf="Y";

	function insertFind($db, $CANDIDATE, $RE_NAME, $RE_TEL01, $RE_TEL02, $RE_TEL03, $NAME, $RELATION, $AGE, $AREA, $TEL01, $TEL02, $TEL03, $HP01, $HP02, $HP03, $EMAIL01, $EMAIL02, $JOB, $CAREER, $use_tf) {
		
		$query5="INSERT INTO TBL_FIND (CANDIDATE, RE_NAME, RE_TEL01, RE_TEL02, RE_TEL03, NAME, 
								RELATION, AGE, AREA, TEL01, TEL02, TEL03, HP01, HP02, HP03, EMAIL01, EMAIL02, JOB, CAREER, USE_TF, REG_DATE) 
														values ('$CANDIDATE', '$RE_NAME', '$RE_TEL01', '$RE_TEL02', '$RE_TEL03', '$NAME', '$RELATION', '$AGE', '$AREA', '$TEL01', '$TEL02', '$TEL03', '$HP01', '$HP02', '$HP03', '$EMAIL01', '$EMAIL02', '$JOB', '$CAREER','$use_tf', now()); ";
		
		//echo $query5;

		//exit;

		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}
	



?>