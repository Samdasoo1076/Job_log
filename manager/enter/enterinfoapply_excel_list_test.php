<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
session_start();

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("x-xss-Protection:0");

# =============================================================================
# File Name    : enterinfoapply_excel_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-26
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
	$menu_right = "EN002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/enter/enter.php";

	$str_title = iconv("UTF-8","UTF-8","입학자료 신청 리스트");

	$file_name=$str_title."-".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel; charset=UTF-8" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );

#====================================================================
# Request Parameter
#====================================================================

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ean_no						= $_POST['ean_no']!=''?$_POST['ean_no']:$_GET['ean_no'];
	$apply_tf					= $_POST['apply_tf']!=''?$_POST['apply_tf']:$_GET['apply_tf'];

/*
	$e_type						= $_POST['e_type']!=''?$_POST['e_type']:$_GET['e_type'];
	$e_year						= $_POST['e_year']!=''?$_POST['e_year']:$_GET['e_year'];
	$e_title					= $_POST['e_title']!=''?$_POST['e_title']:$_GET['e_title'];
	$e_pdf						= $_POST['e_pdf']!=''?$_POST['e_pdf']:$_GET['e_pdf'];
	$e_img						= $_POST['e_img']!=''?$_POST['e_img']:$_GET['e_img'];

	$disp_seq					= $_POST['disp_seq']!=''?$_POST['disp_seq']:$_GET['disp_seq'];

	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$reg_date					= $_POST['reg_date']!=''?$_POST['reg_date']:$_GET['reg_date'];

	$f								= $_POST['f']!=''?$_POST['f']:$_GET['f'];
	$s								= $_POST['s']!=''?$_POST['s']:$_GET['s'];
*/
	$chk							= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

	$use_tf					= "";  
	$apply_tf				= "";
	$del_tf					= "N";

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntEnterInfoApplyExcelChk($conn, $chk, $apply_tf, $use_tf, $del_tf, $f, $s);

	$arr_rs = listEnterInfoApplyExcelChk($conn, $chk, $ea_name, $ea_who, $ea_who_year, $ea_post, $ea_addr, $ea_addr_detail, $ea_phone, $ea_pwd, $agree_tf, $apply_tf, $use_tf, $del_tf, $f, $s);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "입학 자료 신청 리스트 조회", "List");

?>
<html>
<font size=3><b>입학자료 신청 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border=1>
<colgroup>
	<col width="3%" />
	<col width="5%" /><!-- 성명 -->
	<col width="6%" /><!-- 대상  -->
	<col width="10%" /><!-- 전화번호 -->
	<col width="16%" /><!-- 학교  -->
	<col width="6%" /><!-- 우편번호  -->
	<col width="22%" /><!-- 주소  -->
	<col width="19%" /><!-- 신청자료 -->
	<col width="6%" /><!-- 신청처리여부 -->
	<col width="8%" /><!-- 등록일 -->
</colgroup>
<thead>
	<tr>
		<th>번호</th>
		<th>성명</th>
		<th>대상</th>
		<th>전화번호</th>
		<th>학교명</th>
		<th>우편번호</th>
		<th>주소</th>
		<th>신청자료</th>
		<th>처리여부</th>
		<th>등록일</th>
	</tr>
</thead>
<tbody>
<?
	$nCnt = 0;
	
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$rn							= trim($arr_rs[$j]["rn"]);
			$EA_NO						= trim($arr_rs[$j]["EA_NO"]);
			$EAN_NO						= trim($arr_rs[$j]["EAN_NO"]);
			$EA_NAME					= trim($arr_rs[$j]["EA_NAME"]);
			$EA_WHO						= trim($arr_rs[$j]["EA_WHO"]);
			$EA_WHO_YEAR				= trim($arr_rs[$j]["EA_WHO_YEAR"]);	
			$EA_POST					= trim($arr_rs[$j]["EA_POST"]);
			$EA_SC_NM					= trim($arr_rs[$j]["EA_SC_NM"]);
			$EA_SC_AREA					= trim($arr_rs[$j]["EA_SC_AREA"]);
			$EA_ADDR					= trim($arr_rs[$j]["EA_ADDR"]);
			$EA_ADDR_DETAIL				= trim($arr_rs[$j]["EA_ADDR_DETAIL"]);
			$EA_PHONE					= trim($arr_rs[$j]["EA_PHONE"]);
			$E_TITLE					= trim($arr_rs[$j]["E_TITLE"]);
			$EA_NUM						= trim($arr_rs[$j]["EA_NUM"]);
			$EA_MEMO					= trim($arr_rs[$j]["EA_MEMO"]);
			$APPLY_TF					= trim($arr_rs[$j]["APPLY_TF"]);

			$DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);
			$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
			$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
			$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
			$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);

			if ($APPLY_TF == "N") {
				$STR_APPLY_TF = "<font color='red'>접수중</font>";
			} else{
				$STR_APPLY_TF = "<font color='blue'>처리완료</font>";
			}

			if ($USE_TF == "Y") {
				$STR_USE_TF = "<font color='blue'>공개</font>";
			} else {
				$STR_USE_TF = "<font color='red'>비공개</font>";
			}

			$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

			$offset = $nPageSize * ($nPage-1);
			$logical_num = ($nListCnt - $offset);
			$rn = $logical_num - $j;

?>
			<tr align="center">
				<td align="center"><?=$rn?></td>
				<td><?=$EA_NAME?></td>
				<td><?=$EA_WHO?> <? if ($EA_WHO_ETC <> "") echo "(".$EA_WHO_ETC.")" ?></td>
				<td><?=$EA_PHONE?></td>
				<td><?=$EA_SC_NM?> (<?=$EA_SC_AREA?>)</td>
				<td style="mso-number-format:'\@'"><?=$EA_POST?></td> <!-- 엑셀에 0나오도록 처리 2022-09-07 -->
				<td><?=$EA_ADDR?> <?=$EA_ADDR_DETAIL?></td>
				<td><?=$EA_MEMO?></td>
				<td><?=$STR_APPLY_TF?></td>
				<td><?=$REG_DATE?></td>
			</tr>
<?			
		}
	} 
	
	if (sizeof($arr_rs) < 0) { 
?> 
	<tr>
		<td colspan="15">
			<div class="nodata">데이터가 없습니다.</div>
		</td>
	</tr>
<? 
	}
?>
</tbody>
</table>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
