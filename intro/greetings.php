<?session_start();?>
<?
$_PAGE_NO = "3";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>

<script>
	function list1(b_code, b_no) {
        window.location.href = "/intro/greetings_chairman.do?b=" + b_code + "&bn=" + b_no;
    }

	function list2(b_code, b_no) {
        window.location.href = "/intro/greetings_ledger.do?b=" + b_code + "&bn=" + b_no;
    }

	function list3(b_code, b_no) {
        window.location.href = "/intro/vision.do?b=" + b_code + "&bn=" + b_no;
    }
	function list4(b_code, b_no) {
        window.location.href = "/intro/history.do?b=" + b_code + "&bn=" + b_no;
    }
	function list5(b_code, b_no) {
        window.location.href = "/intro/organization.do?b=" + b_code + "&bn=" + b_no;
    }
	function list6(b_code, b_no) {
        window.location.href = "/intro/find_map.do?b=" + b_code + "&bn=" + b_no;
    }
</script>
		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content mobility-industry-page">
				<!-- content-header -->
				<div class="content-header">
					<!-- Sub Visual -->
					<div class="visual-wrap visual-02">
						<div class="inner">
							<h1>소통마당</h1>
							<div class="location">
								<span class="lc-home"><span class="blind">홈</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span>
								<span class="lc-cate"><span>소통마당</span></span>
								<span class="lc-split"><span class="blind">&gt;</span></span>
								<span class="lc-current" aria-current="page">공지사항</span>
							</div>
						</div>
					</div>
					<!-- // Sub Visual -->
					<!-- Sub Nav -->
					<div class="snb-wrap">
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
					</div>
					<!-- // Sub Nav -->
				</div>
				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 타이틀 영역 -->
					<div class="title-wrap">
						<h2 class="title">경영공시</h2>
						<p class="explain">설명이 들어가는 경우</p>
					</div>
					<!-- // 타이틀 영역 -->

					<!-- 게시판목록 영역 -->
					<div class="board-list-wrap">
						실질적인 컨텐츠가 들어가는 영역의 예시입니다.
					</div>
					<!-- // 게시판목록 영역 -->
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

</html>