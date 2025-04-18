<?session_start();?>
<?
# =============================================================================
# File Name    : banner_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-17
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
	$menu_right = "PO009"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/banner/banner.php";

#====================================================================
# DML Process
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$banner_no				= isset($_POST["banner_no"]) && $_POST["banner_no"] !== '' ? $_POST["banner_no"] : (isset($_GET["banner_no"]) ? $_GET["banner_no"] : '');
	$banner_type			= isset($_POST["banner_type"]) && $_POST["banner_type"] !== '' ? $_POST["banner_type"] : (isset($_GET["banner_type"]) ? $_GET["banner_type"] : '');
	$banner_nm				= isset($_POST["banner_nm"]) && $_POST["banner_nm"] !== '' ? $_POST["banner_nm"] : (isset($_GET["banner_nm"]) ? $_GET["banner_nm"] : '');
	$banner_img				= isset($_POST["banner_img"]) && $_POST["banner_img"] !== '' ? $_POST["banner_img"] : (isset($_GET["banner_img"]) ? $_GET["banner_img"] : '');
	$banner_img_m			= isset($_POST["banner_img_m"]) && $_POST["banner_img_m"] !== '' ? $_POST["banner_img_m"] : (isset($_GET["banner_img_m"]) ? $_GET["banner_img_m"] : '');
	$banner_url				= isset($_POST["banner_url"]) && $_POST["banner_url"] !== '' ? $_POST["banner_url"] : (isset($_GET["banner_url"]) ? $_GET["banner_url"] : '');
	$url_type					= isset($_POST["url_type"]) && $_POST["url_type"] !== '' ? $_POST["url_type"] : (isset($_GET["url_type"]) ? $_GET["url_type"] : '');
	$banner_button		= isset($_POST["banner_button"]) && $_POST["banner_button"] !== '' ? $_POST["banner_button"] : (isset($_GET["banner_button"]) ? $_GET["banner_button"] : '');

	$banner_real_img	= isset($_POST["banner_real_img"]) && $_POST["banner_real_img"] !== '' ? $_POST["banner_real_img"] : (isset($_GET["banner_real_img"]) ? $_GET["banner_real_img"] : '');
	$banner_real_img_m= isset($_POST["banner_real_img_m"]) && $_POST["banner_real_img_m"] !== '' ? $_POST["banner_real_img_m"] : (isset($_GET["banner_real_img_m"]) ? $_GET["banner_real_img_m"] : '');

	$title_nm					= isset($_POST["title_nm"]) && $_POST["title_nm"] !== '' ? $_POST["title_nm"] : (isset($_GET["title_nm"]) ? $_GET["title_nm"] : '');
	$sub_title_nm			= isset($_POST["sub_title_nm"]) && $_POST["sub_title_nm"] !== '' ? $_POST["sub_title_nm"] : (isset($_GET["sub_title_nm"]) ? $_GET["sub_title_nm"] : '');

	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$s_date						= isset($_POST["s_date"]) && $_POST["s_date"] !== '' ? $_POST["s_date"] : (isset($_GET["s_date"]) ? $_GET["s_date"] : '');
	$s_hour						= isset($_POST["s_hour"]) && $_POST["s_hour"] !== '' ? $_POST["s_hour"] : (isset($_GET["s_hour"]) ? $_GET["s_hour"] : '');
	$s_min						= isset($_POST["s_min"]) && $_POST["s_min"] !== '' ? $_POST["s_min"] : (isset($_GET["s_min"]) ? $_GET["s_min"] : '');
	$e_date						= isset($_POST["e_date"]) && $_POST["e_date"] !== '' ? $_POST["e_date"] : (isset($_GET["e_date"]) ? $_GET["e_date"] : '');
	$e_hour						= isset($_POST["e_hour"]) && $_POST["e_hour"] !== '' ? $_POST["e_hour"] : (isset($_GET["e_hour"]) ? $_GET["e_hour"] : '');
	$e_min						= isset($_POST["e_min"]) && $_POST["e_min"] !== '' ? $_POST["e_min"] : (isset($_GET["e_min"]) ? $_GET["e_min"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	$con_cate_01			= isset($_POST["con_cate_01"]) && $_POST["con_cate_01"] !== '' ? $_POST["con_cate_01"] : (isset($_GET["con_cate_01"]) ? $_GET["con_cate_01"] : '');

	$flag01						= isset($_POST["flag01"]) && $_POST["flag01"] !== '' ? $_POST["flag01"] : (isset($_GET["flag01"]) ? $_GET["flag01"] : '');
	$flag02						= isset($_POST["flag02"]) && $_POST["flag02"] !== '' ? $_POST["flag02"] : (isset($_GET["flag02"]) ? $_GET["flag02"] : '');

	$chk							= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

//	$result_date			= $_POST['result_date']!=''?$_POST['result_date']:$_GET['result_date'];
//	$result_time			= $_POST['result_time']!=''?$_POST['result_time']:$_GET['result_time'];

	#====================================================================
	$savedir1 = $g_physical_path."upload_data/banner";
	#====================================================================

	$result = false;


	$mode						= SetStringToDB($mode);
	$banner_nm			= SetStringToDB($banner_nm);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	//$max_allow_file_size = $allow_file_size * 1024 * 1024;


	if ($mode == "I") {

		$banner_img				= upload($_FILES["banner_img"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$banner_img_m			= upload($_FILES["banner_img_m"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		
		$arr_data = array("BANNER_TYPE"=>$banner_type,
											"BANNER_NM"=>$banner_nm,
											"BANNER_IMG"=>$banner_img,
											"BANNER_REAL_IMG"=>$banner_img,
											"BANNER_IMG_M"=>$banner_img_m,
											"BANNER_REAL_IMG_M"=>$banner_img_m,
											"BANNER_URL"=>$banner_url,
											"BANNER_BUTTON"=>$banner_button,
											"TITLE_NM"=>$title_nm,
											"SUB_TITLE_NM"=>$sub_title_nm,
											"USE_TF"=>$use_tf,
											"URL_TYPE"=>$url_type,
											"DISP_SEQ"=>0,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result = insertBanner($conn, $arr_data);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 등록 (제목 : ".$banner_nm.") ", "Insert");

	}
	
	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$banner_img				= upload($_FILES["banner_img"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$banner_img				= $banner_real_img;
			break;
			case "delete" :
				$banner_img				= "";
			break;
			case "update" :
				$banner_img				= upload($_FILES["banner_img"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$banner_img_m				= upload($_FILES["banner_img_m"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$banner_img_m				= $banner_real_img_m;
			break;
			case "delete" :
				$banner_img_m				= "";
			break;
			case "update" :
				$banner_img_m				= upload($_FILES["banner_img_m"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		$arr_data = array("BANNER_TYPE"=>$banner_type,
											"BANNER_NM"=>$banner_nm,
											"BANNER_IMG"=>$banner_img,
											"BANNER_REAL_IMG"=>$banner_real_img,
											"BANNER_IMG_M"=>$banner_img_m,
											"BANNER_REAL_IMG_M"=>$banner_real_img_m,
											"BANNER_URL"=>$banner_url,
											"BANNER_BUTTON"=>$banner_button,
											"TITLE_NM"=>$title_nm,
											"SUB_TITLE_NM"=>$sub_title_nm,
											"USE_TF"=>$use_tf,
											"URL_TYPE"=>$url_type,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateBanner($conn, $arr_data, $banner_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 수정 (제목 : ".$banner_nm.") ", "Update");
	}

	if ($mode == "D") {
		$result = deleteBanner($conn, $_SESSION['s_adm_no'], (int)$banner_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 삭제 처리 (제목 : ".$banner_nm.") ", "Delete");
	}

	$rs_banner_no						= "";
	$rs_banner_type					= "";
	$rs_banner_nm						= "";
	$rs_banner_img					= "";
	$rs_banner_img_m				= "";
	$rs_banner_url					= "";
	$rs_url_type						= "";
	$rs_banner_button				= "";

	$rs_title_nm						= "";
	$rs_sub_title_nm				= "";

	$rs_s_date							= "";
	$rs_s_hour							= "";
	$rs_s_min								= "";
	$rs_e_date							= "";
	$rs_e_hour							= "";
	$rs_e_min								= "";
	$rs_use_tf							= "";
	$rs_del_tf							= "";
	$rs_reg_adm							= "";
	$rs_reg_date						= "";

	if ($mode == "S") {

		$arr_rs = selectBanner($conn, (int)$banner_no);

		$rs_banner_no						= trim($arr_rs[0]["BANNER_NO"]); 
		$rs_banner_type					= trim($arr_rs[0]["BANNER_TYPE"]); 
		$rs_banner_nm						= SetStringFromDB($arr_rs[0]["BANNER_NM"]); 
		$rs_banner_img					= trim($arr_rs[0]["BANNER_IMG"]);
		$rs_banner_img_m				= trim($arr_rs[0]["BANNER_IMG_M"]);
		$rs_banner_url					= trim($arr_rs[0]["BANNER_URL"]);
		$rs_url_type						= trim($arr_rs[0]["URL_TYPE"]);
		$rs_banner_button				= trim($arr_rs[0]["BANNER_BUTTON"]);

		$rs_title_nm						= trim($arr_rs[0]["TITLE_NM"]);
		$rs_sub_title_nm				= trim($arr_rs[0]["SUB_TITLE_NM"]);

		$rs_s_date							= trim($arr_rs[0]["S_DATE"]); 
		$rs_s_hour							= trim($arr_rs[0]["S_HOUR"]); 
		$rs_s_min								= trim($arr_rs[0]["S_MIN"]); 
		$rs_e_date							= trim($arr_rs[0]["E_DATE"]); 
		$rs_e_hour							= trim($arr_rs[0]["E_HOUR"]); 
		$rs_e_min								= trim($arr_rs[0]["E_MIN"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 조회 (배너 제목 : ".$rs_banner_nm.") ", "Read");

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
		document.location.href = "banner_list.php<?=$strParam?>";
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
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../../_common/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>

<script type="text/javascript">
/*
$(document).ready(function() {
	<? if ($banner_no <> "") { ?>
	js_get_question();
	<? } ?>
});
*/
function js_list() {
	var frm = document.frm;
		
	frm.method = "get";
	frm.action = "banner_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var banner_no = "<?=$banner_no?>";
	var seq_no = "<?= $banner_no ?>";

	frm.banner_nm.value = frm.banner_nm.value.trim();
	
	//$("#use_tf").val($(":input:radio[name=rd_use_tf]:checked").val());
	
	if (isNull(frm.banner_nm.value)) {
		alert('배너명을 입력해주세요.');
		frm.banner_nm.focus();
		return ;
	}
/*
	if (isNull(frm.banner_url.value)) {
		alert('배너를 연결할 주소를 입력해주세요.');
		frm.banner_url.focus();
		return ;
	}
*/

	if (frm.rd_url_type == null) {
		//alert(frm.rd_use_tf);
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
		}
	}
/*
	if (isNull(frm.banner_button.value)) {
		alert('배너 버튼 내용을 입력해 주세요.');
		frm.banner_button.focus();
		return ;
	}


	if (isNull(frm.s_date.value)) {
		alert('신청 시작일를 입력해주세요.');
		frm.s_date.focus();
		return ;
	}
	alert("aa");

	if (isNull(frm.e_date.value)) {
		alert('신청 마감일를 입력해주세요.');
		frm.e_date.focus();
		return ;
	}
*/

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
	frm.action = "banner_write.php";

	if (isNull(banner_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}
	frm.submit();
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
<input type="hidden" name="banner_no" value="<?=$banner_no?>" />
<input type="hidden" name="banner_type" value="MAINVISUAL" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>메인비주얼</h4></div>
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
									<th>비주얼명 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><input type="text" name="banner_nm" value="<?=$rs_banner_nm?>" style="width:30%"/></td>
								</tr>
								<tr>
									<th scope="row">PC 이미지</th>
									<td colspan="3">
								<?
									if (strlen($rs_banner_img) > 3) {
								?>
									<a href="../../_common/new_download_file.php?menu=banner&banner_no=<?= $rs_banner_no ?>&field=banner_img"><img src="<?=$g_base_dir?>/upload_data/banner/<?= $rs_banner_img ?>" width="300px"></a>
									&nbsp;&nbsp;
									<select name="flag01" style="width:100px;" onchange="javascript:js_fileView(this,'01')">
										<option value="keep">유지</option>
										<option value="update">수정</option>
										<option value="delete">삭제</option>
									</select>
					
									<input type="hidden" name="banner_real_img" value="<?= $rs_banner_img?>">
									<div id="file_change01" style="display:none;"><span class="txt_c02">※ 1388 x 384</span>
										<input type="file" name="banner_img" size="40%" /> 
									</div>

								<?
									} else {
								?>
									<input type="file" size="40%" name="banner_img"> <span class="txt_c02">※ 1388 x 384</span>
									<input type="hidden" name="banner_real_img" value="">
									<input TYPE="hidden" name="flag01" value="insert">
								<?
									}	
								?>
									</td>
								</tr>

								<tr>
									<th scope="row">모바일 이미지</th>
									<td colspan="3">
								<?
									if (strlen($rs_banner_img_m) > 3) {
								?>
									<a href="../../_common/new_download_file.php?menu=banner&banner_no=<?= $rs_banner_no ?>&field=banner_img_m"><img src="<?=$g_base_dir?>/upload_data/banner/<?= $rs_banner_img_m ?>" height="300px"></a>
									&nbsp;&nbsp;
									<select name="flag02" style="width:100px;" onchange="javascript:js_fileView(this,'02')">
										<option value="keep">유지</option>
										<option value="update">수정</option>
										<option value="delete">삭제</option>
									</select>
					
									<input type="hidden" name="banner_real_img_m" value="<?= $rs_banner_img_m?>">
									<div id="file_change02" style="display:none;"><span class="txt_c02">※ 1080 x 432</span>
										<input type="file" name="banner_img_m" size="40%" /> 
									</div>

								<?
									} else {
								?>
									<input type="file" size="40%" name="banner_img_m"> <span class="txt_c02">※ 1080 x 432</span>
									<input type="hidden" name="banner_real_img_m" value="">
									<input TYPE="hidden" name="flag02" value="insert">
								<?
									}	
								?>
									</td>
								</tr>

								<tr>
									<th>슬로건 상단<img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
									<input type="text" name="title_nm" value="<?=$rs_title_nm?>" style="width:30%"/>
								</tr>
								<tr>
									<th>슬로건 하단<img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
									<textarea name="sub_title_nm" style=" width:600px;height:55px;padding:2px;" style="vertical-align:bottom"><?=$rs_sub_title_nm?></textarea>
									<font style="vertical-align:bottom;color:rgb(251,177,50)"> &nbsp;&nbsp;2줄 입력</font></td>
								</tr>
								<!--
								<tr>
									<th scope="row">링크주소</th>
									<td colspan="3"><input type="text" name="banner_url" value="<?=$rs_banner_url?>" style="width:90%" placeholder="#"/></td>
								</tr>
								<tr>
									<th scope="row">링크방식</th>
									<td colspan="3">
										<input type="radio" id="blank" name="rd_url_type" value="Y" <? if (($rs_url_type =="Y") || ($rs_url_type =="")) echo "checked"; ?> /> <label for="blank">새창</label>&nbsp;&nbsp;
										<input type="radio" id="own" name="rd_url_type" value="N" <? if ($rs_url_type =="N") echo "checked"; ?> /> <label for="own">자기창</label>
										<input type="hidden" name="url_type" value="<?= $rs_url_type ?>">
									</td>
								</tr>
								-->
								<!--tr>
									<th>배너버튼내용 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><input type="text" name="banner_button" value="<?=$rs_banner_button?>" style="width:30%"/></td>
								</tr>
								<tr>
									<th>신청 기간 <img src="../images/img_essen.gif" /></th>
									<td colspan="3">
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
									</td>
								</tr-->
								<tr>
									<th>공개여부</th>
									<td colspan="3">
										<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /> <label for="all">공개</label>&nbsp;&nbsp;
										<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret">비공개</label>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ($banner_no == "") {?>
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
			</div>
		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var oEditors = [];

	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "contents",
		sSkinURI: "../../_common/SE2-2.8.2.3/SmartEditor2Skin.html",
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
		fCreator: "createSEditor2"
	});

//-->
</SCRIPT>

<?
#=====================================================================
# DB Close
#=====================================================================

	db_close($conn);
?>