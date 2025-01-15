<?session_start();?>
<?
# =============================================================================
# File Name    : school_config_write.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-10-19
# Modify Date  : 2021-03-15
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
	$menu_right = "HI002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/config/config.php";

#====================================================================
# DML Process
#====================================================================

	$mode										= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$c_no										= isset($_POST["c_no"]) && $_POST["c_no"] !== '' ? $_POST["c_no"] : (isset($_GET["c_no"]) ? $_GET["c_no"] : '');
	$briefing_start					= isset($_POST["briefing_start"]) && $_POST["briefing_start"] !== '' ? $_POST["briefing_start"] : (isset($_GET["briefing_start"]) ? $_GET["briefing_start"] : '');
	$briefing_end						= isset($_POST["briefing_end"]) && $_POST["briefing_end"] !== '' ? $_POST["briefing_end"] : (isset($_GET["briefing_end"]) ? $_GET["briefing_end"] : '');
	$briefing_alert					= isset($_POST["briefing_alert"]) && $_POST["briefing_alert"] !== '' ? $_POST["briefing_alert"] : (isset($_GET["briefing_alert"]) ? $_GET["briefing_alert"] : '');
	$briefing_disabled_date	= isset($_POST["briefing_disabled_date"]) && $_POST["briefing_disabled_date"] !== '' ? $_POST["briefing_disabled_date"] : (isset($_GET["briefing_disabled_date"]) ? $_GET["briefing_disabled_date"] : '');
	$briefing_tf						= isset($_POST["briefing_tf"]) && $_POST["briefing_tf"] !== '' ? $_POST["briefing_tf"] : (isset($_GET["briefing_tf"]) ? $_GET["briefing_tf"] : '');
	$tour_start							= isset($_POST["tour_start"]) && $_POST["tour_start"] !== '' ? $_POST["tour_start"] : (isset($_GET["tour_start"]) ? $_GET["tour_start"] : '');
	$tour_end								= isset($_POST["tour_end"]) && $_POST["tour_end"] !== '' ? $_POST["tour_end"] : (isset($_GET["tour_end"]) ? $_GET["tour_end"] : '');
	$tour_start_able				= isset($_POST["tour_start_able"]) && $_POST["tour_start_able"] !== '' ? $_POST["tour_start_able"] : (isset($_GET["tour_start_able"]) ? $_GET["tour_start_able"] : '');
	$tour_end_able					= isset($_POST["tour_end_able"]) && $_POST["tour_end_able"] !== '' ? $_POST["tour_end_able"] : (isset($_GET["tour_end_able"]) ? $_GET["tour_end_able"] : '');
	$tour_alert							= isset($_POST["tour_alert"]) && $_POST["tour_alert"] !== '' ? $_POST["tour_alert"] : (isset($_GET["tour_alert"]) ? $_GET["tour_alert"] : '');
	$tour_disabled_date			= isset($_POST["tour_disabled_date"]) && $_POST["tour_disabled_date"] !== '' ? $_POST["tour_disabled_date"] : (isset($_GET["tour_disabled_date"]) ? $_GET["tour_disabled_date"] : '');
	$tour_tf								= isset($_POST["tour_tf"]) && $_POST["tour_tf"] !== '' ? $_POST["tour_tf"] : (isset($_GET["tour_tf"]) ? $_GET["tour_tf"] : '');

	$briefing_s_date				= isset($_POST["briefing_s_date"]) && $_POST["briefing_s_date"] !== '' ? $_POST["briefing_s_date"] : (isset($_GET["briefing_s_date"]) ? $_GET["briefing_s_date"] : '');
	$briefing_s_hour				= isset($_POST["briefing_s_hour"]) && $_POST["briefing_s_hour"] !== '' ? $_POST["briefing_s_hour"] : (isset($_GET["briefing_s_hour"]) ? $_GET["briefing_s_hour"] : '');
	$briefing_s_min					= isset($_POST["briefing_s_min"]) && $_POST["briefing_s_min"] !== '' ? $_POST["briefing_s_min"] : (isset($_GET["briefing_s_min"]) ? $_GET["briefing_s_min"] : '');
	$briefing_e_date				= isset($_POST["briefing_e_date"]) && $_POST["briefing_e_date"] !== '' ? $_POST["briefing_e_date"] : (isset($_GET["briefing_e_date"]) ? $_GET["briefing_e_date"] : '');
	$briefing_e_hour				= isset($_POST["briefing_e_hour"]) && $_POST["briefing_e_hour"] !== '' ? $_POST["briefing_e_hour"] : (isset($_GET["briefing_e_hour"]) ? $_GET["briefing_e_hour"] : '');
	$briefing_e_min					= isset($_POST["briefing_e_min"]) && $_POST["briefing_e_min"] !== '' ? $_POST["briefing_e_min"] : (isset($_GET["briefing_e_min"]) ? $_GET["briefing_e_min"] : '');

	$tour_s_date						= isset($_POST["tour_s_date"]) && $_POST["tour_s_date"] !== '' ? $_POST["tour_s_date"] : (isset($_GET["tour_s_date"]) ? $_GET["tour_s_date"] : '');
	$tour_s_hour						= isset($_POST["tour_s_hour"]) && $_POST["tour_s_hour"] !== '' ? $_POST["tour_s_hour"] : (isset($_GET["tour_s_hour"]) ? $_GET["tour_s_hour"] : '');
	$tour_s_min							= isset($_POST["tour_s_min"]) && $_POST["tour_s_min"] !== '' ? $_POST["tour_s_min"] : (isset($_GET["tour_s_min"]) ? $_GET["tour_s_min"] : '');
	$tour_e_date						= isset($_POST["tour_e_date"]) && $_POST["tour_e_date"] !== '' ? $_POST["tour_e_date"] : (isset($_GET["tour_e_date"]) ? $_GET["tour_e_date"] : '');
	$tour_e_hour						= isset($_POST["tour_e_hour"]) && $_POST["tour_e_hour"] !== '' ? $_POST["tour_e_hour"] : (isset($_GET["tour_e_hour"]) ? $_GET["tour_e_hour"] : '');
	$tour_e_min							= isset($_POST["tour_e_min"]) && $_POST["tour_e_min"] !== '' ? $_POST["tour_e_min"] : (isset($_GET["tour_e_min"]) ? $_GET["tour_e_min"] : '');
	
	$tour_info_01						= isset($_POST["tour_info_01"]) && $_POST["tour_info_01"] !== '' ? $_POST["tour_info_01"] : (isset($_GET["tour_info_01"]) ? $_GET["tour_info_01"] : '');
	$tour_info_02						= isset($_POST["tour_info_02"]) && $_POST["tour_info_02"] !== '' ? $_POST["tour_info_02"] : (isset($_GET["tour_info_02"]) ? $_GET["tour_info_02"] : '');
	
