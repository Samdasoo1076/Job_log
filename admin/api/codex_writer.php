<?php
require __DIR__ . '/../common/common_inc.php';
require __DIR__ . '/../com/biz/ai_cli.php';

header('Content-Type: application/json; charset=utf-8');

function codexJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function codexInput(string $key, int $maxLength): string
{
    $value = trim((string)($_POST[$key] ?? ''));
    return mb_strlen($value, 'UTF-8') > $maxLength
        ? mb_substr($value, 0, $maxLength, 'UTF-8')
        : $value;
}

ensureAiCliTables($pdo);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($method === 'POST' && $action === 'enqueue') {
    $notes = codexInput('notes', 6000);
    if ($notes === '') {
        codexJson(['success' => false, 'message' => '생성할 경험이나 내용을 입력해 주세요.'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO ai_cli_job
        (admin_id, post_id, draft_key, job_mode, notes, style, source_title, source_description, source_content)
        VALUES (:admin_id, :post_id, :draft_key, :job_mode, :notes, :style, :source_title, :source_description, :source_content)");
    $stmt->execute([
        ':admin_id' => (string)($_SESSION['admin_id'] ?? ''),
        ':post_id' => (int)($_POST['post_id'] ?? 0) ?: null,
        ':draft_key' => 'codex-' . bin2hex(random_bytes(16)),
        ':job_mode' => codexInput('mode', 30) ?: 'daily',
        ':notes' => $notes,
        ':style' => codexInput('style', 1000),
        ':source_title' => codexInput('title', 255),
        ':source_description' => codexInput('description', 500),
        ':source_content' => codexInput('content', 12000),
    ]);

    codexJson([
        'success' => true,
        'job_id' => (int)$pdo->lastInsertId(),
        'message' => 'CLI 생성 요청이 등록되었습니다. 백그라운드 worker가 처리합니다.',
    ]);
}

if ($method === 'GET' && $action === 'status') {
    $stmt = $pdo->prepare("SELECT id, status, post_id, result_title, result_description, error_message, draft_key
                             FROM ai_cli_job
                            WHERE id = :id AND admin_id = :admin_id");
    $stmt->execute([
        ':id' => (int)($_GET['job_id'] ?? 0),
        ':admin_id' => (string)($_SESSION['admin_id'] ?? ''),
    ]);
    $job = $stmt->fetch();
    if (!$job) {
        codexJson(['success' => false, 'message' => '작업을 찾을 수 없습니다.'], 404);
    }

    codexJson(['success' => true, 'job' => $job]);
}

codexJson(['success' => false, 'message' => '지원하지 않는 요청입니다.'], 400);
