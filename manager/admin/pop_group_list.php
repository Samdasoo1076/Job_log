<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : pop_group_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-04-14
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	$menu_right = "AD004"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#==============================================================================
# Confirm right
#==============================================================================

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/program/program.php";
	require "../../_classes/biz/admin/admin.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$adm_no							= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$con_area_sido			= $_POST['con_area_sido']!=''?$_POST['con_area_sido']:$_GET['con_area_sido'];
	$con_area_sigungu		= $_POST['con_area_sigungu']!=''?$_POST['con_area_sigungu']:$_GET['con_area_sigungu'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$aseq_no						= $_POST['aseq_no']!=''?$_POST['aseq_no']:$_GET['aseq_no'];
	$aseq_no_chk				= $_POST['aseq_no_chk']!=''?$_POST['aseq_no_chk']:$_GET['aseq_no_chk'];
	$chk								= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

	$adm_no			= trim($adm_no);
	$adm_no			= (int)$adm_no;

	if ($mode == "U") {

		$row_cnt = is_null($aseq_no) ? 0 : count($aseq_no);

		for ($k = 0; $k < $row_cnt; $k++) {
			
			$result = updateGroupManager($conn, $adm_no, $aseq_no[$k], $aseq_no_chk[$k]);

		}
	}

	$con_use_tf			= "Y";
	$con_del_tf			= "N";
	$con_app_state	= "1";
	$rs_app_type		= "C";
	
	$nPage = 1;
	
	// 진행 중인 자원사업 번호 가지고 오기
	$con_seq_no = getCurrentProgram($conn);

	$arr_rs_admin = selectAdmin($conn, $adm_no);
	$admin_nm = trim($arr_rs_admin[0]["ADM_NAME"]);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$nListCnt =totalCntApply($conn, $rs_app_type, $con_seq_no, $con_idx_no, $con_area_sido, $con_area_sigungu, $con_app_state, $con_confirm_state, $con_manager_no, $con_add_file_state, $con_del_tf, $search_field, $search_str);

	$nPageSize = $nListCnt;
	
	if ($nListCnt <> 0) {
		$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;
	} else {
		$nTotalPage = 1;
	}

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$arr_rs = listApply($conn, $rs_app_type, $con_seq_no, $con_idx_no, $con_area_sido, $con_area_sigungu, $con_app_state, $con_confirm_state, $con_manager_no, $con_add_file_state, $con_del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);


	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "동아리 등록 (길잡이 번호 : ".$adm_no.") ", "List");

?>
<!DOCTYPE HTML>
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
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->
<script language="JavaScript">

	$(document).on("change", "#con_area_sido", function() {

		$("#con_area_sigungu option").remove();
		$("#con_area_sigungu").append("<option value=''>전체</option>");

		if ($(this).val() != "") {
			
			var mode = "GET_SIGUNGU";
			var sigungu = "";

			var request = $.ajax({
				url:"/_common/ajax_common_dml.php",
				type:"POST",
				data:{mode:mode, sido:$(this).val(), sigungu:sigungu},
				dataType:"json"
			});
			
			request.done(function(data) {

				for (i = 0 ; i < data.length ; i++) {
					$("#con_area_sigungu").append("<option value='"+data[i].AREA_NAME+"'>"+data[i].AREA_NAME+"</option>");
				}
			});
		}

	});


	function js_search() {

		var frm = document.frm;
		
		frm.target = "";
		frm.method = "post";
		frm.action = "pop_group_list.php";
		frm.submit();
	}

	
	function js_save() {
		
		var frm = document.frm;
		
		var total = frm["aseq_no[]"].length;

		if (frm["aseq_no[]"].length == null) {

			if (frm["chk[]"].checked == true) {
				frm["aseq_no_chk[]"].value = "Y";
			} else {
				frm["aseq_no_chk[]"].value = "N";
			}

		} else {
		
			for(var i=0; i< total; i++) {
			
				if (frm["chk[]"][i].checked == true) {
					frm["aseq_no_chk[]"][i].value = "Y";
				} else {
					frm["aseq_no_chk[]"][i].value = "N";
				}
			}

		}

		frm.mode.value = "U";
		frm.target = "";
		frm.method = "post";
		frm.action = "pop_group_list.php";
		frm.submit();
		
	}


</script>
<style type="text/css">
/*
#pop_table_scroll { z-index: 1;  overflow: auto; height: 280px; }
*/
</style>
</head>
<body id="popup">
<div class="popupwrap">
	<h1>길잡이 동아리 등록 (<?=$admin_nm?>)</h1>
	<div class="popcontents">
		<div class="tbl_style01 left">

