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
	require "../_classes/biz/board/board.php";

	$b_code				= $_POST["b_code"]!=''?$_POST["b_code"]:$_GET["b_code"];
	$b_no					= $_POST["b_no"]!=''?$_POST["b_no"]:$_GET["b_no"];

	$b_code = "B_1_2";
	$b_code = "62961";

	$arr_board_files = listBoardFile($conn, $b_code, $b_no);


	$str_path = $g_physical_path."upload_data/board/".$b_code."/";

	$arr_real_file = array();
	$arr_file_name = array();

	if (sizeof($arr_board_files) > 0) {
		for ($j = 0 ; $j < sizeof($arr_board_files); $j++) {
			
			$RS_FILE_NM			= trim($arr_board_files[$j]["FILE_NM"]);
			$RS_FILE_RNM		= trim($arr_board_files[$j]["FILE_RNM"]);
			
			$arr_real_file[$j] = $str_path.$RS_FILE_NM;
			$arr_file_name[$j] = $RS_FILE_RNM;
		}
	}

	//if ($arr_board_files

	// 가상의 경로를 가진 배열 생성
	// $files = ['upload/zipFile_1.txt', 'upload/zipFile_2.txt'];
	// $filePath = $_SERVER['DOCUMENT_ROOT']."/";

	$zip = new ZipArchive();
	exit;

	// zip 아카이브 생성하기 위한 고유값
	$zipName = time()."zip";

	// zip 아카이브 생성 여부 확인
	if (!$zip->open($zipName, ZipArchive::CREATE)) {
		exit("error");
	}

	// addFile ( 파일이 존재하는 경로, 저장될 이름 )
	
	if (sizeof($arr_real_file) > 0) {
		for ($j = 0 ; $j < sizeof($arr_real_file); $j++) {
			$zip->addFile($arr_real_file[$j], $arr_file_name[$j]);
		}
	}

	//foreach ($files as $fileName) {
	//	$zip->addFile($filePath.$fileName, $fileName);
	//}

	// 아카이브 닫아주기
	$zip->close();

	// 다운로드 될 zip 파일명
	$downZipName = "zip_test.zip";

	// 생성한 zip 파일을 다운로드하기
	header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename=$downZipName"); 
	readfile($zipName);
	unlink($zipName);

#=====================================================================
# DB Close
#=====================================================================
	db_close($conn);
?>
