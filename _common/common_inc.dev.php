<?
	header('Content-Type: text/html; charset=utf-8');

#====================================================================
# DB Include, DB Connection
#====================================================================
	require $_SERVER['DOCUMENT_ROOT'] . "/_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# request parameter 
#=====================================================================

	# 이걸 왜 줘야하는지를 모르겠음...
	$_PAGE_NO = "1";
/*
	if ($b == "") {
		$b				= $_POST['b']!=''?$_POST['b']:$_GET['b'];
	}

	if ($m == "") {
		$m				= $_POST['m']!=''?$_POST['m']:$_GET['m'];
	}

	$l								= $_POST['l']!=''?$_POST['l']:$_GET['l'];
	$p								= $_POST['p']!=''?$_POST['p']:$_GET['p'];
	$bn								= $_POST['bn']!=''?$_POST['bn']:$_GET['bn'];
	$cn								= $_POST['cn']!=''?$_POST['cn']:$_GET['cn'];
	$f								= $_POST['f']!=''?$_POST['f']:$_GET['f'];
	$s								= $_POST['s']!=''?$_POST['s']:$_GET['s'];
	$nPage						= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];

	$con_cate_02			= $_POST['con_cate_02']!=''?$_POST['con_cate_02']:$_GET['con_cate_02'];
	$con_cate_03			= $_POST['con_cate_03']!=''?$_POST['con_cate_03']:$_GET['con_cate_03'];
	$con_cate_04			= $_POST['con_cate_04']!=''?$_POST['con_cate_04']:$_GET['con_cate_04'];
	$con_writer_id		= $_POST['con_writer_id']!=''?$_POST['con_writer_id']:$_GET['con_writer_id'];
	$keyword					= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];
	$ref_ip						= $_POST['ref_ip']!=''?$_POST['ref_ip']:$_GET['ref_ip'];
	$con_reply_state	= $_POST['con_reply_state']!=''?$_POST['con_reply_state']:$_GET['con_reply_state'];

	$strParam = "";
*/
	if ($_PAGE_NO != "") $p = $_PAGE_NO;
#	if ($_B_CODE != "") $b = $_B_CODE;
#	if ($_BOARD_MODE != "") $m = $_BOARD_MODE;

#=====================================================================
# common function
#=====================================================================
	require $_SERVER['DOCUMENT_ROOT'] ."/_common/config.php";
	require $_SERVER['DOCUMENT_ROOT'] ."/_classes/com/util/Util.php";
	require $_SERVER['DOCUMENT_ROOT'] ."/_classes/com/util/AES2.php";
	require $_SERVER['DOCUMENT_ROOT'] ."/_classes/com/etc/etc.php";
	require $_SERVER['DOCUMENT_ROOT'] ."/_classes/biz/page/page.php";
	require $_SERVER['DOCUMENT_ROOT'] ."/_classes/biz/config/config.php";

	$arr_config_info = selectConfig($conn, '1');
	
	$config_no					= trim($arr_config_info["C_NO"]); 
	$config_intro_tf		= trim($arr_config_info["C_INTRO_TF"]); 
	$config_notice_tf		= trim($arr_config_info["C_NOTICE_TF"]); 
	$config_info_01			= SetStringFromDB($arr_config_info["C_INFO_01"]);  // 입학상담 안내 문구
	$config_info_02			= SetStringFromDB($arr_config_info["C_INFO_02"]); 
	$config_info_03			= SetStringFromDB($arr_config_info["C_INFO_03"]); 
	$config_info_04			= SetStringFromDB($arr_config_info["C_INFO_04"]); 
	$config_info_05			= SetStringFromDB($arr_config_info["C_INFO_05"]); 

	$config_briefing_start					= SetStringFromDB($arr_config_info["BRIEFING_START"]); 
	$config_briefing_end						= SetStringFromDB($arr_config_info["BRIEFING_END"]); 
	$config_briefing_alert					= SetStringFromDB($arr_config_info["BRIEFING_ALERT"]); 
	$config_briefing_disabled_date	= SetStringFromDB($arr_config_info["BRIEFING_DISABLED_DATE"]); 
	$config_briefing_tf							= SetStringFromDB($arr_config_info["BRIEFING_TF"]); 
	$config_tour_start							= SetStringFromDB($arr_config_info["TOUR_START"]); 
	$config_tour_end								= SetStringFromDB($arr_config_info["TOUR_END"]); 
	$config_tour_alert							= SetStringFromDB($arr_config_info["TOUR_ALERT"]); 
	$config_tour_disabled_date			= SetStringFromDB($arr_config_info["TOUR_DISABLED_DATE"]); 
	$config_tour_tf									= SetStringFromDB($arr_config_info["TOUR_TF"]); 

	$this_date = date("Y-m-d",strtotime("0 day"));
	$next_date = date("Y-m-d",strtotime("1 day"));

	if (($this_date < $config_briefing_start) || ($this_date > $config_briefing_end)) $config_briefing_tf = "N";
	if (($this_date < $config_tour_start) || ($this_date > $config_tour_end)) $config_tour_tf = "N";

	if ($this_date > $config_briefing_start) $config_briefing_start = $next_date;
	if ($this_date > $config_tour_start) $config_tour_start = $next_date;


