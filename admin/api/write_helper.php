<?php
require __DIR__ . '/../common/common_inc.php';

header('Content-Type: application/json; charset=utf-8');

function aiJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function aiPost(string $key, int $maxLength = 6000): string
{
    $value = trim((string)($_POST[$key] ?? ''));
    if (mb_strlen($value, 'UTF-8') > $maxLength) {
        return mb_substr($value, 0, $maxLength, 'UTF-8');
    }
    return $value;
}

function aiDailyLimit(): void
{
    $config = aiConfig();
    $today = date('Y-m-d');
    if (($_SESSION['ai_write_helper_date'] ?? '') !== $today) {
        $_SESSION['ai_write_helper_date'] = $today;
        $_SESSION['ai_write_helper_count'] = 0;
    }

    $limit = (int)(getenv('GITHUB_MODELS_DAILY_LIMIT') ?: ($config['daily_limit'] ?? 25));
    if ((int)($_SESSION['ai_write_helper_count'] ?? 0) >= $limit) {
        aiJson([
            'success' => false,
            'message' => '오늘 AI 글쓰기 도우미 사용량을 모두 사용했습니다.',
        ], 429);
    }
}

function aiConfig(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $path = __DIR__ . '/../private/ai_config.php';
    if (is_file($path)) {
        $loaded = require $path;
        $config = is_array($loaded) ? $loaded : [];
        return $config;
    }

    $config = [];
    return $config;
}

function aiSanitizeHtml(string $html): string
{
    $html = preg_replace('/```(?:html)?|```/i', '', $html);
    $html = preg_replace('/<(script|style|iframe|object|embed|form|input|button|textarea|select|meta|link)[\s\S]*?<\/\1>/i', '', $html);
    $html = preg_replace('/<(script|style|iframe|object|embed|form|input|button|textarea|select|meta|link)\b[^>]*>/i', '', $html);
    $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
    $html = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
    $html = preg_replace('/href\s*=\s*([\'"])\s*javascript:[\s\S]*?\1/i', 'href="#"', $html);

    $allowed = '<h2><h3><h4><p><br><ul><ol><li><strong><b><em><i><span><a><div><code><pre><blockquote>';
    return trim(strip_tags($html, $allowed));
}

function aiBuildPrompt(string $mode, string $notes, string $style, string $title, string $description, string $content): string
{
    $modeLabel = [
        'daily' => '오늘 겪은 일/배운 점을 블로그 회고 초안으로 작성',
        'cv' => '소개글/이력서 스타일 섹션 작성',
        'project' => '프로젝트 경험 설명 작성',
        'improve' => '기존 글을 더 자연스럽게 다듬기',
    ][$mode] ?? '블로그 글 초안 작성';

    return <<<PROMPT
작업: {$modeLabel}

작성자가 입력한 내용:
{$notes}

원하는 스타일/톤:
{$style}

현재 글 제목:
{$title}

현재 설명:
{$description}

현재 본문 일부:
{$content}

출력 규칙:
- 결과는 설명 없이 HTML 조각만 출력한다.
- Markdown 문법과 코드펜스는 쓰지 않는다.
- 한국어로 작성한다.
- 개발자 이지민이 직접 쓰는 개인 기술 블로그의 문체로 작성한다.
- 추상적인 서론보다 실제 업무 상황, 다룬 데이터, 발생한 문제부터 시작한다.
- 한 문단을 길게 늘이지 말고 1~3문장 단위로 끊는다.
- 경험한 사실과 그때의 판단 과정을 중심으로 쓴다. 입력에 없는 수치나 성과는 만들지 않는다.
- 문장 끝은 했다, 였다, 보였다, 알게 됐다를 자연스럽게 섞는다.
- h2는 핵심 전환점에만 사용하고 h3는 꼭 필요할 때만 사용한다.
- 다음 표현은 사용하지 않는다: "단순히 ~가 아니라", "이번 작업을 통해 많은 것을 배웠다", "효율적으로", "견고한", "한층 더", "자연스럽게", "기대할 수 있다", "살펴보자", "다음과 같다", "결국 좋은 설계는".
- 자기 글을 해설하는 문장, 과장된 성장 서사, 홍보 문구를 넣지 않는다.
- 다음 문체를 참고하되 문장을 복사하지 않는다: "전자결재 데이터를 마이그레이션하면서 가장 많이 다룬 값은 DOCID였다.", "그런데 실제 데이터베이스에는 FOREIGN KEY 제약조건이 없었다.", "처음에는 오래된 시스템이라 외래키를 생략한 것이라고 생각했다. 하지만 데이터를 옮기고 검증하면서, 단순한 누락이라고 보기 어려운 상황들을 마주했다."
- 이 블로그는 개발자 이지민의 개인 블로그이며, VS Code/문서형 느낌의 어두운 UI에 본문 HTML이 그대로 들어간다.
- 사용 가능한 태그: h2, h3, h4, p, br, ul, ol, li, strong, em, span, a, div, code, pre, blockquote
- 사용 가능한 색상 클래스: text-blue, text-green, text-orange, text-yellow, text-white, text-red, text-purple, text-teal, text-gray, text-info, text-warning
- 용어 보충 설명은 '<span class="term-tip" data-tip="추가 설명">용어</span>' 형태를 사용한다.
- term-tip은 짧은 용어 설명에만 사용하고, 긴 설명은 dev-note 블록을 사용한다.
- 사용 가능한 개발자 문장 블록 클래스: dev-note, dev-warning, dev-summary, dev-problem, dev-solution
- 개발자 문장 블록은 '<div class="dev-note"><p>내용</p></div>'처럼 div 안에 p 태그를 넣어 사용한다.
- 문제 상황은 dev-problem, 해결 방식은 dev-solution, 주의할 점은 dev-warning, 배운 점/정리는 dev-summary, 보충 설명은 dev-note를 사용한다.
- 코드 예제가 필요하면 Markdown 백틱을 쓰지 말고 반드시 '<div class="code-block"><pre><code class="language-sql">코드</code></pre></div>' 구조를 사용한다.
- SQL은 language-sql, Java는 language-java, JavaScript는 language-javascript, PHP는 language-php, HTML은 language-html, CSS는 language-css, Bash/Shell은 language-bash 클래스를 사용한다.
- 코드블럭 안의 '<', '>', '&' 문자는 각각 '&lt;', '&gt;', '&amp;'로 이스케이프한다.
- 짧은 인라인 코드에는 '<code dir="ltr">DOCID</code>'처럼 code 태그만 사용하고, 긴 예제 코드에만 code-block을 사용한다.
- 섹션 제목은 필요하면 '<h2><span class="text-blue">&lt;/&gt;</span> 제목</h2>' 형태를 사용한다.
- 강조는 '<span class="text-green">핵심 문장</span>' 또는 strong 태그를 사용한다.
- 기술스택은 필요할 때만 '<span class="tech-item"><span class="Php"></span>PHP</span>' 형태를 사용한다.
- 사용 가능한 기술 아이콘 클래스: Spring_boot, Spring_framework, Mariadb_white, Mariadb_blue, Php, Oracle, Mssql, Jquery
- script, style, iframe, onclick 같은 이벤트 속성은 절대 사용하지 않는다.
- 본문에 바로 붙여 넣을 수 있도록 완성된 초안만 작성한다.
PROMPT;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    aiJson(['success' => false, 'message' => 'POST 요청만 사용할 수 있습니다.'], 405);
}

