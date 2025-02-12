<?session_start();?>
<?
$_PAGE_NO = "117";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>

<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content greeting-chairman-page">
				<!-- content-header -->
					<?
						require "../_common/content-header.php";
					?>
				<!-- // content-header -->

<div class="content-header">
	<div class="snb-wrap">
		<nav class="nav dep3-nav">
			<ul class="dep3-list">
			<li class="dep3-item">
					<a href="greetings_chairman.do" class="dep3-link" title="이사장 인사말">이사장 인사말</a>
				</li>

				<li class="dep3-item">
					<a href="greetings_ledger.do" class="dep3-link is-current" title="원장 인사말">원장 인사말</a>
				</li>
			</ul>
		</nav>
	</div>
</div>

				<!-- content-body -->
				<div class="content-body">
					<!-- 타이틀 영역 -->
					<div class="title-wrap">
						<h2 class="title">원장 인사말</h2>
					<!-- <p class="explain">설명이 들어가는 경우</p> -->
					</div>
					<!-- // 타이틀 영역 -->

					<!-- 게시판목록 영역 -->
					<div class="greeting-wrap">
						<div class="profile-img">
							<img class="pc" src="../../assets/images/content/img-greetin-02-pc.jpg">
							<img class="mo" src="../../assets/images/content/img-greetin-02-mo.jpg">
						</div>
						<div class="greeting-cont">
							<h3>안녕하십니까?<br class="pc" /><br class="mo" />
								먼저 우리 진흥원 홈페이지에<br class="mo" /> 방문해주신 여러분께 <br class="pc" />
								깊은 감사의<br class="mo" /> 말씀을 드립니다.
							</h3>
							<div class="text">
								<p>원주미래산업진흥원은 원주시의 지속 가능한 발전과 미래산업 육성을 위해 설립된 기관으로,
									지역 경제 활성화와 일자리 창출을 목표로 디지털, 모빌리티 등 다양한 산업 분야에서
									혁신적인 지원과 협력의 중심 역할을 수행하고 있습니다.</p>
								<p>우리 진흥원은 디지털 대전환 시대의 흐름 속에서 첨단 산업 기술의 융합을 이끌어내고
									나아가 지역 특화 산업의 성장과 발전을 촉진하기 위해 최선을 다하고 있습니다. 이를 위해
									유관 기업들이 함께 소통하여 혁신 생태계를 조성할 수 있도록 사랑방 역할을 하겠습니다.</p>
								<p>원주시 기업인 여러분들의 성공을 바라며
									앞으로도 진흥원에 대한 지속적인 관심과 참여를 부탁드립니다.</p>
								<p>감사합니다.</p>
							</div>
							<div class="sign">
								<p>원주미래산업진흥원 원장</p>
								<p class="name">조 영 희</p>
							</div>
						</div>
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