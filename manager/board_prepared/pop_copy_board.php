<?session_start();?>
<?
# =============================================================================
# File Name    : pop_copy_board.php
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

	if ($mode == "C") {

		//echo $pre_b_code."<br>";
		//echo $next_b_code."<br>";
		//echo $pre_b_no."<br>";
		
	
		$result = copyBoard($conn, $pre_b_code, $next_b_code, $pre_b_no);

		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
	
	function init() {
		alert("게시물이 복사 되었습니다.");
		opener.document.location = "<?=$g_base_dir?>/manager/board/board_list.php?b_code=<?=$next_b_code?>";
		self.close();
	}
	
</script>
</head>
<body onload="init();">
</body>
</html>
<?
			mysql_close($conn);
			exit;
		}

	}
	

	$arr_rs = selectBoard($conn, $b_code, $b_no);
		
	$rs_b_no						= trim($arr_rs[0]["B_NO"]); 
	$rs_b_code					= trim($arr_rs[0]["B_CODE"]); 
	$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]); 

	$flag				= trim($flag);	//호출되는 화면 
	$sub_flag		= trim($sub_flag);	//호출되는 화면 
	
	$arr_rs = listBoardConfig($conn, $g_site_no, "", "", "", "", "N", "", "", "1", "1000");
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../../manager/css/admin.css" type="text/css" />
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<script language="javascript" type="text/javascript" src="../../manager/js/common.js"></script>
<script language="javascript">

	function js_move() {

		var frm = document.frm;
		if (frm.next_b_code.value == "") {
			alert("복사하실 게시판을 선택해 주세요.");
			frm.next_b_code.focus();
			return;
		}

		if (frm.pre_b_code.value == frm.next_b_code.value) {
			alert("복사하실 게시판과 현재 게시판은 같은 게시판 입니다.");
			frm.next_b_code.focus();
			return;
		}

		bDelOK = confirm('해당 게시물을 복사 하시겠습니까?');

		if (bDelOK==true) {
			frm.mode.value = "C";
			frm.target = "";
			frm.action = "<?=$_SERVER["PHP_SELF"]?>";
			frm.submit();
		}
	}

</script>
</head>

<body id="popup" onload="">
<form name="frm" method="post">
<input type="hidden" name="mode" value="S" >
<input type="hidden" name="pre_b_code" value="<?= $rs_b_code ?>" >
<input type="hidden" name="pre_b_no" value="<?= $rs_b_no ?>" >

<div id="popupwrap">
	<h1>게시물 복사</h1>

	<div id="postsch">
		<h2>"<?=$rs_title?>" <br/><br/>&nbsp;&nbsp;의 게시물을 복사하실 게시판을 선택해 주세요.</h2>
		<div class="sp10"></div>
		<div class="addr_inp">
			<div style="padding-left:25px;">
			<select name="next_b_code" style="width:250px;">
				<option value="">선택하세요.</option>
			<?
				if (sizeof($arr_rs) > 0) {
					for ($j = sizeof($arr_rs)-1 ; $j >= 0; $j--) {
						$SITE_NO			= trim($arr_rs[$j]["SITE_NO"]);
						$BOARD_CODE		= trim($arr_rs[$j]["BOARD_CODE"]);
						$BOARD_NM			= SetStringFromDB($arr_rs[$j]["BOARD_NM"]);
			?>
				<option value="<?=$BOARD_CODE?>"><?=$BOARD_NM?></option>
			<?
					}
				}
			?>
			</select>
			</div>
		</div>
  </div>
	<div class="sp20"></div>
	
	<div class="btn">
		<center>
		<a href="javascript:js_move();"><img src="../images/admin/btn_confirm.gif" alt="확인" /></a>
		<a href="javascript:self.close();"><img src="../images/admin/btn_cancel.gif" alt="취소" /></a>
		</center>
	</div>
	<div class="sp30"></div>

  <div class="bot_close"><a href="javascript: window.close();"><img src="../../manager/images/admin/icon_pclose.gif" alt="닫기" /></a></div>
</div>

</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>