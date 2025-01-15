<? session_start(); ?>
<?
$_PAGE_NO = "30";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/facility/list.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/holiday/holiday.php";

// 로그인 상태 확인
if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
	echo "<script>alert('로그인 상태가 아닙니다')</script>";
	header("Location: /member/login.do");
	exit;
}

$rs_industry_category = "";

// room_no를 GET 파라미터에서 가져오기
$room_no = isset($_GET['room_no']) ? (int) $_GET['room_no'] : null;

// room_no가 있을 경우, 그에 해당하는 데이터를 가져오기
if ($room_no !== null) {
	// room_no를 전달하여 상세 데이터를 가져옵니다.
	$arr_rs = detailMeetingRoom($conn, $room_no);  // room_no를 전달

	//echo "<pre>";
	//print_r($arr_rs);
	//echo "</pre>";

	// room_no에 맞는 데이터가 있는지 확인
	if (isset($arr_rs[$room_no])) {
		$room = $arr_rs[$room_no];  // 상세 정보를 room 변수에 저장

		// 디버깅을 위한 출력
	} else {
		echo "방 정보를 찾을 수 없습니다.";
		exit;
	}
}

// 리저베이션디테일에서 뒤로가기 눌렀을 때 받아오는 form값
$rv_start_time = $_POST['rv_start_time'] ?? '';
$rv_purpose = $_POST['rv_purpose'] ?? '';
$rv_use_count = $_POST['rv_use_count'] ?? '';
$rv_equipment = $_POST['rv_equipment'] ?? '';
$rv_reduction_tf = $_POST['rv_reduction_tf'] ?? '';
$rv_reduction_gam = $_POST['rv_reduction_gam'] ?? '';
$rv_date = $_POST['rv_date'] ?? '';

/*
echo "룸 넘버: " . $room_no . "<br>";
echo "시간 선택: " . $rv_start_time . "<br>";
echo "사용 목적: " . $rv_purpose . "<br>";
echo "사용 인원: " . $rv_use_count . "<br>";
echo "대여기자재 사용: " . $rv_equipment . "<br>";
echo "감면대상자: " . $rv_reduction_tf . "<br>";
echo "감면대상: " . $rv_reduction_gam . "<br>";
echo "예약날짜: " . $rv_date . "<br>";
*/

?>

<script>

/**
	function submitForm() {

		if (frm.rv_reduction_rd) {
				var selectedReduction = document.querySelector('input[name="rv_reduction_rd"]:checked');
				if (selectedReduction) {
						frm.rv_reduction_tf.value = selectedReduction.value;
				}
		}


		if (validateForm()) {
			var form = document.getElementById('form');
			form.submit(); // 유효성 검사를 통과하면 폼을 제출
		} else {
			alert("폼에 필수 항목을 모두 입력해 주세요.");
		}
	} **/
