<? session_start(); ?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
error_reporting(E_ALL);
	ini_set("display_errors", 1);
# =============================================================================
# File Name    : sms_log_list.php
# Modlue       : 
# Writer       : Lee Ji Min 
# Create Date  : 2024-12-23
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
	$menu_right = "AD007"; // 메뉴마다 셋팅 해 주어야 합니다

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

	$nListCnt =totalCntSmsLog($conn, $start_date, $end_date, $task_type, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}


	#$del_tf = "Y";

	$arr_rs = listSMSLog($conn, $start_date, $end_date, $task_type, $search_field, $search_str, $nPage, $nPageSize);

	#echo sizeof($arr_rs);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "SMS 로그 리스트 조회", "List");
	
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
		frm.action = "sms_log_list.php";
		frm.submit();
	}
	

	function js_excel_print() {

		var frm = document.frm;
		frm.method = "post";
		frm.action = "sms_log_excel_list.php";
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
									<th>발송구분</th>
									<td>
										<select name="task_type">
											<option value="">전체</option>
											<option value="0" <? if ($task_type == "0") echo "selected"; ?>>아이디 찾기</option>
											<option value="1" <? if ($task_type == "1") echo "selected"; ?>>비밀번호 찾기</option>
											<option value="2" <? if ($task_type == "2") echo "selected"; ?>>회원가입</option>
											<option value="3" <? if ($task_type == "3") echo "selected"; ?>>시설 예약</option>
											<option value="4" <? if ($task_type == "4") echo "selected"; ?>>정보 수정</option>
										</select>
									</td>
									<th>검색조건</th>
									<td>
										<select name="search_field">
											<option value="RPHONE" <? if ($search_field == "LOG_ID") echo "selected"; ?> >전화번호</option>
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
                            <col width="3%" />
                            <col width="7%" />
                            <col width="46%" />
                            <col width="8%" />
                            <col width="10%" />
                            <col width="12%" />
                            <col width="14%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>전화번호</th>
                                <th>메시지</th>
                                <th>발송 구분</th>
                                <th>결과</th>
                                <th>에러 구분</th>
                                <th>전송일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if (sizeof($arr_rs) > 0): ?>
                                <? foreach ($arr_rs as $row): ?>
                                    <tr>
                                        <td><?= $row['SEQ_NO'] ?></td>
                                        <td><?= htmlspecialchars($row['RPHONE']) ?></td>
                                        <td><?= htmlspecialchars($row['MSG']) ?></td>
                                        <td><?
												if ($row['TASK'] == 0) {
													echo "아이디 찾기";
												} elseif ($row['TASK'] == 1) {
													echo htmlspecialchars("비밀번호 찾기");
												} elseif ($row['TASK'] == 2) {
													echo htmlspecialchars("회원가입");
												} elseif ($row['TASK'] == 3) {
													echo htmlspecialchars("시설 예약");
												} elseif ($row['TASK'] == 4) {
													echo htmlspecialchars("내 정보 수정");

												} else {
													echo htmlspecialchars("알 수 없음"); // 기본값
												}
											?></td>
                                        <td><?if ($row['SEND_RESULT'] == "T") { ?>
											 성공 
                                       <? }  else { ?>
											실패
                                        <? } ?>  </td>
                                        <td><?= $row['ERR'] ?></td>
                                        <td><?= $row['SEND_DATE'] ?></td>
                                    </tr>
                                <? endforeach; ?>
                            <? else: ?>
                                <tr>
                                    <td colspan="7">검색 결과가 없습니다.</td>
                                </tr>
                            <? endif; ?>
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
