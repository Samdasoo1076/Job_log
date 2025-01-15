<?

// 문자열이 한글, 영문, 숫자, 특수문자로 구성되어 있는지 검사
function check_string($str, $charset, $options) {

	$s = '';
	for($i=0;$i<strlen($str);$i++) {
		$c = $str[$i];
		$oc = ord($c);

		// 한글
		if ($oc >= 0xA0 && $oc <= 0xFF) {
			if (strtoupper($charset == 'utf-8')) {
				if ($options & _HANGUL_) {
					$s .= $c . $str[$i+1] . $str[$i+2];
				}
				$i+=2;
			} else {
				// 한글은 2바이트 이므로 문자하나를 건너뜀
				$i++;
				if ($options & _HANGUL_) {
					$s .= $c . $str[$i];
				}
			}
		} else if ($oc >= 0x30 && $oc <= 0x39) { // 숫자
			if ($options & _NUMERIC_) {
				$s .= $c;
			}
		} else if ($oc >= 0x41 && $oc <= 0x5A) { // 영대문자
			if (($options & _ALPHABETIC_) || ($options & _ALPHAUPPER_)) {
				$s .= $c;
			}
		} else if ($oc >= 0x61 && $oc <= 0x7A) { // 영소문자
			if (($options & _ALPHABETIC_) || ($options & _ALPHALOWER_)) {
				$s .= $c;
			}
		} else if ($oc >= 0x20) { // 공백
			if ($options & _SPACE_) {
				$s .= $c;
			}
		} else {
			if ($options & _SPECIAL_) {
				$s .= $c;
			}
		}
	}
	// 넘어온 값과 비교하여 같으면 참, 틀리면 거짓
	return ($str == $s);
}


// 경고메세지를 경고창으로
function alert($msg='', $url='') {
	
	if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=euc-kr\">";
	echo "<script type='text/javascript'>alert('$msg');";
	
	if (!$url)
		echo "history.go(-1);";
		echo "</script>";
	
	if ($url)
		// 4.06.00 : 불여우의 경우 아래의 코드를 제대로 인식하지 못함
		//echo "<meta http-equiv='refresh' content='0;url=$url'>";
		goto_url($url);
	exit;
}


function goto_url($url) {
	echo "<script type='text/javascript'> location.replace('$url'); </script>";
	exit;
}

function getContentImages($contents){  //이미지 태그 축출
	$contents = stripslashes($contents); 
	
	$pattern = "'src=[\"|\'](.*?)[\"|\']'si"; 
	preg_match_all($pattern, $contents, $match); 
	return $match[1][0]; 
} 

function encode_2047($subject) {
	return '=?euc-kr?b?'.base64_encode($subject).'?=';
}


function sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto){ //비밀번호찾기 메일

	//ini_set("sendmail_from","admin@goupp.org");
	$admin_email = $EMAIL;
	$admin_name  = iconv("UTF-8","EUC-KR",$NAME);
	$SUBJECT		 = iconv("UTF-8","EUC-KR",$SUBJECT);
	$CONTENT		 = iconv("UTF-8","EUC-KR",$CONTENT);
	//	$mcontent=file_get_contents("idpwcheck_mail.html");
	//	$contents=str_replace("###password###",$CONTENT,$mcontent); 

	$header = "Return-Path:".$admin_email."\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/html; charset=euc-kr\r\n";
	$header .= "X-Mailer: PHP\r\n";
	$header .= "Content-Transfer-Encoding: 8bit\r\n";
	$header .= "From: ".$admin_name."<".$admin_email.">\r\n";
	$header .= "Reply-To: DSR Company<".$admin_email.">\r\n";
	$subject  = $SUBJECT;
	$contents = $CONTENT;

	$message = $contents;
	//$message = base64_encode($contents);
	flush();
	@mail($mailto, $subject, $message, $header, '-f'.$admin_email);
}

function sendMail4QnaAnswer($EMAIL, $NAME, $SUBJECT, $qna_title, $qna_content,$qna_reply, $mailto, $mailtoname){ //비밀번호찾기 메일

	//ini_set("sendmail_from","admin@goupp.org");
	$admin_email = $EMAIL;
	$admin_name  = $NAME;
	$admin_name = encode_2047(iconv("UTF-8","EUC-KR",$admin_name));
	//$mailtoname = iconv("UTF-8","EUC-KR",$mailtoname);

	//$EMAIL, $NAME, $SUBJECT, $inquiry_type, $rs_contents, $reply, $rs_email, $rs_in_name

	$subject = encode_2047(iconv("UTF-8","EUC-KR",$SUBJECT));

	$mcontent=file_get_contents($_SERVER[DOCUMENT_ROOT]."/mail/mail.html");
	$mcontent=str_replace("@@SITE_URL@@","http://".$_SERVER[HTTP_HOST],$mcontent); 
	$mcontent=str_replace("@@CATE_CODE@@",$qna_title,$mcontent); 
	$mcontent=str_replace("@@CONTENTS@@",nl2br($qna_content),$mcontent); 
	$mcontent=str_replace("@@REPLY@@",nl2br($qna_reply),$mcontent); 

	$mcontent = iconv("UTF-8","EUC-KR",$mcontent);

	$header = "Return-Path:".$admin_email."\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/html; charset=euc-kr\r\n";
	$header .= "X-Mailer: PHP\r\n";
	$header .= "Content-Transfer-Encoding: 8bit\r\n";
	$header .= "From: ".$admin_name."<".$admin_email.">\r\n";
	$header .= "Reply-To: ".$admin_name."<".$admin_email.">\r\n";
	//2012-05-17$subject  = "=?EUC-KR?B?".base64_encode($SUBJECT)."?=\n";
	//$contents = $CONTENT;

	$message = $mcontent;
	//$message = base64_encode($contents);

	flush();
	@mail($mailto, $subject, $message, $header, '-f'.$admin_email);
}

Function PageList($URL,$nPage,$TPage,$PBlock,$Ext) {

	$str = "";

	if ($TPage > 1) {

		$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$EPage = $SPage + $PBlock - 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}

		if ($nPage > 1) {
			$str = "<a href='".$URL."?nPage=".($nPage - 1).$Ext."'>PREV</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
		} else {
			$str = "PREV&nbsp;&nbsp;|&nbsp;&nbsp;";
		}

				
		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
					$str = $str . "<b>" . $Cnt . "</b>&nbsp;&nbsp;|&nbsp;&nbsp;";
			} else {
					$str = $str . "<a href='".$URL."?nPage=".$Cnt.$Ext."'>" . $Cnt . "</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			}
		}

		if ($nPage <> (int)($TPage)) {
			$str = $str . "<a href='".$URL."?nPage=".($nPage + 1).$Ext."'>NEXT</a>";
		} else {
			$str = $str . "NEXT";
		}
	
	} else {

		$str = "<b>1</b>";
	}

	return $str;
}


if(!function_exists("date_diff")){
	function date_diff($date1, $date2) 
	{ 
		list($startYear, $startMonth, $startDay) = explode("-", substr($date1, 0, 10)); 
		list($endYear, $endMonth, $endDay) = explode("-", substr($date2, 0, 10)); 

		if($startYear < 1970 || $startMonth < 1 || $startMonth > 12){ 
			echo "<script type='text/javascript' language='javascript'>alert('올바른 날짜형식이 아닙니다.');</script>"; 
			return false; 
		} 
		if($endYear < 1970 || $endMonth < 1 || $endMonth > 12){ 
			echo "<script type='text/javascript' language='javascript'>alert('올바른 날짜형식이 아닙니다.');</script>"; 
			return false; 
		} 

		$startTimestamp = mktime(0, 0, 0, $startMonth, $startDay, $startYear); 
		$endTimestamp = mktime(0, 0, 0, $endMonth, $endDay, $endYear); 
			 
		if($startTimestamp != $endTimestamp){ 
			$diffTimestamp = ($startTimestamp > $endTimestamp ? ($startTimestamp-$endTimestamp) : ($endTimestamp-$startTimestamp)); 
			return $diffDate = round($diffTimestamp / 86400);        // 하루는 60*60*24 초 
		} 
		else 
			return $diffDate = 0; 
	}
}

Function PageListAsForm($script_name,$nPage,$TPage,$PBlock, $nPageSize) {

	$str = "";

	if ($TPage > 1) {

		$SPage = ((int)(($nPage - 1) / $PBlock)) * $PBlock + 1;
		$EPage = $SPage + $PBlock - 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}

		if ($nPage > 1) {
			$str = "<a href='javascript:". $script_name ."(". ($nPage - 1) . ");'>PREV</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
		} else {
			$str = "PREV&nbsp;&nbsp;|&nbsp;&nbsp;";
		}

				
		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
					$str = $str . "<b>" . $Cnt . "</b>&nbsp;&nbsp;|&nbsp;&nbsp;";
			} else {
					$str = $str . "<a href='javascript:". $script_name . "(". $Cnt . ");'>" . $Cnt . "</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			}
		}

		if ($nPage <> (int)($TPage)) {
			$str = $str . "<a href='javascript:" . $script_name . "(". ($nPage + 1) . ");'>NEXT</a>";
		} else {
			$str = $str . "NEXT";
		}
	
	} else {

		$str = "<b>1</b>";
	}

	return $str;
}


/**  
 * 파일 업로드   
 *  
 * @param array $filearray  // 파일 배열 $_FILES['file']   
 * @param string $targetdir  
 * @param integer $max_size  
 * @param array $allowext  
 * @return boolean (FALSE) or string (uploaded filename)  
 *   
 * 사용법  
 *   
 * upload('파일배열', '업로드 디렉토리', '용량MB단위', '허용확장자');  
 * upload($_FILE['filename'], '/home/userdir/public_html/data/board/', 1, array('gif', 'exe', 'jpeg', 'jpg'));  
 *   
 * 1. 정확한 확장자 처리를 위해서는 파일헤더를 분석해야 합니다.   
 * 2. 정확히 업로드를 위해 저장전 파일의 오류코드를 처리하는것도 필요합니다.   
 */  

