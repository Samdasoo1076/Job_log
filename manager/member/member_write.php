<? session_start(); ?>
<!-- php start -->
<?

header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");

# =============================================================================
# File Name    : member_write.php
# Modlue       : 
# Writer       : Seo Hyun Seok 
# Create Date  : 2024-12-04
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
$menu_right = "ME001"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
require "../../_common/common_header.php";

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
require "../../_classes/biz/admin/admin.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/reservation/reservation.php";

#====================================================================
# DML Process
#====================================================================

$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$m_id = isset($_POST["m_id"]) && $_POST["m_id"] !== '' ? $_POST["m_id"] : (isset($_GET["m_id"]) ? $_GET["m_id"] : '');

$m_pwd = isset($_POST["m_pwd"]) && $_POST["m_pwd"] !== '' ? $_POST["m_pwd"] : (isset($_GET["m_pwd"]) ? $_GET["m_pwd"] : '');
$m_phone = isset($_POST["m_phone"]) && $_POST["m_phone"] !== '' ? $_POST["m_phone"] : (isset($_GET["m_phone"]) ? $_GET["m_phone"] : '');

$m_email = isset($_POST["m_email"]) && $_POST["m_email"] !== '' ? $_POST["m_email"] : (isset($_GET["m_email"]) ? $_GET["m_email"] : '');
$m_gubun = isset($_POST["m_gubun"]) && $_POST["m_gubun"] !== '' ? $_POST["m_gubun"] : (isset($_GET["m_gubun"]) ? $_GET["m_gubun"] : '');
$m_addr = isset($_POST["m_addr"]) && $_POST["m_addr"] !== '' ? $_POST["m_addr"] : (isset($_GET["m_addr"]) ? $_GET["m_addr"] : '');
$m_post_cd = isset($_POST["m_post_cd"]) && $_POST["m_post_cd"] !== '' ? $_POST["m_post_cd"] : (isset($_GET["m_post_cd"]) ? $_GET["m_post_cd"] : '');
$m_addr_detail = isset($_POST["m_addr_detail"]) && $_POST["m_addr_detail"] !== '' ? $_POST["m_addr_detail"] : (isset($_GET["m_addr_detail"]) ? $_GET["m_addr_detail"] : '');
$m_biz_no = isset($_POST["m_biz_no"]) && $_POST["m_biz_no"] !== '' ? $_POST["m_biz_no"] : (isset($_GET["m_biz_no"]) ? $_GET["m_biz_no"] : '');
$m_ksic = isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');
$m_ksic_detail = isset($_POST["m_ksic_detail"]) && $_POST["m_ksic_detail"] !== '' ? $_POST["m_ksic_detail"] : (isset($_GET["m_ksic_detail"]) ? $_GET["m_ksic_detail"] : '');
$email_tf = isset($_POST["email_tf"]) && $_POST["email_tf"] !== '' ? $_POST["email_tf"] : (isset($_GET["email_tf"]) ? $_GET["email_tf"] : '');
$message_tf = isset($_POST["message_tf"]) && $_POST["message_tf"] !== '' ? $_POST["message_tf"] : (isset($_GET["message_tf"]) ? $_GET["message_tf"] : '');

# 공통 컬럼 정의
$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

$nPage = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_field = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

