<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : board_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-12
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$b_code			= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$menu_right = $b_code; // 메뉴마다 셋팅 해 주어야 합니다

	//echo $menu_right;

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/board/_prepared_board.php";
	require "../../_classes/biz/admin/admin.php";
	
#==============================================================================
# Request Parameter
#==============================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$b_code							= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no								= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];

	$con_cate_01				= $_POST['con_cate_01']!=''?$_POST['con_cate_01']:$_GET['con_cate_01'];
	$con_cate_02				= $_POST['con_cate_02']!=''?$_POST['con_cate_02']:$_GET['con_cate_02'];
	$con_cate_03				= $_POST['con_cate_03']!=''?$_POST['con_cate_03']:$_GET['con_cate_03'];
	$con_cate_04				= $_POST['con_cate_04']!=''?$_POST['con_cate_04']:$_GET['con_cate_04'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$chk								= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

	$mode			= SetStringToDB($mode);

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	$b_code				= SetStringToDB($b_code);
	$b_no					= SetStringToDB($b_no);

	$bb_code = trim($bb_code);

	if ($b_code == "")
		$b_code = "B_1_1";

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	if ($mode == "T") {
		updateBoardUseTF($conn, $use_tf, $_SESSION['s_adm_id'], $b_code, $b_no);
	}

	if ($mode == "D") {

		// 삭제 권한 관련 입니다.
		$del_ok = "N";
		if ($_SESSION['s_adm_no'] && $arr_page_nm[1] == "manager") {
			if ($sPageRight_D == "Y") {
				$del_ok = "Y";
			}
		}
		
		if ($del_ok == "Y") {
			$row_cnt = is_null($chk) ? 0 : count($chk);
			for ($k = 0; $k < $row_cnt; $k++) {
				$tmp_b_no = (int)$chk[$k];
				$result= deleteBoard($conn, $_SESSION['s_adm_no'], $b_code, $tmp_b_no);
				$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "".$b_board_nm." 게시물삭제 (게시물 번호 : ".$tmp_b_no.") ", "Delete");
			}
		}

	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$con_cate_01	= SetStringToDB($con_cate_01);
	$con_cate_02	= SetStringToDB($con_cate_02);
	$con_cate_03	= SetStringToDB($con_cate_03);
	$keyword			= SetStringToDB($keyword);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$arr_rs_top = listBoardTop($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "".$b_board_nm." 게시물조회", "List");

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
<script language="javascript">

	function js_write() {

		var frm = document.frm;
		frm.target = "";
		frm.action = "board_write.php";
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

	function js_view(b_code, b_no, b_board_type) {

		var frm = document.frm;
		
		frm.b_code.value = b_code;
		frm.b_no.value = b_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "board_read.php";
		frm.submit();
		
	}

	function js_answer(b_code, b_no) {

		var frm = document.frm;
		
		frm.b_code.value = b_code;
		frm.b_no.value = b_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "board_answer.php";
		frm.submit();
		
	}


	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "board_list.php";
		frm.submit();
	}

	function js_reload() {
		var frm = document.frm;
		frm.method = "get";
		frm.target = "";
		frm.action = "board_list.php";
		frm.submit();
	}

	function js_execl() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "board_excel_list.php";
		frm.submit();
	}

function js_con_cate_01 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "board_list.php";
	frm.submit();
}

function js_con_cate_02 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "board_list.php";
	frm.submit();
}

function js_con_cate_03 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "board_list.php";
	frm.submit();
}

