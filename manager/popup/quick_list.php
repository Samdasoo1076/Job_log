<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : quick_list.php
# Modlue       : 
# Writer       : Park Chan Ho
# Create Date  : 2023-08-01
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

	$mtype			= isset($_POST["mtype"]) && $_POST["mtype"] !== '' ? $_POST["mtype"] : (isset($_GET["mtype"]) ? $_GET["mtype"] : '');
	$con_q_type	= isset($_POST["con_q_type"]) && $_POST["con_q_type"] !== '' ? $_POST["con_q_type"] : (isset($_GET["con_q_type"]) ? $_GET["con_q_type"] : '');

	if ($mtype <> "TOP") {
		if ($con_q_type == "") $con_q_type = "ALL";
	}

	if ($mtype == "TOP") { 
		$menu_right = "PO004"; // 메뉴마다 셋팅 해 주어야 합니다
	} else if ($mtype == "MIDDLE") {
		$menu_right = "PO005"; // 메뉴마다 셋팅 해 주어야 합니다
	} else if ($mtype == "BOTTOM") {
		$menu_right = "PO006"; // 메뉴마다 셋팅 해 주어야 합니다
	} else if ($mtype == "CAMPUS") {
		$menu_right = "PO010"; // 메뉴마다 셋팅 해 주어야 합니다
	}

	//echo $con_q_type;

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
	require "../../_classes/biz/popup/quick.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$q_no							= isset($_POST["q_no"]) && $_POST["q_no"] !== '' ? $_POST["q_no"] : (isset($_GET["q_no"]) ? $_GET["q_no"] : '');
	$q_type						= isset($_POST["q_type"]) && $_POST["q_type"] !== '' ? $_POST["q_type"] : (isset($_GET["q_type"]) ? $_GET["q_type"] : '');
	$q_title					= isset($_POST["q_title"]) && $_POST["q_title"] !== '' ? $_POST["q_title"] : (isset($_GET["q_title"]) ? $_GET["q_title"] : '');
	$q_subtitle				= isset($_POST["q_subtitle"]) && $_POST["q_subtitle"] !== '' ? $_POST["q_subtitle"] : (isset($_GET["q_subtitle"]) ? $_GET["q_subtitle"] : '');
	$q_description		= isset($_POST["q_description"]) && $_POST["q_description"] !== '' ? $_POST["q_description"] : (isset($_GET["q_description"]) ? $_GET["q_description"] : '');
	$q_url						= isset($_POST["q_url"]) && $_POST["q_url"] !== '' ? $_POST["q_url"] : (isset($_GET["q_url"]) ? $_GET["q_url"] : '');
	$q_target					= isset($_POST["q_target"]) && $_POST["q_target"] !== '' ? $_POST["q_target"] : (isset($_GET["q_target"]) ? $_GET["q_target"] : '');

	$s_date						= isset($_POST["s_date"]) && $_POST["s_date"] !== '' ? $_POST["s_date"] : (isset($_GET["s_date"]) ? $_GET["s_date"] : '');
	$s_hour						= isset($_POST["s_hour"]) && $_POST["s_hour"] !== '' ? $_POST["s_hour"] : (isset($_GET["s_hour"]) ? $_GET["s_hour"] : '');
	$s_min						= isset($_POST["s_min"]) && $_POST["s_min"] !== '' ? $_POST["s_min"] : (isset($_GET["s_min"]) ? $_GET["s_min"] : '');
	$e_date						= isset($_POST["e_date"]) && $_POST["e_date"] !== '' ? $_POST["e_date"] : (isset($_GET["e_date"]) ? $_GET["e_date"] : '');
	$e_hour						= isset($_POST["e_hour"]) && $_POST["e_hour"] !== '' ? $_POST["e_hour"] : (isset($_GET["e_hour"]) ? $_GET["e_hour"] : '');
	$e_min						= isset($_POST["e_min"]) && $_POST["e_min"] !== '' ? $_POST["e_min"] : (isset($_GET["e_min"]) ? $_GET["e_min"] : '');

	$date_use_tf			= isset($_POST["date_use_tf"]) && $_POST["date_use_tf"] !== '' ? $_POST["date_use_tf"] : (isset($_GET["date_use_tf"]) ? $_GET["date_use_tf"] : '');

	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$chk							= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	if ($mode == "T") {
		$result = updateQuickUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $q_no);
		$use_tf = "";
	}

	if ($mode == "O") {

		$row_cnt = is_null($quick_seq_no) ? 0 : count($quick_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_q_no = $quick_seq_no[$k];

			$result = updateOrderQuick($conn, $k, $tmp_q_no);
		
		}
	}

	if ($mode == "D") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_no = $chk[$k];
			$result = deleteQuick($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "퀵링크 페이지 삭제 (".$tmp_idx.") ", "Delete");
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

	$nListCnt =totalCntQuick($conn, $mtype, $con_q_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listQuick($conn, $mtype, $con_q_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "퀵링크 페이지 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$q_title?></title>
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
		frm.action = "quick_list.php";
		frm.submit();
	}
	
	function js_add_quick() {
		document.location = "quick_write.php?mtype=<?=$mtype?>&con_q_type=<?=$con_q_type?>";
	}

	function js_view(q_no) {
		var frm = document.frm;
		frm.q_no.value = q_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "quick_write.php";
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

				var tempCode = document.frm.seq_q_no[preid-2].value;
			
				document.frm.seq_q_no[preid-2].value = document.frm.seq_q_no[preid-1].value;
				document.frm.seq_q_no[preid-1].value = tempCode;
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

	if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_q_no[preid] != null)) {

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
	
				var tempCode = document.frm.seq_q_no[preid-1].value;
				document.frm.seq_q_no[preid-1].value = document.frm.seq_q_no[preid].value;
				document.frm.seq_q_no[preid].value = tempCode;
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
	document.frm.action = "quick_order_dml.php";
	document.frm.submit();

}

