<? session_start(); ?>
<?
	$nonce = base64_encode(random_bytes(16));
	header("Content-Security-Policy: script-src 'self' 'nonce-$nonce' https://wfi.or.kr https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://www.googletagmanager.com;");
?>
<?
$_PAGE_NO = "2";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/main/main.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/board/board.php";



// 게시물 가져오기 (4개씩 제한)
$posts = selectBoardList($conn, 1, 4); // 1 페이지, 4개씩 가져오기

$b = "B_1_2";

if ($nPage == 0)
	$nPage = "1";

if (($nPage <> "") && ($b_board_type == "NOTICE")) {
	$cntPage = (int) ($nPage);
	$nPage = (int) ($nPage);
} else {
	$cntPage = (int) ($nPage);
	$nPage = (int) ($nPage);
}

if ($nPageSize <> "") {
	$nPageSize = (int) ($nPageSize);
} else {
	$nPageSize = 20;
}

if ($mobile_is_all == true) {
	$nPageBlock = 5;
} else {
	$nPageBlock = 5;
}

$con_use_tf = "Y";
$con_del_tf = "N";
$m_type = "";

$nListCnt = totalCntBoardFront($conn, $b, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $con_reply_state, $con_use_tf, $con_del_tf, $f, $s);


$arr_rs = listBoardFront($conn, $b, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $con_reply_state, $con_use_tf, $con_del_tf, $f, $s, $nPage, $nPageSize, $nListCnt);
$banner_type = "MAINVISUAL";
$banners = getMainlistBanner($conn, $banner_type);

$reservation_list = ReservationStatusAll($conn);

// echo "<pre>";
// print_r($reservation_list);
// echo "</pre>";
// exit;


?>
<?
?>

<main role="main" class="container">
	<!-- content -->
	<div class="content main-page">
		<h1 class="blind">원주미래산업진흥원 홈</h1>

		<section class="main-content">

			<div id="fullpage" class="section_fullpage">
				<!-- s: 01_visual -->
				<section class="main-visual section sec_01">
					<div class="swiper-visual">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								<div class="swiper-slide">
									<div class="inner">
										<h2>
											새로운 변화, 큰 행복,<br />
											더 큰 원주 실현을 위한 <span class="wbr">Think-Tank</span>
										</h2>
									</div>
									<div class="bg">
										<video class="viewer play" preload="metadata" autoplay playsinline muted loop>
											<source src="/assets/images/main/main-slide-video01.mp4"
												type="video/mp4">
										</video>
									</div>
								</div>
							</div>
						</div>
						<div class="pager-wrap">
							<div class="swiper-pagination"></div>
							<button class="btn-pause"><span class="blind">pause</span></button>
						</div>
					</div>
					<div class="scroll-down">
						<span class="txt">SCROLL DOWN</span>
					</div>

				</section>
				<!-- e: 01_visual -->
				<!-- s: 02_인트로 -->
				<div class="inner section sec_02">
					<div class="sec-inner">
						<div class="flex-row">
							<div class="inner-tit-wrap">
								<p class="category">주요업무</p>
								<h2 class="sec-inner-tit">
									<em>미래와 혁신을 주도하는<br />
										WFI의 주요업무를<br />
										소개합니다.</em>
								</h2>
								<a href="/task/digital_industry.do" class="btn-more-view">
									<span class="txt">더보기</span>
								</a>
							</div>
							<div class="team-card-wrap">
								<ul class="team-card-list">
									<li>
										<a href="/task/digital_industry.do#main_task_01" class="team-card active">
											<p class="num">01</p>
											<p class="subject">미래성장 동력<br />
												확보사업</p>
										</a>
									</li>
									<li>
										<a href="/task/digital_industry.do#main_task_02" class="team-card">
											<p class="num">02</p>
											<p class="subject">산업고도화 및<br />
												경쟁력 강화사업</p>
										</a>
									</li>
									<li>
										<a href="/task/digital_industry.do#main_task_03" class="team-card">
											<p class="num">03</p>
											<p class="subject">융합·혁신 생태계<br />
												조성사업​</p>
										</a>
									</li>
								</ul>
							</div>
							<a href="/task/digital_industry.php" class="btn-more-view">
								<span class="txt">더보기</span>
							</a>
						</div>
					</div>
				</div>
				<!-- e: 02_인트로 -->
				<div class="normalScrollElements section sec_03">
					<div class="bg">
						<img src="/assets/images/main/img-main-bg1.png">
						<img class="mo" src="/assets/images/main/img-main-bg2-mo.png">
					</div>
					<!-- s: 03_소통마당 -->
					<div class="inner community">
						<div class="sec-inner">
							<div class="inner-tit-wrap">
								<h2 class="sec-inner-tit"><em>소통마당</em></h2>
								<p class="sec-inner-txt">새로운 소식을 빠르게 만날 수 있습니다.</p>
							</div>
							<div class="inner-area">
								<div class="notice-wrap">
									<ul class="notice-list">
										<?
										$posts = selectBoardList($conn, 1, 4);
										if (!empty($posts)) {
											foreach ($posts as $post) {
												?>
												<li>
													<a href="/communication/view.do?b=<?= urlencode($post['B_CODE']); ?>&bn=<?= urlencode($post['B_NO']); ?>">
														<span class="group">
															<?php
															if ($post['B_CODE'] === 'B_1_1') {
																echo '공지사항';
															} elseif ($post['B_CODE'] === 'B_1_3') {
																echo '경영공시';
															} else {
																echo $post['B_CODE'];
															}
															?>
														</span>
														<p class="subject"><?= $post['TITLE']; ?></p>
														<span class="date"><?= $post['REG_DATE']; ?></span>
													</a>
												</li>
												<?
											}
										} else {
											?>
											<li>
												<p class="nodata">등록된 게시물이 없습니다.</p>
											</li>
											<?php
										}
										?>
									</ul>

								</div>
								<a href="/communication/?b=B_1_1" class="btn-more-view">
									<span class="txt">더보기</span>
								</a>
							</div>
						</div>
					</div>
					<!-- e: 03_소통마당 -->
					
					
