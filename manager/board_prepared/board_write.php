<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : board_write.php
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
	require "../../_classes/biz/board/_prepared_board.php";

	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$config_no		= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$main_tf			= $_POST['main_tf']!=''?$_POST['main_tf']:$_GET['main_tf'];
	$use_tf				= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$top_tf				= $_POST['top_tf']!=''?$_POST['top_tf']:$_GET['top_tf'];
	$ref_tf				= $_POST['ref_tf']!=''?$_POST['ref_tf']:$_GET['ref_tf'];
	$new_tf				= $_POST['new_tf']!=''?$_POST['new_tf']:$_GET['new_tf'];
	$b_code				= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no					= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];
	$parent_no		= $_POST['parent_no']!=''?$_POST['parent_no']:$_GET['parent_no'];
	$b_po					= $_POST['b_po']!=''?$_POST['b_po']:$_GET['b_po'];
	$b_re					= $_POST['b_re']!=''?$_POST['b_re']:$_GET['b_re'];
	$cate_01			= $_POST['cate_01']!=''?$_POST['cate_01']:$_GET['cate_01'];
	$cate_02			= $_POST['cate_02']!=''?$_POST['cate_02']:$_GET['cate_02'];
	$cate_03			= $_POST['cate_03']!=''?$_POST['cate_03']:$_GET['cate_03'];
	$cate_04			= $_POST['cate_04']!=''?$_POST['cate_04']:$_GET['cate_04'];

	$secret_tf		= $_POST['secret_tf']!=''?$_POST['secret_tf']:$_GET['secret_tf'];
	
	$writer_nm		= $_POST['writer_nm']!=''?$_POST['writer_nm']:$_GET['writer_nm'];
	$writer_pw		= $_POST['writer_pw']!=''?$_POST['writer_pw']:$_GET['writer_pw'];

	$email				= $_POST['email']!=''?$_POST['email']:$_GET['email'];
	$phone				= $_POST['phone']!=''?$_POST['phone']:$_GET['phone'];
	$homepage			= $_POST['homepage']!=''?$_POST['homepage']:$_GET['homepage'];
	$title				= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$contents			= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
	$link01				= $_POST['link01']!=''?$_POST['link01']:$_GET['link01'];
	$link02				= $_POST['link02']!=''?$_POST['link02']:$_GET['link02'];
	$info_01			= $_POST['info_01']!=''?$_POST['info_01']:$_GET['info_01'];
	$keyword			= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];

	$thumb_img			= $_POST['thumb_img']!=''?$_POST['thumb_img']:$_GET['thumb_img'];
	
	$s_date					= $_POST['s_date']!=''?$_POST['s_date']:$_GET['s_date'];
	$s_hour					= $_POST['s_hour']!=''?$_POST['s_hour']:$_GET['s_hour'];
	$s_min					= $_POST['s_min']!=''?$_POST['s_min']:$_GET['s_min'];
	$e_date					= $_POST['e_date']!=''?$_POST['e_date']:$_GET['e_date'];
	$e_hour					= $_POST['e_hour']!=''?$_POST['e_hour']:$_GET['e_hour'];
	$e_min					= $_POST['e_min']!=''?$_POST['e_min']:$_GET['e_min'];
	$date_use_tf		= $_POST['date_use_tf']!=''?$_POST['date_use_tf']:$_GET['date_use_tf'];

	$reply					= $_POST['reply']!=''?$_POST['reply']:$_GET['reply'];
	$reply_state		= $_POST['reply_state']!=''?$_POST['reply_state']:$_GET['reply_state'];
	
	$con_cate_01		= $_POST['con_cate_01']!=''?$_POST['con_cate_01']:$_GET['con_cate_01'];
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize			= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field		= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str			= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$reg_date_ymd		= $_POST['reg_date_ymd']!=''?$_POST['reg_date_ymd']:$_GET['reg_date_ymd'];
	$reg_date_time	= $_POST['reg_date_time']!=''?$_POST['reg_date_time']:$_GET['reg_date_time'];

	$comment_tf			= $_POST['comment_tf']!=''?$_POST['comment_tf']:$_GET['comment_tf'];

	$flag01					= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$old_file_nm		= $_POST['old_pdf_nm']!=''?$_POST['old_pdf_nm']:$_GET['old_pdf_nm'];
	$old_file_rnm		= $_POST['old_pdf_rnm']!=''?$_POST['old_pdf_rnm']:$_GET['old_pdf_rnm'];

	$file_nm				= $_POST['pdf_nm']!=''?$_POST['pdf_nm']:$_GET['pdf_nm'];
	$file_rnm				= $_POST['pdf_rnm']!=''?$_POST['pdf_rnm']:$_GET['pdf_rnm'];

	$old_thumb_nm		= $_POST['old_thumb_nm']!=''?$_POST['old_thumb_nm']:$_GET['old_thumb_nm'];
	$old_thumb_rnm	= $_POST['old_thumb_rnm']!=''?$_POST['old_thumb_rnm']:$_GET['old_thumb_rnm'];

	$thumb_nm				= $_POST['thumb_nm']!=''?$_POST['thumb_nm']:$_GET['thumb_nm'];
	$thumb_rnm			= $_POST['thumb_rnm']!=''?$_POST['thumb_rnm']:$_GET['thumb_rnm'];

	$file_flag			= $_POST['file_flag']!=''?$_POST['file_flag']:$_GET['file_flag'];

	//echo $date_use_tf;

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
	$nPageSize			= SetStringToDB($nPageSize);

	$cate_01			= SetStringToDB($cate_01);
	$cate_02			= SetStringToDB($cate_02);
	$cate_03			= SetStringToDB($cate_03);
	$cate_04			= SetStringToDB($cate_04);

	$writer_id			= SetStringToDB($writer_id);
	$writer_nm			= SetStringToDB($writer_nm);
	$writer_pw			= SetStringToDB($writer_pw);
	$email				= SetStringToDB($email);

	$phone				= SetStringToDB($phone);
	$homepage			= SetStringToDB($homepage);
	$title				= SetStringToDB($title);
	$contents			= SetStringToDB($contents);
	$recomm				= SetStringToDB($recomm);
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
		$board_go_page="board_list.php";
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
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
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
	document.location = "board_list.php<?=$strParam?>";
}

