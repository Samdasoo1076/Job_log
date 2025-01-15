<?

	# =============================================================================
	# File Name    : set_board.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_BOARD
	#=========================================================================================================
	
	/*
	CREATE TABLE IF	NOT	EXISTS TBL_BOARD (
	BB_CODE							varchar(5) NOT NULL	default	''						COMMENT	'게시판	코드',
	BB_NO								int(10)	NOT	NULL default '1'							COMMENT	'게시판	번호',
	BB_PO								int(10)	NOT	NULL default '1'							COMMENT	'게시물	포지션 번호',
	BB_RE								int(10)	NOT	NULL default '1'							COMMENT	'게시물	답변 번호',
	BB_DE								int(10)	NOT	NULL default '1'							COMMENT	'게시물	뎁스 번호',
	CATE_01							varchar(50)	default	NULL									COMMENT	'임시	1',
	CATE_02							varchar(50)	default	NULL,
	CATE_03							varchar(50)	default	NULL,
	CATE_04							varchar(50)	default	NULL,
	WRITER_NM						varchar(20)	NOT	NULL default ''						COMMENT	'작성자',
	WRITER_PW						varchar(20)	NOT	NULL default ''						COMMENT	'작성자	비밀번호',
	EMAIL								varchar(100) default NULL									COMMENT	'작성자	이메일',
	HOMEPAGE						varchar(100) default NULL									COMMENT	'작성자	홈페이지',
	TITLE								varchar(100) default NULL									COMMENT	'제목',
	HIT_CNT							int(11)	default	'0'												COMMENT	'조회수',
	REF_IP							varchar(20)	default	NULL									COMMENT	'관련	URL',
	RECOMM							int(11)	default	'0'												COMMENT	'추천수',
	CONTENTS						text																			COMMENT	'내용',
	FILE_NM							varchar(150) NOT NULL	default	''					COMMENT	'첨부	파일명',
	FILE_RNM						varchar(150) NOT NULL	default	''					COMMENT	'첨부	파일 실제	파일명',
	FILE_PATH						varchar(150) NOT NULL	default	''					COMMENT	'파일	경로',
	FILE_SIZE						int(11)																		COMMENT	'파일	사이즈',
	FILE_EXT						varchar(5) NOT NULL	default	''						COMMENT	'파일	확장자',
	KEYWORD							varchar(200) NOT NULL	default	''					COMMENT	'키워드',
	REPLY								text																			COMMENT	'답변',
	REPLY_ADM						int(11)	unsigned													COMMENT	'답변	관리자 TBL_ADMIN ADM_NO',
	REPLY_DATE					datetime																	COMMENT	'답변일',
	REPLY_STATE					char(1)	default	'N'												COMMENT	'답변	상태',
	COMMENT_TF					char(1)	default	'N'												COMMENT	'답변	사용 사용(Y),사용안함(N)',
	USE_TF							char(1)	NOT	NULL default 'Y'							COMMENT	'사용	여부 사용(Y),사용안함(N)',
	DEL_TF							char(1)	NOT	NULL default 'N'							COMMENT	'삭제	여부 삭제(Y),사용(N)',
	REG_ADM							int(11)	unsigned													COMMENT	'등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	REG_DATE						datetime																	COMMENT	'등록일',
	UP_ADM							int(11)	unsigned													COMMENT	'수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	UP_DATE							datetime																	COMMENT	'수정일',
	DEL_ADM							int(11)	unsigned													COMMENT	'삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	DEL_DATE						datetime																	COMMENT	'삭제일',
	PRIMARY	KEY	 (BB_CODE, BB_NO)
	)	TYPE=MyISAM COMMENT	=	'게시판 마스터';


	CREATE TABLE IF NOT EXISTS `TBL_BOARD_FILE` (
  `FILE_NO` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일 SEQ',
  `BB_CODE` varchar(15) NOT NULL DEFAULT '' COMMENT '게시판	코드',
  `BB_NO` int(10) NOT NULL DEFAULT '1' COMMENT '게시판	번호',
  `FILE_NM` varchar(150) NOT NULL DEFAULT '' COMMENT '첨부	파일명',
  `FILE_RNM` varchar(150) NOT NULL DEFAULT '' COMMENT '첨부	파일 실제	파일명',
  `FILE_PATH` varchar(150) NOT NULL DEFAULT '' COMMENT '파일	경로',
  `FILE_SIZE` int(11) DEFAULT NULL COMMENT '파일	사이즈',
  `FILE_EXT` varchar(5) NOT NULL DEFAULT '' COMMENT '파일	확장자',
  `HIT_CNT` int(11) DEFAULT '0' COMMENT '조회수',
  `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
  PRIMARY KEY (`FILE_NO`)
	) ENGINE=MyISAM DEFAULT CHARSET=euckr AUTO_INCREMENT=1 ;

	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	#BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP RECOMM, CONTENTS, 
	#FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

	function listBoardTop($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.BB_CODE = TBL_BOARD_FILE.BB_CODE 
												 AND TBL_BOARD.BB_NO = TBL_BOARD_FILE.BB_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD WHERE 1 = 1 AND TOP_TF = 'Y' ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
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

		$query .= " ORDER BY BB_PO asc ";
		
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


	function listBoard($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoard($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.BB_CODE = TBL_BOARD_FILE.BB_CODE 
												 AND TBL_BOARD.BB_NO = TBL_BOARD_FILE.BB_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD WHERE 1 = 1 ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
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

		$query .= " ORDER BY BB_PO asc limit ".$offset.", ".$nRowCount;
		
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

	function totalCntBoard($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD WHERE 1 = 1 ";
		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
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

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

function listBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD WHERE 1 = 1 ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
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
		
		$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

	//	echo $query."<br>";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD WHERE 1 = 1 ";
		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
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

	//	echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectPostBoard($db, $bb_code, $bb_po, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD WHERE BB_PO > '$bb_po' ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
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

		$query .= " ORDER BY BB_PO limit 1";
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

	function selectPreBoard($db, $bb_code, $bb_po, $cate_01, $cate_02, $cate_03, $cate_04, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD WHERE BB_PO < '$bb_po' ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (KEYWORD like '%".$keyword."%') or (TITLE like '%".$keyword."%') or (WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
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
								
		$query .= " ORDER BY BB_PO DESC limit 1";
		
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



	function viewChkBoard($db, $bb_code, $bb_no) {
		
		$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
	
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function viewChkBoardAsMember($db, $bb_code, $bb_no, $member_id) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ_CNT WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' AND READ_MEMBER = '$member_id' ";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		
		if ($rows[0] == 0) {
			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
			mysqli_query($db, $query);

			$query="INSERT INTO TBL_BOARD_READ_CNT (BB_CODE, BB_NO, READ_MEMBER, REG_DATE) VALUES ('$bb_code', '$bb_no', '$member_id', now()) ";
			mysqli_query($db, $query);
			
		}
	}

	function insertBoard($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $reg_adm, $wr_hit, $wr_datetime) {
		
		$query ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO FROM TBL_BOARD WHERE BB_CODE = '$bb_code' ";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		if ($rows[0] <> 0) {
					
			$new_bb_no = $rows[0] + 1;

			//답변글 번호 찾기
			$query2 ="SELECT IFNULL(MAX(BB_RE),0) AS MAX_NO FROM TBL_BOARD WHERE BB_CODE = '$bb_code' ";
			$result2 = mysqli_query($db,$query2);
			$rows2   = mysqli_fetch_array($result2);

			$new_bb_re = $rows2[0] + 1;

			//po 최소값 찾기
			$query3 ="SELECT IFNULL(MIN(BB_PO),0) AS MAX_NO FROM TBL_BOARD WHERE BB_CODE = '$bb_code' ";
			$result3 = mysqli_query($db,$query3);
			$rows3   = mysqli_fetch_array($result3);

			$new_bb_po = $rows3[0] + 1;


			$query4 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 WHERE BB_CODE = '$bb_code' AND BB_PO > 0 ";

			mysqli_query($db,$query4);
		
		} else {
		
			$new_bb_no = "1";
			$new_bb_po = "1";
			$new_bb_re = "1";
			$new_bb_de = "1";

		}
		
		$query5="INSERT INTO TBL_BOARD (BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_NM, WRITER_PW, EMAIL, 
																		HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, 
																	  CONTENTS, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, COMMENT_TF, TOP_TF, USE_TF, REG_ADM, 
																		REG_DATE) 
														values ('$bb_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '1', '$new_bb_re', '1', '$writer_nm', '$writer_pw', 
																		'$email', '$homepage', '$title', '$wr_hit', '$ref_ip', '$recomm', '$contents', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', 
																		'$keyword', '$comment_tf', '$top_tf', '$use_tf', '$reg_adm', '$wr_datetime'); ";
		
		//echo "<br>".$query5."<br>";

		//exit;

		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_bb_no;
		}

	}

	function insertBoardReply($db, $bb_code, $bb_no, $bb_po, $bb_re, $bb_de, $cate_01, $cate_02, $cate_03, $cate_04, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $reg_adm, $hit_cnt, $reg_date) {
		
		$new_bb_de = $bb_de + 1;

		$query = "SELECT COUNT(BB_NO) AS CNT 
								FROM TBL_BOARD 
							 WHERE BB_CODE = '$bb_code' 
								 AND BB_RE = '$bb_re' 
								 AND BB_DE > '$bb_de' ";

		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);

		$plus_po = $row[0];

		$new_bb_po = $bb_po + $plus_po + 1;

		$query1 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 
							 WHERE BB_CODE = '$bb_code' 
								 AND BB_PO >= '$new_bb_po' ";

		mysqli_query($db,$query1);
		
		$query2 ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO 
								FROM TBL_BOARD 
							 WHERE BB_CODE = '$bb_code' ";

		$result2 = mysqli_query($db,$query2);
		$rows2   = mysqli_fetch_array($result2);

		$new_bb_no = $rows2[0] + 1;
		
		$query5="INSERT INTO TBL_BOARD (BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, 
																	 CONTENTS, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, COMMENT_TF, TOP_TF, USE_TF, REG_ADM, REG_DATE) 
														values ('$bb_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '$new_bb_po', '$bb_re', '$new_bb_de', '$writer_nm', '$writer_pw', 
																		'$email', '$homepage', '$title', '$hit_cnt', '$ref_ip', '$recomm', '$contents', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', 
																		'$keyword', '$comment_tf', '$top_tf,', '$use_tf', '$reg_adm','$reg_date'); ";
		


		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_bb_no;
		}
	}


	function selectBoard($db, $bb_code, $bb_no) {

		$query = "SELECT BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_NM, WRITER_PW, EMAIL, 
										 HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS, 
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, REF_IP
								FROM TBL_BOARD WHERE  BB_CODE = '$bb_code' AND  BB_NO = '$bb_no' ";
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


	function updateBoard($db, $cate_01, $cate_02, $cate_03, $cate_04, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $up_adm, $bb_code, $bb_no) {

		$query = "UPDATE TBL_BOARD SET 
													CATE_01				=	'$cate_01',
													CATE_02				=	'$cate_02',
													CATE_03				=	'$cate_03',
													CATE_04				=	'$cate_04',
													WRITER_NM			=	'$writer_nm',
													WRITER_PW			=	'$writer_pw',
													EMAIL					=	'$email',
													HOMEPAGE			=	'$homepage',
													TITLE					=	'$title',
													REF_IP				=	'$ref_ip',
													CONTENTS			=	'$contents',
													FILE_NM				=	'$file_nm',
													FILE_RNM			=	'$file_rnm',
													FILE_PATH			=	'$file_path',
													FILE_SIZE			=	'$file_size',
													FILE_EXT			=	'$file_ext',
													KEYWORD				=	'$keyword',
													COMMENT_TF		=	'$comment_tf',
													TOP_TF				= '$top_tf',
													USE_TF				=	'$use_tf',
													UP_ADM				=	'$up_adm',
													UP_DATE				=	now()
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

	function updateBoardUseTF($db, $use_tf, $up_adm, $bb_code, $bb_no) {
		
		$query="UPDATE TBL_BOARD SET 
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

	function updateBoardConfirmTF($db, $confirm_tf, $up_adm, $bb_code, $bb_no) {
		
		$query="UPDATE TBL_BOARD SET 
							REPLY_STATE					= '$confirm_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateQnaAnswer($db, $reply, $reply_adm, $reply_state, $bb_code, $bb_no) {

		$query = "UPDATE TBL_BOARD SET 
													REPLY				=	'$reply',
													REPLY_ADM		=	'$reply_adm',
													REPLY_STATE	=	'$reply_state',
													REPLY_DATE	=	now()
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

	function deleteBoard($db, $del_adm, $bb_code, $bb_no) {

		$query =  "SELECT BB_DE, BB_RE FROM TBL_BOARD 
							  WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND BB_CODE				= '$bb_code' 
									AND BB_NO					= '$bb_no' ";
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$list = mysqli_fetch_array($result);
		
		$sde = $list[BB_DE];
		$sre = $list[BB_RE];

		$query =	"SELECT BB_DE FROM TBL_BOARD 
								WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND BB_CODE = '$bb_code' 
									AND BB_RE = '$sre' 
								ORDER BY BB_DE DESC limit 1";

		$result = mysqli_query($db, $query);
		$list		= mysqli_fetch_array($result);
		$chk_sde = $list[BB_DE];

		$query = "DELETE FROM TBL_BOARD_FILE 
							 WHERE BB_CODE				= '$bb_code' 
								AND BB_NO					= '$bb_no' ";

		mysqli_query($db, $query);

		if ($sde != $chk_sde) { 
			
			$query = "UPDATE TBL_BOARD SET 
															TITLE = '작성자 또는 관리자에 의해 삭제 되었습니다.', 
															CONTENTS = '답변글이 남아 있어 내용만 삭제 되었습니다.'
									WHERE BB_CODE				= '$bb_code' 
										AND BB_NO					= '$bb_no' ";
		} else {

			$query = "UPDATE TBL_BOARD SET
													 DEL_TF				= 'Y',
													 DEL_ADM			= '$del_adm',
													 DEL_DATE			= now()
									WHERE BB_CODE				= '$bb_code' 
										AND BB_NO					= '$bb_no' ";
		}
		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listCompanyBoard($db, $bb_code, $bb_no, $type) {
	
		if ($type == 'NO') {
			$query = "SELECT CP_NO, CP_NM
									FROM TBL_COMPANY 
								 WHERE USE_TF = 'Y'
									 AND DEL_TF = 'N'
									 AND CP_NO NOT IN (SELECT CP_NO FROM TBL_BOARD_COMPANY WHERE BB_NO = '$bb_no' AND BB_CODE = '$bb_code' ) 
								 ORDER BY CP_NM ASC ";
		} else {
			$query = "SELECT CP_NO, CP_NM
									FROM TBL_COMPANY 
								 WHERE USE_TF = 'Y'
									 AND DEL_TF = 'N'
									 AND CP_NO IN (SELECT CP_NO FROM TBL_BOARD_COMPANY WHERE BB_NO = '$bb_no' AND BB_CODE = '$bb_code') 
								 ORDER BY CP_NM ASC ";
		}

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

	function makeCompanyScriptArray($db, $objname) {

		$query = "SELECT CP_NO, CP_NM, CP_TYPE
								FROM TBL_COMPANY 
							 WHERE USE_TF = 'Y'
								 AND DEL_TF = 'N'
							 ORDER BY CP_NM ASC, CP_NO ASC ";
		
		$result = mysqli_query($db, $query);
		$total  = mysql_affected_rows();
			
		$tmp_str_no			=	"";
		$tmp_str_name		=	"";
		$tmp_str_type		=	"";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysqli_fetch_array($result);
			
			$RS_CP_NO			= Trim($row[0]);
			$RS_CP_NM			= Trim($row[1]);
			$RS_CP_TYPE		= Trim($row[2]);

			$tmp_str_no			.= ",'".$RS_CP_NO."'";
			$tmp_str_name		.= ",'".$RS_CP_NM."'";
			$tmp_str_type		.= ",'".$RS_CP_TYPE."'";
				
		}
		
		$tmp_str_no			= substr($tmp_str_no, 1, strlen($tmp_str_no)-1);
		$tmp_str_name		= substr($tmp_str_name, 1, strlen($tmp_str_name)-1);
		$tmp_str_type		= substr($tmp_str_type, 1, strlen($tmp_str_type)-1);


		$tmp_str	= $objname."_no = new Array(".$tmp_str_no."); \n";
		$tmp_str .= $objname."_name = new Array(".$tmp_str_name."); \n";
		$tmp_str .= $objname."_type = new Array(".$tmp_str_type."); \n";

		return $tmp_str;
	}

	function deleteBoardCompany($db, $bb_code, $bb_no) {

		$query="DELETE FROM TBL_BOARD_COMPANY 
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

	function insertBoardCompany($db, $bb_code, $bb_no, $cp_no) {
		
		$query="INSERT INTO TBL_BOARD_COMPANY (BB_CODE, BB_NO, CP_NO) 
														values ('$bb_code', '$bb_no', '$cp_no'); ";
		
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listBoardCompany($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $cp_no, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoardCompany($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $cp_no, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.BB_CODE, A.BB_NO, A.BB_PO, A.BB_RE, A.BB_DE, A.CATE_01, A.CATE_02, A.CATE_03, A.CATE_04, 
										 A.WRITER_NM, A.WRITER_PW, A.EMAIL, A.HOMEPAGE, A.TITLE, A.HIT_CNT, A.REF_IP, A.RECOMM, A.CONTENTS,
										 A.FILE_NM, A.FILE_RNM, A.FILE_PATH, A.FILE_SIZE, A.FILE_EXT, A.KEYWORD, A.REPLY, A.REPLY_ADM, A.REPLY_DATE, A.REPLY_STATE, A.COMMENT_TF,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE
								FROM TBL_BOARD A, TBL_BOARD_COMPANY B WHERE A.BB_CODE = B.BB_CODE AND A.BB_NO = B.BB_NO ";

		
		if ($bb_code <> "") {
			$query .= " AND A.BB_CODE = '".$bb_code."' ";
		}

		if ($cp_no <> "") {
			$query .= " AND B.CP_NO= '".$cp_no."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND A.CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND A.CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND A.CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND A.CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (A.KEYWORD like '%".$keyword."%') or (A.TITLE like '%".$keyword."%') or (A.WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND A.REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY A.REG_DATE desc limit ".$offset.", ".$nRowCount;

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

	function totalCntBoardCompany($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $cp_no, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD A, TBL_BOARD_COMPANY B WHERE A.BB_CODE = B.BB_CODE AND A.BB_NO = B.BB_NO ";
		
		if ($bb_code <> "") {
			$query .= " AND A.BB_CODE = '".$bb_code."' ";
		}

		if ($cp_no <> "") {
			$query .= " AND B.CP_NO= '".$cp_no."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND A.CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND A.CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND A.CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND A.CATE_04 = '".$cate_04."' ";
		}

		if ($keyword <> "") {
			$query .= " AND ( (A.KEYWORD like '%".$keyword."%') or (A.TITLE like '%".$keyword."%') or (A.WRITER_NM like '%".$keyword."%')) ";
		}

		if ($reply_state <> "") {
			$query .= " AND A.REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listBoardConfig($db, $site_no, $board_code, $board_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoardConfig($db, $site_no, $board_code, $board_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, B.CONFIG_NO, B.SITE_NO, B.BOARD_CODE, B.BOARD_TYPE, B.READ_GROUP, B.WRITE_GROUP, 
										 B.REPLY_TF, B.HTML_TF, B.FILE_TF, B.FILE_CNT, B.BOARD_NM, B.BOARD_MEMO,
										 B.USE_TF, B.DEL_TF, B.REAL_TF, B.REG_ADM, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE
								FROM TBL_BOARD_CONFIG B WHERE 1 = 1 ";

		if ($site_no <> "") {
			$query .= " AND B.SITE_NO = '".$site_no."' ";
		}

		if ($board_code <> "") {
			$query .= " AND B.BOARD_CODE = '".$board_code."' ";
		}

		if ($board_type <> "") {
			$query .= " AND B.BOARD_TYPE = '".$board_type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY B.REG_DATE desc limit ".$offset.", ".$nRowCount;
		
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


	function totalCntBoardConfig($db, $site_no, $board_code, $board_type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_CONFIG B WHERE 1 = 1 ";
		
		if ($site_no <> "") {
			$query .= " AND B.SITE_NO = '".$site_no."' ";
		}

		if ($board_code <> "") {
			$query .= " AND B.BOARD_CODE = '".$board_code."' ";
		}

		if ($board_type <> "") {
			$query .= " AND B.BOARD_TYPE = '".$board_type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		//echo $query."<br>";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertBoardConfig($db, $site_no, $board_code, $board_type, $read_group, $write_group, $reply_tf, $html_tf, $file_tf, $file_cnt, $board_nm, $board_memo, $use_tf, $reg_adm) {
		
		$query ="SELECT IFNULL(MAX(CONFIG_NO),0) AS MAX_NO FROM TBL_BOARD_CONFIG WHERE SITE_NO = '$site_no' ";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		if ($rows[0] <> 0) {
					
			$new_config_no	= $rows[0] + 1;
			$new_board_code	= $board_type."_".$new_config_no;
			
		} else {
		
			$new_config_no	= "1";
			$new_board_code	= $board_type."_".$new_config_no;

		}
		
		$query5="INSERT INTO TBL_BOARD_CONFIG (CONFIG_NO, SITE_NO, BOARD_CODE, BOARD_TYPE, READ_GROUP, WRITE_GROUP, REPLY_TF, HTML_TF, 
																					 FILE_TF, FILE_CNT, BOARD_NM, BOARD_MEMO, USE_TF, REG_ADM, REG_DATE) 
														values ('$new_config_no', '$site_no', '$new_board_code', '$board_type', '$read_group', '$write_group', 
																		'$reply_tf', '$html_tf', '$file_tf', '$file_cnt', '$board_nm', '$board_memo',
																		'$use_tf', '$reg_adm', now()); ";
		
		//echo $query5;

		//exit;

		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function updateBoardConfig($db, $site_no, $board_code, $board_type, $read_group, $write_group, $reply_tf, $html_tf, $file_tf, $file_cnt, $board_nm, $board_memo, $use_tf, $up_adm, $site_no, $config_no) {
		
		$new_board_code	= $board_type."_".$config_no;

		$query = "UPDATE TBL_BOARD_CONFIG SET 
													SITE_NO				=	'$site_no',
													BOARD_CODE		=	'$new_board_code',
													BOARD_TYPE		=	'$board_type',
													READ_GROUP		=	'$read_group',
													WRITE_GROUP		=	'$write_group',
													REPLY_TF			=	'$reply_tf',
													HTML_TF				=	'$html_tf',
													FILE_TF				=	'$file_tf',
													FILE_CNT			= '$file_cnt',
													BOARD_NM			= '$board_nm',
													BOARD_MEMO		= '$board_memo',
													USE_TF				= '$use_tf',
													UP_ADM				=	'$up_adm',
													UP_DATE				=	now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";
		
		//echo $query."<br>";


		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectBoardConfig($db, $site_no, $config_no) {

		$query = "SELECT B.CONFIG_NO, B.SITE_NO, B.BOARD_CODE, B.BOARD_TYPE, B.READ_GROUP, B.WRITE_GROUP, B.REPLY_TF, B.HTML_TF, 
										 B.FILE_TF, B.FILE_CNT, B.BOARD_NM, B.BOARD_MEMO, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE
								FROM TBL_BOARD_CONFIG B
							 WHERE B.SITE_NO		=	'$site_no' 
								 AND B.CONFIG_NO = '$config_no' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function deleteBoardConfig($db, $del_adm, $site_no, $config_no) {

		$query="UPDATE TBL_BOARD_CONFIG SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		//echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateBoardConfigUseTF($db, $use_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET 
													USE_TF					= '$use_tf',
													UP_ADM					= '$up_adm',
													UP_DATE					= now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBoardConfigRealTF($db, $real_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET 
													REAL_TF					= '$real_tf',
													UP_ADM					= '$up_adm',
													UP_DATE					= now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listBoardFile($db, $bb_code, $bb_no) {

		$query = "SELECT FILE_NO, BB_CODE, BB_NO,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT,
										 DEL_TF, REG_ADM, REG_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD_FILE WHERE 1 = 1 
								 AND BB_CODE = '".$bb_code."' 
								AND BB_NO = '".$bb_no."' ";

		$query .= " ORDER BY REG_DATE desc ";

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

	function insertBoardFile($db, $bb_code, $bb_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_adm, $reg_date) {
				
		$query = "INSERT INTO TBL_BOARD_FILE (BB_CODE, BB_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT, REG_ADM, REG_DATE) 
														values ('$bb_code','$bb_no', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', '0', '$reg_adm', '$reg_date'); ";
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

	function deleteBoardFile($db, $file_no) {
				
		$query = "DELETE FROM TBL_BOARD_FILE WHERE FILE_NO = '$file_no'";
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

	function selectBoardFile($db, $file_no) {

		$query = "SELECT FILE_NO, BB_CODE, BB_NO,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT,
										 DEL_TF, REG_ADM, REG_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD_FILE WHERE 1 = 1 
								 AND FILE_NO = '".$file_no."' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function getReplyCount($db, $bb_code, $bb_no) {

		$query = "SELECT COUNT(BB_NO) AS CNT FROM TBL_BOARD WHERE CATE_01 = '$bb_code' AND CATE_02 = '$bb_no' AND DEL_TF = 'N' ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function deleteAllBaord($db, $board_code) {

		$query = "DELETE FROM TBL_BOARD WHERE BB_CODE = '$board_code'";
		mysqli_query($db, $query);

		$query = "DELETE FROM TBL_BOARD WHERE CATE_01 = '$board_code'";
		mysqli_query($db, $query);

		$query = "DELETE FROM TBL_BOARD_FILE  WHERE BB_CODE = '$board_code'";
		mysqli_query($db, $query);

	}
?>