<?session_start();?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================

	$menu_right = "MA002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/board/board.php";
#====================================================================
# Request Parameter
#====================================================================
	
	$seq_no = "1";
	
	if ($mode == "U") {
		$result= updateMainBoard($conn, $b_code_01, $b_code_02, $b_code_03, $b_code_04, $b_code_05, $b_code_06, $b_code_07, $b_code_08, $b_code_09, $b_code_10, $b_code_11, $b_code_12, $b_code_13, $b_code_14, $b_code_15, $seq_no);
	}

	$arr_rs = selectMainBoard($conn, $seq_no);

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?
	if (($mode == "U")&&($result)) {
?>
<script type="text/javascript">
<!--
	alert('수정되었습니다.');
//-->
</script>
<?
	}
?>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">

	function js_modify(seq) {

		var frm = document.frm;

		if (frm.b_code_01.value == "") {
			alert('메인 상단 노출 게시판을 선택 하세요.');
			return;
		}

		if (frm.b_code_02.value == "") {
			alert('메인 1 번 노출 게시판을 선택 하세요.');
			return;
		}

		if (frm.b_code_03.value == "") {
			alert('메인 2 번 노출 게시판을 선택 하세요.');
			return;
		}

		if (frm.b_code_04.value == "") {
			alert('메인 3 번 노출 게시판을 선택 하세요.');
			return;
		}

		if (frm.b_code_05.value == "") {
			alert('메인 4 번 노출 게시판을 선택 하세요.');
			return;
		}

		if (frm.b_code_06.value == "") {
			alert('메인 5 번 노출 게시판을 선택 하세요.');
			return;
		}

		if (frm.b_code_07.value == "") {
			alert('모바일 메인 상단 노출 게시판을 선택 하세요.');
			return;
		}

		frm.seq_no.value = seq;
		frm.mode.value = "U";
		frm.target = "";
		frm.method = "get";
		frm.action = "main_board.php";
		frm.submit();
		
	}

</script>
</head>

<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		
		<section class="conBox">

<form id="bbsList" name="frm" method="post" action="javascript:js_serch();">
<input type="hidden" name="seq_no" value="1">
<input type="hidden" name="mode" value="">
				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<div class="sp0"></div>
					<div class="expArea">
						<ul class="fLeft">
							<li class="total">메인에 노출 되는 게시판을 선택 합니다.</li> &nbsp;&nbsp;노출 순서는 상위에 게시판 부터 입니다.
						</ul>
						<p class="fRight">
							<a href="javascript:js_modify();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="15%" />
							<col width="85%" />
						</colgroup>

						<thead>
							<tr>
								<th>위치</th>
								<th>게시판 이름</th>
							</tr>
						</thead>
						<tbody>

						<?
							$B_CODE_01 	= trim($arr_rs[0]["B_CODE_01"]);
							$B_CODE_02 	= trim($arr_rs[0]["B_CODE_02"]);
							$B_CODE_03 	= trim($arr_rs[0]["B_CODE_03"]);
							$B_CODE_04 	= trim($arr_rs[0]["B_CODE_04"]);
							$B_CODE_05 	= trim($arr_rs[0]["B_CODE_05"]);
							$B_CODE_06 	= trim($arr_rs[0]["B_CODE_06"]);
							$B_CODE_07 	= trim($arr_rs[0]["B_CODE_07"]);
							$B_CODE_08 	= trim($arr_rs[0]["B_CODE_08"]);
							$B_CODE_09 	= trim($arr_rs[0]["B_CODE_09"]);
							$B_CODE_10 	= trim($arr_rs[0]["B_CODE_10"]);
							$B_CODE_11 	= trim($arr_rs[0]["B_CODE_11"]);
							$B_CODE_12 	= trim($arr_rs[0]["B_CODE_12"]);
							$B_CODE_13 	= trim($arr_rs[0]["B_CODE_13"]);
							$B_CODE_14 	= trim($arr_rs[0]["B_CODE_14"]);
							$B_CODE_15 	= trim($arr_rs[0]["B_CODE_15"]);

							$arr_rs_board = listBoardConfig($conn, $g_site_no, "", "", "", "", "N", "BOARD_GROUP", "KRWU", "1", "1000");

							
						?>
							<tr>
								<td>메인 상단</td>
								<td>
									<select name="b_code_01" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_01) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>메인 1</td>
								<td>
									<select name="b_code_02" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_02) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>메인 2</td>
								<td>
									<select name="b_code_03" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_03) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>메인 3</td>
								<td>
									<select name="b_code_04" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_04) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>메인 4</td>
								<td>
									<select name="b_code_05" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_05) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>메인 5</td>
								<td>
									<select name="b_code_06" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_06) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td>모바일 메인 상단선택</td>
								<td>
									<select name="b_code_07" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_07) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 1</td>
								<td>
									<select name="b_code_08" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_08) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 2</td>
								<td>
									<select name="b_code_09" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_09) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 3</td>
								<td>
									<select name="b_code_10" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_10) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 4</td>
								<td>
									<select name="b_code_11" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_11) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 5</td>
								<td>
									<select name="b_code_12" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_12) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 6</td>
								<td>
									<select name="b_code_13" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
									<?
										if (sizeof($arr_rs_board) > 0) {
											for ($j = sizeof($arr_rs_board)-1 ; $j >= 0; $j--) {
												$SITE_NO			= trim($arr_rs_board[$j]["SITE_NO"]);
												$BOARD_CODE		= trim($arr_rs_board[$j]["BOARD_CODE"]);
												$BOARD_NM			= SetStringFromDB($arr_rs_board[$j]["BOARD_NM"]);
									?>
										<option value="<?=$BOARD_CODE?>" <? if ($BOARD_CODE == $B_CODE_13) echo "selected"; ?> ><?=$BOARD_NM?></option>
									<?
											}
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td>모바일 메인 사진 영역</td>
								<td>
									<select name="b_code_14" style="width:350px;">
									<option value="">게시판을 선택 하세요.</option>
										<option value="photo" <? if ($B_CODE_14 == "photo") echo "selected"; ?> >사진</option>
										<option value="mobile" <? if ($B_CODE_14 == "mobile") echo "selected"; ?> >모바일사진</option>
									</select>
								</td>
							</tr>

						</tbody>
					</table>
				</fieldset>
			</form>
			<div class="btnArea">
				<ul class="fRight">
					
				</ul>
			</div>
		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
