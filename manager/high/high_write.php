<?session_start();?>
<?
# =============================================================================
# File Name    : high_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2022-08-25
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "HI001"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/high/high.php";

#====================================================================
# DML Process
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$h_no							= isset($_POST["h_no"]) && $_POST["h_no"] !== '' ? $_POST["h_no"] : (isset($_GET["h_no"]) ? $_GET["h_no"] : '');
	$h_who						= isset($_POST["h_who"]) && $_POST["h_who"] !== '' ? $_POST["h_who"] : (isset($_GET["h_who"]) ? $_GET["h_who"] : '');
	$h_nm							= isset($_POST["h_nm"]) && $_POST["h_nm"] !== '' ? $_POST["h_nm"] : (isset($_GET["h_nm"]) ? $_GET["h_nm"] : '');
	$h_title					= isset($_POST["h_title"]) && $_POST["h_title"] !== '' ? $_POST["h_title"] : (isset($_GET["h_title"]) ? $_GET["h_title"] : '');
	$h_numbers				= isset($_POST["h_numbers"]) && $_POST["h_numbers"] !== '' ? $_POST["h_numbers"] : (isset($_GET["h_numbers"]) ? $_GET["h_numbers"] : '');
	$h_venue					= isset($_POST["h_venue"]) && $_POST["h_venue"] !== '' ? $_POST["h_venue"] : (isset($_GET["h_venue"]) ? $_GET["h_venue"] : '');

	$h_type						= isset($_POST["h_type"]) && $_POST["h_type"] !== '' ? $_POST["h_type"] : (isset($_GET["h_type"]) ? $_GET["h_type"] : '');
	$h_program				= isset($_POST["h_program"]) && $_POST["h_program"] !== '' ? $_POST["h_program"] : (isset($_GET["h_program"]) ? $_GET["h_program"] : '');
	$h_target					= isset($_POST["h_target"]) && $_POST["h_target"] !== '' ? $_POST["h_target"] : (isset($_GET["h_target"]) ? $_GET["h_target"] : '');
	$h_period					= isset($_POST["h_period"]) && $_POST["h_period"] !== '' ? $_POST["h_period"] : (isset($_GET["h_period"]) ? $_GET["h_period"] : '');
	$h_agenda					= isset($_POST["h_agenda"]) && $_POST["h_agenda"] !== '' ? $_POST["h_agenda"] : (isset($_GET["h_agenda"]) ? $_GET["h_agenda"] : '');
	$h_agenda					= isset($_POST["h_agenda"]) && $_POST["h_agenda"] !== '' ? $_POST["h_agenda"] : (isset($_GET["h_agenda"]) ? $_GET["h_agenda"] : '');
	$h_numbers_info		= isset($_POST["h_numbers_info"]) && $_POST["h_numbers_info"] !== '' ? $_POST["h_numbers_info"] : (isset($_GET["h_numbers_info"]) ? $_GET["h_numbers_info"] : '');

	$apply_s_date			= isset($_POST["apply_s_date"]) && $_POST["apply_s_date"] !== '' ? $_POST["apply_s_date"] : (isset($_GET["apply_s_date"]) ? $_GET["apply_s_date"] : '');
	$apply_s_hour			= isset($_POST["apply_s_hour"]) && $_POST["apply_s_hour"] !== '' ? $_POST["apply_s_hour"] : (isset($_GET["apply_s_hour"]) ? $_GET["apply_s_hour"] : '');
	$apply_s_min			= isset($_POST["apply_s_min"]) && $_POST["apply_s_min"] !== '' ? $_POST["apply_s_min"] : (isset($_GET["apply_s_min"]) ? $_GET["apply_s_min"] : '');
	$apply_e_date			= isset($_POST["apply_e_date"]) && $_POST["apply_e_date"] !== '' ? $_POST["apply_e_date"] : (isset($_GET["apply_e_date"]) ? $_GET["apply_e_date"] : '');
	$apply_e_hour			= isset($_POST["apply_e_hour"]) && $_POST["apply_e_hour"] !== '' ? $_POST["apply_e_hour"] : (isset($_GET["apply_e_hour"]) ? $_GET["apply_e_hour"] : '');
	$apply_e_min			= isset($_POST["apply_e_min"]) && $_POST["apply_e_min"] !== '' ? $_POST["apply_e_min"] : (isset($_GET["apply_e_min"]) ? $_GET["apply_e_min"] : '');

	$event_s_date			= isset($_POST["event_s_date"]) && $_POST["event_s_date"] !== '' ? $_POST["event_s_date"] : (isset($_GET["event_s_date"]) ? $_GET["event_s_date"] : '');
	$event_s_hour			= isset($_POST["event_s_hour"]) && $_POST["event_s_hour"] !== '' ? $_POST["event_s_hour"] : (isset($_GET["event_s_hour"]) ? $_GET["event_s_hour"] : '');
	$event_s_min			= isset($_POST["event_s_min"]) && $_POST["event_s_min"] !== '' ? $_POST["event_s_min"] : (isset($_GET["event_s_min"]) ? $_GET["event_s_min"] : '');
	$event_e_date			= isset($_POST["event_e_date"]) && $_POST["event_e_date"] !== '' ? $_POST["event_e_date"] : (isset($_GET["event_e_date"]) ? $_GET["event_e_date"] : '');
	$event_e_hour			= isset($_POST["event_e_hour"]) && $_POST["event_e_hour"] !== '' ? $_POST["event_e_hour"] : (isset($_GET["event_e_hour"]) ? $_GET["event_e_hour"] : '');
	$event_e_min			= isset($_POST["event_e_min"]) && $_POST["event_e_min"] !== '' ? $_POST["event_e_min"] : (isset($_GET["event_e_min"]) ? $_GET["event_e_min"] : '');

	$h_contents				= isset($_POST["h_contents"]) && $_POST["h_contents"] !== '' ? $_POST["h_contents"] : (isset($_GET["h_contents"]) ? $_GET["h_contents"] : '');
	$h_date						= isset($_POST["h_date"]) && $_POST["h_date"] !== '' ? $_POST["h_date"] : (isset($_GET["h_date"]) ? $_GET["h_date"] : '');
	$apply_link				= isset($_POST["apply_link"]) && $_POST["apply_link"] !== '' ? $_POST["apply_link"] : (isset($_GET["apply_link"]) ? $_GET["apply_link"] : '');
	$confirm_link			= isset($_POST["confirm_link"]) && $_POST["confirm_link"] !== '' ? $_POST["confirm_link"] : (isset($_GET["confirm_link"]) ? $_GET["confirm_link"] : '');

	$flag01						= isset($_POST["flag01"]) && $_POST["flag01"] !== '' ? $_POST["flag01"] : (isset($_GET["flag01"]) ? $_GET["flag01"] : '');
	$h_file						= isset($_POST["h_file"]) && $_POST["h_file"] !== '' ? $_POST["h_file"] : (isset($_GET["h_file"]) ? $_GET["h_file"] : '');
	$h_file_nm				= isset($_POST["h_file_nm"]) && $_POST["h_file_nm"] !== '' ? $_POST["h_file_nm"] : (isset($_GET["h_file_nm"]) ? $_GET["h_file_nm"] : '');

	$apply_tf					= isset($_POST["apply_tf"]) && $_POST["apply_tf"] !== '' ? $_POST["apply_tf"] : (isset($_GET["apply_tf"]) ? $_GET["apply_tf"] : '');

	$apply_use_tf			= isset($_POST["apply_use_tf"]) && $_POST["apply_use_tf"] !== '' ? $_POST["apply_use_tf"] : (isset($_GET["apply_use_tf"]) ? $_GET["apply_use_tf"] : '');
	$event_use_tf			= isset($_POST["event_use_tf"]) && $_POST["event_use_tf"] !== '' ? $_POST["event_use_tf"] : (isset($_GET["event_use_tf"]) ? $_GET["event_use_tf"] : '');
	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');

	$ref_tf						= isset($_POST["ref_tf"]) && $_POST["ref_tf"] !== '' ? $_POST["ref_tf"] : (isset($_GET["ref_tf"]) ? $_GET["ref_tf"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');


#====================================================================
 $savedir1 = $g_physical_path."upload_data/high";
#====================================================================

	$result = false;

	$mode							= SetStringToDB($mode);
	$h_who						= SetStringToDB($h_who);
	$h_nm							= SetStringToDB($h_nm);
	$h_title					= SetStringToDB($h_title);
	$h_venue					= SetStringToDB($h_venue);
	$h_date						= SetStringToDB($h_date);
	$h_contents				= SetStringToDB($h_contents);
	
	$search_field			= SetStringToDB($search_field);
	$search_str				=	SetStringToDB($search_str);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	//$max_allow_file_size = $allow_file_size * 1024 * 1024;

	if ($mode == "I") {
		
		if ($_FILES["h_file"]["name"] <> ""){
			$h_file					= upload($_FILES["h_file"], $savedir1, 100 , array('jpg','gif','png'));
			$h_file_nm				= $_FILES["h_file"]["name"];
		}
		
		$arr_data = array("H_WHO"=>$h_who,
											"H_TYPE"=>$h_type,
											"H_PROGRAM"=>$h_program,
											"H_NM"=>$h_nm,
											"H_TITLE"=>$h_title,
											"H_TARGET"=>$h_target,
											"H_PERIOD"=>$h_period,
											"H_AGENDA"=>$h_agenda,
											"H_NUMBERS"=>$h_numbers,
											"H_VENUE"=>$h_venue,
											"APPLY_S_DATE"=>$apply_s_date,
											"APPLY_S_HOUR"=>$apply_s_hour,
											"APPLY_S_MIN"=>$apply_s_min,
											"APPLY_E_DATE"=>$apply_e_date,
											"APPLY_E_HOUR"=>$apply_e_hour,
											"APPLY_E_MIN"=>$apply_e_min,
											"EVENT_S_DATE"=>$event_s_date,
											"EVENT_S_HOUR"=>$event_s_hour,
											"EVENT_S_MIN"=>$event_s_min,
											"EVENT_E_DATE"=>$event_e_date,
											"EVENT_E_HOUR"=>$event_e_hour,
											"EVENT_E_MIN"=>$event_e_min,
											"H_CONTENTS"=>$h_contents,
											"H_DATE"=>$REG_INSERT_DATE,
											"APPLY_LINK"=>$apply_link,
											"CONFIRM_LINK"=>$confirm_link,
											"H_FILE"=>$h_file,
											"H_FILE_NM"=>$h_file_nm,
											"APPLY_USE_TF"=>$apply_use_tf,
											"EVENT_USE_TF"=>$event_use_tf,
											"HIT_CNT"=>0,
											"DISP_SEQ"=>0,
											"APPLY_TF"=>$apply_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$new_h_no	= insertHigh($conn, $arr_data);
		$result =$new_h_no;


		$result_log	= insertUserLog($conn, 'admin', $_SESSION['g_adm_id'], $_SERVER['REMOTE_ADDR'], '고교연계 페이지 등록 (제목 : '.$h_title.') ', 'Insert');
    
	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$h_file			= upload($_FILES["h_file"], $savedir1, 100 , array('jpg','gif','png'));
				$h_file_nm		= $_FILES["h_file"]["name"];
			break;
			case "keep" :
				$h_file			= $h_file;
				$h_file_nm	= $h_file_nm;
			break;
			case "delete" :
				$h_file			= "";
				$h_file_nm		= "";
			break;
			case "update" :
				$h_file			= upload($_FILES["h_file"], $savedir1, 100 , array('jpg','gif','png'));
				$h_file_nm		= $_FILES["h_file"]["name"];
			break;
		}

		$arr_data = array("H_WHO"=>$h_who,
											"H_TYPE"=>$h_type,
											"H_PROGRAM"=>$h_program,
											"H_NM"=>$h_nm,
											"H_TITLE"=>$h_title,
											"H_TARGET"=>$h_target,
											"H_PERIOD"=>$h_period,
											"H_AGENDA"=>$h_agenda,
											"H_NUMBERS"=>$h_numbers,
											"H_VENUE"=>$h_venue,
											"APPLY_S_DATE"=>$apply_s_date,
											"APPLY_S_HOUR"=>$apply_s_hour,
											"APPLY_S_MIN"=>$apply_s_min,
											"APPLY_E_DATE"=>$apply_e_date,
											"APPLY_E_HOUR"=>$apply_e_hour,
											"APPLY_E_MIN"=>$apply_e_min,
											"EVENT_S_DATE"=>$event_s_date,
											"EVENT_S_HOUR"=>$event_s_hour,
											"EVENT_S_MIN"=>$event_s_min,
											"EVENT_E_DATE"=>$event_e_date,
											"EVENT_E_HOUR"=>$event_e_hour,
											"EVENT_E_MIN"=>$event_e_min,
											"H_CONTENTS"=>$h_contents,
											"H_DATE"=>$REG_INSERT_DATE,
											"APPLY_LINK"=>$apply_link,
											"CONFIRM_LINK"=>$confirm_link,
											"H_FILE"=>$h_file,
											"H_FILE_NM"=>$h_file_nm,
											"APPLY_USE_TF"=>$apply_use_tf,
											"EVENT_USE_TF"=>$event_use_tf,
											"DISP_SEQ"=>0,
											"APPLY_TF"=>$apply_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateHigh($conn, $arr_data, $h_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '고교연계 페이지 수정 (제목 : '.$h_title.') ', 'Update');
	}

	if ($mode == "D") {
		$result = deleteHigh($conn, $_SESSION['s_adm_no'], (int)$h_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '고교연계 페이지 삭제 처리 (제목 : '.$h_title.') ', 'Delete');
	}

	$rs_h_no								= ""; 
	$rs_h_who								= "";
	$rs_h_type							= "";
	$rs_h_program						= "";
	$rs_h_nm								= "";
	$rs_h_title							= "";
	$rs_h_numbers						= "";
	$rs_h_numbers_info			= "";
	$rs_h_target						= "";
	$rs_h_period						= "";
	$rs_h_agenda						= "";
	$rs_h_venue							= "";

	$rs_apply_s_date				= "";
	$rs_apply_s_hour				= "";
	$rs_apply_s_min					= "";
	$rs_apply_e_date				= "";
	$rs_apply_e_hour				= "";
	$rs_apply_e_min					= "";
	$rs_event_s_date				= "";
	$rs_event_s_hour				= "";
	$rs_event_s_min					= "";
	$rs_event_e_date				= "";
	$rs_event_e_hour				= "";
	$rs_event_e_min					= "";
	$rs_h_contents					= "";

	$rs_apply_link					= "";
	$rs_confirm_link				= "";
	$rs_h_file							= "";
	$rs_h_file_nm						= "";
	$rs_h_date							= "";
	$rs_apply_use_tf				= "";
	$rs_event_use_tf				= "";
	$rs_hit_cnt							= "";
	$rs_apply_tf						= "";
	$rs_use_tf							= "";
	$rs_del_tf							= "";
	$rs_reg_adm							= "";
	$rs_reg_date						= "";
	$rs_up_adm							= "";
	$rs_up_date							= "";


	if ($mode == "S") {

		$arr_rs = selectHigh($conn, (int)$h_no);

		$rs_h_no								= trim($arr_rs[0]["H_NO"]); 
		$rs_h_who								= trim($arr_rs[0]["H_WHO"]); 
		$rs_h_type							= trim($arr_rs[0]["H_TYPE"]); 
		$rs_h_program						= trim($arr_rs[0]["H_PROGRAM"]); 
		$rs_h_nm								= trim($arr_rs[0]["H_NM"]); 
		$rs_h_title							= SetStringFromDB($arr_rs[0]["H_TITLE"]); 
		$rs_h_numbers						= SetStringFromDB($arr_rs[0]["H_NUMBERS"]); 
		$rs_h_target						= SetStringFromDB($arr_rs[0]["H_TARGET"]); 
		$rs_h_period						= SetStringFromDB($arr_rs[0]["H_PERIOD"]); 
		$rs_h_agenda						= SetStringFromDB($arr_rs[0]["H_AGENDA"]); 
		$rs_h_venue							= SetStringFromDB($arr_rs[0]["H_VENUE"]); 

		$rs_apply_s_date				= trim($arr_rs[0]["APPLY_S_DATE"]); 
		$rs_apply_s_hour				= trim($arr_rs[0]["APPLY_S_HOUR"]); 
		$rs_apply_s_min					= trim($arr_rs[0]["APPLY_S_MIN"]); 
		$rs_apply_e_date				= trim($arr_rs[0]["APPLY_E_DATE"]); 
		$rs_apply_e_hour				= trim($arr_rs[0]["APPLY_E_HOUR"]); 
		$rs_apply_e_min					= trim($arr_rs[0]["APPLY_E_MIN"]); 
		$rs_event_s_date				= trim($arr_rs[0]["EVENT_S_DATE"]); 
		$rs_event_s_hour				= trim($arr_rs[0]["EVENT_S_HOUR"]); 
		$rs_event_s_min					= trim($arr_rs[0]["EVENT_S_MIN"]); 
		$rs_event_e_date				= trim($arr_rs[0]["EVENT_E_DATE"]); 
		$rs_event_e_hour				= trim($arr_rs[0]["EVENT_E_HOUR"]); 
		$rs_event_e_min					= trim($arr_rs[0]["EVENT_E_MIN"]); 
		$rs_h_contents					= SetStringFromDB($arr_rs[0]["H_CONTENTS"]); 

		$rs_apply_link					= trim($arr_rs[0]["APPLY_LINK"]); 
		$rs_confirm_link				= trim($arr_rs[0]["CONFIRM_LINK"]); 

		$rs_h_file							= trim($arr_rs[0]["H_FILE"]); 
		$rs_h_file_nm						= trim($arr_rs[0]["H_FILE_NM"]); 
		$rs_h_date							= SetStringFromDB($arr_rs[0]["H_DATE"]); 
		$rs_apply_use_tf				= trim($arr_rs[0]["APPLY_USE_TF"]); 
		$rs_event_use_tf				= trim($arr_rs[0]["EVENT_USE_TF"]); 
		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_apply_tf						= trim($arr_rs[0]["APPLY_TF"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm							= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date							= trim($arr_rs[0]["UP_DATE"]); 
 
		$result_log							= insertUserLog($conn, 'admin', $_SESSION['g_adm_id'], $_SERVER['REMOTE_ADDR'], '고교연계 페이지 조회 (제목 : '.$rs_h_title.') ', 'Read');

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;
		$strParam = $strParam."&con_yyyy=".$con_yyyy."&con_type=".$con_type."&con_app_type=".$con_app_type."&con_state=".$con_state;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "high_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$h_title?></title>
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../../_common/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>

<script type="text/javascript">

function js_list() {
	var frm = document.frm_list;
		
	frm.method = "get";
	frm.action = "high_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var h_no = "<?=$h_no?>";

	if (frm.h_type.value == "") {
		alert('고교연계프로그램을 선택해주세요.');
		frm.h_type.focus();
		return ;		
	}

	if (frm.h_program.value == "") {
		alert('고교연계프로그램을 선택해주세요.');
		frm.h_program.focus();
		return ;		
	}

	if (frm.h_nm.value == "") {
		alert('프로그램명을 입력해주세요.');
		frm.h_nm.focus();
		return ;		
	}

	/*
	var chk_str = "^";
	for (var i=0; i<frm.h_who_chk.length ; i++){
		if (frm.h_who_chk[i].checked){
			chk_str += frm.h_who_chk[i].value+"^";
		}
	}

	frm.h_who.value = chk_str;
	*/

	//alert(frm.h_file.value);
	/*
	if (frm.h_file.value == "") {
		alert('썸네일을 선택해주세요.');
		frm.h_file.focus();
		return ;		
	}
	*/

	if (frm.apply_use_tf.value=="Y"){
		if (frm.apply_s_date.value == "") {
			alert('신청 기간을 선택해주세요.');
			frm.apply_s_date.focus();
			return ;		
		}
		if (frm.apply_e_date.value == "") {
			alert('신청 기간을 선택해주세요.');
			frm.apply_e_date.focus();
			return ;		
		}
	}

	if (frm.event_use_tf.value=="Y"){
		if (frm.event_s_date.value == "") {
			alert('행사 기간을 선택해주세요.');
			frm.event_s_date.focus();
			return ;		
		}
		if (frm.event_e_date.value == "") {
			alert('행사 기간을 선택해주세요.');
			frm.event_e_date.focus();
			return ;		
		}
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (frm.h_numbers.value == "") {
		alert('모집인원을 입력해주세요.');
		frm.h_numbers.focus();
		return ;		
	}

	frm.target = "";
	frm.method = "post";
	frm.action = "<?=$_SERVER['PHP_SELF']?>";

	if (isNull(h_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}
		frm.submit();
}

function file_change(file) { 
	document.getElementById("file_name").value = file; 
}

/**
* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
*/
function js_exfileView(idx) {

	var obj = document.frm["file_flag[]"][idx];
	
	if (obj.selectedIndex == 2) {
		document.frm["file_name[]"][idx].style.visibility = "visible"; 
	} else { 
		document.frm["file_name[]"][idx].style.visibility = "hidden"; 
	}	
}

function js_fileView(obj,idx) {
	
	var frm = document.frm;
	
	if (idx == 01) {
		if (obj.selectedIndex == 1) {
			document.getElementById("file_change01").style.display = "inline";
		} else {
			document.getElementById("file_change01").style.display = "none";
		}
	}

	if (idx == 02) {
		if (obj.selectedIndex == 1) {
			document.getElementById("file_change02").style.display = "inline";
		} else {
			document.getElementById("file_change02").style.display = "none";
		}
	}

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');

	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
}

function FuncUseApply(obj){
	var frm = document.frm;
	if(obj.value == "Y"){
		$('#applyclass').css('display','');
	}else{
		$('#applyclass').css('display','none');
		frm.apply_s_date.value="";
		//frm.apply_s_hour.value="";
		//frm.apply_s_min.value="";
		frm.apply_e_date.value="";
		//frm.apply_e_hour.value="";
		//frm.apply_e_min.value="";
	}
}

function FuncUseEvent(obj){
	var frm = document.frm;
	if(obj.value == "Y"){
		$('#eventclass').css('display','');
	}else{
		$('#eventclass').css('display','none');
		frm.event_s_date.value="";
		//frm.event_s_hour.value="";
		//frm.event_s_min.value="";
		frm.event_e_date.value="";
		//frm.event_e_hour.value="";
		//frm.event_e_min.value="";
	}
}


$(document).ready(function() {
	//alert(val);
	makeProgramSelect("<?=$rs_h_program?>");
});

$(document).on("change", "#h_type", function() {
	makeProgramSelect("<?=$rs_h_program?>");
});


function makeProgramSelect(val) {

	$("#h_program").children("option:not(:first)").remove();

	if ($("#h_type").val() != "") {
		
		var mode = "GET_DCODE_LIST";

		var request = $.ajax({
			url:"/_common/ajax_common_dml.php",
			type:"POST",
			data:{mode:mode, pcode:"HIGH_PROGRAM", dcode_ext:$("#h_type").val()},
			dataType:"json"
		});
		
		request.done(function(data) {
			for (i = 0 ; i < data.length ; i++) {
				if (val == data[i].DCODE) {
					$("#h_program").append("<option value='"+data[i].DCODE+"' selected>"+data[i].DCODE_NM+"</option>");
				} else {
					$("#h_program").append("<option value='"+data[i].DCODE+"'>"+data[i].DCODE_NM+"</option>");
				}
			}
		});
	}
}

</script>
</head>
<body>
	<div id="wrap">
		
		<div class="cont_aside">
<?
	#====================================================================
	# common left_area
	#====================================================================
		require "../../_common/left_area.php";
?>
		</div>
		<div id="container">
			<div class="top">
<?
	#====================================================================
	# common top_area
	#====================================================================
		require "../../_common/top_area.php";
?>
			</div>
			<div class="contents">
<?
	#====================================================================
	# common location_area
	#====================================================================
		require "../../_common/location_area.php";
?>
				<div class="tit_h3"><h3><?=$p_menu_name?></h3></div>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="h_no" value="<?=$h_no?>" />
<input type="hidden" name="h_who" value="<?=$h_who?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>고교 연계 정보</h4></div>
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:12%" />
								<col style="width:38%" />
								<col style="width:12%" />
								<col />
							</colgroup>
							<tbody>

								<tr>
									<th>고교연계프로그램 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<?= makeSelectBox($conn,"HIGH_TYPE","h_type", "220","고교연계프로그램을 선택","",$rs_h_type)?>
										<select name="h_program" id="h_program" style="width:220px">
											<option value="">세부 프로그램을 선택하세요.</option>
										</select >
									</td>
								</tr>
								<!--
								<tr>
									<th>대상구분 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
									<? if ($rs_h_who == ""){ 
												 $rs_h_who = "^ALL"; 
										 }
									?>
											<?= makeCheckBoxFront($conn,"HIGH_WHO","h_who_chk", $rs_h_who)?>
									</td>
								</tr>
								-->
								<tr>
									<th>프로그램명 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
									<input type="text" name="h_nm" value="<?=$rs_h_nm?>" style="width:550px;" />
									<input type="hidden" name="h_date" value="<?=$rs_h_date?>" />
									</td>
								</tr>

								<tr>
									<th scope="row">신청기간 사용여부</th>
									<td colspan="3">
										<input type="radio" class="radio" name="apply_use_tf" value="Y" <? if(($rs_apply_use_tf =="Y") || ($rs_apply_use_tf =="")) echo "checked"; ?> onClick="FuncUseApply(this);"> 사용함&nbsp;&nbsp;
										<input type="radio" class="radio" name="apply_use_tf" value="N" <? if($rs_apply_use_tf =="N") echo "checked"; ?> onClick="FuncUseApply(this);"> 사용안함
									</td>
								</tr>

								<!-- [신청기간] 사용유무에 따른 테이블 tr태그 dateclass로 제어 //-->
								<? if (($rs_apply_use_tf == "Y") || ($rs_apply_use_tf == "")) {?>
								<tr id="applyclass">
									<th>신청 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="apply_s_date" id="apply_s_date" class="datepicker onlyphone" value="<?=$rs_apply_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","apply_s_hour","120","","",$rs_apply_s_hour)?>
											<select name="apply_s_min" id="apply_s_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);
													$str_selected = "";
													if ($rs_apply_s_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="apply_e_date" id="apply_e_date" class="datepicker onlyphone" value="<?=$rs_apply_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","apply_e_hour","120","","",$rs_apply_e_hour)?>
											<select name="apply_e_min" id="apply_e_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_apply_e_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
									</td>
								</tr>
								<? } else {?>
								<tr id="applyclass" style="display:none">
									<th>신청 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="apply_s_date" id="apply_s_date" class="datepicker onlyphone" value="<?=$rs_apply_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","apply_s_hour","120","","",$rs_apply_s_hour)?>
											<select name="apply_s_min" id="apply_s_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);
													$str_selected = "";
													if ($rs_apply_s_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="apply_e_date" id="apply_e_date" class="datepicker onlyphone" value="<?=$rs_apply_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","apply_e_hour","120","","",$rs_apply_e_hour)?>
											<select name="apply_e_min" id="apply_e_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_apply_e_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
									</td>
								</tr>
								<? } ?>
								<tr>
									<th scope="row">행사기간 사용여부</th>
									<td>
										<input type="radio" class="radio" name="event_use_tf" value="Y" <? if(($rs_event_use_tf =="Y") || ($rs_event_use_tf =="")) echo "checked"; ?> onClick="FuncUseEvent(this);"> 사용함&nbsp;&nbsp;
										<input type="radio" class="radio" name="event_use_tf" value="N" <? if($rs_event_use_tf =="N") echo "checked"; ?> onClick="FuncUseEvent(this);"> 사용안함
									</td>
								</tr>
								<!-- [행사기간] 사용유무에 따른 테이블 tr태그 dateclass로 제어 //-->
								<? if (($rs_event_use_tf == "Y") || ($rs_event_use_tf == "")) {?>
								<tr id="eventclass">
									<th>행사 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="event_s_date" id="event_s_date" class="datepicker onlyphone" value="<?=$rs_event_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","event_s_hour","120","","",$rs_event_s_hour)?>
											<select name="event_s_min" id="event_s_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);
													$str_selected = "";
													if ($rs_event_s_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="event_e_date" id="event_e_date" class="datepicker onlyphone" value="<?=$rs_event_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","event_e_hour","120","","",$rs_event_e_hour)?>
											<select name="event_e_min" id="event_e_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_event_e_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
									</td>
								</tr>
								<? } else {?>
								<tr id="eventclass" style="display:none">
									<th>행사 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="event_s_date" id="event_s_date" class="datepicker onlyphone" value="<?=$rs_event_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","event_s_hour","120","","",$rs_event_s_hour)?>
											<select name="event_s_min" id="event_s_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_event_s_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="event_e_date" id="event_e_date" class="datepicker onlyphone" value="<?=$rs_event_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","event_e_hour","120","","",$rs_event_e_hour)?>
											<select name="event_e_min" id="event_e_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_event_e_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
									</td>
								</tr>
								<? } ?>
								<!-- // 준비중, 신청중, 마감 메뉴 관리자에서 선택 추가 2021.3.17 추가  -->
								<tr>
									<th>진행여부 수동 설정</th>
									<td colspan="3">
										<select name="apply_tf">
											<option value="" <?if ($rs_apply_tf == ""){?>selected<?}?>>선택</option>
											<option value="P" <?if ($rs_apply_tf == "P"){?>selected<?}?>>준비중</option>
											<option value="I" <?if ($rs_apply_tf == "I"){?>selected<?}?>>신청중</option>
											<option value="F" <?if ($rs_apply_tf == "F"){?>selected<?}?>>마감</option>
										</select>
										<span class="tbl_txt"><span class="txt_c02">※ 선택일 경우에는 신청기간에 따라 진행여부(준비중, 신청중, 마감) 적용</span></span>
									</td>
								</tr>
								<!-- //추가 end -->
								<tr>
									<th>공개여부</th>
									<td colspan="3">
										<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /> <label for="all">공개</label>&nbsp;&nbsp;
										<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret">비공개</label>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>" />
									</td>
								</tr>

								<tr>
									<th>모집 인원 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
									<input type="text" name="h_numbers" value="<?=$rs_h_numbers?>" style="width:100px;" />
									</td>
								</tr>


								<!--
								<tr>
									<th scope="row">썸네일</th>
									<td colspan="3">
									<?
										if (strlen($rs_h_file) > 3) {
									?>
										<span class="tbl_txt">
											<a href="../../_common/new_download_file.php?menu=high&h_no=<?=$rs_h_no?>&field=h_file" target="_blank"><?=$rs_h_file_nm?></a></span>
										&nbsp;&nbsp;
										<select name="flag01" style="width:100px;" onchange="javascript:js_fileView(this,'01')">
											<option value="keep">유지</option>
											<option value="update">수정</option>
											<option value="delete">삭제</option>
										</select>
										<input type="hidden" name="h_file" value="<?= $rs_h_file?>">
										<input type="hidden" name="h_file_nm" value="<?= $rs_h_file_nm?>">
										<div id="file_change01" style="display:none;">
											<input type="file" name="h_file" size="40%" />
										</div>

									<?
										} else {
									?>
										<input type="file" size="40%" name="h_file">(jpg, gif, png 파일 업로드 가능)<br />
										<input type="hidden" name="h_file" value="">
										<input type="hidden" name="h_file_nm" value="">
										<input TYPE="hidden" name="flag01" value="insert">
									<?
										}	
									?>
									</td>
								</tr>

								<tr>
									<th>대상</th>
									<td colspan="3">
										<input type="text" name="h_target" value="<?=$rs_h_target?>" style="width:30%;" />
										<span class="tbl_txt"><span class="txt_c02">ex) 고교 교사(진로진학 상담교사, 진로부장, 고3 담임교사)</span></span>
									</td>
								</tr>
								<tr>
									<th>기간</th>
									<td colspan="3">
										<input type="text" name="h_period" value="<?=$rs_h_period?>" style="width:30%;" />
										<span class="tbl_txt"><span class="txt_c02">ex) 2022-07-07 (목요일)</span></span>
									</td>
								</tr>

								<tr>
									<th>목적</th>
									<td colspan="3">
									<textarea name="h_agenda" style="width:685px;height:150px"><?=$rs_h_agenda?></textarea>
									</td>
								</tr>

								<tr>
									<th>내용</th>
									<td colspan="3">
									<textarea name="h_contents" style="width:685px;height:150px"><?=$rs_h_contents?></textarea>
									</td>
								</tr>
								-->
								<tr>
									<th>신청 페이지 링크</th>
									<td colspan="3">
										<input type="text" name="apply_link" value="<?=$rs_apply_link?>" style="width:50%;" />
										<span class="tbl_txt"><span class="txt_c02">* https:// 또는 http:// 포함 하여 등록</span></span>
									</td>
								</tr>

								<tr>
									<th>신청 확인 페이지 링크</th>
									<td colspan="3">
										<input type="text" name="confirm_link" value="<?=$rs_confirm_link?>" style="width:50%;" />
										<span class="tbl_txt"><span class="txt_c02">* https:// 또는 http:// 포함 하여 등록</span></span>
									</td>
								</tr>


							</tbody>
						</table>
					</div>
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ($h_no == "") {?>
						<?	if ($sPageRight_I == "Y") {?>
						<button type="button" class="button" onClick="js_save()">등록</button>
						<?	} ?>
						<? } else {  ?>
						<?	if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="js_save()">수정</button>
						<?	} ?>
						<?	if ($sPageRight_D == "Y") {?>
						<button type="button" class="button" onClick="js_delete()">삭제</button>
						<?	} ?>
						<? } ?>
					</div>
				</div>
				
</form>
<form name="frm_list" method="post">

<input type="hidden" name="h_no" value="<?=$h_no?>" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>" />
<input type="hidden" name="search_str" value="<?=$search_str?>" />
<input type="hidden" name="order_field" value="<?=$order_field?>" />
<input type="hidden" name="order_str" value="<?=$order_str?>" />
<input type="hidden" name="con_yyyy" value="<?=$con_yyyy?>" />
<input type="hidden" name="con_type" value="<?=$con_type?>" />
<input type="hidden" name="con_app_type" value="<?=$con_app_type?>" />
<input type="hidden" name="con_state" value="<?=$con_state?>" />

</form>

			</div>

		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>

</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================

	db_close($conn);
?>