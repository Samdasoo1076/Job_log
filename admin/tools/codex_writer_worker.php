<?php

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "CLI에서만 실행할 수 있습니다.\n");
    exit(1);
}

require __DIR__ . '/../db.php';
require __DIR__ . '/../com/biz/ai_cli.php';

ensureAiCliTables($pdo);
$once = in_array('--once', $argv ?? [], true);

function workerPrompt(array $job): string
{
    $modeLabels = [
        'daily' => '오늘 겪은 일/배운 점을 블로그 회고 초안으로 작성',
        'cv' => '소개글/이력서 스타일 섹션 작성',
        'project' => '프로젝트 경험 설명 작성',
        'improve' => '기존 글을 더 자연스럽게 다듬기',
    ];
    $mode = $modeLabels[$job['job_mode']] ?? '개발 블로그 글 초안 작성';

    return <<<PROMPT
작업: {$mode}

작성자가 입력한 경험과 요구사항:
{$job['notes']}

원하는 스타일:
{$job['style']}

현재 제목:
{$job['source_title']}

현재 설명:
{$job['source_description']}

현재 본문:
{$job['source_content']}

너는 이 프로젝트의 개발자 이지민이 직접 쓰는 개인 기술 블로그 글을 작성한다.
아래 문체를 기준으로 삼되 문장을 그대로 복사하지 않는다.

문체 참고:
- "전자결재 데이터를 마이그레이션하면서 가장 많이 다룬 값은 DOCID였다."
- "그런데 실제 데이터베이스에는 FOREIGN KEY 제약조건이 없었다."
- "처음에는 오래된 시스템이라 외래키를 생략한 것이라고 생각했다. 하지만 데이터를 옮기고 검증하면서, 단순한 누락이라고 보기 어려운 상황들을 마주했다."
- "이 작업을 거치며 외래키를 바라보는 기준도 달라졌다. 중요한 것은 단순히 FK를 설정했느냐가 아니었다."

문체 규칙:
- 추상적인 서론보다 실제 업무 상황, 다룬 데이터, 발생한 문제부터 시작한다.
- 한 문단을 길게 늘이지 말고 1~3문장 단위로 끊는다.
- 경험한 사실과 그때의 판단 과정을 중심으로 쓴다. 경험하지 않은 수치, 성과, 상황은 만들지 않는다.
- 문장 끝은 했다, 였다, 보였다, 알게 됐다를 자연스럽게 섞는다.
- 섹션을 지나치게 잘게 나누지 않는다. h2는 핵심 전환점에만 사용하고 h3는 꼭 필요할 때만 사용한다.
- 결론을 억지로 거창하게 만들지 않는다. 글에서 다룬 경험의 범위 안에서 마무리한다.
- 다음 표현은 사용하지 않는다: "단순히 ~가 아니라", "이번 작업을 통해 많은 것을 배웠다", "효율적으로", "견고한", "한층 더", "자연스럽게", "기대할 수 있다", "살펴보자", "다음과 같다", "결국 좋은 설계는".
- AI가 작성한 글처럼 보이는 자기 해설, 과장된 성장 서사, 홍보 문구를 넣지 않는다.
최종 응답은 반드시 JSON 객체 하나만 출력한다. Markdown 코드펜스나 설명 문장을 붙이지 않는다.
JSON 형식: {"title":"게시글 제목","description":"짧은 설명","content":"본문 HTML"}

본문 HTML 규칙:
- 한국어로 작성한다.
- 허용 태그는 h2, h3, h4, p, br, ul, ol, li, strong, b, em, i, span, a, div, code, pre, blockquote, hr, table, thead, tbody, tr, th, td다.
- Markdown 문법과 코드펜스를 사용하지 않는다.
- 용어 설명은 <span class="term-tip" data-tip="추가 설명">용어</span>을 사용한다.
- 개발자 블록은 dev-note, dev-warning, dev-summary, dev-problem, dev-solution 클래스를 사용한다.
- SQL, Java, JavaScript, PHP, HTML, CSS, Bash 코드는 <div class="code-block"><pre><code class="language-언어">코드</code></pre></div> 구조로 작성한다.
- 코드 블록 안의 <, >, & 문자는 각각 &lt;, &gt;, &amp;로 이스케이프한다.
- 짧은 인라인 코드는 <code dir="ltr">내용</code>을 사용한다.
- script, style, iframe, object, embed, form, input, button, onclick 같은 이벤트 속성은 사용하지 않는다.
- 완성된 게시글 HTML만 content에 넣는다.
PROMPT;
}

