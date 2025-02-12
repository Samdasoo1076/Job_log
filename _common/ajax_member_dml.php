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

	$mode						= isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');
	$m_no						= isset($_POST["m_no"]) && $_POST["m_no"] !== '' ? $_POST["m_no"] : (isset($_GET["m_no"]) ? $_GET["m_no"] : '');
	$m_id						= isset($_POST["m_id"]) && $_POST["m_id"] !== '' ? $_POST["m_id"] : (isset($_GET["m_id"]) ? $_GET["m_id"] : '');
	$m_phone				= isset($_POST["m_phone"]) && $_POST["m_phone"] !== '' ? $_POST["m_phone"] : (isset($_GET["m_phone"]) ? $_GET["m_phone"] : '');
	$m_pwd					= isset($_POST["m_pwd"]) && $_POST["m_pwd"] !== '' ? $_POST["m_pwd"] : (isset($_GET["m_pwd"]) ? $_GET["m_pwd"] : '');
	$m_organ_name           = isset($_POST["m_organ_name"]) && $_POST["m_organ_name"] !== '' ? $_POST["m_organ_name"] : (isset($_GET["m_organ_name"]) ? $_GET["m_organ_name"] : '');
	$m_biz_no				= isset($_POST["m_biz_no"]) && $_POST["m_biz_no"] !== '' ? $_POST["m_biz_no"] : (isset($_GET["m_biz_no"]) ? $_GET["m_biz_no"] : '');
	$m_name				= isset($_POST["m_name"]) && $_POST["m_name"] !== '' ? $_POST["m_name"] : (isset($_GET["m_name"]) ? $_GET["m_name"] : '');
	$m_addr 				= isset($_POST["m_addr"]) && $_POST["m_addr"] !== '' ? $_POST["m_addr"] : (isset($_GET["m_addr"]) ? $_GET["m_addr"] : '');
	$m_post_cd 			= isset($_POST["m_post_cd"]) && $_POST["m_post_cd"] !== '' ? $_POST["m_post_cd"] : (isset($_GET["m_post_cd"]) ? $_GET["m_post_cd"] : '');
	$m_addr_detail 	= isset($_POST["m_addr_detail"]) && $_POST["m_addr_detail"] !== '' ? $_POST["m_addr_detail"] : (isset($_GET["m_addr_detail"]) ? $_GET["m_addr_detail"] : '');
	$m_ksic					= isset($_POST["m_ksic"]) && $_POST["m_ksic"] !== '' ? $_POST["m_ksic"] : (isset($_GET["m_ksic"]) ? $_GET["m_ksic"] : '');
	$m_email 				= isset($_POST["m_email"]) && $_POST["m_email"] !== '' ? $_POST["m_email"] : (isset($_GET["m_email"]) ? $_GET["m_email"] : '');
	$sms_flag						= isset($_POST["sms_flag"]) && $_POST["sms_flag"] !== '' ? $_POST["sms_flag"] : (isset($_GET["sms_flag"]) ? $_GET["sms_flag"] : '');


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

	if ($mode == "AUTH_PHONE") {

		$m_phone = isset($_POST["m_phone"]) ? $_POST["m_phone"] : "";
		$sms_flag = isset($_POST["sms_flag"]) ? $_POST["sms_flag"] : "";
		$authCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
		$_SESSION['authCode'] = $authCode;
 		$phone = $m_phone;

		//$str_result = send_sms($conn, $m_phone, "", "[원주미래산업진흥원] 인증번호 : ".$authCode." 를 입력해 주세요.", $sms_flag);
		$str_result = biz_send_sms($conn, $phone, $subject, "[원주미래산업진흥원] 인증번호 : ".$authCode." 를 입력해 주세요.", $sms_flag);

		echo json_encode(['success' => true, 'authCode' => $authCode, 'token' => $token, $str_result => '$msg']);
		exit;
	}

	if ($mode == 'AUTH_CODE') {
		$authCode  = isset($_POST["authCode"]) ? $_POST["authCode"] : "";

    if (!isset($_SESSION['authCode'])) {
			echo json_encode(['success' => false, 'message' => '세션에 인증번호가 없습니다.']);
			exit;
		}

    if ((string)$_SESSION['authCode'] === (string)$authCode) {
			echo json_encode(['success' => true, 'message' => '인증 성공', 'debug' => $_SESSION]);
		} else {
				echo json_encode(['success' => false, 'message' => '잘못된 인증번호, 다시 시도해 주세요.', 'debug' => $_SESSION]);
		}
		exit;
	}
	
	if ($mode == "KSIC_DETAIL") {
		$m_ksic = isset($_POST["m_ksic"]) ? $_POST["m_ksic"] : "";

		// $m_ksic_decoded = html_entity_decode($m_ksic);

		if ($m_ksic !== "") {
			$is_ksic_detail = selectKsicCodeDetail($conn, $m_ksic);
			echo json_encode([
				"success" => true,
				"m_ksic" => $m_ksic,
				"data" => $is_ksic_detail
			]);
		} else {
			echo json_encode([
				"success" => false,
				"message" => "1차 산업 분류를 선택해주세요."
			]);
		}
		exit;
	}


	if($mode == "EMAIL_CHANGE") {
		if (empty($m_email) || empty($m_id)) {
				echo json_encode([
						"result" => "F",
						"message" => "이메일 또는 아이디를 확인해주세요."
				]);
				exit;
		}

		$email_enc = encrypt($key, $iv, $m_email);

		$result = changeUserEmail($conn, $m_no, $m_id, $email_enc);

		if ($result) {
				echo json_encode([
						"result" => "T",
						"value"  => decrypt($key, $iv, $result),
						"message" => "이메일이 성공적으로 변경되었습니다."
				]);
		} else {
				echo json_encode([
						"result" => "F",
						"value"  => $result,
						"message" => "이메일 변경에 실패했습니다."
				]);
		}
		exit;
	}

	if ($mode == "PHONE_CHANGE") {
		if (empty($m_phone) || empty($m_id)) {
			echo json_encode([
					"result" => "F",
					"message" => "전화번호 또는 아이디를 확인해주세요."
			]);
			exit;
	}

		$phone_enc = encrypt($key, $iv, $m_phone);

		$result = changeUserPhone($conn, $m_no, $m_id, $phone_enc);

		if ($result) {
			echo json_encode([
					"result" => "T",
					"value"  => decrypt($key, $iv, $result),
					"message" => "전화번호가 성공적으로 변경되었습니다."
				]);
		} else {
				echo json_encode([
						"result" => "F",
						"value"  => $result,
						"message" => "전화번호 변경에 실패했습니다."
				]);
		}
		exit;

	}


	if ($mode == "PWD_CHANGE") {
		if (empty($m_pwd) || empty($m_id)) {
			echo json_encode([
				"result" => "false",
				"message" => "비밀번호 또는 아이디를 확인해주세요."
			]);
			exit;
		}

		$pwd_enc = encrypt($key, $iv, $m_pwd);

		$arr_rs = selectMember($conn, $m_no);

		$old_pwd = $arr_rs[0]['M_PWD'];

		if($pwd_enc == $old_pwd) {
			echo json_encode([
				"result" => "E",	
				"message" => "현재 비밀번호와 동일한 비밀번호입니다."
			]);
			exit;
		}
		
		
		$result = changeUserPwd($conn, $m_no, $m_id, $pwd_enc);

		if ($result) {
			echo json_encode([
				"result" => "T",
							"value"  => decrypt($key, $iv, $result),
				"message" => "비밀번호가 성공적으로 변경되었습니다."
			]);
		} else {
			echo json_encode([
				"result" => "F",
							"value"  => null,
				"message" => "비밀번호 변경에 실패했습니다."
			]);
		}
		exit;
	}

	if($mode == "ORGAN_CHANGE") {
		if (empty($m_organ_name) || empty($m_id)) {
				echo json_encode([
						"result" => "false",
						"message" => "기관명 또는 아이디를 확인해주세요."
				]);
				exit;
		}

		$result = changeUserOrganName($conn, $m_no, $m_id, $m_organ_name);

		if ($result) {
				echo json_encode([
						"result" => "T",
						"value"  => $result,
						"message" => "기관명이 성공적으로 변경되었습니다."
				]);
		} else {
				echo json_encode([
						"result" => "F",
						"value"  => null,
						"message" => "기관명 변경에 실패했습니다."
				]);
		}
		exit;

	}

	if ($mode == "BIZ_CHANGE") {
		if (empty($m_biz_no) || empty($m_id)) {
				echo json_encode([
						"result" => "false",
						"message" => "사업자등록번호 또는 아이디를 확인해주세요."
				]);
				exit;
		}

		$result = changeUserBizNo($conn, $m_no, $m_id, $m_biz_no);

		if ($result) {
				echo json_encode([
						"result" => "T",
						"value"  => $result,
						"message" => "사업자등록번호가 성공적으로 변경되었습니다."
				]);
		} else {
				echo json_encode([
						"result" => "F",
						"value"  => null,
						"message" => "사업자등록번호 변경에 실패했습니다."
				]);
		}
		exit;
	}

	if($mode == "NAME_CHANGE") {
		if (empty($m_name) || empty($m_id)) {
				echo json_encode([
						"result" => "false",
						"message" => "회원명 또는 아이디를 확인해주세요."
				]);
				exit;
		}

		$result = changeUserName($conn, $m_no, $m_id, $m_name);

		if ($result) {
				echo json_encode([
						"result" => "T",
						"value"  => $result,
						"message" => "회원명이 성공적으로 변경되었습니다."
				]);
		} else {
				echo json_encode([
						"result" => "F",
						"value"  => null,
						"message" => "회원명 변경에 실패했습니다."
				]);
		}
		exit;

	}

	if ($mode == "ADDR_CHANGE") {
		if (empty($m_addr) || empty($m_id)) {
				echo json_encode([
						"result" => "false",
						"message" => "주소 또는 아이디를 확인해주세요."
				]);
				exit;
		}

		$addr_enc   			= encrypt($key, $iv, $m_addr);
		$addr_detail_enc  = encrypt($key, $iv, $m_addr_detail);
		$post_cd_enc			= encrypt($key, $iv, $m_post_cd);

		$result = changeUserAddr($conn, $m_no, $m_id, $addr_enc, $addr_detail_enc, $post_cd_enc);

		if ($result) {
			$addr_dec = decrypt($key, $iv, $addr_enc);
			$addr_detail_dec = decrypt($key, $iv, $addr_detail_enc);
			$post_cd_dec = decrypt($key, $iv, $post_cd_enc);

			echo json_encode([
					"result" => "T",
					"value" => [
							"addr" => $addr_dec,
							"addr_detail" => $addr_detail_dec,
							"post_cd" => $post_cd_dec
					],
					"message" => "주소가 성공적으로 변경되었습니다."
			]);
	}else {
				echo json_encode([
						"result" => "F",
						"value"  => null,
						"message" => "주소 변경에 실패했습니다."
				]);
		}
		exit;
	}


	
	db_close($conn);
?>