<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : board_answr.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-11
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	$result = false;
#==============================================================================
# Confirm right
#==============================================================================
	$writer_id	= $s_adm_id;//작성자 아이디:로그인한 사용자 아이디
	$b_code			= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_code			= trim($b_code);

	//echo $b_code;

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = $b_code; // 메뉴마다 셋팅 해 주어야 합니다

	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/board/board.php";


	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');

	$config_no			= isset($_POST["config_no"]) && $_POST["config_no"] !== '' ? $_POST["config_no"] : (isset($_GET["config_no"]) ? $_GET["config_no"] : '');
	$main_tf				= isset($_POST["main_tf"]) && $_POST["main_tf"] !== '' ? $_POST["main_tf"] : (isset($_GET["main_tf"]) ? $_GET["main_tf"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$top_tf					= isset($_POST["top_tf"]) && $_POST["top_tf"] !== '' ? $_POST["top_tf"] : (isset($_GET["top_tf"]) ? $_GET["top_tf"] : '');
	$ref_tf					= isset($_POST["ref_tf"]) && $_POST["ref_tf"] !== '' ? $_POST["ref_tf"] : (isset($_GET["ref_tf"]) ? $_GET["ref_tf"] : '');
	$new_tf					= isset($_POST["new_tf"]) && $_POST["new_tf"] !== '' ? $_POST["new_tf"] : (isset($_GET["new_tf"]) ? $_GET["new_tf"] : '');

	$b_code					= isset($_POST["b_code"]) && $_POST["b_code"] !== '' ? $_POST["b_code"] : (isset($_GET["b_code"]) ? $_GET["b_code"] : '');
	$b_no						= isset($_POST["b_no"]) && $_POST["b_no"] !== '' ? $_POST["b_no"] : (isset($_GET["b_no"]) ? $_GET["b_no"] : '');

	$parent_no			= isset($_POST["parent_no"]) && $_POST["parent_no"] !== '' ? $_POST["parent_no"] : (isset($_GET["parent_no"]) ? $_GET["parent_no"] : '');
	$b_po						= isset($_POST["b_po"]) && $_POST["b_po"] !== '' ? $_POST["b_po"] : (isset($_GET["b_po"]) ? $_GET["b_po"] : '');
	$b_re						= isset($_POST["b_re"]) && $_POST["b_re"] !== '' ? $_POST["b_re"] : (isset($_GET["b_re"]) ? $_GET["b_re"] : '');

	$cate_01				= isset($_POST["cate_01"]) && $_POST["cate_01"] !== '' ? $_POST["cate_01"] : (isset($_GET["cate_01"]) ? $_GET["cate_01"] : '');
	$cate_02				= isset($_POST["cate_02"]) && $_POST["cate_02"] !== '' ? $_POST["cate_02"] : (isset($_GET["cate_02"]) ? $_GET["cate_02"] : '');
	$cate_03				= isset($_POST["cate_03"]) && $_POST["cate_03"] !== '' ? $_POST["cate_03"] : (isset($_GET["cate_03"]) ? $_GET["cate_03"] : '');
	$cate_04				= isset($_POST["cate_04"]) && $_POST["cate_04"] !== '' ? $_POST["cate_04"] : (isset($_GET["cate_04"]) ? $_GET["cate_04"] : '');

	$secret_tf			= isset($_POST["secret_tf"]) && $_POST["secret_tf"] !== '' ? $_POST["secret_tf"] : (isset($_GET["secret_tf"]) ? $_GET["secret_tf"] : '');
	$writer_nm			= isset($_POST["writer_nm"]) && $_POST["writer_nm"] !== '' ? $_POST["writer_nm"] : (isset($_GET["writer_nm"]) ? $_GET["writer_nm"] : '');
	$writer_pw			= isset($_POST["writer_pw"]) && $_POST["writer_pw"] !== '' ? $_POST["writer_pw"] : (isset($_GET["writer_pw"]) ? $_GET["writer_pw"] : '');

	$b_board_type		= isset($_POST["b_board_type"]) && $_POST["b_board_type"] !== '' ? $_POST["b_board_type"] : (isset($_GET["b_board_type"]) ? $_GET["b_board_type"] : '');

	$reply					= isset($_POST["reply"]) && $_POST["reply"] !== '' ? $_POST["reply"] : (isset($_GET["reply"]) ? $_GET["reply"] : '');
	$reply_state		= isset($_POST["reply_state"]) && $_POST["reply_state"] !== '' ? $_POST["reply_state"] : (isset($_GET["reply_state"]) ? $_GET["reply_state"] : '');

	$con_cate_01		= isset($_POST["con_cate_01"]) && $_POST["con_cate_01"] !== '' ? $_POST["con_cate_01"] : (isset($_GET["con_cate_01"]) ? $_GET["con_cate_01"] : '');
	$con_cate_02		= isset($_POST["con_cate_02"]) && $_POST["con_cate_02"] !== '' ? $_POST["con_cate_02"] : (isset($_GET["con_cate_02"]) ? $_GET["con_cate_02"] : '');
	$con_cate_03		= isset($_POST["con_cate_03"]) && $_POST["con_cate_03"] !== '' ? $_POST["con_cate_03"] : (isset($_GET["con_cate_03"]) ? $_GET["con_cate_03"] : '');
	$con_cate_04		= isset($_POST["con_cate_04"]) && $_POST["con_cate_04"] !== '' ? $_POST["con_cate_04"] : (isset($_GET["con_cate_04"]) ? $_GET["con_cate_04"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	$keyword				= isset($_POST["keyword"]) && $_POST["keyword"] !== '' ? $_POST["keyword"] : (isset($_GET["keyword"]) ? $_GET["keyword"] : '');

	$con_writer_id	= isset($_POST["con_writer_id"]) && $_POST["con_writer_id"] !== '' ? $_POST["con_writer_id"] : (isset($_GET["con_writer_id"]) ? $_GET["con_writer_id"] : '');
	$con_use_tf			= isset($_POST["con_use_tf"]) && $_POST["con_use_tf"] !== '' ? $_POST["con_use_tf"] : (isset($_GET["con_use_tf"]) ? $_GET["con_use_tf"] : '');
	$con_use_tf			= isset($_POST["con_use_tf"]) && $_POST["con_use_tf"] !== '' ? $_POST["con_use_tf"] : (isset($_GET["con_use_tf"]) ? $_GET["con_use_tf"] : '');

	$chk						= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	$con_reply_state	= isset($_POST["con_reply_state"]) && $_POST["con_reply_state"] !== '' ? $_POST["con_reply_state"] : (isset($_GET["con_reply_state"]) ? $_GET["con_reply_state"] : '');
	$start_date				= isset($_POST["start_date"]) && $_POST["start_date"] !== '' ? $_POST["start_date"] : (isset($_GET["start_date"]) ? $_GET["start_date"] : '');
	$end_date					= isset($_POST["end_date"]) && $_POST["end_date"] !== '' ? $_POST["end_date"] : (isset($_GET["end_date"]) ? $_GET["end_date"] : '');
	$con_call_tf			= isset($_POST["con_call_tf"]) && $_POST["con_call_tf"] !== '' ? $_POST["con_call_tf"] : (isset($_GET["con_call_tf"]) ? $_GET["con_call_tf"] : '');
	$con_reserve_tf		= isset($_POST["con_reserve_tf"]) && $_POST["con_reserve_tf"] !== '' ? $_POST["con_reserve_tf"] : (isset($_GET["con_reserve_tf"]) ? $_GET["con_reserve_tf"] : '');

	$email						= isset($_POST["email"]) && $_POST["email"] !== '' ? $_POST["email"] : (isset($_GET["email"]) ? $_GET["email"] : '');
	$title						= isset($_POST["title"]) && $_POST["title"] !== '' ? $_POST["title"] : (isset($_GET["title"]) ? $_GET["title"] : '');
	
	$s_date						= isset($_POST["s_date"]) && $_POST["s_date"] !== '' ? $_POST["s_date"] : (isset($_GET["s_date"]) ? $_GET["s_date"] : '');
	$s_hour						= isset($_POST["s_hour"]) && $_POST["s_hour"] !== '' ? $_POST["s_hour"] : (isset($_GET["s_hour"]) ? $_GET["s_hour"] : '');
	$s_min						= isset($_POST["s_min"]) && $_POST["s_min"] !== '' ? $_POST["s_min"] : (isset($_GET["s_min"]) ? $_GET["s_min"] : '');
	$e_date						= isset($_POST["e_date"]) && $_POST["e_date"] !== '' ? $_POST["e_date"] : (isset($_GET["e_date"]) ? $_GET["e_date"] : '');
	$e_hour						= isset($_POST["e_hour"]) && $_POST["e_hour"] !== '' ? $_POST["e_hour"] : (isset($_GET["e_hour"]) ? $_GET["e_hour"] : '');
	$e_min						= isset($_POST["e_min"]) && $_POST["e_min"] !== '' ? $_POST["e_min"] : (isset($_GET["e_min"]) ? $_GET["e_min"] : '');
	$date_use_tf			= isset($_POST["date_use_tf"]) && $_POST["date_use_tf"] !== '' ? $_POST["date_use_tf"] : (isset($_GET["date_use_tf"]) ? $_GET["date_use_tf"] : '');
	
	$reg_date_ymd			= isset($_POST["reg_date_ymd"]) && $_POST["reg_date_ymd"] !== '' ? $_POST["reg_date_ymd"] : (isset($_GET["reg_date_ymd"]) ? $_GET["reg_date_ymd"] : '');
	$reg_date_time		= isset($_POST["reg_date_time"]) && $_POST["reg_date_time"] !== '' ? $_POST["reg_date_time"] : (isset($_GET["reg_date_time"]) ? $_GET["reg_date_time"] : '');
	
	if ($date_use_tf <> "N") {
		$start_date = $s_date." ".$s_hour.":".$s_min.":00";
		$end_date = $e_date." ".$e_hour.":".$e_min.":00";
		$date_use_tf = "Y";
	} else {
		$start_date = "";
		$end_date = "";
	}

//echo "/".$flag01."*".$file_nm."!";

	if ($b_code == "") $b_code = "B_1_1";
	
	$nPage				= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);

	$cate_01			= SetStringToDB($cate_01);
	$cate_02			= SetStringToDB($cate_02);
	$cate_03			= SetStringToDB($cate_03);
	$cate_04			= SetStringToDB($cate_04);

	$writer_id		= SetStringToDB($writer_id);
	$writer_nm		= SetStringToDB($writer_nm);
	$writer_pw		= SetStringToDB($writer_pw);
	$email				= SetStringToDB($email);

	$title				= SetStringToDB($title);
	$keyword			= SetStringToDB($keyword);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	$ref_ip = $_SERVER['REMOTE_ADDR'];

#====================================================================
# DML Process
#====================================================================

	require "../../_common/board/dml.php";

	$arr_rs_files = array();


	if ($mode == "AU") {

		$reply = SetStringToDB($reply);
		$result = updateQnaAnswer($conn, $reply, $s_adm_no, $reply_state, $b_code, $b_no);
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&b_code=".$b_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;


		if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_read.php<?=$strParam?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($mode == "D") {
		require "../../_common/board/del.php";
	}

	if ($mode == "T") {
		updateBannerUseTF($conn, $use_tf, $s_adm_no, $b_code, $b_no);
	}
	

	if ($mode == "R") {

		$arr_rs = selectBoard($conn, $b_code, $b_no);

		$rs_b_no						= trim($arr_rs[0]["B_NO"]); 
		$rs_b_re						= trim($arr_rs[0]["B_RE"]); 
		$rs_b_po						= trim($arr_rs[0]["B_PO"]); 
		$rs_b_code					= trim($arr_rs[0]["B_CODE"]); 
		$rs_secret_tf				= trim($arr_rs[0]["SECRET_TF"]); 

		$rs_title						= trim($arr_rs[0]["TITLE"]);

	}

	if ($mode == "S") {

#====================================================================
# Board Config Start
#====================================================================
		require "../../_common/board/read.php";
#====================================================================
# Board Config End
#====================================================================

	} else {
		$rs_writer_nm = $_SESSION['s_adm_nm'];
		$rs_email	  = $_SESSION['s_adm_email'];
	}


	if ($rs_reg_date <> "") {
		$reg_date_ymd = left($rs_reg_date,10);
		$reg_date_time = right($rs_reg_date,8);
	} else {
		$reg_date_ymd = date("Y-m-d",strtotime("0 day"));
		$reg_date_time = date("H:i:s",strtotime("0 day"));
	}

	if ($rs_ref_tf == "") $rs_ref_tf = "B";

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&b_code=".$b_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {

		if ($b_board_type == "QNA") { 
			$board_go_page="qna_list.php";
		} else {
			$board_go_page="board_list.php";
		}

?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "<?=$board_go_page?><?=$strParam?>";
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
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../../_common/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/board.js"></script>
<script language="javascript" type="text/javascript">
<!--

function js_list() {
	document.location = "qna_list.php<?=$strParam?>";
}

function js_save() {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";

	if(document.frm.title.value==""){
		alert('제목을 입력해주세요.');
		document.frm.title.focus();
		return;
	}

	if(document.frm.writer_nm.value==""){
		alert('작성자를 입력해주세요.');
		document.frm.writer_nm.focus();
		return;
	}
	
	if ($('#date_use_tf').is(":checked") == false) {

		if(document.frm.s_date.value==""){
			alert('게시 시작일을 선택해주세요.');
			document.frm.s_date.focus();
			return;
		}
		
		if(document.frm.e_date.value==""){
			alert('게시 종료일을 선택해주세요.');
			document.frm.e_date.focus();
			return;
		}
	}

	//return;

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}
/*
	if (document.frm.rd_ref_tf == null) {

	} else {
		if (frm.rd_ref_tf[0].checked == true) {
			frm.ref_tf.value = "A";
		} else if (frm.rd_ref_tf[1].checked == true) {
			frm.ref_tf.value = "C";
		} else {
			frm.ref_tf.value = "B";
		}
	}
*/

	if (document.frm.rd_comment_tf == null) {
		//alert(document.frm.rd_comment_tf);
	} else {
		if (frm.rd_comment_tf[0].checked == true) {
			frm.comment_tf.value = "Y";
		} else {
			frm.comment_tf.value = "N";
		}
	}

	if (isNull(b_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.b_no.value = frm.b_no.value;
	}

<? if ($mode == "R") {?>
		frm.mode.value = "IR";
<? }?>

<? if ($b_html_tf == "Y") { ?>
	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.
<? }?>

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER["PHP_SELF"]?>";
	frm.submit();

}

function js_answer() {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";
	
	frm.reply_state.value = "Y";

	if (isNull(b_no)) {
		return;
	} else {
		frm.mode.value = "AU";
		frm.b_no.value = b_no;
	}

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER["PHP_SELF"]?>";
	frm.submit();

}

function js_view(seq) {

	var frm = document.frm;
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER["PHP_SELF"]?>";
	frm.submit();
}

function file_change(file) { 
	document.getElementById("file_nm").value = file; 
}


function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "<?=$_SERVER["PHP_SELF"]?>";
		frm.submit();
	}
}

/**
* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
*/
function js_exfileView(idx) {
	
	// fake input 추가 때문에 이렇게 처리 합니다.
	idx++;

	var obj = document.frm["file_flag[]"][idx];
	
	if (obj.selectedIndex == 2) {
		document.frm["file_nm[]"][idx].style.visibility = "visible"; 
	} else { 
		document.frm["file_nm[]"][idx].style.visibility = "hidden"; 
	}	
}

function js_fileView(obj,idx) {
	
	var frm = document.frm;
	
	if (idx == 01) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change").style.display = "inline";
		} else {
			document.getElementById("file_change").style.display = "none";
		}
	}
	if (idx == 02) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change2").style.display = "inline";
		} else {
			document.getElementById("file_change2").style.display = "none";
		}
	}
}
//-->
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="b_no" value="<?=$b_no?>" />
<input type="hidden" name="b_code" value="<?=$b_code?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="con_cate_01" value="<?=$con_cate_01?>" />

