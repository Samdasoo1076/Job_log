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

#====================================================================
	$savedir1 = $g_physical_path."/upload_data/program";
#====================================================================
	
	$file_nm		= upload($_FILES["file_nm"], $savedir1, 1000 , array('pdf', 'hwp', 'doc','docx','zip'));
	$file_rnm		= $_FILES["file_nm"]["name"];

	echo $file_nm."^".$file_rnm;

?>