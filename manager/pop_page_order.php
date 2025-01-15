<?session_start();?>
<?
# =============================================================================
# File Name    : pop_page_order.php
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

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

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
	require "../_classes/biz/page/page.php";

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

	$m_level					= isset($_POST["m_level"]) && $_POST["m_level"] !== '' ? $_POST["m_level"] : (isset($_GET["m_level"]) ? $_GET["m_level"] : '');
	$sel_page_lang		= isset($_POST["sel_page_lang"]) && $_POST["sel_page_lang"] !== '' ? $_POST["sel_page_lang"] : (isset($_GET["sel_page_lang"]) ? $_GET["sel_page_lang"] : '');
	$menu_tf					= isset($_POST["menu_tf"]) && $_POST["menu_tf"] !== '' ? $_POST["menu_tf"] : (isset($_GET["menu_tf"]) ? $_GET["menu_tf"] : '');

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

	$m_level = SetStringToDB($m_level);

	if (strlen($m_level) == 0) {
		$level_str = "1 뎁스 메뉴";
	} else if (strlen($m_level) == 2) {
		$level_str = "2 뎁스 메뉴";
	} else if (strlen($m_level) == 4) {
		$level_str = "3 뎁스 메뉴";
	} else if (strlen($m_level) == 6) {
		$level_str = "4 뎁스 메뉴";
	} else if (strlen($m_level) == 8) {
		$level_str = "5 뎁스 메뉴";
	}

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Get Result set from stored procedure
#====================================================================

	$del_tf = "N";
	$arr_rs = listPage($conn, $sel_page_lang, $menu_tf, $use_tf, $del_tf, $search_field, $search_str);
	//$arr_rs = listPage($conn, $sel_page_lang, $use_tf, $del_tf, $search_field, $search_str);

?>
<!DOCTYPE HTML>
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
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 300px; }
-->
</style>
<script type="text/javascript" >

/*
 * @(#)menu.js
 * 
 * 페이지설명 : 메뉴 순서 바꾸기 스크립트 파일
 * 작성  일자 : 2003.12.01
 */  


var preid = -1;

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		
		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 0) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.page_no[preid-2].value;
			
				document.frm.page_no[preid-2].value = document.frm.page_no[preid-1].value;
				document.frm.page_no[preid-1].value = tempCode;
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

	if (preid < document.getElementById("t").rows.length-1) {
		
		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 0) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.page_no[preid-1].value;
				document.frm.page_no[preid-1].value = document.frm.page_no[preid].value;
				document.frm.page_no[preid].value = tempCode;
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
	document.frm.action = "pop_page_order_dml.php";
	document.frm.submit();

}


</script>
</head>
<body id="popup">

<div class="popupwrap">
	<h1>사용자 메뉴 순서 변경</h1>
	<div class="popcontents">

