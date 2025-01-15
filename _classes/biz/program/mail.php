<?
	# =============================================================================
	# File Name    : mail.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-03-12
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listApplyMail($db, $seq_no, $send_flag, $send_type, $read_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT * ,@rownum:= @rownum - 1  as rn,
										 (SELECT TITLE FROM TBL_PROGRAM WHERE SEQ_NO = A.SEQ_NO) AS TITLE
								FROM TBL_APPLY_MAIL A
							 WHERE 1 = 1 ";

		if ($seq_no <> "") {
			$query .= " AND SEQ_NO = '$seq_no' ";
		}

		if ($send_flag <> "") {
			$query .= " AND SEND_FLAG = '$send_flag' ";
		}

		if ($send_type <> "") {
			$query .= " AND SEND_TYPE = '$send_type' ";
		}

		if ($read_tf <> "") {
			$query .= " AND READ_TF = '$read_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (SEQ_NUM like '%".$search_str."%' OR APP_NAME like '%".$search_str."%' OR APP_EMAIL like '%".$search_str."%' OR APP_PHONE like '%".$search_str."%')";
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

	function totalCntApplyMail($db, $seq_no, $send_flag, $send_type, $read_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(MSEQ_NO) CNT 
							 FROM TBL_APPLY_MAIL
							WHERE 1 = 1 ";

		if ($seq_no <> "") {
			$query .= " AND SEQ_NO = '$seq_no' ";
		}

		if ($send_flag <> "") {
			$query .= " AND SEND_FLAG = '$send_flag' ";
		}

		if ($send_type <> "") {
			$query .= " AND SEND_TYPE = '$send_type' ";
		}

		if ($read_tf <> "") {
			$query .= " AND READ_TF = '$read_tf' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (SEQ_NUM like '%".$search_str."%' OR APP_NAME like '%".$search_str."%' OR APP_EMAIL like '%".$search_str."%' OR APP_PHONE like '%".$search_str."%')";
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
	
	function deleteApplyMail($db, $adm_no, $mseq_no) {

		$query = "UPDATE TBL_APPLY_MAIL SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE MSEQ_NO = '$mseq_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>