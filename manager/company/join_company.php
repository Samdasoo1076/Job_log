<?session_start();?>
<?
header("x-xss-Protection:0");
ini_set('display_errors', 1); // 오류를 브라우저에 표시
ini_set('display_startup_errors', 1); // 시작 오류 표시
error_reporting(E_ALL); // 모든 오류 표시
# =============================================================================
# File Name    : join_company.php
# Modlue       : 
# Writer       : 
# Create Date  : 2025-04-08
# Modify Date  : 2025-04-08
#	Description : 회사 회원가입 리스트 페이지
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "CP002"; // 메뉴마다 셋팅 해 주어야 합니다

	$con_banner_type = "MAINVISUAL";

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
	require "../../_classes/biz/company/join_company.php";
//	require "../js/common.js";

#====================================================================
# Request Parameter
#====================================================================
$mode       = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$seq        = isset($_POST["seq"]) && $_POST["seq"] !== '' ? $_POST["seq"] : (isset($_GET["seq"]) ? $_GET["seq"] : '');
$jcp_no     = isset($_POST["jcp_no"]) && $_POST["jcp_no"] !== '' ? $_POST["jcp_no"] : (isset($_GET["jcp_no"]) ? $_GET["jcp_no"] : '');
$c_nm       = isset($_POST["c_nm"]) && $_POST["c_nm"] !== '' ? $_POST["c_nm"] : (isset($_GET["c_nm"]) ? $_GET["c_nm"] : '');
$phone      = isset($_POST["phone"]) && $_POST["phone"] !== '' ? $_POST["phone"] : (isset($_GET["phone"]) ? $_GET["phone"] : '');
$content    = isset($_POST["content"]) && $_POST["content"] !== '' ? $_POST["content"] : (isset($_GET["content"]) ? $_GET["content"] : '');
$url        = isset($_POST["url"]) && $_POST["url"] !== '' ? $_POST["url"] : (isset($_GET["url"]) ? $_GET["url"] : '');
$use_tf     = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : 'Y');
$del_tf     = isset($_POST["del_tf"]) && $_POST["del_tf"] !== '' ? $_POST["del_tf"] : (isset($_GET["del_tf"]) ? $_GET["del_tf"] : 'N');
$reg_date   = isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');
$up_date    = isset($_POST["up_date"]) && $_POST["up_date"] !== '' ? $_POST["up_date"] : (isset($_GET["up_date"]) ? $_GET["up_date"] : '');
$del_date   = isset($_POST["del_date"]) && $_POST["del_date"] !== '' ? $_POST["del_date"] : (isset($_GET["del_date"]) ? $_GET["del_date"] : '');
$reg_adm    = isset($_POST["reg_adm"]) && $_POST["reg_adm"] !== '' ? $_POST["reg_adm"] : (isset($_GET["reg_adm"]) ? $_GET["reg_adm"] : '');
$up_adm     = isset($_POST["up_adm"]) && $_POST["up_adm"] !== '' ? $_POST["up_adm"] : (isset($_GET["up_adm"]) ? $_GET["up_adm"] : '');
$el_adm     = isset($_POST["el_adm"]) && $_POST["el_adm"] !== '' ? $_POST["el_adm"] : (isset($_GET["el_adm"]) ? $_GET["el_adm"] : ''); //del_adm <- 테이블 오타임
$ho         = isset($_POST["ho"]) && $_POST["ho"] !== '' ? $_POST["ho"] : (isset($_GET["ho"]) ? $_GET["ho"] : '');  // 호실

// 정리 또는 보안 처리가 필요하다면 여기서 추가 처리
$jcp_no     = trim($jcp_no);
$c_nm       = trim($c_nm);
$phone      = trim($phone);
$content    = trim($content);
$url        = trim($url);
$ho         = trim($ho);