<!-- 					2025-01-21 홍보배너 변경
						<div class="inner adBanner">
							<div class="sec-inner">
								<div class="swiper swiper-ad">
									<div class="swiper-wrapper">
										<? if (!empty($banners)): ?>
											<? foreach ($banners as $banner): ?>
												<div class="swiper-slide">
													<a href="#">
														<img src="/upload_data/banner/<?= $banner['BANNER_IMG'] ?>" alt="<?= $banner['TITLE_NM'] ?>" />
													</a>
												</div>
											<? endforeach; ?>
										<? else: ?>
											<div class="swiper-slide">
												<p>등록된 배너가 없습니다.</p>
											</div>
										<? endif; ?>
									</div>
									<div class="swiper-pagination"></div>
								</div>
							</div>
						</div>
					//홍보배너 변경 -->

					<!-- s: 04_시설임대 -->
					<div class="inner rent">
						<div class="sec-inner">
							<div class="main-rent-wrap">
								<div class="rent-img">
									<img src="/assets/images/main/img-rent-bg.jpg" alt="">
								</div>
								<div class="rent-card">
									<a href="/facility/reservation_form.do" class="btn-more-view">
										<p class="tit">시설 예약</p>
										<p class="txt">회의 및 세미나 등을 위한 공간을 제공합니다.</p>
									</a>
								</div>
							</div>
						</div>
					</div>
					<!-- e: 04_시설임대 -->
					<!-- s: 05_보도자료 -->
					<div class="inner notice">
						<div class="sec-inner">
							<div class="inner-tit-wrap">
								<h2 class="sec-inner-tit"><em>보도자료</em></h2>
								<p class="sec-inner-txt">언론에 보도되고 있는 WFI의 소식을 빠르게 전달합니다.</p>
							</div>
							<div class="inner-area">
								<div class="media-wrap">
									<ul class="media-list">
										<?
										// 배열에서 처음 4개만 가져오도록 제한
										$limited_arr = array_slice($arr_rs, 0, 4);

										if (sizeof($limited_arr) > 0) {
											foreach ($limited_arr as $row) {

												$title = $row["TITLE"];
												$thumbnail = !empty($row["FILE_NM"])
													? "/upload_data/board/B_1_2/" . $row["FILE_NM"]
													: "/assets/images/content/img-media-list-ex01.jpg";
												$reg_date = date("Y.m.d", strtotime($row["REG_DATE"]));
												$b_code = $row['B_CODE'];
												$b_no = $row['B_NO'];
												?>
												<li>
													 <a href="/communication/view.do?b=<?= urlencode($b_code); ?>&bn=<?= urlencode($b_no); ?>">
														<div class="img-wrap">
															<img src="<?= $thumbnail ?>" alt="<?= $title ?>">
														</div>
														<p class="subject"><?= $title ?></p>
														<span class="date"><?= $reg_date ?></span>
													</a>
												</li>
											<?
											}
										} else {
											?>
											<p>등록된 게시물이 없습니다.</p>
										<?
										}
										?>
									</ul>
								</div>
								<a href="/communication/?b=B_1_2" class="btn-more-view">
									<span class="txt">더보기</span>
								</a>
							</div>
						</div>
					</div>
					<!-- e: 05_보도자료 -->

