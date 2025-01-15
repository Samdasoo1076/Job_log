<?session_start();?>
<?
$_PAGE_NO = "16";
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
			<div id="content" class="content history-page">
				<!-- content-header -->
					<?
						require "../_common/content-header.php";
					?>
				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 타이틀 영역 -->
					<div class="title-wrap">
						<h2 class="title"><?=$seo_title?></h2>
						<!-- <p class="explain">설명이 들어가는 경우</p> -->
					</div>
					<!-- // 타이틀 영역 -->

					<!-- 연혁 영역 -->
					<div class="history-wrap">
						<div class="years-cont">
							<div class="cont-wrap">
								<h3 class="year">2024년</h3>
								<div class="cont">
									<div class="month-wrap">
										<p class="month">8월</p>
										<ul class="history-list">
											<li>(재)원주미래산업진흥원 출범 (08.07)</li>
										</ul>
									</div>
									<div class="month-wrap">
										<p class="month">7월</p>
										<ul class="history-list">
											<li>초대원장 조영희 임명 및 과학기술정보통신부 설립인가</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="img-wrap">
								<img src="../../assets/images/content/img-history-01.png">
							</div>
						</div>
						<div class="years-cont">
							<div class="cont-wrap">
								<h3 class="year">2023년</h3>
								<div class="cont">
									<div class="month-wrap">
										<p class="month">9월</p>
										<ul class="history-list">
											<li>원주시 및 강원특별자치도<br/>
												출자·출연기관 운영심의위원회 심의</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="img-wrap">
								<img src="../../assets/images/content/img-history-02.png">
							</div>
						</div>
						<div class="years-cont">
							<div class="cont-wrap">
								<h3 class="year">2021년</h3>
								<div class="cont">
									<div class="month-wrap">
										<p class="month">12월</p>
										<ul class="history-list">
											<li>원주미래산업진흥원 설립 기본계획 수립​</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="img-wrap">
								<img src="../../assets/images/content/img-history-03.png">
							</div>
						</div>
					</div>
					<!-- // 연혁 영역 -->
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