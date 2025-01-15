<?

	# =============================================================================
	# File Name    : board.php
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
	WRITER_ID						varchar(30)	NOT	NULL default ''						COMMENT	'작성자ID',
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
	MAIN_TF							char(1)	NOT	NULL default 'Y'							COMMENT	'사용	여부 사용(Y),사용안함(N)',
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

	function listBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS,
							 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF,
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
					FROM TBL_BOARD_COMMENT WHERE 1 = 1 ";

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

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
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
		
		$query .= " ORDER BY -BB_PO ASC limit ".$offset.", ".$nRowCount;

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

	function totalCntBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04,  $writer_id, $keyword, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_COMMENT WHERE 1 = 1 ";
		
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

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
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


	function insertBoardComment($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $phone, $homepage, $title, $hit_cnt, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $main_tf, $top_tf, $use_tf, $reg_adm, $reg_date) {
		
		$query ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO FROM TBL_BOARD_COMMENT WHERE BB_CODE = '$bb_code' ";
		
		// 글 이동 떄문에 변경
		//$query ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO FROM TBL_BOARD ";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		//echo $query;
		//echo $rows[0];

		if ($rows[0] <> 0) {

			$new_bb_no = $rows[0] + 1;

			//답변글 번호 찾기
			$query2 ="SELECT IFNULL(MAX(BB_RE),0) AS MAX_NO FROM TBL_BOARD_COMMENT WHERE BB_CODE = '$bb_code' ";
			//$query2 ="SELECT IFNULL(MAX(BB_RE),0) AS MAX_NO FROM TBL_BOARD ";
			$result2 = mysqli_query($db,$query2);
			$rows2   = mysqli_fetch_array($result2);

			$new_bb_re = $rows2[0] + 1;

			//po 최소값 찾기
			$query3 ="SELECT IFNULL(MIN(BB_PO),0) AS MAX_NO FROM TBL_BOARD_COMMENT WHERE BB_CODE = '$bb_code' ";
			//$query3 ="SELECT IFNULL(MIN(BB_PO),0) AS MAX_NO FROM TBL_BOARD  ";
			$result3 = mysqli_query($db,$query3);
			$rows3   = mysqli_fetch_array($result3);

			$new_bb_po = $rows3[0] - 1;

			$query4 ="UPDATE TBL_BOARD_COMMENT SET BB_PO = BB_PO + 1 WHERE BB_CODE = '$bb_code' AND BB_PO > 0 ";
			//$query4 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 WHERE BB_PO > 0 ";

			mysqli_query($db,$query4);
		
		} else {
		
			$new_bb_no = "1";
			$new_bb_po = "-1";
			$new_bb_re = "1";
			$new_bb_de = "1";

		}

		$query5="INSERT INTO TBL_BOARD_COMMENT (BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, 
							 CONTENTS, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, COMMENT_TF, MAIN_TF, TOP_TF, USE_TF, REG_ADM, REG_DATE) 
				values ('$bb_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '$new_bb_po', '$new_bb_re', '1', '$writer_id', '$writer_nm', '$writer_pw', 
								'$email', '$phone', '$homepage', '$title', '$hit_cnt', '$ref_ip', '$recomm', '$contents', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', 
								'$keyword', '$comment_tf', '$main_tf', '$top_tf', '$use_tf', '$reg_adm', '$reg_date'); ";

		#echo $query5;
		
		#exit;
		if(!mysqli_query($db,$query5)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_bb_no;
		}
	}

	function insertBoardCommentReply($db, $bb_code, $bb_no, $bb_po, $bb_re, $bb_de, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $homepage, $title, $hit_cnt, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $reg_adm, $reg_date) {
		
		$query = "SELECT BB_RE, BB_DE, BB_PO FROM TBL_BOARD_COMMENT WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
		

		//echo "대댓글 ----- ".$query."<br>";


		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);
		
		$bb_re = $row[0];
		$bb_de = $row[1];
		$bb_po = $row[2];

		$new_bb_de = $bb_de + 1;

		$query = "SELECT COUNT(BB_NO) AS CNT 
								FROM TBL_BOARD_COMMENT 
							 WHERE BB_CODE = '$bb_code' 
								 AND BB_RE = '$bb_re' 
								 AND BB_DE > '$bb_de' 
								 AND BB_PO < '$bb_po'";

		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);

		$plus_po = $row[0];

		$new_bb_po = $bb_po - $plus_po - 1;
		
		$query1 ="UPDATE TBL_BOARD_COMMENT SET BB_PO = BB_PO - 1 
							 WHERE BB_CODE = '$bb_code' 
								 AND BB_PO <= '$new_bb_po' ";

		mysqli_query($db, $query1);
		
		
		$query2 ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO 
								FROM TBL_BOARD_COMMENT 
							 WHERE BB_CODE = '$bb_code' ";

		$result2 = mysqli_query($db,$query2);
		$rows2   = mysqli_fetch_array($result2);

		$new_bb_no = $rows2[0] + 1;

		//echo "등록 하면서 ------ ".$new_bb_de."<br>";
				
		$query5="INSERT INTO TBL_BOARD_COMMENT (BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_ID, WRITER_NM, 
										WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, 
							 CONTENTS, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, COMMENT_TF, TOP_TF, USE_TF, REG_ADM, REG_DATE) 
				values ('$bb_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '$new_bb_po', '$bb_re', '$new_bb_de', 
								'$writer_id', '$writer_nm', '$writer_pw', 
								'$email', '$homepage', '$title', '$hit_cnt', '$ref_ip', '$recomm', '$contents', 
								'$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', 
								'$keyword', '$comment_tf', '$top_tf,', '$use_tf', '$reg_adm', '$reg_date'); ";
		
		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_bb_no;
		}
	}


	function selectBoardComment($db, $bb_code, $bb_no) {

		$query = "SELECT BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, PHONE,
							 HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, CONTENTS, 
							 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 COMMENT_TF, MAIN_TF, TOP_TF,
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, REF_IP
					FROM TBL_BOARD_COMMENT WHERE  BB_CODE = '$bb_code' AND  BB_NO = '$bb_no' ";
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

	function updateBoardComment($db, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $up_adm, $bb_code, $bb_no) {

		$query = "UPDATE TBL_BOARD_COMMENT SET 
						CATE_01				=	'$cate_01',
						CATE_02				=	'$cate_02',
						CATE_03				=	'$cate_03',
						CATE_04				=	'$cate_04',
						WRITER_NM			=	'$writer_nm',
						WRITER_PW			=	'$writer_pw',
						EMAIL				=	'$email',
						HOMEPAGE			=	'$homepage',
						TITLE				=	'$title',
						REF_IP				=	'$ref_ip',
						CONTENTS			=	'$contents',
						FILE_NM				=	'$file_nm',
						FILE_RNM			=	'$file_rnm',
						FILE_PATH			=	'$file_path',
						FILE_SIZE			=	'$file_size',
						FILE_EXT			=	'$file_ext',
						KEYWORD				=	'$keyword',
						COMMENT_TF			=	'$comment_tf',
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

	/*
	===============================================================================================================================
	추천수 관련
	===============================================================================================================================
	*/
	

	function updateBoardRecommComment($db, $gubun, $writer_id, $bb_code, $bb_no) {
		if($gubun=="RECOMM"){
			$query="UPDATE TBL_BOARD_COMMENT SET RECOMM					= RECOMM + 1
					 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
		}else if($gubun=="RECOMMNO"){
			$query="UPDATE TBL_BOARD_COMMENT SET RECOMMNO					= RECOMMNO + 1
					 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";
		}		
		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {			
			$query_rec="INSERT INTO TBL_BOARD_RECOMM (BB_CODE, BB_NO, RECOMM_IDS, REG_DATE) values ('$bb_code', '$bb_no', '$writer_id',  now()); ";			
			@mysqli_query($db,$query_rec);

			return true;
		}
	}


	function deleteBoardComment($db, $del_adm, $bb_code, $bb_no) {

		$query =  "SELECT BB_DE, BB_RE, BB_PO FROM TBL_BOARD_COMMENT 
							  WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND BB_CODE		= '$bb_code' 
									AND BB_NO			= '$bb_no' ";
		
		$result = mysqli_query($db, $query);
		$list = mysqli_fetch_array($result);
		
		$sde = $list[BB_DE];
		$sre = $list[BB_RE];
		$spo = $list[BB_PO];

		$query =	"SELECT BB_DE FROM TBL_BOARD_COMMENT 
								WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND BB_CODE = '$bb_code' 
									AND BB_RE = '$sre' 
									AND BB_PO < '$spo' 
								ORDER BY BB_PO DESC limit 1";

		$result = mysqli_query($db, $query);
		$list		= mysqli_fetch_array($result);
		$chk_sde = $list[BB_DE];

		if ($sde < $chk_sde) { 
			
			$query = "UPDATE TBL_BOARD_COMMENT SET 
									TITLE = '작성자 또는 관리자에 의해 삭제 되었습니다.', 
									CONTENTS = '답변글이 남아 있어 내용만 삭제 되었습니다.'
						WHERE BB_CODE				= '$bb_code' 
							AND BB_NO					= '$bb_no' ";
		} else {

			$query = "UPDATE TBL_BOARD_COMMENT SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
						WHERE BB_CODE		= '$bb_code' 
							AND BB_NO			= '$bb_no' ";
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



	function countRecomComment($db, $bb_code,$bb_no){
		$query = "SELECT count(distinct WRITER_ID) FROM TBL_RECOM WHERE BB_CODE = '$bb_code' and BB_NO='$bb_no' and RECOM_TF='Y'";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	

	function countNRecomComment($db, $bb_code,$bb_no){
		
		$query = "SELECT count(distinct WRITER_ID) FROM TBL_RECOM WHERE BB_CODE = '$bb_code' and BB_NO='$bb_no' and RECOM_TF='N'";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}



	function selectRecomComment($db, $bb_code, $bb_no, $write_id) {

		$query = "SELECT count(seq_no) FROM TBL_RECOM WHERE BB_CODE = '$bb_code' AND  BB_NO = '$bb_no' and WRITER_ID='$write_id'";
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	

	function insertRecomComment($db, $bb_code, $bb_no, $writer_id, $re_tf) {
		
		$ref_ip = $_SERVER['REMOTE_ADDR'];

		$query5="INSERT INTO TBL_RECOM (BB_CODE, BB_NO, WRITER_ID, RECOM_TF, REG_DATE, REF_IP) 
														values ('$bb_code', '$bb_no', '$writer_id', '$re_tf', now(), '$ref_ip'); ";
		
		if(!mysqli_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return "Y";
		}

	}

?>