aiDailyLimit();

$aiConfig = aiConfig();
$token = getenv('GITHUB_MODELS_TOKEN') ?: ($aiConfig['token'] ?? '');
if (!$token || str_contains($token, '여기에_토큰')) {
    aiJson([
        'success' => false,
        'message' => 'GITHUB_MODELS_TOKEN 또는 admin/private/ai_config.php 토큰이 설정되어 있지 않습니다.',
    ], 500);
}

$mode = aiPost('mode', 30);
$notes = aiPost('notes', 4000);
$style = aiPost('style', 1000);
$title = aiPost('title', 255);
$description = aiPost('description', 500);
$content = aiPost('content', 5000);

if ($notes === '') {
    aiJson(['success' => false, 'message' => '오늘 겪은 일이나 작성할 내용을 입력해 주세요.'], 400);
}

$model = getenv('GITHUB_MODELS_MODEL') ?: ($aiConfig['model'] ?? 'openai/gpt-4.1');
$payload = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => '너는 개발자 개인 블로그 관리자 화면에 들어가는 글쓰기 도우미다. 사용자의 경험을 HTML 본문 초안으로 바꾼다.',
        ],
        [
            'role' => 'user',
            'content' => aiBuildPrompt($mode, $notes, $style, $title, $description, $content),
        ],
    ],
    'temperature' => 0.45,
    'max_tokens' => 1400,
];

$headers = [
    'Accept: application/vnd.github+json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
    'X-GitHub-Api-Version: 2026-03-10',
    'User-Agent: leejimin-blog-ai',
];

$raw = false;
$status = 0;
$requestBody = json_encode($payload, JSON_UNESCAPED_UNICODE);

if (function_exists('curl_init')) {
    $ch = curl_init('https://models.github.ai/inference/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $requestBody,
        CURLOPT_TIMEOUT => 45,
    ]);

    $raw = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false) {
        aiJson(['success' => false, 'message' => 'GitHub Models 호출 실패: ' . $curlError], 502);
    }
} else {
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => implode("\r\n", $headers),
            'content' => $requestBody,
            'timeout' => 45,
            'ignore_errors' => true,
        ],
    ]);
    $raw = @file_get_contents('https://models.github.ai/inference/chat/completions', false, $context);
    $responseHeader = $http_response_header ?? [];
    if (isset($responseHeader[0]) && preg_match('/\s(\d{3})\s/', $responseHeader[0], $matches)) {
        $status = (int)$matches[1];
    }

    if ($raw === false) {
        aiJson(['success' => false, 'message' => 'GitHub Models 호출 실패'], 502);
    }
}

if ($status === 429) {
    aiJson(['success' => false, 'message' => 'GitHub Models 무료 사용량 또는 속도 제한에 도달했습니다. 잠시 후 다시 시도해 주세요.'], 429);
}

if ($status < 200 || $status >= 300) {
    $errorData = json_decode((string)$raw, true);
    $errorMessage = $errorData['message']
        ?? $errorData['error']['message']
        ?? $errorData['error']['details']
        ?? 'GitHub Models 응답 오류가 발생했습니다.';
    aiJson([
        'success' => false,
        'message' => $errorMessage,
        'status' => $status,
    ], 502);
}

$data = json_decode($raw, true);
$html = $data['choices'][0]['message']['content'] ?? '';
$html = aiSanitizeHtml((string)$html);

if ($html === '') {
    aiJson(['success' => false, 'message' => 'AI 응답에서 사용할 본문을 찾지 못했습니다.'], 502);
}

$_SESSION['ai_write_helper_count'] = (int)($_SESSION['ai_write_helper_count'] ?? 0) + 1;

aiJson([
    'success' => true,
    'html' => $html,
    'model' => $model,
    'remaining' => max(0, (int)(getenv('GITHUB_MODELS_DAILY_LIMIT') ?: ($aiConfig['daily_limit'] ?? 25)) - (int)$_SESSION['ai_write_helper_count']),
]);
