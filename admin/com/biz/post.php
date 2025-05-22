<?php
// admin/com/biz/post.php
// CRUD 비즈니스 로직만 모아둡니다.

// require_once __DIR__ . '/../../db.php';




/**
 * 게시글 리스트를 반환합니다.
 *
 * @param int|null $folderId  폴더 필터 ID (null 이면 전체)
 * @param int      $limit     페이지당 개수
 * @param int      $offset    오프셋
 * @return array ['total'=>int, 'rows'=>array]
 */
function getPostList(?int $folderId, int $limit, int $offset): array {
    global $pdo;
    // 총 개수 조회 (del_tf = 'N' 조건 추가)
    if ($folderId) {
        $totalStmt = $pdo->prepare("
            SELECT COUNT(*) 
              FROM post 
             WHERE folder_id = :fid 
               AND del_tf = 'N'
        ");
        $totalStmt->execute([':fid' => $folderId]);
    } else {
        $totalStmt = $pdo->query("
            SELECT COUNT(*) 
              FROM post 
             WHERE del_tf = 'N'
        ");
    }
    $total = (int)$totalStmt->fetchColumn();

    // 페이징 데이터 조회 (LEFT JOIN으로 폴더 이름 가져오기, del_tf 필터링)
    $sql = "
        SELECT p.*, f.name AS folder_name
          FROM post p
     LEFT JOIN folder f ON p.folder_id = f.id
         WHERE p.del_tf = 'N'
    ";
    $params = [];
    if ($folderId) {
        $sql .= " AND p.folder_id = :fid";
        $params[':fid'] = $folderId;
    }
    $sql .= "
         ORDER BY p.created_at DESC
         LIMIT :lim 
        OFFSET :off
    ";
    $stmt = $pdo->prepare($sql);
    // 바인딩
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_INT);
    }
    $stmt->bindValue(':lim', $limit,  PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();

    return [
        'total' => $total,
        'rows'  => $rows,
    ];
}

/**
 * 단일 게시글을 반환합니다.
 *
 * @param int $id
 * @return array|false
 */
function getPost(int $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM post WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * 새 게시글을 저장합니다.
 * sort_order 는 해당 폴더의 max+1 로 설정
 */
function createPost(array $data): void {
    global $pdo;
    // 현재 폴더의 max sort_order 조회
    $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order),-1) FROM post WHERE folder_id = :fid");
    $stmt->execute([':fid' => $data['folder_id']]);
    $max = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("
        INSERT INTO post
          (title, content, description, folder_id, sort_order)
        VALUES
          (:t, :c, :d, :f, :ord)
    ");
    $stmt->execute([
        ':t'   => $data['title'],
        ':c'   => $data['content'],
        ':d'   => $data['description'],
        ':f'   => $data['folder_id'],
        ':ord' => $max + 1,
    ]);
}

/**
 * 기존 게시글을 업데이트합니다.
 *
 * @param int   $id
 * @param array $data ['title','content','description','folder_id']
 */
function updatePost(int $id, array $data): void {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE post SET
                          title       = :t,
                          content     = :c,
                          description = :d,
                          folder_id   = :f,
                          allow_comment = :ac
                        WHERE id     = :id");
    $stmt->execute([
        ':t'  => $data['title'],
        ':c'  => $data['content'],
        ':d'  => $data['description'],
        ':f'  => $data['folder_id'],
        ':id' => $id,
        ':ac'  => $data['allow_comment'] ?? 'Y',
    ]);
}

/**
 * 게시글을 삭제합니다.
 *
 * @param int $id
 */
function deletePost(int $id): void {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE post
           SET del_tf = 'Y'
         WHERE id     = :id
    ");
    $stmt->execute([':id' => $id]);
}

/**
 * 게시글 저장(create 또는 update)
 *
 * @param array $data ['id','title','content','description','folder_id']
 */
function savePost(array $data): void {
    if (!empty($data['id'])) {
        updatePost((int)$data['id'], $data);
    } else {
        createPost($data);
    }
}

/**
 * 메인에 노출할 대표 포스트 한 건을 반환합니다.
 * is_featured=1, use_tf='Y', del_tf='N' 인 것 중 sort_order 순으로
 *
 * @return array|false
 */
function getFeaturedPost() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT id, title, description, content, created_at, updated_at
          FROM post
         WHERE is_featured = 1
           AND use_tf      = 'Y'
           AND del_tf      = 'N'
         ORDER BY updated_at DESC
         LIMIT 1
    ");
    $stmt->execute();
    return $stmt->fetch();
}
