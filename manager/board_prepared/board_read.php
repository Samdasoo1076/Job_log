<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : board_write.php
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
	$b_code = $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];

	$b_code = trim($b_code);

	//echo $bb_code;

	if ($b_code == "")
		$b_code = "B_1_1";

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = $b_code; // 메뉴마다 셋팅 해 주어야 합니다
	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/board/_prepared_board.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$b_code							= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no								= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];
	$b_board_type				= $_POST['b_board_type']!=''?$_POST['b_board_type']:$_GET['b_board_type'];
	$con_cate_01				= $_POST['con_cate_01']!=''?$_POST['con_cate_01']:$_GET['con_cate_01'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$reply							= $_POST['reply']!=''?$_POST['reply']:$_GET['reply'];
	$reply_state				= $_POST['reply_state']!=''?$_POST['reply_state']:$_GET['reply_state'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];


#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	$arr_recom_count = countRecom($conn, $b_code, $b_no);
	$arr_Nrecom_count = countNRecom($conn, $b_code, $b_no);
#====================================================================
# DML Process
#====================================================================

	if ($mode == "D") {
		require "../../_common/board/del.php";
	}

	if ($mode == "S") {

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/read.php";
#====================================================================
# Board Config End
#====================================================================

	} else {
		$rs_writer_nm = $_SESSION['s_adm_nm'];
		$rs_email			= $_SESSION['s_adm_email'];
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&mode=S&b_code=".$b_code."&b_no=".$b_no."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($mode == "AU") {

		$reply = SetStringToDB($reply);
		$result = updateQnaAnswer($conn, $reply, $_SESSION['s_adm_no'], $reply_state, $b_code, $b_no);

		if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_read.php<?=$strParam?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_list.php<?=$strParam?>";
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
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../../_common/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>
<style>

.replylist {clear:both; width:100%; overflow-x:hidden; overflow-y:auto; padding-right:10px}
.replylist::-webkit-scrollbar {width:5px}
.replylist::-webkit-scrollbar-track {-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3)} 
.replylist::-webkit-scrollbar-thumb {background-color: darkgrey; outline: 1px solid slategrey}
.replylist fieldset .pic {float:left; margin:0 20px 0 0}
.replylist fieldset .pic span {display:block; width:44px; height:44px; overflow:hidden; margin:0 auto; border-radius:50%}
.replylist fieldset .pic span img {width:100%; height:100%}
.replylist fieldset .pic strong {display:block; text-align:center; line-height:30px; font-size:14px; color:#000}
.replylist fieldset p {float:none; overflow:hidden; position:relative; height:70px; border:1px solid #2e323f}
.replylist fieldset p button {position:absolute; right:0; top:0; width:64px; height:70px; border-left:1px solid #eaeaea; color:#000}
.replylist fieldset p textarea {display:block; width:100%; height:70px; padding:15px; border:0}
.replylist ul {clear:both; overflow:hidden; padding-left:40px}
.replylist ul li {position:relative; padding:30px 0 30px 60px; border-top:1px solid #eaeaea}
.replylist ul li .btn-moreaction {position:absolute; right:0; top:30px; z-index:20; display:block; width:30px; height:30px}
.replylist ul li .btn-moreaction:hover em {display:block}
.replylist ul li .btn-moreaction button {width:30px; height:30px; background:url('../images/icon_moreaction.png') no-repeat 100% 30%; font-size:0; text-indent:-99999rem}
.replylist ul li .btn-moreaction em {display:none; position:absolute; right:0; top:20px; width:64px; height:64px; background:#fff; border:1px solid #dfdfdf}
.replylist ul li .btn-moreaction em a {display:block; text-align:center; color:#999; font-size:13px; line-height:30px}
.replylist ul li .btn-moreaction em a:hover {color:#010101}
.replylist ul li:first-child {border-top:0}
.replylist ul li p {margin-bottom:5px}
.replylist ul li span {display:block; color:#999}
.replylist ul li span.pic {display:block; position:absolute; top:30px; left:0; overflow:hidden; width:44px; height:44px; border-radius:50%}
.replylist ul li span.pic img {width:100%; height:100%}

</style>
<script type="text/javascript" src="../js/board.js"></script>
<script language="javascript" type="text/javascript">

	$(document).ready(function() {
	// 댓글 달기화면이 활성화 되어 있다면
<?	if ($b_comment_tf) { ?>
	//js_getList();
<?	} ?>
	});

	function js_getList() {

		var frm		= document.frm;
		var mode	= "L";
		var b			= frm.b.value;
		var bn		= frm.bn.value;

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, b:b, bn:bn}, 
			function(data){
				data = decodeURIComponent(data);
				$("#div_recomm_list").html(data); 
			}
		);
	}

	function js_comment_save() {

		var frm		= document.frm;
		var mode	= "I";
		var b			= frm.b.value;
		var bn		= frm.bn.value;
		var secret_tf = "";
		if ($("#secret_tf").is(":checked") == true) {
			secret_tf = "Y";
		}

		var contents		= $("#contents").val();

		if (bn == "") {
			return;
		}
		
		var writer_nm = "";
		var writer_pw = "";

		if (contents.trim() == "") {
			alert("내용를 입력해 주십시오.");
			$("#contents").focus();
			return;
		}

		writer_nm = encodeURIComponent(writer_nm);
		writer_pw = encodeURIComponent(writer_pw);
		contents	= encodeURIComponent(contents);
		
		var request = $.ajax({
			url: "<?=$g_base_dir?>/_common/board/ajax.comment.php",
			type: "POST",
			data: {mode: mode, b:b, bn:bn, writer_nm:writer_nm, writer_pw:writer_pw, secret_tf:secret_tf, contents:contents},
			dataType: "html"
		});

		request.done(function(msg) {
			msg = decodeURIComponent(msg);
			if (msg != "") {
				alert(msg);
			} else {
				$("#writer_nm").val("");
				$("#writer_pw").val("");
				document.frm_comment.secret_tf.checked = false;
				$("#contents").val("");
				js_getList();
			}

			return false;
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed:" + textStatus);
			return false;
		});

	}

	function js_comment_delete(cno) {
		var frm = document.frm;
		bDelOK = confirm('댓글을 삭제 하시겠습니까?');

		if (bDelOK == true) {
	
			var mode	= "D";
			var b			= frm.b.value;
			var bn		= frm.bn.value;
			var cno		= cno;

			$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
				{ mode:mode, b:b, bn:bn, cno:cno}, 
				function(msg){
					if (msg != "") {
						alert(msg);
					} else {
						js_getList();
					}
				}
			);
		}
	}

	var active_obj = "";
	var active_act = "";

	function js_comment_reply(cno) {

		var obj = "#reply_"+cno;
		var con_obj		= "#contents_"+cno;
		var secret_obj	= "#secret_tf_"+cno;
		var mode	= "R";
		var frm_omment = eval("document.frm_comment_"+cno);

		$(con_obj).val("");
		frm_omment.secret_tf.checked = false;
		frm_omment.mode.value = "reply";

		if ($(obj).css("display") == "none") {
			if (active_obj) {
				$(active_obj).hide();
			}
			$(obj).show();
			active_obj = obj;
			active_act = mode;
			$("#write_comment").hide();
		} else {
			if (active_act == mode) {
				$(obj).hide();
				$("#write_comment").show();
			} else {
				active_act = mode;
			}
		}		//write_comment
	}

	function js_comment_modify(cno) {
		var obj = "#reply_"+cno;
		var con_obj		= "#contents_"+cno;
		var secret_obj	= "#secret_tf_"+cno;
		var mode	= "S";

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, cno:cno}, 
			function(msg){
				if (msg == "작성자만 수정 가능합니다.") {
					alert(msg);
				} else {
					$(obj).html(msg);
				}
			}
		);
		
		if ($(obj).css("display") == "none") {

			if (active_obj) {
				$(active_obj).hide();
			}
			$(obj).show();
			active_obj = obj;
			active_act = mode;
			$("#write_comment").hide();

		} else {
			if (active_act == mode) {
				$(obj).hide();
				$("#write_comment").show();
			} else {
				active_act = mode;
			}
		}
	}
	function js_comment_reply_save(cno) {
		
		var frm = document.frm;
		var frm_omment = eval("document.frm_comment_"+cno);
		
		if (frm_omment.mode.value == "reply") {
			var mode	= "IR";
		} else {
			var mode	= "U";
		}
		var b			= frm.b.value;
		var bn		= frm.bn.value;
		var secret_tf = "";
		
		if (frm_omment.secret_tf.checked == true) {
			secret_tf = "Y";
		}

		var contents		= frm_omment.contents.value;

		if (bn == "") {
			return;
		}
		
		var writer_nm = "";
		var writer_pw = "";

		if (contents.trim() == "") {
			alert("내용를 입력해 주십시오.");
			frm_omment.contents.focus();
			return;
		}

		writer_nm = encodeURIComponent(writer_nm);
		writer_pw = encodeURIComponent(writer_pw);
		contents	= encodeURIComponent(contents);

		var request = $.ajax({
			url: "<?=$g_base_dir?>/_common/board/ajax.comment.php",
			type: "POST",
			data: {mode: mode, b:b, bn:bn, writer_nm:writer_nm, writer_pw:writer_pw, secret_tf:secret_tf, contents:contents, cno:cno},
			dataType: "html"
		});
		

		request.done(function(msg) {
			msg = decodeURIComponent(msg);
			if (msg != "") {
				alert(msg);
			} else {
				frm_omment.secret_tf.checked = false;
				frm_omment.contents.value = "";
				js_getList();
				$("#write_comment").show();
			}
			return false;
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed:" + textStatus);
			return false;
		});

	}


function js_list() {
	document.location = "board_list.php<?=$strParam?>";
}

function js_reply() {
	var frm = document.frm;
	frm.mode.value = "R";
	frm.target = "";
	frm.method = "get";
	frm.action = "board_write.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";
	
	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(b_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.b_no.value = frm.b_no.value;
	}

<? if ($mode == "R") {?>
		frm.mode.value = "IR";
<? }?>

	frm.method = "post";
	frm.target = "";
	//frm.action = "<?//=$_SERVER["PHP_SELF"]?>";
	frm.submit();

}

function js_answer(state) {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";
	frm.reply.value=frm.reply.value.trim();

	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.
	frm.reply_state.value = state;

	if(frm.reply.value!=""){
		frm.reply_state.value = state;
	}else{
		alert('답변내용이 없습니다.');
		return;
	}

	if (isNull(b_no)) {
		return;
	} else {
		frm.mode.value = "AU";
		frm.b_no.value = b_no;
	}


	frm.method = "post";
	frm.target = "";
	frm.action = "board_read.php";
	//alert(frm.reply.value);
	frm.submit();

}

function js_view() {
	var frm = document.frm;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "post";

	frm.action = "board_write.php";
	frm.submit();
		
}

function file_change(file) {  
	document.getElementById("file_nm").value = file; 
}


function js_delete() {

	var frm = document.frm;

		bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER["PHP_SELF"]?>";
			frm.submit();
		}

//	}
}

function js_move() {
	var frm = document.frm;

	NewWindow('about:blank', 'pop_move_board', '390', '240', 'NO');
	frm.target = "pop_move_board";
	frm.action = "pop_move_board.php";
	frm.submit();
}

function js_copy() {
	var frm = document.frm;

	NewWindow('about:blank', 'pop_copy_board', '390', '240', 'NO');
	frm.target = "pop_copy_board";
	frm.action = "pop_copy_board.php";
	frm.submit();
}


</script>

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

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col width="12%" />
								<col width="38%" />
								<col width="12%" />
								<col width="38%" />
							</colgroup>
							<tbody>

								<? if ($rs_cate_01) {?>
								<tr>
									<? if ($b_board_type == "NOTICE" || ($b_board_type == "FAQ")) { ?>
									<th scope="row" style="width:12%">공지 여부</th>
									<td style="width:38%">
									<? if ($rs_top_tf == "Y" ) { echo "공지"; } else {echo "공지안함"; } ?>
									</td>
									<th scope="row" style="width:12%">카테고리</th>
									<td style="width:38%">
									<?
										if ($b_code == "B_1_7") {
											$str_pcode_name = "HIGH_PROGRAM";
										} else {
											$str_pcode_name = "MOJIB_TYPE";
										}
									?>

									[<?=str_replace("^"," & ",getDcodeName($conn,$str_pcode_name, $rs_cate_01))?>]
									</td>
									<? } else { ?>
									<th scope="row" style="width:12%">카테고리</th>
									<td colspan="3" style="width:38%">
									[<?=str_replace("^"," & ",getDcodeName($conn,$str_pcode_name, $rs_cate_01))?>]
									</td>

									<? } ?>
								</tr>
								<? } else { ?>
								<tr>
									<th scope="row" style="width:12%">공지 여부</th>
									<td olspan="3" style="width:38%">
									<? if ($rs_top_tf == "Y" ) { echo "공지"; } else {echo "공지안함"; } ?>
									</td>
									<th scope="row" style="width:12%">게시판</th>
									<td style="width:38%">
									<?=$b_board_type?>
									</td>
								</tr>
								<? } ?>

								<? if ($b_code == "B_1_10") { ?>
								<tr>
									<th>기출문제/서식</th>
									<td colspan="3">
										<? if ($rs_cate_02 == "Y" ) echo "기출문제/서식";?>
									</td>
								</tr>
								<? } ?>

								<tr>
									<th scope="row">제목</th>
									<td <? if (($b_code <> "B_1_1") && ($b_code <> "B_1_2") && ($b_code <> "B_1_8")) { ?> colspan="3"<? } ?>>
										<?
												if ($rs_secret_tf == "Y") 
													$str_lock = "<img src='../images/bu/ic_lock.jpg' alt='' />";
												else 
													$str_lock = "";
										?>
										<?=$rs_title?> <?=$str_lock?>
									</td>
									<? if (($b_code == "B_1_1") || ($b_code == "B_1_2") || ($b_code == "B_1_8")) { ?>
									<th scope="row">링크</th>
									<td>
										<?
											switch ($b_code) {
												case "B_1_1" : $b_folder = "notice"; break;
												case "B_1_2" : $b_folder = "data"; break;
												case "B_1_8" : $b_folder = "pasttest"; break;
											}

											$copy_url = "//".$_SERVER['HTTP_HOST']."/".$b_folder."/view.php?bn=".$b_no."&m_type=".$rs_cate_01;
											$go_url = "/".$b_folder."/view.php?bn=".$b_no."&m_type=".$rs_cate_01;
										?>
										<button type="button" class="button" onClick="board_copy_url('<?=$copy_url?>');">링크복사</button>
										<button type="button" class="button" onClick="window.open('<?=$go_url?>')">미리보기</button>
									</td>
									<? } ?>
								</tr>

								<tr>
									<th scope="row">작성자</th>
									<td><?=$rs_writer_nm?> <? if ($rs_writer_id) { echo "[".$rs_writer_id."]"; } else { echo "[비회원]"; }?>&nbsp;&nbsp;[<?=$rs_ref_ip?>]</td>
									<th scope="row">이메일</th>
									<td><?=$rs_email?></td>
								</tr>

								<tr>
									<th scope="row">게시 기간</th>
									<td colspan="3"><? if ($rs_date_use_tf == "Y") { ?><?=$rs_start_date?> ~ <?=$rs_end_date?><? } else { ?>무제한 노출<? } ?></td>
								</tr>

								<?
									if ($b_file_tf == "Y") {

										# ==========================================================================
										# Result List
										# ==========================================================================
										
										#Cnt = 0
										$f_Cnt = 0;
								?>
								<tr>
									<th scope="row">첨부파일</th>
									<td colspan="3">
								<?
										if (sizeof($arr_rs_files) > 0) {
											for ($j = 0 ; $j < sizeof($arr_rs_files); $j++) {
										
												//FILE_NO, B_CODE, B_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT 
												$RS_FILE_NO			= trim($arr_rs_files[$j]["FILE_NO"]);
												$RS_B_CODE			= trim($arr_rs_files[$j]["B_CODE"]);
												$RS_B_NO				= trim($arr_rs_files[$j]["B_NO"]);
												$RS_FILE_NM			= trim($arr_rs_files[$j]["FILE_NM"]);
												$RS_FILE_RNM		= trim($arr_rs_files[$j]["FILE_RNM"]);
												$RS_FILE_PATH		= trim($arr_rs_files[$j]["FILE_PATH"]);
												$RS_FILE_SIZE		= trim($arr_rs_files[$j]["FILE_SIZE"]);
												$RS_FILE_EXT		= trim($arr_rs_files[$j]["FILE_EXT"]);
												$RS_HIT_CNT			= trim($arr_rs_files[$j]["HIT_CNT"]);

												if ($RS_FILE_NM <> "") {
								?>
										<a href="../../_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>&nbsp;&nbsp<br>
								<?
												}
												$f_Cnt = $f_Cnt + 1;
											}
										} else {
								?>
									첨부파일이 없습니다.
								<?
										}
								?>
									</td>
								</tr>
								<?
									}
								?>
								
								<?if($rs_link01){?>
								<tr> 
									<th scope="row">링크01</th>
									<td colspan="3">
										<a href="<?=$rs_link01?>" target="_blank"><?=$rs_link01?></a>
									</td>
								</tr>
								<?}?>

								<?if($rs_link02){?>
								<tr> 
									<th scope="row">링크02</th>
									<td colspan="3">
										<a href="<?=$rs_link02?>" target="_blank"><?=$rs_link02?></a>
									</td>
								</tr>
								<?}?>

								<? if ($b_board_type == "RATE") { ?>
								<tr> 
									<th scope="row">경쟁률 URL</th>
									<td colspan="3">
										<?if($rs_homepage){?>
										<a href="<?=$rs_homepage?>" target="_blank"><?=$rs_homepage?></a>
										<?}?>
									</td>
								</tr>
								<tr> 
									<th scope="row">PDF 파일</th>
									<td colspan="3">
										<?if($rs_file_rnm){?>
										<a href="/_common/new_download_file.php?menu=board&b_code=<?= $rs_b_code ?>&b_no=<?= $rs_b_no ?>&field=file_nm" ><?=$rs_file_rnm?></a>
										<?}?>
									</td>
								</tr>

								<? } else if ($b_board_type == "RESULT") { ?>
								<tr> 
									<th scope="row">PDF 파일</th>
									<td colspan="3">
										<?if($rs_file_rnm){?>
										<a href="/_common/new_download_file.php?menu=board&b_code=<?= $rs_b_code ?>&b_no=<?= $rs_b_no ?>&field=file_nm" ><?=$rs_file_rnm?></a>
										<?}?>
									</td>
								</tr>

								<? } else if ($b_board_type == "MOVIE") { ?>

								<tr>
									<th scope="row">썸네일</th>
									<td colspan="3">
									<?
										if (strlen($rs_file_nm) > 3) {
									?>
										<img src="/upload_data/board/<?=$rs_b_code?>/<?=$rs_file_nm?>" width="310" >
									<?
										} else if (strlen($rs_thumb_img) > 3) {
									?>
										<img src="<?=$rs_thumb_img?>" >
									<?
										}
									?>
									</td>
								</tr>

								<?} else { ?>

								<tr class="conTxt"> 
									<th scope="row">내용</th>
									<td colspan="3" id="contents_td" >

								<?	
									
									if ($b_html_tf == "Y") { 

											$rs_contents = str_replace("&quot;","\"", $rs_contents);
											$rs_contents = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $rs_contents);
								?>
											<?=$rs_contents?>
								<? } else { ?>
											<?=stripslashes(nl2br($rs_contents))?>
								<? }  ?>
									</td>
								</tr>
								<? } ?>
	
								<tr style="display:none"> 
									<th scope="row">키워드</th>
									<td colspan="3">
										<?=$rs_keyword?>
									</td>
								</tr>

								<tr> 
									<th scope="row">노출여부</th>
									<td	colspan="3">
									<? if ($rs_use_tf =="Y") echo "보이기"; ?>
									<? if (($rs_use_tf !="Y") || ($rs_use_tf =="")) echo "보이지않기"; ?>
									</td>
								</tr>

								<!--
								<tr> 
									<th scope="row">댓글사용여부</th>
									<td	colspan="3">
									<? if ($rs_comment_tf =="Y") echo "사용"; ?>
									<? if ($rs_comment_tf !="Y") echo "사용안함"; ?>
									</td>
								</tr>
								-->
								<tr> 
									<th scope="row">등록일</th>
									<td	colspan="3">
										<?=$rs_reg_date?>
									</td>
								</tr>

								<tr> 
									<th scope="row">열람확인</th>
									<td	colspan="3">
										<?=getAdminReadList($conn, $b_code, $b_no);?>
									</td>
								</tr>


							</tbody>
						</table>

					</div>
					<div class="btn_wrap">

						<a href="javascript:js_list();" class="button type02">목록</a>

					<? if (($sPageRight_U == "Y" && $b_no <> "") || ($_SESSION['s_adm_no'] == $rs_reg_adm)) { ?>
						<button type="button" class="button" onClick="js_view();">수정</button>
						<a href="javascript:js_move();" class="button">게시물 이동</a>
					<? } ?>

					<? if (($sPageRight_D == "Y" && $b_no <> "") || ($_SESSION['s_adm_no'] == $rs_reg_adm)) { ?>
						<button type="button" class="button type02" onClick="js_delete();">삭제</button>
					<? } ?>

					</div>
				</div>

			<form id="replyFrm" name="frm" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="b_no" value="<?=$b_no?>" />
			<input type="hidden" name="b_code" value="<?=$b_code?>" />
			<input type="hidden" name="nPage" value="<?=$nPage?>" />
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
			<input type="hidden" name="con_cate_01" value="<?=$con_cate_01?>" />

			<? if (($b_board_type == "Q&A") || ($b_board_type == "FAQAA")) { //Q&A 답변 start ?>

			<input type="hidden" name="b_po" value="<?=$rs_b_po?>">
			<input type="hidden" name="b_re" value="<?=$rs_b_re?>">

			<input type="hidden" name="reply_state" value="">
			<input type="hidden" name="reply_mailtoname" value="<?=$rs_writer_nm?>">
			<input type="hidden" name="reply_mailto" value="<?=$rs_email?>">
			<input type="hidden" name="reply_title" value="<?=$rs_title?>">

			<input type="hidden" name="writer_nm" value="<?=$rs_writer_nm?>" />
			<input type="hidden" name="writer_pw" value="<?=$rs_writer_pw?>" />
			<input type="hidden" name="email" value="" />
			<input type="hidden" name="homepage" value="" />

			<input type="hidden" name="b" value="<?=$b_code?>" />
			<input type="hidden" name="bn" value="<?=$b_no?>" />

			<input type="hidden" name="search_field" value="<?=$search_field?>" />
			<input type="hidden" name="search_str" value="<?=$search_str?>" />

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col width="12%" />
								<col width="38%" />
								<col width="12%" />
								<col width="38%" />
						</colgroup>
						<tbody>
							<tr> 
								<th><?=$b_board_type?> 답변</th>
								<td colspan="3" style="padding: 10px 10px 10px 15px">
								<span class="fl" style="padding-left:0px;width:940px;height:400px;">
								<textarea name="reply" id="reply" style="padding-left: 0px; width: 930px; height: 350px; display: none;"><?=$rs_reply?></textarea>
								</span>
								</td>
								</tr>
						</tbody>
					</table>
					<div class="btn_wrap">
						<button type="button" class="button type02" onClick="js_answer('W');">임시저장</button>
						<button type="button" class="button type01" onClick="js_answer('Y');">확인</button>
					</div>
				</div>
					<? } ?>
			</form>


			<? if ($b_comment_tf == "Y" && $rs_b_no <> "") { ?>
			<div style="height:20px;"></div>	
			<div class="replylist">
				<form name="frm_comment" method="get">
					<input type="hidden" name="secret_tf" id="secret_tf" value="N"/>
					<input type="hidden" name="encrypt_str" id="encrypt_str" value="<?=$temp_str?>">
				<fieldset>
					<legend>댓글 등록</legend>
					<div class="pic"><strong><?=$_SESSION['s_adm_nm']?></strong></div>
					<span></span>
					<p><textarea cols="" rows="" placeholder="댓글을 입력해주세요." name="contents" id="contents"></textarea><button type="button" onClick="js_comment_save();">등록</button></p>
				</fieldset>
				</form>
	
	<ul id="div_recomm_list">
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
			<span class="btn-moreaction"><button type="button">수정/삭제</button><em><a href="#">수정</a><a href="#">삭제</a></em></span>
		</li>
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
		</li>
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
			<span class="btn-moreaction"><button type="button">수정/삭제</button><em><a href="#">수정</a><a href="#">삭제</a></em></span>
		</li>
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
		</li>
	</ul>
</div>


			<? } //Q&A 답변 end!?>

			</div>
		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>

<script type="text/javascript">
	window.onload=function() {

		resizeBoardImage('680');
		//drawFont();
	}

	function copyToClipboard(val) {
		var t = document.createElement("textarea");
		document.body.appendChild(t);
		t.value = val;
		t.select();
		document.execCommand('copy');
		document.body.removeChild(t);
	}

	function board_copy_url(url) {
		copyToClipboard(url);
		alert('링크가 복사 되었습니다.');
	}

</script>

<? if (($b_board_type == "Q&A") || ($b_board_type == "FAQ")){?>
<SCRIPT LANGUAGE="JavaScript">
<!--
<? if ($b_html_tf == "Y") { ?>
var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "reply",
	sSkinURI: "../../_common/SE2-2.8.2.3/SmartEditor2Skin.html",
	htParams : {bUseToolbar : true, 
	fOnBeforeUnload : function(){ 
		// alert('야') 
	}
	}, 
	fCreator: "createSEditor2"
});
<? } ?>
//-->
</SCRIPT>
<?}?>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>