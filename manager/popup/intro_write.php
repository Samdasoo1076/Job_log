<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : intro_write.php
# Modlue       : 
# Writer       : Lee Ji Min
# Create Date  : 2025-01-09
# Modify Date  : 
# Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PO001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/popup/intro.php";

#====================================================================
# DML Process
#====================================================================

$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$intro_no = isset($_POST["intro_no"]) && $_POST["intro_no"] !== '' ? $_POST["intro_no"] : (isset($_GET["intro_no"]) ? $_GET["intro_no"] : '');
$intro_title = isset($_POST["intro_title"]) && $_POST["intro_title"] !== '' ? $_POST["intro_title"] : (isset($_GET["intro_title"]) ? $_GET["intro_title"] : '');
$intro_memo = isset($_POST["intro_memo"]) && $_POST["intro_memo"] !== '' ? $_POST["intro_memo"] : (isset($_GET["intro_memo"]) ? $_GET["intro_memo"] : '');
$item_no = isset($_POST["item_no"]) && $_POST["item_no"] !== '' ? $_POST["item_no"] : (isset($_GET["item_no"]) ? $_GET["item_no"] : '');
$item_num = isset($_POST["item_num"]) && $_POST["item_num"] !== '' ? $_POST["item_num"] : (isset($_GET["item_num"]) ? $_GET["item_num"] : '');
$i_no = isset($_POST["i_no"]) && $_POST["i_no"] !== '' ? $_POST["i_no"] : (isset($_GET["i_no"]) ? $_GET["i_no"] : '');
$i_title_s = isset($_POST["i_title_s"]) && $_POST["i_title_s"] !== '' ? $_POST["i_title_s"] : (isset($_GET["i_title_s"]) ? $_GET["i_title_s"] : '');
$i_title = isset($_POST["i_title"]) && $_POST["i_title"] !== '' ? $_POST["i_title"] : (isset($_GET["i_title"]) ? $_GET["i_title"] : '');
$i_url = isset($_POST["i_url"]) && $_POST["i_url"] !== '' ? $_POST["i_url"] : (isset($_GET["i_url"]) ? $_GET["i_url"] : '');
$i_target = isset($_POST["i_target"]) && $_POST["i_target"] !== '' ? $_POST["i_target"] : (isset($_GET["i_target"]) ? $_GET["i_target"] : '');

$disp_seq = isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');

$s_date = isset($_POST["s_date"]) && $_POST["s_date"] !== '' ? $_POST["s_date"] : (isset($_GET["s_date"]) ? $_GET["s_date"] : '');
$s_hour = isset($_POST["s_hour"]) && $_POST["s_hour"] !== '' ? $_POST["s_hour"] : (isset($_GET["s_hour"]) ? $_GET["s_hour"] : '');
$s_min = isset($_POST["s_min"]) && $_POST["s_min"] !== '' ? $_POST["s_min"] : (isset($_GET["s_min"]) ? $_GET["s_min"] : '');
$e_date = isset($_POST["e_date"]) && $_POST["e_date"] !== '' ? $_POST["e_date"] : (isset($_GET["e_date"]) ? $_GET["e_date"] : '');
$e_hour = isset($_POST["e_hour"]) && $_POST["e_hour"] !== '' ? $_POST["e_hour"] : (isset($_GET["e_hour"]) ? $_GET["e_hour"] : '');
$e_min = isset($_POST["e_min"]) && $_POST["e_min"] !== '' ?	 $_POST["e_min"] : (isset($_GET["e_min"]) ? $_GET["e_min"] : '');
$date_use_tf = isset($_POST["date_use_tf"]) && $_POST["date_use_tf"] !== '' ? $_POST["date_use_tf"] : (isset($_GET["date_use_tf"]) ? $_GET["date_use_tf"] : '');

$file_nm					= isset($_POST["file_nm"]) && $_POST["file_nm"] !== '' ? $_POST["file_nm"] : (isset($_GET["file_nm"]) ? $_GET["file_nm"] : '');
$file_rnm					= isset($_POST["file_rnm"]) && $_POST["file_rnm"] !== '' ? $_POST["file_rnm"] : (isset($_GET["file_rnm"]) ? $_GET["file_rnm"] : '');

