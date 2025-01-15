<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : dcode_list_popup.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-20
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
	$pcode_no				= (int)$pcode_no;

	$dcode					= isset($_POST["dcode"]) && $_POST["dcode"] !== '' ? $_POST["dcode"] : (isset($_GET["dcode"]) ? $_GET["dcode"] : '');
	$dcode_no				= isset($_POST["dcode_no"]) && $_POST["dcode_no"] !== '' ? $_POST["dcode_no"] : (isset($_GET["dcode_no"]) ? $_GET["dcode_no"] : '');
	$dcode_no				= (int)$dcode_no;

	$dcode_nm				= isset($_POST["dcode_nm"]) && $_POST["dcode_nm"] !== '' ? $_POST["dcode_nm"] : (isset($_GET["dcode_nm"]) ? $_GET["dcode_nm"] : '');
	$dcode_ext			= isset($_POST["dcode_ext"]) && $_POST["dcode_ext"] !== '' ? $_POST["dcode_ext"] : (isset($_GET["dcode_ext"]) ? $_GET["dcode_ext"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$dcode_seq_no		= isset($_POST["dcode_seq_no"]) && $_POST["dcode_seq_no"] !== '' ? $_POST["dcode_seq_no"] : (isset($_GET["dcode_seq_no"]) ? $_GET["dcode_seq_no"] : '');
	$addcode				= isset($_POST["addcode"]) && $_POST["addcode"] !== '' ? $_POST["addcode"] : (isset($_GET["addcode"]) ? $_GET["addcode"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$dcode_seq_no		= (int)$dcode_seq_no;

	$mode 					= SetStringToDB($mode);
	$pcode					= SetStringToDB($pcode);
	$dcode					= SetStringToDB($dcode);
	$dcode_nm				= SetStringToDB($dcode_nm);
	$dcode_ext			= SetStringToDB($dcode_ext);
	$dcode_seq_no		= SetStringToDB($dcode_seq_no);
	$use_tf					= SetStringToDB($use_tf);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================
	if ($mode == "I") {
		
		$type = "DCODE";
		$result = dupDcode($conn, $dcode, $pcode);

		if ($result == 0) {
		
			$dcode_seq_no = "0";
			#echo "para--->".$pcode." /".$pcode_nm." /".$pcode_memo." /".$pcode_seq_no." /".$use_tf." /".$reg_adm."<br>";
			$result = insertDcode($conn, $pcode, $dcode, $dcode_nm, $dcode_ext, $dcode_seq_no, $use_tf, $_SESSION['s_adm_no']);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "세부코드등록 (".$dcode_nm.") ", "Insert");

		}
		
		$mode = "R";
	}

	if ($mode == "U") {

		//echo $dcode_no."<br>";

		$result = updateDcode($conn, $pcode, $dcode, $dcode_nm, $dcode_ext, $use_tf, $_SESSION['s_adm_no'], $dcode_no);
		$mode = "R";
		
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "세부코드수정 (".$dcode_nm.") ", "Update");

	}

	if ($mode == "T") {

		$result_no = updateDcodeUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $pcode, $dcode_no);

		$mode = "R";

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "세부코드사용여부수정 (".$dcode_nm.") ", "Update");

	}


	if ($mode == "D") {

		$result = deleteDcode($conn, $_SESSION['s_adm_no'], $pcode, $dcode_no);
		$mode = "R";

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "세부코드삭제 (".$dcode_nm.") ", "Delete");

	}


	$rs_pcode_no			= "";
	$rs_pcode					= "";
	$rs_pcode_nm			= "";
	$rs_pcode_memo		= "";
	$rs_pcode_seq_no	= "";
	$rs_use_tf				= "";
	$rs_del_tf				= "";

	if (($mode == "S") || ($mode == "R")) {

		$arr_rs = selectPcode($conn, $pcode_no);

		$rs_pcode_no			= trim($arr_rs[0]["PCODE_NO"]); 
		$rs_pcode					= trim($arr_rs[0]["PCODE"]); 
		$rs_pcode_nm			= trim($arr_rs[0]["PCODE_NM"]); 
		$rs_pcode_memo			= trim($arr_rs[0]["PCODE_MEMO"]); 
		$rs_pcode_seq_no		= trim($arr_rs[0]["PCODE_SEQ_NO"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

	}

	$use_tf			= "";
	$del_tf			= "N";
	$nPage			= "1";
	$nPageSize  = "1000";

	$arr_rs_dcode = listDcode($conn, $rs_pcode, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "세부코드조회", "List");

	if ($result) {
?>	
<script language="javascript">
	//alert('정상 처리 되었습니다.');
	<? if ($addcode == "Y") { ?>
	opener.js_change_dcode('<?=$pcode_no?>');
	<? } ?>

</script>
<?
		//exit;
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
#pop_table_scroll { z-index: 1;  overflow: auto; height: 368px; }
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
		
		var frm = document.frm;
		
		var dcode_no = frm.dcode_no.value;
			
		if (isNull(frm.dcode.value)) {
			alert('코드를 입력해주세요.');
			frm.dcode.focus();
			return ;		
		}

		if (isNull(frm.dcode_nm.value)) {
			alert('코드명을 입력해주세요.');
			frm.dcode_nm.focus();
			return ;		
		}

		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}

		if (isNull(dcode_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}
		
		if (frm.mode.value == "U") {
			bDelOK = confirm('수정 하시겠습니까?');//
			if (bDelOK == false) {	
				return;
			}
		}
		
		//alert(frm.dcode_no.value);

		frm.method = "post";
		frm.target = "";
		frm.action = "dcode_list_popup.php";
		frm.submit();
	}

	function js_delete() {
		
		bDelOK = confirm('정말 삭제 하시겠습니까?');//정말 삭제 하시겠습니까?
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.method = "post";
			frm.target = "";
			frm.action = "dcode_list_popup.php";
			frm.submit();
		} else {
			return;
		}
	}

	function js_view (rn, dcode_no, dcode, dcode_nm,  dcode_ext, use_tf) {
		
		var frm = document.frm;

		$("#btn_delete").show();

		frm.mode.value = "U";
		frm.dcode_no.value = dcode_no;

		frm.dcode.value = dcode;
		frm.old_dcode.value = dcode;
		frm.dcode_nm.value = dcode_nm;
		frm.dcode_ext.value = dcode_ext;

		if (use_tf == "Y") {
			frm.rd_use_tf[0].checked = true;
			frm.use_tf.value = "Y";
		} else {
			frm.rd_use_tf[1].checked = true;
			frm.use_tf.value = "N";
		}

	}

function js_toggle(dcode_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('사용 여부를 변경 하시겠습니까?');
		
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.dcode_no.value = dcode_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "dcode_list_popup.php";
		frm.submit();
	}
}

