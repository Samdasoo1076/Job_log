<?session_start();?>
<?
# =============================================================================
# File Name    : quick_write.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-10-19
# Modify Date  : 2021-05-04
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

	$mtype						= isset($_POST["mtype"]) && $_POST["mtype"] !== '' ? $_POST["mtype"] : (isset($_GET["mtype"]) ? $_GET["mtype"] : '');

	if ($mtype == "TOP") { 
		$menu_right = "PO004"; // 메뉴마다 셋팅 해 주어야 합니다
	} else if ($mtype == "MIDDLE") {
		$menu_right = "PO005"; // 메뉴마다 셋팅 해 주어야 합니다
	} else if ($mtype == "BOTTOM") {
		$menu_right = "PO006"; // 메뉴마다 셋팅 해 주어야 합니다
	} else if ($mtype == "CAMPUS") {
		$menu_right = "PO010"; // 메뉴마다 셋팅 해 주어야 합니다
	}

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
	require "../../_classes/biz/popup/quick.php";

#====================================================================
# DML Process
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$q_no							= isset($_POST["q_no"]) && $_POST["q_no"] !== '' ? $_POST["q_no"] : (isset($_GET["q_no"]) ? $_GET["q_no"] : '');
	$q_type						= isset($_POST["q_type"]) && $_POST["q_type"] !== '' ? $_POST["q_type"] : (isset($_GET["q_type"]) ? $_GET["q_type"] : '');
	$q_mtype					= isset($_POST["q_mtype"]) && $_POST["q_mtype"] !== '' ? $_POST["q_mtype"] : (isset($_GET["q_mtype"]) ? $_GET["q_mtype"] : '');
	$q_color					= isset($_POST["q_color"]) && $_POST["q_color"] !== '' ? $_POST["q_color"] : (isset($_GET["q_color"]) ? $_GET["q_color"] : '');
	$q_title					= isset($_POST["q_title"]) && $_POST["q_title"] !== '' ? $_POST["q_title"] : (isset($_GET["q_title"]) ? $_GET["q_title"] : '');
	$q_subtitle				= isset($_POST["q_subtitle"]) && $_POST["q_subtitle"] !== '' ? $_POST["q_subtitle"] : (isset($_GET["q_subtitle"]) ? $_GET["q_subtitle"] : '');
	$q_description		= isset($_POST["q_description"]) && $_POST["q_description"] !== '' ? $_POST["q_description"] : (isset($_GET["q_description"]) ? $_GET["q_description"] : '');
	$q_url						= isset($_POST["q_url"]) && $_POST["q_url"] !== '' ? $_POST["q_url"] : (isset($_GET["q_url"]) ? $_GET["q_url"] : '');
	$q_target					= isset($_POST["q_target"]) && $_POST["q_target"] !== '' ? $_POST["q_target"] : (isset($_GET["q_target"]) ? $_GET["q_target"] : '');

	$s_date						= isset($_POST["s_date"]) && $_POST["s_date"] !== '' ? $_POST["s_date"] : (isset($_GET["s_date"]) ? $_GET["s_date"] : '');
	$s_hour						= isset($_POST["s_hour"]) && $_POST["s_hour"] !== '' ? $_POST["s_hour"] : (isset($_GET["s_hour"]) ? $_GET["s_hour"] : '');
	$s_min						= isset($_POST["s_min"]) && $_POST["s_min"] !== '' ? $_POST["s_min"] : (isset($_GET["s_min"]) ? $_GET["s_min"] : '');
	$e_date						= isset($_POST["e_date"]) && $_POST["e_date"] !== '' ? $_POST["e_date"] : (isset($_GET["e_date"]) ? $_GET["e_date"] : '');
	$e_hour						= isset($_POST["e_hour"]) && $_POST["e_hour"] !== '' ? $_POST["e_hour"] : (isset($_GET["e_hour"]) ? $_GET["e_hour"] : '');
	$e_min						= isset($_POST["e_min"]) && $_POST["e_min"] !== '' ? $_POST["e_min"] : (isset($_GET["e_min"]) ? $_GET["e_min"] : '');

	$date_use_tf			= isset($_POST["date_use_tf"]) && $_POST["date_use_tf"] !== '' ? $_POST["date_use_tf"] : (isset($_GET["date_use_tf"]) ? $_GET["date_use_tf"] : '');
	$con_q_type				= isset($_POST["con_q_type"]) && $_POST["con_q_type"] !== '' ? $_POST["con_q_type"] : (isset($_GET["con_q_type"]) ? $_GET["con_q_type"] : '');

	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

