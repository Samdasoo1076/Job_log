<?
	# =============================================================================
	# File Name    : program.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-02-10
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listProgram($db, $yyyy, $type, $app_type, $state, $list_type, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * ,@rownum:= @rownum - 1  as rn
								FROM TBL_PROGRAM
							 WHERE 1 = 1 ";

		if ($yyyy <> "") {
			$query .= " AND YYYY = '$yyyy' ";
		}

		if ($type <> "") {
			$query .= " AND TYPE = '$type' ";
		}

		if ($app_type <> "") {
			$query .= " AND APP_TYPE = '$app_type' ";
		}

		if ($list_type <> "") {
			$query .= " AND LIST_TYPE = '$list_type' ";
		}

		if ($state <> "") {
			$query .= " AND STATE = '$state' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (YYYY like '%".$search_str."%' OR TITLE like '%".$search_str."%' OR CONTENTS like '%".$search_str."%')";
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

	function totalCntProgram($db, $yyyy, $type, $app_type, $state, $list_type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(SEQ_NO) CNT 
							 FROM TBL_PROGRAM
							WHERE 1 = 1 ";

		if ($yyyy <> "") {
			$query .= " AND YYYY = '$yyyy' ";
		}

		if ($type <> "") {
			$query .= " AND TYPE = '$type' ";
		}

		if ($app_type <> "") {
			$query .= " AND APP_TYPE = '$app_type' ";
		}

		if ($state <> "") {
			$query .= " AND STATE = '$state' ";
		}

		if ($list_type <> "") {
			$query .= " AND LIST_TYPE = '$list_type' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '$use_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (YYYY like '%".$search_str."%' OR TITLE like '%".$search_str."%' OR CONTENTS like '%".$search_str."%')";
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
	
	function listCurrentProgram($db) {
		
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

	function insertProgram($db, $arr_data) {

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

		$query = "INSERT INTO TBL_PROGRAM (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_seq_no = mysqli_insert_id($db);
			return $new_seq_no;
		}
	}

	function selectProgram($db, $seq_no) {

		$query = "SELECT * FROM TBL_PROGRAM WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateProgram($db, $arr_data, $seq_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_PROGRAM SET ".$set_query_str." ";
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

	function deleteProgram($db, $adm_no, $seq_no) {

		$query = "UPDATE TBL_PROGRAM SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE SEQ_NO = '$seq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	// 문항
	function listQuestion($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_QUESTION WHERE SEQ_NO = '$seq_no' AND DEL_TF = 'N' ORDER BY QSEQ_NO ASC ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function insertQusetion($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		$set_eseq_no = "";
		
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
				
				if ($key == "FORM_NO") {
					$set_form_no = $value;
				}

			}
		}

		$query = "INSERT INTO TBL_QUESTION (".$set_field.", DISP_SEQ, REG_DATE, UP_DATE) 
					values (".$set_value.", '0', now(), now()); ";

		//echo "Q insert : ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "SELECT last_insert_id()";
			$result = mysqli_query($db, $query);
			$rows   = mysqli_fetch_array($result);
			$new_seq_no  = $rows[0];
			return $new_seq_no;
		}
	}

	function updateQusetion($db, $arr_data, $qseq_no) {
		
		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_QUESTION SET ".$set_query_str." ";
		$query .= "UP_DATE = now(),";
		$query .= "QSEQ_NO = '$qseq_no' WHERE QSEQ_NO = '$qseq_no' ";

		//echo "Q update : ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectQuestion($db, $qseq_no) {

		$query = "SELECT *
								FROM TBL_QUESTION 
							 WHERE QSEQ_NO = '$qseq_no' ";

		$result = mysqli_query($db, $query);
		$record = array();
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function insertOption($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_QUESTION_OPTION (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo "insert ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "SELECT last_insert_id()";
			$result = mysqli_query($db, $query);
			$rows   = mysqli_fetch_array($result);
			$new_seq_no  = $rows[0];
			return $new_seq_no;
		}
	}

	function updateOption($db, $arr_data, $oseq_no) {
		
		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_QUESTION_OPTION SET ".$set_query_str." ";
		$query .= "DEL_TF = 'N',";
		$query .= "UP_DATE = now(),";
		$query .= "OSEQ_NO = '$oseq_no' WHERE OSEQ_NO = '$oseq_no' ";

		//echo "update ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteOption($db, $qseq_no) {
		
		$query="UPDATE TBL_QUESTION_OPTION SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE QSEQ_NO			= '$qseq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listOption($db, $qseq_no, $use_tf, $del_tf) {

		$query = "SELECT *
								FROM TBL_QUESTION_OPTION  
							 WHERE 1 = 1 ";

		if ($qseq_no <> "") {
			$query .= " AND QSEQ_NO = '".$qseq_no."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}
		
		if ($order_field == "") 
			$order_field = "OSEQ_NO";

		if ($order_str == "") 
			$order_str = "ASC";

		$query .= " ORDER BY ".$order_field." ".$order_str;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function getOptionString($db, $oseq_no) {

		$query = "SELECT OPTION_VALUE
								FROM TBL_QUESTION_OPTION  
							 WHERE OSEQ_NO = '$oseq_no' ";
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getAnswerOptionString($db, $aseq_no) {

		$query = "SELECT B.OPTION_VALUE
								FROM TBL_ANSWER A, TBL_QUESTION_OPTION B
							 WHERE A.ANSWER_NO = B.OSEQ_NO
								 AND A.ASEQ_NO = '$aseq_no'
								 AND A.DEL_TF = 'N'
								 AND B.DEL_TF = 'N'
								 AND A.QTYPE = 'type01'
							 ORDER BY B.QSEQ_NO DESC LIMIT 1";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function deleteQuestion($db, $qseq_no) {

		$query="UPDATE TBL_QUESTION SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE QSEQ_NO			= '$qseq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertApply($db, $arr_data) {

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

		$query = "INSERT INTO TBL_APPLY (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {

			$new_apply_no = mysqli_insert_id($db);
			return $new_apply_no;
		}
	}

	function updateApply($db, $arr_data, $aseq_no) {
		
		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_APPLY SET ".$set_query_str." ";
		$query .= "UP_DATE = now(),";
		$query .= "ASEQ_NO = '$aseq_no' WHERE ASEQ_NO = '$aseq_no' ";

		//echo "update ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertAnswer($db, $arr_data) {

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

		$query = "INSERT INTO TBL_ANSWER (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteAnswer($db, $aseq_no) {

		$query = "UPDATE TBL_ANSWER SET DEL_TF = 'Y', DEL_DATE = now() WHERE ASEQ_NO = '$aseq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	
	function selectApply($db, $aseq_no) {

		$query = "SELECT *
								FROM TBL_APPLY 
							 WHERE ASEQ_NO = '$aseq_no' ";

		$result = mysqli_query($db, $query);
		$record = array();
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectApplyAuthor($db, $aseq_no, $idx_no) {

		$query = "SELECT *
								FROM TBL_APPLY 
							 WHERE ASEQ_NO = '$aseq_no' AND IDX_NO = '$idx_no' AND DEL_TF = 'N' ";

		$result = mysqli_query($db, $query);
		$record = array();
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectAnswer($db, $aseq_no) {

		$query = "SELECT *, 
										(SELECT TITLE FROM TBL_QUESTION WHERE QSEQ_NO = A.QSEQ_NO) AS TITLE,
										(SELECT ETC FROM TBL_QUESTION WHERE QSEQ_NO = A.QSEQ_NO) AS ETC
							 FROM TBL_ANSWER A WHERE ASEQ_NO = '$aseq_no' AND DEL_TF = 'N' ";

		$result = mysqli_query($db, $query);
		$record = array();
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;

	}

	function dupChkApplyGroup($db, $seq_no, $idx) {
		
		$query = "SELECT ASEQ_NO FROM TBL_APPLY WHERE IDX_NO = '$idx' AND SEQ_NO = '$seq_no' AND DEL_TF = 'N' ORDER BY ASEQ_NO DESC LIMIT 1 ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function copyApplyGroupInfo($db, $seq_no, $idx) {
		
		$query = "DELETE FROM TBL_APPLY_GROUPS_INFO WHERE SEQ_NO = '$seq_no' AND IDX = '$idx' ";
		
		mysqli_query($db, $query);

		$query = "INSERT INTO TBL_APPLY_GROUPS_INFO 
								(SEQ_NO, IDX, REG_NAME, REG_HP, REG_EMAIL, REG_PASSWORD, GROUP_NAME, READ_CAREGORY,
								 GROUP_RUNNER, GROUP_EMAIL, GROUP_START_YEAR, GROUP_SIDO, GROUP_SIGUNGU,
								 GROUP_DONGRI, GROUP_OFFICE, GROUP_ZIP_CODE, GROUP_ADDR_01, GROUP_ADDR_02,
								 GROUP_ADDR_SIDO, GROUP_ADDR_SIGUNGU, GROUP_ADDR_LAT, GROUP_ADDR_LONG,
								 GROUP_JUKI, GROUP_JUKI_ETC, GROUP_YOIL, GROUP_YOIL_CODE, GROUP_TIME,
								 GROUP_INFO, GROUP_INFO_TXT, IS_APPLY, APPLY_START_DATE, APPLY_END_DATE,
								 GROUP_MEMBER_CNT, GROUP_REASON, GROUP_MEMBER_TYPE, WORK_TYPE, GROUP_HOMEPAGE,
								 GROUP_IMAGE, RUSER_IDX, VIEW_OPT, SEND_MSG, IP, OLD_IDX, CONFIRM_TF,
								 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE) 
							SELECT '".$seq_no."', IDX, REG_NAME, REG_HP, REG_EMAIL, REG_PASSWORD, GROUP_NAME, READ_CAREGORY,
								 GROUP_RUNNER, GROUP_EMAIL, GROUP_START_YEAR, GROUP_SIDO, GROUP_SIGUNGU,
								 GROUP_DONGRI, GROUP_OFFICE, GROUP_ZIP_CODE, GROUP_ADDR_01, GROUP_ADDR_02,
								 GROUP_ADDR_SIDO, GROUP_ADDR_SIGUNGU, GROUP_ADDR_LAT, GROUP_ADDR_LONG,
								 GROUP_JUKI, GROUP_JUKI_ETC, GROUP_YOIL, GROUP_YOIL_CODE, GROUP_TIME,
								 GROUP_INFO, GROUP_INFO_TXT, IS_APPLY, APPLY_START_DATE, APPLY_END_DATE,
								 GROUP_MEMBER_CNT, GROUP_REASON, GROUP_MEMBER_TYPE, WORK_TYPE, GROUP_HOMEPAGE,
								 GROUP_IMAGE, RUSER_IDX, VIEW_OPT, SEND_MSG, IP, OLD_IDX, CONFIRM_TF,
								 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							 FROM TBL_GROUPS_INFO WHERE IDX = '".$idx."' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectApplyGroupInfo($db, $idx, $seq_no) {

		$query = "SELECT * FROM TBL_APPLY_GROUPS_INFO WHERE IDX = '$idx' AND SEQ_NO = '$seq_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function chkCntApply($db, $seq_no) {

		$query = "SELECT COUNT(*) AS CNT FROM TBL_APPLY WHERE SEQ_NO = '$seq_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function chkCntApplyGroup($db, $idx, $seq_no) {

		$query = "SELECT COUNT(*) AS CNT FROM TBL_APPLY WHERE IDX_NO = '$idx' AND SEQ_NO = '$seq_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function chkCntApplyPerson($db, $app_email, $seq_no) {

		$query = "SELECT COUNT(*) AS CNT FROM TBL_APPLY WHERE APP_EMAIL = '$app_email' AND SEQ_NO = '$seq_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function listApply($db, $app_type, $seq_no, $idx_no, $area_sido, $area_sigungu, $app_state, $confirm_state, $manager_no, $add_file_state, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		if ($app_type == "C") { 

			$query = "SELECT @rownum:= @rownum - 1  as rn, A.ASEQ_NO, A.IDX_NO, A.SEQ_NO, A.SEQ_NUM, A.APP_NAME, A.APP_PHONE, A.APP_EMAIL, A.APP_AGES, 
											 A.FIRST_PHONE, A.FIRST_AGES, A.SEC_NAME, A.SEC_PHONE, A.SEC_EMAIL, A.SEC_AGES, A.AREA_SIDO, A.AREA_SIGUNGU, 
											 A.DEPT, A.BIRTH, A.GENDER, A.PASSWD, A.FILE_NM_01, A.FILE_RNM_01, A.FILE_NM_02, A.FILE_RNM_02, 
											 A.ANSWER01, A.ANSWER02, A.ANSWER03, A.ANSWER04, A.ANSWER05, A.ANSWER06, A.ANSWER07, A.ANSWER08, A.ANSWER09, A.ANSWER10, A.DEL_TF, A.REG_DATE, A.UP_DATE, A.APP_STATE, A.CONFIRM_STATE,
											 B.GROUP_NAME, B.GROUP_RUNNER, B.REG_EMAIL, B.GROUP_EMAIL, A.MANAGER_NO,
											 A.ADD_FILE01, A.ADD_FILE02, A.ADD_FILE03, A.ADD_FILE04, A.ADD_RFILE01, A.ADD_RFILE02, A.ADD_RFILE03, A.ADD_RFILE04,
											(SELECT IFNULL(SUM(CASE WHEN C.ACCOUNT_TYPE = 'SP' THEN C.BUY_AMOUNT ELSE 
																				 CASE WHEN LEFT(C.ACCOUNT_TYPE,2) = 'IN' THEN -C.BUY_AMOUNT ELSE 
																				 CASE WHEN C.ACCOUNT_TYPE = 'ETC' THEN 0 END END END),0) AS  SPEND_AMOUNT
                         FROM TBL_SPEND_INFO C WHERE C.SEQ_NO = A.SEQ_NO AND C.IDX_NO = A.IDX_NO AND C.DEL_TF = 'N' ) AS SPEND_AMOUNT
									FROM TBL_APPLY A, TBL_GROUPS_INFO B
								 WHERE A.IDX_NO = B.IDX
									 AND B.DEL_TF = 'N' ";

			if ($seq_no <> "") {
				$query .= " AND A.SEQ_NO = '$seq_no' ";
			}

			if ($idx_no <> "") {
				$query .= " AND A.IDX_NO = '$idx_no' ";
			}

			if ($area_sido <> "") {
				$query .= " AND A.AREA_SIDO = '$area_sido' ";
			}

			if ($area_sigungu <> "") {
				$query .= " AND A.AREA_SIGUNGU = '$area_sigungu' ";
			}

			if ($app_state <> "") {
				$query .= " AND A.APP_STATE = '$app_state' ";
			}

			if ($confirm_state <> "") {
				$query .= " AND A.CONFIRM_STATE = '$confirm_state' ";
			}

			if ($manager_no <> "") {
				$query .= " AND A.MANAGER_NO = '$manager_no' ";
			}

			if ($add_file_state <> "") {
				if ($add_file_state == "Y") {
					$query .= " AND (A.ADD_FILE01 <> '' AND A.ADD_FILE02 <> '' AND A.ADD_FILE03 <> '' AND A.ADD_FILE04 <> '') ";
				} else {
					$query .= " AND (A.ADD_FILE01 = '' OR A.ADD_FILE02 = '' OR A.ADD_FILE03 = '' OR A.ADD_FILE04 = ''  OR A.ADD_FILE01 IS NULL OR A.ADD_FILE02 IS NULL OR A.ADD_FILE03 IS NULL OR A.ADD_FILE04 IS NULL) ";
				}
			}

			if ($del_tf <> "") {
				$query .= " AND A.DEL_TF = '$del_tf' ";
			}

			if ($search_str <> "") {
				if ($search_field == "ALL") {
					$query .= " AND (A.SEQ_NUM like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR B.GROUP_NAME like '%".$search_str."%' OR B.GROUP_RUNNER like '%".$search_str."%' OR A.SEC_NAME like '%".$search_str."%' OR A.SEC_EMAIL like		'%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' OR B.REG_EMAIL like '%".$search_str."%' OR B.GROUP_EMAIL like '%".$search_str."%')";
				} else {
					$query .= " AND ".$search_field." like '%".$search_str."%' ";
				}
			}
		
			if ($order_field == "") {
				$order_field = " A.REG_DATE ";
			}

			if ($order_str == "") {
				$order_str = " DESC ";
			}

			$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
			
			//echo $query."<br>";

		} else {

			$query = "SELECT @rownum:= @rownum - 1  as rn, A.ASEQ_NO, A.IDX_NO, A.SEQ_NO, A.SEQ_NUM, A.APP_NAME, A.APP_PHONE, A.APP_EMAIL, A.APP_AGES, 
											 A.FIRST_PHONE, A.FIRST_AGES, A.SEC_NAME, A.SEC_PHONE, A.SEC_EMAIL, A.SEC_AGES, A.AREA_SIDO, A.AREA_SIGUNGU, 
											 A.DEPT, A.BIRTH, A.GENDER, A.PASSWD, A.FILE_NM_01, A.FILE_RNM_01, A.FILE_NM_02, A.FILE_RNM_02, 
											 A.ANSWER01, A.ANSWER02, A.ANSWER03, A.ANSWER04, A.ANSWER05, A.ANSWER06, A.ANSWER07, A.ANSWER08, 
											 A.ANSWER09, A.ANSWER10, A.DEL_TF, A.REG_DATE, A.UP_DATE, A.APP_STATE, A.CONFIRM_STATE, A.MANAGER_NO,
											 A.ADD_FILE01, A.ADD_FILE02, A.ADD_FILE03, A.ADD_FILE04, A.ADD_RFILE01, A.ADD_RFILE02, A.ADD_RFILE03, A.ADD_RFILE04
									FROM TBL_APPLY A
								 WHERE 1 = 1 ";

			if ($seq_no <> "") {
				$query .= " AND A.SEQ_NO = '$seq_no' ";
			}

			if ($idx_no <> "") {
				$query .= " AND A.IDX_NO = '$idx_no' ";
			}

			if ($area_sido <> "") {
				$query .= " AND A.ANSWER06 like '%".$area_sido."%' ";
			}

			if ($area_sigungu <> "") {
				$query .= " AND A.AREA_SIGUNGU = '$area_sigungu' ";
			}

			if ($app_state <> "") {
				$query .= " AND A.APP_STATE = '$app_state' ";
			}

			if ($confirm_state <> "") {
				$query .= " AND A.CONFIRM_STATE = '$confirm_state' ";
			}

			if ($manager_no <> "") {
				$query .= " AND A.MANAGER_NO = '$manager_no' ";
			}

			if ($add_file_state <> "") {
				if ($add_file_state == "Y") {
					$query .= " AND (A.ADD_FILE01 <> '' AND A.ADD_FILE02 <> '' AND A.ADD_FILE03 <> '' AND A.ADD_FILE04 <> '') ";
				} else {
					$query .= " AND (A.ADD_FILE01 = '' OR A.ADD_FILE02 = '' OR A.ADD_FILE03 = '' OR A.ADD_FILE04 = ''  OR A.ADD_FILE01 IS NULL OR A.ADD_FILE02 IS NULL OR A.ADD_FILE03 IS NULL OR A.ADD_FILE04 IS NULL) ";
				}
			}

			if ($del_tf <> "") {
				$query .= " AND A.DEL_TF = '$del_tf' ";
			}

			if ($search_str <> "") {
				if ($search_field == "ALL") {
					$query .= " AND (A.SEQ_NUM like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR A.SEC_NAME like '%".$search_str."%' OR A.SEC_EMAIL like		'%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%')";
				} else {
					$query .= " AND ".$search_field." like '%".$search_str."%' ";
				}
			}
		
			if ($order_field == "") {
				$order_field = " A.REG_DATE ";
			}

			if ($order_str == "") {
				$order_str = " DESC ";
			}

			$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;

		}

		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

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

	function totalCntApply($db, $app_type, $seq_no, $idx_no, $area_sido, $area_sigungu, $app_state, $confirm_state, $manager_no, $add_file_state, $del_tf, $search_field, $search_str){

		if ($app_type == "C") { 

			$query = "SELECT COUNT(A.ASEQ_NO) AS CNT
									FROM TBL_APPLY A, TBL_GROUPS_INFO B
								 WHERE A.IDX_NO = B.IDX
									 AND B.DEL_TF = 'N' ";

			if ($seq_no <> "") {
				$query .= " AND A.SEQ_NO = '$seq_no' ";
			}

			if ($idx_no <> "") {
				$query .= " AND A.IDX_NO = '$idx_no' ";
			}

			if ($area_sido <> "") {
				$query .= " AND A.AREA_SIDO = '$area_sido' ";
			}

			if ($area_sigungu <> "") {
				$query .= " AND A.AREA_SIGUNGU = '$area_sigungu' ";
			}

			if ($app_state <> "") {
				$query .= " AND A.APP_STATE = '$app_state' ";
			}

			if ($confirm_state <> "") {
				$query .= " AND A.CONFIRM_STATE = '$confirm_state' ";
			}

			if ($manager_no <> "") {
				$query .= " AND A.MANAGER_NO = '$manager_no' ";
			}

			if ($add_file_state <> "") {
				if ($add_file_state == "Y") {
					$query .= " AND (A.ADD_FILE01 <> '' AND A.ADD_FILE02 <> '' AND A.ADD_FILE03 <> '' AND A.ADD_FILE04 <> '') ";
				} else {
					$query .= " AND (A.ADD_FILE01 = '' OR A.ADD_FILE02 = '' OR A.ADD_FILE03 = '' OR A.ADD_FILE04 = ''  OR A.ADD_FILE01 IS NULL OR A.ADD_FILE02 IS NULL OR A.ADD_FILE03 IS NULL OR A.ADD_FILE04 IS NULL) ";
				}
			}

			if ($del_tf <> "") {
				$query .= " AND A.DEL_TF = '$del_tf' ";
			}

			if ($search_str <> "") {
				if ($search_field == "ALL") {
					$query .= " AND (A.SEQ_NUM like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR B.GROUP_NAME like '%".$search_str."%' OR B.GROUP_RUNNER like '%".$search_str."%' OR A.SEC_NAME like '%".$search_str."%' OR A.SEC_EMAIL like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' OR B.REG_EMAIL like '%".$search_str."%' OR B.GROUP_EMAIL like '%".$search_str."%')";
				} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
				}
			}

		} else {
		
			$query = "SELECT COUNT(A.ASEQ_NO) AS CNT
									FROM TBL_APPLY A
								 WHERE 1 = 1 ";

			if ($seq_no <> "") {
				$query .= " AND A.SEQ_NO = '$seq_no' ";
			}

			if ($idx_no <> "") {
				$query .= " AND A.IDX_NO = '$idx_no' ";
			}

			if ($area_sido <> "") {
				$query .= " AND A.ANSWER06 like '%".$area_sido."%' ";
			}

			if ($area_sigungu <> "") {
				$query .= " AND A.AREA_SIGUNGU = '$area_sigungu' ";
			}

			if ($app_state <> "") {
				$query .= " AND A.APP_STATE = '$app_state' ";
			}

			if ($confirm_state <> "") {
				$query .= " AND A.CONFIRM_STATE = '$confirm_state' ";
			}

			if ($manager_no <> "") {
				$query .= " AND A.MANAGER_NO = '$manager_no' ";
			}

			if ($add_file_state <> "") {
				if ($add_file_state == "Y") {
					$query .= " AND (A.ADD_FILE01 <> '' AND A.ADD_FILE02 <> '' AND A.ADD_FILE03 <> '' AND A.ADD_FILE04 <> '') ";
				} else {
					$query .= " AND (A.ADD_FILE01 = '' OR A.ADD_FILE02 = '' OR A.ADD_FILE03 = '' OR A.ADD_FILE04 = '' OR A.ADD_FILE01 IS NULL OR A.ADD_FILE02 IS NULL OR A.ADD_FILE03 IS NULL OR A.ADD_FILE04 IS NULL) ";
				}
			}

			if ($del_tf <> "") {
				$query .= " AND A.DEL_TF = '$del_tf' ";
			}

			if ($search_str <> "") {
				if ($search_field == "ALL") {
					$query .= " AND (A.SEQ_NUM like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR A.SEC_NAME like '%".$search_str."%' OR A.SEC_EMAIL like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' )";
				} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
				}
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


	function deleteApply($db, $adm_no, $aseq_no) {

		$query = "UPDATE TBL_APPLY SET DEL_TF = 'Y',  DEL_DATE = now() WHERE ASEQ_NO = '$aseq_no' ";
		
		//echo $query."<br>";

		mysqli_query($db, $query);
		
		$query = "UPDATE TBL_ANSWER SET DEL_TF = 'Y',  DEL_DATE = now() WHERE ASEQ_NO = '$aseq_no' ";

		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertApplyMail($db, $arr_data) {

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

		$query = "INSERT INTO TBL_APPLY_MAIL (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateApplyMail($db, $arr_data, $mseq_no) {
		
		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_APPLY_MAIL SET ".$set_query_str." ";
		$query .= "MSEQ_NO = '$mseq_no' WHERE MSEQ_NO = '$mseq_no' ";

		//echo "update ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listSendApplyMail($db) {

		$this_date_time = date("YmdHi",strtotime("0 day"));
		//$this_date_time = date("YmdHi",strtotime("1 day"));
		
		$query = "SELECT * FROM TBL_APPLY_MAIL WHERE DEL_TF = 'N' AND SEND_FLAG = 'N' AND RESERVE_DATE_TIME <= '$this_date_time' ORDER BY MSEQ_NO DESC ";

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

	function selectApplyMail($db, $mseq_no) {

		$query = "SELECT * FROM TBL_APPLY_MAIL WHERE MSEQ_NO = '$mseq_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function listProgramFile($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_PROGRAM_FILE WHERE 1 = 1
								 AND DEL_TF = 'N'
								 AND SEQ_NO = '".$seq_no."' ";

		$query .= " ORDER BY FILE_NO ASC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function insertProgramFile($db, $arr_data) {

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

		$query = "INSERT INTO TBL_PROGRAM_FILE (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteProgramFile($db, $seq_no) {
				
		$query = "UPDATE TBL_PROGRAM_FILE SET DEL_TF = 'Y', DEL_DATE = now() WHERE SEQ_NO = '$seq_no'";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectProgramFile($db, $file_no) {

		$query = "SELECT *
								FROM TBL_PROGRAM_FILE WHERE 1 = 1 
								 AND DEL_TF = 'N'
								 AND FILE_NO = '".$file_no."' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	
	function updateGroupManager($db, $adm_no, $aseq_no, $aseq_no_chk) {
		
		$query = "SELECT MANAGER_NO FROM TBL_APPLY WHERE ASEQ_NO = '$aseq_no' ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$manager_no = $rows[0];

		if ($manager_no == $adm_no) {
			if ($aseq_no_chk == "N") {
				$query = "UPDATE TBL_APPLY SET MANAGER_NO = '' WHERE ASEQ_NO = '$aseq_no' ";
			}
		} else {
			if ($aseq_no_chk == "Y") {
				$query = "UPDATE TBL_APPLY SET MANAGER_NO = '$adm_no' WHERE ASEQ_NO = '$aseq_no' ";
			}
		}

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function confirmSelectedGroupInfo($db, $seq_no, $idx) {

		$query = "SELECT A.REG_EMAIL, A.REG_PASSWORD, B.ASEQ_NO
								FROM TBL_GROUPS_INFO A, TBL_APPLY B
							 WHERE A.IDX = B.IDX_NO
								 AND B.APP_STATE = '1'
								 AND A.DEL_TF = 'N'
								 AND B.DEL_TF = 'N'
								 AND B.SEQ_NO = '$seq_no'
								 AND A.IDX = '$idx' ";
		
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

	function getApplyType01AnswerCnt($db, $answr_no) {
		
		$query = "SELECT COUNT(*) AS CNT from TBL_ANSWER WHERE ANSWER_NO = '$answr_no' AND DEL_TF = 'N'";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function insertApplyBookInfo($db, $arr_data) {

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

		$query = "INSERT INTO TBL_APPLY_BOOK_INFO (".$set_field.") 
					values (".$set_value."); ";

		//echo "query : ".$query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listApplyBookInfo($db, $aseq_no) {

		$query = "SELECT *
								FROM TBL_APPLY_BOOK_INFO
							 WHERE ASEQ_NO = '$aseq_no' 
								 AND DEL_TF = 'N'
							 ORDER BY APPLY_BOOK_NO ASC ";
		
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

	function deleteApplyBookInfo($db, $aseq_no) {

		$query = "DELETE FROM TBL_APPLY_BOOK_INFO WHERE ASEQ_NO = '$aseq_no' ";
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getApplyType03Amount($db, $seq_no, $idx_no, $aseq_no) {
		
		if ($aseq_no <> "") { 
			$query = "SELECT IFNULL(SUM(SEC_AGES),0) AS ALL_AMOUNT from TBL_APPLY WHERE SEQ_NO = '$seq_no' AND IDX_NO = '$idx_no' AND ASEQ_NO <> '$aseq_no' AND DEL_TF = 'N' ";
		} else {
			$query = "SELECT IFNULL(SUM(SEC_AGES),0) AS ALL_AMOUNT from TBL_APPLY WHERE SEQ_NO = '$seq_no' AND IDX_NO = '$idx_no' AND DEL_TF = 'N' ";
		}

		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function getListApplyGroupInfo($db, $seq_no, $idx_no) {

		$query = "SELECT * FROM TBL_APPLY WHERE SEQ_NO = '$seq_no' AND IDX_NO = '$idx_no' AND DEL_TF = 'N' ORDER BY ASEQ_NO DESC ";
		
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

	function selectPersonApplyInfo($db, $seq_no, $email) {

		$query= "SELECT * FROM TBL_APPLY WHERE SEQ_NO = '$seq_no' AND APP_EMAIL = '$email' AND DEL_TF = 'N' ORDER BY ASEQ_NO DESC LIMIT 1 ";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;

	}


	function listCurrentAuthorApply($db, $yyyy, $group_idx) {

		$query = "SELECT *
								FROM TBL_PROGRAM A, TBL_APPLY B 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.DEL_TF = 'N'
								 AND B.DEL_TF = 'N'
								 AND A.TYPE = 'TYPE03'
								 AND A.YYYY = '$yyyy'
								 AND B.IDX_NO = '$group_idx' "; 
		
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




	function listAuthorApply($db, $app_type, $seq_no, $idx_no, $area_sido, $area_sigungu, $app_state, $confirm_state, $manager_no, $add_file_state, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$type01_seq_no = getCurrentProgram($db);



		$query = "SELECT @rownum:= @rownum - 1  as rn, A.ASEQ_NO, A.IDX_NO, A.SEQ_NO, A.SEQ_NUM, A.APP_NAME, A.APP_PHONE, A.APP_EMAIL, A.APP_AGES, 
										 A.FIRST_PHONE, A.FIRST_AGES, A.SEC_NAME, A.SEC_PHONE, A.SEC_EMAIL, A.SEC_AGES, A.AREA_SIDO, A.AREA_SIGUNGU, 
										 A.DEPT, A.BIRTH, A.GENDER, A.PASSWD, A.FILE_NM_01, A.FILE_RNM_01, A.FILE_NM_02, A.FILE_RNM_02, 
										 A.ANSWER01, A.ANSWER02, A.ANSWER03, A.ANSWER04, A.ANSWER05, A.ANSWER06, A.ANSWER07, A.ANSWER08, A.ANSWER09, A.ANSWER10, A.DEL_TF, A.REG_DATE, A.UP_DATE, A.APP_STATE, A.CONFIRM_STATE,
										 B.GROUP_NAME, B.GROUP_RUNNER, B.REG_EMAIL, B.GROUP_EMAIL, A.MANAGER_NO,
										 A.ADD_FILE01, A.ADD_FILE02, A.ADD_FILE03, A.ADD_FILE04, A.ADD_RFILE01, A.ADD_RFILE02, A.ADD_RFILE03, A.ADD_RFILE04,
										(SELECT IFNULL(SUM(CASE WHEN C.ACCOUNT_TYPE = 'SP' THEN C.BUY_AMOUNT ELSE 
																			 CASE WHEN LEFT(C.ACCOUNT_TYPE,2) = 'IN' THEN -C.BUY_AMOUNT ELSE 
																			 CASE WHEN C.ACCOUNT_TYPE = 'ETC' THEN 0 END END END),0) AS  SPEND_AMOUNT
                        FROM TBL_SPEND_INFO C WHERE C.SEQ_NO = A.SEQ_NO AND C.IDX_NO = A.IDX_NO AND C.DEL_TF = 'N' ) AS SPEND_AMOUNT
								FROM TBL_APPLY A, TBL_GROUPS_INFO B, TBL_APPLY C
							 WHERE A.IDX_NO = B.IDX
							 	 AND C.IDX_NO = B.IDX
								 AND B.DEL_TF = 'N'
								 AND C.DEL_TF = 'N'
								 AND C.SEQ_NO = '$type01_seq_no' ";

		if ($seq_no <> "") {
			$query .= " AND A.SEQ_NO = '$seq_no' ";
		}

		if ($idx_no <> "") {
			$query .= " AND A.IDX_NO = '$idx_no' ";
		}

		if ($area_sido <> "") {
			$query .= " AND A.AREA_SIDO = '$area_sido' ";
		}

		if ($area_sigungu <> "") {
			$query .= " AND A.AREA_SIGUNGU = '$area_sigungu' ";
		}

		if ($app_state <> "") {
			$query .= " AND A.APP_STATE = '$app_state' ";
		}

		if ($confirm_state <> "") {
			$query .= " AND A.CONFIRM_STATE = '$confirm_state' ";
		}

		if ($manager_no <> "") {
			$query .= " AND C.MANAGER_NO = '$manager_no' ";
		}

		if ($add_file_state <> "") {
			if ($add_file_state == "Y") {
				$query .= " AND (A.ADD_FILE01 <> '' AND A.ADD_FILE02 <> '' AND A.ADD_FILE03 <> '' AND A.ADD_FILE04 <> '') ";
			} else {
				$query .= " AND (A.ADD_FILE01 = '' OR A.ADD_FILE02 = '' OR A.ADD_FILE03 = '' OR A.ADD_FILE04 = ''  OR A.ADD_FILE01 IS NULL OR A.ADD_FILE02 IS NULL OR A.ADD_FILE03 IS NULL OR A.ADD_FILE04 IS NULL) ";
			}
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (A.SEQ_NUM like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR B.GROUP_NAME like '%".$search_str."%' OR B.GROUP_RUNNER like '%".$search_str."%' OR A.SEC_NAME like '%".$search_str."%' OR A.SEC_EMAIL like		'%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' OR B.REG_EMAIL like '%".$search_str."%' OR B.GROUP_EMAIL like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		if ($order_field == "") {
			$order_field = " A.REG_DATE ";
		}

		if ($order_str == "") {
			$order_str = " DESC ";
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
			

		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			//echo $query."<br>";
		}

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

	function totalCntAuthorApply($db, $app_type, $seq_no, $idx_no, $area_sido, $area_sigungu, $app_state, $confirm_state, $manager_no, $add_file_state, $del_tf, $search_field, $search_str){

		$type01_seq_no = getCurrentProgram($db);

		$query = "SELECT COUNT(A.ASEQ_NO) AS CNT
								FROM TBL_APPLY A, TBL_GROUPS_INFO B, TBL_APPLY C
							 WHERE A.IDX_NO = B.IDX
							 	 AND C.IDX_NO = B.IDX
								 AND B.DEL_TF = 'N'
								 AND C.DEL_TF = 'N'
								 AND C.SEQ_NO = '$type01_seq_no' ";

		if ($seq_no <> "") {
			$query .= " AND A.SEQ_NO = '$seq_no' ";
		}

		if ($idx_no <> "") {
			$query .= " AND A.IDX_NO = '$idx_no' ";
		}

		if ($area_sido <> "") {
			$query .= " AND A.AREA_SIDO = '$area_sido' ";
		}

		if ($area_sigungu <> "") {
			$query .= " AND A.AREA_SIGUNGU = '$area_sigungu' ";
		}

		if ($app_state <> "") {
			$query .= " AND A.APP_STATE = '$app_state' ";
		}

		if ($confirm_state <> "") {
			$query .= " AND A.CONFIRM_STATE = '$confirm_state' ";
		}

		if ($manager_no <> "") {
			$query .= " AND C.MANAGER_NO = '$manager_no' ";
		}

		if ($add_file_state <> "") {
			if ($add_file_state == "Y") {
				$query .= " AND (A.ADD_FILE01 <> '' AND A.ADD_FILE02 <> '' AND A.ADD_FILE03 <> '' AND A.ADD_FILE04 <> '') ";
			} else {
				$query .= " AND (A.ADD_FILE01 = '' OR A.ADD_FILE02 = '' OR A.ADD_FILE03 = '' OR A.ADD_FILE04 = ''  OR A.ADD_FILE01 IS NULL OR A.ADD_FILE02 IS NULL OR A.ADD_FILE03 IS NULL OR A.ADD_FILE04 IS NULL) ";
			}
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (A.SEQ_NUM like '%".$search_str."%' OR A.APP_NAME like '%".$search_str."%' OR B.GROUP_NAME like '%".$search_str."%' OR B.GROUP_RUNNER like '%".$search_str."%' OR A.SEC_NAME like '%".$search_str."%' OR A.SEC_EMAIL like '%".$search_str."%' OR A.APP_EMAIL like '%".$search_str."%' OR B.REG_EMAIL like '%".$search_str."%' OR B.GROUP_EMAIL like '%".$search_str."%')";
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




?>