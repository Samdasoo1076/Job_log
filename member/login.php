<?
session_start();
ob_start(); // 출력 버퍼링 시작

# =============================================================================
# File Name    : login.php
# Module       : 
# Writer       : Lee Ji Min
# Create Date  : 2024-11-27
# Modify Date  : 
# Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

$_PAGE_NO = "98";
$result = "0";

require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require "../_classes/biz/member/member.php";

// 로그인 페이지로 오기 전 URL 저장
if (!isset($_SESSION['redirect_url']) && isset($_SERVER['HTTP_REFERER'])) {
    $referrer = htmlspecialchars_decode($_SERVER['HTTP_REFERER']); // 디코딩 추가
    if (!str_contains($referrer, 'login.do')) {
        $_SESSION['redirect_url'] = $referrer;
    }
}

if (isset($_SESSION['m_id']) && !empty($_SESSION['m_id'])) {
    $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : (isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'mypage.do');
    $redirect = htmlspecialchars_decode($redirect); // 디코딩 추가
    header("Location: $redirect");
    exit;
}

$m_id = isset($_POST['m_id']) ? trim($_POST['m_id']) : '';
$m_pw = isset($_POST['m_pw']) ? trim($_POST['m_pw']) : '';
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($m_id !== '' && $m_pw !== '') {
        $arr_rs = loginMember($conn, $m_id);

        if (!empty($arr_rs)) {
            $db_password = $arr_rs[0]['M_PWD'];

            if ($db_password === encrypt($key, $iv, $m_pw)) {
                // 로그인 성공
                $result = "0";
                $_SESSION['m_no'] = $arr_rs[0]['M_NO'];
                $_SESSION['m_id'] = $arr_rs[0]['M_ID'];
                $_SESSION['m_pw'] = $arr_rs[0]['M_PWD'];
                $_SESSION['m_gubun'] = $arr_rs[0]['M_GUBUN'];

                $redirect = isset($_SESSION['redirect_url']) ? htmlspecialchars_decode($_SESSION['redirect_url']) : 'mypage.do';
                unset($_SESSION['redirect_url']); 
                header("Location: $redirect");
                exit;
            } else {
                // 비밀번호 불일치
                $result = "2";
                $str_result = "비밀번호가 일치하지 않습니다.";
            }
        } else {
            // 사용자 없음
            $result = "1";
            $str_result = "해당 아이디가 없습니다.";
        }
    } else {
        $result = "2";
        $str_result = "아이디와 비밀번호를 입력해주세요.";
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>로그인 페이지</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#login-form").on("submit", function (e) {
                e.preventDefault();
                js_Login();
            });

            $("input").on("keypress", function (e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $("#btn_login").trigger("click");
                }
            });
        });

        function js_Login() {
            const frm = document.frm;

            if (frm.m_id.value.trim() === "") {
                alert('아이디를 입력해주세요.');
                frm.m_id.focus();
                return;
            }

            if (frm.m_pw.value.trim() === "") {
                alert('비밀번호를 입력해주세요.');
                frm.m_pw.focus();
                return;
            }

            frm.submit();
        }
    </script>
</head>

<body>
    <main role="main" class="container">
        <div id="content" class="content login-page">
            <div class="content-body">
                <div class="member-page">
                    <div class="login-form-wrap">
                        <div class="login-form">
                            <h2>안녕하세요.<br /><em>원주미래산업진흥원</em>입니다.</h2>
                            <form name="frm" class="login-post" method="post" id="login-form">
                                <!-- 리디렉트 URL을 숨겨진 필드로 전송 -->
                                <div class="info-inp-wrap login-filed">
                                    <div class="inp-wrap">
                                        <div class="frm-inp h-48">
                                            <input type="text" name="m_id" id="m_id" placeholder="아이디를 입력해주세요" class="inp" />
                                        </div>
                                    </div>
                                </div>
                                <div class="info-inp-wrap login-filed">
                                    <div class="inp-wrap">
                                        <div class="frm-inp h-48">
                                            <input type="password" name="m_pw" id="pam_pwdssword" placeholder="비밀번호를 입력해주세요" class="inp" />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-basic h-56 primary fluid login" id="btn_login">
                                    <span>로그인</span>
                                </button>
                            </form>
                            <div class="login-info">
                                <div class="item">
                                    <a href="/member/login_find.do">아이디 찾기</a>
                                </div>
                                <div class="item">
                                    <a href="/member/password_find.do">비밀번호 찾기</a>
                                </div>
                            </div>
                            <div class="join-page">
                                <a href="/member/member_form.do">
                                    <p class="tit">회원가입</p>
                                    <p class="txt">아직 WFI 회원이 아니신가요?</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <? if ($result !== "0") { ?>
        <script>alert('<?= $str_result ?>');</script>
    <? } ?>
</body>

</html>
<?
db_close($conn);
?>