<?session_start();?>
<?

	$_PAGE_NO = "103";
	require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";

	require "../_classes/biz/member/member.php";

	#====================================================================
	# DML Process
	#====================================================================
	# 테이블 - TBL_MEMBER
	$m_no =                     isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
	$m_id =                     isset($_POST["m_id"]) && $_POST["m_id"] !== '' ? $_POST["m_id"] : (isset($_GET["m_id"]) ? $_GET["m_id"] : '');
	$m_name =                   isset($_POST["m_name"]) && $_POST["m_name"] !== '' ? $_POST["m_name"] : (isset($_GET["m_name"]) ? $_GET["m_name"] : '');
	$m_organ_name =             isset($_POST["m_organ_name"]) && $_POST["m_organ_name"] !== '' ? $_POST["m_organ_name"] : (isset($_GET["m_organ_name"]) ? $_GET["m_organ_name"] : '');
	$m_pwd =                    isset($_POST["m_pwd"]) && $_POST["m_pwd"] !== '' ? $_POST["m_pwd"] : (isset($_GET["m_pwd"]) ? $_GET["m_pwd"] : '');
	$m_phone =                  isset($_POST["m_phone"]) && $_POST["m_phone"] !== '' ? $_POST["m_phone"] : (isset($_GET["m_phone"]) ? $_GET["m_phone"] : '');
	$m_email =                  isset($_POST["m_email"]) && $_POST["m_email"] !== '' ? $_POST["m_email"] : (isset($_GET["m_email"]) ? $_GET["m_email"] : '');
	$m_gubun =                  isset($_POST["m_gubun"]) && $_POST["m_gubun"] !== '' ? $_POST["m_gubun"] : (isset($_GET["m_gubun"]) ? $_GET["m_gubun"] : '');
	$m_addr =                   isset($_POST["m_addr"]) && $_POST["m_addr"] !== '' ? $_POST["m_addr"] : (isset($_GET["m_addr"]) ? $_GET["m_addr"] : '');
	$m_post_cd =                isset($_POST["m_post_cd"]) && $_POST["m_post_cd"] !== '' ? $_POST["m_post_cd"] : (isset($_GET["m_post_cd"]) ? $_GET["m_post_cd"] : '');
	$m_addr_detail =            isset($_POST["m_addr_detail"]) && $_POST["m_addr_detail"] !== '' ? $_POST["m_addr_detail"] : (isset($_GET["m_addr_detail"]) ? $_GET["m_addr_detail"] : '');
	$m_biz_no =                 isset($_POST["m_biz_no"]) && $_POST["m_biz_no"] !== '' ? $_POST["m_biz_no"] : (isset($_GET["m_biz_no"]) ? $_GET["m_biz_no"] : '');
	$m_ksic =                   isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');
	$m_ksic_detail =            isset($_POST["m_ksic_detail"]) && $_POST["m_ksic_detail"] !== '' ? $_POST["m_ksic_detail"] : (isset($_GET["m_ksic_detail"]) ? $_GET["m_ksic_detail"] : '');
	$email_tf =                 isset($_POST["email_tf"]) && $_POST["email_tf"] !== '' ? $_POST["email_tf"] : (isset($_GET["email_tf"]) ? $_GET["email_tf"] : '');
	$message_tf =               isset($_POST["message_tf"]) && $_POST["message_tf"] !== '' ? $_POST["message_tf"] : (isset($_GET["message_tf"]) ? $_GET["message_tf"] : '');
	# =====================================================================

	# 공통 컬럼 정의
	$mode     =                   isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$use_tf   =                   isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
	$reg_date =                   isset($_POST["reg_date"]) && $_POST["reg_date"] !== '' ? $_POST["reg_date"] : (isset($_GET["reg_date"]) ? $_GET["reg_date"] : '');

	# 페이징 관련
	$nPage    =                   isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
	$nPageSize =                isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

	# 검색 관련
	$search_field =             isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
	$search_str   =             isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

	# 검색 필드 세팅 (회원구분, 산업분류, 이메일수신동의, 문자수신동의)
	$m_gubun    =                SetStringToDB($m_gubun);
	$email_tf   =                SetStringToDB($email_tf);
	$m_ksic     =                SetStringToDB($m_ksic);

	$result = false;

	$mode                 = SetStringToDB($mode);
	$nPage                = SetStringToDB($nPage);
	$nPageSize            = SetStringToDB($nPageSize);
	$nPage                = trim($nPage);
	$nPageSize            = trim($nPageSize);

	$search_field         = SetStringToDB($search_field);
	$search_str           = SetStringToDB($search_str);
	$search_field         = trim($search_field);
	$search_str           = trim($search_str);


	$m_no                 = SetStringToDB($m_no);
	$use_tf               = SetStringToDB($use_tf);
	$del_tf = "N";

	#============================================================
	# 등록일 경우

	if ($mode == 'I') {
		$m_no =                     SetStringToDB($m_no);
		$m_id =                     SetStringToDB($m_id);
		$m_name =					SetStringToDB($m_name);
		$m_organ_name =             SetStringToDB($m_organ_name);
		$m_pwd =                    SetStringToDB($m_pwd);
		$m_phone =                  SetStringToDB($m_phone);
		$m_email =                  SetStringToDB($m_email);
		$m_gubun =                  SetStringToDB($m_gubun);
		$m_addr =                   SetStringToDB($m_addr);
		$m_post_cd =                SetStringToDB($m_post_cd);
		$m_addr_detail =            SetStringToDB($m_addr_detail);
		$m_biz_no =                 SetStringToDB($m_biz_no);
		$m_ksic =                   SetStringToDB($m_ksic);
		$m_ksic_detail =			SetStringToDB($m_ksic_detail);
		$email_tf =                 SetStringToDB($email_tf);
		$message_tf =               SetStringToDB($message_tf);

		$reg_date       	= SetStringToDB($reg_date);
		$use_tf            	= SetStringToDB($use_tf);
		$search_field   = SetStringToDB($search_field);
		$search_str        = SetStringToDB($search_str);

		//$result_flag = 0;

		$passwd_enc 	    = encrypt($key, $iv, $m_pwd);
		$phone_enc 		    = encrypt($key, $iv, $m_phone);
		$email_enc		    = encrypt($key, $iv, $m_email);
		$addr_enc   		= encrypt($key, $iv, $m_addr);
		$addr_detail_enc    = encrypt($key, $iv, $m_addr_detail);
		$post_cd_enc		= encrypt($key, $iv, $m_post_cd);

		$arr_data = array(
			"M_ID" 			=> $m_id,
			"M_NAME"        => $m_name,
			"M_ORGAN_NAME"  => $m_organ_name,
			"M_PWD" 		=> $passwd_enc,
			"M_PHONE" 		=> $phone_enc,
			"M_EMAIL" 		=> $email_enc,
			"M_GUBUN" 		=> $m_gubun,
			"M_ADDR" 		=> $addr_enc,
			"M_POST_CD" 	=> $post_cd_enc,
			"M_ADDR_DETAIL" => $addr_detail_enc,
			"M_BIZ_NO" 		=> $m_biz_no,
			"M_KSIC" 		=> $m_ksic,
			"M_KSIC_DETAIL" => $m_ksic_detail,
			"EMAIL_TF" => $email_tf,
			"MESSAGE_TF" => $message_tf,
			// "USE_TF" => $use_tf,
			//"REG_ADM" => $_SESSION['m_id']
		);
		$result =  insertMember($conn, $arr_data);
	}

	// Response Data
	$rs_m_no = isset($m_no) && $m_no !== '' ? $m_no : '';
	$rs_m_id = isset($m_id) && $m_id !== '' ? $m_id : '';
	$rs_m_organ_name = isset($m_organ_name) && $m_organ_name !== '' ? $m_organ_name : '';
	$rs_m_name = isset($m_name) && $m_name !== '' ? $m_name : '';
	$rs_m_pwd = isset($m_pwd) && $m_pwd !== '' ? $m_pwd : '';
	$rs_m_phone = isset($m_phone) && $m_phone !== '' ? $m_phone : '';
	$rs_m_email = isset($m_email) && $m_email !== '' ? $m_email : '';
	$rs_m_gubun = isset($m_gubun) && $m_gubun !== '' ? $m_gubun : '';
	$rs_m_addr = isset($m_addr) && $m_addr !== '' ? $m_addr : '';
	$rs_m_post_cd = isset($m_post_cd) && $m_post_cd !== '' ? $m_post_cd : '';
	$rs_m_addr_detail = isset($m_addr_detail) && $m_addr_detail !== '' ? $m_addr_detail : '';
	$rs_m_biz_no = isset($m_biz_no) && $m_biz_no !== '' ? $m_biz_no : '';
	$rs_m_ksic = isset($m_ksic) && $m_ksic !== '' ? $m_ksic : '';
	$rs_m_ksic_detail = isset($m_ksic_detail) && $m_ksic_detail !== '' ? $m_ksic_detail : '';
	$rs_email_tf = isset($email_tf) && $email_tf !== '' ? $email_tf : '';
	$rs_message_tf = isset($message_tf) && $message_tf !== '' ? $message_tf : '';

	if ($result) {
		$strParam = $strParam . "?nPage=" . $nPage . "&nPageSize=" . $nPageSize . "&search_field=" . $search_field . "&search_str=" . $search_str;
	?>
    <!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $g_charset ?>" />
        <script language="javascript">
            alert('회원가입이 완료되었습니다.');
            document.location.href = "login.do<?= $strParam ?>";
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

$(document).ready(function() {

		$("#biz_no_area").hide();
		$("#m_name_area").hide();
		$("#m_organ_name_area").hide();
		$("#phone_auth_area").hide();

		// 기업회원일 경우 사업자등록번호 노출
		$("input[name='rd_m_gubun']").on("change", function() {
			if ($("#m_gubun_c").is(":checked")) {
				$("#biz_no_area").show();
				$("#m_organ_name_area").show();
				$("#m_name_area").hide();
			} else {
				$("#biz_no_area").hide();
				$("#m_organ_name_area").hide();
				$("#m_name_area").show();
			}
		});

		var m_no = "<?= (int)$m_no ?>";

		$("input, select, checkbox").on("input change", function () {
			validateForm(); // 폼 검증 함수 호출
		});

		// 폼 검증 함수
		function validateForm() {
			var isValid = true;

			// 필수 입력값 확인
			$("input[required]").each(function () {
				if ($(this).val().trim() === "") {
					isValid = false;
				}
			});
			// 체크박스 확인
			if (!$("#touTf").is(":checked")) {
				isValid = false;
			}

			// 버튼 활성화/비활성화
			$("#joinButton").prop("disabled", !isValid);

				return isValid;
		}
	});


	function curl($url, $fields = array(), $headers = array())
	{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$response = curl_exec($ch);
			curl_close($ch);

			return $response;
	}

	/*
	###############################################################
	# 아이디 관련
	###############################################################
	*/

	// 아이디 중복체크
	let isDuplicateChecked = false;

	function js_idDupChk() {
		var m_id = document.getElementById("m_id").value.trim();
		var idRegex = /^(?=.*[a-zA-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

		if (m_id == '' && m_id != null) {
			alert("중복 체크할 아이디를 입력해주세요.");
			document.getElementById('m_id').focus();
			return false;
		}

		// if (!idRegex.test(m_id)) {
		//     alert("아이디는 영문과 숫자를 조합하여 최소 8자 이상이어야 합니다.");
		//     document.getElementById('m_id').focus();
		//     return false;
		// }

		let idMessage = document.getElementById('idMessage');

		if (!idRegex.test(m_id)) {
			idMessage.innerText = "아이디는 영문과 숫자를 조합하여 최소 8자 이상이어야 합니다.";
			idMessage.style.color = "red";
			return false;
		} else {
			idMessage.innerText = "";
		}

		// AJAX 요청
		$.ajax({
			type: "POST",
			url: "/_common/ajax_member_dml.php",
			data: {
				mode: "CHK_ID",
				m_id: m_id
			},
			dataType: "json",
			success: function(response) {
				if (response.result === "T") {
					alert(response.message);
					isDuplicateChecked = true;
					$("#m_id").prop('disabled', true);
					$("#id_dup_chk").prop('disabled', true);
					$("#id_dup_chk").text('중복체크 완료');					
				} else if (response.result === "F") {
					alert(response.message);
					isDuplicateChecked = false;
				} else {
					alert("서버와의 통신에 문제가 발생했습니다. 다시 시도해주세요.");
					isDuplicateChecked = false;
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error: ", error);
				alert("아이디 중복 체크에 실패했습니다. 다시 시도해주세요.");
				isDuplicateChecked = false;
			}
		});
	}

	/*
	###############################################################
	# 비밀번호 관련
	###############################################################
	*/

	// 비밀번호 검증
	function js_pwd_chk() {
		const regPwd = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,15}$/;

		const password = document.getElementById("m_pwd").value.trim();
		const confirmPassword = document.getElementById("m_pwd_chk").value.trim();
		const pwdMessage = document.getElementById("pwdMessage");
		const pwdChkMessage = document.getElementById("pwdChkMessage");

		// 비밀번호 입력값 검증
		if (!regPwd.test(password)) {
				pwdMessage.innerText = "비밀번호는 최소 8자리 이상, 영문 대/소문자, 숫자, 특수문자를 포함해야 합니다.";
				pwdMessage.style.color = "red";

				return false;
		} else {
				pwdMessage.innerText = "사용가능한 비밀번호입니다.";
				pwdMessage.style.color = "green";
		}

		if (password === "" || confirmPassword === "") {
			pwdMessage.innerText = "";
			pwdChkMessage.innerText = "";
			return false;
		}

		if (password === confirmPassword) {
			pwdChkMessage.innerText = "비밀번호가 일치합니다.";
			pwdChkMessage.style.color = "green";
			return true;
		} else {
			pwdChkMessage.innerText = "비밀번호가 불일치합니다. 다시 입력해주세요.";
			pwdChkMessage.style.color = "red";
			return false;
		}
  }

	/*
	###############################################################
	# 전화번호 관련
	###############################################################
	*/
	function formatPhone(input) {

		var phone = input.value.replace(/[^0-9]/g, "");

		if (phone.length > 11) {
			phone = phone.substring(0, 11);
		}
			input.value = phone;
		}

	function authPhoneRequest() {

		var mPhone = document.getElementById('m_phone').value;

		if (mPhone == '' && mPhone != null) {
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
				sms_flag : "2",
				m_phone: mPhone
			},
			dataType: "json",
			success: function(response) {
				console.log("✅ 성공 응답 데이터:", response);

			if (response.success) {
				$("#phone_auth_area").show();
				alert(`인증번호가 발송되었습니다`);
				startTimer(180, "#setTimer");
				$("#m_phone_rq").prop('disabled', true);
				
			} else {
				stopTimer();
				alert("인증번호 생성 중 오류가 발생했습니다.");
				}
			},
			error: function(xhr, status, error) {
				alert("AJAX 요청 중 오류 발생: " + error);
			}
		});
}
	var timerInterval;

	// 3분 타이머 함수
	function startTimer(duration, selector) {
		let timer = duration; // 타이머 초기화
		const element = document.querySelector(selector);

		timerInterval  = setInterval(() => {
				const minutes = Math.floor(timer / 60);
				const seconds = timer % 60;

				// 2자리 숫자로 포맷
				const formattedTime = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
				element.textContent = formattedTime;

				if (timer === 0) {
						clearInterval(interval); // 타이머 중지
						element.textContent = "인증 시간이 만료되었습니다.";
				} else {
						timer--; // 타이머 감소
				}
		}, 1000); // 1초마다 실행
	}

	function stopTimer() {
		if (timerInterval) {
			clearInterval(timerInterval);
			timerInterval = null;
			document.querySelector("#setTimer").textContent = "";
		}
	}

	function authPhoneCheck() {
		const authCode = document.getElementById('auth_code').value;

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
				$("#m_phone").prop('disabled', true);
				$("#m_phone_rq").prop('disabled', true);
				$("#auth_phone_chk").prop('disabled', true);
				$("#auth_code").prop('disabled', true);
				$('#setTimer').hide();
				$("#auth_status").val("Y");
            } else {
				stopTimer();
				$("#m_phone_rq").prop('disabled', false);
				$('#setTimer').val("");
				$("#auth_code").val("");
                alert("인증번호가 올바르지 않습니다.");
            }
        },
        error: function (xhr, status, error) {
            alert("AJAX 요청 중 오류 발생: " + error);
        },
    });
	}

	// 휴대폰 인증 검증 함수
	function validatePhoneAuth() {
			const mPhone = document.getElementById('m_phone').value.trim();
			const authChecked = document.getElementById('auth_status').value; // 인증 상태 (hidden 필드)

			if (!mPhone) {
					alert("휴대폰 번호를 입력해주세요.");
					document.getElementById('m_phone').focus();
					return false;
			}

			if (!/^\d{11}$/.test(mPhone)) {
					alert("휴대폰 번호는 숫자만 11자리여야 합니다. (예: 01012345678)");
					document.getElementById('m_phone').focus();
					return false;
			}

			if (authChecked !== "Y") {
					alert("휴대폰 인증을 완료해주세요.");
					document.getElementById('m_phone').focus();
					return false;
			}

			return true;
	}

	/*
	###############################################################
	# 이메일 관련
	###############################################################
	*/

	function js_email03(selectElement) {
		const email01 = document.getElementById('email01').value.trim();
		const email02 = document.getElementById('email02');
		const email03 = document.getElementById('email03').value.trim();
		const selectedValue = selectElement.value;

		$('#email02').val(selectedValue);
	}

	/*
	###############################################################
	# 회원 구분 검증 및 사업자 등록번호 관련
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

	function formatBizNo(input) {
		const part1 = document.getElementById("biz_no1").value.replace(/[^0-9]/g, "");
		const part2 = document.getElementById("biz_no2").value.replace(/[^0-9]/g, "");
		const part3 = document.getElementById("biz_no3").value.replace(/[^0-9]/g, "");
		const formattedBizNo = `${part1}-${part2}-${part3}`;
		document.getElementById("m_biz_no").value = formattedBizNo;

	}

	/*
	###############################################################
	# 주소 관련
	###############################################################
	*/

	function searchAddress() {
		new daum.Postcode({
			oncomplete: function(data) {
				const addr = data.address;
				const postCode = data.zonecode;
				document.getElementById('m_addr').value = addr;
				document.getElementById('m_post_cd').value = postCode;
			}
		}).open();
	}

	// 주소 유효성 검증 함수
	function validateAddressForm() {
		var mAddr = document.getElementById('m_addr').value.trim();
		var mAddrDetail = document.getElementById('m_addr_detail').value.trim();
		var mPostCd = document.getElementById('m_post_cd').value.trim();

		if (!mAddr) {
			alert("주소검색을 통해 주소를 검색해주세요.");
			document.getElementById('m_addr').focus();
			return false;
		}

		if (!mAddrDetail) {
			alert("상세 주소를 입력하세요.");
			document.getElementById('m_addr_detail').focus();
			return false;
		}

		if (!mPostCd) {
			alert("주소검색을 통해 주소를 검색해주세요.");
			document.getElementById('m_post_cd').focus();
			return false;
		}

		return true;
	}

	/*
	###############################################################
	# 표준 산업 코드 관련
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
		.done(function(response) {

			if (response.success) {
				const ksicDetailSelector = document.getElementById("ksic_detail_selector");
				$('#ksic_detail_selector').children('option:not(:first)').remove();

				response.data.forEach(function(m_ksic_detail) {
					var option = document.createElement("option");
					option.value = m_ksic_detail.DCODE_NM; // 코드 값 value
					option.textContent = m_ksic_detail.DCODE_NM; // 코드 이름 name
					ksicDetailSelector.appendChild(option);
				});

			} else {
				alert("2차 산업 분류를 가져오는 중 오류가 발생했습니다: " + response.message);
			}
		})
		.fail(function(xhr, status, error) {
			console.error("AJAX 요청 실패: ", error);
			alert("2차 산업 분류를 가져오는 중 문제가 발생했습니다. 다시 시도해주세요.");
		});
	}

	/*
	###############################################################
	# 이메일 수신여부 및 문자 수신여부 검증 관련
	###############################################################
	*/

	function validateEmailandMessageType() {

    const selectedEmailType = document.querySelector('input[name="rd_email_tf"]:checked');
		const selectedMessageType = document.querySelector('input[name="rd_message_tf"]:checked');

    const emailHiddenInput = document.getElementById('email_tf');
    const emailFocusPersonal = document.getElementById('email_tf_Y');

    const messageHiddenInput = document.getElementById('message_tf');
    const messageFocusPersonal = document.getElementById('message_tf_Y');

    if (!selectedEmailType) {
        alert("이메일 수신여부를 선택해주세요.");
        emailFocusPersonal.focus();
        return false;
    }

		if (!selectedMessageType) {
        alert("문자 수신여부를 선택해주세요.");
        messageFocusPersonal.focus();
        return false;
    }

    emailHiddenInput.value = selectedEmailType.value;
		messageHiddenInput.value = selectedMessageType.value;


    return true;
	}

	/*
	###############################################################
	# 폼데이터 submit 관련
	###############################################################
	*/

	function js_save() {
		var frm = document.frm;
		var m_phone = frm.m_phone.value.trim();

		if (!isDuplicateChecked) {
			alert("아이디 중복 체크를 진행해주세요.");
			document.getElementById('m_id').focus();
			return;
		}

		frm.mode.value = "I";
		// 휴대폰 번호 검증 및 인증 여부 확인
		if (!validatePhoneAuth()) {
    	return;
    }


		const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
		// 이메일 조합 검증
		if (frm.email01.value == "") {
			alert('이메일(앞)을 입력해주세요.');
			frm.email01.focus();
			return ;
		}

		if (frm.email02.value == "") {
			alert('이메일(뒤)을 입력해주세요.');
			frm.email02.focus();
			return ;
		}

		frm.m_email.value = frm.email01.value+"@"+frm.email02.value;

				// 이메일 형식 검증
		if (!emailRegex.test(frm.m_email.value)) {
			alert("유효한 이메일 주소를 입력해주세요.");
			frm.email01.focus();
			return;
		}

		if (!js_pwd_chk()) {
			alert("비밀번호를 다시 확인해주세요.");
			frm.m_pwd.focus();
			return;
		}

		// 회원 구분 (필수)
    if (!validateMemberType()) {
        return false;
    }

	if ($("#m_gubun_p").is(":checked")) {
		var mName = $("#m_name").val().trim();

		// 개인 회원 선택시 회원명 검증
		if(!mName) {
			alert("회원 이름을 입력해주세요.");
			$("#m_name").focus();
			return false;
		}

	}

	if ($("#m_gubun_c").is(":checked")) {
		var bizNo1 = $("#biz_no1").val().trim();
		var bizNo2 = $("#biz_no2").val().trim();
		var bizNo3 = $("#biz_no3").val().trim();
		var organName = $("#m_organ_name").val().trim();

		//기업회원 체크시 기관명 검증
		if(!organName) {
			alert("기관명을 입력해주세요.");
			$("#m_organ_name").focus();
			return false;
		}

		// 사업자 등록번호 필드 검증
		if (!bizNo1 || !bizNo2 || !bizNo3) {
			alert("사업자 등록번호를 모두 입력해주세요.");
			if (!bizNo1) {
				$("#biz_no1").focus();
			} else if (!bizNo2) {
				$("#biz_no2").focus();
			} else if (!bizNo3) {
				$("#biz_no3").focus();
			}
			return false;
		}

		// 사업자 등록번호 자릿수 검증
		if (bizNo1.length !== 3) {
			alert("사업자 등록번호의 첫 번째 자리는 3자리여야 합니다.");
			$("#biz_no1").focus();
			return false;
		}
		if (bizNo2.length !== 2) {
			alert("사업자 등록번호의 두 번째 자리는 2자리여야 합니다.");
			$("#biz_no2").focus();
			return false;
		}
		if (bizNo3.length !== 5) {
			alert("사업자 등록번호의 세 번째 자리는 5자리여야 합니다.");
			$("#biz_no3").focus();
			return false;
		}
	}


		// 주소 유효성 검증
		if (!validateAddressForm()) {
			return;
		}

		// 표준 산업 분류 검증
		var ksic = document.getElementById('m_ksic').value;
		var ksic_detail = document.getElementById('ksic_detail_selector').value;

		if (!ksic) {
			alert("1차 산업 분류를 선택해주세요.");
			document.getElementById("m_ksic").focus();
			return;
		}

		if (!ksic_detail) {
			alert("2차 산업 분류를 선택해주세요.");
			document.getElementById("ksic_detail_selector").focus();
			return;
		}

		// 이메일 및 문자 수신 여부 (필수)
		if (!validateEmailandMessageType()) {
			return false;
    }

		// 필수 체크박스 (이용약관 동의) 검증
		var touTf = document.getElementById('touTf');
		if (!touTf.checked) {
			alert("이용약관에 동의하셔야 합니다.");
			touTf.focus();
			return false;
		}

		var formattedPhone = m_phone.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
		frm.m_phone.value = formattedPhone;

		frm.target = "";
		frm.action = "member_form.do";
		frm.submit();
	}
