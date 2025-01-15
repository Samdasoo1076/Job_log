<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    :  eon.php
# Writer       : Kim Kyeong Eon 
# Create Date  : 2024-11-15
# Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
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
$menu_right = "EON01"; // 메뉴마다 셋팅 해 주어야 합니다


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
require "../../_classes/biz/eon/member.php";
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
            $tmp_b_no = (int)$chk[$k];
            $result= deleteMemberTF($conn, $_SESSION['s_adm_no'], (int)$m_no);
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
        document.location = "eon_write.php?menu_cd="+menu_cd;
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
			frm.action = "eon.php";
			frm.submit();
		}
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
                <div class="left"><span class="txt">총 <span class="txt_c01"></span>햄 명</span></div>
                <div class="right">

                <form id="searchBar" name="frm" method="post" action="javascript:js_search();">
                    <input type="hidden" name="m_no" value="">
                    <input type="hidden" name="use_tf" value="">
                    <input type="hidden" name="menu_cd" value="<?//=$menu_cd?>" >
                    <input type="hidden" name="mode" value="">
                    <input type="hidden" name="nPage" value="<?=(int)$nPage?>">
                    <input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>">

                    
                    <select name="search_field" id="search_field">
                        <option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >햄체</option>
                        <option value="title" <? if ($search_field == "title") echo "selected"; ?> >햄명</option>
                        <option value="contents" <? if ($search_field == "contents") echo "selected"; ?> >햄한</option>
                    </select>
                    <input type="text" value="<?=$search_str?>" name="search_str"  id="search_str" placeholder="검색어 입력" />
                    <button type="button" class="button" onClick="js_search();">검색</button>
                </div>
            </form>    
            </div>
			<!-- 아 집에 가고 싶다 <br>
			집에 보내줘
			
			            <pre>
			                그거 아시나요...
			
			                레몬 한개에는 무려...
			
				레몬 2개 분의 비타민C가 들어있다고?
			
				이걸 쓰면 br을 안 써도 되는군아...
			
				혹시 오늘 점심 뭐 먹어요
			
			                김밥에 라면이지요... ㅠㅠ
			
				근데 누구세요
			
			                정의의 사도 배트맨입니다만?
			
			            </pre>
			
			<table> 
					<td>
						안녕
					</td>
					<td>
						안녕?
					</td>
				</tr>
			</table> -->
			 <div class="tbl_style01 left">
				<table id="t">
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
							<th scope="col">
								<input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" />
							</th>
							<th scope="col">번호</th>
							<th scope="col">이름</th>
							<th scope="col">이메일</th>
							<th scope="col">연락처</th>
							<th scope="col">상태</th>
							<th scope="col">권한</th>
							<th scope="col">가입일</th>
							<th scope="col">사용여부</th>
						</tr>
					</thead>
					<tbody>
						<!-- 데이터가 없을 경우 메시지를 출력 -->
						<tr>
							<td height="50" align="center" colspan="9" style="text-align: center;">햄스터가 없습니다.</td>
						</tr>
					</tbody>
			</table>


        </div>

                </tbody>
                </table>
			</div>
            </div>
            <div class="btn_wrap">

                <div class="wrap_paging">
						<?
								$arr_rs = $arr_rs ?? [];

								if (sizeof($arr_rs) > 0) {
								} else {

								}

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
        <!-- contents end-->
		 <div class="right">
               <button type="button" class="button" onClick="js_write();">등록햄스터</button>
               <button type="button" class="button type02" onClick="js_delete();">삭제햄스터</button>
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