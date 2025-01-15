<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8");
ini_set('display_errors', 1);
error_reporting(E_ALL);
# =============================================================================
# File Name    : intro_list.php
# Modlue       : 
# Writer       : Lee Ji Min
# Create Date  : 2025-01-099
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
	$menu_right = "PO001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/popup/intro.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode					= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$intro_no				= isset($_POST["intro_no"]) && $_POST["intro_no"] !== '' ? $_POST["intro_no"] : (isset($_GET["intro_no"]) ? $_GET["intro_no"] : '');
	$intro_memo				= isset($_POST["intro_memo"]) && $_POST["intro_memo"] !== '' ? $_POST["intro_memo"] : (isset($_GET["intro_memo"]) ? $_GET["intro_memo"] : '');
	$intro_title			= isset($_POST["intro_title"]) && $_POST["intro_title"] !== '' ? $_POST["intro_title"] : (isset($_GET["intro_title"]) ? $_GET["intro_title"] : '');
	$intro_disp_seq			= isset($_POST["intro_disp_seq"]) && $_POST["intro_disp_seq"] !== '' ? $_POST["intro_disp_seq"] : (isset($_GET["intro_disp_seq"]) ? $_GET["intro_disp_seq"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date				= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$i_num					= isset($_POST["i_num"]) && $_POST["i_num"] !== '' ? $_POST["i_num"] : (isset($_GET["i_num"]) ? $_GET["i_num"] : '');
	$i_title				= isset($_POST["i_title"]) && $_POST["i_title"] !== '' ? $_POST["i_title"] : (isset($_GET["i_title"]) ? $_GET["i_title"] : '');
	$i_url					= isset($_POST["i_url"]) && $_POST["i_url"] !== '' ? $_POST["i_url"] : (isset($_GET["i_url"]) ? $_GET["i_url"] : '');
	$i_target				= isset($_POST["i_target"]) && $_POST["i_target"] !== '' ? $_POST["i_target"] : (isset($_GET["i_target"]) ? $_GET["i_target"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$chk					= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');
	if ($mode == "T") {
		$result = updateIntroUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $intro_no);
		$use_tf = "";
	}

	if ($mode == "D") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_no = $chk[$k];
			$result = deleteIntro($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result = deleteIntroDetail($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "인트로 삭제 (".$tmp_idx.") ", "Delete");
		}
	}


	#List Parameter
	$nPage				= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= "I_TITLE";
	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	
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

	$nListCnt =totalCntIntro($conn, $intro_no, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listIntro($conn, $intro_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);


	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "인트로 리스트 조회", "List");

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
		frm.action = "intro_list.php";
		frm.submit();
	}
	
	function js_add_intro() {
		document.location = "intro_write.php";
	}

	function js_view(intro_no) {
		var frm = document.frm;
		frm.intro_no.value = intro_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "intro_write.php";
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
		
		if (frm.all_chk_no.checked){
			for (i=0;i<check.length;i++) {
					check.item(i).checked = true;
			}
		} else {
			for (i=0;i<check.length;i++) {
					check.item(i).checked = false;
			}
		}
	}

	function js_toggle(intro_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('공개 여부를 변경 하시겠습니까?');

		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.intro_no.value = intro_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "<?=$_SERVER['PHP_SELF']?>";

			frm.submit();
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

				var tempCode = document.frm.seq_intro_no[preid-2].value;
			
				document.frm.seq_intro_no[preid-2].value = document.frm.seq_intro_no[preid-1].value;
				document.frm.seq_intro_no[preid-1].value = tempCode;
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

	if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_intro_no[preid] != null)) {

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
	
				var tempCode = document.frm.seq_intro_no[preid-1].value;
				document.frm.seq_intro_no[preid-1].value = document.frm.seq_intro_no[preid].value;
				document.frm.seq_intro_no[preid].value = tempCode;
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
	document.frm.action = "intro_order_dml.php";
	document.frm.submit();

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
			<input type="hidden" name="intro_no" value="">
			<input type="hidden" name="use_tf" value="">

					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
						</div>
					</div>

					<div class="tbl_style01">
						<table id="t">
							<colgroup>
								<col width="5%" />
								<col width="5%" /><!-- 순서  -->
								<col width="30%" /><!-- 팝업 이미지  -->
								<col width="10%" /><!-- 제목  -->
								<col width="20%" /><!-- 관리자메모  -->
								<col width="20%" /><!-- 게시기간  -->
								<col width="10%" /><!-- 공개여부 -->
								<col width="10%" /><!-- 등록일 -->
							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
									<th>순서</th>
									<th>팝업 이미지</th>
									<th>제목</th>
									<th>관리자메모</th>
									<th>게시기간</th>
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
										$INTRO_NO					= trim($arr_rs[$j]["INTRO_NO"]);
										$INTRO_TITLE			= trim($arr_rs[$j]["INTRO_TITLE"]);
										$FILE_NM			= trim($arr_rs[$j]["FILE_NM"]);
										$FILE_RNM			= trim($arr_rs[$j]["FILE_RNM"]);
										$INTRO_MEMO				= trim($arr_rs[$j]["INTRO_MEMO"]);	
										$INTRO_DISP_SEQ		= trim($arr_rs[$j]["INTRO_DISP_SEQ"]);
										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);
										$VIEW_TF					= trim($arr_rs[$j]["VIEW_TF"]);
										$START_DATE				= trim($arr_rs[$j]["START_DATE"]);
										$END_DATE					= trim($arr_rs[$j]["END_DATE"]);
										$DATE_USE_TF			= trim($arr_rs[$j]["DATE_USE_TF"]);

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
										<td><input type="checkbox" name="chk[]" value="<?=$INTRO_NO?>"></td>
										<td class="moveIcon">
										<? if ($USE_TF == "Y") { ?>
											<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/common/icon_arr_top.gif" alt="up" /></a>
											<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/common/icon_arr_bot.gif" alt="down" /></a>
										<? } ?>
										</td>
										
										<td>
											<a href="javascript:js_view('<?=$INTRO_NO?>');"><img src="/upload_data/popup/<?=$FILE_NM?>" width="200px"></a>
										</td>
                                        
										<td>
											<a href="javascript:js_view('<?=$INTRO_NO?>');"><?=$INTRO_TITLE?></a>
											<? if ($USE_TF == "Y") { ?>
											<input type="hidden" name="seq_intro_no" value="<?=$INTRO_NO?>">
											<input type="hidden" name="intro_seq_no[]" value="<?=$INTRO_NO?>">
											<? } ?>
										</td>
										<td>
											<a href="javascript:js_view('<?=$INTRO_NO?>');"><?=$INTRO_MEMO?> </a>
										</td>
										<td>
											<? if ($DATE_USE_TF == "Y") {?>
											<?	if ($VIEW_TF) {?>
											<?=$START_DATE?>~<?=$END_DATE?>
											<?	} else { ?>
											<font color="red"><?=$START_DATE?>~<?=$END_DATE?></font>
											<?	} ?>
											<? } ?>
										</td>
										<td><a href="javascript:js_toggle('<?=$INTRO_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a></td>
										<td><?=$REG_DATE?></td>
									</tr>

							<?			
									}
								} else { 
							?> 
								<tr>
									<td colspan="8">
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
						<div class="left">
							<!-- <button type="button" class="button" onClick="window.open('/main/index_preview.php')" >비공개 비주얼 공지 미리보기</button>&nbsp;&nbsp;&nbsp; -->
						</div>
						<div class="right">
							<!-- 버튼 자리 -->
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_add_intro();" >등록</button>
							<? } ?>
							<?	if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete();">삭제</button>
							<?  } ?>
							<?	if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_execl();">엑셀</button>-->
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