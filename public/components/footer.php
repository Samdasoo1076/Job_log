<?php
// public/components/job_log.php

/**
 * GitHub job_log 리포지터리의 최신 커밋 메시지와 링크 렌더링
 * @param string $owner GitHub 사용자명
 */
function renderJobLog(string $owner) {
    $cachePath = sys_get_temp_dir() . '/blog-github-' . hash('sha256', $owner) . '.json';
    $cache = @fopen($cachePath, 'c+');
    $data = [];
    if ($cache && flock($cache, LOCK_EX)) {
        $saved = json_decode(stream_get_contents($cache), true) ?: [];
        $data = $saved['data'] ?? [];
        if (time() - (int)($saved['checked_at'] ?? 0) >= 600) {
            $context = stream_context_create(['http' => [
                'method' => 'GET',
                'header' => ['User-Agent: ' . $owner . '-blog', 'Accept: application/vnd.github+json'],
                'timeout' => 3,
            ]]);
            $raw = @file_get_contents('https://api.github.com/repos/' . rawurlencode($owner) . '/job_log/commits?per_page=1', false, $context);
            $fresh = $raw !== false ? json_decode($raw, true) : null;
            if (isset($fresh[0]['commit']['message'], $fresh[0]['commit']['author']['date'], $fresh[0]['html_url'])) {
                $data = $fresh;
            }
            rewind($cache);
            ftruncate($cache, 0);
            fwrite($cache, json_encode(['checked_at' => time(), 'data' => $data]));
            fflush($cache);
        }
        flock($cache, LOCK_UN);
    }
    if (is_resource($cache)) fclose($cache);
    if (empty($data[0])) {
        $repo = htmlspecialchars('https://github.com/' . rawurlencode($owner) . '/job_log/commits', ENT_QUOTES, 'UTF-8');
        echo '<div class="job-log"><a href="' . $repo . '" target="_blank" rel="noopener noreferrer">GitHub 커밋 기록</a></div>';
        return;
    }
/*
    $apiUrl = "https://api.github.com/repos/{$owner}/job_log/commits?per_page=1";
    $opts = [
        'http' => [
            'method'  => 'GET',
            'header'  => [
                "User-Agent: {$owner}-blog"
            ]
        ]
    ];
    $context = stream_context_create($opts);
    $json = @file_get_contents($apiUrl, false, $context);
    if ($json === false) {
        echo '<div class="job-log">GitHub 정보를 불러올 수 없습니다.</div>';
        return;
    }
    $data = json_decode($json, true);
    if (empty($data[0])) {
        echo '<div class="job-log">최근 커밋 정보가 없습니다.</div>';
        return;
    }

*/
     $isoDate = $data[0]['commit']['author']['date'];
 
     // DateTime 객체로 변환 (로컬 타임존으로 포맷)
     $dt = new DateTime($isoDate);
     $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
     $when = $dt->format('Y-m-d H:i');

    $message = $data[0]['commit']['message'];
    $fullCommit = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $commit = htmlspecialchars(mb_strlen($message, 'UTF-8') > 20 ? mb_substr($message, 0, 20, 'UTF-8') . '...' : $message, ENT_QUOTES, 'UTF-8');
    $link   = htmlspecialchars($data[0]['html_url'], ENT_QUOTES, 'UTF-8');
    echo "<div class=\"commit\">
            <span class=\"message\">
            <strong>Latest Commit </strong>
            <a href=\"{$link}\" target=\"_blank\" rel=\"noopener noreferrer\" title=\"{$fullCommit}\">{$commit}</a>
            </span>
            <span class=\"time\" ><a href=\"admin/\">Samdasoo1076</a> &nbsp {$when}</span>
          </div>";
}
