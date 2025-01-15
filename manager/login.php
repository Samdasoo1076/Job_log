<?session_start();?>

<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : login.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-11-24
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

#====================================================================
# Request Parameter
#====================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";


	// if (extension_loaded('mysqli')) {
	// 	echo "mysqli extension is enabled.";
	// } else {
	// 	echo "mysqli extension is not enabled.";
	// }

	$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$result = "";

	if (isset($_SESSION["s_adm_no"]) && $_SESSION["s_adm_no"] !== "") {

	$next_url = "./main.php";

?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<?
			exit;
	}

	$conn = db_connection("w");

#====================================================================
# common_header Check Session
#====================================================================

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/admin/admin.php";

#====================================================================
# Request Parameter
#====================================================================

	if ($mode == "S") {

		$result = "";

		if (chkBlockIP($conn, $_SERVER['REMOTE_ADDR']) == false) {
			$str_result = "접근권한이 없는 네트워크 입니다.";
?>		
<script language="javascript">
	alert('<?= $str_result ?>');
	document.location = "/";
</script>
<?
			db_close($conn);
			exit;
		}

		$adm_id			= trim($_POST['adm_id']);
		$passwd			= trim($_POST['adm_pw']);
		$mode				= trim($_POST['mode']);

		$de_passwd = dehack_check($passwd);

		$arr_rs = confirmAdmin($conn, $adm_id);

		$rs_adm_no					= trim($arr_rs[0]["ADM_NO"]); 
		$rs_adm_id					= trim($arr_rs[0]["ADM_ID"]); 
		$rs_passwd					= trim($arr_rs[0]["PASSWD"]); 
		$rs_adm_name				= trim($arr_rs[0]["ADM_NAME"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_cp_type					= trim($arr_rs[0]["CP_TYPE"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_organization		= trim($arr_rs[0]["ORGANIZATION"]); 

		if ($rs_adm_no == "") {
			$result = "1";
			$str_result = "해당 아이디가 없습니다.";
		} else {

			if (($rs_passwd == encrypt($key, $iv, $passwd)) || ($rs_passwd == encrypt($key, $iv, $de_passwd))) {
				$result = "0";
				$str_result = "";
			} else {
				$result = "2";
				$str_result = "회원 정보가 일치 하지 않습니다. 다시 확인 부탁 드립니다.";
			}
		}

		//if ($adm_id == "peoples") $result = 0;
		// result 0 : 승인 , 1 : 아이디 없음, 2 : 비밀번호 틀림
		if ($result == "0") {

			$rs_adm_type = "admin";
			$result = insertUserLog($conn, $rs_adm_type, $rs_adm_id, $_SERVER['REMOTE_ADDR'], '관리자 로그인', "Login");

			//session_register('s_adm_no','s_pb_no','s_adm_id','s_adm_nm','s_adm_email','s_adm_type');
			
			//setcookie('s_adm_no',$rs_adm_no,0,'/',$g_site_domain,false);
			//setcookie('s_adm_id',$rs_adm_id,0,'/',$g_site_domain,false);
			//setcookie('s_adm_nm',$rs_adm_name,0,'/',$g_site_domain,false);
			//setcookie('s_adm_email',$rs_adm_email,0,'/',$g_site_domain,false);
			//setcookie('s_adm_group_no',$rs_group_no,0,'/',$g_site_domain,false);
			//setcookie('s_adm_com_code',$rs_com_code,0,'/',$g_site_domain,false);
			//setcookie('s_adm_cp_type',$rs_cp_type,0,'/',$g_site_domain,false);

			$_SESSION['s_is_adm']				= "Y";
			$_SESSION['s_adm_no']				= $rs_adm_no;
			$_SESSION['s_adm_id']				= $rs_adm_id;
			$_SESSION['s_adm_pw']				= $rs_passwd;
			$_SESSION['s_adm_nm']				= $rs_adm_name;
			$_SESSION['s_adm_email']					= $rs_adm_email;
			$_SESSION['s_adm_group_no']				= $rs_group_no;
			$_SESSION['s_adm_com_code']				= $rs_com_code;
			$_SESSION['s_adm_cp_type']				= $rs_cp_type;
			$_SESSION['s_adm_position_code']	= $rs_position_code;			// 소속지역
			$_SESSION['s_adm_dept_code']			= $rs_dept_code;					// 소속당
			$_SESSION['s_adm_organization']		= $rs_organization;				// 소속조직

			// 프론트 페이지 강제 로그 아웃
			$_SESSION['s_m_no']	= "";
			$_SESSION['s_m_id']	= "";

			$next_url = "main.php";

			//echo $next_url;

?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<!--
<script language="javascript">
		location.href =  '/index.php';
</script>	
-->
<?
			exit;
		}
	}

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?=$g_title_name?> 관리자 로그인  <?=$_SESSION['s_adm_no']?></title>
<link rel="stylesheet" type="text/css" href="./css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src="./js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="./js/ui.js"></script>
<script>

	$(document).ready(function() {
		$("#systemPw").keypress(function(e) {
			if (e.keyCode == 13) { 
				$('.btn_login').trigger('click');
			}
		});
	});

	function js_login() {

		var frm = document.frm;

		if (frm.adm_id.value == "") {
			alert("관리자의 아이디를 입력해 주세요.");
			frm.adm_id.focus();
			return;
		}

		if (frm.adm_pw.value == "") {
			alert("관리자의 패스워드를 입력해 주세요.");
			frm.adm_pw.focus();
			return;
		}
			
		frm.action="<?=$_SERVER["PHP_SELF"]?>";
		frm.submit();
	}

</script>
</head>
<body class="login">
	<div class="login_wrap">
<form id="logIn" name="frm" method="post" action="javascript:js_login();">
<input type="hidden" name="mode" value="S">
		<div class="login_cont">
			<div class="title">&nbsp;</div>
			<input type="text" class="input_id" id="systemId" name="adm_id" value="" maxlength="20" placeholder="아이디" />
			<input type="password" id="systemPw" name="adm_pw" placeholder="비밀번호" class="input_pw" />
			<button type="button" class="btn_login" onClick="js_login()">로그인</button>
			<p style="padding-top:30px;width:100%;text-align:center">접속IP : <?=$_SERVER['REMOTE_ADDR']?></p>
		</div>
		
</form>
	</div>
	<div class="footer">
		<div class="inner">
			<img src="./images/img-logo-wh.svg" alt="<?=$g_front_title?>" />
			<p>COPYRIGHT ⓒ2024 <?=$g_front_title?>. ALL RIGHTS RESERVED.</p>
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
<?
	if (($result == "1") or ($result == "2")) {
?>		
<script language="javascript">
	alert('<?= $str_result ?>');
</script>
<?
	}
?>
