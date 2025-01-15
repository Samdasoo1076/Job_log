<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");

# =============================================================================
# File Name    : pop_holiday.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-07-03
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
	$cp_no=$_SESSION["s_adm_com_code"];
#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SY004"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/holiday/holiday.php";
	require "../../_classes/biz/admin/admin.php";

	$mode				= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$sch_date		= isset($_POST["sch_date"]) && $_POST["sch_date"] !== '' ? $_POST["sch_date"] : (isset($_GET["sch_date"]) ? $_GET["sch_date"] : '');
	$is_holiday	= isset($_POST["is_holiday"]) && $_POST["is_holiday"] !== '' ? $_POST["is_holiday"] : (isset($_GET["is_holiday"]) ? $_GET["is_holiday"] : '');
	$title			= isset($_POST["title"]) && $_POST["title"] !== '' ? $_POST["title"] : (isset($_GET["title"]) ? $_GET["title"] : '');

	$mode				= trim($mode);
	$sch_date		= trim($sch_date);
	$is_holiday	= trim($is_holiday);

	$title			= SetStringToDB($title);

	if ($mode == "I") {

		$hit_cnt = 0;

		$result = insertHoliday($conn, $sch_date, $is_holiday, $title, $_SESSION["s_adm_no"]);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "휴일 관리 등록", "Insert");

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("저장 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($mode == "U") {

		$result = updateSchedule($conn, $sch_cate, $sch_date, $title, $place, $from_hh, $from_mm, $to_hh, $to_mm, $contents, $addr, $lat, $lng, $temp01, $temp02, $temp03, $use_tf, $_SESSION["s_adm_no"], $seq_no);

		$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], " [".$title."]휴일 관리 수정", "Update");

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("수정 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($mode == "D") {

		$result = deleteHoliday($conn, $sch_date);

		$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], " [".$sch_date."]일 휴일 관리 삭제", "Delete");

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("삭제 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($sch_date <> "") {

		$arr_rs = selectHoliday($conn, $sch_date);

		$rs_h_date			= ""; 
		$rs_is_holiday	= "";
		$rs_title				= "";
		$rs_reg_adm			= "";
		$rs_reg_date		= "";

		if ($arr_rs) {
			$rs_h_date			= trim($arr_rs[0]["H_DATE"]); 
			$rs_is_holiday	= trim($arr_rs[0]["IS_HOLIDAY"]); 
			$rs_title				= trim($arr_rs[0]["TITLE"]); 
			$rs_reg_adm			= trim($arr_rs[0]["REG_ADM"]); 
			$rs_reg_date		= trim($arr_rs[0]["REG_DATE"]); 
		}
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">

function isNull_pop(str) {
	str = str.replace(/\s/g, "");
	return ((str == null || str == "" || str == "<undefined>" || str == "undefined") ? true:false);
}

function js_save() {

	var frm = document.frm;

	frm.title.value		= frm.title.value.trim();


	if (!chk_value(frm.title, '내용을 입력해주세요.')) return;

	frm.mode.value = "I";

	if (frm.rd_is_holiday[0].checked == true) {
		frm.is_holiday.value = "Y";
	} else if(frm.rd_is_holiday[1].checked == true){
		frm.is_holiday.value = "N";
	} 

	frm.target = "";
	frm.action = "<?=$_SERVER["PHP_SELF"]?>";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('일정을 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "<?=$_SERVER["PHP_SELF"]?>";
		frm.submit();
	}
}

</script>

</head>
<body id="popup">
<div class="popupwrap">
	<h1>휴일등록</h1>
	<div class="popcontents">

<form name="frm" method="post">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="sch_date" value="<?= $sch_date ?>">

		<div class="tbl_style01 left">
			<table>
				<colgroup>
					<col style="width:15%" />
					<col style="width:auto" />
				</colgroup>
				<tbody>
					<tr>
						<th>휴일구분</th>
						<td>
							<div class="wrap_check">
								<div><input type="radio" name="rd_is_holiday" id="is_holiday_01" value="Y" <? if (($rs_is_holiday == "") || ($rs_is_holiday == "Y")) echo "checked"; ?>><label for="is_holiday_01">휴일</label></div>
								<div><input type="radio" name="rd_is_holiday" id="is_holiday_02" value="N" <? if ($rs_is_holiday =="N") echo "checked"; ?>><label for="is_holiday_02">휴일아님</label></div>
								<input type="hidden" name="is_holiday" value="<?= $rs_is_holiday?>"> 
							</div>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<div class="fl_wrap">
								<input type="text" name="title" id="title" value="<?= $rs_title ?>" style="width:95%;" required class="txt" style="ime-mode:active">
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

</form>

		<div class="btn_wrap right">
			<? if ($sPageRight_I == "Y") { ?>
			<button type="button" class="button type02" onClick="js_save()">등록</button>
			<? } ?>
			<? if ($sPageRight_D == "Y") { ?>
			<button type="button" class="button type02" onClick="js_delete()">삭제</button>
			<? } ?>
			<button type="button" class="button type03" onClick="self.close()">닫기</button>
		</div>

	</div>
</div>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>