#====================================================================
	$savedir1 = $g_physical_path."upload_data/config";
#====================================================================

	$result = false;

	$mode										= SetStringToDB($mode);
	$c_no										= SetStringToDB($c_no);
	$briefing_alert					= SetStringToDB($briefing_alert);
	$briefing_disabled_date	= SetStringToDB($briefing_disabled_date);
	$tour_alert							= SetStringToDB($tour_alert);
	$tour_disabled_date			= SetStringToDB($tour_disabled_date);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "U") {

		$arr_data = array("BRIEFING_START"=>$briefing_start,
											"BRIEFING_END"=>$briefing_end,
											"BRIEFING_ALERT"=>$briefing_alert,
											"BRIEFING_DISABLED_DATE"=>$briefing_disabled_date,
											"BRIEFING_TF"=>$briefing_tf,
											"TOUR_START"=>$tour_start,
											"TOUR_END"=>$tour_end,
											"TOUR_ALERT"=>$tour_alert,
											"TOUR_DISABLED_DATE"=>$tour_disabled_date,
											"TOUR_TF"=>$tour_tf,
											"UP_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateConfig($conn, $arr_data, $c_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '환경설정 수정 (제목 : '.$c_title.') ', 'Update');
	}

	if ($mode == "") {

		$arr_rs = selectConfig($conn, 1); 

		$rs_c_no										= trim($arr_rs["C_NO"]); 
		$rs_briefing_start					= SetStringFromDB($arr_rs["BRIEFING_START"]); 
		$rs_briefing_end						= SetStringFromDB($arr_rs["BRIEFING_END"]); 
		$rs_briefing_alert					= SetStringFromDB($arr_rs["BRIEFING_ALERT"]); 
		$rs_briefing_disabled_date	= SetStringFromDB($arr_rs["BRIEFING_DISABLED_DATE"]); 
		$rs_briefing_tf							= SetStringFromDB($arr_rs["BRIEFING_TF"]); 
		$rs_tour_start							= SetStringFromDB($arr_rs["TOUR_START"]); 
		$rs_tour_end								= SetStringFromDB($arr_rs["TOUR_END"]); 
		$rs_tour_alert							= SetStringFromDB($arr_rs["TOUR_ALERT"]); 
		$rs_tour_disabled_date			= SetStringFromDB($arr_rs["TOUR_DISABLED_DATE"]); 
		$rs_tour_tf									= SetStringFromDB($arr_rs["TOUR_TF"]); 
		
		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '환경설정 조회', 'Read');

	}

	if ($result) {

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "school_config_write.php";
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
<title>고교대학연계 설정 관리</title>
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

function js_save() {

	var frm = document.frm;
	var c_no = 1;

/*
	if (!frm.c_title.value.trim()){
		alert('학년도를 입력해주세요.');
		frm.c_title.focus();
		return ;		
	}
	if (!frm.c_subtitle.value.trim()){
		alert('부제목을 입력해주세요.');
		frm.c_subtitle.focus();
		return ;		
	}
*/
/*
	if (!frm.c_noti_title.value.trim()){
		alert('광운알리미 제목을 입력해주세요.');
		frm.c_noti_title.focus();
		return ;		
	}

	if (!frm.c_noti_subtitle.value.trim()){
		alert('광운알리미 부제목을 입력해주세요.');
		frm.c_noti_subtitle.focus();
		return ;		
	}
*/

/*
	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}
*/
	frm.target = "";
	frm.method = "post";
	frm.action = "<?=$_SERVER['PHP_SELF']?>";

	frm.mode.value = "U";

	frm.submit();

}

function js_fileView(obj) {
	
	var frm = document.frm;

	if (obj.selectedIndex == 1) {
		document.getElementById("file_change01").style.display = "inline";
	} else {
		document.getElementById("file_change01").style.display = "none";
	}

}

function js_fileView_1(obj) {
	
	var frm = document.frm;

	if (obj.selectedIndex == 1) {
		document.getElementById("file_change02").style.display = "inline";
	} else {
		document.getElementById("file_change02").style.display = "none";
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
<input type="hidden" name="c_no" value="1" />
<input type="hidden" name="mode" value="" />

				<div class="cont">
					<div class="tit_h4 first"><h4>찾아가는 입학설명회 설정</h4></div>
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
									<th>찾아가는 입학설명회 사용여부 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="radio" id="briefing_Y" name="briefing_tf" value="Y" <? if ($rs_briefing_tf == "Y") echo "checked"; ?> class="radio" /> <label for="briefing_Y">사용</label>&nbsp;&nbsp;
										<input type="radio" id="briefing_N" name="briefing_tf" value="N" <? if ($rs_briefing_tf == "N") echo "checked"; ?> class="radio" /> <label for="briefing_N">사용안함</label>
									</td>
								</tr>
								<tr>
									<th>사용 안할 경우 안내 문구 </th>
									<td colspan="3">
										<input type="text" name="briefing_alert" id="briefing_alert" value="<?=$rs_briefing_alert?>" style="width:60%"/>
									</td>
								</tr>
								<tr>
									<th>신청 가능 기간 </th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="briefing_start" id="briefing_start" class="datepicker onlyphone" value="<?=$rs_briefing_start?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="briefing_end" id="briefing_end" class="datepicker onlyphone" value="<?=$rs_briefing_end?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
									</td>
								</tr>
								<tr>
									<th>신청 제외 일자 </th>
									<td colspan="3">
										<input type="text" name="briefing_disabled_date" id="briefing_disabled_date" value="<?=$rs_briefing_disabled_date?>" style="width:90%"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
<!--
					<div class="tit_h4 first" style="padding-top:30px;"><h4>캠퍼스 투어 설정</h4></div>

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
									<th>캠퍼스 투어 사용여부 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<input type="radio" id="tour_Y" name="tour_tf" value="Y" <? if ($rs_tour_tf == "Y") echo "checked"; ?> class="radio" /> <label for="tour_Y">사용</label>&nbsp;&nbsp;
										<input type="radio" id="tour_N" name="tour_tf" value="N" <? if ($rs_tour_tf == "N") echo "checked"; ?> class="radio" /> <label for="tour_N">사용안함</label>
									</td>
								</tr>
								<tr>
									<th>사용 안할 경우 안내 문구 </th>
									<td colspan="3">
										<input type="text" name="tour_alert" id="tour_alert" value="<?=$rs_tour_alert?>" style="width:60%"/>
									</td>
								</tr>
								<tr>
									<th>신청 가능 기간 </th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="tour_start" id="tour_start" class="datepicker onlyphone" value="<?=$rs_tour_start?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="tour_end" id="tour_end" class="datepicker onlyphone" value="<?=$rs_tour_end?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
									</td>
								</tr>
								<tr>
									<th>신청 제외 일자 </th>
									<td colspan="3">
										<input type="text" name="tour_disabled_date" id="tour_disabled_date" value="<?=$rs_tour_disabled_date?>" style="width:90%"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
-->
					<div class="btn_wrap">
						<button type="button" class="button" onClick="js_save()">수정</button>
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