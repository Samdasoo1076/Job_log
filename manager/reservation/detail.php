<? session_start(); ?>
<!-- php start -->
<?

header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");

# =============================================================================
# File Name    : member_write.php
# Modlue       : 
# Writer       : Seo Hyun Seok 
# Create Date  : 2024-12-04
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================


#====================================================================
# DB Include, DB Connection
#====================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
$menu_right = "RE002"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
require "../../_common/common_header.php";

#=====================================================================
# common function, login_function
#=====================================================================

require "../../_common/config.php";
require "../../_classes/com/util/Util.php";
require "../../_classes/com/util/ImgUtil.php";
require "../../_classes/com/util/ImgUtilResize.php";
require "../../_classes/com/util/AES2.php";
require "../../_classes/com/etc/etc.php";
require "../../_classes/biz/member/member.php";
require "../../_classes/biz/online/online.php";
require "../../_classes/biz/admin/admin.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/reservation/reservation.php";



#====================================================================
# DML Process
#====================================================================

$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$m_id = isset($_POST["m_id"]) && $_POST["m_id"] !== '' ? $_POST["m_id"] : (isset($_GET["m_id"]) ? $_GET["m_id"] : '');

$m_pwd = isset($_POST["m_pwd"]) && $_POST["m_pwd"] !== '' ? $_POST["m_pwd"] : (isset($_GET["m_pwd"]) ? $_GET["m_pwd"] : '');
$m_phone = isset($_POST["m_phone"]) && $_POST["m_phone"] !== '' ? $_POST["m_phone"] : (isset($_GET["m_phone"]) ? $_GET["m_phone"] : '');

$m_email = isset($_POST["m_email"]) && $_POST["m_email"] !== '' ? $_POST["m_email"] : (isset($_GET["m_email"]) ? $_GET["m_email"] : '');
$m_gubun = isset($_POST["m_gubun"]) && $_POST["m_gubun"] !== '' ? $_POST["m_gubun"] : (isset($_GET["m_gubun"]) ? $_GET["m_gubun"] : '');
$m_addr = isset($_POST["m_addr"]) && $_POST["m_addr"] !== '' ? $_POST["m_addr"] : (isset($_GET["m_addr"]) ? $_GET["m_addr"] : '');
$m_post_cd = isset($_POST["m_post_cd"]) && $_POST["m_post_cd"] !== '' ? $_POST["m_post_cd"] : (isset($_GET["m_post_cd"]) ? $_GET["m_post_cd"] : '');
$m_addr_detail = isset($_POST["m_addr_detail"]) && $_POST["m_addr_detail"] !== '' ? $_POST["m_addr_detail"] : (isset($_GET["m_addr_detail"]) ? $_GET["m_addr_detail"] : '');
$m_biz_no = isset($_POST["m_biz_no"]) && $_POST["m_biz_no"] !== '' ? $_POST["m_biz_no"] : (isset($_GET["m_biz_no"]) ? $_GET["m_biz_no"] : '');
$m_ksic = isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');
$m_ksic_detail = isset($_POST["m_ksic_detail"]) && $_POST["m_ksic_detail"] !== '' ? $_POST["m_ksic_detail"] : (isset($_GET["m_ksic_detail"]) ? $_GET["m_ksic_detail"] : '');
$email_tf = isset($_POST["email_tf"]) && $_POST["email_tf"] !== '' ? $_POST["email_tf"] : (isset($_GET["email_tf"]) ? $_GET["email_tf"] : '');
$message_tf = isset($_POST["message_tf"]) && $_POST["message_tf"] !== '' ? $_POST["message_tf"] : (isset($_GET["message_tf"]) ? $_GET["message_tf"] : '');

# 공통 컬럼 정의
$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

$nPage = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_field = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

