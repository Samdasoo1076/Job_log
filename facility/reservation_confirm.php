<? session_start(); ?>
<?
$_PAGE_NO = "111";

if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
	echo "<script>alert('로그인 상태가 아닙니다. 로그인 페이지로 이동합니다.');</script>";
	header("Location: /member/login.do");
	exit;
}

require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/facility/list.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/reservation/reservation.php";

$equipment_code = $_GET['equipment_code'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$room_no = isset($_POST['mr_room_no']) ? $_POST['mr_room_no'] : '';
	$rv_start_time = isset($_POST['rv_start_time']) ? $_POST['rv_start_time'] : '';
	$rv_end_time = isset($_POST['rv_end_time']) ? $_POST['rv_end_time'] : '';
	$rv_purpose = isset($_POST['rv_purpose']) ? $_POST['rv_purpose'] : '';
	$rv_use_count = isset($_POST['rv_use_count']) ? $_POST['rv_use_count'] : '';
	$rv_equipment = isset($_POST['rv_equipment']) ? $_POST['rv_equipment'] : '';
	$rv_reduction_tf = isset($_POST['rv_reduction_tf']) ? $_POST['rv_reduction_tf'] : '';

	//$rv_memo = isset($_POST['sRadio1']) ? $_POST['sRadio1'] : '';
	$rv_reduction = isset($_POST['rv_reduction']) ? $_POST['rv_reduction'] : '';
	$rv_date = isset($_POST['Rrv_date']) ? $_POST['Rrv_date'] : '';


}
// GET 요청으로 room_no 값 확인
$room_no = isset($_GET['room_no']) ? (int) $_GET['room_no'] : null;
$room = completeMeetingRoom($conn, $room_no);

$room_data = MeetingRoomPrice($conn, $room_no, $rv_start_time, $rv_equipment, $rv_reduction);

if (!$room) {
	echo "<script>alert('존재하지 않는 시설입니다.');</script>";
	header("Location: /facility/list.do");
	exit;
}

# 테이블 - TBL_RESERVATION
$room_no = isset($_POST["room_no"]) && $_POST["room_no"] !== '' ? $_POST["room_no"] : (isset($_GET["room_no"]) ? $_GET["room_no"] : '');
$rv_start_time = isset($_POST["rv_start_time"]) && $_POST["rv_start_time"] !== '' ? $_POST["rv_start_time"] : (isset($_GET["rv_start_time"]) ? $_GET["rv_start_time"] : '');
$rv_end_time = isset($_POST["rv_end_time"]) && $_POST["rv_end_time"] !== '' ? $_POST["rv_end_time"] : (isset($_GET["rv_end_time"]) ? $_GET["rv_end_time"] : '');
$rv_purpose = isset($_POST["rv_purpose"]) && $_POST["rv_purpose"] !== '' ? $_POST["rv_purpose"] : (isset($_GET["rv_purpose"]) ? $_GET["rv_purpose"] : '');
$rv_use_count = isset($_POST["rv_use_count"]) && $_POST["rv_use_count"] !== '' ? $_POST["rv_use_count"] : (isset($_GET["rv_use_count"]) ? $_GET["rv_use_count"] : '');
$rv_equipment = isset($_POST["rv_equipment"]) && $_POST["rv_equipment"] !== '' ? $_POST["rv_equipment"] : (isset($_GET["rv_equipment"]) ? $_GET["rv_equipment"] : '');
$rv_reduction = isset($_POST["rv_reduction"]) && $_POST["rv_reduction"] !== '' ? $_POST["rv_reduction"] : (isset($_GET["rv_reduction"]) ? $_GET["rv_reduction"] : '');
$rv_reduction_tf = isset($_POST["rv_reduction_tf"]) && $_POST["rv_reduction_tf"] !== '' ? $_POST["rv_reduction_tf"] : (isset($_GET["rv_reduction_tf"]) ? $_GET["rv_reduction_tf"] : '');
$rv_date = isset($_POST["rv_date"]) && $_POST["rv_date"] !== '' ? $_POST["rv_date"] : (isset($_GET["rv_date"]) ? $_GET["rv_date"] : '');
$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$totalPrice = isset($_POST["totalPrice"]) && $_POST["totalPrice"] !== '' ? $_POST["totalPrice"] : (isset($_GET["totalPrice"]) ? $_GET["totalPrice"] : '');

