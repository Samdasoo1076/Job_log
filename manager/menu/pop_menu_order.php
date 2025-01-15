<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : pop_menu_order.php
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

	$m_level					= isset($_POST["m_level"]) && $_POST["m_level"] !== '' ? $_POST["m_level"] : (isset($_GET["m_level"]) ? $_GET["m_level"] : '');

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
		$level_str = "대분류 메뉴";
	} else if (strlen($m_level) == 2) {
		$level_str = "중분류 메뉴";
	} else if (strlen($m_level) == 4) {
		$level_str = "소분류 메뉴";
	}

?>
<?

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Get Result set from stored procedure
#====================================================================

	$del_tf = "N";

	$arr_rs = listAdminMenu($conn, $use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 순서 조회", "List");

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="shortcut icon" href="/assets/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 300px; }
-->
</style>

<script type="text/javascript" >

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

				var tempCode = document.frm.menu_no[preid-2].value;
			
				document.frm.menu_no[preid-2].value = document.frm.menu_no[preid-1].value;
				document.frm.menu_no[preid-1].value = tempCode;
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
	
				var tempCode = document.frm.menu_no[preid-1].value;
				document.frm.menu_no[preid-1].value = document.frm.menu_no[preid].value;
				document.frm.menu_no[preid].value = tempCode;
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
	document.frm.action = "pop_menu_order_dml.php";
	document.frm.submit();

}


</script>

</head>
<body id="popup">

<div class="popupwrap">
	<h1>관리자 메뉴 순서 변경</h1>
	<div class="popcontents">

<form name="frm" method="post" action="javascript:check_data();">
<input type=hidden name='mode' value='add'>
<input type=hidden name='m_level' value='<?=$m_level?>'>

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
					
					$sMenu_no = "";

					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							//SEQ, MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
							
							$MENU_SEQ			= trim($arr_rs[$j]["SEQ"]);
							$MENU_NO			= trim($arr_rs[$j]["MENU_NO"]);
							$MENU_CD			= trim($arr_rs[$j]["MENU_CD"]);
							$MENU_NAME		= trim($arr_rs[$j]["MENU_NAME"]);
							$MENU_URL			= trim($arr_rs[$j]["MENU_URL"]);
							$MENU_FLAG		= trim($arr_rs[$j]["MENU_FLAG"]);
							$MENU_SEQ01		= trim($arr_rs[$j]["MENU_SEQ01"]);
							$MENU_SEQ02		= trim($arr_rs[$j]["MENU_SEQ02"]);
							$MENU_SEQ03		= trim($arr_rs[$j]["MENU_SEQ03"]);
							$MENU_RIGHT		= trim($arr_rs[$j]["MENU_RIGHT"]);
							//$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
							//$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
							//$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);

							//$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							
							if (strlen($m_level) == 0) {

								if (strlen(Trim($MENU_CD)) == 2) {
									
									for ($j_sub = 0; $j_sub < sizeof($arr_rs) ; $j_sub++) {
										
										$SUB_MENU_NO	= trim($arr_rs[$j_sub]["MENU_NO"]);
										$SUB_MENU_CD	= trim($arr_rs[$j_sub]["MENU_CD"]);

										if (Trim($MENU_CD) == substr(Trim($SUB_MENU_CD),0,2)) {
											$sMenu_no = $sMenu_no ."^". $SUB_MENU_NO;
										}
									}
					

				?>
							<tr>
								<td>
									<span><?=($nCnt++ + 1)?></span>
									<a href="javascript:js_up('<?=($nCnt)?>');"><img src="../images/common/icon_arr_top.gif" alt="" /></a> 
									<a href="javascript:js_down('<?=($nCnt)?>');"><img src="../images/common/icon_arr_bot.gif" alt="" /></a>
								</td>
								<td>
									<?=$MENU_NAME?>
									<input type="hidden" name="catid[]" value="<?=trim($MENU_NO)?>">
									<input type="hidden" name="cat_id[]" value="<?=trim($MENU_SEQ)?>">
									<input type="hidden" name="arr_menu_no[]" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
									<input type="hidden" name="menu_no" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
								</td>
								<td><?=$MENU_URL?></td>
							</tr>
				
				<?
									$nCnt = $nCnt++;
									$sMenu_no = "";
								}
							}

							#중 분류인 경우
							if (strlen($m_level) == 2) {

								if ((substr($m_level,0,2) == substr(Trim($MENU_CD),0,2)) && (strlen(Trim($MENU_CD)) == 4)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_MENU_NO	= trim($arr_rs[$j_sub]["MENU_NO"]);
										$SUB_MENU_CD	= trim($arr_rs[$j_sub]["MENU_CD"]);

										if (Trim($MENU_CD) == substr(Trim($SUB_MENU_CD),0,4)) {
											$sMenu_no = $sMenu_no ."^". $SUB_MENU_NO;
										}
									}
				?>
							<tr>
								<td>
									<span><?=($nCnt++ + 1)?></span>
									<a href="javascript:js_up('<?=($nCnt)?>');"><img src="../images/common/icon_arr_top.gif" alt="" /></a> 
									<a href="javascript:js_down('<?=($nCnt)?>');"><img src="../images/common/icon_arr_bot.gif" alt="" /></a>
								</td>
								<td>
									<?=$MENU_NAME?>
									<input type="hidden" name="catid[]" value="<?=trim($MENU_NO)?>">
									<input type="hidden" name="cat_id[]" value="<?=trim($MENU_SEQ)?>">
									<input type="hidden" name="arr_menu_no[]" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
									<input type="hidden" name="menu_no" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
								</td>
								<td><?=$MENU_URL?></td>
							</tr>
				
				<?
									$nCnt = $nCnt++;
									$sMenu_no = "";
								}
							}
						
							#세부 분류인 경우

							if (strlen($m_level) == 4) {

								if ((substr($m_level,0,4) == substr(Trim($MENU_CD),0,4)) && (strlen(Trim($MENU_CD)) == 6)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_MENU_NO	= trim($arr_rs[$j_sub]["MENU_NO"]);
										$SUB_MENU_CD	= trim($arr_rs[$j_sub]["MENU_CD"]);
										
										if (Trim($MENU_CD) == substr(Trim($SUB_MENU_CD),0,6)) {
											
											$sMenu_no = $sMenu_no ."^". $SUB_MENU_NO;

										}
									}

				?>
							<tr>
								<td>
									<span><?=($nCnt++ + 1)?></span>
									<a href="javascript:js_up('<?=($nCnt)?>');"><img src="../images/common/icon_arr_top.gif" alt="" /></a> 
									<a href="javascript:js_down('<?=($nCnt)?>');"><img src="../images/common/icon_arr_bot.gif" alt="" /></a>
								</td>
								<td>
									<?=$MENU_NAME?>
									<input type="hidden" name="catid[]" value="<?=trim($MENU_NO)?>">
									<input type="hidden" name="cat_id[]" value="<?=trim($MENU_SEQ)?>">
									<input type="hidden" name="arr_menu_no[]" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
									<input type="hidden" name="menu_no" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
								</td>
								<td><?=$MENU_URL?></td>
							</tr>
				
				<?
									$nCnt = $nCnt++;
									$sMenu_no = "";
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