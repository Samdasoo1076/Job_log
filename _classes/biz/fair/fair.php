<?
	# =============================================================================
	# File Name    : fair.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2023-07-16
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listFairApply($db, $fa_no, $fa_type, $apply_tf, $use_tf, $del_tf, $f, $s, $nPage, $nRowCount) {

		$total_cnt = totalCntFairApply($db, $fa_no, $fa_type, $apply_tf, $use_tf, $del_tf, $f, $s);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, FA_NO, FA_TYPE, FA_NAME, FA_PHONE, FA_EMAIL, FA_PWD, FA_MEMO, 
										 AGREE_TF, APPLY_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, SEND_DATE
							FROM TBL_FAIR_APPLY WHERE 1 = 1 ";

		if ($fa_type <> "") {
			$query .= " AND FA_TYPE = '".$fa_type."' ";
		}

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			if ($f == "ALL") {
				$query .= " AND (FA_NAME like '%".$s."%' OR FA_MEMO like '%".$s."%')";
			} else { 
				$query .= " AND ".$f." like '%".$s."%' ";
			}
		}

		$query .= " ORDER BY FA_NO DESC";  //limit ".$offset.", ".$nRowCount;

		//echo $query;
		//exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntFairApply($db, $fa_no, $fa_type, $apply_tf, $use_tf, $del_tf, $f, $s){

		$query ="SELECT COUNT(*) CNT FROM TBL_FAIR_APPLY WHERE 1 = 1 ";

		if ($fa_type <> "") {
			$query .= " AND FA_TYPE = '".$fa_type."' ";
		}

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			if ($f == "ALL") {
				$query .= " AND (FA_NAME like '%".$s."%' OR FA_MEMO like '%".$s."%')";
			} else { 
				$query .= " AND ".$f." like '%".$s."%' ";
			}
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function insertFairApply($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_FAIR_APPLY (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_fa_no = mysqli_insert_id($db);  //insert 후 e_no값을 알아오기 위한 구분
			return $new_fa_no;
		}
	}

	function selectFairApply($db, $fa_no) {

		$query = "SELECT * FROM TBL_FAIR_APPLY WHERE FA_NO='$fa_no'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateFairApply($db, $arr_data, $fa_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_FAIR_APPLY SET ".$set_query_str." ";
		$query .= "FA_NO = '$fa_no' WHERE FA_NO = '$fa_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateFairApplyUseTF($db, $use_tf, $up_adm, $fa_no) {
		
		$query="UPDATE TBL_FAIR_APPLY SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE FA_NO			= '$fa_no' ";

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

	function deleteFairApply($db, $adm_no, $fa_no) {

		$query = "UPDATE TBL_FAIR_APPLY SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE FA_NO = '$fa_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

?>