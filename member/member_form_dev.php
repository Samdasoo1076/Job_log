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

	$mode                = SetStringToDB($mode);
	$nPage                = SetStringToDB($nPage);
	$nPageSize            = SetStringToDB($nPageSize);
	$nPage                = trim($nPage);
	$nPageSize            = trim($nPageSize);

	$search_field        = SetStringToDB($search_field);
	$search_str            = SetStringToDB($search_str);
	$search_field        = trim($search_field);
	$search_str            = trim($search_str);


	$m_no               = SetStringToDB($m_no);
	$use_tf                = SetStringToDB($use_tf);
	$del_tf = "N";

	#============================================================
	# 등록일 경우

	if ($mode == 'I') {
		$m_no =                     SetStringToDB($m_no);
		$m_id =                     SetStringToDB($m_id);
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

		$reg_date       = SetStringToDB($reg_date);
		$use_tf            = SetStringToDB($use_tf);
		$search_field   = SetStringToDB($search_field);
		$search_str        = SetStringToDB($search_str);

		//$result_flag = 0;

		$passwd_enc 	    = encrypt($key, $iv, $m_pwd);
		$phone_enc 		    = encrypt($key, $iv, $m_phone);
		$email_enc		    = encrypt($key, $iv, $m_email);
		$addr_enc   		= encrypt($key, $iv, $m_addr);
		$addr_detail_enc    = encrypt($key, $iv, $m_addr_detail);
		$post_cd_enc		= encrypt($key, $iv, $post_cd_enc);

		$arr_data = array(
			"M_ID" 			=> $m_id,
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
			//"EMAIL_TF" => $email_tf,
			//"MESSAGE_TF" => $message_tf,
			// "USE_TF" => $use_tf,
			//"REG_ADM" => $_SESSION['m_id']
		);
		$result =  insertMember($conn, $arr_data);
	}

	// Response Data Setting
	$rs_m_no = "";
	$rs_m_name = "";
	$rs_m_email = "";
	$rs_m_phone = "";
	$rs_m_status = "";
	$rs_m_role = "";
	$rs_disp_seq = "";
	$rs_industry_category = "";
	$rs_m_gubun = "";
	$rs_email_tf = "";
	$rs_message_tf = "";
	$rs_use_tf = "";
	$rs_del_tf = "";
	$rs_reg_adm = "";
	$rs_reg_date = "";
	$rs_up_adm = "";
	$rs_up_date = "";
	$rs_del_adm = "";
	$rs_del_date = "";

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
		$("#phone_auth_area").hide();

		// 기업회원일 경우 사업자등록번호 노출 
		$("input[name='rd_m_gubun']").on("change", function() {
			if ($("#m_gubun_c").is(":checked")) {
				$("#biz_no_area").show();
			} else {
				$("#biz_no_area").hide();
			}
		});
		
		var m_no = "<?= (int)$m_no ?>";
	});

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

	function formatBizNo(input) {
		const part1 = document.getElementById("biz_no1").value.replace(/[^0-9]/g, "");
		const part2 = document.getElementById("biz_no2").value.replace(/[^0-9]/g, "");
		const part3 = document.getElementById("biz_no3").value.replace(/[^0-9]/g, "");
		const formattedBizNo = `${part1}-${part2}-${part3}`;
		document.getElementById("m_biz_no").value = formattedBizNo;

	}

	function js_email03(selectElement) {
		const email01 = document.getElementById('email01').value.trim();
		const email02 = document.getElementById('email02');
		const email03 = document.getElementById('email03').value.trim();
		const selectedValue = selectElement.value;
			
		$('#email02').val(selectedValue);
	}

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

		$("#phone_auth_area").show();
	
		alert("휴대폰 인증 비즈뿌리오 API 연동 추가 예정  = " + mPhone);

	}

	function authPhoneCheck() {
		alert("인증확인 로직 추가 예정");
	}


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

	// 비밀번호 검증
	function js_pwd_chk() {
		let regPwd = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,15}$/

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
				ksicDetailSelector.innerHTML = '<option value="" data-placeholder="true">2차 산업분류 선택하세요</option>';
				
				response.data.forEach(function(m_ksic_detail) {
					var option = document.createElement("option");
					option.value = m_ksic_detail.DCODE_NM; // 코드 값 value
					option.textContent = m_ksic_detail.DCODE_NM; // 코드 이름 name
					ksicDetailSelector.appendChild(option);
				});

				console.log("Final HTML in Select:", ksicDetailSelector.innerHTML); // 최종 HTML 확인
				
			} else {
				alert("2차 산업 분류를 가져오는 중 오류가 발생했습니다: " + response.message);
			}
		})
		.fail(function(xhr, status, error) {
			console.error("AJAX 요청 실패: ", error);
			alert("2차 산업 분류를 가져오는 중 문제가 발생했습니다. 다시 시도해주세요.");
		});
	}

	function js_save() {
		var frm = document.frm;
		//var m_no = "<?= (int)$m_no ?>";
		var m_phone = frm.m_phone.value.trim();
		var formattedPhone = m_phone.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
		frm.m_phone.value = formattedPhone;

		if (!isDuplicateChecked) {
			alert("아이디 중복 체크를 진행해주세요.");
			document.getElementById('m_id').focus();
			return;
		}

		frm.mode.value = "I";

		if (!js_pwd_chk()) {
			alert("비밀번호를 다시 확인해주세요.");
			frm.m_pwd.focus();
			return;
		}

		// 이메일 조합 검증
		if (!frm.email01.value.trim() || !frm.email02.value.trim()) {
			alert("이메일을 모두 입력해주세요.");
			frm.email01.focus();
			return false;
		}

		frm.m_email.value = frm.email01.value+"@"+frm.email02.value;

		// 회원 구분 (필수)
		if (frm.rd_m_gubun) {
		var selectedGubun = document.querySelector('input[name="rd_m_gubun"]:checked');
			if (!selectedGubun) {
				alert("회원 구분을 선택해주세요.");
				return false;
			}
			frm.m_gubun.value = selectedGubun.value;
		}

		// 필수 체크박스 (이용약관 동의) 검증
		var touTf = document.getElementById('touTf');
		if (!touTf.checked) {
			alert("이용약관에 동의하셔야 합니다.");
			touTf.focus(); 
			return false; 
		}

		frm.target = "";
		frm.action = "member_form.do";
		frm.submit();
	}
