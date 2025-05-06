<?

	function insertHoliday($db, $h_date, $is_holiday, $title, $reg_adm) {
		
		$query ="SELECT COUNT(H_DATE) AS CNT FROM TBL_HOLIDAY WHERE H_DATE = '$h_date' ";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		
		$cnt = $rows[0];
		
		if ($cnt == 0) {
			$query = "INSERT INTO TBL_HOLIDAY (H_DATE, IS_HOLIDAY, TITLE, REG_ADM, REG_DATE) 
																 values ('$h_date','$is_holiday', '$title', '$reg_adm', now()); ";
		} else {
			$query = "UPDATE TBL_HOLIDAY SET IS_HOLIDAY = '$is_holiday', TITLE = '$title', REG_ADM = '$reg_adm', REG_DATE = now() 
								 WHERE H_DATE = '$h_date' ";
		}
		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteHoliday($db, $h_date) {
		

		$query = "DELETE FROM TBL_HOLIDAY WHERE H_DATE = '$h_date' ";

		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectHoliday($db, $h_date) {

		$query = "SELECT H_DATE, IS_HOLIDAY, TITLE, REG_ADM, REG_DATE
								FROM TBL_HOLIDAY WHERE H_DATE = '$h_date' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function getHolidayDate($db, $h_date) {
		
		$return_str = "";

		$query = "SELECT H_DATE, IS_HOLIDAY, TITLE, REG_ADM, REG_DATE
								FROM TBL_HOLIDAY WHERE H_DATE = '$h_date' ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$H_DATE			= "";
		$IS_HOLIDAY = "";
		$TITLE			= "";

		if ($result && mysqli_num_rows($result) > 0) {
			$H_DATE			= $rows[0];
			$IS_HOLIDAY = $rows[1];
			$TITLE			= $rows[2];
		}

		if ($H_DATE <> "") {
			if ($IS_HOLIDAY == "Y") {
				$return_str = "<font color='red'>[휴일]</font>";
			} else {
				$return_str = "<font color='navy'>[휴일아님]</font>";
			}
			
			$return_str = $return_str."&nbsp;".$TITLE;

		}

		return $return_str;

	}
?>