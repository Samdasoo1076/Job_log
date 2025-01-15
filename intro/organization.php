<?session_start();?>
<?
$_PAGE_NO = "19";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>
		<!-- Container -->
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
						<h2 class="title">조직도</h2>
						<!-- <p class="explain">설명이 들어가는 경우</p> -->
					</div>
					<!-- // 타이틀 영역 -->

					<!-- 연혁 영역 -->
					<div class="organization-wrap">
						<div class="organization-box">
							<img class="pc" src="../../assets/images/content/img-organimation-pc.svg">
							<img class="mo" src="../../assets/images/content/img-organimation-mo.svg">
						</div>
						<div class="organization-table">
							<div class="tbl-scroll">
								<table class="tbl centered">
									<caption>
										<strong>조직도 상세 표</strong>
										<p>부서, 성명, 직책, 전화번호, 주요업무, 팩스로 구성되었습니다.</p>
									</caption>
									<colgroup>
										<col style="width: 16%" />
										<col style="width: 10%" />
										<col style="width: 8%" />
										<col style="width: 18%" />
										<col style="width: auto" />
										<col style="width: 18%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">부서</th>
											<th scope="col">성명</th>
											<th scope="col">직책</th>
											<th scope="col">전화번호</th>
											<th scope="col">주요업무</th>
											<th scope="col">팩스</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>원장실</td>
											<td>조영희</td>
											<td>원장</td>
											<td>033-764-2721</td>
											<td>
												<ul class="bul dot">
													<li>진흥원 업무 총괄</li>
												</ul>
											</td>
											<td></td>
										</tr>
										<tr>
											<td rowspan="6">경영지원실</td>
											<td>신현정</td>
											<td>실장</td>
											<td>033-764-2722</td>
											<td>
												<ul class="bul dot">
													<li>경영지원실 업무 총괄</li>
												</ul>
											</td>
											<td rowspan="6">033-764-9022</td>
										</tr>
										<tr>
											<td>이혁제</td>
											<td>부장</td>
											<td>033-737-3028</td>
											<td>
												<ul class="bul dot">
													<li>경영지원실 운영 총괄</li>
													<li>클라우드 사업 총괄 및 운영</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>곽정아</td>
											<td>과장</td>
											<td>033-764-3159</td>
											<td>
												<ul class="bul dot">
													<li>계약, 총무, 홍보, 인사·노무, 예·결산</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>박은규</td>
											<td>과장</td>
											<td>033-737-3026</td>
											<td>
												<ul class="bul dot">
													<li>클라우드 사업 발굴 및 업무 추진</li>
													<li>복무 관리</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>김태수</td>
											<td>대리</td>
											<td>033-764-3160</td>
											<td>
												<ul class="bul dot">
													<li>이사회 운영, 규정 관리, 창업지원허브 임대사업, 교육, 경영평가</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>안선재</td>
											<td>주임</td>
											<td>033-764-9020</td>
											<td>
												<ul class="bul dot">
													<li>회계, 급여, 세무, 경영공시, 관용차량 관리, 서무</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td rowspan="4">디지털<br>산업부</td>
											<td>김태형</td>
											<td>부장</td>
											<td>033-764-3151</td>
											<td>
												<ul class="bul dot">
													<li>디지털산업부 운영 총괄</li>
													<li>모빌리티산업부 운영 총괄(겸직)</li>
												</ul>
											</td>
											<td rowspan="8">033-764-3021</td>
										</tr>
										<tr>
											<td>박하영</td>
											<td>과장</td>
											<td>033-764-3152</td>
											<td>
												<ul class="bul dot">
													<li>미래 산업 진흥을 위한 정책 기획 및 조사&middot;연구</li>
													<li>신산업 분야 공모사업 기획 및 수행 등</li>
													<li>주력산업과 ICT 융합을 통한 고도화 지원사업 기획 및 수행 등</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>방지수</td>
											<td>대리</td>
											<td>033-764-3153</td>
											<td>
												<ul class="bul dot">
													<li>지역 주력산업 육성 및 고도화 사업기획 및 수행</li>
													<li>지역 SW산업진흥기관 지정 업무</li>
													<li>강원 ICT 글로벌 마케팅 기획 및 수행</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>박종영</td>
											<td>주임</td>
											<td>033-764-3154</td>
											<td>
												<ul class="bul dot">
													<li>디지털산업 유관 기관&middot;단체&middot;협회 등 운영 지원</li>
													<li>전문인력 양성 및 창업&middot;보육 등</li>
													<li>기타 업무지원</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td rowspan="4">모빌리티<br>산업부</td>
											<td></td>
											<td>부장</td>
											<td>033-764-3155</td>
											<td>
												<ul class="bul dot">
													<li>모빌리티산업부 운영 총괄</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>임근택</td>
											<td>과장</td>
											<td>033-764-3156</td>
											<td>
												<ul class="bul dot">
													<li>모빌리티 산업 분야 공모 사업기획 및 수행 등</li>
													<li>미래차 산업 지원 기반 조성 및 기업 육성 지원</li>
													<li>미래차 사업 육성</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>문성록</td>
											<td>대리</td>
											<td>033-764-3157</td>
											<td>
												<ul class="bul dot">
													<li>모빌리티 산업 분야 공모 사업기획 및 수행 등</li>
													<li>드론 산업 육성 및 활성화 지원</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td>서진형</td>
											<td>주임</td>
											<td>033-764-3158</td>
											<td>
												<ul class="bul dot">
													<li>미래 신산업 인재양성 허브구축 실무 담당</li>
													<li>유관 기관&middot;단체&middot;협회 등 운영 지원</li>
													<li>전문인력 양성 및 창업&middot;보육 등</li>
													<li>강원 이모빌리티 협회 협업 지원</li>
													<li>기타 업무지원</li>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
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