<?
	# =============================================================================
	# File Name    : online.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2022-08-29
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function selectOnline($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_ONLINE_INFO  WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateOnline($db, $arr_data, $seq_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ONLINE_INFO SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE SEQ_NO = '$seq_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>