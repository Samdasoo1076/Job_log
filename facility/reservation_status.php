<? session_start(); ?>
<?
$_PAGE_NO = "108";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>

<head></head>
<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content notice-list-page mo-bg-white">
		<!-- content-header -->
		<div class="content-header">
			<?
			require "../_common/content-header.php";

			?>
			<!-- // Sub Nav -->
		</div>
		<!-- // content-header -->
		<!-- content-body -->
		<div class="content-body">
			<!-- 게시판목록 페이지 -->
			<div class="board-list-page">
				<!-- 타이틀 영역 -->
				<div class="title-wrap">
					<h2 class="title">예약 현황</h2>

				</div>
				<!-- // 타이틀 영역 -->

				<div class="board-list-wrap">

					<div class="board-list">
						<div class="gallery-board">
							<div class="detail-wrap">
								<div class="reservation-calendar" id="calendar"></div>
								<div class="choice-info">
									<div class="choice-block">
										<p class="tit between" id="selectedDate"></p>
										<div class="table-wrap">
											<table>
												<caption>
													시간선택
												</caption>
												<colgroup>
													<col style="width:50%" />
													<col style="width:50%" />
												</colgroup>
												<thead>
													<tr>
														<th>시설명</th>
														<th>예약 시간</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
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

<!-- 예약완료팝업  -->
<div class="modal fade info-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-basic">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <h1 class="modal-title" id="exampleModalLabel">Modal title</h1> -->
				<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal" aria-label="Close"><span
						class="blind">닫기</span></button>
			</div>
			<div class="modal-body">
				<div class="cont center">
					<p class="tit"><em>예약이 완료</em>되었습니다.</p>
					<div class="complete-txt">
						<p class="txt">
							시설 예약 신청서가 제출되었습니다.<br />
							업무일 기준 48시간내 예약자 연락처로 문자 발송드립니다.
						</p>
					</div>
					<div class="info-txt">
						<dl>
							<dt>예약번호</dt>
							<dd>241029C002468​</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="btn-wrap">
					<button type="button" class="btn-basic h-56 primary center-w200">
						<span>확인</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	//캘린더
	document.addEventListener('DOMContentLoaded', function () {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			titleFormat: function (date) {
				// YYYY년 MM월
				if (date.date.month < 9) {
					return `${date.date.year}. ${"0" + (date.date.month + 1)}`;
				}
				return `${date.date.year}. ${date.date.month + 1}`;
			},
			columnFormat: {
				day: 'M월d일'
			},
			dayCellContent: function (info) {
				var number = document.createElement("a");
				number.classList.add("fc-daygrid-day-number");
				number.innerHTML = info.dayNumberText.replace("일", "");
				if (info.view.type === "dayGridMonth") {
					return {
						html: number.outerHTML
					};
				}
			},
			dateClick: function (info) {
				var today = new Date();
				today.setHours(0, 0, 0, 0); // 시간 정보 초기화 (비교를 위해)
				var clickDate = new Date(info.dateStr);

				// 오늘 날짜 이전을 클릭하면 아무 동작도 하지 않음
				if (clickDate < today) {
					alert('올바른 날짜를 선택해주세요/.');
					return;
				}
			
				// 기존 스타일 제거
				document.querySelectorAll('.is-clicked').forEach(el => {
					el.classList.remove('is-clicked');
				});

				// 선택한 날짜에 클래스 추가
				info.dayEl.classList.add('is-clicked');


				fetchReservations(info.dateStr); // AJAX 함수 호출, 클릭된 날짜 정보를 가져와 처리
				updateDisplayedDate(info.dateStr);

				// 오늘 날짜 스타일 제거

				// URL을 클릭된 날짜를 포함하도록 변경
				const newUrl = `${window.location.pathname}?rv_date=${info.dateStr}`;
				window.history.pushState({ path: newUrl }, '', newUrl);
			},
			headerToolbar: {
				left: 'prev',
				center: 'title',
				right: 'next'
			},
			locale: 'ko' // 한국어 설정
		});


		// URL에 rv_date 값 기반 조회
		const queryParams = new URLSearchParams(window.location.search);
		const rvDate = queryParams.get('rv_date');
		const today = new Date().toISOString().slice(0, 10);
		if (rvDate) {
			// URL의 rv_date 값을 기반으로 날짜 설정 및 데이터 가져오기
			calendar.gotoDate(rvDate);
			fetchReservations(rvDate);
			updateDisplayedDate(rvDate); // 화면에 표시되는 날짜 업데이트
		} else {
			// URL에 rv_date 값이 없는 경우 오늘 날짜로 초기화
			calendar.gotoDate(today);
			fetchReservations(today);
			updateDisplayedDate(today);
		}

		calendar.render();
		setTimeout(() => {
			this.calendarComponent.getApi().updateSize();
			const todayElement = calendarEl.querySelector('.fc-day-today');
			if (todayElement) {
				todayElement.classList.add('fc-day-clicked'); // 오늘 날짜에 클래스 추가
				todayElement.dispatchEvent(new CustomEvent('click')); // 오늘 날짜에 클릭 이벤트 발생
			}
		}, 10);
		function fetchReservations(date) {
			$.ajax({
				url: `/_common/ajax_reservation_status.php?rv_date=${date}`,
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					if (response && response.length > 0) {
						updateReservationTable(response);
					} else {
						displayNoReservations();
					}
				},
				error: function (xhr, status, error) {
					console.error('AJAX Error:', error);
					alert('데이터 에러');
				}
			});
		}

		function updateReservationTable(reservations) {
			const tableBody = document.querySelector('.choice-info .table-wrap tbody');
			tableBody.innerHTML = '';
			reservations.forEach(reservation => {
				const row = `
			<tr>
				<td>${reservation.ROOM_NAME}</td>
				<td>${reservation.RV_END_TIME}</td>
			</tr>
		`;
				tableBody.insertAdjacentHTML('beforeend', row);
			});
		}

		function displayNoReservations() {
			const tableBody = document.querySelector('.choice-info .table-wrap tbody');
			tableBody.innerHTML = '<tr><td colspan="2">예약 현황이 없습니다.</td></tr>';
		}

		// 현재 날짜 가져오기 함수
		function updateDisplayedDate(dateStr) {
			var date = new Date(dateStr);
			var options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'short' };
			var formattedDate = date.toLocaleDateString('ko-KR', options)
				.replace(/년 /g, '년 ')
				.replace(/월 /g, '월 ')
				.replace(/일 /g, '일 ');

			// 요일 처리
			var parts = formattedDate.split(' ');
			if (parts.length > 3) {
				var weekday = parts.pop();
				formattedDate = `${parts.join(' ')} (${weekday})`;
			}

			document.querySelector('.tit.between').textContent = formattedDate;
		}

	});
</script>