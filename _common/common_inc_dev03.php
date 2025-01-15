<?
	header('Content-Type: text/html; charset=utf-8');

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# request parameter 
#=====================================================================
	if ($b == "") {
		$b				= $_POST['b']!=''?$_POST['b']:$_GET['b'];
	}

	if ($m == "") {
		$m				= $_POST['m']!=''?$_POST['m']:$_GET['m'];
	}

	$l				= $_POST['l']!=''?$_POST['l']:$_GET['l'];
	$p				= $_POST['p']!=''?$_POST['p']:$_GET['p'];
	$bn				= $_POST['bn']!=''?$_POST['bn']:$_GET['bn'];
	$cn				= $_POST['cn']!=''?$_POST['cn']:$_GET['cn'];
	$f				= $_POST['f']!=''?$_POST['f']:$_GET['f'];
	$s				= $_POST['s']!=''?$_POST['s']:$_GET['s'];
	$nPage		= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];

	if ($_PAGE_NO != "") $p = $_PAGE_NO;
	if ($_B_CODE != "") $b = $_B_CODE;
	if ($_BOARD_MODE != "") $m = $_BOARD_MODE;

#=====================================================================
# common function
#=====================================================================
	require "../_common/config.php";

	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/page/page.php";
	require "../_classes/biz/config/config.php";

	$arr_config_info = selectConfig($conn, '1');
	
	$config_no						= trim($arr_config_info["C_NO"]); 
	$config_c_title				= trim($arr_config_info["C_TITLE"]);    // 수시 학년도
	$config_c_subtitle		= trim($arr_config_info["C_SUBTITLE"]); 
	$config_c_noti_img		= trim($arr_config_info["C_NOTI_IMG"]); 
	$config_c_m_title			= trim($arr_config_info["C_M_TITLE"]);  // 정시 학년도
	$config_c_m_title1		= trim($arr_config_info["C_M_TITLE1"]); // 편입 학년도
	$config_c_noti_title	= trim($arr_config_info["C_NOTI_TITLE"]); // 재외국민 학년도

	$config_briefing_start					= SetStringFromDB($arr_config_info["BRIEFING_START"]); 
	$config_briefing_end						= SetStringFromDB($arr_config_info["BRIEFING_END"]); 
	$config_briefing_alert					= SetStringFromDB($arr_config_info["BRIEFING_ALERT"]); 
	$config_briefing_disabled_date	= SetStringFromDB($arr_config_info["BRIEFING_DISABLED_DATE"]); 
	$config_briefing_tf							= SetStringFromDB($arr_config_info["BRIEFING_TF"]); 
	$config_tour_start							= SetStringFromDB($arr_config_info["TOUR_START"]); 
	$config_tour_end								= SetStringFromDB($arr_config_info["TOUR_END"]); 
	$config_tour_alert							= SetStringFromDB($arr_config_info["TOUR_ALERT"]); 
	$config_tour_disabled_date			= SetStringFromDB($arr_config_info["TOUR_DISABLED_DATE"]); 
	$config_tour_tf									= SetStringFromDB($arr_config_info["TOUR_TF"]); 

	$this_date = date("Y-m-d",strtotime("0 day"));

	if ($this_date > $config_briefing_start) $config_briefing_start = $this_date;
	if ($this_date > $config_tour_start) $config_tour_start = $this_date;

	if (($this_date < $config_briefing_start) || ($this_date > $config_briefing_end)) $config_briefing_tf = "N";
	if (($this_date < $config_tour_start) || ($this_date > $config_tour_end)) $config_tour_tf = "N";

	//$config_c_subtitle = "Y";

	if ($_PAGE_NO <> "2") { 
		$config_c_subtitle = "N";
	}

