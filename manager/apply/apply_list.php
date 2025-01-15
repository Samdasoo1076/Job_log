<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : apply_list.php
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
	$menu_right = "AP001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/apply/apply.php";

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

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($mode == "C") {

		$row_cnt = is_null($chk) ? 0 : count($chk);

		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_idx = $chk[$k];

			$arr_data = array("APP_FLAG"=>$app_flag_value,
												"UP_DATE"=>$INS_DATE);

			$result = updateApply($conn, $arr_data, $tmp_idx);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "신청프로그램 상태변경 (".$tmp_idx.") ", "Update");
		}
	}

	if ($mode == "D") {

		$row_cnt = is_null($chk) ? 0 : count($chk);

		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_idx = $chk[$k];

			$arr_data = array("DEL_TF"=>'Y',
												"DEL_ADM"=>$_SESSION['s_adm_no'],
												"DEL_DATE"=>$INS_DATE);

			$result = updateApply($conn, $arr_data, $tmp_idx);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "신청프로그램 신청삭제 (".$tmp_idx.") ", "Delete");
		}
	}
	
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

	$nListCnt =totalCntApply($conn, $con_app_flag, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listApply($conn, $con_app_flag, $con_use_tf, $con_del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "신청프로그램 신청 리스트 조회", "List");

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

	$(document).ready(function() {
		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});
	});

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER['PHP_SELF']?>";
		frm.submit();
	}
	
	function js_excel_print() {
		var frm = document.frm;
		frm.method = "post";
		frm.target = "";
		frm.action = "apply_excel_list.php";
		frm.submit();
	}

	function js_add_apply() {
		document.location = "apply_write.php";
	}

	function js_view(aseq_no) {
		var frm = document.frm;
		frm.aseq_no.value = aseq_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "apply_read.php";
		frm.submit();
	}

	function js_delete() {

		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
		
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
		
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
			
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER['PHP_SELF']?>";
				frm.submit();
			}
		}
	}

	function js_flag_change(state) {

		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
		
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
		
		var msg = "";
		if (state == "0") {
			msg = "선택하신 자료를 접수로 변경 하시겠습니까?";
		} else if (state == "1") {
			msg = "선택하신 자료를 확정으로 변경 하시겠습니까?";
		} 
		
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm(msg);
			
			if (bDelOK==true) {
				frm.app_flag_value.value = state;
				frm.mode.value = "C";
				frm.target = "";
				frm.action = "<?=$_SERVER['PHP_SELF']?>";
				frm.submit();
			}
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

		<form id="bbsList" name="frm" method="post">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
			<input type="hidden" name="app_flag_value" value="">

				<div class="cont">
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:100%" />
							</colgroup>
							<tbody>
								<tr>
									<td style="width:100%;text-align:right;">
										<select name="search_field">
											<option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >전체</option>
											<option value="SCH_NM" <? if ($search_field == "SCH_NM") echo "selected"; ?> >고등학교 이름</option>
											<option value="APP_NM" <? if ($search_field == "APP_NM") echo "selected"; ?> >담당교사 성명</option>
											<option value="PRO_NM" <? if ($search_field == "PRO_NM") echo "selected"; ?> >프로그램</option>
										</select>
										<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" style="width:200px"/>
										<button type="button" class="button type03" onClick="js_search();">검색</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="tbl_top">
						<div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span></div>
						<div class="right">
							<? if ($sPageRight_U == "Y") { ?>
							<button type="button" class="button" onClick="js_flag_change('0');">접수로 변경</button>
							<button type="button" class="button" onClick="js_flag_change('1');">확정으로 변경</button>
							<? } ?>
							<? if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete()">삭제</button>
							<? } ?>
						</div>
					</div>

					<div class="tbl_style01">
						<table>
							<colgroup>
								<col width="3%"> <!-- 선택 -->
								<col width="10%" /><!-- 고등학교 이름 -->
								<col width="5%" /><!-- 담당교사 성명 -->
								<col width="8%" /><!-- 담당교사 연락처 -->
								<col width="10%" /><!-- 담당교사 이메일 -->
								<col width="20%" /><!-- 프로그램 -->
								<col width="16%" /><!-- 희망일시 -->
								<col width="5%" /><!-- 학생수 -->
								<col width="8%" /><!-- 운영장소 -->
								<col width="10%" /><!-- 등록일 -->
								<col width="5%" /><!-- 상태 -->
							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no"/></th>
									<th>고등학교 이름</th>
									<th>교사성명</th>
									<th>교사연락처</th>
									<th>교사이메일</th>
									<th>프로그램</th>
									<th>희망일시</th>
									<th>학생수</th>
									<th>운영장소</th>
									<th>등록일</th>
									<th>상태</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

										$rn								= trim($arr_rs[$j]["rn"]);
										$APP_NO						= trim($arr_rs[$j]["APP_NO"]);
										$SCH_NM						= trim($arr_rs[$j]["SCH_NM"]);
										$APP_NM						= trim($arr_rs[$j]["APP_NM"]);
										$APP_HPHONE				= trim($arr_rs[$j]["APP_HPHONE"]);
										$APP_EMAIL				= trim($arr_rs[$j]["APP_EMAIL"]);
										$PRO_NO						= trim($arr_rs[$j]["PRO_NO"]);
										$PRO_NM						= SetStringFromDB($arr_rs[$j]["PRO_NM"]);
										$APP_DATE					= trim($arr_rs[$j]["APP_DATE"]);
										$APP_WEEK					= trim($arr_rs[$j]["APP_WEEK"]);
										$APP_HH						= trim($arr_rs[$j]["APP_HH"]);
										$APP_MM						= trim($arr_rs[$j]["APP_MM"]);
										$TIME_HH					= trim($arr_rs[$j]["TIME_HH"]);
										$STU_CNT					= trim($arr_rs[$j]["STU_CNT"]);
										$APP_PLACE				= trim($arr_rs[$j]["APP_PLACE"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);
										$APP_FLAG					= trim($arr_rs[$j]["APP_FLAG"]);

										$offset = $nPageSize * ($nPage-1);
										$logical_num = ($nListCnt - $offset);
										$rn = $logical_num - $j;

										$str_app_flag = $APP_FLAG==0 ?"접수":"<font color='blue'>확정</font>";
							?>

									<tr>
										<td><input type="checkbox" class="chk_no" name="chk[]" value="<?=$APP_NO?>"></td>
										<td><?=$SCH_NM?></td>
										<td><?=$APP_NM?></td>
										<td><?=$APP_HPHONE?></td>
										<td><?=$APP_EMAIL?></td>
										<td style="text-align:left">[<?=$PRO_NO?>] <?=$PRO_NM?></td>
										<td style="text-align:left"><?=$APP_DATE?> (<?=$APP_WEEK?>) <?=$APP_HH?>:<?=$APP_MM?> 총<?=$TIME_HH?></td>
										<td><?=$STU_CNT?></td>
										<td><?=$APP_PLACE?></td>
										<td><?=$REG_DATE?></td>
										<td><?=$str_app_flag?></td>
									</tr>

							<?			
									}
								} else { 
							?> 
								<tr>
									<td colspan="11">
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
								$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;
								$strParam = $strParam."&con_app_flag=".$con_app_flag;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>
						</div>
						<div class="right">
							<? if ($sPageRight_F == "Y") { ?>
							<button type="button" class="button" onClick="js_excel_print();">검색한 자료 엑셀로 받기</button>
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
