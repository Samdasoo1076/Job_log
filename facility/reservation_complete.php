<?session_start();?>
<?
$_PAGE_NO = "30";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/facility/list.php";


$rs_industry_category = "";



$room_no = isset($_SESSION['room_no']) ? $_SESSION['room_no'] : null;
?>
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
			<div id="content" class="content notice-list-page">
				<!-- content-header -->
								<!-- content-header -->
				<div class="content-header">
					<?
					require "../_common/content-header.php";

					?>
					<!-- // Sub Nav -->
				</div>
				<!-- // content-header -->
				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 게시판목록 페이지 -->
					<div class="board-list-page">
						<!-- 타이틀 영역 -->
						<div class="title-wrap">
							<h2 class="title"><?= $room_no ?>시설 예약 확인</h2> 
							
						</div>
						<!-- // 타이틀 영역 -->

						<div class="board-list-wrap">

							<div class="board-list">
								<div class="gallery-board">
									<div class="detail-wrap">
										<div class="reservation-detail">
											<img src="../../assets/images/content/img-reservation-room01.jpg">
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
									<div class="btn-wrap center-w200">
									<a href="reservation_detail.do?room_no=<?= $arr_rs['ROOM_INFO']['ROOM_NO'] ?>">
										<button type="button" class="btn-basic h-56">
											<span>뒤로가기</span>
										</button>
									</a>
										<button type="button" class="btn-basic h-56 primary btn-popup" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
											<span>신청서 제출하기</span>
										</button>
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
	<div class="modal fade info-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-basic">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <h1 class="modal-title" id="exampleModalLabel">Modal title</h1> -->
					<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="blind">닫기</span></button>
				</div>
				<div class="modal-body">
					<div class="cont center">
						<p class="tit"><em>예약이 완료</em>되었습니다.</p>
						<div class="complete-txt">
							<p class="txt">
								시설 예약 신청서가 제출되었습니다.<br/>
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
</body>

</html>