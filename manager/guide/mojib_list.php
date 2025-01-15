<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : mojib_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-21
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
	$menu_right = "BA001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/mojib/guide.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$m_no						= isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
	$m_type					= isset($_POST["m_type"]) && $_POST["m_type"] !== '' ? $_POST["m_type"] : (isset($_GET["m_type"]) ? $_GET["m_type"] : '');
	$m_title				= isset($_POST["m_title"]) && $_POST["m_title"] !== '' ? $_POST["m_title"] : (isset($_GET["m_title"]) ? $_GET["m_title"] : '');
	$m_year					= isset($_POST["m_year"]) && $_POST["m_year"] !== '' ? $_POST["m_year"] : (isset($_GET["m_year"]) ? $_GET["m_year"] : '');
	$m_pdf					= isset($_POST["m_pdf"]) && $_POST["m_pdf"] !== '' ? $_POST["m_pdf"] : (isset($_GET["m_pdf"]) ? $_GET["m_pdf"] : '');
	$m_hwp					= isset($_POST["m_hwp"]) && $_POST["m_hwp"] !== '' ? $_POST["m_hwp"] : (isset($_GET["m_hwp"]) ? $_GET["m_hwp"] : '');

	$disp_seq				= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$use_tf					= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date				= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$md_num					= isset($_POST["md_num"]) && $_POST["md_num"] !== '' ? $_POST["md_num"] : (isset($_GET["md_num"]) ? $_GET["md_num"] : '');
	$md_title				= isset($_POST["md_title"]) && $_POST["md_title"] !== '' ? $_POST["md_title"] : (isset($_GET["md_title"]) ? $_GET["md_title"] : '');
	$md_disp_seq		= isset($_POST["md_disp_seq"]) && $_POST["md_disp_seq"] !== '' ? $_POST["md_disp_seq"] : (isset($_GET["md_disp_seq"]) ? $_GET["md_disp_seq"] : '');

	$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	$con_m_type			= isset($_POST["con_m_type"]) && $_POST["con_m_type"] !== '' ? $_POST["con_m_type"] : (isset($_GET["con_m_type"]) ? $_GET["con_m_type"] : '');
	$con_m_year			= isset($_POST["con_m_year"]) && $_POST["con_m_year"] !== '' ? $_POST["con_m_year"] : (isset($_GET["con_m_year"]) ? $_GET["con_m_year"] : '');

	$chk						= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	if ($mode == "T") {
		$result = updateMojibUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $m_no);
		$use_tf = "";
	}

	if ($mode == "D") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_no = $chk[$k];
			$result = deleteMojib($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result = deleteMojibDetail($conn, $_SESSION['s_adm_no'], $tmp_no);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "가이드북 삭제 (".$tmp_idx.") ", "Delete");
		}
	}


	#List Parameter
	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	$nPage					= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf					= SetStringToDB($use_tf);
	
	$del_tf					= "N";
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

	$nListCnt =totalCntMojib($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listMojib($conn, $m_no, $m_type, $m_title, $m_year, $m_pdf, $m_hwp, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "가이드북 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
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
		frm.action = "mojib_list.php";
		frm.submit();
	}
	
	function js_add_mojib() {
		document.location = "mojib_write.php";
	}

	function js_view(m_no) {
		var frm = document.frm;
		frm.m_no.value = m_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "mojib_write.php";
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

	function js_check() {

		var frm = document.frm;

		check=document.getElementsByName("chk[]");
		
		if (frm.all_chk_no.checked){
			for (i=0;i<check.length;i++) {
					check.item(i).checked = true;
			}
		} else {
			for (i=0;i<check.length;i++) {
					check.item(i).checked = false;
			}
		}
	}

	function js_toggle(m_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('공개 여부를 변경 하시겠습니까?');

		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.m_no.value = m_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "<?=$_SERVER['PHP_SELF']?>";

			frm.submit();
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
			<input type="hidden" name="m_no" value="">
			<input type="hidden" name="use_tf" value="">

					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<select name="search_field" id="search_field">
							<option value="M_TITLE" <? if ($search_field == "M_TITLE") echo "selected"; ?> >제목</option>
							</select>
							<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
						</div>
					</div>

					<div class="tbl_style01">
						<table>
							<colgroup>

                                                                <col width="4%" />
                                                                <col width="7%" /><!-- 구분 -->
                                                                <col width="15%" /><!-- 모집년도  -->
                                                                <col width="29%" /><!-- 제목  -->
                                                                <col width="29%" /><!-- PDF 파일 -->
                                                                <col width="10%" /><!-- HWP 파일 -->
                                                                <col width="6%" /><!-- 공개여부 -->
                                                                <col width="10%" /><!-- 등록일 -->

                                                        </colgroup>
                                                        <thead>
                                                                <tr>
                                                                        <th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
                                                                        <th>구분</th>
                                                                        <th>모집년도</th>
                                                                        <th>제목</th>
                                                                        <th>PDF 파일</th>
                                                                        <th>HWP 파일</th>
                                                                        <th>공개여부</th>
                                                                        <th>등록일</th>
                                                                </tr>
                                                        </thead>
							<tbody>
                                                        <?
                                                                $nCnt = 0;

                                                                if (sizeof($arr_rs) > 0) {

                                                                        for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

                                                                                $rn                                                     = trim($arr_rs[$j]["rn"]);
                                                                                $M_NO                                           = trim($arr_rs[$j]["M_NO"]);
                                                                                $M_TYPE                                         = trim($arr_rs[$j]["M_TYPE"]);
                                                                                $M_YEAR                                         = trim($arr_rs[$j]["M_YEAR"]);
                                                                                $M_TITLE                                        = trim($arr_rs[$j]["M_TITLE"]);
                                                                                $M_PDF                                          = trim($arr_rs[$j]["M_PDF"]);
                                                                                $M_HWP                                          = trim($arr_rs[$j]["M_HWP"]);
                                                                                $M_PDF_NAME                                     = trim($arr_rs[$j]["M_PDF_NAME"]);
                                                                                $M_HWP_NAME                                     = trim($arr_rs[$j]["M_HWP_NAME"]);

                                                                                $DISP_SEQ = trim($arr_rs[$j]["DISP_SEQ"]);
                                                                                                                                                                					$USE_TF = trim($arr_rs[$j]["USE_TF"]);$REG_DATE = trim($arr_rs[$j]["REG_DATE"]);
                                                                                $UP_DATE = trim($arr_rs[$j]["UP_DATE"]);

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
                                                                        <tr>
                                                                                <td><input type="checkbox" name="chk[]" value="<?=$M_NO?>"></td>
                                                                                <td><a href="javascript:js_view('<?=$M_NO?>');"><?=getDcodeName($conn, "GUIDE_TYPE", $M_TYPE)?></a></td>
                                                                                <td><a href="javascript:js_view('<?=$M_NO?>');"><?=getDcodeName($conn, "GUIDE_YEAR", $M_YEAR)?></a></td>
                                                                                <td><a href="javascript:js_view('<?=$M_NO?>');"><?=$M_TITLE?></a></td>
                                                                                <td><a href="../../_common/new_download_file.php?menu=guidebook&m_no=<?=$M_NO?>&field=m_pdf" target="_blank"><?=$M_PDF_NAME?></a></td>
                                                                                <td><a href="../../_common/new_download_file.php?menu=guidebook&m_no=<?=$M_NO?>&field=m_hwp" target="_blank"><?=$M_HWP_NAME?></a></td>
                                                                                <td><a href="javascript:js_toggle('<?=$M_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a></td>

                                                                                <td><?=$REG_DATE?></td>
                                                                        </tr>

                                                        <?
                                                                        }
                                                                } else {
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
                                        </div>
					<div class="btn_wrap">
						<div class="right">
							<!-- 버튼 자리 -->
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_add_mojib();" >등록</button>
							<? } ?>
							<?	if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete();">삭제</button>
							<?  } ?>
							<?	if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_execl();">엑셀</button>-->
							<?  } ?>
						</div>
					</div>


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
								$strParam = $strParam."&con_m_type=".$con_m_type;

						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
						<?
							}
						?>
						</div>
						<div class="right">
							<? if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_excel_print();">선택한 항목 엑셀로 받기</button>-->
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
