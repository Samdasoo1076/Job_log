<?session_start();?>
<?
$_PAGE_NO = "68";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>

		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content vision-page">
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

					<!-- 비전 영역 -->
					<div class="vision-wrap">
						<img class="pc" src="../../assets/images/content/img-vision-pc.png" alt="Mission: 새로운 변화, 큰 행복, 더 큰 원주 실현을 위한 Think-Tank, WFI 추진목표 : 미래성장 동력확보, 산업고도화 및 경쟁력 강화, 융합/혁신 생태계 조성 / Vision: 원주시의 성장을 견인할 미래산업 생태계 및 기반 조성" />
						<img class="mo" src="../../assets/images/content/img-vision-mo.png" alt="Mission: 새로운 변화, 큰 행복, 더 큰 원주 실현을 위한 Think-Tank, WFI 추진목표 : 미래성장 동력확보, 산업고도화 및 경쟁력 강화, 융합/혁신 생태계 조성 / Vision: 원주시의 성장을 견인할 미래산업 생태계 및 기반 조성" />
					</div>
					<!-- // 비전 영역 -->
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