function validateForm() {
    const rvPurpose = document.getElementById('rv_purpose');
    const rvUseCount = document.getElementById('rv_use_count');
    const rvStartTime = document.getElementById('abled_time');
    const rvEquipment = document.querySelector('select[name="rv_equipment"]');
    const rvReductionRadio = document.querySelector('input[name="rv_reduction_rd"]:checked');
    const rvReductionSelect = document.querySelector('select[name="rv_reduction"]');

    if (!rvStartTime || rvStartTime.value.trim() === "") {
        alert("예약 시작 시간을 선택해주세요.");
        rvStartTime.focus();
        return false;
    }

    if (!rvPurpose || rvPurpose.value.trim() === "") {
        alert("사용 목적을 입력해주세요.");
        rvPurpose.focus();
        return false;
    }

    if (!rvUseCount || rvUseCount.value.trim() === "" || isNaN(rvUseCount.value.trim())) {
        alert("사용 인원을 숫자로 입력해주세요.");
        rvUseCount.focus();
        return false;
    }

    if (!rvEquipment || rvEquipment.value.trim() === "") {
        console.log("대여 기자재를 선택하지 않았습니다.");
    } else {
        console.log("선택된 대여 기자재: " + rvEquipment.value);
    }

    if (!rvReductionRadio) {
        alert("감면 대상 여부를 선택해주세요.");
        return false;
    }

    if (rvReductionRadio.value === "Y" && (!rvReductionSelect || rvReductionSelect.value.trim() === "")) {
        alert("감면 대상을 선택해주세요.");
        rvReductionSelect.focus();
        return false;
    }

    return true; // 모든 유효성 검사가 통과되면 true 반환
}

	// 버튼 활성화 함수
	function toggleSubmitButton() {

		var isValid = true;

		// 각 입력 필드 확인
		if (!document.getElementById('rv_purpose').value ||
			!document.getElementById('rv_use_count').value ||
			!document.getElementById('rv_equipment').value ||
			!document.getElementById('rv_date').value ||
			!document.getElementById('rv_start_time').value ||
			!document.querySelector('input[name="rv_reduction_tf"]:checked')) {
			isValid = false;
		}

		// 버튼 활성화/비활성화 설정
		document.getElementById('submitBtn').disabled = !isValid;  // 모든 필드가 채워지지 않으면 버튼 비활성화

	}
	
	//라디오 "아니오"선택 시 셀렉트 박스 비활성화시키닏
	function toggleReductionSelect() {
		const rvReductionSelect = document.querySelector('select[name="rv_reduction"]');
		const rvReductionRadioYes = document.querySelector('input[name="rv_reduction_rd"][value="Y"]');
		const rvReductionRadioNo = document.querySelector('input[name="rv_reduction_rd"][value="N"]');

		if (rvReductionRadioYes.checked) {
			rvReductionSelect.disabled = false;
		} else if (rvReductionRadioNo.checked) {
			rvReductionSelect.disabled = true;
			rvReductionSelect.value = "";
		}
	}

	// 페이지 새로고침 할 때 원복
	document.addEventListener('DOMContentLoaded', function () {
		toggleReductionSelect();

		// 라디오 버튼 클릭 시 상태 변경
		const rvReductionRadios = document.querySelectorAll('input[name="rv_reduction_rd"]');
		rvReductionRadios.forEach(function (radio) {
			radio.addEventListener('change', toggleReductionSelect);
		});
	});

</script>


		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content notice-list-page mo-bg-white"> <!-- mo-bg-white : 모바일에서 백그라운드 white -->
				<!-- content-header -->
				<div class="content-header">
					<?
					require "../_common/content-header.php";
					?>
				</div>
				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 게시판목록 페이지 -->
					<div class="board-list-page">
						<!-- 타이틀 영역 -->
						<!-- <div class="title-wrap">
							<h2 class="title">시설 예약</h2> 
							
						</div> -->
						<!-- // 타이틀 영역 -->

						<div class="board-list-wrap">

							<div class="board-list">
								<div class="gallery-board">
									<div class="detail-wrap">
										<div class="reservation-detail">
											<div class="swiper-container" id="mySwiper" navigation="true">
												<div class="swiper-wrapper">
													<?php foreach ($room['FILES'] as $file): ?>
														<div class="swiper-slide">
															<img src="/upload_data/meetingroom/<?= $file['FILE_NM'] ?>"
																alt="Room Image">
														</div>
													<?php endforeach; ?>
												</div>
												<div class="swiper-button-prev"></div>
												<div class="swiper-button-next"></div>
											</div>
										</div>
										<div class="cont-txt">
											<h3 class="name"><?= $room['ROOM_NAME'] ?></h3>
											<div class="dl-wrap">
												<dl>
													<dt>예약기간</dt>
													<dd><?= $room['ABLE_PERIOD'] ?></dd>
												</dl>
												<dl>
													<dt>취소기간</dt>
													<dd>예약 3일 전</dd>
												</dl>
												<dl>
													<dt>시설규모</dt>
													<dd><?= $room['ROOM_SCALE'] ?></dd>
												</dl>
												<dl>
													<dt>수용인원</dt>
													<dd><?= $room['ROOM_CAPACITY'] ?></dd>
												</dl>
											</div>
											<div class="fee-wrap">
												<dl class="fee">
													<dt>이용료</dt>
													<dd><?= number_format($room['ROOM_PRICE']) ?> 원</dd>
												</dl>
												<dl class="fee">
													<dt>야간 이용료</dt>
													<dd><?= number_format($room['ROOM_NIGHT_PRICE']) ?> 원</dd>
												</dl>
											</div>
										</div>
									</div>

									<div class="detail-wrap">
										<div class="reservation-calendar" id="calendar"></div>

