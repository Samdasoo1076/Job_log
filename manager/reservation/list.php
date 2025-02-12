<? session_start(); ?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
#====================================================================
# DB Include, DB Connection
#====================================================================
require "../../_classes/com/db/DBUtil.php";

$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
$menu_right = "RE002"; // 메뉴마다 셋팅 해 주어야 합니다

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
require "../../_classes/biz/reservation/reservation.php";
require "../../_classes/biz/online/online.php";
require "../../_classes/biz/admin/admin.php";

#==============================================================================
# Request Parameter
#==============================================================================
$m_no = isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$rv_no = isset($_POST["rv_no"]) && $_POST["rv_no"] !== '' ? $_POST["rv_no"] : (isset($_GET["rv_no"]) ? $_GET["rv_no"] : '');

# 공통 컬럼 정의
$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf = isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');
$chk = isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

# 페이징 관련
$nPage = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_field = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

if ($mode == "T") {
    updateAgreeTF($conn, $use_tf, $_SESSION['s_adm_no'], (int) $rv_no);
    $result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "예약 승인 여부 변경 (예약번호 : " . (int) $rv_no . ")", "Update");
}

if ($mode == "D") {

    // 삭제 권한 관련 입니다.
    $del_ok = "N";
    if ($_SESSION['s_adm_no'] && $arr_page_nm[1] == "manager") {
        if ($sPageRight_D == "Y") {
            $del_ok = "Y";
        }
    }

    if ($del_ok == "Y") {
        $row_cnt = is_null($chk) ? 0 : count($chk);
        for ($k = 0; $k < $row_cnt; $k++) {
            $tmp_rv_no = (int)$chk[$k];
            $result = deleteReservationTF($conn, $_SESSION['s_adm_no'], $tmp_rv_no);
            $result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "".$m_id." 예약 내용 삭제 (예약 번호 : ".$tmp_rv_no.") ", "Delete");
        }
    }
}

#============================================================
# Page process
#============================================================
if ($nPage == 0)
    $nPage = "1";

#List Parameter

$nPage = SetStringToDB($nPage);
$nPageSize = SetStringToDB($nPageSize);
$nPage = trim($nPage);
$nPageSize = trim($nPageSize);

# 검색 조건 추가
$search_field = SetStringToDB($search_field);
$search_str = SetStringToDB($search_str);
$search_field = trim($search_field);
$search_str = trim($search_str);

$mode = SetStringToDB($mode);
$m_no = SetStringToDB($m_no);
$use_tf = SetStringToDB($use_tf);
$del_tf = "N";

if ($nPage <> "" && $nPageSize <> 0) {
    $nPage = (int) ($nPage);
} else {
    $nPage = 1;
}

if ($nPage <> "" && $nPageSize <> 0) {
    $nPageSize = (int) ($nPageSize);
} else {
    $nPageSize = 20;
}

if ($nPageSize == 0) {
    $nPageSize = 20;
}

$nPageBlock = 10;

#===============================================================
# Get Search list count
#===============================================================

$nListCnt = totalCntReservationAdmin($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, null);

$nTotalPage = (int) (($nListCnt - 1) / $nPageSize + 1);

if ((int) ($nTotalPage) < (int) ($nPage)) {
    $nPage = $nTotalPage;
}

$arr_rs = listReservations2($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str, null, $nPage, $nPageSize, $nListCnt);

?>
<!doctype html>
<html lang="ko">