function upload($filearray, $targetdir, $max_size/* MByte */, $allowext) {
	
	if ($max_size == "") $max_size = 4;

	if ($max_size == "400KB") {
		$max_size = 407200;
	} else {
		$max_size = $max_size * 1024 * 1024;    // 바이트로 계산한다. 1MB = 1024KB = 1048576Byte
	}
	
	//echo $targetdir;
	if (!file_exists($targetdir)) { 
		//echo "targetdir --> ".$targetdir."<br>";
		mkdir($targetdir, 0777);
		//exec("mkdir -p ".$targetdir);                # 디렉토리 만들기
	}
	
	if($filearray['size'] > $max_size){
		?>	
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<SCRIPT LANGUAGE="JavaScript">
				<!--
					alert('<?=$filearray['size']?>등록가능 용량초과 입니다.<?=$max_size?>');
					history.back();
				//-->
				</SCRIPT>
				<?
					die;
	}else {
		//try {   // 특별한 경우를 위해 예외처리    
			/**  
			 * 이부분은 파일명의 마지막 부분을 리턴시킵니다.   
			 *   
			 * 예를 들어 test.jpeg 이면 jpeg를 가져옵니다.  
			 */  
			
			$array_fileinfo = explode('.', $filearray['name']);
			$file_ext = end($array_fileinfo);   

			$file_real_name = str_replace(".".$file_ext,"",$filearray['name']);
			/** 함수 end  
			 * end 는 배열의 마지막 원소를 가리키게 한 후 마지막 원소를 리턴 시킵니다.   
			 * array('a', 'b', 'c' , 'd') 이면 'd'를 리턴시킵니다.  
			 */  
			   
			/** 함수 explode   
			 * explode 는 정해진 문자열 위 코드에서는 "." 를 이용해서 배열로 나눕니다.   
			 * 문자열 a.b.c.d 는 explode 를 거친후에는  
			 * array('a', 'b', 'c','d') 가 됩니다.   
			 */  
			   
			/**  
			 * in_array 는 배열에 해당 값이 있는지 찾습니다.   
			 * array(찾을원소, 배열)   
			 * 찾으면 true 못찾으면 false를 출력합니다.  
			 */  
		
			if (in_array(strtolower($file_ext), $allowext)) { // 확장자를 검사한다.   
			
				//공백 _ 로 대치 
				$temp_file_name = str_replace(" ","_",$filearray['name']);

				//한글 파일명 처리를 위해 임시 파일명을 날짜로 만듦
				$fn_rand = mt_rand (0, (strlen ($temp_file_name)));
				$writeday = date("YmdHis",strtotime("0 day"));
				$temp_file_name = $writeday."_".$fn_rand.".".$file_ext;

				$file_name = get_filename_check($targetdir, $temp_file_name);

				//$file_name = $file_real_name."-".mktime() . '.' . $file_ext;    
				// 중복된 파일이 업로드 될수 있으므로 time함수를 이용해 unixtime으로 파일이름을 만들어주고   
				// 그 후 파일 확장자를 붙여줍니다. 정확히는 이 방식으로는 파일업로드를 정확히 중복을 체크했다고 할수 없습니다.   
				
				$path = $targetdir . '/' . $file_name;   
				// 파일 저장 경로를 만들어 줍니다. 함수 실행시에 입력받은 경로를 이용해서 만들어 줍니다.    

				if(move_uploaded_file($filearray['tmp_name'], $path))
				{   
					// 정상적으로 업로드 했다면 업로드된 파일명을 내보냅니다   
					// 이부분에 DB에 저장 구문을 넣어주시거나 파일명을 저장하는 부분을 넣어주시면 됩니다.    
					// 또는 리턴된 파일명으로 처리 하시면 됩니다.    
					return $file_name;
				}
				else return false;   
				// 실패 했을 경우에는 false를 출력합니다.   
			
			}
			else{ //return false;

				if($file_ext!=""){
				?>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<SCRIPT LANGUAGE="JavaScript">
				<!--
					alert('등록할수 없는 확장자 입니다.');
					history.back();
				//-->
				</SCRIPT>
				<?
					die;
				}
			}
		}
	
		//catch (Exception $e) {
		//	throw new Exception('파일 업로드에 실패 하였습니다.');
		//}
	//}
}


function multiupload($filearray, $cnt, $targetdir, $max_size/* MByte */, $allowext) {

	if ($max_size == "") $max_size = 4;

	$max_size = $max_size * 1024 * 1024;    // 바이트로 계산한다. 1MB = 1024KB = 1048576Byte
	
	//echo "multiupload 01<br>";

	if (!file_exists($targetdir)) { 
		echo "targetdir --> ".$targetdir."<br>";
		mkdir($targetdir, 0707);
		chmod($targetdir, 0707);
		//exec("mkdir -p ".$targetdir);                # 디렉토리 만들기
	}

	//echo "multiupload 02<br>";

	if($filearray['size'][$cnt] > $max_size){

		alert('등록가능 용량을 초과하여 파일 업로드를 취소합니다.');
		return false;

	} else {

		//echo "multiupload 03<br>";

		//try {   // 특별한 경우를 위해 예외처리    
			/**  
			 * 이부분은 파일명의 마지막 부분을 리턴시킵니다.   
			 *   
			 * 예를 들어 test.jpeg 이면 jpeg를 가져옵니다.  
			 */  

 		  //$file_ext = end(explode('.', $filearray['name'][$cnt]));   //php 버전 업에 따라 아래 2줄로 변경
			$array_fileinfo = explode('.', $filearray['name'][$cnt]);
			$file_ext = end($array_fileinfo);   


			//echo "multiupload 04 : ".$file_ext."<br>";

			$file_real_name = str_replace(".".$file_ext,"",$filearray['name'][$cnt]);
			/** 함수 end  
			 * end 는 배열의 마지막 원소를 가리키게 한 후 마지막 원소를 리턴 시킵니다.   
			 * array('a', 'b', 'c' , 'd') 이면 'd'를 리턴시킵니다.  
			 */  
			   
			/** 함수 explode   
			 * explode 는 정해진 문자열 위 코드에서는 "." 를 이용해서 배열로 나눕니다.   
			 * 문자열 a.b.c.d 는 explode 를 거친후에는  
			 * array('a', 'b', 'c','d') 가 됩니다.   
			 */  
			   
			/**  
			 * in_array 는 배열에 해당 값이 있는지 찾습니다.   
			 * array(찾을원소, 배열)   
			 * 찾으면 true 못찾으면 false를 출력합니다.  
			 */  

			if(in_array(strtolower($file_ext), $allowext)) { // 확장자를 검사한다.   
			
				//공백 _ 로 대치 
				$temp_file_name = str_replace(" ","_",$filearray['name'][$cnt]);

				//한글 파일명 처리를 위해 임시 파일명을 날짜로 만듦
				$fn_rand = mt_rand (0, (strlen ($temp_file_name)));
				$writeday = date("YmdHis",strtotime("0 day"));
				$temp_file_name = $writeday."_".$fn_rand.".".$file_ext;

				$file_name = get_filename_check($targetdir, $temp_file_name);

				//$file_name = $file_real_name."-".mktime() . '.' . $file_ext;    
				// 중복된 파일이 업로드 될수 있으므로 time함수를 이용해 unixtime으로 파일이름을 만들어주고   
				// 그 후 파일 확장자를 붙여줍니다. 정확히는 이 방식으로는 파일업로드를 정확히 중복을 체크했다고 할수 없습니다.   

				$path = $targetdir . '/' . $file_name;

				//echo "multiupload 05 : ".$path."<br>";
				//echo "multiupload 06 : ".$filearray['tmp_name'][$cnt]."<br>";
				
				// 파일 저장 경로를 만들어 줍니다. 함수 실행시에 입력받은 경로를 이용해서 만들어 줍니다.    
		
				if(move_uploaded_file($filearray['tmp_name'][$cnt], $path))
				{   
					// 정상적으로 업로드 했다면 업로드된 파일명을 내보냅니다   
					// 이부분에 DB에 저장 구문을 넣어주시거나 파일명을 저장하는 부분을 넣어주시면 됩니다.    
					// 또는 리턴된 파일명으로 처리 하시면 됩니다.    
					return $file_name;
				}
				else return false;   
				
				// 실패 했을 경우에는 false를 출력합니다.

			}else{
				//return false;

				if($file_ext!=""){
				?>
				<meta charset="euc-kr">
				<SCRIPT LANGUAGE="JavaScript">
				<!--
					alert('등록할수 없는 파일 확장자이므로 파일 업로드를 취소합니다.');
				//	history.back(-1);
				//-->
				</SCRIPT>
				<?
					return false;
				//	die;
				}
			}
		}
	
		//catch (Exception $e) {
		//	throw new Exception('파일 업로드에 실패 하였습니다.');
		//}
	//}
}


//파일 중복 체크
function get_filename_check($filepath, $filename) { 

	if (!preg_match("'/$'", $filepath)) $filepath .= '/'; 
	if (is_file($filepath . $filename)) { 

		preg_match("'^([^.]+)(\[([0-9]+)\])(\.[^.]+)$'", $filename, $match); 

		if (empty($match)) { 

			$filename = preg_replace("`^([^.]+)(\.[^.]+)$`", "\\1[1]\\2", $filename); 
		} 
		else{ 

			$filename = $match[1] . '[' . ($match[3] + 1) . ']' . $match[4]; 
		} 

		return get_filename_check($filepath, $filename); 
	} 
	else { 

		return $filename; 
	} 
} 

// 페이지 표시
function Image_PageList__ ($URL, $nPage, $TPage, $PBlock, $Ext) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
	
		$str = "<div class=\"paging\">\n";
		
		//echo $intTemp;

		# 이전 블록
		if ($intTemp == 1) {
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=1&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 처음\"></a></span>\n";
			$str .= "<span class=\"arr\"><a href=\"#\"><img src=\"../images/admin/pag_first.gif\" alt=\"이전".$PBlock."개\"></a></span>\n";
		} else {
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=1&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 처음\"></a></span>\n";
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=".($intTemp - $PBlock)."&".$Ext.">";
			$str .= "<img src=\"../images/admin/pag_first.gif\" alt=\"이전".$PBlock."개\">";
			$str .= "</a></span>\n";
		}
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<span class=\"arr\"><a href=\"#\"><img src=\"../images/admin/pag_prev.gif\" alt=\"이전으로\" /></a></span>\n";
		} else {
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=".($nPage - 1)."&".$Ext.">";
			$str .= "<img src=\"../images/admin/pag_prev.gif\" alt=\"이전으로\" />";
			$str .= "</a></span> ";
		}
		

		# 페이지

		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<span><a href=\"".$URL."?nPage=".$Cnt."&".$Ext."\" class=\"selected\">" .$Cnt. "</a></span>\n";
			} else {
				$str .= "<span><a href=\"".$URL."?nPage=".$Cnt."&".$Ext."\" >" .$Cnt. "</a></span>\n";
			}
			$intTemp++;
		}
	
		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<span class=\"arr\"><a href=\"#\"><img src=\"../images/admin/pag_next.gif\" alt=\"다음으로\" /></a></span>\n";
		} else {
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=" .($nPage + 1). "&".$Ext.">";
			$str .= "<img src=\"../images/admin/pag_next.gif\" alt=\"다음으로\" />";
			$str .= "</a></span>\n";
		}
		
		# 다음 블록
		if ($intTemp > $TPage) {
			$str .= "<span class=\"arr\"><a href=\"#\"><img src=\"../images/admin/pag_final.gif\" alt=\"다음".$PBlock."개\"></a></span>\n";
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=".$TPage."&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 마지막\"></a></span>\n";
		} else {
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=" .$intTemp. "&".$Ext.">";
			$str .= "<img src=\"../images/admin/pag_final.gif\" alt=\"다음".$PBlock."개\">";
			$str .= "</a></span>\n";
			$str .= "<span class=\"arr\"><a href=".$URL."?nPage=".$TPage."&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 마지막\"></a></span>\n";
		}
		
		$str .= "</div>";
		
		
	}
	return $str;
}

// 페이지 표시


