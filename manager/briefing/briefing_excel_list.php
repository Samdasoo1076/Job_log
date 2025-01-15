<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
session_start();

header("Content-Type: text/html; charset=UTF-8");
header("x-xss-Protection:0");

# =============================================================================
# File Name    : briefing_excel_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2023-08-16
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
	$menu_right = "HI004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/briefing/briefing.php";

	$str_title = iconv("UTF-8","EUC-KR","입학설명회 신청자 리스트");

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

	$nListCnt =totalCntBriefing($conn, $con_app_area, $con_app_flag, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBriefing($conn, $con_app_area, $con_app_flag, $con_use_tf, $con_del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], $rs_title." 입학설명회 신청자 리스트 엑셀 출력", "Excel");

?>
<html>
<style>
	br {mso-data-placement:same-cell;}
	/*
	td {mso-number-format:\@;}
	*/
</style>
<body>
<font size=3><b><?=$rs_title?>입학설명회 신청자 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>

<table border="1">
	<tr>
		<th>NO</th>
		<th>지역</th>
		<th>학교명</th>
		<th>직책</th>
		<th>담당자명</th>
		<th>휴대전화</th>
		<th>E-Mail</th>
		<th>운영방식</th>
		<th>비대면희망여부</th>
		<th>교사간담회희망여부</th>
		<th>1차 희망 일시</th>
		<th>2차 희망 일시</th>
		<th>입학사정관 방문장소</th>
		<th>설명회 장소</th>
		<th>인원 : 학생</th>
		<th>인원 : 학부모</th>
		<th>인원 : 교사</th>
		<th>기기설치 여부</th>
		<th>관심 여부</th>
		<th>특이사항</th>
		<th>등록일</th>
		<th>상태</th>
	</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

				$rn									= trim($arr_rs[$j]["rn"]);
				$APPLY_NO						= trim($arr_rs[$j]["APPLY_NO"]);
				$APPLY_AREA					= trim($arr_rs[$j]["APPLY_AREA"]);
				$APPLY_SCHOOL				= trim($arr_rs[$j]["APPLY_SCHOOL"]);
				$SCH_CODE						= trim($arr_rs[$j]["SCH_CODE"]);
				$SCH_AREA						= trim($arr_rs[$j]["SCH_AREA"]);
				$APPLY_POSITION			= trim($arr_rs[$j]["APPLY_POSITION"]);
				$APPLY_POSITION_ETC	= trim($arr_rs[$j]["APPLY_POSITION_ETC"]);
				$APPLY_NAME					= trim($arr_rs[$j]["APPLY_NAME"]);
				$APPLY_HPHONE				= trim($arr_rs[$j]["APPLY_HPHONE"]);
				$APPLY_EMAIL				= trim($arr_rs[$j]["APPLY_EMAIL"]);
				$APPLY_TYPE					= trim($arr_rs[$j]["APPLY_TYPE"]);
				$APPLY_TYPE_ONLINE	= trim($arr_rs[$j]["APPLY_TYPE_ONLINE"]);
				$APPLY_TYPE_TEACHER	= trim($arr_rs[$j]["APPLY_TYPE_TEACHER"]);
				$APPLY_DATE_01			= trim($arr_rs[$j]["APPLY_DATE_01"]);
				$APPLY_TIME_01			= trim($arr_rs[$j]["APPLY_TIME_01"]);
				$APPLY_DATE_02			= trim($arr_rs[$j]["APPLY_DATE_02"]);
				$APPLY_TIME_02			= trim($arr_rs[$j]["APPLY_TIME_02"]);
				$APPLY_APPOINTMENT	= trim($arr_rs[$j]["APPLY_APPOINTMENT"]);
				$APPLY_PLACE				= trim($arr_rs[$j]["APPLY_PLACE"]);
				$STU_CNT						= trim($arr_rs[$j]["STU_CNT"]);
				$PARENT_CNT					= trim($arr_rs[$j]["PARENT_CNT"]);
				$TEACHER_CNT				= trim($arr_rs[$j]["TEACHER_CNT"]);
				$APPLY_EQUIPMENT		= trim($arr_rs[$j]["APPLY_EQUIPMENT"]);
				$APPLY_INTEREST			= trim($arr_rs[$j]["APPLY_INTEREST"]);
				$APPLY_MEMO					= trim($arr_rs[$j]["APPLY_MEMO"]);
				$APPLY_ADMIN_MEMO		= trim($arr_rs[$j]["APPLY_ADMIN_MEMO"]);
				$TEACHER_CNT				= trim($arr_rs[$j]["TEACHER_CNT"]);
				$APPLY_PASSWD				= trim($arr_rs[$j]["APPLY_PASSWD"]);
				$APP_FLAG						= trim($arr_rs[$j]["APP_FLAG"]);
				$REG_DATE						= trim($arr_rs[$j]["REG_DATE"]);
				$UP_DATE						= trim($arr_rs[$j]["UP_DATE"]);

				$offset = $nPageSize * ($nPage-1);
				$logical_num = ($nListCnt - $offset);
				$rn = $logical_num - $j;

				//$str_app_tf = $APPLY_TF=="Y" ?"참석":"불참";
				$str_app_flag = $APP_FLAG==0 ?"접수":"<font color='blue'>확정</font>";

				$str_app_type_online	= $APPLY_TYPE_ONLINE="Y" ? "가능" : "불가";
				$str_app_type_teacher = $APPLY_TYPE_TEACHER="Y" ? "신청" : "미신청";

				if ($APPLY_POSITION == "기타") $APPLY_POSITION = $APPLY_POSITION_ETC;

	?>

	<tr>
		<td><?=$rn?></td>
		<td><?=$SCH_AREA?></td>
		<td><?=$APPLY_SCHOOL?></td>
		<td><?=$APPLY_POSITION?></td>
		<td><?=$APPLY_NAME?></td>
		<td><?=$APPLY_HPHONE?></td>
		<td><?=$APPLY_EMAIL?></td>
		<td><?=$APPLY_TYPE?></td>
		<td><?=$str_app_type_online?></td>
		<td><?=$str_app_type_teacher?></td>
		<td><?=$APPLY_DATE_01?> <?=$APPLY_TIME_01?></td>
		<td><?=$APPLY_DATE_02?> <?=$APPLY_TIME_02?></td>
		<td><?=$APPLY_APPOINTMENT?></td>
		<td><?=$APPLY_PLACE?></td>
		<td><?=$STU_CNT?></td>
		<td><?=$PARENT_CNT?></td>
		<td><?=$TEACHER_CNT?></td>
		<td><?=$APPLY_EQUIPMENT?></td>
		<td><?=$APPLY_INTEREST?></td>
		<td><?=nl2br($APPLY_MEMO)?></td>
		<td><?=$REG_DATE?></td>
		<td><?=$str_app_flag?></td>
	</tr>

	<?			
			}
		} else { 
	?> 
		<tr>
			<td colspan="22">
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
