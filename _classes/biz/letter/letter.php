<?
	# =============================================================================
	# File Name    : letter.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-12-23
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listNewLetter($db, $start_date, $end_date, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, REG_NM, REG_EMAIL, MEMO, REG_IP, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE  
								FROM TBL_NEWS_LETTER
							 WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND REG_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND REG_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (REG_NM like '%".$search_str."%' OR REG_EMAIL like '%".$search_str."%' OR MEMO like '%".$search_str."%')";
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

	function totalCntNewLetter($db, $start_date, $end_date, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(SEQ_NO) CNT 
							 FROM TBL_NEWS_LETTER
							WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND REG_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND REG_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (REG_NM like '%".$search_str."%' OR REG_EMAIL like '%".$search_str."%' OR MEMO like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	function insertNewLetter($db, $arr_data) {

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

		$query = "INSERT INTO TBL_NEWS_LETTER (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectNewLetter($db, $seq_no) {

		$query = "SELECT * FROM TBL_NEWS_LETTER WHERE SEQ_NO = '$seq_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateNewLetter($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_NEWS_LETTER SET ".$set_query_str." ";
		$query .= "SEQ_NO = '$seq_no' WHERE SEQ_NO = '$seq_no' ";

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

	function deleteNewLetter($db, $adm_no, $seq_no) {

		$query = "UPDATE TBL_NEWS_LETTER SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE SEQ_NO = '$seq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>