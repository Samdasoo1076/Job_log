<? session_start(); ?>
<?
$_PAGE_NO = "101";

// 로그인 상태 확인
if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
	echo "<script>alert('로그인 상태가 아닙니다')</script>";
	echo "<script>window.location.href='login.do';</script>";
	exit;
}


require_once $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

$m_no = $_SESSION['m_no'];
$m_id = $_SESSION['m_id'];
$page_header_type = 'mypage';

$post_m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$post_m_id = isset($_POST["m_id"]) && $_POST["m_id"] !== '' ? $_POST["m_id"] : (isset($_GET["m_id"]) ? $_GET["m_id"] : '');

// 폼데이터용 변수 설정
$m_gubun = isset($_POST["m_gubun"]) && $_POST["m_gubun"] !== '' ? $_POST["m_gubun"] : (isset($_GET["m_gubun"]) ? $_GET["m_gubun"] : '');
$m_ksic = isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');
$m_ksic_detail = isset($_POST["m_ksic_detail"]) && $_POST["m_ksic_detail"] !== '' ? $_POST["m_ksic_detail"] : (isset($_GET["m_ksic_detail"]) ? $_GET["m_ksic_detail"] : '');
$email_tf = isset($_POST["email_tf"]) && $_POST["email_tf"] !== '' ? $_POST["email_tf"] : (isset($_GET["email_tf"]) ? $_GET["email_tf"] : '');
$message_tf = isset($_POST["message_tf"]) && $_POST["message_tf"] !== '' ? $_POST["message_tf"] : (isset($_GET["message_tf"]) ? $_GET["message_tf"] : '');

$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$up_date = isset($_POST["up_date"]) && $_POST["up_date"] !== '' ? $_POST["up_date"] : (isset($_GET["up_date"]) ? $_GET["up_date"] : '');



// 사용자 조회
$user_data = mypageUserMember($conn, $m_no);

$email 		= decrypt($key, $iv, $user_data['M_EMAIL']);
$pwd   		= decrypt($key, $iv, $user_data['M_PWD']);
$email_parts = explode('@', $email);
$email01_value = isset($email_parts[0]) ? $email_parts[0] : '';
$email02_value = isset($email_parts[1]) ? $email_parts[1] : '';

// 휴대폰 번호 중간 네 자리를 ****로 변환
function maskPhoneNumber($phone)
{
	if (preg_match('/^(\d{3})-(\d{4})-(\d{4})$/', $phone, $matches)) {
		return $matches[1] . '-****-' . $matches[3];
	}
	return $phone;
}


function maskEmail($email)
{
	if (strpos($email, '@') !== false) {
		list($username, $domain) = explode('@', $email, 2);

		// 사용자 이름 마스킹 (4번째 이후 문자부터 '*')
		if (strlen($username) > 4) {
			$masked = substr($username, 0, 4) . str_repeat('*', strlen($username) - 4);
		} else {
			$masked = str_repeat('*', strlen($username));
		}
		return $masked . '@' . $domain;
	}
	return $email; // 이메일 형식이 아닌 경우 원래 값 반환
}


// 비밀번호를 전체 마스킹 처리하는 함수
function maskPassword($password)
{
	if (!empty($password)) {
		return str_repeat('*', strlen($password)); // 비밀번호 길이만큼 *로 대체
	}
	return ''; // 비밀번호가 없을 경우 빈 문자열 반환
}

// 수정일 경우
if ($mode == 'U') {
	$m_gubun = SetStringToDB($m_gubun);
	$m_ksic = SetStringToDB($m_ksic);
	$m_ksic_detail = SetStringToDB($m_ksic_detail);
	$email_tf = SetStringToDB($email_tf);
	$message_tf = SetStringToDB($message_tf);

	//$up_date       = SetStringToDB($up_date);

	$arr_data = array(
		"M_GUBUN" => $m_gubun,
		"M_KSIC" => $m_ksic,
		"M_KSIC_DETAIL" => $m_ksic_detail,
		"EMAIL_TF" => $email_tf,
		"MESSAGE_TF" => $message_tf,
	);

	$result_update = updateMember($conn, $arr_data, $m_no);

}

if (isset($result_update)) {
	#$strParam = $strParam . "?nPage=" . $nPage . "&nPageSize=" . $nPageSize . "&search_field=" . $search_field . "&search_str=" . $search_str;
	?>
	<!DOCTYPE html
		PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= $g_charset ?>" />
		<script language="javascript">
			alert('변경된 내용이 저장되었습니다.');
			document.location.href = "/member/mypage.do";
		</script>
	</head>

	</html>
	<?
	exit;
}
?>

