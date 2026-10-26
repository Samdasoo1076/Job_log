<?php
// admin/dashboard.php
require_once __DIR__ . '/common/common_inc.php';
// header.php 안에서 front_head.php 까지 포함하므로 이 한 줄만 있으면 됩니다.
require_once __DIR__ . '/layout/header.php';

function formatBytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $size = $bytes;
    $index = 0;
    while ($size >= 1024 && $index < count($units) - 1) {
        $size /= 1024;
        $index++;
    }
    return number_format($size, $index === 0 ? 0 : 1) . $units[$index];
}

function readMemInfo(): array
{
    $info = [];
    foreach (@file('/proc/meminfo') ?: [] as $line) {
        if (preg_match('/^(\w+):\s+(\d+)/', $line, $matches)) {
            $info[$matches[1]] = (int)$matches[2] * 1024;
        }
    }

    $total = $info['MemTotal'] ?? 0;
    $available = $info['MemAvailable'] ?? 0;
    $used = max(0, $total - $available);

    return [
        'total' => $total,
        'used' => $used,
        'available' => $available,
        'percent' => $total > 0 ? round(($used / $total) * 100, 1) : 0,
    ];
}

function readCpuInfo(): array
{
    $load = sys_getloadavg() ?: [0, 0, 0];
    $cores = (int)trim((string)@shell_exec('nproc 2>/dev/null'));
    if ($cores <= 0) {
        $cores = max(1, substr_count((string)@file_get_contents('/proc/cpuinfo'), 'processor'));
    }

    return [
        'load1' => round((float)$load[0], 2),
        'load5' => round((float)$load[1], 2),
        'load15' => round((float)$load[2], 2),
        'cores' => $cores,
        'percent' => $cores > 0 ? min(100, round(((float)$load[0] / $cores) * 100, 1)) : 0,
    ];
}

function readDiskInfo(string $path = '/'): array
{
    $total = (int)@disk_total_space($path);
    $free = (int)@disk_free_space($path);
    $used = max(0, $total - $free);

    return [
        'total' => $total,
        'used' => $used,
        'free' => $free,
        'percent' => $total > 0 ? round(($used / $total) * 100, 1) : 0,
    ];
}

function readTopProcesses(): array
{
    $output = [];
    @exec('ps -eo pid,comm,%cpu,%mem --sort=-%cpu 2>/dev/null | head -8', $output);
    array_shift($output);

    $rows = [];
    foreach ($output as $line) {
        $parts = preg_split('/\s+/', trim($line));
        if (count($parts) < 4) {
            continue;
        }
        $rows[] = [
            'pid' => $parts[0],
            'command' => $parts[1],
            'cpu' => $parts[2],
            'mem' => $parts[3],
        ];
    }
    return $rows;
}

// DB에서 카운트
$totalFolders  = $pdo->query('SELECT COUNT(*) FROM folder')->fetchColumn();
$totalPosts    = $pdo->query('SELECT COUNT(*) FROM post')->fetchColumn();
$totalComments = $pdo->query('SELECT COUNT(*) FROM comment')->fetchColumn();
$memory = readMemInfo();
$cpu = readCpuInfo();
$disk = readDiskInfo('/');
$topProcesses = readTopProcesses();
?>
<h1>관리자 대시보드</h1>
<div class="dashboard-cards">
  <div class="card">
    <h3>카테고리 수</h3>
    <p><?= $totalFolders ?></p>
  </div>
  <div class="card">
    <h3>게시글 수</h3>
    <p><?= $totalPosts ?></p>
  </div>
  <div class="card">
    <h3>댓글 수</h3>
    <p><?= $totalComments ?></p>
  </div>
</div>

<h2 class="dashboard-section-title">서버 사용량</h2>
<div class="server-monitor">
  <div class="monitor-row">
    <div class="monitor-label">CPU Load</div>
    <div class="monitor-main">
      <div class="usage-bar"><span style="width: <?= $cpu['percent'] ?>%"></span></div>
      <small>1분 <?= $cpu['load1'] ?> · 5분 <?= $cpu['load5'] ?> · 15분 <?= $cpu['load15'] ?></small>
    </div>
    <div class="monitor-value"><?= $cpu['load1'] ?> / <?= $cpu['cores'] ?> cores</div>
  </div>
  <div class="monitor-row">
    <div class="monitor-label">Memory</div>
    <div class="monitor-main">
      <div class="usage-bar"><span style="width: <?= $memory['percent'] ?>%"></span></div>
      <small><?= $memory['percent'] ?>% 사용 · 가용 <?= formatBytes($memory['available']) ?></small>
    </div>
    <div class="monitor-value"><?= formatBytes($memory['used']) ?> / <?= formatBytes($memory['total']) ?></div>
  </div>
  <div class="monitor-row">
    <div class="monitor-label">Disk /</div>
    <div class="monitor-main">
      <div class="usage-bar"><span style="width: <?= $disk['percent'] ?>%"></span></div>
      <small><?= $disk['percent'] ?>% 사용 · 여유 <?= formatBytes($disk['free']) ?></small>
    </div>
    <div class="monitor-value"><?= formatBytes($disk['used']) ?> / <?= formatBytes($disk['total']) ?></div>
  </div>
</div>

<div class="server-panel">
  <h2>CPU 사용 상위 프로세스</h2>
  <table class="server-process-table">
    <thead>
      <tr>
        <th>PID</th>
        <th>COMMAND</th>
        <th>CPU</th>
        <th>MEM</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!$topProcesses): ?>
      <tr><td colspan="4">프로세스 정보를 가져올 수 없습니다.</td></tr>
    <?php endif; ?>
    <?php foreach ($topProcesses as $process): ?>
      <tr>
        <td><?= htmlspecialchars($process['pid']) ?></td>
        <td><?= htmlspecialchars($process['command']) ?></td>
        <td><?= htmlspecialchars($process['cpu']) ?>%</td>
        <td><?= htmlspecialchars($process['mem']) ?>%</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php
require_once __DIR__ . '/layout/footer.php';
