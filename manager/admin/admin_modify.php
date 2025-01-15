<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : admin_modify.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-06-12
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD006"; // 메뉴마다 셋팅 해 주어야 합니다

	$result = false;

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

/*
	$sPageRight_		= "Y";
	$sPageRight_R		= "Y";
	$sPageRight_I		= "Y";
	$sPageRight_U		= "Y";
	$sPageRight_D		= "Y";
	$sPageRight_F		= "Y";
*/
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";

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

	$search_str					= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	$adm_name	= SetStringToDB($adm_name);
	$adm_info	= SetStringToDB($adm_info);

	#echo $adm_no;

#====================================================================
	$savedir1 = $g_physical_path."upload_data/profile";
#====================================================================

	if ($mode == "") $mode = "S";

	if ($mode == "U") {

		$arr_data = array("ADM_NAME"=>$adm_name,
											"DEPT_CODE"=>$dept_code,
											"POSITION_CODE"=>$position_code,
											"ADM_PHONE"=>$adm_phone,
											"ADM_HPHONE"=>$adm_hphone,
											"ADM_EMAIL"=>$adm_email,
											"ENTER_DATE"=>$enter_date,
											"OUT_DATE"=>$out_date,
											"ADM_INFO"=>$adm_info,
											"UP_ADM"=>$_SESSION['s_adm_no']
											);

		$result = updateAdmin($conn, $arr_data, $_SESSION['s_adm_no']);

		$passwd_enc = encrypt($key, $iv, $passwd);

		if($passwd_chk=="Y") updateAdminPwd($conn, $passwd_enc, $_SESSION['s_adm_no'], (int)$adm_no);

		$mode = "S";

	}



	if ($mode == "S") {

		$arr_rs = selectAdmin($conn, $_SESSION['s_adm_no']);

		//ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
		//GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE

		$rs_adm_no					= trim($arr_rs[0]["ADM_NO"]); 
		$rs_adm_id					= trim($arr_rs[0]["ADM_ID"]); 
		$rs_passwd					= trim($arr_rs[0]["PASSWD"]); 
		$rs_adm_name				= SetStringFromDB($arr_rs[0]["ADM_NAME"]); 
		$rs_adm_info				= SetStringFromDB($arr_rs[0]["ADM_INFO"]); 
		$rs_adm_hphone			= trim($arr_rs[0]["ADM_HPHONE"]); 
		$rs_adm_phone				= trim($arr_rs[0]["ADM_PHONE"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_enter_date			= trim($arr_rs[0]["ENTER_DATE"]); 
		$rs_out_date				= trim($arr_rs[0]["OUT_DATE"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_adm_flag				= trim($arr_rs[0]["ADM_FLAG"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_profile					= trim($arr_rs[0]["PROFILE"]); 

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "admin_modify.php";
</script>
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

function js_save() {

	var frm = document.frm;
	var adm_no = "<?=$_SESSION['s_adm_no']?>";
	
	frm.adm_name.value = frm.adm_name.value.trim();
	frm.adm_id.value = frm.adm_id.value.trim();
	frm.passwd.value = frm.passwd.value.trim();

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

	if (frm.passwd_chk.checked) {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
			return ;
		}
	}

	if (isNull(adm_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.adm_no.value = frm.adm_no.value;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER['PHP_SELF']?>";
	frm.submit();

}

	// Ajax
function sendKeyword() {

	if (frm.old_adm_id.value != frm.adm_id.value)	{

		var keyword = document.frm.adm_id.value;

		//alert(keyword);
					
		if (keyword != '') {
			var params = "keyword="+encodeURIComponent(keyword);
		
			//alert(params);
			sendRequest("admin_dup_check.php", params, displayResult, 'POST');
		}
		//setTimeout("sendKeyword();", 100);
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
				alert("사용중인 권한 코드 입니다.");
				return;
			} else {
				js_save();
			}
		} else {
			alert("에러 발생: "+httpRequest.status);
		}
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
<input type="hidden" name="mode" value="" />
<input type="hidden" name="adm_no" value="<?=$s_adm_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
<input type="hidden" name="group_no" value="<?= $rs_group_no ?>"> 

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:15%" />
								<col style="width:35%" />
								<col style="width:15%" />
								<col style="width:35%" />
							</colgroup>

							<tbody>

								<tr>
									<th>이름 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td><input type="text" name="adm_name" value="<?=$rs_adm_name?>" placeholder="이름 입력" /></td>
									<th>아이디 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td>
										<input type="hidden" name="adm_id" value="<?=$rs_adm_id?>" /><?=$rs_adm_id?>
										<input type="hidden" name="old_adm_id" value="<?=$rs_adm_id?>">
									</td>
								</tr>

								<tr>
									<th>비밀번호</th>
									<td colspan="3">
										<input type="password" class="txt" name="passwd" id="passwd" value="" autocomplete="off" placeholder="비밀번호 입력" style="background-color:#EFEFEF;" readonly="1" />
										<span class="tbl_txt"><input type="checkbox" name="passwd_chk" id="passwd_chk" value="Y" class="check"> <label>비밀번호 변경</label></span>
									</td>
								</tr>
								<tr>
									<th>부서</th>
									<td><?= makeSelectBox($conn,"DEPT","dept_code","","선택","",$rs_dept_code)?></td>
									<th>직급</th>
									<td><?= makeSelectBox($conn,"POSITION","position_code","","선택","",$rs_position_code)?></td>
								</tr>
								<tr>
									<th>전화번호</th>
									<td><input type="text" class="txt" name="adm_phone" value="<?=$rs_adm_phone?>" class="onlyphone inputphone" maxlength="15" /></td>
									<th>휴대전화번호</th>
									<td><input type="text" class="txt" name="adm_hphone" value="<?=$rs_adm_hphone?>" class="onlyphone inputphone" maxlength="15" /></td>
								</tr>
								<tr>
									<th>이메일</th>
									<td colspan="3"><input type="text" class="txt" name="adm_email" value="<?=$rs_adm_email?>" /></td>
								</tr>
								<tr>
									<th>입사일</th>
									<td>
										<div class="datepickerbox" style="width:150px">
											<input type="text" class="datepicker" name="enter_date" value="<?=$rs_enter_date?>" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10" autocomplete="off"/>
										</div>
									</td>
									<th>퇴사일</th>
									<td>
										<div class="datepickerbox" style="width:150px">
											<input type="text" class="datepicker" name="out_date" value="<?=$rs_out_date?>" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10" autocomplete="off"/>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btn_wrap">
						<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="sendKeyword();">수정</button>
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
<script>

</script>

<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>