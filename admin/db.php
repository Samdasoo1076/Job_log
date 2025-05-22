<?php
    // PHP 내부 타임존 설정 (php.ini 대신 코드 상에서 잡아줄 수도 있습니다)
    date_default_timezone_set('Asia/Seoul');

    $host = '158.179.168.106';
    $db   = 'cms_blog';
    $user = 'admin';
    $pass = '2134';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

    // MySQL 세션 타임존을 서울(UTC+9)로 설정
    $pdo->exec("SET time_zone = '+09:00'");


    // function getFolders($parentId = null) {
    //     global $pdo;
    //     if ($parentId === null) {
    //         $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id IS NULL ORDER BY name");
    //         $stmt->execute();
    //     } else {
    //         $stmt = $pdo->prepare("SELECT * FROM folder WHERE parent_id = :pid ORDER BY name");
    //         $stmt->execute([':pid' => $parentId]);
    //     }
    //     $folders = $stmt->fetchAll();
    //     foreach ($folders as &$f) {
    //         $f['children'] = getFolders($f['id']);
    //     }
    //     return $folders;
    // }

    function getPostsByFolderAdmin($folderId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * 
              FROM post 
             WHERE folder_id = :fid
               AND use_tf = 'Y'
               AND del_tf = 'N'
          ORDER BY sort_order ASC, created_at DESC
        ");
        $stmt->execute([':fid' => $folderId]);
        return $stmt->fetchAll();
    }


    function getComments($postId) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM comment WHERE post_id = :pid ORDER BY created_at");
        $stmt->execute([':pid' => $postId]);
        return $stmt->fetchAll();
    }

    function getAllPosts() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, updated_at FROM post");
        return $stmt->fetchAll();
    }
    

// db.php (기존에 있던 부분 위쪽에 삽입)
function getRootPosts() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT * 
          FROM post 
         WHERE folder_id IS NULL 
           AND use_tf = 'Y' 
           AND del_tf = 'N'
      ORDER BY sort_order ASC, created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}


    ?>