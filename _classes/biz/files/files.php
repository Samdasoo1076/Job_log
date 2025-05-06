<?
	# =============================================================================
	# File Name    : files.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_FILES
	#=========================================================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function listFiles($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $nListCnt) {

		$query = "SELECT top ".$nRowCount." AA.FILE_NO, AA.FILE_NM, AA.PDF_FILE_NM, 
										 AA.PDF_FILE_RNM, AA.HWP_FILE_NM, AA.HWP_FILE_RNM, 
										 AA.USE_TF, AA.DEL_TF, AA.REG_ADM, AA.REG_DATE, AA.UP_ADM, AA.UP_DATE, AA.DEL_ADM, AA.DEL_DATE 
								FROM (SELECT top ".($nListCnt - ($nPage - 1) * $nRowCount)." FILE_NO, FILE_NM, PDF_FILE_NM, 
												PDF_FILE_RNM, HWP_FILE_NM, HWP_FILE_RNM, 
												USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								        FROM TBL_FILES AA WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY AA.FILE_NO desc ) AA ORDER BY AA.FILE_NO asc ";
		
		//echo $query;

		$result = sqlsrv_query($db, $query);
		$record = array();
		$record = sql_result_array($result);

		return $record;
	}


	function totalCntFiles ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_FILES WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$result = sqlsrv_query($db, $query);
		$rows   = sqlsrv_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertFiles($db, $file_nm, $pdf_file_nm, $pdf_file_rnm, $hwp_file_nm, $hwp_file_rnm, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_FILES (FILE_NM, PDF_FILE_NM, PDF_FILE_RNM, HWP_FILE_NM, HWP_FILE_RNM, 
												USE_TF, REG_ADM, REG_DATE) 
											 values ('$file_nm', '$pdf_file_nm', '$pdf_file_rnm', '$hwp_file_nm', '$hwp_file_rnm', '$use_tf', '$reg_adm', getDate()); ";
		
		//echo $query;

		if(!sqlsrv_query($db, $query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".sqlsrv_errors().":".sqlsrv_errors()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectFiles($db, $file_no) {

		$query = "SELECT FILE_NO, FILE_NM, PDF_FILE_NM, PDF_FILE_RNM, HWP_FILE_NM, HWP_FILE_RNM, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_FILES WHERE FILE_NO = '$file_no' ";
		
		$result = sqlsrv_query($db, $query);
		$record = array();
		$record = sql_result_array($result);

		return $record;
	}

	function updateFiles($db, $file_nm, $pdf_file_nm, $pdf_file_rnm, $hwp_file_nm, $hwp_file_rnm, $use_tf, $up_adm, $file_no) {
		
		$query="UPDATE TBL_FILES SET 
							FILE_NM					= '$file_nm', 
							PDF_FILE_NM			= '$pdf_file_nm', 
							PDF_FILE_RNM		= '$pdf_file_rnm', 
							HWP_FILE_NM			= '$hwp_file_nm', 
							HWP_FILE_RNM		= '$hwp_file_rnm', 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= getDate()
				 WHERE FILE_NO				= '$file_no' ";

		//echo $query;
		if(!sqlsrv_query($db, $query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".sqlsrv_errors().":".sqlsrv_errors()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>