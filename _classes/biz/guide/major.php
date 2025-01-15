<?
	# =============================================================================
	# File Name    : major.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2021-03-22
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

		$query = "SELECT @rownum:= @rownum - 1  as rn, M_NO, M_TYPE, M_TITLE, M_YEAR, M_PDF, M_PDF_NAME, DISP_SEQ,
						  USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_MAJOR WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, M_NO DESC";  //limit ".$offset.", ".$nRowCount;

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


	function totalCntMojib($db, $m_no, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_MAJOR WHERE 1 = 1 ";

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

	function insertMojib($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_MAJOR (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		echo $query;

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

		$query = "INSERT INTO TBL_MAJOR_DETAIL (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_seq_no;
		}
	}

	function selectMojib($db, $m_no) {

		$query = "SELECT * FROM TBL_MAJOR WHERE M_NO='$m_no' AND DEL_TF='N' ";

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

		$query ="SELECT * FROM TBL_MAJOR_DETAIL WHERE M_NO='$m_no' AND DEL_TF = 'N' ORDER BY MD_NO ASC";
		
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

		$query = "UPDATE TBL_MAJOR SET ".$set_query_str." ";
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

		$query = "UPDATE TBL_MAJOR_DETAIL SET ".$set_query_str." ";
		$query .= "md_no = '$md_no' WHERE MD_NO = '$md_no' ";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateMojibUseTF($conn, $use_tf, $up_adm, $m_no) {
		
		$query = "UPDATE TBL_MAJOR SET 
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


	function deleteMojib($db, $adm_no, $m_no) {

		$query = "UPDATE TBL_MAJOR SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE M_NO = '$m_no' ";

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

		$query = "UPDATE TBL_MAJOR_DETAIL SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE M_NO = '$m_no' ";

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

		$query = "DELETE FROM TBL_MAJOR_DETAIL WHERE MD_NO = '$md_no' ";

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

		//$query = "SELECT DISTINCT M_YEAR FROM TBL_MAJOR WHERE M_TYPE = '$m_type' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY M_YEAR DESC ";
		
		$query = "SELECT DISTINCT A.M_YEAR
								FROM TBL_MAJOR A, TBL_CODE_DETAIL B
							 WHERE A.M_YEAR = B.DCODE
								 AND B.PCODE = 'MOJIB_YEAR'
								 AND A.M_TYPE = '$m_type'
								 AND A.USE_TF = 'Y'
								 AND A.DEL_TF = 'N'
							 ORDER BY B.DCODE_SEQ_NO ASC ";
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

		$query = "SELECT * FROM TBL_MAJOR WHERE M_TYPE = '$m_type' AND M_YEAR = '$m_year' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY M_YEAR DESC LIMIT 1 ";

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