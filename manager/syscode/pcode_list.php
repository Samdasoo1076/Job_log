<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : pcode_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-21
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SY002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/syscode/syscode.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$order_field		= isset($_POST["order_field"]) && $_POST["order_field"] !== '' ? $_POST["order_field"] : (isset($_GET["order_field"]) ? $_GET["order_field"] : '');
	$order_str			= isset($_POST["order_str"]) && $_POST["order_str"] !== '' ? $_POST["order_str"] : (isset($_GET["order_str"]) ? $_GET["order_str"] : '');

	#List Parameter

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$order_field		= SetStringToDB($order_field);
	$order_str			= SetStringToDB($order_str);

	$use_tf				= SetStringToDB($use_tf);
	
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

	$nListCnt =totalCntPcode($conn, $g_site_no, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listPcode($conn, $g_site_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "대분류코드조회", "List");

	#echo sizeof($arr_rs);
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
	<script language="javascript" type="text/javascript" >

	function js_write() {
		var url = "pcode_write_popup.php";
		NewWindow(url, '대분류등록', '560', '363', 'NO');
	}

	function js_view(rn, seq) {

		var url = "pcode_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '560', '363', 'NO');
	}
	
	function js_view_dcode(rn, seq) {

		var url = "dcode_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '560', '680', 'NO');
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "pcode_list.php";
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
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

							<div class="fl_wrap">
								<select name="search_field" id="search_field">
									<option value="PCODE" <? if ($search_field == "PCODE") echo "selected"; ?> >코드</option>
									<option value="PCODE_NM" <? if ($search_field == "PCODE_NM") echo "selected"; ?> >코드명</option>
								</select>
								<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
								<button type="button" class="button type03" onClick="js_search();">검색</button>
							</div>
</form>
						</div>
					</div>

					<div class="tbl_style01">
						<table>
							<colgroup>
								<col width="5%">
								<col width="30%">
								<col width="30%">
								<col width="35%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">NO.</th>
									<th scope="col">코드</th>
									<th scope="col">코드명</th>
									<th scope="col">메뉴</th>
								</tr>
							</thead>
							<tbody>
				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							#rn, PCODE_NO, PCODE, PCODE_NM, PCODE_MEMO, PCODE_SEQ_NO, USE_TF, DEL_TF, 
							#REG_ADM, REG_DATE, UP_ADM, UP_DATED, DEL_ADM, DEL_DATE

							$rn							= trim($arr_rs[$j]["rn"]);
							$PCODE_NO				= trim($arr_rs[$j]["PCODE_NO"]);
							$PCODE					= trim($arr_rs[$j]["PCODE"]);
							$PCODE_NM				= trim($arr_rs[$j]["PCODE_NM"]);
							$PCODE_MEMO			= trim($arr_rs[$j]["PCODE_MEMO"]);
							$PCODE_SEQ_NO		= trim($arr_rs[$j]["PCODE_SEQ_NO"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
				
				?>
								<tr> 
									<td><?=$rn?></td>
									<td><a href="javascript:js_view('<?= $rn ?>','<?= $PCODE_NO ?>');"><?= $PCODE ?></a></td>
									<td><?= $PCODE_NM ?></td>
									<td><a href="javascript:js_view_dcode('<?= $rn ?>','<?= $PCODE_NO ?>');">[세부분류코드]</a></td>
								</tr>
				<?			
						}
					} else { 
				?> 
								<tr>
									<td colspan="7">
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
	<div style="height:50px"></div>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>