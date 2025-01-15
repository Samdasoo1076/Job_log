<?session_start();?>
<?
# =============================================================================
# File Name    : ifrm_comment.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-22
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

# =============================================================================
#	register_globals off 설정에 따른 코드 
#	(하나의 변수 명에 POST, GET을 모두 사용한 페이지에서만 사용 기본으로는 해당 코드 없이 POST, GET 명시)
	extract($_POST);
	extract($_GET);
	$s_adm_no = $_SESSION['s_adm_no'];
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================
//	require "../../_common/common_header.php"; 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";

#====================================================================
# Request Parameter
#====================================================================
	$parent_bb_code			= $_POST['parent_bb_code']!=''?$_POST['parent_bb_code']:$_GET['parent_bb_code'];
	$parent_bb_no				= $_POST['parent_bb_no']!=''?$_POST['parent_bb_no']:$_GET['parent_bb_no'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	#List Parameter
	$parent_bb_code	= trim($parent_bb_code);
	$parent_bb_no		= trim($parent_bb_no);
	
	// parent 게시판 정보 입력
	$cate_01	= $parent_bb_code;
	$cate_02	= $parent_bb_no;

	$con_cate_01 = $parent_bb_code;
	$con_cate_02 = $parent_bb_no;

	$bb_code		= "R_".$parent_bb_code."_".$parent_bb_no;

	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_use_tf = "Y";
	$del_tf = "N";
	
	if ($mode == "IC") {

		$title			= $_POST['title']!=''?$_POST['title']:$_GET['title'];
		$contents		= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
		$title			= SetStringToDB($title);
		$contents		= SetStringToDB($contents);
		
		$result =  insertBoard($conn, $bb_code, $con_cate_01, $con_cate_02, $cate_03, $cate_04, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no);
		
		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script type="text/javascript">
	document.location = "ifrm_comment.php?parent_bb_code=<?=$parent_bb_code?>&parent_bb_no=<?=$parent_bb_no?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($mode == "UC") {

		$title			= $_POST['title']!=''?$_POST['title']:$_GET['title'];
		$contents		= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
		$title					= SetStringToDB($title);
		$temp_contents	= SetStringToDB($temp_contents);
		
		$result = updateBoard($conn, $temp_cate_01, $temp_cate_02, $temp_cate_03, $temp_cate_04, $temp_writer_name, $temp_writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $temp_contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no, $temp_bb_code, $temp_bb_no);
		
		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script type="text/javascript">
	document.location = "ifrm_comment.php?parent_bb_code=<?=$parent_bb_code?>&parent_bb_no=<?=$parent_bb_no?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($mode == "RC") {

		$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
		$temp_contents	= $_POST['temp_contents']!=''?$_POST['temp_contents']:$_GET['temp_contents'];
		$title					= SetStringToDB($title);
		$temp_contents	= SetStringToDB($temp_contents);

		$result =  insertBoardReply($conn, $temp_bb_code, $temp_bb_no, $temp_bb_po, $temp_bb_re, $temp_bb_de, $cate_01, $cate_02, $cate_03, $cate_04, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $temp_contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no);

		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script type="text/javascript">
	document.location = "ifrm_comment.php?parent_bb_code=<?=$parent_bb_code?>&parent_bb_no=<?=$parent_bb_no?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($mode == "DC") {
		
		$result = deleteBoard($conn, $s_adm_no, $temp_bb_code, $temp_bb_no);
		
		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script type="text/javascript">
	document.location = "ifrm_comment.php?parent_bb_code=<?=$parent_bb_code?>&parent_bb_no=<?=$parent_bb_no?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

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
		$nPageSize = 5;
	}

	$nPageBlock	= 10;

	$nListCnt =totalCntBoard($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBoard($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script language="javascript" type="text/javascript" src="../js/common.js"></script>

<script type="text/javascript">

	function init(){ 
		var doc = document.getElementById("infodoc"); 
		doc.style.top=0; 
		doc.style.left=0; 
		if(doc.offsetHeight == 0){ 
		} else { 
			pageheight = doc.offsetHeight; 
			pagewidth = "100%"; 

			parent.document.getElementById('ifr_comment').height = pageheight;
			//parent.frames["ifr_detail"].resizeTo(pagewidth,pageheight); 
		}
	}

	function js_reply_write() {
		var frm = document.frm;

		if ((frm.contents.value == "간략한 댓글을 올려주세요. 로그인 후 등록하실 수 있습니다.") || (frm.contents.value == "")) {
			alert("간략한 댓글을 올려주세요.");
			frm.contents.focus();
			return;
		}

		if (frm.bb_no.value == "") {
			frm.mode.value = "IC";
		} else {
			frm.mode.value = "UC";
		}
		frm.target = "";
		frm.action = "<?=$_SERVER["PHP_SELF"]?>";
		frm.submit();
	}


	var now_reply_idx = "";
	var now_update_idx = "";

	function js_reply_modify(idx) {

		for (k=0; k < <?=$nPageSize?> ; k++) {
			if (document.getElementById('update_'+k) != null) {
				document.getElementById('update_'+k).style.display='none';
			}
			if (document.getElementById('reply_'+k) != null) {
				document.getElementById('reply_'+k).style.display='none';
			}
		}
		
		if (now_reply_idx == idx) {
			document.getElementById('update_'+idx).style.display='none';
			now_reply_idx = "";
		} else {
			document.getElementById('update_'+idx).style.display='block';
			now_reply_idx = idx;
		}

		init();
	}

	function js_reply_reply(idx) {

		for (k=0; k < <?=$nPageSize?> ; k++) {
			if (document.getElementById('update_'+k) != null) {
				document.getElementById('update_'+k).style.display='none';
			}
			if (document.getElementById('reply_'+k) != null) {
				document.getElementById('reply_'+k).style.display='none';
			}
		}

		if (now_update_idx == idx) {
			document.getElementById('reply_'+idx).style.display='none';
			now_update_idx = "";
		} else {
			document.getElementById('reply_'+idx).style.display='block';
			now_update_idx = idx;
		}
		init();
	}

	function js_reply_delete(bb_code, bb_no) {

		var frm = document.frm;
		bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "DC";
			frm.temp_bb_code.value = bb_code;
			frm.temp_bb_no.value	 = bb_no;
			frm.target = "";
			frm.action = "<?=$_SERVER["PHP_SELF"]?>";
			frm.submit();
		}
	}

	function js_reply_update_write(idx, bb_code,bb_no,name,cate_01,cate_02,cate_03,pw) {
		
		var frm = document.frm;
		
		frm.temp_bb_code.value = bb_code;
		frm.temp_bb_no.value = bb_no;
		frm.temp_writer_name.value = name;
		frm.temp_cate_01.value = cate_01;
		frm.temp_cate_02.value = cate_02;
		frm.temp_cate_03.value = cate_03;
		frm.temp_writer_pw.value = pw;
		frm.temp_contents.value = document.getElementById('update_contents_'+idx).value;

		frm.mode.value = "UC";

		frm.target = "";
		frm.action = "<?=$_SERVER["PHP_SELF"]?>";
		frm.submit();

	}

	function js_reply_reply_write(idx, bb_code, bb_no, bb_de, bb_re, bb_po, cate_01, cate_02) {
		
		var frm = document.frm;
		
		frm.temp_bb_code.value = bb_code;
		frm.temp_bb_no.value = bb_no;
		frm.temp_bb_de.value = bb_de;
		frm.temp_bb_re.value = bb_re;
		frm.temp_bb_po.value = bb_po;
		frm.temp_cate_01.value = cate_01;
		frm.temp_cate_02.value = cate_02;
		frm.temp_contents.value = document.getElementById('reply_contents_'+idx).value;

		frm.mode.value = "RC";

		frm.target = "";
		frm.action = "<?=$_SERVER["PHP_SELF"]?>";
		frm.submit();

	}

</script>
<body onload="init();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="infodoc" style="position:absolute;left:0;top:0;width:100%"> 
<br />

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="bb_no" value="">
<input type="hidden" name="bb_code" value="<?=$bb_code?>">
<input type="hidden" name="parent_bb_code" value="<?=$parent_bb_code?>">
<input type="hidden" name="parent_bb_no" value="<?=$parent_bb_no?>">
<input type="hidden" name="next_url" value="" />
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

<input type="hidden" name="temp_bb_code" value="">
<input type="hidden" name="temp_bb_no" value="">
<input type="hidden" name="temp_bb_po" value="">
<input type="hidden" name="temp_bb_de" value="">
<input type="hidden" name="temp_bb_re" value="">
<input type="hidden" name="temp_writer_name" value="">
<input type="hidden" name="temp_cate_01" value="">
<input type="hidden" name="temp_cate_02" value="" />
<input type="hidden" name="temp_cate_03" value="">
<input type="hidden" name="temp_writer_pw" value="">
<input type="hidden" name="temp_contents" value="">

<table cellpadding="0" cellspacing="0" class="colstable">
	<colgroup>
		<col width="120" />
		<col width="*" />
	</colgroup>
	<tr>
		<th>
			댓글
		</th>
		<td class="contentswrite end line" style="padding: 10px 10px 10px 15px">
			<textarea name="contents" style="width: 80.2%; height:50px" onFocus="if (this.value=='간단한 댓글을 올리실 수 있습니다.') this.value=''" onBlur="if (this.value == ''){this.value='간단한 댓글을 올리실 수 있습니다.'}">간단한 댓글을 올리실 수 있습니다.</textarea>
			<a href="javascript:js_reply_write();"><img src="/manager/images/admin/co_btn_write.gif" alt="확인"></a>
			<input type="hidden" name="writer_nm" value="<?=$s_adm_nm?>">
			<input type="hidden" name="writer_pw" value="<?=$s_adm_no?>">
			<input type="hidden" name="cate_03" value="<?=$s_adm_id?>">
			<input type="hidden" name="cate_04" value="<?=$s_mem_no?>">
			<input type="hidden" name="use_tf" value="Y">
		</td>
	</tr>
</table>
	<!-- //Write -->
<br>
	<!-- Read -->
		<?
			$nCnt = 0;
			
			if (sizeof($arr_rs) > 0) {
				
				for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
					
					$rn							= trim($arr_rs[$j]["rn"]);
					$BB_NO					= trim($arr_rs[$j]["BB_NO"]);

					$BB_DE					= trim($arr_rs[$j]["BB_DE"]); 
					$BB_RE					= trim($arr_rs[$j]["BB_RE"]); 
					$BB_PO					= trim($arr_rs[$j]["BB_PO"]); 

					$BB_CODE				= trim($arr_rs[$j]["BB_CODE"]);
					$CATE_01				= trim($arr_rs[$j]["CATE_01"]);
					$CATE_02				= trim($arr_rs[$j]["CATE_02"]);
					$CATE_03				= trim($arr_rs[$j]["CATE_03"]);
					$CATE_04				= trim($arr_rs[$j]["CATE_04"]);
					$WRITER_NM			= SetStringFromDB($arr_rs[$j]["WRITER_NM"]);
					$WRITER_PW			= trim($arr_rs[$j]["WRITER_PW"]);
					$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
					$CONTENTS				= SetStringFromDB($arr_rs[$j]["CONTENTS"]);
					$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
					$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
					$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
					
					$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

					if ($j == (sizeof($arr_rs) -1)) {
						$str_class = "class=\"end\"";
					} else {
						$str_class = "class=\"modeual_cont\"";
					}

					$space = "";
					$sp = 10;

					for ($l = 1; $l < $BB_DE; $l++) {
						if ($l != 1) {
							$sp			= $sp + 15;
						} else {
							$sp			= $sp + 15;
						}
				
						if ($l == ($BB_DE - 1))
							$space .= "┗&nbsp;";
				
					}
		
		?>
<table cellpadding="0" border="0" cellspacing="0" class="rowstable03">
	<colgroup>
		<col width="95%" />
		<col width="5%" />
	</colgroup>
	<tr>
		<td class="modeual_nm"><font color="navy"><?=$WRITER_NM?></font>&nbsp;&nbsp;<?=$REG_DATE?></td>
		<td>
			<a href="javascript:js_reply_reply('<?=$j?>');"><img src="/manager/images/admin/co_btn_reply.gif" alt="답변"></a>
			<a href="javascript:js_reply_modify('<?=$j?>');"><img src="/manager/images/admin/co_btn_modify.gif" alt="수정"></a>
			<a href="javascript:js_reply_delete('<?=$BB_CODE?>', '<?=$BB_NO?>');"><img src="/manager/images/admin/co_btn_delete.gif" alt="삭제"></a>
		</td>
	</tr>
	<tr>
		<td colspan="3" <?=$str_class?> style="padding-left:<?=$sp?>px">
			<?=$space?><?=nl2br($CONTENTS)?>
		</td>
	</tr>
</table>
<div id="reply_<?=$j?>" style="display:none">
<div class="sp2"></div>
<table cellpadding="0" cellspacing="0" class="colstable">
	<colgroup>
		<col width="120" />
		<col width="*" />
	</colgroup>
	<tr>
		<th>
			댓글
		</th>
		<td class="contentswrite end line" style="padding: 10px 10px 10px 15px">
			<textarea name="reply_contents_<?=$j?>" id="reply_contents_<?=$j?>" style="width: 80.2%; height:50px" ></textarea>
			<a href="javascript:js_reply_reply_write('<?=$j?>','<?=$BB_CODE?>','<?=$BB_NO?>','<?=$BB_DE?>','<?=$BB_RE?>','<?=$BB_PO?>', '<?=$CATE_01?>','<?=$CATE_02?>');"><img src="/manager/images/admin/co_btn_write.gif" alt="확인"></a>
		</td>
	</tr>
</table>
</div>

<div id="update_<?=$j?>" style="display:none">
<div class="sp2"></div>
<table cellpadding="0" cellspacing="0" class="colstable">
	<colgroup>
		<col width="120" />
		<col width="*" />
	</colgroup>
	<tr>
		<th>
			댓글
		</th>
		<td class="contentswrite end line" style="padding: 10px 10px 10px 15px">
			<textarea name="update_contents_<?=$j?>" id="update_contents_<?=$j?>" style="width: 80.2%; height:50px" ><?=$CONTENTS?></textarea>
			<a href="javascript:js_reply_update_write('<?=$j?>','<?=$BB_CODE?>','<?=$BB_NO?>','<?=$WRITER_NM?>','<?=$CATE_01?>','<?=$CATE_02?>','<?=$CATE_03?>','<?=$WRITER_PW?>' );"><img src="/manager/images/admin/co_btn_write.gif" alt="확인"></a>
		</td>
	</tr>
</table>
</div>
		<?
				}
			} else { 
		?> 
<table cellpadding="0" border="0" cellspacing="0" class="rowstable03">
	<colgroup>
		<col width="100%" />
	</colgroup>
	<tr>
		<td height="50" align="center" colspan="8">등록된 내용이 없습니다. </td>
	</tr>
</table>
		<? 
			}
		?>
	<!-- //Read -->
	<!-- Paging -->
	<!-- --------------------- 페이지 처리 화면 START -------------------------->
	<?
	# ==========================================================================
	#  페이징 처리
	# ==========================================================================
		if (sizeof($arr_rs) > 0) {
		#$search_field		= trim($search_field);
		#$search_str			= trim($search_str);
		$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&parent_bb_code=".$parent_bb_code."&parent_bb_no=".$parent_bb_no;

	?>
		<?= Image_PageList($_SERVER["PHP_SELF"],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
	<?
		}
	?>
	<!-- --------------------- 페이지 처리 화면 END -------------------------->
	<!-- //Paging -->
<br />
<br />
<br />
</div>
</form>
</body>
</html>
<!-- //리플달기 -->
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
