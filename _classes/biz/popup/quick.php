<?
	# =============================================================================
	# File Name    : quick.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-19
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listQuick($db, $mtype, $q_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntQuick($db, $mtype, $q_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db,$query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, Q_NO, Q_MTYPE, Q_COLOR, Q_TYPE, Q_TITLE, Q_SUBTITLE, Q_DESCRIPTION, Q_URL, Q_TARGET, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DATE_USE_TF, DISP_SEQ,
						  USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_QUICK WHERE 1 = 1 ";

		if ($mtype <> "") {
			$query .= " AND Q_MTYPE = '".$mtype."' ";
		}

		if ($q_type <> "") {
			$query .= " AND Q_TYPE = '".$q_type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, Q_NO DESC";  //limit ".$offset.", ".$nRowCount;

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


	function totalCntQuick($db, $mtype, $q_type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_QUICK WHERE 1 = 1 ";

		if ($mtype <> "") {
			$query .= " AND Q_MTYPE = '".$mtype."' ";
		}

		if ($q_type <> "") {
			$query .= " AND Q_TYPE = '".$q_type."' ";
		}

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

	function insertQuick($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_QUICK (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

//echo $query;
//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_q_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_q_no;
		}
	}

	function selectQuick($db, $q_no) {

		$query = "SELECT * FROM TBL_QUICK WHERE Q_NO='$q_no'";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateQuick($db, $arr_data, $q_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_QUICK SET ".$set_query_str." ";
		$query .= "q_no = '$q_no' WHERE Q_NO = '$q_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteQuick($db, $adm_no, $q_no) {

		$query = "UPDATE TBL_QUICK SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE Q_NO = '$q_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	function updateQuickUseTF($db, $use_tf, $up_adm, $q_no) {
		
		$query="UPDATE TBL_QUICK SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE Q_NO			= '$q_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderQuick($db, $disp_seq_no, $q_no) {

		$query="UPDATE TBL_QUICK SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE Q_NO	= '$q_no' ";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function mainlistQuick($db, $mtype, $type) {

		$query = "SELECT Q_NO, Q_MTYPE, Q_COLOR, Q_TYPE, Q_TITLE, Q_SUBTITLE, Q_DESCRIPTION, Q_URL, Q_TARGET, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DATE_USE_TF, DISP_SEQ,
						  USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_QUICK WHERE DEL_TF = 'N' AND USE_TF = 'Y' ";

		if ($mtype <> "") {
			$query .= " AND Q_MTYPE = '".$mtype."' ";
		}
		
		if ($type <> "") {
			$query .= " AND Q_TYPE = '".$type."' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, Q_NO DESC";

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

?>