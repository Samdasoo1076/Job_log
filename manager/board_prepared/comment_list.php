<?session_start();?>
<?
# =============================================================================
# File Name    : comment_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-22
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

	$menu_right = "BO002";

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

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
	require "../../_classes/biz/board/_prepared_board.php";
	require "../../_classes/biz/board/board_comment.php";
	require "../../_classes/biz/admin/admin.php";
	
#==============================================================================
# Request Parameter
#==============================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$chk								= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

	$mode			= SetStringToDB($mode);

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	$b_code				= SetStringToDB($b_code);
	$b_no					= SetStringToDB($b_no);

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
				$tmp_c_no = (int)$chk[$k];
				//echo $tmp_c_no;
				$result= deleteBoardComment($conn, $s_adm_no, $tmp_c_no, "", "관리자에 의한 삭제 입니다.");
			}
		}
	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

	$b_no = "";
	$con_use_tf = "Y";
	$con_del_tf = "N";

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt = totalCntManagerBoardComment($conn, $b_no, $con_writer_id, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listManagerBoardComment($conn, $b_no, $con_writer_id, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">

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
				frm.action = "<?=$_SERVER["PHP_SELF"]?>";
				frm.submit();
			}
		}
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm_search;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		//frm.action = "<?//=$_SERVER["PHP_SELF"]?>";
		frm.action = "comment_list.php";
		frm.submit();
	}

</script>
</head>
<body>
<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		
		<section class="conBox">

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?>&nbsp;&nbsp;&nbsp;&nbsp;</legend>

				<? if ($bb_code == "FAQ_6") {?>
        <div class="category_choice">
				<?=makeSelectBoxOnChange($conn,"FAQ_KIND","con_cate_02","125","선택","",$con_cate_02)?>
				</div>
				<? } ?>

				<table summary="이곳에서 <?=$p_menu_name?> 관리하실 수 있습니다">
					<caption><?=$p_menu_name?></caption>
					<colgroup>
						<col width="2%" />
						<col width="3%" />  <!-- No. -->
						<col width="23%" /> <!-- 게시글 -->
						<col width="13%" /> <!-- 작성자 -->
						<col width="42%" /> <!-- 내용 -->
						<col width="10%" /> <!-- 등록일 -->
						<col width="7%" /> <!-- 작성자IP -->
					</colgroup>
					<thead>
					<tr>
						<th scope="col">&nbsp;</th>
						<th scope="col">No.</th>
						<th scope="col">게시글</th>
						<th scope="col">작성자</th>
						<th scope="col">내용</th>
						<th scope="col">등록일</th>
						<th scope="col">작성자IP</th>
					</tr>
					<?
						if (sizeof($arr_rs) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

								$rn							= trim($arr_rs[$j]["rn"]);
								$C_NO						= trim($arr_rs[$j]["C_NO"]);
								$WRITER_ID			= trim($arr_rs[$j]["WRITER_ID"]);
								$WRITER_NM			= trim($arr_rs[$j]["WRITER_NM"]);
								$WRITER_NICK		= trim($arr_rs[$j]["WRITER_NICK"]);
								$CONTENTS				= trim($arr_rs[$j]["CONTENTS"]);
								$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
								$REF_IP 				= trim($arr_rs[$j]["REF_IP"]);
								$BTITLE 				= trim($arr_rs[$j]["BTITLE"]);
								

								if ($WRITER_ID == "own") {
									$str_writer_name = $WRITER_NM." (".$WRITER_NICK.")";
								} else {
									$str_writer_name = $WRITER_NM." (임직원 가족 [".$WRITER_NICK."])";
								}

								$str_reg_date = date("Y-m-d H:i:s",strtotime($REG_DATE));

					?>
					<tr> 
						<td><input type="checkbox" name="chk[]" value="<?=$C_NO?>"></td>
						<td><?= $rn ?></td>
						<td class="tit"><?=$BTITLE?></td>
						<td class="tit"><?=$str_writer_name?></td>
						<td class="tit"><?=nl2br($CONTENTS)?></td>
						<td><?=$str_reg_date?></td>
						<td class="filedown"><?=$REF_IP?></td>
					</tr>
					<?
							}
						}
					?>
				</table>
			</fieldset>
		</form>

			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<?	if ($sPageRight_D == "Y") { ?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<?  } ?>
				</ul>
			</div>
			<div id="bbspgno">
				<!-- --------------------- 페이지 처리 화면 START -------------------------->
				<?
					# ==========================================================================
					#  페이징 처리
					# ==========================================================================
					if (sizeof($arr_rs) > 0) {
						#$search_field		= trim($search_field);
						#$search_str			= trim($search_str);
						$strParam = $strParam."&nPageSize=".$nPageSize."&b_code=".$b_code."&search_field=".$search_field."&search_str=".$search_str;

				?>
				<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
				<?
					}
				?>
				<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>

			<form id="searchBar" name="frm_search" action="javascript:js_search();" method="post">
			<input type="hidden" name="rn" value="">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
					<legend>검색창</legend>
					<? if ($b_board_cate) { ?>
					<?=makeBoardSelectBox("con_cate_01", "전체", "", $b_board_cate, "style='width:100px'", $con_cate_01); ?>
					<? } ?>
					<select name="search_field" style="width:84px;">
						<option value="A.CONTENTS" <? if ($search_field == "A.CONTENTS") echo "selected"; ?> >내용</option>
						<!--option value="WRITER_ID" <? if ($search_field == "WRITER_ID") echo "selected"; ?> >작성자ID</option>
						<option value="REF_IP" <? if ($search_field == "REF_IP") echo "selected"; ?> >작성자IP</option>
						<option value="WRITER_NM" <? if ($search_field == "WRITER_NM") echo "selected"; ?> >닉네임</option-->
					</select>
					<input type="text" value="<?=$search_str?>" name="search_str" class="txt" />
					<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" class="sch" alt="Search" /></a>
				</fieldset>
			</form>
		</section>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>