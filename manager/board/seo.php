<?session_start();?>
<?  #echo "<h1>공통 템플릿입니다. 여기를 지우고 활용하세요구르트.</h1> <br>"; ?>
<?
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : seo.php
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
require "../../_classes/com/etc/etc.php";
require "../../_classes/biz/seo/member.php";
require "../../_classes/biz/admin/admin.php";
require "../../_classes/biz/online/online.php";


#==============================================================================
# Request Parameter
#==============================================================================

#meber_test 테이블 컬럼 정의
# $m_no, $m_name, $m_email, $m_phone, $m_status, $m_role
$m_no =                     isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
$m_name =                   isset($_POST["m_name"]) && $_POST["m_name"] !== '' ? $_POST["m_name"] : (isset($_GET["m_name"]) ? $_GET["m_name"] : '');
$m_email =                  isset($_POST["m_email"]) && $_POST["m_email"] !== '' ? $_POST["m_email"] : (isset($_GET["m_email"]) ? $_GET["m_email"] : '');
$m_phone =                  isset($_POST["m_phone"]) && $_POST["m_phone"] !== '' ? $_POST["m_phone"] : (isset($_GET["m_phone"]) ? $_GET["m_phone"] : '');
$m_status =                 isset($_POST["m_status"]) && $_POST["m_status"] !== '' ? $_POST["m_status"] : (isset($_GET["m_status"]) ? $_GET["m_status"] : '');
$m_role =                   isset($_POST["m_role"]) && $_POST["m_role"] !== '' ? $_POST["m_role"] : (isset($_GET["m_role"]) ? $_GET["m_role"] : '');

# 공통 컬럼 정의
$mode   =                   isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
$use_tf =                   isset($_POST["use_tf"]) && $_POST["use_tf"] !== '' ? $_POST["use_tf"] : (isset($_GET["use_tf"]) ? $_GET["use_tf"] : '');

$chk						= isset($_POST["chk"]) && $_POST["chk"] !== '' ? $_POST["chk"] : (isset($_GET["chk"]) ? $_GET["chk"] : '');

# 페이징 관련
$nPage					= isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize			= isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');

# 검색 관련
$search_field		= isset($_POST["search_field"]) && $_POST["search_field"] !== '' ? $_POST["search_field"] : (isset($_GET["search_field"]) ? $_GET["search_field"] : '');
$search_str			= isset($_POST["search_str"]) && $_POST["search_str"] !== '' ? $_POST["search_str"] : (isset($_GET["search_str"]) ? $_GET["search_str"] : '');

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
            $result= deleteMemberTF($conn, $_SESSION['s_adm_no'], $tmp_m_no);
            //$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "".$b_board_nm." 게시물삭제 (게시물 번호 : ".$tmp_b_no.") ", "Delete");
        }
    }

}


#====================================================================
# Request Parameter
#====================================================================
if ($nPage == 0) $nPage = "1";

#List Parameter

$nPage			    = SetStringToDB($nPage);
$nPageSize		    = SetStringToDB($nPageSize);
$nPage			    = trim($nPage);
$nPageSize		    = trim($nPageSize);

$search_field		= SetStringToDB($search_field);
$search_str			= SetStringToDB($search_str);
$search_field		= trim($search_field);
$search_str			= trim($search_str);

$mode			    = SetStringToDB($mode);
$m_no               = SetStringToDB($m_no);
$use_tf				= SetStringToDB($use_tf);
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

$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

$nListCnt = totalCntMembers($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str);

$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1);

if ((int)($nTotalPage) < (int)($nPage)) {
    $nPage = $nTotalPage;
}

