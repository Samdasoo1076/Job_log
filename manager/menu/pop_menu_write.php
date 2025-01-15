<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : pop_menu_write.php
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

		//$query="delete from TB_ADMIN_MENU  WHERE menu_no			= '19' ";
		//mysql_query($query,$conn);
#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD004"; // 메뉴마다 셋팅 해 주어야 합니다

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


	#sPageRight_R 해당 메뉴 읽기 권한
	#sPageRight_I 해당 메뉴 입력 권한
	#sPageRight_U 해당 메뉴 수정 권한
	#sPageRight_D 해당 메뉴 삭제 권한
	#sPageRight_  해당 메뉴 코드 메뉴 관리 에서 지정된 코드 값을 사용한다.

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/menu/menu.php";

#====================================================================
# Request Parameter
#====================================================================

$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$menu_no					= isset($_POST["menu_no"]) && $_POST["menu_no"] !== '' ? $_POST["menu_no"] : (isset($_GET["menu_no"]) ? $_GET["menu_no"] : '');
$m_level					= isset($_POST["m_level"]) && $_POST["m_level"] !== '' ? $_POST["m_level"] : (isset($_GET["m_level"]) ? $_GET["m_level"] : '');
$m_seq01					= isset($_POST["m_seq01"]) && $_POST["m_seq01"] !== '' ? $_POST["m_seq01"] : (isset($_GET["m_seq01"]) ? $_GET["m_seq01"] : '');
$m_seq02					= isset($_POST["m_seq02"]) && $_POST["m_seq02"] !== '' ? $_POST["m_seq02"] : (isset($_GET["m_seq02"]) ? $_GET["m_seq02"] : '');

$menu_name				= isset($_POST["menu_name"]) && $_POST["menu_name"] !== '' ? $_POST["menu_name"] : (isset($_GET["menu_name"]) ? $_GET["menu_name"] : '');
$menu_url					= isset($_POST["menu_url"]) && $_POST["menu_url"] !== '' ? $_POST["menu_url"] : (isset($_GET["menu_url"]) ? $_GET["menu_url"] : '');
$menu_flag				= isset($_POST["menu_flag"]) && $_POST["menu_flag"] !== '' ? $_POST["menu_flag"] : (isset($_GET["menu_flag"]) ? $_GET["menu_flag"] : '');
$menu_yn					= isset($_POST["menu_yn"]) && $_POST["menu_yn"] !== '' ? $_POST["menu_yn"] : (isset($_GET["menu_yn"]) ? $_GET["menu_yn"] : '');
$menu_cd					= isset($_POST["menu_cd"]) && $_POST["menu_cd"] !== '' ? $_POST["menu_cd"] : (isset($_GET["menu_cd"]) ? $_GET["menu_cd"] : '');
$in_menu_right		= isset($_POST["in_menu_right"]) && $_POST["in_menu_right"] !== '' ? $_POST["in_menu_right"] : (isset($_GET["in_menu_right"]) ? $_GET["in_menu_right"] : '');
$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

$mode	 = SetStringToDB($mode);

$m_level = SetStringToDB($m_level);
$m_seq01 = SetStringToDB($m_seq01);
$m_seq02 = SetStringToDB($m_seq02);

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Request Parameter
#====================================================================

$menu_no		= SetStringToDB($menu_no);
$m_level		= SetStringToDB($m_level);
$m_seq01		= SetStringToDB($m_seq01);
$m_seq02		= SetStringToDB($m_seq02);
$menu_name	= SetStringToDB($menu_name);
$menu_url		= SetStringToDB($menu_url);
$menu_flag	= SetStringToDB($menu_flag);
$menu_yn		= SetStringToDB($menu_yn);
$menu_cd		= SetStringToDB($menu_cd);
$in_menu_right = SetStringToDB($in_menu_right);

$use_tf			= SetStringToDB($use_tf);

//echo $m_level;

$result = false;
#====================================================================
# DML Process
#====================================================================
	
#====================================================================
	$savedir1 = $g_physical_path."upload_data/menu";
