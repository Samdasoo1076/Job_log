<?session_start();?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/high/high.php";

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$h_no				  	= $_POST['h_no']!=''?$_POST['h_no']:$_GET['h_no'];
	$disp_seq				= $_POST['disp_seq']!=''?$_POST['disp_seq']:$_GET['disp_seq'];

	if ($mode == "change_disp_seq") {
		// 해당 게시물 순서 변경
		$arr_data = array("DISP_SEQ"=>$disp_seq);
		$result = updateHigh($conn, $arr_data, $h_no);

		if ($result) {
			$result = "T";
			$str_result = "정상 처리 되었습니다.";
		} else {
			$result = "F";
			$str_result = "오류 입니다.";
		}
		
		$arr_result = array("result"=>$result, "msg"=>$str_result);
		echo json_encode($arr_result);

	}

	db_close($conn);
?>