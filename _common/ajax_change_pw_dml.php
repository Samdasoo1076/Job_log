<?
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
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

$m_id = isset($_POST['m_id']) ? trim($_POST['m_id']) : '';
$m_pwd = isset($_POST['m_pwd']) ? trim($_POST['m_pwd']) : '';
$formattedPhone = isset($_POST['formattedPhone']) ? trim($_POST['formattedPhone']) : '';

if (!$m_id || !$formattedPhone || !$m_pwd) {
    echo json_encode(['success' => false, 'message' => '비밀번호 변경에 필요한 정보가 부족합니다.']);
    exit;
}
$encrypted_password = encrypt($key, $iv, $m_pwd);
$encrypted_formattedPhone = encrypt($key, $iv, $formattedPhone);
error_log("Encrypted Password: " . $encrypted_password);

$result = changeUserPassword($conn, $m_id, $encrypted_formattedPhone, $encrypted_password);

if ($result) {
    echo json_encode(['success' => true, 'message' => '비밀번호가 성공적으로 변경되었습니다.']);
} else {
    echo json_encode(['success' => false, 'message' => '비밀번호 변경에 실패했습니다.']);
}
?>