// 상세보기
if ($mode == "S") {
    $arr_rs = selectMember($conn, (int) $m_no);

    // echo json_encode($arr_rs, JSON_UNESCAPED_UNICODE);

    $rs_m_no = trim($arr_rs[0]["M_NO"]);
    $rs_m_id = trim($arr_rs[0]["M_ID"]);
    $rs_m_pwd = trim($arr_rs[0]["M_PWD"]);
	$rs_m_name = trim($arr_rs[0]["M_NAME"]);
	$rs_m_organ_name = trim($arr_rs[0]["M_ORGAN_NAME"]);

    $rs_m_email = trim($arr_rs[0]["M_EMAIL"]);
    $m_email_dec = decrypt($key, $iv, $rs_m_email);

    $rs_m_phone = trim($arr_rs[0]["M_PHONE"]);
    $m_phone_dec = decrypt($key, $iv, $rs_m_phone);

    $rs_m_addr = trim($arr_rs[0]["M_ADDR"]);
    $m_addr_dec = decrypt($key, $iv, $rs_m_addr);

    $rs_m_post_cd = trim($arr_rs[0]["M_POST_CD"]);
    $m_post_cd_dec = decrypt($key, $iv, $rs_m_post_cd);

    $rs_m_addr_detail = trim($arr_rs[0]["M_ADDR_DETAIL"]);
    $m_addr_detail_dec = decrypt($key, $iv, $rs_m_addr_detail);

    $rs_m_gubun = trim($arr_rs[0]["M_GUBUN"]);
    $rs_m_biz_no = trim($arr_rs[0]["M_BIZ_NO"]);
    $rs_m_ksic = trim($arr_rs[0]["M_KSIC"]);
    $rs_m_ksic_detail = trim($arr_rs[0]["M_KSIC_DETAIL"]);
    $rs_email_tf = trim($arr_rs[0]["EMAIL_TF"]);
    $rs_message_tf = trim($arr_rs[0]["MESSAGE_TF"]);
    $rs_disp_seq = trim($arr_rs[0]["DISP_SEQ"]);
    $rs_use_tf = trim($arr_rs[0]["USE_TF"]);
    $rs_del_tf = trim($arr_rs[0]["DEL_TF"]);
    $rs_reg_adm = trim($arr_rs[0]["REG_ADM"]);
    $rs_reg_date = trim($arr_rs[0]["REG_DATE"]);
    $rs_up_adm = trim($arr_rs[0]["UP_ADM"]);
    $rs_up_date = trim($arr_rs[0]["UP_DATE"]);
    $rs_del_adm = trim($arr_rs[0]["DEL_ADM"]);
    $rs_del_date = trim($arr_rs[0]["DEL_DATE"]);
    $search_period = "";

    $result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "회원 상세 조회 (회원 아이디 : " . $rs_m_id . ") ", "Read");

    $reservations = getReservationsByMember($conn, $m_no, null);

    $nListCnt = totalCntReservation($conn, $m_no, $rs_use_tf, $rs_del_tf, $search_field, $search_str, $search_period);
}
?>
<!doctype html>
<lang="ko">

    <head>
        <meta charset="<?= $g_charset ?>">
        <title><?= $g_title ?></title>
        <link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
        <link rel="shortcut icon" href="/manager/images/mobile.png" />
        <link rel="apple-touch-icon" href="/manager/images/mobile.png" />
        <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
        <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
            crossorigin="anonymous"></script>
        <script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
        <script type="text/javascript" src="../js/jquery-ui.js"></script>
        <script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
        <script type="text/javascript" src="../js/ui.js"></script>
        <script type="text/javascript" src="../js/common.js"></script>
        <script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->
        <script language="javascript">
            $(document).ready(function () {
            });

            function js_list() {
                var frm = document.frm;

                frm.method = "get";
                frm.action = "member_list.php";
                frm.submit();
            }
            $(document).ready(function () {

            });


            // function js_rv_detail(rv_no, m_no) {
            //     $.ajax({
            //         url: "/_common/ajax_reservation_dml.php", // 데이터를 요청할 서버의 URL
            //         type: "POST",
            //         data: {
            //             mode: "GET_RESERVATION_DETAIL",
            //             rv_no: rv_no,
            //             m_no: m_no
            //         },
            //         dataType: "json",
            //         success: function (response) {
            //             if (response.success) {
            //                 // 모달의 내용을 업데이트하는 함수 호출
            //                 updateModal(response.data);
            //                 // 모달을 활성화
            //                 $('#defaultModal').modal('show');
            //             } else {
            //                 alert("오류: " + response.message);
            //             }
            //         },
            //         error: function (xhr, status, error) {
            //             alert("AJAX 요청에 실패했습니다: " + error);
            //         }
            //     });
            // }
            // function updateModal(data) {
            //     var reservationDetails = data[0]; // 배열의 첫 번째 객체를 가져옴
            //     var modalBody = $('#defaultModal .modal-body');
            //     modalBody.empty(); // 기존 내용을 비우고 새로운 내용을 삽입

            //     var html = `
            //     <p class="tit"><em>${reservationDetails.M_ID}</em>님의 예약 내역입니다.</p>
            //     <div class="info top">
            //     <dl>
            //         <dt>예약 요청날짜</dt>
            //         <dd>${reservationDetails.REG_DATE}</dd>
            //     </dl>
            //     </div>
            //     <p><strong>연락처:</strong> ${reservationDetails.M_PHONE}</p>
            //     <p><strong>승인 여부:</strong> ${reservationDetails.RV_AGREE_TF === 'Y' ? '승인됨' : '미승인'}</p>
            //     <p><strong>예약 시설명:</strong> ${reservationDetails.ROOM_NAME}</p>
            //     <p><strong>이용 인원:</strong> ${reservationDetails.RV_USE_COUNT}명</p>
            //     <p><strong>사용 목적:</strong> ${reservationDetails.RV_PURPOSE}</p>
            //     <p><strong>시설 방문 날짜:</strong> ${reservationDetails.RV_DATE}</p>
            //     <p><strong>이용 시간:</strong> ${reservationDetails.RV_START_TIME} ~ ${reservationDetails.RV_END_TIME}</p>`;

            //     modalBody.html(html); // 새로운 내용으로 모달을 업데이트합니다.
            // }




        </script>

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
                        <input type="hidden" name="m_no" value="<?= (int) $m_no ?>" />
                        <input type="hidden" name="nPage" value="<?= (int) $nPage ?>" />
                        <input type="hidden" name="nPageSize" value="<?= (int) $nPageSize ?>" />
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
                                            <th>가입 날짜</th>
                                            <td colspan="3">
                                                <?= date('Y-m-d', strtotime($rs_reg_date)) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>회원 아이디</th>
                                            <td>
                                                <?= $rs_m_id ?>
                                            </td>
											<th>회원명</th>
											<td> 
												<? if($rs_m_gubun == "C") { 
														$m_name = $rs_m_organ_name;
													} else {
														$m_name = $rs_m_name;
													}?>
												<?= $m_name; ?>
											</td>
                                        </tr>

                                        <tr id="phone_auth_area">
                                            <th>전화번호</th>
                                            <td><?= $m_phone_dec ?></td>
                                        </tr>

                                        <tr>
                                            <th>메일 주소</th>
                                            <td><?= $m_email_dec ?></td>
                                        </tr>

                                        <tr>
                                            <th>회원 구분</th>
                                            <td colspan="3">
                                                <!-- 
                                          <input type="radio" class="radio" name="rd_m_gubun" id="m_gubun_p" value="P" <? echo "checked" ?> <? #if (($rs_m_gubun =="P") || ($rs_m_gubun =="")) echo "checked";                                                                                                  ?>> 개인회원 <span style="width:20px;"></span>
                                          <input type="radio" class="radio" name="rd_m_gubun" id="m_gubun_c" value="C" <?  #if ($rs_m_gubun =="C") echo "checked";                                                                  ?>> 기업회원 
                                          <input type="hidden" name="m_gubun" value="<?= $rs_m_gubun ?>">
                                          -->
                                                <?= ($rs_m_gubun === 'C') ? '기업회원' : (($rs_m_gubun === 'P') ? '개인회원' : '회원구분없음') ?>
                                            </td>
                                        </tr>

                                        <tr id="biz_no_area" style="<?= $rs_m_gubun === 'C' ? '' : 'display: none;' ?>">
                                            <th>사업자등록번호</th>
                                            <td>
                                                <?= $rs_m_biz_no ?>
                                                <input type="hidden" name="m_biz_no" id="m_biz_no"
                                                    value="<?= $rs_m_biz_no ?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>주소</th>
                                            <td>
                                                [<?= $m_post_cd_dec ?>] <?= $m_addr_dec ?> <?= $m_addr_detail_dec ?>

                                            </td>
                                            <!-- <th>우편번호</th>
                                        <td>
                                            <input type="text" id="m_post_cd" name="m_post_cd" readonly>
                                        </td> -->

                                        </tr>

                                        <tr>
                                            <th>표준산업분류</th>
                                            <td>
                                                <?= $rs_m_ksic ?> [<?= $rs_m_ksic_detail ?>]
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>이메일 수신동의</th>
                                            <td colspan="3">
                                                <!-- <input type="radio" class="radio" name="rd_email_tf" value="Y" <? if (($rs_email_tf == "Y") || ($rs_email_tf == ""))
                                                    echo "checked"; ?>> 동의 <span style="width:20px;"></span>
                                            <input type="radio" class="radio" name="rd_email_tf" value="N" <? if ($rs_email_tf == "N")
                                                echo "checked"; ?>> 거부-->
                                                <?= ($rs_email_tf === 'Y') ? '동의' : (($rs_email_tf === 'N') ? '거부' : '구분없음') ?>


                                            </td>
                                        </tr>

                                        <tr>
                                            <th>문자 수신동의</th>
                                            <td colspan="3">
                                                <!-- <input type="radio" class="radio" name="rd_message_tf" value="Y" <? if (($rs_message_tf == "Y") || ($rs_message_tf == ""))
                                                    echo "checked"; ?>> 동의 <span style="width:20px;"></span>
                                            <input type="radio" class="radio" name="rd_message_tf" value="N" <? if ($rs_message_tf == "N")
                                                echo "checked"; ?>> 거부 -->
                                                <?= ($rs_message_tf === 'Y') ? '동의' : (($rs_message_tf === 'N') ? '거부' : '구분없음') ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tbl_top">
                                <div class="left">
                                    <span class="txt">시설 예약 이력
                                        <span class="txt_c02"><?= $nListCnt ?></span> 건</span>
                                </div>
                            </div>
                            <div class="tbl_style01 center">
                                <table id='t'>
                                    <colgroup>
                                        <col style="width:16%" />
                                        <col style="width:16%" />
                                        <col style="width:16%" />
                                        <col style="width:16%" />
                                        <col style="width:16%" />
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th scope="col">예약날짜</th>
                                            <th scope="col">예약시설</th>
                                            <th scope="col">시설 이용일</th>
                                            <th scope="col">예약번호</th>
                                            <th scope="col">처리상태</th>
                                            <th scope="col">처리날짜</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? if (!empty($reservations)): ?> <!-- 예약 데이터가 있을 때 -->
                                            <? foreach ($reservations as $reservation): ?>
                                                <tr>
                                                    <td><?= $reservation['REG_DATE'] ?></td> <!-- 포맷된 등록 날짜 -->
                                                    <td><?= $reservation['ROOM_NAME'] ?></td> <!-- 시설 이름 -->
                                                    <td><?= $reservation['RV_DATE'] ?></td> <!-- 예약 날짜 -->
                                                    <td><?= $reservation['R_NO'] ?></td> <!-- 예약 번호 -->                         
                                                    <td>
                                                        <?
                                                        switch ($reservation['RV_AGREE_TF']) {
                                                            case '0':
                                                                echo '승인대기';
                                                                break;
                                                            case '1':
                                                                echo '승인';
                                                                break;
                                                            case '2':
                                                                echo '미승인';
                                                                break;
                                                            case '3':
                                                                echo '취소';
                                                                break;
                                                            case '4':
                                                                echo '회원취소';
                                                                break;
                                                            default:
                                                                echo '상태 미확인';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?= $reservation['UP_DATE']?></td>
                                                </tr>
                                            <? endforeach; ?>
                                        <? else: ?> <!-- 예약 데이터가 없을 때 -->
                                            <tr>
                                                <td colspan="6" style="text-align: center;">예약 내역이 없습니다.</td>
                                            </tr>
                                        <? endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="btn_wrap">
                                <a href="javascript:js_list();" class="button type02">목록</a>
                            </div>
                        </div>
                </div>
                <!-- contents end-->
                <!-- <button type="button" class="ui-btn" data-bs-toggle="modal" data-bs-target="#defaultModal">
                    Default modal
                </button> -->




                </form>

                <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog"
                    aria-labelledby="defaultModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-유형">
                        <div class="modal-content modal-컨텐츠">
                            <div class="modal-header">
                                <h3 class="modal-title" id="defaultModalLabel"></h3>
                                <button type="button" class="ui-btn btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">닫기</button>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
            </div>

    </body>

    </html>

    <?
    #====================================================================
# DB Close
#====================================================================
    
    db_close($conn);
    ?>