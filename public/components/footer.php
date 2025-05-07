<?php
// public/components/job_log.php

/**
 * GitHub job_log 리포지터리의 최신 커밋 메시지와 링크 렌더링
 * @param string $owner GitHub 사용자명
 */
function renderJobLog(string $owner) {
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

     $isoDate = $data[0]['commit']['author']['date'];
 
     // DateTime 객체로 변환 (로컬 타임존으로 포맷)
     $dt = new DateTime($isoDate);
     $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
     $when = $dt->format('Y-m-d H:i');

    $commit = htmlspecialchars($data[0]['commit']['message'], ENT_QUOTES, 'UTF-8');
    $link   = htmlspecialchars($data[0]['html_url'], ENT_QUOTES, 'UTF-8');
    echo "<div class=\"commit\">
            <span class=\"message\">
            <strong>Latest Commit </strong>
            <a href=\"{$link}\" target=\"_blank\">{$commit}</a>
            </span>
            <span class=\"time\">Samdasoo1076 &nbsp {$when}</span>
          </div>";
}
