<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : enterinfoapply_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-24
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
	$menu_right = "EN002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/enter/enter.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ea_no						= $_POST['ea_no']!=''?$_POST['ea_no']:$_GET['ea_no'];
	$apply_tf					= $_POST['apply_tf']!=''?$_POST['apply_tf']:$_GET['apply_tf'];
	$apply_all_txt				= $_POST['apply_all_txt']!=''?$_POST['apply_all_txt']:$_GET['apply_all_txt'];
	$f							= $_POST['f']!=''?$_POST['f']:$_GET['f'];
	$s							= $_POST['s']!=''?$_POST['s']:$_GET['s'];

/*
	$e_type						= $_POST['e_type']!=''?$_POST['e_type']:$_GET['e_type'];
	$e_year						= $_POST['e_year']!=''?$_POST['e_year']:$_GET['e_year'];
	$e_title					= $_POST['e_title']!=''?$_POST['e_title']:$_GET['e_title'];
	$e_pdf						= $_POST['e_pdf']!=''?$_POST['e_pdf']:$_GET['e_pdf'];
	$e_img						= $_POST['e_img']!=''?$_POST['e_img']:$_GET['e_img'];

	$disp_seq					= $_POST['disp_seq']!=''?$_POST['disp_seq']:$_GET['disp_seq'];

	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$reg_date					= $_POST['reg_date']!=''?$_POST['reg_date']:$_GET['reg_date'];
*/

	$chk							= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

	if ($mode == "T") {
		updateEnterInfoApplyProcessTF($conn, $use_tf, $_SESSION['s_adm_no'], $ea_no);
	}

	if ($mode == "TA") { //신청 일괄처리여부
		updateEnterInfoApplyProcessTFAll($conn, $_SESSION['s_adm_no'], $chk);
	}


	if ($mode == "D") {

		$row_cnt = is_null($chk) ? 0 : count($chk);

		for ($k = 0; $k < $row_cnt; $k++) {

			$tmp_no = $chk[$k];
	
			$result = deleteEnterInfoApply($conn, $_SESSION['s_adm_no'], $tmp_no );
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "입시자료 신청 리스트 삭제 (".$tmp_no.") ", "Delete");
		}
	}

	#List Parameter
	$nPage				= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);
	
	$use_tf				= "";  //SetStringToDB($use_tf);
	$apply_tf			= "";
	$del_tf				= "N";
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

	$nListCnt =totalCntEnterInfoApply($conn, $ea_no, $apply_tf, $use_tf, $del_tf, $f, $s);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listEnterInfoApply($conn, $ea_no, $ea_name, $ea_who, $ea_who_year, $ea_post, $ea_addr, $ea_addr_detail, $ea_phone, $ea_pwd, $agree_tf, $apply_tf, $use_tf, $del_tf, $f, $s, $nPage, $nRowCount);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "입시 자료 신청 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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
		frm.action = "enterinfoapply_list.php";
		frm.submit();
	}
	
	function js_add_enterInfo() {
		//document.location = "enterinfoapply_write.php";
	}

	function js_view(ea_no) {
		var frm = document.frm;
		frm.ea_no.value = ea_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "enterinfoapply_write.php";
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

function js_toggle_apply(ea_no, apply_tf) {
	var frm = document.frm;

	bDelOK = confirm('신청처리 여부를 변경 하시겠습니까?');

	if (bDelOK==true) {

		if (apply_tf == "") {
			apply_tf = "Y";
		} else {
			apply_tf = "";
		}
		frm.ea_no.value = ea_no;
		frm.apply_tf.value = apply_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
}

function js_toggle_apply_all() {

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

		apply_all_OK = confirm('처리여부를 일괄 변경하시겠습니까?');

		if (apply_all_OK == true){

			frm.mode.value = "TA";
			frm.target = "";
			frm.action = "<?=$_SERVER['PHP_SELF']?>";
			frm.submit();

		}
	}
}

function js_toggle(ea_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');

	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}
		frm.ea_no.value = ea_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";

		frm.submit();
	}
}

	function js_excel_print() {
		var frm = document.frm;
		frm.method = "post";
		frm.target = "";
		frm.action = "enterinfoapply_excel_list_test.php";
		frm.submit();
	}


	function js_view_dcode(seq) {
		var url = "/manager/syscode/dcode_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '560', '650', 'NO');
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
			<input type="hidden" name="ean_no" value="<?=$ean_no?>">
			<input type="hidden" name="apply_tf" value="">
			<input type="hidden" name="apply_all_txt" value="">
			<input type="hidden" name="use_tf" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">


					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<select name="f" id="f">
							<option value="EA_NAME" <? if ($f== "E_NAME") echo "selected"; ?> >신청자성명</option>
							</select>
							<input type="text" value="<?=$s?>" name="s"  id="s" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
							<button type="button" class="button" onClick="js_view_dcode('42');">신청자료 관리</button>
						</div>
					</div>

					<div class="tbl_style01">
						<table>
							<colgroup>

								<col width="3%" />
								<col width="5%" /><!-- 성명 -->
								<col width="6%" /><!-- 대상  -->
								<col width="10%" /><!-- 전화번호 -->
								<col width="16%" /><!-- 학교  -->
								<col width="6%" /><!-- 우편번호  -->
								<col width="22%" /><!-- 주소  -->
								<col width="19%" /><!-- 신청자료 -->
								<col width="6%" /><!-- 신청처리여부 -->
								<col width="8%" /><!-- 등록일 -->

							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
									<th>성명</th>
									<th>대상</th>
									<th>전화번호</th>
									<th>학교명</th>
									<th>우편번호</th>
									<th>주소</th>
									<th>신청자료</th>
									<th>처리여부</th>
									<th>등록일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

										$rn								= trim($arr_rs[$j]["rn"]);
										$EA_NO						= trim($arr_rs[$j]["EA_NO"]);
										$EA_NAME					= trim($arr_rs[$j]["EA_NAME"]);
										$EA_WHO						= trim($arr_rs[$j]["EA_WHO"]);
										$EA_WHO_ETC				= trim($arr_rs[$j]["EA_WHO_ETC"]);
										$EA_WHO_YEAR			= trim($arr_rs[$j]["EA_WHO_YEAR"]);	
										$EA_POST					= trim($arr_rs[$j]["EA_POST"]);
										$EA_SC_NM					= trim($arr_rs[$j]["EA_SC_NM"]);
										$EA_SC_AREA				= trim($arr_rs[$j]["EA_SC_AREA"]);
										$EA_ADDR					= trim($arr_rs[$j]["EA_ADDR"]);
										$EA_ADDR_DETAIL		= trim($arr_rs[$j]["EA_ADDR_DETAIL"]);
										$EA_PHONE					= trim($arr_rs[$j]["EA_PHONE"]);
										$E_TITLE					= trim($arr_rs[$j]["E_TITLE"]);
										$EA_NUM						= trim($arr_rs[$j]["EA_NUM"]);
										$EA_MEMO					= trim($arr_rs[$j]["EA_MEMO"]);
										$APPLY_TF					= trim($arr_rs[$j]["APPLY_TF"]);

										$DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);
										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);

										if ($APPLY_TF == "N") {
											$STR_APPLY_TF = "<font color='red'>접수중</font>";
										} else{
											$STR_APPLY_TF = "<font color='blue'>처리완료</font>";
										}

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
											<td><input type="checkbox" name="chk[]" value="<?=$EA_NO?>"></td>
											<td><?=$EA_NAME?></td>
											<td><?=$EA_WHO?> <? if ($EA_WHO_ETC <> "") echo "(".$EA_WHO_ETC.")" ?></td>
											<td><?=$EA_PHONE?></td>
											<td><?=$EA_SC_NM?> (<?=$EA_SC_AREA?>)</td>
											<td><?=$EA_POST?></td>
											<td><?=$EA_ADDR?> <?=$EA_ADDR_DETAIL?></td>
											<td><?=$EA_MEMO?></td>
											<td><a href="javascript:js_toggle_apply('<?=$EA_NO?>','<?=$APPLY_TF?>');"><?=$STR_APPLY_TF?></a></td>
											<td><?=$REG_DATE?></td>
										</tr>
							<?			
									}
								} 
								
								if (sizeof($arr_rs) <= 0) { 
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
							<!--button type="button" class="button" onClick="js_add_enterInfo();" >등록</button-->						
								<button type="button" class="button" onClick="js_excel_print();">자료 엑셀로 받기</button>
							<? } ?>

							<? if ($sPageRight_I == "Y") { ?>
								<button type="button" class="button" onClick="javascript:js_toggle_apply_all();">처리 상태 변경</button>
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
								$f		= trim($f);
								$s			= trim($s);
								$strParam = $strParam."&nPageSize=".$nPageSize."&f=".$f."&s=".$s."&order_field=".$order_field."&order_str=".$order_str;
								$strParam = $strParam."&con_sido=".$con_sido."&con_sigungu=".$con_sigungu."&con_type=".$con_type."&con_confirm_tf=".$con_confirm_tf;
								$strParam = $strParam."&con_year=".$con_year;
						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>
						</div>
					</div>
				</div>
</form>
			</div>
		</div>
	</div>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