<!-- e: 03_소통마당 -->

<!-- s: 06_푸터 -->

					<div class="inner">
						<footer class="footer">
							<?
							require $_SERVER['DOCUMENT_ROOT'] . "/_common/front_footer.php";
							?>
						</footer>
					</div>
					<!-- e: 06_푸터 -->
				</div>
			</div>
		</section>
		<!-- 2024-12-06 시설 예약현황 구조 수정
					1. reservation-wrap ~ modal-body 까지 모달 구조가 수정됨.
					2. z-index 이슈로 모달위치 변경.
					3. modal-body 내부는 수정 없음
				 -->
		<div class="reservation-wrap">
			<div class="reservation-top">
				<p class="tit">시설 예약현황</p>
				<button type="button" class="calendar-open" data-bs-toggle="modal" data-bs-target="#carendar"
					aria-expanded="true" aria-controls="carendar">
					<span class="blind">열기</span>
				</button>
			</div>
		</div>
	</div>
	</div>
	<!-- // content -->
</main>
<!-- // Container -->
<!-- 시설현황 모달 -->
<div class="reservation-modal modal fade" id="carendar" tabindex="-1" aria-labelledby="carendarModalLabel"
	aria-hidden="true">
	<? require $_SERVER['DOCUMENT_ROOT'] . "/_common/reservation_status_modal.php";?>
</div>
<!-- //시설현황 모달 -->

<!-- 이메일무단수집거부 모달 -->
<div class="modal fade policy-modal" id="mainEmailPolicyModal" tabindex="-1" role="dialog" aria-labelledby="mainEmailPolicyModalLabel" aria-hidden="true">
	<? require $_SERVER['DOCUMENT_ROOT'] . "/_common/email_policy_modal.php";?>
</div>
<!-- // 이메일무단수집거부 모달 -->
<!-- include_footer.html -->
<!-- // include_footer.html -->

	<!-- 홍보배너 팝업 -->
	<!-- <div class="modal fade" id="adModal" tabindex="-1" role="dialog" aria-labelledby="adModalLabel" aria-hidden="true">
		<div class="modal-dialog ad-modal">
			<div class="modal-content">
				<div class="modal-header blind">
					<h3 class="modal-title" id="adModalLabel">배너모음</h3>
				</div>
				<div class="modal-body">
					<div class="swiper swiper-ad">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<a href="#">
									<img src="https://img.freepik.com/premium-vector/anxiety-concept-illustration-mental-disorders-sad-desperate-flat-vector-design_722351-22.jpg?w=826" alt="" />
								</a>
							</div>
							<div class="swiper-slide">
								<a href="#">
									<img src="https://img.freepik.com/free-vector/savings-concept-illustration_114360-1526.jpg?t=st=1736470862~exp=1736474462~hmac=7d078aff48c21b6bb722faf4049d831439531024da5035a3510134b110de04ac&w=826" alt="" />
								</a>
							</div>
						</div>
						<div class="swiper-pagination"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" id="todayModalHidden">오늘 하루 보지않기</button>
					<button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">닫기</button>
				</div>
			</div>
		</div>
	</div> -->
	<!-- // 홍보배너 팝업 -->
