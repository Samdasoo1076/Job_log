<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : calendar.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2024-12-03
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
	$menu_right = "FI001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

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
	require "../../_classes/biz/meetingroom/meetingroom.php";

	$room_no	= isset($_POST["room_no"]) && $_POST["room_no"] !== '' ? $_POST["room_no"] : (isset($_GET["room_no"]) ? $_GET["room_no"] : '');
	$year			= isset($_POST["year"]) && $_POST["year"] !== '' ? $_POST["year"] : (isset($_GET["year"]) ? $_GET["year"] : '');
	$month		= isset($_POST["month"]) && $_POST["month"] !== '' ? $_POST["month"] : (isset($_GET["month"]) ? $_GET["month"] : '');

	$arr_rs = selectMeetingRoom($conn, (int)$room_no);

	// 시설 정보 중 시간 대 가지고 오기
	$rs_room_name	= SetStringFromDB($arr_rs[0]["ROOM_NAME"]);
	$rs_use_time	= SetStringFromDB($arr_rs[0]["USE_TIME"]);

	$arr_times = getListDcode($conn, $rs_use_time, "");


	function Get_TotalDays($year,$month) { 
		$day = 1; 
		while(checkdate($month,$day,$year)) { 
			$day ++ ; 
		} 
		$day--; 
		return $day; 
	}

	if(!$year) {
		$year = date("Y",time());
	} 

	if(!$month) {
		$month = date("m",time());
	}

	$sDate = $year."-".$month."-01";

	# Get_TotalDays 함수로 이번달의 총 일자를 구한다
	# 몇일 까지 있는지를 검사한후 쿼리해서 결과 값을 가져온다

	$total_days = Get_TotalDays($year,$month) ;
	$this_date = date("Ymd",strtotime("0 day"));

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "시설 관리 신청불가일 조회", "List");

	if ($month == "01") {
		$pre_year = $year - 1;
		$next_year = $year;
		$pre_month = "12";
		$next_month = "02";
	} else if ($month == "12") {
		$pre_year = $year;
		$next_year = $year + 1;
		$pre_month = "11";
		$next_month = "01";
	} else {
		$pre_year = $year;
		$next_year = $year;
		$pre_month = right("0".((int)$month - 1), 2);
		$next_month = right("0".((int)$month + 1), 2);
	}

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" >

	function re_load() {
		document.frm.submit();
	}

	$(document).on("click", "#btn_prev", function() {
		document.location = "calendar.php?year=<?=$pre_year?>&month=<?=$pre_month?>&room_no=<?=$room_no?>";
	});

	$(document).on("click", "#btn_next", function() {
		document.location = "calendar.php?year=<?=$next_year?>&month=<?=$next_month?>&room_no=<?=$room_no?>";
	});

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

<form name="frm" method="post" action="calendar.php">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="room_no" value="<?=$room_no?>">
<input type="hidden" name="year" value="<?=$year?>">
<input type="hidden" name="month" value="<?=$month?>">

			<div class="cont">
				<div class="tit_h4 first"><h4><?=$rs_room_name?> 예약 불가일 등록하기</h4></div>
				<div class="month-calendar">
					<div class="calendar-top">
						<button type="button" class="btn prev" id="btn_prev">이전</button>
						<strong><?=$year?>년 <?=$month?>월</strong>
						<button type="button" class="btn next" id="btn_next">다음</button>
					</div>
					<table>
						<colgroup>
							<col style="width:auto" />
							<col style="width:14.2%" />
							<col style="width:14.2%" />
							<col style="width:14.2%" />
							<col style="width:14.2%" />
							<col style="width:14.2%" />
							<col style="width:14.2%" />
						</colgroup>
						<thead>
							<tr>
								<th>일요일</th>
								<th>월요일</th>
								<th>화요일</th>
								<th>수요일</th>
								<th>목요일</th>
								<th>금요일</th>
								<th>토요일</th>
							</tr>
						</thead>
						<tbody>
<?
		$we = date("w", strtotime($sDate));

		//echo $we;
		//echo $sDate;

		if ($we == 0) {
			$start_day = 0;
			$end_day = 6;
		} else if ($we == 1) {
			$start_day = -1;
			$end_day = 5;
		} else if ($we == 2) {
			$start_day = -2;
			$end_day = 4;
		} else if ($we == 3) {
			$start_day = -3;
			$end_day = 3;
		} else if ($we == 4) {
			$start_day = -4;
			$end_day = 2;
		} else if ($we == 5) {
			$start_day = -5;
			$end_day = 2;
		} else if ($we == 6) {
			$start_day = -6;
			$end_day = 1;
		}
		
		# 이전달 마지막 날짜 구하기

		if ($month != "01") {
			$pre_month = $month - 1;
			$pre_year = $year;
		} else {
			$pre_month = "12";	
			$pre_year = $year - 1;
		}

		$pre_total_days = Get_TotalDays($pre_year,$pre_month);
		
		$pre_day = $pre_total_days - ($we - 1) ;

		//echo $pre_total_days."<br>";
		//echo $pre_day;

		# 다음달 첫날 구하기

		if ($month != "12") {
			$next_month = $month + 1;
			$next_year = $year;
		} else {
			
			$next_month = "01";	
			$next_year = $year + 1;
		}

		$next_sDate = $next_year."-".$next_month."-01";
		$next_we = date("w", strtotime($next_sDate));

		//echo $next_sDate."<br>";
		//echo $next_we;

		$firstDay = 0;
		$first_week = 1;
		$week_total = 0;
		$month_total = 0;
		$sun_total = 0;
		$mon_total = 0;
		$tus_total = 0;
		$wed_total = 0;
		$thu_total = 0;
		$fri_total = 0;
		$sat_total = 0;

