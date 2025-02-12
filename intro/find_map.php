<?session_start();?>
<?
$_PAGE_NO = "77";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=367c853cbe54d3d1b08fe4c5347e8986&libraries=services,clusterer,drawing"></script>

<!-- 페이지 스크립트 영역 -->
<script language="javascript">

$(document).ready(function() {

});

</script>

		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content find-map-page">
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
					<!-- 찾아오시는길 영역 -->
					<div class="map-wrap">
						<div class="map-area">
							<div class="map-box" id="map"></div>
							<div class="address-box">
								<div class="logo">
									<img src="/assets/images/common/icn-logo-bk.svg" alt="(재)원주미래산업진흥원">
								</div>
								<div class="address-info">
									<dl>
										<dt>주소</dt>
										<dd>강원 원주시 마재2로 10 2층 (재)원주미래산업진흥원​</dd>
									</dl>
									<dl>
										<dt>전화번호</dt>
										<dd><a href="tel:033-764-3160">033-764-3160</a></dd>
									</dl>
								</div>
							</div>
						</div>

						<!-- <div id="kakaoLoad" style="display: flex; justify-content: center; align-items: center;">
							<button type="button" name="kakaoLoadButton" id="kakaoLoadButton" class="btn-basic h-48 black mo-w100">길찾기</button>
							<button type="button" name="CircumFerenceButton" id="CircumFerenceButton" class="btn-basic h-48 black mo-w100">주변 지도보기</button>
						</div> -->

						<div class="togo-wrap">
							<div class="togo">
								<p class="title">대중교통 이용 시</p>
								<ul class="lists">
									<li class="train">
										<p class="bold">원주역(KTX) 기준</p>
										<p>원주역 하차 후 도보로 6분</p>
									</li>
									<li class="bus">
										<p class="bold">원주고속버스터미널 기준</p>
										<p>. 원주역 하차: 34-1 (35분 소요), 111 (40분 소요)</p>
										<p>. 마재길 하차: 5 (22분 소요), 18 (20분 소요), 30 (20분 소요)</p>
									</li>
								</ul>
							</div>
							<div class="togo">
								<p class="title">자가용 이용 시</p>
								<ul class="lists row">
									<li class="txt" data-text="동중주 IC">
										<p>동중주교차로에서 210m 이동 후 ‘원주, 제천’ 방면으로 직진 → 둔전사거리에서 ‘판부, 원주역’ 방면으로 우회전(31km) → 원주역 사거리 전 신호등에서 좌회전 후 우회전</p>
									</li>
									<li class="txt" data-text="문막 IC">
										<p>문막사거리에서 ‘원주혁신도시, 원주’ 방면으로 373m 이동 후 우회전 → 5.8km 이동 후 광터교차로에서 ‘원주혁신도시’ 방면으로 우회전 → 6.2km 이동 후 흥업교차로에서 ‘충주, 흥업’ 방면으로 오른쪽 방향 → 430m 이동 후 벌말삼거리에서 ‘원주’ 방면으로 좌회전 → 둔전사거리에서 ‘판부, 원주역’ 방면으로 우회전(31km) → 원주역 사거리 전 신호등에서 좌회전 후 우회전</p>
									</li>
									<li class="txt" data-text="남원주 IC">
										<p>남원주IC에서 ‘충주’ 방면으로 224m 이동 → 1.7km 이동 후 ‘판부’ 방면으로 좌회전 → 원주역 사거리 전 신호등에서 좌회전 후 우회전</p>
									</li>
									<li class="txt" data-text="원주 IC">
										<p>원주톨게이트에서 ‘원주시청’ 방면으로 216m 이동 → 62m 이동 후 원주IC 교차로에서 ‘시청, 혁신도시, 기업도시’ 방면으로 우회전 → 6km 이동 후 단계지하차도에서 ‘남원주IC’ 방면으로 지하차도 진입 → ‘판부’ 방면으로 좌회전 → 원주역 사거리 전 신호등에서 좌회전 후 우회전</p>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- // 찾아오시는길 영역 -->
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


	<script>
	// 카카오 지도 API 초기화
	var container = document.getElementById('map');
	var options = {
			center: new kakao.maps.LatLng(37.314424, 127.921593), // 초기 위치
			level: 3
	};

	var map = new kakao.maps.Map(container, options);

	// 목적지 좌표 설정
	var destinationXY = {
			x: 37.3142624092727,
			y: 127.921742279
	};

	// 목적지에 마커 추가
	var destinationMarker = new kakao.maps.Marker({
			position: new kakao.maps.LatLng(destinationXY.x, destinationXY.y),
			map: map
	});


	var routeUrl = "https://map.kakao.com/link/to/원주미래산업진흥원​," + destinationXY.x + "," + destinationXY.y;
	var CircumFerenceUrl = "https://map.kakao.com/link/search/원주미래산업진흥원​";

	/* 
		지도 클릭시 길찾기
	*/

	// $("#map").on("click", function(e) {
	// 	e.preventDefault();

	// 	if (routeUrl) {
	// 			window.open(routeUrl, '_blank');
	// 		} else {
	// 			alert("URL 생성에 실패했습니다. 다시 시도해주세요.");
	// 		}

	// });


/* 
	원주미래산업진흥원 좌표값.
	위도: 37.3142624092727,
	경도: 127.921742279
*/

	/*
	var mapLoad = document.getElementById('map');
	var CircumFerenceButton = document.getElementById('CircumFerenceButton');

	kakaoLoadButton.onclick = function() {
			if (routeUrl) {
				window.open(routeUrl, '_blank'); // 새 창으로 열기

				//window.location.href = routeUrl;
			} else {
					alert("URL 생성에 실패했습니다. 다시 시도해주세요.");
			}
	};

	CircumFerenceButton.onclick = function() {
			if (CircumFerenceUrl) {
				window.open(CircumFerenceUrl, '_blank'); // 새 창으로 열기

				//window.location.href = CircumFerenceUrl;
			} else {
					alert("URL 생성에 실패했습니다. 다시 시도해주세요.");
			}
	};
	*/
	</script>

</html>