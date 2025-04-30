<? session_start(); ?>
<?
	$nonce = base64_encode(random_bytes(16));
	header("Content-Security-Policy: script-src 'self' 'nonce-$nonce' https://leejimin.kr https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://www.googletagmanager.com;");
?>
<?
$_PAGE_NO = "2";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/v2_common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/main/main.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/board/board.php";

$depth_01_page_name = "";
$depth_02_page_name = "";
$depth_03_page_name = "";
?>
<?
?>
<link rel="stylesheet" href="/assets/css/lee/lee.css">
<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content greeting-wrap">
				<!-- content-header -->
					<?
						require "_common/content-header.php";
					?>
				<!-- // content-header -->


				<!-- content-body -->
				<div class="content-body">
					<!-- 타이틀 영역 -->
					<div class="title-wrap">
						<!-- <h2 class="title">이지민</h2> -->
					<!-- <p class="explain">설명이 들어가는 경우</p> -->
					</div>
					<!-- // 타이틀 영역 -->

					<!-- 게시판목록 영역 -->
					<div class="greeting-wrap">
					<!-- 왼쪽 프로필 -->
					<div class="profile-left">
						<h2>Profile.</h2>

						<!-- <div class="profile-img">
						<img src="../../assets/images/content/이지민이다이.png" alt="이지민 프로필">
						</div> -->

						<h3 class="greeting">
						안녕하세요.<br>
						<span class="badge">개발자</span>이지민 입니다.
						</h3>

						<hr class="divider">

						<div class="contact">
						<!-- <p>Phone: 010-4103-6966</p>
						<p>E-mail: myucheu0617@gmail.com</p> -->
						</div>
					</div>

  <!-- 오른쪽 기존 컨텐츠 (그대로 두세요) -->
						<div class="greeting-cont">
							<h3>안녕하십니까?<br class="pc" /><br class="mo" />
								웹 엔지니어 이지민 입니다.
							</h3>
							<div class="text">
								<p>웹 프론트엔드와 백엔드에 모두 관심이 있으며, <br>여러 환경에서 풀스택 개발자로서 근무했습니다.
									다수의 프로덕션 경험(인하우스 B2C 통합 마케팅 플랫폼, 사내 인트라넷, B2C/B2B 플랫폼 신규 구축 및 리뉴얼, DevOps 등)이 있습니다.

									주어진 시간 내에서 기대 이상의 것을 만들어내는 것을 추구합니다.
									업무가 주어질 때 100% 빈틈 없는 디자인이나 기획은 존재하지 않는다고 생각하며, 소통과 공부로 그 빈틈을 채우는 것을 중요하게 여깁니다.
									</p>
									<br>
								<p style="font-size: 28rem; line-height: 40rem; font-weight: 700;">직무 경험</p>
								<p style="font-size: 20rem; line-height: 20rem; font-weight: 600;">유컴패니온 운영TFT팀  |  백엔드 엔지니어 (2024-02 ~ 현재)
								</p>
								<p style="font-size: 18rem; line-height: 10rem; font-weight: 500;">
								롯데렌터카 (홈페이지 고도화) 2024-02 ~ 2025-04
								</p>
								<p>- 4개 언어(국문·영문·중문·일문) 다국어 시스템 구축 → 해외 예약 증가 기여
								- 간편·효율적 다국어 콘텐츠 관리 체계 및 CMS 개발
								- UX 통계 수집 기능 추가
								- 프로젝트 평가 A 만점 획득
								</p>
							
								<p style="font-size: 18rem; line-height: 10rem; font-weight: 500;">
								한양대·건국대 입학처 사이트 (운영·고도화) 2024-07 ~ 2025-03
								</p>
								<p>- 입학처 콘텐츠 카테고리 관리 기능 개선
								- 공지사항 자동 마감 시스템 구축, 관리자 페이지 개선
								- QR 출입·만족도 조사 기능(반응형 웹) 개발 → 입시 설명회 참여 촉진
								</p>

								<!-- 기존 직무 경험 항목 아래에 이어 붙이세요 -->
								<p style="font-size: 18rem; line-height: 10rem; font-weight: 500;">
								커넥트 플러스 - SKT 사내 홈페이지(운영 및 신규 기능 개발) 2024-08 ~ 2025-04
								</p>
								<p>
								- SKT 계열사 커뮤니케이션 플랫폼 '커넥트 플러스' 운영·유지보수  
								- 시스템 모니터링으로 이슈 사전 감지 및 신속 대응  
								- 관리자 페이지·콘텐츠 관리 기능 유지보수  
								- 메뉴 개편·UI 개선 등 서비스 개선 요청 대응  
								- 통계 기능 수정 및 데이터 시각화 개선  
								</p>

								<p style="font-size: 18rem; line-height: 10rem; font-weight: 500;">
								중랑구 대형생활폐기물 인터넷 신고센터 (운영) 2024-12 ~ 현재
								</p>
								<p>
								- 시스템 코드 관리 기능 개발로 반복 작업 최소화 (주소 검색, 품목 선택)  
								- 시스템 모니터링으로 운영 이슈 사전 감지 및 신속 대응  
								- 소스 코드·DB 자동 백업 도입으로 서비스 안정화  
								</p>

								<p style="font-size: 18rem; line-height: 10rem; font-weight: 500;">
								원주미래산업진흥원 (구축) 2024-11 ~ 2025-01
								</p>
								<p>
								- 소통마당·회원시스템·시설예약 등 주요 기능 설계·개발  
								- CMS 관리자 페이지 개발 (회원·시설·예약 관리)  
								- 소스 코드·DB 자동 백업 도입으로 서비스 안정화  
								</p>

								<!-- 디센트 -->
								<p style="font-size: 20rem; line-height: 20rem; font-weight: 600;">
								디센트 개발 2팀 | 백엔드 연구원 (2022-09 ~ 2023-12)
								</p>
								<p style="font-size: 18rem; line-height: 10rem; font-weight: 500;">
								DproCloud 개발
								</p>
								<p>
								- 주문을 웨이브 단위로 그룹화해 피킹 최적화 기능 개발  
								- Nexacro 이용 빠른 관리자 페이지 구현  
								- 실무 경험 기반 Spring Framework, OracleDB 활용  
								</p>

								<!-- 사이드 프로젝트 -->
								<p style="font-size: 28rem; line-height: 40rem; font-weight: 700;">
								사이드 프로젝트
								</p>
								<!-- 필요시 프로젝트 리스트를 <ul><li> 형태로 추가 -->

								<!-- 강의 & 강연 -->
								<p style="font-size: 28rem; line-height: 40rem; font-weight: 700;">
								강의 &amp; 강연
								</p>
								<p style="font-size: 20rem; line-height: 20rem; font-weight: 600;">
								상일미디어고등학교 (2024-10-06)
								</p>
								<p>
								- 도제학교 우수학생으로 모교 초청 강연  
								- 소프트웨어 전공 소개, 도제학교 과정 설명, 예제 코드 시연  
								</p>

								<!-- 학력 -->
								<p style="font-size: 28rem; line-height: 40rem; font-weight: 700;">
								학력
								</p>
								<p style="font-size: 20rem; line-height: 20rem; font-weight: 600;">
								한국폴리텍대학(성남)  2024-03 ~ 현재  
								</p>
								<p>
								- 전공: IoT 소프트웨어  
								- Arduino, Java, Ubuntu 학습  
								</p>
								<p style="font-size: 20rem; line-height: 20rem; font-weight: 600;">
								상일미디어고(도제학교)  2022-03 ~ 2024-02  
								</p>
								<p>
								- 웹 개발자 과정 (JSP, OracleDB)  
								</p>
								<p style="font-size: 20rem; line-height: 20rem; font-weight: 600;">
								상일미디어고  2021-03 ~ 2024-02  
								</p>
								<p>
								- 전공: 스마트소프트웨어  
								- 동아리: 여울컴  
								</p>

								<!-- 자격증 및 시험 -->
								<p style="font-size: 28rem; line-height: 40rem; font-weight: 700;">
								자격증 및 시험
								</p>
								<p>
								- 정보처리산업기사 (2023년)  
								- SW개발_L3 (2023년)  
								</p>


							</div>
						</div>
					</div>
				</div>
				<!-- // content-body -->
			</div>
			<!-- // content -->
		</main>
<!-- // Container -->
</body>