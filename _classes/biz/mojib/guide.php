<?
	# =============================================================================
	# File Name    : guide.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-13
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listMojib($db, $m_no, $m_type, $m_title, $m_year, $m_pdf, $m_hwp, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntMojib($db, $m_no, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.M_NO, A.M_TYPE, A.M_TITLE, A.M_YEAR, A.M_PDF, 
										 A.M_HWP, A.M_PDF_NAME, A.M_HWP_NAME, A.DISP_SEQ,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE
							FROM TBL_GUIDE A, TBL_CODE_DETAIL B WHERE A.M_YEAR = B.DCODE AND B.PCODE = 'MOJIB_YEAR' ";

		if ($m_type <> "") {
			$query .= " AND A.M_TYPE = '".$m_type."' ";
		}

		if ($m_year <> "") {
			$query .= " AND A.M_YEAR = '".$m_year."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		//$query .= " ORDER BY B.DCODE_SEQ_NO ASC, A.USE_TF DESC, A.DISP_SEQ asc, A.M_NO DESC";  //limit ".$offset.", ".$nRowCount;
		$query .= " ORDER BY B.DCODE_SEQ_NO ASC, A.USE_TF DESC, A.DISP_SEQ asc, A.M_NO DESC"; // limit ".$offset.", ".$nRowCount;

		// echo $query;
		// exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntMojib($db, $m_no, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_GUIDE WHERE 1 = 1 ";

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
/*
	function listCurrentBanner($db) {
		
		$query = "SELECT *, CASE WHEN APP_TYPE = 'C' 
												THEN (SELECT COUNT(ASEQ_NO) FROM TBL_APPLY B, TBL_GROUPS_INFO C WHERE B.IDX_NO = C.IDX AND B.SEQ_NO = A.SEQ_NO AND B.DEL_TF = 'N' AND C.DEL_TF = 'N')
												ELSE (SELECT COUNT(ASEQ_NO) FROM TBL_APPLY B WHERE B.SEQ_NO = A.SEQ_NO AND B.DEL_TF = 'N' )
												END AS APPLY_CNT
								FROM TBL_PROGRAM A
							 WHERE A.DEL_TF = 'N' AND A.USE_TF = 'Y' ORDER BY A.SEQ_NO DESC ";
		
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
*/

	function insertMojib($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_GUIDE (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_m_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_m_no;
		}
	}

	//PDF 페이지 세부정보 등록
	function insertMojibDetail($db, $arr_data) {

		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_GUIDE_DETAIL (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectMojib($db, $m_no) {

		$query = "SELECT * FROM TBL_GUIDE WHERE M_NO='$m_no' AND DEL_TF='N' ";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	//PDF 페이지 세부정보 SELECT						
	function selectMojibDetail($db, $m_no){ //INPUT BOX의 갯수를 가지고 오기 위해

		$query ="SELECT * FROM TBL_GUIDE_DETAIL WHERE M_NO='$m_no' AND DEL_TF = 'N' ORDER BY MD_NO ASC";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateMojib($db, $arr_data, $m_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_GUIDE SET ".$set_query_str." ";
		$query .= "m_no = '$m_no' WHERE M_NO = '$m_no' ";



		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	//PDF 페이지 세부정보 UPDATE
	function updateMojibDetail($db, $arr_data, $md_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_GUIDE_DETAIL SET ".$set_query_str." ";
		$query .= "md_no = '$md_no' WHERE MD_NO = '$md_no' ";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMojib($db, $adm_no, $m_no) {

		$query = "UPDATE TBL_GUIDE SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE M_NO = '$m_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	//PDF 페이지 세부정보 전체 DELETE
	function deleteMojibDetail($db, $adm_no, $m_no) {

		$query = "UPDATE TBL_GUIDE_DETAIL SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE M_NO = '$m_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	//PDF 페이지 세부정보 개별항목 DELETE
	function deleteMojibDetail_1($db, $adm_no, $md_no, $m_no) {

		$query = "DELETE FROM TBL_GUIDE_DETAIL WHERE MD_NO = '$md_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			echo "<script>alert('성공');document.location.href='mojib_write.php?mode=S&m_no=".$m_no."';</script>";
			exit;
		}
	}

	function getMojibYear($db, $m_type) {

		$query = "SELECT DISTINCT M_YEAR FROM TBL_GUIDE WHERE M_TYPE = '$m_type' AND DEL_TF = 'N' AND USE_TF = 'Y' ORDER BY M_YEAR DESC ";
		
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

	function getMojibInfo($db, $m_type, $m_year) {

		$query = "SELECT * FROM TBL_GUIDE WHERE M_TYPE = '$m_type' AND M_YEAR = '$m_year' AND DEL_TF = 'N' AND USE_TF = 'Y' ORDER BY M_YEAR DESC LIMIT 1 ";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;

	}

	function updateMojibUseTF($conn, $use_tf, $up_adm, $m_no) {
		
		$query = "UPDATE TBL_GUIDE SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE M_NO			= '$m_no' ";

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
?>
