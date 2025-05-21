<?php
// public/api/search.php
header('Content-Type: application/json; charset=utf-8');
require  '../../db.php';

// 폴더 경로 계산 헬퍼
function getFolderPath($id) {
    global $pdo;
    $parts = [];
    while ($id) {
        $stmt = $pdo->prepare("SELECT id,name,parent_id FROM folder WHERE id = ? AND del_tf = 'N'");
        $stmt->execute([$id]);
        $f = $stmt->fetch();
        if (!$f) break;
        array_unshift($parts, $f['name']);
        $id = $f['parent_id'];
    }
    return implode('/', $parts);
}

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode([]);
    exit;
}
$q = "%$q%";

$stmt = $pdo->prepare("
    SELECT id, title, folder_id
      FROM post
     WHERE title LIKE :q AND del_tf = 'Y'
     ORDER BY created_at DESC
     LIMIT 20
");
$stmt->execute([':q'=>$q]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$out = [];
foreach ($rows as $r) {
    $out[] = [
        'id'   => $r['id'],
        'title'=> $r['title'],
        'path' => getFolderPath($r['folder_id'])
    ];
}

echo json_encode($out);
