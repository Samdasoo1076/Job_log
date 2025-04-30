<?php
// ——————————————————————————————————————
// 1) 다운로드 처리
// ——————————————————————————————————————
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    // 1-1) URL 유효성 검사
    exec('yt-dlp --skip-download --print-json '.escapeshellarg($url).' 2>&1', $out, $rv);
    if ($rv !== 0) {
        http_response_code(400);
        exit('❌ 잘못된 YouTube URL 또는 yt-dlp 에러');
    }
    $meta = json_decode(implode("\n", $out), true);
    $title = preg_replace('/[^\w\- ]/', '', $meta['title']);
    $filename = $title . '.mp3';

    // 1-2) 쿠키에 URL / 파일명 저장 (중복 방지)
    $downloads = json_decode($_COOKIE['downloads'] ?? '[]', true);
    $exists = false;
    foreach ($downloads as $item) {
        if ($item['url'] === $url) { $exists = true; break; }
    }
    if (!$exists) {
        $downloads[] = ['url' => $url, 'name' => $filename];
        setcookie('downloads', json_encode($downloads), time()+60*60*24*365*10, '/');
    }

    // 1-3) 스트리밍 응답 헤더
    header('Content-Type: audio/mpeg');
    header('Content-Disposition: attachment; filename="'.rawurlencode($filename).'"');

    // 1-4) yt-dlp → ffmpeg 파이프라인으로 MP3 변환 (3200 kbps)
    //    - yt-dlp: 오디오 스트림만 stdout으로
    //    - ffmpeg: pipe:0에서 읽어 MP3로 변환, pipe:1으로 출력
    $cmd = sprintf(
      'yt-dlp -f bestaudio -o - %s 2>/dev/null | ffmpeg -hide_banner -loglevel error -i pipe:0 -b:a 3200k -f mp3 pipe:1',
      escapeshellarg($url)
    );
    passthru($cmd);
    exit;
}

// ——————————————————————————————————————
// 2) HTML 렌더링 (폼 + 다운로드 리스트)
// ——————————————————————————————————————
$downloads = json_decode($_COOKIE['downloads'] ?? '[]', true) ?: [];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>YT→MP3 다운로더</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>YT→MP3 다운로더</h1>

    <form method="get" class="dl-form">
      <input type="url" name="url" placeholder="유튜브 링크 입력" required>
      <button type="submit">다운로드</button>
    </form>

    <h2>다운받은 파일</h2>
    <ul class="dl-list">
      <?php if (empty($downloads)): ?>
        <li>아직 다운로드한 파일이 없습니다.</li>
      <?php else: ?>
        <?php foreach ($downloads as $item): ?>
          <li>
            <a href="?url=<?= urlencode($item['url']) ?>">
              <?= htmlspecialchars($item['name'], ENT_QUOTES) ?>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
</body>
</html>
