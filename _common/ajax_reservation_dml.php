<? session_start(); ?>
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
require "../_classes/biz/reservation/reservation.php";
require "../_classes/biz/member/member.php";


$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$rv_no = isset($_POST["rv_no"]) && $_POST["rv_no"] !== '' ? $_POST["rv_no"] : (isset($_GET["rv_no"]) ? $_GET["rv_no"] : '');
$search_period = isset($_POST["search_period"]) && $_POST["search_period"] !== '' ? $_POST["search_period"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');

$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

# 페이징 관련
$nPage = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_period = isset($_POST["search_period"]) && $_POST["search_period"] !== '' ? $_POST["search_period"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_field = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');
$del_tf = 'N';

if ($nPage <> "" && $nPageSize <> 0) {
	$nPage = (int) ($nPage);
} else {
	$nPage = 1;
}

if ($nPage <> "" && $nPageSize <> 0) {
	$nPageSize = (int) ($nPageSize);
} else {
	$nPageSize = 20;
}

if ($nPageSize == 0) {
	$nPageSize = 20;
}

$nPageBlock = 10;

$nListCnt = totalCntReservation($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period);

$nTotalPage = (int) (($nListCnt - 1) / $nPageSize + 1);

if ((int) ($nTotalPage) < (int) ($nPage)) {
	$nPage = $nTotalPage;
}

if ($mode == "REVSERVATION") {

	$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
	$room_no = isset($_POST["room_no"]) && $_POST["room_no"] !== '' ? $_POST["room_no"] : (isset($_GET["room_no"]) ? $_GET["room_no"] : '');
	$rv_start_time = isset($_POST["rv_start_time"]) && $_POST["rv_start_time"] !== '' ? $_POST["rv_start_time"] : (isset($_GET["rv_start_time"]) ? $_GET["rv_start_time"] : '');
	$rv_end_time = isset($_POST["rv_end_time"]) && $_POST["rv_end_time"] !== '' ? $_POST["rv_end_time"] : (isset($_GET["rv_end_time"]) ? $_GET["rv_end_time"] : '');
	$rv_purpose = isset($_POST["rv_purpose"]) && $_POST["rv_purpose"] !== '' ? $_POST["rv_purpose"] : (isset($_GET["rv_purpose"]) ? $_GET["rv_purpose"] : '');
	$rv_use_count = isset($_POST["rv_use_count"]) && $_POST["rv_use_count"] !== '' ? $_POST["rv_use_count"] : (isset($_GET["rv_use_count"]) ? $_GET["rv_use_count"] : '');
	$rv_equipment = isset($_POST["rv_equipment"]) && $_POST["rv_equipment"] !== '' ? $_POST["rv_equipment"] : (isset($_GET["rv_equipment"]) ? $_GET["rv_equipment"] : '');
	$rv_reduction_tf = isset($_POST["rv_reduction_tf"]) && $_POST["rv_reduction_tf"] !== '' ? $_POST["rv_reduction_tf"] : (isset($_GET["rv_reduction_tf"]) ? $_GET["rv_reduction_tf"] : '');
	$rv_reduction = isset($_POST["rv_reduction"]) && $_POST["rv_reduction"] !== '' ? $_POST["rv_reduction"] : (isset($_GET["rv_reduction"]) ? $_GET["rv_reduction"] : '');
	$rv_memo = isset($_POST["rv_memo"]) && $_POST["rv_memo"] !== '' ? $_POST["rv_memo"] : (isset($_GET["rv_memo"]) ? $_GET["rv_memo"] : '');
	$rv_date = isset($_POST["rv_date"]) && $_POST["rv_date"] !== '' ? $_POST["rv_date"] : (isset($_GET["rv_date"]) ? $_GET["rv_date"] : '');
	$totalPrice = isset($_POST["totalPrice"]) && $_POST["totalPrice"] !== '' ? $_POST["totalPrice"] : (isset($_GET["totalPrice"]) ? $_GET["totalPrice"] : '');

	$cnt = chkDupReservation ($conn, $room_no, $rv_date, $rv_start_time);
	$member_info = selectMember($conn, $m_no);
	$m_phone = $member_info[0]['M_PHONE'];
	$m_phone_dec = decrypt($key, $iv, $m_phone);
	$m_phone_dec = str_replace('-', '', $m_phone_dec);

	if ($cnt == 0) {

		$arr_data = array(
			"ROOM_NO" => $room_no,
			"RV_START_TIME" => $rv_start_time,
			"RV_END_TIME" => $rv_end_time,
			"RV_PURPOSE" => $rv_purpose,
			"RV_USE_COUNT" => $rv_use_count,
			"RV_EQUIPMENT" => $rv_equipment,
			"RV_REDUCTION_TF" => $rv_reduction_tf,
			"RV_REDUCTION" => $rv_reduction,
			"RV_MEMO" => $rv_memo,
			"RV_DATE" => $rv_date,
			"M_NO" => $m_no,
			"RV_COST" => $totalPrice,
		);

		$reservation_no = insertReservation($conn, $arr_data);

		$sms_flag = '3';

		// 예약할시 문자 발송 추가 멘트는 콘텐츠 수급이되면 수정
		$str_result = biz_send_sms($conn, $m_phone_dec, $subject, "[원주미래산업진흥원] 예약이 완료되었습니다. 고객님의 예약번호는 ".$reservation_no." 입니다.", $sms_flag);

		echo json_encode([
			"success" => true,
			"reservation_no" => $reservation_no,
			"str_result" => $str_result,
			"message" => ""
		]);

	} else {
		echo json_encode([
			"success" => false,
			"reservation_no" => "",
			"message" => "예약된 정보가 있습니다."
		]);

	}

	db_close($conn);
	exit;

}

if ($mode == "GET_ABLED_TIME") {

	$chk_date = isset($_POST["chk_date"]) ? $_POST["chk_date"] : "";
	$room_no = isset($_POST["room_no"]) ? $_POST["room_no"] : "";

	if ($chk_date !== "" && $room_no !== "") {

		$arr_times = getAbleDateTime($conn, $chk_date, $room_no);

		echo json_encode([
			"success" => true,
			"data" => $arr_times,
			"message" => ""
		]);

	} else {
		echo json_encode([
			"success" => false,
			"message" => "오류 발생"
		]);
	}

	db_close($conn);
	exit;
}

if ($mode == "GET_RESERVATION_DETAIL") {
	$m_no = isset($_POST["m_no"]) ? $_POST["m_no"] : "";
	$rv_no = isset($_POST["rv_no"]) ? $_POST["rv_no"] : "";


	if ($m_no !== "" && $rv_no !== "") {
		$is_reservation_detail = getReservationsByMember($conn, $m_no, $rv_no);

		$m_phone = isset($is_reservation_detail[0]['M_PHONE']) ? $is_reservation_detail[0]['M_PHONE'] : null;
		$m_phone_dec = decrypt($key, $iv, $m_phone);

		$rv_equipment = isset($is_reservation_detail[0]['RV_EQUIPMENT']) ? $is_reservation_detail[0]['RV_EQUIPMENT'] : null;
		$equipment = getDcodeName($conn, "EQUIPMENT", $rv_equipment);

		$rv_reduction = isset($is_reservation_detail[0]['RV_REDUCTION']) ? $is_reservation_detail[0]['RV_REDUCTION'] : null;
		$reduction  = getDcodeName($conn, "GAM", $rv_reduction);

		echo json_encode([
			"success" => true,
			"data" => $is_reservation_detail,
			"m_phone_dec" => $m_phone_dec,
			"rv_equipment" =>$equipment,
			"rv_reduction" => $reduction
		]);
	} else {
		echo json_encode([
			"success" => false,
			"message" => "오류 발생"
		]);
	}
	db_close($conn);
	exit;
}


if ($mode == "SEARCH_PERIOD") {
	$search_period = isset($_POST["search_period"]) ? $_POST["search_period"] : "";
	$m_no = isset($_POST["m_no"]) ? $_POST["m_no"] : "";


	if ($search_period !== "" && $search_period !== null) {
		$arr_rv_search_list = listReservations2($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period, $nPage, $nPageSize, $nListCnt);
		$rs_nListCnt = totalCntReservation($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period);
		
		echo json_encode([
			"success" => true,
			"data" => $arr_rv_search_list,
			"totalCnt" => $rs_nListCnt,
		]);
	} else {
		echo json_encode([
			"success" => false,
			"message" => "오류 발생"
		]);
	}

	db_close($conn);
	exit;
}

if ($mode == "CANCLE") {
	$rv_no = isset($_POST["rv_no"]) ? $_POST["rv_no"] : "";
	$m_no = isset($_POST["m_no"]) ? $_POST["m_no"] : "";
	error_log('gd' . $m_no);
	if ($rv_no !== "") {
		$result = updateReservationCancle($conn, $rv_no, $m_no);
		echo json_encode([
			"success" => "T",
			"message" => "회원님의 예약이 정상적으로 취소되었습니다.",
		]);
	} else {
		echo json_encode([
			"success" => "F",
			"message" => "오류 발생",
		]);
	}

	db_close($conn);
	exit;
}

if ($mode == "Reservation_Control") {
	$rv_no = isset($_POST["rv_no"]) ? $_POST["rv_no"] : "";
	$rv_agree_tf = isset($_POST["rv_agree_tf"]) ? $_POST["rv_agree_tf"] : "";

	if (!empty($rv_no) && isset($rv_agree_tf)) {
		$result = updateReservationAgree($conn, $rv_no, $rv_agree_tf);
		if ($result) {
			echo json_encode([
				"success" => "T",
				"message" => "예약 상태가 성공적으로 업데이트 되었습니다."
			]);
		} else {
			echo json_encode([
				"success" => "F",
				"message" => "데이터베이스 업데이트 중 오류가 발생했습니다."
			]);
		}
	} else {
		echo json_encode([
			"success" => "F",
			"message" => "필수 데이터가 누락되었습니다."
		]);
	}

	db_close($conn);
	exit;
}

if ($mode == "MODIFY_MEMO") {

	$r_no = isset($_POST["r_no"]) ? $_POST["r_no"] : "";
	$rv_memo = isset($_POST["rv_memo"]) ? $_POST["rv_memo"] : "";

	if ($r_no !== '' && $rv_memo !== '') {
		$result = updateReservationMemo($conn, $r_no, $rv_memo);
		
		if ($result) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => '데이터베이스 업데이트 실패']);
		}
	} else {
		echo json_encode(['success' => false, 'message' => '필요한 데이터가 누락되었습니다.']);
	}
	db_close($conn);
	exit;
}





?>