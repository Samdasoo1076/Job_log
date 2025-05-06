<?
	function totalCntPopup($db,$use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_POPUP WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listPopup($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntPopup($db,$use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, POP_NO, CATE_01, CATE_02, SIZE_W, SIZE_H, TOP, LEFT_, SCROLLBARS, TITLE, CONTENTS,
										 S_DATE, S_TIME, E_DATE, E_TIME, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF FROM TBL_POPUP WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

	//	echo $query;

		$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		$result = mysqli_query($db, $query);
		$record = array();


		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function insertPopup($db, $use_tf_date, $use_tf_linktype, $size_w, $size_h, $top, $left, $scrollbars, $s_date, $s_time, $e_date, $e_time, $title, $contents, $use_tf, $reg_date) {

		$query5 = "INSERT INTO TBL_POPUP (CATE_01, CATE_02, SIZE_W, SIZE_H, TOP, LEFT_, SCROLLBARS, TITLE, CONTENTS, S_DATE, S_TIME, E_DATE, E_TIME, USE_TF, DEL_TF, REG_DATE)
				values ('$use_tf_date', '$use_tf_linktype', '$size_w', '$size_h', '$top', '$left', '$scrollbars', '$title', '$contents', '$s_date', '$s_time', '$e_date', '$e_time', '$use_tf','N','$reg_date'); ";

		//exit;
		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
		//	deleteTemporarySave($db, $bb_code, $writer_id);
			$new_bb_no="Y";
			return $new_bb_no;
		}
	}


	function selectPopup($db, $pop_no) {

		$query = "SELECT POP_NO, CATE_01, CATE_02, SIZE_W, SIZE_H, TOP, LEFT_, SCROLLBARS, TITLE, CONTENTS, S_DATE, S_TIME, E_DATE, E_TIME,
							 USE_TF, DEL_TF, REG_ADM, REG_DATE FROM TBL_POPUP WHERE  POP_NO = '$pop_no' ";
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


	function updatePopup($db, $use_tf_date, $use_tf_linktype, $size_w, $size_h, $top, $left, $scrollbars, $s_date, $s_time, $e_date, $e_time, $title, $contents, $use_tf, $pop_no, $reg_date) {

		$query = "UPDATE TBL_POPUP SET
						CATE_01				=	'$use_tf_date',
						CATE_02				=	'$use_tf_linktype',
						SIZE_W				=	'$size_w',
						SIZE_H				=	'$size_h',
						TOP						=	'$top',
						LEFT_					=	'$left',
						SCROLLBARS			=	'$scrollbars',
						TITLE					=	'$title',
						CONTENTS			=	'$contents',
						S_DATE				=	'$s_date',
						S_TIME			=	'$s_time',
						E_DATE			=	'$e_date',
						E_TIME			=	'$e_time',
						USE_TF				=	'$use_tf',
						UP_DATE				=	now()
				 WHERE POP_NO = '$pop_no'";

		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deletePopup($db, $pop_no) {

		$query = "DELETE FROM TBL_POPUP
					 WHERE POP_NO				= '$pop_no'";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>