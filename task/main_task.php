<?
$_PAGE_NO = "93";
$b = ""; //페이지마다 세팅
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";

?>

<head>

</head>
		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content main-task">
				<!-- content-header -->
				<div class="content-header">
					<!-- Sub Visual -->
					<div class="visual-wrap visual-03">
						<div class="inner">
							<h1>주요업무</h1>
							<div class="location">
								<span class="lc-home"><span class="blind">홈</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span>
								<span class="lc-cate" aria-current="page"><span>주요업무</span></span>
							</div>
						</div>
					</div>
					<!-- // Sub Visual -->
					<!-- Sub Nav -->
					<!-- <div class="snb-wrap">
						<nav class="nav dep2-nav">
							<ul class="dep2-list">
								<li class="dep2-item">
									<a href="#" class="dep2-link">공지사항</a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link">공고안내</a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link">보도자료</a>
								</li>
								<li class="dep2-item">
									<a href="#" class="dep2-link is-current" aria-current="page">경영공시</a>
								</li>
							</ul>
						</nav>
						<nav class="nav dep3-nav">
							<ul class="dep3-list">
								<li class="dep3-item">
									<a href="#" class="dep3-link is-current" aria-current="page">전체</a>
								</li>
								<li class="dep3-item">
									<a href="#" class="dep3-link">임직원 현황</a>
								</li>
								<li class="dep3-item">
									<a href="#" class="dep3-link">기관운영현황</a>
								</li>
								<li class="dep3-item">
									<a href="#" class="dep3-link">예산 및 결산현황</a>
								</li>
								<li class="dep3-item">
									<a href="#" class="dep3-link">이사회 회의록</a>
								</li>
								<li class="dep3-item">
									<a href="#" class="dep3-link">경영성과</a>
								</li>
							</ul>
						</nav>
					</div> -->
					<!-- // Sub Nav -->
				</div>
				<!-- // content-header -->
				<div class="bg"><!-- 주요업무 페이지만 추가 -->
					<!-- <img src="../../assets/images/content/img-task-bg.png"> -->
				</div>
				<!-- content-body -->
				<div class="content-body">

					<!-- 페이지 유형 예시 (페이지 유형에 따라 타이틀 영역의 예외처리 대응) -->
					<div class="board-list-page">
						<!-- 타이틀 영역 -->
						<div class="sub-title-wrap">
							<p class="title">미래와 혁신을 주도하는<br/>
								WFI의 주요업무를 소개합니다</p>
						</div>
						<!-- // 타이틀 영역 -->

						<!-- 게시판목록 영역 -->
						<div class="main-tast">
							<div class="buisness-item" id="main_task_01">
								<div class="img-wrap">
									<img src="../../assets/images/content/img-task-item01.jpg">
								</div>
								<div class="cont-wrap">
									<div class="cont">
										<p class="red-tit">미래성장 동력 확보사업</p>
										<ul class="bul dot">
											<li>미래산업 정책 기획 및 조사연구​</li>
											<li>미래산업발굴​</li>
											<li>미래산업 거점조성​</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="buisness-item" id="main_task_02">
								<div class="img-wrap">
									<img src="../../assets/images/content/img-task-item02.jpg">
								</div>
								<div class="cont-wrap">
									<div class="cont">
										<p class="red-tit">산업고도화 및 경쟁력 강화사업</p>
										<ul class="bul dot">
											<li>지역 주력산업 디지털 전환​​</li>
											<li>강소/앵커 기업 육성​​</li>
											<li>ESG 경영 및 글로벌 지원 진출​​</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="buisness-item" id="main_task_03">
								<div class="img-wrap">
									<img src="../../assets/images/content/img-task-item03.jpg">
								</div>
								<div class="cont-wrap">
									<div class="cont">
										<p class="red-tit">융합·혁신 생태계 조성사업​</p>
										<ul class="bul dot">
											<li>혁신네트워크 활성화​​​</li>
											<li>창업 및 기업성장 지원​​​</li>
											<li>전문인력 양성​​</li>
											<li>투자 생태계 조성​​</li>
										</ul>
									</div>
								</div>
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
		<footer class="footer">
	<?
		require "../_common/front_footer.php";
	?>
		</footer>
