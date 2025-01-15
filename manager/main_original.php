<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : Park Chan ho
# Create Date  : 2019-11-24
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

	if ($_SESSION['s_adm_no'] == "") {

		$next_url = "./login.php";

?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<?
			exit;
	}

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/admin/admin.php";
	require "../_classes/biz/board/board.php";
	require "../_classes/biz/group/group.php";
	require "../_classes/biz/space/space.php";
	require "../_classes/biz/banner/banner.php";

#====================================================================
# Request Parameter
#====================================================================

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/common_header.php"; 

	$nPage = 1;
	$nPageSize = 100;
	$nPageBlock	= 10;
	$con_del_tf = "N";

	// 공지사항
	$b_code = "B_1_1";

	$nListCnt = totalCntBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs_borad = listBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);


	$arr_group		= listNewGroupInfo($conn, "5");
	$arr_space		= listNewSpace($conn, "5");
	$arr_book			= listNewBoard($conn, "B_1_5", "5");
	$arr_culture	= listNewBoard($conn, "B_1_6", "5");
	$arr_tour			= listNewBoard($conn, "B_1_7", "5");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="./css/common.css" media="all" />
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
.pop_table_scroll { z-index: 1;  overflow: auto; height: 122px; }
.board_read_scroll { z-index: 1;  overflow: auto; height: 360px; }
-->
</style>
<style>

