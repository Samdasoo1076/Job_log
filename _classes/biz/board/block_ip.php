<?
	# =============================================================================
	# File Name    : block_ip.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2024-03-05
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table `TBL_BOARD_BLOCK_IP`
	#=========================================================================================================
	
	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function listBoardBlockIP($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$total_cnt = totalCntBoardBlockIP ($db, $use_tf, $del_tf, $search_field, $search_str);

		if ($total_cnt == 0) {
			$offset = 0;
		} else { 
			$offset = $nRowCount*($nPage-1);
		}

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, BLOCK_IP, MEMO,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD_BLOCK_IP WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {

			$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";
			$types .= "s";
			$bindParams[] = $search_str;

		}

		$query .= " ORDER BY SEQ_NO DESC limit ".$offset.", ".$nRowCount;

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();

		return $record;
	}

	function totalCntBoardBlockIP ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_BLOCK_IP WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = ? ";
			$types .= "s";
			$bindParams[] = $use_tf;
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = ? ";
			$types .= "s";
			$bindParams[] = $del_tf;
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like CONCAT('%',?,'%') ";
			$types .= "s";
			$bindParams[] = $search_str;
		}

		$stmt = $db->prepare($query);
		//$types = str_repeat('s', count($bindParams)); // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$stmt->bind_param($types, ...$bindParams);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;

	}

	function insertBoardBlockIP($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";

		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		foreach ($arr_data as $key => $value) {
			if ($key == "REF_IP") $value = $_SERVER['REMOTE_ADDR'];
			$value = ($value === null) ? '' : $value;
			$set_field .= $key.","; 
			$set_value .= "?,"; 
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "INSERT INTO TBL_BOARD_BLOCK_IP (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function selectBoardBlockIP($db, $seq_no) {

		$query = "SELECT SEQ_NO, BLOCK_IP, MEMO,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD_BLOCK_IP WHERE SEQ_NO = ? ";
		
		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $seq_no);
		$stmt->execute();
		$result = $stmt->get_result();

		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}

		$stmt->close();
		return $record;
	}

	function updateBoardBlockIP($db, $arr_data, $seq_no) {
	
		$types = ""; // 바인딩할 값의 데이터 유형을 지정하기 위한 문자열
		$bindParams = []; // 바인딩할 변수를 담을 배열

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "CATE_01") $CATE_01 = $value;
			$value = ($value === null) ? '' : $value;
			$set_query_str .= $key." = ?,";
			$types .= "s";
			$bindParams[] = $value;
		}

		$query = "UPDATE TBL_BOARD_BLOCK_IP SET ".$set_query_str. "
							UP_DATE				=	now()
				WHERE SEQ_NO = ? ";

		$types .= "s";
		$bindParams[] = $seq_no;

		$stmt = $db->prepare($query);
		$stmt->bind_param($types, ...$bindParams);

		if ($stmt->execute()) {

			$stmt->close();
			return true;
			exit;
		} else {
			//echo $db->error;
			$stmt->close();
			return false;
			exit;
		}
	}

	function updateBoardBlockIPUseTF($db, $use_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_BOARD_BLOCK_IP SET 
							USE_TF					= ?,
							UP_ADM					= ?,
							UP_DATE					= now()
				 WHERE SEQ_NO = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("sss", $use_tf, $up_adm, $seq_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function deleteBoardBlockIP($db, $del_adm, $seq_no) {

		$query = "DELETE FROM TBL_BOARD_BLOCK_IP WHERE SEQ_NO = ? ";
	
		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $seq_no);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			$stmt->close();
			return false;
		}
	}

	function chkBoardBlockIP ($db, $block_ip) {

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_BLOCK_IP WHERE DEL_TF = 'N' AND USE_TF = 'Y' AND BLOCK_IP = ? ";

		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $block_ip);

		$stmt->execute();
		$result = $stmt->get_result();

		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];

		$stmt->close();

		return $record;

	}
?>