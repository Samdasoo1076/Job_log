<?
	# =============================================================================
	# File Name    : intro.php
	# Modlue       : 
	# Writer       : JeGal Jeong
	# Create Date  : 2022-12-08
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function insertIntro($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		//foreach ($arr_data as $key => $value) {
		//		$set_field .= $key.","; 
		//		$set_value .= "'".$value."',"; 
		//}
		
		foreach ($arr_data as $key => $value) {
    if (is_array($value)) {
        // 배열인 경우 JSON 등으로 변환하거나 무시
        continue;
    }
    $set_field .= $key . ",";
    $set_value .= "'" . mysqli_real_escape_string($db, $value) . "',";
}


		$query = "INSERT INTO TBL_INTRO (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			echo $query; 
			exit;
		} else {
			$new_intro_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_intro_no;
		}
	}

	function insertIntro2($db, $arr_data) {
		
		// 인트로 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_INTRO (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_intro_no = mysqli_insert_id($db);  //insert 후 intro_no값을 알아오기 위한 구분
			return $new_intro_no;
		}
	}

	// 세부항목 등록
	function insertIntroDetail($db, $arr_data) {

		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_INTRO_DETAIL (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_seq_no;
		}
	}

	function updateIntro($db, $arr_data, $intro_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_INTRO SET ".$set_query_str." ";
		$query .= "intro_no = '$intro_no' WHERE INTRO_NO = '$intro_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	//페이지 세부정보 UPDATE
	function updateIntroDetail($db, $arr_data, $i_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_INTRO_DETAIL SET ".$set_query_str." ";
		$query .= "i_no = '$i_no' WHERE I_NO = '$i_no' ";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteIntro($db, $adm_no, $intro_no) {

		$query = "UPDATE TBL_INTRO SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE INTRO_NO = '$intro_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	//인트로 페이지 세부정보 전체 DELETE
	function deleteIntroDetail($db, $adm_no, $intro_no) {

		$query = "UPDATE TBL_INTRO_DETAIL SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE INTRO_NO = '$intro_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	//PDF 페이지 세부정보 개별항목 DELETE
	function deleteIntroDetail_1($db, $adm_no, $i_no, $intro_no) {

		$query = "DELETE FROM TBL_INTRO_DETAIL WHERE I_NO = '$i_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			echo "<script>alert('성공');document.location.href='intro_write.php?mode=S&intro_no=".$intro_no."';</script>";
			exit;
		}
	}

	function selectIntro($db, $intro_no) {

		$query = "SELECT * FROM TBL_INTRO WHERE INTRO_NO='$intro_no' AND DEL_TF='N'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	//인트로 페이지 세부정보 SELECT						
	function selectIntroDetail($db, $intro_no) { //INPUT BOX의 갯수를 가지고 오기 위해

		$query ="SELECT * FROM TBL_INTRO_DETAIL WHERE INTRO_NO='$intro_no' ORDER BY I_NO ASC";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	//인트로 페이지 세부정보 개수
	function selectIntroDetailNum($db, $intro_no){ //INPUT BOX의 갯수를 가지고 오기 위해

		$query ="SELECT count(*) CNT FROM TBL_INTRO_DETAIL WHERE INTRO_NO='$intro_no' ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;
	}

	function updateIntroUseTF($conn, $use_tf, $up_adm, $intro_no) {
		
		$query = "UPDATE TBL_INTRO SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE INTRO_NO			= '$intro_no' ";

//echo $query;
//exit;
		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listIntro($db, $intro_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$search_field = "INTRO_TITLE";

		$total_cnt = totalCntIntro($db, $intro_no, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db, $query);

		$now_date_ymdhis = date("Y-m-d H:i:s",strtotime("0 day"));

		$query = "SELECT @rownum:= @rownum - 1  as rn, INTRO_NO, INTRO_TITLE, INTRO_DISP_SEQ, INTRO_MEMO, FILE_NM, FILE_RNM,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, START_DATE, END_DATE, DATE_USE_TF,
										 ('".$now_date_ymdhis."' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') AS VIEW_TF
							FROM TBL_INTRO WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		//$query .= " ORDER BY B.DCODE_SEQ_NO ASC, A.USE_TF DESC, A.DISP_SEQ asc, A.M_NO DESC";  //limit ".$offset.", ".$nRowCount;
		//$query .= " ORDER BY B.DCODE_SEQ_NO ASC, A.USE_TF DESC, A.DISP_SEQ asc, A.M_NO DESC limit ".$offset.", ".$nRowCount;
		$query .= " ORDER BY USE_TF DESC, INTRO_DISP_SEQ asc, INTRO_NO DESC limit ".$offset.", ".$nRowCount;

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


	function totalCntIntro($db, $intro_no, $use_tf, $del_tf, $search_field, $search_str){

		$search_field = "INTRO_TITLE";

		$query ="SELECT COUNT(*) CNT FROM TBL_INTRO WHERE 1 = 1 ";


		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}


	function updateOrderIntro($db, $disp_seq_no, $intro_no) {

		$query="UPDATE TBL_INTRO SET
										INTRO_DISP_SEQ	=	'$disp_seq_no'
							WHERE INTRO_NO	= '$intro_no' ";
			
		//echo $query."<br />";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>