/*
							<div class="board-page">
								<button type="button" class="btn-page-first" disabled><span class="blind">처음 페이지</span></button>
								<button type="button" class="btn-page-prev" disabled><span class="blind">이전 페이지</span></button>
								<!-- Case 현재페이지: is-current 클래스,  aria-current="page" 추가 -->
								<button type="button" class="btn-page-number is-current" aria-current="page">1<span class="blind">&nbsp;페이지</span></button>
								<button type="button" class="btn-page-number">2<span class="blind">&nbsp;페이지</span></button>
								<button type="button" class="btn-page-number">3<span class="blind">&nbsp;페이지</span></button>
								<button type="button" class="btn-page-number">4<span class="blind">&nbsp;페이지</span></button>
								<button type="button" class="btn-page-number">5<span class="blind">&nbsp;페이지</span></button>
								<button type="button" class="btn-page-next"><span class="blind">다음 페이지</span></button>
								<button type="button" class="btn-page-last"><span class="blind">마지막 페이지</span></button>
							</div>
*/



function Front_Image_PageList ($URL, $nPage, $TPage, $PBlock, $Ext) {

	$str = "<div class='board-page'>\n";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
		
		# 이전 블록
		
		if ($intTemp == 1) {
			$str .= "<button type='button' class='btn-page-first' disabled><span class='blind'>이전".$PBlock."개</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-first' onClick='location=\"".$URL."?nPage=".($intTemp - $PBlock)."&".$Ext."\"'><span class='blind'>이전".$PBlock."개'></span></button>\n";
		}
		
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<button type='button' class='btn-page-prev' disabled><span class='blind'>이전 페이지</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-prev' onClick='location=\"".$URL."?nPage=".($nPage - 1)."&".$Ext."\"'><span class='blind'>이전 페이지</span></button>\n";
		}
		
		# 페이지

		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<button type='button' class='btn-page-number is-current' aria-current='page' onClick='return false;'>".$Cnt."<span class='blind'>&nbsp;페이지</span></button>\n";
			} else {
				$str .= "<button type='button' class='btn-page-number' onClick='location=\"".$URL."?nPage=".$Cnt."&".$Ext."\"' >".$Cnt."<span class='blind'>&nbsp;페이지</span></button>\n";
			}
			$intTemp++;
		}

		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<button type='button' class='btn-page-next' disabled onClick='return false;'><span class='blind'>다음 페이지</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-next' onClick='location=\"".$URL."?nPage=".($nPage + 1)."&".$Ext."\"' ><span class='blind'>다음 페이지</span></button>\n";
		}
		
		# 다음 블록

		if ($intTemp > $TPage) {
			$str .= "<button type='button' class='btn-page-last' disabled><span class='blind'>다음".$PBlock."개</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-last' onClick='location=\"".$URL."?nPage=".$intTemp."&".$Ext."\"'><span class='blind'>다음".$PBlock."개</span></button>";
		}

		
	} else {

		$str .= "<button type='button' class='btn-page-first' disabled><span class='blind'>처음 페이지</span></button>";
		$str .= "<button type='button' class='btn-page-prev' disabled><span class='blind'>이전 페이지</span></button>";
		$str .= "<button type='button' class='btn-page-number is-current' aria-current='page'>1<span class='blind'>&nbsp;페이지</span></button>";
		$str .= "<button type='button' class='btn-page-next' disabled><span class='blind'>다음 페이지</span></button>";
		$str .= "<button type='button' class='btn-page-last' disabled><span class='blind'>마지막 페이지</span></button>";
	}

	$str .= "</div>";

	return $str;
}

function Front_Image_PageList_contact_list ($URL, $nPage, $TPage, $PBlock, $Ext) { // 소통마당 / 문의사항 전용 

	$str = "<div class='board-page-wrap'><div class='board-page'>\n";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
		
		# 이전 블록
		
		if ($intTemp == 1) {
			$str .= "<button type='button' class='btn-page-first' disabled><span class='blind'>이전".$PBlock."개</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-first' onClick='location=\"".$URL."?nPage=".($intTemp - $PBlock)."&".$Ext."\"'><span class='blind'>이전".$PBlock."개'></span></button>\n";
		}
		
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<button type='button' class='btn-page-prev' disabled><span class='blind'>이전 페이지</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-prev' onClick='location=\"".$URL."?nPage=".($nPage - 1)."&".$Ext."\"'><span class='blind'>이전 페이지</span></button>\n";
		}
		
		# 페이지

		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<button type='button' class='btn-page-number is-current' aria-current='page' onClick='return false;'>".$Cnt."<span class='blind'>&nbsp;페이지</span></button>\n";
			} else {
				$str .= "<button type='button' class='btn-page-number' onClick='location=\"".$URL."?nPage=".$Cnt."&".$Ext."\"' >".$Cnt."<span class='blind'>&nbsp;페이지</span></button>\n";
			}
			$intTemp++;
		}

		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<button type='button' class='btn-page-next' disabled onClick='return false;'><span class='blind'>다음 페이지</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-next' onClick='location=\"".$URL."?nPage=".($nPage + 1)."&".$Ext."\"' ><span class='blind'>다음 페이지</span></button>\n";
		}
		
		# 다음 블록

		if ($intTemp > $TPage) {
			$str .= "<button type='button' class='btn-page-last' disabled><span class='blind'>다음".$PBlock."개</span></button>\n";
		} else {
			$str .= "<button type='button' class='btn-page-last' onClick='location=\"".$URL."?nPage=".$intTemp."&".$Ext."\"'><span class='blind'>다음".$PBlock."개</span></button>";
		}

		
	} else {

		$str .= "<button type='button' class='btn-page-first' disabled><span class='blind'>처음 페이지</span></button>";
		$str .= "<button type='button' class='btn-page-prev' disabled><span class='blind'>이전 페이지</span></button>";
		$str .= "<button type='button' class='btn-page-number is-current' aria-current='page'>1<span class='blind'>&nbsp;페이지</span></button>";
		$str .= "<button type='button' class='btn-page-next' disabled><span class='blind'>다음 페이지</span></button>";
		$str .= "<button type='button' class='btn-page-last' disabled><span class='blind'>마지막 페이지</span></button>";
	}

	$str .= "</div>
				<div class='board-btn'>
					<button type='button' class='btn-basic h-48 primary' onclick='js_desk();'>
						<span>문의하기</span>
					</button>
				</div>
			</div>";

	return $str;
}


function Front_Popup_Image_PageList ($jsName, $nPage, $TPage, $PBlock) {

	$str = "<div class='paging'>";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}

		# 이전 페이지
		if ($nPage == 1) {
			//$str .= "<button type='button' class='btn-paging-prev' title='이전'>이전</button>\n";
			$str .= "<button type='button' class='btn-prev' title='이전'>이전</button>\n";
		} else {
			$str .= "<button type='button' class='btn-prev' onClick=\"".$jsName."('".($nPage - 1)."')\" >이전</button>\n";
		}
		
		# 페이지
		$str .= "<span>";

		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<a href='javascript:void(0)' class='on'>" .$Cnt. "</a>\n";
			} else {
				$str .= "<a href=\"javascript:".$jsName."('".$Cnt."')\">" .$Cnt. "</a>\n";
			}
			$intTemp++;
		}

		$str .= "</span>";
		
		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<button type='button' class='btn-next' title='다음'>다음</button>\n";
		} else {
			$str .= "<button type='button' class='btn-next' onClick=\"".$jsName."('".($nPage + 1)."')\" >다음</button>\n";
		}
		
		$str .= "</div>";
		
	} else {

		$str .= "<div class='paging'>\n";
		$str .= "<button type='button' class='btn-prev' title='이전'>이전</button>\n";
		$str .= "<span>\n";
		$str .= "<a href='javascript:void(0)' class='on'>1</a>\n";
		$str .= "</span>\n";
		$str .= "<button type='button' class='btn-next' title='다음'>다음</button>\n";
		$str .= "</div>\n";
	}

	$str .= "</div>";

	return $str;
}


function mFront_Image_Board_PageList ($URL, $nPage, $TPage, $PBlock, $Ext) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}

		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<a href=\"javascript:void(0);\"><img src=\"../images/bbs/arrow_back.gif\" /></a>\n";
		} else {
			$str .= "<a href=".$URL."?nPage=".($nPage - 1)."&".$Ext."><img src=\"../images/bbs/arrow_back.gif\" /></a>\n";
		}
		
		# 페이지

		$str .= "<strong>".$nPage."</strong> / ".$TPage."\n";
	
		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<a href=\"javascript:void(0);\" ><img src=\"../images/bbs/arrow_next.gif\" /></a>\n";
		} else {
			$str .= "<a href=".$URL."?nPage=".($nPage + 1)."&".$Ext."><img src=\"../images/bbs/arrow_next.gif\" /></a>\n";
		}
	}
	return $str;
}

function mFront_Image_Goods_PageList ($c, $dp, $nPage, $TPage, $PBlock) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
	
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<a href=\"javascript:void(0);\" class=\"fl\"><img src=\"../images/bbs/arrow_back1.png\" /></a>\n";
		} else {
			$str .= "<a href=\"javascript:js_goods_list('".$c."','".$dp."','".($nPage - 1)."');\" class=\"fl\"><img src=\"../images/bbs/arrow_back1.png\" /></a>\n";
		}
		
		# 페이지

		$str .= "<strong>".$nPage."</strong> / ".$TPage."\n";
	
		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<a href=\"javascript:void(0);\" class=\"fr\"><img src=\"../images/bbs/arrow_next1.png\" /></a>\n";
		} else {
			$str .= "<a href=\"javascript:js_goods_list('".$c."','".$dp."','".($nPage + 1)."');\" class=\"fr\"><img src=\"../images/bbs/arrow_next1.png\" /></a>\n";
		}
	}
	return $str;
}

function Front_Image_PageList_Sub ($l, $ct, $c, $nPage, $TPage, $PBlock) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
	
		$str .= "<ul>\n";
		
		# 이전 블록
		if ($intTemp == 1) {
			//$str .= "<li><a href=".$URL."?nPage=1&".$Ext."><img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\"></a></li>\n";
			$str .= "<li><img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\" /></li>\n";
		} else {
			//$str .= "<span class=\"arr\"><a href=".$URL."?nPage=1&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 처음\"></a></span>\n";
			$str .= "<li><a href=\"javascript:js_go_subpage('".$l."','".$ct."','".$c."','".($intTemp - $PBlock)."')\" >";
			$str .= "<img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\">";
			$str .= "</a></li>\n";
		}
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<li class='prev'><img src=\"../images/bbs/prev01.gif\" alt=\"이전으로\" /></li>\n";
		} else {
			$str .= "<li class='prev'><a href=\"javascript:js_go_subpage('".$l."','".$ct."','".$c."','".($nPage - 1)."')\" >";
			$str .= "<img src=\"../images/bbs/prev01.gif\" alt=\"이전으로\" />";
			$str .= "</a></li> ";
		}
		
		# 페이지

		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<li><strong class=\"sel\">" .$Cnt. "</strong></li>\n";
			} else {
				$str .= "<li><a href=\"javascript:js_go_subpage('".$l."','".$ct."','".$c."','".$Cnt."')\" >" .$Cnt. "</a></li>\n";
			}
			$intTemp++;
		}
	
		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<li class='next'><img src=\"../images/bbs/next01.gif\" alt=\"다음으로\" /></a></li>\n";
		} else {
			$str .= "<li class='next'><a href=\"javascript:js_go_subpage('".$l."','".$ct."','".$c."','".($nPage + 1)."')\" >";
			$str .= "<img src=\"../images/bbs/next01.gif\" alt=\"다음으로\" />";
			$str .= "</a></li>\n";
		}
		
		# 다음 블록
		if ($intTemp > $TPage) {
			$str .= "<li><img src=\"../images/bbs/next02.gif\" alt=\"다음".$PBlock."개\"></li>\n";
			//$str .= "<li><a href=".$URL."?nPage=".$TPage."&".$Ext."><img src=\"../images/bbs/next01.gif\" alt=\"맨 마지막\"></a></li>\n";
		} else {
			$str .= "<li><a href=\"javascript:js_go_subpage('".$l."','".$ct."','".$c."','".$intTemp."')\">";
			$str .= "<img src=\"../images/bbs/next02.gif\" alt=\"다음".$PBlock."개\">";
			$str .= "</a></li>\n";
			//$str .= "<li><a href=".$URL."?nPage=".$TPage."&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 마지막\"></a></li>\n";
		}
		
		$str .= "</ul>";

	} else {

		$str .= "<ul>\n";
		$str .= "<li><img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\" /></li>\n";
		$str .= "<li class='prev'><img src=\"../images/bbs/prev01.gif\" alt=\"이전으로\" /></li>\n";
		$str .= "<li><strong class=\"sel\">1</strong></li>\n";
		$str .= "<li class='next'><img src=\"../images/bbs/next01.gif\" alt=\"다음".$PBlock."개\"></li>\n";
		$str .= "<li><img src=\"../images/bbs/next02.gif\" alt=\"다음".$PBlock."개\"></li>\n";
		$str .= "</ul>";
	}

	return $str;
}

