<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : period_page_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2023-10-16
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#==============================================================================
# DB Include, DB Connection
#==============================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "ST002"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# common_header Check Session
#==============================================================================
	require "../../_common/common_header.php"; 

#===============================================================================
# common function, login_function
#===============================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/statistic/statistic.php";
	require "../../_classes/biz/admin/admin.php";
	
#==============================================================================
# Request Parameter
#==============================================================================

	$mode								= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');

	$start_date					= isset($_POST["start_date"]) && $_POST["start_date"] !== '' ? $_POST["start_date"] : (isset($_GET["start_date"]) ? $_GET["start_date"] : '');
	$end_date						= isset($_POST["end_date"]) && $_POST["end_date"] !== '' ? $_POST["end_date"] : (isset($_GET["end_date"]) ? $_GET["end_date"] : '');

	$con_divicetype			= isset($_POST["con_divicetype"]) && $_POST["con_divicetype"] !== '' ? $_POST["con_divicetype"] : (isset($_GET["con_divicetype"]) ? $_GET["con_divicetype"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

	$mode			= SetStringToDB($mode);

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

#==============================================================================
# Request Parameter
#==============================================================================

	if ($start_date == "") $start_date = date("Y-m-d",strtotime("-7 day"));
	if ($end_date == "") $end_date = date("Y-m-d",strtotime("0 day"));

#==============================================================================
# Page process
#==============================================================================

	$arr_rs = listPagePeriod($conn, $start_date, $end_date, $con_divicetype);
	
	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "기간별 접속통계 조회", "List");

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

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "period_page_list.php";
		frm.submit();
	}

	function js_reload() {
		var frm = document.frm;
		frm.method = "get";
		frm.target = "";
		frm.action = "period_page_list.php";
		frm.submit();
	}


	function js_excel_list() {

		var frm = document.frm;
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER["PHP_SELF"])?>";
		//frm.submit();

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
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<div class="cont">
					
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
									<th>조회기간</th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
										<select name="con_divicetype">
											<option value="">기기 전체</option>
											<option value="P" <? if ($con_divicetype == "P") { ?>selected<? } ?>>PC</option>
											<option value="M" <? if ($con_divicetype == "M") { ?>selected<? } ?>>Mobile</option>
										</select>
										</div>
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" id="start_date" name="start_date" value="<?=$start_date?>" class="datepicker" style="width:120px" readonly="1" />
										</div>
										<span class="tbl_txt"> ~ </span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" id="end_date" name="end_date" value="<?=$end_date?>" class="datepicker" style="width:120px" readonly="1" />
										</div>
										<div class="datepickerbox" style="width:120px;" >
										<button type="button" class="button" onClick="js_search();">검색</button>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format(sizeof($arr_rs))?></span> 페이지</span></div>
						<div class="right">
							
							<!-- 버튼 자리 -->
						</div>
					</div>
					

					<div class="tbl_style01">
						<table>

							<colgroup>
								<col width="33%" /><!-- 1뎁스 -->
								<col width="34%" /><!-- 페이지명 -->
								<col width="33%" /><!-- 조회수  -->
							</colgroup>
							<thead>
							<tr>
								<th scope="col">구분</th>
								<th scope="col">페이지명</th>
								<th scope="col">조회수</th>
							</tr>
							</thead>
							<tbody>
							<?
								$nCnt			= 0;
								$CNT			= 0;
								$TOT_CNT	= 0;

								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										
										$PAGE_NAME			= trim($arr_rs[$j]["PAGE_NAME"]);
										$CATE						= trim($arr_rs[$j]["CATE"]);
										$CNT						= trim($arr_rs[$j]["CNT"]);

										$TOT_CNT				= $TOT_CNT + $CNT;

							?>
								<tr> 
									<td><?=$CATE?></td>
									<td><?=$PAGE_NAME?></td>
									<td style="text-align:right;padding-right:10px"><?=number_format($CNT)?></td>
								</tr>
							<?
									}
							?>
								<tr bgcolor="#EFEFEF"> 
									<td colspan="2">합계</td>
									<td style="text-align:right;padding-right:10px"><?=number_format($TOT_CNT)?></td>
								</tr>
							<?
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

						<div class="right">
							<? if ($sPageRight_F == "Y") { ?>
							<button type="button" class="button" onClick="js_excel_list();">엑셀로 받기</button>
							<? } ?>
						</div>
						
					</div>
					<div class="sp60"></div>
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