<input type="hidden" name="b_po" value="<?=$rs_b_po?>">
<input type="hidden" name="b_re" value="<?=$rs_b_re?>">

<input type="hidden" name="reply_state" value="">
<input type="hidden" name="comment_tf" value="N"> 

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col width="12%" />
								<col width="38%" />
								<col width="12%" />
								<col width="38%" />
							</colgroup>
							<tr>
								<th scope="row">제목</th>
								<td colspan="3">

									[<?=getDcodeName($conn, "MOJIB_TYPE", $rs_cate_01)?>]&nbsp;&nbsp;&nbsp;
									<?=$rs_title?>&nbsp;&nbsp;&nbsp;
									<? if ($b_secret_tf != "N") { ?>&nbsp; (비밀글)<? } ?>
								</td>
							</tr>

							<tr>
								<th scope="row">작성자</th>
								<td colspan="3"><?=$rs_writer_nm?></td>
							</tr>

							<tr> 
								<th scope="row">내용</th>
								<td colspan="3" style="padding: 10px 10px 10px 10px">
									<?=nl2br($rs_contents)?>
								</td>
							</tr>

							<tr> 
								<th scope="row">답변</th>
								<td colspan="3" style="padding: 10px 10px 10px 10px">
								<?
									// ================================================================== 수정 부분
								?>
									<span class="fl" style="padding-left:0px;width:90%;height:400px;">
										<textarea name="reply" id="reply"  style="padding-left:0px;width:100%;height:350px;"><?=$rs_reply?></textarea>
									</span>
								<?
									// ================================================================== 수정 부분
								?>
								</td>
							</tr>

							<tr> 
								<th scope="row">답변여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_reply_state" value="N" <? if (($rs_reply_state =="N") || ($rs_reply_state =="")) echo "checked"; ?>> 답변대기<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_reply_state" value="Y" <? if ($rs_reply_state =="Y")echo "checked"; ?>> 답변완료
									<input type="hidden" name="reply_state" id="reply_state" value="<?= $rs_reply_state ?>">
								</td>
							</tr>
							<tr> 
								<th scope="row">답변일</th>
								<td colspan="3">
									<?=$rs_reply_date?>
								</td>
							</tr>
						</table>
						</div>

						<div class="btn_wrap">

						<? if ($sPageRight_I == "Y" && $b_no <> "" && $b_reply_tf == "Y") { ?>
						<!--
						<li><a href="javascript:js_reply();"><img src="../images/btn/btn_re.gif" alt="답변" /></a></li>
						-->
						<? } ?>
						
							<a href="javascript:js_list();" class="button type02">목록</a>

						<? 
							if ($_SESSION['s_adm_no'] == $rs_reg_adm || $sPageRight_I == "Y") {
								echo '<button type="button" class="button" onClick="js_reply_save();">확인</button>';
							}
						?>
				</div>
			</div>
		</div>
	</div>
