<?
	# =============================================================================
	# File Name    : config.php
	# Modlue       : 
	# Writer       : Park Chan Ho / JeGal Jeong
	# Create Date  : 2020-10-19
	# Modify Date  : 2021-03-16
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	
	function selectConfig($conn, $c_no) {

		$query = "SELECT * FROM TBL_CONFIG WHERE C_NO='$c_no'";

		$result = mysqli_query($conn,$query);
		$record = array();

		$record = mysqli_fetch_array($result);

		return $record;
	}

	function updateConfig($conn, $arr_data, $c_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_CONFIG SET ".$set_query_str." ";
		$query .= "C_NO = $c_no WHERE C_NO = $c_no ";

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