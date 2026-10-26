<?php
require __DIR__ . '/../common/common_inc.php';
include __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/public/css/post.css?v=<?= filemtime(__DIR__ . '/../../public/css/post.css') ?>">
<link rel="stylesheet" href="/admin/css/html_guide.css?v=<?= filemtime(__DIR__ . '/../css/html_guide.css') ?>">

<section class="html-guide">
  <div class="guide-head">
    <div>
      <h1>본문 HTML 가이드</h1>
      <p>게시글 본문에서 사용할 수 있는 클래스와 HTML 패턴입니다.</p>
    </div>
    <a href="/admin/post/posts_form_pro.php">글쓰기</a>
  </div>

  <div class="guide-grid">
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
