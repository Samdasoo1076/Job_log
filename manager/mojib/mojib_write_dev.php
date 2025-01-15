<?session_start();?>
<?
# =============================================================================
# File Name    : mojib_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-10-08
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
	$menu_right = "MJ001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/mojib/mojib.php";

#====================================================================
# DML Process
#====================================================================

	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$item_no					= $_POST['item_no']!=''?$_POST['item_no']:$_GET['item_no']; //PDF 페이지 개별 항목 DELETE
	$m_no							= $_POST['m_no']!=''?$_POST['m_no']:$_GET['m_no'];
	$m_type						= $_POST['m_type']!=''?$_POST['m_type']:$_GET['m_type'];
	$m_title					= $_POST['m_title']!=''?$_POST['m_title']:$_GET['m_title'];
	$m_year						= $_POST['m_year']!=''?$_POST['m_year']:$_GET['m_year'];

	$flag01						= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$flag02						= $_POST['flag02']!=''?$_POST['flag02']:$_GET['flag02'];
	
	$m_pdf						= $_POST['m_pdf']!=''?$_POST['m_pdf']:$_GET['m_pdf'];
	$old_m_pdf				= $_POST['old_m_pdf']!=''?$_POST['old_m_pdf']:$_GET['old_m_pdf'];
	$old_m_pdf_name		= $_POST['old_m_pdf_name']!=''?$_POST['old_m_pdf_name']:$_GET['old_m_pdf_name'];
	$m_hwp						= $_POST['m_hwp']!=''?$_POST['m_hwp']:$_GET['m_hwp'];
	$old_m_hwp				= $_POST['old_m_hwp']!=''?$_POST['old_m_hwp']:$_GET['old_m_hwp'];
	$old_m_hwp_name		= $_POST['old_m_hwp_name']!=''?$_POST['old_m_hwp_name']:$_GET['old_m_hwp_name'];
  
	$disp_seq					= $_POST['disp_seq']!=''?$_POST['disp_seq']:$_GET['disp_seq'];
	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$reg_date					= $_POST['reg_date']!=''?$_POST['reg_date']:$_GET['reg_date'];

	$md_no						= $_POST['md_no']!=''?$_POST['md_no']:$_GET['md_no'];
	$md_num						= $_POST['md_num']!=''?$_POST['md_num']:$_GET['md_num'];
	$md_title					= $_POST['md_title']!=''?$_POST['md_title']:$_GET['md_title'];
	$md_disp_seq			= $_POST['md_disp_seq']!=''?$_POST['md_disp_seq']:$_GET['md_disp_seq'];

	$con_m_type				= $_POST['con_m_type']!=''?$_POST['con_m_type']:$_GET['con_m_type'];
	$con_m_year				= $_POST['con_m_year']!=''?$_POST['con_m_year']:$_GET['con_m_year'];


	$arr_rs_detail = array();
#====================================================================
 $savedir1 = $g_physical_path."upload_data/mojib";
