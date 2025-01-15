<?
	# =============================================================================
	# File Name    : gate.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-15
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listGate($db, $g_no, $g_type, $g_title, $s_date, $s_hour, $s_min, $e_date, $e_hour, $e_min, $date_use_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntGate($db, $g_no, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db,$query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, G_NO, G_TYPE, G_TITLE, G_URL, G_TARGET, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DATE_USE_TF, DISP_SEQ,
						  USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_GATE WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, G_NO DESC";  //limit ".$offset.", ".$nRowCount;

		//echo $query;
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


	function totalCntGate($db, $g_no, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_GATE WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$result = mysqli_query($db,$query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function insertGate($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_GATE (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

//echo $query;
//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_g_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_g_no;
		}
	}

	function selectGate($db, $g_no) {

		$query = "SELECT * FROM TBL_GATE WHERE G_NO='$g_no'";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateGate($db, $arr_data, $g_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_GATE SET ".$set_query_str." ";
		$query .= "g_no = '$g_no' WHERE G_NO = '$g_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteGate($db, $adm_no, $g_no) {

		$query = "UPDATE TBL_GATE SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE G_NO = '$g_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	function updateGateUseTF($db, $use_tf, $up_adm, $g_no) {
		
		$query="UPDATE TBL_GATE SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE G_NO			= '$g_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderGate($db, $disp_seq_no, $g_no) {

		$query="UPDATE TBL_GATE SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE G_NO	= '$g_no' ";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


?>