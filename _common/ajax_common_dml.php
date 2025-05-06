<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 
	error_reporting(E_ALL & ~E_NOTICE);
	
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$pcode					= $_POST['pcode']!=''?$_POST['pcode']:$_GET['pcode'];
	$dcode					= $_POST['dcode']!=''?$_POST['dcode']:$_GET['dcode'];
	$dcode_ext			= $_POST['dcode_ext']!=''?$_POST['dcode_ext']:$_GET['dcode_ext'];

	// 시군구
	$sido						= $_POST['sido']!=''?$_POST['sido']:$_GET['sido'];
	$sigungu				= $_POST['sigungu']!=''?$_POST['sigungu']:$_GET['sigungu'];

	// 중복값
	$reg_email			= $_POST['reg_email']!=''?$_POST['reg_email']:$_GET['reg_email'];
	$idx						= $_POST['idx']!=''?$_POST['idx']:$_GET['idx'];

	// 학교 검색
	$school_name		= $_POST['school_name']!=''?$_POST['school_name']:$_GET['school_name'];
	
	// 주소 검색
	$addr_name			= $_POST['addr_name']!=''?$_POST['addr_name']:$_GET['addr_name'];

	$book_name			= $_POST['book_name']!=''?$_POST['book_name']:$_GET['book_name'];
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	
	// 입학자료 신청 
	$authors				= $_POST['authors']!=''?$_POST['authors']:$_GET['authors'];
	$contents				= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
	$datetime				= $_POST['datetime']!=''?$_POST['datetime']:$_GET['datetime'];
	$isbn						= $_POST['isbn']!=''?$_POST['isbn']:$_GET['isbn'];
	$publisher			= $_POST['publisher']!=''?$_POST['publisher']:$_GET['publisher'];
	$thumbnail			= $_POST['thumbnail']!=''?$_POST['thumbnail']:$_GET['thumbnail'];
	$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$translators		= $_POST['translators']!=''?$_POST['translators']:$_GET['translators'];
	$tmp_session_no	= $_POST['tmp_session_no']!=''?$_POST['tmp_session_no']:$_GET['tmp_session_no'];

	$is_apply				= $_POST['is_apply']!=''?$_POST['is_apply']:$_GET['is_apply'];


	// 지출 보고
	$cate01					= $_POST['cate01']!=''?$_POST['cate01']:$_GET['cate01'];
	$cate02					= $_POST['cate02']!=''?$_POST['cate02']:$_GET['cate02'];


	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($nPage == "") $nPage = 1;
	if ($nPage == 0) $nPage = 1;

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/admin/admin.php";
	require "../_classes/biz/board/board.php";


	if ($mode == "GET_DCODE_LIST") {

		$arr_rs = getListDcode($conn, $pcode, $dcode_ext);
		echo json_encode($arr_rs);

	}

	if ($mode == "GATE_SET_COOKIE") {
		
		$INS_DATE = date("Y-m-d",strtotime("0 day"));
		setcookie("c_today", $INS_DATE, time() + 3600 * 24, "/");
		
		$result = "T";
		$arr_result = array("result"=>$result);
		echo json_encode($arr_result);

	}


	if ($mode == "FRONT_SEARCH_SCHOOL") {

		if ($school_name == "") {

			$nListCnt = 0;
			$list_str = "";

		} else {

			//$school_name = str_replace(" ", "", $school_name);

			$arr_rs = getListSchoolSearch($conn, $school_name);
			
			$list_str = "";

			$nListCnt = sizeof($arr_rs);
			
			if (sizeof($arr_rs) > 0) {
			
				for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

					$H_SC_CODE		= trim($arr_rs[$j]["H_SC_CODE"]);
					$H_SC_NAME		= trim($arr_rs[$j]["H_SC_NAME"]);
					$H_AREA				= trim($arr_rs[$j]["H_AREA"]);

					$list_str = $list_str . "<tr>";
					$list_str = $list_str . "<td><a href=\"javascript:js_sel_school('".$H_SC_CODE."','".$H_SC_NAME."','".$H_AREA."');\">".$H_SC_NAME."</a></td>";
					$list_str = $list_str . "<td>".$H_AREA."</td>";
					$list_str = $list_str . "</tr>";

				}
			}
		}

		$result = "T";
		$arr_result = array("result"=>$result, "total"=>$nListCnt, "list"=>$list_str);
		echo json_encode($arr_result);

	}


	if ($mode == "FRONT_SEARCH_ADDR") {

		if ($nPage == "") $nPage = 1;
		if ($nPage == 0) $nPage = 1;

		if ($nPage <> "") {
			$nPage = (int)($nPage);
		} else {
			$nPage = 1;
		}

		if ($nPageSize <> "") {
			$nPageSize = (int)($nPageSize);
		} else {
			$nPageSize = 50;
		}

		$nPageBlock	= 5;

		if ($addr_name == "") {

			$nListCnt = 0;
			$nTotalPage = "1";
			$nPage = "1";
			$arr_rs = null;

		} else {

			$addr_name = str_replace(" ", "", $addr_name);

			$res = requestAddrSearch($addr_name, $nPage, $nPageSize);

			$res = json_decode($res);
			
			$nListCnt = $res->results->common->totalCount;
			
			$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

			if ((int)($nTotalPage) < (int)($nPage)) {
				$nPage = $nTotalPage;
			}

			$list_str = "";

			for ($j = 0 ; $j < sizeof($res->results->juso) ; $j++) {

				$zipNo				= $res->results->juso[$j]->zipNo;
				$roadAddr			= $res->results->juso[$j]->roadAddr;
				$siNm					= $res->results->juso[$j]->siNm;
				$sggNm				= $res->results->juso[$j]->sggNm;

				$roadAddr = str_replace('"','##쌍따움표##', $roadAddr);
				$roadAddr = str_replace("'","##따움표##", $roadAddr);
				
				$list_str = $list_str . "<li>";
				$list_str = $list_str . "<a href=\"javascript:js_sel_addr('".$zipNo."','".$roadAddr."','".$siNm."','".$sggNm."');\">";
				$list_str = $list_str . "<strong>".$zipNo."</strong><span>".$res->results->juso[$j]->roadAddr."</span>";
				$list_str = $list_str . "</a>";
				$list_str = $list_str . "</li>";

			}
		}

		$page_str = Front_Popup_Image_PageList ("js_addr_page", $nPage, $nTotalPage, $nPageBlock);

		$result = "T";
		$arr_result = array("result"=>$result, "total"=>$nListCnt, "totalpage"=>$nTotalPage, "list"=>$list_str, "page"=>$page_str);
		echo json_encode($arr_result);

	}

	db_close($conn);
?>