#====================================================================

	$result = false;

	$mode							= SetStringToDB($mode);
	$m_type						= SetStringToDB($m_type);
	$m_title					= SetStringToDB($m_title);
	$m_year						= SetStringToDB($m_year);

	$search_field			= SetStringToDB($search_field);
	$search_str				=	SetStringToDB($search_str);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$max_allow_file_size = $allow_file_size * 1024 * 1024;

	if ($mode == "I") {
		
		if ($_FILES["m_pdf"]["name"] <> ""){
			$m_pdf					= upload($_FILES["m_pdf"], $savedir1, 100 , array('pdf'));
			$m_pdf_name			= $_FILES["m_pdf"]["name"];
		}
		
		if ($_FILES["m_hwp"]["name"] <> ""){
			$m_hwp					= upload($_FILES["m_hwp"], $savedir1, 100 , array('hwp'));
			$m_hwp_name			= $_FILES["m_hwp"]["name"];
		}
		
		$arr_data = array("M_TYPE"=>$m_type,
											"M_TITLE"=>$m_title,
											"M_YEAR"=>$m_year,
											"M_PDF"=>$m_pdf,
											"M_PDF_NAME"=>$m_pdf_name,
											"M_HWP"=>$m_hwp,
											"M_HWP_NAME"=>$m_hwp_name,
											"HIT_CNT"=>0,
											"DISP_SEQ"=>0,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);
		$new_m_no	= insertMojib($conn, $arr_data);
		$result =$new_m_no;

		if (sizeof($md_num) > 0) {
			for ($j = 0 ; $j < sizeof($md_num); $j++) {

				$arr_data_detail = array("M_NO"=>$new_m_no,
																"MD_NUM"=>$md_num[$j],
																"MD_TITLE"=>$md_title[$j],
																"MD_DISP_SEQ"=>0,
																"USE_TF"=>$use_tf,
																"REG_ADM"=>$_SESSION["s_adm_no"],
																"UP_DATE"=>$REG_INSERT_DATE
				);
				$result_detail = insertMojibDetail($conn, $arr_data_detail);
			}
		}

		$result_log	= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '모집요강 등록 (제목 : '.$m_type.'-'.$m_year.') ', 'Insert');
    
	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$m_pdf				= upload($_FILES["m_pdf"], $savedir1, 100 , array('pdf'));
				$m_pdf_name		= $_FILES["m_pdf"]["name"];
			break;
			case "keep" :
				$m_pdf				= $old_m_pdf;
				$m_pdf_name		= $old_m_pdf_name;
			break;
			case "delete" :
				$m_pdf				= "";
				$m_pdf_name		= "";
			break;
			case "update" :
				$m_pdf				= upload($_FILES["m_pdf"], $savedir1, 100 , array('pdf'));
				$m_pdf_name		= $_FILES["m_pdf"]["name"];
			break;
		}

		switch ($flag02) {
			case "insert" :
				$m_hwp				= upload($_FILES["m_hwp"], $savedir1, 100 , array('hwp'));
				$m_hwp_name		= $_FILES["m_hwp"]["name"];
			break;
			case "keep" :
				$m_hwp				= $old_m_hwp;
				$m_hwp_name		= $old_m_hwp_name;
			break;
			case "delete" :
				$m_hwp				= "";
				$m_hwp_name		= "";
			break;
			case "update" :
				$m_hwp				= upload($_FILES["m_hwp"], $savedir1, 100 , array('hwp'));
				$m_hwp_name		= $_FILES["m_hwp"]["name"];
			break;
		}