function Front_Image_PageList_Sub2 ($l, $ct, $c, $nPage, $TPage, $PBlock) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
	
		$str .= "<ul>\n";
		
		# 이전 블록
		if ($intTemp == 1) {
			//$str .= "<li><a href=".$URL."?nPage=1&".$Ext."><img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\"></a></li>\n";
			$str .= "<li><img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\" /></li>\n";
		} else {
			//$str .= "<span class=\"arr\"><a href=".$URL."?nPage=1&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 처음\"></a></span>\n";
			$str .= "<li><a href=\"javascript:js_go_appsubpage('".$l."','".$ct."','".$c."','".($intTemp - $PBlock)."')\" >";
			$str .= "<img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\">";
			$str .= "</a></li>\n";
		}
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<li class='prev'><img src=\"../images/bbs/prev01.gif\" alt=\"이전으로\" /></li>\n";
		} else {
			$str .= "<li class='prev'><a href=\"javascript:js_go_appsubpage('".$l."','".$ct."','".$c."','".($nPage - 1)."')\" >";
			$str .= "<img src=\"../images/bbs/prev01.gif\" alt=\"이전으로\" />";
			$str .= "</a></li> ";
		}
		
		# 페이지

		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<li><strong class=\"sel\">" .$Cnt. "</strong></li>\n";
			} else {
				$str .= "<li><a href=\"javascript:js_go_appsubpage('".$l."','".$ct."','".$c."','".$Cnt."')\" >" .$Cnt. "</a></li>\n";
			}
			$intTemp++;
		}
	
		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<li class='next'><img src=\"../images/bbs/next01.gif\" alt=\"다음으로\" /></a></li>\n";
		} else {
			$str .= "<li class='next'><a href=\"javascript:js_go_appsubpage('".$l."','".$ct."','".$c."','".($nPage + 1)."')\" >";
			$str .= "<img src=\"../images/bbs/next01.gif\" alt=\"다음으로\" />";
			$str .= "</a></li>\n";
		}
		
		# 다음 블록
		if ($intTemp > $TPage) {
			$str .= "<li><img src=\"../images/bbs/next02.gif\" alt=\"다음".$PBlock."개\"></li>\n";
			//$str .= "<li><a href=".$URL."?nPage=".$TPage."&".$Ext."><img src=\"../images/bbs/next01.gif\" alt=\"맨 마지막\"></a></li>\n";
		} else {
			$str .= "<li><a href=\"javascript:js_go_appsubpage('".$l."','".$ct."','".$c."','".$intTemp."')\">";
			$str .= "<img src=\"../images/bbs/next02.gif\" alt=\"다음".$PBlock."개\">";
			$str .= "</a></li>\n";
			//$str .= "<li><a href=".$URL."?nPage=".$TPage."&".$Ext."><img src=\"../images/admin/pag_first_bu.gif\" alt=\"맨 마지막\"></a></li>\n";
		}
		
		$str .= "</ul>";

	} else {

		$str .= "<ul>\n";
		$str .= "<li><img src=\"../images/bbs/prev02.gif\" alt=\"이전".$PBlock."개\" /></li>\n";
		$str .= "<li class='prev'><img src=\"../images/bbs/prev01.gif\" alt=\"이전으로\" /></li>\n";
		$str .= "<li><strong class=\"sel\">1</strong></li>\n";
		$str .= "<li class='next'><img src=\"../images/bbs/next01.gif\" alt=\"다음".$PBlock."개\"></li>\n";
		$str .= "<li><img src=\"../images/bbs/next02.gif\" alt=\"다음".$PBlock."개\"></li>\n";
		$str .= "</ul>";
	}

	return $str;
}

function Mobile_Front_Image_PageList ($URL, $nPage, $TPage, $PBlock, $Ext) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;

	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}
	
		# 이전 블록
		if ($intTemp == 1) {
			$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_first.png' title='이전".$PBlock."개'></a>\n";
		} else {
			$str .= "<a href=".$URL."?nPage=".($intTemp - $PBlock)."&".$Ext." class=''><img src='../images/btn_first.png' title='이전".$PBlock."개'></a>\n";
		}
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_prev.png' title='이전페이지'></a>\n";
		} else {
			$str .= "<a href=".$URL."?nPage=".($nPage - 1)."&".$Ext."><img src='../images/btn_prev.png' title='이전페이지'></a>";
		}
		
		# 페이지

		//$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			$intTemp++;
		}

		$str .= "<em>".$nPage." / ".$TPage."</em>";

		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_next.png' title='다음페이지'></a>\n";
		} else {
			$str .= "<a href=".$URL."?nPage=" .($nPage + 1). "&".$Ext."  class='' ><img src='../images/btn_next.png' title='다음페이지'></a>";
		}
		
		# 다음 블록
		if ($intTemp > $TPage) {
			$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_end.png' title='다음".$PBlock."개'></a>\n";
		} else {
			$str .= "<a href=".$URL."?nPage=" .$intTemp. "&".$Ext." class=''><img src='../images/btn_end.png' title=\"다음".$PBlock."개\">";
		}
		
	} else {

		$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_first.png' title=\"이전".$PBlock."개\"></a>\n";
		$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_prev.png' title='이전페이지'></a>\n";
		$str .= "<em>1 / 1</em>\n";
		$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_next.png' title='다음페이지'></a>\n";
		$str .= "<a href='javascript:void(0)' class=''><img src='../images/btn_end.png' title=\"다음".$PBlock."개\"></a>\n";
	}
	return $str;

}



//문자열 자르기
function TextCut($str,$start,$len,$suffix = "...") {
	$lenth=$len - $start;   
	
	if (strlen($str)>$lenth) {  //만일 자르게 된다면 표시 
		$ok=1;
	}
 
	$str = trim($str); 
	$backcnt= 0; // 시작첫글자에서 뒤로간 byte 수 (space나 영/숫자가 나올때 까지) 
	$cntcheck =0; 
	
	if ($start>0 ) { 
		if(ord($str[$start]) >= 128) { // 첫 시작글자가 한글이면 
			for ($i=$start;$i>0;$i--) { 
				if (ord($str[$i]) >= 128) { 
					$backcnt++; 
				} else { 
					break; 
				} 
			}
			
			$start= ($backcnt%2) ? $start : $start-1; //첫글짜가 깨지면, 시작점 = (시작 byte -1byte) 

			if (($backcnt%2)==1) { 
				$cntcheck = 0; //문장 시작 첫글자 안짤림 
			} else { 
				$cntcheck = 1; //문장 시작 첫글자 짤림 
			} 

		}
	}

	$backcnt2= 0; // 마지막글자에서 뒤로간 byte 수 (space나 영/숫자가 나올때 까지) 
	
	for ($i=($len-1);$i>=0;$i--) { 
		if (ord($str[$i+$start]) >= 128) { 
			$backcnt2++; 
		} else { 
			break; 
		} 
	} 

	if (($backcnt2%2)==1) { 
		$cntcheck2 = 1; //문장 마지막 글자 짤림 
	} else { 
		$cntcheck2 = 0; //문장 마지막 글자 안짤림 
	} 

	(int)$cnt=$len-abs($backcnt2%2); //자를 문자열 길이 (byte) 
	if(($cntcheck+$cntcheck2)==2) $cnt+=2; //$cntcheck가 짤리고, $cntcheck2가 짤리는 경우 
	$cutstr = substr($str,$start,$cnt); 
	if ($ok){$cutstr .= $suffix;}  ///잘랐을 경우에만 끝에 ... 붙임 
	return $cutstr; 
}


// DB에 입력 하기
function SetStringToDB($str) {

	$temp_str = "";
	
	if ($str) { 
		$temp_str = trim($str);
		$temp_str = str_replace('##쌍따움표##', '"', $temp_str);
		$temp_str = str_replace("##따움표##", "'", $temp_str);
		$temp_str = addslashes($temp_str);
	}

	return $temp_str; 
}

// DB에서 빼오기
function SetStringFromDB($str) {
	
	$temp_str = "";
	
	if ($str) { 
		$temp_str = trim($str);
		$temp_str = str_replace('##쌍따움표##', '"', $temp_str);
		$temp_str = str_replace("##따움표##", "'", $temp_str);

		$temp_str = stripslashes($temp_str);
		//$temp_str = str_replace("\"","&quot;", $temp_str);
		$temp_str = str_replace("<script","", $temp_str);

	}

	return $temp_str; 
}

// DB에서 빼오기
function getStringFromDB($str) {
	
	$temp_str = "";

	if ($str) { 
	
		$temp_str = trim($str);
		$temp_str = str_replace('##쌍따움표##', '"', $temp_str);
		$temp_str = str_replace("##따움표##", "'", $temp_str);

		$temp_str = stripslashes($temp_str);
		//$temp_str = str_replace("\"","&quot;", $temp_str);
		$temp_str = str_replace("<script","", $temp_str);
	}

	return $temp_str; 
}



// 확장자 구하기 
function file_ext($str) {
	$ext1 = strrev($str);
	$ext2 = explode(".",$ext1);
	return strrev($ext2[0]);

}

function byteConvert($bytes) {
	$s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB'); 
	$e = floor(log($bytes)/log(1024)); 
	return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e)))); 
} 

function cleanQuery($string) {

	//if(get_magic_quotes_gpc()) { // prevents duplicate backslashes
		
		$string = stripslashes($string);
	//}

	if (phpversion() >= '4.3.0') {
		$string = mysql_real_escape_string($string);
	}	else {
		$string = mysql_escape_string($string);
	}
	return $string;
}