<head>
	<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
	<script language="javascript">

	$(document).ready(function () {

		$("#email_change_area").hide();
		$("#phone_auth_area").hide();
		$("#phone_change_area").hide();
		$("#pwd_change_area").hide();
		$("#pwd_chk_change_area").hide();
		$("#organ_change_area").hide();
		$("#biz_change_area").hide();
		$("#name_change_area").hide();
		$("#addr_change_area").hide();

		var gubunValue = document.getElementById('m_gubun').value;
		var m_ksic = document.getElementById('m_ksic').value;
		var m_ksic_detail = "<?= $user_data['M_KSIC_DETAIL'] ?>";

		js_m_ksicByValue(m_ksic, m_ksic_detail);

		// 기업회원일 경우 사업자등록번호 노출
		$("input[name='rd_m_gubun']").on("change", function () {

			if ($("#m_gubun_c").is(":checked")) {
				$("#biz_no_area").show();
				$("#m_organ_area").show();
				$("#m_name_area").hide();
				
				if (gubunValue === 'C') {
					$("#biz_change_area").hide();
					$("#biz_rs_area").show();
				} else if (gubunValue === 'P') {
					$("#biz_rs_area").hide();
					$("#biz_change_area").show();
				}
			} else {
				$("#biz_no_area").hide();
				$("#m_organ_area").hide();
				$("#m_name_area").show();
			}
		});

		// 기존 값 저장
		const initialData = {
			m_gubun: $('input[name="rd_m_gubun"]:checked').val(),
			m_ksic: $('#m_ksic').val(),
			m_ksic_detail: $('#ksic_detail_selector').val(),
			email_tf: $('input[name="rd_email_tf"]:checked').val(),
			message_tf: $('input[name="rd_message_tf"]:checked').val()
		};

    // 변경 상태 확인
    function checkChanges() {
		const currentData = {
				m_gubun: $('input[name="rd_m_gubun"]:checked').val(),
				m_ksic: $('#m_ksic').val(),
				m_ksic_detail: $('#ksic_detail_selector').val(),
				email_tf: $('input[name="rd_email_tf"]:checked').val(),
				message_tf: $('input[name="rd_message_tf"]:checked').val()
		};

      // 변경된 값이 있는지 확인
		const isChanged = Object.keys(initialData).some(key => initialData[key] !== currentData[key]);

		// 버튼 활성화/비활성화
		if (isChanged) {
				$('#update_btn').prop('disabled', false);
		} else {
				$('#update_btn').prop('disabled', true);
		}
    }

    $('input[name="rd_m_gubun"]').on('change', checkChanges);
    $('#m_ksic, #ksic_detail_selector').on('change', checkChanges);
    $('input[name="rd_email_tf"]').on('change', checkChanges);
    $('input[name="rd_message_tf"]').on('change', checkChanges);


	});

	/*
	###############################################################
	# 전화번호 관련
	###############################################################
	*/

	function js_phone_change() {

		var bPhonChangeOK = confirm('휴대폰 본인 인증이 필요한 서비스입니다. 계속 진행하시겠습니까?');

		if (bPhonChangeOK === true) {
			$("#phone_rs_area").hide();
			$("#phone_change_area").show();
			//$("#phone_change_btn").prop('disabled', true);

		} else {
			$("#phone_rs_area").show();
			$("#phone_change_area").hide();
			//$("#phone_change_btn").prop('disabled', false);
		}
	}

	let timerInterval = null;

	// 인증 요청 함수
	function authPhoneRequest() {
		const mPhone = document.getElementById('m_phone').value;

		if (mPhone === '' || mPhone === null) {
			alert("휴대폰 번호를 입력해주세요.");
			document.getElementById('m_phone').focus();
			return false;
		}

		if (!/^\d{11}$/.test(mPhone)) {
			alert("휴대폰 번호는 숫자만 11자리여야 합니다. (예시: 01012345678)");
			document.getElementById('m_phone').focus();
			return false;
		}

		// AJAX 요청
		$.ajax({
			type: "POST",
			url: "/_common/ajax_member_dml.php",
			data: {
				mode: "AUTH_PHONE",
				sms_flag : "3",
				m_phone: mPhone
			},
			dataType: "json",
			success: function(response) {
				if (response.success) {
					$("#phone_auth_area").show();
					alert(`인증번호가 발송되었습니다`);
					startTimer(180, "#setTimer");
				} else {
					alert("인증번호 생성 중 오류가 발생했습니다.");
				}
			},
			error: function(xhr, status, error) {
				alert("AJAX 요청 중 오류 발생: " + error);
			}
		});
	}

	// 3분 타이머 함수
	function startTimer(duration, selector) {
    if (timerInterval) {
        clearInterval(timerInterval);
    }

    let timer = duration; // 타이머 초기화
    const element = document.querySelector(selector);

    timerInterval = setInterval(() => {
        const minutes = Math.floor(timer / 60);
        const seconds = timer % 60;

        // 2자리 숫자로 포맷
        const formattedTime = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
        element.textContent = formattedTime;

        if (timer === 0) {
            clearInterval(timerInterval); // 타이머 중지
            timerInterval = null; // 타이머 변수 초기화
            element.textContent = "인증에 실패하였습니다. 다시 시도해주세요.";
        } else {
            timer--; // 타이머 감소
        }
    }, 1000); // 1초마다 실행
}

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
		document.querySelector("#setTimer").textContent = "인증에 실패하였습니다. 다시 시도해주세요.";
    }
}

	function formatPhone(input) {

		var phone = input.value.replace(/[^0-9]/g, "");
		if (phone.length > 11) {
			phone = phone.substring(0, 11);
		}
		input.value = phone;
	}

	function authPhoneCheck() {
		const authCode = document.getElementById('auth_code').value;
		const m_phone = document.getElementById('m_phone').value;
		const formattedPhone = m_phone.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
		const m_id = $("#m_id").val();
		const m_no = $("#m_no").val();


		if (!/^\d{4}$/.test(authCode)) {
				alert("4자리 숫자 인증번호를 입력해주세요.");
				return false;
		}
		// AJAX 요청
		$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode : "AUTH_CODE",
					authCode: authCode
				},
				dataType: "json",
				success: function (response) {
						if (response.success) {
								alert("인증에 성공하였습니다!");
								$("#m_phone_rq").prop('disabled', true);
								$("#auth_phone_chk").prop('disabled', true);
								$('#setTimer').hide();
								$("#auth_status").val("Y");

						// AJAX 요청
						$.ajax({
							type: "POST",
							url: "/_common/ajax_member_dml.php",
							data: {
								mode: "PHONE_CHANGE",
								m_no: m_no,
								m_id: m_id,
								m_phone: formattedPhone
							},
							dataType: "json",
							success: function (response) {
								if (response.result === "T") {
									alert(response.message);
									$("#phone_change_btn").prop('disabled', true);
									$("#phone_change_btn").text('인증완료');
									$("#phone_rs_area").show();
									$("#phone_change_area").hide();
									document.getElementById("rs_phone").innerText = response.value;
									return;

								} else if (response.result === "F") {
									alert(response.message);
									$("#auth_status").val("N");
								} else {
									alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
								}
							},
							error: function (xhr, status, error) {
								console.error("AJAX Error: ", error);
								alert("전화번호 변경에 실패했습니다. 다시 시도해주세요.");
							}
						});

						} else {
							stopTimer();
							alert("잘못된 인증번호, 다시 시도해 주세요");
						}
				},
				error: function (xhr, status, error) {
						alert("AJAX 요청 중 오류 발생: " + error);
				},
		});
	}

		/*
		###############################################################
		# 이메일 관련
		###############################################################
		*/

		function js_email_change() {
			var bEmailChangeOK = confirm('이메일을 변경하시겠습니까?');

			if (bEmailChangeOK === true) {
				$("#email_rs_area").hide();
				$("#email_change_area").show();
			} else {
				$("#email_rs_area").show();
				$("#email_change_area").hide();
			}
		}

		function js_email_submit() {

			const email01 = document.getElementById('email01').value.trim();
			const email02 = document.getElementById('email02').value.trim();
			const m_email = email01 + "@" + email02;

			const m_id = $("#m_id").val();
			const m_no = $("#m_no").val();

			const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

			if (email01 == "") {
				alert('이메일(앞)을 입력해주세요.');
				frm.email01.focus();
				return;
			}

			if (email02 == "") {
				alert('이메일(뒤)을 입력해주세요.');
				frm.email02.focus();
				return;
			}

			// 이메일 형식 검증
			if (!emailRegex.test(m_email)) {
				alert("유효한 이메일 주소를 입력해주세요.");
				frm.email01.focus(); // 초점은 email01에 맞춤
				return;
			}

			// AJAX 요청
			$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode: "EMAIL_CHANGE",
					m_no: m_no,
					m_id: m_id,
					m_email: m_email
				},
				dataType: "json",
				success: function (response) {
					if (response.result === "T") {

						alert(response.message);

						$("#email_rs_area").show();
						$("#email_change_area").hide();
						$("#email_change_btn").text("변경완료");
						$("#email_change_btn").prop('disabled', true);

						document.getElementById("rs_email").innerText = response.value;

					} else if (response.result === "F") {
						alert(response.message);
					} else {
						alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", error);
					alert("이메일 변경에 실패했습니다. 다시 시도해주세요.");
				}
			});
		}

		function js_email03(selectElement) {
			const email01 = document.getElementById('email01').value.trim();
			const email02 = document.getElementById('email02');
			const email03 = document.getElementById('email03').value.trim();
			const selectedValue = selectElement.value;

			$('#email02').val(selectedValue);
		}


		/*
		###############################################################
		# 비밀번호 관련
		###############################################################
		*/
		function js_pwd_change() {
			var bPwdChangeOK = confirm('비밀번호를 변경하시겠습니까?');

			if (bPwdChangeOK === true) {
				$("#pwd_rs_area").hide();
				$("#pwd_change_area").show();
				$("#pwd_chk_change_area").show();
			} else {
				$("#pwd_rs_area").show();
				$("#pwd_change_area").hide();
				$("#pwd_chk_change_area").hide();
			}
		}

		// 비밀번호 검증
		function js_pwd_chk() {
			const regPwd = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,15}$/;
			const password = document.getElementById("m_pwd").value.trim();
			const confirmPassword = document.getElementById("m_pwd_chk") ? document.getElementById("m_pwd_chk").value.trim() : '';
			const pwdMessage = document.getElementById("pwdMessage");
			const pwdChkMessage = document.getElementById("pwdChkMessage");

			// 비밀번호 입력값 검증
			if (!regPwd.test(password)) {
				if (pwdMessage.innerText !== "비밀번호는 최소 8자리 이상, 영문 대/소문자, 숫자, 특수문자를 포함해야 합니다.") {
					pwdMessage.innerText = "비밀번호는 최소 8자리 이상, 영문 대/소문자, 숫자, 특수문자를 포함해야 합니다.";
					pwdMessage.style.color = "red";
				}
				return false;
			} else {
				if (pwdMessage.innerText !== "사용가능한 비밀번호입니다.") {
					pwdMessage.innerText = "사용가능한 비밀번호입니다.";
					pwdMessage.style.color = "green";
				}
			}

			if (password === "" || confirmPassword === "") {
				pwdMessage.innerText = "";
				if (pwdChkMessage) pwdChkMessage.innerText = "";
				return false;
			}

			if (password === confirmPassword) {
				if (pwdChkMessage && pwdChkMessage.innerText !== "비밀번호가 일치합니다.") {
					pwdChkMessage.innerText = "비밀번호가 일치합니다.";
					pwdChkMessage.style.color = "green";
				}
				return true;
			} else {
				if (pwdChkMessage && pwdChkMessage.innerText !== "비밀번호가 불일치합니다. 다시 입력해주세요.") {
					pwdChkMessage.innerText = "비밀번호가 불일치합니다. 다시 입력해주세요.";
					pwdChkMessage.style.color = "red";
				}
				return false;
			}
		}


		function js_pwd_submit() {

			const m_pwd = document.getElementById('m_pwd').value.trim();
			const m_id = $("#m_id").val();
			const m_no = $("#m_no").val();

			const pwdMessage = document.getElementById("pwdMessage");
			const pwdChkMessage = document.getElementById("pwdChkMessage");


			if (!js_pwd_chk()) {
				alert("비밀번호를 다시 확인해주세요.");
				frm.m_pwd.focus();
				return;
			}

			// AJAX 요청
			$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode: "PWD_CHANGE",
					m_no: m_no,
					m_id: m_id,
					m_pwd: m_pwd
				},
				dataType: "json",
				success: function (response) {

					if (response.result === "T") {

						alert(response.message);

						$("#pwd_change_area").hide();
						$("#pwd_chk_change_area").hide();
						$("#pwd_rs_area").show();

					} else if (response.result === "F") {
						alert(response.message);
					} else if (response.result === "E"){
						pwdMessage.innerText = "이전과 다른 비밀번호를 입력해주세요.";
						pwdMessage.style.color = "red";
						pwdChkMessage.innerText = "";
						alert(response.message);
					}

					else {
						alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", error);
					alert(" 변경에 실패했습니다. 다시 시도해주세요.");
				}
			});

		}

		/*
		###############################################################
		# 회원 구분 관련
		###############################################################
		*/
		function validateMemberType() {
			const selectedGubun = document.querySelector('input[name="rd_m_gubun"]:checked');
			const hiddenInput = document.getElementById('m_gubun');
			const radioPersonal = document.getElementById('m_gubun_p');

			if (!selectedGubun) {
				alert("회원구분을 선택해주세요.");
				radioPersonal.focus();
				return false;
			}

			hiddenInput.value = selectedGubun.value;

			return true;
		}


		/*
		###############################################################
		# 사업자 등록 번호 및 회원명 관련
		###############################################################
		*/

		function formatBizNo(input) {
			const part1 = document.getElementById("biz_no1").value.replace(/[^0-9]/g, "");
			const part2 = document.getElementById("biz_no2").value.replace(/[^0-9]/g, "");
			const part3 = document.getElementById("biz_no3").value.replace(/[^0-9]/g, "");
			const formattedBizNo = `${part1}-${part2}-${part3}`;
			document.getElementById("m_biz_no").value = formattedBizNo;
		}

		// organ_rs_area

		function js_organ_change() {
			var bOrganChangeOK = confirm('기관명을 변경하시겠습니까?');

			if (bOrganChangeOK === true) {
				$("#organ_rs_area").hide();
				$("#organ_change_area").show();
			} else {
				$("#organ_rs_area").show();
				$("#organ_change_area").hide();
			}
		}

		function js_organ_submit() {
			const m_organ_name =document.getElementById("m_organ_name").value.trim();
			const m_id = $("#m_id").val();
			const m_no = $("#m_no").val();

						// AJAX 요청
						$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode: "ORGAN_CHANGE",
					m_no: m_no,
					m_id: m_id,
					m_organ_name: m_organ_name
				},
				dataType: "json",
				success: function (response) {

					if (response.result === "T") {

						alert(response.message);
						document.getElementById("rs_m_organ").innerText = response.value;
						$("#organ_rs_area").show();
						$("#organ_change_area").hide();

					} else if (response.result === "F") {
						alert(response.message);
					} else {
						alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", error);
					alert(" 변경에 실패했습니다. 다시 시도해주세요.");
				}
			});
		
		}
 

		function js_biz_change() {
			var bBizChangeOK = confirm('사업자 등록번호를 변경하시겠습니까?');

			if (bBizChangeOK === true) {
				$("#biz_rs_area").hide();
				$("#biz_change_area").show();
			} else {
				$("#biz_rs_area").show();
				$("#biz_change_area").hide();
			}
		}

		function js_biz_submit() {

			const m_biz_no = document.getElementById("m_biz_no").value;
			const m_id = $("#m_id").val();
			const m_no = $("#m_no").val();

			// AJAX 요청
			$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode: "BIZ_CHANGE",
					m_no: m_no,
					m_id: m_id,
					m_biz_no: m_biz_no
				},
				dataType: "json",
				success: function (response) {

					if (response.result === "T") {

						alert(response.message);
						document.getElementById("rs_biz_no").innerText = response.value;
						$("#biz_rs_area").show();
						$("#biz_change_area").hide();

					} else if (response.result === "F") {
						alert(response.message);
					} else {
						alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", error);
					alert(" 변경에 실패했습니다. 다시 시도해주세요.");
				}
			});

		}

		function js_name_change() {
			var bNameChangeOK = confirm('회원명을 변경하시겠습니까?');

			if (bNameChangeOK === true) {
				$("#name_rs_area").hide();
				$("#name_change_area").show();
			} else {
				$("#name_rs_area").show();
				$("#name_change_area").hide();
			}

		}

		function js_name_submit() {
			const m_name = document.getElementById("m_name").value;
			const m_id = $("#m_id").val();
			const m_no = $("#m_no").val();

						// AJAX 요청
			$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode: "NAME_CHANGE",
					m_no: m_no,
					m_id: m_id,
					m_name: m_name
				},
				dataType: "json",
				success: function (response) {

					if (response.result === "T") {

						alert(response.message);
						document.getElementById("rs_m_name").innerText = response.value;
						$("#name_rs_area").show();
						$("#name_change_area").hide();

					} else if (response.result === "F") {
						alert(response.message);
					} else {
						alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", error);
					alert(" 변경에 실패했습니다. 다시 시도해주세요.");
				}
			});



		}

		/*
		###############################################################
		# 주소 관련
		###############################################################
		*/

		function js_addr_change() {

			var bAddrChangeOK = confirm('주소를 변경하시겠습니까?');

			if (bAddrChangeOK === true) {
				$("#addr_rs_area").hide();
				$("#addr_change_area").show();
				$("#addr_change_btn").hide();
				//$("#phone_change_btn").prop('disabled', true);

			} else {
				$("#addr_rs_area").show();
				$("#addr_change_area").hide();
				$("#addr_change_btn").show();
				//$("#phone_change_btn").prop('disabled', false);
			}
		}

		function searchAddress() {
			new daum.Postcode({
				oncomplete: function (data) {
					const addr = data.address;
					const postCode = data.zonecode;
					document.getElementById('m_addr').value = addr;
					document.getElementById('m_post_cd').value = postCode;
				}
			}).open();
		}

		function js_addr_submit() {
			const m_id = $("#m_id").val();
			const m_no = $("#m_no").val();
			const m_addr = document.getElementById('m_addr').value.trim();
			const m_addr_detail = document.getElementById('m_addr_detail').value.trim();
			const m_post_cd = document.getElementById('m_post_cd').value.trim();

			// 유효성 검사
			if (!m_addr || !m_addr_detail || !m_post_cd) {
				alert("주소, 주소 상세, 우편번호를 모두 입력해주세요.");
				return;
			}
			// AJAX 요청
			$.ajax({
				type: "POST",
				url: "/_common/ajax_member_dml.php",
				data: {
					mode: "ADDR_CHANGE",
					m_no: m_no,
					m_id: m_id,
					m_addr: m_addr,
					m_addr_detail: m_addr_detail,
					m_post_cd: m_post_cd
				},
				dataType: "json",
				success: function (response) {
					if (response.result === "T") {
						alert(response.message);
						// document.getElementById("rs_addr_data").innerHTML =
						// "주소: " + response.value.addr + "<br>" +
						// "상세 주소: " + response.value.addr_detail + "<br>" +
						// "우편번호: " + response.value.post_cd;
						document.getElementById("rs_addr_data").innerHTML =
							response.value.addr + " " + response.value.addr_detail;

						$("#addr_rs_area").show();
						$("#addr_change_area").hide();
					} else if (response.result === "F") {
						alert(response.message);
					} else {
						alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", error);
					alert("주소 변경에 실패했습니다. 다시 시도해주세요.");
				}
			});

		}

	/*
	###############################################################
	# 표준 산업 분류 관련
	###############################################################
	*/
		function js_m_ksic(selectElement) {
			var ksic = selectElement.value;

			if (!ksic) {
				alert("1차 산업 분류를 선택해주세요.");
				return;
			}

			// AJAX 요청
			$.ajax({
				url: "/_common/ajax_member_dml.php",
				type: "POST",
				data: {
					mode: "KSIC_DETAIL", // 구분을 위한 값
					m_ksic: ksic
				},
				dataType: "json"
			})
				.done(function (response) {

					if (response.success) {
						const ksicDetailSelector = document.getElementById("ksic_detail_selector");

						$('#ksic_detail_selector').children('option:not(:first)').remove();

						response.data.forEach(function (m_ksic_detail) {
							var option = document.createElement("option");
							option.value = m_ksic_detail.DCODE_NM; // 코드 값 value
							option.textContent = m_ksic_detail.DCODE_NM; // 코드 이름 name
							ksicDetailSelector.appendChild(option);
						});

						const defaultOption = ksicDetailSelector.querySelector("option");

						if (defaultOption) {
							defaultOption.value = "";
							defaultOption.textContent = "2차 산업분류를 선택해주세요.";
                            defaultOption.disabled = true;
							defaultOption.setAttribute("data-placeholder", "true");
                            defaultOption.style.color = "gray";
                        }

					} else {
						alert("2차 산업 분류를 가져오는 중 오류가 발생했습니다: " + response.message);
					}
				})
				.fail(function (xhr, status, error) {
					console.error("AJAX 요청 실패: ", error);
					alert("2차 산업 분류를 가져오는 중 문제가 발생했습니다. 다시 시도해주세요.");
				});
		}


		function js_m_ksicByValue(m_ksic, m_ksic_detail) {

			var ksic = m_ksic;
			var ksic_detail = m_ksic_detail;

			if (!ksic) {
				alert("1차 산업 분류를 선택해주세요.");
				return;
			}

			// AJAX 요청
			$.ajax({
				url: "/_common/ajax_member_dml.php",
				type: "POST",
				data: {
					mode: "KSIC_DETAIL", // 구분을 위한 값
					m_ksic: ksic
				},
				dataType: "json"
			})
				.done(function (response) {

					if (response.success) {
						const ksicDetailSelector = document.getElementById("ksic_detail_selector");
						$('#ksic_detail_selector').children('option:not(:first)').remove();

						response.data.forEach(function (m_ksic_detail) {
							var option = document.createElement("option");
							option.value = m_ksic_detail.DCODE_NM; // 코드 값 value
							option.textContent = m_ksic_detail.DCODE_NM; // 코드 이름 name
							ksicDetailSelector.appendChild(option);
						});

					} else {
						alert("2차 산업 분류를 가져오는 중 오류가 발생했습니다: " + response.message);
					}
				})
				.fail(function (xhr, status, error) {
					console.error("AJAX 요청 실패: ", error);
					alert("2차 산업 분류를 가져오는 중 문제가 발생했습니다. 다시 시도해주세요.");
				});
		}


	document.addEventListener('DOMContentLoaded', function () {
		const emailTfY = document.getElementById('email_tf_y');
		const emailTfN = document.getElementById('email_tf_n');
		const messageTfY = document.getElementById('message_tf_y');
		const messageTfN = document.getElementById('message_tf_n');

		const previousState = {
			email: emailTfY.checked ? 'Y' : 'N',
			message: messageTfY.checked ? 'Y' : 'N'
		};

		function handleConfirm(event, message, group, currentValue) {
			const confirmResult = confirm(message);
			if (!confirmResult) {

				if (group === 'email') {
					emailTfY.checked = previousState.email === 'Y';
					emailTfN.checked = previousState.email === 'N';
				} else if (group === 'message') {
					messageTfY.checked = previousState.message === 'Y';
					messageTfN.checked = previousState.message === 'N';
				}
				event.preventDefault();
			} else {
				// 확인 시 상태 업데이트
				if (group === 'email') {
					previousState.email = currentValue;
				} else if (group === 'message') {
					previousState.message = currentValue;
				}
			}
		}

		emailTfY.addEventListener('change', function (event) {
			if (emailTfY.checked) {
				handleConfirm(event, '앞으로 다양한 WFI의 소식을 받을 수 있습니다.', 'email', 'Y');
			}
		});

		emailTfN.addEventListener('change', function (event) {
			if (emailTfN.checked) {
				handleConfirm(event, '이메일 수신 동의를 거부하시겠습니까? 앞으로 다양한 WFI의 소식을 받을 수 없게 됩니다.', 'email', 'N');
			}
		});

		messageTfY.addEventListener('change', function (event) {
			if (messageTfY.checked) {
				handleConfirm(event, '앞으로 다양한 WFI의 소식을 받을 수 있습니다.', 'message', 'Y');
			}
		});

		messageTfN.addEventListener('change', function (event) {
			if (messageTfN.checked) {
				handleConfirm(event, '문자 수신 동의를 거부하시겠습니까? 앞으로 다양한 WFI의 소식을 받을 수 없게 됩니다.', 'message', 'N');
			}
		});
	});


		/*
		###############################################################
		# frm-data submit 부분
		###############################################################
		*/

		function js_update() {

			var frm = document.frm;

			frm.mode.value = "U";

			if (frm.rd_m_gubun) {
				var selectedGubun = document.querySelector('input[name="rd_m_gubun"]:checked');
				if (selectedGubun) {
					frm.m_gubun.value = selectedGubun.value;
				}
			}

			// 이메일 수신 동의
			if (frm.rd_email_tf) {
				var selectedEmail = document.querySelector('input[name="rd_email_tf"]:checked');
				if (selectedEmail) {
					frm.email_tf.value = selectedEmail.value;
				}
			}

			// 문자 수신 동의
			if (frm.rd_message_tf) {
				var selectedMessage = document.querySelector('input[name="rd_message_tf"]:checked');
				if (selectedMessage) {
					frm.message_tf.value = selectedMessage.value;
				}
			}

			// 회원 구분 (필수)
			if (!validateMemberType()) {
				return false;
			}

			var ksic = document.getElementById('m_ksic').value;
			var ksic_detail = document.getElementById('ksic_detail_selector').value;

			if (!ksic) {
				alert("1차 산업 분류를 선택해주세요.");
				document.getElementById("m_ksic").focus();
				return;
			}

			if (!ksic_detail) {
				frm.m_ksic_detail.value = "<?= $user_data['M_KSIC_DETAIL'] ?>";
			}

			var bUpdateChangeOK = confirm('변경 내용을 저장하시겠습니까?');

			if (bUpdateChangeOK === true) {
				frm.target = "";
				frm.action = "mypage_modify.php";
				frm.submit();
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
			<!-- 게시판목록 페이지 -->
			<form name="frm" method="post" enctype="multipart/form-data">
				<input type="hidden" id="mode" name="mode" value="" />
				<input type="hidden" id="m_id" name="m_id" value="<?= $_SESSION['m_id'] ?>" />
				<input type="hidden" id="m_no" name="m_no" value="<?= $_SESSION['m_no'] ?>" />
				<div class="member-page">
					<!-- 타이틀 영역 -->
					<div class="title-wrap">
						<h2 class="title">나의 회원정보</h2>
					</div>
					<!-- // 타이틀 영역 -->
					<div class="mypage-wrap">
						<!-- 첫화면 -->
						<div class="list-cont">
							<div class="list-item">
								<div class="labels">
									아이디
								</div>
								<div class="value">
									<p class="bold"><?= $user_data['M_ID'] ?></p>
								</div>
							</div>

							<div class="list-item" id="phone_rs_area">
								<div class="labels">
									전화번호
								</div>
								<div class="value">
									<p id="rs_phone"><?= decrypt($key, $iv, $user_data['M_PHONE']); ?></p>
									<button type="button" id="phone_change_btn" class="btn-basic h-48"
										onclick="js_phone_change();">
										<span>변경하기</span>
									</button>
								</div>
							</div>

							<div class="list-item" id="phone_change_area">
								<div class="labels">
									새 전화번호
								</div>
								<div class="value">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" name="m_phone" id="m_phone" value="<?= decrypt($key, $iv, $user_data['M_PHONE']); ?>"
													placeholder="“-”없이 숫자만 입력해주세요​" title="" class="inp"
													oninput="formatPhone(this)" maxlength="11">
											</div>
											<button type="button" name="m_phone_rq" id="m_phone_rq"
												onclick="authPhoneRequest()" class="btn-basic h-48 black">
												<span>인증번호 받기</span>
											</button>
										</div>

										<div id="phone_auth_area" class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" name="auth_code" id="auth_code" placeholder="인증번호를 입력해주세요." title="" class="inp">
												<span class="red-time" id="setTimer" name="setTimer"></span>
												<input type="hidden" id="auth_status" name="auth_status" value="N">
											</div>
											<button type="button" class="btn-basic h-48 black" id="auth_phone_chk"
												name="auth_phone_chk" onclick="authPhoneCheck()">
												<span>인증하기</span>
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="list-item">
								<div class="labels">
									이메일
								</div>
								<div id="email_rs_area" class="value">
									<p id="rs_email"><?= decrypt($key, $iv, $user_data['M_EMAIL']); ?></p>
									<div>
										<button type="button" id="email_change_btn" class="btn-basic h-48"
											onclick="js_email_change();">
											<span>변경하기</span>
										</button>
									</div>
								</div>

								<!-- 변경하기 누르면 인풋 생성 -->
								<div id="email_change_area" class="value">
									<div class="info-inp-wrap">
										<div class="inp-wrap email">
											<div class="frm-inp h-48">
												<input type="text" name="email01" id="email01" placeholder="" title="" value="<?=$email01_value?>"
													class="inp">
											</div>
											@
											<div class="frm-inp h-48">
												<input type="text" name="email02" id="email02" placeholder="" title="" value="<?=$email02_value?>"
													class="inp">
											</div>
											<div class="frm-sel h-48">
												<?= makeSelectBoxOnChange2($conn, "EMAIL", "email03", "", "직접입력", "", $user_data['M_EMAIL']) ?>
											</div>
										</div>
									</div>
									<button type="button" id="email_submit_btn" class="btn-basic h-48 black"
										onclick="js_email_submit();">
										<span>저장하기</span>
									</button>
								</div>
								<!-- //변경하기 누르면 인풋 생성 -->
							</div>
							<div class="list-item">
								<div class="labels">
									비밀번호
								</div>
								<div id="pwd_rs_area" class="value">
									<p><?= maskPassword($user_data['M_PWD']) ?></p>
									<button type="button" class="btn-basic h-48" onclick="js_pwd_change();">
										<span>변경하기</span>
									</button>
								</div>

								<!-- 변경하기 누르면 인풋 생성 -->
								<div id="pwd_change_area" class="value">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="password" name="m_pwd" id="m_pwd"
													placeholder="비밀번호를 입력해주세요" title="" class="inp"
													oninput="js_pwd_chk()">
											</div>
										</div>
										<p class="info-txt" id="pwdMessage">최소 8자리 이상, 영문 대/소문자, 숫자, 특수문자(!, @, # 등)포함</p>
									</div>
								</div>
								<!-- //변경하기 누르면 인풋 생성 -->
							</div>
							<!-- 비밀번호 인풋 생성시 나타남 -->
							<div id="pwd_chk_change_area" class="list-item">
								<div class="labels">
									비밀번호 확인
								</div>
								<div class="value">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="password" name="m_pwd_chk" id="m_pwd_chk"
													oninput="js_pwd_chk();" placeholder="위와 동일한 비밀번호를 입력해주세요.​" title=""
													class="inp">
											</div>
										</div>
										<p class="info-txt" id="pwdChkMessage"></p>
									</div>
									<button type="button" id="pwd_submit_btn" class="btn-basic h-48 black" onclick="js_pwd_submit();">
										<span>저장하기</span>
									</button>
								</div>
							</div>

							<div class="list-item">
								<div class="labels">
									회원구분
								</div>
								<div class="value">
									<div class="inp-wrap">
										<div class="frm-rdo">
											<input type="radio" name="rd_m_gubun" id="m_gubun_p" value="P"
												<?= $user_data['M_GUBUN'] == 'P' ? 'checked' : '' ?>>
											<label for="m_gubun_p"><span>개인회원</span></label>
										</div>
										<div class="frm-rdo">
											<input type="radio" name="rd_m_gubun" id="m_gubun_c" value="C"
												<?= $user_data['M_GUBUN'] == 'C' ? 'checked' : '' ?>>
											<label for="m_gubun_c"><span>기업회원</span></label>
										</div>
										<input type="hidden" id="m_gubun" name="m_gubun"
											value="<?= $user_data['M_GUBUN'] ?>">
									</div>
								</div>
							</div>

							<div class="list-item" id="m_organ_area" <?= $user_data['M_GUBUN'] == 'P' ? 'style="display: none;"' : '' ?>>
								<div class="labels">
									기관명
								</div>
								<div class="value" id="organ_rs_area">
									<p id="rs_m_organ"><?= $user_data['M_ORGAN_NAME'] ?></p>
									<button type="button" id="organ_change_btn" class="btn-basic h-48"
										onclick="js_organ_change();">
										<span>변경하기</span>
									</button>
								</div>
								<!-- 변경하기 누르면 인풋 생성 -->
								<div class="value" id="organ_change_area">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" name="m_organ_name" id="m_organ_name" placeholder="기관명을 입력해주세요." title="" class="inp">
											</div>
										</div>
									</div>
									<button type="button" id="organ_submit_btn" class="btn-basic h-48 black" onclick="js_organ_submit();">
										<span>저장하기</span>
									</button>
								</div>
								<!-- //변경하기 누르면 인풋 생성 -->
							</div>

							<div class="list-item" id="biz_no_area" <?= $user_data['M_GUBUN'] == 'P' ? 'style="display: none;"' : '' ?>>
								<div class="labels">
									사업자등록번호
								</div>
								<div class="value" id="biz_rs_area">
									<p id="rs_biz_no"><?= $user_data['M_BIZ_NO'] ?></p>
									<button type="button" id="biz_change_btn" class="btn-basic h-48"
										onclick="js_biz_change();">
										<span>변경하기</span>
									</button>
								</div>
								<!-- 변경하기 누르면 인풋 생성 -->
								<div class="value" id="biz_change_area">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" name="biz_no1" id="biz_no1" maxlength="3"
													placeholder="" title="" class="inp" oninput="formatBizNo()">
											</div>
											-
											<div class="frm-inp h-48">
												<input type="text" name="biz_no2" id="biz_no2" maxlength="2"
													placeholder="" title="" class="inp" oninput="formatBizNo()">
											</div>
											-
											<div class="frm-inp h-48">
												<input type="text" name="biz_no3" id="biz_no3" maxlength="5"
													placeholder="" title="" class="inp" oninput="formatBizNo()">
											</div>
											<input type="hidden" name="m_biz_no" id="m_biz_no">
										</div>
									</div>
									<button type="button" id="biz_submit_btn" class="btn-basic h-48 black" onclick="js_biz_submit();">
										<span>저장하기</span>
									</button>
								</div>
								<!-- //변경하기 누르면 인풋 생성 -->
							</div>

							<div class="list-item" id="m_name_area" <?= $user_data['M_GUBUN'] == 'C' ? 'style="display: none;"' : '' ?>>
								<div class="labels">
									회원명
								</div>
								<div class="value" id="name_rs_area">
									<p id="rs_m_name"><?= $user_data['M_NAME'] ?></p>
									<button type="button" id="name_change_btn" class="btn-basic h-48"
										onclick="js_name_change();">
										<span>변경하기</span>
									</button>
								</div>
								<!-- 변경하기 누르면 인풋 생성 -->
								<div class="value" id="name_change_area">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" name="m_name" id="m_name" maxlength="6"
													placeholder="회원명을 입력해주세요." title="" class="inp">
											</div>
										</div>
									</div>
									<button type="button" id="name_submit_btn" class="btn-basic h-48 black" onclick="js_name_submit();">
										<span>저장하기</span>
									</button>
								</div>
								<!-- //변경하기 누르면 인풋 생성 -->
							</div>

							<div class="list-item">
								<div class="labels">
									회원가입날짜
								</div>
								<div class="value">
									<p><?= $user_data['REG_DATE'] ?></p>
								</div>
							</div>

							<div class="list-item">
								<div class="labels">
									주소
								</div>
								<div class="value align-end" id="addr_rs_area">
									<p id="rs_addr_data">
										<?= decrypt($key, $iv, $user_data['M_ADDR']); ?>
										<?= decrypt($key, $iv, $user_data['M_ADDR_DETAIL']); ?>
									</p>
									<button type="button" id="addr_change_btn" class="btn-basic h-48"
										onclick="js_addr_change();">
										<span>변경하기</span>
									</button>
								</div>
								<!-- 변경하기 누르면 인풋 생성 -->
								<div id="addr_change_area" class="value">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" name="m_addr"
													id="m_addr" value="<?= decrypt($key, $iv, $user_data['M_ADDR']); ?>" title="" class="inp" readonly>
											</div>
											<button type="button" name="addr_search" id="addr_search"
												onclick="searchAddress()" class="btn-basic h-48 black">
												<span>주소 검색</span>
											</button>
										</div>
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" placeholder="상세 주소를 입력하세요" name="m_addr_detail"
													id="m_addr_detail" value="<?= decrypt($key, $iv, $user_data['M_ADDR_DETAIL']); ?>" title="" class="inp">
											</div>
										</div>
										<div class="inp-wrap">
											<div class="frm-inp h-48">
												<input type="text" placeholder="우편번호" name="m_post_cd" id="m_post_cd"
												 value="<?= decrypt($key, $iv, $user_data['M_POST_CD']); ?>" title="" class="inp" readonly>
											</div>
										</div>
									</div>
									<button type="button" class="btn-basic h-48 black" onclick="js_addr_submit();">
										<span>저장하기</span>
									</button>
								</div>
								<!-- //변경하기 누르면 인풋 생성 -->
							</div>
							<div class="list-item">
								<div class="labels">
									표준산업분류
								</div>
								<div class="value">
									<div class="info-inp-wrap">
										<div class="inp-wrap">
											<div class="frm-sel h-48 w300">
												<?= makeSelectBoxOnChange2($conn, "INDUSTRY_CATEGORY", "m_ksic", "", "1차 산업분류 선택하세요", "", $user_data['M_KSIC']) ?>
											</div>
											<div class="frm-sel h-48 w232">
												<select id="ksic_detail_selector" name="m_ksic_detail" title="검색 구분"
													class="sel">
													<option value="">
													<?= $user_data['M_KSIC_DETAIL'];  ?>
													</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="list-item">
								<div class="labels">
									이메일 수신동의
								</div>
								<div class="value">
									<div class="inp-wrap">
										<div class="frm-rdo">
											<input type="radio" name="rd_email_tf" id="email_tf_y" value="Y"
												<?= $user_data['EMAIL_TF'] == 'Y' ? 'checked' : '' ?>>
											<label for="email_tf_y"><span>예</span></label>
										</div>
										<div class="frm-rdo">
											<input type="radio" name="rd_email_tf" id="email_tf_n" value="N"
												<?= $user_data['EMAIL_TF'] == 'N' ? 'checked' : '' ?>>
											<label for="email_tf_n"><span>아니요</span></label>
										</div>
										<input type="hidden" name="email_tf" value="<?= $user_data['EMAIL_TF'] ?>">
									</div>
								</div>
							</div>
							<div class="list-item">
								<div class="labels">
									문자 수신동의
								</div>
								<div class="value">
									<div class="inp-wrap">
										<div class="frm-rdo">
											<input type="radio" name="rd_message_tf" id="message_tf_y" value="Y"
												<?= $user_data['MESSAGE_TF'] == 'Y' ? 'checked' : '' ?>>
											<label for="message_tf_y"><span>예</span></label>
										</div>
										<div class="frm-rdo">
											<input type="radio" name="rd_message_tf" id="message_tf_n" value="N"
												<?= $user_data['MESSAGE_TF'] == 'N' ? 'checked' : '' ?>>
											<label for="message_tf_n"><span>아니요</span></label>
										</div>
										<input type="hidden" name="message_tf" value="<?= $user_data['MESSAGE_TF'] ?>">
									</div>
								</div>
							</div>
							<div class="btn-wrap">
								<button type="button" id="update_btn" name="update_btn"
									class="btn-basic h-56 primary center-w360" onclick="js_update();" disabled>
									<span>변경 내용 저장</span>
								</button>
							</div>
						</div>
						<!-- //첫화면 -->
					</div>
				</div>
			</form>
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
	#====================================================================
	# layout header
	#====================================================================
	require "../layout/include_footer.php";
	?>
</footer>
<!-- // include_footer.html -->