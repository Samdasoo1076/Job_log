<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
session_start();
?>
<?
# =============================================================================
# File Name    : board_excel_list.php
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
	$b_code						= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$menu_right = $b_code; // 메뉴마다 셋팅 해 주어야 합니다

	//echo $menu_right;

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
	require "../../_classes/biz/board/board.php";
	require "../../_classes/biz/admin/admin.php";

	$str_title = iconv("UTF-8","EUC-KR","게시판 리스트");

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );

#==============================================================================
# Request Parameter
#==============================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$b_code							= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no								= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];

	$con_cate_01				= $_POST['con_cate_01']!=''?$_POST['con_cate_01']:$_GET['con_cate_01'];
	$con_cate_02				= $_POST['con_cate_02']!=''?$_POST['con_cate_02']:$_GET['con_cate_02'];
	$con_cate_03				= $_POST['con_cate_03']!=''?$_POST['con_cate_03']:$_GET['con_cate_03'];
	$con_cate_04				= $_POST['con_cate_04']!=''?$_POST['con_cate_04']:$_GET['con_cate_04'];

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

	$bb_code = trim($bb_code);

	if ($b_code == "")
		$b_code = "B_1_1";

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

#====================================================================
# Request Parameter
#====================================================================
	$nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$con_cate_01	= SetStringToDB($con_cate_01);
	$con_cate_02	= SetStringToDB($con_cate_02);
	$con_cate_03	= SetStringToDB($con_cate_03);
	$keyword			= SetStringToDB($keyword);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$nPageSize = $nListCnt;

	$nPageBlock	= 10;

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

?>
<font size=3><b>게시판 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>No</td>
		<td align='center' bgcolor='#F4F1EF'>제목</td>
		<td align='center' bgcolor='#F4F1EF'>작성자</td>
		<td align='center' bgcolor='#F4F1EF'>등록일</td>
		<td align='center' bgcolor='#F4F1EF'>댓글여부</td>
		<td align='center' bgcolor='#F4F1EF'>노출여부</td>
		<td align='center' bgcolor='#F4F1EF'>조회수</td>
		<td align='center' bgcolor='#F4F1EF'>작성자IP</td>
	</tr>
<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				$rn							= trim($arr_rs[$j]["rn"]);
				$B_NO						= trim($arr_rs[$j]["B_NO"]);
				$B_RE						= trim($arr_rs[$j]["B_RE"]);
				$B_PO						= trim($arr_rs[$j]["B_PO"]);
				$B_CODE					= trim($arr_rs[$j]["B_CODE"]);
				$CATE_01				= trim($arr_rs[$j]["CATE_01"]);
				$CATE_02				= trim($arr_rs[$j]["CATE_02"]);
				$CATE_03				= trim($arr_rs[$j]["CATE_03"]);
				$CATE_04				= trim($arr_rs[$j]["CATE_04"]);
				$WRITER_NM			= trim($arr_rs[$j]["WRITER_NM"]);
				$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
				$REG_ADM				= trim($arr_rs[$j]["REG_ADM"]);
				$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
				$REF_IP					= trim($arr_rs[$j]["REF_IP"]);
				$MAIN_TF				= trim($arr_rs[$j]["MAIN_TF"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$COMMENT_TF			= trim($arr_rs[$j]["COMMENT_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$SECRET_TF			= trim($arr_rs[$j]["SECRET_TF"]);
				$F_CNT					= trim($arr_rs[$j]["F_CNT"]);
				$REPLY_DATE			= trim($arr_rs[$j]["REPLY_DATE"]);
				$REPLY_STATE		= trim($arr_rs[$j]["REPLY_STATE"]);

				$rs_admin			= selectAdmin($conn, $REG_ADM);
				$rs_adm_name	= SetStringFromDB($rs_admin[0]["ADM_NAME"]);

				$CATE_01 = str_replace("^"," & ",$CATE_01);

				$is_new = "";
				if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
					if ($MAIN_TF <> "N") {
						$is_new = "<img src='../images/bu/ic_new.png' alt='새글' /> ";
					}
				}

				$REG_DATE = date("Y-m-d H:i",strtotime($REG_DATE));

				if ($b_board_type == "EVENT") {
					$TITLE = $TITLE." (기간 : ".$CATE_03." ~ ".$CATE_04.")";
				}

				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>보이기</font>";
				} else {
					$STR_USE_TF = "<font color='red'>보이지않기</font>";
				}
				
				if ($COMMENT_TF == "Y") {
					$STR_COMMENT_TF = "<font color='navy'>사용</font>";
				} else {
					$STR_COMMENT_TF = "<font color='red'>사용안함</font>";
				}

				$STR_REPLY_STATE = "";
				if ($REPLY_STATE == "Y") {
					$STR_REPLY_STATE = "<font color='navy'>답변완료</font>";
				} else {
					$STR_REPLY_STATE = "<font color='red'>대기중</font>";
				}

				$R_CNT = getReplyCount($conn, $B_CODE, $B_NO);

				if ($R_CNT > 0) {
					$reply_cnt = "<span class=\"orange f11\">(".$R_CNT.")</span>";
				} else {
					$reply_cnt = "";
				}

				$space = "";
				
				$DEPTH = strlen($B_PO);

				for ($l = 0; $l < $DEPTH; $l++) {
					if ($l != 1)
						$space .= "&nbsp;";
					else
						$space .= "&nbsp;";
	
					if ($l == ($DEPTH - 1))
						$space .= "&nbsp;┗";
	
					$space .= "&nbsp;";
				}

				if ($SECRET_TF == "Y") 
					$str_lock = "<img src='../images/bu/ic_lock.jpg' alt='' />";
				else 
					$str_lock = "";

				if ($F_CNT > 0) 
					$str_file = "<img src='../images/bu/ic_file2.gif' alt='' />";
				else 
					$str_file = "";
	?>
		<tr style='border-collapse:collapse;table-layout:fixed;'>
			<td><?= $rn ?></td>
			<td class="tit"><?=$space?>
			<? if ($CATE_01) {?>
			[<?=$CATE_01?>]&nbsp;
			<? } ?>
			<?=$TITLE?> <?=$reply_cnt?> <?=$str_lock?> <?=$str_file?></td>
			<td><?= $WRITER_NM ?></td>
			<td><?= $REG_DATE ?></td>
			<td>
				<?=$STR_COMMENT_TF?>
			</td>
			<td class="filedown">
				<?=$STR_USE_TF?>
			</td>
			<td class="filedown">
				<?=$HIT_CNT?>
			</td>
			<td class="filedown">
				<?=$REF_IP?>
			</td>
		</tr>
	<?			
			}
		} else { 
	?> 
		<tr>
			<td height="50" align="center" colspan="10">데이터가 없습니다. </td>
		</tr>
	<? 
		}
	?>
</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
