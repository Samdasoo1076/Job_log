<? session_start(); ?>
<?
$_PAGE_NO = "107";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/facility/list.php";

$arr_rs = listFormMeetingRoom($conn);
$arr_listequipment = listEquipment($conn);



?>
<script>
	function viewPost(event, room_no) {
		event.preventDefault();  // 기본 동작 방지

		// 로그인 상태 확인
		if (!<?= json_encode(isset($_SESSION['m_id'])) ?>) {
			var userConfirmed = confirm("로그인이 필요합니다. 로그인 페이지로 이동 하시겠습니까?");
			if (userConfirmed) {
				var currentUrl = encodeURIComponent(window.location.href);
				window.location.href = '/member/login.do?redirect=' + currentUrl; // 로그인 페이지로 리디렉션
			}
			return; // 함수 종료
		}
		let roomDetailUrl = "reservation_detail.do?room_no=" + room_no;
		window.location.href = roomDetailUrl;
	}
</script>

<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content notice-list-page">
		<!-- content-header -->
		<?
		require "../_common/content-header.php";
		?>
		<!-- // content-header -->
		<!-- content-body -->
		<div class="content-body">
			<!-- 게시판목록 페이지 -->
			<div class="board-list-page">
				<!-- 타이틀 영역 -->
				<div class="title-wrap">
					<h2 class="title">시설 예약</h2>
					<!-- <p class="explain">최대 24개월 내역까지 조회 가능합니다.​​</p> -->
				</div>
				<!-- // 타이틀 영역 -->

				<div class="board-list-wrap">
					<div class="reservation-info-box accordion">
						<p class="tit">대관료 책정 시 기본내용</p>

						<ul class="list">
							<li>1. 기준시간당 단가 산정
								<ul class="bul dot">
									<li>대관가능 시간 : 09:00 ~ 22:00(야간 18:00~22:00) ​</li>
									<li>기준시간 이하로 대관할 경우 기준시간으로 대관료 책정​</li>
									<li>기준시간 초과 대관 시 추가 기준시간 사용료 가산​</li>
								</ul>
							</li>
						</ul>
						<button class="accordion-button btn-info-view collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
							<span class="blind">더보기</span>
						</button>
						<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<ul class="list">
									<li>2. 야간의 대관행사에 대하여는 기준금액의 30% 가산함</li>
									<li>3. 오후와 야간이 겹칠 경우 야간 사용으로 함</li>
									<li>4. 준비시간 및 리허설을 포함한 대관 전체시간에 대해 동일한 사용료 책정</li>
									<li>5. 대관 신청 시간보다 실 사용시간이 초과할 경우 사용료 가산</li>
								</ul>
								<p class="tit">대관료 책정 시 사용료 감면</p>
								<ul class="list">
									<li>1. 사용료 전액 감면 대상
										<ul class="bul dot">
											<li>중앙행정기관, 강원특별자치도, 원주시가 주관하는 행사 및 교육​</li>
											<li>재단이 후원하거나 공동 주최하는 행사​</li>
											<li>기타 원장이 필요하다고 인정하는 행사​</li>
											<li>입주기업 대상 소회의실 사용시 사용료 전액 감면​</li>
										</ul>
									</li>
									<li>2. 사용료 50% 감면 대상(부속설비 감면대상 제외)
										<ul class="bul dot">
											<li>입주기업이 주관하는 행사 및 교육​</li>
										</ul>
									</li>
								</ul>
								<p class="tit between">기본시설 사용료
									<span class="vat">(부가가치세 별도)</span>
								</p>
								<div class="table-wrap">
									<table>
										<caption>
											기본시설 사용료
										</caption>
										<colgroup>
											<col style="width:14%" />
											<col style="width:14%" />
											<col style="width:14%" />
											<col style="width:14%" />
											<col style="width:14%" />
											<col style="width:14%" />
											<col style="width:14%" />
										</colgroup>
										<thead>
											<tr>
												<th rowspan="2">시설명</th>
												<th rowspan="2">면적</th>
												<th rowspan="2">기준시간</th>
												<th colspan="2">대관 사용료</th>
												<th rowspan="2">수용인원</th>
												<th rowspan="2">비고</th>
											</tr>
											<tr>
												<th>정상요금</th>
												<th>야간요금</th>
											</tr>
										</thead>
										<tbody>
											<? 
												if (sizeof($arr_rs) > 0) {
													for($j=0; $j < sizeof($arr_rs) ; $j++) {
											?>
											<tr>
												<td scope="row"><?=$arr_rs[$j]['ROOM_NAME']?></td>
												<td><?=$arr_rs[$j]['ROOM_SCALE']?></td>
												<td>
													<?=left($arr_rs[$j]['USE_TIME'],1)?>시간
												</td>
												<td><?=number_format($arr_rs[$j]['ROOM_PRICE'])?>원</td>
												<td>
													<? 
														if ($arr_rs[$j]['ROOM_PRICE'] <> $arr_rs[$j]['ROOM_NIGHT_PRICE']) { 
															echo number_format($arr_rs[$j]['ROOM_NIGHT_PRICE'])."원";
														} else {
															echo "-";
														} 
													?>
												</td>
												<td><?=$arr_rs[$j]['ROOM_CAPACITY']?></td>
												<td>-</td>
											</tr>
											<? 
													}
												} 
											?>
										</tbody>
									</table>
									<ul class="bul dot">
										<li>야간시간은 기준 사용료의 30% 가산​</li>
										<li>소회의실은 야간 대관 불가​</li>
									</ul>
								</div>
								<p class="tit between">부속시설 사용료
									<span class="vat">(부가가치세 별도)</span>
								</p>
								<div class="table-wrap">
									<table class="basic-fee">
										<caption>
											기본시설 사용료
										</caption>
										<colgroup>
											<col style="width:25%" />
											<col style="width:25%" />
											<col style="width:25%" />
											<col style="width:25%" />
										</colgroup>
										<thead>
											<tr>
												<th>시설명</th>
												<th>단위</th>
												<th>사용료</th>
												<th>비고</th>
											</tr>
										</thead>
										<tbody>
											<? 
												if (sizeof($arr_listequipment) > 0) {
													for($j=0; $j < sizeof($arr_listequipment) ; $j++) {
											?>
											<tr>
												<td scope="row"><?=$arr_listequipment[$j]['DCODE_NM']?></td>
												<td>1대</td>
												<td><?=number_format($arr_listequipment[$j]['DCODE_EXT'])?>원</td>
												<td>-</td>
											</tr>
											<?
													}
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="board-toolbar">
								<p class="board-count">전체 <em>3</em>건의 예약</p>
								<div class="board-srch">
									<span class="set">조회기간 설정</span>
									<div class="frm-sel h-48 w160">
										<select name="" id="frmSelect01"  title="검색 구분" class="sel">
											<option value="">최근 6개월</option>
											<option value="">12개월</option>
											<option value="">24개월</option>
										</select>
									</div>
								</div>
							</div> -->
					<div class="board-list">
						<div class="gallery-board">
							<ul class="gallery-board-list col3 hover">
								<?
									if (sizeof($arr_rs) > 0) {
										for($j=0; $j < sizeof($arr_rs) ; $j++) {
								?>
									<li onclick="viewPost(event, '<?= $arr_rs[$j]['ROOM_NO'] ?>')">
										<a href="javascript:void(0);">
											<!-- <a href="reservation_detail.do?room_no=<?= $arr_rs['ROOM_INFO']['ROOM_NO'] ?>"> -->
											<div class="img-wrap">
												<img src="/upload_data/meetingroom/<?= $arr_rs[$j]['ROOM_FILE'] ?>"
													alt="Room Image">
											</div>
											<div class="cont-wrap">
												<p class="title"><?= $arr_rs[$j]['ROOM_NAME'] ?></p>
												<div class="info-wrap">
													<dl>
														<dt>예약기간</dt>
														<dd><?= $arr_rs[$j]['ABLE_PERIOD'] ?></dd>
													</dl>
													<dl>
														<dt>시설규모</dt>
														<dd><?= $arr_rs[$j]['ROOM_SCALE'] ?></dd>
													</dl>
													<dl>
														<dt>수용인원</dt>
														<dd><?= $arr_rs[$j]['ROOM_CAPACITY'] ?></dd>
													</dl>
													<dl>
														<dt>이용료</dt>
														<dd><?= number_format($arr_rs[$j]['ROOM_PRICE']) ?> 원</dd>
													</dl>
												</div>
												<div class="btn-wrap">
													<button type="button" class="btn-basic h-56 fluid">
														<span>시설 예약하기</span>
													</button>
												</div>
											</div>
										</a>
									</li>
								<?
										}
									}
								?>
							</ul>
						</div>
					</div>

					<!-- 예약내역팝업  -->
					<!-- <div class="modal fade info-modal" id="exampleModal" tabindex="-1" role="dialog"
						aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-basic">
							<div class="modal-content">
								<div class="modal-header">
									<h1 class="modal-title" id="exampleModalLabel">Modal title</h1>
									<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal"
										aria-label="Close"><span class="blind">닫기</span></button>
								</div>
								<div class="modal-body">
									<div class="cont">
										<p class="tit"><em>woonju1234</em>님의 예약 내역입니다.</p>
										<div class="info top">
											<dl>
												<dt>예약번호</dt>
												<dd>241029C002468​</dd>
											</dl>
											<dl>
												<dt>연락처</dt>
												<dd>241029C002468​</dd>
											</dl>
											<dl>
												<dt>미승인</dt>
												<dd>시설 리모델링​</dd>
											</dl>
										</div>
										<div class="info">
											<dl>
												<dt>시설명</dt>
												<dd>컨퍼런스룸​</dd>
											</dl>
											<dl>
												<dt>사용 인원</dt>
												<dd>34명</dd>
											</dl>
											<dl>
												<dt>사용 목적</dt>
												<dd>미팅​</dd>
											</dl>
											<dl>
												<dt>시설 방문 날짜</dt>
												<dd>미2024-10-10</dd>
											</dl>
											<dl>
												<dt>이용 시간</dt>
												<dd>14:00~18:00 (오후 반일)</dd>
											</dl>
											<dl>
												<dt>감면대상자</dt>
												<dd>아니요</dd>
											</dl>
											<dl>
												<dt>대여기자재 사용</dt>
												<dd>아니요</dd>
											</dl>
										</div>
										<div class="info-txt">
											<p>사용안내글이 노출됩니다. 사용안내글이 노출됩니다. 사용안내글이 노출 됩니다. 사용안내글이 노출됩니다.사용안내글이 노출됩니다.
												사용안내글이 사용안내글이 노출됩니다. 사용안내글이 노출됩니다. 노출됩니다.</p>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<div class="btn-wrap">
										<button type="button" class="btn-basic h-56 primary center-w200">
											<span>PDF로 저장하기</span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
					<!-- // 게시판목록 페이지 -->
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