</script>
</head>
<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content member-form-page">
			<!-- content-body -->
			<form name="frm" method="post" enctype="multipart/form-data">
			<input type="hidden" name="seq_no" value="" />
                    <input type="hidden" name="mode" value="" />
                    <input type="hidden" name="menu_cd" value="">
                    <input type="hidden" name="m_no" value="<?= (int)$m_no ?>" />

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
							<div class="join-form">
								<p class="req">필수입력사항</p>
								<div class="field-wrap">
									<div class="field">
										<div class="labels">
											<span class="txt require">아이디</span><!-- 필수입력 시 : require -->
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
											<span class="txt require">전화번호</span><!-- 필수입력 시 : require -->
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
												<div class="frm-inp h-48 red-time">
													<input type="text" name="" id="" placeholder="" title="" value="02:59" class="inp">
												</div>
												<button type="button" class="btn-basic h-48 black"  id="auth_phone_chk" name="auth_phone_chk" onclick="authPhoneCheck()" >
													<span>인증번호 확인</span>
												</button>
											</div>
										</div>
									</div>
									<div class="field">
										<div class="labels"><span class="txt require">이메일</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap email">
												<div class="frm-inp h-48">
													<input type="text" name="email01" id="email01" placeholder="" title="" class="inp">
												</div>
												@
												<div class="frm-inp h-48">
													<input type="text" name="email02" id="email02" placeholder="" title="" class="inp">
												</div>
												<div class="frm-sel h-48">
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
										<div class="labels"><span class="txt require">비밀번호</span></div>
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
										<div class="labels"><span class="txt require">비밀번호 확인</span></div>
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
													<input type="radio" name="rd_m_gubun" id="m_gubun_p" value="P" checked>
													<label for="sRadio11"><span>개인회원</span></label>
												</div>
												<div class="frm-rdo">
													<input type="radio" name="rd_m_gubun" id="m_gubun_c" value="C">
													<label for="sRadio12"><span>기업회원</span></label>
												</div>
												<input type="hidden" name="m_gubun" value="<?= $rs_m_gubun ?>">
											</div>
										</div>
									</div>

									<div id="biz_no_area" class="field">
										<div class="labels"><span class="txt require">사업자등록번호</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="biz_no1" id="biz_no1" maxlength="3" placeholder="" title="" class="inp" oninput="formatBizNo()">
												</div>
												-
												<div class="frm-inp h-48">
													<input type="text" name="biz_no2" id="biz_no2" maxlength="2" placeholder="" title="" class="inp" oninput="formatBizNo()">
												</div>
												-
												<div class="frm-inp h-48">
													<input type="text" name="biz_no3" id="biz_no3" maxlength="5" placeholder="" title="" class="inp" oninput="formatBizNo()">
												</div>
												<input type="hidden" name="m_biz_no" id="m_biz_no">
											</div>
										</div>
									</div>

									<div class="field">
										<div class="labels"><span class="txt require">주소</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_addr" id="m_addr" placeholder="" title="" class="inp">
												</div>
												<button type="button" class="btn-basic h-48 black" name="addr_search" id="addr_search" onclick="searchAddress()">
													<span>주소 검색</span>
												</button>
											</div>
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_addr_detail" id="m_addr_detail" placeholder="상세 주소를 입력하세요" title="" class="inp">
												</div>
											</div>
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="m_post_cd" id="m_post_cd" placeholder="" title="" class="inp">
												</div>
											</div>
										</div>
									</div>
									<div class="field">
										<div class="labels"><span class="txt require">표준산업분류</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-sel h-48 w300">
													<?= makeSelectBoxOnChange2($conn, "INDUSTRY_CATEGORY", "m_ksic", "300", "1차 산업분류 선택하세요", "", $rs_industry_category) ?>
												</div>

												<div class="frm-sel h-48 w232">
													<select id="ksic_detail_selector" name="m_ksic_detail" title="검색 구분" class="sel">
														<option value="" data-placeholder="true">2차 산업분류 선택하세요</option>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="agree-box">
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-chk agree">
													<input type="checkbox" name="touTf" id="touTf">
													<label for="sCheck12"><span class="bold">이용약관 전체동의</span></label>
												</div>
											</div>
											<div class="text-box-wrap">
												<div class="text-box">
													<p class="tit">&lt;이용약관 동의&gt;</p>
													<p> 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다.
														이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다. 이용약관 동의 내용이 들어갑니다.
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="btn-wrap">
										<button type="button" onClick="js_save();" class="btn-basic h-56 primary join">
											<span>회원 가입하기</span>
										</button>
									</div>
								</div>
							</div>
						</div>
						<!-- // 게시판목록 영역 -->
					</div>
				</div>
			</form>	
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

