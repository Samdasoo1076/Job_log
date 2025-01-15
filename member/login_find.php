<? session_start(); ?>
<?

$_PAGE_NO = "98";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        $("#auth_code_section").hide();
        //let authCode = "0000"; // 테스트 인증번호
        let isAuthVerified = false;
        let timer;

        // 인증번호 받기 버튼 클릭 시
        $('#m_phone_rq').on('click', function () {
            const phone_number = $('input[name="phone_number"]').val();
            $('#formattedPhone').val(phone_number.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3"));

            if (!phone_number) {
                alert('휴대폰 번호를 입력해주세요.');
                return;
            }
           	// AJAX 요청
            $.ajax({
                type: "POST",
                url: "/_common/ajax_member_dml.php",
                data: {
                    mode: "AUTH_PHONE",
					sms_flag : "0",
                    m_phone: phone_number
                },
                dataType: "json",
                success: function(response) {
                if (response.success) {
                    $("#auth_code_section").show();
                    startTimer(180, "#setTimer");
                } else {
                        alert("인증번호 생성 중 오류가 발생했습니다.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX 요청 중 오류 발생: " + error);
                }
            });

        // 3분 타이머 함수
        function startTimer(duration, selector) {
            let timer = duration; // 타이머 초기화
            const element = document.querySelector(selector);

            const interval = setInterval(() => {
            const minutes = Math.floor(timer / 60);
            const seconds = timer % 60;

            // 2자리 숫자로 포맷
            const formattedTime = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
            element.textContent = formattedTime;

            if (timer === 0) {
                    clearInterval(interval); // 타이머 중지
                    alert("인증 시간이 만료되었습니다.");
                    $('#setTimer').hide();
                    $('#authBtn').prop('disabled', true);
            } else {
                    timer--; // 타이머 감소
        }
            }, 1000); // 1초마다 실행
        }

    });

    // 인증번호 확인 버튼 클릭 시
    $('#authBtn').on('click', function () {
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
                    $("#auth_status").val("Y");
                    $('#findIdButton').prop('disabled', false);
                } else {
                    alert("인증번호가 올바르지 않습니다.");
                }
            },
            error: function (xhr, status, error) {
                alert("AJAX 요청 중 오류 발생: " + error);
            },
        });

    });

        $('#findIdButton').prop('disabled', true);

    });

    function formatPhone(input) {

        var phone = input.value.replace(/[^0-9]/g, "");
        if (phone.length > 11) {
            phone = phone.substring(0, 11);
        }
            input.value = phone;
    }

</script>

<main role="main" class="container">
    <div id="content" class="content login-page">
		<div class="content-header"></div>
        <div class="content-body">
            <div class="title-wrap">
                <h2 class="title">아이디 찾기</h2>
                <p class="explain">휴대폰 문자 인증을 통해 아이디를 찾을 수 있습니다. <br />
                    회원가입시 사용하였던 휴대폰 번호를 입력해 주세요.​</p>
            </div>
            <div class="member-page">
                <form action="../member/login_find_result.do" method="POST" id="findIdForm">
                    <input type="hidden" value="" name="formattedPhone" id="formattedPhone">
                    <div class="login-form-wrap pad-b60 bg">
                        <div class="login-form">
                            <div class="info-inp-wrap login-filed">
                                <div class="labels">
                                    <span class="txt">휴대폰 번호</span>
                                </div>
                                <div class="inp-wrap">
                                    <div class="frm-inp h-48">
                                        <input type="number" name="phone_number" id="phone_number" placeholder="“-”없이 숫자만 입력해주세요​" maxlength="11" oninput="formatPhone(this);"  title="" class="inp">
                                    </div>
                                    <button type="button" class="btn-basic h-48 black" id="m_phone_rq" name="m_phone_rq">
                                        <span>인증번호 받기</span>
                                    </button>
                                </div>
                            </div>

                            <div class="info-inp-wrap login-filed" id="auth_code_section">
                                <div class="labels"><span class="txt">인증번호</span></div>
								<div class="inp-wrap">
									<div class="frm-inp h-48">
										<input type="text" name="auth_code" id="auth_code" placeholder="" title="" value="" class="inp">
										<span class="red-time" id="setTimer"></span>
									</div>
									<button type="button" class="btn-basic h-48 black" id="authBtn">
										<span>인증번호 확인</span> <!-- 인증번호 받기 후 -->
										<!-- <span>재요청</span>  인증번호 입력 후 -->
									</button>
								</div>
                            </div>

							<button type="submit" class="btn-basic h-56 primary fluid login" id="findIdButton" disabled>
								<span>아이디 찾기</span>
							</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<footer class="footer">

	<?
		require_once "../_common/front_footer.php";
	?>
		</footer>
