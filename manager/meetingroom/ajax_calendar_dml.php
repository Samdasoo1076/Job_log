<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 
	error_reporting(E_ALL & ~E_NOTICE);

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/meetingroom/meetingroom.php";

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$room_no				= isset($_POST["room_no"]) && $_POST["room_no"] !== '' ? $_POST["room_no"] : (isset($_GET["room_no"]) ? $_GET["room_no"] : '');
	$datetime				= isset($_POST["datetime"]) && $_POST["datetime"] !== '' ? $_POST["datetime"] : (isset($_GET["datetime"]) ? $_GET["datetime"] : '');
	$chk_flag				= isset($_POST["chk_flag"]) && $_POST["chk_flag"] !== '' ? $_POST["chk_flag"] : (isset($_GET["chk_flag"]) ? $_GET["chk_flag"] : '');

	if ($mode == "DATE_TIME_TOGGLE") {

		$arr_date_time = explode("^", $datetime);

		if ($chk_flag == "true") {

			$arr_data = array("ROOM_NO"=>$room_no,
												"DISABLE_DATE"=>$arr_date_time[0],
												"DISABLE_TIME"=>$arr_date_time[1],
												"REG_ADM"=>$_SESSION["s_adm_no"]
											 );

			$result = insertMeetingRoomDisable($conn, $arr_data);

		} else {

			$result = deleteMeetingRoomDisableDate($conn, $room_no, $arr_date_time[0], $arr_date_time[1]);

		}

		if ($result) {
			$result		= "T";
			$message  = "";
		} else {
			$result		= "F";
			$message = "새로 고침 후 다시 시도해 주세요.";
		}

		$arr_result = array("result"=>$result, "message"=>$message);
		echo json_encode($arr_result);

	}

	db_close($conn);
?>