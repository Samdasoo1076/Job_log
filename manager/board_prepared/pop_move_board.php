<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 
	error_reporting(E_ALL & ~E_NOTICE);

# =============================================================================
# File Name    : pop_move_board.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-22
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

# =============================================================================
#	register_globals off 설정에 따른 코드 
#	(하나의 변수 명에 POST, GET을 모두 사용한 페이지에서만 사용 기본으로는 해당 코드 없이 POST, GET 명시)

	$s_adm_no = $_SESSION['s_adm_no'];

	$mode			= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$b_code		= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no			= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];

	$pre_b_no			= $_POST['pre_b_no']!=''?$_POST['pre_b_no']:$_GET['pre_b_no'];

	$pre_b_code		= $_POST['pre_b_code']!=''?$_POST['pre_b_code']:$_GET['pre_b_code'];
	$next_b_code	= $_POST['next_b_code']!=''?$_POST['next_b_code']:$_GET['next_b_code'];
# =============================================================================

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
	require "../../_classes/biz/board/_prepared_board.php";
	require "../../_classes/biz/admin/admin.php";

	if ($mode == "M") {

		//echo $pre_b_code."<br>";
		//echo $next_b_code."<br>";
		//echo $pre_b_no."<br>";
		
	
		$result = moveBoard($conn, $pre_b_code, $next_b_code, $pre_b_no);

		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
	
	function init() {
		//alert("게시물이 이동 되었습니다.");
		opener.document.location = "<?=$g_base_dir?>/manager/board/board_list.php?b_code=<?=$next_b_code?>";
		self.close();
	}
	
</script>
</head>
<body onload="init();">
</body>
</html>
<?
			db_close($conn);
			exit;
		}

	}
	

	$arr_rs = selectBoard($conn, $b_code, $b_no);
		
	$rs_b_no						= trim($arr_rs[0]["B_NO"]); 
	$rs_b_code					= trim($arr_rs[0]["B_CODE"]); 
	$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]); 

	$flag				= trim($flag);	//호출되는 화면 
	$sub_flag		= trim($sub_flag);	//호출되는 화면 
	
	//$arr_rs = listBoardConfig($conn, $g_site_no, "", "", "", "", "N", "", "", "1", "1000");
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 368px; }
-->
</style>
<script language="javascript">

	function js_move() {

		var frm = document.frm;
		if (frm.next_b_code.value == "") {
			alert("이동하실 게시판을 선택해 주세요.");
			frm.next_b_code.focus();
			return;
		}

		if (frm.pre_b_code.value == frm.next_b_code.value) {
			alert("이동하실 게시판과 현재 게시판은 같은 게시판 입니다.");
			frm.next_b_code.focus();
			return;
		}

		bDelOK = confirm('해당 게시물을 이동 하시겠습니까?');

		if (bDelOK==true) {
			frm.mode.value = "M";
			frm.target = "";
			frm.action = "<?=$_SERVER["PHP_SELF"]?>";
			frm.submit();
		}
	}

</script>
</head>
<body id="popup">
<div class="popupwrap">
	<h1>게시물 이동</h1>
	<div class="popcontents">


<form name="frm" method="post">
<input type="hidden" name="mode" value="S" >
<input type="hidden" name="pre_b_code" value="<?= $rs_b_code ?>" >
<input type="hidden" name="pre_b_no" value="<?= $rs_b_no ?>" >

		<div class="addr_inp">
			<div class="tbl_style01 left">
				<table>
					<colgroup>
						<col width="30%">
						<col width="30%">
						<col width="20%">
						<col width="30%">
					</colgroup>
					<tr>
						<td colspan="4"><b>"<?=$rs_title?>" <br/>게시물을 이동하실 게시판을 선택해 주세요.</b></td>
					</tr>
					<tr>
						<th>이동할 게시판</th>
						<td colspan=""3">
							<select name="next_b_code" style="width:250px;">
								<option value="">선택하세요.</option>
								<option value="B_1_1">공지사항</option>
								<option value="B_1_3">입학전형결과</option>
								<option value="B_1_2">통합자료실</option>
								<option value="B_1_8">기출문제</option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>

</form>

		<div class="btn_wrap right">
			<button type="button" class="button" onClick="js_move();">확인</button>
			<button type="button" class="button type03" onClick="self.close();">닫기</button>
		</div>
	</div>
</div>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>