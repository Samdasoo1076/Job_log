<?session_start();?>
<?
# =============================================================================
# File Name    : config_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-10-19
# Modify Date  : 2023-07-21
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
	$menu_right = "PO011"; // 메뉴마다 셋팅 해 주어야 합니다

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

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$c_no							= isset($_POST["c_no"]) && $_POST["c_no"] !== '' ? $_POST["c_no"] : (isset($_GET["c_no"]) ? $_GET["c_no"] : '');
	
	$c_intro_tf				= isset($_POST["c_intro_tf"]) && $_POST["c_intro_tf"] !== '' ? $_POST["c_intro_tf"] : (isset($_GET["c_intro_tf"]) ? $_GET["c_intro_tf"] : '');
	$c_notice_tf			= isset($_POST["c_notice_tf"]) && $_POST["c_notice_tf"] !== '' ? $_POST["c_notice_tf"] : (isset($_GET["c_notice_tf"]) ? $_GET["c_notice_tf"] : '');
	
	$c_info_01				= isset($_POST["c_info_01"]) && $_POST["c_info_01"] !== '' ? $_POST["c_info_01"] : (isset($_GET["c_info_01"]) ? $_GET["c_info_01"] : '');
	$c_info_02				= isset($_POST["c_info_02"]) && $_POST["c_info_02"] !== '' ? $_POST["c_info_02"] : (isset($_GET["c_info_02"]) ? $_GET["c_info_02"] : '');
	$c_info_03				= isset($_POST["c_info_03"]) && $_POST["c_info_03"] !== '' ? $_POST["c_info_03"] : (isset($_GET["c_info_03"]) ? $_GET["c_info_03"] : '');
	$c_info_04				= isset($_POST["c_info_04"]) && $_POST["c_info_04"] !== '' ? $_POST["c_info_04"] : (isset($_GET["c_info_04"]) ? $_GET["c_info_04"] : '');
	$c_info_05				= isset($_POST["c_info_05"]) && $_POST["c_info_05"] !== '' ? $_POST["c_info_05"] : (isset($_GET["c_info_05"]) ? $_GET["c_info_05"] : '');
	
	$c_title					= isset($_POST["c_title"]) && $_POST["c_title"] !== '' ? $_POST["c_title"] : (isset($_GET["c_title"]) ? $_GET["c_title"] : '');
	$c_subtitle				= isset($_POST["c_subtitle"]) && $_POST["c_subtitle"] !== '' ? $_POST["c_subtitle"] : (isset($_GET["c_subtitle"]) ? $_GET["c_subtitle"] : '');
	$c_m_title				= isset($_POST["c_m_title"]) && $_POST["c_m_title"] !== '' ? $_POST["c_m_title"] : (isset($_GET["c_m_title"]) ? $_GET["c_m_title"] : '');
	$c_m_title1				= isset($_POST["c_m_title1"]) && $_POST["c_m_title1"] !== '' ? $_POST["c_m_title1"] : (isset($_GET["c_m_title1"]) ? $_GET["c_m_title1"] : '');
	$c_setqna					= isset($_POST["c_setqna"]) && $_POST["c_setqna"] !== '' ? $_POST["c_setqna"] : (isset($_GET["c_setqna"]) ? $_GET["c_setqna"] : '');
	$c_noti_title			= isset($_POST["c_noti_title"]) && $_POST["c_noti_title"] !== '' ? $_POST["c_noti_title"] : (isset($_GET["c_noti_title"]) ? $_GET["c_noti_title"] : '');
	$c_noti_subtitle	= isset($_POST["c_noti_subtitle"]) && $_POST["c_noti_subtitle"] !== '' ? $_POST["c_noti_subtitle"] : (isset($_GET["c_noti_subtitle"]) ? $_GET["c_noti_subtitle"] : '');
	$c_noti_img				= isset($_POST["c_noti_img"]) && $_POST["c_noti_img"] !== '' ? $_POST["c_noti_img"] : (isset($_GET["c_noti_img"]) ? $_GET["c_noti_img"] : '');
	$c_noti_m_img			= isset($_POST["c_noti_m_img"]) && $_POST["c_noti_m_img"] !== '' ? $_POST["c_noti_m_img"] : (isset($_GET["c_noti_m_img"]) ? $_GET["c_noti_m_img"] : '');
	
	$flag01						= isset($_POST["flag01"]) && $_POST["flag01"] !== '' ? $_POST["flag01"] : (isset($_GET["flag01"]) ? $_GET["flag01"] : '');
	$flag02						= isset($_POST["flag02"]) && $_POST["flag02"] !== '' ? $_POST["flag02"] : (isset($_GET["flag02"]) ? $_GET["flag02"] : '');
	
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$c_noti_real_img	= isset($_POST["c_noti_real_img"]) && $_POST["c_noti_real_img"] !== '' ? $_POST["c_noti_real_img"] : (isset($_GET["c_noti_real_img"]) ? $_GET["c_noti_real_img"] : '');

#====================================================================
	$savedir1 = $g_physical_path."upload_data/config";
