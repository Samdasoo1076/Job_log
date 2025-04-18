<?
	session_start();
?>

<?
# =============================================================================
# File Name    : config.php
# Modlue       : 
# Writer       : Lee Ji Min
# Create Date  : 2025-01-31
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
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
	$g_title_name = "원주미래산업진흥원";
 
	# 사이트 Tile
	Global  $g_title; 
	$g_title = "원주미래산업진흥원";
 
	# 사이트 Tile
	Global  $g_front_title; 
	$g_front_title = "원주미래산업진흥원"; 
	# 사이트 절대 경로
	Global  $g_physical_path; 
	$g_physical_path = $_SERVER['DOCUMENT_ROOT']."/";
 
	# 사이트 절대 경로
	Global  $g_old_data_path; 
	$g_old_data_path = $_SERVER['DOCUMENT_ROOT']."/upload_data/";
 
	//echo $g_physical_path;
 
	Global  $g_site_domain;
	$g_site_domain	= "www.wfi.or.kr";
 
	Global  $g_site_url;
	$g_site_url	= "https://180.210.76.103/";
 
	//재가입기간 설정
	Global  $g_site_re_enter_period;
	$g_site_re_enter_period	= 30;
 
	//글쓰기 시간 초단위로 설정
	Global  $g_site_re_write;
	//테스트 기간동안 10초 하자
	$g_site_re_write	= 60;
	//$g_site_re_write	= 10;
 
	//닉네임 변경 일단위로 설정
	Global  $g_site_nick_period;
	$g_site_nick_period	= 1;
 
	Global  $g_admin_email_01;
	$g_admin_email_01	= "myucheu0617@ucomp.co.kr";
 
	Global  $g_admin_email_02;
	$g_admin_email_02	= "aodhzld45@ucomp.co.kr";
 
	Global  $g_admin_email_03;
	$g_admin_email_03	= "myucheu0617@ucomp.co.kr";
 
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
 
?>
<script>
	document.location = "https://<?=$http_host?><?=$request_uri?>";
</script>
<?
		db_close($conn);
		exit;
 
	}
 
	// 회원 아이디 닉 금지어 
	$g_prohibit_id = "admin,administrator,system,운영자,어드민,주인장,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,관리자,root,루트,su,guest,방문객";
	// 회원 가입시 권한
	Global  $g_register_level;
	$g_register_level = 2;
 
	$g_url = 'http://' . $http_host . $request_uri;
 
	$urlencode = urlencode($_SERVER["REQUEST_URI"]);
 
	# 리뉴얼 플래그
	if (($_SERVER['REMOTE_ADDR'] == "183.98.184.244") || ($_SERVER['REMOTE_ADDR'] == "182.205.250.10") || ($_SERVER['REMOTE_ADDR'] == "124.194.41.2")) {
		$g_renewal = "1"; 
		//echo $_SERVER['HTTPS'];
	} else {
		$g_renewal = "1"; 
	}
	// 공공데이터 주조 검색 API Key
	//Global  $g_addr_api_key;
	//$g_addr_api_key = "U01TX0FVVEgyMDIwMTAxMzE0NDY1NDExMDI4MzM=";
 
	//echo $_SERVER["DOCUMENT_ROOT"];
 
	function hack_check($var) {
 
		$danger_str = array("syscolumns", "column_name", "table_name", "user_table_columns", "user_tables", "union", "select", "sleep", "insert", "update", "delete", "having", " drop ", " and ", "sleep", " or ", " eval ", "javascript", "alert", "cookie", "iframe", "frame", "document", "script", "%2F", "--"); // 이곳에 제외할 단어 작성
 
		if(is_array($var)) {
			$var = array_map("hack_check", $var);
		} else {
 
			for($i=0; $i<count($danger_str); $i++){
				$var = str_ireplace($danger_str[$i], "", $var);
			}
 
			$var = str_ireplace("&", "&amp;", $var);
			$var = str_ireplace("#", "&#35;", $var);
			$var = str_ireplace("<", "&lt;", $var);
			$var = str_ireplace(">", "&gt;", $var);
			$var = str_ireplace("(", "&#40;", $var);
			$var = str_ireplace(")", "&#41;", $var);
			$var = str_ireplace("'", "&#39;", $var);
			$var = str_ireplace("\"", "&quot;", $var);
 
		}
		return $var;
	}
 
 
	function dehack_check($var) {
 
		$var = str_ireplace("&amp;", "&", $var);
		$var = str_ireplace("&#35;", "#", $var);
		$var = str_ireplace("&lt;", "<", $var);
		$var = str_ireplace("&gt;", ">", $var);
		$var = str_ireplace("&#40;", "(", $var);
		$var = str_ireplace("&#41;", ")", $var);
		$var = str_ireplace("&#39;", "'", $var);
		$var = str_ireplace("&quot;", "\"", $var);
 
		return $var;
	}
 
	$arr_page_nm = explode("/", $_SERVER['PHP_SELF']);
 
	if ($arr_page_nm[1] != "manager") {
 
		if(is_array($_POST)) $_POST = array_map("hack_check", $_POST);
		if(is_array($_GET)) $_GET = array_map("hack_check", $_GET);
		if(is_array($_REQUEST)) $_REQUEST = array_map("hack_check", $_REQUEST);
		if(is_array($_COOKIE)) $_COOKIE = array_map("hack_check", $_COOKIE);
		if(is_array($_SESSION)) $_SESSION = array_map("hack_check", $_SESSION);
		if(is_array($_SERVER)) $_SERVER = array_map("hack_check", $_SERVER);
 
	}
 
 
?>