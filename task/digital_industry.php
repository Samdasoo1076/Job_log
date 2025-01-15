<? session_start(); ?>
<?
$_PAGE_NO = "94";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>
<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content main-task">
		<!-- Sub Visual -->
		<?
		require "../_common/content-header.php";

		?>
		<!-- // Sub Visual -->

		<div class="bg"><!-- 주요업무 페이지만 추가 -->
			<!-- <img src="../../assets/images/content/img-task-bg.png"> -->
		</div>
		<!-- content-body -->
		<div class="content-body">
			<!-- 타이틀 영역 -->
			<div class="sub-title-wrap">
				<p class="title">미래와 혁신을 주도하는<br />
					WFI의 주요업무를 소개합니다</p>
			</div>
			<!-- // 타이틀 영역 -->

			<!-- 주요업무 영역 -->
			<div class="main-task-wrap">
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
			<!-- // 주요업무 영역 -->
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