#====================================================================
	//$savedir1 = $q_physical_path."upload_data/popup/quick";
#====================================================================

	$result = false;

	$mode							= SetStringToDB($mode);
	$q_type						= SetStringToDB($q_type);
	$q_mtype					= SetStringToDB($q_mtype);
	$q_title					= SetStringToDB($q_title);
	$q_subtitle				= SetStringToDB($q_subtitle);
	$q_description		= SetStringToDB($q_description);
	$q_url						= SetStringToDB($q_url);
	$q_target					= SetStringToDB($q_target);

	$search_field			= SetStringToDB($search_field);
	$search_str				=	SetStringToDB($search_str);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "I") {
		
		$arr_data = array("Q_TYPE"=>$q_type,
											"Q_MTYPE"=>$mtype,
											"Q_TITLE"=>$q_title,
											"Q_COLOR"=>$q_color,
											"Q_SUBTITLE"=>$q_subtitle,
											"Q_DESCRIPTION"=>$q_description,
											"Q_URL"=>$q_url,
											"Q_TARGET"=>$q_target,
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"DATE_USE_TF"=>$date_use_tf,
											"HIT_CNT"=>0,
											"DISP_SEQ"=>0,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$new_q_no	= insertQuick($conn, $arr_data);
		$result =$new_q_no;

		$result_log	= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '퀵링크 페이지지지 등록 (제목 : '.$q_title.') ', 'Insert');

	}

	if ($mode == "U") {

		$arr_data = array("Q_TYPE"=>$q_type,
											"Q_COLOR"=>$q_color,
											"Q_TITLE"=>$q_title,
											"Q_SUBTITLE"=>$q_subtitle,
											"Q_DESCRIPTION"=>$q_description,
											"Q_URL"=>$q_url,
											"Q_TARGET"=>$q_target,
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"DATE_USE_TF"=>$date_use_tf,
											"USE_TF"=>$use_tf,
											"UP_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateQuick($conn, $arr_data, $q_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '퀵링크 페이지 수정 (제목 : '.$q_title.') ', 'Update');
	}

	if ($mode == "D") {
		$result = deleteQuick($conn, $_SESSION['s_adm_no'], (int)$q_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '퀵링크 페이지 삭제 처리 (제목 : '.$q_title.') ', 'Delete');
	}

	$rs_q_no								= ""; 
	$rs_q_type							= "";
	$rs_q_mtype							= "";
	$rs_q_title							= "";
	$rs_q_color							= "";
	$rs_q_subtitle					= "";
	$rs_q_description				= "";
	$rs_q_url								= "";
	$rs_q_target						= "";
	$rs_s_date							= "";
	$rs_s_hour							= "";
	$rs_s_min								= "";
	$rs_e_date							= "";
	$rs_e_hour							= "";
	$rs_e_min								= "";
	$rs_date_use_tf					= "";

	$rs_hit_cnt							= "";
	$rs_use_tf							= "";
	$rs_del_tf							= "";
	$rs_reg_adm							= "";
	$rs_reg_date						= "";
	$rs_up_adm							= "";
	$rs_up_date							= "";

	if ($mode == "S") {

		$arr_rs = selectQuick($conn, (int)$q_no);

		$rs_q_no								= trim($arr_rs[0]["Q_NO"]); 
		$rs_q_type							= trim($arr_rs[0]["Q_TYPE"]); 
		$rs_q_mtype							= trim($arr_rs[0]["Q_MTYPE"]); 
		$rs_q_title							= SetStringFromDB($arr_rs[0]["Q_TITLE"]); 
		$rs_q_color							= trim($arr_rs[0]["Q_COLOR"]); 
		$rs_q_subtitle					= SetStringFromDB($arr_rs[0]["Q_SUBTITLE"]); 
		$rs_q_description				= SetStringFromDB($arr_rs[0]["Q_DESCRIPTION"]); 
		$rs_q_url								= SetStringFromDB($arr_rs[0]["Q_URL"]); 
		$rs_q_target						= SetStringFromDB($arr_rs[0]["Q_TARGET"]); 
		$rs_s_date							= trim($arr_rs[0]["S_DATE"]); 
		$rs_s_hour							= trim($arr_rs[0]["S_HOUR"]); 
		$rs_s_min								= trim($arr_rs[0]["S_MIN"]); 
		$rs_e_date							= trim($arr_rs[0]["E_DATE"]); 
		$rs_e_hour							= trim($arr_rs[0]["E_HOUR"]); 
		$rs_e_min								= trim($arr_rs[0]["E_MIN"]); 
		$rs_date_use_tf					= trim($arr_rs[0]["DATE_USE_TF"]); 

		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm							= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date							= trim($arr_rs[0]["UP_DATE"]); 
 
		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '퀵링크 페이지 조회 (제목 : '.$rs_q_title.') ', 'Read');

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;
		$strParam = $strParam."&mtype=".$mtype."&con_q_type=".$con_q_type;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "quick_list.php<?=$strParam?>";
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
<title><?=$q_title?></title>
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
	frm.action = "quick_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var q_no = "<?=$q_no?>";
	
	if (!frm.q_title.value.trim()){
		alert('제목을 입력해주세요.');
		frm.q_title.focus();
		return ;		
	}

/*
	if (!frm.q_subtitle.value.trim()){
		alert('부제목을 입력해주세요.');
		frm.q_subtitle.focus();
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

	if (document.frm.rd_q_target == null) {
		// 
	} else {
		if (frm.rd_q_target[0].checked == true) {
			frm.q_target.value = "Y";
		} else {
			frm.q_target.value = "N";
		}
	}

	frm.target = "";
	frm.method = "post";
	frm.action = "<?=$_SERVER['PHP_SELF']?>";

	if (isNull(q_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}
	frm.submit();
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

<form name="frm" id="frm" method="post">
<input type="hidden" name="q_no" value="<?=$q_no?>" />
<input type="hidden" name="mtype" value="<?=$mtype?>" />
<input type="hidden" name="con_q_type" value="<?=$con_q_type?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>퀵링크 페이지 등록</h4></div>
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:12%" />
								<col style="width:38%" />
								<col style="width:12%" />
								<col />
							</colgroup>
							<tbody>
<?
								if ($mtype == "TOP") { 
?>
								<tr>
									<th>구분 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="radio" name="q_type" value="ALL" id="gtype_t" <? if (($rs_q_type =="ALL") || ($rs_q_type =="")) echo "checked"; ?> />
										<label for="gtype_t"> 공통 </label>&nbsp;&nbsp;
										<input type="radio" name="q_type" value="SUSI" id="gtype_a" <? if ($rs_q_type =="SUSI") echo "checked"; ?> />
										<label for="gtype_a"> 수시 </label>&nbsp;&nbsp;
										<input type="radio" name="q_type" value="JEONGSI" id="gtype_b" <? if ($rs_q_type =="JEONGSI") echo "checked"; ?> />
										<label for="gtype_b"> 정시 </label>&nbsp;&nbsp;
										<input type="radio" name="q_type" value="SUNGIN" id="gtype_f" <? if ($rs_q_type =="SUNGIN") echo "checked"; ?> />
										<label for="gtype_b"> 성인·재직자 </label>&nbsp;&nbsp;
										<input type="radio" name="q_type" value="JEOEGUK" id="gtype_c" <? if ($rs_q_type =="JEOEGUK") echo "checked"; ?> />
										<label for="gtype_c"> 재외국민 </label>&nbsp;&nbsp;
										<input type="radio" name="q_type" value="PYEONIP" id="gtype_d" <? if ($rs_q_type =="PYEONIP") echo "checked"; ?> />
										<label for="gtype_d"> 편입학 </label>&nbsp;&nbsp;
										<input type="hidden" name="q_color" value="accent-09"/>

									</td>
								</tr>
<?
								}

								if ($mtype == "MIDDLE") { 
?>
								<tr>
									<th>아이콘 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-01.svg" id="q_subtitle_t" <? if (($rs_q_subtitle =="ico-entrance-exam-01.svg") || ($rs_q_subtitle =="")) echo "checked"; ?> />
										<label for="q_subtitle_t"><img src="/assets/images/icon/ico-entrance-exam-01.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-02.svg" id="q_subtitle_a" <? if ($rs_q_subtitle =="ico-entrance-exam-02.svg") echo "checked"; ?> />
										<label for="q_subtitle_a"><img src="/assets/images/icon/ico-entrance-exam-02.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-03.svg" id="q_subtitle_b" <? if ($rs_q_subtitle =="ico-entrance-exam-03.svg") echo "checked"; ?> />
										<label for="q_subtitle_b"><img src="/assets/images/icon/ico-entrance-exam-03.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-04.svg" id="q_subtitle_c" <? if ($rs_q_subtitle =="ico-entrance-exam-04.svg") echo "checked"; ?> />
										<label for="q_subtitle_c"><img src="/assets/images/icon/ico-entrance-exam-04.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-05.svg" id="q_subtitle_d" <? if ($rs_q_subtitle =="ico-entrance-exam-05.svg") echo "checked"; ?> />
										<label for="q_subtitle_d"><img src="/assets/images/icon/ico-entrance-exam-05.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-06.svg" id="q_subtitle_e" <? if ($rs_q_subtitle =="ico-entrance-exam-06.svg") echo "checked"; ?> />
										<label for="q_subtitle_e"><img src="/assets/images/icon/ico-entrance-exam-06.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-01.svg" id="q_subtitle_f" <? if ($rs_q_subtitle =="ico-quick-01.svg") echo "checked"; ?> />
										<label for="q_subtitle_f"><img src="/assets/images/icon/ico-quick-01.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-02.svg" id="q_subtitle_g" <? if ($rs_q_subtitle =="ico-quick-02.svg") echo "checked"; ?> />
										<label for="q_subtitle_g"><img src="/assets/images/icon/ico-quick-02.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-03.svg" id="q_subtitle_h" <? if ($rs_q_subtitle =="ico-quick-03.svg") echo "checked"; ?> />
										<label for="q_subtitle_h"><img src="/assets/images/icon/ico-quick-03.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-04.svg" id="q_subtitle_i" <? if ($rs_q_subtitle =="ico-quick-04.svg") echo "checked"; ?> />
										<label for="q_subtitle_i"><img src="/assets/images/icon/ico-quick-04.svg"></label>&nbsp;&nbsp;
										<input type="hidden" name="q_type" value="<?=$con_q_type?>"/>
									</td>
								</tr>
<?
								}

								if ($mtype == "BOTTOM") { 
?>
								<tr>
									<th>아이콘 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="radio" name="q_subtitle" value="ico-quick-01.svg" id="q_subtitle_f" <? if (($rs_q_subtitle =="ico-quick-01.svg") || ($rs_q_subtitle =="")) echo "checked"; ?> />
										<label for="q_subtitle_f"><img src="/assets/images/icon/ico-quick-01.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-02.svg" id="q_subtitle_g" <? if ($rs_q_subtitle =="ico-quick-02.svg") echo "checked"; ?> />
										<label for="q_subtitle_g"><img src="/assets/images/icon/ico-quick-02.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-03.svg" id="q_subtitle_h" <? if ($rs_q_subtitle =="ico-quick-03.svg") echo "checked"; ?> />
										<label for="q_subtitle_h"><img src="/assets/images/icon/ico-quick-03.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-quick-04.svg" id="q_subtitle_i" <? if ($rs_q_subtitle =="ico-quick-04.svg") echo "checked"; ?> />
										<label for="q_subtitle_i"><img src="/assets/images/icon/ico-quick-04.svg"></label>&nbsp;&nbsp;
										<input type="radio" name="q_subtitle" value="ico-entrance-exam-01.svg" id="q_subtitle_t" <? if ($rs_q_subtitle =="ico-entrance-exam-01.svg") echo "checked"; ?> />
										<label for="q_subtitle_t"><img src="/assets/images/icon/ico-entrance-exam-01.svg"></label>&nbsp;&nbsp;
										<input type="hidden" name="q_type" value="<?=$con_q_type?>"/>

									</td>
								</tr>
<?
								}

								if ($mtype == "CAMPUS") { 
?>
										<input type="hidden" name="q_type" value="<?=$con_q_type?>"/>
<?
								}
?>
								
								<tr>
									<th>제목 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<? if ($mtype == "MIDDLE") { ?>
										<textarea name="q_title" style=" width:250px;height:55px;padding:2px;" style="vertical-align:bottom"><?=$rs_q_title?></textarea>
										<font style="vertical-align:bottom;color:rgb(251,177,50)"> &nbsp;&nbsp;줄바꿈 엔터로 입력</font>
										<? } else if ($mtype == "BOTTOM") { ?>
										<input type="text" name="q_title" value="<?=$rs_q_title?>" style="width:550px;" />
										<? } else { ?>
										<input type="text" name="q_title" value="<?=$rs_q_title?>" style="width:550px;" />
										<? } ?>
									</td>
								</tr>
								<!--
								<tr>
									<th>제목 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="q_subtitle" style=" width:30%;" value="<?=$rs_q_subtitle?>" >
									</td>
								</tr>
								-->
								<tr>
									<th>설명 </th>
									<td colspan="3">
										<input type="text" name="q_description" value="<?=$rs_q_description?>" style="width:550px;" />
									</td>
								</tr>
								<tr>
									<th>링크 주소(URL) <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="text" name="q_url" value="<?=$rs_q_url?>" style="width:450px;" />
										<? if ($mtype == "CAMPUS") { ?>
										<span class="tbl_txt"><span class="txt_c02">※ 유투브 활용 시 https://youtu.be/6FW8crS9HMY 형태로 입력해 주세요.</span></span>
										<? } ?>
									</td>
								</tr>
								<tr>
									<th>링크 방식</th>
									<td colspan="3">
										<input type="radio" id="blank" name="rd_q_target" value="Y" <? if (($rs_q_target =="Y") || ($rs_q_target =="")) echo "checked"; ?> /> <label for="blank">새창</label>&nbsp;&nbsp;
										<input type="radio" id="own" name="rd_q_target" value="N" <? if ($rs_q_target =="N") echo "checked"; ?> /> <label for="own">자기창</label>
										<input type="hidden" name="q_target" value="<?= $rs_q_target ?>">
									</td>
									</td>
								</tr>
								<? //신청 기간 대신 등록 날짜 자동으로 입력되도록
									$dateString = date("Y-m-d", time());
								?>
								<input type="hidden" name="s_date" value="<?=$dateString?>">

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
						<? if ($q_no == "") {?>
						<?	if ($sPageRight_I == "Y") {?>
						<button type="button" class="button" onClick="js_save()">등록</button>
						<?	} ?>
						<? } else {  ?>
						<?	if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="js_save()">수정</button>
						<?	} ?>
						<?	if ($sPageRight_D == "Y") {?>
						<?		if ($mtype == "TOP") { ?>
						<button type="button" class="button" onClick="js_delete()">삭제</button>
						<?		} ?>
						<?	} ?>
						<? } ?>
					</div>
				</div>

</form>

<form name="frm_list" method="post">

<input type="hidden" name="q_no" value="<?=$q_no?>" />
<input type="hidden" name="mtype" value="<?=$mtype?>" />
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
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================

	db_close($conn);
?>