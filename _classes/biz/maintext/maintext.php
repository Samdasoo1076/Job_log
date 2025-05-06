<?
	# =============================================================================
	# File Name    : maintext.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_MAIN_TEXT
	#=========================================================================================================
	
	/*
	CREATE TABLE IF NOT EXISTS `TBL_MAIN_TEXT` (
  `SEQ_NO` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `SITE_NO` int(11) unsigned DEFAULT NULL COMMENT '사이트 일련번호',
  `TEXT_LANG` varchar(15) DEFAULT 'KOR',
  `TEXT_TITLE` varchar(200) NOT NULL COMMENT '제목',
  `TEXT_DESC` varchar(200) NOT NULL COMMENT '설명',
  `TEXT_SUB` varchar(200) NOT NULL COMMENT '서브',
  `TEXT_URL` varchar(250) NOT NULL COMMENT 'URL',
  `URL_TYPE` varchar(10) NOT NULL,
  `DISP_SEQ` int(11) unsigned DEFAULT NULL COMMENT '순서',
  `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
  PRIMARY KEY (`SEQ_NO`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listMaintext($db, $site_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntMaintext ($db, $site_no, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, SITE_NO, TEXT_LANG, TEXT_TITLE, 
										 TEXT_DESC, TEXT_SUB, TEXT_URL, URL_TYPE, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_MAIN_TEXT WHERE 1 = 1 ";

		if ($site_no <> "") {
			$query .= " AND SITE_NO = '".$site_no."' ";
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
		
		$query .= " ORDER BY DISP_SEQ asc limit ".$offset.", ".$nRowCount;

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


	function totalCntMaintext ($db, $site_no, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_MAIN_TEXT WHERE 1 = 1 ";


		if ($site_no <> "") {
			$query .= " AND SITE_NO = '".$site_no."' ";
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


	function insertMaintext($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_MAIN_TEXT (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function selectMaintext($db, $site_no, $seq_no) {

		$query = "SELECT *
								FROM TBL_MAIN_TEXT WHERE SEQ_NO = '$seq_no' AND SITE_NO = '$site_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateMaintext($db, $arr_data, $seq_no) {
		
		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_MAIN_TEXT SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "SEQ_NO = '$seq_no' WHERE SEQ_NO = '$seq_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderMaintext($db, $disp_seq_no, $site_no, $seq_no) {

		$query="UPDATE TBL_MAIN_TEXT SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE SEQ_NO	= '$seq_no' AND SITE_NO = '$site_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

		function deleteMaintext($db, $del_adm, $g_site_no, $tmp_seq_no) {

			$query = "UPDATE TBL_MAIN_TEXT SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
							WHERE SITE_NO				= '$g_site_no' 
							AND SEQ_NO				= '$tmp_seq_no' ";
	
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