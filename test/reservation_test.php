<?session_start();?>
<?
$_PAGE_NO = "30";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/facility/list.php";

// GET 파라미터로 room_no를 가져오기
$room_no = isset($_GET['room_no']) ? (int) $_GET['room_no'] : null;

// room_no가 있을 경우, 그에 해당하는 데이터를 가져오기
if ($room_no !== null) {
    $arr_rs = detailMeetingRoom($conn, $room_no);  // room_no를 전달

    // room_no에 맞는 데이터가 있는지 확인
    if (isset($arr_rs[$room_no])) {
        $room = $arr_rs[$room_no];
    } else {
        echo "방 정보를 찾을 수 없습니다.";
        exit;
    }
} else {
    echo "잘못된 방 번호입니다.";
    exit;
}
?>

<!-- <pre>
<?php print_r($arr_rs); ?>
</pre>
 -->

<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport"
		content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="keywords" content="원주미래산업진흥원" />
	<meta name="description" content="원주미래산업진흥원" />
	<title>시설안내 | 원주미래산업진흥원</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/WFI.css" />

	<script src="../../assets/js/libs/jquery.min.js"></script>
	<script src="../../assets/js/libs/librurys.min.js"></script>
	<script src="../../assets/js/ui/ui.common.js"></script>
	<script src="../../assets/js/ui/ui.publish.js"></script><!-- 퍼블리싱 전용 (개발적용안함) -->

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

	<!-- Swiper Librarys -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<!-- fullcalendar -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
	<script src="../../assets/js/libs/index.global.js"></script>
	<script src="../../assets/js/libs/index.global.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.js"></script>
	<!-- 페이지 스크립트 영역 -->
	<script>

	</script>
</head>

