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
	<title>마이페이지 | 원주미래산업진흥원</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/WFI.css" />

	<script src="../../assets/js/libs/jquery.min.js"></script>
	<script src="../../assets/js/libs/librurys.min.js"></script>
	<script src="../../assets/js/ui/ui.common.js"></script>

	<!-- 페이지 스크립트 영역 -->
	<script></script>
</head>

<body>
	<!-- 레이아웃 유형구분
		메인: main-wrapper,
		서브: sub-wrapper,
		기타: [기타]-wrapper
	 -->
	<div class="wrapper sub-wrapper">
		<!-- include_header.html -->
		<header class="header">
        <?
			require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
			?>
        </header>
		<!-- // include_header.html -->

		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content notice-list-page">
				<!-- content-header -->
				<div class="content-header">
					<!-- Sub Visual -->
					<div class="visual-wrap visual-05">
						<div class="inner">
							<h1>마이페이지</h1>
							<div class="location">
								<span class="lc-home"><span class="blind">홈</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span>
								<span class="lc-cate"><span>마이페이지</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span> 
								<span class="lc-current" aria-current="page">나의 회원정보</span>
							</div>
						</div>
					</div>
					<!-- // Sub Visual -->
					<!-- Sub Nav -->
					<div class="snb-wrap">
						<nav class="nav dep2-nav">
							<ul class="dep2-list">
								<li class="dep2-item">
									<a href="#" class="dep2-link">나의 회원정보</a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link is-current">나의 예약현황</a>
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
					<div class="member-page">
						<!-- 타이틀 영역 -->
						<div class="title-wrap">
							<h2 class="title">나의 예약 현황</h2> 
							<p class="explain">최대 24개월 내역까지 조회 가능합니다.​​</p>
						</div>
						<!-- // 타이틀 영역 -->

						<div class="mypage-wrap">
							<div class="reservation-info-box">
								<p class="tit">중요안내</p>
								<ul class="bul dot">
									<li>예약 완료 및 예약 확정 안내 문자를 받은 예약 건은 취소가 불가합니다. ​</li>
									<li>예약 취소는 승인 대기 상태에서만 가능하며, 시설 사정으로 예약이 불가할 경우 자동 취소 처리되며 취소 안내 문자를 발송해 드립니다. ​</li>
									<li>자세한 문의는 고객센터에 문의하세요.​</li>
								</ul>
								<p class="call">고객센터 전화 <a href="tel:070-0000-0000">070-0000-0000</a></p>
							</div>
							<div class="board-list-wrap">
								<div class="board-toolbar">
									<p class="board-count">전체 <em>0</em>건의 예약</p>
									<div class="board-srch">
										<span class="set">조회기간 설정</span>
										<div class="frm-sel h-48 w160">
											<select name="" id="frmSelect01"  title="검색 구분" class="sel">
												<option value="">최근 6개월</option>
												<option value="">12개월</option>
												<option value="">24개월</option>
											</select>
										</div>
									</div>
								</div>
								<div class="board-list-reserv">
									<div class="no-list">
										<p>최근 6개월동안 예약 내역이 없습니다.​</p>
									</div>
								</div>
								<div class="board-page">
									<button type="button" class="btn-page-first" disabled><span class="blind">처음 페이지</span></button>
									<button type="button" class="btn-page-prev" disabled><span class="blind">이전 페이지</span></button>
									<!-- Case 현재페이지: is-current 클래스,  aria-current="page" 추가 -->
									<button type="button" class="btn-page-number is-current" aria-current="page">1<span class="blind">&nbsp;페이지</span></button>
									<button type="button" class="btn-page-next"><span class="blind">다음 페이지</span></button>
									<button type="button" class="btn-page-last"><span class="blind">마지막 페이지</span></button>
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
		<footer class="footer"></footer>
		<!-- // include_footer.html -->
	</div>
</body>

</html>