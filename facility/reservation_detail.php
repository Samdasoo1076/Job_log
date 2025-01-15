<? session_start(); ?>
<?
$_PAGE_NO = "110";

// 로그인 상태 확인
if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
	echo "<script>alert('로그인 상태가 아닙니다')</script>";
	header("Location: /member/login.do");
	exit;
}

require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/facility/list.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/holiday/holiday.php";

$rs_industry_category = "";

$room_no = isset($_GET['room_no']) ? (int) $_GET['room_no'] : null;

if ($room_no !== null) {

	$arr_rs = detailMeetingRoom($conn, $room_no);

	if (isset($arr_rs[$room_no])) {
		$room = $arr_rs[$room_no];

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
	
	
		function checkDateSelection() {
			const rvDateField = document.getElementById("Rrv_date"); // 숨겨진 날짜 필드

			if (!rvDateField.value.trim()) { // 날짜가 선택되지 않은 경우
				alert("날짜를 선택해주세요.");
				document.getElementById("abled_time").blur(); // 포커스를 제거하여 드롭다운 닫기
			}
		}

	
	function validateForm() {
		const rvStartTime = document.getElementById('abled_time');
		const rvEndTime = document.getElementById('Rv_end_time');
		const rvPurpose = document.getElementById('rv_purpose');
		const rvUseCount = document.getElementById('rv_use_count');
		const rvReductionRadio = document.querySelector('input[name="rv_reduction_rd"]:checked');
		const rvReductionSelect = document.querySelector('select[name="rv_reduction"]');
		const rvReductionTF = document.getElementById('rv_reduction_tf'); 

		// 시작 시간 검증
		if (rvStartTime.value.trim() === "") {
			alert("예약 시간을 선택해주세요.");
			rvStartTime.focus();
			return false;
		}

		// 사용 목적 검증
		if (rvPurpose.value.trim() === "") {
			alert("사용 목적을 입력해주세요.");
			rvPurpose.focus();
			return false;
		}

		// 사용 인원 검증
		if (rvUseCount.value.trim() === "" || isNaN(rvUseCount.value.trim())) {
			alert("사용 인원을 숫자로 입력해주세요.");
			rvUseCount.focus();
			return false;
		}

		// 감면 대상자 검증
		if (!rvReductionRadio) {
			alert("감면 대상 여부를 선택해주세요.");
			return false;
		}

		if (rvReductionRadio.value === "Y" && rvReductionSelect.value.trim() === "") {
			alert("감면 대상을 선택해주세요.");
			rvReductionSelect.focus();
			return false;
		}

		rvReductionTF.value = rvReductionSelect.value.trim() !== "" ? "Y" : "N";


		console.log("rv_end_time 설정:", rvEndTime.value);

		return true;
	}




	// 버튼 활성화 함수
	function toggleSubmitButton() {

		var isValid = true;

		if (!document.getElementById('rv_purpose').value ||
			!document.getElementById('rv_use_count').value ||
			!document.getElementById('rv_equipment').value ||
			!document.getElementById('rv_date').value ||
			!document.getElementById('rv_start_time').value ||
			!document.querySelector('input[name="rv_reduction_tf"]:checked')) {
			isValid = false;
		}

		document.getElementById('submitBtn').disabled = !isValid;  // 모든 필드가 채워지지 않으면 버튼 비활성화

	}


	function toggleReductionSelect() {
		const rvReductionSelect = document.querySelector('select[name="rv_reduction"]');
		const rvReductionRadioYes = document.querySelector('input[name="rv_reduction_rd"][value="Y"]');
		const rvReductionRadioNo = document.querySelector('input[name="rv_reduction_rd"][value="N"]');

		if (rvReductionRadioYes.checked) {
			rvReductionSelect.disabled = false;
		} else if (rvReductionRadioNo.checked) {
			rvReductionSelect.disabled = true;
			rvReductionSelect.value = ""; // 선택 초기화
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		const rvReductionSelect = document.querySelector('select[name="rv_reduction"]');
		const rvReductionRadioYes = document.querySelector('input[name="rv_reduction_rd"][value="Y"]');
		const rvReductionRadioNo = document.querySelector('input[name="rv_reduction_rd"][value="N"]');
		const rvEquipmentSelect = document.querySelector('select[name="rv_equipment"]');

		rvReductionSelect.addEventListener('change', function () {
			if (this.value.trim() === "" || this.value === "감면대상 선택") {

				rvReductionRadioNo.checked = true;
				rvReductionSelect.disabled = true;
				rvReductionSelect.value = "";
			} else {
				rvReductionRadioYes.checked = true;
				rvReductionSelect.disabled = false;
			}
			toggleReductionSelect();
		});

		const rvReductionRadios = document.querySelectorAll('input[name="rv_reduction_rd"]');
		rvReductionRadios.forEach(function (radio) {
			radio.addEventListener('change', toggleReductionSelect);
		});

		toggleReductionSelect();
	});

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

    function handleSelectChange(selectElement, defaultValues) {
        selectElement.addEventListener('change', function () {
            const selectedValue = this.value.trim();

            if (defaultValues.includes(selectedValue)) {
                alert("이미 선택된 값입니다.");

                this.blur();

                this.selectedIndex = 0;
            }
        });
    }

    // "감면대상 선택"과 "선택안함"을 체크
    handleSelectChange(rvEquipmentSelect, ["", "선택 안 함"]);
    handleSelectChange(rvReductionSelect, ["", "감면대상 선택"]);

////////////////////////////////////////////////////////////////////////////////


function hideElement(elementId) {
    document.getElementById(elementId).style.display = "none"; // 숨김
}

function showElement(elementId) {
    document.getElementById(elementId).style.display = "block"; // 표시
}


document.addEventListener("DOMContentLoaded", function () {
    hideElement("timeSelection");
    hideElement("purposeSelection");
    hideElement("countSelection");
});


function getAbledTime(date) {
    $('#abled_time').children('option:not(:first)').remove();

    let room_no = "<?= $room_no ?>";
    let mode = "GET_ABLED_TIME";

    $.ajax({
        type: 'POST',
        url: '/_common/ajax_reservation_dml.php',
        data: { mode: mode, room_no: room_no, chk_date: date },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                for (var j = 0; j < response.data.length; j++) {
                    if (parseInt(response.data[j].RESERVATION_FLAG) <= 0) {
                        $("#abled_time").append('<option value="' + response.data[j].DCODE + '">' + response.data[j].DCODE_NM + '</option>');
                    } else {
                        $("#abled_time").append('<option value="' + response.data[j].DCODE + '" disabled style="color:gray">' + response.data[j].DCODE_NM + ' (예약불가)</option>');
                    }
                }

                // 시간 선택 필드 표시
                showElement("timeSelection");
            } else {
                alert(response.message || '가능한 시간이 없습니다.');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX 요청 실패:", error);
        }
    });
}


document.getElementById('abled_time').addEventListener('change', function () {
    const selectedTime = this.value;
    if (selectedTime) {
        showElement("purposeSelection");
    }
});


document.getElementById('rv_purpose').addEventListener('input', function () {
    const purpose = this.value.trim();
    if (purpose) {
        showElement("countSelection");
    }
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
													<img src="/upload_data/meetingroom/<?= $file['FILE_NM'] ?>" alt="Room Image">
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
										<!-- <dl>
											<dt>취소기간</dt>
											<dd>예약 3일 전</dd>
										</dl> -->
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
											<dd>
												<input type="hidden" name="ROOM_NIGHT_PRICE" value="<?= $room['ROOM_NIGHT_PRICE'] ?>">
											</dd>

										</dl>
									</div>
								</div>
							</div>

							<div class="detail-wrap">
								<!-- 범례 추가를 위한 그룹 -->
								<div class="reservation-calendar-wrap">
									<div class="reservation-calendar" id="calendar"></div>

									<!-- 범례 추가 -->
									<div class="calendar-legend">
										<span class="legend-clicked">선택</span>
										<span class="legend-disabled">예약불가</span>
									</div>
								</div>

								<form class="choice-info" name="frm" class="post" method="POST" id="form" action="reservation_confirm.do?room_no=<?= $room_no ?>" onsubmit="return validateForm();">
									<div class="choice-block">
										<p class="tit between">시간 선택
											<span class="vat" name="rv_date" id="rv_date">날짜를 선택해주세요.</span>
											<input type="hidden" name="room_no" id="mr_room_no" value="<?= $room_no ?>">
											<input type="hidden" id="Rrv_date" name="rv_date" value="">
											<input type="hidden" id="Rv_end_time" name="rv_end_time">
										</p>
										<div class="field-wrap">
											<div class="field">
												<div class="labels"><span class="txt require">시간 선택</span></div>
												<div class="info-inp-wrap">
													<div class="inp-wrap">
														<div class="frm-sel h-48" id="rv_start_time">
															<select name="rv_start_time" id="abled_time" class="sel">
																<option value="" data-placeholder="true">시간을 선택해주세요.</option>
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
													<span class="txt require">사용
														목적</span><!-- 필수입력 시 : require -->
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
												<div class="labels"><span name="rv_equipment" id="rv_equipment"
														class="txt require">대여기자재 사용</span></div>
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
															<input type="radio" name="rv_reduction_rd" id="sRadio11" value="Y">
															<label for="sRadio11"><span>예</span></label>
														</div>
														<div class="frm-rdo">
															<input type="radio" name="rv_reduction_rd" id="sRadio12" value="N" checked>
															<label for="sRadio12"><span>아니요</span></label>
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
										<button type="button" class="btn-basic h-56" onclick="window.location.href='/facility/reservation_form.do'">
											<span>뒤로가기</span>
										</button>
										<button type="submit" id="submitBtn" class="btn-basic h-56 primary">
											<span>다음단계</span>
										</button>
									</div>
									<!-- //선택항목 모두 체크했을때 노출 -->
								</form>
							</div>

							<div class="detail-wrap">
								<div class="text-area" style="max-width:900px;margin-left: 20px;text-align:left">
									<!--
									<p>ㅇ 대관신청시 첨부된 '대관시설 사용 신청서'를 작성하여 사전에 제출해주시기 바랍니다.</p>
									<p>ㅇ 대관사용 취소 또는 변경시 '대관사용변경신청서'를 사전에 제출하지 않을 경우 대관비용이 발생할 수 있습니다.</p>
									-->
									<p>ㅇ 별표에 따라 사용료 감면대상에 해당될 경우 '사용료 감면 신청서'를 담당자에게 제출해주시기 바랍니다.</p>
									<p>ㅇ 시설물, 기자재 문제 발생할 경우 담당자에게 전달해주세요. 분실 파손 시 원상 복구 혹은 배상 책임이 있습니다.</p>
									<p>ㅇ 창업지원허브 건물 안에서는 화기 엄금입니다. 안전을 위해 모든 공간에서 불씨를 일으킬만한 어떤 행위도 허용되지 않습니다.</p>
									<p>ㅇ 기타 문의, 외부기자재 사용 및 운반건 등 사전 협의가 필요한 사항들은 미리 유선확인(033-764-3160) 확인 후 이용하시길 바랍니다.</p>
									<p>ㅇ 미리 협의가 없으면 제한되는 부분이 있을 수 있으니 유의하시어 불편함 없으시길 당부 드립니다.​</p>
									<p style="padding-top:10px;margin-left: 10px;"><font color="red"><b>* 담당자연락처: 033-764-3160 / woody@wfi.or.kr</b></font></p>
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
		// 		return <li class="${className}"><span>${index+1}</span></li>;
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
				// 기존 스타일 제거
				document.querySelectorAll('.is-clicked').forEach(el => {
					el.classList.remove('is-clicked');
				});

				// 선택한 날짜에 클래스 추가
				info.dayEl.classList.add('is-clicked');


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

		//const vatElement = document.querySelector('.vat');
		//vatElement.style.display = 'none';
		
		const vatElement = document.getElementById('rv_date');
		vatElement.textContent = "날짜를 선택해주세요.";

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


					for (var j = 0; j < response.data.length; j++) {

						if (parseInt(response.data[j].RESERVATION_FLAG) <= 0) {
							$("#abled_time").append('<option value="' + response.data[j].DCODE + '">' + response.data[j].DCODE_NM + '</option>');
						} else {
							$("#abled_time").append('<option value="' + response.data[j].DCODE + '" disabled="true" style="color:gray">' + response.data[j].DCODE_NM + ' (예약불가)</option>');
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

		document.getElementById('abled_time').addEventListener('change', function () {
			var selectedOption = this.options[this.selectedIndex];
			// 선택된 옵션의 텍스트를 Rv_end_time 필드에 설정
			document.getElementById('Rv_end_time').value = selectedOption.text;
		});
	}




</script>

</html>