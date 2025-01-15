<? session_start();
$_PAGE_NO = "105";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc_dev.php";
$page_header_type = 'search_list';
http://35.216.92.39/comm/search_nolist.php
?>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport"
		content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="keywords" content="원주미래산업진흥원" />
	<meta name="description" content="원주미래산업진흥원" />
	<title>공고안내 목록 | 원주미래산업진흥원</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/WFI.css" />

	<script src="../../assets/js/libs/jquery.min.js"></script>
	<script src="../../assets/js/libs/librurys.min.js"></script>
	<script src="../../assets/js/ui/ui.common.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

	<!-- 페이지 스크립트 영역 -->
	<script></script>
</head>

<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content annou-list-page">
		<!-- content-header -->
		<div class="content-header">
			<?
			require $_SERVER['DOCUMENT_ROOT'] . "/_common/hard_content_header.php";
			?>
			<!-- // Sub Visual -->

		</div>
		<!-- // content-header -->
		<!-- content-body -->
		<div class="content-body search-wrap">
			<!-- 페이지 유형 예시 (페이지 유형에 따라 타이틀 영역의 예외처리 대응) -->
			<div class="board-list-page">
				<!-- 타이틀 영역 -->
				<div class="title-wrap">
					<h2 class="title">통합검색</h2>
					<div class="search_wrap">
						<div class="input">
							<input id="input_search_header" type="search" name="" placeholder="검색어를 입력하세요">
							<!-- <span class="delete"><span class="blind">delete</span></span>  -->
							<button id="btn_search_header" type="button" class="btn_search"><span
									class="blind">search</span></button>
						</div>
					</div>
					<p class="explain"><em class="search">기업</em>에 대한 검색 결과 <em class="num">0</em>건의 검색결과를
						찾았습니다.</p>
				</div>
				<!-- // 타이틀 영역 -->


				<!-- 게시판목록 영역 -->

				<div class="board-list-wrap">
					<!-- Sub Nav -->
					<div class="snb-wrap">
						<nav class="nav dep2-nav">
							<ul class="dep2-list">
								<li class="dep2-item">
									<a href="#" class="dep2-link is-current" aria-current="page">전체 <span
											class="total">100</span></a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link">공지사항 <span class="total">50</span></a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link">보도자료 <span class="total">30</span></a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link">경영공시 <span class="total">20</span></a>
								</li>
							</ul>
						</nav>
					</div>
					<!-- // Sub Nav -->
					<div class="board-list">
						<div class="no-list">
							<p>검색결과가 없습니다.</p>
						</div>
					</div>
					<div class="board-page">
						<button type="button" class="btn-page-first" disabled><span class="blind">처음
								페이지</span></button>
						<button type="button" class="btn-page-prev" disabled><span class="blind">이전
								페이지</span></button>
						<!-- Case 현재페이지: is-current 클래스,  aria-current="page" 추가 -->
						<button type="button" class="btn-page-number is-current" aria-current="page">1<span
								class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">2<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">3<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">4<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">5<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-next"><span class="blind">다음 페이지</span></button>
						<button type="button" class="btn-page-last"><span class="blind">마지막 페이지</span></button>
					</div>
				</div>
				<!-- // 게시판목록 영역 -->
			</div>
		</div>
		<!-- // content-body -->
	</div>
	<!-- // content -->
</main>
<!-- // Container -->

<!-- include_footer.html -->
<footer class="footer"></footer>
<!-- // include_footer.html -->