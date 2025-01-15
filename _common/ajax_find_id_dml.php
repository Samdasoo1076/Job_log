<? sesion_start();
# =============================================================================
# File Name    : ajax_find_id_dml.php
# Writer       : Lee Ji Min
# Create Date  : 2024-11-29
# Modify Date  : 

#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================
?>

<?
header("Content-Type: text/html; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE);

#====================================================================
# DB Include, DB Connection
#====================================================================
require "../_classes/com/db/DBUtil.php";
$conn = db_connection("w");
require "../_common/config.php";
require "../_classes/com/util/Util.php";
require "../_classes/com/util/AES2.php";
require "../_classes/com/util/ImgUtil.php";
require "../_classes/com/etc/etc.php";
require "../_classes/biz/member/member.php";

// 요청 데이터 받기
$phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
$formattedPhone = isset($_POST['formattedPhone']) ? trim($_POST['formattedPhone']) : '';
if (empty($formattedPhone)) {
	echo json_encode(['success' => false, 'message' => '휴대폰 번호를 입력해주세요.']);
	exit;
}

$test = encrypt($key, $iv, $formattedPhone);

// 사용자 조회
$user = findUserid($conn, encrypt($key, $iv, $formattedPhone));

if ($user) {
	echo json_encode(['success' => true, 'userId' => $user['M_ID']]);
} else {
	echo json_encode(['success' => false, 'message' => '등록된 번호로 조회된 아이디가 없습니다.' . $test]);
}

?>