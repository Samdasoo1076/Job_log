<?
	# =============================================================================
	# File Name    : meetingroom.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2024-12-03
	# Modify Date  : 
	#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	function listMeetingRoom($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.ROOM_NO, A.ROOM_NAME, A.ROOM_DEC, A.TIME_TYPE, A.ABLE_PERIOD, A.ROOM_SCALE, 
										 A.ROOM_CAPACITY, A.ROOM_PRICE, A.ROOM_NIGHT_PRICE, A.USE_TIME, A.USE_GUIDE, A.ROOM_FILE, A.ROOM_RFILE, A.DISP_SEQ,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE
							FROM TBL_MEETING_ROOM A WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY A.USE_TF DESC, A.DISP_SEQ asc, A.ROOM_NO DESC limit ".$offset.", ".$nRowCount;

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


	function totalCntMeetingRoom($db, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_MEETING_ROOM WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		//echo $query;
		//exit;
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function insertMeetingRoom($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_MEETING_ROOM (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_rooom_no = mysqli_insert_id($db);  //insert 후 m_no값을 알아오기 위한 구분
			return $new_rooom_no;
		}
	}

	function insertMeetingRoomFile($db, $arr_data) {

		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_MEETING_ROOM_FILE (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertMeetingRoomDisable($db, $arr_data) {

		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_MEETING_ROOM_DISABLE (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectMeetingRoom($db, $room_no) {

		$query = "SELECT * FROM TBL_MEETING_ROOM WHERE ROOM_NO='$room_no' AND DEL_TF='N'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectMeetingRoomFile($db, $room_no){ //INPUT BOX의 갯수를 가지고 오기 위해

		$query ="SELECT * FROM TBL_MEETING_ROOM_FILE WHERE ROOM_NO='$room_no' ORDER BY DISP_SEQ ASC";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectMeetingRoomDisable($db, $room_no){ //INPUT BOX의 갯수를 가지고 오기 위해

		$query ="SELECT * FROM TBL_MEETING_ROOM_DISABLE WHERE ROOM_NO='$room_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateMeetingRoom($db, $arr_data, $room_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_MEETING_ROOM SET ".$set_query_str." ";
		$query .= "ROOM_NO = '$room_no' WHERE ROOM_NO = '$room_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMeetingRoom($db, $adm_no, $room_no) {

		$query = "UPDATE TBL_MEETING_ROOM SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE ROOM_NO = '$room_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	function deleteMeetingRoomFile($db, $room_no) {

		$query = "DELETE FROM TBL_MEETING_ROOM_FILE WHERE ROOM_NO = '$room_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMeetingRoomDisable($db, $room_no) {

		$query = "DELETE FROM TBL_MEETING_ROOM_DISABLE WHERE ROOM_NO = '$room_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMeetingRoomDisableDate($db, $room_no, $date, $time) {

		$query = "DELETE FROM TBL_MEETING_ROOM_DISABLE WHERE ROOM_NO = '$room_no' AND DISABLE_DATE = '$date' AND DISABLE_TIME = '$time' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function cntMeetingRoomDisableDate($db, $room_no, $date, $time){

		$query ="SELECT COUNT(*) CNT FROM TBL_MEETING_ROOM_DISABLE WHERE ROOM_NO = '$room_no' AND DISABLE_DATE = '$date' AND DISABLE_TIME = '$time' ";
		
		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

?>