<?
	# =============================================================================
	# File Name    : youtube.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-16
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listYoutube($conn, $y_no, $y_type, $y_title, $y_date, $y_url, $y_target, $y_img, $y_img_nm, $y_thumb, $y_thumb_nm, $y_youtube_id, $y_youtube_img, $y_youtube_thumb, $date_use_tf, $ref_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntYoutube($conn, $y_no, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($conn,$query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, Y_NO, Y_TYPE, Y_TITLE, Y_DATE, Y_URL, Y_TARGET, Y_IMG, Y_IMG_NM, Y_THUMB, Y_THUMB_NM, Y_YOUTUBE_ID, Y_YOUTUBE_IMG, Y_YOUTUBE_THUMB, DATE_USE_TF, DISP_SEQ,
						  REF_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_YOUTUBE WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY REF_TF DESC, USE_TF DESC, DISP_SEQ ASC, Y_NO DESC";  //limit ".$offset.", ".$nRowCount;

		//echo $query;
		//exit;

		$result = mysqli_query($conn,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntYoutube($conn, $y_no, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_YOUTUBE WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$result = mysqli_query($conn,$query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function insertYoutube($conn, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_YOUTUBE (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_y_no = mysqli_insert_id($db);  //insert 후 y_no값을 알아오기 위한 구분
			return $new_y_no;
		}
	}

	function selectYoutube($conn, $y_no) {

		$query = "SELECT * FROM TBL_YOUTUBE WHERE Y_NO='$y_no'";

		$result = mysqli_query($conn,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateYoutube($conn, $arr_data, $y_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_YOUTUBE SET ".$set_query_str." ";
		$query .= "y_no = '$y_no' WHERE Y_NO = '$y_no' ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateYoutubeUseTF($conn, $use_tf, $up_adm, $y_no) {
		
		$query = "UPDATE TBL_YOUTUBE SET 
							USE_TF		= '$use_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE Y_NO			= '$y_no' ";

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

	function updateYoutubeRefTF($conn, $ref_tf, $up_adm, $y_no) {

		$query = "UPDATE TBL_YOUTUBE SET 
							REF_TF		= 'N',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE Y_NO			<> '$y_no' ";

		$result = mysqli_query($conn,$query);

		$query = "UPDATE TBL_YOUTUBE SET 
							REF_TF		= '$ref_tf',
							UP_ADM		= '$up_adm',
							UP_DATE		= now()
				 WHERE Y_NO			= '$y_no' ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateOrderYoutube($conn, $disp_seq_no, $y_no) {

	    $query="UPDATE TBL_YOUTUBE SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE Y_NO	= '$y_no'";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function deleteYoutube($conn, $adm_no, $y_no) {

		$query = "UPDATE TBL_YOUTUBE SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE Y_NO = '$y_no' ";

		if(!mysqli_query($conn,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}


?>