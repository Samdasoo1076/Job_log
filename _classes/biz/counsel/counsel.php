<?
	# =============================================================================
	# File Name    : counsel.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listCounsel($db, $start_date, $end_date, $use_type, $counsel_adm_no, $counsel_type, $answer_type, $answer_adm_no, $mem_no, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.COUNSEL_DATE, A.COUNSEL_TYPE, A.COUNSEL_ADM_NM, A.COUNSEL_ADM_NO, 
										 A.MEM_NO, A.MEM_NUM, A.ASK, A.ANSWER, A.ANSWER_DATE, A.ANSWER_TYPE, A.ANSWER_ADM_NM, A.ANSWER_ADM_NO, A.USE_TF,
										 A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE, B.USE_TYPE, B.MEM_NM, B.PHONE, B.HPHONE
							 FROM TBL_COUNSEL A, TBL_MEMBER B
							WHERE A.MEM_NO = B.MEM_NO AND A.DEL_TF = 'N' AND B.DEL_TF = 'N' ";

		if ($start_date <> "") {
			$query .= " AND A.COUNSEL_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND A.COUNSEL_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($use_type <> "") {
			$query .= " AND B.USE_TYPE = '$use_type' ";
		}

		if ($counsel_adm_no <> "") {
			$query .= " AND A.COUNSEL_ADM_NO = '$counsel_adm_no' ";
		}

		if ($counsel_type <> "") {
			$query .= " AND A.COUNSEL_TYPE = '$counsel_type' ";
		}

		if ($answer_type <> "") {
			$query .= " AND A.ANSWER_TYPE = '$answer_type' ";
		}

		if ($answer_adm_no <> "") {
			$query .= " AND A.ANSWER_ADM_NO = '$answer_adm_no' ";
		}

		if ($mem_no <> "") {
			$query .= " AND A.MEM_NO = '$mem_no' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
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

	function totalCntCounsel($db, $start_date, $end_date, $use_type, $counsel_adm_no, $counsel_type, $answer_type, $answer_adm_no, $mem_no, $search_field, $search_str){

		$query ="SELECT COUNT(A.SEQ_NO) CNT 
							 FROM TBL_COUNSEL A, TBL_MEMBER B
							WHERE A.MEM_NO = B.MEM_NO AND A.DEL_TF = 'N' AND B.DEL_TF = 'N' ";

		if ($start_date <> "") {
			$query .= " AND A.COUNSEL_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND A.COUNSEL_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($use_type <> "") {
			$query .= " AND B.USE_TYPE = '$use_type' ";
		}

		if ($counsel_adm_no <> "") {
			$query .= " AND A.COUNSEL_ADM_NO = '$counsel_adm_no' ";
		}

		if ($counsel_type <> "") {
			$query .= " AND A.COUNSEL_TYPE = '$counsel_type' ";
		}

		if ($answer_type <> "") {
			$query .= " AND A.ANSWER_TYPE = '$answer_type' ";
		}

		if ($answer_adm_no <> "") {
			$query .= " AND A.ANSWER_ADM_NO = '$answer_adm_no' ";
		}

		if ($mem_no <> "") {
			$query .= " AND A.MEM_NO = '$mem_no' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	function insertCounsel($db, $arr_data) {

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
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_COUNSEL (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			//$new_mem_no = mysqli_insert_id($db);
			//return $new_mem_no;
			return true;
		}
	}

	function selectCounsel($db, $seq_no) {

		$query = "SELECT * FROM TBL_COUNSEL WHERE SEQ_NO = '$seq_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateCounsel($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_COUNSEL SET ".$set_query_str." ";
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

	function deleteCounsel($db, $adm_no, $seq_no) {

		$query = "UPDATE TBL_COUNSEL SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE SEQ_NO = '$seq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function listMemberCounsel($db, $mem_no) {

		$query = "SELECT A.SEQ_NO, A.COUNSEL_DATE, A.COUNSEL_TYPE, A.COUNSEL_ADM_NM, A.COUNSEL_ADM_NO, 
										 A.MEM_NO, A.MEM_NUM, A.ASK, A.ANSWER, A.ANSWER_DATE, A.ANSWER_TYPE, A.ANSWER_ADM_NM, A.ANSWER_ADM_NO, A.USE_TF,
										 A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE, B.USE_TYPE, B.MEM_NM, B.PHONE, B.HPHONE
							 FROM TBL_COUNSEL A, TBL_MEMBER B
							WHERE A.MEM_NO = B.MEM_NO AND A.DEL_TF = 'N' AND B.DEL_TF = 'N' ";

		if ($mem_no <> "") {
			$query .= " AND A.MEM_NO = '$mem_no' ";
		}

		$query .= " ORDER BY A.SEQ_NO DESC ";
		
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


?>