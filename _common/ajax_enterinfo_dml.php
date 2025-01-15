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
	require "../_classes/biz/enter/enter.php";

	$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ea_name				= $_POST['ea_name']!=''?$_POST['ea_name']:$_GET['ea_name'];
	$ea_pwd					= $_POST['ea_pwd']!=''?$_POST['ea_pwd']:$_GET['ea_pwd'];
	$ea_phone1			= $_POST['ea_phone1']!=''?$_POST['ea_phone1']:$_GET['ea_phone1'];
	$ea_phone2			= $_POST['ea_phone2']!=''?$_POST['ea_phone2']:$_GET['ea_phone2'];
	$ea_phone3			= $_POST['ea_phone3']!=''?$_POST['ea_phone3']:$_GET['ea_phone3'];
	$ean_no					= $_POST['ean_no']!=''?$_POST['ean_no']:$_GET['ean_no'];

	$ea_phone = $ea_phone1."-".$ea_phone2."-".$ea_phone3;

	if ($mode == "CONFIRM_PASSWORD") {

		$en_ea_pwd = base64_encode(hash('sha256', $ea_pwd, true));

		$arr_rs = selectEnterInfoApply($conn, $ea_name, $en_ea_pwd, $ea_phone); 

		if (sizeof($arr_rs) > 0) {

			$result = "T";

			$list_str = "";
			$all = "";
			$id = 0;
			$msg		= "";


			$list_str = $list_str . "<h4>신청내역</h4>";

			for($i = 0 ; $i < sizeof($arr_rs) ; $i++){  //같은 이름으로 다시 신청시
	
				$EA_NO					= trim($arr_rs[$i]["EA_NO"]);
				$EA_NAME				= trim($arr_rs[$i]["EA_NAME"]);
				$EA_WHO					= trim($arr_rs[$i]["EA_WHO"]);
				$EA_POST				= trim($arr_rs[$i]["EA_POST"]);
				$EA_ADDR				= trim($arr_rs[$i]["EA_ADDR"]);
				$EA_ADDR_DETAIL	= trim($arr_rs[$i]["EA_ADDR_DETAIL"]);
				$EA_PHONE				= trim($arr_rs[$i]["EA_PHONE"]);
				$EA_MEMO				= trim($arr_rs[$i]["EA_MEMO"]);
				$APPLY_TF				= trim($arr_rs[$i]["APPLY_TF"]);
				$USE_TF					= trim($arr_rs[$i]["USE_TF"]);
				$REG_DATE				= trim($arr_rs[$i]["REG_DATE"]);

				$EA_MEMO = str_replace(":", "<br />", $EA_MEMO);

				$SEND_DATE			= $arr_rs[$i]["SEND_DATE"];

				if ($APPLY_TF == "Y") {
					$STR_APPLY_TF = "발송";
				} else { 
					$STR_APPLY_TF = "접수";
				}

				$list_str = $list_str . "<table class='type-ta'>";
				$list_str = $list_str . "<tr>";
				$list_str = $list_str . "<th class='text-left'><!--<input type='checkbox' name='ean_no[]' value='".$EAN_NO."' id='enter_info".$i."'>--><label for='enter_info".$i."'>신청자료</label></th>";
				$list_str = $list_str . "<td>".$EA_MEMO."</td>";
				$list_str = $list_str . "</tr>";
				$list_str = $list_str . "<tr>";
				$list_str = $list_str . "<th class='text-left'><label for='input-name'>이름</label></th>";
				$list_str = $list_str . "<td>".$EA_NAME."</td>";
				$list_str = $list_str . "</tr>";
				$list_str = $list_str . "<tr>";
				$list_str = $list_str . "<th class='text-left'><label for='input-type'>분류</label></th>";
				$list_str = $list_str . "<td>".$EA_WHO."</td>";
				$list_str = $list_str . "</tr>";
				$list_str = $list_str . "<tr>";
				$list_str = $list_str . "<th class='text-left'><label for='input-type'>휴대전화</label></th>";
				$list_str = $list_str . "<td>".$EA_PHONE."</td>";
				$list_str = $list_str . "</tr>";
				$list_str = $list_str . "<tr>";
				$list_str = $list_str . "<th class='text-left'><label for='input-type'>주소</label></th>";
				$list_str = $list_str . "<td>[".$EA_POST."] ".$EA_ADDR." ".$EA_ADDR_DETAIL."</td>";
				$list_str = $list_str . "</tr>";
				$list_str = $list_str . "<tr>";
				$list_str = $list_str . "<th class='text-left'><label for='input-type'>상태</label></th>";
				$list_str = $list_str . "<td>".$STR_APPLY_TF."</td>";
				$list_str = $list_str . "</tr>";

				if ($APPLY_TF == "Y") {
					$list_str = $list_str . "<th class='text-left'><label for='input-type'>발송일</label></th>";
					$list_str = $list_str . "<td>".$SEND_DATE."</td>";
					$list_str = $list_str . "</tr>";
				} else { 
					$list_str = $list_str . "<th class='text-left'><label for='input-type'>접수일</label></th>";
					$list_str = $list_str . "<td>".$REG_DATE."</td>";
					$list_str = $list_str . "</tr>";
				}

				$list_str = $list_str . "</table><br /><br /><br />";
			}


		} else {
			$result = "F";
			$list_str = "";
			$msg		= "신청한 내역이 없거나 비밀번호 오류입니다. 다시 한번 확인해 주세요";
		}

		$arr_result = array("result"=>$result, "list_str"=>$list_str, "msg"=>$msg, "all"=>$all);
		echo json_encode($arr_result);
		
	}

	if ($mode == "DEL_APPLY") {

		$tmp = "";

		for ($j = 0 ; $j < sizeof($ean_no) ; $j++){
				$tmp = $ean_no[$j];
				$result = deleteEnterInfoApplyNum($conn, 0, $tmp);
		}
		
		$arr_result = array("result"=>$result);
		echo json_encode($arr_result);

	}

	db_close($conn);
?>