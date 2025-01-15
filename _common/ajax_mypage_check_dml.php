<? session_start();
# =============================================================================
# File Name    : ajax_mypage_check_dml.php
# Writer       : Lee Ji Min
# Create Date  : 2024-12-02
# Modify Date  : 

#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================
?>


<?
header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 공통 인클루드
require "../_classes/com/db/DBUtil.php";
$conn = db_connection(usr_type: "w");
require "../_common/config.php";
require "../_classes/com/util/Util.php";
require "../_classes/com/util/AES2.php";
require "../_classes/com/util/ImgUtil.php";
require "../_classes/com/etc/etc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

// 세션에서 사용자 번호 가져오기
$m_no = $_SESSION['m_no'] ?? null;
$password = $_POST['password'] ?? '';

if (!$m_no || !$password) {
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

// DB에서 사용자 정보 가져오기
$user_data = mypageUserMember($conn, $m_no);

if (!$user_data) {
    echo json_encode(['success' => false, 'message' => '사용자 정보를 찾을 수 없습니다.']);
    exit;
}

// 암호화된 비밀번호 비교
$encrypted_password = encrypt($key, $iv, $password); // 사용자의 입력 비밀번호를 암호화

if ($encrypted_password === $user_data['M_PWD']) {
    echo json_encode(['success' => true]); // 비밀번호 일치
} else {
    echo json_encode(['success' => false, 'message' => '비밀번호가 일치하지 않습니다.']); // 비밀번호 불일치
}
?>