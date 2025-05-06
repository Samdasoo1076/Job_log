<?
# =============================================================================
# File Name    : ajax_board_search.php
# Writer       : Lee Ji Min
# Create Date  : 2024-12-02
# Modify Date  : 

#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

header('Content-Type: application/json; charset=UTF-8');

error_reporting(E_ALL);

// 공통 인클루드
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/com/db/DBUtil.php";
$conn = db_connection("w");
require $_SERVER['DOCUMENT_ROOT'] . "/_common/config.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/com/util/Util.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/com/util/AES2.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/com/util/ImgUtil.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/com/etc/etc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/board/board.php";


$searchKeyword = isset($_GET['searchKeyword']) ? trim($_GET['searchKeyword']) : '';
$b_code = isset($_GET['b_code']) ? trim($_GET['b_code']) : ''; // 필요하다면 사용
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 현재 페이지 번호
$nRowCount = isset($_GET['nRowCount']) ? intval($_GET['nRowCount']) : 2; // 페이지당 결과 

// listBoardFrontDev 함수 호출
$totalCount = totalCntBoardFrontDev($conn, $b_code, $searchKeyword);
// 전체 페이지 수 계산
$totalPages = ceil($totalCount / $nRowCount);

// 카테고리별 카운트 계산
$noticeCount = totalCntBoardFrontDev($conn, 'B_1_1', $searchKeyword);
$pressCount = totalCntBoardFrontDev($conn, 'B_1_2', $searchKeyword);
$managementCount = totalCntBoardFrontDev($conn, 'B_1_3', $searchKeyword);
$results = listBoardFrontDev($conn, $b_code, $searchKeyword, $page, $nRowCount);

// 결과를 JSON 형태로 출력
echo json_encode([
    'status' => 'success',
    'data' => $results,
    'totalPages' => $totalPages,
    'totalCount' => $totalCount,
    'categoryCounts' => [
        'notice' => $noticeCount,
        'press' => $pressCount,
        'management' => $managementCount,
    ]
]);
?>