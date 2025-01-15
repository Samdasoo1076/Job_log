<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 
	error_reporting(E_ALL & ~E_NOTICE);

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
	require "../_classes/biz/member/member.php";

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$mode				= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$m_id				= isset($_POST["m_id"]) && $_POST["m_id"] !== '' ? $_POST["m_id"] : (isset($_GET["m_id"]) ? $_GET["m_id"] : '');
	$m_ksic				= isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');

	if ($mode == "CHK_ID") {
		if (!empty($m_id)) {

			$is_duplicate = dupMemberIdChk($conn, $m_id);
            
			if ($is_duplicate == 0) {
				$result		= "T";
				$message	= "사용 가능한 아이디입니다.";
			} else {
				$result = "F";
				$message	= "이미 사용 중인 아이디입니다.";
			}

		} else {
			$result = "F";
			$message	= "아이디를 입력해 주세요.";
		}
	
		$arr_result = array("result"=>$result, "message"=>$message);
		echo json_encode($arr_result);

	}

    ?>