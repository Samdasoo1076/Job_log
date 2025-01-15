<?
	# =============================================================================
	# File Name    : ip.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table `TBL_BLOCK_IP`
	#=========================================================================================================
	
	/*
CREATE TABLE IF NOT EXISTS `TBL_BLOCK_IP` (
  `SEQ_NO` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `BLOCK_IP` varchar(20) NOT NULL,
  `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_CANDIDATE',
  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_CANDIDATE',
  `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_CANDIDATE',
  `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
  PRIMARY KEY (`SEQ_NO`)
) ENGINE=MyISAM
	*/

	#BANNER_NO, SITE_NO, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, DISP_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listBlockIP($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBlockIP ($db, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, BLOCK_IP, MEMO,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BLOCK_IP WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY SEQ_NO DESC limit ".$offset.", ".$nRowCount;

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


	function totalCntBlockIP ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_BLOCK_IP WHERE 1 = 1 ";

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


	function insertBlockIP($db, $block_ip, $memo, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_BLOCK_IP (BLOCK_IP, MEMO, USE_TF, REG_ADM, REG_DATE) 
											 values ('$block_ip', '$memo', '$use_tf', '$reg_adm', now()); ";
		
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

	function selectBlockIP($db, $seq_no) {

		$query = "SELECT SEQ_NO, BLOCK_IP, MEMO,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BLOCK_IP WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateBlockIP($db, $block_ip, $memo, $use_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_BLOCK_IP SET 
							BLOCK_IP				= '$block_ip', 
							MEMO						= '$memo', 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				WHERE SEQ_NO = '$seq_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBlockIPUseTF($db, $use_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_BLOCK_IP SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE SEQ_NO = '$seq_no' ";

		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteBlockIP($db, $del_adm, $seq_no) {

		$query = "DELETE FROM TBL_BLOCK_IP WHERE SEQ_NO = '$seq_no' ";
	
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