<?
	# =============================================================================
	# File Name    : services.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-21
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listServices($conn, $s_no, $s_type, $s_title, $s_color, $s_date, $s_hour, $s_min, $e_date, $e_hour, $e_min, $date_use_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntServices($conn, $s_no, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($conn,$query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, S_NO, S_TYPE, S_TITLE, S_COLOR, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DATE_USE_TF, DISP_SEQ,
						  USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, S_URL
							FROM TBL_SERVICES WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, S_NO DESC";  //limit ".$offset.", ".$nRowCount;

		//echo $query;
		//exit;

		$result = mysqli_query($conn,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntServices($conn, $s_no, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_SERVICES WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$result = mysqli_query($conn,$query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function insertServices($conn, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_SERVICES (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_s_no = mysqli_insert_id($db);  //insert 후 s_no값을 알아오기 위한 구분
			return $new_s_no;
		}
	}

	function selectServices($conn, $s_no) {

		$query = "SELECT * FROM TBL_SERVICES WHERE S_NO='$s_no'";

		$result = mysqli_query($conn,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function updateServices($conn, $arr_data, $s_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_SERVICES SET ".$set_query_str." ";
		$query .= "s_no = '$s_no' WHERE S_NO = '$s_no' ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteServices($conn, $adm_no, $s_no) {

		$query = "UPDATE TBL_SERVICES SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE S_NO = '$s_no' ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	function updateServicesUseTF($db, $use_tf, $up_adm, $s_no) {
		
		$query="UPDATE TBL_SERVICES SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE S_NO			= '$s_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderServices($db, $disp_seq_no, $s_no) {

		$query="UPDATE TBL_SERVICES SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE S_NO	= '$s_no' ";

		echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


?>