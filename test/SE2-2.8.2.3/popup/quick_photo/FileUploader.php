<?php
session_start();

//기본 리다이렉트
echo $_REQUEST["htImageInfo"];

$url = $_REQUEST["callback"] .'?callback_func='. $_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);
if ($bSuccessUpload) { //성공 시 파일 사이즈와 URL 전송
	
	require "../../../../_classes/com/util/Util.php";
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	//$new_path = "../../../upload_data/board/".urlencode($_FILES['Filedata']['name']);
	//@move_uploaded_file($tmp_name, $new_path);

	

	$g_physical_path = $_SERVER['DOCUMENT_ROOT']."/"; 
	$savedir1 = $g_physical_path."upload_data/board";
	//$file_nm		= upload($_FILES['Filedata'], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','mp3','mp4','avi','wmv','txt','wav','mid','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','MP3','MP3','AVI','WMV','TXT','WAV','MID','mov','MOV','mpg','MPG'));
	$file_nm		= upload($_FILES['Filedata'], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png','GIF', 'JPEG', 'JPG','PNG'));
	$file_rnm		= $_FILES['file_nm']['name'];
	$file_size	= $_FILES['file_nm']['size'];
	$file_ext		= end(explode('.', $_FILES['file_nm']['name']));

	

	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($name));
	//$url .= "&size=". $_FILES['Filedata']['size'];
	//아래 URL을 변경하시면 됩니다.
	//$url .= "&sFileURL=http://".$_SERVER[HTTP_HOST]."/upload_data/board/".urlencode(urlencode($file_nm));
	//$url .= "&sFileURL=http://".$_SERVER[HTTP_HOST]."/upload_data/board/".$file_nm;
	$url .= "&sFileURL=/upload_data/board/".$file_nm;
} else { //실패시 errstr=error 전송
	$url .= '&errstr=error';
}
header('Location: '. $url);
?>