function js_disp_seq(b_no, disp_seq) {
	
	var bOK = confirm("전시 순위를 변경 하시겠습니까?");
	
	if (bOK) { 
		
		var mode = "change_disp_seq";
		var b_code = "<?=$b_code?>";

		var request = $.ajax({
			url:"/_common/board/ajax.board.php",
			type:"POST",
			data:{mode:mode, b_code:b_code, b_no:b_no, disp_seq:disp_seq},
			dataType:"json"
		});

		request.done(function(data) {
			if (data.result == "T") {
				alert(data.msg);
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

function js_view_dcode(seq) {
	var url = "/manager/syscode/dcode_list_popup.php?mode=R&pcode_no="+seq;
	NewWindow(url, '세부분류조회', '560', '650', 'NO');
}

function js_date_reset() {
	var frm = document.frm;
	frm.start_date.value = "";
	frm.end_date.value = "";
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

				var tempCode = document.frm.seq_faq_no[preid-2].value;
			
				document.frm.seq_faq_no[preid-2].value = document.frm.seq_faq_no[preid-1].value;
				document.frm.seq_faq_no[preid-1].value = tempCode;
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

	if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_faq_no[preid] != null)) {

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
	
				var tempCode = document.frm.seq_faq_no[preid-1].value;
				document.frm.seq_faq_no[preid-1].value = document.frm.seq_faq_no[preid].value;
				document.frm.seq_faq_no[preid].value = tempCode;
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
	document.frm.action = "border_faq_order_dml.php";
	document.frm.submit();

}

function js_toggle(b_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
	
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.b_no.value = b_no;
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

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="b_no" value="">
<input type="hidden" name="b_code" value="<?=$b_code?>">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<div class="cont">
					
					<? if ($b_board_type == "COUNSEL") { ?>

					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:14%" />
								<col style="width:36%" />
								<col style="width:14%" />
								<col style="width:36%" />
							</colgroup>
							<tbody>
								<tr>
									<th>등록일</th>
									<td>
										<div class="datepickerbox" style="width:150px">
											<input type="text" name="start_date" value="<?=$start_date?>" class="datepicker" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10" autocomplete="off"/>
										</div>
										<span class="tbl_txt"> ~ </span> 
										<div class="datepickerbox" style="width:150px">
											<input type="text" name="end_date" value="<?=$end_date?>" class="datepicker" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10" autocomplete="off"/>
										</div>
										<button type="button" class="button" onClick="js_date_reset();">초기화</button>
									</td>
									<th>답변여부</th>
									<td colspan="3">
										<span class="tbl_txt">
											<input type='radio' class="radio" name='con_reply_state' value='' <? if ($con_reply_state == "") echo " checked"; ?> > 전체 &nbsp;
											<input type='radio' class="radio" name='con_reply_state' value='Y' <? if ($con_reply_state == "Y") echo " checked"; ?> > 답변완료 &nbsp;
											<input type='radio' class="radio" name='con_reply_state' value='N' <? if (($con_reply_state == "N") || ($con_reply_state == "W")) echo " checked"; ?>> 접수
										</span>
									</td>
								<tr>
								</tr>
									<th>콜유무</th>
									<td>
										<span class="tbl_txt">
											<input type='radio' class="radio" name='con_call_tf' value='' <? if ($con_call_tf == "") echo " checked"; ?> > 전체 &nbsp;
											<input type='radio' class="radio" name='con_call_tf' value='Y' <? if ($con_call_tf == "Y") echo " checked"; ?> > Y &nbsp;
											<input type='radio' class="radio" name='con_call_tf' value='N' <? if ($con_call_tf == "N") echo " checked"; ?>> N
										</span>
									</td>
									<th>예약유무</th>
									<td>
										<span class="tbl_txt">
											<input type='radio' class="radio" name='con_reserve_tf' value='' <? if ($con_reserve_tf == "") echo " checked"; ?> > 전체 &nbsp;
											<input type='radio' class="radio" name='con_reserve_tf' value='Y' <? if ($con_reserve_tf == "Y") echo " checked"; ?> > Y &nbsp;
											<input type='radio' class="radio" name='con_reserve_tf' value='N' <? if ($con_reserve_tf == "N") echo " checked"; ?>> N
										</span>
									</td>
								</tr>
								<tr>
									<th>검색조건</th>
									<td colspan="3">
										<select name="search_field">
											<option value="ALL3" <? if ($search_field == "ALL3") echo "selected"; ?> >전체</option>
											<option value="TITLE" <? if ($search_field == "TITLE") echo "selected"; ?> >제목</option>
											<option value="CONTENTS" <? if ($search_field == "CONTENTS") echo "selected"; ?> >내용</option>
											<option value="WRITER_NM" <? if ($search_field == "WRITER_NM") echo "selected"; ?> >작성자</option>
											<option value="EMAIL" <? if ($search_field == "EMAIL") echo "selected"; ?> >이메일</option>
											<option value="CATE_04" <? if ($search_field == "CATE_04") echo "selected"; ?> >상담자</option>
											<option value="REPLY" <? if ($search_field == "REPLY") echo "selected"; ?> >답변</option>
										</select>
										<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" style="width:240px"/>
										<button type="button" class="button" onClick="js_search();">검색</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span></div>
						<div class="right">
							<!-- 버튼 자리 -->
						</div>
					</div>
					
					<?
						} else {
					?>
					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 개</span></div>
						<div class="right">

							<div class="fl_wrap">
								<? if ($b_board_type == "EVENT") { ?>
								<select name="con_use_tf" onChange="js_search();">
									<option value="" <? if ($con_use_tf == "") echo "selected"; ?> >노출여부 전체</option>
									<option value="Y" <? if ($con_use_tf == "Y") echo "selected"; ?> >보이기</option>
									<option value="N" <? if ($con_use_tf == "N") echo "selected"; ?> >보이지않기</option>
								</select>
								<select name="con_reply_state" onChange="js_search();">
									<option value="" <? if ($con_reply_state == "") echo "selected"; ?> >진행여부 전체</option>
									<option value="0" <? if ($con_reply_state == "0") echo "selected"; ?> >시작전</option>
									<option value="1" <? if ($con_reply_state == "1") echo "selected"; ?> >진행중</option>
									<option value="2" <? if ($con_reply_state == "2") echo "selected"; ?> >종료</option>
								</select>
								<? } ?>

								<? if ($b_board_cate) { ?>
								<?=makeBoardCodeSelectBox($conn, "con_cate_01", "전체", "", $b_board_cate, "style='width:200px'", $con_cate_01); ?>
								<? } ?>
								<select name="search_field" id="search_field">
									<option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >전체</option>
									<option value="title" <? if ($search_field == "title") echo "selected"; ?> >제목</option>
									<option value="contents" <? if ($search_field == "contents") echo "selected"; ?> >내용</option>
								</select>
								<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
								<button type="button" class="button" onClick="js_search();">검색</button>
							</div>
						</div>
					</div>
					<?
						}
					?>


					<div class="tbl_style01">
						<table id='t'>

				<? if ($b_board_type == "NOTICE") { ?>
<?
	require "board_list.board.php";
?>
				<? } else if ($b_board_type == "FAQ") { ?>
<?
	$arr_rs = listBoard_faq($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	require "board_list.faq.php";
?>
				<? } else if ($b_board_type == "RATE") { ?>
<?
	require "board_list.rate.php";
?>
				<? } else if ($b_board_type == "RESULT") { ?>
<?
	require "board_list.result.php";
?>
				<? } else if ($b_board_type == "COUNSEL") { ?>
<?
	require "board_list.counsel.php";
?>
				<? } else if ($b_board_type == "PDS") { ?>
<?
	require "board_list.pds.php";
?>
				<? } else { ?>
<?
	require "board_list.board.php";
?>
				<? }?>

						</table>
					</div>
					<div class="btn_wrap">
						<div class="left">
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
								$strParam = $strParam."&nPageSize=".$nPageSize."&start_date=".$start_date."&end_date=".$end_date;
								$strParam = $strParam."&con_cate_01=".$con_cate_01."&b_code=".$b_code."&con_use_tf=".$con_use_tf."&con_reply_state=".$con_reply_state;
								$strParam = $strParam."&con_call_tf=".$con_call_tf."&con_reserve_tf=".$con_reserve_tf;
								$strParam = $strParam."&search_field=".$search_field."&search_str=".$search_str;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>

						</div>
						<div class="right">

							<? if (($sPageRight_I == "Y") && ($b_board_type != "COUNSEL")) { ?>
							<button type="button" class="button" onClick="js_write();">등록</button>
							<? } ?>
							<?	if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete();">삭제</button>
							<?  } ?>
							<?	if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_execl();">엑셀</button>-->
							<?  } ?>

							<? if ($b_board_type == "FAQ") { ?>
							<!--<button type="button" class="button" onClick="js_view_dcode('39');">추천검색어 관리</button>-->
							<?  } ?>

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