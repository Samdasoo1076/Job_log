<script>

	$(function(){
		// PC Header
		$('#input_search_header').on('keydown', function (event) {
			if (event.key === 'Enter') {
				$('#btn_search_header').trigger('click');
			}
		});

		$('#btn_search_header').on('click', function (event) {
			
			const searchKeyword = $("#input_search_header").val();
			
			if (!searchKeyword) {
				alert('검색어를 입력해주세요.');
				return;
			} else {
				$("#input_hidden_search_header").val($("#input_search_header").val());
				searchForm.submit();
			}
		});

		// Mobile Header
		$('#mobile_input_search_header').on('keydown', function (event) {
			if (event.key === 'Enter') {
				$('#mobile_btn_search_header').trigger('click');
			}
		});

		$('#mobile_btn_search_header').on('click', function (event) {
			
			const searchKeyword = $("#mobile_input_search_header").val();
			
			if (!searchKeyword) {
				alert('검색어를 입력해주세요.');
				return;
			} else {
				$("#input_hidden_search_header").val($("#mobile_input_search_header").val());
				searchForm.submit();
			}
		});
	});

</script>


<header class="header">
	<div class="header-skip">
		<a href="#content">본문 바로가기</a>
	</div>

	<!-- PC Header -->
	<div class="header-pc-sec">
		<!-- Member 영역 -->
		<!-- <div class="mem-area">
		<button type="button" class="ui-btn">
			<div class="icn" aria-hidden=""></div>
			<span>로그인</span>
		</button>
		<button type="button" class="ui-btn">
			<div class="icn" aria-hidden=""></div>
			<span>회원가입</span>
		</button>
	</div> -->
		<!-- // Member 영역 -->

		<!-- Global 영역 -->
		<div class="gnb-area">
			<!-- 로고 -->
			<div class="header-logo">
				<a href="/index.do">
					<img class="down" src="/assets/images/common/img-logo-wh.svg" alt="WFI (재)원주미래산업진흥원" />
					<img class="up" src="/assets/images/common/img-logo-bk.svg" alt="WFI (재)원주미래산업진흥원" />
					<span class="blind">WFI (재)원주미래산업진흥원</span>
				</a>
			</div>
			<!-- // 로고 -->

			<!-- 메뉴 -->
			<nav class="header-nav">
				<ul class="dep1-list">
					<?
					if (sizeof($arr_menu_rs_01) > 0) {

						for ($j = 0; $j < sizeof($arr_menu_rs_01); $j++) {

							$PAGE_NO = trim($arr_menu_rs_01[$j]["PAGE_NO"]);
							$PAGE_CD = trim($arr_menu_rs_01[$j]["PAGE_CD"]);
							$PAGE_NAME = trim($arr_menu_rs_01[$j]["PAGE_NAME"]);
							$PAGE_URL = trim($arr_menu_rs_01[$j]["PAGE_URL"]);
							$PAGE_INFO01 = trim($arr_menu_rs_01[$j]["PAGE_INFO01"]);
							$PAGE_INFO02 = trim($arr_menu_rs_01[$j]["PAGE_INFO02"]);
							$PAGE_INFO03 = trim($arr_menu_rs_01[$j]["PAGE_INFO03"]);
							$PAGE_INFO04 = trim($arr_menu_rs_01[$j]["PAGE_INFO04"]);
							$URL_TYPE = trim($arr_menu_rs_01[$j]["URL_TYPE"]);

							$PAGE_NAME = str_replace("<br />", "", $PAGE_NAME);
							$PAGE_NAME = str_replace("<br/>", "", $PAGE_NAME);
							$PAGE_NAME = str_replace("<br>", "", $PAGE_NAME);

							if ($PAGE_URL == "") {
								$PAGE_URL = "/pages/?p=" . $PAGE_NO . "&title=" . $PAGE_NAME;
							} else {
								if (strpos($PAGE_URL, "script:") == true) {
									$PAGE_URL = $PAGE_URL;
								} else {
									$PAGE_URL = $PAGE_URL;
								}
							}

							if ($this_depth01 == $PAGE_CD) {
								$depth_01_page_name = $PAGE_NAME;
								$depth_01_page_cd = $PAGE_CD;
							}

							if ($this_depth01 == $PAGE_CD)
								$depth_01_page_name = $PAGE_NAME;
							?>
							<li class="dep1-item">
								<a href="<?= $PAGE_URL ?>" <? if ($URL_TYPE == "Y") { ?>target="_blank" title="새창열기" <? } ?>
									class="dep1-link"><?= $PAGE_NAME ?></a>
								<?
								if (sizeof($arr_menu_rs_02) > 0) {
									?>
									<div class="dep2-list-wrap">
										<ul class="dep2-list">
											<?
											for ($jj = 0; $jj < sizeof($arr_menu_rs_02); $jj++) {

												$SUB_PAGE_NO = trim($arr_menu_rs_02[$jj]["PAGE_NO"]);
												$SUB_PAGE_CD = trim($arr_menu_rs_02[$jj]["PAGE_CD"]);
												$SUB_PAGE_NAME = trim($arr_menu_rs_02[$jj]["PAGE_NAME"]);
												$SUB_PAGE_URL = trim($arr_menu_rs_02[$jj]["PAGE_URL"]);
												$SUB_PAGE_INFO01 = trim($arr_menu_rs_02[$jj]["PAGE_INFO01"]);
												$SUB_PAGE_INFO02 = trim($arr_menu_rs_02[$jj]["PAGE_INFO02"]);
												$SUB_PAGE_INFO03 = trim($arr_menu_rs_02[$jj]["PAGE_INFO03"]);
												$SUB_PAGE_INFO04 = trim($arr_menu_rs_02[$jj]["PAGE_INFO04"]);
												$SUB_URL_TYPE = trim($arr_menu_rs_02[$jj]["URL_TYPE"]);

												$SUB_PAGE_NAME = str_replace("<br />", "", $SUB_PAGE_NAME);
												$SUB_PAGE_NAME = str_replace("<br/>", "", $SUB_PAGE_NAME);
												$SUB_PAGE_NAME = str_replace("<br>", "", $SUB_PAGE_NAME);

												if ($this_depth01 . $this_depth02 == $SUB_PAGE_CD) {
													$depth_02_page_name = $SUB_PAGE_NAME;
													$depth_02_class = $SUB_PAGE_INFO03;
												}

												if ($PAGE_CD == left($SUB_PAGE_CD, 2)) {

													if ($SUB_PAGE_URL == "") {
														$SUB_PAGE_URL = "/pages/?p=" . $SUB_PAGE_NO . "&title=" . $SUB_PAGE_NAME;
													} else {
														if (strpos($SUB_PAGE_URL, "script:") == true) {
															$SUB_PAGE_URL = $SUB_PAGE_URL;
														} else {
															$SUB_PAGE_URL = $SUB_PAGE_URL;
														}
													}
													?>
													<li class="dep2-item"><a href="<?= $SUB_PAGE_URL ?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기" <? } ?>
															class="dep2-link"><?= $SUB_PAGE_NAME ?></a>
													</li>
												<?
												}
											}
											?>
										</ul>
									<?
								}
								?>
								</div>
							</li>
						<?
						}
					}
					?>
				</ul>
				<div class="header-nav-bg" aria-hidden="false"></div>
			</nav>
			<!-- // 메뉴 -->
			<!-- snb메뉴 -->
			<div class="snb-wrap">
				<ul class="util pc">
					<li>
						<? if (!empty($_SESSION['m_id'])): ?>
							<div class="btn-user-wrap">
								<a href="#" class="btn-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									<span class="name"><?= $_SESSION['m_id'] ?></span> 님
								</a>
								<div class="user-menu dropdown-menu">
									<ul class="user-menu-list">
										<li><a href="/member/mypage.do">나의 회원정보</a></li>
										<li><a href="/member/mypage_reservation.do">나의 예약현황</a></li>
									</ul>
								</div>
							</div>
						<? endif; ?>
					</li>
					<li>
						<? if (!empty($_SESSION['m_id'])): ?>
							<!-- 로그인 상태 -->
							<div class="btn-log-wrap on">
								<a href="/member/logout.do" class="btn-log"><span class="blind">사용자메뉴</span></a>
							</div>
						<? else: ?>
							<!-- 비로그인 상태 -->
							<div class="btn-log-wrap">
								<a href="/member/login.do" class="btn-log"><span class="blind">로그인</span></a>
							</div>
						<? endif; ?>

					</li>
					<li>
						<!-- 2024-12-06 헤더 검색 피시 수정 -->
						<a href="javascript:void(0)" class="ui-btn btn-search" aria-expanded="false">
							<span class="blind">검색</span>
						</a>
						<!-- 검색 -->
						
						<div class="header-search">
							<div class="search_wrap">
								<div class="input">
									<input id="input_search_header" type="search" name="searchKeyword" placeholder="검색어를 입력하세요">
									<!-- <span class="delete"><span class="blind">delete</span></span>  -->
									<button id="btn_search_header" type="submit" class="btn_search"><span class="blind">search</span></button>
								</div>
							</div>
						</div>

						<!-- // 검색 -->
					</li>
					<li>
						<!-- 2024-12-06 헤더 피시 모바일 전체메뉴 병합 -->
						<a href="javascript:void(0)" class="btn-gnb" data-bs-toggle="modal" data-bs-target="#mobileNavModal">
							<span></span>
							<span></span>
							<span></span>
						</a>
					</li>
				</ul>
			</div>
						
		</div>
		<!-- // Global 영역 -->
	</div>
	<!-- // PC Header -->
	<form id="searchForm" action="/comm/search_list.do" method="GET">
		<input type="hidden" id="input_hidden_search_header" type="search" name="searchKeyword">
	</form>
	<!-- Mobile Header -->
	<div class="header-mobile-sec">
		<div class="gnb-area">
			<div class="header-logo">
				<a href="/index.do">
					<img class="down" src="/assets/images/common/img-logo-wh.svg" alt="WFI (재)원주미래산업진흥원 로고">
					<img class="up" src="/assets/images/common/img-logo-bk.svg" alt="WFI (재)원주미래산업진흥원 로고">
					<span class="blind">WFI (재)원주미래산업진흥원</span>
				</a>
			</div>
			<div class="header-btns">
				<!-- 2024-12-06 헤더 검색 모바일 수정 -->
				<button type="button" class="ui-btn btn-search" aria-expanded="false">
					<span class="blind">검색</span>
				</button>
				<button type="button" class="ui-btn btn-gnb" data-bs-toggle="modal" data-bs-target="#mobileNavModal">
					<span class="blind">전체메뉴</span>
				</button>
				<!-- 검색 -->
				<div class="header-search">
					<div class="search_wrap">
						<div class="input">
							<input id="mobile_input_search_header" type="search" name="" placeholder="검색어를 입력하세요">
							<!-- <span class="delete"><span class="blind">delete</span></span>  -->
							<button id="mobile_btn_search_header" type="button" class="btn_search"><span
									class="blind">search</span></button>
						</div>
					</div>
				</div>
				<!-- // 검색 -->

			</div>
		</div>
	</div>
	<!-- // Mobile Header -->

	<!-- Mobile Nav Modal -->
	<!-- 2024-12-06 헤더 피시 모바일 전체메뉴 병합 -->
	<div class="modal fade mobile-nav-modal" id="mobileNavModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
		aria-labelledby="mobileNavModalLabel" aria-hidden="true">
		<!-- 모바일 메뉴 -->
		<div class="modal-dialog">
			<div class="modal-content">
				<h2 id="mobileNavModalLabel" class="blind">전체메뉴</h2>
				<!-- Member 영역 -->
				<!-- Case 로그인 전 -->
				<!-- <div class="mem-area">
				<h3>로그인이 필요합니다.</h3>
				<div class="mem-area-btn-wrap">
					<button type="button" class="ui-btn login">
						<span>로그인</span>
					</button>
					<button type="button" class="ui-btn join">
						<span>회원가입</span>
					</button>
				</div>
			</div> -->
				<!-- // Case 로그인 전 -->
				<!-- Case 로그인 후 -->
				<div class="mem-area">
					<? if (!empty($_SESSION['m_id'])): ?>
						<!-- 로그인 상태 -->
						<h3><a href="/member/mypage.do"><?= $_SESSION['m_id'] ?>님</a></h3>
						<div class="mem-area-btn-wrap">
							<button type="button" class="ui-btn logout" onclick="location.href='/member/logout.do'">
								<span><a href="/member/logout.do">로그아웃</a></span>
							</button>
						</div>
					<? else: ?>
						<!-- 비로그인 상태 -->
						<h3>로그인이 필요합니다.</h3>
						<div class="mem-area-btn-wrap">
							<button type="button" class="ui-btn login" onclick="location.href='/member/login.do'">
								<span><a href="/member/login.do">로그인</a></span>
							</button>
							<button type="button" class="ui-btn join" onclick="location.href='/member/member_form.do'">
								<span><a href="/member/member_form.do">회원가입</a></span>
							</button>
						</div>
					<? endif; ?>
				</div>
				<!-- // Case 로그인 후 -->
				<!-- // Member 영역 -->

				<!-- Global 영역 -->
				<div class="gnb-area">
					<nav class="mobile-nav">
						<ul class="dep1-list accordionExample" id="accordionExample">
							<?
							if (sizeof($arr_menu_rs_01) > 0) {

								for ($j = 0; $j < sizeof($arr_menu_rs_01); $j++) {

									$PAGE_NO = trim($arr_menu_rs_01[$j]["PAGE_NO"]);
									$PAGE_CD = trim($arr_menu_rs_01[$j]["PAGE_CD"]);
									$PAGE_NAME = trim($arr_menu_rs_01[$j]["PAGE_NAME"]);
									$PAGE_URL = trim($arr_menu_rs_01[$j]["PAGE_URL"]);
									$PAGE_INFO01 = trim($arr_menu_rs_01[$j]["PAGE_INFO01"]);
									$PAGE_INFO02 = trim($arr_menu_rs_01[$j]["PAGE_INFO02"]);
									$PAGE_INFO03 = trim($arr_menu_rs_01[$j]["PAGE_INFO03"]);
									$PAGE_INFO04 = trim($arr_menu_rs_01[$j]["PAGE_INFO04"]);
									$URL_TYPE = trim($arr_menu_rs_01[$j]["URL_TYPE"]);

									$PAGE_NAME = str_replace("<br />", "", $PAGE_NAME);
									$PAGE_NAME = str_replace("<br/>", "", $PAGE_NAME);
									$PAGE_NAME = str_replace("<br>", "", $PAGE_NAME);

									if ($PAGE_URL == "") {
										$PAGE_URL = "/pages/?p=" . $PAGE_NO . "&title=" . $PAGE_NAME;
									} else {
										if (strpos($PAGE_URL, "script:") == true) {
											$PAGE_URL = $PAGE_URL;
										} else {
											$PAGE_URL = $PAGE_URL;
										}
									}

									if ($this_depth01 == $PAGE_CD) {
										$depth_01_page_name = $PAGE_NAME;
										$depth_01_page_cd = $PAGE_CD;
									}

									if ($this_depth01 == $PAGE_CD)
										$depth_01_page_name = $PAGE_NAME;
									?>
									<li class="dep1-item">
										<a href="<?= $PAGE_URL ?>" <? if ($URL_TYPE == "Y") { ?>target="_blank" title="새창열기" <? } ?> class="dep1-link"><?= $PAGE_NAME ?></a>
										<button type="button" class="dep2-open collapsed" data-bs-toggle="collapse"
											data-bs-target="#dep2-<?= ($j + 1) ?>" aria-controls="dep2-<?= ($j + 1) ?>">
											<span class="blind">열기</span>
										</button>
										<div class="dep2-list-wrap accordion-collapse collapse" id="dep2-<?= ($j + 1) ?>" data-bs-parent="#accordionExample">
											<?
											if (sizeof($arr_menu_rs_02) > 0) {
												?>
												<ul class="dep2-list">
													<?
													for ($jj = 0; $jj < sizeof($arr_menu_rs_02); $jj++) {

														$SUB_PAGE_NO = trim($arr_menu_rs_02[$jj]["PAGE_NO"]);
														$SUB_PAGE_CD = trim($arr_menu_rs_02[$jj]["PAGE_CD"]);
														$SUB_PAGE_NAME = trim($arr_menu_rs_02[$jj]["PAGE_NAME"]);
														$SUB_PAGE_URL = trim($arr_menu_rs_02[$jj]["PAGE_URL"]);
														$SUB_PAGE_INFO01 = trim($arr_menu_rs_02[$jj]["PAGE_INFO01"]);
														$SUB_PAGE_INFO02 = trim($arr_menu_rs_02[$jj]["PAGE_INFO02"]);
														$SUB_PAGE_INFO03 = trim($arr_menu_rs_02[$jj]["PAGE_INFO03"]);
														$SUB_PAGE_INFO04 = trim($arr_menu_rs_02[$jj]["PAGE_INFO04"]);
														$SUB_URL_TYPE = trim($arr_menu_rs_02[$jj]["URL_TYPE"]);

														$SUB_PAGE_NAME = str_replace("<br />", "", $SUB_PAGE_NAME);
														$SUB_PAGE_NAME = str_replace("<br/>", "", $SUB_PAGE_NAME);
														$SUB_PAGE_NAME = str_replace("<br>", "", $SUB_PAGE_NAME);

														if ($this_depth01 . $this_depth02 == $SUB_PAGE_CD) {
															$depth_02_page_name = $SUB_PAGE_NAME;
															$depth_02_class = $SUB_PAGE_INFO03;
														}

														if ($PAGE_CD == left($SUB_PAGE_CD, 2)) {

															if ($SUB_PAGE_URL == "") {
																$SUB_PAGE_URL = "/pages/?p=" . $SUB_PAGE_NO . "&title=" . $SUB_PAGE_NAME;
															} else {
																if (strpos($SUB_PAGE_URL, "script:") == true) {
																	$SUB_PAGE_URL = $SUB_PAGE_URL;
																} else {
																	$SUB_PAGE_URL = $SUB_PAGE_URL;
																}
															}
															?>
															<li class="dep2-item"><a href="<?= $SUB_PAGE_URL ?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기" <? } ?>
																	class="dep2-link"><?= $SUB_PAGE_NAME ?></a></li>
														<?
														}
													}
													?>
												</ul>
											<?
											}
											?>
										</div>
									</li>
								<?
								}
							}
							?>
							<? if (!empty($_SESSION['m_no'])) { ?>
							<li class="dep1-item">
								<a href="/member/mypage.do" class="dep1-link">마이페이지</a>
								<button type="button" class="dep2-open collapsed" data-bs-toggle="collapse" data-bs-target="#dep2-5" aria-controls="dep2-5">
									<span class="blind">열기</span>
								</button>
								<div class="dep2-list-wrap accordion-collapse collapse" id="dep2-5" data-bs-parent="#accordionExample">
										<ul class="dep2-list">
											<li class="dep2-item"><a href="/member/mypage.do" class="dep2-link">나의 회원정보</a></li>
											<li class="dep2-item"><a href="/member/mypage_reservation.do" class="dep2-link">나의 예약정보</a></li>
										</ul>
								</div>
							</li>
							<? } ?>
						</ul>
						<div class="header-nav-bg" aria-hidden="false"></div>
					</nav>
				</div>
				<button type="button" class="ui-btn btn-gnb-close" data-bs-dismiss="modal">
					<i class="icn" aria-label="메뉴닫기">메뉴닫기</i>
				</button>
			</div>
		</div>
		<!-- // 모바일 메뉴 -->

		<!-- 전체메뉴 -->
		<!-- 2024-12-06 헤더 피시 모바일 전체메뉴 병합 -->
		<div class="modal-dialog sitemap">
			<div class="modal-content">
				<h2 class="sitemap-logo">
					<a href="/index.do">
						<img class="up" src="/assets/images/common/img-logo-bk.svg" alt="WFI (재)원주미래산업진흥원 로고">
						<span class="blind">WFI (재)원주미래산업진흥원</span>
					</a>
				</h2>
				<div class="wrap">
					<div class="all-gnb-intro">
						<p class="category"><!--Site Map--></p>
						<h3 class="welcome">
							원주미래산업진흥원에<br />
							오신 것을 환영합니다
						</h3>
						<div class="login-menu">
							<ul class="login-list">
								<?php if (!empty($_SESSION['m_no'])): ?>
									<!-- 로그인 상태 -->
									<li>
										<a href="/member/mypage.do"><span class="user-name"><?= $_SESSION['m_id'] ?></span>님</a>
									</li>
									<li>
										<a href="/member/logout.do" class="logout">로그아웃</a>
									</li>
								<?php else: ?>
									<!-- 비로그인 상태 -->
									<li>
										<a href="/member/login.do">로그인</a>
									</li>
									<li>
										<a href="/member/member_form.do">회원가입</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<div class="all-gnb-menu">
						<ul class="all-gnb-menu-list">
						<?
							if (sizeof($arr_menu_rs_01) > 0) {

								for ($j = 0; $j < sizeof($arr_menu_rs_01); $j++) {

									$PAGE_NO = trim($arr_menu_rs_01[$j]["PAGE_NO"]);
									$PAGE_CD = trim($arr_menu_rs_01[$j]["PAGE_CD"]);
									$PAGE_NAME = trim($arr_menu_rs_01[$j]["PAGE_NAME"]);
									$PAGE_URL = trim($arr_menu_rs_01[$j]["PAGE_URL"]);
									$PAGE_INFO01 = trim($arr_menu_rs_01[$j]["PAGE_INFO01"]);
									$PAGE_INFO02 = trim($arr_menu_rs_01[$j]["PAGE_INFO02"]);
									$PAGE_INFO03 = trim($arr_menu_rs_01[$j]["PAGE_INFO03"]);
									$PAGE_INFO04 = trim($arr_menu_rs_01[$j]["PAGE_INFO04"]);
									$URL_TYPE = trim($arr_menu_rs_01[$j]["URL_TYPE"]);

									$PAGE_NAME = str_replace("<br />", "", $PAGE_NAME);
									$PAGE_NAME = str_replace("<br/>", "", $PAGE_NAME);
									$PAGE_NAME = str_replace("<br>", "", $PAGE_NAME);

									if ($PAGE_URL == "") {
										$PAGE_URL = "/pages/?p=" . $PAGE_NO . "&title=" . $PAGE_NAME;
									} else {
										if (strpos($PAGE_URL, "script:") == true) {
											$PAGE_URL = $PAGE_URL;
										} else {
											$PAGE_URL = $PAGE_URL;
										}
									}

									if ($this_depth01 == $PAGE_CD) {
										$depth_01_page_name = $PAGE_NAME;
										$depth_01_page_cd = $PAGE_CD;
									}

									if ($this_depth01 == $PAGE_CD)
										$depth_01_page_name = $PAGE_NAME;
									?>
									<li>
										<a href="<?= $PAGE_URL ?>" <? if ($URL_TYPE == "Y") { ?>target="_blank" title="새창열기" <? } ?>
											class="dep1"><?= $PAGE_NAME ?></a>
										<?
										if (sizeof($arr_menu_rs_02) > 0) {
											?>
											<ul class="dep2-menu-list">
												<?
												for ($jj = 0; $jj < sizeof($arr_menu_rs_02); $jj++) {

													$SUB_PAGE_NO = trim($arr_menu_rs_02[$jj]["PAGE_NO"]);
													$SUB_PAGE_CD = trim($arr_menu_rs_02[$jj]["PAGE_CD"]);
													$SUB_PAGE_NAME = trim($arr_menu_rs_02[$jj]["PAGE_NAME"]);
													$SUB_PAGE_URL = trim($arr_menu_rs_02[$jj]["PAGE_URL"]);
													$SUB_PAGE_INFO01 = trim($arr_menu_rs_02[$jj]["PAGE_INFO01"]);
													$SUB_PAGE_INFO02 = trim($arr_menu_rs_02[$jj]["PAGE_INFO02"]);
													$SUB_PAGE_INFO03 = trim($arr_menu_rs_02[$jj]["PAGE_INFO03"]);
													$SUB_PAGE_INFO04 = trim($arr_menu_rs_02[$jj]["PAGE_INFO04"]);
													$SUB_URL_TYPE = trim($arr_menu_rs_02[$jj]["URL_TYPE"]);

													$SUB_PAGE_NAME = str_replace("<br />", "", $SUB_PAGE_NAME);
													$SUB_PAGE_NAME = str_replace("<br/>", "", $SUB_PAGE_NAME);
													$SUB_PAGE_NAME = str_replace("<br>", "", $SUB_PAGE_NAME);

													if ($this_depth01 . $this_depth02 == $SUB_PAGE_CD) {
														$depth_02_page_name = $SUB_PAGE_NAME;
														$depth_02_class = $SUB_PAGE_INFO03;
													}

													if ($PAGE_CD == left($SUB_PAGE_CD, 2)) {

														if ($SUB_PAGE_URL == "") {
															$SUB_PAGE_URL = "/pages/?p=" . $SUB_PAGE_NO . "&title=" . $SUB_PAGE_NAME;
														} else {
															if (strpos($SUB_PAGE_URL, "script:") == true) {
																$SUB_PAGE_URL = $SUB_PAGE_URL;
															} else {
																$SUB_PAGE_URL = $SUB_PAGE_URL;
															}
														}
														?>
														<li><a href="<?= $SUB_PAGE_URL ?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank"
																	title="새창열기" <? } ?> class="dep2"><?= $SUB_PAGE_NAME ?></a></li>
													<?
													}
												}
												?>
											</ul>
										<?
										}
										?>
									</li>
								<?
								}
							}
							?>
							<? if (!empty($_SESSION['m_no'])) { ?>
							<li>
								<a href="/member/mypage.do" class="dep1">마이페이지</a>
								<ul class="dep2-menu-list">
									<li><a href="/member/mypage.do" class="dep2">나의 회원정보</a></li>
									<li><a href="/member/mypage_reservation.do" class="dep2">나의 예약정보</a></li>
								</ul>
							</li>
							<? } ?>
						</ul>
					</div>
				</div>
				<button type="button" class="menuClose btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span class="blind">닫기</span>
				</button>
			</div>
		</div>
		<!-- 전체메뉴 -->
	</div>
	<!-- Mobile Nav Modal -->

	<!-- s: 전체메뉴 -->
	<!-- <div class="area-siteMap modal" id="sitemap"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

</div> -->
	<!-- e: 전체메뉴 -->
</header>