<body>
	<!-- 레이아웃 유형구분
		메인: main-wrapper,
		서브: sub-wrapper,
		기타: [기타]-wrapper
	 -->
	<div class="wrapper sub-wrapper">
		<!-- include_header.html -->
		<header class="header"></header>
		<!-- // include_header.html -->

		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content notice-list-page mo-bg-white"> <!-- mo-bg-white : 모바일에서 백그라운드 white -->
				<!-- content-header -->
				<div class="content-header">
					<!-- Sub Visual -->
					<div class="visual-wrap visual-04">
						<div class="inner">
							<h1>시설안내</h1>
							<div class="location">
								<span class="lc-home"><span class="blind">홈</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span>
								<span class="lc-cate"><span>시설안내</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span> 
								<span class="lc-current" aria-current="page">시설 예약</span>
							</div>
						</div>
					</div>
					<!-- // Sub Visual -->
					<!-- Sub Nav -->
					<div class="snb-wrap">
						<nav class="nav dep2-nav swiper-container">
							<ul class="dep2-list swiper-wrapper">
								<li class="dep2-item swiper-slide">
									<a href="#" class="dep2-link is-current">시설 예약</a>
								</li>
								<li class="dep2-item swiper-slide">
									<a href="#" class="dep2-link">예약 현황</a>
								</li>
								<li class="dep2-item swiper-slide">
									<a href="#" class="dep2-link">시설 임대</a>
								</li>
							</ul>
						</nav>
					</div>
					<!-- // Sub Nav -->
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
											<div class="swiper-container" navigation="true">
												<div class="swiper-wrapper">
													<div class="swiper-slide"><!-- <img src="../../assets/images/content/img-reservation-room01.jpg"> -->이것은 사진이여..</div>
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
											</div>
										</div>
									</div>

									<div class="detail-wrap">
										<div class="reservation-calendar" id="calendar"></div>
										<div class="choice-info">
											<div class="choice-block">
												<p class="tit between">시간 선택
													<span class="vat">2024년 12월 14일 (목)</span>
												</p>
												<div class="field-wrap">
													<div class="field">
														<div class="labels"><span class="txt require">시작 시간</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-sel h-48">
																	<select name="" title="검색 구분" class="sel">
																		<option value="" data-placeholder="true">시작 시간을 선택해주세요.</option>
																		<option value="">09:00</option>
																		<option value="">10:00</option>
																		<option value="">11:00</option>
																		<option value="">12:00</option>
																		<option value="">13:00</option>
																		<option value="">14:00</option>
																		<option value="">15:00</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
													<div class="field">
														<div class="labels"><span class="txt require">종료 시간</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-sel h-48">
																	<select name="" title="검색 구분" class="sel">
																		<option value="" data-placeholder="true">종료 시간을 선택해주세요.</option>
																		<option value="">10:00</option>
																		<option value="">11:00</option>
																		<option value="">12:00</option>
																		<option value="">13:00</option>
																		<option value="">14:00</option>
																		<option value="">15:00</option>
																		<option value="">16:00</option>
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
																	<input type="text" name="" id="" placeholder="사용 목적을 입력해주세요.(예:회의, 전시)​" title="" class="inp">
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
																	<input type="text" name="" id="" placeholder="사용 인원을 입력해주세요.(숫자만 입력 가능)​" title="" class="inp">
																</div>
															</div>
														</div>
													</div>
													<div class="field">
														<div class="labels"><span class="txt require">대여기자재 사용</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-sel h-48">
																	<select name="" title="검색 구분" class="sel">
																		<option value="" data-placeholder="true">사용하지 않음</option>
																		<option value="" >콤보상자1</option>
																		<option value="">콤보상자2</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
													<div class="field">
														<div class="labels"><span class="txt require">감면대상자</span></div>
														<div class="info-inp-wrap">
															<div class="inp-wrap">
																<div class="frm-rdo">
																	<input type="radio" name="sRadio1" id="sRadio11" checked>
																	<label for="sRadio11"><span>예</span></label>
																</div>
																<div class="frm-rdo">
																	<input type="radio" name="sRadio1" id="sRadio12">
																	<label for="sRadio12"><span>아니요</span></label>
																</div>
															</div>
															<div class="inp-wrap">
																<div class="frm-sel h-48">
																	<select name="" title="검색 구분" class="sel">
																		<option value="" data-placeholder="true">A.농업, 임업 및 어업(01~03)</option>
																		<option value="" >콤보상자1</option>
																		<option value="">콤보상자2</option>
																	</select>
																</div>
															</div>
															<p class="info-txt">대여기자재 및 감면 대상자 관련 사항은 하단의 약관을 참조바랍니다.​</p>
														</div>
													</div>
												</div>
											</div>
											<!-- 선택항목 모두 체크했을때 노출 -->
											<div class="btn-wrap">
											<a href="reservation_form.php">
												<button type="button" class="btn-basic h-56">
													<span>뒤로가기</span>
												</button>
												</a>
												<button type="button" class="btn-basic h-56 primary" disabled>
													<span>다음 단계</span>
												</button>
											</div>
											<!-- //선택항목 모두 체크했을때 노출 -->
										</div>
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
	let slidedetail = new Swiper('.reservation-detail .swiper-container', {
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
	document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			titleFormat: function (date) {
                // YYYY년 MM월
                if ( date.date.month < 9){
                    return `${date.date.year}. ${"0"+(date.date.month + 1)}`;
                }
                return `${date.date.year}. ${date.date.month + 1}`;
            },
			columnFormat: {
				day: 'M월d일'
			},
			dayCellContent: function(info) {
				var number = document.createElement("a");
				number.classList.add("fc-daygrid-day-number");
				number.innerHTML = info.dayNumberText.replace("일", "").replace("日", "");
				if (info.view.type === "dayGridMonth") {
					return {
						html: number.outerHTML
					};
				}
			},
			dateClick: function() {
				alert('a day has been clicked!');
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
			this.calendarComponent.getApi().updateSize();
		});
    });

	//choice-block 시간선택 체크하면 활성화
	const possibleCheckboxes = document.querySelectorAll('.table-wrap tr.possible .chk');

	// 체크박스 상태 변경 이벤트 등록
	possibleCheckboxes.forEach(checkbox => {
		checkbox.addEventListener('change', function () {
			// 체크박스의 부모 tr 요소 찾기
			const parentRow = this.closest('tr');
			
			if (this.checked) {
				// 체크되면 'on' 클래스 추가
				parentRow.classList.add('on');
			} else {
				// 체크 해제되면 'on' 클래스 제거
				parentRow.classList.remove('on');
			}
		});
	});

	//.possible 안붙어있으면 비활성화
	// 모든 tr 요소를 선택
	const allRows = document.querySelectorAll('tr');

	// 각 행을 순회
	allRows.forEach(row => {
		// 현재 행에 'possible' 클래스가 있는지 확인
		if (!row.classList.contains('possible')) {
			// 'possible' 클래스가 없으면 .chk 체크박스를 비활성화
			const checkbox = row.querySelector('.chk');
			if (checkbox) {
				checkbox.disabled = true;
			}
		}
	});
</script>

</html>