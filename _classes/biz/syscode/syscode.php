<?

	# =============================================================================
	# File Name    : syscode.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-08
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_CODE_PARENT, TBL_CODE_DETAIL
	#=========================================================================================================
	/*
	CREATE TABLE IF	NOT	EXISTS TBL_CODE_PARENT (
	PCODE_NO						int(11)	unsigned NOT NULL	auto_increment	COMMENT	'대분류	코드 일련번호',
	SITE_NO						int(11)	unsigned													COMMENT	'사이트 일련번호',
	PCODE							varchar(20)	NOT	NULL											COMMENT	'대분류	코드',
	PCODE_NM						varchar(50)	NOT	NULL											COMMENT	'코드명',
	PCODE_MEMO					text																			COMMENT	'코드	메모',
	PCODE_SEQ_NO				int(11)																		COMMENT	'코드	전시 순번',
	USE_TF							char(1)	NOT	NULL default 'Y'							COMMENT	'사용	여부 사용(Y),사용안함(N)',
	DEL_TF							char(1)	NOT	NULL default 'N'							COMMENT	'삭제	여부 삭제(Y),사용(N)',
	REG_ADM						int(11)	unsigned													COMMENT	'등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	REG_DATE						datetime																	COMMENT	'등록일',
	UP_ADM							int(11)	unsigned													COMMENT	'수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	UP_DATE						datetime																	COMMENT	'수정일',
	DEL_ADM						int(11)	unsigned													COMMENT	'삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	DEL_DATE						datetime																	COMMENT	'삭제일',
	PRIMARY KEY  (PCODE_NO)
	) TYPE=MyISAM COMMENT	=	'대분류 코드 마스터';

	CREATE TABLE IF	NOT	EXISTS TBL_CODE_DETAIL (
	DCODE_NO						int(11)	unsigned NOT NULL	auto_increment	COMMENT	'하위분류	일련번호',
	PCODE							varchar(20)	NOT	NULL											COMMENT	'대분류	코드 TBL_CODE_PARENT (FK)',
	DCODE							varchar(20)	NOT	NULL											COMMENT	'하위분류	코드',
	DCODE_NM						varchar(50)	NOT	NULL											COMMENT	'코드명',
	DCODE_SEQ_NO				int(11)																		COMMENT	'코드전시순번',
	USE_TF							char(1)	NOT	NULL default 'Y'							COMMENT	'사용	여부 사용(Y),사용안함(N)',
	DEL_TF							char(1)	NOT	NULL default 'N'							COMMENT	'삭제	여부 삭제(Y),사용(N)',
	REG_ADM						int(11)	unsigned													COMMENT	'등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	REG_DATE						datetime																	COMMENT	'등록일',
	UP_ADM							int(11)	unsigned													COMMENT	'수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	UP_DATE						datetime																	COMMENT	'수정일',
	DEL_ADM						int(11)	unsigned													COMMENT	'삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	DEL_DATE						datetime																	COMMENT	'삭제일',
	PRIMARY KEY  (PCODE_NO, DCODE_NO)
	) TYPE=MyISAM COMMENT	=	'소분류 코드 마스터';
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	#PCODE_NO, PCODE, PCODE_NM, PCODE_MEMO, PCODE_SEQ_NO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATED, DEL_ADM, DEL_DATE	

	function listPcode($db, $site_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$query = "set @rownum = ".$offset."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum + 1  as rn, PCODE_NO, SITE_NO, PCODE, PCODE_NM, PCODE_MEMO, PCODE_SEQ_NO, USE_TF, DEL_TF, 
										 REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_CODE_PARENT WHERE 1 = 1 ";

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
		
		$query .= " ORDER BY PCODE_SEQ_NO, PCODE_NO desc limit ".$offset.", ".$nRowCount;
		
		#echo $query."<br>";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntPcode($db, $site_no, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_CODE_PARENT WHERE 1 = 1 ";

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
		
		#echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertPcode($db, $site_no, $pcode, $pcode_nm, $pcode_memo, $pcode_seq_no, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_CODE_PARENT (PCODE, SITE_NO, PCODE_NM, PCODE_MEMO, PCODE_SEQ_NO, USE_TF, REG_ADM, REG_DATE) 
															 values ('$pcode', '$site_no', '$pcode_nm', '$pcode_memo', '$pcode_seq_no', '$use_tf', '$reg_adm', now()); ";
		
		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectPcode($db, $pcode_no) {

		$query = "SELECT PCODE_NO, PCODE, SITE_NO, PCODE_NM, PCODE_MEMO, PCODE_SEQ_NO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_CODE_PARENT WHERE PCODE_NO = '$pcode_no' ";

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

	function updatePcode($db, $site_no, $pcode, $pcode_nm, $pcode_memo, $pcode_seq_no, $use_tf, $up_adm, $pcode_no) {
		
		/*
		$query="UPDATE TBL_CODE_PARENT SET 
													PCODE							=	'$pcode',
													SITE_NO						=	'$site_no',
													PCODE_NM					=	'$pcode_nm',
													PCODE_MEMO				=	'$pcode_memo',
													PCODE_SEQ_NO			=	'$pcode_seq_no',
													USE_TF						=	'$use_tf',
													UP_ADM						=	'$up_adm',
													UP_DATE						=	now()
											 WHERE PCODE_NO				= '$pcode_no' ";
		*/

		$query="UPDATE TBL_CODE_PARENT SET 
													PCODE							=	'$pcode',
													SITE_NO						=	'$site_no',
													PCODE_NM					=	'$pcode_nm',
													PCODE_MEMO				=	'$pcode_memo',
													USE_TF						=	'$use_tf',
													UP_ADM						=	'$up_adm',
													UP_DATE						=	now()
											 WHERE PCODE_NO				= '$pcode_no' ";

		#echo $query."<br>";
		
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deletePcode($db, $del_adm, $pcode_no) {

		$query="UPDATE TBL_CODE_PARENT SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
											 WHERE PCODE_NO			= '$pcode_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	#DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_SEQ_NO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE				


	function listDcode($db, $pcode, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;
		
		$query = "set @rownum = ".$offset."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum + 1  as rn, DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_EXT, DCODE_SEQ_NO, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_CODE_DETAIL WHERE 1 = 1 ";

		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
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
		
		$query .= " ORDER BY DCODE_SEQ_NO, DCODE_NO desc limit ".$offset.", ".$nRowCount;
		
		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntDcode($db, $pcode,  $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_CODE_DETAIL WHERE 1 = 1 ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
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


	function insertDcode($db, $pcode, $dcode, $dcode_nm, $dcode_ext, $dcode_seq_no, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_CODE_DETAIL (PCODE, DCODE, DCODE_NM, DCODE_EXT, DCODE_SEQ_NO, USE_TF, REG_ADM, REG_DATE) 
															 values ( '$pcode', '$dcode', '$dcode_nm', '$dcode_ext', '$dcode_seq_no', '$use_tf', '$reg_adm', now()); ";
		
		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectDcode($db, $pcode, $dcode_no) {

		$query = "SELECT DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_EXT, DCODE_SEQ_NO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_CODE_DETAIL WHERE DCODE_NO = '$dcode_no' AND PCODE = '$pcode' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateDcode($db, $pcode, $dcode, $dcode_nm, $dcode_ext, $use_tf, $up_adm, $dcode_no) {

		$query="UPDATE TBL_CODE_DETAIL SET
													PCODE							=	'$pcode',
													DCODE							=	'$dcode',
													DCODE_NM					=	'$dcode_nm',
													DCODE_EXT					=	'$dcode_ext',
													USE_TF						=	'$use_tf',
													UP_ADM						=	'$up_adm',
													UP_DATE						=	now()
											 WHERE DCODE_NO				= '$dcode_no' 
												 AND PCODE = '$pcode' ";
												
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderDcode($db, $dcode_seq_no, $pcode, $dcode_no) {

		$query="UPDATE TBL_CODE_DETAIL SET
													DCODE_SEQ_NO			=	'$dcode_seq_no'
											 WHERE DCODE_NO				= '$dcode_no'
												 AND PCODE = '$pcode' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateDcodeUseTF($db, $use_tf, $up_adm,  $pcode, $dcode_no) {
		
		$query="UPDATE TBL_CODE_DETAIL SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE DCODE_NO				= '$dcode_no' 
					 AND PCODE					= '$pcode' ";
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteDcode($db, $del_adm, $pcode, $dcode_no) {

		$query="UPDATE TBL_CODE_DETAIL SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
											 WHERE DCODE_NO			= '$dcode_no' 
												 AND PCODE				= '$pcode' ";
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function dupPcode ($db,$pcode) {
		
		$query ="SELECT COUNT(*) CNT FROM TBL_CODE_PARENT WHERE 1 = 1 AND DEL_TF = 'N' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		
		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}
				
	}

	function dupDcode ($db, $dcode, $pcode) {

		$query ="SELECT COUNT(*) CNT FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' ";
		
		if ($dcode <> "") {
			$query .= " AND PCODE = '".$pcode."' AND DCODE = '".$dcode."' ";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		
		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}

	}

?>