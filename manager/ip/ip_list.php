<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : ip_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2023-08-04
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
	$menu_right = "SY003"; // 메뉴마다 셋팅 해 주어야 합니다

//	$sPageRight_		= "Y";
//	$sPageRight_R		= "Y";
//	$sPageRight_I		= "Y";
//	$sPageRight_U		= "Y";
//	$sPageRight_D		= "Y";
//	$sPageRight_F		= "Y";

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
	require "../../_classes/biz/ip/ip.php";

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$seq_no					= isset($_POST["seq_no"]) && $_POST["seq_no"] !== '' ? $_POST["seq_no"] : (isset($_GET["seq_no"]) ? $_GET["seq_no"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	if ($mode == "T") {

		updateBlockIPUseTF($conn, $use_tf, $_SESSION['s_adm_no'], (int)$seq_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 허용IP 변경 (일련번호 : ".(int)$seq_no.")", "Update");

	}

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage				= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage				= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_use_tf	= "";
	$con_del_tf	= "N";
#============================================================
# Page process
#============================================================
	if ($nPage == 0) $nPage = "1";

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	$nPageSize = 20;
	$nPageBlock	= 10;
#===============================================================
# Get Search list count
#===============================================================
	
	$nListCnt = totalCntBlockIP ($conn, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$arr_rs = listBlockIP($conn, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 허용IP 리스트 조회", "List");

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
		menu_cd=document.frm.menu_cd.value;
		document.location.href = "ip_write.php?menu_cd="+menu_cd;
	}

	function js_view(seq_no) {

		var frm = document.frm;
		
		frm.seq_no.value = seq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "ip_write.php";
		frm.submit();
		
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "ip_list.php";
		frm.submit();
	}

	function js_toggle(seq_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('사용 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.seq_no.value = seq_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "ip_list.php";
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
				<div class="cont">
					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span>개</span></div>
						<div class="right">

<form id="searchBar" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="menu_cd" value="<?//=$menu_cd?>" >
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=(int)$nPage?>">
<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>">

							<div class="fl_wrap">
								<select name="search_field" id="search_field">
									<option value="BLOCK_IP" <? if ($search_field == "BLOCK_IP") echo "selected"; ?> >IP</option>
									<option value="MEMO" <? if ($search_field == "MEMO") echo "selected"; ?> >메모</option>
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
								<col width="5%" /><!-- 번호 -->
								<col width="22%" /><!-- 허용IP -->
								<col width="55%" /><!-- 메모 -->
								<col width="11%" /><!-- 등록일 -->
								<col width="7%" /><!-- 사용여부 -->
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>허용 IP</th>
									<th>메모</th>
									<th>등록일</th>
									<th>사용여부</th>
								</tr>
							</thead>
							<tbody>

							<?

								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										
										$rn							= trim($arr_rs[$j]["rn"]);
										$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
										$BLOCK_IP				= trim($arr_rs[$j]["BLOCK_IP"]);
										$MEMO						= SetStringFromDB($arr_rs[$j]["MEMO"]);
										$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
										$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
										$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
										$REG_ADM				= trim($arr_rs[$j]["REG_ADM"]);
										$UP_DATE				= trim($arr_rs[$j]["UP_DATE"]);
										$UP_ADM					= trim($arr_rs[$j]["UP_ADM"]);
										
										if ($USE_TF == "Y") {
											$STR_USE_TF = "<font color='navy'>사용중</font>";
										} else {
											$STR_USE_TF = "<font color='red'>사용안함</font>";
										}

										//$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
										$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;

							?>
								<tr>
									<td><?=$rn?></td>
									<td><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?=$BLOCK_IP?></a></td>
									<td><?= $MEMO?></td>
									<td><?= $REG_DATE ?></td>
									<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$USE_TF?>');"><?= $STR_USE_TF ?></a></td>
								</tr>

							<?
									}
								} else { 
							?> 
								<tr>
									<td colspan="5">
										<div class="nodata">검색 결과가 없습니다.</div>
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