#=====================================================================
# common memu infomation
#=====================================================================
	require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_memu_info.php";
	
	// 게시물의 경우.
/*	if ($b) {

		$page_type = "board";
		require "../_classes/biz/board/board.php";
		require "../_common/board/config_info.php";
		
		// 게시판 관련
		if ($m == "") $m = "list";
		
		if ($m == "read") {
			
			$read_ip = $_SERVER['REMOTE_ADDR'];
			$result = viewChkBoardAsIp($conn,$b, $bn, $read_ip);
			$arr_rs_read = selectBoard($conn, $b, $bn);
			$arr_rs_files = listBoardFile($conn, $b, $bn);

			if ($arr_rs_read == null) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
	alert('삭제된 게시물 이거나 존재 하지 않는 게시물 입니다.');
	history.go(-1);
</script>
<?
				db_close($conn);
				exit;
			}

			$seo_keywords				= trim($arr_rs_read[0]["KEYWORD"]); 
			$rs_iso_reg_date		= trim($arr_rs_read[0]["REG_DATE"]); 
			$rs_iso_up_date			= trim($arr_rs_read[0]["UP_DATE"]); 
			$rs_iso_up_date			= date("c", strtotime($rs_iso_up_date));
			$rs_iso_reg_date		= date("c", strtotime($rs_iso_reg_date));
		
			// 최근 업데이트 ISO 날짜
			if ($rs_iso_up_date == "") $rs_iso_up_date = $rs_iso_reg_date; 
			
			// SEO 관련 정보 등록
			$seo_title					= SetStringFromDB($arr_rs_read[0]["TITLE"]); 
			$seo_description	= "";								//description

		}
	}

	// QNA 입니다.
	if (($_confirm_type ?? '') == "qna") {
		if (($m == "read") || ($m == "mod")) {

			$arr_rs_read = selectBoard($conn, $b, $bn);
			
			$rs_secret_tf				= trim($arr_rs_read[0]["SECRET_TF"]); 

			if ($rs_secret_tf == "Y") {

				// 비밀글 인데 만일 session 이 없을 경우  
				if ($_SESSION['s_board_bn'] <> encrypt($key, $iv, $bn)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
	alert('비밀글은 비밀번호 확인 후 보실 수 있습니다.');
	history.go(-1);
</script>
<?
					db_close($conn);
					exit;
				}

			}

			$subCategory = isset($arr_rs_read[0]["SUB_CATEGORY"]) ? $arr_rs_read[0]["SUB_CATEGORY"] : "";

			$seo_keywords				= trim($subCategory); 
			$rs_iso_reg_date		= trim($arr_rs_read[0]["REG_DATE"]); 
			$rs_iso_up_date			= trim($arr_rs_read[0]["UP_DATE"]); 
			$rs_iso_up_date			= date("c", strtotime($rs_iso_up_date));
			$rs_iso_reg_date		= date("c", strtotime($rs_iso_reg_date));
		
			// 최근 업데이트 ISO 날짜
			if ($rs_iso_up_date == "") $rs_iso_up_date = $rs_iso_reg_date; 
			
			// SEO 관련 정보 등록
			$seo_title					= SetStringFromDB($arr_rs_read[0]["TITLE"]); 
			$seo_description	= "";								//description

		}
	}
*/
	$seo_title					= removeSeoStr($seo_title);
	$seo_keywords				= removeSeoStr($seo_keywords);
	$seo_description		= removeSeoStr($seo_description);

#=====================================================================
# common head 
#=====================================================================
	#require "../_common/front_head.php"; 
	require $_SERVER['DOCUMENT_ROOT'] . "/_common/front_header.php"; 

?>