// 상세보기
if ($mode == "S") {
	$arr_rs = selectMember($conn, (int) $m_no);

	// echo json_encode($arr_rs, JSON_UNESCAPED_UNICODE);
	$rs_m_no = trim($arr_rs[0]["M_NO"]);
	$rs_m_id = trim($arr_rs[0]["M_ID"]);
	$rs_m_pwd = trim($arr_rs[0]["M_PWD"]);

	$rs_m_email = trim($arr_rs[0]["M_EMAIL"]);
	$m_email_dec = decrypt($key, $iv, $rs_m_email);

	$rs_m_phone = trim($arr_rs[0]["M_PHONE"]);
	$m_phone_dec = decrypt($key, $iv, $rs_m_phone);

	$rs_m_addr = trim($arr_rs[0]["M_ADDR"]);
	$m_addr_dec = decrypt($key, $iv, $rs_m_addr);

	$rs_m_post_cd = trim($arr_rs[0]["M_POST_CD"]);
	$m_post_cd_dec = decrypt($key, $iv, $rs_m_post_cd);

	$rs_m_addr_detail = trim($arr_rs[0]["M_ADDR_DETAIL"]);
	$m_addr_detail_dec = decrypt($key, $iv, $rs_m_addr_detail);

	$rs_m_gubun = trim($arr_rs[0]["M_GUBUN"]);
	$rs_m_biz_no = trim($arr_rs[0]["M_BIZ_NO"]);
	$rs_m_ksic = trim($arr_rs[0]["M_KSIC"]);
	$rs_m_ksic_detail = trim($arr_rs[0]["M_KSIC_DETAIL"]);
	$rs_email_tf = trim($arr_rs[0]["EMAIL_TF"]);
	$rs_message_tf = trim($arr_rs[0]["MESSAGE_TF"]);
	$rs_disp_seq = trim($arr_rs[0]["DISP_SEQ"]);
	$rs_use_tf = trim($arr_rs[0]["USE_TF"]);
	$rs_del_tf = trim($arr_rs[0]["DEL_TF"]);
	$rs_reg_adm = trim($arr_rs[0]["REG_ADM"]);
	$rs_reg_date = trim($arr_rs[0]["REG_DATE"]);
	$rs_up_adm = trim($arr_rs[0]["UP_ADM"]);
	$rs_up_date = trim($arr_rs[0]["UP_DATE"]);
	$rs_del_adm = trim($arr_rs[0]["DEL_ADM"]);
	$rs_del_date = trim($arr_rs[0]["DEL_DATE"]);
	$search_period = "";

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "회원 조회 (회원 아이디 : " . $rs_m_id . ") ", "Read");

	$reservations = ReservationsByMember($conn, $m_no);
	$nListCnt = totalCntReservation($conn, $m_no, $rs_use_tf, $rs_del_tf, $search_field, $search_str, $search_period);
}

$rv_no = isset($_GET['rv_no']) ? $_GET['rv_no'] : ''; // 예약 번호 가져오기

$reservationDetails = getReservationDetails($conn, $rv_no);

$m_phonedesc = decrypt($key, $iv, $reservationDetails['M_PHONE']);

if ($mode == "U") {

}

