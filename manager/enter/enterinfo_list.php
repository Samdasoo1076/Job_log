<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : enterinfo_list.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-11-19
# Modify Date  : 2021-05-07
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
	$menu_right = "EN001"; // 메뉴마다 셋팅 해 주어야 합니다

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

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$e_no						= isset($_POST["e_no"]) && $_POST["e_no"] !== '' ? $_POST["e_no"] : (isset($_GET["e_no"]) ? $_GET["e_no"] : '');
	$e_type					= isset($_POST["e_type"]) && $_POST["e_type"] !== '' ? $_POST["e_type"] : (isset($_GET["e_type"]) ? $_GET["e_type"] : '');
	$e_year					= isset($_POST["e_year"]) && $_POST["e_year"] !== '' ? $_POST["e_year"] : (isset($_GET["e_year"]) ? $_GET["e_year"] : '');
	$e_title				= isset($_POST["e_title"]) && $_POST["e_title"] !== '' ? $_POST["e_title"] : (isset($_GET["e_title"]) ? $_GET["e_title"] : '');
	$e_pdf					= isset($_POST["e_pdf"]) && $_POST["e_pdf"] !== '' ? $_POST["e_pdf"] : (isset($_GET["e_pdf"]) ? $_GET["e_pdf"] : '');
	$e_img					= isset($_POST["e_img"]) && $_POST["e_img"] !== '' ? $_POST["e_img"] : (isset($_GET["e_img"]) ? $_GET["e_img"] : '');
	$disp_seq				= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$apply_tf				= isset($_POST["apply_tf"]) && $_POST["apply_tf"] !== '' ? $_POST["apply_tf"] : (isset($_GET["apply_tf"]) ? $_GET["apply_tf"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date				= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$f							= isset($_POST["f"]) && $_POST["f"] !== '' ? $_POST["f"] : (isset($_GET["f"]) ? $_GET["f"] : '');
	$s							= isset($_POST["s"]) && $_POST["s"] !== '' ? $_POST["s"] : (isset($_GET["s"]) ? $_GET["s"] : '');

	$chk						= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	$con_sido				= isset($_POST["con_sido"]) && $_POST["con_sido"] !== '' ? $_POST["con_sido"] : (isset($_GET["con_sido"]) ? $_GET["con_sido"] : '');
	$con_sigungu		= isset($_POST["con_sigungu"]) && $_POST["con_sigungu"] !== '' ? $_POST["con_sigungu"] : (isset($_GET["con_sigungu"]) ? $_GET["con_sigungu"] : '');
	$con_type				= isset($_POST["con_type"]) && $_POST["con_type"] !== '' ? $_POST["con_type"] : (isset($_GET["con_type"]) ? $_GET["con_type"] : '');
	$con_confirm_tf	= isset($_POST["con_confirm_tf"]) && $_POST["con_confirm_tf"] !== '' ? $_POST["con_confirm_tf"] : (isset($_GET["con_confirm_tf"]) ? $_GET["con_confirm_tf"] : '');
	$con_year				= isset($_POST["con_year"]) && $_POST["con_year"] !== '' ? $_POST["con_year"] : (isset($_GET["con_year"]) ? $_GET["con_year"] : '');

	if ($mode == "T") {
		updateEnterInfoUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $e_no);
	}

	if ($mode == "TD") { //다운노출여부
		updateEnterInfoDownTF($conn, $apply_tf, $_SESSION['s_adm_no'], $e_no);
	}

	if ($mode == "O") {

		$row_cnt = is_null($enterinfo_seq_no) ? 0 : count($enterinfo_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_e_no = $enterinfo_seq_no[$k];

			$result = updateOrderEnter($conn, $k, $tmp_e_no);
		
		}
	}

	if ($mode == "D") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_no = $chk[$k];
			$result = deleteEnterInfo($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "입학자료 삭제 (".$tmp_idx.") ", "Delete");
		}
	}

	#List Parameter
	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	$nPage					= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$f							= SetStringToDB($f);
	$s							= SetStringToDB($s);
	$f							= trim($f);
	$s							= trim($s);
	
	$use_tf					= "";  //SetStringToDB($use_tf);
	$apply_tf				= ""; 
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

	$nListCnt =totalCntEnterInfo($conn, $e_no, $apply_tf, $use_tf, $del_tf, $f, $s);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listEnterInfo($conn, $e_no, $e_type, $e_year, $e_title, $e_pdf, $e_img, $apply_tf, $use_tf, $del_tf, $f, $s, $nPage, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "입학 자료 리스트 조회", "List");

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
		frm.action = "enterinfo_list.php";
		frm.submit();
	}
	
	function js_add_enterInfo() {
		document.location = "enterinfo_write.php";
	}

	function js_view(e_no) {
		var frm = document.frm;
		frm.e_no.value = e_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "enterinfo_write.php";
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

					var tempCode = document.frm.seq_e_no[preid-2].value;
				
					document.frm.seq_e_no[preid-2].value = document.frm.seq_e_no[preid-1].value;
					document.frm.seq_e_no[preid-1].value = tempCode;
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

		if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_e_no[preid] != null)) {

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
		
					var tempCode = document.frm.seq_e_no[preid-1].value;
					document.frm.seq_e_no[preid-1].value = document.frm.seq_e_no[preid].value;
					document.frm.seq_e_no[preid].value = tempCode;
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
		document.frm.action = "enterinfo_order_dml.php";
		document.frm.submit();

	}

	function js_toggle_down(e_no, apply_tf) {
		var frm = document.frm;

		bDelOK = confirm('신청노출 여부를 변경 하시겠습니까?');

		if (bDelOK==true) {

			if (apply_tf == "Y") {
				apply_tf = "N";
			} else {
				apply_tf = "Y";
			}
			frm.e_no.value = e_no;
			frm.apply_tf.value = apply_tf;
			frm.mode.value = "TD";
			frm.target = "";
			frm.action = "<?=$_SERVER['PHP_SELF']?>";

			frm.submit();
		}
	}

	function js_toggle(e_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('공개 여부를 변경 하시겠습니까?');

		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}
			frm.e_no.value = e_no;
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
			<input type="hidden" name="e_no" value="<?=$e_no?>">
			<input type="hidden" name="apply_tf" value="">
			<input type="hidden" name="use_tf" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">


					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<select name="f" id="search_field">
							<option value="E_TYPE" <? if ($f == "E_TYPE") echo "selected"; ?> >모집유형</option>
							<option value="E_YEAR" <? if ($f == "E_YEAR") echo "selected"; ?> >모집년도</option>
							</select>
							<input type="text" value="<?=$s?>" name="s"  id="search_str" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
						</div>
					</div>

					<div class="tbl_style01">
						<table id="t">
							<colgroup>

								<col width="5%" />
								<col width="5%" />
								<col width="6%" /><!-- 모집종류 -->
								<col width="5%" /><!-- 모집년도  -->
								<col width="23%" /><!-- 제목  -->
								<col width="20%" /><!-- PDF 파일 -->
								<col width="20%" /><!-- IMG 파일 -->
								<col width="8%" /><!-- 공개여부 -->
								<col width="8%" /><!-- 공개여부 -->
								<col width="10%" /><!-- 등록일 -->

							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
									<th>순서</th>
									<th>구분</th>
									<th>모집년도</th>
									<th>제목</th>
									<th>PDF 파일</th>
									<th>IMG 파일</th>
									<th>신청노출여부</th>
									<th>열람공개여부</th>
									<th>등록일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

										$rn								= trim($arr_rs[$j]["rn"]);
										$E_NO							= trim($arr_rs[$j]["E_NO"]);
										$E_TYPE						= trim($arr_rs[$j]["E_TYPE"]);
										$E_YEAR						= trim($arr_rs[$j]["E_YEAR"]);	
										$E_TITLE					= trim($arr_rs[$j]["E_TITLE"]);	
										$E_PDF						= trim($arr_rs[$j]["E_PDF"]);
										$E_IMG						= trim($arr_rs[$j]["E_IMG"]);
										$E_PDF_NM					= trim($arr_rs[$j]["E_PDF_NM"]);
										$E_IMG_NM					= trim($arr_rs[$j]["E_IMG_NM"]);

										$DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);
										$APPLY_TF					= trim($arr_rs[$j]["APPLY_TF"]);
										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);

										if ($APPLY_TF == "Y") {
											$STR_APPLY_TF = "<font color='blue'>신청가능</font>";
										} else {
											$STR_APPLY_TF = "<font color='red'>신청불가</font>";
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
										<td><input type="checkbox" name="chk[]" value="<?=$E_NO?>"></td>
										<td class="moveIcon">
										<? if ($USE_TF == "Y") { ?>
											<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/common/icon_arr_top.gif" alt="up" /></a>
											<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/common/icon_arr_bot.gif" alt="down" /></a>
										<? } ?>
										</td>
										<td><a href="javascript:js_view('<?=$E_NO?>');"><?=getDcodeName($conn, "MOJIB", $E_TYPE)?></a></td>
										<td><a href="javascript:js_view('<?=$E_NO?>');"><?=$E_YEAR?></a></td>
										<td style="text-align:left"><a href="javascript:js_view('<?=$E_NO?>');"><?=$E_TITLE?></a>
										<? if ($USE_TF == "Y") { ?>
											<input type="hidden" name="seq_e_no" value="<?=$E_NO?>">
											<input type="hidden" name="enterinfo_seq_no[]" value="<?=$E_NO?>">
										<? } ?>
										</td>
										<td style="text-align:left"><a href="../../_common/new_download_file.php?menu=enterinfo&e_no=<?=$E_NO?>&field=e_pdf" target="_blank"><?=$E_PDF_NM?></a></td>
										<td style="text-align:left"><a href="../../_common/new_download_file.php?menu=enterinfo&e_no=<?=$E_NO?>&field=e_img" target="_blank"><?=$E_IMG_NM?></a></td>
										<td><a href="javascript:js_toggle_down('<?=$E_NO?>','<?=$APPLY_TF?>');"><?=$STR_APPLY_TF?></a></td>
										<td><a href="javascript:js_toggle('<?=$E_NO?>','<?=$USE_TF?>');"><?=$STR_USE_TF?></a></td>
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
							<button type="button" class="button" onClick="js_add_enterInfo();" >등록</button>						
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
								#$f		= trim($f);
								#$s			= trim($s);
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
<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
