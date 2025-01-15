<? session_start(); ?>
<?  #echo "<h1>공통 템플릿입니다. 여기를 지우고 활용하세요구르트.</h1> <br>"; 
?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : seo_list.php
# Modlue       : 
# Writer       : Seo Hyun Seok 
# Create Date  : 2024-11-13
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
// $b_code			= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
$menu_right = "SEO01"; // 메뉴마다 셋팅 해 주어야 합니다


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



#==============================================================================
# Request Parameter
#==============================================================================

#meber_tst 테이블 컬럼 정의
/*
    M_NO,M_ID,M_PWD,M_PHONE,M_EMAIL,M_GUBUN,M_ADDR
    M_POST_CD,M_ADDR_DETAIL,M_BIZ_NO,M_KSIC
    EMAIL_TF,MESSAGE_TF,DISP_SEQ
*/
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
# 공통 컬럼 정의
$mode   =                   isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf =                   isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

$chk                      = isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

# 페이징 관련
$nPage                    = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize                = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
// $con_m_gubun              = '';
// $con_m_msgTf              = '';
// $con_m_emailTf            = '';

$search_field          = isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str            = isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

if ($mode == "T") {
    updateMemberUseTF($conn, $use_tf, $_SESSION['s_adm_no'], (int)$m_no);
    //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 사용여부 변경 (관리자번호 : ".(int)$adm_no.")", "Update");
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
            $tmp_m_no = (int)$chk[$k];
            $result = deleteMemberTF($conn, $_SESSION['s_adm_no'], $tmp_m_no);
            //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "".$b_board_nm." 게시물삭제 (게시물 번호 : ".$tmp_b_no.") ", "Delete");
        }
    }
}

# 이건 테스트얌
$rs_industry_category = "";

#====================================================================
# Request Parameter
#====================================================================
if ($nPage == 0) $nPage = "1";

#List Parameter

$nPage                = SetStringToDB($nPage);
$nPageSize            = SetStringToDB($nPageSize);
$nPage                = trim($nPage);
$nPageSize            = trim($nPageSize);

# 검색 조건 추가
$search_field        = SetStringToDB($search_field);
$search_str            = SetStringToDB($search_str);
$search_field        = trim($search_field);
$search_str            = trim($search_str);

$mode                = SetStringToDB($mode);
$m_no               = SetStringToDB($m_no);
$use_tf                = SetStringToDB($use_tf);
$del_tf = "N";
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
$arr_rs = listMember($conn, $m_gubun, $m_ksic, $email_tf, $message_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt)