function js_reply() {
	var frm = document.frm;

	frm.contents.value = "";
	frm.mode.value = "R";
	frm.target = "";
	frm.method = "get";
	frm.action = "board_write.php";
	frm.submit();
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

							<? if (($b_board_type == "NOTICE") || ($b_board_type == "PDS") || ($b_board_type == "RATE") || ($b_board_type == "QNA") || ($b_board_type == "FAQ"))  { ?>
							<tr>
								<th>상단공지</th>
								<td colspan="3">
									<input type="checkbox" class="radio" name="top_tf" value="Y" <? if ($rs_top_tf == "Y" ) echo "checked" ?>/> 상단에 보이기
								</td>
							</tr>
							<? } else { ?>
							<input type="hidden" name="top_tf" value="" />
							<? } ?>

							<? if ($b_code == "B_1_10") { ?>
							<tr>
								<th>기출문제/서식</th>
								<td colspan="3">
									<input type="checkbox" class="radio" name="cate_02" value="Y" <? if ($rs_cate_02 == "Y" ) echo "checked" ?>/> 기출문제/서식
								</td>
							</tr>
							<? } else { ?>
							<input type="hidden" name="cate_02" value="" />
							<? } ?>


							<tr>
								<th scope="row">제목 <img src="../images/img_essen.gif" alt="필수입력" /></th>
								<td colspan="3">
						<?
							// 카테고리 사용 게시판인경우 
							if ($b_board_cate) { 

						?>
									<select name="cate_01" id="cate_01" style="width:180px">
						<?

								$arr_board_cate = explode(";",$b_board_cate);

								if ($b_code == "B_1_7") {
									$str_pcode_name = "HIGH_PROGRAM";
								} else {
									$str_pcode_name = "MOJIB_TYPE";
								}

								for ($k=0; $k<sizeof($arr_board_cate); $k++) {
						?>
										<option value="<?=$arr_board_cate[$k]?>" <? if ($rs_cate_01 == $arr_board_cate[$k]) echo "selected";?> ><?=getDcodeName($conn, $str_pcode_name, $arr_board_cate[$k])?></option>
						<?
								}
						?>
									</select>
									<?//=makeSelectBox($conn,"MOJIB_TYPE","cate_01","150","","",$rs_cate_01); ?>
						<? 
							}
						?>
									<? $str_rs_title = str_replace("\"","&quot;", $rs_title) ?>
									<input type="text" name="title" value="<?=$str_rs_title?>" style="width: 70%;" />
									<? if ($b_secret_tf != "N") { ?>
									&nbsp;<input type="checkbox" class="radio" name="secret_tf" value="Y" <? if ($rs_secret_tf == "Y") echo "checked"?> > 비밀글
									<? } else {  ?>
									<input type="hidden" name="secret_tf" value="<?=$rs_secret_tf?>">
									<? } ?>
								</td>
							</tr>

							<tr>
								<th scope="row">작성자 <img src="../images/img_essen.gif" alt="필수입력" /></th>
								<td>
									<input type="text" name="writer_nm" value="<?=$rs_writer_nm?>" style="width: 120px;" />
								</td>
								<th scope="row">EMAIL</th>
								<td>
									<input type="text" name="email" value="<?=$rs_email?>" style="width: 220px;" />
								</td>
							</tr>

							<tr id="dateclass">
								<th>게시 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
								<td colspan="3" valign="middle">
									<div class="datepickerbox" style="width:120px;" >
										<input type="text" name="s_date" id="s_date" class="datepicker onlyphone" value="<?=$rs_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
									</div>
									<div style="display:inline-block;">
										<?= makeSelectBox($conn,"TIME","s_hour","120","","",$rs_s_hour)?>
										<select name="s_min" id="s_min" style="width:80px">
										<?
											for ($k = 0 ; $k < 60 ; $k++) {
												$str_k = right(("0".$k),2);

												$str_selected = "";
												if ($rs_s_min == $str_k) $str_selected  = "selected";
										?>
											<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
										<?
											}
										?>
										</select>
									</div>
									<span class="tbl_txt">~&nbsp;&nbsp;</span> 
									<div class="datepickerbox" style="width:120px;" >
										<input type="text" name="e_date" id="e_date" class="datepicker onlyphone" value="<?=$rs_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
									</div>
									<div style="display:inline-block;">
										<?= makeSelectBox($conn,"TIME","e_hour","120","","",$rs_e_hour)?>
										<select name="e_min" id="e_min" style="width:80px">
										<?
											for ($k = 0 ; $k < 60 ; $k++) {
												$str_k = right(("0".$k),2);

												$str_selected = "";
												if ($rs_e_min == $str_k) $str_selected  = "selected";
										?>
											<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
										<?
											}
										?>
										</select>
									</div>
									<div style="display:inline-block;height:3px;width:160px;padding-left:20px;vertical-align: middle;">
										<input type="checkbox" class="radio" name="date_use_tf" id="date_use_tf" value="N" <? if (($rs_date_use_tf == "N") || ($rs_date_use_tf == "")) echo "checked"?> > 선택 시 무제한 노출 
									</div>
								</td>
							</tr>

							<? if ($b_board_type == "RATE") { ?>

							<tr>
								<th scope="row">경쟁률 URL</th>
								<td colspan="3">
									<input type="text" name="homepage" value="<?=$rs_homepage?>" style="width: 80%;" />
								</td>
							</tr>

							<tr>
								<th>경쟁률 PDF</th>
								<td colspan="3">
								<?
									if (strlen($rs_file_nm) > 3) {
								?>
									<a href="/_common/new_download_file.php?menu=board&b_code=<?= $b_code ?>&b_no=<?= $b_no ?>&field=file_nm"><?=$rs_file_rnm?></a>
									<select name="flag01" onchange="javascript:js_fileView(this,'01')">
										<option value="keep">유지</option>
										<option value="delete">삭제</option>
										<option value="update">수정</option>
									</select>
								
									<input type="hidden" name="old_pdf_nm" value="<?= $rs_file_nm?>">
									<input type="hidden" name="old_pdf_rnm" value="<?= $rs_file_rnm?>">

									<div id="file_change" style="display:none;">
										<input type="file" id="pdf_nm" name="pdf_nm" />
										<span class="tbl_txt"><span class="txt_c02">※ 확장자 PDF 만 올려 주세요</span></span>
									</div>
								<?
									} else {	
								?>
									<input type="file" id="pdf_nm" name="pdf_nm" />
									<input type="hidden" name="old_pdf_nm" value="">
									<input type="hidden" name="old_pdf_rnm" value="">
									<input TYPE="hidden" name="flag01" value="insert">
									
									<span class="tbl_txt"><span class="txt_c02">※ 확장자 PDF 만 올려 주세요</span></span>
								<?
									}	
								?>
								</td>
							</tr>

							<? } else if ($b_board_type == "RESULT") { ?>

							<tr>
								<th>입시결과 PDF</th>
								<td colspan="3">
								<?
									if (strlen($rs_file_nm) > 3) {
								?>
									<a href="/_common/new_download_file.php?menu=board&b_code=<?= $b_code ?>&b_no=<?= $b_no ?>&field=file_nm"><?=$rs_file_nm?></a>
									<select name="flag01" onchange="javascript:js_fileView(this,'01')">
										<option value="keep">유지</option>
										<option value="delete">삭제</option>
										<option value="update">수정</option>
									</select>
								
									<input type="hidden" name="old_pdf_nm" value="<?= $rs_file_nm?>">
									<input type="hidden" name="old_pdf_rnm" value="<?= $rs_file_rnm?>">

									<div id="file_change" style="display:none;">
										<input type="file" id="pdf_nm" name="pdf_nm" />
										<span class="tbl_txt"><span class="txt_c02">※ 확장자 PDF 만 올려 주세요</span></span>
									</div>
								<?
									} else {	
								?>
									<input type="file" id="pdf_nm" name="pdf_nm" />
									<input type="hidden" name="old_pdf_nm" value="">
									<input type="hidden" name="old_pdf_rnm" value="">
									<input TYPE="hidden" name="flag01" value="insert">
									
									<span class="tbl_txt"><span class="txt_c02">※ 확장자 PDF 만 올려 주세요</span></span>
								<?
									}	
								?>
								</td>
							</tr>
							
							<? } else if ($b_board_type == "MOVIE") { ?>

							<tr>
								<th scope="row">영상URL</th>
								<td colspan="3">
									<input type="text" name="homepage" value="<?=$rs_homepage?>" style="width:300px;" onBlur="js_img()"/> <span class="tbl_txt">ex) Youtube 경로</span>
									<span class="tbl_txt"><font color ="red">유튜브에 링크를 올릴 경우 소스코드는 입력 하지 않으셔도 됩니다.</font> ex) http://youtu.be/u-tX968hWrY</span>
									<div style="padding:10px 10px 10px 0px">
										<b><font color ="red">* 유튜브 동영상 등록 시 꼭 확인 부탁 합니다.</font></b>
										<div style="padding-left:5px">
											<br >1. 유튜브 화면에서 공유 버튼 클릭해 주세요.
											<br >2. 공유 팝업에 링크 옆 복사를 클릭해 주세요.
											<br >3. 복사된 링크를 등록해 주세요.
											<br ><b><font color ="red">4. 하단 YOUTUBE 제공 이미지가 1개 이상 나와야 정상 등록 된 것 입니다.</b></font>
										</div>
									</div>
									<!--Youtube 계정 : dsrcorpwebmaster@gmail.com : qlalfqjsghdsr-->
								</td>
							</tr>
							<tr>
								<th>YOUTUBE 제공 이미지<br>(480x360)</th>
								<td colspan="3">
									<? if ($rs_thumb_img == "") {?>
									<font style="color:rgb(134,141,219)">
									<img src="/manager/images/bg_visual.jpg" name="youtube_img" id="youtube_img" width="150" height="100" /> 
									<?} else { ?>
									<font style="color:rgb(134,141,219)">
									<img src="<?=$rs_thumb_img?>" name="youtube_img" id="youtube_img" width="150" height="100" />
									</font>
									<?}?>
									<input type="hidden" name="thumb_img" value="<?=$rs_thumb_img?>">
									<input type="hidden" name="link01" value="<?=$rs_link01?>">
									<input type="hidden" name="link02" value="<?=$rs_link02?>">

									<input type="radio" class="radio" name="info_01" value="THUMB_IMG" <? if (($rs_info_01 =="THUMB_IMG") || ($rs_info_01 =="")) echo "checked"; ?>> 480x360 사용
								</td>
							</tr>
							<tr>
								<th>YOUTUBE 제공 이미지<br>(1280x720)</th>
								<td colspan="3">
									<? if ($rs_link01 == "") {?>
									<font style="color:rgb(134,141,219)">
									<img src="/manager/images/bg_visual.jpg" name="youtube_img_big" id="youtube_img_big" width="150" height="100" /> 
									<?} else { ?>
									<font style="color:rgb(134,141,219)">
									<img src="<?=$rs_link01?>" name="youtube_img_big" id="youtube_img_big"width="150" height="100" />
									</font>
									<?}?>
									<input type="radio" class="radio" name="info_01" value="LINK01" <? if ($rs_info_01 =="LINK01") echo "checked"; ?>> 1280x720 사용
								</td>
							</tr>
							<!--
							<tr>
								<th scope="row">소스코드</th>
								<td colspan="3">
									<textarea name="cate_03" rows="8" cols="50" /><?=$rs_cate_03?></textarea> 
									<br>
									<span class="tbl_txt">소스코드 등록시 <font color ="red"> width="408" height="268" </font> 화면 사이즈 부분 수정해주세요.</span>
								</td>
							</tr>
							-->
							<tr>
								<th scope="row">썸네일</th>
								<td colspan="3">
								<?
									if (strlen($rs_file_nm) > 3) {
								?>
									<img src="/upload_data/board/<?=$rs_b_code?>/<?=$rs_file_nm?>" width="310" >
									<select name="flag01" style="width:80px;" onchange="javascript:js_fileView(this,'01')">
										<option value="keep">유지</option>
										<option value="delete">삭제</option>
										<option value="update">수정</option>
									</select>
								
									<input type="hidden" name="old_thumb_nm" value="<?= $rs_file_nm?>">
									<input type="hidden" name="old_thumb_rnm" value="<?= $rs_file_rnm?>">

									<div id="file_change" style="display:none;">
											<input type="file" id="file_nm" class="w50per" name="thumb_nm" /><!--<span class="explain">400 * 162</span>-->
									</div>
								<?
									} else {	
								?>
									<input type="file" id="file_nm" class="w50per" name="thumb_nm" /><!--<span class="explain">400 * 162</span>-->
									<input type="hidden" name="old_thumb_nm" value="">
									<input type="hidden" name="old_thumb_rnm" value="">
									<input TYPE="hidden" name="flag01" value="insert">
								<?
									}	
								?>
									<span class="tbl_txt"><span class="txt_c02">※  1280 * 720</span></span>
								</td>
							</tr>

								<!-- 내용 없이 첨부파일만 사용 -->
							<? } else { ?>
							<?	if ($b_html_tf == "Y") { ?>
							<tr> 
								<th scope="row">내용</th>
								<td colspan="3" style="padding: 10px 10px 10px 10px">
								<?
									// ================================================================== 수정 부분
								?>
									<span class="fl" style="padding-left:0px;width:90%;height:500px;">
										<textarea name="contents" id="contents"  style="padding-left:0px;width:100%;height:450px;"><?=$rs_contents?></textarea>
									</span>
								<?
									// ================================================================== 수정 부분
								?>
								</td>
							</tr>
							<?	} else { ?>
							<tr> 
								<th scope="row">내용</th>
								<td colspan="3" style="padding: 10px 10px 10px 15px">
									<textarea style="width: 90%; height:180px" name="contents"><?=$rs_contents?></textarea>
								</td>
							</tr>
							<?	} ?>
							<? } ?>

						<?if($sPageRight_F=="Y"){?>
							<input type="hidden" name="file_flag[]" value=""> 
							<input type="file" name="file_nm[]" value="" style="display:none">
							<input type="hidden" name="old_file_no[]" value="">
							<input type="hidden" name="old_file_nm[]" value="">
							<input type="hidden" name="old_file_rnm[]" value="">

						<?
							if ($b_file_tf == "Y") {

								# ==========================================================================
								# Result List
								# ==========================================================================
								#Cnt = 0
								$f_Cnt = 0;

								if (sizeof($arr_rs_files) > 0) {

									for ($j = 0 ; $j < sizeof($arr_rs_files); $j++) {
										
										//FILE_NO, B_CODE, B_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT 
										$RS_FILE_NO			= trim($arr_rs_files[$j]["FILE_NO"]);
										$RS_B_CODE			= trim($arr_rs_files[$j]["B_CODE"]);
										$RS_B_NO				= trim($arr_rs_files[$j]["B_NO"]);
										$RS_FILE_NM			= trim($arr_rs_files[$j]["FILE_NM"]);
										$RS_FILE_RNM		= trim($arr_rs_files[$j]["FILE_RNM"]);
										$RS_FILE_PATH		= trim($arr_rs_files[$j]["FILE_PATH"]);
										$RS_FILE_SIZE		= trim($arr_rs_files[$j]["FILE_SIZE"]);
										$RS_FILE_EXT		= trim($arr_rs_files[$j]["FILE_EXT"]);
										$RS_HIT_CNT			= trim($arr_rs_files[$j]["HIT_CNT"]);
										
										If ($RS_FILE_NM <> "") {
						?>
							<tr>
								<th scope="row">첨부파일</th>
								<td colspan="3">
									<a href="../../_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>
									&nbsp;&nbsp;
									<select name="file_flag[]" onchange="javascript:js_exfileView('<?=$f_Cnt?>')">
										<option value="keep">유지</option>
										<option value="delete">삭제</option>
										<option value="update">수정</option>
									</select> 
									<input type="hidden" name="old_file_no[]" value="<?=$RS_FILE_NO?>">
									<input type="hidden" name="old_file_nm[]" value="<?=$RS_FILE_NM?>">
									<input type="hidden" name="old_file_rnm[]" value="<?=$RS_FILE_RNM?>">
									<input TYPE="file" NAME="file_nm[]" size="40%" style="visibility:hidden"> <?=$img_size?>
								</td>
							</tr>
						<?
											$f_Cnt = $f_Cnt + 1;
										}
									}
								}
						
								$j = 0;
						
								if ($b_file_tf == "Y" ) {

									$b_file_cnt = $b_file_cnt - $f_Cnt;

									for ($j =0 ; $j < $b_file_cnt ; $j++) { // 총 파일 갯수
						?>
							<tr>
								<th scope="row">첨부파일</th>
								<td colspan="3">
									<input type="hidden" name="file_flag[]" value="insert"> 
									<input type="file" size="40%" name="file_nm[]" id="contract_file"> <?=$img_size?>
								</td>
							</tr>
						<?
									}
								}
							}
						?>

					<?}?>
							<input type="hidden" name="keyword" value="">

							<tr> 
								<th scope="row">노출여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 보이지않기 
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
								</td>
							</tr>

							<!--
							<tr> 
								<th scope="row">댓글사용여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_comment_tf" value="Y" <? if (($rs_comment_tf =="Y") || ($rs_comment_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_comment_tf" value="N" <? if ($rs_comment_tf =="N")echo "checked"; ?>> 사용안함 
									<input type="hidden" name="comment_tf" value="<?= $rs_comment_tf ?>"> 
								</td>
							</tr>
							-->
							<tr> 
								<th scope="row">등록일</th>
								<td colspan="3">
									<div class="datepickerbox" style="width:120px;" >
										<input type="text" name="reg_date_ymd"  class="datepicker onlyphone" value="<?=$reg_date_ymd?>" maxlength="10" autocomplete="off" readonly="1"/>
									</div>
									<div style="display:inline-block;width:160px;">
										<input type="text" name="reg_date_time" value="<?=$reg_date_time?>" style="width:160px;"> 
									</div>
									<span class="tbl_txt"><span class="txt_c02">※  ex) 수정 시 '10:30:00' 포멧으로 입력</span></span>
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
								echo '<button type="button" class="button" onClick="js_save();">확인</button>';
								if ($b_no <> "") {
									if($sPageRight_D=="Y"){
										echo ' <button type="button" class="button type02" onClick="js_delete();">삭제</button>';
									}
 								}
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
		elPlaceHolder: "contents",
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