/*
		if ($_FILES["m_pdf"]["name"] <> ""){
			$m_pdf					= upload($_FILES["m_pdf"], $savedir1, 100 , array('pdf'));
		}
		if ($_FILES["m_hwp"]["name"] <> ""){
			$m_hwp					= upload($_FILES["m_hwp"], $savedir1, 100 , array('hwp'));
		}
*/
		$arr_data = array("M_TYPE"=>$m_type,
											"M_TITLE"=>$m_title,
											"M_YEAR"=>$m_year,
											"M_PDF"=>$m_pdf,
											"M_PDF_NAME"=>$m_pdf_name,
											"M_HWP"=>$m_hwp,
											"M_HWP_NAME"=>$m_hwp_name,
											"DISP_SEQ"=>0,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result = updateMojib($conn, $arr_data, $m_no);

		$result = deleteMojibDetail($conn, $_SESSION['s_adm_no'], (int)$m_no);

		if (sizeof($md_num) > 0) {
			for ($j = 0 ; $j < sizeof($md_num); $j++) {

				$arr_data_detail = array("M_NO"=>$m_no,
																"MD_NUM"=>$md_num[$j],
																"MD_TITLE"=>$md_title[$j],
																"MD_DISP_SEQ"=>0,
																"USE_TF"=>$use_tf,
																"REG_ADM"=>$_SESSION["s_adm_no"],
																"UP_DATE"=>$REG_INSERT_DATE
				);
				$result_detail = insertMojibDetail($conn, $arr_data_detail);
			}
		}



		//PDF 페이지 세부정보 update
/*
		if (sizeof($md_no) > 0) {
			for ($j = 0 ; $j < sizeof($md_no); $j++) {

				$arr_data_detail = array("MD_NUM"=>$md_num[$j],
																"MD_TITLE"=>$md_title[$j],
																"MD_DISP_SEQ"=>0,
																"USE_TF"=>$use_tf,
																"REG_ADM"=>$_SESSION["s_adm_no"],
																"UP_DATE"=>$REG_INSERT_DATE
				);
				$result_detail = updateMojibDetail($conn, $arr_data_detail, $md_no[$j]);
			}
		}
*/
		//PDF 페이지 세부정보를 update로 추가시 
/*
		if (sizeof($md_num) > sizeof($md_no)) {
			for ($j = sizeof($md_no) ; $j < sizeof($md_num); $j++) {

				$arr_data_detail = array("M_NO"=>$m_no,
																"MD_NUM"=>$md_num[$j],
																"MD_TITLE"=>$md_title[$j],
																"MD_DISP_SEQ"=>0,
																"USE_TF"=>$use_tf,
																"REG_ADM"=>$_SESSION["s_adm_no"],
																"UP_DATE"=>$REG_INSERT_DATE
				);
				$result_detail = insertMojibDetail($conn, $arr_data_detail);
			}
		}
*/

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '모집요강 수정 (제목 : '.$m_type.'-'.$m_year.') ', 'Update');
	}

	if ($mode == "D") {
		$result = deleteMojib($conn, $_SESSION['s_adm_no'], (int)$m_no);
		$result = deleteMojibDetail($conn, $_SESSION['s_adm_no'], (int)$m_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '모집요강 삭제 처리 (제목 : '.$m_type.'-'.$m_year.') ', 'Delete');
	}

	if ($mode == "ITEM") {
		$result = deleteMojibDetail_1($conn, $_SESSION['s_adm_no'], (int)$item_no, (int)$m_no);
		$mode = "S";
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '모집요강 항목 삭제 처리 (제목 : '.$m_type.'-'.$m_year.') ', 'Delete');
	}

	if ($mode == "S") {

		$arr_rs = selectMojib($conn, (int)$m_no);

		$rs_m_no								= trim($arr_rs[0]["M_NO"]); 
		$rs_m_type							= trim($arr_rs[0]["M_TYPE"]); 
		$rs_m_title							= SetStringFromDB($arr_rs[0]["M_TITLE"]); 
		$rs_m_year							= SetStringFromDB($arr_rs[0]["M_YEAR"]); 
		$rs_m_pdf								= trim($arr_rs[0]["M_PDF"]); 
		$rs_m_pdf_name					= trim($arr_rs[0]["M_PDF_NAME"]); 
		$rs_m_hwp								= trim($arr_rs[0]["M_HWP"]); 
		$rs_m_hwp_name					= trim($arr_rs[0]["M_HWP_NAME"]); 
		$rs_md_num							= trim($arr_rs[0]["MD_NUM"]); 
		$rs_md_title						= SetStringFromDB($arr_rs[0]["MD_TITLE"]); 
 
		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm							= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date							= trim($arr_rs[0]["UP_DATE"]); 
 
		$arr_rs_detail					= selectMojibDetail($conn, $rs_m_no);

		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '모집요강 조회 (제목 : '.$rs_m_type.'-'.$rs_m_year.') ', 'Read');

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;
		$strParam = $strParam."&con_m_type=".$con_m_type."&con_m_year=".$con_m_year;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "mojib_list.php<?=$strParam?>";
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
	frm.action = "mojib_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var m_no = "<?=$m_no?>";
	var md_no = "<?=$md_no?>";

	if (frm.m_type.value == "") {
		alert('모집유형을 선택해주세요.');
		frm.m_type.focus();
		return ;		
	}

	if (frm.m_year.value == "") {
		alert('모집년도를 선택해주세요.');
		frm.m_year.focus();
		return ;		
	}

	var ret = true;
	var chk = 1;  //항목 배열 공백 체크

	$("input[name='md_title[]']").each(function(idx, item){
			if(!$(item).val().trim()){
				ret=false;
				alert(idx+1+"번째 목차 제목을 입력해주세요.");
				$(item).focus();
				return false;
			}
	});

	if(ret==true){
		$("input[name='md_num[]']").each(function(idx, item){
			if(!$(item).val().trim()){
				ret=false;
				alert(idx+1+"번째 연결 페이지 번호를 입력해주세요.");
				$(item).focus();
				return false;
			}
		});
	}

 if(ret!=true){
	 return false;
 }

	if (document.frm.rd_url_type == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
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

	if (isNull(m_no)) {
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

function js_delete_1(ii) {

	var frm = document.frm;
	bDelOK = confirm('항목을 삭제 하시겠습니까?');

	if (bDelOK==true) {
		frm.mode.value = "ITEM";
		frm.item_no.value = ii
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
}

function js_inputAdd(){
	var t= "<div style='padding-bottom:10px'>";
			t +="<span class='tbl_txt'>목차 제목</span>&nbsp;&nbsp;<input type='text' name='md_title[]' style='width:350px;' /> ";
			t +="<span class='tbl_txt'>번호</span>&nbsp;&nbsp;<input type='text' name='md_num[]' style='width:50px;' /> ";
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
<input type="hidden" name="m_no" value="<?=$m_no?>" />
<input type="hidden" name="md_no" value="<?=$md_no?>" />
<input type="hidden" name="item_no" value="<?=$md_no?>" />
<input type="hidden" name="con_m_type" value="<?=$con_m_type?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>모집 요강</h4></div>
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
									<th>모집유형 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><?= makeSelectBox($conn,"MOJIB","m_type",100,"선택","",$rs_m_type)?></td>
								</tr>
								<tr>
									<th>모집년도 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><?= makeSelectBox($conn,"MOJIB_YEAR","m_year",170,"선택","",$rs_m_year)?></td>
								</tr>
								<tr>
									<th>모집요강 제목</th>
									<td colspan="3">
									<input type="text" name="m_title" value="<?=$rs_m_title?>"/>
									</td>
								</tr>
								<tr>
									<th scope="row">PDF 파일</th>
									<td colspan="3">
									<?
										if (strlen($rs_m_pdf) > 3) {
									?>
										<span class="tbl_txt"><a href="../../_common/new_download_file.php?menu=mojib&m_no=<?=$rs_m_no?>&field=m_pdf" target="_blank"><?=$rs_m_pdf_name?></a></span>
										&nbsp;&nbsp;
										<select name="flag01" style="width:100px;" onchange="javascript:js_fileView(this,'01')">
											<option value="keep">유지</option>
											<option value="update">수정</option>
											<option value="delete">삭제</option>
										</select>
						
										<input type="hidden" name="old_m_pdf" value="<?= $rs_m_pdf?>">
										<input type="hidden" name="old_m_pdf_name" value="<?= $rs_m_pdf_name?>">
										<div id="file_change01" style="display:none;">
											<input type="file" name="m_pdf" size="40%" />
										</div>

									<?
										} else {
									?>
										<input type="file" size="40%" name="m_pdf">
										<input type="hidden" name="old_m_pdf" value="">
										<input type="hidden" name="old_m_pdf_name" value="">
										<input TYPE="hidden" name="flag01" value="insert">
									<?
										}	
									?>
									</td>
								</tr>
								<tr>
									<th scope="row">HWP 파일</th>
									<td colspan="3">
									<?
										if (strlen($rs_m_hwp) > 3) {
									?>
										<span class="tbl_txt"><a href="../../_common/new_download_file.php?menu=mojib&m_no=<?=$rs_m_no?>&field=m_hwp" target="_blank"><?=$rs_m_hwp_name?></a></span>
										&nbsp;&nbsp;
										<select name="flag02" style="width:100px;" onchange="javascript:js_fileView(this,'02')">
											<option value="keep">유지</option>
											<option value="update">수정</option>
											<option value="delete">삭제</option>
										</select>
						
										<input type="hidden" name="old_m_hwp" value="<?= $rs_m_hwp?>">
										<input type="hidden" name="old_m_hwp_name" value="<?= $rs_m_hwp_name?>">
										<div id="file_change02" style="display:none;">
											<input type="file" name="m_hwp" size="40%" />
										</div>

									<?
										} else {
									?>
										<input type="file" size="40%" name="m_hwp">
										<input type="hidden" name="old_m_hwp" value="">
										<input type="hidden" name="old_m_hwp_name" value="">
										<input TYPE="hidden" name="flag02" value="insert">
									<?
										}	
									?>
									</td>
								</tr>
							
								<tr>
									<th>PDF 페이지 정보</th>
									<td colspan="3">
										<div class="sp5"></div>
										<button class="button" type="button" onClick="js_inputAdd()" >목차 항목 추가</button>
										<div id="item_add" style="padding-top:10px">
												<?
													$nCnt = 0;
													
													if (sizeof($arr_rs_detail) > 0) {
														
														for ($j = 0 ; $j < sizeof($arr_rs_detail); $j++) {
															$md_no					= trim($arr_rs_detail[$j]["MD_NO"]);
															$md_title				= trim($arr_rs_detail[$j]["MD_TITLE"]);
															$md_num					= trim($arr_rs_detail[$j]["MD_NUM"]);	
												?>
															<div style="padding-bottom:10px">
																<span class="tbl_txt">목차 제목</span>&nbsp;&nbsp;<input type="text" name="md_title[]" value="<?=$md_title?>" style="width:350px;" />
																<span class="tbl_txt">번호</span>&nbsp;&nbsp;<input type="text" name="md_num[]" value="<?=$md_num?>" style="width:50px;" />
																<span class="tbl_txt"><img src="../images/btn_del.gif" onClick="js_inputRemove(this)" style="cursor:hand">
																<input type="hidden" name="md_no[]" value="<?=$md_no?>" /></span>
															</div>
															
												<?			
														}
													} else {
												?>
															<div style="padding-bottom:10px">
																<span class="tbl_txt">목차 제목</span>&nbsp;&nbsp;<input type=text name="md_title[]" style="width:350px;" />
																<span class="tbl_txt">번호</span>&nbsp;&nbsp;<input type="text" name="md_num[]" style="width:50px;" />
															</div>
												<?}?>
											</div>
									</td>
								</tr>

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
						<? if ($m_no == "") {?>
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

<input type="hidden" name="m_no" value="<?=$m_no?>" />
<input type="hidden" name="md_no" value="<?=$md_no?>" />
<input type="hidden" name="item_no" value="<?=$md_no?>" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>" />
<input type="hidden" name="search_str" value="<?=$search_str?>" />
<input type="hidden" name="order_field" value="<?=$order_field?>" />
<input type="hidden" name="order_str" value="<?=$order_str?>" />
<input type="hidden" name="con_m_type" value="<?=$con_m_type?>" />
<input type="hidden" name="con_m_year" value="<?=$con_m_year?>" />

</form>


			</div>

		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>

<script type="text/javascript" src="../js/Sortable.js"></script>

<script>

	new Sortable(item_add, {
		animation: 150,
		ghostClass: 'blue-background-class'
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