<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : high_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-03
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "HI001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/high/high.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$h_no							= isset($_POST["h_no"]) && $_POST["h_no"] !== '' ? $_POST["h_no"] : (isset($_GET["h_no"]) ? $_GET["h_no"] : '');
	$h_who						= isset($_POST["h_who"]) && $_POST["h_who"] !== '' ? $_POST["h_who"] : (isset($_GET["h_who"]) ? $_GET["h_who"] : '');
	$h_nm							= isset($_POST["h_nm"]) && $_POST["h_nm"] !== '' ? $_POST["h_nm"] : (isset($_GET["h_nm"]) ? $_GET["h_nm"] : '');
	$h_title					= isset($_POST["h_title"]) && $_POST["h_title"] !== '' ? $_POST["h_title"] : (isset($_GET["h_title"]) ? $_GET["h_title"] : '');
	$h_numbers				= isset($_POST["h_numbers"]) && $_POST["h_numbers"] !== '' ? $_POST["h_numbers"] : (isset($_GET["h_numbers"]) ? $_GET["h_numbers"] : '');
	$h_venue					= isset($_POST["h_venue"]) && $_POST["h_venue"] !== '' ? $_POST["h_venue"] : (isset($_GET["h_venue"]) ? $_GET["h_venue"] : '');

	$apply_s_date			= isset($_POST["apply_s_date"]) && $_POST["apply_s_date"] !== '' ? $_POST["apply_s_date"] : (isset($_GET["apply_s_date"]) ? $_GET["apply_s_date"] : '');
	$apply_s_hour			= isset($_POST["apply_s_hour"]) && $_POST["apply_s_hour"] !== '' ? $_POST["apply_s_hour"] : (isset($_GET["apply_s_hour"]) ? $_GET["apply_s_hour"] : '');
	$apply_s_min			= isset($_POST["apply_s_min"]) && $_POST["apply_s_min"] !== '' ? $_POST["apply_s_min"] : (isset($_GET["apply_s_min"]) ? $_GET["apply_s_min"] : '');
	$apply_e_date			= isset($_POST["apply_e_date"]) && $_POST["apply_e_date"] !== '' ? $_POST["apply_e_date"] : (isset($_GET["apply_e_date"]) ? $_GET["apply_e_date"] : '');
	$apply_e_hour			= isset($_POST["apply_e_hour"]) && $_POST["apply_e_hour"] !== '' ? $_POST["apply_e_hour"] : (isset($_GET["apply_e_hour"]) ? $_GET["apply_e_hour"] : '');
	$apply_e_min			= isset($_POST["apply_e_min"]) && $_POST["apply_e_min"] !== '' ? $_POST["apply_e_min"] : (isset($_GET["apply_e_min"]) ? $_GET["apply_e_min"] : '');

	$event_s_date			= isset($_POST["event_s_date"]) && $_POST["event_s_date"] !== '' ? $_POST["event_s_date"] : (isset($_GET["event_s_date"]) ? $_GET["event_s_date"] : '');
	$event_s_hour			= isset($_POST["event_s_hour"]) && $_POST["event_s_hour"] !== '' ? $_POST["event_s_hour"] : (isset($_GET["event_s_hour"]) ? $_GET["event_s_hour"] : '');
	$event_s_min			= isset($_POST["event_s_min"]) && $_POST["event_s_min"] !== '' ? $_POST["event_s_min"] : (isset($_GET["event_s_min"]) ? $_GET["event_s_min"] : '');
	$event_e_date			= isset($_POST["event_e_date"]) && $_POST["event_e_date"] !== '' ? $_POST["event_e_date"] : (isset($_GET["event_e_date"]) ? $_GET["event_e_date"] : '');
	$event_e_hour			= isset($_POST["event_e_hour"]) && $_POST["event_e_hour"] !== '' ? $_POST["event_e_hour"] : (isset($_GET["event_e_hour"]) ? $_GET["event_e_hour"] : '');
	$event_e_min			= isset($_POST["event_e_min"]) && $_POST["event_e_min"] !== '' ? $_POST["event_e_min"] : (isset($_GET["event_e_min"]) ? $_GET["event_e_min"] : '');

	$apply_tf					= isset($_POST["apply_tf"]) && $_POST["apply_tf"] !== '' ? $_POST["apply_tf"] : (isset($_GET["apply_tf"]) ? $_GET["apply_tf"] : '');

	$h_contents				= isset($_POST["h_contents"]) && $_POST["h_contents"] !== '' ? $_POST["h_contents"] : (isset($_GET["h_contents"]) ? $_GET["h_contents"] : '');

	$h_file						= isset($_POST["h_file"]) && $_POST["h_file"] !== '' ? $_POST["h_file"] : (isset($_GET["h_file"]) ? $_GET["h_file"] : '');
	$h_file_nm				= isset($_POST["h_file_nm"]) && $_POST["h_file_nm"] !== '' ? $_POST["h_file_nm"] : (isset($_GET["h_file_nm"]) ? $_GET["h_file_nm"] : '');
	$h_date						= isset($_POST["h_date"]) && $_POST["h_date"] !== '' ? $_POST["h_date"] : (isset($_GET["h_date"]) ? $_GET["h_date"] : '');

	$apply_use_tf			= isset($_POST["apply_use_tf"]) && $_POST["apply_use_tf"] !== '' ? $_POST["apply_use_tf"] : (isset($_GET["apply_use_tf"]) ? $_GET["apply_use_tf"] : '');
	$event_use_tf			= isset($_POST["event_use_tf"]) && $_POST["event_use_tf"] !== '' ? $_POST["event_use_tf"] : (isset($_GET["event_use_tf"]) ? $_GET["event_use_tf"] : '');
	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$ref_tf						= isset($_POST["ref_tf"]) && $_POST["ref_tf"] !== '' ? $_POST["ref_tf"] : (isset($_GET["ref_tf"]) ? $_GET["ref_tf"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

	$con_h_type				= isset($_POST["con_h_type"]) && $_POST["con_h_type"] !== '' ? $_POST["con_h_type"] : (isset($_GET["con_h_type"]) ? $_GET["con_h_type"] : '');
	$con_h_program		= isset($_POST["con_h_program"]) && $_POST["con_h_program"] !== '' ? $_POST["con_h_program"] : (isset($_GET["con_h_program"]) ? $_GET["con_h_program"] : '');
	

	$chk							= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	if ($mode == "T") {
		$result = updateHighUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $h_no);
		$use_tf = "";
	}

	if ($mode == "M") {
		$result = updateHighRefTF($conn, $ref_tf, $_SESSION['s_adm_no'], $h_no);
		$use_tf = "";
	}

	if ($mode == "O") {

		$row_cnt = is_null($high_seq_no) ? 0 : count($high_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_h_no = $high_seq_no[$k];

			$result = updateOrderHigh($conn, $k, $tmp_h_no);
		
		}
	}

	if ($mode == "D") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_no = $chk[$k];
			$result = deleteHigh($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "고교연계 페이지 삭제 (".$tmp_idx.") ", "Delete");
		}
	}

	#List Parameter
	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	$nPage					= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf					= SetStringToDB($use_tf);
	
	$del_tf					= "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "" && $nPageSize <> 0) {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10; 

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntHigh($conn, $h_no, $h_who, $con_h_type, $con_h_program, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listHigh($conn, $h_no, $h_who, $con_h_type, $con_h_program, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "고교연계 페이지 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>

<script type="text/javascript" >

	$(document).ready(function() {
		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});
	});

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
	
	function js_add_high() {
		document.location = "high_write.php";
	}

	function js_view(h_no) {
		var frm = document.frm;
		frm.h_no.value = h_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "high_write.php";
		frm.submit();
	}

	function js_delete() {

		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
		
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
		
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
			
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER['PHP_SELF']?>";
				frm.submit();
			}
		}
	}

	function js_check() {

		var frm = document.frm;

		check=document.getElementsByName("chk[]");

		if(frm.all_chk_no.checked){
			for (i=0;i<check.length;i++) {
					check.item(i).checked=true;
			}
		} else {
			for (i=0;i<check.length;i++) {
					check.item(i).checked=false;
			}
		}
	}

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 1) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.seq_h_no[preid-2].value;
			
				document.frm.seq_h_no[preid-2].value = document.frm.seq_h_no[preid-1].value;
				document.frm.seq_h_no[preid-1].value = tempCode;
			}
		}
		
		//preid = preid - 1;

		js_change_order();

	} else {
		alert("가장 상위에 있습니다. ");
	}
}