?>
<!doctype html>
<lang="ko">

	<head>
		<meta charset="<?= $g_charset ?>">
		<title><?= $g_title ?></title>
		<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
		<link rel="shortcut icon" href="/manager/images/mobile.png" />
		<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
		<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
		<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
		<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
		<script type="text/javascript" src="../js/jquery-ui.js"></script>
		<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
		<script type="text/javascript" src="../js/ui.js"></script>
		<script type="text/javascript" src="../js/common.js"></script>
		<script language="javascript">

		function js_save() {

			var rv_memo = $('#rv_memo').val();
			var r_no = '<?=$reservationDetails['R_NO']?>'; // 예약 번호를 PHP에서 가져옵니다.
				// AJAX 요청
			$.ajax({
				url: "/_common/ajax_reservation_dml.php",
				type: "POST",
				data: {
					mode: "MODIFY_MEMO",
					r_no: r_no,
					rv_memo: rv_memo
				},
				dataType: "json"
			}).done(function (response) {
				if (response.success) {
					alert("관리자메모를 저장 하였습니다.");
				} else {
					alert("오류가 발생했습니다: " + response.message);
				}
			}).fail(function (xhr, status, error) {
				console.error("AJAX 요청 실패: ", error);
				alert("문제가 발생했습니다. 다시 시도해주세요.");
			});

		}

		function js_list() {
			var frm = document.frm;

			frm.method = "get";
			frm.action = "list.php";
			frm.submit();
		}

		$(document).ready(function () {
			// 셀렉트 박스 값 변경 감지
			$('#rv_agree_tf').change(function () {
				var rv_no = "<?= (int) $rv_no ?>";
				var rv_agree_tf = $(this).val(); // 선택된 승인 상태 값

				// AJAX 요청
				$.ajax({
					url: "/_common/ajax_reservation_dml.php",
					type: "POST",
					data: {
						mode: "Reservation_Control",
						rv_no: rv_no,
						rv_agree_tf: rv_agree_tf
					},
					dataType: "json"
				}).done(function (response) {
					if (response.success) {
						alert("상태가 업데이트 되었습니다.");
					} else {
						alert("오류가 발생했습니다: " + response.message);
					}
				}).fail(function (xhr, status, error) {
					console.error("AJAX 요청 실패: ", error);
					alert("문제가 발생했습니다. 다시 시도해주세요.");
				});
			});
		});

	</script>

	</head>

		<body>
			<div id="wrap">
				<div class="cont_aside">
				<?
					#====================================================================
					# common left_area
					#====================================================================
					require "../../_common/left_area.php";
				?>
			</div>
			<div id="container">
				<div class="top">
					<?
						#====================================================================
						# common top_area
						#====================================================================
						require "../../_common/top_area.php";
					?>
				</div>
				<!-- contents start-->
				<div class="contents">
					<?
						#====================================================================
						# common location_area
						#====================================================================
						require "../../_common/location_area.php";
					?>
					<div class="tit_h3">
						<h3><?= $p_menu_name ?></h3>
					</div>
					<form name="frm" method="post" enctype="multipart/form-data">

					<div class="cont">
						<div class="tbl_style01 left">
							<table>
								<colgroup>
									<col style="width:15%" />
									<col style="width:85%" />
								</colgroup>
								<tbody>
									<tr>
										<th>예약 번호</th>
										<td><?= $reservationDetails['R_NO'] ?></td>
									</tr>
									<tr>
										<th>예약 요청날짜</th>
										<td><?= $reservationDetails['REG_DATE'] ?></td>
									</tr>
									<tr>
										<th>예약 처리날짜</th>
										<td><?= $reservationDetails['UP_DATE'] ?></td>
									</tr>
									<tr>
										<th>처리상태</th>
										<td>
											<select name="rv_agree_tf" id="rv_agree_tf">
												<option value="0" <?= ($reservationDetails['RV_AGREE_TF'] == '0' ? 'selected' : ''); ?>>승인대기</option>
												<option value="1" <?= ($reservationDetails['RV_AGREE_TF'] == '1' ? 'selected' : ''); ?>>승인</option>
												<option value="2" <?= ($reservationDetails['RV_AGREE_TF'] == '2' ? 'selected' : ''); ?>>미승인</option>
												<option value="3" <?= ($reservationDetails['RV_AGREE_TF'] == '3' ? 'selected' : ''); ?>>취소</option>
												<option value="4" <?= ($reservationDetails['RV_AGREE_TF'] == '4' ? 'selected' : ''); ?>>회원취소</option>
											</select>
										</td>
									</tr>
									<tr>
										<th>회원아이디</th>
										<td><?= $reservationDetails['M_ID'] ?></td>
									</tr>
									<tr>
										<th>연락처</th>
										<td><?= $m_phonedesc ?></td>
									</tr>
									<tr>
										<th>시설명</th>
										<td><?= $reservationDetails['ROOM_NAME'] ?></td>
									</tr>
									<tr>
										<th>사용 인원</th>
										<td><?= $reservationDetails['RV_USE_COUNT'] ?></td>
									</tr>
									<tr>
										<th>사용 목적</th>
										<td><?= $reservationDetails['RV_PURPOSE'] ?></td>
									</tr>
									<tr>
										<th>시설 이용 날짜</th>
										<td><?= $reservationDetails['RV_DATE'] ?></td>
									</tr>
									<tr>
										<th>이용시간</th>
										<td><?= $reservationDetails['RV_END_TIME'] ?></td>
									</tr>
									<tr>
										<th>감면 대상자</th>
										<td>
											<?
												if ($reservationDetails['RV_REDUCTION'] === 'G001') {
													echo "전액감면 대상자";
												} elseif ($reservationDetails['RV_REDUCTION'] === 'G002') {
													echo "50% 감면 대상자";
												} else {
													echo "해당사항 없음";
												}
											?>
										</td>
									</tr>
									<tr>
										<th>대여기자재</th>
										<td>
											<?
												if ($reservationDetails['RV_EQUIPMENT'] === 'E009') {
													echo "빔프로젝터 & 엠프";
												} elseif ($reservationDetails['RV_EQUIPMENT'] === 'E003') {
													echo "엠프";
												} elseif ($reservationDetails['RV_EQUIPMENT'] === 'E001') {
													echo "빔프로젝터";
												} elseif ($reservationDetails['RV_EQUIPMENT'] === 'E002') {
													echo "마이크";
												} else {
													echo "해당사항 없음";
												} 
											?>
										</td>
									</tr>
									<tr>
										<th>사용료</th>
										<td><?= number_format($reservationDetails['RV_COST'])?></td>
									</tr>
									<tr>
										<th>미승인 사유</th>
										<td>
											<textarea style="height:150px" name="rv_memo" id="rv_memo"><?= $reservationDetails['RV_MEMO'] ?? '' ?></textarea>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="btn_wrap">
							<a href="javascript:js_save();" class="button type02">사유등록</a>
							<a href="javascript:js_list();" class="button type02">목록</a>
						</div>
					</div>
				</div>
				</form>

				<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-유형">
						<div class="modal-content modal-컨텐츠">
							<div class="modal-header">
								<h3 class="modal-title" id="defaultModalLabel"></h3>
								<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal" aria-label="Close">닫기</button>
							</div>
							<div class="modal-body"></div>
							<div class="modal-footer"></div>
						</div>
					</div>
				</div>
			</div>

	</body>

</html>

<?
	#====================================================================
	# DB Close
	#====================================================================
    
	db_close($conn);
?>