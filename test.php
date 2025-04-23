<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - My Portfolio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <nav class="lnb">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li class="has-submenu">
                    <a href="blog.html">Blog</a>
                    <ul class="submenu">
                      <li><a href="blog.html?category=web">Web</a></li>
                      <li><a href="blog.html?category=react">React</a></li>
                      <li><a href="blog.html?category=backend">Backend</a></li>
                    </ul>
                  </li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>
    <main>
    <section id="home">
        <div class="container">

            <div class="info">
  <div class="info-item">
    <!-- 프로필 -->
    <h2>안녕하세요, 엔지니어 <strong>이지민</strong> 입니다.</h2>
    <p>웹 프론트엔드와 백엔드에 모두 관심이 있으며, 여러 환경에서 풀스택 개발자로서 일했습니다.
        <br>
        다수의 프로덕션 경험(인하우스 B2C 통합 마케팅 플랫폼, 사내 인트라넷, B2C/B2B 플랫폼 신규 구축 및 리뉴얼, DevOps 등)이 있습니다.</p>
    <p>주어진 시간 내에서 기대 이상의 것을 만들어내는 것을 추구합니다. 
        <br>업무가 주어질 때 100% 빈틈 없는 디자인이나 기획은 존재하지 않는다고 생각하며, 소통과 공부로 그 빈틈을 채우는 것을 중요하게 여깁니다.</p>

    <!-- 직무 경험 -->
    <h3>직무 경험</h3>
    <h4>유컴패니온 운영TFT팀 &nbsp;|&nbsp; 백엔드 엔지니어 (2024-02 ~ 현재)</h4>
    <ul>
      <li>
        <strong>롯데렌터카 (홈페이지 고도화) 2024-02 ~ 2025-04</strong>
        <ul>
          <li>4개 언어(국문·영문·중문·일문) 다국어 시스템 구축 → 해외 예약 증가 기여</li>
          <li>간편·효율적 다국어 콘텐츠 관리 체계 및 CMS 개발</li>
          <li>UX 통계 수집 기능 추가</li>
          <li>프로젝트 평가 A 만점 획득</li>
        </ul>
        <p><em>기술:</em> Spring Framework, OracleDB</p>
      </li>
      <li>
        <strong>한양대·건국대 입학처 사이트 (운영·고도화) 2024-07 ~ 2025-03</strong>
        <ul>
          <li>입학처 콘텐츠 카테고리 관리 기능 개선</li>
          <li>공지사항 자동 마감 시스템 구축, 관리자 페이지 개선</li>
          <li>QR 출입·만족도 조사 기능(반응형 웹) 개발 → 입시 설명회 참여 촉진</li>
        </ul>
        <p><em>기술:</em> Spring Framework, OracleDB</p>
      </li>
      <li>
        <strong>커넥트 플러스 (SK 사내 홈페이지) 2024-08 ~ 2025-04</strong>
        <ul>
          <li>시스템 모니터링 및 이슈 대응</li>
          <li>관리자 페이지 유지보수·UI 개선</li>
          <li>통계 기능 수정 및 데이터 시각화 개선</li>
        </ul>
        <p><em>기술:</em> Spring Boot, Mustache, MSSQL</p>
      </li>
      <li>
        <strong>중랑구 대형생활폐기물 신고센터 (운영) 2024-12 ~ 현재</strong>
        <ul>
          <li>주소 검색·품목 선택 자동화 기능 개발</li>
          <li>DB·소스코드 자동 백업 도입</li>
        </ul>
        <p><em>기술:</em> PHP, MariaDB, jQuery</p>
      </li>
      <li>
        <strong>원주미래산업진흥원 (구축) 2024-11 ~ 2025-01</strong>
        <ul>
          <li>회원 시스템·시설 예약 기능 개발</li>
          <li>CMS(관리자 페이지) 개발</li>
          <li>DB·소스코드 자동 백업 도입</li>
        </ul>
        <p><em>기술:</em> PHP, MariaDB, jQuery</p>
      </li>
    </ul>

    <h4>디센트 개발 2팀 &nbsp;|&nbsp; 백엔드 연구원 (2022-09 ~ 2023-12)</h4>
    <ul>
      <li>
        <strong>DproCloud</strong>
        <ul>
          <li>주문 그룹화 피킹 최적화 기능 개발</li>
          <li>Nexacro 기반 관리자 페이지 빠른 개발</li>
        </ul>
        <p><em>기술:</em> Spring Framework, OracleDB</p>
      </li>
    </ul>

    <!-- 사이드 프로젝트 -->
    <h3>사이드 프로젝트</h3>
    <p>작성 중…</p>

    <!-- 강의 & 강연 -->
    <h3>강의 &amp; 강연</h3>
    <ul>
      <li>
        <strong>상일미디어고등학교 (2024-10-06)</strong>  
        도제학교 우수학생 초청 강연 (소프트웨어 전공·도제학교 소개 및 예제 코드)
      </li>
    </ul>

    <!-- 학력 -->
    <h3>학력</h3>
    <ul>
      <li>
        <strong>한국폴리텍대학(성남) (2024-03 ~)</strong>  
        전공: IoT 소프트웨어 (Arduino, Java, Ubuntu)
      </li>
      <li>
        <strong>상일미디어고(도제학교) (2022-03 ~ 2024-02)</strong>  
        웹 개발자 과정 (JSP, OracleDB)
      </li>
      <li>
        <strong>상일미디어고 (2021-03 ~ 2024-02)</strong>  
        전공: 스마트소프트웨어, 동아리 여울컴
      </li>
    </ul>

    <!-- 자격증 및 시험 -->
    <h3>자격증 및 시험</h3>
    <ul>
      <li>정보처리산업기사 (2023)</li>
      <li>SW개발_L3 (2023)</li>
    </ul>
  </div>
</div>
        </div>
    </section>

    </main>
    </div>
    <footer>
        <p>© 2025 [이지민]. All Rights Reserved.</p>
    </footer>
</body>
</html>
