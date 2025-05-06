<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/recommend/recommend.php";

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$search_str			= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$field_str			= $_POST['field_str']!=''?$_POST['field_str']:$_GET['field_str'];
	$rtype					= $_POST['rtype']!=''?$_POST['rtype']:$_GET['rtype'];
	$rcate					= $_POST['rcate']!=''?$_POST['rcate']:$_GET['rcate'];
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$mPage					= $_POST['mPage']!=''?$_POST['mPage']:$_GET['mPage'];
	$nPageSize			= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];

	if ($nPage == "") $nPage = 1;
	if ($nPage == 0) $nPage = 1;
	if ($mPage == "") $mPage = 1;
	if ($mPage == 0) $mPage = 1;

	if ($mode == "LIST") {

		if ($nPage <> "") {
			$nPage = (int)($nPage);
		} else {
			$nPage = 1;
		}

		if ($nPageSize <> "") {
			$nPageSize = (int)($nPageSize);
		} else {
			$nPageSize = 10;
		}

		$nPageBlock	= 5;

		$con_use_tf = "Y";
		$con_del_tf = "N";
		
		$nListCnt =totalCntRecommend($conn, $rtype, $rcate, $con_info_01, $con_del_tf, $field_str, $search_str);

		$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;
		
		if ((int)($nTotalPage) < (int)($nPage)) {
			$nPage = $nTotalPage;
		}

		//echo "nPage : ".$nPage."<br>";
		//echo "nPageSize : ".$nPageSize."<br>";
		//echo "nListCnt : ".$nListCnt."<br>";
		$arr_rs = listRecommend($conn, $rtype, $rcate, $con_info_01, $con_del_tf, $field_str, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);

		$result = "T";
		
		$list_str = "";

		if (sizeof($arr_rs) > 0) {

			for ($j = 0 ;$j < sizeof($arr_rs) ; $j++) {

				$rn								= trim($arr_rs[$j]["rn"]);
				$BOOK_NO					= trim($arr_rs[$j]["BOOK_NO"]);
				$RECOMMEND_TYPE		= SetStringFromDB($arr_rs[$j]["RECOMMEND_TYPE"]);
				$CATEGORY					= SetStringFromDB($arr_rs[$j]["CATEGORY"]);
				$BOOK_NM					= SetStringFromDB($arr_rs[$j]["BOOK_NM"]);
				$BOOK_IMG					= trim($arr_rs[$j]["BOOK_IMG"]);
				$AUTHORS					= SetStringFromDB($arr_rs[$j]["AUTHORS"]);
				$TRANSLATORS			= SetStringFromDB($arr_rs[$j]["TRANSLATORS"]);
				$PUBLISHER				= trim($arr_rs[$j]["PUBLISHER"]);
				$PUBLISH_DATE			= trim($arr_rs[$j]["PUBLISH_DATE"]);
				$ISBN							= trim($arr_rs[$j]["ISBN"]);
				$INFO_01					= SetStringFromDB($arr_rs[$j]["INFO_01"]);
				$CONTENTS					= SetStringFromDB($arr_rs[$j]["BOOK_CONTENTS"]);
				$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);

				$str_reg_date			= date("Y.m.d",strtotime($REG_DATE));

				$list_str = $list_str . "<li>\n";
				$list_str = $list_str . "<div class='picbox'>\n";
				$list_str = $list_str . "<ul>\n";

				if ($BOOK_IMG <> "") {
					$list_str = $list_str . "<li><img src='".$BOOK_IMG."' alt='' /></li>\n";
				}

				$list_str = $list_str . "</ul>\n";
				$list_str = $list_str . "</div>\n";
				$list_str = $list_str . "<a href=\"javascript:js_view('".$BOOK_NO."')\">\n";
				$list_str = $list_str . "<div>\n";
				$list_str = $list_str . "<label>".$CATEGORY."</label>\n";
				$list_str = $list_str . "<strong>".$BOOK_NM."</strong>\n";
				$list_str = $list_str . "<p class='info'><span>".$AUTHORS."</span>\n";
				$list_str = $list_str . "<span>".$PUBLISHER." ".$PUBLISH_DATE."</span><span>".$ISBN."</span></p>\n";
				$list_str = $list_str . "<p class='txt'>".$CONTENTS."..</p>\n";

				$list_str = $list_str . "<div class='recommend-list'>\n";
				$list_str = $list_str . "<ul>\n";
				$list_str = $list_str . "<li><span>".$INFO_01."</span></li>\n";
				//$list_str = $list_str . "<li><span>국립중앙도서관</span></li>\n";
				//$list_str = $list_str . "<li><span>2019년 9월 사서추천도서</span></li>\n";
				$list_str = $list_str . "</ul>\n";
				$list_str = $list_str . "</div>\n";

				$list_str = $list_str . "<em class='date'>".$str_reg_date."</em>\n";
				$list_str = $list_str . "</div>\n";
				$list_str = $list_str . "</a>\n";
				$list_str = $list_str . "</li>\n";

			}
		}

		$page_str = Front_Image_PageList($nPage, $nTotalPage, $nPageBlock);

		$script_str = "<script>
