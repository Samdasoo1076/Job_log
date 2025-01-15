<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : Park Chan ho
# Create Date  : 2020-11-17
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
	require "../_classes/biz/statistic/statistic.php";
	require "../_classes/biz/board/board.php";
	require "../_classes/biz/banner/banner.php";

	$rs_qna_count		= getQnACount($conn);
	$rs_enter_count = getEnterInfoCount($conn);

	$arr_time_rs = getTodayTimeStatistic($conn);
	$str_today = "";

	for ($j = 0 ; $j < 25 ; $j++) {

		$t_cnt = 0;

		for ($k = 0 ; $k < sizeof($arr_time_rs) ; $k++) {
			
			$HH = trim($arr_time_rs[$k]["HH"]);
			$UV = trim($arr_time_rs[$k]["UV"]);
			
			if (right("0".$j,2) == trim($HH)) {
				$t_cnt = $UV;
			} 
		}

		if ($str_today == "") {
			$str_today = "".$t_cnt;
		} else {
			$str_today = $str_today.",".$t_cnt;
		}
	}


#====================================================================
# Request Parameter
#====================================================================

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/common_header.php"; 
	
	$s_adm_nm			= protect_xss_v2($_SESSION['s_adm_nm']);

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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

		.cont:after {content:""; display:block; clear:both; overflow:hidden}
		.canvas {-moz-user-select: none;-webkit-user-select: none;-ms-user-select: none;max-width:100%;}
		.canvas_wrap {float:left; width:60%;padding:20px; border:1px solid #ddd; }
		.conBox .conTitle {float:none; padding-left:0; !important;}
		.wrap_chart {width:100%;}
		.chart {float:left; padding-left:1%}
		.chart.count {float:right; width:35%}
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
<script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src="./js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="./js/ui.js"></script>
<script type="text/javascript" src="./js/common.js"></script>
<script type="text/javascript" src="./js/Chart.bundle.js"></script>

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

					<div class="wrap_chart">
						<div class="canvas_wrap">
							<canvas id="myChart"></canvas>
						</div>

<script>

	var ctx = document.getElementById("myChart");
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: [
				"0시", "1시","2시","3시","4시","5시","6시","7시","8시","9시","10시","11시","12시","13시","14시","15시","16시","17시","18시","19시","20시","21시","22시","23시"
			],
			datasets: [{
				label: '금일 방문자 수',
				//방문자수
				data: [
				<?=$str_today?>
				],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)',
					 'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)',
					 'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)',
					 'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true
					}
				}]
			}
		}
	});


</script>
						<div class="chart count">
							<div class="conTitle"><strong>누적 방문자 수</strong> <button onClick="window.open('main_check.php')">확인</button></div>
							<div class="sp5"></div>
							<table class="chart_tbl">
								<colgroup>
									<col width="100">
									<col width="150">
								</colgroup>
								<tr>
									<th>오늘</th>
									<td><?=number_format(getTodayStatistic($conn))?> 명</td>
								</tr>
								<tr>
									<th>어제</th>
									<td><?=number_format(getYesterdayStatistic($conn))?> 명</td>
								</tr>
								<!--
								<tr>
									<th>전체</th>
									<td><?//=number_format(getYearStatistic($conn))?> 명</td>
								</tr>
								-->
							</table>

							<div class="sp25"></div>
							
							<div class="conTitle"><strong>Q&A, 입학자료신청 등록 수</strong></div>
							<div class="sp5"></div>
							<table class="chart_tbl">
								<colgroup>
									<col width="*">
									<col width="30%">
									<col width="30%">
								</colgroup>
								<tr>
									<th>구분</th>
									<th><strong><font color='red'>접수</font></strong></th>
									<th>처리완료</th>
								</tr>
								<tr>
									<th><a href="/manager/board/qna_list.php?b_code=B_1_4">Q&A</a></th>
									<td><strong><font color='red'><?=number_format($rs_qna_count[0]["A1"])?></font></strong></td>
									<td><?=number_format($rs_qna_count[0]["A2"])?></td>
								</tr>
								<tr>
									<th><a href="/manager/enter/enterinfoapply_list.php">입학자료신청</a></th>
									<td><strong><font color='red'><?=number_format($rs_enter_count[0]["A1"])?></font></strong></td>
									<td><?=number_format($rs_enter_count[0]["A2"])?></td>
								</tr>
							</table>


						</div>
					</div>
				</div>


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