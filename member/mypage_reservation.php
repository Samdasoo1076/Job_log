<? session_start(); ?>
<?
$_PAGE_NO = "102";

// 로그인 상태 확인
if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
	echo "<script>alert('로그인 상태가 아닙니다')</script>";
	echo "<script>window.location.href='login.do';</script>";
	exit;
}

require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/reservation/reservation.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";


$m_no = $_SESSION['m_no'];

$page_header_type = 'mypage';

# 공통 컬럼 정의
$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

# 페이징 관련
$nPage = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_period = isset($_POST["search_period"]) && $_POST["search_period"] !== '' ? $_POST["search_period"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_field = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');


if ($nPage <> "" && $nPageSize <> 0) {
	$nPage = (int) ($nPage);
} else {
	$nPage = 1;
}

if ($nPage <> "" && $nPageSize <> 0) {
	$nPageSize = (int) ($nPageSize);
} else {
	$nPageSize = 10;
}

if ($nPageSize == 0) {
	$nPageSize = 10;
}

$nPageBlock = 10;

$nListCnt = totalCntReservation($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period);

$nTotalPage = (int) (($nListCnt - 1) / $nPageSize + 1);

if ((int) ($nTotalPage) < (int) ($nPage)) {
	$nPage = $nTotalPage;
}

// 사용자 조회
$user_data = mypageUserMember($conn, $m_no);

// 예약 현황 목록 조회
$arr_rv_list = listReservations2($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period, $nPage, $nPageSize, $nListCnt);

$current_url = $_SERVER['REQUEST_URI'];


?>

<head>
	<!-- 페이지 스크립트 영역 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

	<script language="javascript">
		$(document).ready(function () {
			var defaultPeriod = '6M'; // 기본 조회 기간 설정
			var defaultSelectElement = $('#frmSelect01');
			defaultSelectElement.val(defaultPeriod);
			js_search_period(defaultSelectElement.get(0));
		});

		//
		function js_search_period(selectElement) {
			const selectedValue = selectElement.value;
			const m_no = "<?= (int) $m_no ?>";

			// AJAX 요청
			$.ajax({
				url: "/_common/ajax_reservation_dml.php",
				type: "POST",
				data: {
					mode: "SEARCH_PERIOD",
					search_period: selectedValue,
					m_no: m_no
				},
				dataType: "json"
			}).done(function (response) {
				if (response.success) {
					updateReservationList(response.data, response.totalCnt);
				} else {
					alert("오류가 발생했습니다: " + response.message);
				}
			}).fail(function (xhr, status, error) {
				console.error("AJAX 요청 실패: ", error);
				alert("문제가 발생했습니다. 다시 시도해주세요.");
			});
		}

	// 리스트 업데이트 함수
	function updateReservationList(data, totalCnt) {
		const reservationList = $("#reservationList"); // 대상 요소 선택
		reservationList.empty(); // 기존 리스트 비우기

		// 데이터가 없을 경우 처리
		if (data.length === 0) {
			
			reservationList.html(`
				<div class="no-list">
					<p>${$('#frmSelect01 option:selected').text()}동안 예약 내역이 없습니다.​</p>
				</div>
			`);
			$("#totalReservationCount").text(0); // 총 개수 0으로 설정
			return;
		}


		data.forEach((reservation) => {
			const listItem = `
				<div class="reserv-item">
					<div class="img-wrap">
						<img src="/upload_data/meetingroom/${reservation.ROOM_FILE || 'default.jpg'}" alt="Room Image">
					</div>
					<div class="wrap">
						<div class="info">
							<div>
								<dl>
									<dt>시설명</dt>
									<dd class="bold">${reservation.ROOM_NAME || 'N/A'}</dd>
								</dl>
								<dl>
									<dt>시설크기</dt>
									<dd>${reservation.ROOM_SCALE || 'N/A'}</dd>
								</dl>
							</div>
							<a class="page-move" href="/facility/reservation_detail.do?room_no=${reservation.ROOM_NO}">
								시설정보
							</a>
						</div>
						<div class="info">
							<div>
								<dl>
									<dt>예약날짜</dt>
									<dd>${reservation.RV_REG_DATE ? formatDate(reservation.RV_REG_DATE) : 'N/A'}</dd>
								</dl>
								<dl>
									<dt>이용날짜</dt>
									<dd>${reservation.RV_DATE ? formatDate(reservation.RV_DATE) : 'N/A'}</dd>
								</dl>
							</div>
							<a class="page-move btn-popup" href="#" data-bs-toggle="modal"
							data-bs-target="#exampleModal"
							onclick="js_rv_detail('${reservation.RV_NO}', '${reservation.M_NO}')">
								예약 내역
							</a>
						</div>
					</div>
					${getReservationStatus(reservation.RV_AGREE_TF, reservation.RV_UP_DATE, reservation.RV_NO, reservation.M_NO, reservation.RV_MEMO )}
				</div>
			`;

		// 예약 리스트 추가
		$("#reservationList").append(listItem);
		$("#totalReservationCount").text(totalCnt); // 예약 총 개수 업데이트
});


	}

	// 예약 상태 처리 함수
	function getReservationStatus(rvAgreeTF, rvUpDate, rvNo, mNo, memo) {
		switch (rvAgreeTF) {
			case '1':
				return `
					<div class="result">
						<p class="value complete">예약완료</p>
						<p class="date">처리날짜: ${rvUpDate ? formatDate(rvUpDate) : 'N/A'}</p>
					</div>
				`;
			case '0':
				return `
					<div class="result">
						<p class="value waiting">승인대기</p>
						<button type="button" id="reservationCancleBtn" name="reservationCancleBtn" class="btn-basic h-48"
								onclick="js_reservation_cancle('${rvNo}', '${mNo}');">
							<span>취소하기</span>
						</button>
					</div>
				`;
			case '2':
				return `
					<div class="result">
						<p class="value complete">미승인</p>
						<p>사유 : ${memo}</p>
						<p class="date">처리날짜: ${rvUpDate ? formatDate(rvUpDate) : 'N/A'}</p>
					</div>
				`;
			case '3':
				return `
					<div class="result">
						<p class="value cancelled">승인취소</p>
					</div>
				`;
			case '4':
				return `
					<div class="result">
						<p class="value cancelled">회원취소</p>
						<p class="date">처리날짜: ${rvUpDate ? formatDate(rvUpDate) : 'N/A'}</p>
					</div>
				`;
			default:
				return '';
		}
	}

		function formatDate(dateString) {
			const date = new Date(dateString);
			return date.toISOString().split('T')[0]; // YYYY-MM-DD 형식으로 변환
		}

		function formatNumberWithComma(number) {
		return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}

		function js_rv_detail(rv_no, m_no) {
			// AJAX 요청
			$.ajax({
				url: "/_common/ajax_reservation_dml.php",
				type: "POST",
				data: {
					mode: "GET_RESERVATION_DETAIL",
					rv_no: rv_no,
					m_no: m_no
				},
				dataType: "json"
			}).done(function (response) {
				if (response.success) {
					updateModal(response.data, response.m_phone_dec, response.rv_equipment, response.rv_reduction);
				} else {
					alert("오류가 발생했습니다: " + response.message);
				}
			}).fail(function (xhr, status, error) {
				console.error("AJAX 요청 실패: ", error);
				alert("문제가 발생했습니다. 다시 시도해주세요.");
			});
		}

		function updateModal(data, m_phone, equipment, reduction ) {
			var reservationDetails = data[0];
			var modalBody = $('.modal-body .cont');
			modalBody.empty();

			var approveStatus = "";
		switch (reservationDetails.RV_AGREE_TF) {
			case "0":
				approveStatus = "승인대기";
				break;
			case "1":
				approveStatus = "예약완료";
				break;
			case "2":
				approveStatus = "미승인" + " 사유 : " + reservationDetails.RV_MEMO;
				break;
			case "3":
				approveStatus = "승인취소";
				break;
			case "4":
				approveStatus = "회원취소";
				break;
			default:
				approveStatus = "flag값 오류";
				break;
		}
			var formattedNumber = formatNumberWithComma(reservationDetails.RV_COST);

			var rentalEquipment = reservationDetails.RV_EQUIPMENT
			? equipment
			: "사용안함";

			var reductionInfo = reservationDetails.RV_REDUCTION
			? reduction
			: "대상 아님";

			var html = `<p class="tit"><em>${reservationDetails.M_ID}</em>님의 예약 내역입니다.</p>
				<div class="info top">
					<dl><dt>예약번호</dt><dd>${reservationDetails.R_NO}</dd></dl>
					<dl><dt>연락처</dt><dd>${m_phone}</dd></dl>
					   <dl><dt>승인여부</dt><dd>${approveStatus}</dd></dl>
				</div>
				<div class="info">
					<dl><dt>시설명</dt><dd>${reservationDetails.ROOM_NAME}</dd></dl>
					<dl><dt>사용 인원</dt><dd>${reservationDetails.RV_USE_COUNT}명</dd></dl>
					<dl><dt>사용 목적</dt><dd>${reservationDetails.RV_PURPOSE}</dd></dl>
					<dl><dt>시설 방문 날짜</dt><dd>${reservationDetails.RV_DATE}</dd></dl>
					<dl><dt>이용 시간</dt><dd>${reservationDetails.RV_END_TIME}</dd></dl>
					<dl><dt>대여기자재 사용</dt><dd>${rentalEquipment}</dd></dl>
					<dl><dt>감면대상자</dt><dd>${reductionInfo}</dd></dl>
					<dl><dt>예약비용</dt><dd>${formattedNumber}원</dd></dl>
				</div>
				<div class="info-txt">
					<div class="text-area" style="max-width:900px;margin-left: 20px;text-align:left">
									<!--
									<p>ㅇ 대관신청시 첨부된 '대관시설 사용 신청서'를 작성하여 사전에 제출해주시기 바랍니다.</p>
									<p>ㅇ 대관사용 취소 또는 변경시 '대관사용변경신청서'를 사전에 제출하지 않을 경우 대관비용이 발생할 수 있습니다.</p>
									-->
									<p>ㅇ 별표에 따라 사용료 감면대상에 해당될 경우 '사용료 감면 신청서'를 담당자에게 제출해주시기 바랍니다.</p>
									<p>ㅇ 시설물, 기자재 문제 발생할 경우 담당자에게 전달해주세요. 분실 파손 시 원상 복구 혹은 배상 책임이 있습니다.</p>
									<p>ㅇ 창업지원허브 건물 안에서는 화기 엄금입니다. 안전을 위해 모든 공간에서 불씨를 일으킬만한 어떤 행위도 허용되지 않습니다.</p>
									<p>ㅇ 기타 문의, 외부기자재 사용 및 운반건 등 사전 협의가 필요한 사항들은 미리 유선확인(033-764-3160) 확인 후 이용하시길 바랍니다.</p>
									<p>ㅇ 미리 협의가 없으면 제한되는 부분이 있을 수 있으니 유의하시어 불편함 없으시길 당부 드립니다.​</p>
									<p style="padding-top:10px;margin-left: 10px;"><font color="red"><b>* 담당자연락처: 033-764-3160 / woody@wfi.or.kr</b></font></p>
								</div>
				</div>
				<div class="modal-footer">
					<div class="btn-wrap">
						<button type="button" id="pdfBtn" class="btn-basic h-56 primary center-w200"
							onclick="generatePDF('${reservationDetails.R_NO}')">
							<span>PDF로 저장하기</span>
						</button>
					</div>
				</div>`;

			modalBody.html(html);
			$('#exampleModal').modal('show');
		}

		function generatePDF(Rno) {

			const { jsPDF } = window.jspdf; // jsPDF 객체 가져오기

			// PDF 객체 생성
			var doc = new jsPDF();
			var modalContent = document.querySelector('.modal-body .cont');

			html2canvas(modalContent, {
				scale: 1,
				useCORS: true, // CORS가 필요한 이미지가 있을 경우
				ignoreElements: (element) => {
					// .modal-footer를 제외
					return element.classList.contains('modal-footer');
        		}
			})
				.then(canvas => {
					var imgData = canvas.toDataURL('image/png');

					var imgWidth = canvas.width;
					var imgHeight = canvas.height;

					// PDF 페이지 크기 설정
					var pdfWidth = imgWidth * 0.264583;
					var pdfHeight = imgHeight * 0.264583;

					// jsPDF 페이지 크기 설정
					doc = new jsPDF({
						orientation: pdfWidth > pdfHeight ? 'l' : 'p',
						unit: 'mm',
						format: [pdfWidth, pdfHeight]
					});

					// PDF에 이미지 추가
					doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

					// PDF 저장
					doc.save(Rno + '.pdf');
				})
				.catch(error => {
					console.error("PDF 생성 중 오류 발생:", error);
					alert("PDF 생성 중 문제가 발생했습니다.");
				});
		}

		// 사용자가 예약을 직접 취소할 경우
		function js_reservation_cancle(rv_no, m_no) {

			var bReservationChangeOK = confirm('예약을 취소하시겠습니까?');

			if(bReservationChangeOK == true) {
			// AJAX 요청
			$.ajax({
				type: "POST",
				url: "/_common/ajax_reservation_dml.php",
				data: {
					mode: "CANCLE",
					rv_no: rv_no,
					m_no: m_no,
				},
				dataType: "json",
				success: function (response) {
					if (response.success === "T") { // 문자열 "T"와 비교
						alert(response.message);   // 성공 메시지 출력
						location.reload();         // 페이지 리로드
					} else {
						alert("오류: " + response.message); // 오류 메시지 출력
					}
				},
				error: function (error) {
					// Ajax 요청 실패 시 처리
					alert("서버 요청 중 오류가 발생했습니다.");
					console.error(error + 'ggggg');
				}
			});

			}else {
				return false;
			}
		}


	</script>

</head>
<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content notice-list-page">
		<!-- content-header -->
		<div class="content-header">
			<?
			require $_SERVER['DOCUMENT_ROOT'] . "/_common/hard_content_header.php";
			?>
		</div>
		<!-- // content-header -->
		<!-- content-body -->
		<div class="content-body">
			<!-- 타이틀 영역 -->
			<div class="title-wrap">
				<h2 class="title">나의 예약 현황</h2>
				<p class="explain">최대 24개월 내역까지 조회 가능합니다.​​</p>
			</div>
			<!-- // 타이틀 영역 -->

			<div class="mypage-wrap">
				<div class="reservation-info-box">
					<p class="tit">중요안내</p>
					<ul class="bul dot">
						<li>예약 완료 및 예약 확정 안내 문자를 받은 예약 건은 취소가 불가합니다. ​</li>
						<li>예약 취소는 승인 대기 상태에서만 가능하며, 시설 사정으로 예약이 불가할 경우 자동 취소 처리되며 취소 안내 문자를 발송해 드립니다. ​</li>
						<li>자세한 문의는 고객센터에 문의하세요.​</li>
					</ul>
					<p class="call">고객센터 전화 <a href="tel:033-764-3160​">033-764-3160​</a></p>
				</div>
				<div class="board-list-wrap">
					<div class="board-toolbar">
						<p class="board-count">전체 <em id="totalReservationCount"><?= $nListCnt ?></em>건의 예약</p>
						<div class="board-srch">
							<span class="set">조회기간 설정</span>
							<div class="frm-sel h-48 w160">

								<select name="search_period" id="frmSelect01" title="검색 구분" class="sel"
									onchange="js_search_period(this);">
									<option value="6M">최근 6개월</option>
									<option value="12M">최근 12개월</option>
									<option value="24M">최근 24개월</option>
								</select>
							</div>
						</div>
					</div>
					<div class="board-list-reserv" id="reservationList">
						<? foreach ($arr_rv_list as $arr_rs): ?>
							<div class="reserv-item">
								<div class="img-wrap">
									<img src="/upload_data/meetingroom/<?= $arr_rs['ROOM_FILE'] ?>" alt="Room Image">
								</div>
								<div class="wrap">
									<div class="info">
										<div>
											<dl>
												<dt>시설명</dt>
												<dd class="bold"><?= $arr_rs['ROOM_NAME'] ?></dd>
											</dl>
											<dl>
												<dt>시설크기</dt>
												<dd><?= $arr_rs['ROOM_SCALE'] ?></dd>
											</dl>
										</div>
										<a class="page-move"
											href="/facility/reservation_detail.do?room_no=<?= $arr_rs['ROOM_NO'] ?>">시설정보</a>
									</div>
									<div class="info">
										<div>
											<dl>
												<dt>예약날짜</dt>
												<dd><?= isset($arr_rs['RV_REG_DATE']) ? date("Y-m-d", strtotime($arr_rs['RV_REG_DATE'])) : 'N/A'; ?>
												</dd>
											</dl>
											<dl>
												<dt>이용날짜</dt>

												<dd><?= isset($arr_rs['RV_DATE']) ? date("Y-m-d", strtotime($arr_rs['RV_DATE'])) : 'N/A'; ?>
												</dd>
											</dl>
										</div>
										<a class="page-move btn-popup" href="#" data-bs-toggle="modal"
											data-bs-target="#exampleModal"
											onclick="js_rv_detail('<?= $arr_rs['RV_NO'] ?>', '<?= $arr_rs['M_NO'] ?>')">
											예약 내역
										</a>
									</div>
								</div>

								<? if ($arr_rs['RV_AGREE_TF'] === '1'): ?>
									<div class="result">
										<p class="value complete">예약완료</p>
										<p class="date">처리날짜 :
											<?= isset($arr_rs['RV_UP_DATE']) ? date("Y-m-d", strtotime($arr_rs['RV_UP_DATE'])) : 'N/A'; ?>
										</p>
									</div>
								<? elseif ($arr_rs['RV_AGREE_TF'] === '0'): ?>
									<div class="result">
										<p class="value waiting">승인대기</p>
										<button type="button" id="reservationCancleBtn" name="reservationCancleBtn"
											class="btn-basic h-48"
											onclick="js_reservation_cancle('<?= $arr_rs['RV_NO'] ?>', '<?= $arr_rs['M_NO'] ?>');">
											<span>취소하기</span>
										</button>
									</div>
								<? elseif ($arr_rs['RV_AGREE_TF'] === '2'): ?>
									<div class="result">
										<p class="value complete">미승인</p>
										<p>사유 : <?= $arr_rs['RV_MEMO'] ?></p>
										<p class="date">처리날짜 :
											<?= isset($arr_rs['RV_UP_DATE']) ? date("Y-m-d", strtotime($arr_rs['RV_UP_DATE'])) : 'N/A'; ?>
										</p>
									</div>
								<? elseif ($arr_rs['RV_AGREE_TF'] === '3'): ?>
									<div class="result">
										<p class="value cancelled">승인취소</p>
									</div>
								<? elseif ($arr_rs['RV_AGREE_TF'] === '4'): ?>
									<div class="result">
										<p class="value cancelled">회원취소</p>
										<p class="date">처리날짜 :
											<?= isset($arr_rs['RV_UP_DATE']) ? date("Y-m-d", strtotime($arr_rs['RV_UP_DATE'])) : 'N/A'; ?>
										</p>
									</div>
								<? else: ?>
									<div class="result">
										<p class="value unknown">Flag 오류</p>
									</div>
								<? endif; ?>

							</div>
							<? endforeach; ?>

					</div>

					<? if (sizeof($arr_rv_list) > 0) { ?>
						<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						$strParam = $strParam . "f=" . $f . "&s=" . $s;

						?>
						<?= Front_Image_PageList($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>

					<? } ?>

				</div>
			</div>
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

<!-- 예약내역팝업 START -->
<div class="modal fade info-modal" id="exampleModal" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-basic">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <h1 class="modal-title" id="exampleModalLabel">Modal title</h1> -->
				<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="blind">닫기</span></button>
			</div>
			<div class="modal-body">
				<div class="cont">

				</div>
			</div>
			<!-- <div class="modal-footer">
				<div class="btn-wrap">
					<button type="button" id="pdfBtn" class="btn-basic h-56 primary center-w200"
						onclick="generatePDF('<?= $arr_rs['R_NO'] ?>')">
						<span>PDF로 저장하기</span>
					</button>
				</div>
			</div> -->
		</div>
	</div>
</div>
<!-- 예약내역팝업 END -->
</body>
</html>