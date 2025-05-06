<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$file_type			= $_POST['file_type']!=''?$_POST['file_type']:$_GET['file_type'];
	$b							= $_POST['b']!=''?$_POST['b']:$_GET['b'];

#====================================================================
	$savedir1 = $g_physical_path."/upload_data/board/".$b;
#====================================================================
	
	if ($file_type == "I") {
		$file_nm		= upload($_FILES["file_nm"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		$file_rnm		= $_FILES["file_nm"]["name"];
	} else {
		$file_nm		= upload($_FILES["attach_file_nm"], $savedir1, 1000 , array('jpg', 'png', 'gif', 'hwp', 'ppt', 'pptx', 'xls', 'xlsx', 'doc', 'docx', 'zip', 'pdf'));
		$file_rnm		= $_FILES["attach_file_nm"]["name"];
	}

	echo $file_nm."^".$file_rnm;

?>