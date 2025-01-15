<? session_start(); ?>
<?
$_PAGE_NO = "98";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

$phone = '';
$userId = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
    $formattedPhone = isset($_POST['formattedPhone']) ? trim($_POST['formattedPhone']) : '';

    if (!empty($phone)) {
        $user = findUserid($conn, encrypt($key, $iv, $formattedPhone));

        if ($user) {
            $userId = $user['M_ID'];
        } else {
            $error_message = "등록된 번호로 조회된 아이디가 없습니다.";
        }
    } else {
        $error_message = "휴대폰 번호를 입력해주세요.";
    }
}
?>

<script>
    function copy() {
        var input = document.createElement('input');
        input.setAttribute('value', document.getElementById('copyTxt').textContent);
        document.body.appendChild(input);
        input.select();
        var result = document.execCommand('copy');
        document.body.removeChild(input);

        if (result) {
            alert('아이디가 복사되었습니다!');
        } else {
            alert('복사 실패: 브라우저 설정을 확인해주세요.');
        }
    }
</script>

<main role="main" class="container">
    <div id="content" class="content login-page">
        <div class="content-body">
            <div class="title-wrap">
                <h2 class="title">아이디 찾기</h2>
            </div>
            <div class="member-page">
                <div class="login-form-wrap pad-b60 bg">
                    <div class="login-form">
                        <div class="id-result-wrap">
                            <? if (!empty($userId)) { ?>
                                <p>회원님의 아이디는</p>
                                <div>
                                    <em class="id-result" id="copyTxt"><?= $userId ?></em>
                                    <button class="btn-copy" onclick="copy();"><span class="blind">COPY</span></button>
                                </div>
                                <p>입니다.</p>

                            <? } else { ?>
                                <p><?= $error_message ?></p>
                            <? } ?>
                        </div>
                        <button type="button" class="btn-basic h-56 primary fluid login"
                            onclick="location.href='../member/login.do'">
                            <span>로그인하기</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>