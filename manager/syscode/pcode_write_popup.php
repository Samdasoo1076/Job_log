<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : pcode_write_popup.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-21
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SY002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 


#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/syscode/syscode.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');

	$pcode					= isset($_POST["pcode"]) && $_POST["pcode"] !== '' ? $_POST["pcode"] : (isset($_GET["pcode"]) ? $_GET["pcode"] : '');
	$pcode_no				= isset($_POST["pcode_no"]) && $_POST["pcode_no"] !== '' ? $_POST["pcode_no"] : (isset($_GET["pcode_no"]) ? $_GET["pcode_no"] : '');
	$pcode_nm				= isset($_POST["pcode_nm"]) && $_POST["pcode_nm"] !== '' ? $_POST["pcode_nm"] : (isset($_GET["pcode_nm"]) ? $_GET["pcode_nm"] : '');
	$pcode_memo			= isset($_POST["pcode_memo"]) && $_POST["pcode_memo"] !== '' ? $_POST["pcode_memo"] : (isset($_GET["pcode_memo"]) ? $_GET["pcode_memo"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$pcode_seq_no		= isset($_POST["pcode_seq_no"]) && $_POST["pcode_seq_no"] !== '' ? $_POST["pcode_seq_no"] : (isset($_GET["pcode_seq_no"]) ? $_GET["pcode_seq_no"] : '');

	$pcode_seq_no		= (int)$pcode_seq_no;

	$mode 				= SetStringToDB($mode);
	$pcode				= SetStringToDB($pcode);
	$pcode_nm			= SetStringToDB($pcode_nm);
	$pcode_memo		= SetStringToDB($pcode_memo);
	$pcode_seq_no	= SetStringToDB($pcode_seq_no);
	$use_tf				= SetStringToDB($use_tf);

	$pcode_no			= SetStringToDB($pcode_no);

	//echo $mode;
	
	$result = false;
#====================================================================
# DML Process
#====================================================================
	if ($mode == "I") {
		
		$pcode_seq_no = "0";
		
		//echo "para--->".$pcode." ".$pcode_nm." ".$pcode_memo." ".$pcode_seq_no." ".$use_tf." ".$reg_adm."<br>";

		$result = insertPcode($conn, $g_site_no, $pcode, $pcode_nm, $pcode_memo, $pcode_seq_no, $use_tf, $_SESSION['s_adm_no']);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "대분류코드 등록 (".$pcode.")", "Insert");

		//exit;

	}

	$rs_pcode_no			= "";
	$rs_pcode					= "";
	$rs_pcode_nm			= "";
	$rs_pcode_memo		= "";
	$rs_pcode_seq_no	= "";
	$rs_use_tf				= "";
	$rs_del_tf				= "";


	if ($mode == "S") {

		$arr_rs = selectPcode($conn, $pcode_no);

		$rs_pcode_no			= trim($arr_rs[0]["PCODE_NO"]); 
		$rs_pcode					= trim($arr_rs[0]["PCODE"]); 
		$rs_pcode_nm			= trim($arr_rs[0]["PCODE_NM"]); 
		$rs_pcode_memo		= trim($arr_rs[0]["PCODE_MEMO"]); 
		$rs_pcode_seq_no	= trim($arr_rs[0]["PCODE_SEQ_NO"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

	}

	if ($mode == "U") {
		$result = updatePcode($conn, $g_site_no, $pcode, $pcode_nm, $pcode_memo, $pcode_seq_no, $use_tf, $_SESSION['s_adm_no'], $pcode_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "대분류코드 수정 (".$pcode_no.")", "Update");

	}

	if ($mode == "D") {
		$result = deletePcode($conn, $_SESSION['s_adm_no'], $pcode_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "대분류코드 삭제 (".$pcode_no.")", "Delete");

	}

	if ($result) {
?>	
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		opener.js_search();
		self.close();
</script>
<?
		exit;
	}	
?>
<!DOCTYPE HTML>
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
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  height: 220; background-color:#f7f7f7; overflow: auto; height: 325px; border:1px solid #d1d1d1;}
-->
</style>
<script language="javascript">

function getXMLHttpRequest() {
	if (window.ActiveXObject) {
		try {
			return new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
	
		try {
			return new ActiveXObject("Microsoft.XMLHTTP");
		} catch(e1) { return null; }
	}
	
	} else if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	} else {
	return null;
	}
}

var httpRequest = null;

function sendRequest(url, params, callback, method) {
	
	httpRequest = getXMLHttpRequest();
	var httpMethod = method ? method : 'GET';
	
	if (httpMethod != 'GET' && httpMethod != 'POST') {
		httpMethod = 'GET';
	}
	
	var httpParams = (params == null || params == '') ? null : params;
	var httpUrl = url;
	
	if (httpMethod == 'GET' && httpParams != null) {
		httpUrl = httpUrl + "?" + httpParams;
	}
	
	httpRequest.open(httpMethod, httpUrl, true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.onreadystatechange = callback;
	httpRequest.send(httpMethod == 'POST' ? httpParams : null);
}

	// 저장 버튼 클릭 시 
	function js_save() {
		
		var pcode_no = "<?= $pcode_no ?>";
		var frm = document.frm;
		
		if (isNull(frm.pcode.value)) {
			alert('코드를 입력해주세요.');
			frm.pcode.focus();
			return ;		
		}

		if (isNull(frm.pcode_nm.value)) {
			alert('코드명을 입력해주세요.');
			frm.pcode_nm.focus();
			return ;		
		}

		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}

		if (isNull(pcode_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

		frm.method = "post";
		frm.action = "pcode_write_popup.php";
		frm.submit();
	}

	function js_delete() {
		
		bDelOK = confirm('정말 삭제 하시겠습니까?');//정말 삭제 하시겠습니까?
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.method = "post";
			frm.action = "pcode_write_popup.php";
			frm.submit();
		} else {
			return;
		}
	}

	// Ajax
	function sendKeyword() {

		if (frm.old_pcode.value != frm.pcode.value)	{

			var keyword = document.frm.pcode.value;

			//alert(keyword);
						
			if (keyword != '') {
				var params = "keyword="+encodeURIComponent(keyword);
			
				//alert(params);
				sendRequest("pcode_dup_check.php", params, displayResult, 'POST');
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
					alert("사용중인 코드 입니다.");
					return;
				} else {
					js_save();
				}
			} else {
				alert("에러 발생: "+httpRequest.status);
			}
		}
	}
</script>

</head>
<body id="popup">
<div class="popupwrap">
	<h1>대분류 코드 등록</h1>
	<div class="popcontents">
		<div class="tbl_style01 left">

<body id="popup_code" onload="frm.pcode.focus();">
<form name="frm" method="post">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="pcode_no" value="<?= $pcode_no ?>">

			<table>
				<colgroup>
					<col width="20%">
					<col width="80%">
				</colgroup>
				<tr>
					<td class="lpd20 left bu03">코드</td>
					<td colspan="3" class="lpd20 rpd20 right">
						<input type="Text" name="pcode" value="<?= $rs_pcode ?>" style="width:95%;" required class="txt">
						<input type="hidden" name="old_pcode" value="<?= $rs_pcode ?>">
					</td>
				</tr>
				<tr>
					<td class="lpd20 left bu03">코드명</td>
					<td colspan="3" class="lpd20 rpd20 right">
						<input type="Text" name="pcode_nm" value="<?= $rs_pcode_nm ?>" style="width:95%;" required class="txt">
					</td>
				</tr>
				<tr>
					<td class="lpd20 left">코드설명</td>
					<td colspan="3" class="lpd20 rpd20 right">
						<textarea name="pcode_memo" class="txt" style="width:95%" rows="4"><?= $rs_pcode_memo ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="lpd20 left">사용여부</td>
					<td class="lpd20 right">
						<input type="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
						<input type="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>> 미사용
						<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
					</td>
				<tr>
			</table>
		</div>

		<div class="btn_wrap right">
			<? if (($sPageRight_I == "Y") && ($sPageRight_U == "Y")) { ?>
			<button type="button" class="button" onClick="sendKeyword();">저장</button>
			<? } ?>
			<? if (($sPageRight_D == "Y") && ($pcode_no <> "")){ ?>
			<button type="button" class="button type02" onClick="js_delete();">삭제</button>
			<? } ?>
			<button type="button" class="button type03" onClick="self.close();">닫기</button>
		</div>

	</div>
</div>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>