$('.picbox ul').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	dots: true,
	touchMove: false
});
</script>";

		$arr_result = array("result"=>$result, "total"=>$nListCnt, "totalpage"=>$nTotalPage, "list"=>$list_str, "page"=>$page_str , "script"=>$script_str);
		echo json_encode($arr_result);
	
	}

	if ($mode == "ADD") {

		if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str'])) {

			$result = "F";
			$msg		= "정상적인 글 등록 방식이 아닙니다.";

			$arr_result = array("result"=>$result, "msg"=>$msg);
			echo json_encode($arr_result);

			db_close($conn);
			exit;
		} 

		$writer_nm			= $_POST['writer_nm']!=''?$_POST['writer_nm']:$_GET['writer_nm'];
		$email_01				= $_POST['email_01']!=''?$_POST['email_01']:$_GET['email_01'];
		$email_02				= $_POST['email_02']!=''?$_POST['email_02']:$_GET['email_02'];
		$writer_pw			= $_POST['writer_pw']!=''?$_POST['writer_pw']:$_GET['writer_pw'];
		$cate_02				= $_POST['cate_02']!=''?$_POST['cate_02']:$_GET['cate_02'];
		$cate_04				= $_POST['cate_04']!=''?$_POST['cate_04']:$_GET['cate_04'];
		$info_01				= $_POST['info_01']!=''?$_POST['info_01']:$_GET['info_01'];
		$info_02				= $_POST['info_02']!=''?$_POST['info_02']:$_GET['info_02'];
		$info_03				= $_POST['info_03']!=''?$_POST['info_03']:$_GET['info_03'];
		$info_04				= $_POST['info_04']!=''?$_POST['info_04']:$_GET['info_04'];
		$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
		$contents				= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
		$add_img				= $_POST['add_img']!=''?$_POST['add_img']:$_GET['add_img'];
		$add_img_rm			= $_POST['add_img_rm']!=''?$_POST['add_img_rm']:$_GET['add_img_rm'];
		$add_files			= $_POST['add_files']!=''?$_POST['add_files']:$_GET['add_files'];
		$add_files_rm		= $_POST['add_files_rm']!=''?$_POST['add_files_rm']:$_GET['add_files_rm'];

		$writer_nm			= SetStringToDB($writer_nm);
		$info_03				= SetStringToDB($info_03);
		$title					= SetStringToDB($title);
		$contents				= SetStringToDB($contents);

		$en_writer_pw		= md5($writer_pw);
		$email = $email_01."@".$email_02;

		$b_re = getBoardNextRe($conn);
		$b_po = "";

		$arr_data = array("B_CODE"=>$bcode,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"CATE_01"=>$cate_01,
											"CATE_02"=>$cate_02,
											"CATE_03"=>$cate_03,
											"CATE_04"=>$cate_04,
											"WRITER_ID"=>$writer_nm,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nm,
											"WRITER_PW"=>$en_writer_pw,
											"EMAIL"=>$email,
											"PHONE"=>$phone,
											"HOMEPAGE"=>$homepage,
											"TITLE"=>$title,
											"REF_IP"=>$_SERVER['REMOTE_ADDR'],
											"CONTENTS"=>$contents,
											"KEYWORD"=>$keyword,
											"LINK01"=>$link01,
											"LINK02"=>$link02,
											"SECRET_TF"=>$secret_tf,
											"MAIN_TF"=>$main_tf,
											"INFO_01"=>$info_01,
											"INFO_02"=>$info_02,
											"INFO_03"=>$info_03,
											"INFO_04"=>$info_04,
											"INFO_05"=>$info_05,
											"TOP_TF"=>$top_tf,
											"REF_TF"=>$ref_tf,
											"COMMENT_TF"=>$comment_tf,
											"REPLY_STATE"=>"N",
											"USE_TF"=>"Y",
											"FILE_NM"=>$add_img,
											"FILE_RNM"=>$add_img_rm,
											"REG_ADM"=>0,
											"REG_DATE"=>$INS_DATE);

		$new_b_no =  insertBoard($conn, $arr_data);
		
		$arr_add_files			= explode("^",$add_files);
		$arr_add_files_rm		= explode("^",$add_files_rm);
		
		if (sizeof($arr_add_files) > 0) {
			for ($j = 0 ; $j < sizeof($arr_add_files) ; $j++) {
				$result_file = insertBoardFile($conn, $bcode, $new_b_no, $arr_add_files[$j], $arr_add_files_rm[$j], "", "", "", "0");
			}
		}

		$result = "T";
		$msg		= "글이 등록 되었습니다. 담당자의 확인 및 승인 완료 후 홈페이지에 게시됩니다.";

		$arr_result = array("result"=>$result, "msg"=>$msg);
		echo json_encode($arr_result);
		$_SESSION['s_encrypt_str'] = "";

	}

	db_close($conn);
?>