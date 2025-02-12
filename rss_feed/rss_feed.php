<? session_start(); ?>
<?
	require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";

	<body>
		<!-- 레이아웃 유형구분
			메인: main-wrapper,
			서브: sub-wrapper,
			기타: [기타]-wrapper
		 -->
		<div class="wrapper sub-wrapper">
			<!-- include_header.html -->
			<header class="header"></header>
			<!-- // include_header.html -->

			<!-- Container -->
			<main role="main" class="container">
				<!-- content -->
				<div id="content" class="content notice-list-page">
					<!-- content-header -->
					<div class="content-header">
						<!-- Sub Visual -->
						<div class="visual-wrap visual-02">
							<div class="inner">
								<h1>소통마당</h1>
								<div class="location">
									<span class="lc-home"><span class="blind">홈</span></span>
									<span class="lc-split"><span class="blind">&gt;</span></span>
									<span class="lc-cate"><span>소통마당</span></span>
									<span class="lc-split"><span class="blind">&gt;</span></span>
									<span class="lc-current" aria-current="page">공지사항</span>
								</div>
							</div>
						</div>
						<!-- // Sub Visual -->
						<!-- Sub Nav -->
						<div class="snb-wrap">
							<nav class="nav dep2-nav swiper-container">
								<ul class="dep2-list swiper-wrapper">
									<li class="dep2-item swiper-slide">
										<a href="#" class="dep2-link is-current" aria-current="page">공지사항</a>
									</li>
									<li class="dep2-item swiper-slide">
										<a href="#" class="dep2-link">공고안내</a>
									</li>
									<li class="dep2-item swiper-slide">
										<a href="#" class="dep2-link">보도자료</a>
									</li>
									<li class="dep2-item swiper-slide">
										<a href="#" class="dep2-link">경영공시</a>
									</li>
									<li class="dep2-item swiper-slide">
										<a href="#" class="dep2-link">문의사항</a>
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
								<h2 class="title">공지사항</h2>
							</div>
							<!-- // 타이틀 영역 -->

							<!-- 게시판목록 영역 -->
							<div class="board-list-wrap">
								<div class="board-toolbar">
									<p class="board-count">전체 <em>45</em>건</p>
									<div class="board-srch">
										<div class="frm-sel h-48">
											<select name="" id="frmSelect01"  title="검색 구분" class="sel">
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
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다. 1줄을 초과할 경우 말줄임표로 표시됩니다. 타이틀은 1줄까지 표시됩니다. 1줄을 초과할 경우 말줄임표로 표시됩니다.</a>
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
														<a href="#" class="link">타이틀타이틀타이틀타이틀타이틀1111111111111111111111111111타이틀타이틀타이틀타이틀타이틀11111111111111111111111111</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link"><em>타이틀은</em> 1줄까지 표시됩니다.</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다.</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다.</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다.</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다.</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다.</a>
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
													<p>9</p>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="#" class="link">타이틀은 1줄까지 표시됩니다.</a>
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
									<button type="button" class="btn-page-number is-current" aria-current="page">1<span class="blind">&nbsp;페이지</span></button>
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
			<!-- // Container -->

			<!-- include_footer.html -->
			<footer class="footer"></footer>
			<!-- // include_footer.html -->
		</div>
	</body>

	</html>