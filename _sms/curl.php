<?
	$oCurl = curl_init();
	$url =  "https://sslsms.cafe24.com/smsSenderPhone.php";
	$aPostData['userId'] = "wfiwfisms"; // SMS 아이디
	$aPostData['passwd'] = "b0737bbf00b15fe5e968db4e7db92694"; // 인증키
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPostData);
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
	$ret = curl_exec($oCurl);
	echo $ret;
	curl_close($oCurl);
?>