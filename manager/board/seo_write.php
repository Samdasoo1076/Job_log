<? session_start(); ?>
<?  #echo "<h1>공통 템플릿입니다. 여기를 지우고 활용하세요구르트.</h1> <br>"; 
?>
<?
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : seo_write.php
# Modlue       : 
# Writer       : Seo Hyun Seok 
# Create Date  : 2024-11-14
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================


#====================================================================
# DB Include, DB Connection
#====================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
$menu_right = "SEO01"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
require "../../_common/common_header.php";

$sPageRight_        = "Y";
$sPageRight_R        = "Y";
$sPageRight_I        = "Y";
$sPageRight_U        = "Y";
$sPageRight_D        = "Y";
$sPageRight_F        = "Y";

#=====================================================================
# common function, login_function
#=====================================================================
require "../../_common/config.php";
require "../../_classes/com/util/Util.php";
require "../../_classes/com/util/ImgUtil.php";
require "../../_classes/com/util/ImgUtilResize.php";
require "../../_classes/com/util/AES2.php";
require "../../_classes/com/etc/etc.php";
require "../../_classes/biz/member/member.php";
require "../../_classes/biz/online/online.php";

#====================================================================
# DML Process
#====================================================================

# 테이블 - TBL_MEMBER_TST
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

# 검색 필드 세팅 (회원구분, 산업분류, 이메일수신동의, 문자수신동의) -> 리스트에서 쓸거임... 쯔업 그냥 $변수만 보내면 됬었당... 쯔업쯔업
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
    $m_ksic_detail =            SetStringToDB($m_ksic_detail);
    $email_tf =                 SetStringToDB($email_tf);
    $message_tf =               SetStringToDB($message_tf);

    $reg_date       = SetStringToDB($reg_date);
    $use_tf            = SetStringToDB($use_tf);
    $search_field   = SetStringToDB($search_field);
    $search_str        = SetStringToDB($search_str);

    //$result_flag = 0;

    $passwd_enc = encrypt($key, $iv, $m_pwd);

    $arr_data = array(
        "M_ID" => $m_id,
        "M_EMAIL" => $m_email,
        "M_PWD" => $passwd_enc,
        "M_PHONE" => $m_phone,
        "M_EMAIL" => $m_email,
        "M_GUBUN" => $m_gubun,
        "M_ADDR" => $m_addr,
        "M_POST_CD" => $m_post_cd,
        "M_ADDR_DETAIL" => $m_addr_detail,
        "M_BIZ_NO" => $m_biz_no,
        "M_KSIC" => $m_ksic,
        "M_KSIC_DETAIL" => $m_ksic_detail,
        "EMAIL_TF" => $email_tf,
        "MESSAGE_TF" => $message_tf,
        "USE_TF" => $use_tf,
        "REG_ADM" => $_SESSION['s_adm_no']
    );
    $result =  insertMember($conn, $arr_data);
    //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "멤버 등록 (멤버명 : ".$m_name.") ", "Insert");
}

// 아이디 중복체크를 따로 만드렀어염
if ($mode == "CHK_ID") {
    $m_id = isset($_POST["m_id"]) ? $_POST["m_id"] : "";

    if ($m_id !== "") {
        $is_duplicate = dupMemberIdChk($conn, $m_id);
        echo json_encode([
            "success" => true,
            "m_id" => $m_id,
            "data" => $is_duplicate
        ]);
    } else {
        echo "error";
    }
    exit;
}

// 이거슨 거시기 2차 표준산업 분류여야요 꺄르르
if ($mode == "KSIC_DETAIL") {
    $m_ksic = isset($_POST["m_ksic"]) ? $_POST["m_ksic"] : "";

    if ($m_ksic !== "") {
        $is_ksic_detail = selectKsicCodeDetail($conn, $m_ksic);
        echo json_encode([
            "success" => true,
            "m_ksic" => $m_ksic,
            "data" => $is_ksic_detail
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "1차 산업 분류 값 누락."
        ]);
    }
    exit;
}

