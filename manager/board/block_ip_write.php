<?session_start();?>
<?
# =============================================================================
# File Name    : block_ip_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2024-03-05
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
	$menu_right = "SY004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/board/block_ip.php";

#====================================================================
# DML Process
#====================================================================

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$seq_no					= isset($_POST["seq_no"]) && $_POST["seq_no"] !== '' ? $_POST["seq_no"] : (isset($_GET["seq_no"]) ? $_GET["seq_no"] : '');

	$block_ip				= isset($_POST["block_ip"]) && $_POST["block_ip"] !== '' ? $_POST["block_ip"] : (isset($_GET["block_ip"]) ? $_GET["block_ip"] : '');
	$memo						= isset($_POST["memo"]) && $_POST["memo"] !== '' ? $_POST["memo"] : (isset($_GET["memo"]) ? $_GET["memo"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	
	$result = false;

	$mode			= SetStringToDB($mode);
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "I") {

		$arr_data = array("BLOCK_IP"=>$block_ip,
											"MEMO"=>$memo,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  insertBoardBlockIP($conn, $arr_data);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 허용 IP 등록 (IP : ".$block_ip.") ", "Insert");

	}

	if ($mode == "U") {

		$arr_data = array("BLOCK_IP"=>$block_ip,
											"MEMO"=>$memo,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result = updateBoardBlockIP($conn, $arr_data, $seq_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 허용 IP 수정 (IP : ".$block_ip.") ", "Update");

	}

	if ($mode == "T") {

		$result = updateBoardBlockIPUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $seq_no);

	}

	if ($mode == "D") {
		$result = deleteBoardBlockIP($conn, $_SESSION['s_adm_no'], $seq_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 허용 IP 삭제 처리 (일련 번호 : ".$seq_no.") ", "Delete");
	}

	$rs_seq_no					= "";
	$rs_block_ip				= "";
	$rs_memo						= "";
	$rs_use_tf					= "";
	$rs_del_tf					= "";
	$rs_reg_adm					= "";
	$rs_reg_date				= "";
	$rs_up_adm					= "";
	$rs_up_date					= "";

	if ($mode == "S") {

		$arr_rs = selectBoardBlockIP($conn, $seq_no);

		$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_block_ip				= trim($arr_rs[0]["BLOCK_IP"]); 
		$rs_memo						= SetStringFromDB($arr_rs[0]["MEMO"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm					= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date					= trim($arr_rs[0]["UP_DATE"]); 

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 허용 IP 조회 (조회 아이디 : ".$_SESSION['s_adm_id'].") ", "Read");

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
		document.location.href = "block_ip_list.php<?=$strParam?>";
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
	frm.action = "block_ip_list.php";
	frm.submit();
}


function js_save() {

	var frm = document.frm;
	var seq_no = "<?=$seq_no?>";
	
	frm.block_ip.value = frm.block_ip.value.trim();

	if (frm.block_ip.value == "") {
		alert('IP를 입력해 주세요.');
		frm.block_ip.focus();
		return ;		
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

	if (seq_no == "") {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}

	//alert(frm.mode.value);

	frm.target = "";
	frm.action = "block_ip_write.php";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('IP를 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "block_ip_write.php";
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="menu_cd" value="" >
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
									<th>차단 IP <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><input type="text" name="block_ip" value="<?=$rs_block_ip?>" placeholder="IP 입력" /></td>
								</tr>
								<tr>
									<th>메모</th>
									<td colspan="3"><input type="text" name="memo" value="<?=$rs_memo?>" placeholder="메모 입력" style="width:80%"/></td>
								</tr>

								<tr>
									<th>사용여부</th>
									<td colspan="3">
										<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용함 <span style="width:20px;"></span>
										<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>> 사용안함
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ((int)$seq_no <> "" ) {?>
							<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="js_save();">수정</button>
						<? } ?>
						<? } else {?>
						<? if ($sPageRight_I == "Y") {?>
						<button type="button" class="button" onClick="js_save();">저장</button>
						<? } ?>
						<? }?>

						<? if ((int)$seq_no <> "") {?>
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