$arr_rs = listMember($conn, $m_no, $m_name, $m_email, $m_phone, $m_status, $m_role, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

//$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 멤버 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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
        menu_cd=document.frm.menu_cd.value;
        document.location = "seo_write.php?menu_cd="+menu_cd;
    }

    function js_toggle(m_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('사용 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.m_no.value = m_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "seo.php";
			frm.submit();
		}
	}

    // 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.target = "";
		frm.action = "seo.php";
		frm.submit();
	}

	function js_reload() {
		var frm = document.frm;
		frm.method = "post";
		frm.target = "";
		frm.action = "seo.php";
		frm.submit();
	}

    function js_check() {
        var frm = document.frm;

        check=document.getElementsByName("chk[]");
        
        if (frm.all_chk_no.checked){
            for (i=0;i<check.length;i++) {
                    check.item(i).checked = true;
            }
        } else {
            for (i=0;i<check.length;i++) {
                    check.item(i).checked = false;
            }
        }
    }

    
	function js_delete() {
		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
	
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
	
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER['PHP_SELF']?>";
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
        	<div class="tit_h3"><h3><?=$p_menu_name?></h3></div>
            <div class="tbl_top">
                <div class="left"><span class="txt">총 <span class="txt_c01"><?=number_format($nListCnt)?></span> 명</span></div>
				
				
                <div class="right">

                <form id="searchBar" name="frm" method="post" action="javascript:js_search();">
                    <input type="hidden" name="m_no" value="">
                    <input type="hidden" name="use_tf" value="">
                    <input type="hidden" name="menu_cd" value="<?//=$menu_cd?>" >
                    <input type="hidden" name="mode" value="">
                    <input type="hidden" name="nPage" value="<?=(int)$nPage?>">
                    <input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>">

                <div class="fl_wrap">
                    <?# if ($b_board_type == "EVENT") { ?>
                    <select name="con_use_tf" onChange="js_search();">
                        <option value="" <? #if ($con_use_tf == "") echo "selected"; ?> >서현석 전체</option>
                        <option value="Y" <? #if ($con_use_tf == "Y") echo "selected"; ?> >이지민</option>
                        <option value="N" <? #if ($con_use_tf == "N") echo "selected"; ?> >김경언</option>
                    </select>
                    <?# } ?>
                    
                    <select name="search_field" id="search_field">
                        <option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >전체</option>
                        <option value="M_NAME" <? if ($search_field == "M_NAME") echo "selected"; ?> >이름</option>
                        <option value="M_ROLE" <? if ($search_field == "M_ROLE") echo "selected"; ?> >권한</option>
                    </select>
                    <input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
                    <button type="button" class="button" onClick="js_search();">검색</button>
                </div>
          
            </div>
        </div>

            <div class="tbl_style01 left">
                <table id='t'>
                <colgroup>
                <col style="width:3%" />
                <col style="width:5%" />
                <col style="width:7%" />
                <col style="width:15%" />
                <col style="width:15%" />
                <col style="width:12%" />
                <col style="width:10%" />
                <col style="width:10%" />
                <col style="width:10%" />
            </colgroup>
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
                    <th scope="col">번호</th>
                    <th scope="col">이름</th>
                    <th scope="col">이메일</th>
                    <th scope="col">연락처</th>
                    <th scope="col">상태</th>
                    <th scope="col">권한</th>
                    <th scope="col">가입일</th>
                    <th scope="col">사용여부</th>
                </tr>
                    <?
                $nCnt = 0;
                
            ?>

            <?
                $nCnt = 0;
                
                if (sizeof($arr_rs) > 0) {
                    
                    for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
                        
                        $rn							= trim($arr_rs[$j]["rn"]);
                        $M_NO						= trim($arr_rs[$j]["M_NO"]);
                        $M_NAME                     = trim($arr_rs[$j]["M_NAME"]);
                        $M_EMAIL                    = trim($arr_rs[$j]["M_EMAIL"]);
                        $M_PHONE                    = trim($arr_rs[$j]["M_PHONE"]);
                        $M_STATUS                   = trim($arr_rs[$j]["M_STATUS"]);
                        $M_ROLE                     = trim($arr_rs[$j]["M_ROLE"]);
                        $REG_DATE                   = trim($arr_rs[$j]["REG_DATE"]);
                        $USE_TF                     = trim($arr_rs[$j]["USE_TF"]);
                        $DEL_TF                     = trim($arr_rs[$j]["DEL_TF"]);


                        $REG_DATE = date("Y-m-d H:i",strtotime($REG_DATE));

                        if ($USE_TF == "Y") {
                            $STR_USE_TF = "<font color='navy'>사용</font>";
                        } else {
                            $STR_USE_TF = "<font color='red'>사용안함</font>";
                        }
            ?>
                <tr style="<?=$top_style?> <?=$view_style?>"> 
                    <td><input type="checkbox" name="chk[]" value="<?=$M_NO?>"></td>
                    <td style="text-align:center">
                        <?=$M_NO ?> 
                    </td>
                    <td style="text-align:center">
                        <a href="javascript:js_view('<?= $rn ?>','<?= $M_NO ?>');"><?=$M_NAME ?></a>
                    </td>
                    <td style="text-align:left">
                        <?=$M_EMAIL ?> 
                    </td>
                    <td style="text-align:center">
                        <?=$M_PHONE ?>
                    </td>
                    <td><?=$M_STATUS ?></td>
                    <td><?=$M_ROLE?></td>
                    <td><?= $REG_DATE ?></td>
                    <td class="filedown">
                        <a href="javascript:js_toggle('<?=$M_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a>
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
                                $strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;
						?>
						<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
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