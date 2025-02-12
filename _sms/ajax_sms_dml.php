<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 

	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/etc/etc.php";

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$msg						= isset($_POST["msg"]) && $_POST["msg"] !== '' ? $_POST["msg"] : (isset($_GET["msg"]) ? $_GET["msg"] : '');
	$rphone					= isset($_POST["rphone"]) && $_POST["rphone"] !== '' ? $_POST["rphone"] : (isset($_GET["rphone"]) ? $_GET["rphone"] : '');

	//$str_result = send_sms ($conn, "01052264159", "", "찬호의 폰으로 테스트를 합니다.", "초기테스트");

	if ($mode == "SEND_SMS") {
		
		$sms_url = "https://sslsms.cafe24.com/sms_sender.php";	// 전송요청 URL
		$sms['user_id'] = base64_encode("wfiwfisms");						//SMS 아이디.
		$sms['secure'] = base64_encode("b0737bbf00b15fe5e968db4e7db92694") ;//인증키
		$sms['msg'] = base64_encode(stripslashes($msg));

		if( $_POST['smsType'] == "L"){
			$sms['subject'] =  base64_encode($_POST['subject']);
		}

		$sms['rphone'] = base64_encode($rphone);
		$sms['sphone1'] = base64_encode("033");
		$sms['sphone2'] = base64_encode("764");
		$sms['sphone3'] = base64_encode("9020");

		/* 값 없어도 되요.*/
		$sms['rdate'] = base64_encode($_POST['rdate']);
		$sms['rtime'] = base64_encode($_POST['rtime']);
		$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$sms['returnurl'] = base64_encode($_POST['returnurl']);
		$sms['testflag'] = base64_encode($_POST['testflag']);
		$sms['destination'] = strtr(base64_encode($_POST['destination']), '+/=', '-,');
		$returnurl = $_POST['returnurl'];
		$sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
		$sms['repeatNum'] = base64_encode($_POST['repeatNum']);
		$sms['repeatTime'] = base64_encode($_POST['repeatTime']);
		$sms['smsType'] = base64_encode($_POST['smsType']); // LMS일경우 L
		$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3]."/".$host_info[4];

		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
		//print_r($sms);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		foreach($sms AS $index => $value){
			$data .="--$boundary\r\n";
			$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			$data .= "\r\n".$value."\r\n";
			$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

		$fp = fsockopen($host, 80);

		if ($fp) {
			fputs($fp, $header.$data);
			$rsp = '';
			while(!feof($fp)) {
				$rsp .= fgets($fp,8192);
			}
			fclose($fp);
			$msg = explode("\r\n\r\n",trim($rsp));
			$rMsg = explode(",", $msg[1]);
			$Result= $rMsg[0]; //발송결과
			$Count= $rMsg[1]; //잔여건수

			//발송결과 알림
			if($Result=="success") {

				$result		= "T";
				$message	= "문자를 전송하였습니다.";

				//$alert = "성공";
				//$alert .= " 잔여건수는 ".$Count."건 입니다.";

			} else if ($Result=="reserved") {

				$result		= "T";
				$message	= "문자를 전송하였습니다.";

				//$alert = "성공적으로 예약되었습니다.";
				//$alert .= " 잔여건수는 ".$Count."건 입니다.";
			} else if($Result=="3205") {

				$result		= "F";
				$message	= "잘못된 번호형식입니다.";

			} else if($Result=="0044") {

				$result		= "F";
				$message	= "스팸문자는발송되지 않습니다.";

				$alert = "";
			} else {
				$result		= "F";
				$message	= "[Error]".$Result;
				//$alert = "[Error]".$Result;
			}
		} else {
			$result		= "F";
			$message	= "Connection Failed";
			//$alert = "Connection Failed";
		}

		$arr_result = array("result"=>$result, "message"=>$message);
		echo json_encode($arr_result);

	}

	db_close($conn);
?>