<form name="frm" action="pop_group_list.php" method="post">
<input type="hidden" name="mode" value="">
<input type="hidden" name="adm_no" value="<?=$adm_no?>">

			<table>
				<colgroup>
					<col style="width:15%" />
					<col style="width:85%" />
				</colgroup>
				<tbody>
					<tr>
						<th>지역</th>
						<td>
							<?=makeSidoSelectBox($conn, "con_area_sido", "전체", "", $con_area_sido, "con_area_sigungu", "전체", "", $con_area_sigungu)?>
						</td>
					</tr>
					<tr>
						<th>검색조건</th>
						<td>
							<select name="search_field">
								<option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >전체</option>
								<? if ($rs_app_type == "C") { ?>
								<option value="A.SEQ_NUM" <? if ($search_field == "A.SEQ_NUM") echo "selected"; ?> >접수번호</option>
								<option value="B.GROUP_NAME" <? if ($search_field == "B.GROUP_NAME") echo "selected"; ?> >동아리 명</option>
								<option value="A.APP_NAME" <? if ($search_field == "A.APP_NAME") echo "selected"; ?> >담당자 명</option>
								<option value="A.APP_EMAIL" <? if ($search_field == "A.APP_EMAIL") echo "selected"; ?> >담당자 이메일</option>
								<option value="A.SEC_NAME" <? if ($search_field == "A.SEC_NAME") echo "selected"; ?> >부대표 명</option>
								<option value="A.SEC_EMAIL" <? if ($search_field == "A.SEC_EMAIL") echo "selected"; ?> >부대표 이메일</option>
								<option value="B.GROUP_RUNNER" <? if ($search_field == "B.GROUP_RUNNER") echo "selected"; ?> >대표자 명</option>
								<option value="B.GROUP_EMAIL" <? if ($search_field == "B.GROUP_EMAIL") echo "selected"; ?> >대표 이메일</option>
								<option value="B.REG_EMAIL" <? if ($search_field == "B.REG_EMAIL") echo "selected"; ?> >등록자 이메일</option>
								<? } else { ?>
								<? } ?>
							</select>
							<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" style="width:200px"/>
							<button type="button" class="button type03" onClick="js_search();">검색</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="sp10"></div>


<div class="tbl_style01" id="pop_table_scroll">
	<table>
		<colgroup>
			<col width="4%" /><!-- chk -->
			<col width="13%" /><!-- 접수번호 -->
			<col width="10%" /><!-- 시도 -->
			<col width="11%" /><!-- 시군구 -->
			<col width="17%" /><!-- 동아리명 -->
			<col width="8%" /><!-- 담당자 -->
			<col width="12%" /><!-- 담당자연락처 -->
			<col width="11%" /><!-- 수정일 -->
			<col width="9%" /><!-- 담당 길잡이 -->
		</colgroup>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>접수번호</th>
				<th>시도</th>
				<th>시군구</th>
				<th>동아리명</th>
				<th>담당자</th>
				<th>담당자연락처</th>
				<th>수정일</th>
				<th>담당길잡이</th>
			</tr>
		</thead>
		<tbody>
		<?
			$nCnt = 0;
			
			if (sizeof($arr_rs) > 0) {
				
				for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

					$rn								= trim($arr_rs[$j]["rn"]);
					$ASEQ_NO					= trim($arr_rs[$j]["ASEQ_NO"]);
					$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
					$SEQ_NUM					= trim($arr_rs[$j]["SEQ_NUM"]);
					$AREA_SIDO				= trim($arr_rs[$j]["AREA_SIDO"]);
					$AREA_SIDO				= trim($arr_rs[$j]["AREA_SIDO"]);
					$AREA_SIGUNGU			= trim($arr_rs[$j]["AREA_SIGUNGU"]);
					$GROUP_NAME				= SetStringFromDB($arr_rs[$j]["GROUP_NAME"]);
					$GROUP_RUNNER			= trim($arr_rs[$j]["GROUP_RUNNER"]);
					$FIRST_PHONE			= trim($arr_rs[$j]["FIRST_PHONE"]);
					$SEC_NAME					= SetStringFromDB($arr_rs[$j]["SEC_NAME"]);
					$SEC_PHONE				= trim($arr_rs[$j]["SEC_PHONE"]);
					$APP_NAME					= trim($arr_rs[$j]["APP_NAME"]);
					$APP_PHONE				= trim($arr_rs[$j]["APP_PHONE"]);
					$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);
					$APP_STATE				= trim($arr_rs[$j]["APP_STATE"]);
					$CONFIRM_STATE		= trim($arr_rs[$j]["CONFIRM_STATE"]);
					$MANAGER_NO				= trim($arr_rs[$j]["MANAGER_NO"]);

					$offset = $nPageSize * ($nPage-1);
					$logical_num = ($nListCnt - $offset);
					$rn = $logical_num - $j;
		?>

				<tr>
					<td>
						<input type="checkbox" name="chk[]" value="<?=$ASEQ_NO?>" <? if ($MANAGER_NO == $adm_no) { echo "checked"; } ?>>
						<input type="hidden" name="aseq_no_chk[]" value="">
						<input type="hidden" name="aseq_no[]" value="<?=$ASEQ_NO?>">
					</td>
					<td><?=$SEQ_NUM?></td>
					<td><?=$AREA_SIDO?></td>
					<td><?=$AREA_SIGUNGU?></td>
					<td><?=$GROUP_NAME?></td>
					<td><?=$APP_NAME?></td>
					<td><?=$APP_PHONE?></td>
					<td><?=left($UP_DATE,10)?></td>
					<td><?=getAdminName($conn, $MANAGER_NO)?></td>
				</tr>

		<?			
				}
			} else { 
		?> 
			<tr>
				<td colspan="9">
					<div class="nodata">검색 결과가 없습니다. <br>다시 검색해주세요.</div>
				</td>
			</tr>
		<? 
			}
		?>
		</tbody>
	</table>
</div>


		<div class="btn_wrap right">
			<? if (($sPageRight_I == "Y") && ($sPageRight_U == "Y") && ($sPageRight_D == "Y")) { ?>
			<button type="button" class="button" onClick="js_save();">저장</button>
			<? } ?>
			<button type="button" class="button type03" onClick="self.close();">닫기</button>
		</div>
	</div>
</div>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>