<?

	# =============================================================================
	# File Name    : mainvisual.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_MAIN_VISUAL
	#=========================================================================================================
	
	/*
	CREATE TABLE IF NOT EXISTS `tbl_main_visual` (
	  `BB_CODE` varchar(15) NOT NULL DEFAULT 'MAINVISUAL' COMMENT '게시판	코드',
	  `BB_NO` int(10) NOT NULL DEFAULT '1' COMMENT '게시판	번호',
	  `WRITER_NM` varchar(20) NOT NULL DEFAULT '' COMMENT '작성자',
	  `TITLE` varchar(150) DEFAULT NULL COMMENT '제목',
	  `LINK` varchar(150) DEFAULT NULL COMMENT '링크',
	  `CONTENTS` text COMMENT '내용',
	  `FILE_NM` varchar(150) DEFAULT NULL COMMENT '제목',
	  `FILE_RNM` varchar(150) DEFAULT NULL COMMENT '제목',
	  `ORDER_NUM` int(10) DEFAULT '0',
	  `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
	  `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
	  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
	  `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	  `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
	  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	  `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
	  PRIMARY KEY (`BB_CODE`,`BB_NO`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;






	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	#BB_CODE, BB_NO,  WRITER_NM, TITLE, CONTENTS, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

	function selectMainVisualTop($db, $bb_code) {

		$query = "SELECT BB_CODE, BB_NO, WRITER_NM, TITLE, CONTENTS, ORDER_NUM, FILE_NM, FILE_RNM,
						USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
				FROM TBL_MAIN_VISUAL WHERE 1 = 1 AND ORDER_NUM >0  AND USE_TF = 'Y' AND DEL_TF = 'N' ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}


		$query .= " ORDER BY BB_NO desc limit 0, 1";
		
		//$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		//
		//if($bb_code!="MAINVISUAL")echo $query."<br>";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function listMainVisualTop($db, $bb_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, WRITER_NM, TITLE, MLINK, CONTENTS, ORDER_NUM, FILE_NM, FILE_RNM,
						USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
				FROM TBL_MAIN_VISUAL WHERE 1 = 1 AND ORDER_NUM >0 ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY ORDER_NUM desc";
		
		//$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}



	function totalCntMainVisualTop($db, $bb_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT COUNT(*) CNT FROM TBL_MAIN_VISUAL WHERE 1 = 1 AND ORDER_NUM >0 ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}


		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listMainVisual($db, $bb_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntMainVisual($db, $bb_code, $use_tf, $del_tf, $search_field, $search_str);
		
		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BB_CODE, BB_NO, WRITER_NM, TITLE, MLINK, CONTENTS, FILE_NM, FILE_RNM,
						ORDER_NUM, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
				FROM TBL_MAIN_VISUAL WHERE 1 = 1 ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		} 
		
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY BB_NO desc limit ".$offset.", ".$nRowCount;
		
		//$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntMainVisual($db, $bb_code, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_MAIN_VISUAL WHERE 1 = 1 ";
		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	/*
	function viewChkMainVisual($db, $bb_code, $bb_no) {
		
		$query="UPDATE TBL_MAIN_VISUAL SET HIT_CNT = HIT_CNT + 1 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
	
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	*/
	

	function insertMainVisual($db, $bb_code, $writer_nm, $title, $mlink, $contents, $order_num,  $file_nm, $file_rnm, $use_tf, $reg_adm) {
		
		//$query ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO FROM TBL_MAIN_VISUAL WHERE BB_CODE = '$bb_code' ";
		// 글 이동 떄문에 변경
		$query ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO FROM TBL_MAIN_VISUAL ";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		//echo $query;
		//echo $rows[0];

		if ($rows[0] <> 0) {					
			$new_bb_no = $rows[0] + 1;
			
		} else {		
			$new_bb_no = "1";
		}
		
		$ref_ip = $_SERVER['REMOTE_ADDR'];
		$query5="INSERT INTO TBL_MAIN_VISUAL (BB_CODE, BB_NO, WRITER_NM, TITLE, MLINK, CONTENTS, ORDER_NUM,  FILE_NM, FILE_RNM, USE_TF, REG_ADM, REG_DATE) 
				values ('$bb_code', '$new_bb_no', '$writer_nm', '$title', '$mlink', '$contents', '$order_num', '$file_nm', '$file_rnm', '$use_tf', '$reg_adm', now()); ";
		
		//exit;

		//echo $query5;
		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_bb_no;
		}

	}



	function selectMainVisual($db, $bb_code, $bb_no) {

		$query = "SELECT BB_CODE, BB_NO, WRITER_NM, TITLE, MLINK, CONTENTS, ORDER_NUM,  FILE_NM, FILE_RNM,
						 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
				FROM TBL_MAIN_VISUAL WHERE  BB_CODE = '$bb_code' AND  BB_NO = '$bb_no' ";
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


	function updateMainVisual($db, $writer_nm, $title, $mlink, $contents, $order_num, $file_nm, $file_rnm, $use_tf, $up_adm, $bb_code, $bb_no) {

		$ref_ip = $_SERVER['REMOTE_ADDR'];
		$query = "UPDATE TBL_MAIN_VISUAL SET 
						WRITER_NM			= '$writer_nm',
						TITLE				= '$title',
						MLINK				= '$mlink', 
						CONTENTS			= '$contents',
						ORDER_NUM			= '$order_num',
						FILE_NM				= '$file_nm', 
						FILE_RNM			= '$file_rnm',
						USE_TF				= '$use_tf',
						UP_ADM				= '$up_adm',
						UP_DATE				= now()
				 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
		
		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateMainVisualUseTF($db, $use_tf, $up_adm, $bb_code, $bb_no) {
		
		$query="UPDATE TBL_MAIN_VISUAL SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";

		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}



	function deleteMainVisual($db, $del_adm, $bb_code, $bb_no) {

			$query = "UPDATE TBL_MAIN_VISUAL SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
					WHERE BB_CODE				= '$bb_code' 
						AND BB_NO				= '$bb_no' ";

		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}







	
?>