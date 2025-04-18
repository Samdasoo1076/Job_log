<?
	# =============================================================================
	# File Name    : join_company.php
	# Modlue       : Park Chan Ho's framwork
	# Writer       : Kim Kyeong Eon
	# Create Date  : 2025-04-07
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	
	
	//company 리스트
	function listJoinCompany($db, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount) {

		$total_cnt = totalCntJoinCompany($db, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount * ($nPage - 1);
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "SET @rownum := ".$logical_num.";";
		mysqli_query($db, $query);

		$query = "
			SELECT 
				@rownum := @rownum - 1 AS rn,
				JCP_NO,
				SEQ,
				C_NM,
				PHONE,
				CONTENT,
				URL,
				HO,
				USE_TF,
				DEL_TF,
				REG_DATE,
				UP_DATE,
				DEL_DATE,
				REG_ADM,
				UP_ADM,
				EL_ADM
			FROM TBL_JOIN_COMPANY
			WHERE 1 = 1
		";

		if ($use_tf != "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf != "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str != "") {
			$query .= " AND ".$search_field." LIKE '%".$search_str."%' ";
		}

		if ($order_field == "") $order_field = "REG_DATE";
		if ($order_str == "") $order_str = "DESC";

		$query .= " ORDER BY ".$order_field." ".$order_str." LIMIT ".$offset.", ".$nRowCount;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result != "") {
			for($i = 0; $i < mysqli_num_rows($result); $i++) {
				$record[$i] = sql_result_array($db, $result, $i);
			}
		}
		return $record;
	}


	function insertJoinCompany($db, $seq, $c_nm, $phone, $content, $url, $use_tf, $reg_adm, $ho) {

		$query = "
			INSERT INTO TBL_JOIN_COMPANY (
				SEQ, C_NM, PHONE, CONTENT, URL, 
				USE_TF, DEL_TF, REG_DATE, REG_ADM, HO
			) VALUES (
				'$seq', '$c_nm', '$phone', '$content', '$url',
				'$use_tf', 'N', NOW(), '$reg_adm', '$ho'
			)
		";

		$result = mysqli_query($db, $query);

		if (!$result) {
			echo "<script>alert('[1] 오류가 발생하였습니다.');</script>";
			return false;
		}

		return true;
	}




	function selectJoinCompany($db, $jcp_no) {

		$query = "
			SELECT 
				JCP_NO, SEQ, C_NM, PHONE, CONTENT, URL, USE_TF, DEL_TF, 
				DATE_FORMAT(REG_DATE, '%Y-%m-%d %H:%i:%s') AS REG_DATE, 
				DATE_FORMAT(UP_DATE, '%Y-%m-%d %H:%i:%s') AS UP_DATE, 
				DATE_FORMAT(DEL_DATE, '%Y-%m-%d %H:%i:%s') AS DEL_DATE, 
				REG_ADM, UP_ADM, EL_ADM, HO
			FROM 
				TBL_JOIN_COMPANY
			WHERE 
				JCP_NO = '$jcp_no'
		";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result) {
			for ($i = 0; $i < mysqli_num_rows($result); $i++) {
				$record[$i] = sql_result_array($db, $result, $i);
			}
		}

		return $record;
	}


	function updateJoinCompany($db, $seq, $c_nm, $phone, $content, $url, $use_tf, $up_adm, $jcp_no, $ho) {

		$query = "
			UPDATE TBL_JOIN_COMPANY SET 
				SEQ = '$seq',
				C_NM = '$c_nm',
				PHONE = '$phone',
				CONTENT = '$content',
				URL = '$url',
				USE_TF = '$use_tf',
				UP_DATE = NOW(),
				UP_ADM = '$up_adm',
				HO = '$ho'
			WHERE JCP_NO = '$jcp_no'
		";

		if (!mysqli_query($db, $query)) {
			echo "<script>alert('[1] 오류가 발생하였습니다.');</script>";
			return false;
		} else {
			return true;
		}
	}

	function chkJoinCompanyNm($db, $c_nm) {
		$query = "
			SELECT COUNT(*) AS CNT 
			FROM TBL_JOIN_COMPANY 
			WHERE C_NM = '$c_nm' AND DEL_TF = 'N'
		";

		$result = mysqli_query($db, $query);
		$rows = mysqli_fetch_array($result);

		if ($rows[0] == 0) {
			return true;
		} else {
			return false;
		}
	}

	
?>
