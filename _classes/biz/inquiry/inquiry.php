<?

	# =============================================================================
	# File Name    : admin.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_ADMIN
	#=========================================================================================================

	/*
	CREATE TABLE IF NOT EXISTS `TBL_INQUIRY` (
  `SEQ_NO` int(11) unsigned NOT NULL auto_increment COMMENT '접수번호',
  `ORDER_NO` int(11) NOT NULL default '1' COMMENT '문의 구분',
  `CATE_CODE` varchar(50) NOT NULL default '' COMMENT '문의 구분',
  `TITLE` varchar(255) NOT NULL COMMENT '제목',
  `ASK_CODE` varchar(50) default NULL COMMENT '문의 구분',
  `COM_NAME` varchar(50) default NULL COMMENT '회사명',
  `IN_NAME` varchar(50) default NULL COMMENT '신청인',
  `AREA` varchar(10) default NULL COMMENT '지역',
  `PHONE` varchar(20) default NULL COMMENT '연락처',
  `HPHONE` varchar(20) default NULL COMMENT '연락처',
  `EMAIL` varchar(100) default NULL COMMENT '작성자	이메일',
  `ZIP_CODE` varchar(7) default NULL COMMENT '우편번호',
  `ADDR1` varchar(150) default NULL COMMENT '주소',
  `ADDR2` varchar(150) default NULL COMMENT '주소',
  `CONTENTS` longtext COMMENT '내용',
  `REPLY` text COMMENT '답변',
  `REPLY_ADM` int(11) unsigned default NULL COMMENT '답변	관리자 TBL_ADMIN ADM_NO',
  `REPLY_DATE` datetime default NULL COMMENT '답변일',
  `REPLY_STATE` char(1) default 'N' COMMENT '답변	상태',
  `USE_TF` char(1) NOT NULL default 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  `DEL_TF` char(1) NOT NULL default 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned default NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  `REG_DATE` datetime default NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned default NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  `UP_DATE` datetime default NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned default NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  `DEL_DATE` datetime default NULL COMMENT '삭제일',
  PRIMARY KEY  (`SEQ_NO`,`CATE_CODE`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function listInquiry($db, $lang, $cate_code, $ask_code, $start_date, $end_date, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, ORDER_NO, CATE_CODE, LANG, TITLE, ASK_CODE, COM_NAME, IN_NAME, AREA, 
										 PHONE, HPHONE, EMAIL, ZIP_CODE, ADDR1, ADDR2, CONTENTS, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_INQUIRY A WHERE 1 = 1 ";
		

		if ($lang <> "") {
			$query .= " AND LANG = '".$lang."' ";
		}

		if ($cate_code <> "") {
			$query .= " AND CATE_CODE = '".$cate_code."' ";
		}

		if ($ask_code <> "") {
			$query .= " AND ASK_CODE = '".$ask_code."' ";
		}

		if ($start_date <> "") {
			$query .= " AND REG_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND REG_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (TITLE like '%".$search_str."%' 
											OR IN_NAME like '%".$search_str."%' ) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") 
			$order_field = "REG_DATE";

		if ($order_str == "") 
			$order_str = "DESC";

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		#echo $query;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntInquiry ($db, $lang, $cate_code, $ask_code, $start_date, $end_date, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_INQUIRY WHERE 1 = 1 ";
		
		if ($lang <> "") {
			$query .= " AND LANG = '".$lang."' ";
		}

		if ($cate_code <> "") {
			$query .= " AND CATE_CODE = '".$cate_code."' ";
		}

		if ($ask_code <> "") {
			$query .= " AND ASK_CODE = '".$ask_code."' ";
		}

		if ($start_date <> "") {
			$query .= " AND REG_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND REG_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (TITLE like '%".$search_str."%' 
											OR IN_NAME like '%".$search_str."%' ) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertInquiry($db, $order_no, $cate_code, $lang, $title, $ask_code, $com_name, $in_name, $area, $phone, $hphone, $email, $zip_code, $addr1, $addr2, $contents, $reg_adm) {
		
		$query="INSERT INTO TBL_INQUIRY (ORDER_NO, CATE_CODE, LANG, TITLE, ASK_CODE, COM_NAME, IN_NAME, AREA, 
										 PHONE, HPHONE, EMAIL, ZIP_CODE, ADDR1, ADDR2, CONTENTS, USE_TF, REG_ADM, REG_DATE) 
											 values ('$order_no', '$cate_code', '$lang', '$title', '$ask_code', '$com_name', '$in_name', '$area', 
															 '$phone', '$hphone', '$email', '$zip_code', '$addr1', '$addr2', '$contents', 'Y', '$reg_adm', now()); ";
		
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateInquiry($db, $reply, $reply_state, $up_adm, $seq_no) {

		$query="UPDATE TBL_INQUIRY SET 
									 REPLY				= '$reply', 
									 REPLY_ADM		= '$up_adm', 
									 REPLY_DATE		= now(), 
									 REPLY_STATE	= '$reply_state', 
									 UP_ADM				= '$up_adm',
									 UP_DATE			= now()
						 WHERE SEQ_NO	= '$seq_no' ";

		#echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateStateInquiry($db, $adm_no, $seq_no) {
		$query="UPDATE TBL_INQUIRY SET 
											 REPLY_STATE	= 'Y',
											 REPLY_ADM		= '$adm_no',
											 REPLY_DATE		= now()
								 WHERE SEQ_NO	= '$seq_no' ";
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteInquiry($db, $del_adm, $seq_no) {

		$query="UPDATE TBL_INQUIRY SET 
											 DEL_TF				= 'Y',
											 DEL_ADM			= '$del_adm',
											 DEL_DATE			= now()
								 WHERE SEQ_NO	= '$seq_no' ";
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateInquiryReply($db, $seq_no, $reply, $reply_state, $adm_no) {

		$query="UPDATE TBL_INQUIRY SET 
											 REPLY				= '$reply',
											 REPLY_STATE	= '$reply_state',
											 REPLY_ADM		= '$adm_no',
											 REPLY_DATE		= now()
								 WHERE SEQ_NO	= '$seq_no' ";
		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function selectInquiry($db, $seq_no) {

		$query = "SELECT SEQ_NO, ORDER_NO, CATE_CODE, LANG, TITLE, ASK_CODE, COM_NAME, IN_NAME, AREA, 
										 PHONE, HPHONE, EMAIL, ZIP_CODE, ADDR1, ADDR2, CONTENTS, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_INQUIRY WHERE SEQ_NO	= '$seq_no' ";
		
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