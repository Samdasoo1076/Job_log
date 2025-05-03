<?
	session_start();
?>

<?
# =============================================================================
# File Name    : config.php
# Modlue       : 
# Writer       : Lee Ji Min
# Create Date  : 2025-05-02
# Modify Date  : 
#	Copyright : Copyright @LeeJiMin Corp. All Rights Reserved.
# =============================================================================
 
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
// 상수 정의
 
// 입력값 검사 상수
define('_ALPHAUPPER_', 1); // 영대문자
define('_ALPHALOWER_', 2); // 영소문자
define('_ALPHABETIC_', 4); // 영대,소문자
define('_NUMERIC_', 8); // 숫자
define('_HANGUL_', 16); // 한글
define('_SPACE_', 32); // 공백
define('_SPECIAL_', 64); // 특수문자
 
#====================================================================
# SITE_INFO
#====================================================================
 
	//$test_url = "_new";
	$strParam = "";
	$order_field = "";
	$order_str = "";
 
	# 사이트 사용 언어 셋
	Global  $g_charset; 
	$g_charset = "utf-8";
 
	# 사이트 Tile
	Global  $g_site_no; 
	$g_site_no = "1";
 
	Global  $g_base_dir;
	$g_base_dir = "";
 
	# 사이트 Tile
	Global  $g_title_name; 
	$g_title_name = "이지민 블로그";
 
	# 사이트 Tile
	Global  $g_title; 
	$g_title = "이지민 블로그";
 
	# 사이트 Tile
	Global  $g_front_title; 
	$g_front_title = "이지민 블로그"; 
	# 사이트 절대 경로
	Global  $g_physical_path; 
	$g_physical_path = $_SERVER['DOCUMENT_ROOT']."/";
 
	# 사이트 절대 경로
	Global  $g_old_data_path; 
	$g_old_data_path = $_SERVER['DOCUMENT_ROOT']."/upload_data/";
 
	Global  $g_site_domain;
	$g_site_domain	= "leejimin.kr";
 
	Global  $g_site_url;
	$g_site_url	= "https://144.24.85.101/";
 
	Global  $g_admin_email_01;
	$g_admin_email_01	= "myucheu0617@gmail.com";
 
 
	//모바일로 접속했는지 여부
	$mobile_is_all=false;
	if(preg_match('/(iPhone|Android|Opera Mini|SymbianOS|Windows CE|BlackBerry|Nokia|SonyEricsson|webOS|PalmOS)/i', $_SERVER['HTTP_USER_AGENT'])) {
		$mobile_is_all=true;
	}
	
	//if ($_SERVER['HTTPS'] == "off") {
	//	$ssl_is_on = "F";
	//} else {
		//$ssl_is_on = "F";
	//}
 
	$ssl_is_on = "F";
	Global  $g_site_url;
 
	if ($ssl_is_on == "F") {
		$g_site_url	= "https://".$_SERVER['HTTP_HOST'];
	} else {
		$g_site_url	= "https://".$_SERVER['HTTP_HOST'];
	}
 
	$urlencode = urlencode($_SERVER["REQUEST_URI"]);
 
	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
 
	if ($ssl_is_on == "F") {
		$g_url = 'https://' . $http_host . $request_uri;
	} else {
		exit;
	}
 
	// 댓글 아이디 닉 금지어 
	$g_prohibit_id = "admin,administrator,system,운영자,어드민,주인장,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,관리자,root,루트,su,guest,방문객";
	$g_url = 'http://' . $http_host . $request_uri;

 
?>