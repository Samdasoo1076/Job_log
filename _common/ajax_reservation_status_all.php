<? session_start();
# =============================================================================
# File Name    : ajax_find_id_dml.php
# Writer       : Lee Ji Min
# Create Date  : 2024-11-29
# Modify Date  :

#   Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================
?>

<?
header("Content-Type: text/html; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

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
require "../_classes/biz/reservation/reservation.php";

$reservation_counts = ReservationStatusAll($conn); // 함수 호출

echo json_encode($reservation_counts);
?>