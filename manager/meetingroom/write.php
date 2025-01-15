<?session_start();?>
<?
# =============================================================================
# File Name    : write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2024-12-03
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
	$menu_right = "FI001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/meetingroom/meetingroom.php";

#====================================================================
# DML Process
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$item_no					= isset($_POST["item_no"]) && $_POST["item_no"] !== '' ? $_POST["item_no"] : (isset($_GET["item_no"]) ? $_GET["item_no"] : '');
	$room_no					= isset($_POST["room_no"]) && $_POST["room_no"] !== '' ? $_POST["room_no"] : (isset($_GET["room_no"]) ? $_GET["room_no"] : '');
	$room_name				= isset($_POST["room_name"]) && $_POST["room_name"] !== '' ? $_POST["room_name"] : (isset($_GET["room_name"]) ? $_GET["room_name"] : '');
	$room_dec					= isset($_POST["room_dec"]) && $_POST["room_dec"] !== '' ? $_POST["room_dec"] : (isset($_GET["room_dec"]) ? $_GET["room_dec"] : '');
	$time_type				= isset($_POST["time_type"]) && $_POST["time_type"] !== '' ? $_POST["time_type"] : (isset($_GET["time_type"]) ? $_GET["time_typea"] : '');
	$able_period			= isset($_POST["able_period"]) && $_POST["able_period"] !== '' ? $_POST["able_period"] : (isset($_GET["able_period"]) ? $_GET["able_period"] : '');
	$room_scale				= isset($_POST["room_scale"]) && $_POST["room_scale"] !== '' ? $_POST["room_scale"] : (isset($_GET["room_scale"]) ? $_GET["room_scale"] : '');
	$room_capacity		= isset($_POST["room_capacity"]) && $_POST["room_capacity"] !== '' ? $_POST["room_capacity"] : (isset($_GET["room_capacity"]) ? $_GET["room_capacity"] : '');
	$room_price				= isset($_POST["room_price"]) && $_POST["room_price"] !== '' ? $_POST["room_price"] : (isset($_GET["room_price"]) ? $_GET["room_price"] : '');
	$room_night_price	= isset($_POST["room_night_price"]) && $_POST["room_night_price"] !== '' ? $_POST["room_night_price"] : (isset($_GET["room_night_price"]) ? $_GET["room_night_price"] : '');
	$room_file				= isset($_POST["room_file"]) && $_POST["room_file"] !== '' ? $_POST["room_file"] : (isset($_GET["room_file"]) ? $_GET["room_file"] : '');
	$room_rfile				= isset($_POST["room_rfile"]) && $_POST["room_rfile"] !== '' ? $_POST["room_rfile"] : (isset($_GET["room_rfile"]) ? $_GET["room_rfile"] : '');
	$use_time					= isset($_POST["use_time"]) && $_POST["use_time"] !== '' ? $_POST["use_time"] : (isset($_GET["use_time"]) ? $_GET["use_time"] : '');
	$use_guide				= isset($_POST["use_guide"]) && $_POST["use_guide"] !== '' ? $_POST["use_guide"] : (isset($_GET["use_guide"]) ? $_GET["use_guide"] : '');

	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$seq_no						= isset($_POST["seq_no"]) && $_POST["seq_no"] !== '' ? $_POST["seq_no"] : (isset($_GET["seq_no"]) ? $_GET["seq_no"] : '');
	$file_disp_seq		= isset($_POST["file_disp_seq"]) && $_POST["file_disp_seq"] !== '' ? $_POST["file_disp_seq"] : (isset($_GET["file_disp_seq"]) ? $_GET["file_disp_seq"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$flag01						= isset($_POST["flag01"]) && $_POST["flag01"] !== '' ? $_POST["flag01"] : (isset($_GET["flag01"]) ? $_GET["flag01"] : '');

	$old_room_file		= isset($_POST["old_room_file"]) && $_POST["old_room_file"] !== '' ? $_POST["old_room_file"] : (isset($_GET["old_room_file"]) ? $_GET["old_room_file"] : '');
	$old_room_rfile		= isset($_POST["old_room_rfile"]) && $_POST["old_room_rfile"] !== '' ? $_POST["old_room_rfile"] : (isset($_GET["old_room_rfile"]) ? $_GET["old_room_rfile"] : '');

	$file_nm					= isset($_POST["file_nm"]) && $_POST["file_nm"] !== '' ? $_POST["file_nm"] : (isset($_GET["file_nm"]) ? $_GET["file_nm"] : '');
	$file_rnm					= isset($_POST["file_rnm"]) && $_POST["file_rnm"] !== '' ? $_POST["file_rnm"] : (isset($_GET["file_rnm"]) ? $_GET["file_rnm"] : '');

	$old_file_nm			= isset($_POST["old_file_nm"]) && $_POST["old_file_nm"] !== '' ? $_POST["old_file_nm"] : (isset($_GET["old_file_nm"]) ? $_GET["old_file_nm"] : '');
	$old_file_rnm			= isset($_POST["old_file_rnm"]) && $_POST["old_file_rnm"] !== '' ? $_POST["old_file_rnm"] : (isset($_GET["old_file_rnm"]) ? $_GET["old_file_rnm"] : '');

	$file_flag				= isset($_POST["file_flag"]) && $_POST["file_flag"] !== '' ? $_POST["file_flag"] : (isset($_GET["file_flag"]) ? $_GET["file_flag"] : '');

	$arr_rs_detail = array();
#====================================================================
	$savedir1 = $g_physical_path."upload_data/meetingroom";
#====================================================================

	$result = false;

	$mode							= SetStringToDB($mode);
	$search_field			= SetStringToDB($search_field);
	$search_str				=	SetStringToDB($search_str);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "I") {
		
		if ($_FILES["room_file"]["name"] <> ""){
			$room_file					= upload($_FILES["room_file"], $savedir1, 100 , array('jpg','png','gif','jpeg','tif'));
			$room_rfile					= $_FILES["room_file"]["name"];
		}
		
		$arr_data = array("ROOM_NAME"=>$room_name,
											"ROOM_DEC"=>$room_dec,
											"TIME_TYPE"=>$time_type,
											"ABLE_PERIOD"=>$able_period,
											"ROOM_SCALE"=>$room_scale,
											"ROOM_CAPACITY"=>$room_capacity,
											"ROOM_PRICE"=>$room_price,
											"ROOM_NIGHT_PRICE"=>$room_night_price,
											"ROOM_FILE"=>$room_file,
											"ROOM_RFILE"=>$room_rfile,
											"USE_TIME"=>$use_time,
											"USE_GUIDE"=>$use_guide,
											"DISP_SEQ"=>0,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$new_room_no	= insertMeetingRoom($conn, $arr_data);
		$result =$new_room_no;

		if (is_null($file_nm)) {
			$file_cnt = 0;
		} elseif (is_countable($file_nm)) {
			$file_cnt = count($file_nm);
		} else {
			// $file_nm이 문자열일 경우 처리
			$file_cnt = 1;
		}

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);
		$allow_file_size = 100;

		for($i=0; $i <= $file_cnt; $i++) {

			if (isset($_POST["file_flag"][$i]) && $_POST["file_flag"][$i] == "insert") {

				$file_nm					= multiupload($_FILES["file_nm"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','GIF', 'JPEG', 'JPG','PNG'));
				$file_rnm					= $_FILES["file_nm"]["name"][$i];
				$use_tf = "Y";

				if ($file_nm <> "") {

					$arr_data_detail = array("ROOM_NO"=>$new_room_no,
																	"FILE_NM"=>$file_nm,
																	"FILE_RNM"=>$file_rnm,
																	"DISP_SEQ"=>$i,
																	"REG_ADM"=>$_SESSION["s_adm_no"]
					);

					$result_file = insertMeetingRoomFile($conn, $arr_data_detail);
				}
			}
		}

		$result_log	= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '시설 등록', 'Insert');

	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$room_file					= upload($_FILES["room_file"], $savedir1, 100 , array('jpg','png','gif','jpeg','tif'));
				$room_rfile					= $_FILES["room_file"]["name"];
			break;
			case "keep" :
				$room_file		= $old_room_file;
				$room_rfile		= $old_room_rfile;
			break;
			case "delete" :
				$room_file		= "";
				$room_rfile		= "";
			break;
			case "update" :
				$room_file					= upload($_FILES["room_file"], $savedir1, 100 , array('jpg','png','gif','jpeg','tif'));
				$room_rfile					= $_FILES["room_file"]["name"];
			break;
		}

		$arr_data = array("ROOM_NAME"=>$room_name,
											"ROOM_DEC"=>$room_dec,
											"TIME_TYPE"=>$time_type,
											"ABLE_PERIOD"=>$able_period,
											"ROOM_SCALE"=>$room_scale,
											"ROOM_CAPACITY"=>$room_capacity,
											"ROOM_PRICE"=>$room_price,
											"ROOM_NIGHT_PRICE"=>$room_night_price,
											"ROOM_FILE"=>$room_file,
											"ROOM_RFILE"=>$room_rfile,
											"USE_TIME"=>$use_time,
											"USE_GUIDE"=>$use_guide,
											"DISP_SEQ"=>0,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result = updateMeetingRoom($conn, $arr_data, $room_no);

		$result = deleteMeetingRoomFile($conn, (int)$room_no);

		if (is_null($file_nm)) {
			$file_cnt = 0;
		} elseif (is_countable($file_nm)) {
			$file_cnt = count($file_nm);
		} else {
			// $file_nm이 문자열일 경우 처리
			$file_cnt = 1;
		}

		$file_cnt = is_null($file_flag) ? 0 : count($file_flag);
		$allow_file_size = 100;

		for($i=0; $i <= $file_cnt; $i++) {

			if (isset($_POST["file_flag"][$i]) && ($_POST["file_flag"][$i] == "insert" || $_POST["file_flag"][$i] == "update")) {

				$file_nm					= multiupload($_FILES["file_nm"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','GIF', 'JPEG', 'JPG','PNG'));
				$file_rnm					= $_FILES["file_nm"]["name"][$i];
				$use_tf = "Y";

				if ($file_nm <> "") {

					$arr_data_detail = array("ROOM_NO"=>$room_no,
																	"FILE_NM"=>$file_nm,
																	"FILE_RNM"=>$file_rnm,
																	"DISP_SEQ"=>$i,
																	"REG_ADM"=>$_SESSION["s_adm_no"]
					);

					$result_file = insertMeetingRoomFile($conn, $arr_data_detail);
				}
			}

			if (isset($_POST["file_flag"][$i]) && $_POST["file_flag"][$i] == "keep") {

				$file_nm					= $old_file_nm[$i];
				$file_rnm					= $old_file_rnm[$i];

				if ($file_nm <> "") {

					$arr_data_detail = array("ROOM_NO"=>$room_no,
																	"FILE_NM"=>$file_nm,
																	"FILE_RNM"=>$file_rnm,
																	"DISP_SEQ"=>$i,
																	"REG_ADM"=>$_SESSION["s_adm_no"]
					);

					$result_file = insertMeetingRoomFile($conn, $arr_data_detail);
				}
			}

		}

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '시설정보 수정', 'Update');
	}

	if ($mode == "D") {
		$result = deleteMeetingRoom($conn, $_SESSION['s_adm_no'], (int)$room_no);
		$result = deleteMeetingRoomFile($conn, (int)$room_no);
		$result = deleteMeetingRoomDisable($conn, (int)$room_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '시설정보 삭제 처리', 'Delete');
	}

	$rs_room_no							= "";
	$rs_room_name						= "";
	$rs_room_dec						= "";
	$rs_time_type						= "";
	$rs_able_period					= "";
	$rs_room_scale					= "";
	$rs_room_capacity				= "";
	$rs_room_price					= "";
	$rs_room_night_price		= "";
	$rs_room_file						= "";
	$rs_room_rfile					= "";
	$rs_use_time						= "";
	$rs_use_guide						= "";
	$rs_disp_seq						= "";
	$rs_use_tf							= "";
	$rs_del_tf							= "";
	$rs_reg_adm							= "";
	$rs_reg_date						= "";
	$rs_up_adm							= "";
	$rs_up_date							= "";

	if ($mode == "S") {

		$arr_rs = selectMeetingRoom($conn, (int)$room_no);

		$rs_room_no						= trim($arr_rs[0]["ROOM_NO"]); 
		$rs_room_name					= SetStringFromDB($arr_rs[0]["ROOM_NAME"]);
		$rs_room_dec					= SetStringFromDB($arr_rs[0]["ROOM_DEC"]);
		$rs_time_type					= SetStringFromDB($arr_rs[0]["TIME_TYPE"]);
		$rs_able_period				= SetStringFromDB($arr_rs[0]["ABLE_PERIOD"]);
		$rs_room_scale				= SetStringFromDB($arr_rs[0]["ROOM_SCALE"]);
		$rs_room_capacity			= SetStringFromDB($arr_rs[0]["ROOM_CAPACITY"]);
		$rs_room_price				= trim($arr_rs[0]["ROOM_PRICE"]);
		$rs_room_night_price	= trim($arr_rs[0]["ROOM_NIGHT_PRICE"]);
		$rs_room_file					= SetStringFromDB($arr_rs[0]["ROOM_FILE"]);
		$rs_room_rfile				= SetStringFromDB($arr_rs[0]["ROOM_RFILE"]);
		$rs_use_time					= SetStringFromDB($arr_rs[0]["USE_TIME"]);
		$rs_use_guide					= SetStringFromDB($arr_rs[0]["USE_GUIDE"]);
		$rs_disp_seq					= trim($arr_rs[0]["DISP_SEQ"]);
		$rs_use_tf						= trim($arr_rs[0]["USE_TF"]);
		$rs_del_tf						= trim($arr_rs[0]["DEL_TF"]);
		$rs_reg_adm						= trim($arr_rs[0]["REG_ADM"]);
		$rs_reg_date					= trim($arr_rs[0]["REG_DATE"]);
		$rs_up_adm						= trim($arr_rs[0]["UP_ADM"]);
		$rs_up_date						= trim($arr_rs[0]["UP_DATE"]);
		 
		$arr_rs_detail					= selectMeetingRoomFile($conn, $rs_room_no);

		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '시설 조회', 'Read');

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "list.php<?=$strParam?>";
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
<title><?=$g_title?></title>
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
	frm.action = "list.php";
	frm.submit();
}

function js_disable_date() {
	document.location = "calendar.php?room_no=<?=$room_no?>";
}

function js_exfileView(idx) {
	
	// fake input 추가 때문에 이렇게 처리 합니다.
	idx++;

	var obj = document.frm["file_flag[]"][idx];
	
	if (obj.selectedIndex == 1) {
		document.frm["file_nm[]"][idx].style.visibility = "visible"; 
	} else { 
		document.frm["file_nm[]"][idx].style.visibility = "hidden"; 
	}	
}

function js_save() {

	var frm = document.frm;
	var room_no = "<?=$room_no?>";
	var seq_no = "<?=$seq_no?>";

	if (frm.room_name.value == "") {
		alert('시설명을 입력해 주세요.');
		frm.room_name.focus();
		return ;		
	}

	if (frm.able_period.value == "") {
		alert('예약기간을 입력해 주세요.');
		frm.able_period.focus();
		return ;		
	}

	if (frm.room_scale.value == "") {
		alert('시설규모를 입력해 주세요.');
		frm.room_scale.focus();
		return ;		
	}

	if (frm.room_capacity.value == "") {
		alert('수용인원을 입력해 주세요.');
		frm.room_capacity.focus();
		return ;		
	}

	if (frm.room_price.value == "") {
		alert('이용료를 입력해 주세요.');
		frm.room_price.focus();
		return ;		
	}

	if (frm.room_night_price.value == "") {
		alert('야간 이용료를 입력해 주세요.');
		frm.room_night_price.focus();
		return ;		
	}

	var ret = true;
	var chk = 1;  //항목 배열 공백 체크

	if (document.frm.rd_url_type == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
		}
	}

	if (document.frm.rd_use_time == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_time[0].checked == true) {
			frm.use_time.value = "1H";
		} else if (frm.rd_use_time[1].checked == true) {
			frm.use_time.value = "2H";
		} else if (frm.rd_use_time[2].checked == true) {
			frm.use_time.value = "3H";
		} else {
			frm.use_time.value = "4H";
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

	frm.target = "";
	frm.method = "post";
	frm.action = "<?=$_SERVER['PHP_SELF']?>";

	if (isNull(room_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}
	frm.submit();
}

function file_change(file) { 
	document.getElementById("room_file").value = file; 
}

/**
* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
*/
/*
function js_exfileView(idx) {

	var obj = document.frm["file_flag[]"][idx];
	
	if (obj.selectedIndex == 2) {
		document.frm["file_name[]"][idx].style.visibility = "visible"; 
	} else { 
		document.frm["file_name[]"][idx].style.visibility = "hidden"; 
	}	
}
*/

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

function js_inputAdd(){
	
	var t= "<div style='padding-bottom:10px;cursor:pointer'>";
			t +="<input type='hidden' name='file_flag[]' value='insert'>";
			t +="<span class='tbl_txt'>이미지</span>&nbsp;&nbsp;<input type='file' size='40%' name='file_nm[]'> 800 * 600 ";
			t +="<span class='tbl_txt'><img src='../images/btn_del.gif' onClick='js_inputRemove(this)' style='cursor:hand'></span>";
			t +="</div>";

	$("#item_add").append(t);
}

function js_inputRemove(t){
	$(t).parent().parent().remove();
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
<input type="hidden" name="room_no" value="<?=$room_no?>" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="item_no" value="<?=$seq_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>시설정보</h4></div>
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
									<th>시설명 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="room_name" id="room_name" value="<?=$rs_room_name?>"/>
									</td>
								</tr>
								<tr>
									<th>예약기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="able_period" id="able_period" value="<?=$rs_able_period?>"/>
									</td>
								</tr>
								<tr>
									<th>시설규모 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="room_scale" id="room_scale" value="<?=$rs_room_scale?>"/>
									</td>
								</tr>
								<tr>
									<th>수용인원 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="room_capacity" id="room_capacity" value="<?=$rs_room_capacity?>"/>
									</td>
								</tr>
								<tr>
									<th>이용료 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="room_price" id="room_price" value="<?=$rs_room_price?>"/>
									</td>
								</tr>
								<tr>
									<th>야간이용료 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="room_night_price" id="room_night_price" value="<?=$rs_room_night_price?>"/>
									</td>
								</tr>
								<tr>
									<th>예약시간구분</th>
									<td colspan="3">
										<input type="radio" id="1H" name="rd_use_time" value="1H" <? if (($rs_use_time =="1H") || ($rs_use_time =="")) echo "checked"; ?> class="radio" /> <label for="1H">1시간 단위</label>&nbsp;&nbsp;
										<input type="radio" id="2H" name="rd_use_time" value="2H" <? if ($rs_use_time =="2H") echo "checked"; ?> class="radio" /> <label for="2H">2시간 단위</label>&nbsp;&nbsp;
										<input type="radio" id="3H" name="rd_use_time" value="3H" <? if ($rs_use_time =="3H") echo "checked"; ?> class="radio" /> <label for="3H">3시간 단위</label>&nbsp;&nbsp;
										<input type="radio" id="4H" name="rd_use_time" value="4H" <? if ($rs_use_time =="4H") echo "checked"; ?> class="radio" /> <label for="4H">4시간 단위</label>
										<input type="hidden" name="use_time" value="<?= $rs_use_time ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">대표이미지</th>
									<td colspan="3">
									<?
										if (strlen($rs_room_file) > 3) {
									?>
										<span class="tbl_txt"><img src="/upload_data/meetingroom/<?=$rs_room_file?>" width="200px"></span>
										&nbsp;&nbsp;
										<select name="flag01" style="width:100px;" onchange="javascript:js_fileView(this,'01')">
											<option value="keep">유지</option>
											<option value="update">수정</option>
											<option value="delete">삭제</option>
										</select>
						
										<input type="hidden" name="old_room_file" value="<?= $rs_room_file?>">
										<input type="hidden" name="old_room_rfile" value="<?= $rs_room_rfile?>">
										<div id="file_change01" style="display:none;">
											<input type="file" name="room_file" size="40%" />
										</div>

									<?
										} else {
									?>
										<input type="file" size="40%" name="room_file">
										<input type="hidden" name="old_room_file" value="">
										<input type="hidden" name="old_room_rfile" value="">
										<input TYPE="hidden" name="flag01" value="insert">
									<?
										}	
									?>
									</td>
								</tr>
								<tr>
									<th>추가 이미지</th>
									<td colspan="3">
										<div class="sp5"></div>
										<button class="button" type="button" onClick="js_inputAdd()" >이미지 추가</button>
										<div id="item_add" style="padding-top:10px">
											<input type="hidden" name="file_flag[]" value=""> 
											<input type="file" name="file_nm[]" value="" style="display:none">
											<input type="hidden" name="old_file_no[]" value="">
											<input type="hidden" name="old_file_nm[]" value="">
											<input type="hidden" name="old_file_rnm[]" value="">
												<?
													$nCnt = 0;
													$f_Cnt = 0;
													
													if (sizeof($arr_rs_detail) > 0) {
														
														for ($j = 0 ; $j < sizeof($arr_rs_detail); $j++) {
															$file_nm				= trim($arr_rs_detail[$j]["FILE_NM"]);
															$file_rnm				= trim($arr_rs_detail[$j]["FILE_RNM"]);
												?>
															<div style="padding-bottom:10px;cursor:pointer">
																<span class="tbl_txt">이미지</span>&nbsp;&nbsp;<img src="/upload_data/meetingroom/<?=$file_nm?>" width="150px">&nbsp;&nbsp;
																<select name="file_flag[]" onchange="javascript:js_exfileView('<?=$f_Cnt?>')">
																	<option value="keep">유지</option>
																	<option value="update">수정</option>
																	<option value="delete">삭제</option>
																</select>
																<input type="hidden" name="old_file_nm[]" value="<?=$file_nm?>">
																<input type="hidden" name="old_file_rnm[]" value="<?=$file_rnm?>">
																<input TYPE="file" NAME="file_nm[]" size="40%" style="visibility:hidden"> 800 * 600
																<span class="tbl_txt"><img src="../images/btn_del.gif" onClick="js_inputRemove(this)" style="cursor:hand">
															</div>
															
												<?		$f_Cnt++;
														}
													} else {
												?>
															<div style="padding-bottom:10px;cursor:pointer">
																<input type="hidden" name="file_flag[]" value="insert">
																<span class="tbl_txt">이미지</span>&nbsp;&nbsp;<input type="file" size="40%" name="file_nm[]"> 800 * 600
															</div>
												<?}?>
											</div>
											<span class="txt_c02" style="padding-left:10px">※ Drag & Drop 으로 순서를 조정 하실 수 있습니다.</span>
									</td>
								</tr>
								<? if ($room_no <> "") {?>
								<tr>
									<th>예약 불가일 설정</th>
									<td colspan="3">
										<div class="sp5"></div>
										<button class="button" type="button" onClick="js_disable_date()" >예약 불가일 등록</button>
										<div class="sp5"></div>
									</td>
								</tr>
								<? } ?>
								<tr>
									<th>공개여부</th>
									<td colspan="3">
										<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /> <label for="all">공개</label>&nbsp;&nbsp;
										<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret">비공개</label>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>" />
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ($room_no == "") {?>
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

<input type="hidden" name="room_no" value="<?=$room_no?>" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="item_no" value="<?=$md_no?>" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>" />
<input type="hidden" name="search_str" value="<?=$search_str?>" />
<input type="hidden" name="order_field" value="<?=$order_field?>" />
<input type="hidden" name="order_str" value="<?=$order_str?>" />

</form>


			</div>

		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>

<script type="text/javascript" src="../js/Sortable.js"></script>

<script>

	new Sortable(item_add, {
		animation: 150,
		//ghostClass: 'blue-background-class'
	});

</script>

</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================

	db_close($conn);
?>