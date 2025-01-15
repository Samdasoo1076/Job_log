<?session_start();?>
<?ob_start();
	
	error_reporting(E_ALL);

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/config.php";
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("r");
#=====================================================================
# common function, login_function
#=====================================================================
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/cla_directzip.php";
	require "../_classes/biz/board/board.php";

/*
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_GET_VARS);
	@extract($HTTP_SESSION_VARS);
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);
*/

	extract($_POST);
	extract($_GET);

	global $BUFSIZ;
	$BUFSIZ = 8196; 

	$b_code				= $_POST["b_code"]!=''?$_POST["b_code"]:$_GET["b_code"];
	$b_no					= $_POST["b_no"]!=''?$_POST["b_no"]:$_GET["b_no"];

	//$b_code = "B_1_2";
	//$b_no		= "62961";

	$arr_rs_read = selectBoard($conn, $b_code, $b_no);

	$rs_title		= SetStringFromDB($arr_rs_read[0]["TITLE"]); 

	$arr_board_files = listBoardFile($conn, $b_code, $b_no);
	$str_path = $g_physical_path."upload_data/board/".$b_code."/";


	$str_file = "";

	$zip = new DirectZip();
	$zip->open($rs_title.'.zip');
	
	if (sizeof($arr_board_files) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_board_files); $j++) {

			$FILE_NM = trim($arr_board_files[$j]["FILE_NM"]);
			$FILE_RNM = trim($arr_board_files[$j]["FILE_RNM"]);

			//echo $str_path.$FILE_NM."<br>";

			$zip->addFile($str_path.$FILE_NM, $FILE_RNM);
		}
	}

	//$zip->close();

	// 다운로드 될 zip 파일명
	//$downZipName = "zip_test.zip";

	// 생성한 zip 파일을 다운로드하기
	//header("Content-type: application/zip");
	//header("Content-Disposition: attachment; filename=$downZipName"); 
	//readfile($zipName);
	//unlink($zipName);

#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>
