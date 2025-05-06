<?
	# =============================================================================
	# File Name    : banner.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-11-17
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listBanner($db, $banner_type, $cate_01, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBanner($db, $banner_type, $cate_01, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BANNER_NO, BANNER_NM, BANNER_TYPE, CATE_01, TITLE_NM, SUB_TITLE_NM, 
										BANNER_IMG, BANNER_REAL_IMG, BANNER_IMG_M, BANNER_REAL_IMG_M, BANNER_URL, BANNER_BUTTON, URL_TYPE, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE 1 = 1 ";

		if ($banner_type <> "") {
			$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, BANNER_NO DESC";  //limit ".$offset.", ".$nRowCount;

//		echo $query;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntBanner($db, $banner_type, $cate_01, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BANNER WHERE 1 = 1 ";

		if ($banner_type <> "") {
			$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function insertBanner($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_BANNER (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

//echo $query;
//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectBanner($db, $banner_no) {

		$query = "SELECT * FROM TBL_BANNER WHERE BANNER_NO = '$banner_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateBanner($db, $arr_data, $banner_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_BANNER SET ".$set_query_str."  ";
		$query .= "UP_DATE = now() WHERE BANNER_NO = '$banner_no' ";

		//echo $query."<br>";
		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteBanner($db, $adm_no, $banner_no) {

		$query = "UPDATE TBL_BANNER SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE BANNER_NO = '$banner_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBannerUseTF($db, $use_tf, $up_adm, $banner_no) {
		
		$query="UPDATE TBL_BANNER SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE BANNER_NO			= '$banner_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderBanner($db, $disp_seq_no, $banner_no) {

		$query="UPDATE TBL_BANNER SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE BANNER_NO	= '$banner_no' ";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function getMainlistBanner($db, $banner_type) {

		$query = "SELECT BANNER_NO, BANNER_NM, BANNER_TYPE, CATE_01, TITLE_NM, SUB_TITLE_NM, 
										 BANNER_IMG, BANNER_REAL_IMG, BANNER_IMG_M, BANNER_REAL_IMG_M, BANNER_URL, BANNER_BUTTON, URL_TYPE, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE USE_TF = 'Y' AND DEL_TF = 'N' ";

		if ($banner_type <> "") {
			$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		}
		
		$query .= " ORDER BY DISP_SEQ asc, BANNER_NO DESC";  //limit ".$offset.", ".$nRowCount;

//		echo $query;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

?>