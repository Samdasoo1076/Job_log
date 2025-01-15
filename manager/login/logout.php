<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : logout.php
# Modlue       : 
# Writer       : chingong 
# Create Date  : 2019-06-10
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================
	require "../../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");

	require "../../_common/config.php";
	require "../../_classes/com/etc/etc.php";

/*
	setcookie('s_adm_no','',0,'/');
	setcookie('s_adm_id','',0,'/');
	setcookie('s_adm_nm','',0,'/');
	setcookie('s_adm_email','',0,'/');
	setcookie('s_adm_group_no','',0,'/');

	setcookie('s_adm_no',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_id',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_nm',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_email',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_group_no',"",0,'/',$g_site_domain,false);
*/
/*
	setcookie('s_adm_no',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_id',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_nm',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_email',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_group_no',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_com_code',"",0,'/',$g_site_domain,false);
	setcookie('s_adm_cp_type',"",0,'/',$g_site_domain,false);
*/
	$result = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '관리자 로그아웃', "Logout");

	$_SESSION['s_is_adm']				= "";
	$_SESSION['s_adm_no']				= "";
	$_SESSION['s_adm_id']				= "";
	$_SESSION['s_adm_pw']				= "";
	$_SESSION['s_adm_nm']				= "";
	$_SESSION['s_adm_email']					= "";
	$_SESSION['s_adm_group_no']				= "";
	$_SESSION['s_adm_com_code']				= "";
	$_SESSION['s_adm_cp_type']				= "";
	$_SESSION['s_adm_position_code']	= "";
	$_SESSION['s_adm_dept_code']			= "";
	$_SESSION['s_adm_organization']		= "";

	db_close($conn)
?>
<meta http-equiv='Refresh' content='0; URL=../login.php'>