# =====================================================================
$m_no = (int) $_SESSION['m_no'];


$arr_data = array(
	"ROOM_NO" => $room_no,
	"RV_START_TIME" => $rv_start_time,
	"RV_END_TIME" => $rv_end_time,
	"RV_PURPOSE" => $rv_purpose,
	"RV_USE_COUNT" => $rv_use_count,
	"RV_EQUIPMENT" => $rv_equipment,
	"RV_REDUCTION_TF" => $rv_reduction_tf,
	"RV_REDUCTION" => $rv_reduction,
	"RV_DATE" => $rv_date,
	"M_NO" => $m_no,
	"RV_COST" => $totalPrice,
);

// 함수 호출
// if (insertReservation($db, $arr_data)) {
//     echo "<script>alert('예약이 완료되었습니다.');</script>";
//     header("Location: /facility/reservation_success.do");
//     exit;
// } else {
//     echo "<script>alert('예약에 실패했습니다. 다시 시도해주세요.');</script>";
// }

//$message = completeReservation($conn, $room_no, $rv_start_time, $rv_purpose, $rv_use_count, $rv_equipment, $rv_reduction_tf, $rv_memo, $rv_date, $m_no);

?>
<script>
	function goBack() {
		// URL에 room_no 값 포함된 채로 페이지 이동
		window.location.href = "reservation_detail.do?room_no=" + "<?= $room_no ?>";
	}

	function js_open_modal() {
		$("#reservation_confirm").trigger("Click");
	}


	function js_save() {

		// AJAX 요청 보내기
		$.ajax({
			url: '/_common/ajax_reservation_dml.php', // 데이터를 처리할 PHP 파일
			type: 'POST',
			dataType: 'json',
			data: $("#reservationform").serialize(),

			success: function (response) {

				//console.log("response : " + response.reservation_no);

				if (response.success == true) {
					$("#reservation_no").html(response.reservation_no);
					$("#conf_msg").html("시설 예약 신청서가 제출되었습니다.<br />업무일 기준 48시간내 예약자 연락처로 문자 발송드립니다.");
					$("#conf_titlle").html("<em>예약이 완료</em>되었습니다.");
					$("#reservation_confirm").trigger("Click");
				} else {
					$("#reservation_no").html("");
					$("#conf_msg").html("예약된 정보가 있습니다.");
					$("#conf_titlle").html("<em>예약 처리</em>되지 않았습니다.");
					$("#reservation_confirm").trigger("Click");
				}
				//console.log("s04");
			},
			error: function (xhr, status, error) {
				alert("Error: " + error);
			}
		});
	}

	// 버튼 클릭 이벤트에 js_save() 연결
	$(document).ready(function () {
		$("#saveButton").click(function () {
			js_save();
		});
	});



	///price count

	const basePrice = parseInt(document.getElementById('basePrice').innerText);
	const equipmentPrice = parseInt(document.getElementById('equipmentPrice').innerText);
	const totalPrice = basePrice + equipmentPrice;

	document.getElementById('totalPrice').innerText = totalPrice.toLocaleString();

