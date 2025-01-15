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
	$s_adm_no = $_COOKIE['s_adm_no'];
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
	$writer_id = $s_adm_id;//작성자 아이디:로그인한 사용자 아이디

	$parent_bb_code			= $_POST['parent_bb_code']!=''?$_POST['parent_bb_code']:$_GET['parent_bb_code'];
	$parent_bb_no				= $_POST['parent_bb_no']!=''?$_POST['parent_bb_no']:$_GET['parent_bb_no'];
	
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$bb_code						= $_POST['bb_code']!=''?$_POST['bb_code']:$_GET['bb_code'];
	$bb_no							= $_POST['bb_no']!=''?$_POST['bb_no']:$_GET['bb_no'];
	$con_cate_01				= $_POST['con_cate_01']!=''?$_POST['con_cate_01']:$_GET['con_cate_01'];
	$con_cate_02				= $_POST['con_cate_02']!=''?$_POST['con_cate_02']:$_GET['con_cate_02'];
	$con_cate_03				= $_POST['con_cate_03']!=''?$_POST['con_cate_03']:$_GET['con_cate_03'];
	$con_cate_04				= $_POST['con_cate_04']!=''?$_POST['con_cate_04']:$_GET['con_cate_04'];
	
	$writer_id					= $_POST['writer_id']!=''?$_POST['writer_id']:$_GET['writer_id'];
	$writer_nm					= $_POST['writer_nm']!=''?$_POST['writer_nm']:$_GET['writer_nm'];
	$writer_pw					= $_POST['writer_pw']!=''?$_POST['writer_pw']:$_GET['writer_pw'];
	$email							= $_POST['email']!=''?$_POST['email']:$_GET['email'];

	$phone							= $_POST['phone']!=''?$_POST['phone']:$_GET['phone'];
	$homepage						= $_POST['homepage']!=''?$_POST['homepage']:$_GET['homepage'];
	$title							= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$contents						= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
	$temp_contents			= $_POST['temp_contents']!=''?$_POST['temp_contents']:$_GET['temp_contents'];
	$recomm							= $_POST['recomm']!=''?$_POST['recomm']:$_GET['recomm'];
	$keyword						= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];

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

	
	$ref_ip = $_SERVER['REMOTE_ADDR'];
	
	if ($mode == "IC") {

		$title			= SetStringToDB($title);
		$contents		= SetStringToDB($contents);
		
		//$result =  insertBoardComment($conn, $bb_code, $con_cate_01, $con_cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no);
		$result =  insertBoardComment($conn, $bb_code, $con_cate_01, $con_cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $phone, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $main_tf, $top_tf, $use_tf, $s_adm_no);
		
		$iresult = selectBoardComment($conn, $bb_code, $result);		
			$BB_DE					= trim($iresult[0]["bb_de"]); 
			$BB_RE					= trim($iresult[0]["bb_re"]); 
			$BB_PO					= trim($iresult[0]["bb_po"]); 

			$CATE_01				= trim($iresult[0]["cate_01"]);
			$CATE_02				= trim($iresult[0]["cate_02"]);
			$CATE_03				= trim($iresult[0]["cate_03"]);
			$CATE_04				= trim($iresult[0]["cate_04"]);


		if ($result) {
			echo '{"bbscode":"'.$bb_code.'","bbsid":"'.$result.'","bb_de":"'.$BB_DE.'","bb_re":"'.$BB_RE.'","bb_po":"'.$BB_PO.'","cate_01":"'.$CATE_01.'","cate_02":"'.$CATE_02.'","cate_03":"'.$CATE_03.'","cate_04":"'.$CATE_04.'","rstatus":"Success"}';
		}else{
			echo '{"bbscode":"'.$bb_code.'","bbsid":"'.$result.'","rstatus":"Fail"}';
		}
		exit;
	}

	if ($mode == "UC") {

		$title					= SetStringToDB($title);
		$temp_contents	= SetStringToDB($temp_contents);
		
		$result = updateBoardComment($conn, $temp_cate_01, $temp_cate_02, $temp_cate_03, $temp_cate_04, $writer_id, $temp_writer_name, $temp_writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $temp_contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no, $temp_bb_code, $temp_bb_no);
		
		if ($result) {
			echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$temp_bb_no.'","rstatus":"Success"}';
		}else{
			echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$temp_bb_no.'","rstatus":"Fail"}';
		}
		exit;
	}

	if ($mode == "RC") {

		$title					= SetStringToDB($title);
		$temp_contents	= SetStringToDB($temp_contents);

		//$result =  insertBoardReplyComment($conn, $temp_bb_code, $temp_bb_no, $temp_bb_po, $temp_bb_re, $temp_bb_de, $temp_cate_01, $temp_cate_02, $temp_cate_03, $temp_cate_04,$writer_id,  $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $temp_contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no);
		$result =  insertBoardCommentReply($conn, $temp_bb_code, $temp_bb_no, $temp_bb_po, $temp_bb_re, $temp_bb_de, $temp_cate_01, $temp_cate_02, $temp_cate_03, $temp_cate_04, $writer_id, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $temp_contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no);

		$iresult = selectBoardComment($conn, $temp_bb_code, $result);		
			$BB_DE					= trim($iresult[0]["BB_DE"]); 
			$BB_RE					= trim($iresult[0]["BB_RE"]); 
			$BB_PO					= trim($iresult[0]["BB_PO"]); 

			$CATE_01				= trim($iresult[0]["CATE_01"]);
			$CATE_02				= trim($iresult[0]["CATE_02"]);
			$CATE_03				= trim($iresult[0]["CATE_03"]);
			$CATE_04				= trim($iresult[0]["CATE_04"]);

		if ($result) {
			//echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$result.'","rstatus":"Success"}';
			echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$result.'","bb_de":"'.$BB_DE.'","bb_re":"'.$BB_RE.'","bb_po":"'.$BB_PO.'","cate_01":"'.$CATE_01.'","cate_02":"'.$CATE_02.'","cate_03":"'.$CATE_03.'","cate_04":"'.$CATE_04.'","rstatus":"Success"}';
		}else{
			echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$result.'","rstatus":"Fail"}';
		}
		exit;
	}

	if ($mode == "DC") {
		
		$result = deleteBoardComment($conn, $s_adm_no, $temp_bb_code, $temp_bb_no);
		
		if ($result) {
			echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$result.'","rstatus":"Success"}';
		}else{
			echo '{"bbscode":"'.$temp_bb_code.'","bbsid":"'.$result.'","rstatus":"Fail"}';
		}
		exit;
	}



#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
