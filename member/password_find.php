<? session_start(); ?>
<?
$_PAGE_NO = "98";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script type="text/javascript" src="../manager/js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../manager/js/jquery-ui.js"></script>
<script type="text/javascript" src="../manager/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../manager/js/ui.js"></script>
<script type="text/javascript" src="../manager/js/common.js"></script>
<script type="text/javascript" src="../manager/js/httpRequest.js"></script> <!-- Ajax js -->
<script>
$(document).ready(function () {

    $("#auth_code_section").hide();

    // 인증번호 받기 클릭
    $('#sendAuthCode').on('click', function () {
        const m_phone = $('#m_phone').val().trim();

        if (!m_phone) {
            alert('휴대폰 번호를 입력해주세요.');
            return;
        }

    // AJAX 요청
    $.ajax({
        type: "POST",
        url: "/_common/ajax_member_dml.php",
        data: {
            mode: "AUTH_PHONE",
			sms_flag : "1",
            m_phone: m_phone
        },
        dataType: "json",
        success: function(response) {
        if (response.success) {
            $('#auth_code_section').show(); // 인증번호 입력 필드 보이기
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
                $('#findPasswordBtn').prop('disabled', true);
        } else {
                timer--; // 타이머 감소
        }
        }, 1000); // 1초마다 실행
    }
        //startTimer(); // 타이머 시작
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
                    $('#findPasswordBtn').prop('disabled', false);
                } else {
                    alert("인증번호가 올바르지 않습니다.");
                }
            },
            error: function (xhr, status, error) {
                alert("AJAX 요청 중 오류 발생: " + error);
            },
        });

    });

    // 인증번호 확인 버튼 클릭
    $('#findPasswordBtn').on('click', function () {
        const m_id = $('#m_id').val().trim();
        const m_phone = $('#m_phone').val().trim();
        const enteredCode = $('#auth_code').val().trim();

        const formattedPhone = m_phone.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
        console.log("Formatted Phone:", $('#formattedPhone').val());

        if (!m_id || !m_phone || !enteredCode) {
            alert('모든 필드를 입력해주세요.');
            return;
        }

        // AJAX 요청
        $.ajax({
            type: 'POST',
            url: '/_common/ajax_find_pw_dml.php', // AJAX 처리 파일
            data: { m_id, m_phone, auth_code: enteredCode,  formattedPhone: formattedPhone  },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '/member/password_change.php'
                    });
                    form.append($('<input>', { type: 'hidden', name: 'm_id', value: m_id }));
                    form.append($('<input>', { type: 'hidden', name: 'm_phone', value: m_phone }));
                    form.append($('<input>', { type: 'hidden', name: 'formattedPhone', id: 'formattedPhone', value: formattedPhone }));
                    $('body').append(form);
                    form.submit(); // 폼 제출
                } else {
                    alert(response.message || '사용자 정보를 찾을 수 없습니다.');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX 요청 실패:");
                console.error("상태:", status);
                console.error("에러 메시지:", error);
                console.error("응답 데이터:", xhr.responseText); // 서버 응답 확인
                alert('서버 요청에 실패했습니다. 다시 시도해주세요.');
            }
        });
    });

    // 타이머 시작 함수
    // function startTimer() {
    //     clearInterval(timer); // 기존 타이머 초기화
    //     timeLeft = 180; // 3분 설정
    //     $('#timer').text('03:00').show(); // 초기 타이머 설정

    //     timer = setInterval(function () {
    //         const minutes = Math.floor(timeLeft / 60);
    //         const seconds = timeLeft % 60;
    //         $('#timer').text(`${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`);
    //         timeLeft--;

    //         if (timeLeft < 0) {
    //             clearInterval(timer);
    //             alert('인증 시간이 만료되었습니다. 다시 요청해주세요.');
    //             $('#timer').hide();
    //         }
    //     }, 1000);
    // }
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
                <h2 class="title">비밀번호 찾기</h2>
                <p class="explain">휴대폰 문자 인증을 통해 비밀번호를 변경할 수 있습니다.<br />회원가입시 사용하였던 아이디, 휴대폰 번호를 입력해주세요.​</p>
            </div>
            <div class="member-page">
                <form id="findPwForm">
                <input type="hidden" value="" name="formattedPhone" id="formattedPhone">
                    <div class="login-form-wrap pad-b60 bg">
                        <div class="login-form">
                            <div class="info-inp-wrap login-filed">
                                <div class="labels"><span class="txt">아이디</span></div>
                                <div class="inp-wrap">
                                    <div class="frm-inp h-48">
                                        <input type="text" name="m_id" id="m_id" placeholder="아이디를 입력해주세요" title="" class="inp">
                                    </div>
                                </div>
                            </div>
                            <div class="info-inp-wrap login-filed">
                                <div class="labels"><span class="txt">휴대폰 번호</span></div>
                                <div class="inp-wrap">
                                    <div class="frm-inp h-48">
                                        <input type="text" name="m_phone" id="m_phone" placeholder="“-”없이 숫자만 입력해주세요​" title="" class="inp" max="11" oninput="formatPhone(this);">
                                    </div>
                                    <button type="button" id="sendAuthCode" class="btn-basic h-48 black">
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

                            <button type="button" id="findPasswordBtn" class="btn-basic h-56 primary fluid login" disabled>
                                <span>비밀번호 찾기</span>
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