function getFileIcon($str) {
	
	$string = "";
	
	$str = strtolower($str); 
	
	//echo $str;

	switch ($str) {

		case 'ai' ;
			$string = "/kor/images/icon/icon_ai.gif";
			break;

		case 'doc' ;
			$string = "/kor/images/icon/icon_doc.gif";
			break;

		case 'docx' ;
			$string = "/kor/images/icon/icon_doc.gif";
			break;

		case 'txt' ;
			$string = "/kor/images/icon/icon_document.gif";
			break;

		case 'exe' ;
			$string = "/kor/images/icon/icon_exe.gif";
			break;

		case 'xls' ;
			$string = "/kor/images/icon/icon_exel.gif";
			break;

		case 'xlsx' ;
			$string = "/kor/images/icon/icon_exel.gif";
			break;

		case 'fla' ;
			$string = "/kor/images/icon/icon_fla.gif";
			break;

		case 'gif' ;
			$string = "/kor/images/icon/icon_gif.gif";
			break;

		case 'zip'; 'rar' ; 'gz' ; 'tgz' ;
			$string = "/kor/images/icon/icon_gz.gif";
			break;

		case 'htm' ; 'html' ;
			$string = "/kor/images/icon/icon_htm.gif";
			break;

		case 'hwp' ;
			$string = "/kor/images/icon/icon_hwp.gif";
			break;

		case 'jpg' ;
			$string = "/kor/images/icon/icon_jpg.gif";
			break;

		case 'mp3' ;
			$string = "/kor/images/icon/icon_mp3.gif";
			break;

		case 'pdf' ;
			$string = "/kor/images/icon/icon_pdf.gif";
			break;

		case 'ppt' ; 'pptx' ;
			$string = "/kor/images/icon/icon_ppt.gif";
			break;

		case 'wmv' ;
			$string = "/kor/images/icon/icon_wm.gif";
			break;

		case 'qm' ;
			$string = "/kor/images/icon/icon_qm.gif";
			break;

		default ; 
			$string = "/kor/images/icon/icon_disk.gif";
			break;
		
	}
	
	if ($str == "") $string = "/kor/images/icon/blank.gif";

	return $string;

}

function go($str) {
	
	$string = "";
	
	$string .= "<html>";
	$string .= "<script type='text/javascript'>";
	$string .= "document.frm.submit();";
	$string .= "</script>";
	$string .= "<body onload='init();'>";
	$string .= "<form name='frm' action='".$str."' target='_parent'>";
	$string .= "</form>";
	$string .= "</body>";
	$string .= "</html>";

	return $string;
}

function chkDate($str, $format) {

	if ($format == "YYYY-MM-DD") {
		
		$arr_date = explode("-", $str);
		
		$yyyy		= $arr_date[0];
		$mm			= $arr_date[1];
		$dd			= $arr_date[2];

		/*
		$yyyy = left($str, 4);
		$mm = substr($str, 5,2);
		$dd = right($str,2);
		*/

		return checkdate($mm , $dd, $yyyy);
	} else {
		return false;
	}
}

function dateformat($str, $delimiter) {
	
	$ret = "";

	if (strlen($str) == 8) {
		
		$yyyy = left($str, 4);
		$mm = substr($str, 4,2);
		$dd = right($str,2);
		$ret = $yyyy.$delimiter.$mm.$delimiter.$dd;
	} 
	return $ret;
}

function get_text($str, $html=0)
{
    /* 3.22 막음 (HTML 체크 줄바꿈시 출력 오류때문)
    $source[] = "/  /";
    $target[] = " &nbsp;";
    */

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) {
        $str = html_symbol($str);
    }

    $source[] = "/</";
    $target[] = "&lt;";
    $source[] = "/>/";
    $target[] = "&gt;";
    //$source[] = "/\"/";
    //$target[] = "&#034;";
    $source[] = "/\'/";
    $target[] = "&#039;";
    //$source[] = "/}/"; $target[] = "&#125;";
    if ($html) {
        $source[] = "/\n/";
        $target[] = "<br/>";
    }

    return preg_replace($source, $target, $str);
}

function html_symbol($str)
{
	return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

// 세션변수 생성
function set_session($session_name, $value)
{
	session_register($session_name);
	// PHP 버전별 차이를 없애기 위한 방법
	$$session_name = $_SESSION["$session_name"] = $value;
}


// 세션변수값 얻음
function get_session($session_name)
{
	return $_SESSION[$session_name];
}

function right($value, $count){
	$value = substr($value, (strlen($value) - $count), strlen($value));
	return $value;
}

function left($string, $count){
	return substr($string, 0, $count);
}

function makeBoardSelectBox($objName, $str, $val, $strCate, $style, $checkVal) {
	
	$arr_strCate = explode(";",$strCate);

	$tmp_str = "<select name='".$objName."' ".$style." >";

	if ($str <> "") {
		$tmp_str .= "<option value='".$val."'>".$str."</option>";
	}

	for ($i = 0; $i < sizeof($arr_strCate) ; $i++) {

		$tmp_cate = str_replace("^"," & ",$arr_strCate[$i]);

		if ($checkVal == $arr_strCate[$i]) {
			$tmp_str .= "<option value='".$arr_strCate[$i]."' selected>".$tmp_cate."</option>";
		} else {
			$tmp_str .= "<option value='".$arr_strCate[$i]."'>".$tmp_cate."</option>";
		}
	}

	$tmp_str .= "</select>";
	return $tmp_str;

}

function makeBoardSelectBoxOnChange($objName, $str, $val, $strCate, $style, $checkVal) {
	
	$arr_strCate = explode(";",$strCate);

	$tmp_str = "<select name='".$objName."' ".$style." onChange='js_".$objName."();'>";

	if ($str <> "") {
		$tmp_str .= "<option value='".$val."'>".$str."</option>";
	}

	for ($i = 0; $i < sizeof($arr_strCate) ; $i++) {

		$tmp_cate = str_replace("^"," & ",$arr_strCate[$i]);

		if ($checkVal == $arr_strCate[$i]) {
			$tmp_str .= "<option value='".$arr_strCate[$i]."' selected>".$tmp_cate."</option>";
		} else {
			$tmp_str .= "<option value='".$arr_strCate[$i]."'>".$tmp_cate."</option>";
		}
	}

	$tmp_str .= "</select>";
	return $tmp_str;

}

function getSplitPhone($str) {

	$arr_phone = explode("-",$str);

	if (sizeof($arr_phone) == 3) {
		return $arr_phone;
	} else {
		$str = str_replace("-","",$str);
		
		if (left($str,2) == "02") {
			
			$arr_phone[0] = "02";
			
			if (strlen($str) == 9) 
				$arr_phone[1] = substr($str,2,3);
			else
				$arr_phone[1] = substr($str,2,4);
			
			$arr_phone[2] = right($str,4);

		} else {
			
			if (left($str,1) == "0") {
				
				$arr_phone[0] = left($str,3);
				if (strlen($str) == 10) 
					$arr_phone[1] = substr($str,3,3);
				else
					$arr_phone[1] = substr($str,3,4);

				$arr_phone[2] = right($str,4);

			} else {
				$arr_phone[0] = "";
				if (strlen($str) == 7) 
					$arr_phone[1] = substr($str,0,3);
				else
					$arr_phone[1] = substr($str,0,4);
				$arr_phone[2] = right($str,4);
			}
		}
	}
	return $arr_phone;
}

function indexOf($search_str, $str){ 
		
	$i = strlen(stristr($str, $search_str));

	//echo "s".$i;
	return $i;
} 



function FileWriting($u,$f)
{
	//global $_ERROR_LOG_DIR;
	//try
	//{
		//$u = str_replace("PHPSESSID=","PHP=YES&",$u);
		$fp = fopen($f, "w");
		fwrite($fp, $u);
		fclose($fp);
	//	return true;
	//}
	//catch(Exception $e) 
	//	return false;
	//}
	
}

function getDDay($str_date) {

	$days = intval((strtotime($str_date)-strtotime(date("Y-m-d")))/86400);

	$str_days = right("0".$days,2);

	for ($i = 0 ; $i < strlen($str_days) ; $i++) {
		$str_counter = $str_counter."<img src='/kor/images/bu/".substr($str_days, $i,1).".gif' alt='' />";
	}

	return $str_counter;
}


function getPercentValue($total, $val) {
	
	$percent_val = 0;
	
	
	if ($total <> 0) 
		$percent_val = round((($val * 100) / $total));
	
	/*
	if ($total <> 0) 
		$percent_val = floor((($val * 100) / $total));
	*/
	return $percent_val;
}

	function strcut_utf8($str, $len, $checkmb=false, $tail='...') {
		preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);

		$m = $match[0];
		$slen = strlen($str); // length of source string
		$tlen = strlen($tail); // length of tail string
		$mlen = count($m); // length of matched characters

		if ($slen <= $len) return $str;
		if (!$checkmb && $mlen <= $len) return $str;

		$ret = array();
		$count = 0;

		for ($i=0; $i < $len; $i++) {
			$count += ($checkmb && strlen($m[$i]) > 1)?2:1;
			
			if ($count + $tlen > $len) break;
				$ret[] = $m[$i];
		}
		return join('', $ret).$tail;
	}

	function TextCut_euckr($str,$start,$len,$suffix = "..") {
	$lenth=$len - $start;   
	
	if (strlen($str)>$lenth) {  //만일 자르게 된다면 표시 
		$ok=1;
	}
 
	$str = trim($str); 
	$backcnt= 0; // 시작첫글자에서 뒤로간 byte 수 (space나 영/숫자가 나올때 까지) 
	$cntcheck =0; 
	
	if ($start>0 ) { 
		if(ord($str[$start]) >= 128) { // 첫 시작글자가 한글이면 
			for ($i=$start;$i>0;$i--) { 
				if (ord($str[$i]) >= 128) { 
					$backcnt++; 
				} else { 
					break; 
				} 
			}
			
			$start= ($backcnt%2) ? $start : $start-1; //첫글짜가 깨지면, 시작점 = (시작 byte -1byte) 

			if (($backcnt%2)==1) { 
				$cntcheck = 0; //문장 시작 첫글자 안짤림 
			} else { 
				$cntcheck = 1; //문장 시작 첫글자 짤림 
			} 

		}
	}

	$backcnt2= 0; // 마지막글자에서 뒤로간 byte 수 (space나 영/숫자가 나올때 까지) 
	
	for ($i=($len-1);$i>=0;$i--) { 
		if (ord($str[$i+$start]) >= 128) { 
			$backcnt2++; 
		} else { 
			break; 
		} 
	} 

	if (($backcnt2%2)==1) { 
		$cntcheck2 = 1; //문장 마지막 글자 짤림 
	} else { 
		$cntcheck2 = 0; //문장 마지막 글자 안짤림 
	} 

	(int)$cnt=$len-abs($backcnt2%2); //자를 문자열 길이 (byte) 
	if(($cntcheck+$cntcheck2)==2) $cnt+=2; //$cntcheck가 짤리고, $cntcheck2가 짤리는 경우 
	$cutstr = substr($str,$start,$cnt); 
	if ($ok){$cutstr .= $suffix;}  ///잘랐을 경우에만 끝에 ... 붙임 
	return $cutstr; 
}

