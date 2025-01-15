<?session_start();?>
<?
# =============================================================================
# File Name    : admin_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-20
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
	$menu_right = "AD002"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	$sPageRight_		= "Y";
	$sPageRight_R		= "Y";
	$sPageRight_I		= "Y";
	$sPageRight_U		= "Y";
	$sPageRight_D		= "Y";
	$sPageRight_F		= "Y";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";

#====================================================================
# DML Process
#====================================================================

	$mode								= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$adm_no							= isset($_POST["adm_no"]) && $_POST["adm_no"] !== '' ? $_POST["adm_no"] : (isset($_GET["adm_no"]) ? $_GET["adm_no"] : '');
	
	$com_code						= isset($_POST["com_code"]) && $_POST["com_code"] !== '' ? $_POST["com_code"] : (isset($_GET["com_code"]) ? $_GET["com_code"] : '');
	$profile						= isset($_POST["profile"]) && $_POST["profile"] !== '' ? $_POST["profile"] : (isset($_GET["profile"]) ? $_GET["profile"] : '');

	$adm_id							= isset($_POST["adm_id"]) && $_POST["adm_id"] !== '' ? $_POST["adm_id"] : (isset($_GET["adm_id"]) ? $_GET["adm_id"] : '');
	$old_adm_id					= isset($_POST["old_adm_id"]) && $_POST["old_adm_id"] !== '' ? $_POST["old_adm_id"] : (isset($_GET["old_adm_id"]) ? $_GET["old_adm_id"] : '');
	$adm_name						= isset($_POST["adm_name"]) && $_POST["adm_name"] !== '' ? $_POST["adm_name"] : (isset($_GET["adm_name"]) ? $_GET["adm_name"] : '');
	$adm_info						= isset($_POST["adm_info"]) && $_POST["adm_info"] !== '' ? $_POST["adm_info"] : (isset($_GET["adm_info"]) ? $_GET["adm_info"] : '');
	$passwd							= isset($_POST["passwd"]) && $_POST["passwd"] !== '' ? $_POST["passwd"] : (isset($_GET["passwd"]) ? $_GET["passwd"] : '');
	$passwd_chk					= isset($_POST["passwd_chk"]) && $_POST["passwd_chk"] !== '' ? $_POST["passwd_chk"] : (isset($_GET["passwd_chk"]) ? $_GET["passwd_chk"] : '');
	$adm_hphone					= isset($_POST["adm_hphone"]) && $_POST["adm_hphone"] !== '' ? $_POST["adm_hphone"] : (isset($_GET["adm_hphone"]) ? $_GET["adm_hphone"] : '');
	$adm_phone					= isset($_POST["adm_phone"]) && $_POST["adm_phone"] !== '' ? $_POST["adm_phone"] : (isset($_GET["adm_phone"]) ? $_GET["adm_phone"] : '');
	$adm_email					= isset($_POST["adm_email"]) && $_POST["adm_email"] !== '' ? $_POST["adm_email"] : (isset($_GET["adm_email"]) ? $_GET["adm_email"] : '');
	$adm_flag						= isset($_POST["adm_flag"]) && $_POST["adm_flag"] !== '' ? $_POST["adm_flag"] : (isset($_GET["adm_flag"]) ? $_GET["adm_flag"] : '');
	$position_code			= isset($_POST["position_code"]) && $_POST["position_code"] !== '' ? $_POST["position_code"] : (isset($_GET["position_code"]) ? $_GET["position_code"] : '');
	$dept_code					= isset($_POST["dept_code"]) && $_POST["dept_code"] !== '' ? $_POST["dept_code"] : (isset($_GET["dept_code"]) ? $_GET["dept_code"] : '');
	$group_no						= isset($_POST["group_no"]) && $_POST["group_no"] !== '' ? $_POST["group_no"] : (isset($_GET["group_no"]) ? $_GET["group_no"] : '');
	$use_tf							= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

	$enter_date					= isset($_POST["enter_date"]) && $_POST["enter_date"] !== '' ? $_POST["enter_date"] : (isset($_GET["enter_date"]) ? $_GET["enter_date"] : '');
	$out_date						= isset($_POST["out_date"]) && $_POST["out_date"] !== '' ? $_POST["out_date"] : (isset($_GET["out_date"]) ? $_GET["out_date"] : '');
	$emp_no							= isset($_POST["emp_no"]) && $_POST["emp_no"] !== '' ? $_POST["emp_no"] : (isset($_GET["emp_no"]) ? $_GET["emp_no"] : '');
	$group_cd						= isset($_POST["group_cd"]) && $_POST["group_cd"] !== '' ? $_POST["group_cd"] : (isset($_GET["group_cd"]) ? $_GET["group_cd"] : '');

	$nPage							= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize					= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field				= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str					= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	
	$result = false;

	$adm_name	= SetStringToDB($adm_name);
	$adm_info	= SetStringToDB($adm_info);

	$mode			= SetStringToDB($mode);
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);

	if ($mode == "I") {

		$adm_id					= SetStringToDB($adm_id);
		$passwd					= SetStringToDB($passwd);
		$adm_hphone			= SetStringToDB($adm_hphone);
		$adm_phone			= SetStringToDB($adm_phone);

		$adm_email			= SetStringToDB($adm_email);
		$adm_flag				= SetStringToDB($adm_flag);
		$position_code	= SetStringToDB($position_code);
		$dept_code			= SetStringToDB($dept_code);

		$use_tf					= SetStringToDB($use_tf);

		$search_field		= SetStringToDB($search_field);
		$search_str			= SetStringToDB($search_str);

		$result_flag = dupAdmin($conn, $adm_id);
		
		if(empty($adm_flag))$adm_flag="Y";
		
		if ($result_flag == 0) {

			$passwd_enc = encrypt($key, $iv, $passwd);

			$arr_data = array("ADM_ID"=>$adm_id,
												"PASSWD"=>$passwd_enc,
												"ADM_NAME"=>$adm_name,
												"ADM_INFO"=>$adm_info,
												"ADM_HPHONE"=>$adm_hphone,
												"ADM_PHONE"=>$adm_phone,
												"ADM_EMAIL"=>$adm_email,
												"GROUP_NO"=>$group_no,
												"ADM_FLAG"=>$adm_flag,
												"POSITION_CODE"=>$position_code,
												"DEPT_CODE"=>$dept_code,
												"COM_CODE"=>$com_code,
												"ENTER_DATE"=>$enter_date,
												"OUT_DATE"=>$out_date,
												"EMP_NO"=>$emp_no,
												"PROFILE"=>$profile,
												"USE_TF"=>$use_tf,
												"REG_ADM"=>$_SESSION['s_adm_no']
											);

			$result =  insertAdmin($conn, $arr_data);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 등록 (관리자 아이디 : ".$adm_id.") ", "Insert");

		} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('사용중인 ID 입니다.');
		document.location.href = "admin_write.php";
</script>
</head>
</html>
<?
		exit;
		}
	}
	

	if ($mode == "U") {

		$adm_id					= SetStringToDB($adm_id);
		$passwd					= SetStringToDB($passwd);
		$adm_hphone			= SetStringToDB($adm_hphone);
		$adm_phone			= SetStringToDB($adm_phone);

		$adm_email			= SetStringToDB($adm_email);
		$adm_flag				= SetStringToDB($adm_flag);
		$position_code	= SetStringToDB($position_code);
		$dept_code			= SetStringToDB($dept_code);

		$use_tf					= SetStringToDB($use_tf);

		$search_field		= SetStringToDB($search_field);
		$search_str			= SetStringToDB($search_str);

		$result_flag = 0;

		if ($old_adm_id <> $adm_id) {
			$result_flag = dupAdmin($conn, $adm_id);
			echo "중복";
		}
		
		if ($result_flag == 0) {

			$passwd_enc = encrypt($key, $iv, $passwd);
			
			$arr_data = array("ADM_ID"=>$adm_id,
												"ADM_NAME"=>$adm_name,
												"ADM_INFO"=>$adm_info,
												"ADM_HPHONE"=>$adm_hphone,
												"ADM_PHONE"=>$adm_phone,
												"ADM_EMAIL"=>$adm_email,
												"GROUP_NO"=>$group_no,
												"ADM_FLAG"=>$adm_flag,
												"POSITION_CODE"=>$position_code,
												"DEPT_CODE"=>$dept_code,
												"COM_CODE"=>$com_code,
												"ENTER_DATE"=>$enter_date,
												"OUT_DATE"=>$out_date,
												"EMP_NO"=>$emp_no,
												"PROFILE"=>$profile,
												"USE_TF"=>$use_tf,
												"UP_ADM"=>$_SESSION['s_adm_no']
											);

			$result =  updateAdmin($conn, $arr_data, $adm_no);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 정보 수정 (관리자 아이디 : ".$adm_id.") ", "Update");

			if($passwd_chk=="Y")updateAdminPwd($conn, $passwd_enc, $s_adm_no, (int)$adm_no);

		} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('사용중인 ID 입니다.');
		document.location.href = "admin_write.php?mode=S&adm_no=<?=$adm_no?>";
</script>
</head>
</html>
<?
		exit;
		}
	}

	if ($mode == "T") {

		//updateEventUseTF($conn, $use_tf, $s_adm_no, $event_no);

	}

	if ($mode == "D") {
		$result = deleteAdmin($conn, $_SESSION['s_adm_no'], (int)$adm_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 삭제 처리 (관리자 번호 : ".(int)$adm_no.") ", "Delete");
	}


	$rs_adm_no					= "";
	$rs_adm_id					= "";
	$rs_passwd					= "";
	$rs_adm_name				= "";
	$rs_adm_info				= "";
	$rs_adm_hphone			= "";
	$rs_adm_phone				= "";
	$rs_adm_email				= "";
	$rs_group_no				= "";
	$rs_adm_flag				= "";
	$rs_position_code		= "";
	$rs_dept_code				= "";
	$rs_com_code				= "";
	$rs_enter_date			= "";
	$rs_out_date				= "";
	$rs_use_tf					= "";
	$rs_del_tf					= "";
	//$rs_organization		= "";
	$rs_reg_adm					= "";
	$rs_reg_date				= "";
	$rs_up_adm					= "";
	$rs_up_date					= "";
	$rs_emp_no					= "";


	if ($mode == "S") {

		$arr_rs = selectAdmin($conn, (int)$adm_no);

		$rs_adm_no					= trim($arr_rs[0]["ADM_NO"]); 
		$rs_adm_id					= trim($arr_rs[0]["ADM_ID"]); 
		$rs_passwd					= trim($arr_rs[0]["PASSWD"]); 
		$rs_adm_name				= SetStringFromDB($arr_rs[0]["ADM_NAME"]); 
		$rs_adm_info				= SetStringFromDB($arr_rs[0]["ADM_INFO"]); 
		$rs_adm_hphone			= trim($arr_rs[0]["ADM_HPHONE"]); 
		$rs_adm_phone				= trim($arr_rs[0]["ADM_PHONE"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_adm_flag				= trim($arr_rs[0]["ADM_FLAG"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_enter_date			= trim($arr_rs[0]["ENTER_DATE"]); 
		$rs_out_date				= trim($arr_rs[0]["OUT_DATE"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		//$rs_organization		= trim($arr_rs[0]["ORGANIZATION"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm					= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date					= trim($arr_rs[0]["UP_DATE"]); 
		$rs_emp_no					= trim($arr_rs[0]["EMP_NO"]); 

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 조회 (관리자 아이디 : ".$rs_adm_id.") ", "Read");

	} else {

			//$arr_admin_seq = getAdminInfoNum($conn, "CU");
			$rs_emp_no = 0;

	//	echo $admin_num;
	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "admin_list.php<?=$strParam?>";
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
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

<script type="text/javascript">

$(document).ready(function() {

});


function js_list() {
	var frm = document.frm;
		
	frm.method = "get";
	frm.action = "admin_list.php";
	frm.submit();
}


function js_save() {

	var frm = document.frm;
	var adm_no = "<?= (int)$adm_no ?>";
	
	frm.adm_name.value = frm.adm_name.value.trim();
	frm.adm_id.value = frm.adm_id.value.trim();
	frm.passwd.value = frm.passwd.value.trim();

	if (frm.group_no.value == "") {
		alert('관리자 그룹을 선택해주세요.');
		frm.group_no.focus();
		return ;		
	}

	if (isNull(frm.adm_name.value)) {
		alert('이름을 입력해주세요.');
		frm.adm_name.focus();
		return ;		
	}

	if (isNull(frm.adm_id.value)) {
		alert('아이디을 입력해주세요.');
		frm.adm_id.focus();
		return ;		
	}

	if (isNull(adm_no) || parseInt(adm_no)==0) {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
			return ;		
		}
	}

	if (frm.passwd_chk.checked) {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
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

	if (isNull(adm_no) || parseInt(adm_no)==0) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.adm_no.value = frm.adm_no.value;
	}

	frm.target = "";
	frm.action = "admin_write.php";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "admin_write.php";
		frm.submit();
	}

}

function sendKeyword() {

	if (frm.old_adm_id.value != frm.adm_id.value)	{

		var keyword = document.frm.adm_id.value;

		if (keyword != '') {
			var params = "keyword="+encodeURIComponent(keyword);
			sendRequest("admin_dup_check.php", params, displayResult, 'POST');
		}
	} else {
		js_save();
	}
}

function displayResult() {
	
	if (httpRequest.readyState == 4) {
		if (httpRequest.status == 200) {
			
			var resultText = httpRequest.responseText;
			var result = resultText;
			
			//alert(result);
			
			//return;
			if (result == "1") {
				alert("사용중인 아이디 입니다.");
				return;
			} else {
				js_save();
			}
		} else {
			alert("에러 발생: "+httpRequest.status);
		}
	}
}

$(document).on("click", "#passwd_chk", function() { 
	if ($(this).is(":checked")) {
		$("#passwd").css("background-color", "#FFFFFF");
		$("#passwd").attr("readonly", false);
	} else {
		$("#passwd").css("background-color", "#EFEFEF");
		$("#passwd").attr("readonly", true);
	}
});

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
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="menu_cd" value="" >
<input type="hidden" name="adm_no" value="<?=(int)$adm_no?>" />
<input type="hidden" name="adm_flag" value="<?=$rs_adm_flag?>" />
<input type="hidden" name="nPage" value="<?=(int)$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:14%" />
								<col style="width:36%" />
								<col style="width:14%" />
								<col />
							</colgroup>
							<tbody>
								<tr>
									<th>사용자 등급</th>
									<td colspan="3">
										<?= makeAdminGroupSelectBox($conn, "group_no" , "155", "사용자 등급 선택", "", (int)$rs_group_no); ?>
									</td>
									<!--
									<th>사원번호</th>
									<td>
										<input type="text" name="emp_no" value="<?=$rs_emp_no?>" placeholder="사원번호 입력" />
									</td>
									-->
								</tr>
								<tr>
									<th>이름 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td><input type="text" name="adm_name" value="<?=$rs_adm_name?>" placeholder="이름 입력" /></td>
									<th>아이디 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td>
										<input type="text" name="adm_id" value="<?=$rs_adm_id?>" placeholder="아이디 입력" />
										<input type="hidden" name="old_adm_id" value="<?=$rs_adm_id?>">
									</td>
								</tr>

								<tr>
									<th>비밀번호 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<? if ($mode == "S") { ?>
										<input type="password" name="passwd" id="passwd" value=""  autocomplete="off" placeholder="비밀번호 입력" style="background-color:#EFEFEF;" readonly="1"/>
										<span class="tbl_txt">
											<INPUT TYPE="checkbox" name="passwd_chk" id="passwd_chk" value="Y" class="radio"> 비밀번호 변경
										</span>
										<? } else { ?>
										<input type="password" name="passwd" value=""  autocomplete="off" placeholder="비밀번호 입력"/>
										<input type="hidden" name="passwd_chk" id="passwd_chk" value="Y">
										<? } ?>
									</td>
								</tr>

								<tr>
									<th>소속</th>
									<td><?= makeSelectBox($conn,"DEPT","dept_code","125","선택","",$rs_dept_code)?></td>
									<th>직급</th>
									<td><?= makeSelectBox($conn,"POSITION","position_code","125","선택","",$rs_position_code)?></td>
								</tr>

								<tr>
									<th>전화번호</th>
									<td><input type="text" name="adm_phone" class="onlyphone inputphone" value="<?=$rs_adm_phone?>" maxlength="15" /></td>
									<th>휴대전화번호</th>
									<td><input type="text" name="adm_hphone" class="onlyphone inputphone" value="<?=$rs_adm_hphone?>" maxlength="15" /></td>
								</tr>
								<tr>
									<th>이메일</th>
									<td colspan="3"><input type="text" name="adm_email" value="<?=$rs_adm_email?>" /></td>
								</tr>
								<tr>
									<th>입사일</th>
									<td>
										<div class="datepickerbox" style="width:150px">
											<input type="text" class="datepicker" name="enter_date" value="<?=$rs_enter_date?>" autocomplete="off" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10"/>
										</div>
									</td>
									<th>퇴사일</th>
									<td>
										<div class="datepickerbox" style="width:150px">
											<input type="text" class="datepicker" name="out_date" value="<?=$rs_out_date?>" autocomplete="off" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10"/>
										</div>
									</td>
								</tr>
								<tr>
									<th>기타메모</th>
									<td colspan="3" class="subject"><textarea cols="100" rows="5" name="adm_info"><?=$rs_adm_info?></textarea></td>
								</tr>

								<tr>
									<th>사용여부</th>
									<td colspan="3">
										<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용함 <span style="width:20px;"></span>
										<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>> 사용안함
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									</td>
								</tr>
								<? if ($rs_adm_no <> "") { ?>
								<tr>
									<th>최초등록직원</th>
									<td><?=getAdminName($conn, $rs_reg_adm)?></td>
									<th>등록일</th>
									<td><?=$rs_reg_date?></td>
								</tr>

								<tr>
									<th>최종수정직원</th>
									<td><?=getAdminName($conn, $rs_up_adm)?></td>
									<th>수정일</th>
									<td>
										<?
											if ($rs_reg_date <> $rs_up_date) { 
												echo $rs_up_date;
											} 
										?>
									</td>
								</tr>
								<? } ?>
							</tbody>
						</table>
					</div>
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ($adm_no <> "" ) {?>
							<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="sendKeyword();">수정</button>
						<? } ?>
						<? } else {?>
						<? if ($sPageRight_I == "Y") {?>
						<button type="button" class="button" onClick="sendKeyword();">저장</button>
						<? } ?>
						<? }?>

						<? if ($adm_no <> "") {?>
						<?	if ($sPageRight_D == "Y") {?>
						<button type="button" class="button type02" onClick="js_delete();">삭제</button>
						<?	} ?>
						<? }?>

						
					</div>
				</div>

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