//$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 멤버 리스트 조회", "List");

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
        function js_write() {
            menu_cd = document.frm.menu_cd.value;
            document.location = "seo_write.php?menu_cd=" + menu_cd;
        }

        
        function js_excel_list() {
            var frm = document.frm;
            frm.target = "";
            frm.action = "<?=str_replace("list","excel_list",$_SERVER["PHP_SELF"])?>";
            frm.submit();
        }

        function js_toggle(m_no, use_tf) {
            var frm = document.frm;

            bDelOK = confirm('사용 여부를 변경 하시겠습니까?');

            if (bDelOK == true) {

                if (use_tf == "Y") {
                    use_tf = "N";
                } else {
                    use_tf = "Y";
                }

                frm.m_no.value = m_no;
                frm.use_tf.value = use_tf;
                frm.mode.value = "T";
                frm.target = "";
                frm.action = "seo_list.php";
                frm.submit();
            }
        }

        // 조회 버튼 클릭 시 
        function js_search() {
            var frm = document.frm;

            frm.nPage.value = "1";
            frm.method = "post";
            frm.target = "";
            frm.action = "seo_list.php";
            frm.submit();
        }

        function js_reload() {
            var frm = document.frm;
            frm.method = "post";
            frm.target = "";
            frm.action = "seo_list.php";
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

        function js_view(rn, m_no) {

            var frm = document.frm;

            frm.m_no.value = m_no;
            frm.mode.value = "S";
            frm.target = "";
            frm.method = "get";
            frm.action = "seo_write.php";
            frm.submit();
        }

        function js_info() {
            alert("쩝...");
        }

        let clickCounter = 0;

        function js_ucom() {

            clickCounter += 10000;
            document.getElementById("clickCount").innerText = clickCounter;
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
                    <h3><?=$p_menu_name ?></h3>
                </div>


                <div class="tbl_top">
                    <div class="left"><span class="txt">총 <span class="txt_c01"><?= number_format($nListCnt) ?></span> 명</span></div>

                    <div class="right">
                        <form id="searchBar" name="frm" method="post" action="javascript:js_search();">
                            <input type="hidden" name="m_no" value="">
                            <input type="hidden" name="use_tf" value="">
                            <input type="hidden" name="menu_cd" value="<? //=$menu_cd?>">
                            <input type="hidden" name="mode" value="">
                            <input type="hidden" name="nPage" value="<?= (int)$nPage ?>">
                            <input type="hidden" name="nPageSize" value="<?= (int)$nPageSize ?>">

                            <div class="fl_wrap">
                                <select name="m_gubun" id="m_gubun">
                                    <option value="" <? if ($m_gubun == "") echo "selected"; ?>>회원 구분 선택</option>
                                    <option value="P" <? if ($m_gubun == "P") echo "selected"; ?>>솔로여</option>
                                    <option value="C" <? if ($m_gubun == "C") echo "selected"; ?>>단체여</option>
                                </select>

                                <?= makeSelectBox($conn, "INDUSTRY_CATEGORY", "m_ksic", "170", "산업분류인것이여", "", $m_ksic) ?>

                                <select name="message_tf" id="message_tf">
                                    <option value="" <? if ($message_tf == "") echo "selected"; ?>>문자수신여부 선택</option>
                                    <option value="Y" <? if ($message_tf == "Y") echo "selected"; ?>>받을껴</option>
                                    <option value="N" <? if ($message_tf == "N") echo "selected"; ?>>안받을껴</option>
                                </select>
                                <select name="email_tf" id="email_tf">
                                    <option value="" <? if ($email_tf == "") echo "selected"; ?>>이메일수신여부 선택</option>
                                    <option value="Y" <? if ($email_tf == "Y") echo "selected"; ?>>받을껴</option>
                                    <option value="N" <? if ($email_tf == "N") echo "selected"; ?>>안받을껴</option>
                                </select>



                                <input type="text" value="<?= $search_str ?>" name="search_str" id="search_str" placeholder="검색어 입력" />
                                <button type="button" class="button" onClick="js_search();">검색</button>
                            </div>
                    </div>

                </div>

                <div class="tbl_style01 left">
                    <table id='t'>
                        <colgroup>
                            <col style="width:3%" />
                            <col style="width:5%" />
                            <col style="width:10%" />
                            <col style="width:6%" />
                            <col style="width:15%" />
                            <col style="width:15%" />
                            <col style="width:10%" />
                            <col style="width:15%" />
                            <col style="width:10%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
                                <th scope="col">번호</th>
                                <th scope="col">회원아이디</th>
                                <th scope="col">구분</th>
                                <th scope="col">산업분류</th>
                                <th scope="col">주소</th>
                                <th scope="col">전화번호</th>
                                <th scope="col">메일주소</th>
                                <th scope="col">메일수신</th>
                                <th scope="col">문자수신</th>
                                <th scope="col">사용여부</th>
                            </tr>
                            <?
                            $nCnt = 0;

                            ?>

                            <?
                            $nCnt = 0;

                            if (sizeof($arr_rs) > 0) {

                                for ($j = 0; $j < sizeof($arr_rs); $j++) {

                                    $rn                         = trim($arr_rs[$j]["rn"]);
                                    $M_NO                       = trim($arr_rs[$j]["M_NO"]);
                                    $M_ID                       = trim($arr_rs[$j]["M_ID"]);
                                    $M_PWD                      = trim($arr_rs[$j]["M_PWD"]);
                                    $M_EMAIL                    = trim($arr_rs[$j]["M_EMAIL"]);
                                    $M_PHONE                    = trim($arr_rs[$j]["M_PHONE"]);
                                    $M_GUBUN                    = trim($arr_rs[$j]["M_GUBUN"]);
                                    $M_ADDR                     = trim($arr_rs[$j]["M_ADDR"]);
                                    $M_POST_CD                  = trim($arr_rs[$j]["M_POST_CD"]);
                                    $M_ADDR_DETAIL              = trim($arr_rs[$j]["M_ADDR_DETAIL"]);
                                    $M_BIZ_NO                     = trim($arr_rs[$j]["M_BIZ_NO"]);
                                    $M_KSIC                     = trim($arr_rs[$j]["M_KSIC"]);
                                    $EMAIL_TF                     = trim($arr_rs[$j]["EMAIL_TF"]);
                                    $MESSAGE_TF                     = trim($arr_rs[$j]["MESSAGE_TF"]);
                                    $REG_DATE                   = trim($arr_rs[$j]["REG_DATE"]);
                                    $USE_TF                     = trim($arr_rs[$j]["USE_TF"]);
                                    $DEL_TF                     = trim($arr_rs[$j]["DEL_TF"]);

                                    $REG_DATE = date("Y-m-d H:i", strtotime($REG_DATE));

                                    if ($USE_TF == "Y") {
                                        $STR_USE_TF = "<font color='navy'>사용</font>";
                                    } else {
                                        $STR_USE_TF = "<font color='red'>사용안함</font>";
                                    }
                            ?>
                            <tr style="<?= $top_style ?> <?= $view_style ?>">
                                <td><input type="checkbox" name="chk[]" value="<?= $M_NO ?>"></td>
                                <td style="text-align:center">
                                    <?= $M_NO ?>
                                </td>
                                <td style="text-align:center" class="filedown">
                                    <a href="javascript:js_view('<?= $rn ?>','<?= $M_NO ?>');"><?= $M_ID ?></a>
                                </td>
                                <td style="text-align:center">
                                    <?
                                            if ($M_GUBUN === "P") {
                                                echo "개인회원";
                                            } else if ($M_GUBUN === "C") {
                                                echo "기업회원";
                                            } 
                                            ?>
                                </td>

                                <td style="text-align:center">
                                    <?= $M_KSIC ?>
                                </td>
                                <td><?= $M_ADDR ?></td>
                                <td><?= $M_PHONE ?></td>
                                <td><?= $M_EMAIL ?></td>
                                <td style="text-align:center">
                                    <?= $EMAIL_TF ?>
                                </td>
                                <td style="text-align:center">
                                    <?= $MESSAGE_TF ?>
                                </td>
                                <!-- <td><?= $REG_DATE ?></td> -->
                                <td style="text-align:center" class="filedown">
                                    <a href="javascript:js_toggle('<?= $M_NO ?>','<?= $USE_TF ?>');"><span><?= $STR_USE_TF ?></span></a>
                                </td>
                            </tr>
                            <?
                                }
                            } else {
                                ?>
                            <tr>
                                <td height="50" align="center" colspan="9" style="text-align: center;">데이터가 없습니다. </td>
                            </tr>
                            <?
                            }
                            ?>
                            </tbody>
                    </table>
                </div>
                <div class="left">
                    <button type="button" class="button" onClick="js_info();">유컴 돈줘!</button>
                    <button type="button" class="button" onClick="js_ucom();">님도 누르셈 유컴이 돈줌</button>
                    <span id="clickCount">0</span>

                    <? if ($sPageRight_F == "Y") { ?>
					    <button type="button" class="button" onClick="js_excel_list();">엑셀로 받기</button>
					<? } ?>
                    
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
                        <button type="button" class="button" onClick="js_write();">등록</button>
                        <button type="button" class="button type02" onClick="js_delete();">삭제</button>
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