function u8_strcut($str, $limit, $suffix = "...") 
/* Note: */ 
/* $str must be a valid UTF-8 string */ 
/* it may return an empty string even if $limit > 0 */ 
{ 
	$return_str = "";
	$len= strlen($str); 

	if ($len<= $limit ) 
		return $str; 

	$len= $limit; 

		/* ASCII are encoded in the range 0x00 to 0x7F 
		* The first byte of multibyte sequence is in the range 0xC0 to 0xFD. 
		* All furthur bytes are in the range 0x80 to 0xBF. 
		*/ 

	while ($len > 0 && ($ch = ord($str[$len])) >= 128 && ($ch < 192)) 
		$len --; 
	
	$return_str = substr($str, 0, $len).$suffix;
	return $return_str;
}


/*
오라클과 연동시 입력용으로 사용
오라클과 연동시 출력용 putenv("NLS_LANG=AMERICAN_AMERICA.KO16MSWIN949");
*/
function iconv_to_euc_kr($get_data) {
  $work_unit = 50;
 
  $init_size = strlen($get_data);
 
  $result_data = "";
 
  $count = 0;
 
  for ( $i = 0 ; $i < $init_size ; $i++ ) {
    $cur_char = substr($get_data,$i,1);
 
    $t = ord($cur_char);
    if ( $t == 9 || $t == 10 || (32 <= $t && $t <= 126) ) {
      $tn = 1;
    }
    else if ( 194 <= $t && $t <= 223 ) {
      $tn = 2;
    }
    else if ( 224 <= $t && $t < 239 ) {
      $tn = 3;
    }
    else if ( 240 <= $t && $t <= 247 ) {
      $tn = 4;
    }
    else if ( 248 <= $t && $t <= 251 ) {
      $tn = 5;
    }
    else if ( $t == 252 || $t == 253 ) {
      $tn = 6;
    }
    else {
      $tn = 1;
    }
 
    if ( $work_unit < $tn ) {
      break;
    }
 
    if ( $count + $tn > $work_unit ) {
      $temp_data = iconv("utf-8","euc-kr",$work_string);
      $result_data .= $temp_data;
 
      $work_string = "";
      $i--;
      $count = 0;
    }
    else {
      for ( $j = 0 ; $j < $tn ; $j++ ) {
        $work_string .= $cur_char;
        $i++;
        $count++;
        $cur_char = substr($get_data,$i,1);
      }
      $i--;
    }
  }
 
  if ( $work_string ) {
    $temp_data = iconv("utf-8","euc-kr",$work_string);
    $result_data .= $temp_data;
  }


  return $result_data;
}


	/*
	sample code
	
	$arr_value = getJuminDICode("7003231001010");
	echo $arr_value[0]; // 리턴 flag : 성공 : 1 실패 : 0
	echo $arr_value[1]; // 성공 시 DupInfo 실패시 : 실패 사유
	*/

	function getJuminDICode ($str) {
		
		$values = array();

		$return_flag = "0";
		$return_msg = "";
		$str = str_replace("-","", $str);
		
		if (strlen($str) == 13) {
			$sSiteCode	= "C798";			// IPIN 서비스 사이트 코드		(NICE신용평가정보에서 발급한 사이트코드)
			$sSitePw		= "Grim0550";			// IPIN 서비스 사이트 패스워드	(NICE신용평가정보에서 발급한 사이트패스워드)

			/*
			사용자 정보 (주민등록번호 13자리 or 안심키값 13자리)
			*/
			$sJumin			= $str;
			
			/*
			┌ $sFlag 변수에 대한 설명  ───────────────────────────────────────
				실명확인 서비스 구분값.
				JID : 일반실명확인 서비스 (주민등록번호)
				SID : 안심실명확인 서비스 (안심키값)
				사용중인 실명확인 서비스에 따라서 정의해 주세요.
			└─────────────────────────────────────────────────────────────────
			*/
			$sFlag		= "JID";
			$sModulePath				= "/home/httpd/goupp/NICE_CHECK/KisinfoIPINInterop";
			$ReturnData = `$sModulePath $sSiteCode $sSitePw $sFlag $sJumin`;
			
			// 데이타 구분자는 ^ 이며, 구분자로 데이타를 파싱합니다.
			$arrData = split("\^", $ReturnData);
			$iCount = count($arrData);

			if ($iCount == 3) {
				// 데이타 추출하기
				$sDateTime = $arrData[0];
				$iRtn = $arrData[1];
				$sDupInfo = $arrData[2];
			}

			if ($iRtn == 1) {

				//echo "처리일시 = [$sDateTime]<BR>";
				//echo "리턴코드 = [$iRtn]<BR>";
				$return_msg		= $sDupInfo;
				$return_flag	= "1";

			} else if ($iRtn == 3) {
				$return_msg = "사용자 정보와 실명확인 서비스 구분값 매핑 오류";
			} else if ($iRtn == -9) {
				$return_msg = "입력값 오류";
			} else if ($iRtn == -21 || $iRtn == -31 || $iRtn == -34) {
				$return_msg = "통신오류";
			} else {
				$return_msg = "iRtn 값 확인 후, 한국신용평가정보 개발 담당자에게 문의해 주세요.";
			}

		} else {
			$return_msg = "잘못된 주민등록 번호입니다.";
		}
		
		$values[0] = $return_flag;
		$values[1] = $return_msg;
		
		return $values;
	}

	function ie_version() {
		
		ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);

		if (floatval($reg[1]) == '6') {
			return true;
		} else {
			return false;
		}
	}

	function lang_search($str) {

		if (preg_match_all('!['.'\x{0030}-\x{0039}'.']+!u', $str, $match)) {
			return 'D';
		}

		if (preg_match_all('!['.'\x{0061}-\x{007a}|\x{0041}-\x{005a}'.']+!u', $str, $match)) {
			return 'E';
		}

		if (preg_match_all('!['.'\x{1100}-\x{11ff}\x{3130}-\x{318f}\x{ac00}-\x{d7af}'.']+!u', $str, $match)) {
			return 'H';
		}

		if (preg_match_all('!['.'\x{2E80}-\x{2EFF}'.'\x{31C0}-\x{31EF}\x{3200}-\x{32FF}'.'\x{3400}-\x{4DBF}\x{4E00}-\x{9FBF}\x{F900}-\x{FAFF}'.'\x{20000}-\x{2A6DF}\x{2F800}-\x{2FA1F}'.']+!u', $str, $match)) {
			return 'C';
		}
		if (preg_match_all('!['.'\x{2E80}-\x{2EFF}'.'\x{31C0}-\x{31EF}\x{3200}-\x{32FF}'.'\x{3400}-\x{4DBF}\x{4E00}-\x{9FBF}\x{F900}-\x{FAFF}'.'\x{20000}-\x{2A6DF}\x{2F800}-\x{2FA1F}'.']+!u', $str, $match)) {
			return 'J';
		}
	}

	function check_html($str) {
		
		$value = "";

		if (preg_match("/</",$str)) {
			
			$arr_str = explode("<",$str);
		
			if (sizeof($arr_str) > 0) {

				for($i = 0 ; $i < sizeof($arr_str) ; $i++) {
					
					if ($i <> 0) {
						$temp_str = "<".$arr_str[$i];
					} else {
						$temp_str = $arr_str[$i];
					}

					if ((lang_search($arr_str[$i][0]) == "E") || ($arr_str[$i][0] == "/")) {
						$value = $value.strip_tags($temp_str);
					} else {
						$value = $value.($temp_str);
					}
				}
			} else {
				$value = $str;
			}
		} else {
			$value = $str;
		}
		return $value;
	}


	//만 19세이상인지 여부 확인
	function age19_check($jumin1, $jumin2){
		$ret = true;
		
		if(strlen($jumin1)==6 && strlen($jumin2)==7){
			$toyear = Date("Y"); 
			$tomonth = Date("m"); 
			$todate = Date("d");

			 
			$ntyear = substr($jumin2,0,1);

			if ($ntyear == 1 || $ntyear == 2) {
				$bhyear = (1900 + (int)substr($jumin1,0,2));
			}else if ($ntyear == 3 || $ntyear == 4){
				$bhyear = (2000 + (int)substr($jumin1,0,2));
			}

			$bhmonth = substr($jumin1,2,2);  
			$bhdate = substr($jumin1,4,2);    
			$birthyear = $toyear - $bhyear;

			if ($birthyear < 19) { 
				#echo("미성년자는 가입하실 수 없습니다.!");
				$ret = false;
				
			} else if ($birthyear == 19) {
				if ((int)($tomonth) < (int)($bhmonth)) {
					#echo("미성년자는 입가입하실 수 없습니다.!!");  
					$ret = false;
				} else if (((int)($tomonth) == (int)($bhmonth)) && ((int)($todate) < (int)($bhdate))) {
					#echo("미성년자는 가입하실 수 없습니다.!!!");   
					$ret = false;
				}
			} 
		}else{
					$ret = false;
		}

		return $ret;
	}



	function replace_tag_parts($in_html,$arr1,$arr2){		
		$ret = $in_html;
		for($ii=0;$ii<sizeof($arr1);$ii++){
			$ret = str_replace($arr1[$ii],$arr2[$ii],$ret);
		}
		return $ret;
	}

	function clickable($url){
		$url = str_replace("\\r","\r",$url);
		$url = str_replace("\\n","\n<BR>",$url);
		$url = str_replace("\\n\\r","\n\r",$url);

		$in=array(
			'`((?:https?|ftp)://\S+[[:alnum:]]/?)`si',
			'`((?<!//)(www\.\S+[[:alnum:]]/?))`si'
		);
		$out=array(
			'<a href="$1"  rel=nofollow target="_blank">$1</a> ',
			'<a href="http://$1" rel=\'nofollow\' target=\'_blank\'>$1</a>'
		);
		return preg_replace($in,$out,$url);
	}

	function protect_xss_v2($s){
		$ret="";
		if(is_array($s)){

			$ret = $s;
		}else{
			$ret = trim($s);
			if($ret!=""){
				$ret		= str_replace("<","&lt;",$ret);
				$ret		= str_replace(">","&gt;",$ret);
				$ret		= str_replace('"','&#34;',$ret);
				$ret		= str_replace("'","&#39;",$ret);
				$ret		= str_replace("(","&#40;",$ret);
				$ret		= str_replace(")","&#41;",$ret);
				$ret		= str_replace("-","&#45;",$ret);
			}
		}
		return $ret;

	}


