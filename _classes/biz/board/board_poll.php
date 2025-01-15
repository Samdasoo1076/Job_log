<?

	function selectPoll($db, $mem_name, $jumin) {

		$query = "SELECT P_NO, P_NAME, P_REGNO, P_ADDRESS, P_POLL_TF, USE_TF, REG_DATE FROM TBL_POLLBOOK WHERE  P_NAME = '$mem_name' AND  P_REGNO = '$jumin' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updatePoll($db, $mem_name, $jumin) {

		$query = "UPDATE TBL_POLLBOOK SET 
						USE_TF					='Y',
						REG_DATE				=	now()
				 WHERE P_NAME = '$mem_name' AND P_REGNO = '$jumin' ";
		
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function PollCnt($db, $mem_name, $jumin){

		$query ="SELECT COUNT(*) CNT FROM TBL_POLLBOOK WHERE P_NAME = '$mem_name' AND P_REGNO = '$jumin' ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		
		$record  = $rows[0];
		return $record;
	}
/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//임시 저장용 관련 끝
*/
?>