<head>
    <meta charset="<?= $g_charset ?>">
    <title><?= $g_title ?></title>
    <link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
    <link rel="shortcut icon" href="/manager/images/mobile.png" />
    <link rel="apple-touch-icon" href="/manager/images/mobile.png" />
    <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
    <script type="text/javascript" src="../js/ui.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script language="javascript">

        // 조회 버튼 클릭 시 
        function js_search() {
            var frm = document.frm;

            frm.nPage.value = "1";
            frm.method = "post";
            frm.target = "";
            frm.action = "list.php";
            frm.submit();
        }

        function js_reload() {
            var frm = document.frm;
            frm.method = "post";
            frm.target = "";
            frm.action = "list.php";
            frm.submit();
        }


        function js_check() {
            var frm = document.frm;

            check = document.getElementsByName("chk[]");

            if (frm.all_chk_no.checked) {
                for (i = 0; i < check.length; i++) {
                    check.item(i).checked = true;
                }
            } else {
                for (i = 0; i < check.length; i++) {
                    check.item(i).checked = false;
                }
            }
        }

			function js_agreeTf_chagne(rv_no, idx, selectElement) {

				bChangeOK = confirm('예약여부를 변경하시겠습니까?');

				if (bChangeOK) {

					// const mode = "AGREETF_CHANGE";
					const mode = "Reservation_Control";
					const agreetf = selectElement.value;
					// AJAX 요청
					$.ajax({
						url: "/_common/ajax_reservation_dml.php",
						type: "POST",
						data: {
								mode: mode,
								rv_no: rv_no,
								rv_agree_tf : agreetf
						},
						dataType: "json"
					}).done(function(response) {
						if (response.success) {
							$("#old_rv_agree_tf_"+idx).val($("#rv_agree_tf_"+idx).val());
							alert(response.message);
						} else {
							alert(response.message);
						}
						}).fail(function (xhr, status, error) {
							console.error("AJAX 요청 실패: ", error);
							alert("문제가 발생했습니다. 다시 시도해주세요.");
					});

				} else {

					$("#rv_agree_tf_"+idx).val($("#old_rv_agree_tf_"+idx).val());

				}
			}


    function js_delete() {
        var frm = document.frm;
        var chk_cnt = 0;

        check = document.getElementsByName("chk[]");

        for (i = 0; i < check.length; i++) {
            if (check.item(i).checked == true) {
                chk_cnt++;
            }
        }

        if (chk_cnt == 0) {
            alert("선택 하신 자료가 없습니다.");
        } else {

            bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');

            if (bDelOK == true) {
                frm.mode.value = "D";
                frm.target = "";
                frm.action = "<?= $_SERVER['PHP_SELF'] ?>";
                frm.submit();
            }
        }
    }

    function js_excel_list() {
        bExcelOK = confirm('엑셀 파일을 다운로드 하시겠습니까?');

        if (bExcelOK === true) {
            var frm = document.frm;
            frm.target = "";
            frm.action = "<?= str_replace("list", "excel_list", $_SERVER["PHP_SELF"]) ?>";
            frm.submit();
        }
    }


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


                <div class="tbl_top">
                    <div class="left"><span class="txt">총 <span class="txt_c01"><?= number_format($nListCnt) ?></span>
                            건</span></div>

                    <div class="right">
                        <form id="searchBar" name="frm" method="post" action="javascript:js_search();">
                            <input type="hidden" name="m_no" value="">
                            <input type="hidden" name="rv_no" value="">
                            <input type="hidden" name="rv_agree_tf" value="">
                            <input type="hidden" name="use_tf" value="">
                            <input type="hidden" name="menu_cd" value="<? //=$menu_cd ?>">
                            <input type="hidden" name="mode" value="">
                            <input type="hidden" name="nPage" value="<?= (int) $nPage ?>">
                            <input type="hidden" name="nPageSize" value="<?= (int) $nPageSize ?>">

                            <div class="fl_wrap">

                                <select name="search_field">
                                    <option value="ALL" <?= ($search_field == "ALL") ? "selected" : "" ?>>전체</option>
                                    <option value="ROOM_NAME" <?= ($search_field == "ROOM_NAME") ? "selected" : "" ?>>시설명
                                    </option>
                                    <option value="RV_DATE" <?= ($search_field == "RV_DATE") ? "selected" : "" ?>>예약 날짜
                                    </option>
                                    <option value="RV_AGREE_TF" <?= ($search_field == "RV_AGREE_TF") ? "selected" : "" ?>>예약 여부
                                    </option>

                                </select>

                                <input type="text" value="<?= $search_str ?>" name="search_str" id="search_str"
                                    placeholder="검색어 입력" />
                                <button type="button" class="button" onClick="js_search();">검색</button>
                            </div>
                    </div>

                </div>

                <div class="tbl_style01 center">
                    <table id='t'>
                        <colgroup>
                            <col style="width:3%" /> <!-- chk -->
                            <col style="width:10%" /> <!-- 예약번호 -->
                            <col style="width:8%" /> <!-- 회원아이디 -->
                            <col style="width:8%" /> <!-- 시설명 -->
                            <col style="width:17%" /> <!-- 사용목적 -->
                            <col style="width:9%" /> <!-- 예약날짜 -->
                            <col style="width:10%" /> <!-- 시작시간 - 종료시간 -->
                            <col style="width:8%" /> <!-- 시설대여기자재 -->
                            <col style="width:4%" /> <!-- 사용인원 -->
                            <col style="width:5%" /> <!-- 이용료 -->
                            <col style="width:10%" /> <!-- 예약관련메모 -->
                            <col style="width:8%" /> <!-- 예약여부 -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
                                <th scope="col">예약번호</th>
                                <th scope="col">회원아이디</th>
                                <th scope="col">시설명</th>
                                <th scope="col">사용목적</th>
                                <th scope="col">예약날짜</th>
                                <th scope="col">시작시간 - 종료시간</th>
                                <th scope="col">시설대여기자재</th>
                                <th scope="col">사용인원</th>
                                <th scope="col">이용료</th>
                                <th scope="col">예약관련메모</th>
                                <th scope="col">예약여부</th>
                            </tr>
                            <?
                            $nCnt = 0;

                            ?>

                            <?
                            $nCnt = 0;

                            if (sizeof($arr_rs) > 0) {

                                for ($j = 0; $j < sizeof($arr_rs); $j++) {

                                    $rn = trim($arr_rs[$j]["rn"]);
                                    $RV_NO = trim($arr_rs[$j]["RV_NO"]);
                                    $R_NO = trim($arr_rs[$j]["R_NO"]);
                                    $M_ID = trim($arr_rs[$j]["M_ID"]);
                                    $ROOM_NAME = trim($arr_rs[$j]["ROOM_NAME"]);
                                    $RV_PURPOSE = trim($arr_rs[$j]["RV_PURPOSE"]);
                                    $RV_DATE = trim($arr_rs[$j]["RV_DATE"]);

                                    $RV_START_TIME = trim($arr_rs[$j]["RV_START_TIME"]);

                                    $RV_END_TIME = trim($arr_rs[$j]["RV_END_TIME"]);

                                    $RV_EQUIPMENT = trim($arr_rs[$j]["RV_EQUIPMENT"]);

                                    $RV_USE_COUNT = trim($arr_rs[$j]["RV_USE_COUNT"]);

                                    $RV_AGREE_TF = trim($arr_rs[$j]["RV_AGREE_TF"]);

                                    $RV_COST = trim($arr_rs[$j]["RV_COST"]);

                                    $RV_MEMO = trim($arr_rs[$j]["RV_MEMO"]);
                                    $REG_DATE = trim($arr_rs[$j]["REG_DATE"]);
                                    $USE_TF = trim($arr_rs[$j]["USE_TF"]);
                                    $DEL_TF = trim($arr_rs[$j]["DEL_TF"]);

                                    $REG_DATE = date("Y-m-d H:i", strtotime($REG_DATE));

                                    if ($RV_AGREE_TF == "0") {
                                        $STR_RV_AGREE_TF = "<font color='navy'>승인대기</font>";
                                    } else if ($RV_AGREE_TF == "1") {
                                        $STR_RV_AGREE_TF = "<font color='red'>승인</font>";
                                    } else if ($RV_AGREE_TF == "2") {
                                        $STR_RV_AGREE_TF = "<font color='red'>미승인</font>";
                                    } else if ($RV_AGREE_TF == "3") {
                                        $STR_RV_AGREE_TF = "<font color='red'>취소</font>";
                                    } else if ($RV_AGREE_TF == "4") {
                                        $STR_RV_AGREE_TF = "<font color='red'>회원취소</font>";
                                    }

                                    if ($RV_EQUIPMENT == "E001") {
                                        $STR_RV_EQUPMENT = "빔프로젝터";
                                    } else if ($RV_EQUIPMENT == "E002") {
                                        $STR_RV_EQUPMENT = "마이크";
                                    } else if ($RV_EQUIPMENT == "E003") {
                                        $STR_RV_EQUPMENT = "엠프";
                                    } else if ($RV_EQUIPMENT == "E009") {
                                        $STR_RV_EQUPMENT = "빔프로젝터 & 엠프";
                                    } else {
                                        $STR_RV_EQUPMENT = "미사용";
                                    }


                                    ?>

                                    <tr style="<?= $top_style ?> <?= $view_style ?>">

                                        <td><input type="checkbox" name="chk[]" value="<?= $RV_NO ?>"></td>
                                        <td><a href="detail.php?rv_no=<?= $RV_NO ?>"><?= $R_NO ?></a></td>
                                        <td><?= $M_ID ?></td>
                                        <td><?= $ROOM_NAME ?></td>
                                        <td><?= $RV_PURPOSE ?></td>
                                        <td><?= $RV_DATE ?></td>
                                        <td><?= $RV_END_TIME ?></td>
                                        <td><?= $STR_RV_EQUPMENT ?></td>
                                        <td><?= $RV_USE_COUNT ?></td>
                                        <td><?= $RV_COST ?> 원</td>
                                        <td><?= $RV_MEMO ?></td>

                                        <td style="text-align:center" class="filedown">
                                            <select name="rv_agree_tf" id="rv_agree_tf_<?=$j?>" onchange="js_agreeTf_chagne('<?=$RV_NO ?>', '<?=$j?>', this)">
                                                <option value="0" <?= ($RV_AGREE_TF === '0') ? 'selected' : '' ?>>승인대기</option>
                                                <option value="1" <?= ($RV_AGREE_TF === '1') ? 'selected' : '' ?>>승인</option>
                                                <option value="2" <?= ($RV_AGREE_TF === '2') ? 'selected' : '' ?>>미승인</option>
                                                <option value="3" <?= ($RV_AGREE_TF === '3') ? 'selected' : '' ?>>취소</option>
                                                <option value="4" <?= ($RV_AGREE_TF === '4') ? 'selected' : '' ?>>회원취소</option>
                                            </select>
																						<input type="hidden" name="old_rv_agree_tf" id="old_rv_agree_tf_<?=$j?>" value="<?=$RV_AGREE_TF?>">
                                        </td>

                                    </tr>
                                    <?
                                }
                            } else {
                                ?>
                                <tr>
                                    <td height="50" align="center" colspan="11" style="text-align: center;">데이터가 없습니다. </td>
                                </tr>
                                <?
                            }
                            ?>
                            </tbody>
                    </table>
                </div>
                <div class="left">


                </div>
                <div class="btn_wrap">

                    <div class="wrap_paging">
                        <?
                        # ==========================================================================
                        #  페이징 처리
                        # ==========================================================================
                        if (sizeof($arr_rs) > 0) {
                            #$search_field		= trim($search_field);
                            #$search_str			= trim($search_str);
                            $strParam = $strParam . "&nPageSize=" . $nPageSize . "&search_field=" . $search_field . "&search_str=" . $search_str;
                            ?>
                            <?= Image_PageList($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>
                            <?
                        }
                        ?>
                    </div>
                    <div class="right">
                        <!-- <button type="button" class="button" onClick="js_write();">등록</button> -->
                        <? if ($sPageRight_F == "Y") { ?>
                            <button type="button" class="button" onClick="js_excel_list();">검색한 자료 엑셀로 받기</button>
                        <? } ?>

                        <? if ($sPageRight_D == "Y") { ?>
                            <button type="button" class="button type02" onClick="js_delete();">삭제</button>
                        <? } ?>


                    </div>
                </div>
            </div>
            </form>
            <!-- contents end-->

        </div>
</body>

</html>
<?
#====================================================================
# DB Close
#====================================================================

db_close($conn);
?>