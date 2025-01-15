<?session_start();?>
<?
$_PAGE_NO = "13";
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
			<div id="content" class="content greeting-chairman-page">
				<!-- content-header -->
				<?
					require "../_common/content-header.php";
				?>

<div class="content-header">
	<div class="snb-wrap">
		<nav class="nav dep3-nav">
			<ul class="dep3-list">
			<li class="dep3-item">
					<a href="greetings_chairman.do" class="dep3-link is-current" title="이사장 인사말">이사장 인사말</a>
				</li>

				<li class="dep3-item">
					<a href="greetings_ledger.do" class="dep3-link" title="원장 인사말">원장 인사말</a>
				</li>
			</ul>
		</nav>
	</div>
</div>

				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 타이틀 영역 -->
					<div class="title-wrap">
						<h2 class="title">이사장 인사말</h2>
						<!-- <p class="explain">설명이 들어가는 경우</p> -->
					</div>
					<!-- // 타이틀 영역 -->

					<!-- 인사말 영역 -->
					<div class="greeting-wrap">
						<div class="profile-img">
							<img class="pc" src="../../assets/images/content/img-greetin-01-pc.jpg">
							<img class="mo" src="../../assets/images/content/img-greetin-01-mo.jpg">
						</div>
						<div class="greeting-cont">
							<h3>안녕하십니까?<br class="pc" /><br class="mo" />
								원주미래산업진흥원 이사장 원강수입니다.
							</h3>
							<div class="text">
								<p>먼저 원주미래산업진흥원 홈페이지를 찾아주신 여러분,
									진심으로 환영합니다.</p>
								<p>원주미래산업진흥원은 24년 8월 7일에 정식 출범한 기관으로 미래산업 발굴·육성 및
									정보통신기술(ICT) 융합을 통해 원주시의 지역 주력산업의 경쟁력을 강화하고
									산업고도화와 혁신 인프라 구축을 위한 Think-Tank의 역할을 수행하고 있습니다.</p>
								<p>​또한 원주시가 주력산업으로 육성하는 반도체, 모빌리티, 의료기기, 바이오, 데이터 등의
									미래 산업 분야에서 지역 먹거리를 창출하는 ‘곡식 창고’의 핵심적인 역할을 수행할 것입니다.</p>
								<p>원주미래산업진흥원에 대한 지속적인 관심과 응원에 감사드리며
									앞으로도 원주시 기업들의 많은 참여 부탁드립니다.</p>
								<p>감사합니다.</p>
							</div>
							<div class="sign">
								<p>원주미래산업진흥원 이사장</p>
								<p class="name">원 강 수</p>
							</div>
						</div>
						<!-- // 인사말 영역 -->
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
		<!-- // include_footer.html -->
	</div>
</body>
