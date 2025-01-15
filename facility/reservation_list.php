<? session_start(); ?>
<?
$_PAGE_NO = "96";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>
<script>
	function list1(b_code, b_no) {
		window.location.href = "/facility/reservation_list.php?b=" + b_code + "&bn=" + b_no;
	}
	function list2(b_code, b_no) {
		window.location.href = "/facility/reservation_detail.php?b=" + b_code + "&bn=" + b_no;
	}

	function list3(b_code, b_no) {
		window.location.href = "/facility/reservation_form.php?b=" + b_code + "&bn=" + b_no;
	}

	function list4(b_code, b_no) {
		window.location.href = "/facility/reservation_status.php?b=" + b_code + "&bn=" + b_no;
	}


</script>


<head>
</head>

<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content notice-list-page">
		<!-- content-header -->
		<div class="content-header">
			<!-- Sub Visual -->
			<div class="visual-wrap visual-04">
				<div class="inner">
					<h1>시설안내</h1>
					<div class="location">
						<span class="lc-home"><span class="blind">홈</span></span>
						<span class="lc-split"><span class="blind">&gt;</span></span>
						<span class="lc-cate"><span>시설안내</span></span>
						<span class="lc-split"><span class="blind">&gt;</span></span>
						<span class="lc-current" aria-current="page">시설 임대</span>
					</div>
				</div>
			</div>
			<!-- // Sub Visual -->
			<!-- Sub Nav -->
			<div class="snb-wrap">
				<nav class="nav dep2-nav">
					<ul class="dep2-list">
						<li class="dep2-item">
							<a href="javascript:void(0);" class="dep2-link" onclick="list3">시설 예약</a>
						</li>
						<li class="dep2-item">
							<a href="javascript:void(0);" class="dep2-link" onclick="list4">예약 현황</a>
						</li>
						<li class="dep2-item">
							<a href="javascript:void(0);" class="dep2-link is-current" onclick="list1">시설 임대</a>
						</li>
					</ul>
				</nav>
			</div>
			<!-- // Sub Nav -->
		</div>
		<!-- // content-header -->
		<!-- content-body -->
		<div class="content-body">
			<!-- 게시판목록 페이지 -->
			<div class="board-list-page">
				<!-- 타이틀 영역 -->
				<div class="title-wrap">
					<h2 class="title">시설 임대</h2>
				</div>
				<!-- // 타이틀 영역 -->

				<!-- 게시판목록 영역 -->
				<div class="board-list-wrap">
					<div class="board-toolbar">
						<p class="board-count">전체 <em>45</em>건</p>
						<div class="board-srch">
							<div class="frm-sel h-48">
								<select name="" id="frmSelect01" title="검색 구분" class="sel">
									<option value="">전체</option>
									<option value="">제목</option>
									<option value="">작성자</option>
									<option value="">내용</option>
								</select>
							</div>
							<div class="frm-inp h-48 has-on-right">
								<input type="text" name="" placeholder="검색어를 입력해주세요." title="검색 키워드" class="inp" />
								<div class="on-right">
									<button type="button" class="btn-srch">
										<span class="blind">검색</span>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="board-list">
						<table class="tbl">
							<caption>
								<strong>공지사항 목록</strong>
								<p>No., 제목, 작성자, 등록일, 조회수 순서로 구성됨</p>
							</caption>
							<colgroup>
								<col style="width:120rem" />
								<col style="width:auto" />
								<col style="width:200rem" />
								<col style="width:120rem" />
								<col style="width:120rem" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">제목</th>
									<th scope="col">작성자</th>
									<th scope="col">등록일</th>
									<th scope="col">조회수</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="tbl-no">
										<p class="noti">공지</p><!-- Case 공지사항 -->
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다시설 임대 신청서입니다시설 임대 신청서입니다시설 임대
												신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>10</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다시설 임대 신청서입니다시설 임대 신청서입니다</a>
											<!-- <a href="#" class="file"><span class="blind">첨부파일</span></a> Case 첨부파일없음 -->
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>8</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원원주미래산업진흥원원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>7</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>6</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>5</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>4</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>3</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>2</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
								<tr>
									<td class="tbl-no">
										<p>1</p>
									</td>
									<td class="tbl-tit">
										<p>
											<a href="#" class="link">시설 임대 신청서입니다</a>
											<a href="#" class="file"><span class="blind">첨부파일</span></a>
										</p>
									</td>
									<td class="tbl-writer">
										<p>원주미래산업진흥원</p>
									</td>
									<td class="tbl-date">
										<p>2024-11-01</p>
									</td>
									<td class="tbl-views">
										<p>3,400</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="board-page">
						<button type="button" class="btn-page-first" disabled><span class="blind">처음 페이지</span></button>
						<button type="button" class="btn-page-prev" disabled><span class="blind">이전 페이지</span></button>
						<!-- Case 현재페이지: is-current 클래스,  aria-current="page" 추가 -->
						<button type="button" class="btn-page-number is-current" aria-current="page">1<span
								class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">2<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">3<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">4<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-number">5<span class="blind">&nbsp;페이지</span></button>
						<button type="button" class="btn-page-next"><span class="blind">다음 페이지</span></button>
						<button type="button" class="btn-page-last"><span class="blind">마지막 페이지</span></button>
					</div>
				</div>
				<!-- // 게시판목록 영역 -->
			</div>
			<!-- // 게시판목록 페이지 -->
		</div>
		<!-- // content-body -->
	</div>
	<!-- // content -->
</main>

<!-- include_footer.html -->
<footer class="footer"></footer>