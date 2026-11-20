<?php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/common_code.php';
include __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/public/css/post.css?v=<?= filemtime(__DIR__ . '/../../public/css/post.css') ?>">
<link rel="stylesheet" href="/admin/css/html_guide.css?v=<?= filemtime(__DIR__ . '/../css/html_guide.css') ?>">
<link rel="stylesheet" href="/public/css/blocks.php">

<section class="html-guide">
  <div class="guide-head">
    <div>
      <h1>본문 HTML 가이드</h1>
      <p>게시글 본문에서 사용할 수 있는 클래스와 HTML 패턴입니다.</p>
    </div>
    <a href="/admin/post/posts_form_pro.php">글쓰기</a>
  </div>

  <section class="writing-guide" aria-labelledby="writing-guide-title">
    <h2 id="writing-guide-title">글 작성 가이드</h2>
    <p>기준 글: <a href="/?post_id=16" target="_blank" rel="noopener noreferrer">leejimin.cv</a>. 색상은 문장의 역할을 구분할 때 사용하고, 본문 전체를 강조하지 않습니다.</p>
    <div class="writing-guide-table"><table>
      <thead><tr><th>역할</th><th>표현</th><th>기준 글에서의 사용</th></tr></thead>
      <tbody>
        <tr><td>섹션 제목</td><td><code>h2</code> + <code>text-blue</code></td><td>인사말, 직무 경험, 사이드 프로젝트, 학력 등 큰 구분</td></tr>
        <tr><td>제목 앞 기호</td><td><code>text-yellow</code>로 감싼 <code>&amp;lt;/&amp;gt;</code></td><td>주요 섹션 앞의 &lt;/&gt; 표시. 모든 문단에 반복하지 않음</td></tr>
        <tr><td>프로젝트·조직·기술 개념</td><td><code>text-green</code>, 필요하면 <code>strong</code></td><td>프로젝트명, 소속명, 모놀리식 아키텍처</td></tr>
        <tr><td>역할·핵심 표현</td><td><code>text-orange</code></td><td>백엔드 엔지니어, 기대 이상의 것, 평가 결과</td></tr>
        <tr><td>중요한 기능·이름</td><td><code>strong</code></td><td>이름과 UX 통계 수집 기능처럼 짧은 단어만 강조</td></tr>
        <tr><td>설명·기간</td><td><code>p</code>, 기본 글자색</td><td>프로젝트 설명과 날짜는 보통 색상으로 유지</td></tr>
        <tr><td>업무와 세부 작업</td><td><code>ul</code> / <code>li</code></td><td>프로젝트 아래 수행한 작업을 항목으로 구분</td></tr>
        <tr><td>근거 링크·기술 스택</td><td><code>a.text-blue</code>, <code>tech-item</code></td><td>관련 기사 링크와 기술 아이콘</td></tr>
      </tbody>
    </table></div>
    <h3>색상과 구조를 함께 사용하는 예시</h3>
    <div class="guide-preview content">
      <h2><span class="text-yellow">&lt;/&gt;</span> <span class="text-blue">게시글 조회 구조</span></h2>
      <p><strong class="text-green">Joblog</strong>의 <span class="text-orange">사용자 화면</span>에서 글을 선택하면 본문 API를 호출한다.</p>
      <p>첫 화면은 PHP가 구성하고, 다른 글을 선택할 때는 <strong>본문 영역만 교체</strong>한다.</p>
      <ul><li>PHP에서 게시글을 조회한다.</li><li>JavaScript가 응답을 받아 본문에 반영한다.</li></ul>
    </div>
    <pre class="guide-code">&lt;h2&gt;&lt;span class="text-yellow"&gt;&amp;lt;/&amp;gt;&lt;/span&gt; &lt;span class="text-blue"&gt;게시글 조회 구조&lt;/span&gt;&lt;/h2&gt;
&lt;p&gt;&lt;strong class="text-green"&gt;Joblog&lt;/strong&gt;의 &lt;span class="text-orange"&gt;사용자 화면&lt;/span&gt;에서 글을 선택하면 본문 API를 호출한다.&lt;/p&gt;
&lt;p&gt;첫 화면은 PHP가 구성하고, 다른 글을 선택할 때는 &lt;strong&gt;본문 영역만 교체&lt;/strong&gt;한다.&lt;/p&gt;
&lt;ul&gt;
  &lt;li&gt;PHP에서 게시글을 조회한다.&lt;/li&gt;
  &lt;li&gt;JavaScript가 응답을 받아 본문에 반영한다.&lt;/li&gt;
