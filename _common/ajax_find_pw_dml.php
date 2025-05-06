<? session_start();
# =============================================================================
# File Name    : ajax_find_pw_dml.php
# Writer       : Lee Ji Min
# Create Date  : 2024-12-02
# Modify Date  : 

#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# ============================================================================= ?>
<?

header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 공통 인클루드
require "../_classes/com/db/DBUtil.php";
$conn = db_connection("w");
require "../_common/config.php";
require "../_classes/com/util/Util.php";
require "../_classes/com/util/AES2.php";
require "../_classes/com/util/ImgUtil.php";
require "../_classes/com/etc/etc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

$m_id = isset($_POST['m_id']) ? trim($_POST['m_id']) : '';
$m_phone = isset($_POST['m_phone']) ? str_replace('-', '', trim($_POST['m_phone'])) : '';
$auth_code = isset($_POST['auth_code']) ? trim($_POST['auth_code']) : '';
$formattedPhone = isset($_POST['formattedPhone']) ? trim($_POST['formattedPhone']) : '';

$server_auth_code = "0000";

// if (!$m_id || !$m_phone || !$auth_code) {
//     echo json_encode(['success' => false, 'message' => '모든 필드를 입력해주세요.']);
//     exit;
// }

if (!$m_id || !$m_phone) {
    echo json_encode(['success' => false, 'message' => '모든 필드를 입력해주세요.']);
    exit;
}

// if ($auth_code !== $server_auth_code) {
//     echo json_encode(['success' => false, 'message' => '인증번호가 올바르지 않습니다.']);
//     exit;
// }
$test = encrypt($key, $iv, $formattedPhone);

$user = findUserpw($conn, $m_id, encrypt($key, $iv, $formattedPhone));

if ($user) {
    echo json_encode(['success' => true, 'message' => '사용자 인증 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '일치하는 사용자 정보를 찾을 수 없습니다.' . $formattedPhone . $test . $m_phone]);
}