</form>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">
<!--
<? if ($b_html_tf == "Y") { ?>

	var oEditors = [];

	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "reply",
		sSkinURI: "../../_common/SE2-2.8.2.3/SmartEditor2Skin.php",
		htParams : {
			bUseToolbar : true, 
			fOnBeforeUnload : function(){ 
				// alert('야') 
			},
			fOnAppLoad : function(){ 
			// 이 부분에서 FOCUS를 실행해주면 됩니다. 
			this.oApp.exec("EVENT_EDITING_AREA_KEYDOWN", []); 
			this.oApp.setIR(""); 
			//oEditors.getById["ir1"].exec("SET_IR", [""]); 
			}
		}, 
		fCreator: "createSEditor"
	});

<? } ?>

function js_reply_save() {
	
	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.

	if (document.frm.rd_reply_state == null) {
		//alert(document.frm.rd_comment_tf);
	} else {
		if (frm.rd_reply_state[0].checked == true) {
			reply_state = "N";
		} else {
			reply_state = "Y";
		}
	}

	var b = "<?=$b_code?>";
	var bn = "<?=$b_no?>";
	var reply = $("#reply").val();

	var mode = "ADM_REPLY_SAVE";

	var request = $.ajax({
		url:"/_common/ajax_board_dml.php",
		type:"POST",
		data:{mode:mode, b:b, bn:bn, reply:reply, reply_state:reply_state},
		dataType:"json"
	});

	request.done(function(data) {
		alert("처리 되었습니다.");
		document.location = "qna_list.php?b_code=<?=$b_code?>&con_cate_01=<?=$con_cate_01?>&nPage=<?=$nPage?>&search_field=<?=$search_field?>&search_str=<?=$search_str?>";
	});

}

//-->
</SCRIPT>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>