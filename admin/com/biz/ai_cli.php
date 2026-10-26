<?php

function ensureAiCliTables(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS ai_cli_job (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        admin_id VARCHAR(64) NOT NULL DEFAULT '',
        post_id INT(11) DEFAULT NULL,
        draft_key VARCHAR(64) NOT NULL,
        job_mode VARCHAR(30) NOT NULL DEFAULT 'daily',
        notes TEXT NOT NULL,
        style VARCHAR(1000) NOT NULL DEFAULT '',
        source_title VARCHAR(255) NOT NULL DEFAULT '',
        source_description VARCHAR(500) NOT NULL DEFAULT '',
        source_content MEDIUMTEXT,
        status ENUM('queued','processing','completed','failed') NOT NULL DEFAULT 'queued',
        result_title VARCHAR(255) NOT NULL DEFAULT '',
        result_description VARCHAR(500) NOT NULL DEFAULT '',
        result_content MEDIUMTEXT,
        error_message TEXT,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        started_at DATETIME DEFAULT NULL,
        finished_at DATETIME DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY uq_ai_cli_draft_key (draft_key),
        KEY idx_ai_cli_status_created (status, created_at),
        KEY idx_ai_cli_admin_created (admin_id, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS post_draft (
        id INT(11) NOT NULL AUTO_INCREMENT,
        draft_key VARCHAR(64) NOT NULL,
        post_id INT(11) DEFAULT NULL,
        admin_id VARCHAR(64) DEFAULT NULL,
        folder_id INT(11) DEFAULT NULL,
        title VARCHAR(255) DEFAULT '',
        description VARCHAR(255) DEFAULT '',
        content LONGTEXT DEFAULT NULL,
        is_featured TINYINT(1) NOT NULL DEFAULT 0,
        allow_comment CHAR(1) NOT NULL DEFAULT 'Y',
        use_tf CHAR(1) NOT NULL DEFAULT 'Y',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_post_draft_key (draft_key),
        KEY idx_post_draft_post_id (post_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

function aiCliSanitizeHtml(string $html): string
{
    $html = preg_replace('/```(?:html)?|```/i', '', $html);
    $html = preg_replace('/<(script|style|iframe|object|embed|form|input|button|textarea|select|meta|link)[\s\S]*?<\/\1>/i', '', $html);
    $html = preg_replace('/<(script|style|iframe|object|embed|form|input|button|textarea|select|meta|link)\b[^>]*>/i', '', $html);
    $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
    $html = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
    $html = preg_replace('/href\s*=\s*([\'"])\s*javascript:[\s\S]*?\1/i', 'href="#"', $html);

    return trim(strip_tags($html, '<h2><h3><h4><p><br><ul><ol><li><strong><b><em><i><span><a><div><code><pre><blockquote><hr><table><thead><tbody><tr><th><td>'));
}