// 필요한 모드에 따른 처리 예시
if ($mode == "D") {
    // 삭제 처리
    $result = deleteCompany($conn, $_SESSION['s_adm_no'], $jcp_no);
    $result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "기업 삭제 (".$jcp_no.")", "Delete");
} else if ($mode == "U") {
    // 수정 처리
    $result = updateCompany($conn, $jcp_no, $seq, $c_nm, $phone, $content, $url, $use_tf, $_SESSION['s_adm_no'], $ho);
} else if ($mode == "I") {
    // 등록 처리
    $result = insertCompany($conn, $seq, $c_nm, $phone, $content, $url, $use_tf, $_SESSION['s_adm_no'], $ho);
}

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
		$nPageSize = 50;
	}

	$nPageBlock	= 10;
	
#===============================================================
# Get Search list count
#===============================================================

// 총 레코드 수
$nListCnt = totalCntJoinCompany($conn, $use_tf, $del_tf, $search_field, $search_str);

// 총 페이지 수 계산
$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1);

if ((int)($nTotalPage) < (int)($nPage)) {
    $nPage = $nTotalPage;
}

// 회사 리스트 조회
$arr_rs = listJoinCompany($conn, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize);

// 로그 기록 등 필요한 작업 (예: 회사 리스트 조회 로그)
// $result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "참여 기업 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>A
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
		frm.action = "banner_list.php";
		frm.submit();
	}
	
	function js_add_banner() {
		document.location = "banner_write.php";
	}

	function js_view(banner_no) {
		var frm = document.frm;
		frm.banner_no.value = banner_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.target = "";
		frm.action = "banner_write.php";
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

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 1) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.seq_banner_no[preid-2].value;
			
				document.frm.seq_banner_no[preid-2].value = document.frm.seq_banner_no[preid-1].value;
				document.frm.seq_banner_no[preid-1].value = tempCode;
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

	if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_banner_no[preid] != null)) {

	//alert(preid);
	//return;

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 1) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.seq_banner_no[preid-1].value;
				document.frm.seq_banner_no[preid-1].value = document.frm.seq_banner_no[preid].value;
				document.frm.seq_banner_no[preid].value = tempCode;
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
	document.frm.action = "banner_order_dml.php";
	document.frm.submit();

}

