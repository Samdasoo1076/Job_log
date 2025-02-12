<?session_start();?>
<?
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("x-xss-Protection:0");
# =============================================================================
# File Name    : admin_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-06-10
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

	$str_title = "SMS 발송 로그 리스트";

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: myucheu0617@gmail.com" );

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
	$nPage			= SetStringToDB($nPage);
	$nPageSize	= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

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
		$nPageSize = 20000;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntSmsLog($conn, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listSMSLog($conn, $start_date, $end_date, $task_type, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "SMS 발송 로그 엑셀 조회", "Excel");
	#echo sizeof($arr_rs);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?=$g_title?></title>
</head>
<body>
<table style='border-collapse:collapse;table-layout:fixed;width:260pt' width=480>
	<tr>
		<td>
<font size=3><b>SMS 발송 로그 리스트</b></font> <br>
<!-- <br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br> -->
<br>
<table border="1">
	<tr>
		<th style=" width: 10px;">발송 번호</th>
		<th>전화번호</th>
		<th>메세지</th>
		<th>발송구분</th>
		<th>결과</th>
		<th>에러 구분</th>
		<th>전송일</th>
	</tr>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							// `SEQ_NO` int(11) NOT NULL AUTO_INCREMENT, 시퀀스 번호
							// `RPHONE` varchar(15) DEFAULT NULL, 발송된 전화번호
							// `MSG` varchar(2000) DEFAULT NULL, 메세지
							// `TASK` varchar(1000) DEFAULT NULL, 발송 구분
							// `SEND_RESULT` varchar(1000) DEFAULT NULL, 결과
							// `ERR` varchar(1000) DEFAULT NULL, 에러
							// `SEND_DATE` datetime DEFAULT NULL, 전송 날짜

							$SEQ_NO				= trim($arr_rs[$j]["SEQ_NO"]);
							$RPHONE				= trim($arr_rs[$j]["RPHONE"]);
							$MSG					= trim($arr_rs[$j]["MSG"]);
							$TASK		= trim($arr_rs[$j]["TASK"]);
							$SEND_RESULT		= trim($arr_rs[$j]["SEND_RESULT"]);
							$ERR		= trim($arr_rs[$j]["ERR"]);
							$SEND_DATE		= trim($arr_rs[$j]["SEND_DATE"]);
					
							$LOGIN_DATE = date("Y-m-d H:i:s",strtotime($LOGIN_DATE));
							
							$offset = $nPageSize * ($nPage-1);
							$logical_num = ($nListCnt - $offset);
							$rn = $logical_num - $j;
				?>

						<tr>
							<td style="height:22px"><?=$SEQ_NO?></td>
							<td><?= $RPHONE ?></td>
							<td><?= $MSG ?></td>
							<td>
							<? if($TASK == 0) {
									echo "아이디 찾기";
							} elseif($TASK == 1) {
								echo "비밀번호 찾기";
							} elseif($TASK == 2) {
								echo "회원가입";
							} elseif($TASK == 3) {
								echo "시설예약";
							} elseif($TASK == 4) {
								echo "내 정보수정";
							} ?>
							</td>
							<td style="text-align:left;padding-left:10px">
							<? if($SEND_RESULT) {
								echo "성공";
							} else {
								echo "실패";
							}?></td>
							<td><?= $ERR ?></td>
							<td><?= $SEND_DATE ?></td>
						</tr>

				<?			
						}
					} else { 
				?> 
						<tr>
							<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
						</tr>
				<? 
					}
				?>

</table>
		</td>
	</tr>
</table>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>