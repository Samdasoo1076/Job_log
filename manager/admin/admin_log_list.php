<? session_start(); ?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
error_reporting(E_ALL);
	ini_set("display_errors", 1);
# =============================================================================
# File Name    : admin_log_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-20
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
	$menu_right = "AD005"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

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
	require "../../_classes/biz/admin/admin.php";

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$adm_no					= isset($_POST["adm_no"]) && $_POST["adm_no"] !== '' ? $_POST["adm_no"] : (isset($_GET["adm_no"]) ? $_GET["adm_no"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$start_date			= isset($_POST["start_date"]) && $_POST["start_date"] !== '' ? $_POST["start_date"] : (isset($_GET["start_date"]) ? $_GET["start_date"] : '');
	$end_date				= isset($_POST["end_date"]) && $_POST["end_date"] !== '' ? $_POST["end_date"] : (isset($_GET["end_date"]) ? $_GET["end_date"] : '');

	$task_type			= isset($_POST["task_type"]) && $_POST["task_type"] !== '' ? $_POST["task_type"] : (isset($_GET["task_type"]) ? $_GET["task_type"] : '');

	if ($start_date == "") {
		$start_date = date("Y-m-d",strtotime("-1 month"));
	} else {
		$start_date = trim($start_date);
	}

	if ($end_date == "") {
		$end_date = date("Y-m-d",strtotime("0 month"));
	} else {
		$end_date = trim($end_date);
	}

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage				= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "" && $nPageSize <> 0) {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntAdminLog($conn, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}


	#$del_tf = "Y";

	$arr_rs = listAdminLog($conn, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	#echo sizeof($arr_rs);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 접속 로그 리스트 조회", "List");

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

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "admin_log_list.php";
		frm.submit();
	}
	

	function js_excel_print() {

		var frm = document.frm;
		frm.method = "post";
		frm.action = "admin_log_excel_list.php";
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

		<form id="bbsList" name="frm" method="post">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:14%" />
								<col style="width:28%" />
								<col style="width:14%" />
								<col />
							</colgroup>
							<tbody>
								<tr>
									<th>조회기간</th>
									<td colspan="3">
										<div class="datepickerbox" style="width:150px">
											<input type="text" name="start_date" id="start_date" value="<?=$start_date?>" class="datepicker" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10" autocomplete="off"/>
										</div>
										<span class="tbl_txt"> ~ </span> 
										<div class="datepickerbox" style="width:150px">
											<input type="text" name="end_date" id="end_date" value="<?=$end_date?>" class="datepicker" onfocus="OnCheckDate(this)" onKeyup="OnCheckDate(this)" maxlength="10" autocomplete="off"/>
										</div>
									</td>
								</tr>
								<tr>
									<th>업무구분</th>
									<td>
										<select name="task_type">
											<option value="">전체</option>
											<option value="Login" <? if ($task_type == "Login") echo "selected"; ?>>로그인</option>
											<option value="Logout" <? if ($task_type == "Logout") echo "selected"; ?>>로그아웃</option>
											<option value="List" <? if ($task_type == "List") echo "selected"; ?>>리스트조회</option>
											<option value="Excel" <? if ($task_type == "Excel") echo "selected"; ?>>엑셀조회</option>
											<option value="Read" <? if ($task_type == "Read") echo "selected"; ?>>조회</option>
											<option value="Insert" <? if ($task_type == "Insert") echo "selected"; ?>>등록</option>
											<option value="Update" <? if ($task_type == "Update") echo "selected"; ?>>수정</option>
											<option value="Delete" <? if ($task_type == "Delete") echo "selected"; ?>>삭제</option>
										</select>
									</td>
									<th>검색조건</th>
									<td>
										<select name="search_field">
											<option value="LOG_ID" <? if ($search_field == "LOG_ID") echo "selected"; ?> >아이디</option>
											<option value="LOG_IP" <? if ($search_field == "LOG_IP") echo "selected"; ?> >아이피</option>
											<option value="TASK" <? if ($search_field == "TASK") echo "selected"; ?> >업무내용</option>
										</select>
										<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
										<button type="button" class="button type03" onClick="js_search();">검색</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span></div>
					</div>

					<div class="tbl_style01">
						<table>
							<colgroup>
								<col width="6%" />
								<col width="10%" />
								<col width="10%" />
								<col width="10%" />
								<col width="38%" />
								<col width="12%" />
								<col width="14%" />
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>ID</th>
									<th>관리자</th>
									<th>업무구분</th>
									<th>업무내용</th>
									<th>접속아이피</th>
									<th>처리일시</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										
										//ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
										//GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										//GROUP_NAME

										$LOG_ID				= trim($arr_rs[$j]["LOG_ID"]);
										$LOG_IP				= trim($arr_rs[$j]["LOG_IP"]);
										$TASK					= trim($arr_rs[$j]["TASK"]);
										$TASK_TYPE		= trim($arr_rs[$j]["TASK_TYPE"]);
										$LOGIN_DATE		= trim($arr_rs[$j]["LOGIN_DATE"]);
										$ADM_NAME			= trim($arr_rs[$j]["ADM_NAME"]);

										$LOGIN_DATE = date("Y-m-d H:i:s",strtotime($LOGIN_DATE));
										
										$offset = $nPageSize * ($nPage-1);
										$logical_num = ($nListCnt - $offset);
										$rn = $logical_num - $j;
							?>

									<tr>
										<td><?=$rn?></td>
										<td><?= $LOG_ID ?></td>
										<td><?= $ADM_NAME ?></td>
										<td><?= $TASK_TYPE ?></td>
										<td style="text-align:left;padding-left:10px"><?= $TASK ?></td>
										<td><?= $LOG_IP ?></td>
										<td><?= $LOGIN_DATE ?></td>
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
								$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&start_date=".$start_date."&end_date=".$end_date."&task_type=".$task_type;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>
						</div>
						<div class="right">
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_excel_print();">선택한 항목 엑셀로 받기</button>
							<? } ?>
						</div>
					</div>
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