function claimJob(PDO $pdo): ?array
{
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->query("SELECT * FROM ai_cli_job WHERE status = 'queued' ORDER BY created_at, id LIMIT 1 FOR UPDATE");
        $job = $stmt->fetch();
        if (!$job) {
            $pdo->commit();
            return null;
        }
        $update = $pdo->prepare("UPDATE ai_cli_job SET status = 'processing', started_at = NOW() WHERE id = :id AND status = 'queued'");
        $update->execute([':id' => $job['id']]);
        $pdo->commit();
        return $job;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function failJob(PDO $pdo, int $jobId, string $message): void
{
    $stmt = $pdo->prepare("UPDATE ai_cli_job SET status = 'failed', error_message = :message, finished_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $jobId, ':message' => mb_substr($message, 0, 2000, 'UTF-8')]);
}

function runCodex(array $job): array
{
    $codex = getenv('CODEX_BIN') ?: '/home/dasoo/.nvm/versions/node/v20.19.6/bin/codex';
    if (!is_executable($codex)) {
        throw new RuntimeException('Codex CLI를 찾을 수 없습니다: ' . $codex);
    }

    $schema = __DIR__ . '/../private/codex_post_schema.json';
    $output = tempnam(sys_get_temp_dir(), 'codex-post-');
    $command = implode(' ', array_map('escapeshellarg', [
        $codex, 'exec', '--json', '--ephemeral', '--sandbox', 'read-only',
        '--skip-git-repo-check', '--output-schema', $schema,
        '--output-last-message', $output, '-C', dirname(__DIR__, 2), '-'
    ]));

    $process = proc_open($command, [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ], $pipes, dirname(__DIR__, 2));
    if (!is_resource($process)) {
        @unlink($output);
        throw new RuntimeException('Codex CLI 프로세스를 시작하지 못했습니다.');
    }

    fwrite($pipes[0], workerPrompt($job));
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);
    $raw = is_file($output) ? file_get_contents($output) : '';
    @unlink($output);

    if ($exitCode !== 0 || trim($raw) === '') {
        throw new RuntimeException(trim($stderr) ?: trim($stdout) ?: 'Codex CLI가 결과를 생성하지 못했습니다.');
    }

    $result = json_decode($raw, true);
    if (!is_array($result) || !isset($result['content'])) {
        throw new RuntimeException('Codex 결과가 게시글 JSON 형식이 아닙니다.');
    }

    return [
        'title' => mb_substr(trim((string)($result['title'] ?? $job['source_title'])), 0, 255, 'UTF-8'),
        'description' => mb_substr(trim((string)($result['description'] ?? $job['source_description'])), 0, 500, 'UTF-8'),
        'content' => aiCliSanitizeHtml((string)$result['content']),
    ];
}

function saveDraft(PDO $pdo, array $job, array $result): void
{
    $stmt = $pdo->prepare("INSERT INTO post_draft
        (draft_key, post_id, admin_id, title, description, content, is_featured, allow_comment, use_tf)
        VALUES (:draft_key, :post_id, :admin_id, :title, :description, :content, 0, 'Y', 'N')
        ON DUPLICATE KEY UPDATE
            post_id = VALUES(post_id), admin_id = VALUES(admin_id), title = VALUES(title),
            description = VALUES(description), content = VALUES(content), updated_at = NOW()");
    $stmt->execute([
        ':draft_key' => $job['draft_key'],
        ':post_id' => $job['post_id'] ?: null,
        ':admin_id' => $job['admin_id'],
        ':title' => $result['title'],
        ':description' => $result['description'],
        ':content' => $result['content'],
    ]);
}

while (true) {
    $job = claimJob($pdo);
    if (!$job) {
        if ($once) {
            exit(0);
        }
        sleep(3);
        continue;
    }

    try {
        fwrite(STDOUT, "AI CLI 작업 #{$job['id']} 처리 중...\n");
        $result = runCodex($job);
        if ($result['content'] === '') {
            throw new RuntimeException('생성된 본문이 비어 있습니다.');
        }
        saveDraft($pdo, $job, $result);
        $stmt = $pdo->prepare("UPDATE ai_cli_job SET status = 'completed', result_title = :title,
            result_description = :description, result_content = :content, finished_at = NOW() WHERE id = :id");
        $stmt->execute([
            ':id' => $job['id'], ':title' => $result['title'],
            ':description' => $result['description'], ':content' => $result['content'],
        ]);
        fwrite(STDOUT, "AI CLI 작업 #{$job['id']} 완료\n");
    } catch (Throwable $e) {
        failJob($pdo, (int)$job['id'], $e->getMessage());
        fwrite(STDERR, "AI CLI 작업 #{$job['id']} 실패: {$e->getMessage()}\n");
    }

    if ($once) {
        exit(0);
    }
}
