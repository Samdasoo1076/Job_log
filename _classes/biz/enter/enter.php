<?
	# =============================================================================
	# File Name    : enter.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2020-10-13
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	function listEnterInfo($db, $e_no, $e_type, $e_year, $e_title, $e_pdf, $e_img, $apply_tf, $use_tf, $del_tf, $f, $s, $nPage, $nRowCount) {

		$total_cnt = totalCntEnterInfo($db, $e_no, $apply_tf, $use_tf, $del_tf, $f, $s);

		$offset = $nRowCount*($nPage-1);

		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";

		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, E_NO, E_TYPE, E_YEAR, E_TITLE, E_PDF, E_IMG, E_PDF_NM, E_IMG_NM, DISP_SEQ,
						  APPLY_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_ENTERINFO WHERE 1 = 1 ";

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			if ($f == "ALL") {
				$query .= " AND E_TITLE like '%".$s."%' ";
			} else { 
				$query .= " AND ".$f." like '%".$s."%' ";
			}
		}

		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, E_NO DESC";  //limit ".$offset.", ".$nRowCount;

		//echo $query;
		//exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntEnterInfo($db, $e_no, $apply_tf, $use_tf, $del_tf, $f, $s){

		$query ="SELECT COUNT(*) CNT FROM TBL_ENTERINFO WHERE 1 = 1 ";

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			if ($f == "ALL") {
				$query .= " AND E_TITLE like '%".$s."%' ";
			} else { 
				$query .= " AND ".$f." like '%".$s."%' ";
			}
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function listEnterInfoApply($db, $ea_no, $ea_name, $ea_who, $ea_who_year, $ea_post, $ea_addr, $ea_addr_detail, $ea_phone, $ea_pwd, $agree_tf, $apply_tf, $use_tf, $del_tf, $f, $s, $nPage, $nRowCount, $total_cnt) {

		//echo $nRowCount."<br>";
		//echo $nPage."<br>";

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, EA_NO, EA_NAME, EA_WHO, EA_WHO_ETC, EA_WHO_YEAR, EA_SC_NM, EA_SC_CODE, EA_SC_AREA, EA_POST, EA_ADDR, EA_ADDR_DETAIL, EA_PHONE, EA_PWD, 
							EA_MEMO, AGREE_TF, APPLY_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, SEND_DATE
							FROM TBL_ENTERINFO_APPLY B WHERE 1 = 1 ";
/*
		$query = "SELECT @rwonum:= @rownum - 1  as rn, B.EAN_NO, A.REG_DATE, B.APPLY_TF,
										 (SELECT E_TITLE FROM TBL_ENTERINFO  WHERE E_NO = B.E_NO) AS E_TITLE, A.EA_NAME, B.EA_NUM,
										 A.EA_WHO, A.EA_WHO_YEAR, A.EA_POST, A.EA_ADDR, A.EA_ADDR_DETAIL, A.EA_PHONE, A.EA_MEMO
								FROM TBL_ENTERINFO_APPLY A, TBL_ENTERINFO_APPLY_NUM B  WHERE A.EA_NO = B.EA_NO ";
*/

		if ($apply_tf <> "") {
			$query .= " AND B.APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			$query .= " AND ".$f." like '%".$s."%' ";
		}
		
		$query .= " ORDER BY B.USE_TF DESC, B.EA_NO DESC limit ".$offset.", ".$nRowCount;

		//echo $query;
		//exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntEnterInfoApply($db, $ea_no, $apply_tf, $use_tf, $del_tf, $f, $s){

		$query ="SELECT COUNT(*) CNT FROM TBL_ENTERINFO_APPLY B WHERE 1 = 1 ";

		//$query = "SELECT COUNT(*) CNT FROM TBL_ENTERINFO_APPLY A, TBL_ENTERINFO_APPLY_NUM B  WHERE A.EA_NO = B.EA_NO ";

		if ($apply_tf <> "") {
			$query .= " AND B.APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			$query .= " AND ".$f." like '%".$s."%' ";
		}

		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function listEnterInfoApplyExcel($db, $ea_no, $ea_name, $ea_who, $ea_who_year, $ea_post, $ea_addr, $ea_addr_detail, $ea_phone, $ea_pwd, $agree_tf, $apply_tf, $use_tf, $del_tf, $f, $s) {

		$query = "SELECT @rownum:= @rownum - 1  as rn, EA_NO, EA_NAME, EA_WHO, EA_WHO_ETC, EA_WHO_YEAR, EA_POST, EA_SC_NM, EA_SC_CODE, EA_SC_AREA, EA_ADDR, EA_ADDR_DETAIL, EA_PHONE, EA_PWD, EA_MEMO, AGREE_TF,
						  APPLY_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, SEND_DATE
							FROM TBL_ENTERINFO_APPLY WHERE 1 = 1 ";


		/*
		$query = "SELECT @rwonum:= @rownum - 1  as rn, B.EAN_NO, A.REG_DATE, B.APPLY_TF,
										 (SELECT E_TITLE FROM TBL_ENTERINFO  WHERE E_NO = B.E_NO) AS E_TITLE, A.EA_NAME, B.EA_NUM,
										 A.EA_WHO, A.EA_WHO_YEAR, A.EA_POST, A.EA_ADDR, A.EA_ADDR_DETAIL, A.EA_PHONE, A.EA_MEMO
								FROM TBL_ENTERINFO_APPLY A, TBL_ENTERINFO_APPLY_NUM B  WHERE A.EA_NO = B.EA_NO ";
		*/

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}
		if ($s <> "") {
			$query .= " AND ".$f." like '%".$s."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, EA_NO DESC";  //limit ".$offset.", ".$nRowCount;

	//	echo $query;
	//	exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntEnterInfoApplyExcel($db, $ea_no, $apply_tf, $use_tf, $del_tf, $f, $s){
		
		$str = "";

		$query = "SELECT COUNT(*) CNT FROM TBL_ENTERINFO_APPLY  WHERE 1 = 1 ";

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			$query .= " AND ".$f." like '%".$s."%' ";
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function listEnterInfoApplyExcelChk($db, $chk, $ea_name, $ea_who, $ea_who_year, $ea_post, $ea_addr, $ea_addr_detail, $ea_phone, $ea_pwd, $agree_tf, $apply_tf, $use_tf, $del_tf, $f, $s) {

		$chk_str = "";

		if ($chk <> ""){
			$chk_str = implode(", ", $chk);
		} 
		$query = "SELECT @rownum:= @rownum - 1  as rn, EA_NO, EA_NAME, EA_WHO, EA_WHO_ETC, EA_WHO_YEAR, EA_POST, EA_SC_NM, EA_SC_CODE, EA_SC_AREA, EA_ADDR, EA_ADDR_DETAIL, EA_PHONE, EA_PWD, EA_MEMO, AGREE_TF, APPLY_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, SEND_DATE
							FROM TBL_ENTERINFO_APPLY WHERE 1 = 1 ";

		/*
		$query = "SELECT @rwonum:= @rownum - 1  as rn, B.EAN_NO, A.REG_DATE, B.APPLY_TF,
										 (SELECT E_TITLE FROM TBL_ENTERINFO  WHERE E_NO = B.E_NO) AS E_TITLE, A.EA_NAME, B.EA_NUM,
										 A.EA_WHO, A.EA_WHO_YEAR, A.EA_POST, A.EA_ADDR, A.EA_ADDR_DETAIL, A.EA_PHONE, A.EA_MEMO
								FROM TBL_ENTERINFO_APPLY A, TBL_ENTERINFO_APPLY_NUM B  WHERE A.EA_NO = B.EA_NO ";
		*/

		if ($chk <> "") {
			$query .= " AND EA_NO IN ($chk_str)";
		}

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}
		if ($s <> "") {
			$query .= " AND ".$f." like '%".$s."%' ";
		}
		
		$query .= " ORDER BY USE_TF DESC, EA_NO DESC";  //limit ".$offset.", ".$nRowCount;

	//	echo $query;
	//	exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntEnterInfoApplyExcelChk($db, $chk, $apply_tf, $use_tf, $del_tf, $f, $s){
		
		$str = "";
		$chk_str = "";

		if ($chk <> ""){
			$chk_str = implode(", ", $chk);
		} 

		$query = "SELECT COUNT(*) CNT FROM TBL_ENTERINFO_APPLY  WHERE 1 = 1 ";

		if ($chk <> "") {
			$query .= " AND EA_NO IN ($chk_str)";
		}

		if ($apply_tf <> "") {
			$query .= " AND APPLY_TF = '".$apply_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($s <> "") {
			$query .= " AND ".$f." like '%".$s."%' ";
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;

	}

	function listEnterInfoApplyNum($db, $ea_no, $use_tf, $del_tf) { 

		$query ="SELECT * FROM TBL_ENTERINFO_APPLY_NUM WHERE EA_NO='$ea_no' ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		$query .= " ORDER BY EAN_NO ASC, USE_TF DESC, DISP_SEQ asc, EA_NO DESC";  //limit ".$offset.", ".$nRowCount;


		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function insertEnterInfo($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_ENTERINFO (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_e_no = mysqli_insert_id($db);  //insert 후 e_no값을 알아오기 위한 구분
			return $new_e_no;
		}
	}

	function insertEnterInfoApply($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_ENTERINFO_APPLY (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		//echo $query;
		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			$new_ea_no = mysqli_insert_id($db);  //insert 후 e_no값을 알아오기 위한 구분
			return $new_ea_no;
		}
	}

	function insertEnterInfoApplyNum($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_ENTERINFO_APPLY_NUM (".$set_field." REG_DATE) 
					values (".$set_value." now()); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectEnterInfo($db, $e_no) {

		$query = "SELECT * FROM TBL_ENTERINFO WHERE E_NO='$e_no'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectEnterInfoApply($db, $ea_name, $ea_pwd, $ea_phone) {

		$query = "SELECT * FROM TBL_ENTERINFO_APPLY WHERE EA_NAME='$ea_name' AND EA_PWD='$ea_pwd' AND EA_PHONE='$ea_phone' AND DEL_TF = 'N'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectEnterInfoApplyAll($db, $ea_name, $ea_pwd, $ea_phone) {

		$query = "SELECT B.EAN_NO, A.REG_DATE, B.APPLY_TF,
										 (SELECT E_TITLE FROM TBL_ENTERINFO  WHERE E_NO = B.E_NO) AS E_TITLE, A.EA_NAME, B.EA_NUM,
										 A.EA_WHO, A.EA_WHO_YEAR, A.EA_POST, A.EA_ADDR, A.EA_ADDR_DETAIL,A.EA_PHONE
								FROM TBL_ENTERINFO_APPLY A, TBL_ENTERINFO_APPLY_NUM B
							 WHERE A.EA_NO = B.EA_NO
								 AND A.EA_NAME = '$ea_name'
								 AND A.EA_PWD = '$ea_pwd'
								 AND A.EA_PHONE = '$ea_phone'
								 AND B.DEL_TF = 'N'
								 AND A.DEL_TF = 'N'
							 ORDER BY B.EAN_NO DESC ";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function selectEnterInfoResult($db, $ea_no) {

		$query = "SELECT A.E_TITLE, B.EA_NAME, B.REG_DATE, C.APPLY_TF, C.EAN_NO 
		            FROM TBL_ENTERINFO A, TBL_ENTERINFO_APPLY B, TBL_ENTERINFO_APPLY_NUM C 
							 WHERE A.E_NO = C.E_NO 
							   AND B.EA_NO='$ea_no' 
								 AND C.EA_NO='$ea_no' 
								 AND C.DEL_TF = 'N'";

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateEnterInfo($db, $arr_data, $e_no) {

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ENTERINFO SET ".$set_query_str." ";
		$query .= "e_no = '$e_no' WHERE E_NO = '$e_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateEnterInfoUseTF($db, $use_tf, $up_adm, $e_no) {
		
		$query="UPDATE TBL_ENTERINFO SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE E_NO			= '$e_no' ";

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

	function updateEnterInfoDownTF($db, $apply_tf, $up_adm, $e_no) {
		
		$query="UPDATE TBL_ENTERINFO SET 
							APPLY_TF					= '$apply_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE E_NO			= '$e_no' ";

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


	function updateEnterInfoApplyProcessTF($db, $apply_tf, $up_adm, $ea_no) {
		
		$SEND_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

		if ($apply_tf == "Y") {
			$query="UPDATE TBL_ENTERINFO_APPLY SET 
								APPLY_TF					= '$apply_tf',
								SEND_DATE					= '$SEND_DATE',
								UP_ADM					= '$up_adm',
								UP_DATE					= now()
							 WHERE EA_NO			= '$ea_no' ";
		} else { 
			$query="UPDATE TBL_ENTERINFO_APPLY SET 
								APPLY_TF					= '$apply_tf',
								SEND_DATE					= '',
								UP_ADM					= '$up_adm',
								UP_DATE					= now()
							 WHERE EA_NO			= '$ea_no' ";
		}

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

	function updateEnterInfoApplyProcessTFAll($db, $apply_tf, $up_adm, $chk) {

		$apply_tf = "Y";

		$str = "";
		for ($i = 0 ; $i < sizeof($chk) ; $i++){
				$str .= $chk[$i]."," ;
		}
		if ($str <> ""){
			$str = substr($str, 0, strlen($str)-1);
		}

		$SEND_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

		if ($apply_tf == "Y") {

			$query="UPDATE TBL_ENTERINFO_APPLY
								 SET APPLY_TF		= '$apply_tf',
										 SEND_DATE	= '$SEND_DATE',
		 								 UP_ADM			= '$up_adm',
										 UP_DATE		= now()
							WHERE  EA_NO IN (".$str.")";

		} else { 

			$query="UPDATE TBL_ENTERINFO_APPLY
								 SET APPLY_TF = '$apply_tf',
										 SEND_DATE	= '',
			 							 UP_ADM		= '$up_adm',
										 UP_DATE	= now()
							WHERE  EA_NO IN (".$str.")";

		}

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

	function deleteEnterInfo($db, $adm_no, $e_no) {

		$query = "UPDATE TBL_ENTERINFO SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE E_NO = '$e_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	function deleteEnterInfoApply($db, $adm_no, $ea_no) {

		$query = "UPDATE TBL_ENTERINFO_APPLY SET DEL_TF = 'Y', DEL_DATE = now() WHERE EA_NO = '$ea_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
				return true;
		}
	}

	function deleteEnterInfoApplyNum($db, $adm_no, $ean_no) {

		$query = "UPDATE TBL_ENTERINFO_APPLY_NUM SET DEL_TF = 'Y', DEL_DATE = now() WHERE EAN_NO = '$ean_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {

				return true;
		}
	}

	function updateOrderEnter($db, $disp_seq_no, $e_no) {

		$query="UPDATE TBL_ENTERINFO SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE E_NO	= '$e_no' ";

		echo $query."<br>";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listApplyTfEnterInfo($db) {

		$query = "SELECT *
							FROM TBL_ENTERINFO 
							WHERE APPLY_TF = 'Y'
								AND DEL_TF = 'N' 
								AND USE_TF = 'Y' ";

		$query .= " ORDER BY E_NO DESC"; //limit ".$offset.", ".$nRowCount;

		//echo $query;
		//exit;

		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function mainlistEnterInfo($db, $cnt) {

		$query = "SELECT E_NO, E_TYPE, E_YEAR, E_TITLE, E_PDF, E_IMG, E_PDF_NM, E_IMG_NM, DISP_SEQ,
						  APPLY_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_ENTERINFO WHERE USE_TF = 'Y' AND DEL_TF = 'N' ";

		$query .= " ORDER BY DISP_SEQ asc, E_NO DESC limit ".$cnt;

		//echo $query;
		//exit;

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