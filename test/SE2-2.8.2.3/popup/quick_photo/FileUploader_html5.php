<?php

session_start();

 	$sFileInfo = '';
	$headers = array(); 
	foreach ($_SERVER as $k => $v){   
  	
		if(substr($k, 0, 9) == "HTTP_FILE"){ 
			$k = substr(strtolower($k), 5); 
			$headers[$k] = $v; 
		} 
	}
	
	$file = new stdClass; 
	$file->name = rawurldecode($headers['file_name']);	
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input"); 

	$file_ext = end(explode('.', $file->name));   
	$fn_rand = mt_rand (0, (strlen ($file->name)));
	$writeday = date("YmdHis",strtotime("0 day"));
	$temp_file_name = $writeday."_".$fn_rand.".".$file_ext;
	

	$g_physical_path = $_SERVER[DOCUMENT_ROOT]."/"; 
	$newPath = $g_physical_path.'upload_data/board/'.iconv("utf-8", "cp949", $temp_file_name);
	#$newPath = $g_physical_path.'upload_data/board/'.iconv("utf-8", "cp949", $file->name);
	
	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$file->name;
		$sFileInfo .= "&sFileURL=/upload_data/board/".$temp_file_name;
		//$sFileInfo .= "&sFileURL=http://".$_SERVER[HTTP_HOST]."/upload_data/board/".$temp_file_name;
		#$sFileInfo .= "&sFileURL=http://".$_SERVER[HTTP_HOST]."/upload_data/board/".$file->name;
	}
	echo $sFileInfo;
 ?>
