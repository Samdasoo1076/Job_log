<?
session_start();

$_PAGE_NO = "98";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";
$m_id = isset($_POST['m_id']) ? $_POST['m_id'] : null;
$m_phone = isset($_POST['m_phone']) ? $_POST['m_phone'] : null;
$formattedPhone = isset($_POST['formattedPhone']) ? trim($_POST['formattedPhone']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $m_id = isset($_POST['m_id']) ? $_POST['m_id'] : null;
    $m_phone = isset($_POST['m_phone']) ? $_POST['m_phone'] : null;
    $formattedPhone = isset($_POST['formattedPhone']) ? trim($_POST['formattedPhone']) : '';

    if (!$m_id || !$m_phone) {
        echo "<script>alert('유효하지 않은 접근입니다.'); location.href='/member/password_find.do';</script>";
        exit;
    }
}
?>

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script type="text/javascript" src="../manager/js/jquery-ui.js"></script>
<script type="text/javascript" src="../manager/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../manager/js/common.js"></script>
<script type="text/javascript" src="../manager/js/httpRequest.js"></script> <!-- Ajax js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
			
			function updateButtonState() {
				const m_pwd = $('#new_password').val();
				const confirm_password = $('#confirm_password').val();
				$('#savePassword').prop('disabled', !(m_pwd && confirm_password));
			}
			 // 비밀번호 입력 필드에 키 입력 이벤트 핸들러 추가
			$('#new_password, #confirm_password').on('keyup', updateButtonState);
		
            $('#savePassword').on('click', function () {
                const m_id = $('#m_id').val();
                const m_phone = $('#m_phone').val();
                const m_pwd = $('#new_password').val();
                const confirm_password = $('#confirm_password').val();
                const formattedPhone = m_phone.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");

                if (!m_pwd  || !confirm_password) {
                    alert('비밀번호를 입력해주세요.');
                    return;
                }

                if (m_pwd  !== confirm_password) {
                    alert('비밀번호가 일치하지 않습니다.');
                    return;
                }

                // AJAX 요청
                $.ajax({
                    type: 'POST',
                    url: '/_common/ajax_change_pw_dml.php',
                    data: { m_id, m_pwd, formattedPhone   },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = '/member/login.do'; // 변경 후 로그인 페이지로 이동
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('서버 요청에 실패했습니다. 다시 시도해주세요.');
                    }
                });
            });
			// 페이지 로드 시 초기 버튼 상태 설정
			updateButtonState();
        });
    </script>

<head>
    <meta charset="UTF-8">
    <title>비밀번호 변경</title>
</head>

        <!-- Container -->
        <main role="main" class="container">
            <!-- content -->
            <div id="content" class="content login-page">
                <!-- content-body -->
                <div class="content-body">
                    <!-- 타이틀 영역 -->
                    <div class="title-wrap">
                        <h2 class="title">비밀번호 변경</h2>
                    </div>
                    <!-- // 타이틀 영역 -->
                    <div class="member-page">
                        <!-- 비밀번호 변경 폼 -->
                        <div class="login-form-wrap pad-b60 bg">
                            <div class="login-form">
									<input type="hidden" id="m_id" value="<?= $m_id ?>">
									<input type="hidden" id="m_phone" value="<?= $m_phone ?>">
                                <div class="info-inp-wrap login-filed">
                                    <div class="labels"><span class="txt">새 비밀번호</span></div>
                                    <div class="inp-wrap">
                                        <div class="frm-inp h-48">
                                            <input type="password" id="new_password" placeholder="새 비밀번호를 입력해주세요" title="" class="inp">
                                        </div>
                                    </div>
                                    <p class="info-txt">최소 8자리 이상, 영문 대/소문자, 숫자, 특수문자(!, @, # 등) 포함</p>
                                </div>
                                <div class="info-inp-wrap login-filed">
                                    <div class="labels"><span class="txt">비밀번호 확인</span></div>
                                    <div class="inp-wrap">
                                        <div class="frm-inp h-48">
                                            <input type="password" id="confirm_password" placeholder="위와 동일한 비밀번호를 다시 입력해주세요" title="" class="inp">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="savePassword" class="btn-basic h-56 primary fluid login">
                                    <span>비밀번호 저장</span>
                                </button>
                            </div>
                        </div>
                        <!-- // 비밀번호 변경 폼 -->
                    </div>
                </div>
                <!-- // content-body -->
            </div>
            <!-- // content -->
        </main>
