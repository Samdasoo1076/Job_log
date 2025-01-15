<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />

<meta name="description" content="<?=$seo_description?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?=$seo_title?>" />
<meta property="og:url" content="<?=$g_url?>" />
<meta property="og:site_name" content="<?=$g_front_title?>" />
<meta property="og:description" content="<?=$seo_description?>" />
<meta property="og:image" content="<?=$seo_og_image ?? ''?>" />
<meta property="og:updated_time" content="<?=$rs_iso_up_date?>" />
<meta property="fb:admins" content="" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?=$seo_title?>" />
<meta name="twitter:description" content="<?=$seo_description?>" />
<meta name="twitter:image" content="<?=$seo_og_image ?? ''?>" />
<meta name="twitter:creator" content="<?=$g_front_title?>" />
<meta name="keywords" content="<?=$seo_keywords?>" />

<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />

<? if ($_PAGE_NO == "2") { ?>
<title><?=$g_front_title?></title>
<? } else { ?>
<title><?=$seo_title?> | <?=$g_front_title?></title>
<? } ?>

<link rel="stylesheet" type="text/css" href="/assets/css/WFI.css" />

<script src="/assets/js/libs/jquery.min.js"></script>
<script src="/assets/js/libs/librurys.min.js"></script>
<script src="/assets/js/ui/ui.common.js"></script>

<? if ($_PAGE_NO == "2") { ?>
<script src="/assets/js/ui/ui.main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.9.6/lottie.min.js"></script>
<? } ?>

<!-- Swiper Librarys -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<? if ($_PAGE_NO == "2") { ?>
<!-- fullPage -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/4.0.25/fullpage.min.css" integrity="sha512-A7Hgc495WYRjDFNdIQ2B0eths46do08SH9bUocyn8cKl09HAq7kHJ9t+BA0tFH1qgTg+lI/mXPfgocdknvk3PQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/4.0.25/fullpage.min.js" integrity="sha512-rc+ezQrlvTWQ1bOJtmCN7Fm/GyfhIBX9eOZhbQ7BtSpnG8SgrafIjsl0jWh0a/zLsLj8wbzv2+vkAyZtYn7xwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<? } ?>

<!-- fullcalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="/assets/js/libs/index.global.js"></script>
<script src="/assets/js/libs/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.js"></script>

<script src="/assets/js/fontzoom.js"></script>
<script src="/assets/js/jquery.form.js"></script>
<script src="/assets/js/common.js"></script>

<!-- 페이지 스크립트 영역 -->
<script></script>
</head>
<?
	// 로그 수집 부분
	$log_ymd	= date("Y-m-d",strtotime("0 day"));

	$arr_log_ymd = explode("-", $log_ymd);

	$log_yy		= $arr_log_ymd[0];
	$log_mm		= $arr_log_ymd[1];
	$log_dd		= $arr_log_ymd[2];
	$log_hh		= date("H",strtotime("0 day"));
	$log_mi		= date("i",strtotime("0 day"));
	$log_ww		= date("w",strtotime("0 day"));

	//echo date("Y-m-d H:i:s",strtotime("0 day"))."<br>";
	//echo $log_hh;

	$log_depth01 = $this_depth01;
	$log_depth02 = $this_depth01.$this_depth02;
	$log_depth03 = $this_depth01.$this_depth02.$this_depth03;

	$log_page_no = $p;

	$http_host = $g_site_domain;

	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
		$log_url = 'https://'.$http_host.$_SERVER['PHP_SELF'];
	} else {
		$log_url = 'http://'.$http_host.$_SERVER['PHP_SELF'];
	}

	$b				= $b ?? "";
	$bn				= $bn ?? "";
	$ex				= $ex ?? "";
	$section	= $section ?? "";
	$en				= $en ?? "";
	$m				= $m ?? "";

	$log_param01 = $b.$ex.$section;
	$log_param02 = $bn.$en;
	$log_param03 = "";

	$log_pagetype = $m;

	if ($mobile_is_all) {
		$log_divicetype = "M";
	} else {
		$log_divicetype = "P";
	}

	$log_sid = session_id();
	$log_refip = $_SERVER['REMOTE_ADDR'];

	$arr_data = array("YMD"=>$log_ymd,
										"YYYY"=>$log_yy,
										"MM"=>$log_mm,
										"DD"=>$log_dd,
										"HH"=>$log_hh,
										"MI"=>$log_mi,
										"WK"=>$log_ww,
										"DEPTH01"=>$log_depth01,
										"DEPTH02"=>$log_depth02,
										"DEPTH03"=>$log_depth03,
										"PAGE_NO"=>$log_page_no,
										"URL"=>$log_url,
										"PARAM01"=>$log_param01,
										"PARAM02"=>$log_param02,
										"PARAM03"=>$log_param03,
										"PAGETYPE"=>$log_pagetype,
										"DIVICETYPE"=>$log_divicetype,
										"REFERER"=>$_SERVER['HTTP_REFERER'] ?? '',
										"SID"=>$log_sid,
										"REFIP"=>$log_refip);

	$log_result = insertLogData($conn, $arr_data);
?>
<body>
	<!-- 레이아웃 유형구분
		메인: main-wrapper,
		서브: sub-wrapper,
		기타: [기타]-wrapper
	 -->
<?
	if ($_PAGE_NO == "2") {
?>
    <div class="wrapper main-wrapper">
<?
} elseif ($_PAGE_NO == "98" || $_PAGE_NO == "103") {
?>
    <div class="wrapper sub-wrapper gnb-bg">
<?
} else {
?>
    <div class="wrapper sub-wrapper">
<?
}
?>