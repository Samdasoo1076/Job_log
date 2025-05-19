<?php
// /common/logger.php

require_once __DIR__ . '/../db.php';
// require_once __DIR__ . '/com/etc.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 사용자의 페이지 조회를 로그에 남깁니다.
 *
 * @param int|null $postId 조회한 포스트 ID, 없으면 NULL
 */
function log_user_activity(?int $postId = null): void {
    global $pdo;
    $now = new DateTime();

    $data = [
        ':log_date'     => $now->format('Y-m-d'),
        ':log_hour'     => (int)$now->format('H'),
        ':log_minute'   => (int)$now->format('i'),
        ':log_week'     => (int)$now->format('W'),
        ':post_id'      => $postId,
        ':url'          => $_SERVER['REQUEST_URI'],
        ':device_type'  => preg_match('/Mobile|Android|iPhone|iPad|iPod/', $_SERVER['HTTP_USER_AGENT'] ?? '') ? 'MO' : 'PC',
        ':referer'      => $_SERVER['HTTP_REFERER'] ?? null,
        ':session_id'   => session_id(),
        ':ip_address'   => $_SERVER['REMOTE_ADDR'] ?? '',
        ':reg_datetime' => $now->format('Y-m-d H:i:s'),
        ':user_agent'   => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ':user_id'      => $_SESSION['user_id'] ?? null,
        ':http_status'  => http_response_code()
    ];

    $sql = <<<'SQL'
INSERT INTO user_activity_log
    (log_date, log_hour, log_minute, log_week,
     post_id, url, device_type, referer,
     session_id, ip_address, reg_datetime,
     user_agent, user_id, http_status)
VALUES
    (:log_date, :log_hour, :log_minute, :log_week,
     :post_id, :url, :device_type, :referer,
     :session_id, :ip_address, :reg_datetime,
     :user_agent, :user_id, :http_status)
SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
}