// 수정일 경우
if ($mode == 'U') {
    $m_name         = SetStringToDB($m_name);
    $m_email        = SetStringToDB($m_email);
    $m_phone        = SetStringToDB($m_phone);
    $m_status       = SetStringToDB($m_status);
    $m_role         = SetStringToDB($m_role);

    $reg_date       = SetStringToDB($reg_date);
    $use_tf            = SetStringToDB($use_tf);
    $search_field   = SetStringToDB($search_field);
    $search_str        = SetStringToDB($search_str);

    $arr_data = array(
        "M_NAME" => $m_name,
        "M_EMAIL" => $m_email,
        "M_PHONE" => $m_phone,
        "M_STATUS" => $m_status,
        "M_ROLE" => $m_role,
        "USE_TF" => $use_tf,
        "UP_ADM" => $_SESSION['s_adm_no']
    );

    $result = updateMember($conn, $arr_data, $m_no);

    //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "멤버 정보 수정 (멤버 이름 : ".$m_name.") ", "Update");
}

// 삭제일 경우
if ($mode == 'D') {
    $result = deleteMemberTF($conn, $_SESSION['s_adm_no'], (int)$m_no);
    //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "멤버 삭제 처리 (멤버 번호 : ".(int)$m_no.") ", "Delete");
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

// 상세보기
if ($mode == "S") {
    $arr_rs = selectMember($conn, (int)$m_no);

    echo json_encode($arr_rs, JSON_UNESCAPED_UNICODE);

    $rs_m_no       = trim($arr_rs[0]["M_NO"]);
    $rs_m_name     = trim($arr_rs[0]["M_NAME"]);
    $rs_m_email    = trim($arr_rs[0]["M_EMAIL"]);
    $rs_m_phone    = trim($arr_rs[0]["M_PHONE"]);
    $rs_m_status   = trim($arr_rs[0]["M_STATUS"]);
    $rs_m_role     = trim($arr_rs[0]["M_ROLE"]);
    $rs_disp_seq   = trim($arr_rs[0]["DISP_SEQ"]);
    $rs_use_tf     = trim($arr_rs[0]["USE_TF"]);
    $rs_del_tf     = trim($arr_rs[0]["DEL_TF"]);
    $rs_reg_adm    = trim($arr_rs[0]["REG_ADM"]);
    $rs_reg_date   = trim($arr_rs[0]["REG_DATE"]);
    $rs_up_adm     = trim($arr_rs[0]["UP_ADM"]);
    $rs_up_date    = trim($arr_rs[0]["UP_DATE"]);
    $rs_del_adm    = trim($arr_rs[0]["DEL_ADM"]);
    $rs_del_date   = trim($arr_rs[0]["DEL_DATE"]);

    //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "멤버 조회 (멤버명 : ".$rs_m_name.") ", "Read");

}

#============================================================
# Page process
#============================================================

if ($nPage <> "" && $nPageSize <> 0) {
    $nPage = (int)($nPage);
} else {
    $nPage = 1;
}

if ($nPage <> "" && $nPageSize <> 0) {
    $nPageSize = (int)($nPageSize);
} else {
    $nPageSize = 20;
}

if ($nPageSize == 0) {
    $nPageSize = 20;
}

$nPageBlock    = 10;

#===============================================================
# Get Search list count
#===============================================================

$nListCnt = totalCntMembers($conn, $m_no, $m_gubun, $m_ksic, $email_tf, $message_tf, $use_tf, $del_tf, $search_field, $search_str);

$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1);

if ((int)($nTotalPage) < (int)($nPage)) {
    $nPage = $nTotalPage;
}

if ($result) {
    $strParam = $strParam . "?nPage=" . $nPage . "&nPageSize=" . $nPageSize . "&search_field=" . $search_field . "&search_str=" . $search_str;

    //$arr_rs = listMember($conn, $m_no, $m_name, $m_email, $m_phone, $m_status, $m_role, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

    //$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 회원 리스트 조회", "List");

?>
    <!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $g_charset ?>" />
        <script language="javascript">
            alert('정상 처리 되었습니다.');
            document.location.href = "seo_list.php<?= $strParam ?>";
        </script>
    </head>

    </html>
<?
    exit;
}
?>

<!doctype html>
<html lang="ko">

