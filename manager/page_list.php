<?session_start();?>
<?
# =============================================================================
# File Name    : page_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-08-02
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "CP001"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/admin/admin.php";
	require "../_classes/biz/page/page.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode								= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$sel_page_lang			= isset($_POST["sel_page_lang"]) && $_POST["sel_page_lang"] !== '' ? $_POST["sel_page_lang"] : (isset($_GET["sel_page_lang"]) ? $_GET["sel_page_lang"] : '');
	$page_lang					= isset($_POST["page_lang"]) && $_POST["page_lang"] !== '' ? $_POST["page_lang"] : (isset($_GET["page_lang"]) ? $_GET["page_lang"] : '');
	$use_tf							= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

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
	
	if ($sel_page_lang == "") $sel_page_lang= "KOR";

	$menu_tf = "";

	$arr_rs = listPage($conn, $sel_page_lang, $menu_tf, $use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "사용자 메뉴 리스트 조회", "List");

	#echo sizeof($arr_rs);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<link rel="stylesheet" type="text/css" href="./css/common.css" media="all" />
<script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src="./js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="./js/ui.js"></script>
<script type="text/javascript" src="./js/common.js"></script>
<script type="text/javascript" >

	function js_write() {
		var url = "pcode_write.php";
	}

	function js_view(rn, seq) {

		var url = "pcode_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '600', '353', 'NO');
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
		frm.action = "page_list.php";
		frm.submit();
	}

	function js_sel_page_lang() {
		var frm = document.frm;
		frm.action = "page_list.php";
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
		require "../_common/left_area.php";
?>
		</div>
		<div id="container">
			<div class="top">
<?
	#====================================================================
	# common top_area
	#====================================================================
		require "../_common/top_area.php";
?>
			</div>
			<div class="contents">
<?
	#====================================================================
	# common location_area
	#====================================================================
		require "../_common/location_area.php";
?>
				<div class="tit_h3"><h3><?=$p_menu_name?></h3></div>
				<div class="cont">

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

					<div class="tbl_top">

					<div class="btn_wrap">
						<div class="right">
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="javascript:document.location='page_write.php?sel_page_lang=<?=$sel_page_lang?>'">1 뎁스메뉴등록</button>
							<? } ?>
							<? if ($sPageRight_U == "Y") { ?>
							<button type="button" class="button type02" onClick="NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>', 'pop_order_page', '560', '470', 'no');">메뉴순서변경</button>
							<? } ?>
						</div>
					</div>

					<button type="button" class="button <? if ($sel_page_lang <> "KOR") echo "type02"; ?> " onClick="javascript:document.location='page_list.php?sel_page_lang=KOR'">국문</button>
					<!--
					<button type="button" class="button <? if ($sel_page_lang <> "ENG") echo "type02"; ?>" onClick="javascript:document.location='page_list.php?sel_page_lang=ENG'">영문</button>
					<button type="button" class="button <? if ($sel_page_lang <> "CHN") echo "type02"; ?>" onClick="javascript:document.location='page_list.php?sel_page_lang=CHN'">중문</button>
					-->
					<input type="hidden" name="sel_page_lang" id="sel_page_lang" value="<?=$sel_page_lang?>">

					</div>
					<div class="tbl_style01">
						<table>
							<colgroup>
								<col width="23%" />
								<col width="35%" />
								<col width="10%" />
								<col width="10%" />
								<col width="22%" />
							</colgroup>
							<thead>
								<tr>
									<th>메뉴명</th>
									<th>메뉴URL</th>
									<th>메뉴노출여부</th>
									<th>외부링크</th>
									<th>비고</th>
								</tr>
							</thead>
							<tbody>
				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							//PAGE_NO, PAGE_CD, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_RIGHT
							
							$PAGE_NO				= trim($arr_rs[$j]["PAGE_NO"]);
							$PAGE_CD				= trim($arr_rs[$j]["PAGE_CD"]);
							$PAGE_NAME			= setStringFromDB($arr_rs[$j]["PAGE_NAME"]);
							$PAGE_URL				= setStringFromDB($arr_rs[$j]["PAGE_URL"]);
							$PAGE_FLAG			= trim($arr_rs[$j]["PAGE_FLAG"]);
							$PAGE_SEQ01			= trim($arr_rs[$j]["PAGE_SEQ01"]);
							$PAGE_SEQ02			= trim($arr_rs[$j]["PAGE_SEQ02"]);
							$PAGE_SEQ03			= trim($arr_rs[$j]["PAGE_SEQ03"]);
							$PAGE_SEQ04			= trim($arr_rs[$j]["PAGE_SEQ04"]);
							$PAGE_RIGHT			= trim($arr_rs[$j]["PAGE_RIGHT"]);
							$MENU_TF				= trim($arr_rs[$j]["MENU_TF"]);
							$URL_TYPE				= trim($arr_rs[$j]["URL_TYPE"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							//$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
							
							$PAGE_NAME = str_replace("<br />","",$PAGE_NAME);
							$PAGE_NAME = str_replace("<br/>","",$PAGE_NAME);
							$PAGE_NAME = str_replace("<br>","",$PAGE_NAME);

							//$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

							if (strlen($PAGE_CD) == 2) {
								$menu_str = "<font color='blue'>⊙ ".$PAGE_NAME." (".$PAGE_NO.")</font>";
							} else {
								for ($menuspace = 0 ; $menuspace < strlen($PAGE_CD) ;$menuspace++) {
									$menu_str = $menu_str ."&nbsp;";
								}

								if (strlen($PAGE_CD) == 4) {
									$menu_str = $menu_str ."┗ <font color='navy'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								} else if (strlen($PAGE_CD) == 6) {
									$menu_str = $menu_str ."&nbsp;&nbsp;┗ <font color='gray'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								} else if (strlen($PAGE_CD) == 8) {
									$menu_str = $menu_str ."&nbsp;&nbsp;&nbsp;&nbsp;┗ <font color='darkgray'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								} else if (strlen($PAGE_CD) == 10) {
									$menu_str = $menu_str ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗ <font color='orange'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								}
							}

							if ($URL_TYPE == "Y") {
								$str_url_type = "외부링크";
							} else { 
								$str_url_type = "";
							}

							if ($MENU_TF == "Y") {
								$str_menu_tf = "보이기";
							} else { 
								$str_menu_tf = "";
							}
				?>
						<tr>
							<td style="text-align:left;padding-left:10px"><a href="page_write.php?mode=S&m_level=<?=$PAGE_CD?>&page_no=<?=$PAGE_NO?>&sel_page_lang=<?=$sel_page_lang?>"><?=$menu_str?></a></td>
							<td style="text-align:left;padding-left:10px"><?=$PAGE_URL?></td>
							<td><?=$str_menu_tf?></td>
							<td><?=$str_url_type?></td>
							<td>
							<? 
								if ($sPageRight_I == "Y") {
									if (strlen($PAGE_CD) <= 8) {
										if (strlen($PAGE_CD) == 2) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>">2 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($PAGE_CD) == 4) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>&m_seq03=<?=$PAGE_SEQ03?>&m_seq04=<?=$PAGE_SEQ04?>">3 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($PAGE_CD) == 6) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>&m_seq03=<?=$PAGE_SEQ03?>&m_seq04=<?=$PAGE_SEQ04?>">4 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($PAGE_CD) == 8) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>&m_seq03=<?=$PAGE_SEQ03?>&m_seq04=<?=$PAGE_SEQ04?>">5 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
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
							<td align="center" height="50" colspan="7">
								<div class="nodata">데이터가 없습니다.</div>
								
							</td>
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
