<?

	// 삭제 권한 관련 입니다.
	$del_ok = "N";

	if ($_SESSION['s_adm_no']) {
		if ($sPageRight_D == "Y") {
			$del_ok = "Y";
		}
	} else {
		if ($writer_id == $rs_writer_id) {
			$del_ok = "Y";
		}
	}
		
	if ($del_ok == "Y") {
		$result = deleteBoard($conn, $s_adm_no, $b_code, $b_no);
	} else {
		alert("삭제 권한이 없습니다.");
	}

?>