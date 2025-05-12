<?php
// admin/com/biz/folders.php

function getFolders($parentId = null) {
    global $pdo;
    if ($parentId === null) {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id IS NULL ORDER BY name");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id = :pid ORDER BY name");
        $stmt->execute([':pid' => $parentId]);
    }
    $folders = $stmt->fetchAll();
    foreach ($folders as &$f) {
        $f['children'] = getFolders($f['id']);
    }
    return $folders;
}