<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8");

$rss_feed_url = 'https://news.google.com/rss?hl=ko&gl=KR&ceid=KR:ko';

#====================================================================
# DB Include, DB Connection
#====================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
require "../../_classes/biz/member/member.php";
require "../../_classes/com/etc/etc.php";

// RSS 데이터 로드
$xml = simplexml_load_file($rss_feed_url);

// XML 데이터 로드 결과 확인
if ($xml === false) {
    echo "Failed to load XML\n";
    foreach (libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
} else {
    // 디버깅하면 줄이 길어져서 생략하는거에염
    //  echo "<pre>";
    //  print_r($xml);
    //  echo "</pre>";
}
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($g_title ?? '대강제목부분', ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="shortcut icon" href="/manager/images/mobile.png" />
    <link rel="apple-touch-icon" href="/manager/images/mobile.png" />
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="../js/jquery-1.11.2.min.js"></script>
    <script src="../js/jquery.nicefileinput.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/jquery.bxslider.min.js"></script>
    <script src="../js/ui.js"></script>
    <script src="../js/common.js"></script>

    <script language="javascript">
        /* 
            다음 주소 검색 함수에염 위의 스크립트로
            <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js" />
            를 head 태그 부분에 꽂아야 되염
        */
        function searchAddress() {
            new daum.Postcode({
                oncomplete: function(data) {
                    console.log("주소 데이터얌 = ",data);
                    const addr = data.address; 
                    const postCode = data.zonecode;
                    document.getElementById('m_addr').value = addr;
                    document.getElementById('m_post_cd').value = postCode;
                }
            }).open();
        }

        // 사업자 등록번호 포맷팅 함수에염
        function formatBizNo(input){
            var bizNo = input.value.replace(/[^0-9]/g, "");
            if (bizNo.length > 3) bizNo = bizNo.replace(/^(\d{3})(\d+)/, "$1-$2");
            if (bizNo.length > 6) bizNo = bizNo.replace(/^(\d{3})-(\d{2})(\d+)/, "$1-$2-$3");
            input.value = bizNo.substring(0, 12); // 최대 길이 제한
        }

        // 입력될때 검증하는 휴대폰 인증 포맷팅  함수에염
        function formatPhone(input) {
            var phone = input.value.replace(/[^0-9]/g, "");

            if (phone.length > 11) {
                phone = phone.substring(0, 11); 
            }
            input.value = phone;
        }

        // 휴대폰 인증 발송 함수에염
        function authPhoneRequest(){
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
            
            alert("mPhone = " +  mPhone);
        }
        


    </script>

    <style>
        .address-btn {
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 12px;
            color: #007bff;
            cursor: pointer;
            text-align: left;
            display: inline-block;
        }

        .address-btn:hover {
            text-decoration: underline;
        }


    </style>
</head>
<body>
    <header>
        <h1>대강 헤더부분</h1>
    </header>

    <main>
        <section id="rss-feed">
            <? if ($xml !== false): ?>
                <h2>대강 콘텐츠 부분</h2>
                <ul>
            <?php foreach ($xml->channel->item as $item): ?>
                <li>
                    <a href="<?= htmlspecialchars((string)$item->link, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                        <?= htmlspecialchars((string)$item->title, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                    <br>
                    <small>
                        <?= htmlspecialchars(date("Y.m.d H:i", strtotime((string)$item->pubDate)), ENT_QUOTES, 'UTF-8'); ?>
                    </small>
                </li>
            <?php endforeach; ?>
        </ul>
            <? else: ?>
                <p>RSS 데이터를 불러오는 데 실패했습니다.</p>
            <? endif; ?>
        </section>

    <h1>대강 회원가입 폼</h1>
        <form action="register_process.php" method="post">
            <label for="m_id">회원 아이디:</label>
            <input type="text" id="m_id" name="m_id" required><br>

            <label for="m_pwd">회원 비밀번호:</label>
            <input type="password" id="m_pwd" name="m_pwd" required><br>

            <label for="m_phone">전화번호:</label>
            <input type="text" id="m_phone" name="m_phone" oninput="formatPhone(this)" placeholder="" maxlength="11">
            <button type="button" id="m_phone_rq" name="m_phone_rq" onclick="authPhoneRequest()">인증번호 발송</button><br>

            <label for="m_phone_chk">인증확인</label>
			<input type="text" name="auth_phone" id="auth_phone" maxlength="11" class="onlyNum" value="" >
            <button type="button" class="button">인증확인</button><br>

            <label for="m_email">이메일 주소:</label>
            <input type="email" id="m_email" name="m_email"><br>

            <label for="m_gubun">회원 구분:</label>
            <select id="m_gubun" name="m_gubun">
                <option value="개인">개인</option>
                <option value="기업">기업</option>
            </select><br>
            <label for="m_addr">주소 부분이다이</label><br>
            <label for="m_post_cd">우편번호</label><br>
                <input type="text" id="m_post_cd" name="m_post_cd" readonly><br>
            <label for="m_addr">주소</label><br>
                <input type="text" id="m_addr" name="m_addr" readonly>
            <a class="address-btn" onclick="searchAddress()">주소 검색</a><br>

        <label for="m_addr_detail">상세 주소:</label>
        <input type="text" id="m_addr_detail" name="m_addr_detail"><br>

            <label for="m_biz_no">사업자 등록번호 (기업회원):</label>
            <input type="text" id="m_biz_no" name="m_biz_no" placeholder="XXX-XX-XXXXX" oninput="formatBizNo(this)" maxlength="12"><br>

            <label for="m_ksic">KSIC 코드:</label>
            <input type="text" id="m_ksic" name="m_ksic"><br>

            <!-- <label for="disp_seq">순서:</label>
            <input type="number" id="disp_seq" name="disp_seq"><br> -->

            <label for="use_tf">사용 여부:</label>
            <select id="use_tf" name="use_tf">
                <option value="Y" selected>사용</option>
                <option value="N">사용 안함</option>
            </select><br>

            <button type="submit">회원가입</button>
        </form>
    </main>

    <footer>
                <h1>대강 푸터부분</h1>
    </footer>





</body>
</html>