<head>
    <meta charset="<?= $g_charset ?>">
    <title><?= $g_title ?></title>
    <link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
    <link rel="shortcut icon" href="/manager/images/mobile.png" />
    <link rel="apple-touch-icon" href="/manager/images/mobile.png" />
    <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
    <script type="text/javascript" src="../js/ui.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

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

        function js_pwd_chk() {
            // 비밀번호 검증 부분도 공통으로 빼고싶다잉...
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
                pwdMessage.innerText = "사용가능한 비밀번호여";
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

        // function formatBizNo(input) {
        //     var bizNo = input.value.replace(/[^0-9]/g, "");
        //     if (bizNo.length > 3) bizNo = bizNo.replace(/^(\d{3})(\d+)/, "$1-$2");
        //     if (bizNo.length > 6) bizNo = bizNo.replace(/^(\d{3})-(\d{2})(\d+)/, "$1-$2-$3");
        //     input.value = bizNo.substring(0, 12); // 최대 길이 제한
        // }

        function formatBizNo(input) {
            // 세개니까 하나로 합친다잉?
            const part1 = document.getElementById("biz_no1").value.replace(/[^0-9]/g, "");
            const part2 = document.getElementById("biz_no2").value.replace(/[^0-9]/g, "");
            const part3 = document.getElementById("biz_no3").value.replace(/[^0-9]/g, "");

            const formattedBizNo = `${part1}-${part2}-${part3}`;

            document.getElementById("m_biz_no").value = formattedBizNo;

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


        let isDuplicateChecked = false;

        function js_idDupChk() {
            var m_id = document.getElementById("m_id").value.trim();
            // 이걸 공통으로 빼고 싶어염 아이디 정규식... 쩝
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
                url: "seo_write.php",
                data: {
                    mode: "CHK_ID", 
                    m_id: m_id
                },
                dataType: "json", 
                success: function(response) {

                    if (response.data === 0) {
                        alert("사용 가능한 아이디입니다.");
                        isDuplicateChecked = true;
                    } else if (response.data === 1) {
                        alert("이미 사용 중인 아이디입니다.");
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

        // 2차 표준 산업 분류
        function js_m_ksic(selectElement) {
            var ksic = selectElement.value; 

            if (!ksic) {
                alert("1차 산업 분류를 선택해주세요.");
                return;
            }

            alert("현재 선택된 1차 산업 분류 = " + ksic);
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
                    var ksicDetailSelector = document.getElementById("ksic_detail_selector");

                    ksicDetailSelector.innerHTML = '<option value="">2차 산업 분류를 선택하세요</option>';
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




        function js_save() {
            var frm = document.frm;

            var m_no = "<?= (int)$m_no ?>";

            var m_phone = frm.m_phone.value.trim();
            var formattedPhone = m_phone.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
            // 쩝... 또 바꾸기 귀찮다...
            frm.m_phone.value = formattedPhone;

            // 아이디 중복 체크 확인해야대 안하면 혼나 ㅠㅠ
            if (!isDuplicateChecked) {
                alert("아이디 중복 체크를 진행해주세요.");
                document.getElementById('m_id').focus();
                return;
            }

            if (isNull(m_no) || parseInt(m_no) == 0) {
                frm.mode.value = "I";
            } else {
                frm.mode.value = "U";
                frm.m_no.value = frm.m_no.value;
            }

            if (!js_pwd_chk()) {
                alert("비밀번호를 다시 확인해주세요.");
                return;
            }

            // 회원 구분
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

            // 사용 여부
            if (frm.rd_use_tf) {
                var selectedUse = document.querySelector('input[name="rd_use_tf"]:checked');
                if (selectedUse) {
                    frm.use_tf.value = selectedUse.value;
                }
            }

            alert("수정이냐 아니냐 0이면 등록임 " + m_no);

            frm.target = "";
            frm.action = "seo_write.php";
            frm.submit();
        }


        function js_list() {
            var frm = document.frm;

            frm.method = "get";
            frm.action = "seo_list.php";
            frm.submit();
        }
    </script>

    <style>
        /* Flexbox 설정 */
    .biz-no-wrap {
        display: flex;
        align-items: center;
        gap: 8px; /* 입력 필드 간 간격 */
    }

    /* 입력 필드 스타일 */
    .biz-no-wrap .inp {
        width: 60px; /* 각 입력 필드의 너비 */
        height: 48px; /* 높이 조정 */
        padding: 0 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    /* 대시(-) 스타일 */
    .biz-no-wrap .dash {
        font-size: 18px;
        color: #333;
    }
    </style>
</head>

<body>
    <div id="wrap">
        <div class="cont_aside">
            <?
            #====================================================================
            # common left_area
            #====================================================================
            require "../../_common/left_area.php";
            ?>
        </div>
        <div id="container">
            <div class="top">
                <?
                #====================================================================
                # common top_area
                #====================================================================
                require "../../_common/top_area.php";
                ?>
            </div>
            <!-- contents start-->
            <div class="contents">
                <?
                #====================================================================
                # common location_area
                #====================================================================
                require "../../_common/location_area.php";
                ?>
                <div class="tit_h3">
                    <h3><?= $p_menu_name ?></h3>
                </div>

                <form name="frm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="seq_no" value="" />
                    <input type="hidden" name="mode" value="" />
                    <input type="hidden" name="menu_cd" value="">
                    <input type="hidden" name="m_no" value="<?= (int)$m_no ?>" />
                    <input type="hidden" name="nPage" value="<?= (int)$nPage ?>" />
                    <input type="hidden" name="nPageSize" value="<?= (int)$nPageSize ?>" />
                    <input type="hidden" name="search_field" value="<?= $search_field ?>">
                    <input type="hidden" name="search_str" value="<?= $search_str ?>">

                    <div class="cont">
                        <div class="tbl_style01 left">
                            <table>
                                <colgroup>
                                    <col style="width:14%" />
                                    <col style="width:36%" />
                                    <col style="width:14%" />
                                    <col />
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>회원 아이디</th>
                                        <td colspan="3">
                                            <input type="text" id="m_id" name="m_id">
                                            <span id="idMessage" style="margin-left: 10px;"></span>
                                            <button type="button" class="button" name="id_dup_chk" id="id_dup_chk" onclick="js_idDupChk()">아이디 중복체크</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>휴대전화번호</th>
                                        <td>
                                            <input type="text" id="m_phone" name="m_phone" oninput="formatPhone(this)" placeholder="" maxlength="11">
                                            <button type="button" class="button" id="m_phone_rq" name="m_phone_rq" onclick="authPhoneRequest()">인증번호 발송</button><br>
                                        </td>
                                    </tr>
                                    <tr id="phone_auth_area">
                                    <th>인증확인</th>
                                            <td>
                                                <input type="text" name="auth_phone" id="auth_phone" maxlength="11" class="onlyNum" value="">
                                                <button class="button" id="auth_phone_chk" name="auth_phone_chk" onclick="authPhoneCheck()">인증확인</button>
                                            </td>
                                    </tr>

                                    <tr>
                                        <th>이메일</th>
                                        <td>
                                            <input type="text" name="m_email" value="<?= $rs_m_email ?>" placeholder="이메일 입력" />
                                        </td>
                                        
                                        <!-- <td><input type="text" name="m_email" value="<?= $rs_m_email ?>" placeholder="이메일 입력"/></td> -->
                                    </tr>

                                    <tr>
                                        <th>비밀번호</th>
                                        <td colspan="3">
                                            <input type="password" id="m_pwd" name="m_pwd" required oninput="js_pwd_chk()">
                                            <span id="pwdMessage" style="margin-left: 10px;"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>비밀번호 확인</th>
                                        <td colspan="3">
                                            <input type="password" id="m_pwd_chk" name="m_pwd_chk" required oninput="js_pwd_chk()">
                                            <span id="pwdChkMessage" style="margin-left: 10px;"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>회원 구분</th>
                                        <td colspan="3">
                                            <input type="radio" class="radio" name="rd_m_gubun" id="m_gubun_p" value="P" <? echo "checked" ?> <? #if (($rs_m_gubun =="P") || ($rs_m_gubun =="")) echo "checked"; 
                                                                                                                                                ?>> 개인회원 <span style="width:20px;"></span>
                                            <input type="radio" class="radio" name="rd_m_gubun" id="m_gubun_c" value="C" <?  #if ($rs_m_gubun =="C") echo "checked"; 
                                                                                                                            ?>> 기업회원
                                            <input type="hidden" name="m_gubun" value="<?= $rs_m_gubun ?>">
                                        </td>
                                    </tr>

                                    <tr id="biz_no_area">
                                    <th>사업자등록번호</th>
                                    <td>
                                        <div class="biz-no-wrap">
                                            <input type="text" name="biz_no1" id="biz_no1" maxlength="3" placeholder="XXX" title="사업자등록번호 1" class="inp" oninput="formatBizNo()">
                                            <span class="dash">-</span>
                                            <input type="text" name="biz_no2" id="biz_no2" maxlength="2" placeholder="XX" title="사업자등록번호 2" class="inp" oninput="formatBizNo()">
                                            <span class="dash">-</span>
                                            <input type="text" name="biz_no3" id="biz_no3" maxlength="5" placeholder="XXXXX" title="사업자등록번호 3" class="inp" oninput="formatBizNo()">
                                        </div>
                                        <input type="hidden" name="m_biz_no" id="m_biz_no">
                                    </td>
                                    </tr>

                                    <tr>
                                        <th>주소</th>
                                        <td>
                                            <input type="text" id="m_addr" name="m_addr" readonly>
                                            <button type="button" class="button" onclick="searchAddress()">주소 검색</button>
                                        </td>
                                        <th>우편번호</th>
                                        <td>
                                            <input type="text" id="m_post_cd" name="m_post_cd" readonly>
                                        </td>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>상세주소</th>
                                        <td>
                                            <input type="text" id="m_addr_detail" name="m_addr_detail">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>표준산업분류</th>
                                        <td>
                                            <?= makeSelectBoxOnChange2($conn, "INDUSTRY_CATEGORY", "m_ksic", "184", "선택", "", $rs_industry_category) ?>

                                            <select id="ksic_detail_selector" name="m_ksic_detail">
                                                <option value="">2차 산업 분류를 선택하세요</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>이메일 수신동의</th>
                                        <td colspan="3">
                                            <input type="radio" class="radio" name="rd_email_tf" value="Y" <? if (($rs_email_tf == "Y") || ($rs_email_tf == "")) echo "checked"; ?>> 동의 <span style="width:20px;"></span>
                                            <input type="radio" class="radio" name="rd_email_tf" value="N" <? if ($rs_email_tf == "N") echo "checked"; ?>> 거부
                                            <input type="hidden" name="email_tf" value="<?= $rs_email_tf ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>문자 수신동의</th>
                                        <td colspan="3">
                                            <input type="radio" class="radio" name="rd_message_tf" value="Y" <? if (($rs_message_tf == "Y") || ($rs_message_tf == "")) echo "checked"; ?>> 동의 <span style="width:20px;"></span>
                                            <input type="radio" class="radio" name="rd_message_tf" value="N" <? if ($rs_message_tf == "N") echo "checked"; ?>> 거부
                                            <input type="hidden" name="message_tf" value="<?= $rs_message_tf ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>사용여부</th>
                                        <td colspan="3">
                                            <input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf == "Y") || ($rs_use_tf == "")) echo "checked"; ?>> 활성화 <span style="width:20px;"></span>
                                            <input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf == "N") echo "checked"; ?>> 비활성화
                                            <input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="btn_wrap">
                            <a href="javascript:js_list();" class="button type02">목록</a>

                            <? if ($m_no != null || $m_no > 0) {  ?>
                                <? if ($sPageRight_U == "Y") {  ?>
                                    <button type="button" class="button" onClick="js_save();">수정</button>
                                <? } ?>
                            <? } else {  ?>
                                <? if ($sPageRight_I == "Y") {  ?>
                                    <button type="button" class="button" onClick="js_save();">저장</button>
                                <? } ?>
                            <? } ?>


                            <? if ((int)$m_no <> "") { ?>
                                <? if ($sPageRight_D == "Y") { ?>
                                    <button type="button" class="button type02" onClick="js_delete();">삭제</button>
                                <?    } ?>
                            <? } ?>
                        </div>
                    </div>
            </div>
            <!-- contents end-->

            </form>

        </div>
</body>

</html>
<?
#====================================================================
# DB Close
#====================================================================

db_close($conn);
?>