function Image_PageList($URL, $nPage, $TPage, $PBlock, $Ext) {

/*
							<a href="" class="btn first"><span class="hidden">처음</span></a>
							<a href="" class="btn prev"><span class="hidden">이전</span></a>
							<span>1</span>
							<a href="">2</a>
							<a href="">3</a>
							<a href="" class="btn next"><span class="hidden">다음</span></a>
							<a href="" class="btn end"><span class="hidden">끝</span></a>
*/


	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;


	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}

		# 이전 블록
		if ($intTemp == 1) {
			$str .= "<a href=\"javascript:void(0);\" class=\"btn first\"><span class=\"hidden\">이전".$PBlock."개</span></a>\n";
		} else {
			$str .= "<a href=\"".$URL."?nPage=".($intTemp - $PBlock).$Ext."\" class=\"btn first\"><span class=\"hidden\">이전".$PBlock."개</span></a>\n";
		}
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<a href=\"javascript:void(0);\" class=\"btn prev\"><span class=\"hidden\">이전</span></a>\n";
		} else {
			$str .= "<a href=\"".$URL."?nPage=".($nPage - 1).$Ext."\" class=\"btn prev\"><span class=\"hidden\">이전</span></a>\n";
		}
		
		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<span>" .$Cnt. "</span>\n";
			} else {
				$str .= "<a href=\"".$URL."?nPage=".$Cnt.$Ext."\" >" .$Cnt. "</a>\n";
			}
			$intTemp++;
		}

		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<a href=\"javascript:void(0);\" class=\"btn next\"><span class=\"hidden\">다음</span></a>\n";
		} else {
			$str .= "<a href=\"".$URL."?nPage=" .($nPage + 1).$Ext."\" class=\"btn next\"><span class=\"hidden\">다음</span></a>\n";
		}
		
		# 다음 블록
		if ($intTemp > $TPage) {
			$str .= "<a href=\"javascript:void(0);\" class=\"btn end\"><span class=\"hidden\">다음".$PBlock."개</span></a>\n";
		} else {
			$str .= "<a href=\"".$URL."?nPage=" .$intTemp.$Ext."\" class=\"btn end\"><span class=\"hidden\">다음".$PBlock."개</span></a>\n";
		}

	//echo $str;
		
	}
	return $str;
}

function ajax_PageList($nPage, $TPage, $PBlock) {

	$str = "";

	$SPage = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
	$EPage = $SPage + $PBlock - 1;


	if ($TPage > 1 ) {

		$intTemp = (int)(($nPage - 1) / $PBlock) * $PBlock + 1;
		$intLoop = 1;

		if ($TPage < $EPage) {
			$EPage = $TPage;
		}

		$str = "<ul>\n";
		
		# 이전 블록
		if ($intTemp == 1) {
			$str .= "<li><a href=\"javascript:void(0)\"><img src=\"../images/btn/btn_prev.gif\" alt=\"이전".$PBlock."개\" /></a></li>\n";
		} else {
			$str .= "<li><a href=\"javascript:js_ajax_page(".($intTemp - $PBlock).")\"><img src=\"../images/btn/btn_prev.gif\" alt=\"이전".$PBlock."개\" /></a></li>\n";
		}
		
		# 이전 페이지
		if ($nPage == 1) {
			$str .= "<li><a href=\"javascript:void(0)\"><img src=\"../images/btn/btn_prev2.gif\" alt=\"이전 페이지\" /></a></li>\n";
		} else {
			$str .= "<li><a href=\"javascript:js_ajax_page(".($nPage - 1).")\"><img src=\"../images/btn/btn_prev2.gif\" alt=\"이전 페이지\" /></a></li>\n";
		}
		
		$Cnt = 1;  # 숫자로 인식시킴 현재 페이지 볼드체 되게 수정	
		for ($Cnt = $SPage; $Cnt <= $EPage ; $Cnt++) {
			if ($Cnt == (int)($nPage)) {
				$str .= "<li><strong class=\"sel\"><a href=\"javascript:js_ajax_page(".$Cnt.")\">" .$Cnt. "</a></strong></li>\n";
			} else {
				$str .= "<li><a href=\"javascript:js_ajax_page(".$Cnt.")\">" .$Cnt. "</a></li>\n";
			}
			$intTemp++;
		}

		# 다음 페이지
		if ($nPage >= $TPage) {
			$str .= "<li><a href=\"javascript:void(0)\"><img src=\"../images/btn/btn_next.gif\" alt=\"다음 페이지\" /></a></li>\n";
		} else {
			$str .= "<li><a href=\"javascript:js_ajax_page(".($nPage + 1).")\"><img src=\"../images/btn/btn_next.gif\" alt=\"다음 페이지\" /></a></li>\n";
		}
		
		# 다음 블록
		if ($intTemp > $TPage) {
			$str .= "<li><a href=\"javascript:void(0)\"><img src=\"../images/btn/btn_next2.gif\" alt=\"다음".$PBlock."개\"></a></li>\n";
		} else {
			$str .= "<li><a href=\"javascript:js_ajax_page(".$intTemp.")\"><img src=\"../images/btn/btn_next2.gif\" alt=\"다음".$PBlock."개\" /></a></li>\n";
		}

		# 페이지
		$str .= "</ul>\n";

	//echo $str;
		
	}
	return $str;
}

function generateRandomPassword($length=8, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}


function getBrowser() {

	//shamlessly "borrowed" from the manual at http://php.net/manual/en/function.get-browser.php
	//Needed some cleanup.
	//Still needs some cleanup

	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version = "";
	$xploded = explode(';',$u_agent);

	//pretty($xploded);

	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .
           ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}

	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
			$version = $matches['version'][0];
		} else {
			$version = $matches['version'][1];
		}
	} else {
		$version = $matches['version'][0];
	}

	// check if wfunction getBrowser() {
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version = "";

	//First get the platform?
	if (preg_match('/linux/i', $u_agent)) {
		$platform = 'linux';
	} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	} elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
	}

	// Next get the name of the useragent yes seperately and for good reason
	if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	} elseif (preg_match('/Firefox/i', $u_agent)) {
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	} elseif (preg_match('/Chrome/i', $u_agent)) {
		$bname = 'Google Chrome';
		$ub = "Chrome";
	} elseif (preg_match('/Safari/i', $u_agent)) {
		$bname = 'Apple Safari';
		$ub = "Safari";
	} elseif (preg_match('/Opera/i', $u_agent)) {
		$bname = 'Opera';
		$ub = "Opera";
	} elseif (preg_match('/Netscape/i', $u_agent)) {
		$bname = 'Netscape';
		$ub = "Netscape";
	}

	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .
           ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}

	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
			$version = $matches['version'][0];
		} else {
			$version = $matches['version'][1];
		}
	} else {
		$version = $matches['version'][0];
	}
	// check if we have a number
	if ($version == null || $version == "") {
		$version = "?";
	}

	$bname = strtolower($bname);
	$windows_version = floatval(trim(str_ireplace('Windows NT','',$xploded[2]) ));

	//Sloppy hack for dealing with IE 9, specifically.
	$trial = intval($version);
	if($windows_version == 6.1 && $bname == 'internet explorer' && $trial == 7){
		//the browser is lying to you.
		//print "We are being lied to<br>";
		$version = 9.0;
	}

	//print 'windows version is......'.$windows_version.'<br>';
	//print 'browser id is......'.$bname.'<br>';
	//print 'raw version is......'.$version.'<br>';


	return array(
		'test'=>'test',
		'name' => $bname,
		'version' => intval($version)
	);
}


// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire, $domain) {
	setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/', $domain);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name) {
	return base64_decode($_COOKIE[md5($cookie_name)]);
}

function hide_str($str, $len, $char) {

	$temp_len = strlen($str) - $len;
	if ($len >= strlen($str)) {
		$return_str = $str;

	} else {
		$return_str = substr($str,0,$temp_len);
		for ($i = 0 ; $i < $len ; $i++) {
			$return_str .= $char;
		}
	}
	return $return_str;
}


/**
* @brief iframe, script코드 제거
**/
function removeJSEvent($matches) 
{
	$tag = strtolower($matches[1]);
	if(preg_match('/(src|href)=("|\'?)javascript:/i',$matches[2])) $matches[0] = preg_replace('/(src|href)=("|\'?)javascript:/i','$1=$2_javascript:', $matches[0]);
	return preg_replace('/ on([a-z]+)=/i',' _on$1=',$matches[0]);
}

function removeSrcHack($matches) 
{
	$tag = strtolower(trim($matches[1]));

	$buff = trim(preg_replace('/(\/>|>)/','/>',$matches[0]));
	$buff = preg_replace_callback('/([^=^"^ ]*)=([^ ^>]*)/i', fixQuotation, $buff);

	$oXmlParser = new XMLParser();
	$xml_doc = $oXmlParser->parse($buff);

	// src값에 module=admin이라는 값이 입력되어 있으면 이 값을 무효화 시킴
	$src = $xml_doc->{$tag}->attrs->src;
	$dynsrc = $xml_doc->{$tag}->attrs->dynsrc;
	if(_isHackedSrc($src) || _isHackedSrc($dynsrc) ) return sprintf("<%s>",$tag);

	return $matches[0];
}

function _isHackedSrc($src) {
	if(!$src) return false;
	if($src && preg_match('/javascript:/i',$src)) return true;
	if($src) 
	{
		$url_info = parse_url($src);
		$query = $url_info['query'];
		$queries = explode('&', $query);
		$cnt = count($queries);
		for($i=0;$i<$cnt;$i++) 
		{
			$pos = strpos($queries[$i],'=');
			if($pos === false) continue;
			$key = strtolower(trim(substr($queries[$i], 0, $pos)));
			$val = strtolower(trim(substr($queries[$i] ,$pos+1)));
			if(($key == 'module' && $val == 'admin') || $key == 'act' && preg_match('/admin/i',$val)) return true;
		}
	}
	return false;
}


// sql injection 처리
function quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc"
	);

	$value = trim(str_replace($banlist, '', strtolower($value)));
	/* //sql injection 처리 */

	//$value = addslashes($value);

	/* XSS 처리 */
	// iframe 제거
	$value = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $value);

	// script code 제거
	$value = preg_replace("!<script(.*?)<\/script>!is", '', $value);

	// meta 태그 제거
	$value = preg_replace("!<meta(.*?)>!is", '', $value);

	// style 태그 제거
	$value = preg_replace("!<style(.*?)<\/style>!is", '', $value);

	// XSS 사용을 위한 이벤트 제거
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

	/**
	* 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
	* - 취약점 제보 : 김상원님
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS 처리 */

	// stripslashes
	//if (get_magic_quotes_gpc()) {
	//	$value = htmlspecialchars(stripslashes($value));
	//}else{
		$value = htmlspecialchars($value);
	//}

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}

	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

	$value = str_replace("\"","&quot;", $value);
	$value = str_replace("'","&#039;", $value);
	//$value = str_replace("%","&#037;", $value);
	$value = str_replace(">","&gt;", $value);
	$value = str_replace("<","&lt;", $value);

	return $value;
}

// sql injection 처리
function upper_quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"INSERT", "SELECT", "UPDATE", "DELETE", "DISTINCT", "HAVING", "TRUNCATE", "REPLACE",
			"HANDLER", "LIKE", " AS ", "OR ", "PROCEDURE", "LIMIT", "ORDER BY", "GROUP BY", "ASC", "DESC"
	);

	$value = trim(str_replace($banlist, '', strtoupper($value)));
	/* //sql injection 처리 */

	//$value = addslashes($value);

	/* XSS 처리 */
	// iframe 제거
	$value = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $value);

	// script code 제거
	$value = preg_replace("!<script(.*?)<\/script>!is", '', $value);

	// meta 태그 제거
	$value = preg_replace("!<meta(.*?)>!is", '', $value);

	// style 태그 제거
	$value = preg_replace("!<style(.*?)<\/style>!is", '', $value);

	// XSS 사용을 위한 이벤트 제거
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

	/**
	* 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
	* - 취약점 제보 : 김상원님
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS 처리 */

	// stripslashes
	//if (get_magic_quotes_gpc()) {
	//	$value = htmlspecialchars(stripslashes($value));
	//}else{
		$value = htmlspecialchars($value);
	//}

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}

	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

	$value = str_replace("\"","&quot;", $value);
	$value = str_replace("'","&#039;", $value);
	//$value = str_replace("%","&#037;", $value);
	$value = str_replace(">","&gt;", $value);
	$value = str_replace("<","&lt;", $value);

	return $value;
}

