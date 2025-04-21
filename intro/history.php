<?session_start();?>
<?
$_PAGE_NO = "16";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/v1_common_inc.php";
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