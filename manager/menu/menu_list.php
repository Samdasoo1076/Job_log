<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : menu_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-20
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
	$menu_right = "AD004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/menu/menu.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$order_field			= isset($_POST["order_field"]) && $_POST["order_field"] !== '' ? $_POST["order_field"] : (isset($_GET["order_field"]) ? $_GET["order_field"] : '');
	$order_str				= isset($_POST["order_str"]) && $_POST["order_str"] !== '' ? $_POST["order_str"] : (isset($_GET["order_str"]) ? $_GET["order_str"] : '');

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

	$use_tf			= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listAdminMenu($conn, $use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 조회", "List");

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

	<script type="text/javascript" >


	function js_write() {
		var url = "pcode_write_popup.php";
		NewWindow(url, '대분류등록', '600', '373', 'NO');
	}

	function js_view(rn, seq) {

		var url = "pcode_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '600', '373', 'NO');
	}
	
	function js_view_dcode(rn, seq) {

		var url = "dcode_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '600', '650', 'NO');
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "menu_list.php";
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

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" >
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

					<div class="tbl_top">

					<div class="btn_wrap">
						<div class="right">
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="NewWindow('pop_menu_write.php', 'pop_add_menu', '560', '305', 'no');">대분류등록</button>
							<? } ?>
							<? if ($sPageRight_U == "Y") { ?>
							<button type="button" class="button type02" onClick="NewWindow('pop_menu_order.php', 'pop_order_menu', '560', '470', 'no');">메뉴순서변경</button>
							<? } ?>
						</div>
					</div>

					</div>
					<div class="tbl_style01">
						<table>
							<colgroup>
								<col width="23%" />
								<col width="45%" />
								<!--<col width="10%" />-->
								<col width="10%" />
								<col width="22%" />
							</colgroup>
							<thead>
								<tr>
									<th>메뉴명</th>
									<th>메뉴URL</th>
									<!--<th>메뉴구분</th>-->
									<th>권한코드</th>
									<th>비고</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										
										//MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
										
										$MENU_NO				= trim($arr_rs[$j]["MENU_NO"]);
										$MENU_CD				= trim($arr_rs[$j]["MENU_CD"]);
										$MENU_NAME			= trim($arr_rs[$j]["MENU_NAME"]);
										$MENU_URL				= trim($arr_rs[$j]["MENU_URL"]);
										$MENU_FLAG			= trim($arr_rs[$j]["MENU_FLAG"]);
										$MENU_SEQ01			= trim($arr_rs[$j]["MENU_SEQ01"]);
										$MENU_SEQ02			= trim($arr_rs[$j]["MENU_SEQ02"]);
										$MENU_SEQ03			= trim($arr_rs[$j]["MENU_SEQ03"]);
										//$MENU_TYPE			= trim($arr_rs[$j]["MENU_TYPE"]);
										$MENU_RIGHT			= trim($arr_rs[$j]["MENU_RIGHT"]);
										//$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
										//$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
										//$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

										//$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

										/*
										if ($MENU_TYPE == "S") {
											$str_menu_type = "시스템 관리";
										} else {
											$str_menu_type = "콘텐츠 관리";
										}
										*/

										if (strlen($MENU_CD) == 2) {
											$menu_str = "<font color='blue'>⊙ ".$MENU_NAME."</font>";
										} else {
											for ($menuspace = 0 ; $menuspace < strlen($MENU_CD) ;$menuspace++) {
												$menu_str = $menu_str ."&nbsp;";
											}

											if (strlen($MENU_CD) == 4) {
												$menu_str = $menu_str ."┗ <font color='navy'>".$MENU_NAME."</font>";
											} else if (strlen($MENU_CD) == 6) {
												$menu_str = $menu_str ."&nbsp;&nbsp;┗ <font color='gray'>".$MENU_NAME."</font>";
											}
										}

							?>
								<tr>
									<td style="text-align:left;padding-left:20px"><a href="javascript:NewWindow('pop_menu_write.php?mode=S&m_level=<?=$MENU_CD?>&menu_no=<?=$MENU_NO?>', 'pop_modify_menu', '560', '305', 'no');"><?=$menu_str?></a></td>
									<td style="text-align:left;padding-left:20px"><?=$MENU_URL?></td>
									<!--<td><?= $str_menu_type ?></td>-->
									<td><?= $MENU_RIGHT ?></td>
									<td>
							<? 
								if ($sPageRight_I == "Y") {
									if (strlen($MENU_CD) <= 4) {
										if (strlen($MENU_CD) == 2) {
							?>
							<? if ($sPageRight_I == "Y") {?>
										<button type="button" class="button" onClick="NewWindow('pop_menu_write.php?m_level=<?=$MENU_CD?>&m_seq01=<?=$MENU_SEQ01?>&m_seq02=<?=$MENU_SEQ02?>', 'pop_add_menu', '560', '305', 'no');">중분류등록</button>
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
										<button type="button" class="button type02" onClick="NewWindow('pop_menu_order.php?m_level=<?=$MENU_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</button>
							<? } ?>
							<?
										} else {
							?>
							<? if ($sPageRight_I == "Yzzzz") {?>
										<a href="javascript:NewWindow('pop_menu_write.php?m_level=<?=$MENU_CD?>&m_seq01=<?=$MENU_SEQ01?>&m_seq02=<?=$MENU_SEQ02?>', 'pop_add_menu', '560', '305', 'no');">소분류등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Yzzzz") {?>
										<a href="javascript:NewWindow('pop_menu_order.php?m_level=<?=$MENU_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										}
									}
									echo "&nbsp;";
								} else {
									echo "&nbsp;";
								}
							?>
								</td>
							</tr>
							<?			
									$menu_str = "";
									}
								} else { 
							?> 
							<tr>
								<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
							</tr>
							<? 
								}
							?>
						</tbody>
						</table>
					</div>
			</form>
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