#====================================================================
	
	if ($mode == "I") {
		

		#$menu_img					= upload($_FILES[menu_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		#$menu_img_over		= upload($_FILES[menu_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		//$file_rnm					= $_FILES[menu_img][name];

		$result = insertAdminMenu($conn, $m_level, $m_seq01, $m_seq02, $menu_name, $menu_url, $menu_flag, $in_menu_right, $menu_img, $menu_img_over, $use_tf, $_SESSION['s_adm_no']);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 등록", "Insert");
	
	}

	$rs_menu_no				= "";
	$rs_menu_name			= "";
	$rs_menu_url			= "";
	$rs_menu_flag			= "";
	$rs_menu_cd				= "";
	$rs_menu_right		= "";
	$rs_menu_img			= "";
	$rs_menu_img_over	= "";
	$rs_use_tf				= "";
	$rs_del_tf				= "";


	if ($mode == "S") {

		$arr_rs = selectAdminMenu($conn, $menu_no);

		//MENU_NO, MENU_NAME, MENU_URL, MENU_FLAG, MENU_CD, MENU_RIGHT,MENU_IMG,MENU_IMG_OVER

		$rs_menu_no				= trim($arr_rs[0]["MENU_NO"]); 
		$rs_menu_name			= trim($arr_rs[0]["MENU_NAME"]); 
		$rs_menu_url			= trim($arr_rs[0]["MENU_URL"]); 
		$rs_menu_flag			= trim($arr_rs[0]["MENU_FLAG"]); 
		$rs_menu_cd				= trim($arr_rs[0]["MENU_CD"]); 
		$rs_menu_right		= trim($arr_rs[0]["MENU_RIGHT"]); 
		$rs_menu_img			= trim($arr_rs[0]["MENU_IMG"]); 
		$rs_menu_img_over	= trim($arr_rs[0]["MENU_IMG_OVER"]); 
		//$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		//$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

	}

	if ($mode == "U") {
		/*
		switch ($flag01) {
			case "insert" :
				$menu_img					= upload($_FILES[menu_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$menu_img			= $old_menu_img;
			break;
			case "delete" :
				$menu_img			= "";
			break;
			case "update" :
				$menu_img					= upload($_FILES[menu_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$menu_img_over		= upload($_FILES[menu_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$menu_img_over		= $old_menu_img_over;
			break;
			case "delete" :
				$menu_img_over		= "";
			break;
			case "update" :
				$menu_img_over		= upload($_FILES[menu_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}
		*/

		$result = updateAdminMenu($conn, $menu_name, $menu_url, $menu_flag, $in_menu_right, $menu_img, $menu_img_over, $use_tf, $_SESSION['s_adm_no'], $menu_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 수정 (".$menu_no.") ", "Update");

	}

	if ($mode == "D") {
		$result = deleteAdminMenu($conn, $_SESSION['s_adm_no'], $menu_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 삭제(".$menu_no.") ", "Delete");
	}

	if ($rs_menu_cd <> "") {

		if (strlen($m_level) == 2) {
			$level_str = "대분류 메뉴";
		} else if (strlen($m_level) == 4) {
			$level_str = "중분류 메뉴";
		} else if (strlen($m_level) == 6) {
			$level_str = "소분류 메뉴";
		}

	
	} else {

		if (strlen($m_level) == 0) {
			$level_str = "대분류 메뉴";
		} else if (strlen($m_level) == 2) {
			$level_str = "중분류 메뉴";
		} else if (strlen($m_level) == 4) {
			$level_str = "소분류 메뉴";
		}	
	}


#=================================================================
# Get Result set from stored procedure
#=================================================================
	if ($result) {
?>	
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		menu_cd=opener.document.frm.menu_cd.value;
		opener.document.location = "menu_list.php?menu_cd="+menu_cd;
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
<link rel="shortcut icon" href="/assets/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<script language="javascript">
	
	function js_save() {
		
		var menu_no = "<?= $menu_no ?>";
		var frm = document.frm;

		if (frm.menu_name.value == "") {
			alert("메뉴명을 입력하세요.");
			frm.menu_name.focus();
			return;
		}

		if (frm.menu_url.value == "") {
			alert("메뉴경로를 입력하세요.");
			frm.menu_url.focus();
			return;
		}

		if (frm.in_menu_right.value == "") {
			alert("권한코드를 입력하세요.");
			frm.in_menu_right.focus();
			return;
		}

		if (isNull(menu_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}
		
		frm.target = "";
		frm.action = "pop_menu_write.php";
		frm.submit();
	}

	// Ajax
	function sendKeyword() {

		if (frm.old_menu_right.value != frm.in_menu_right.value)	{

			var keyword = document.frm.in_menu_right.value;

			if (keyword != '') {
				var params = "keyword="+encodeURIComponent(keyword);
			
				sendRequest("menu_dup_check.php", params, displayResult, 'POST');
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
	
	function js_delete() {
		var frm = document.frm;

		bDelOK = confirm('해당 메뉴를 삭제 하시겠습니까?\n\n해당 메뉴에 하위 메뉴도 모두 삭제 됩니다.');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "pop_menu_write.php";
			frm.submit();
		}
	}

</script>
</head>
<body id="popup">
<div class="popupwrap">
	<h1>관리자 메뉴 등록</h1>
	<div class="popcontents">
		<div class="tbl_style01 left">

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="">
<input type="hidden" name="menu_no" value="<?=$menu_no?>">
<input type="hidden" name="m_level" value="<?=$m_level?>">
<input type="hidden" name="m_seq01" value="<?=$m_seq01?>">
<input type="hidden" name="m_seq02" value="<?=$m_seq02?>">

			<table>
				<colgroup>
					<col width="20%">
					<col width="80%">
				</colgroup>
				<tr>
					<th>메뉴분류</th>
					<td>
						<?=$level_str?>
					</td>
				</tr>
				<tr>
					<th>메뉴명</th>
					<td>
						<input type="text" name="menu_name" value="<?= $rs_menu_name ?>" style="width:90%;" class="txt" />
					</td>
				</tr>
				<tr>
					<th>메뉴경로</th>
					<td colspan="3">
						<input type="text" name="menu_url" value="<?= $rs_menu_url ?>" style="width:90%;" class="txt" />
					</td>
				</tr>
				<tr>
					<th>권한코드</th>
					<td colspan="3">
						<input type="text" name="in_menu_right" value="<?= $rs_menu_right ?>" style="width:30%;" class="txt" />
						<input type="hidden" name="old_menu_right" value="<?= $rs_menu_right ?>">
					</td>
				</tr>
			</table>
		</div>

		<div class="btn_wrap right">

			<? if ($menu_no <> "" ) {?>
				<? if ($sPageRight_U == "Y") {?>
				<button type="button" class="button" onClick="sendKeyword();">저장</button>
				<? } ?>
			<? } else {?>
			<? if ($sPageRight_I == "Y") {?>
				<button type="button" class="button" onClick="sendKeyword();">저장</button>
			<? } ?>
			<? }?>
			<? if ($menu_no <> "") {?>
			<? if ($sPageRight_D == "Y") {?>
			<button type="button" class="button type02" onClick="js_delete();">삭제</button>
			<? } ?>
			<? }?>
			<button type="button" class="button type03" onClick="self.close();">닫기</button>

		</div>
	</div>
</div>
<input type="hidden" name="menu_flag" value="Y">
<input type="hidden" name="use_tf" value="Y">
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>