</script>
<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content notice-list-page">
		<!-- content-header -->
		<!-- content-header -->
		<div class="content-header">
			<?
			require "../_common/content-header.php";

			?>
			<!-- // Sub Nav -->
		</div>
		<!-- // content-header -->
		<!-- // content-header -->
		<!-- content-body -->
		<div class="content-body">
			<!-- 게시판목록 페이지 -->
			<div class="board-list-page">
				<!-- 타이틀 영역 -->
				<div class="title-wrap">
					<h2 class="title">시설 예약 확인</h2>

				</div>
				<!-- // 타이틀 영역 -->

				<div class="board-list-wrap">

					<div class="board-list">
						<div class="gallery-board">
							<div class="detail-wrap">
								<div class="reservation-detail">
									<? foreach ($room['FILES'] as $file): ?>
										<img src="/upload_data/meetingroom/<?= $file['FILE_NM']; ?>"
											alt="<?= $file['FILE_RNM']; ?>">
									<? endforeach; ?>
								</div>
								<div class="cont-txt">
									<h3 class="name"><?= $room['ROOM_NAME'] ?></h3>
									<div class="dl-wrap">
										<dl>
											<dt>예약 일</dt>
											<dd><?= $rv_date ?></dd>
										</dl>
										<dl>
											<dt>예약 시간</dt>
											<dd><?= $rv_end_time ?></dd>
										</dl>
										<dl>
											<dt>사용 목적</dt>
											<dd><?= $rv_purpose ?></dd>
										</dl>
										<dl>
											<dt>사용 인원</dt>
											<dd><?= $rv_use_count ?> </dd>
										</dl>
										<dl>
											<dt>대여기자재</dt>
											<dd><?= empty($rv_equipment) ? "사용 안 함" : getDcodeName($conn, "EQUIPMENT", $rv_equipment) ?> </dd>
										</dl>
										<dl>
											<dt>감면대상자</dt>
											<dd><?= ($rv_reduction_tf === 'Y' && !empty($rv_reduction)) ? getDcodeName($conn, "GAM", $rv_reduction) : "대상 아님" ?></dd>
										</dl>
									</div>
									<div class="fee-wrap">
										<dl class="fee">
											<dt>이용료</dt>
											<dd id="totalPrice">
												<?=  number_format($room_data['BASE_PRICE'] + $room_data['EQUIPMENT_PRICE']) ?> 원
											</dd>
										</dl>
									</div>
								</div>
							</div>

							<form id="reservationform" method="POST">
								<input type="hidden" name="mode" value="REVSERVATION">
								<input type="hidden" name="room_no" value="<?= $room_no ?>">
								<input type="hidden" name="rv_start_time" value="<?= $rv_start_time ?>">
								<input type="hidden" name="rv_end_time" value="<?= $rv_end_time ?>">
								<input type="hidden" name="rv_purpose" value="<?= $rv_purpose ?>">
								<input type="hidden" name="rv_use_count" value="<?= $rv_use_count ?>">
								<input type="hidden" name="rv_equipment" value="<?= $rv_equipment ?>">
								<input type="hidden" name="rv_reduction_tf" value="<?= $rv_reduction_tf ?>">
								<input type="hidden" name="rv_reduction" value="<?= $rv_reduction ?>">
								<input type="hidden" name="rv_date" value="<?= $rv_date ?>">
								<input type="hidden" name="m_no" value="<?= $m_no ?>">
								<input type="hidden" name="totalPrice" value="<?= $room_data['BASE_PRICE'] + $room_data['EQUIPMENT_PRICE'] ?>">
							</form>
							<div class="btn-wrap center-w200">
								<button type="button" class="btn-basic h-56"
									onClick="location='reservation_detail.do?room_no=<?= $room_no ?>'">
									<span>뒤로가기</span>
								</button>
								<button type="button" id="saveButton" class="btn-basic h-56 primary btn-popup" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
									<span>신청서 제출하기</span>
								</button>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- // 게시판목록 페이지 -->
		</div>
		<!-- // content-body -->
	</div>
	<!-- // content -->
</main>
<!-- // Container -->

<!-- include_footer.html -->
<footer class="footer">
	<?
	require "../_common/front_footer.php";
	?>
</footer>
<!-- // include_footer.html -->
</div>

<!-- 예약완료팝업  -->
<div class="modal fade info-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-basic">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <h1 class="modal-title" id="exampleModalLabel">Modal title</h1> -->
				<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal" aria-label="Close"><span
						class="blind">닫기</span></button>
			</div>
			<div class="modal-body">
				<div class="cont center">
					<p class="tit" id="conf_titlle"><em>예약이 완료</em>되었습니다.</p>
					<div class="complete-txt">
						<p class="txt" id="conf_msg">
							시설 예약 신청서가 제출되었습니다.<br />
							업무일 기준 48시간내 예약자 연락처로 문자 발송드립니다.
						</p>
					</div>
					<div class="info-txt">
						<dl>
							<dt>예약번호</dt>
							<dd id="reservation_no"></dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="btn-wrap">
					<button type="button" class="btn-basic h-56 primary center-w200" onClick="location='/member/mypage_reservation.do'">
						<span>확인</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>