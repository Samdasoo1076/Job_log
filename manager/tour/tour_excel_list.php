<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
session_start();

header("Content-Type: text/html; charset=UTF-8");
header("x-xss-Protection:0");

# =============================================================================
# File Name    : tour_excel_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2023-05-05
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
	$menu_right = "HI003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/tour/tour.php";

	$str_title = iconv("UTF-8","EUC-KR","캠퍼스 투어 신청자 리스트");

	$file_name=$str_title."-".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );

#====================================================================
# Request Parameter
#====================================================================

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$con_app_flag				= $_POST['con_app_flag']!=''?$_POST['con_app_flag']:$_GET['con_app_flag'];
	$app_flag_value			= $_POST['app_flag_value']!=''?$_POST['app_flag_value']:$_GET['app_flag_value'];
	
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$order_field				= $_POST['order_field']!=''?$_POST['order_field']:$_GET['order_field'];
	$order_str					= $_POST['order_str']!=''?$_POST['order_str']:$_GET['order_str'];

	$chk								= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

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
	
	$con_del_tf = "N";
#============================================================
# Page process
#============================================================

	$nPage = 1;
	$nPageSize = 100;
	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntTour($conn, $con_app_flag, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listTour($conn, $con_app_flag, $con_use_tf, $con_del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], $rs_title." 캠퍼스 투어 신청자 리스트 엑셀 출력", "Excel");

?>
<html>
<style>
	br {mso-data-placement:same-cell;}
	/*
	td {mso-number-format:\@;}
	*/
</style>
<body>
<font size=3><b><?=$rs_title?>캠퍼스 투어 신청자 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>

<table border="1">
	<tr>
		<th>NO</th>
		<th>신청고교명</th>
		<th>대표(교사)명</th>
		<th>참석여부</th>
		<th>신청일자</th>
		<th>학년</th>
		<th>인원</th>
		<th>교통편</th>
		<th>휴대전화</th>
		<th>E-Mail</th>
		<th>등록일</th>
		<th>상태</th>
	</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

				$rn								= trim($arr_rs[$j]["rn"]);
				$APPLY_NO					= trim($arr_rs[$j]["APPLY_NO"]);
				$APPLY_SCHOOL			= trim($arr_rs[$j]["APPLY_SCHOOL"]);
				$APPLY_NAME				= trim($arr_rs[$j]["APPLY_NAME"]);
				$APPLY_TF					= trim($arr_rs[$j]["APPLY_TF"]);
				$APPLY_DATE				= trim($arr_rs[$j]["APPLY_DATE"]);
				$APPLY_TIME				= trim($arr_rs[$j]["APPLY_TIME"]);
				$APPLY_GRADE			= trim($arr_rs[$j]["APPLY_GRADE"]);
				$APPLY_CNT				= trim($arr_rs[$j]["APPLY_CNT"]);
				$APPLY_TRANS			= trim($arr_rs[$j]["APPLY_TRANS"]);
				$APPLY_HPHONE			= trim($arr_rs[$j]["APPLY_HPHONE"]);
				$APPLY_EMAIL			= trim($arr_rs[$j]["APPLY_EMAIL"]);
				$APPLY_MEMO				= trim($arr_rs[$j]["APPLY_MEMO"]);
				$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
				$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);
				$APP_FLAG					= trim($arr_rs[$j]["APP_FLAG"]);

				$offset = $nPageSize * ($nPage-1);
				$logical_num = ($nListCnt - $offset);
				$rn = $logical_num - $j;

				$str_app_tf = $APPLY_TF=="Y" ?"참석":"불참";
				$str_app_flag = $APP_FLAG==0 ?"접수":"<font color='blue'>확정</font>";

	?>

	<tr>
		<td><?=$rn?></td>
		<td><?=$APPLY_SCHOOL?></td>
		<td><?=$APPLY_NAME?></td>
		<td><?=$str_app_tf?></td>
		<td><?=$APPLY_DATE?> <?=$APPLY_TIME?></td>
		<td><?=$APPLY_GRADE?></td>
		<td><?=$APPLY_CNT?></td>
		<td><?=$APPLY_TRANS?></td>
		<td><?=$APPLY_HPHONE?></td>
		<td><?=$APPLY_EMAIL?></td>
		<td><?=$REG_DATE?></td>
		<td><?=$str_app_flag?></td>
	</tr>

	<?			
			}
		} else { 
	?> 
		<tr>
			<td colspan="13">
				검색 결과가 없습니다. 다시 검색해주세요.
			</td>
		</tr>
	<? 
		}
	?>

</table>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