function js_img(){
//유튜브의 고유ID값 가져와서 THUMB이미지 링크
	
	var frm = document.frm;

	var url = frm.homepage.value;
	var youtube_id = "";
	var url_idx1="https://youtu.be/";
	var url_idx2="https://www.youtube.com/watch?v=";

	if (url.indexOf(url_idx1)>=0){
			youtube_id = url.replace(url_idx1,"");
			var arr_youtube_id = youtube_id.split("?");
			youtube_id = arr_youtube_id[0];
	}
	if (url.indexOf(url_idx2)>=0){
			youtube_id = url.replace(url_idx2,"");
			console.log("1  "+youtube_id);
			var arr_youtube_id = youtube_id.split("=");
			youtube_id = arr_youtube_id[0];
			arr_youtube_id = youtube_id.split("&");
			youtube_id = arr_youtube_id[0];
	}
	
	if (youtube_id == "") {
			alert("Youtube 연결 URL을 다시 한번 확인해 주세요.\nhttps://www.youtube.com/watch?v=... 또는 https://youtu.be/...로\n시작되어야 합니다!")
			frm.y.url.focus();
			return;		
	}
	
	frm.thumb_img.value = "http://img.youtube.com/vi/"+youtube_id+"/mqdefault.jpg";
	frm.link01.value = "http://img.youtube.com/vi/"+youtube_id+"/maxresdefault.jpg";
	frm.link02.value = youtube_id;

	//alert(frm.thumb_img.value);

	frm.youtube_img.src = frm.thumb_img.value;
	frm.youtube_img.width = "150";
	frm.youtube_img.height = "100";
	
	//alert(frm.link01.value);

	frm.youtube_img_big.src = frm.link01.value;
	frm.youtube_img_big.width = "300";
	frm.youtube_img_big.height = "200";


  //alert("만약 잠시 후 이미지가 보이지 않는다면 직접 이미지를 등록하셔야합니다!");

}


//-->
</SCRIPT>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>