&lt;/ul&gt;</pre>
    <h3>내용을 작성할 때</h3>
    <ul class="writing-guide-rules">
      <li>소개글은 기준 글처럼 존댓말로, 기술 경험 글은 실제 상황과 판단을 담은 짧은 회고체로 작성합니다. 글 안에서는 말투를 통일합니다.</li>
      <li>상황 → 문제 → 확인한 내용 → 처리 방법 순서로 풀되, 불필요한 서론과 거창한 결론은 덜어냅니다.</li>
      <li>한 문단에 1~3문장을 담고, 새 주제는 새 문단으로 나눕니다. 기준 글의 br은 소개·역할·기간처럼 묶인 정보에만 참고하고 모바일 줄 길이를 맞추려고 강제 줄바꿈하지 않습니다.</li>
      <li>여러 작업을 나열할 때 목록을 사용합니다. 하위 목록은 상위 li 안에 넣고, 깊게 중첩하지 않습니다.</li>
      <li>코드는 실제 설명에 필요한 부분만 언어별 코드 블록으로 보여줍니다. 파일명과 컬럼명처럼 짧은 값은 인라인 code를 사용합니다.</li>
      <li>문제·주의·정리 블록은 필요한 지점에만 사용합니다. 모든 문단에 박스나 색상을 적용하지 않습니다.</li>
      <li>경험하지 않은 작업, 측정하지 않은 수치, 확인하지 않은 성과는 추가하지 않습니다. CV의 성과 표현도 새 글의 사실로 가져오지 않습니다.</li>
    </ul>
    <h3>저장 전 확인</h3>
    <p>에디터에서는 본문 스타일이 모두 표시되지 않을 수 있습니다. 미리보기에서 소제목 색상, 코드 언어·줄바꿈, 블록과 링크를 확인하고 사용자 화면의 라이트·다크 모드와 모바일에서도 읽히는지 확인합니다. 색상은 고정 인라인 스타일보다 기존 클래스를 사용합니다.</p>
  </section>
  <h2>HTML 구성요소</h2>
  <div class="guide-grid">
    <article class="guide-panel wide">
      <h2>공통 코드 색상 블록</h2>
      <?php foreach (commonCodeOptions($pdo, 'POST_BLOCK') as $block): ?>
        <?php if (!preg_match('/^dev-[a-z][a-z0-9-]{0,44}$/D', $block['DCODE'])) continue; ?>
        <div class="guide-preview content"><div class="<?= htmlspecialchars($block['DCODE'], ENT_QUOTES) ?>"><p><?= htmlspecialchars($block['DCODE_NM']) ?></p></div></div>
        <pre class="guide-code"><?= htmlspecialchars('<div class="' . $block['DCODE'] . '"><p>내용</p></div>') ?></pre>
      <?php endforeach; ?>
    </article>
    <article class="guide-panel">
      <h2>텍스트 색상</h2>
      <div class="guide-preview content">
        <p>
          <span class="text-blue">text-blue</span>
          <span class="text-green">text-green</span>
          <span class="text-orange">text-orange</span>
          <span class="text-yellow">text-yellow</span>
          <span class="text-red">text-red</span>
          <span class="text-purple">text-purple</span>
          <span class="text-teal">text-teal</span>
          <span class="text-gray">text-gray</span>
        </p>
      </div>
      <pre class="guide-code">&lt;span class="text-blue"&gt;강조 문장&lt;/span&gt;</pre>
    </article>

    <article class="guide-panel">
      <h2>인라인 코드</h2>
      <div class="guide-preview content">
        <p>짧은 컬럼명은 <code dir="ltr">DOCID</code>, <code dir="ltr">FOREIGN KEY</code>처럼 표시합니다.</p>
      </div>
      <pre class="guide-code">&lt;code dir="ltr"&gt;DOCID&lt;/code&gt;</pre>
    </article>

    <article class="guide-panel">
      <h2>용어 설명</h2>
      <div class="guide-preview content">
        <p><span class="term-tip" data-tip="문서의 고유 식별자로, 시스템마다 생성 규칙이 다를 수 있습니다.">DOCID</span>처럼 짧은 보충 설명이 필요한 단어에 사용합니다.</p>
        <p><span class="term-tip" data-tip="DB가 직접 강제하지 않지만 애플리케이션이나 운영 규칙으로 관리하는 참조 관계입니다.">논리적 외래키</span>처럼 글의 흐름을 끊지 않고 설명을 덧붙일 수 있습니다.</p>
      </div>
      <pre class="guide-code">&lt;span class="term-tip" data-tip="추가 설명"&gt;용어&lt;/span&gt;</pre>
      <p class="guide-note">마우스를 올리거나 클릭하면 설명 레이어가 표시됩니다. 긴 설명은 dev-note 블록을 사용하세요.</p>
    </article>

    <article class="guide-panel wide">
      <h2>코드블럭</h2>
      <div class="guide-preview content">
        <div class="code-block">
          <pre><code class="language-sql">SELECT id, title
