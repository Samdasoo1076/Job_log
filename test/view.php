<?session_start();?>
<?
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";


// URL 파라미터 가져오기
$b_code = $_GET['b_code'] ?? '';
$b_no = $_GET['b_no'] ?? '';

// 게시물 조회
$post = selectBoardDetail($conn, $b_code, $b_no);

// 게시물이 없을 경우 처리
if (!$post) {
    echo '게시물이 존재하지 않습니다.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['TITLE']); ?> - 상세 페이지</title>
</head>
<body>
    <h1><?= htmlspecialchars($post['TITLE']); ?></h1>
    <p>작성일: <?= htmlspecialchars($post['REG_DATE']); ?></p>
    <div>
        <?= nl2br(htmlspecialchars($post['CONTENT'])); ?>
    </div>
    <a href="/communication/">목록으로 돌아가기</a>
</body>
</html>