function js_toggle(banner_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
	
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.banner_no.value = banner_no;
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

		<form id="bbsList" name="frm" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="use_tf" value="">
			<input type="hidden" name="banner_no" value="<?=$banner_no?>">			
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

					<div class="tbl_top">
						<div class="left">
							<span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 건</span>
						</div>
						<div class="right">
							<select name="search_field" id="search_field">
							<option value="BANNER_NM" <? if ($search_field == "BANNER_NM") echo "selected"; ?> >제목</option>
							</select>
							<input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
							<button type="button" class="button" onClick="js_search();">검색</button>
						</div>
					</div>
					<div class="tbl_style01">
						<table id='t'>
							<colgroup>
								<col width="5%" />
								<col width="5%" /><!-- 순서 -->
								<col width="10%" /><!-- 이미지 -->
								<!--<col width="10%" />--><!-- 모바일 이미지 -->
								<col width="15%" /><!-- 제목 -->
								<col width="17%" /><!-- 슬로건 상단 -->
								<col width="27%" /><!-- 슬로건 하단 -->
								<col width="8%" /><!-- 공개여부 -->
								<col width="13%" /><!-- 등록일 -->
								<col width="10%" />
							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no"/></th>
									<th>순서</th>
									<th>회사명</th>
									<!--<th>모바일이미지</th>-->
									<th>입주호실</th>
									<th>대표번호</th>
									<th>주요업무</th>
									<th>홈페이지 URL</th>
									<th>공개여부</th>
									<th>등록일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

										$rn								= trim($arr_rs[$j]["rn"]);
										$BANNER_NO				= trim($arr_rs[$j]["BANNER_NO"]);
										$BANNER_NM				= trim($arr_rs[$j]["BANNER_NM"]);
										$BANNER_TYPE			= trim($arr_rs[$j]["BANNER_TYPE"]);
										$CATE_01					= trim($arr_rs[$j]["CATE_01"]);
										$TITLE_NM					= trim($arr_rs[$j]["TITLE_NM"]);
										$SUB_TITLE_NM			= trim($arr_rs[$j]["SUB_TITLE_NM"]);
										$BANNER_IMG				= trim($arr_rs[$j]["BANNER_IMG"]);
										$BANNER_REAL_IMG	= trim($arr_rs[$j]["BANNER_REAL_IMG"]);
										$BANNER_IMG_M			= trim($arr_rs[$j]["BANNER_IMG_M"]);
										$BANNER_REAL_IMG_M= trim($arr_rs[$j]["BANNER_REAL_IMG_M"]);
										$BANNER_URL				= trim($arr_rs[$j]["BANNER_URL"]);
										$BANNER_BUTTON		= trim($arr_rs[$j]["BANNER_BUTTON"]);

										$S_DATE						= trim($arr_rs[$j]["S_DATE"]);
										$S_HOUR						= trim($arr_rs[$j]["S_HOUR"]);
										$S_MIN						= trim($arr_rs[$j]["S_MIN"]);
										$E_DATE						= trim($arr_rs[$j]["E_DATE"]);
										$E_HOUR						= trim($arr_rs[$j]["E_HOUR"]);
										$E_MIN						= trim($arr_rs[$j]["E_MIN"]);
										$E_MIN						= trim($arr_rs[$j]["E_MIN"]);

										$URL_TYPE					= trim($arr_rs[$j]["URL_TYPE"]);
										$DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);

										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
										$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);

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
										<td><input type="checkbox" class="chk_no" name="chk[]" value="<?=$BANNER_NO?>"></td>
										<td class="moveIcon">
											<? if ($USE_TF == "Y") { ?>
											<ul>
												<li>
													<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/common/icon_arr_top.gif" alt="up" /></a>
													<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/common/icon_arr_bot.gif" alt="down" /></a>
												</li>
											</ul>
											<? } ?>
										</td>
										<td><a href="javascript:js_view('<?=$BANNER_NO?>');"><img src="/upload_data/banner/<?=$BANNER_IMG?>" width="100px"></a></td>
										<!--<td><a href="javascript:js_view('<?=$BANNER_NO?>');"><img src="/upload_data/banner/<?=$BANNER_IMG_M?>" height="100px"></a></td>-->
										<td><a href="javascript:js_view('<?=$BANNER_NO?>');"><?=$BANNER_NM?></a>
										<? if ($USE_TF == "Y") { ?>
											<input type="hidden" name="seq_banner_no" value="<?=$BANNER_NO?>">
											<input type="hidden" name="banner_seq_no[]" value="<?=$BANNER_NO?>">
										<? } ?>
										</td>
										<td><?=$TITLE_NM?></td>
										<td><?=nl2br($SUB_TITLE_NM)?></td>
										<td><!--td에 클래스 on/off로 공개/비공개 제어-->
										<a href="javascript:js_toggle('<?=$BANNER_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a>
										</td>
										<td><?=$REG_DATE?></td>
									</tr>

							<?			
									}
								} else { 
							?> 
								<tr>
									<td colspan="9">
										<div class="nodata">데이터가 없습니다.</div>
									</td>
								</tr>
							<? 
								}
							?>
							</tbody>
						</table>
					</div>
					<div class="tbl_top">
						<div class="right">
							<!-- 버튼 자리 -->
							<? if ($sPageRight_I == "Y") { ?>
							<button type="button" class="button" onClick="js_add_banner();" >등록</button>						
							<? } ?>
							<?	if ($sPageRight_D == "Y") { ?>
							<button type="button" class="button type02" onClick="js_delete();">삭제</button>
							<?  } ?>
							<?	if ($sPageRight_F == "Y") { ?>
							<!--<button type="button" class="button" onClick="js_execl();">엑셀</button>-->
							<?  } ?>
						</div>
					</div>

				</div>
</form>
			</div>
		</div>
	</div>
<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
