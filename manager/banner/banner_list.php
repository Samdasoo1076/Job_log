<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : banner_list.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2020-02-10
# Modify Date  : 2021-05-03
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
	$menu_right = "PO009"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/banner/banner.php";
//	require "../js/common.js";

#====================================================================
# Request Parameter
#====================================================================

	$mode							= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$banner_no				= isset($_POST["banner_no"]) && $_POST["banner_no"] !== '' ? $_POST["banner_no"] : (isset($_GET["banner_no"]) ? $_GET["banner_no"] : '');
	$banner_nm				= isset($_POST["banner_nm"]) && $_POST["banner_nm"] !== '' ? $_POST["banner_nm"] : (isset($_GET["banner_nm"]) ? $_GET["banner_nm"] : '');
	$banner_img				= isset($_POST["banner_img"]) && $_POST["banner_img"] !== '' ? $_POST["banner_img"] : (isset($_GET["banner_img"]) ? $_GET["banner_img"] : '');
	$banner_img_m			= isset($_POST["banner_img_m"]) && $_POST["banner_img_m"] !== '' ? $_POST["banner_img_m"] : (isset($_GET["banner_img_m"]) ? $_GET["banner_img_m"] : '');
	$banner_url				= isset($_POST["banner_url"]) && $_POST["banner_url"] !== '' ? $_POST["banner_url"] : (isset($_GET["banner_url"]) ? $_GET["banner_url"] : '');
	$url_type					= isset($_POST["url_type"]) && $_POST["url_type"] !== '' ? $_POST["url_type"] : (isset($_GET["url_type"]) ? $_GET["url_type"] : '');
	$banner_button		= isset($_POST["banner_button"]) && $_POST["banner_button"] !== '' ? $_POST["banner_button"] : (isset($_GET["banner_button"]) ? $_GET["banner_button"] : '');

	$banner_real_img	= isset($_POST["banner_real_img"]) && $_POST["banner_real_img"] !== '' ? $_POST["banner_real_img"] : (isset($_GET["banner_real_img"]) ? $_GET["banner_real_img"] : '');
	$banner_real_img_m= isset($_POST["banner_real_img_m"]) && $_POST["banner_real_img_m"] !== '' ? $_POST["banner_real_img_m"] : (isset($_GET["banner_real_img_m"]) ? $_GET["banner_real_img_m"] : '');

	$title_nm					= isset($_POST["title_nm"]) && $_POST["title_nm"] !== '' ? $_POST["title_nm"] : (isset($_GET["title_nm"]) ? $_GET["title_nm"] : '');
	$sub_title_nm			= isset($_POST["sub_title_nm"]) && $_POST["sub_title_nm"] !== '' ? $_POST["sub_title_nm"] : (isset($_GET["sub_title_nm"]) ? $_GET["sub_title_nm"] : '');

	$disp_seq					= isset($_POST["disp_seq"]) && $_POST["disp_seq"] !== '' ? $_POST["disp_seq"] : (isset($_GET["disp_seq"]) ? $_GET["disp_seq"] : '');
	$use_tf						= isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date					= isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	$s_date						= isset($_POST["s_date"]) && $_POST["s_date"] !== '' ? $_POST["s_date"] : (isset($_GET["s_date"]) ? $_GET["s_date"] : '');
	$s_hour						= isset($_POST["s_hour"]) && $_POST["s_hour"] !== '' ? $_POST["s_hour"] : (isset($_GET["s_hour"]) ? $_GET["s_hour"] : '');
	$s_min						= isset($_POST["s_min"]) && $_POST["s_min"] !== '' ? $_POST["s_min"] : (isset($_GET["s_min"]) ? $_GET["s_min"] : '');
	$e_date						= isset($_POST["e_date"]) && $_POST["e_date"] !== '' ? $_POST["e_date"] : (isset($_GET["e_date"]) ? $_GET["e_date"] : '');
	$e_hour						= isset($_POST["e_hour"]) && $_POST["e_hour"] !== '' ? $_POST["e_hour"] : (isset($_GET["e_hour"]) ? $_GET["e_hour"] : '');
	$e_min						= isset($_POST["e_min"]) && $_POST["e_min"] !== '' ? $_POST["e_min"] : (isset($_GET["e_min"]) ? $_GET["e_min"] : '');

	$nPage						= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize				= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');
	$search_field			= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str				= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
	$con_cate_01			= isset($_POST["con_cate_01"]) && $_POST["con_cate_01"] !== '' ? $_POST["con_cate_01"] : (isset($_GET["con_cate_01"]) ? $_GET["con_cate_01"] : '');

	$chk							= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

	if ($mode == "T") {
		updateBannerUseTF($conn, $use_tf, $_SESSION['s_adm_no'], $banner_no);
	}

	if ($mode == "O") {

		$row_cnt = is_null($banner_seq_no) ? 0 : count($banner_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_banner_no = $banner_seq_no[$k];

			$result = updateOrderBanner($conn, $k, $tmp_banner_no);
		
		}
	}

	if ($mode == "D") {
		$row_cnt = is_null($chk) ? 0 : count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_idx = $chk[$k];
			$result = deleteBanner($conn, $_SESSION['s_adm_no'], $tmp_idx);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 삭제 (".$tmp_idx.") ", "Delete");
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
	
	$use_tf				= ""; //SetStringToDB($use_tf);
	
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
		$nPageSize = 50;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================


	$nListCnt =totalCntBanner($conn, $con_banner_type, $con_cate_01, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBanner($conn, $con_banner_type, $con_cate_01, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 조회", "List");

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
							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no"/></th>
									<th>순서</th>
									<th>이미지</th>
									<!--<th>모바일이미지</th>-->
									<th>제목</th>
									<th>슬로건 상단</th>
									<th>슬로건 하단</th>
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