FROM post
WHERE use_tf = 'Y'
  AND del_tf = 'N';</code></pre>
        </div>
      </div>
      <pre class="guide-code">&lt;div class="code-block"&gt;
  &lt;pre&gt;&lt;code class="language-sql"&gt;SELECT *
FROM post
WHERE use_tf = 'Y';&lt;/code&gt;&lt;/pre&gt;
&lt;/div&gt;</pre>
      <p class="guide-note">언어 클래스: language-sql, language-java, language-javascript, language-php, language-html, language-css, language-bash</p>
    </article>

    <article class="guide-panel">
      <h2>보충 설명</h2>
      <div class="guide-preview content">
        <div class="dev-note">
          <p>맥락이나 보충 설명을 짧게 정리할 때 사용합니다.</p>
        </div>
      </div>
      <pre class="guide-code">&lt;div class="dev-note"&gt;
  &lt;p&gt;보충 설명&lt;/p&gt;
&lt;/div&gt;</pre>
    </article>

    <article class="guide-panel">
      <h2>주의</h2>
      <div class="guide-preview content">
        <div class="dev-warning">
          <p>실수하기 쉬운 조건이나 운영상 주의할 점을 표시합니다.</p>
        </div>
      </div>
      <pre class="guide-code">&lt;div class="dev-warning"&gt;
  &lt;p&gt;주의할 점&lt;/p&gt;
&lt;/div&gt;</pre>
    </article>

    <article class="guide-panel">
      <h2>문제 상황</h2>
      <div class="guide-preview content">
        <div class="dev-problem">
          <p>문제가 발생한 조건과 증상을 분리해서 설명합니다.</p>
        </div>
      </div>
      <pre class="guide-code">&lt;div class="dev-problem"&gt;
  &lt;p&gt;문제 상황&lt;/p&gt;
&lt;/div&gt;</pre>
    </article>

    <article class="guide-panel">
      <h2>해결 방식</h2>
      <div class="guide-preview content">
        <div class="dev-solution">
          <p>적용한 해결책과 선택 이유를 정리합니다.</p>
        </div>
      </div>
      <pre class="guide-code">&lt;div class="dev-solution"&gt;
  &lt;p&gt;해결 방식&lt;/p&gt;
&lt;/div&gt;</pre>
    </article>

    <article class="guide-panel">
      <h2>정리</h2>
      <div class="guide-preview content">
        <div class="dev-summary">
          <p>글의 결론, 배운 점, 다음에 적용할 기준을 정리합니다.</p>
        </div>
      </div>
      <pre class="guide-code">&lt;div class="dev-summary"&gt;
  &lt;p&gt;정리 내용&lt;/p&gt;
&lt;/div&gt;</pre>
    </article>

    <article class="guide-panel wide">
      <h2>기술 아이콘</h2>
      <div class="guide-preview content">
        <p>
          <span class="tech-item"><span class="Php"></span>PHP</span>
          <span class="tech-item"><span class="Mariadb_blue"></span>MariaDB</span>
          <span class="tech-item"><span class="Spring_boot"></span>Spring Boot</span>
        </p>
      </div>
      <pre class="guide-code">&lt;span class="tech-item"&gt;&lt;span class="Php"&gt;&lt;/span&gt;PHP&lt;/span&gt;
&lt;span class="tech-item"&gt;&lt;span class="Mariadb_blue"&gt;&lt;/span&gt;MariaDB&lt;/span&gt;</pre>
    </article>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" defer></script>
<script src="/public/js/code_blocks.js?v=<?= filemtime(__DIR__ . '/../../public/js/code_blocks.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
