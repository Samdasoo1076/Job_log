<?session_start();?>
<?
# =============================================================================
# File Name    : enterinfo_write.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-11-19
# Modify Date  : 2021-05-07
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
	$menu_right = "EN001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/enter/enter.php";

#====================================================================
# DML Process
#====================================================================

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$e_no						= isset($_POST["e_no"]) && $_POST["e_no"] !== '' ? $_POST["e_no"] : (isset($_GET["e_no"]) ? $_GET["e_no"] : '');
	$e_type					= isset($_POST["e_type"]) && $_POST["e_type"] !== '' ? $_POST["e_type"] : (isset($_GET["e_type"]) ? $_GET["e_type"] : '');
	$e_year					= isset($_POST["e_year"]) && $_POST["e_year"] !== '' ? $_POST["e_year"] : (isset($_GET["e_year"]) ? $_GET["e_year"] : '');
	$e_title				= isset($_POST["e_title"]) && $_POST["e_title"] !== '' ? $_POST["e_title"] : (isset($_GET["e_title"]) ? $_GET["e_title"] : '');
	$e_pdf					= isset($_POST["e_pdf"]) && $_POST["e_pdf"] !== '' ? $_POST["e_pdf"] : (isset($_GET["e_pdf"]) ? $_GET["e_pdf"] : '');
	$e_img					= isset($_POST["e_img"]) && $_POST["e_img"] !== '' ? $_POST["e_img"] : (isset($_GET["e_img"]) ? $_GET["e_img"] : '');
	$disp_seq				= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$apply_tf				= isset($_POST["apply_tf"]) && $_POST["apply_tf"] !== '' ? $_POST["apply_tf"] : (isset($_GET["apply_tf"]) ? $_GET["apply_tf"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date				= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$f							= isset($_POST["f"]) && $_POST["f"] !== '' ? $_POST["f"] : (isset($_GET["f"]) ? $_GET["f"] : '');
	$s							= isset($_POST["s"]) && $_POST["s"] !== '' ? $_POST["s"] : (isset($_GET["s"]) ? $_GET["s"] : '');

	$chk						= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	$con_sido				= isset($_POST["con_sido"]) && $_POST["con_sido"] !== '' ? $_POST["con_sido"] : (isset($_GET["con_sido"]) ? $_GET["con_sido"] : '');
	$con_sigungu		= isset($_POST["con_sigungu"]) && $_POST["con_sigungu"] !== '' ? $_POST["con_sigungu"] : (isset($_GET["con_sigungu"]) ? $_GET["con_sigungu"] : '');
	$con_type				= isset($_POST["con_type"]) && $_POST["con_type"] !== '' ? $_POST["con_type"] : (isset($_GET["con_type"]) ? $_GET["con_type"] : '');
	$con_confirm_tf	= isset($_POST["con_confirm_tf"]) && $_POST["con_confirm_tf"] !== '' ? $_POST["con_confirm_tf"] : (isset($_GET["con_confirm_tf"]) ? $_GET["con_confirm_tf"] : '');
	$con_year				= isset($_POST["con_year"]) && $_POST["con_year"] !== '' ? $_POST["con_year"] : (isset($_GET["con_year"]) ? $_GET["con_year"] : '');
	$con_yyyy				= isset($_POST["con_yyyy"]) && $_POST["con_yyyy"] !== '' ? $_POST["con_yyyy"] : (isset($_GET["con_yyyy"]) ? $_GET["con_yyyy"] : '');
	$con_app_type		= isset($_POST["con_app_type"]) && $_POST["con_app_type"] !== '' ? $_POST["con_app_type"] : (isset($_GET["con_app_type"]) ? $_GET["con_app_type"] : '');
	$con_state			= isset($_POST["con_state"]) && $_POST["con_state"] !== '' ? $_POST["con_state"] : (isset($_GET["con_state"]) ? $_GET["con_state"] : '');

	$flag01					= isset($_POST["flag01"]) && $_POST["flag01"] !== '' ? $_POST["flag01"] : (isset($_GET["flag01"]) ? $_GET["flag01"] : '');
	$flag02					= isset($_POST["flag02"]) && $_POST["flag02"] !== '' ? $_POST["flag02"] : (isset($_GET["flag02"]) ? $_GET["flag02"] : '');

	$e_pdf_nm				= isset($_POST["e_pdf_nm"]) && $_POST["e_pdf_nm"] !== '' ? $_POST["e_pdf_nm"] : (isset($_GET["e_pdf_nm"]) ? $_GET["e_pdf_nm"] : '');
	$old_e_pdf_nm		= isset($_POST["old_e_pdf_nm"]) && $_POST["old_e_pdf_nm"] !== '' ? $_POST["old_e_pdf_nm"] : (isset($_GET["old_e_pdf_nm"]) ? $_GET["old_e_pdf_nm"] : '');

	$e_img_nm				= isset($_POST["e_img_nm"]) && $_POST["e_img_nm"] !== '' ? $_POST["e_img_nm"] : (isset($_GET["e_img_nm"]) ? $_GET["e_img_nm"] : '');
	$old_e_img_nm		= isset($_POST["old_e_img_nm"]) && $_POST["old_e_img_nm"] !== '' ? $_POST["old_e_img_nm"] : (isset($_GET["old_e_img_nm"]) ? $_GET["old_e_img_nm"] : '');

#====================================================================
 $savedir1 = $g_physical_path."upload_data/enter";
#====================================================================

	$result = false;

	$mode							= SetStringToDB($mode);
	$e_type						= SetStringToDB($e_type);
	$e_year						= SetStringToDB($e_year);
	$e_title					= SetStringToDB($e_title);

	$f								= SetStringToDB($f);
	$s								=	SetStringToDB($s);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	//$max_allow_file_size = $allow_file_size * 1024 * 1024;

	if ($mode == "I") {
		
		if ($_FILES["e_pdf"]["name"] <> ""){
			$e_pdf					= upload($_FILES["e_pdf"], $savedir1, 320 , array('pdf'));
			$e_pdf_nm			= $_FILES["e_pdf"]["name"];
		}
		
		if ($_FILES["e_img"]["name"] <> ""){
			$e_img					= upload($_FILES["e_img"], $savedir1, 320 , array('gif', 'jpeg', 'jpg','png'));
			$e_img_nm			= $_FILES["e_img"]["name"];
		}
		
		$arr_data = array("E_TYPE"=>$e_type,
											"E_YEAR"=>$e_year,
											"E_TITLE"=>$e_title,
											"E_PDF"=>$e_pdf,
											"E_PDF_NM"=>$e_pdf_nm,
											"E_IMG"=>$e_img,
											"E_IMG_NM"=>$e_img_nm,
											"HIT_CNT"=>0,
											"DISP_SEQ"=>0,
											"APPLY_TF"=>$apply_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);
		$new_e_no	= insertEnterInfo($conn, $arr_data);
		$result =$new_e_no;
		$result_log	= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '입학자료 등록 (제목 : '.$e_type.'-'.$e_year.'-'.$e_title.') ', 'Insert');
	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$e_pdf			= upload($_FILES["e_pdf"], $savedir1, 100 , array('pdf'));
				$e_pdf_nm		= $_FILES["e_pdf"]["name"];
			break;
			case "keep" :
				$e_pdf			= $e_pdf_nm;
				$e_pdf_nm		= $old_e_pdf_nm;
			break;
			case "delete" :
				$e_pdf			= "";
				$e_pdf_nm		= "";
			break;
			case "update" :
				$e_pdf			= upload($_FILES["e_pdf"], $savedir1, 100 , array('pdf'));
				$e_pdf_nm		= $_FILES["e_pdf"]["name"];
			break;
		}

		switch ($flag02) {
			case "insert" :
				$e_img				= upload($_FILES["e_img"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				$e_img_nm			= $_FILES["e_img"]["name"];
			break;
			case "keep" :
				$e_img				= $e_img_nm;
				$e_img_nm				= $old_e_img_nm;
			break;
			case "delete" :
				$e_img				= "";
				$e_img_nm			= "";
			break;
			case "update" :
				$e_img				= upload($_FILES["e_img"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				$e_img_nm			= $_FILES["e_img"]["name"];
			break;
		}

		$arr_data = array("E_TYPE"=>$e_type,
											"E_YEAR"=>$e_year,
											"E_TITLE"=>$e_title,
											"E_PDF"=>$e_pdf,
											"E_PDF_NM"=>$e_pdf_nm,
											"E_IMG"=>$e_img,
											"E_IMG_NM"=>$e_img_nm,
											"DISP_SEQ"=>0,
											"APPLY_TF"=>$apply_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateEnterInfo($conn, $arr_data, $e_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '입학자료 수정 (제목 : '.$e_type.'-'.$e_year.'-'.$e_title.') ', 'Update');
	}

	if ($mode == "D") {
		$result = deleteEnterInfo($conn, $_SESSION['s_adm_no'], (int)$e_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '입학자료 삭제 처리 (제목 : '.$e_type.'-'.$e_year.'-'.$e_title.') ', 'Delete');
	}

	$rs_e_no								= "";
	$rs_e_type							= "";
	$rs_e_year							= "";
	$rs_e_title							= "";
	$rs_e_pdf								= "";
	$rs_e_pdf_nm						= "";
	$rs_e_img								= "";
	$rs_e_img_nm						= "";

	$rs_hit_cnt							= "";
	$rs_apply_tf						= "";
	$rs_use_tf							= "";
	$rs_del_tf							= "";
	$rs_reg_adm							= "";
	$rs_reg_date						= "";
	$rs_up_adm							= "";
	$rs_up_date							= "";

	if ($mode == "S") {

		$arr_rs = selectEnterInfo($conn, (int)$e_no);

		$rs_e_no								= trim($arr_rs[0]["E_NO"]); 
		$rs_e_type							= trim($arr_rs[0]["E_TYPE"]); 
		$rs_e_year							= SetStringFromDB($arr_rs[0]["E_YEAR"]); 
		$rs_e_title							= SetStringFromDB($arr_rs[0]["E_TITLE"]); 
		$rs_e_pdf								= trim($arr_rs[0]["E_PDF"]); 
		$rs_e_pdf_nm						= trim($arr_rs[0]["E_PDF_NM"]); 
		$rs_e_img								= trim($arr_rs[0]["E_IMG"]); 
		$rs_e_img_nm						= trim($arr_rs[0]["E_IMG_NM"]); 

		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_apply_tf						= trim($arr_rs[0]["APPLY_TF"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm							= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date							= trim($arr_rs[0]["UP_DATE"]); 
 
		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '입학자료 조회 (제목 : '.$e_type.'-'.$e_year.'-'.$e_title.') ', 'Read');

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&f=".$f."&s=".$s."&order_field=".$order_field."&order_str=".$order_str;
		$strParam = $strParam."&con_yyyy=".$con_yyyy."&con_type=".$con_type."&con_app_type=".$con_app_type."&con_state=".$con_state;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "enterinfo_list.php<?=$strParam?>";
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
	frm.action = "enterinfo_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var e_no = "<?=$e_no?>";

	if (frm.e_type.value == "" ) {
		alert('모집유형을 선택해주세요.');
		frm.e_type.focus();
		return ;		
	}

	if (frm.e_year.value == "") {
		alert('모집년도를 선택해주세요.');
		frm.e_year.focus();
		return ;		
	}

	if (document.frm.rd_url_type == null) {
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
		}
	}

	if (document.frm.rd_apply_tf == null) {
	} else {
		if (frm.rd_apply_tf[0].checked == true) {
			frm.apply_tf.value = "Y";
		} else {
			frm.apply_tf.value = "N";
		}
	}

	if (document.frm.rd_use_tf == null) {
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

	if (isNull(e_no)) {
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
<input type="hidden" name="e_no" value="<?=$e_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="f" value="<?=$f?>">
<input type="hidden" name="s" value="<?=$s?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>입학 자료</h4></div>
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
									<th>유형 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><?= makeSelectBox($conn,"MOJIB","e_type",200,"선택","",$rs_e_type)?></td>
								</tr>
								<tr>
									<th>모집년도 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><?= makeSelectBox($conn,"MOJIB_YEAR","e_year",200,"선택","",$rs_e_year)?></td>
								</tr>
								<tr>
									<th>입시자료 제목</th>
									<td colspan="3">
									<input type="text" name="e_title" value="<?=$rs_e_title?>" style="width:80%" />
									</td>
								</tr>
								<tr>
									<th scope="row">PDF 파일</th>
									<td colspan="3">
									<?
										if (strlen($rs_e_pdf) > 3) {
									?>
										<span class="tbl_txt"><a href="../../_common/new_download_file.php?menu=enterinfo&e_no=<?=$rs_e_no?>&field=e_pdf" target="_blank"><?=$rs_e_pdf_nm?></a></span>
										&nbsp;&nbsp;
										<select name="flag01" style="width:100px;" onchange="javascript:js_fileView(this,'01')">
											<option value="keep">유지</option>
											<option value="update">수정</option>
											<option value="delete">삭제</option>
										</select>

										<input type="hidden" name="e_pdf_nm" value="<?= $rs_e_pdf?>">
										<input type="hidden" name="old_e_pdf_nm" value="<?= $rs_e_pdf_nm?>">
										<div id="file_change01" style="display:none;">
											<input type="file" name="e_pdf" size="40%" />
										</div>

									<?
										} else {
									?>
										<input type="file" size="40%" name="e_pdf"><span class="txt_c02">PDF 파일</span>
										<input type="hidden" name="e_pdf_nm" value="">
										<input TYPE="hidden" name="flag01" value="insert">
									<?
										}	
									?>
									</td>
								</tr>
								<tr>
									<th scope="row">IMG 파일 (306*389)</th>
									<td colspan="3">
									<?
										if (strlen($rs_e_img) > 3) {
									?>
										<span class="tbl_txt"><a href="../../_common/new_download_file.php?menu=enterinfo&e_no=<?=$rs_e_no?>&field=e_img" target="_blank"><?=$rs_e_img_nm?></a></span>
										&nbsp;&nbsp;
										<select name="flag02" style="width:100px;" onchange="javascript:js_fileView(this,'02')">
											<option value="keep">유지</option>
											<option value="update">수정</option>
											<option value="delete">삭제</option>
										</select>
						
										<input type="hidden" name="e_img_nm" value="<?= $rs_e_img?>">
										<input type="hidden" name="old_e_img_nm" value="<?= $rs_e_img_nm?>">
										<div id="file_change02" style="display:none;">
											<input type="file" name="e_img" size="40%" />
										</div>

									<?
										} else {
									?>
										<input type="file" size="40%" name="e_img"><span class="txt_c02">이미지 파일 확장자명('gif', 'jpeg', 'jpg','png')</span>
										<input type="hidden" name="e_img_nm" value="">
										<input TYPE="hidden" name="flag02" value="insert"> 
									<?
										}	
									?>
									</td>
								</tr>
								<tr>
									<th>신청자료 노출여부</th>
									<td colspan="3">
										<input type="radio" id="all_down" name="rd_apply_tf" value="Y" <? if (($rs_apply_tf =="Y") || ($rs_apply_tf =="")) echo "checked"; ?> class="radio" /> <label for="all_down">노출</label>&nbsp;&nbsp;
										<input type="radio" id="secret_down" name="rd_apply_tf" value="N" <? if ($rs_apply_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret_down">숨김</label>
										<input type="hidden" name="apply_tf" value="<?= $rs_apply_tf ?>" />
									</td>
								</tr>
								<tr>
									<th>열람 공개여부</th>
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
						<? if ($e_no == "") {?>
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

<input type="hidden" name="e_no" value="<?=$e_no?>" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="f" value="<?=$f?>" />
<input type="hidden" name="s" value="<?=$s?>" />
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