.replylist {clear:both; width:100%; overflow-x:hidden; overflow-y:auto; padding-right:10px}
.replylist::-webkit-scrollbar {width:5px}
.replylist::-webkit-scrollbar-track {-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3)} 
.replylist::-webkit-scrollbar-thumb {background-color: darkgrey; outline: 1px solid slategrey}
.replylist fieldset .pic {float:left; margin:0 20px 0 0}
.replylist fieldset .pic span {display:block; width:44px; height:44px; overflow:hidden; margin:0 auto; border-radius:50%}
.replylist fieldset .pic span img {width:100%; height:100%}
.replylist fieldset .pic strong {display:block; text-align:center; line-height:30px; font-size:14px; color:#000}
.replylist fieldset p {float:none; overflow:hidden; position:relative; height:70px; border:1px solid #2e323f}
.replylist fieldset p button {position:absolute; right:0; top:0; width:64px; height:70px; border-left:1px solid #eaeaea; color:#000}
.replylist fieldset p textarea {display:block; width:100%; height:70px; padding:15px; border:0}
.replylist ul {clear:both; overflow:hidden; padding-left:40px}
.replylist ul li {position:relative; padding:30px 0 30px 60px; border-top:1px solid #eaeaea}
.replylist ul li .btn-moreaction {position:absolute; right:0; top:30px; z-index:20; display:block; width:30px; height:30px}
.replylist ul li .btn-moreaction:hover em {display:block}
.replylist ul li .btn-moreaction button {width:30px; height:30px; background:url('../images/icon_moreaction.png') no-repeat 100% 30%; font-size:0; text-indent:-99999rem}
.replylist ul li .btn-moreaction em {display:none; position:absolute; right:0; top:20px; width:64px; height:64px; background:#fff; border:1px solid #dfdfdf}
.replylist ul li .btn-moreaction em a {display:block; text-align:center; color:#999; font-size:13px; line-height:30px}
.replylist ul li .btn-moreaction em a:hover {color:#010101}
.replylist ul li:first-child {border-top:0}
.replylist ul li p {margin-bottom:5px}
.replylist ul li span {display:block; color:#999}
.replylist ul li span.pic {display:block; position:absolute; top:30px; left:0; overflow:hidden; width:44px; height:44px; border-radius:50%}
.replylist ul li span.pic img {width:100%; height:100%}

</style>
<script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src="./js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="./js/ui.js"></script>
<script type="text/javascript" src="./js/common.js"></script>

	<style>
		.cont:after {content:""; display:block; clear:both; overflow:hidden}
		.canvas {-moz-user-select: none;-webkit-user-select: none;-ms-user-select: none;max-width:100%;}
		.canvas_wrap {float:left; width:60%;padding:20px; border:1px solid #ddd; }
		.conBox .conTitle {float:none; padding-left:0; !important;}
		.wrap_chart {width:100%;}
		.chart {float:left; padding-left:3%}
		.chart.count {float:right; width:33%}
		.chart_tbl { border-top:1px solid #333;}
		.chart_tbl td, 
		.chart_tbl th{padding:10px 20px ; border-bottom:1px solid #ddd;}
		.chart_tbl th {background-color: #fafafa;}
		.chart_tbl td {border-left:1px solid #ddd; text-align:right;}

		/*메인*/
		.main{ width:100%; margin:0 auto; padding-bottom:30px; }
		.main dt{ clear:both; background:url(./images/btn_arrow.gif) no-repeat 0 36px; font-weight:bold; padding:35px 0 5px 22px; }
		.main dd{ width:100%; background:#f8f8f8; border:1px solid #ededed; }
		.main dd ul{ padding:10px 20px 0; border:1px solid #fff; *border:none; }
		.main dd ul li{ float:left; width:180px; text-align:left; background:url(./images/arrow2.gif) no-repeat left 6px; padding-left:8px; padding-bottom:10px; }
		.main dd ul:after{ content:""; display:block; clear:both; }

	</style>

<script>

	function js_view(b_code, b_no) {

		$(".class_tr").each(function(index, item) {
			$(".class_tr").css("background","#FFFFFF");
		});
		
		$("#tr_board_"+b_no).css("background","#ffe297");
		
		$("#td_title_"+b_no).css("color", "");
		
		//alert($("#td_title_"+b_no));

		// ajax 로 공지사항 상세 가지고 오기
		var request = $.ajax({
			url:"/_common/board/ajax_board_read.php",
			type:"POST",
			data:{b_code:b_code, b_no:b_no},
			dataType:"html"
		});

		request.done(function(msg) {
			$("#notice_read").html(msg);
		});

		request.fail(function(jqXHR, textStatus) {
		});
	}

	function js_notice_list() {
		document.location = "/manager/board/board_list.php?b_code=B_1_1";
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
		require "../_common/left_area.php";
?>
		</div>
		<div id="container">
			<div class="top">
<?
	#====================================================================
	# common top_area
	#====================================================================
		require "../_common/top_area.php";
?>
			</div>
			<div class="contents">
<?
	#====================================================================
	# common location_area
	#====================================================================
		require "../_common/location_area.php";
?>

			<div class="tit_h3"><h3><?=$g_front_title?> CMS</h3></div>

			<div class="cont">

<? if ($_SESSION['s_adm_group_no'] <> "2") { ?>

				<div class="tit_h4 first"><h4>승인 대기 동아리</h4></div>
				<div class="tbl_style01">
					<table>
						<colgroup>
							<col width="16%" /><!-- 동아리명 -->
							<col width="8%" /><!-- 대표운영자 -->
							<col width="10%" /><!-- 지역 -->
							<col width="10%" /><!-- 모임주기 -->
							<col width="10%" /><!-- 모임요일 -->
							<col width="8%" /><!-- 모집여부 -->
							<col width="12%" /><!-- 모집기간 -->
							<col width="10%" /><!-- 등록일 -->
							<col width="10%" /><!-- 수정일 -->
							<col width="6%" /><!-- 사용여부 -->
						</colgroup>
						<thead>
							<tr>
								<th>동아리명</th>
								<th>대표운영자</th>
								<th>지역</th>
								<th>모임주기</th>
								<th>모임요일</th>
								<th>모집여부</th>
								<th>모집기간</th>
								<th>등록일</th>
								<th>수정일</th>
								<th>사용여부</th>
							</tr>
						</thead>
						<tbody>
			<?
				if (sizeof($arr_group) > 0) {
					for ($j = 0 ; $j < sizeof($arr_group) ; $j++) {

						$IDX							= trim($arr_group[$j]["IDX"]);
						$GROUP_NAME				= SetStringFromDB($arr_group[$j]["GROUP_NAME"]);
						$GROUP_RUNNER			= SetStringFromDB($arr_group[$j]["GROUP_RUNNER"]);
						$GROUP_ADDR_SIDO	= trim($arr_group[$j]["GROUP_ADDR_SIDO"]);
						$GROUP_JUKI				= trim($arr_group[$j]["GROUP_JUKI"]);
						$GROUP_YOIL_CODE	= trim($arr_group[$j]["GROUP_YOIL_CODE"]);
						$IS_APPLY					= trim($arr_group[$j]["IS_APPLY"]);
						$APPLY_START_DATE	= trim($arr_group[$j]["APPLY_START_DATE"]);
						$APPLY_END_DATE		= trim($arr_group[$j]["APPLY_END_DATE"]);
						$REG_DATE					= trim($arr_group[$j]["REG_DATE"]);
						$UP_DATE					= trim($arr_group[$j]["UP_DATE"]);
						$CONFIRM_TF				= trim($arr_group[$j]["CONFIRM_TF"]);
						$USE_TF						= trim($arr_group[$j]["USE_TF"]);
						$GROUP_ADDR_LAT		= trim($arr_group[$j]["GROUP_ADDR_LAT"]);

						$str_yoil = str_replace("||",",",$GROUP_YOIL_CODE);
						$str_yoil = str_replace("|","",$str_yoil);

						$str_apply_due = $APPLY_START_DATE." ~ ".$APPLY_END_DATE;

						if (trim($str_apply_due) == "~") $str_apply_due = "";

						$REG_DATE = date("Y.m.d",strtotime($REG_DATE));
						$UP_DATE	= date("Y.m.d",strtotime($UP_DATE));

			?>
							<tr>
								<td style="text-align:left"><a href="/manager/group/group_write.php?mode=S&idx=<?=$IDX?>"><?=$GROUP_NAME?></a></td>
								<td><a href="/manager/group/group_write.php?mode=S&idx=<?=$IDX?>"><?=$GROUP_RUNNER?></a></td>
								<td><?=$GROUP_ADDR_SIDO?></td>
								<td><?=$GROUP_JUKI?></td>
								<td><?=$str_yoil?></td>
								<td><?=getDcodeName($conn, "IS_APPLY", $IS_APPLY)?></td>
								<td><?=$str_apply_due?></td>
								<td><?=$REG_DATE?></td>
								<td><?=$UP_DATE?></td>
								<td><?=getDcodeName($conn, "USE_TF", $USE_TF)?></td>
							</tr>
			<?
					} 
				} else {
			?>
							<tr>
								<td colspan="10" style="text-align:center">승인 대기 중인 동아리가 없습니다.</td>
							</tr>
			<?
				}
			?>
						</tbody>
					</table>
				</div>

				<div class="sp20"></div>

				<div class="tit_h4 first"><h4>승인 대기 공간나눔</h4></div>
				<div class="tbl_style01">
					<table>
						<colgroup>
							<col width="15%" />
							<col width="15%" />
							<col width="15%" />
							<col width="15%" />
							<col width="15%" />
							<col width="15%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
									<th>공간구분</th>
									<th>공간이름</th>
									<th>지역</th>
									<th>참여년도</th>
									<th>등록일</th>
									<th>수정일</th>
									<th>사용여부</th>
							</tr>
						</thead>
						<tbody>
			<?
				if (sizeof($arr_space) > 0) {
					for ($j = 0 ; $j < sizeof($arr_space) ; $j++) {

						$IDX							= trim($arr_space[$j]["IDX"]);
						$SPACE_NAME				= SetStringFromDB($arr_space[$j]["SPACE_NAME"]);
						$SPACE_TYPE				= SetStringFromDB($arr_space[$j]["SPACE_TYPE"]);
						$SPACE_YEAR				= trim($arr_space[$j]["SPACE_YEAR"]);
						$SPACE_ADDR_SIDO	= trim($arr_space[$j]["SPACE_ADDR_SIDO"]);
						$REG_DATE					= trim($arr_space[$j]["REG_DATE"]);
						$UP_DATE					= trim($arr_space[$j]["UP_DATE"]);
						$CONFIRM_TF				= trim($arr_space[$j]["CONFIRM_TF"]);
						$USE_TF						= trim($arr_space[$j]["USE_TF"]);
						
						$SPACE_YEAR = str_replace("||",",",$SPACE_YEAR);
						$SPACE_YEAR = str_replace("|","",$SPACE_YEAR);

			?>
							<tr>
								<td><a href="/manager/space/space_write.php?mode=S&idx=<?=$IDX?>"><?=getDcodeName($conn, "SPACE_TYPE", $SPACE_TYPE)?></a></td>
								<td><a href="/manager/space/space_write.php?mode=S&idx=<?=$IDX?>"><?=$SPACE_NAME?></a></td>
								<td><?=$SPACE_ADDR_SIDO?></td>
								<td><?=$SPACE_YEAR?></td>
								<td><?=$REG_DATE?></td>
								<td><?=$UP_DATE?></td>
								<td><?=getDcodeName($conn, "USE_TF", $USE_TF)?></td>
							</tr>
			<?
					} 
			?>

			<?
				} else {
			?>
							<tr>
								<td colspan="10" style="text-align:center">승인 대기 중인 공간나눔이 없습니다.</td>
							</tr>
			<?
				}
			?>
						</tbody>
					</table>
				</div>

				<div class="sp20"></div>

				<div class="tit_h4 first"><h4>승인 대기 동아리와 책</h4></div>
				<div class="tbl_style01">
					<table>
						<colgroup>
							<col width="15%" />
							<col width="45%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
								<th>동아리 이름</th>
								<th>제목</th>
								<th>작성자</th>
								<th>노출여부</th>
								<th>등록일</th>
								<th>수정일</th>
							</tr>
						</thead>
						<tbody>
			<?
				if (sizeof($arr_book) > 0) {
					for ($j = 0 ; $j < sizeof($arr_book) ; $j++) {
						
						$B_NO						= trim($arr_book[$j]["B_NO"]);
						$B_CODE					= trim($arr_book[$j]["B_CODE"]);
						$CATE_01				= trim($arr_book[$j]["CATE_01"]);
						$CATE_02				= trim($arr_book[$j]["CATE_02"]);
						$CATE_03				= trim($arr_book[$j]["CATE_03"]);
						$CATE_04				= trim($arr_book[$j]["CATE_04"]);
						$WRITER_NM			= trim($arr_book[$j]["WRITER_NM"]);
						$TITLE					= SetStringFromDB($arr_book[$j]["TITLE"]);
						$REG_ADM				= trim($arr_book[$j]["REG_ADM"]);
						$HIT_CNT				= trim($arr_book[$j]["HIT_CNT"]);
						$REF_IP					= trim($arr_book[$j]["REF_IP"]);
						$MAIN_TF				= trim($arr_book[$j]["MAIN_TF"]);
						$USE_TF					= trim($arr_book[$j]["USE_TF"]);
						$COMMENT_TF			= trim($arr_book[$j]["COMMENT_TF"]);
						$REG_DATE				= trim($arr_book[$j]["REG_DATE"]);
						$UP_DATE				= trim($arr_book[$j]["UP_DATE"]);

						$REG_DATE = date("Y.m.d",strtotime($REG_DATE));
						$UP_DATE	= date("Y.m.d",strtotime($UP_DATE));
			?>
							<tr>
								<td><a href="/manager/board/board_write.php?mode=S&b_code=<?=$B_CODE?>&b_no=<?=$B_NO?>"><?=$CATE_02?></a></td>
								<td><a href="/manager/board/board_write.php?mode=S&b_code=<?=$B_CODE?>&b_no=<?=$B_NO?>"><?=$TITLE?></a></td>
								<td><?=$WRITER_NM?></td>
								<td><?=getDcodeName($conn, "USE_TF", $USE_TF)?></td>
								<td><?=$REG_DATE?></td>
								<td><?=$UP_DATE?></td>
							</tr>
			<?
					} 
			?>

			<?
				} else {
			?>
							<tr>
								<td colspan="10" style="text-align:center">승인 대기 중인 동아리와 책이 없습니다.</td>
							</tr>
			<?
				}
			?>
						</tbody>
					</table>
				</div>


				<div class="sp20"></div>

				<div class="tit_h4 first"><h4>승인 대기 독서문화활동</h4></div>
				<div class="tbl_style01">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="10%" />
							<col width="45%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
									<th>지역</th>
									<th>구분</th>
									<th>제목</th>
									<th>작성자</th>
									<th>노출여부</th>
									<th>등록일</th>
									<th>수정일</th>
							</tr>
						</thead>
						<tbody>
			<?
				if (sizeof($arr_culture) > 0) {
					for ($j = 0 ; $j < sizeof($arr_culture) ; $j++) {
						
						$B_NO						= trim($arr_culture[$j]["B_NO"]);
						$B_CODE					= trim($arr_culture[$j]["B_CODE"]);
						$CATE_01				= trim($arr_culture[$j]["CATE_01"]);
						$CATE_02				= trim($arr_culture[$j]["CATE_02"]);
						$CATE_03				= trim($arr_culture[$j]["CATE_03"]);
						$CATE_04				= trim($arr_culture[$j]["CATE_04"]);
						$WRITER_NM			= trim($arr_culture[$j]["WRITER_NM"]);
						$TITLE					= SetStringFromDB($arr_culture[$j]["TITLE"]);
						$REG_ADM				= trim($arr_culture[$j]["REG_ADM"]);
						$HIT_CNT				= trim($arr_culture[$j]["HIT_CNT"]);
						$REF_IP					= trim($arr_culture[$j]["REF_IP"]);
						$MAIN_TF				= trim($arr_culture[$j]["MAIN_TF"]);
						$USE_TF					= trim($arr_culture[$j]["USE_TF"]);
						$COMMENT_TF			= trim($arr_culture[$j]["COMMENT_TF"]);
						$REG_DATE				= trim($arr_culture[$j]["REG_DATE"]);
						$UP_DATE				= trim($arr_culture[$j]["UP_DATE"]);

						$REG_DATE = date("Y.m.d",strtotime($REG_DATE));
						$UP_DATE	= date("Y.m.d",strtotime($UP_DATE));
			?>
							<tr>
								<td><a href="/manager/board/board_write.php?mode=S&b_code=<?=$B_CODE?>&b_no=<?=$B_NO?>"><?= getDcodeName($conn,"AREA",$CATE_02)?></a></td>
								<td><a href="/manager/board/board_write.php?mode=S&b_code=<?=$B_CODE?>&b_no=<?=$B_NO?>"><?= getDcodeName($conn,"CULTURE_TYPE",$CATE_04)?></a></td>
								<td><?=$TITLE?></td>
								<td><?=$WRITER_NM?></td>
								<td><?=getDcodeName($conn, "USE_TF", $USE_TF)?></td>
								<td><?=$REG_DATE?></td>
								<td><?=$UP_DATE?></td>
							</tr>
			<?
					} 
			?>

			<?
				} else {
			?>
							<tr>
								<td colspan="10" style="text-align:center">승인 대기 중인 독서문화활동이 없습니다.</td>
							</tr>
			<?
				}
			?>
						</tbody>
					</table>
				</div>

				<div class="sp20"></div>

				<div class="tit_h4 first"><h4>승인 대기 책여행</h4></div>
				<div class="tbl_style01">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="10%" />
							<col width="45%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
									<th>지역</th>
									<th>여행지명</th>
									<th>제목</th>
									<th>작성자</th>
									<th>노출여부</th>
									<th>등록일</th>
									<th>수정일</th>
							</tr>
						</thead>
						<tbody>
			<?
				if (sizeof($arr_tour) > 0) {
					for ($j = 0 ; $j < sizeof($arr_tour) ; $j++) {
						
						$B_NO						= trim($arr_tour[$j]["B_NO"]);
						$B_CODE					= trim($arr_tour[$j]["B_CODE"]);
						$CATE_01				= trim($arr_tour[$j]["CATE_01"]);
						$CATE_02				= trim($arr_tour[$j]["CATE_02"]);
						$CATE_03				= trim($arr_tour[$j]["CATE_03"]);
						$CATE_04				= trim($arr_tour[$j]["CATE_04"]);
						$WRITER_NM			= trim($arr_tour[$j]["WRITER_NM"]);
						$INFO_01				= SetStringFromDB($arr_tour[$j]["INFO_01"]);
						$TITLE					= SetStringFromDB($arr_tour[$j]["TITLE"]);
						$REG_ADM				= trim($arr_tour[$j]["REG_ADM"]);
						$HIT_CNT				= trim($arr_tour[$j]["HIT_CNT"]);
						$REF_IP					= trim($arr_tour[$j]["REF_IP"]);
						$MAIN_TF				= trim($arr_tour[$j]["MAIN_TF"]);
						$USE_TF					= trim($arr_tour[$j]["USE_TF"]);
						$COMMENT_TF			= trim($arr_tour[$j]["COMMENT_TF"]);
						$REG_DATE				= trim($arr_tour[$j]["REG_DATE"]);
						$UP_DATE				= trim($arr_tour[$j]["UP_DATE"]);

						$REG_DATE = date("Y.m.d",strtotime($REG_DATE));
						$UP_DATE	= date("Y.m.d",strtotime($UP_DATE));
			?>
							<tr>
								<td><a href="/manager/board/board_write.php?mode=S&b_code=<?=$B_CODE?>&b_no=<?=$B_NO?>"><?= getDcodeName($conn,"AREA",$CATE_02)?></a></td>
								<td><a href="/manager/board/board_write.php?mode=S&b_code=<?=$B_CODE?>&b_no=<?=$B_NO?>"><?= $INFO_01?></a></td>
								<td><?=$TITLE?></td>
								<td><?=$WRITER_NM?></td>
								<td><?=getDcodeName($conn, "USE_TF", $USE_TF)?></td>
								<td><?=$REG_DATE?></td>
								<td><?=$UP_DATE?></td>
							</tr>
			<?
					} 
			?>

			<?
				} else {
			?>
							<tr>
								<td colspan="10" style="text-align:center">승인 대기 중인 책여행이 없습니다.</td>
							</tr>
			<?
				}
			?>
						</tbody>
					</table>
				</div>

<?
	}
?>

				<dl class="main">

			<?
				if (sizeof($arr_rs_menu) > 0) {
					for ($m = 0 ; $m < sizeof($arr_rs_menu); $m++) {
			
						$M_MENU_CD		= trim($arr_rs_menu[$m]["MENU_CD"]);
						$M_MENU_NAME	= trim($arr_rs_menu[$m]["MENU_NAME"]);
						$M_ADMIN_URL	= trim($arr_rs_menu[$m]["MENU_URL"]);

						if (strpos($M_ADMIN_URL, "?") > 0) {
							$str_menu_url = $M_ADMIN_URL."&menu_cd=".$M_MENU_CD;
						} else {
							$str_menu_url = $M_ADMIN_URL."?menu_cd=".$M_MENU_CD;
						}

						if (strlen($M_MENU_CD) == "2") {
							if ($m <> 0) {
			?>
						</ul>
					</dd>
			<?
							}
			?>
					<dt><?=$M_MENU_NAME?></dt>
					<dd>
						<ul>
			<?
						}
						if ((strlen($M_MENU_CD) == "4") && ($M_ADMIN_URL <> "#") && ($M_ADMIN_URL <> "")) {
			?>
							<li><a href="<?=$str_menu_url?>"><?=$M_MENU_NAME?></a></li>
			<?
						}
					}
				}
			?>
						</ul>
					</dd>
				</dl>

			</div>
		</div>
	</div>
</body>
<script>

</script>
</html>
<?
#====================================================================
# DB Close
#====================================================================
	db_close($conn);
?>