function js_toggle(q_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
	
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.q_no.value = q_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
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
			<input type="hidden" name="q_no" value="">
			<input type="hidden" name="use_tf" value="">
			<input type="hidden" name="mtype" value="<?=$mtype?>">

					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<? if ($mtype <> "TOP") { ?>
							<select name="con_q_type" id="con_q_type" onChange="js_search();">
								<option value="ALL" <? if ($con_q_type == "ALL") echo "selected"; ?>>주요입시정보</option>
							</select>
							<? } ?>
							<select name="search_field" id="search_field">
							<option value="Q_SUBTITLE" <? if ($search_field == "Q_SUBTITLE") echo "selected"; ?> >제목</option>
							<!--<option value="Q_TYPE" <? if ($search_field == "Q_TYPE") echo "selected"; ?> >구분</option>-->
							</select>
							<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
						</div>
					</div>

					<div class="tbl_style01">
						<table id="t">
							<colgroup>
								<col width="5%" />
								<col width="5%" /><!-- 순서 -->
								<!--<col width="5%" /><!-- 구분 -->
								<? if ($mtype <> "CAMPUS") { ?><col width="10%" /><? } ?><!-- 아이콘 -->
								<col width="*" /><!-- 제목 -->
								<col width="45%" /><!-- 링크(URL) -->
								<col width="5%" /><!-- 공개여부 -->
								<col width="10%" /><!-- 등록일 -->

							</colgroup>
							<thead>
								<tr>
									<th><? if ($mtype == "TOP") { ?><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /><? } else { ?>번호<? } ?></th>
									<th>순서</th>
									<!--<th>구분</th>-->
									<? if ($mtype <> "CAMPUS") { ?><th>아이콘</th><? } ?>
									<th>제목</th>
									<th>링크(URL)</th>
									<th>공개여부</th>
									<th>등록(수정)일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

										$rn								= trim($arr_rs[$j]["rn"]);
										$Q_NO							= trim($arr_rs[$j]["Q_NO"]);
										$Q_TYPE						= trim($arr_rs[$j]["Q_TYPE"]);
										$Q_COLOR					= trim($arr_rs[$j]["Q_COLOR"]);
										$Q_TITLE					= trim($arr_rs[$j]["Q_TITLE"]);
										$Q_SUBTITLE				= trim($arr_rs[$j]["Q_SUBTITLE"]);
										$Q_DESCRIPTION		= trim($arr_rs[$j]["Q_DESCRIPTION"]);
										$Q_URL						= trim($arr_rs[$j]["Q_URL"]);

										$S_DATE						= trim($arr_rs[$j]["S_DATE"]);
										$S_HOUR						= trim($arr_rs[$j]["S_HOUR"]);
										$S_MIN						= trim($arr_rs[$j]["S_MIN"]);
										$E_DATE						= trim($arr_rs[$j]["E_DATE"]);
										$E_HOUR						= trim($arr_rs[$j]["E_HOUR"]);
										$E_MIN						= trim($arr_rs[$j]["E_MIN"]);
										$E_MIN						= trim($arr_rs[$j]["E_MIN"]);
										$DATE_USE_TF			= trim($arr_rs[$j]["DATE_USE_TF"]);

										$DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);
										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);

										if ($USE_TF == "Y") {
											$STR_USE_TF = "<font color='blue'>공개</font>";
										} else {
											$STR_USE_TF = "<font color='red'>비공개</font>";
										}

										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

										if ($Q_COLOR == "accent-01") $q_color = "#a71142f2";
										if ($Q_COLOR == "accent-05") $q_color = "#ff6666f2";
										if ($Q_COLOR == "accent-06") $q_color = "#ff9a2ef2";
										if ($Q_COLOR == "accent-07") $q_color = "#86bb00f2";
										if ($Q_COLOR == "accent-08") $q_color = "#13bd94f2";
										if ($Q_COLOR == "accent-09") $q_color = "#1db4cbf2";

										if ($Q_TYPE == "ALL") $q_type = "공통";
										if ($Q_TYPE == "SUSI") $q_type = "수시";
										if ($Q_TYPE == "JEONGSI") $q_type = "정시";
										if ($Q_TYPE == "SUNGIN") $q_type = "성인·재직자";
										if ($Q_TYPE == "PYEONIP") $q_type = "편입";
										if ($Q_TYPE == "JEOEGUK") $q_type = "재외국민";
										if ($Q_TYPE == "SCHOOL") $q_type = "고교대학연계";

										$offset = $nPageSize * ($nPage-1);
										$logical_num = ($nListCnt - $offset);
										$rn = $logical_num - $j;
							?>
									<tr>
										<td>
											<? if ($mtype == "TOP") { ?><input type="checkbox" name="chk[]" value="<?=$Q_NO?>"><? } else { ?><?=$j+1?><? } ?>
										</td>
										<td class="moveIcon">
										<? if ($USE_TF == "Y") { ?>
											<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/common/icon_arr_top.gif" alt="up" /></a>
											<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/common/icon_arr_bot.gif" alt="down" /></a>
										<? } ?>
										</td>
										<!--<td><a href="javascript:js_view('<?=$Q_NO?>');"><?=$Q_TYPE?></a></td>-->
							<?
										if ($mtype == "TOP") { 
							?>
										<td><a href="javascript:js_view('<?=$Q_NO?>');"><?=$q_type?></a>
							<?
										}
							?>
							<?
										if ($mtype == "MIDDLE") { 
							?>
										<td><a href="javascript:js_view('<?=$Q_NO?>');"><img src="/assets/images/icon/<?=$Q_SUBTITLE?>"></a>
							<?
										}
							?>
							<?
										if ($mtype == "BOTTOM") { 
							?>
										<td><a href="javascript:js_view('<?=$Q_NO?>');"><img src="/assets/images/icon/<?=$Q_SUBTITLE?>"></a>
							<?
										}
							?>
										<? if ($USE_TF == "Y") { ?>
										<input type="hidden" name="seq_q_no" value="<?=$Q_NO?>">
										<input type="hidden" name="quick_seq_no[]" value="<?=$Q_NO?>">
										<? } ?>
										</td>
										<td><a href="javascript:js_view('<?=$Q_NO?>');"><?=$Q_TITLE?></a></td>
										<td  style="text-align:left"><a href="<?=$Q_URL?>" target="_blank"><?=$Q_URL?></a></td>
										<td><a href="javascript:js_toggle('<?=$Q_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a></td>
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
							<? if ($mtype == "TOP") { ?>
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_add_quick();" >등록</button>
							<? } ?>
							<?	if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete();">삭제</button>
							<?  } ?>
							<?	if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_execl();">엑셀</button>-->
							<?  } ?>
							<?  } ?>
						</div>
					</div>


							<!--<button type="button" class="button type02">삭제</button>-->
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
								$strParam = $strParam."&mtype=".$mtype;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>
						</div>
						<div class="right">
							<? if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_excel_print();">선택한 항목 엑셀로 받기</button>-->
							<? } ?>
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