<form name="frm" class="post" method="POST" id="form" action="reservation_confirm.do?room_no=<?= $room_no ?>" onsubmit="return validateForm();">

										<div class="choice-info">
											<div class="choice-block">
												<p class="tit between">시간 선택
													<span class="vat" name="rv_date" id="rv_date" ></span>
													<input type="hidden" name="room_no" id="mr_room_no" value="<?= $room_no ?>">
													<input type="hidden" id="Rrv_date" name="rv_date">
													<input type="hidden" id="Rv_end_time" name="rv_end_time">
												</p>
												<div class="field-wrap">
													<div class="field">
														<div class="labels"><span class="txt require">시작 시간</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-sel h-48" id="rv_start_time">
																	<select name="rv_start_time" id="abled_time" class="sel">
																		<option value="" data-placeholder="true">시작 시간을 선택해주세요.</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="choice-block">
												<p class="tit between">이용자 상세</p>
												<div class="field-wrap">
													<div class="field">
														<div class="labels">
															<span class="txt require">사용 목적</span><!-- 필수입력 시 : require -->
														</div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-inp h-48">
																	<input type="text" name="rv_purpose" id="rv_purpose" placeholder="사용 목적을 입력해주세요.(예:회의, 전시)​" title="" class="inp">
																</div>
															</div>
														</div>
													</div>
													<div class="field">
														<div class="labels">
															<span class="txt require">사용 인원</span><!-- 필수입력 시 : require -->
														</div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-inp h-48">
																	<input type="text" name="rv_use_count" id="rv_use_count" placeholder="사용 인원을 입력해주세요.(숫자만 입력 가능)​" title="" class="inp">
																</div>
															</div>
														</div>
													</div>
													<div class="field">
														<div class="labels"><span name="rv_equipment" id="rv_equipment" class="txt require">대여기자재 사용</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-sel h-48">
																	<?= makeSelectBox2($conn, "EQUIPMENT", "rv_equipment", "", "선택 안 함", "", "") ?>
																</div>
															</div>
														</div>
													</div>
													<div class="field">
														<div class="labels"><span class="txt require">감면대상자</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-rdo">
																	<input type="radio" name="rv_reduction_rd" id="sRadio111" value="Y">
																	<label for="sRadio11"><span>예</span></label>
																</div>
																<div class="frm-rdo">
																	<input type="radio" name="rv_reduction_rd" id="sRadio122" value="N">
																	<label
																		for="sRadio12"><span>아니요</span></label>
																</div>
																<input type="hidden" name="rv_reduction_tf" id="rv_reduction_tf" value="">
															</div>
															<div class="inp-wrap">
																<div class="frm-sel h-48">
																	<?= makeSelectBox2($conn, "GAM", "rv_reduction", "", "감면대상 선택", "", "") ?>
																</div>
															</div>
															<p class="info-txt">대여기자재 및 감면 대상자 관련 사항은 하단의
																약관을 참조바랍니다.​</p>
														</div>
													</div>
												</div>
											</div>
											<!-- 선택항목 모두 체크했을때 노출 -->
											<div class="btn-wrap">
												<a href="reservation_form.do">
													<button type="button" class="btn-basic h-56">
														<span>뒤로가기</span>
													</button>
												</a>
												<a href="">
													<button type="submit" id="submitBtn" class="btn-basic h-56 primary">
														<span>다음 단계</span>
													</button>
												</a>
											</div>
											<!-- //선택항목 모두 체크했을때 노출 -->
										</div>

</form>

									</div>

									<div class="detail-wrap">
										<div class="text-area">
											<p>이용안내 (약관(+기본내용), 첨부파일, 유의사항(취소 관련, 야간 사용료 가산 등), 대여기자재, 위치정보 등)​
												내용 적은 경우 시설 예약 초기 페이지 상단 기본 내용 부분에 공통으로 작성도 고려​</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- // 게시판목록 페이지 -->
				</div>
				<!-- // content-body -->

			</div>
			<!-- // content -->
		</main>
		<!-- // Container -->

		<!-- include_footer.html -->
		<footer class="footer">
			<?
			require "../_common/front_footer.php";
			?>
		</footer>
		<!-- // include_footer.html -->

	</div>