function js_down(n) {

	preid = parseInt(n);

	if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_h_no[preid] != null)) {

	//alert(preid);
	//return;

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 1) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.seq_h_no[preid-1].value;
				document.frm.seq_h_no[preid-1].value = document.frm.seq_h_no[preid].value;
				document.frm.seq_h_no[preid].value = tempCode;
			}
		}
		
		//preid = preid + 1;
		js_change_order();

	} else{
		alert("가장 하위에 있습니다. ");
	}
}

function js_change_order() {

	if(document.getElementById("t").rows.length < 2) {
		alert("순서를 저장할 메뉴가 없습니다");//순서를 저장할 메뉴가 없습니다");
		return;
	}

	document.frm.mode.value = "O";
	document.frm.target = "ifr_hidden";
	document.frm.action = "high_order_dml.php";
	document.frm.submit();

}

function js_toggle(h_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
	
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.h_no.value = h_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
}

function js_toggle_ref(h_no, ref_tf) {
	var frm = document.frm;

	bDelOK = confirm('메인을 변경 하시겠습니까?');
	
	if (bDelOK==true) {

		if (ref_tf == "Y") {
			ref_tf = "N";
		} else {
			ref_tf = "Y";
		}

		frm.h_no.value = h_no;
		frm.ref_tf.value = ref_tf;
		frm.mode.value = "M";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
}

	function js_disp_seq(h_no, disp_seq) {
	
		var bOK = confirm("전시 순위를 변경 하시겠습니까?");
	
		if (bOK) { 
		
			var mode = "change_disp_seq";

			var request = $.ajax({
				url:"/manager/high/ajax.high.php",
				type:"POST",
				data:{mode:mode, h_no:h_no, disp_seq:disp_seq},
				dataType:"json"
			});

			request.done(function(data) {
				if (data.result == "T") {
					//alert(data.msg);
					js_reload();
				} else {
					alert(data.msg);
				}
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});
	
		} else {
			js_reload();
		}

	}

		function js_reload() {
			var frm = document.frm;
			frm.method = "get";
			frm.target = "";
			frm.action = "<?=$_SERVER['PHP_SELF']?>";
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

		<form id="bbsList" name="frm" method="post">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
			<input type="hidden" name="h_no" value="">
			<input type="hidden" name="ref_tf" value="">
			<input type="hidden" name="use_tf" value="">


					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<select name="search_field" id="search_field">
							<option value="H_NM" <? if ($search_field == "H_NM") echo "selected"; ?> >프로그램명</option>
							</select>
							<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
						</div>
					</div>

					<div class="tbl_style01">
						<table id="t">
							<colgroup>
								<col width="3%" />
								<col width="5%" /><!-- 순서-->
								<col width="15%" /><!-- 프로그램구분  -->
								<col width="22%" /><!-- 프로그램명  -->
								<col width="15%" /><!-- 신청기간  -->
								<col width="15%" /><!-- 행사기간 -->
								<col width="10%" /><!-- 신청상태 -->
								<col width="5%" /><!-- 공개여부 -->
								<col width="10%" /><!-- 등록일 -->

							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
									<th>순서</th>
									<th>프로그램구분</th>
									<th>프로그램명</th>
									<th>신청기간</th>
									<th>행사기간</th>
									<th>신청상태</th>
									<th>공개여부</th>
									<th>등록일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

										$rn								= trim($arr_rs[$j]["rn"]);
										$H_NO							= trim($arr_rs[$j]["H_NO"]);
										$H_WHO						= trim($arr_rs[$j]["H_WHO"]);
										$H_NM							= trim($arr_rs[$j]["H_NM"]);
										$H_TYPE						= trim($arr_rs[$j]["H_TYPE"]);	
										$H_PROGRAM				= trim($arr_rs[$j]["H_PROGRAM"]);	
										$H_TITLE					= trim($arr_rs[$j]["H_TITLE"]);	
										$H_NUMBERS				= trim($arr_rs[$j]["H_NUMBERS"]);	
										$H_DATE						= trim($arr_rs[$j]["H_DATE"]);
										$APPLY_S_DATE			= trim($arr_rs[$j]["APPLY_S_DATE"]);
										$APPLY_E_DATE			= trim($arr_rs[$j]["APPLY_E_DATE"]);
										$EVENT_S_DATE			= trim($arr_rs[$j]["EVENT_S_DATE"]);
										$EVENT_E_DATE			= trim($arr_rs[$j]["EVENT_E_DATE"]);

										$APPLY_TF					= trim($arr_rs[$j]["APPLY_TF"]); //신청상태 수동 설정 2021.03.17 추가

										//$H_URL						= trim($arr_rs[$j]["H_URL"]);	
										$H_TARGET					= trim($arr_rs[$j]["H_TARGET"]);
										//$H_IMG			  		= trim($arr_rs[$j]["H_IMG"]);
										//$H_IMG_NM		  		= trim($arr_rs[$j]["H_IMG_NM"]);
										//$H_THUMB		  		= trim($arr_rs[$j]["H_THUMB"]);
										//$H_THUMB_NM		  	= trim($arr_rs[$j]["H_THUMB_NM"]);
										$DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);
										//$DATE_USE_TF			= trim($arr_rs[$j]["DATE_USE_TF"]);
										//$REF_TF						= trim($arr_rs[$j]["REF_TF"]);
										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);
										
										/*
										if ($REF_TF == "Y") {
											$STR_REF_TF = "<font color='blue'>MAIN</font>";
										} else {
											$STR_REF_TF = "<font color='red'>NO</font>";
										}
										*/

										if ($USE_TF == "Y") {
											$STR_USE_TF = "<font color='blue'>공개</font>";
										} else {
											$STR_USE_TF = "<font color='red'>비공개</font>";
										}

										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

										$offset = $nPageSize * ($nPage-1);
										$logical_num = ($nListCnt - $offset);
										$rn = $logical_num - $j;
							?>
									<tr>
										<td><input type="checkbox" name="chk[]" value="<?=$H_NO?>"></td>
										<td class="moveIcon">
										<? if ($USE_TF == "Y") { ?>
											<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/common/icon_arr_top.gif" alt="up" /></a>
											<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/common/icon_arr_bot.gif" alt="down" /></a>
										<? } ?>
										</td>
										<td><a href="javascript:js_view('<?=$H_NO?>');"><?=getDcodeName($conn,"HIGH_TYPE", $H_TYPE)?> - <?=getDcodeName($conn, "HIGH_PROGRAM", $H_PROGRAM)?></a>
										<td><a href="javascript:js_view('<?=$H_NO?>');"><?=$H_NM?></a>
										<? if ($USE_TF == "Y") { ?>
											<input type="hidden" name="seq_h_no" value="<?=$H_NO?>">
											<input type="hidden" name="high_seq_no[]" value="<?=$H_NO?>">
										<? } ?>
										</td>
										<td><?=$APPLY_S_DATE?> ~ <?=$APPLY_E_DATE?></td>
										<td><?=$EVENT_S_DATE?> ~ <?=$EVENT_E_DATE?></td>
										<td>
											<? ////신청상태 수동 설정 2021.03.17 추가
												if ($APPLY_TF == ""){
													$n = date("Y-m-d H:i:s");
													if (($n > $APPLY_E_DATE) and ($APPLY_S_DATE != "")) {
														$str = "<button type='button' class='button type02'>마감";
													} else if (($n > $APPLY_S_DATE) and ($n < $APPLY_E_DATE)) {
														$str = "<button type='button' class='button'>신청중";
													} else {
														$str = "<button type='button' class='button type02'>준비중";
													}
												} else {
													if ($APPLY_TF == "P"){
														$str = "<button type='button' class='button type02'>준비중";
													} else if ($APPLY_TF == "I"){
														$str = "<button type='button' class='button'>신청중";
													} else {
														$str = "<button type='button' class='button type02'>마감";
													}
												}
												//수동 추가 end
											?>
											<a href="javascript:js_view('<?=$H_NO?>');"><?=$str?></button></a>
										</td>
										<td><a href="javascript:js_toggle('<?=$H_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a></td>
										<!-- <td><?=makeDispSeqSelect("disp_seq", $H_NO, $DISP_SEQ)?></td> //-->
										<td><?=$REG_DATE?></td>
									</tr>

							<?			
									}
								} else { 
							?> 
								<tr>
									<td colspan="15">
										<div class="nodata">데이터가 없습니다.</div>
									</td>
								</tr>
							<? 
								}
							?>
							</tbody>
						</table>
					</div>
					<div class="btn_wrap">
						<div class="right">
							<!-- 버튼 자리 -->
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_add_high();" >등록</button>
							<? } ?>
							<?	if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete();">삭제</button>
							<?  } ?>
						</div>
					</div>
						</div>

						<div class="wrap_paging">
						<?
							# ==========================================================================
							#  페이징 처리
							# ==========================================================================
							if (sizeof($arr_rs) > 0) {
								#$search_field		= trim($search_field);
								#$search_str			= trim($search_str);
								$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>
						<p>
						</div>
					</div>
				</div>
</form>

			</div>
		</div>
	</div>
<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
