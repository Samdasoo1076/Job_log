<?
	# =============================================================================
	# File Name    : tour.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2023-08-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listTour($db, $apply_flag, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * ,@rownum:= @rownum - 1  as rn
								FROM TBL_TOUR
							 WHERE 1 = 1 ";

		if ($apply_flag <> "") {
			$query .= " AND APPLY_FLAG = '$apply_flag' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (APPLY_SCHOOL like '%".$search_str."%' OR APPLY_NAME like '%".$search_str."%' OR APPLY_HPHONE like '%".$search_str."%')";
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

	function totalCntTour($db, $apply_flag, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(APPLY_NO) CNT 
							 FROM TBL_TOUR
							WHERE 1 = 1 ";

		if ($apply_flag <> "") {
			$query .= " AND APPLY_FLAG = '$apply_flag' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (APPLY_SCHOOL like '%".$search_str."%' OR APPLY_NAME like '%".$search_str."%' OR APPLY_HPHONE like '%".$search_str."%')";
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

	function insertTour($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_TOUR (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_apply_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_apply_no;
		}
	}

	function confirmTour($db, $apply_name, $apply_hphone) {

		$query = "SELECT APPLY_NO
							FROM TBL_TOUR WHERE APPLY_NAME = '".$apply_name."' AND APPLY_HPHONE = '".$apply_hphone."' AND DEL_TF = 'N' ";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateTour($db, $arr_data, $apply_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_TOUR SET ".$set_query_str." ";
		$query .= "APPLY_NO = '$apply_no' WHERE APPLY_NO = '$apply_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectTour($db, $apply_no) {

		$query = "SELECT * FROM TBL_TOUR WHERE APPLY_NO='$apply_no' AND DEL_TF='N'";

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

	function getTourSchoolCnt($db, $apply_school, $apply_no) {

		$query ="SELECT COUNT(APPLY_NO) CNT 
							 FROM TBL_TOUR
							WHERE DEL_TF = 'N' ";

		if ($apply_school <> "") {
			$query .= " AND APPLY_SCHOOL = '$apply_school' ";
		}

		if ($apply_no <> "") {
			$query .= " AND APPLY_NO <> '$apply_no' ";
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	function getTourHphoneCnt($db, $apply_hphone, $apply_no) {

		$query ="SELECT COUNT(APPLY_NO) CNT 
							 FROM TBL_TOUR
							WHERE DEL_TF = 'N' ";

		if ($apply_hphone <> "") {
			$query .= " AND APPLY_HPHONE = '$apply_hphone' ";
		}

		if ($apply_no <> "") {
			$query .= " AND APPLY_NO <> '$apply_no' ";
		}
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

?>