</body>
<script type="text/javascript" nonce="<?= $nonce; ?>">
	// 비주얼 슬라이드
	let slideVisual = new Swiper('.swiper-visual .swiper-container', {
		autoplay:
		{
			delay: 4000,
			disableOnInteraction: false
		},
		loop: true,
		// navigation: {
		// 	nextEl: '.swiper-button-next', // 다음 슬라이드로 이동하는 버튼
		// 	prevEl: '.swiper-button-prev' // 이전 슬라이드로 이동하는 버튼
		// },
		effect: 'fade',
		fadeEffect: {
			crossFade: true, // 필수
		},
		// observer: true,
		// observeParents: true,
		pagination: {
			el: '.swiper-visual .swiper-pagination',
			clickable: true,
			renderBullet: function (index, className) {
				return `<li class="${className}"><span>${index + 1}</span></li>`;
			}
		},
	});
	var sw = 0;
	$('.btn-pause').click(function () {
		if (sw == 0) {
			$('.btn-pause').addClass('on');
			slideVisual.autoplay.stop();
			sw = 1;
		} else {
			$('.btn-pause').removeClass('on');
			slideVisual.autoplay.start();
			sw = 0;
		}
	});

	slideVisual.on('slideChange', function () {
		$('.swiper-pagination-bullet').removeClass('current');
		$('.swiper-pagination-bullet').eq(slideVisual.realIndex).addClass('current');
	});


	//시설 예약
	document.addEventListener('DOMContentLoaded', function () {

		const calendarEl = document.getElementById('calendar');
		const today = new Date().toISOString().slice(0, 10); //오늘 날짜 검색
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
					return;
				}
				// 기존 스타일 제거
				document.querySelectorAll('.is-clicked').forEach(el => {
					el.classList.remove('is-clicked');
				});

				// 선택한 날짜에 클래스 추가
				info.dayEl.classList.add('is-clicked');

				fetchReservations(info.dateStr); // AJAX 함수 호출, 클릭된 날짜 정보를 가져와 처리

			},
			headerToolbar: {
				left: 'prev',
				center: 'title',
				right: 'next'
			},
			locale: 'ko' // 한국어 설정
		});
		calendar.render();

		setTimeout(() => {
			//this.calendarComponent.getApi().updateSize();
			const todayElement = calendarEl.querySelector('.fc-day-today');
			if (todayElement) {
				todayElement.classList.add('fc-day-clicked'); // 오늘 날짜에 클래스 추가
				todayElement.dispatchEvent(new CustomEvent('click')); // 오늘 날짜에 클릭 이벤트 발생
			}
		}, 10);

		fetchReservations(today);

		// 예약 현황 데이터 가져오기
		function fetchReservations(date) {
			$.ajax({
				url: `/_common/ajax_reservation_status.php?rv_date=${date}`,
				type: 'GET',
				dataType: 'json',
				success: function (data) {
					updateReservationChoice(data);
				},
				error: function () {
					alert('예약 데이터를 가져오는 중 오류가 발생했습니다.');
				}
			});
		}
		// 예약 현황 업데이트
		function updateReservationChoice(reservations) {
			const choiceWrap = document.querySelector('.reservation-choice-wrap .reservation-choice');

			choiceWrap.innerHTML = reservations.length
				? reservations.map(res => `
					<div class="radio_area">
						<input type="radio" name="it_radio" id="it_radio_${res.ID}">
						<label for="it_radio_${res.ID}">
							<p class="title">${res.ROOM_NAME}</p>
							<div class="cont">
								<span class="txt">시설규모 : ${res.ROOM_SCALE}</span>
								<span class="txt">수용인원 : ${res.ROOM_CAPACITY}</span>
							</div>
							<p class="price">이용료 : ${Number(res.RV_COST).toLocaleString('ko-KR')}원</p>
						</label>
					</div>
				`).join('')
				: '<p>예약된 시설이 없습니다.</p>';
		}
	});

	document.querySelector('.calendar-open').addEventListener('click', function () {
		document.querySelector('.reservation-top').classList.toggle('open');
	});

	document.addEventListener("DOMContentLoaded", function () {
		const choiceWrap = document.querySelector('.reservation-choice-wrap');

		// 예약 선택 영역에 이벤트 리스너 추가
		choiceWrap.addEventListener('click', function (event) {
			// 'radio_area' 클래스를 가진 요소에서 클릭 이벤트가 발생했을 경우
			if (event.target.closest('.radio_area')) {
				// 이벤트 전파 중지
				event.stopPropagation();
				// 클릭 이벤트 무시
				event.preventDefault();
				console.log('이 요소에서는 클릭 이벤트가 동작하지 않습니다.');
			}
		}, true); // 이벤트 캡처링 사용
	});


	document.addEventListener("DOMContentLoaded", function () {
		document.getElementById("btnReservationStatus").addEventListener("click", function () {
			window.location.href = "/facility/reservation_status.do";
		});

		document.getElementById("btnReservationForm").addEventListener("click", function () {
			window.location.href = "/facility/reservation_form.do";
		});
	});

	//시설예약


	//sec_02 호버 백그라운드 변경
	document.addEventListener('DOMContentLoaded', function () {
		// sec_02 섹션 가져오기
		const sec02 = document.querySelector('.sec_02');


		// 해상도가 768px 이상일 때만 실행
		if ($(window).width() > 767) {
			// 기본 배경 이미지 설정
			const defaultBackgroundImage = "url('/assets/images/main/img-sec_02-bg1.jpg')";
			sec02.style.backgroundImage = defaultBackgroundImage;
			sec02.style.backgroundSize = 'cover';
			sec02.style.backgroundPosition = 'center';
			sec02.style.transition = '.2s background';

			// 모든 team-card 항목 가져오기
			const teamCards = document.querySelectorAll('.team-card');

			// 각 team-card 항목에 이벤트 리스너 추가
			teamCards.forEach((card, index) => {
				card.addEventListener('mouseover', () => {
					// 각 card에 따라 다른 배경 이미지를 적용
					switch (index) {
						case 0:
							sec02.style.backgroundImage = "url('/assets/images/main/img-sec_02-bg1.jpg')"; // 첫 번째 카드 이미지
							break;
						case 1:
							sec02.style.backgroundImage = "url('/assets/images/main/img-sec_02-bg2.jpg')"; // 두 번째 카드 이미지
							break;
						case 2:
							sec02.style.backgroundImage = "url('/assets/images/main/img-sec_02-bg3.jpg')"; // 세 번째 카드 이미지
							break;
						default:
							sec02.style.backgroundImage = defaultBackgroundImage; // 기본 이미지
					}
					sec02.style.backgroundSize = 'cover';
					sec02.style.backgroundPosition = 'center';

					// team-card에 active 클래스 추가
					card.classList.add('active');
				});

				// 마우스가 빠져나가면 디폴트 배경 이미지로 복원 및 active 클래스 제거
				card.addEventListener('mouseout', () => {
					sec02.style.backgroundImage = defaultBackgroundImage;

					// team-card의 active 클래스 제거
					card.classList.remove('active');
				});
			});
		}
	});
	
	//s: 2025-01-21 홍보배너 변경
	document.addEventListener("DOMContentLoaded", function() {
		// 비주얼 슬라이드
		let adSwiper = new Swiper('.swiper-ad', {
			autoplay: {
				delay: 4000,
				disableOnInteraction: false
			},
			loop: true,
			pagination: {
				el: '.swiper-ad .swiper-pagination',
			},
		});
	});

    //s: 홍보배너
	document.addEventListener("DOMContentLoaded", function() {
		// 퍼블리싱 바로 볼 수 있도록 호출
		var adModalElement = document.getElementById("adModal");
		var adModal = new bootstrap.Modal(adModalElement);

		// 쿠키 확인
		if (!getCookie("hideAdModal")) {
			adModal.show();
		}

		// "오늘 하루 보지 않기" 버튼 클릭 이벤트
		document.getElementById("todayModalHidden").addEventListener("click", function () {
			// 쿠키 설정: hideAdModal=1; expires=오늘 자정까지
			setCookie("hideAdModal", "1", 1); // 1은 하루 유지 의미
			adModal.hide(); // 모달 닫기
		});

		// 모달이 표시된 후 실행할 콜백
		adModalElement.addEventListener("shown.bs.modal", function () {
			// 비주얼 슬라이드
			let adSwiper = new Swiper('.swiper-ad', {
				autoplay: {
					delay: 4000,
					disableOnInteraction: false
				},
				loop: true,
				pagination: {
					el: '.swiper-ad .swiper-pagination',
				},
			});
			console.log('show', adSwiper);
		});
	});

	// 쿠키 설정 함수
	function setCookie(name, value, days) {
		var date = new Date();
		date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000); // 하루
		var expires = "expires=" + date.toUTCString();
		document.cookie = name + "=" + value + ";" + expires + ";path=/";
	}

	// 쿠키 가져오기 함수
	function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(";");
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === " ") c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}
	//e: 홍보배너




</script>