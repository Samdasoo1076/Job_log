<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : board_config_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-22
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
	$menu_right = "BO001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/admin/admin.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

	$bb_code				= isset($_POST["bb_code"]) && $_POST["bb_code"] !== '' ? $_POST["bb_code"] : (isset($_GET["bb_code"]) ? $_GET["bb_code"] : '');
	$bb_no					= isset($_POST["bb_no"]) && $_POST["bb_no"] !== '' ? $_POST["bb_no"] : (isset($_GET["bb_no"]) ? $_GET["bb_no"] : '');

	$con_cate_01		= isset($_POST["con_cate_01"]) && $_POST["con_cate_01"] !== '' ? $_POST["con_cate_01"] : (isset($_GET["con_cate_01"]) ? $_GET["con_cate_01"] : '');
	$con_cate_02		= isset($_POST["con_cate_02"]) && $_POST["con_cate_02"] !== '' ? $_POST["con_cate_02"] : (isset($_GET["con_cate_02"]) ? $_GET["con_cate_02"] : '');
	$con_cate_03		= isset($_POST["con_cate_03"]) && $_POST["con_cate_03"] !== '' ? $_POST["con_cate_03"] : (isset($_GET["con_cate_03"]) ? $_GET["con_cate_03"] : '');
	$con_use_tf			= isset($_POST["con_use_tf"]) && $_POST["con_use_tf"] !== '' ? $_POST["con_use_tf"] : (isset($_GET["con_use_tf"]) ? $_GET["con_use_tf"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$con_board_code	= isset($_POST["con_board_code"]) && $_POST["con_board_code"] !== '' ? $_POST["con_board_code"] : (isset($_GET["con_board_code"]) ? $_GET["con_board_code"] : '');
	$con_board_type	= isset($_POST["con_board_type"]) && $_POST["con_board_type"] !== '' ? $_POST["con_board_type"] : (isset($_GET["con_board_type"]) ? $_GET["con_board_type"] : '');
	$con_board_cate	= isset($_POST["con_board_cate"]) && $_POST["con_board_cate"] !== '' ? $_POST["con_board_cate"] : (isset($_GET["con_board_cate"]) ? $_GET["con_board_cate"] : '');

	#List Parameter
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
	$bb_code			= SetStringToDB($bb_code);
	$bb_no				= SetStringToDB($bb_no);

	$con_cate_01 = SetStringToDB($con_cate_01);
	$con_cate_02 = SetStringToDB($con_cate_02);
	$con_cate_03 = SetStringToDB($con_cate_03);
	$con_use_tf	 = SetStringToDB($con_use_tf);
	
	$con_del_tf = "N";
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
		$nPageSize = 15;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntBoardConfig($conn, $g_site_no, $con_board_code, $con_board_type, $con_board_cate, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBoardConfig($conn, $g_site_no, $con_board_code, $con_board_type, $con_board_cate, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize);

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
<script language="javascript">

	function js_write() {
		document.location.href = "board_config_write.php";
	}

	function js_view(rn, config_no) {

		var frm = document.frm;
		
		frm.config_no.value = config_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "board_config_write.php";
		frm.submit();
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "board_config_list.php";
		frm.submit();
	}

	function js_toggle(bb_code, bb_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.bb_code.value = bb_code;
			frm.bb_no.value = bb_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "board_config_list.php";
			frm.submit();
		}
	}

	function js_con_cate_01 () {
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "board_config_list.php";
		frm.submit();
	}

	function js_con_cate_02 () {
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "board_config_list.php";
		frm.submit();
	}

	function js_con_cate_03 () {
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "board_config_list.php";
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
				<div class="cont">
					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 개</span></div>
						<div class="right">

<form id="searchBar" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="config_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

							<div class="fl_wrap">
								<select name="search_field" id="search_field">
									<option value="BOARD_NM" <? if ($search_field == "BOARD_NM") echo "selected"; ?> >게시판명</option>
								</select>
								<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
								<button type="button" class="button" onClick="js_search();">검색</button>
							</div>
</form>
						</div>
					</div>

					<div class="tbl_style01">
						<table>
							<colgroup>
								<col width="5%" />
								<col width="17%" /> <!-- 게시판명 -->
								<col width="10%" /> <!-- 게시판명 -->
								<col width="10%" /> <!-- 게시판유형 -->
								<col width="8%" /> <!-- 리스트권한 --> 
								<col width="8%" /> <!-- 조회권한 --> 
								<col width="8%" /> <!-- 쓰기권한 -->
								<!--
								<col width="6%" />
								<col width="6%" />
								-->
								<col width="8%" />	<!-- HTML -->
								<col width="8%" /> <!-- 파일 -->
								<col width="8%" /> <!-- 게시판 URL -->
								<col width="10%" /> <!-- 등록일 -->
							</colgroup>
							<thead>
								<tr>
									<th>No.</th>
									<th>게시판명</th>
									<th>게시판그룹</th>
									<th>게시판유형</th>
									<th>목록권한</th>
									<th>읽기권한</th>
									<th>쓰기권한</th>
									<!--
									<th>비밀글</th>
									<th>검색</th>
									-->
									<th>HTML</th>
									<th>파일</th>
									<th>게시판 CODE</th>
									<th>등록일</th>
								</tr>
							</thead>
							<tbody>

							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										
										//B.CONFIG_NO, B.SITE_NO, B.BOARD_CODE, B.BOARD_TYPE, B.READ_GROUP, B.WRITE_GROUP, B.REPLY_TF, B.HTML_TF, 
										//B.FILE_TF, B.FILE_CNT, B.BOARD_NM, B.BOARD_MEMO, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE

										$rn						= trim($arr_rs[$j]["rn"]);
										$CONFIG_NO		= trim($arr_rs[$j]["CONFIG_NO"]);
										$SITE_NO			= trim($arr_rs[$j]["SITE_NO"]);
										$BOARD_CODE		= trim($arr_rs[$j]["BOARD_CODE"]);
										$BOARD_TYPE		= trim($arr_rs[$j]["BOARD_TYPE"]);
										$BOARD_GROUP	= trim($arr_rs[$j]["BOARD_GROUP"]);
										$LIST_GROUP		= trim($arr_rs[$j]["LIST_GROUP"]);
										$READ_GROUP		= trim($arr_rs[$j]["READ_GROUP"]);
										$WRITE_GROUP	= trim($arr_rs[$j]["WRITE_GROUP"]);
										$SECRET_TF		= trim($arr_rs[$j]["SECRET_TF"]);
										$SEARCH_TF		= trim($arr_rs[$j]["SEARCH_TF"]);
										$COMMENT_TF		= trim($arr_rs[$j]["COMMENT_TF"]);
										$REPLY_TF			= trim($arr_rs[$j]["REPLY_TF"]);
										$HTML_TF			= trim($arr_rs[$j]["HTML_TF"]);
										$FILE_TF			= trim($arr_rs[$j]["FILE_TF"]);
										$FILE_CNT			= trim($arr_rs[$j]["FILE_CNT"]);
										$BOARD_NM			= SetStringFromDB($arr_rs[$j]["BOARD_NM"]);
										$BOARD_MEMO		= SetStringFromDB($arr_rs[$j]["BOARD_MEMO"]);
										$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);
										
										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
										
										$str_secret_tf = "";
										if ($SECRET_TF == "E") $str_secret_tf = "선택";
										if ($SECRET_TF == "A") $str_secret_tf = "무조건";
										if ($SECRET_TF == "N") $str_secret_tf = "사용안함";

										$str_search_tf = "";
										if ($SEARCH_TF == "Y") $str_search_tf = "O";
										if ($SEARCH_TF == "N") $str_search_tf = "X";

										$str_html_tf = "";
										if ($HTML_TF == "Y") $str_html_tf = "O";
										if ($HTML_TF == "N") $str_html_tf = "X";

										$str_file_tf = "";
										if ($FILE_TF == "Y") $str_file_tf = "O";
										if ($FILE_TF == "N") $str_file_tf = "X";

							?>
								<tr> 
									<td><?= $rn ?></td>
									<td><a href="javascript:js_view('<?=$rn?>','<?=$CONFIG_NO?>');"><?=$BOARD_NM?></a></td>
									<td><?= getDcodeName($conn, "BOARD_GROUP", $BOARD_GROUP); ?></td>
									<td><?= getDcodeName($conn, "BOARD_TYPE", $BOARD_TYPE); ?></td>
									<td><?= getDcodeName($conn, "LIST_GROUP", $LIST_GROUP); ?></td>
									<td><?= getDcodeName($conn, "READ_GROUP", $READ_GROUP); ?></td>
									<td><?= getDcodeName($conn, "WRITE_GROUP", $WRITE_GROUP); ?></td>
									<!--
									<td><?= $str_secret_tf ?></td>
									<td><?= $str_search_tf ?></td>
									-->
									<td><?= $str_html_tf ?></td>
									<td><?= $str_file_tf ?></td>
									<td><!--<a href="/kor/board/board_list.php?bb_code=<?= $BOARD_CODE ?>">--><?= $BOARD_CODE ?><!--</a>--></td>
									<td>
										<?=$REG_DATE?>
									</td>
								</tr>
							<?			
									}
								} else { 
							?> 
								<tr>
									<td colspan="14">
										<div class="nodata">검색 결과가 없습니다. <br>다시 검색해주세요.</div>
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
								$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>

						</div>
						<div class="right">
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_write();">등록</button>
							<? } ?>
						</div>
					</div>
				</div>
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