/*
 * @(#)menu.js
 * 
 * 페이지설명 : 메뉴 순서 바꾸기 스크립트 파일
 * 작성  일자 : 2003.12.01
 */  


var preid = -1;

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 0) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.seq_dcode_no[preid-2].value;
			
				document.frm.seq_dcode_no[preid-2].value = document.frm.seq_dcode_no[preid-1].value;
				document.frm.seq_dcode_no[preid-1].value = tempCode;
			}
		}
		
		//preid = preid - 1;
		js_change_order();

	} else {
		alert("가장 상위에 있습니다. ");
	}
}


function js_down(n) {

	preid = parseInt(n);

	//alert(preid_plus);

	if (preid < document.getElementById("t").rows.length-1) {
		
		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 0) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.seq_dcode_no[preid-1].value;
				document.frm.seq_dcode_no[preid-1].value = document.frm.seq_dcode_no[preid].value;
				document.frm.seq_dcode_no[preid].value = tempCode;
			}
		}
		
		//preid = preid + 1;	
		js_change_order();
	} else{
		alert("가장 하위에 있습니다. ");
	}
}

function js_change_order() {
	
	if(document.getElementById("t").rows.length < 2) {
		alert("순서를 저장할 메뉴가 없습니다");//순서를 저장할 메뉴가 없습니다");
		return;
	}

	document.frm.mode.value = "O";
	document.frm.target = "ifr_hidden";
	document.frm.action = "dcode_order_dml.php";
	document.frm.submit();

	<? if ($addcode == "Y") { ?>
	opener.js_change_dcode('<?=$pcode_no?>');
	<? } ?>

}

	// Ajax
	function sendKeyword() {

		if (frm.old_dcode.value != frm.dcode.value)	{

			var keyword = document.frm.dcode.value+"^"+document.frm.pcode.value;
			
			//alert(keyword);

			if (keyword != '') {
				var params = "keyword="+encodeURIComponent(keyword);

				sendRequest("dcode_dup_check.php", params, displayResult, 'POST');
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
	<h1>세부분류 코드 등록</h1>
	<div class="popcontents">

<form name="frm" method="post">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="pcode" value="<?= $rs_pcode ?>">
<input type="hidden" name="pcode_no" value="<?= $pcode_no ?>">
<input type="hidden" name="addcode" value="<?= $addcode ?>">
<input type="hidden" name="dcode_no" value="">
<input type="hidden" name="seq_no" value="">

		<div class="addr_inp">
			<div class="tbl_style01 left">
				<table>
					<colgroup>
						<col width="20%">
						<col width="30%">
						<col width="20%">
						<col width="30%">
					</colgroup>
					<tr>
						<td colspan="4"><b><?= $rs_pcode_nm ?></b></td>
					</tr>
					<tr>
						<th>코드</th>
						<td>
							<input type="Text" name="dcode" value="" style="width:95%;" required class="txt">
							<input type="hidden" name="old_dcode" value="">
						</td>
						<th>코드명</th>
						<td>
							<input type="Text" name="dcode_nm" value="" style="width:95%;" required class="txt">
						</td>
					</tr>
					<tr>
						<th>기타정보</th>
						<td colspan="3">
							<input type="Text" name="dcode_ext" value="" style="width:95%;" class="txt">
						</td>
					<tr>
					<tr>
						<th>사용여부</th>
						<td colspan="3">
							<input type="radio" name="rd_use_tf" value="Y" checked> 사용<span style="width:20px;"></span>
							<input type="radio" name="rd_use_tf" value="N"> 미사용
							<input type="hidden" name="use_tf" value=""> 
						</td>
					<tr>
				</table>
			</div>
		</div>

		<div class="btn_wrap right">
			<? if (($sPageRight_I == "Y") && ($sPageRight_U == "Y")) { ?>
			<button type="button" class="button" onClick="sendKeyword();">저장</button>
			<? } ?>
			<? if ($sPageRight_D == "Y") { ?>
			<button type="button" class="button type02" onClick="js_delete();" id="btn_delete" style="display:none;">삭제</button>
			<? } ?>
			<button type="button" class="button type03" onClick="self.close();">닫기</button>
		</div>

		<div class="addr_inp">
			<div id="pop_table_list">
				<div id="pop_table_scroll">
					<div class="tbl_style01 left">
						<table id='t'>
							<colgroup>
								<col width="20%">
								<col width="30%">
								<col width="30%">
								<col width="20%">
							</colgroup>
							<thead>
								<tr>
									<th>NO.</th>
									<th>코드</th>
									<th>코드명</th>
									<th>사용여부</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs_dcode) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs_dcode); $j++) {
										
										#rn, DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_SEQ_NO, 
										#USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

										$rn							= trim($arr_rs_dcode[$j]["rn"]);
										$DCODE_NO				= trim($arr_rs_dcode[$j]["DCODE_NO"]);
										$PCODE					= trim($arr_rs_dcode[$j]["PCODE"]);
										$DCODE					= trim($arr_rs_dcode[$j]["DCODE"]);
										$DCODE_NM				= trim($arr_rs_dcode[$j]["DCODE_NM"]);
										$DCODE_EXT			= trim($arr_rs_dcode[$j]["DCODE_EXT"]);
										$DCODE_SEQ_NO		= trim($arr_rs_dcode[$j]["DCODE_SEQ_NO"]);
										$USE_TF					= trim($arr_rs_dcode[$j]["USE_TF"]);
										$DEL_TF					= trim($arr_rs_dcode[$j]["DEL_TF"]);
										$REG_DATE				= trim($arr_rs_dcode[$j]["REG_DATE"]);

										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							

										if ($USE_TF == "Y") {
											$STR_USE_TF = "사용";
										} else {
											$STR_USE_TF = "사용안함";
										}
							?>
								<tr>
									<td style="text-align:center;"><span><?=$rn?></span> 
										<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/common/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/common/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td style="text-align:center;">
										<a href="javascript:js_view('<?= $rn ?>','<?= $DCODE_NO ?>','<?= $DCODE ?>','<?= $DCODE_NM ?>','<?= $DCODE_EXT ?>','<?= $USE_TF ?>');"><?= $DCODE ?></a>
										<input type="hidden" name="seq_dcode_no" value="<?=$DCODE_NO?>">
										<input type="hidden" name="dcode_seq_no[]" value="<?=$DCODE_NO?>">
									</td>
									<td style="text-align:center;"><?= $DCODE_NM?></td>
									<td style="text-align:center;"><a href="javascript:js_toggle('<?=$DCODE_NO?>','<?=$USE_TF?>');"><?=$STR_USE_TF?></a></td>
								</tr>
							<?			
									}
								} else { 
							?> 
								<tr>
									<td height="50" align="center" colspan="7">데이터가 없습니다. </td>
								</tr>
							<? 
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>