</script>
<style>
	.partition ul.bul.dep2 {
		list-style: decimal inside; /* 번호 스타일 설정 */
		padding-left: 20px; /* 왼쪽 여백 */
	}

	.partition ul.bul.dep2 li {
		text-indent: -20px; /* 번호를 드려쓰기 */
		margin-left: 20px; /* 본문 위치 조정 */
	}
</style>

</head>
<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content member-form-page">
			<!-- content-header -->
			<div class="content-header">

			</div>
			<!-- // content-header -->
			<!-- content-body -->

				<div class="content-body">
					<!-- 페이지 유형 예시 (페이지 유형에 따라 타이틀 영역의 예외처리 대응) -->
					<div class="member-page">
						<!-- 타이틀 영역 -->
						<div class="title-wrap">
							<h2 class="title">회원가입</h2>
						</div>
						<!-- // 타이틀 영역 -->

						<!-- 게시판목록 영역 -->
						<div class="join-wrap">
							<form class="join-form" name="frm" method="post" enctype="multipart/form-data">
								<input type="hidden" name="seq_no" value="" />
								<input type="hidden" name="mode" value="" />
								<input type="hidden" name="menu_cd" value="">
								<input type="hidden" name="m_no" value="<?= (int)$m_no ?>" />
								<p class="req">필수입력사항</p>
								<div class="field-wrap">
									<div class="field">
										<div class="labels">
											<label for="m_id" class="txt require">아이디</label><!-- 필수입력 시 : require -->
										</div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_id" id="m_id" placeholder="아이디를 입력해주세요" title="" class="inp">
												</div>
												<button type="button" name="id_dup_chk" id="id_dup_chk" class="btn-basic h-48 black mo-w100" onclick="js_idDupChk()">
													<span>아이디 중복확인</span>
												</button>
											</div>
											<p id="idMessage" class="info-txt">영문과 숫자를 조합하여 8자 이상으로 입력해주세요​</p>
										</div>
									</div>

									<div class="field">
										<div class="labels">
											<label for="m_phone" class="txt require">전화번호</label><!-- 필수입력 시 : require -->
										</div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_phone" id="m_phone" placeholder="“-”없이 숫자만 입력해주세요​" title="" class="inp" oninput="formatPhone(this)" maxlength="11">
												</div>
												<button type="button" name="m_phone_rq" id="m_phone_rq" onclick="authPhoneRequest()" class="btn-basic h-48 black">
													<span>인증번호 받기</span>
												</button>
											</div>

											<div class="inp-wrap" id="phone_auth_area">
												<div class="frm-inp h-48">
													<input type="text" name="auth_code" id="auth_code" placeholder="인증번호를 입력해주세요." title="" class="inp">
													<span class="red-time" id="setTimer" name="setTimer"></span>
													<input type="hidden" id="auth_status" name="auth_status" value="N">
												</div>
												<button type="button" class="btn-basic h-48 black" id="auth_phone_chk" name="auth_phone_chk" onclick="authPhoneCheck()" >
													<span>인증번호 확인</span>
												</button>
											</div>
										</div>
									</div>

									<div class="field">
										<div class="labels"><label for="email01" class="txt require">이메일</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap email">
												<div class="frm-inp h-48">
													<input type="text" name="email01" id="email01" placeholder="" title="" class="inp" title="이메일 아이디">
												</div>
												@
												<div class="frm-inp h-48">
													<input type="text" name="email02" id="email02" placeholder="" title="" class="inp" title="이멩리 도메인">
												</div>
												<div class="frm-sel h-48" title="이메일 도메인 목록">
													<?= makeSelectBoxOnChange2($conn, "EMAIL", "email03", "", "직접입력", "", $rs_m_email) ?>
													<!-- <select name="" title="검색 구분" class="sel">
														<option value="" data-placeholder="true">직접입력</option>
														<option value="">콤보상자1</option>
														<option value="">콤보상자2</option>
													</select> -->
												</div>
												<input type="hidden" id="m_email" name="m_email">
											</div>
										</div>
									</div>
									<div class="field">
										<div class="labels"><label for="m_pwd" class="txt require">비밀번호</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="password" name="m_pwd" id="m_pwd" placeholder="비밀번호를 입력해주세요" title="" class="inp" oninput="js_pwd_chk()">
												</div>
											</div>
											<p class="info-txt" id="pwdMessage">최소 8자리 이상, 영문 대/소문자, 숫자, 특수문자(!, @, # 등) 포함​​</p>
										</div>
									</div>
									<div class="field">
										<div class="labels"><label for="m_pwd_chk" class="txt require">비밀번호 확인</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="password" name="m_pwd_chk" id="m_pwd_chk" oninput="js_pwd_chk()" placeholder="위와 동일한 비밀번호를 입력해주세요.​" title="" class="inp">
												</div>
											</div>
											<p class="info-txt" id="pwdChkMessage">​​</p>
										</div>
									</div>
									<div class="field">
										<div class="labels"><span class="txt require">회원구분</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-rdo">
													<input type="radio" name="rd_m_gubun" id="m_gubun_p" value="P">
													<label for="m_gubun_p"><span>개인회원</span></label>
												</div>
												<div class="frm-rdo">
													<input type="radio" name="rd_m_gubun" id="m_gubun_c" value="C">
													<label for="m_gubun_c"><span>기업회원</span></label>
												</div>
												<input type="hidden" id="m_gubun" name="m_gubun" value="<?= $rs_m_gubun ?>">
											</div>
										</div>
									</div>

									<div id="m_name_area" class="field">
										<div class="labels"><label for="m_name" class="txt require">회원명</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_name" id="m_name" maxlength="5" placeholder="회원이름을 입력해주세요." title="" class="inp" title="회원명">
												</div>
											</div>
										</div>
									</div>

									<div id="m_organ_name_area" class="field">
										<div class="labels"><label for="m_organ_name" class="txt require">기관명</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_organ_name" id="m_organ_name"  placeholder="기관명을 입력해주세요." title="" class="inp" title="기관명">
												</div>
											</div>
										</div>
									</div>

									<div id="biz_no_area" class="field">
										<div class="labels"><label for="biz_no1" class="txt require">사업자등록번호</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="biz_no1" id="biz_no1" maxlength="3" placeholder="" title="" class="inp" oninput="formatBizNo()" title="첫 번째 블록 3자리">
												</div>
												-
												<div class="frm-inp h-48">
													<input type="text" name="biz_no2" id="biz_no2" maxlength="2" placeholder="" title="" class="inp" oninput="formatBizNo()" title="첫 번째 블록 2자리">
												</div>
												-
												<div class="frm-inp h-48">
													<input type="text" name="biz_no3" id="biz_no3" maxlength="5" placeholder="" title="" class="inp" oninput="formatBizNo()" title="첫 번째 블록 5자리">
												</div>
												<input type="hidden" name="m_biz_no" id="m_biz_no">
											</div>
										</div>
									</div>

									<div class="field">
										<div class="labels"><label for="m_addr" class="txt require">주소</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_addr" id="m_addr" placeholder="" title="" class="inp" readonly title="기본 주소">
												</div>
												<button type="button" class="btn-basic h-48 black" name="addr_search" id="addr_search" onclick="searchAddress()">
													<span>주소 검색</span>
												</button>
											</div>
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_addr_detail" id="m_addr_detail" placeholder="상세 주소를 입력하세요" title="" class="inp" title="상세 주소ㅛ">
												</div>
											</div>
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_post_cd" id="m_post_cd" placeholder="" title="" class="inp" readonly title="나머지 주소">
												</div>
											</div>
										</div>
									</div>
									<div class="field">
										<div class="labels"><span class="txt require">표준산업분류</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-sel h-48 w300">
													<?= makeSelectBoxOnChange2($conn, "INDUSTRY_CATEGORY", "m_ksic", "", "1차 산업분류를 선택하세요", "", $rs_industry_category) ?>
												</div>

												<div class="frm-sel h-48 w232">
													<select id="ksic_detail_selector" name="m_ksic_detail" title="검색 구분" class="sel">
														<option value="" data-placeholder="true">2차 산업분류를 선택하세요</option>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="field">
										<div class="labels"><label for="rd_email_tf" class="txt require">이메일 수신여부</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-rdo">
													<input type="radio" name="rd_email_tf" id="email_tf_Y" value="Y">
													<label for="email_tf_Y"><span>동의</span></label>
												</div>
												<div class="frm-rdo">
													<input type="radio" name="rd_email_tf" id="email_tf_N" value="N">
													<label for="email_tf_N"><span>거부</span></label>
												</div>
												<input type="hidden" id="email_tf" name="email_tf" value="">
											</div>
										</div>
									</div>

									<div class="field">
										<div class="labels"><label for="rd_message_tf" class="txt require">문자 수신여부</label></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-rdo">
													<input type="radio" name="rd_message_tf" id="message_tf_Y" value="Y">
													<label for="message_tf_Y"><span>동의</span></label>
												</div>
												<div class="frm-rdo">
													<input type="radio" name="rd_message_tf" id="message_tf_N" value="N">
													<label for="message_tf_N"><span>거부</span></label>
												</div>
												<input type="hidden" id="message_tf" name="message_tf" value="">
											</div>
										</div>
									</div>

									<div class="agree-box">
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-chk agree">
													<input type="checkbox" name="touTf" id="touTf">
													<label for="touTf"><span class="bold">이용약관 전체동의</span></label>
												</div>
											</div>
											<div class="text-box-wrap">
												<div class="text-box">

						<div class="text-content">

							<div class="partition">
								<h3>제1장 총 칙</h3>
								<h4>제1조(목적)</h4>
								<p>이 약관은 (재)원주미래산업진흥원(이하 "재단"이라 한다)가 홈페이지(www.wfi.or.kr)에서 제공하는 모든 서비스(이하 "서비스"라 한다)의 이용조건 및 절차에 관한 사항을 규정함을 목적으로 합니다.</p>
							</div>

							<div class="partition">
								<h4>제2조(정의)</h4>
								<ul class="bul dep2">
									<li>- 이용자 : 본 약관에 따라 재단이 제공하는 서비스를 받는 자</li>
									<li>- 이용계약 : 서비스 이용과 관련하여 재단과 이용자 간에 체결하는 계약</li>
									<li>- 가입 : 재단이 제공하는 신청서 양식에 해당 정보를 기입하고, 본 약관에 동의하여 서비스 이용계약을 완료시키는 행위</li>
									<li>- 회원 : 당 사이트에 회원가입에 필요한 개인정보를 제공하여 회원 등록을 한 자</li>
									<li>- 이용자번호(ID) : 회원 식별과 회원의 서비스 이용을 위하여 이용자가 선정하고 재단이 승인하는 영문자와 숫자의 조합</li>
									<li>- 패스워드(PASSWORD) : 회원의 정보 보호를 위해 이용자 자신이 설정한 영문자와 숫자, 특수문자의 조합</li>
								</ul>
							</div>

							<div class="partition">
								<h4>제3조(약관의 효력과 변경)</h4>
								<ul class="bul dep2">
									<li>① 회원은 변경된 약관에 동의하지 않을 경우 회원 탈퇴(해지)를 요청할 수 있으며, 변경된 약관의 효력 발생일로부터 7일 이후에도 거부의사를 표시하지 아니하고 서비스를 계속 사용할 경우 약관의 변경 사항에 동의한 것으로 간주됩니다.</li>
									<li>② 이 약관의 서비스 화면에 게시하거나 공지사항 게시판 또는 기타의 방법으로 공지함으로써 효력이 발생됩니다.</li>
									<li>③ 기관은 필요하다고 인정되는 경우 이 약관의 내용을 변경할 수 있으며, 변경된 약관은 서비스 화면에 공지하고, 공지 후 7일 이후에도 거부의사를 표시하지 아니하고 서비스를 계속 사용할 경우 약관의 변경 사항에 동의한 것으로 간주됩니다.</li>
									<li>④ 이용자가 변경된 약관에 동의하지 않는 경우 서비스 이용을 중단하고 본인의 회원등록을 취소할 수 있으며, 계속 사용하시는 경우에는 약관 변경에 동의한 것으로 간주되며 변경된 약관은 전항과 같은 방법으로 효력이 발생합니다.</li>
								</ul>
							</div>

							<div class="partition">
								<h4>제4조(준용규정)</h4>
								<p>이 약관에 명시되지 않은 사항은 전기통신기본법, 전기통신사업법 및 기타 관련법령의 규정에 따릅니다.</p>
							</div>

							<div class="partition">
								<h3>제2장 서비스 이용계약</h3>
								<h4>제5조(이용계약의 성립)</h4>
								<p>이용계약은 이용자의 이용 신청에 대한 기관의 승낙과 이용자의 약관 내용에 대한 동의로 성립됩니다.</p>
							</div>

							<div class="partition">
								<h4>제6조(이용신청)</h4>
								<p>이용신청은 서비스의 회원정보 화면에서 이용자가 기관에서 요구하는 가입신청서 양식에 개인의 신상정보를 기록하여 신청할 수 있습니다.</p>
							</div>

							<div class="partition">
								<h4>제7조(이용신청의 승낙)</h4>
								<ul class="bul dep2">
									<li>① 회원이 신청서의 모든 사항을 정확히 기재하여 이용신청을 하였을 경우에 특별한 사정이 없는 한 서비스 이용신청을 승낙합니다.</li>
									<li>② 다음 각 호에 해당하는 경우에는 이용 승낙을 하지 않을 수 있습니다.</li>
									<li style="padding-left:20px">- 본인의 실명으로 신청하지 않았을 때</li>
									<li style="padding-left:20px">- 타인의 명의를 사용하여 신청하였을 때</li>
									<li style="padding-left:20px">- 이용신청의 내용을 허위로 기재한 경우</li>
									<li style="padding-left:20px">- 사회의 안녕 질서 또는 미풍양속을 저해할 목적으로 신청하였을 때</li>
									<li style="padding-left:20px">- 기타 회사가 정한 이용신청 요건에 미비 되었을 때</li>
								</ul>
							</div>

							<div class="partition">
								<h4>제8조(계약사항의 변경)</h4>
								<p>회원은 이용신청 시 기재한 사항이 변경 되었을 경우에는 수정하여야 하며, 수정하지 아니하여 발생하는 문제의 책임은 회원에게 있습니다.</p>
							</div>

							<div class="partition">
								<h3>제3장 계약당사자의 의무</h3>
								<h4>제9조(기관의 의무)</h4>
								<p>기관은 서비스 제공과 관련해서 알고 있는 회원의 신상 정보를 본인의 승낙 없이 제3자에게 누설하거나 배포하지 않습니다. 단, 전기통신기본법 등 법률의 규정에 의해 국가기관의 요구가 있는 경우, 범죄에 대한 수사상의 목적이 있거나 또는 기타 관계 법령에서 정한 절차에 의한 요청이 있을 경우에는 그러하지 아니합니다.</p>
							</div>

							<div class="partition">
								<h4>제10조(회원의 의무)</h4>
								<ul class="bul dep2">
									<li>① 회원은 서비스를 이용할 때 다음 각 호의 행위를 하지 않아야 합니다.</li>
									<li style="padding-left:20px">- 다른 회원의 ID를 부정하게 사용하는 행위</li>
									<li style="padding-left:20px">- 서비스에서 얻은 정보를 복제, 출판 또는 제3자에게 제공하는 행위</li>
									<li style="padding-left:20px">- 기관의 저작권, 제3자의 저작권 등 기타 권리를 침해하는 행위</li>
									<li style="padding-left:20px">- 공공질서 및 미풍양속에 위반되는 내용을 유포하는 행위</li>
									<li style="padding-left:20px">- 범죄와 결부된다고 객관적으로 판단되는 행위</li>
									<li style="padding-left:20px">- 기타 관계법령에 위반되는 행위</li>
									<li>② 회원은 서비스를 이용하여 영업활동을 할 수 없으며, 영업활동에 이용하여 발생한 결과에 대하여 기관은 책임을 지지 않습니다.</li>
									<li>③ 회원은 서비스의 이용권한, 기타 이용계약 상 지위를 타인에게 양도하거나 증여할 수 없으며, 이를 담보로도 제공할 수 없습니다.</li>
								</ul>
							</div>

							<div class="partition">
								<h3>제4장 서비스 이용</h3>
								<h4>제11조(회원의 의무)</h4>
								<ul class="bul dep2">
									<li>① 회원은 필요에 따라 자신의 메일, 게시판, 등록자료 등 유지보수에 대한 관리책임을 갖습니다.</li>
									<li>② 회원은 기관에서 제공하는 자료를 임의로 삭제, 변경할 수 없습니다.</li>
									<li>③ 회원은 기관의 홈페이지에 공공질서 및 미풍양속에 위반되는 내용물이나 제3자의 저작권 등 기타권리를 침해하는 내용물을 등록하는 행위를 하지 않아야 합니다. 만약 이와 같은 내용물을 게재하였을 때 발생하는 결과에 대한 모든 책임은 회원에게 있습니다.</li>
								</ul>
							</div>

							<div class="partition">
								<h4>제12조(게시물 관리 및 삭제)</h4>
								<p>효율적인 서비스 운영을 위하여 회원의 메모리 공간, 메시지 크기, 보관일수 등을 제한할 수 있으며 등록하는 내용이 다음 각 호에 해당하는 경우에는 사전 통지 없이 삭제할 수 있습니다.</p>
								<ul class="bul dep2">
									<li>- 다른 회원 또는 제3자를 비방하거나 중상모략으로 명예를 손상시키는 내용인 경우</li>
									<li>- 공공질서 및 미풍양속에 위반되는 내용인 경우</li>
									<li>- 범죄적 행위에 결부된다고 인정되는 내용인 경우</li>
									<li>- 기관의 저작권, 제3자의 저작권 등 기타 권리를 침해하는 내용인 경우</li>
									<li>- 회원이 기관의 홈페이지와 게시판에 음란물을 게재하거나 음란 사이트를 링크하는 경우</li>
									<li>- 기타 관계법령에 위반된다고 판단되는 경우</li>
								</ul>
							</div>

							<div class="partition">
								<h4>제13조(게시물의 저작권)</h4>
								<p>게시물의 저작권은 게시자 본인에게 있으며 회원은 서비스를 이용하여 얻은 정보를 가공, 판매하는 행위 등 서비스에 게재된 자료를 상업적으로 사용할 수 없습니다.</p>
							</div>

							<div class="partition">
								<h4>제14조(서비스 이용시간)</h4>
								<p>서비스의 이용은 업무상 또는 기술상 특별한 지장이 없는 한 연중무휴 1일 24시간을 원칙으로 합니다. 다만 정기 점검 등의 사유 발생 시는 그러하지 않습니다.</p>
							</div>

							<div class="partition">
								<h4>제15조(서비스 이용 책임)</h4>
								<p>서비스를 이용하여 해킹, 음란사이트 링크, 상용S/W 불법배포 등의 행위를 하여서는 아니되며, 이를 위반으로 인해 발생한 영업활동의 결과 및 손실, 관계기관에 의한 법적 조치 등에 관해서는 기관이 책임을 지지 않습니다.</p>
							</div>

							<div class="partition">
								<h4>제16조(서비스 제공의 중지)</h4>
								<p>다음 각 호에 해당하는 경우에는 서비스 제공을 중지할 수 있습니다.</p>
								<ul class="bul dep2">
									<li>- 서비스용 설비의 보수 등 공사로 인한 부득이한 경우</li>
									<li>- 전기통신사업법에 규정된 기간통신사업자가 전기통신 서비스를 중지했을 경우</li>
									<li>- 시스템 점검이 필요한 경우</li>
									<li>- 기타 불가항력적 사유가 있는 경우</li>
								</ul>
							</div>

							<div class="partition">
								<h3>제5장 계약해지 및 이용제한</h3>
								<h4>제17조(계약해지 및 이용제한)</h4>
								<ul class="bul dep2">
									<li>① 회원이 이용계약을 해지하고자 하는 때에는 회원 본인이 인터넷을 통하여 해지신청을 하여야 하며, 기관에서는 본인 여부를 확인 후 조치합니다.</li>
									<li>② 기관은 회원이 다음 각 호에 해당하는 행위를 하였을 경우 해지조치 30일전까지 그 뜻을 이용고객에게 통지하여 의견진술 할 기회를 주어야 합니다.</li>
									<li style="padding-left:20px">- 타인의 이용자ID 및 패스워드를 도용한 경우</li>
									<li style="padding-left:20px">- 서비스 운영을 고의로 방해한 경우</li>
									<li style="padding-left:20px">- 허위로 가입 신청을 한 경우</li>
									<li style="padding-left:20px">- 같은 사용자가 다른 ID로 이중 등록을 한 경우</li>
									<li style="padding-left:20px">- 공공질서 및 미풍양속에 저해되는 내용을 유포시킨 경우</li>
									<li style="padding-left:20px">- 타인의 명예를 손상시키거나 불이익을 주는 행위를 한 경우</li>
									<li style="padding-left:20px">- 서비스의 안정적 운영을 방해할 목적으로 다량의 정보를 전송하거나 광고성 정보를 전송하는 경우</li>
									<li style="padding-left:20px">- 정보통신설비의 오작동이나 정보 등의 파괴를 유발시키는 컴퓨터바이러스 프로그램 등을 유포하는 경우</li>
									<li style="padding-left:20px">- 재단 또는 다른 회원이나 제3자의 지적재산권을 침해하는 경우</li>
									<li style="padding-left:20px">- 타인의 개인정보, 이용자ID 및 패스워드를 부정하게 사용하는 경우</li>
									<li style="padding-left:20px">- 회원이 자신의 홈페이지나 게시판 등에 음란물을 게재하거나 음란 사이트를 링크하는 경우</li>
									<li style="padding-left:20px">- 기타 관련법령에 위반된다고 판단되는 경우</li>
								</ul>
							</div>

							<div class="partition">
								<h3>제6장 기 타</h3>
								<h4>제18조(양도금지)</h4>
								<p>회원은 서비스의 이용권한, 기타 이용계약상의 지위를 타인에게 양도, 증여할 수 없으며, 이를 담보로 제공할 수 없습니다.</p>
							</div>

							<div class="partition">
								<h4>제19조(손해배상)</h4>
								<p>기관은 무료로 제공되는 서비스와 관련하여 회원에게 어떠한 손해가 발생하더라도 동 손해가 회사의 고의 또는 중대한 과실로 인한 손해를 제외하고 이에 대하여 책임을 부담하지 아니합니다.</p>
							</div>

							<div class="partition">
								<h4>제20조(면책 조항)</h4>
								<ul class="bul dep2">
									<li>① 기관은 천재지변, 전쟁 또는 기타 이에 준하는 불가항력으로 인하여 서비스를 제공할 수 없는 경우에는 서비스 제공에 관한 책임이 면제됩니다.</li>
									<li>② 기관은 서비스용 설비의 보수, 교체, 정기점검, 공사 등 부득이한 사유로 발생한 손해에 대한 책임이 면제됩니다.</li>
									<li>③ 기관은 회원의 귀책사유로 인한 서비스 이용의 장애에 대하여 책임을 지지 않습니다.</li>
									<li>④ 기관은 회원이 서비스를 이용하여 기대하는 이익이나 서비스를 통하여 얻는 자료로 인한 손해에 관하여 책임을 지지 않습니다.</li>
									<li>⑤ 기관은 회원이 서비스에 게재한 정보, 자료, 사실의 신뢰도, 정확성 등의 내용에 관해서는 책임을 지지 않습니다.</li>
								</ul>
							</div>

							<div class="partition">
								<h4>제21조(관할법원)</h4>
								<p>서비스 이용으로 발생한 분쟁에 대해 소송이 제기 될 경우 회사의 소재지를 관할하는 법원을 전속 관할법원으로 합니다.</p>
							</div>

						</div>

												</div>
											</div>
										</div>
									</div>
									<div class="btn-wrap">
										<button type="button" id="joinButton" onClick="js_save();" class="btn-basic h-56 primary join" disabled>
											<span>회원 가입하기</span>
										</button>
									</div>
								</div>
							</form>
						</div>
						<!-- // 게시판목록 영역 -->
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

