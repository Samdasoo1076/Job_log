<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="<?=$seo_description?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?=$seo_title?>" />
<meta property="og:url" content="<?=$g_url?>" />
<meta property="og:site_name" content="<?=$g_front_title?>" />
<meta property="og:description" content="<?=$seo_description?>" />
<meta property="og:image" content="<?=$seo_og_image?>" />
<meta property="og:updated_time" content="<?=$rs_iso_up_date?>" />
<meta property="fb:admins" content="" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?=$seo_title?>" />
<meta name="twitter:description" content="<?=$seo_description?>" />
<meta name="twitter:image" content="<?=$seo_og_image?>" />
<meta name="twitter:creator" content="<?=$g_front_title?>" />
<meta name="keywords" content="<?=$seo_keywords?>" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<? if ($_PAGE_NO == "2") { ?>
<title><?=$g_front_title?></title>
<? } else { ?>
<title><?=$seo_title?> | <?=$g_front_title?></title>
<? } ?>

<script src="/assets/js/jquery-1.12.4.min.js"></script>
<script src="/assets/js/swiper.min.js"></script>

<script src="/assets/js/jquery-1.11.2.min.js"></script>
<script src="/assets/js/jquery_ui.min.js"></script>
<script src="/assets/js/fontzoom.js"></script>
<script src="/assets/js/printThis.js"></script>
<script src="/assets/js/jquery.form.js"></script>
<script src="/assets/js/common.js"></script>

<? if ($pdf_page == "Y") { ?>
<script type="text/javascript" src="/assets/js/pdfobject.js?v=20230819"></script>
<style>
.pdf-viewer__viewer {overflow: hidden !important;border:1px solid #4d4d4d;}
</style> 
<? } ?>

<link rel="stylesheet" type="text/css" href="/assets/css/konkuk.min.css" />

<style>

/* datepicker */
#ui-datepicker-div {margin-top:-0.05rem; overflow:hidden; background:#fff; border-radius:1rem; border:1px solid #c3c7cd; opacity:0}
.ui-datepicker-calendar {margin:0; border-top:1px solid #ddd}
.ui-datepicker {width:28.4rem}
.ui-datepicker table {width:100%;margin-bottom:0}
.ui-datepicker-title {text-align:center; font-size:1.5rem; font-weight:700}
.ui-datepicker-title select {width:10.6rem; height:3rem; padding:0 3rem 0 1rem; background-color:#fcfcfc; background-position:100% 0; color:#555}
.ui-datepicker-title select:last-child {margin-left:0.5rem}
.ui-datepicker .ui-widget-header {position:relative; padding:0.7rem; background:#fff; border:none; border-bottom:none}
.ui-datepicker .ui-widget-header a {position:absolute; display:block; top:0.9rem; width:0.9rem; height:1.7rem; background-repeat:no-repeat; font-size:0; text-indent:-99999rem; cursor:pointer}
.ui-datepicker .ui-widget-header .ui-datepicker-prev {left:10%; background-image:url('/assets/images/common/icon_arr_03.png'); background-size:100% auto; transform:rotate(-180deg)}
.ui-datepicker .ui-widget-header .ui-datepicker-next {right:10%; background-image:url('/assets/images/common/icon_arr_03.png'); background-size:100% auto}
.ui-datepicker .ui-widget-header .ui-state-hover {background-color:transparent; border:none}
.ui-datepicker td {padding:0 0.6rem; margin:0; height:2.5rem; border-left:1px solid #ddd; border-top:1px solid #ddd; color:#555; text-align:center; font-size:1.3rem; line-height:3rem}
.ui-datepicker td:hover, .ui-datepicker td:hover .ui-state-default {background:#f1f1f1; color:#000}
.ui-datepicker-today, .ui-datepicker-today .ui-state-default.ui-state-highlight {background:#f1f1f1; color:#0063bf; font-weight:800}
.ui-datepicker td:first-child {border-left:none}
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button,html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {border:none}
.ui-widget.ui-widget-content {border-color:#ddd}
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {display:block; background:#fff; color:#666}
.ui-datepicker .ui-datepicker-buttonpane {position:relative; padding:8px; margin-top:0; border:1px solid #999; border-top:none}
.ui-datepicker .ui-datepicker-buttonpane .ui-state-default {width:4.3rem; height:2.2rem; line-height:2.2rem; border:1px solid #ddd; color:#666; font-size:1.3rem; border-radius:0.3rem}
.ui-datepicker .ui-datepicker-buttonpane .ui-datepicker-close {position:absolute; right:0.8rem; top:0.8rem}
.ui-datepicker-calendar th {padding:0; text-align:center; font-size:1.3rem; color:#333; line-height:3rem}


#ui-datepicker-div .ui-datepicker-calendar a {
	text-decoration: none;
	font-weight: 700;
	display: block;
	color: #000;
	width:100%;
	border: 1px solid #f6f6f6;
	background-color: #3978f878;
}

.ui-datepicker td {
    padding: 0 0;
    margin: 0;
    height: 2.5rem;
    border-left: 1px solid #ddd;
    border-top: 1px solid #ddd;
    color: #555;
    text-align: center;
    font-size: 1.3rem;
    line-height: 3rem;
}
</style>

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
										"REFERER"=>$_SERVER['HTTP_REFERER'],
										"SID"=>$log_sid,
										"REFIP"=>$log_refip);

	$log_result = insertLogData($conn, $arr_data);
?>
<body>
	<div id="wrapper">