function str_quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc", "iframe", "script", "meta", "style"
	);

	//$value = addslashes($value);
	$value = trim(str_ireplace($banlist, '', $value));
	/* //sql injection 처리 */

	/* XSS 처리 */
	// iframe 제거

	$value = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $value);

	// script code 제거
	$value = preg_replace("!<script(.*?)<\/script>!is", '', $value);

	// meta 태그 제거
	$value = preg_replace("!<meta(.*?)>!is", '', $value);

	// style 태그 제거
	$value = preg_replace("!<style(.*?)<\/style>!is", '', $value);

	// XSS 사용을 위한 이벤트 제거
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

	/**
	* 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
	* - 취약점 제보 : 김상원님
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS 처리 */

	// stripslashes
	//if (get_magic_quotes_gpc()) {
	//	$value = htmlspecialchars(stripslashes($value));
	//}else{
		$value = htmlspecialchars($value);
	//}

	//$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

	$value = str_replace("\"","&quot;", $value);
	$value = str_replace("'","&#039;", $value);
	$value = str_replace("%","&#037;", $value);
	$value = str_replace(">","&gt;", $value);
	$value = str_replace("<","&lt;", $value);

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}


	return $value;
}

function html_quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc", "iframe", "script", "meta", "style",
			"INSERT", "SELECT", "UPDATE", "DELETE", "DISTINCT", "HAVING", "TRUNCATE", "REPLACE",
			"HANDLER", "LIKE", " AS ", "OR ", "PROCEDURE", "LIMIT", "ORDER BY", "GROUP BY", "ASC", "DESC", "IFRAME", "SCRIPT", "META", "STYLE"
	);

	//$value = addslashes($value);
	$value = trim(str_ireplace($banlist, '', $value));
	/* //sql injection 처리 */

	/* XSS 처리 */
	// iframe 제거

	$value = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $value);

	// script code 제거
	$value = preg_replace("!<script(.*?)<\/script>!is", '', $value);

	// meta 태그 제거
	$value = preg_replace("!<meta(.*?)>!is", '', $value);

	// style 태그 제거
	$value = preg_replace("!<style(.*?)<\/style>!is", '', $value);

	// XSS 사용을 위한 이벤트 제거
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

	/**
	* 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
	* - 취약점 제보 : 김상원님
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS 처리 */

	// stripslashes

	//if (get_magic_quotes_gpc()) {
	//	$value = htmlspecialchars(stripslashes($value));
	//}else{
	//	$value = htmlspecialchars($value);
	//}

	//$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$value = str_replace("\"","&quot;", $value);
		$value = str_replace("'","&#039;", $value);
		$value = str_replace("%","&#037;", $value);
//	$value = str_replace(">","&gt;", $value);
//	$value = str_replace("<","&lt;", $value);

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}


	return $value;
}

	function getContentMovSrc($str){  //동영상 링크 축출

		$str = stripslashes($str); 
		$str = str_replace("allowscriptaccess","",$str);
		$pattern = "/\< *[embed][^\>]*[src] *= *[\"\']{0,1}([^\"\'\ >]*)/i"; 
		preg_match_all($pattern, $str, $match); 
		
		$ret_str = $match[1][0];
		return $ret_str; 
	}

	function getMovSrc($str) {

		$str_temp = getStringBetween(" ".$str, "<iframe", ">");
		
		if ($str_temp <> "") {
			$PosStart	= strripos($str_temp, "src=");
			$str_temp	= substr($str_temp, ($PosStart + 5), strlen($str_temp));
			$PosStart	= strripos($str_temp, "\"");
			$str_temp	= substr($str_temp, 0, $PosStart);
		}	 else {
			$str_temp = "";
		}

		return $str_temp;
	}

	function getPhoneNumber($str) {
		
		$str = str_replace("-","",$str);

		$phone = "";

		$phone01 = ""; 
		$phone02 = ""; 
		$phone03 = ""; 
		

		if (left($str,2) == "02") {

			if (strlen($str) == 9) {
				$phone01 = left($str,2);
				$phone02 = right(left($str,5),3);
				$phone03 = right($str,4);
			}

			if (strlen($str) == 10) {
				$phone01 = left($str,2);
				$phone02 = right(left($str,6),4);
				$phone03 = right($str,4);
			}

		} else {

			if (strlen($str) == 10) {
				$phone01 = left($str,3);
				$phone02 = right(left($str,6),3);
				$phone03 = right($str,4);
			}

			if (strlen($str) == 11) {
				$phone01 = left($str,3);
				$phone02 = right(left($str,7),4);
				$phone03 = right($str,4);
			}
		}
		
		$phone = $phone01."-".$phone02."-".$phone03;

		return $phone; 

	}

	function requestBookSearch($path, $query, $content_type, $book_api_key) {
		
		if ($content_type == "") $content_type = 'json';
		
		$str_param = "Authorization: KakaoAK ".$book_api_key;

		$api_server = 'https://dapi.kakao.com';
		$headers = array($str_param);
		$opts = array(
			CURLOPT_URL => $api_server . $path . '.' . $content_type . '?' . $query,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSLVERSION => 1,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $headers
		);

		$curl_session = curl_init();
		curl_setopt_array($curl_session, $opts);
		$return_data = curl_exec($curl_session);

		if (curl_errno($curl_session)) {
			throw new Exception(curl_error($curl_session));
		} else {
			//print_r(curl_getinfo($curl_session));
			curl_close($curl_session);
			return $return_data;
		}
	}

	function requestAddrSearch($query, $nPage, $nPageSize) {


		$api_server = "http://www.juso.go.kr/addrlink/addrLinkApi.do?currentPage=".$nPage."&countPerPage=".$nPageSize."&keyword=".$query."&confmKey=U01TX0FVVEgyMDIwMTAxMzE0NDY1NDExMDI4MzM=&resultType=json";
		
		$opts = array(
			CURLOPT_URL => $api_server,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSLVERSION => 1,
			CURLOPT_HEADER => false
		);

		$curl_session = curl_init();
		curl_setopt_array($curl_session, $opts);

		$return_data = curl_exec($curl_session);

		if (curl_errno($curl_session)) {
			throw new Exception(curl_error($curl_session));
		} else {
			//print_r(curl_getinfo($curl_session));
			curl_close($curl_session);
			return $return_data;
		}
	}

	// --- 이미지 업로드관련
	function file_extension($filename) {
		return end(explode(".", $filename));
	}

	// --- 날짜별 폴더 생성 함수
	function MAKEDIR($path){
	// --- 폴더가 존재하는지 여부 검사 및 생성
		$documentRoot = $_SERVER['DOCUMENT_ROOT'];
	// --- 업로될 날짜 폴더
		$today = $documentRoot . $path;

		if(is_dir($today)) {
			//echo"폴더가 이미 존재";
		} else {
			//echo "폴더가 없으므로 생성가능";
			mkdir($today, 0777);
		}
		return;
	}

	// --- 이미지 리사이즈를 위해 실제 이미지의 가로/세로 크기 확인 
	function IMAGE_SIZE($max_width, $max_height, $img_path){	
		list($width, $height) = @getimagesize($img_path); 
		$ratioh = @($max_height/$height); 
		$ratiow = @($max_width/$width); 
		$ratio = min($ratioh, $ratiow); 
		// New dimensions 
		$size["width"] =intval($ratio*$width); 
		$size["height"] = intval($ratio*$height); 
		return $size;
	}

	// --- 파일삭제
	function FILEDEL( $filename ){
		$filename = $_SERVER['DOCUMENT_ROOT'] . $filename;
		if( is_file($filename)) {
			unlink($filename);
			return TRUE;
		}else{
			return FALSE;
		}
	}

	// 이름 연락처
	function letterMasking($_type, $_data) {
		$_data = str_replace('-','',$_data);
		$strlen = mb_strlen($_data, 'utf-8');
		$maskingValue = "";

		$useHyphen = "-";

		if($_type == 'N'){
			switch($strlen){
				case 2:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'*';
					break;
				case 3:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'*'.mb_strcut($_data, 8, 11, "UTF-8");
					break;
				case 4:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'**'.mb_strcut($_data, 12, 15, "UTF-8");
					break;
				default:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'**'.mb_strcut($_data, 12, 15, "UTF-8");
					break;
				}
		}else if($_type == 'P'){
			switch($strlen){
				case 10:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}***{$useHyphen}".mb_substr($_data, 6, 4);
					break;
				case 11:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				default:
					trigger_error('Not a known format parametter in function', E_USER_NOTICE);
					break;
			}
		}else{
			trigger_error('Masking Function Parameter Error', E_USER_NOTICE);
		}
		return $maskingValue;
	}


	function string_star($str, $cut_len=3, $type='right', $star = "*") {
		$length = mb_strlen($str,'UTF-8');
		//* 변환할 길이가 문자길이보다 더 길다면 원본리턴
		if($length < $cut_len) return $str;

		if($cut_len=='harf') $cut_len = floor($length/2);

		if($type=='right'){
			$temp = substr($str, 0, $length - $cut_len);

			for($i=0;$i<$cut_len;$i++){
				$temp.= $star;
			}

			$ref = $temp;
		} else if ($type=='left'){
			$temp = substr($str, $cut_len, $length - $cut_len);
			$temp2 = "";

			for($i=0;$i<$cut_len;$i++){
				$temp2.= $star;
			}

			$ref = $temp2.$temp;
		} else if ($type=='mid'){
			$harf = floor($length/2);//내림 (가운데점을 잡을수 없으면 앞쪽으로 한칸이동)
			$harf_cut_len_1 = floor($cut_len/2);//내림
			$harf_cut_len_2 = ceil($cut_len/2);//올림
	
			$temp2 = "";
	
			$temp_head = substr($str, 0, $harf-$harf_cut_len_1);
			$temp_foot = substr($str, $harf+$harf_cut_len_2, $harf);
	
			for($i=0;$i<$cut_len;$i++){
				$temp2.= $star;
			}
			$ref = $temp_head.$temp2.$temp_foot;
		}
		return $ref;
	}


	function makeDispSeqSelect($objname, $b_no, $selseq) {
		
		if ($selseq == "") $selseq = 1;
		$tmp_str = "";

		$tmp_str = "<select name='".$objname."' onchange=\"js_".$objname."('".$b_no."', this.value);\">";
		$tmp_str .= "<option value='0'>선택</option>";

		for ($i=1 ; $i < 11 ; $i++) {
			if ($selseq == $i) { 
				$tmp_str .= "<option value='".$i."' selected>".$i."</option>";
			} else { 
				$tmp_str .= "<option value='".$i."'>".$i."</option>";
			}
		}

		$tmp_str .= "</select>";

		return $tmp_str;
	}

	function removeSeoStr($str) {
		
		$str = strip_tags($str);
		$str = str_replace("'","",$str);
		$str = str_replace("\"","",$str);

		return $str;
	}

?>
