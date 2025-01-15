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
	<title>Pages | 원주미래산업진흥원</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/WFI.css" />

	<script src="../../assets/js/libs/jquery.min.js"></script>
	<script src="../../assets/js/libs/librurys.min.js"></script>
	<script src="../../assets/js/ui/ui.common.js"></script>
	<script src="../../assets/js/ui/ui.publish.js"></script><!-- 퍼블리싱 전용 (개발적용안함) -->

	<!-- 페이지 스크립트 영역 -->
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// 퍼블리싱 바로 볼 수 있도록 호출
			var defaultModal = new bootstrap.Modal(document.getElementById('defaultModal'));
			defaultModal.show();
		});
	</script>
</head>

<body>
	<div class="wrapper">
		<div style="padding: 30rem;">
			<!-- 트리거로 모달 띄우기 -->
			<button type="button" class="ui-btn" data-bs-toggle="modal" data-bs-target="#defaultModal">
				Default modal
			</button>
		</div>
	</div>

	<!--
		[모달 작업용 기본 양식]
		모달 유형 및 스타일 가이드 반영하여 제작한다.
	-->
	<!-- modal -->
	<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-유형">
			<div class="modal-content modal-컨텐츠">
				<div class="modal-header">
					<h3 class="modal-title" id="defaultModalLabel"></h3>
					<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal" aria-label="Close">닫기</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<!-- // modal -->
</body>

</html>