$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
$reg_date = isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');
$search_field = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');


	if ($date_use_tf <> "N") {
		$start_date = $s_date." ".$s_hour.":".$s_min.":00";
		$end_date = $e_date." ".$e_hour.":".$e_min.":59";
		$date_use_tf = "Y";
	} else {
		$start_date = "";
		$end_date = "";
	}

	$arr_rs_detail = array();
#====================================================================
 $savedir1 = $g_physical_path."upload_data/popup";
#====================================================================

	$result = false;

	$intro_title		= SetStringToDB($intro_title);
	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	
	if($mode == "") {
		$rs_intro_no = ""; 
		$rs_intro_title = ""; 
		$rs_intro_memo = ""; 
		$rs_intro_hit_cnt = ""; 
		$rs_start_date = ""; 
		$rs_end_date = ""; 
		$rs_date_use_tf = ""; 
		$rs_use_tf = ""; 
		$rs_del_tf = ""; 
		$rs_reg_adm = ""; 
		$rs_reg_date = ""; 
		$rs_up_adm = ""; 
		$rs_up_date = ""; 

		$arr_start_date = array();
		$arr_end_date = array();

		$arr_start_time = array();
		$arr_end_time = array();

		$rs_s_date = "";
		$rs_e_date = "";

		$rs_s_hour = "";
		$rs_e_hour = "";

		$rs_s_min = "";
		$rs_e_min = "";
	}


	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "I") {

			$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));


		if ($_FILES["file_nm"]["name"] <> ""){
			$file_nm					= upload($_FILES["file_nm"], $savedir1, 100 , array('jpg','png','gif','jpeg','tif'));
			$file_rnm					= $_FILES["file_nm"]["name"];
		}

		$arr_data = array("INTRO_TITLE"=>$intro_title,
			"INTRO_MEMO"=>$intro_memo,
			"HIT_CNT"=>0,
			"INTRO_DISP_SEQ"=>0,
			"FILE_NM"=> $file_nm,
			"FILE_RNM"=> $file_rnm,
			"START_DATE"=>$start_date,
			"END_DATE"=>$end_date,
			"DATE_USE_TF"=>$date_use_tf,
			"USE_TF"=>$use_tf,
			"REG_ADM"=>$_SESSION["s_adm_no"],
			"UP_DATE"=>$REG_INSERT_DATE
		);

	//추가
	// 현재 날짜 기반 폴더 설정
    // $current_date = date("Ymd");
    // $upload_dir = $savedir1 . "/" . $current_date;

    // if (!is_dir($upload_dir)) {
    //     mkdir($upload_dir, 0777, true); // 디렉토리 생성
    // }

    // // 파일 업로드 처리
    // if (isset($_FILES['file_nm']) && $_FILES['file_nm']['error'] === UPLOAD_ERR_OK) {
	// 	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

    //     $file_tmp_name = $_FILES['file_nm']['tmp_name'];
    //     $original_file_name = $_FILES['file_nm']['name'];
    //     $file_size = $_FILES['file_nm']['size'];
    //     $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);

    //     // 고유한 파일 이름 생성
    //     //$new_file_name = uniqid() . "." . $file_extension;
    //     //$target_file = $upload_dir . "/" . $new_file_name;

    //     // 파일 이동
    //     if (move_uploaded_file($file_tmp_name, $target_file)) {

    //         $file_nm = $new_file_name; // 저장된 파일명
    //         $file_rnm = $original_file_name; // 원본 파일명
    //         $file_path = $current_date; // 파일 저장 경로

	// 		$arr_data = array("INTRO_TITLE"=>$intro_title,
	// 		"INTRO_MEMO"=>$intro_memo,
	// 		"HIT_CNT"=>0,
	// 		"INTRO_DISP_SEQ"=>0,
	// 		"FILE_NM"=> $file_nm,
	// 		"FILE_RNM"=> $file_rnm,
	// 		"FILE_PATH"=> $file_nm,
	// 		"START_DATE"=>$start_date,
	// 		"END_DATE"=>$end_date,
	// 		"DATE_USE_TF"=>$date_use_tf,
	// 		"USE_TF"=>$use_tf,
	// 		"REG_ADM"=>$_SESSION["s_adm_no"],
	// 		"UP_DATE"=>$REG_INSERT_DATE
	// 	);
	// 	$new_intro_no	= insertIntro($conn, $arr_data);
	// 	$result =$new_intro_no; 

	// 	echo "<script>alert('파일이 성공적으로 업로드되었습니다.');</script>";

    //         if ($result) {
    //             echo "<script>alert('파일이 성공적으로 업로드되었습니다.');</script>";
    //         } else {
    //             echo "<script>alert('DB 저장 중 오류가 발생했습니다.');</script>";
    //         }
    //     } else {
    //         echo "<script>alert('파일 업로드 중 문제가 발생했습니다.');</script>";
    //     }
    // } else {
    //     echo "<script>alert('파일이 업로드되지 않았습니다.');</script>";
    // }
 //추가
		$new_room_no	= insertIntro($conn, $arr_data);
		$result =$new_room_no;			
		
		$result_log	= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '메인 팝업 등록 (제목 : '.$intro_title.') ', 'Insert');
   
	}

	if ($mode == "U") {

		$arr_data = array("INTRO_TITLE"=>$intro_title,
											"INTRO_MEMO"=>$intro_memo,
											"START_DATE"=>$start_date,
											"END_DATE"=>$end_date,
											"DATE_USE_TF"=>$date_use_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateIntro($conn, $arr_data, $intro_no);

		//세부정보 update
		if (sizeof($i_no) > 0) {
			for ($j = 0 ; $j < sizeof($i_no); $j++) {

				$arr_data_detail = array("I_TITLE_S"=>$i_title_s[$j],
														"I_TITLE"=>$i_title[$j],
														"I_URL"=>$i_url[$j],
														"I_TARGET"=>$i_target[$j],
														"DISP_SEQ"=>0,
														"USE_TF"=>$use_tf,
														"REG_ADM"=>$_SESSION["s_adm_no"],
														"UP_DATE"=>$REG_INSERT_DATE
				);
				$result_detail = updateIntroDetail($conn, $arr_data_detail, $i_no[$j]);
			}
		}

		$arr_rs_detail_num	= selectIntroDetailNum($conn, $intro_no);

		//페이지 세부정보를 update로 추가시 
		if (sizeof($i_title) > $arr_rs_detail_num) {
			for ($j =$arr_rs_detail_num ; $j < sizeof($i_title); $j++) {

				$arr_data_detail = array("INTRO_NO"=>$intro_no,
														"I_TITLE_S"=>$i_title_s[$j],
														"I_TITLE"=>$i_title[$j],
														"I_URL"=>$i_url[$j],
														"I_TARGET"=>$i_target[$j],
														"DISP_SEQ"=>0,
														"USE_TF"=>$use_tf,
														"REG_ADM"=>$_SESSION["s_adm_no"],
														"UP_DATE"=>$REG_INSERT_DATE
				);
				$result_detail = insertIntroDetail($conn, $arr_data_detail);
			}
		}
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '인트로 수정 (제목 : '.$i_title.') ', 'Update');
	}

	if ($mode == "D") {
		$result = deleteIntro($conn, $_SESSION['s_adm_no'], (int)$intro_no);
		$result = deleteIntroDetail($conn, $_SESSION['s_adm_no'], (int)$intro_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '인트로 삭제 처리 (제목 : '.$intro_title.') ', 'Delete');
	}

	if ($mode == "ITEM") {
		$result = deleteIntroDetail_1($conn, $_SESSION['s_adm_no'], (int)$item_no, (int)$intro_no);
		$mode = "S";
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '인트로 세부항목 삭제 처리 (제목 : '.$i_title.') ', 'Delete');
	}

	if ($mode == "S") {

		$arr_rs = selectIntro($conn, (int)$intro_no);

		$rs_intro_no						= trim($arr_rs[0]["INTRO_NO"]); 
		$rs_intro_title					= SetStringFromDB($arr_rs[0]["INTRO_TITLE"]); 
		$rs_intro_memo					= SetStringFromDB($arr_rs[0]["INTRO_MEMO"]); 
		$rs_intro_hit_cnt				= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_start_date					= trim($arr_rs[0]["START_DATE"]); 
		$rs_end_date						= trim($arr_rs[0]["END_DATE"]); 
		$rs_date_use_tf					= trim($arr_rs[0]["DATE_USE_TF"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm							= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date							= trim($arr_rs[0]["UP_DATE"]); 

		$arr_start_date			= explode(" ", $rs_start_date);
		$arr_end_date				= explode(" ", $rs_end_date);

		$arr_start_time			= explode(":", $arr_start_date[1]);
		$arr_end_time				= explode(":", $arr_end_date[1]);
		
		$rs_s_date = $arr_start_date[0];
		$rs_e_date = $arr_end_date[0];
				
		$rs_s_hour = $arr_start_time[0];
		$rs_e_hour = $arr_end_time[0];

		$rs_s_min = $arr_start_time[1];
		$rs_e_min = $arr_end_time[1];

		$arr_rs_detail						= selectIntro($conn, $rs_intro_no);

		$result_log							= insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], '인트로 조회 (제목 : '.$rs_intro_title.') ', 'Read');

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "intro_list.php<?=$strParam?>";
		//document.location.href = "intro_write.php?mode=S&intro_no=10";
		//document.location.href = "intro_write.php<?=$strParam?>";
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
<title><?=$g_title?></title>
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

// 이미지를 리사이징 하는 함수 추가

// <input type="file" name="file_nm" value="" style="display:none">

function js_list() {
	var frm = document.frm_list;
	frm.method = "get";
	frm.action = "intro_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var intro_no = "<?=$intro_no?>";
	var i_no = "<?=$i_no?>";

	var ret = true;
	var chk = 1;  //항목 배열 공백 체크

	if(frm.intro_title.value == ""){
		alert("제목을 입력해주세요.");
		frm.intro_title.focus();
		return false;
	}

/*
	$("input[name='i_title_s[]']").each(function(idx, item){
			if(!$(item).val().trim()){
				ret=false;
				alert(idx+1+"번째 항목 소제목을 입력해주세요.");
				$(item).focus();
				return false;
			}
	});
*/

	$("input[name='i_title[]']").each(function(idx, item){
			if(!$(item).val().trim()){
				ret=false;
				alert(idx+1+"번째 항목 제목을 입력해주세요.");
				$(item).focus();
				return false;
			}
	});

	if(ret==true){
		$("input[name='i_url[]']").each(function(idx, item){
			if(!$(item).val().trim()){
				ret=false;
				alert(idx+1+"번째 링크 주소(URL)를 입력해주세요.");
				$(item).focus();
				return false;
			}
		});
	}

 if(ret!=true){
	 return false;
 }

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	frm.target = "";
	frm.method = "post";
	frm.action = "<?=$_SERVER['PHP_SELF']?>";

	if (isNull(intro_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.item_num.value = $("input[name='i_title[]']").length;
	}
	frm.submit();
}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');

	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
}

function js_delete_1(ii) {

	var frm = document.frm;
	bDelOK = confirm('항목을 삭제 하시겠습니까?');

	if (bDelOK==true) {
		frm.mode.value = "ITEM";
		frm.item_no.value = ii
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
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
<tr?
	#====================================================================
	# common location_area
	#====================================================================
		require "../../_common/location_area.php";
?>
				<div class="tit_h3"><h3><?=$p_menu_name ?></h3></div>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="intro_no" value="<?=$intro_no?>" />
<input type="hidden" name="i_no" value="<?=$i_no?>" />
<input type="hidden" name="item_no" value="<?=$i_no?>" />
<input type="hidden" name="item_num" value="<?=$item_num?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>메인 비주얼 공지 등록 <?= $mode ?></h4></div>
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:10%" />
								<col />
							</colgroup>
							<tbody>

								<tr>
									<th>제목 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td>
										<textarea name="intro_title" style=" width:300px;height:55px;padding:2px;" style="vertical-align:bottom"><? if($mode == "S") {?> 
										
										<? echo $rs_intro_title; 
											} else {
											echo $intro_title;
										}?>
										</textarea>
										<font style="vertical-align:bottom;color:rgb(251,177,50)"> &nbsp;&nbsp;2줄 입력</font>
									</td>
								</tr>

								<tr>
									<th>관리자메모 </th>
									<td>
										<input type="text" name="intro_memo" id="intro_memo" value="<?=$rs_intro_memo?>" style="width:80%"/>
									</td>
								</tr>

								<tr>
									<th>바로가기 URL</th>
									<td>
										<input type="text" name="intro_memo" id="intro_memo" value="<?=$rs_intro_memo?>" style="width:80%"/>
									<td>
								</tr>

								<tr id="dateclass">
									<th>게시 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td valign="middle">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="s_date" id="s_date" class="datepicker onlyphone" value="<?=$rs_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","s_hour","120","","",$rs_s_hour)?>
											<select name="s_min" id="s_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_s_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="e_date" id="e_date" class="datepicker onlyphone" value="<?=$rs_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","e_hour","120","","",$rs_e_hour)?>
											<select name="e_min" id="e_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_e_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<div style="display:inline-block;height:3px;width:160px;padding-left:20px;vertical-align: middle;">
											<input type="checkbox" class="radio" name="date_use_tf" id="date_use_tf" value="N" <? if ($rs_date_use_tf == "N") echo "checked"?> > 선택 시 무제한 노출 
										</div>
									</td>
								</tr>
													<?
													$nCnt = 0;
													
													if (sizeof($arr_rs_detail) > 0) {
														
														for ($j = 0 ; $j < sizeof($arr_rs_detail); $j++) {
														
															$file_nm				= trim($arr_rs_detail[$j]["FILE_NM"]);	
												?>
															
															
												<? } } ?>
							
								<tr>
								<th scope="row">첨부파일</th>
								<td colspan="3">
									<div id="item_add" style="padding-top:10px">
											<input type="hidden" name="file_flag[]" value=""> 
											<input type="file" name="file_nm" id="upload_org_file" value="" style="display:none">
											<input type="hidden" name="old_file_no[]" value="">
											<input type="hidden" name="old_file_nm[]" value="">
											<input type="hidden" name="old_file_rnm[]" value="">
												<?php if (!empty($file_nm)) { ?>
                <!-- 업로드된 파일이 있는 경우 -->
                <div style="padding-bottom:10px;">
                    <span class="tbl_txt">이미지</span>
                    <img src="/upload_data/popup/<?=$file_nm?>" width="150px" alt="Uploaded Image">&nbsp;&nbsp;
                    <button type="button" onclick="deleteUploadedFile()">삭제</button>
                    <input type="hidden" name="old_file_nm" value="<?=$file_nm?>">
                    <input type="hidden" name="old_file_rnm" value="<?=$file_rnm?>">
                </div>
            <?php } else { ?>
                <!-- 업로드된 파일이 없는 경우 -->
                <div style="padding-bottom:10px;">
                    <span class="tbl_txt">이미지</span>
                    <input type="file" name="file_nm" accept="image/*" style="display:block;">
                    <span class="tbl_txt">이미지 크기: 800 x 600 (권장)</span>
                </div>
            <?php } ?>
											</div>
											<!--<span class="txt_c02" style="padding-left:10px">※ Drag & Drop 으로 순서를 조정 하실 수 있습니다.</span> !-->
								</td>
							</tr>

								<tr>
									<th>공개여부</th>
									<td>
										<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /> <label for="all">공개</label>&nbsp;&nbsp;
										<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret">비공개</label>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>" />
									</td>
								</tr>

							</tbody>
						</table>
					</div>
					
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ($intro_no == "") {?>
						<?	if ($sPageRight_I == "Y") {?>
						<button type="button" class="button" onClick="js_save()">등록</button>
						<?	} ?>
						<? } else {  ?>
						<?	if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="js_save()">수정</button>
						<?	} ?>
						<?	if ($sPageRight_D == "Y") {?>
						<!--<button type="button" class="button" onClick="js_delete()">삭제</button>-->
						<?	} ?>
						<? } ?>
					</div>
				</div>

</form>

<form name="frm_list" method="post">

<input type="hidden" name="intro_no" value="<?=$intro_no?>" />
<input type="hidden" name="i_no" value="<?=$i_no?>" />
<input type="hidden" name="item_no" value="<?=$i_no?>" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>" />
<input type="hidden" name="search_str" value="<?=$search_str?>" />
<input type="hidden" name="order_field" value="<?=$order_field?>" />
<input type="hidden" name="order_str" value="<?=$order_str?>" />

</form>


			</div>

		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================

	db_close($conn);
?>