</body>
<script>
	// 비주얼 슬라이드
	var mySwiper = new Swiper('#mySwiper', {
		autoplay: false,
		// {
		// 	delay: 4000,
		// 	disableOnInteraction: false
		// },
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next', // 다음 슬라이드로 이동하는 버튼
			prevEl: '.swiper-button-prev' // 이전 슬라이드로 이동하는 버튼
		},
		// effect: 'fade',
		// fadeEffect: {
		// 	crossFade: true, // 필수
		// },
		// observer: true,  
		// observeParents: true,
		// pagination: {
		// 	el: '.swiper-visual .swiper-pagination',
		// 	clickable: true,
		// 	renderBullet: function(index, className) {
		// 		return `<li class="${className}"><span>${index+1}</span></li>`;
		// 	}
		// },
	});

	//캘린더
	document.addEventListener('DOMContentLoaded', function () {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			titleFormat: function (date) {
				if (date.date.month < 9) {
					return `${date.date.year}. ${"0" + (date.date.month + 1)}`;
				}
				return `${date.date.year}. ${date.date.month + 1}`;
			},
			columnFormat: {
				day: 'M월d일'
			},
			dayCellContent: function (info) {
				return {
					html: info.dayNumberText.replace("일", "").replace("日", "")
				};
			},
			dayCellDidMount: function (info) {
				const today = new Date();
				today.setHours(0, 0, 0, 0);

				const cellDate = new Date(info.date);
				cellDate.setHours(0, 0, 0, 0);

				const threeDaysLater = new Date(today);
				threeDaysLater.setDate(today.getDate() + 3);

				const threeMonthsLater = new Date(today);
				threeMonthsLater.setMonth(today.getMonth() + 3);

				if (cellDate < threeDaysLater || cellDate > threeMonthsLater) {
					info.el.style.backgroundColor = '#f9f9f9';
					info.el.style.color = '#bebebe';
					info.el.style.pointerEvents = 'none';
				}
			},
			dateClick: function (info) {
				if (info.dayEl.style.pointerEvents === 'none') {
					return;
				}
				// 이전에 선택된 모든 날짜의 스타일을 초기화
				var activeElements = document.querySelectorAll('.fc-day');
				activeElements.forEach(function (el) {
					el.style.borderBottom = ''; // 밑줄 제거
				});

				// 현재 선택한 날짜에 빨간색 밑줄 스타일 적용
				info.dayEl.style.borderBottom = '2px solid red';
				;

				const selectedDate = new Date(info.dateStr);
				const year = selectedDate.getFullYear();
				const month = ("0" + (selectedDate.getMonth() + 1)).slice(-2);
				const day = ("0" + selectedDate.getDate()).slice(-2);

				const daysOfWeek = ["일", "월", "화", "수", "목", "금", "토"];
				const weekday = daysOfWeek[selectedDate.getDay()];

				const formattedDate = `${year}년 ${month}월 ${day}일 (${weekday})`;


				const vatElement = document.querySelector('.vat');
				vatElement.textContent = formattedDate;
				vatElement.style.display = 'inline';

				// 날짜 값을 숨겨진 입력 필드에 설정
				var rv_date = document.getElementById('Rrv_date').value = info.dateStr;

				// 예약가능 시간 자기고 오기
				getAbledTime(info.dateStr);

			},
			headerToolbar: {
				left: 'prev',
				center: 'title',
				right: 'next'
			},
			locale: 'ko'
		});
		calendar.render();

		const vatElement = document.querySelector('.vat');
		vatElement.style.display = 'none';

	});

	function getAbledTime(date) {

		$('#abled_time').children('option:not(:first)').remove();

		let room_no = "<?= $room_no ?>";
		let mode = "GET_ABLED_TIME";

		//console.log(date);
		//console.log(room_no);
		//console.log(mode);

		$.ajax({
			type: 'POST',
			url: '/_common/ajax_reservation_dml.php', // AJAX 처리 파일
			data: { mode: mode, room_no: room_no, chk_date: date },
			dataType: 'json',
			success: function (response) {
				if (response.success) {

					//abled_date
					//JSON.stringify(response);
					//console.log('Item: ' + JSON.stringify(response));

					for (var j = 0; j < response.data.length; j++) {

						if (parseInt(response.data[j].RESERVATION_FLAG) <= 0) {
							$("#abled_time").append('<option value="' + response.data[j].DCODE + '">' + response.data[j].DCODE_NM + '</option>');
						} else {
							$("#abled_time").append('<option value="' + response.data[j].DCODE + '" disabled="true" style="color:gray">' + response.data[j].DCODE_NM + ' (예약)</option>');
						}

						//console.log(response.data[j].DCODE_NM);
					}

				} else {
					alert(response.message || '가능한 시간이 없습니다.');
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX 요청 실패:");
				console.error("상태:", status);
				console.error("에러 메시지:", error);
				console.error("응답 데이터:", xhr.responseText); // 서버 응답 확인
				//alert('서버 요청에 실패했습니다. 다시 시도해주세요.');
			}
		});
	}

</script>
</html>