?>
							<tr>

<?
		//echo $total_days+($we-1);

		for ($i = 0 ; $i <= $total_days+($we-1) ; $i++) {
			if (($i == 7) || ($i == 14) || ($i == 21) || ($i == 28) || ($i == 35) || ($i == 42)) {
?>
							</tr>
							<tr>
<?
				$week_total = 0;
			}

			if ($i == $we) {
				$firstDay = 1;
			}

?>
								<td <? if ($firstDay <= 0) {?>class="disabled"<? } ?> <? if ($this_date == $year.$month.right("0".$firstDay,2)) { ?> style="background:#ffe297" <? } ?>>
<?

			if ($firstDay > 0) {

				$str_color = "#666";

				if ($i%7 == 0) {
					$str_color = "red";
				}

				if ($i%7 == 6) {
					$str_color = "orange";
				}

				if (strlen($firstDay) == 1) {
					$day_ = "0".$firstDay;
				} else {
					$day_ = $firstDay;
				}

				$sch_date = $year."-".$month."-".$day_;
				
				if ($firstDay == 1) {
					$str_firstDay = (int)$month."월".$firstDay."일";
				} else {
					$str_firstDay = $firstDay;
				}
				
?>
									<span class="date"><a href="#"><font color="<?=$str_color?>"><?echo $str_firstDay?></font></a></span>
									<div style="width:100%;text-align:center">
										<div class="sp5"></div>
										<? 
											if (sizeof($arr_times) > 0) { 
												for ($l = 0 ; $l < sizeof($arr_times) ; $l++) {
													$dcode = $arr_times[$l]["DCODE"];
													$dname = $arr_times[$l]["DCODE_NM"];
													
													$chk_cnt = cntMeetingRoomDisableDate($conn, $room_no, $sch_date, $dcode);
										?>
										<input type="checkbox" value="<?=$dcode?>" class="time_chk" data-datetime="<?=$sch_date?>^<?=$dcode?>" <?if ($chk_cnt > 0) echo "checked" ?> > <?=$dname?><div class="sp5"></div>
										<? 
												}
											} 
										?>
									</div>
<?						 
				$firstDay++;
			} else {
?>
									<span class="date"><font color="silver"><?echo $pre_day++?></font></span>
<?
				if (strlen($pre_month) == 1) {
					$pre_month_ = "0".$pre_month;
				} else {
					$pre_month_ = $pre_month;
				}

				if ($pre_month_ == "12") {
					$pre_year_ = $year - 1;
				} else {
					$pre_year_ = $year;
				}
				
				$sch_date = $pre_year_."-".$pre_month_."-".($pre_day-1);

?>
									<?//=getHolidayDate($conn, $sch_date);?>
<?
			}

			if ($firstDay-1 == $total_days) {
				if($next_we	!= 0) {
					for ($j = 1; $j < (8 - $next_we); $j++) {
?>
								</td>
								<td class="disabled">
<?
						if (strlen($j) == 1) {
							$j_ = "0".$j;
						} else {
							$j_ = $j;
						}

						if (strlen($next_month) == 1) {
							$next_month_ = "0".$next_month;
						} else {
							$next_month_ = $next_month;
						}

						if ($next_month_ == "01") {
							$next_year_ = $year + 1;
						} else {
							$next_year_ = $year;
						}

						$sch_date = $next_year_."-".$next_month_."-".$j_;

						if ($j == 1) {
							$str_j = (int)$next_month_."월".$j."일";;
						} else {
							$str_j = $j;
						}
?>
									<span class="date"><font color="silver"><?echo $str_j?></font></span>
									<?//=getHolidayDate($conn, $sch_date);?>
<?
					}
				}
			}
?>
								</td>
<?
		}
?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="btn_wrap">
				<? if ($room_no <> "") {?>
				<button type="button" class="button" onClick="js_read()">시설정보가기</button>
				<? } ?>
			</div>

</form>

		</div>
	</div>
</div>
</body>
<script>

	$(document).ready(function() {

		$('.time_chk').change(function() {

			//alert($(this).attr("data-datetime"));
			//alert($(this).prop('checked'));
			let mode = "DATE_TIME_TOGGLE";

			var request = $.ajax({
				url:"ajax_calendar_dml.php",
				type:"POST",
				data:{mode:mode, room_no:"<?=$room_no?>", datetime:$(this).attr("data-datetime"), chk_flag:$(this).prop('checked')},
				dataType:"json"
			});

			request.done(function(data) {
				console.log(data);
			});

		});

	});

	function js_read() {
		document.location = "write.php?mode=S&room_no=<?=$room_no?>";
	}

</script>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