#====================================================================

	$result = false;

	$mode							= SetStringToDB($mode);
	$c_no							= SetStringToDB($c_no);
	$c_info_01				= SetStringToDB($c_info_01);
	$c_info_02				= SetStringToDB($c_info_02);
	$c_info_03				= SetStringToDB($c_info_03);
	$c_info_04				= SetStringToDB($c_info_04);
	$c_info_05				= SetStringToDB($c_info_05);
	
	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "U") {

		$arr_data = array("C_INTRO_TF"=>$c_intro_tf,
											"C_NOTICE_TF"=>$c_notice_tf,
											"C_INFO_01"=>$c_info_01,
											"C_INFO_02"=>$c_info_02,
											"C_INFO_03"=>$c_info_03,
											"C_INFO_04"=>$c_info_04,
											"C_INFO_05"=>$c_info_05,
											"UP_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
											);

		$result =  updateConfig($conn, $arr_data, $c_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '기본설정 수정 (일자 : '.$REG_INSERT_DATE.') ', 'Update');
	}

	if ($mode == "") {

		$arr_rs = selectConfig($conn, 1); 

		$rs_c_no								= trim($arr_rs["C_NO"]); 
		$rs_c_intro_tf					= trim($arr_rs["C_INTRO_TF"]); 
		$rs_c_notice_tf					= trim($arr_rs["C_NOTICE_TF"]); 
		$rs_c_info_01						= SetStringFromDB($arr_rs["C_INFO_01"]); 
		$rs_c_info_02						= SetStringFromDB($arr_rs["C_INFO_02"]); 
		$rs_c_info_03						= SetStringFromDB($arr_rs["C_INFO_03"]); 
		$rs_c_info_04						= SetStringFromDB($arr_rs["C_INFO_04"]); 
		$rs_c_info_05						= SetStringFromDB($arr_rs["C_INFO_05"]); 

		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '기본설정 조회 (일자 : '.$REG_INSERT_DATE.') ', 'Read');

	}

	if ($result) {

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "config_write.php";
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
<title><?=$c_title?></title>
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

function js_save() {

	var frm = document.frm;
	var c_no = 1;

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
					<!--<div class="tit_h4 first"><h4>기본 설정</h4></div>-->
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
									<th>인트로 사용여부</th>
									<td colspan="3">
										<input type="radio" id="c_intro_Y" name="c_intro_tf" value="Y" <? if ($rs_c_intro_tf == "Y") echo "checked"; ?> class="radio" /> <label for="c_intro_Y">사용</label>&nbsp;&nbsp;
										<input type="radio" id="c_intro_N" name="c_intro_tf" value="N" <? if ($rs_c_intro_tf == "N") echo "checked"; ?> class="radio" /> <label for="c_intro_N">사용안함</label>
									</td>
								</tr>
								<tr>
									<th>주요공지 오픈여부 </th>
									<td colspan="3">
										<input type="radio" id="c_notice_Y" name="c_notice_tf" value="Y" <? if ($rs_c_notice_tf == "Y") echo "checked"; ?> class="radio" /> <label for="c_notice_Y">사용</label>&nbsp;&nbsp;
										<input type="radio" id="c_notice_N" name="c_notice_tf" value="N" <? if ($rs_c_notice_tf == "N") echo "checked"; ?> class="radio" /> <label for="c_notice_N">사용안함</label>
									</td>
								</tr>
								<!--
								<tr>
									<th>입학상담센터 문구</th>
									<td colspan="3">
										<textarea name="c_info_01" style=" width:80%;height:150px;padding:2px;" style="vertical-align:bottom"><?=$rs_c_info_01?></textarea><br />
										<font style="vertical-align:bottom;color:rgb(251,177,50)">* 엔터 시 PC화면에서 줄바꿈</font>
									</td>
								</tr>
								<tr>
									<th>e-공정성확보시스템 문구</th>
									<td colspan="3">
										<textarea name="c_info_02" style=" width:80%;height:150px;padding:2px;" style="vertical-align:bottom"><?=$rs_c_info_02?></textarea><br />
										<font style="vertical-align:bottom;color:rgb(251,177,50)">* 엔터 시 PC화면에서 줄바꿈</font>
										<font style="vertical-align:bottom;color:rgb(251,177,50)"> &nbsp;&nbsp;최대 2줄 입력</font>
									</td>
								</tr>
								<tr>
									<th>특기자 e-공정성확보시스템 문구</th>
									<td colspan="3">
										<textarea name="c_info_03" style=" width:80%;height:150px;padding:2px;" style="vertical-align:bottom"><?=$rs_c_info_03?></textarea><br />
										<font style="vertical-align:bottom;color:rgb(251,177,50)">* 엔터 시 PC화면에서 줄바꿈</font>
										<font style="vertical-align:bottom;color:rgb(251,177,50)"> &nbsp;&nbsp;최대 2줄 입력</font>
									</td>
								</tr>
								-->
								<tr>
									<th>건우건희 문구</th>
									<td colspan="3">
										<textarea name="c_info_04" style=" width:80%;height:150px;padding:2px;" style="vertical-align:bottom"><?=$rs_c_info_04?></textarea><br />
										<font style="vertical-align:bottom;color:rgb(251,177,50)">* 엔터 시 PC화면에서 줄바꿈</font>
									<!--<font style="vertical-align:bottom;color:rgb(251,177,50)"> &nbsp;&nbsp;최대 2줄 입력</font>-->
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
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