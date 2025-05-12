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
    // 총 개수 조회
    if ($folderId) {
        $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM post WHERE folder_id = :fid");
        $totalStmt->execute([':fid'=>$folderId]);
    } else {
        $totalStmt = $pdo->query("SELECT COUNT(*) FROM post");
    }
    $total = (int)$totalStmt->fetchColumn();

    // 페이징 데이터 조회
    $sql = "SELECT p.*, f.name AS folder_name
            FROM post p
            JOIN folder f ON p.folder_id = f.id";
    $params = [];
    if ($folderId) {
        $sql .= " WHERE p.folder_id = :fid";
        $params[':fid'] = $folderId;
    }
    $sql .= " ORDER BY p.created_at DESC
              LIMIT :lim OFFSET :off";
    $stmt = $pdo->prepare($sql);
    if ($folderId) {
        $stmt->bindValue(':fid', $folderId, PDO::PARAM_INT);
    }
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    return ['total'=>$total, 'rows'=>$rows];
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
 *
 * @param array $data ['title','content','description','folder_id']
 */
function createPost(array $data): void {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO post (title, content, description, folder_id)
                           VALUES (:t, :c, :d, :f)");
    $stmt->execute([
        ':t' => $data['title'],
        ':c' => $data['content'],
        ':d' => $data['description'],
        ':f' => $data['folder_id'],
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
                          folder_id   = :f
                        WHERE id     = :id");
    $stmt->execute([
        ':t'  => $data['title'],
        ':c'  => $data['content'],
        ':d'  => $data['description'],
        ':f'  => $data['folder_id'],
        ':id' => $id,
    ]);
}

/**
 * 게시글을 삭제합니다.
 *
 * @param int $id
 */
function deletePost(int $id): void {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM post WHERE id = ?");
    $stmt->execute([$id]);
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