<form name="frm" method="post" action="javascript:check_data();">
<input type="hidden" name='mode' value='add'>
<input type="hidden" name='m_level' value='<?=$m_level?>'>
<input type="hidden" name='sel_page_lang' value='<?=$sel_page_lang?>'>

		<div id="postsch_code">
			<div class="tbl_style01 left">

				<table>
					<colgroup>
						<col width="20%">
						<col width="80%">
					</colgroup>
					<thead>
						<tr>
							<th>메뉴분류</th>
							<td>
								<?=$level_str?>
							</td>
						</tr>
					</thead>
				</table>
			</div>
			<br>
			<div id="pop_table_scroll">
				<div class="tbl_style01 left">
					<table id='t'>
						<colgroup>
							<col width="15%" />
							<col width="30%" />
							<col width="55%" />
						</colgroup>
						<thead>
							<tr>
								<th>번호</th>
								<th>메뉴명</th>
								<th class="end">메뉴URL</th>
							</tr>
						</thead>
						<tbody>
				<?
					$nCnt = 0;
					
					$sPage_no = "";

					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							$PAGE_NO			= trim($arr_rs[$j]["PAGE_NO"]);
							$PAGE_CD			= trim($arr_rs[$j]["PAGE_CD"]);
							$PAGE_NAME		= trim($arr_rs[$j]["PAGE_NAME"]);
							$PAGE_URL			= trim($arr_rs[$j]["PAGE_URL"]);
							$PAGE_FLAG		= trim($arr_rs[$j]["PAGE_FLAG"]);
							$PAGE_SEQ01		= trim($arr_rs[$j]["PAGE_SEQ01"]);
							$PAGE_SEQ02		= trim($arr_rs[$j]["PAGE_SEQ02"]);
							$PAGE_SEQ03		= trim($arr_rs[$j]["PAGE_SEQ03"]);
							$PAGE_SEQ04		= trim($arr_rs[$j]["PAGE_SEQ04"]);
							$PAGE_SEQ05		= trim($arr_rs[$j]["PAGE_SEQ05"]);
							$PAGE_RIGHT		= trim($arr_rs[$j]["PAGE_RIGHT"]);
							$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
							//$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);

							//$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							
							if (strlen($m_level) == 0) {

								if (strlen(Trim($PAGE_CD)) == 2) {
									
									for ($j_sub = 0; $j_sub < sizeof($arr_rs) ; $j_sub++) {
										
										$SUB_PAGE_NO	= trim($arr_rs[$j_sub]["PAGE_NO"]);
										$SUB_PAGE_CD	= trim($arr_rs[$j_sub]["PAGE_CD"]);

										if (Trim($PAGE_CD) == substr(Trim($SUB_PAGE_CD),0,2)) {
											$sPage_no = $sPage_no ."^". $SUB_PAGE_NO;
										}
									}
					

				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$PAGE_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($PAGE_NO)?>">
										<input type="hidden" name="cat_id[]" value="">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$PAGE_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}

							#중 분류인 경우
							if (strlen($m_level) == 2) {

								if ((substr($m_level,0,2) == substr(Trim($PAGE_CD),0,2)) && (strlen(Trim($PAGE_CD)) == 4)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_PAGE_NO	= trim($arr_rs[$j_sub]["PAGE_NO"]);
										$SUB_PAGE_CD	= trim($arr_rs[$j_sub]["PAGE_CD"]);

										if (Trim($PAGE_CD) == substr(Trim($SUB_PAGE_CD),0,4)) {
											$sPage_no = $sPage_no ."^". $SUB_PAGE_NO;
										}
									}
				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$PAGE_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($PAGE_NO)?>">
										<input type="hidden" name="cat_id[]" value="">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$PAGE_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}
						
							#세부 분류인 경우

							if (strlen($m_level) == 4) {

								if ((substr($m_level,0,4) == substr(Trim($PAGE_CD),0,4)) && (strlen(Trim($PAGE_CD)) == 6)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_PAGE_NO	= trim($arr_rs[$j_sub]["PAGE_NO"]);
										$SUB_PAGE_CD	= trim($arr_rs[$j_sub]["PAGE_CD"]);
										
										if (Trim($PAGE_CD) == substr(Trim($SUB_PAGE_CD),0,6)) {
											
											$sPage_no = $sPage_no ."^". $SUB_PAGE_NO;

										}
									}

				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$PAGE_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($PAGE_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($PAGE_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$PAGE_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}
						

							if (strlen($m_level) == 6) {

								if ((substr($m_level,0,6) == substr(Trim($PAGE_CD),0,6)) && (strlen(Trim($PAGE_CD)) == 8)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_PAGE_NO	= trim($arr_rs[$j_sub]["PAGE_NO"]);
										$SUB_PAGE_CD	= trim($arr_rs[$j_sub]["PAGE_CD"]);
										
										if (Trim($PAGE_CD) == substr(Trim($SUB_PAGE_CD),0,8)) {
											
											$sPage_no = $sPage_no ."^". $SUB_PAGE_NO;

										}
									}

				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="/manager/images/common/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$PAGE_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($PAGE_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($PAGE_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$PAGE_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}



						}
					} else {
				?>
								<tr align="center" bgcolor="#FFFFFF">
									<td height="25" colspan="12">등록 목록이 없습니다.<!--한국어 등록 목록이 없습니다.--></td>
								</tr>
				<?   
					}  
				?>
						</tbody>
					</table>
				</div>
			</div>
		
		</div>
	</div>
</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>