#=====================================================================
# common memu infomation
#=====================================================================
	require "../_common/common_memu_info.php";

	// 새로운 아주대
	$arr_new_ajou = getListNewAjouNotice($conn);


	// 게시물의 경우.
	if ($b) {

		$page_type = "board";
		require "../_classes/biz/board/board.php";
		require "../_common/board/config_info.php";
		
		// 게시판 관련
		if ($m == "") $m = "list";
		
		if ($m == "read") {
			
			$read_ip = $_SERVER['REMOTE_ADDR'];
			$result = viewChkBoardAsIp($conn,$b, $bn, $read_ip);
			$arr_rs_read = selectBoard($conn, $b, $bn);
			$arr_rs_files = listBoardFile($conn, $b, $bn);

			if ($arr_rs_read == null) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
	alert('삭제된 게시물 이거나 존재 하지 않는 게시물 입니다.');
	history.go(-1);
</script>
<?
				db_close($conn);
				exit;
			}

			$seo_keywords				= trim($arr_rs_read[0]["KEYWORD"]); 
			$rs_iso_reg_date		= trim($arr_rs_read[0]["REG_DATE"]); 
			$rs_iso_up_date			= trim($arr_rs_read[0]["UP_DATE"]); 
			$rs_iso_up_date			= date("c", strtotime($rs_iso_up_date));
			$rs_iso_reg_date		= date("c", strtotime($rs_iso_reg_date));
		
			// 최근 업데이트 ISO 날짜
			if ($rs_iso_up_date == "") $rs_iso_up_date = $rs_iso_reg_date; 
			
			// SEO 관련 정보 등록
			$seo_title					= SetStringFromDB($arr_rs_read[0]["TITLE"]); 
			$seo_description	= "";								//description

		}
	}

	// QNA 입니다.
	if ($_confirm_type == "qna") {
		if (($m == "read") || ($m == "mod")) {

			$arr_rs_read = selectBoard($conn, $b, $bn);
			
			$rs_secret_tf				= trim($arr_rs_read[0]["SECRET_TF"]); 

			if ($rs_secret_tf == "Y") {

				// 비밀글 인데 만일 session 이 없을 경우  
				if ($_SESSION['s_board_bn'] <> encrypt($key, $iv, $bn)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
	alert('비밀글은 비밀번호 확인 후 보실 수 있습니다.');
	history.go(-1);
</script>
<?
					db_close($conn);
					exit;
				}

			}

			$seo_keywords				= trim($arr_rs_read[0]["SUB_CATEGORY"]); 
			$rs_iso_reg_date		= trim($arr_rs_read[0]["REG_DATE"]); 
			$rs_iso_up_date			= trim($arr_rs_read[0]["UP_DATE"]); 
			$rs_iso_up_date			= date("c", strtotime($rs_iso_up_date));
			$rs_iso_reg_date		= date("c", strtotime($rs_iso_reg_date));
		
			// 최근 업데이트 ISO 날짜
			if ($rs_iso_up_date == "") $rs_iso_up_date = $rs_iso_reg_date; 
			
			// SEO 관련 정보 등록
			$seo_title					= SetStringFromDB($arr_rs_read[0]["TITLE"]); 
			$seo_description	= "";								//description

		}
	}

	$seo_title					= removeSeoStr($seo_title);
	$seo_keywords				= removeSeoStr($seo_keywords);
	$seo_description		= removeSeoStr($seo_description);

#=====================================================================
# common head 
#=====================================================================
	require "../_common/front_head_dev.php"; 
	require "../_common/front_header.php"; 

?>
<body>
<? if ($_PAGE_NO == "2") { ?>

<?
	$arr_Intro = getListIntro($conn);
?>
	<div id="wrap" class="main">

		<header class="header">
			<h1 onclick="document.location='/'" style="cursor:pointer">아주대학교 입학처</h1>
			<div class="header-actions">
				<button type="button" class="btn-icon primary notify--button" onclick="front.notifyToggle()">
					<span class="sr-only">알림</span>
					<span class="notify--badge"><?=number_format(sizeof($arr_new_ajou))?></span>
				</button>
				<button type="button" class="btn-icon menu--button" onclick="front.gnbToggle()">
					<span>MENU</span>
				</button>
			</div>
		</header>

		<!-- main page content -->
		<section>
			<!-- main-head -->
			<div class="main-visual" style="background:url(/assets/images/main/main_03.jpg) no-repeat center center/100% 100%;<? if (sizeof($arr_Intro) == 0) { ?>display:none<? } ?>">
				<div class="dim-overlay"></div>

<? } else { ?>

	<div id="wrap" class="sub <?=$rs_page_info05?>">

			<!-- header -->
			<header class="header">
				<h1  onclick="document.location='/'" style="cursor:pointer">아주대학교 입학처</h1>
				<div class="header-actions">
					<button type="button" class="btn-icon primary notify--button" onclick="front.notifyToggle()">
						<span class="sr-only">알림</span>
						<span class="notify--badge"><?=number_format(sizeof($arr_new_ajou))?></span>
					</button>
					<button type="button" class="btn-icon menu--button" onclick="front.gnbToggle()">
						<span>MENU</span>
					</button>
				</div>
			</header>
			<!-- // header -->
			<!-- sub-visual -->
			<div class="sub-visual">
				<div class="page-title">
					<h2><?=$depth_01_page_name?></h2>
				</div>

<?
	require "../_common/paginavi.php"; 
?>
			</div>
			<div class="content-area">
				<section id ="print_content">
					<div class="title-area">
						<h3><?=$depth_02_page_name?></h3>
						<? if ($b == "B_1_4") { ?>
						<style>
							.txt-tip-qna {
								position: relative;
								line-height: 1.5;
								color: #666;
								font-weight: 400;
								width:75%
							}

							@media only screen and (max-width : 800px) {
								.txt-tip-qna {
								padding-left: 0.2rem;
								font-size: 1.2rem;
								line-height: 1.4;
								width:85%
							}
						</style>
						<p class="txt-tip-qna">
								<b>아주대학교 Q&A게시판은 전체 공개로 운영합니다.<br>
								개인정보가 포함된 문의사항은 <br class="mo-only"/>별도 유선문의 바랍니다.(031-219-3200)</b>
						</p>
						<? } ?>
						<div class="setting-area">
							<ul>
								<li>
									<button type="button" class="icon-reduction" title="폰트 크기 축소" onclick="browserZoomOut();">축소</button>
									<i class="icon-font"></i>
									<button type="button" class="icon-magnification" title="폰트 크기 확대" onclick="browserZoomIn();">확대</button>
								</li>
								<li>
									<button type="button" class="icon-refresh" title="새로고침" onclick="browserZoomReset();">새로고침</button>
									<button type="button" class="icon-print" title="인쇄하기" onclick="printPage('#print_content');">프린트</button>
								</li>
							</ul>
						</div>
					</div>

<? } ?>
<!-- //page-head -->